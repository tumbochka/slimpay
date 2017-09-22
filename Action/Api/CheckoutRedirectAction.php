<?php

namespace Payum\Slimpay\Action\Api;

use HapiClient\Hal\Resource;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\CheckoutRedirect;

class CheckoutRedirectAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param CheckoutRedirect $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['order', 'checkout_mode']);
        if (!$model['order'] instanceof Resource) {
            throw new LogicException('Order should be an instance of Resource');
        }
        if (Constants::CHECKOUT_MODE_REDIRECT != $model['checkout_mode']) {
            throw new LogicException(sprintf('Redirect is not available for mode %s', $model['checkout_mode']));
        }

        throw new HttpRedirect($this->api->getCheckoutRedirect($model['order']));
    }
    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof CheckoutRedirect &&
            $request->getModel() instanceof \ArrayAccess;
    }
}