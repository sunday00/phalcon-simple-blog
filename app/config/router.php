<?php

$router = $di->getRouter();

$router->add('/user/sign', 'User::doSign')->via(['POST'])->setName('userLogin');

// $router->add()...;;

//$router->mount(new Tutorial1Routes());
// or route group mount. route groups are in app/routes

$router->handle($_SERVER['REQUEST_URI']);
$router->removeExtraSlashes(true);