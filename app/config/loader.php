<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
    ]
);

$loader->registerFiles([
    BASE_PATH . "/vendor/autoload.php",
    APP_PATH . "/config/acl.php",
]);

$loader->registerNamespaces([
    'App\Routes'        => '/app/routes/',
    'App\Controllers'   => '/app/controllers/',
    'App\Models'        => '/app/models/',
    'App\Services'      => '/app/services/',
    'App\Plugins'       => '/app/plugins/',
]);

$loader->register();