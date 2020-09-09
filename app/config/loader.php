<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces([
    'Acc\Models'      => $config->application->modelsDir,
    'Acc\Controllers' => $config->application->controllersDir,
    'Acc'             => $config->application->libraryDir,
    'Acc\Middleware' => 		$config->application->middlewareDir
]);
$loader->register();
