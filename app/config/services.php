<?php
declare(strict_types=1);

use Phalcon\Mvc\Dispatcher;

use Phalcon\Escaper;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Model\MetaData\Redis as MetaDataAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

use Phalcon\Session\Manager as SessionManager;
use Phalcon\Session\Adapter\Redis;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Url as UrlResolver;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    if ($config->mode == 'dev') {
        array_map('unlink', array_filter( (array) glob(BASE_PATH."/cache/*.php")));
    }

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'path' => $config->application->cacheDir,
                'separator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
//    return new MetaDataAdapter();
    $config = $this->getConfig();
    return new MetaDataAdapter( new \Phalcon\Cache\AdapterFactory, $config->redis->toArray() );
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    $escaper = new Escaper();
    $flash = new Flash($escaper);
    $flash->setImplicitFlush(false);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger msg',
        'success' => 'alert alert-success msg',
        'notice'  => 'alert alert-info msg',
        'warning' => 'alert alert-warning msg'
    ]);

    return $flash;
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flashSession', function () {
    $config = $this->getConfig();
    $escaper = new Escaper();
    $session = new SessionManager();

    $factory = new AdapterFactory( new SerializerFactory );
    $redis = new Redis($factory, $config->redis->toArray());

    $session->setAdapter($redis)->start();

    $session->setAdapter($redis);
    $flash   = new FlashSession($escaper, $session);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger msg',
        'success' => 'alert alert-success msg',
        'notice'  => 'alert alert-info msg',
        'warning' => 'alert alert-warning msg'
    ]);

    return $flash;
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $config = $this->getConfig();
    $session = new SessionManager();
    $factory = new AdapterFactory( new SerializerFactory );
    $redis = new Redis($factory, $config->redis->toArray());

    $session->setAdapter($redis)->start();

    return $session;
});

/**
 * Overwrite custom dispatcher
 */
$di->set(
    'dispatcher',
    function () use ($di) {
        $dispatcher = new Dispatcher();

        // namespace setting
        $dispatcher->setDefaultNamespace('App\Controllers');

        // register Acl
        $eventsManager = $di->getShared('eventsManager');
        $eventsManager->attach('dispatch:beforeDispatch', new App\Plugins\Acl($di));
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    }
);