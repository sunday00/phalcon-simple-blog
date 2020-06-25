<?php

namespace App\Routes;

use Phalcon\Mvc\Router\Group;
use Phalcon\Mvc\Router\RouteInterface;

class UserRoutes extends Group
{
    public function initialize()
    {
        $this->addPost('/user/sign', [
            'controller' => 'user',
            'action' => 'doSign',
        ])->setName('userLogin');

        $this->addPost('/user/logout', [
            'controller' => 'user',
            'action' => 'signOut',
        ])->setName('userLogout');;

        $this->addPost('/api/v1/user/signIn', [
            'controller' => 'user',
            'action' => 'doApiSign',
        ]);
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