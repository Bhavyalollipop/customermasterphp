<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
require APP_PATH . "/../vendor/autoload.php"; 
$dotenv = new Dotenv\Dotenv(BASE_PATH);
$dotenv->load();
return new \Phalcon\Config([
    'database' => [
        'adapter'    => 'Mysql',
        'host'        => getenv('DBHOST'),
        'username'    => getenv('DBUSER'),
        'password'    => getenv('DBPWD'),
        'dbname'      => getenv('DBNAME'),
        'charset'    => 'utf8',
    ],

    'application' => [
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'libraryDir'       => APP_PATH . '/library/',
        'middlewareDir'       => APP_PATH . '/middleware/',
        'viewsDir'       => APP_PATH . '/views/',
        'controllersDir'       => APP_PATH . '/controllers/',
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],
    'authentication' => [
        'secret' => '??PHP@123', // This will sign the token. (still insecure)
        'expiration_time' => 86400 * 7, // One week till token expires
        'iss' => 'localhost', // Token issuer eg. www.myproject.com
        'aud' => 'localhost', // Token audience eg. www.myproject.com
    ],
]);
