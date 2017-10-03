<?php
namespace Payum\Slimpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\GetPaymentHumanStatus;
use Payum\Slimpay\Request\Api\SyncPayment;

class PaymentStatusAction implements ActionInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param GetPaymentHumanStatus $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if($model['payment']) {
            $this->gateway->execute(new SyncPayment($model));
            switch ($model['payment']['executionStatus']) {
                case Constants::PAYMENT_STATUS_PROCESSING:
                    $request->markPending();
                    break;
                case Constants::PAYMENT_STATUS_PROCESSED:
                    $request->markCaptured();
                    break;
                case Constants::PAYMENT_STATUS_REJECTED:
                    $request->markFailed();
                    break;
                default:
                    $request->markUnknown();
            }
        } else {
            $request->markNew();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetPaymentHumanStatus &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
