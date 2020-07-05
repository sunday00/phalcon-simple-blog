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
    'App\Routes'        => APP_PATH.'/routes/',
    'App\Controllers'   => APP_PATH.'/controllers/',
    'App\Models'        => APP_PATH.'/models/',
    'App\Services'      => APP_PATH.'/services/',
    'App\Plugins'       => APP_PATH.'/plugins/',
]);

$loader->register();

