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
     * @var string
     */
    private $view;

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

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }
}