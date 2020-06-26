<?php

namespace App\Routes;

use Phalcon\Mvc\Router\Group;
use Phalcon\Mvc\Router\RouteInterface;

class FileRoutes extends Group
{
    public function initialize()
    {
        $this->add('/image/upload/file', [
            'controller' => 'file',
            'action' => 'uploadByFile',
        ])->setName('image-file');;
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