<?php

namespace Payum\Slimpay\Action\Api;

use HapiClient\Exception\HttpException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Request\Api\SyncOrder;
use Payum\Slimpay\Util\ResourceSerializer;

class SyncOrderAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param SyncOrder $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['order']);

        $order = ResourceSerializer::unserializeResource($model['order']);

        try {
            $model['order'] = ResourceSerializer::serializeResource(
                $this->api->getOrder($order->getState()['id'])
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
            $request instanceof SyncOrder &&
            $request->getModel() instanceof \ArrayAccess
            ;
    }
}