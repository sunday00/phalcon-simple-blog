<?php

namespace App\Plugins;

class Middleware
{
    public function __construct($di)
    {
        $this->request = $di->getShared('request');
        $this->security = $di->getShared('security');
        $this->flashSession = $di->getShared('flashSession');
        $this->response = $di->getShared('response');


    }
}