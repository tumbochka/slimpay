<?php
namespace Payum\Slimpay;

use Payum\Slimpay\Action\Api\CheckoutIframeAction;
use Payum\Slimpay\Action\Api\CheckoutRedirectAction;
use Payum\Slimpay\Action\Api\PaymentAction;
use Payum\Slimpay\Action\Api\SetUpCardAliasAction;
use Payum\Slimpay\Action\Api\SignMandateAction;
use Payum\Slimpay\Action\Api\SyncOrderAction;
use Payum\Slimpay\Action\Api\SyncPaymentAction;
use Payum\Slimpay\Action\Api\UpdatePaymentMethodWithCheckoutAction;
use Payum\Slimpay\Action\Api\UpdatePaymentMethodWithIbanAction;
use Payum\Slimpay\Action\AuthorizeAction;
use Payum\Slimpay\Action\CancelAction;
use Payum\Slimpay\Action\ConvertPaymentAction;
use Payum\Slimpay\Action\CaptureAction;
use Payum\Slimpay\Action\NotifyAction;
use Payum\Slimpay\Action\OrderStatusAction;
use \Payum\Slimpay\Action\Api\RefundAction as ApiRefundAction;
use Payum\Slimpay\Action\PaymentStatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Payum\Slimpay\Action\SyncAction;
use Payum\Slimpay\Action\Api\NotifyAction as ApiNotifyAction;

class SlimpayGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.template.authorize' => '@PayumSlimpay/Action/capture.html.twig'
        ]);
        $config->defaults([
            'payum.factory_name' => 'slimpay',
            'payum.factory_title' => 'slimpay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction($config['payum.template.authorize']),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new PaymentStatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.order_status' => new OrderStatusAction(),
            'payum.action.payment_status' => new PaymentStatusAction(),
            'payum.action.sync' => new SyncAction(),

            'payum.action.api.checkout_iframe' => new CheckoutIframeAction(),
            'payum.action.api.checkout_redirect' => new CheckoutRedirectAction(),
            'payum.action.api.notify' => new ApiNotifyAction(),
            'payum.action.api.payment' => new PaymentAction(),
            'payum.action.api.refund' => new ApiRefundAction(),
            'payum.action.api.set_up_card_alias' => new SetUpCardAliasAction(),
            'payum.action.api.sign_mandate' => new SignMandateAction(),
            'payum.action.api.sync_order' => new SyncOrderAction(),
            'payum.action.api.sync_payment' => new SyncPaymentAction(),
            'payum.action.api.update_payment_method_with_checkout' => new UpdatePaymentMethodWithCheckoutAction(),
            'payum.action.api.update_payment_method_with_iban' => new UpdatePaymentMethodWithIbanAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'app_id' => '',
                'app_secret' => '',
                'creditor_reference' => '',
                'return_url' => '',
                'notify_url' => '',
                'sandbox' => true,
                'default_checkout_mode' => null
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $slimpayConfig = [
                    'app_id' => $config['app_id'],
                    'app_secret' => $config['app_secret'],
                    'creditor_reference' => $config['creditor_reference'],
                    'return_url' => $config['return_url'],
                    'notify_url' => $config['notify_url'],
                    'sandbox' => $config['sandbox'],
                    'checkout_mode' => $config['checkout_mode']
                    ];

                return new Api($slimpayConfig, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
