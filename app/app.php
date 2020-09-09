<?php
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
/**
 * session
 */
$users = new MicroCollection();
$users->setHandler('Acc\Controllers\SessionController', true);
$users->setPrefix('/session'); 
$users->post('/register', 'register');
$users->post('/login', 'login');
$app->mount($users);
/**
 * customer
 */
$customer = new MicroCollection();
$customer->setHandler('Acc\Controllers\CustomerController', true);
$customer->setPrefix('/customer'); 
$customer->get('/index/{page}/{count}', 'index');
$customer->get('/{page}/{count}', 'index');
$customer->post('/create', 'create');
$customer->post('/update/{id}', 'update'); 
$customer->get('/view/{id}', 'view'); 
$app->mount($customer);

/**
 * Not found handler
 */
$app->notFound(function () use ($app) {
	$app->response->setStatusCode(404, "Not Found");
	$app->response->sendHeaders();
	// $response = new Response();
	return $app->response->setJsonContent(
		[
			'status' => 'ERROR_404_NOT_FOUND',
		]
	);
});
