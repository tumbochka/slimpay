<?php
namespace Payum\Slimpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Authorize;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Slimpay\Constants;
use Payum\Slimpay\Request\Api\SetUpCardAlias;
use Payum\Slimpay\Request\Api\SignMandate;

class AuthorizeAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @var string
     */
    protected $templateName;

    /**
     * @param string|null $templateName
     */
    public function __construct($templateName)
    {
        $this->templateName = $templateName;
    }


    /**
     * {@inheritDoc}
     *
     * @param Authorize $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validateNotEmpty(['payment_schema']);

        $model['authorize_template'] = $this->templateName;

        if(Constants::PAYMENT_SCHEMA_CARD == $model['payment_schema']) {
            $this->gateway->execute(new SetUpCardAlias($model));
        } else {
            $this->gateway->execute(new SignMandate($model));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Authorize &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
