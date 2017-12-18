<?php

namespace Payum\Slimpay\Action\Api;


use HapiClient\Exception\HttpException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\CheckoutIframe;
use Payum\Slimpay\Request\Api\CheckoutRedirect;
use Payum\Slimpay\Request\Api\UpdatePaymentMethodWithCheckout;
use Payum\Slimpay\Util\ResourceSerializer;

class UpdatePaymentMethodWithCheckoutAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param UpdatePaymentMethodWithCheckout $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['subscriber_reference', 'mandate_reference']);

        try {
            $model['order'] = ResourceSerializer::serializeResource($this->api->updatePaymentMethodWithCheckout(
                $model['subscriber_reference'],
                $model['mandate_reference']
            ));

            if(null === $model['checkout_mode']) {
                $model['checkout_mode'] = $this->api->getDefaultCheckoutMode();
            }

            $model->validateNotEmpty(['checkout_mode']);

            if(Constants::CHECKOUT_MODE_REDIRECT == $model['checkout_mode']) {
                $this->gateway->execute(new CheckoutRedirect($model));
            } elseif (in_array(
                $model['checkout_mode'],
                [Constants::CHECKOUT_MODE_IFRAME_EMBADDED, Constants::CHECKOUT_MODE_IFRAME_POPIN]
            )) {
                $this->gateway->execute(new CheckoutIframe($model));
            }
        } catch (HttpException $e) {
            $this->populateDetailsWithError($model, $e, $request);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof UpdatePaymentMethodWithCheckout &&
            $request->getModel() instanceof \ArrayAccess;
    }
}