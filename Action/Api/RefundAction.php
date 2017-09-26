<?php

namespace Payum\Slimpay\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\Refund;
use Payum\Slimpay\Util\ResourceSerializer;

class RefundAction extends BaseApiAwareAction
{

    /**
     * {@inheritDoc}
     *
     * @param Refund $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['amount', 'currency', 'scheme', 'mandate_reference']);

        if (Constants::PAYMENT_SCHEMA_SEPA_CREDIT_TRANSFER != $model['scheme']) {
            throw new LogicException(sprintf(
                'Only %s payment scheme is supported',
                Constants::PAYMENT_SCHEMA_SEPA_CREDIT_TRANSFER
            ));
        }

        $model['payment'] = ResourceSerializer::serializeResource(
            $this->api->refundPayment($model['scheme'], $model['mandate_reference'], [
                'reference' => $model['reference'],
                'amount' => $model['amount'],
                'currency' => 'EUR',
                'scheme' => $model['scheme'],
                'label' => $model['label'],
                'executionDate' => $model['execution_date']
            ])
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Refund &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}