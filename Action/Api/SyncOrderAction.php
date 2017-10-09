<?php

namespace Payum\Slimpay\Action\Api;

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

        $model['order'] = ResourceSerializer::serializeResource(
            $this->api->getOrder($model['order']->getState()['id'])
        );
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