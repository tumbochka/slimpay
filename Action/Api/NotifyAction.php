<?php

namespace Payum\Slimpay\Action\Api;


use HapiClient\Hal\Resource;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\Notify;
use Payum\Slimpay\Util\ResourceSerializer;

class NotifyAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param Notify $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['order']);

        $order = ResourceSerializer::unserializeResource($model['order']);

        $model['state'] = $order->getState()['state'];
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayAccess
            ;
    }
}