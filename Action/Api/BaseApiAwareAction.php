<?php
namespace Payum\Slimpay\Action\Api;

use HapiClient\Exception\HttpException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Slimpay\Api;

abstract class BaseApiAwareAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait;
    use ApiAwareTrait;

    /**
     * @var Api
     */
    protected $api;

    public function __construct()
    {
        $this->apiClass = Api::class;
    }

    /**
     * @param \ArrayAccess     $details
     * @param HttpException $e
     * @param object           $request
     */
    protected function populateDetailsWithError(\ArrayAccess $details, HttpException $e, $request)
    {
        $details['error_request'] = get_class($request);
        $details['error_file'] = $e->getFile();
        $details['error_line'] = $e->getLine();
        $details['error_code'] = (int) $e->getCode();
        $details['error_message'] = $e->getMessage();
        $details['error_reason_phrase'] = $e->getReasonPhrase();
        $details['error_response_body'] = $e->getResponseBody();
        $details['error_response_code'] = $e->getStatusCode();
    }
}
