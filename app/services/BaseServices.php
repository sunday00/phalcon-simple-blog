<?php

namespace App\Services;

use http\Env\Response;
use Phalcon\Di\FactoryDefault;

class BaseServices
{
    public FactoryDefault $di;
    protected $request;
    protected $response;
    protected $security;
    protected $session;
    protected $flashSession;
    protected $url;


    public function __construct(FactoryDefault $di)
    {
        $this->di = $di;
        $this->request      = $this->di->getShared('request');
        $this->response     = $this->di->getShared('response');
        $this->security     = $this->di->getShared('security');
        $this->session      = $this->di->getShared('session');
        $this->flashSession = $this->di->getShared('flashSession');
        $this->url          = $this->di->getShared('url');
    }
}