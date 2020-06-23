<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->routesDir,
        $config->application->controllersDir,
        $config->application->modelsDir,
    ]
);

$loader->registerFiles([
    BASE_PATH . "/vendor/autoload.php",
    APP_PATH . "/config/acl.php",
]);

$loader->registerNamespaces([
    'App\Controllers'   => '/app/controllers/',
    'App\Models'        => '/app/models/',
    'App\Plugins'       => '/app/plugins/',
]);

$loader->register();