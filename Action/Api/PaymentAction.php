<?php

namespace Payum\Slimpay\Action\Api;


use HapiClient\Exception\HttpException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Request\Api\Payment;
use Payum\Slimpay\Util\ResourceSerializer;

class PaymentAction  extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param Payment $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['amount', 'currency', 'payment_scheme', 'payment_reference']);

        try {
            $model['payment'] = ResourceSerializer::serializeResource(
                $this->api->createPayment($model['payment_scheme'], $model['payment_reference'], [
                    'reference' => $model['reference'],
                    'amount' => $model['amount'],
                    'currency' =>  $model['currency'],
                    'scheme' => $model['payment_scheme'],
                    'label' => $model['label'],
                    'executionDate' => $model['execution_date']
                ])
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
            $request instanceof Payment &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}