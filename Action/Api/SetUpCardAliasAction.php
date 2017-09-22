<?php

namespace Payum\Slimpay\Action\Api;


use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\CheckoutIframe;
use Payum\Slimpay\Request\Api\CheckoutRedirect;
use Payum\Slimpay\Request\Api\SetUpCardAlias;

class SetUpCardAliasAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param SetUpCardAlias $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (!in_array($model['payment_schema'], Constants::getSupportedPaymentShemas())) {
            throw new LogicException('Payment Schema not set or not supported');
        }

        if (Constants::PAYMENT_SCHEMA_CARD != $model['payment_schema']) {
            throw new LogicException('Setting up card alias is available for Card schema only');
        }

        $model->validateNotEmpty(['subscriber_reference']);

        $model['order'] = $this->api->setUpCardAlias($model['subscriber_reference']);

        if(Constants::CHECKOUT_MODE_REDIRECT == $model['checkout_mode']) {
            $this->gateway->execute(new CheckoutRedirect($model));
        } elseif (in_array(
            $model['checkout_mode'],
            [Constants::CHECKOUT_MODE_IFRAME_EMBADDED, Constants::CHECKOUT_MODE_IFRAME_POPIN]
        )) {
            $this->gateway->execute(new CheckoutIframe($model));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof SetUpCardAlias &&
            $request->getModel() instanceof \ArrayAccess;
    }
}