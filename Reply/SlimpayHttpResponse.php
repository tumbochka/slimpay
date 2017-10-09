<?php

namespace Payum\Slimpay\Reply;

use HapiClient\Hal\Resource;
use Payum\Core\Reply\HttpResponse;

class SlimpayHttpResponse extends HttpResponse
{
    /**
     * @var Resource
     */
    private $order;

    /**
     * @return Resource
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Resource $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
}