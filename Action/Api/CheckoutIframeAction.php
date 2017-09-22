<?php

namespace Payum\Slimpay\Action\Api;

use HapiClient\Hal\Resource;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\RenderTemplate;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\CheckoutIframe;

class CheckoutIframeAction extends BaseApiAwareAction
{
    /**
     * @var string
     */
    protected $templateName;

    /**
     * @param string|null $templateName
     */
    public function __construct($templateName)
    {
        $this->templateName = $templateName;
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     *
     * @param CheckoutIframe $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['order', 'checkout_mode']);
        if (!$model['order'] instanceof Resource) {
            throw new LogicException('Order should be an instance of Resource');
        }
        if (!in_array(
            $model['checkout_mode'],
            [Constants::CHECKOUT_MODE_IFRAME_EMBADDED, Constants::CHECKOUT_MODE_IFRAME_POPIN]
        )) {
            throw new LogicException(sprintf('Iframe is not available for mode %s', $model['checkout_mode']));
        }

        $iframe = $this->api->getCheckoutIframe($model['order'], $model['checkout_mode']);

        $renderTemplate = new RenderTemplate($this->templateName, array(
            'snippet' => $iframe,
        ));
        $this->gateway->execute($renderTemplate);

        throw new HttpResponse($renderTemplate->getResult());
    }
    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof CheckoutIframe &&
            $request->getModel() instanceof \ArrayAccess;
    }

}