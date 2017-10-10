<?php

namespace Payum\Slimpay\Action\Api;


use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Request\Api\GetOrderMandate;
use Payum\Slimpay\Util\ResourceSerializer;

class GetOrderMandateAction  extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param GetOrderMandate $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['order']);

        $model['mandate'] = ResourceSerializer::serializeResource(
            $this->api->getOrderMandate(ResourceSerializer::unserializeResource($model['order']))
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetOrderMandate &&
            $request->getModel() instanceof \ArrayAccess
            ;
    }
}