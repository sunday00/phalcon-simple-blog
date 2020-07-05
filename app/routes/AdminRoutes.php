<?php

namespace App\Routes;

use Phalcon\Mvc\Router\Group;
use Phalcon\Mvc\Router\RouteInterface;

class AdminRoutes extends Group
{
    public function initialize()
    {
//        $this->add('/user/loginGet', 'User::loginProcessGet')->via(['GET']);

        $this->add('/shielded/dashboard/:params', [
            'controller' => 'admin',
            'action' => 'dashboard',
            'params' => 1
        ])->setName('admin');

        $this->addGet('/api/v1/info', [
            'controller' => 'index',
            'action' => 'info',
        ])->setName('info');

        $this->addGet('/api/v1/getTheme', [
            'controller' => 'admin',
            'action' => 'getTheme',
        ])->setName('getTheme');
        
        $this->addGet('/about', [
            'controller' => 'index',
            'action' => 'about',
        ])->setName('about');

//        $this->add('/aka/news/{str1}/{str2}', [
//            'controller' => 'post',
//            'action' => 'news'
//        ]);

    }

    /**
     * @inheritDoc
     */
    public function addConnect(string $pattern, $paths = null): RouteInterface
    {
        // TODO: Implement addConnect() method.
    }

    /**
     * @inheritDoc
     */
    public function addPurge(string $pattern, $paths = null): RouteInterface
    {
        // TODO: Implement addPurge() method.
    }

    /**
     * @inheritDoc
     */
    public function addTrace(string $pattern, $paths = null): RouteInterface
    {
        // TODO: Implement addTrace() method.
    }
}
