<?php
namespace Payum\Slimpay;

use Payum\Slimpay\Action\AuthorizeAction;
use Payum\Slimpay\Action\CancelAction;
use Payum\Slimpay\Action\ConvertPaymentAction;
use Payum\Slimpay\Action\CaptureAction;
use Payum\Slimpay\Action\NotifyAction;
use Payum\Slimpay\Action\RefundAction;
use Payum\Slimpay\Action\PaymentStatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class SlimpayGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'slimpay',
            'payum.factory_title' => 'slimpay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new PaymentStatusAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'app_id' => '',
                'app_secret' => '',
                'creditor_reference' => '',
                'return_url' => '',
                'notify_url' => '',
                'sandbox' => true,
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $slimpayConfig = new Config();

                $slimpayConfig->appId = $config['app_id'];
                $slimpayConfig->appSecret = $config['app_secret'];
                $slimpayConfig->creditorReference = $config['creditor_reference'];
                $slimpayConfig->returnUrl = $config['return_url'];
                $slimpayConfig->notifyUrl = $config['notify_url'];
                $slimpayConfig->baseUri = $config['sandbox'] ?
                    Constants::BASE_URI_SANDBOX :
                    Constants::BASE_URI_PROD
                ;

                return $slimpayConfig;
            };
        }
    }
}
