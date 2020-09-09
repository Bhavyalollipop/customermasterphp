<?php
declare(strict_types=1);
use Firebase\JWT\JWT as JWT;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Url as UrlResolver;
use Acc\Middleware\CORSMiddleware;
use Acc\Auth\Auth;
use Acc\Auth\Exception as AuthException;
/*set cors origin check */
$di->set('cors', function () {
    $CORSMiddleware = new CORSMiddleware();
    return $CORSMiddleware;
});
/**
 * tokenConfig
 */
$di->setShared('tokenConfig', function ()  {
    $config = $this->getConfig();
    $tokenConfig = $config->authentication->toArray();
    return $tokenConfig;
});
/**
 * JWT service
 */
$di->setShared('jwt', function () {
    $jwt = new JWT();
    $jwt::$leeway = 5;
    return $jwt;
});
// Start the session the first time when some component request the session service
$di->setShared(
    'session',
    function () {
        $session = new Phalcon\Session\Manager();
        $files = new Phalcon\Session\Adapter\Stream();
        $session->setAdapter($files)->start();
        return $session;
    }
);
/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * Sets the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/*
* Set auth file for verifying the login and registration user
*/
$di->set('auth', function () {
    return new Auth();
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

    $connection = new $class($params);

    return $connection;
});
