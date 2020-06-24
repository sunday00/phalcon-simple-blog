<?php

use App\Routes\AdminRoutes;

$router = $di->getRouter();

$router->add('/user/sign', 'User::doSign')->via(['POST'])->setName('userLogin');

// $router->add()...;;

$router->mount(new AdminRoutes());
// or route group mount. route groups are in app/routes

$router->handle($_SERVER['REQUEST_URI']);
$router->removeExtraSlashes(true);