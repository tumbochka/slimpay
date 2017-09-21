<?php

namespace Payum\Slimpay\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\SignMandate;

class SignMandateAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param SignMandate $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if(empty($model['payment_schema']) ||
            !in_array($model['payment_schema'], Constants::getSupportedPaymentShemas())) {
            throw new LogicException('Payment Schema not set or not supported');
        }


    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return $request instanceof SignMandate;
    }
}