<?php

namespace App\Routes;

use Phalcon\Mvc\Router\Group;
use Phalcon\Mvc\Router\RouteInterface;

class PostRoutes extends Group
{
    public function initialize()
    {
        $this->addGet('/post/create', [
            'controller'    => 'post',
            'action'        => 'create',
        ])->setName('post-create');

        $this->addPost('/api/v1/post/store', [
            'controller'    => 'post',
            'action'        => 'store',
        ])->setName('post-store');

        $this->addGet('/api/v1/post/read/{id}', [
            'controller'    => 'post',
            'action'        => 'readData',
        ])->setName('post-read');
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