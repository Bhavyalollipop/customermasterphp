<?php
header('Access-Control-Allow-Origin: http://localhost:3002');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token,Authorization, x-requested-with');
header('Access-Control-Allow-Credentials: true');


use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;

error_reporting(0);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include Services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';
    /**
     Create a events manager
    */
    $eventsManager = new EventsManager();
    $eventsManager->attach('micro:beforeHandleRoute', $di->get('cors'));

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);
    /**
     *  check the cors origin policy
    */
    $app->setEventsManager($eventsManager);
     /*
    check for success response
    */
    $app->options('/{catch:(.*)}', function() use ($app) { 
        $app->response->setStatusCode(200, "OK")->send();
    });
    
    /**
     * Include Application
     */
    include APP_PATH . '/app.php';

    /**
     * Handle the request
     */
   
    $app->handle('/'.$_SERVER['REQUEST_URI']);
} catch (\Exception $e) {
      echo $e->getMessage() . '<br>';
      echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
