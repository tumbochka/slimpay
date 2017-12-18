<?php

namespace Payum\Slimpay\Action\Api;

use HapiClient\Exception\HttpException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Request\Api\SyncPayment;
use Payum\Slimpay\Util\ResourceSerializer;

class SyncPaymentAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param SyncPayment $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['payment']);

        $payment = ResourceSerializer::unserializeResource($model['payment']);

        try {
            $model['payment'] = ResourceSerializer::serializeResource(
                $this->api->getPayment($payment->getState()['id'])
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
        return
            $request instanceof SyncPayment &&
            $request->getModel() instanceof \ArrayAccess
            ;
    }
}