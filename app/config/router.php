<?php

use App\Routes\AdminRoutes;
use App\Routes\UserRoutes;
use App\Routes\PostRoutes;
use App\Routes\FileRoutes;

$router = $di->getRouter();

//$router->add('/user/sign', 'User::doSign')->via(['POST'])->setName('userLogin');

// $router->add()...;;

$router->mount(new AdminRoutes());
$router->mount(new UserRoutes());
$router->mount(new PostRoutes());
$router->mount(new FileRoutes());
// or route group mount. route groups are in app/routes

$router->handle($_SERVER['REQUEST_URI']);
$router->removeExtraSlashes(true);