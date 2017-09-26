<?php

namespace Payum\Slimpay;


class Constants
{
    const PAYMENT_SCHEMA_SEPA_DIRECT_DEBIT_CORE = 'SEPA.DIRECT_DEBIT.CORE';
    const PAYMENT_SCHEMA_SEPA_DIRECT_DEBIT_B2B = 'SEPA.DIRECT_DEBIT.B2B';
    const PAYMENT_SCHEMA_BACS_DIRECT_DEBIT = 'BACS.DIRECT_DEBIT';
    const PAYMENT_SCHEMA_SEPA_CREDIT_TRANSFER = 'SEPA.CREDIT_TRANSFER';
    const PAYMENT_SCHEMA_CARD = 'CARD';

    const PAYMENT_STATUS_TO_PROCESS = 'toprocess';
    const PAYMENT_STATUS_PROCESSING = 'processing';
    const PAYMENT_STATUS_NOT_PROCESSED = 'notprocessed';
    const PAYMENT_STATUS_TO_REPLAY = 'toreplay';
    const PAYMENT_STATUS_PROCESSED = 'processed';
    const PAYMENT_STATUS_REJECTED = 'rejected';

    const ORDER_STATE_ABORT = 'closed.aborted';
    const ORDER_STATE_COMPLETE = 'closed.completed';
    const ORDER_STATE_RUNNING = 'open.running';

    const BASE_URI_SANDBOX = 'https://api.preprod.slimpay.com';
    const BASE_URI_PROD = 'https://api.slimpay.net';

    const FOLLOW_CREATE_ORDERS = 'create-orders';
    const FOLLOW_CREATE_PAYINS = 'create-payins';
    const FOLLOW_CREATE_PAYOUTS = 'create-payouts';
    const FOLLOW_USER_APPROVAL = 'user-approval';
    const FOLLOW_EXTENDED_USER_APPROVAL = 'extended-user-approval';
    const FOLLOW_GET_MANDATES = 'get-mandates';
    const FOLLOW_GET_MANDATE = 'get-mandates';
    const FOLLOW_UPDATE_BANK_ACCOUNT = 'update-bank-account';

    const ITEM_TYPE_SIGN_MANDATE = 'signMandate';
    const ITEM_TYPE_MANDATE = 'mandate';
    const ITEM_TYPE_CARD_ALIAS = 'cardAlias';
    const ITEM_TYPE_PAYMENT = 'payment';

    const ITEM_ACTION_SIGN = 'sign';
    const ITEM_ACTION_AMEND_BANK_ACCOUNT = 'amendBankAccount';
    const ITEM_ACTION_CREATE = 'create';

    const CHECKOUT_MODE_REDIRECT = 'redirect';
    const CHECKOUT_MODE_IFRAME_POPIN = 'iframepopin';
    const CHECKOUT_MODE_IFRAME_EMBADDED = 'iframeembedded';

    /**
     * @return array
     */
    public static function getSupportedPaymentShemas()
    {
        return [
            self::PAYMENT_SCHEMA_CARD,
            self::PAYMENT_SCHEMA_BACS_DIRECT_DEBIT,
            self::PAYMENT_SCHEMA_SEPA_DIRECT_DEBIT_B2B,
            self::PAYMENT_SCHEMA_SEPA_DIRECT_DEBIT_CORE
        ];
    }
}