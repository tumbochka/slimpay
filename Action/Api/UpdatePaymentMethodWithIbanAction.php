<?php

namespace Payum\Slimpay\Action\Api;

use HapiClient\Exception\HttpException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Request\Api\UpdatePaymentMethodWithIban;
use Payum\Slimpay\Util\ResourceSerializer;

class UpdatePaymentMethodWithIbanAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param UpdatePaymentMethodWithIban $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['iban', 'mandate_reference']);

        try {
            $model['bank_account'] = ResourceSerializer::serializeResource(
                $this->api->updatePaymentMethodWithIban($model['mandate_reference'], $model['iban'])
            );
        } catch (HttpException $e) {
            $this->populateDetailsWithError($model, $e, $request);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof UpdatePaymentMethodWithIban &&
            $request->getModel() instanceof \ArrayAccess;
    }
}