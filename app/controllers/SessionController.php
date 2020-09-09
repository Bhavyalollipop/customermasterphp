<?php
namespace Acc\Controllers;
use Phalcon\Http\Response;
use Acc\Models\User;
use Acc\Auth\Exception as AuthException;
class SessionController extends ControllerBase
{
   	/*
    registraiton method
    */
	public function register()
	{
		/*
		get post data in raw json format
		*/
		$request = $this->request->getJsonRawBody();
		/*
		declare the response to send back 
		*/
		$response = new Response();
		/*
		checking for exist user with this email id
        */
        $users = $this->modelsManager->createBuilder()
			->columns("user_mail")
			->addFrom("Acc\Models\User")
			->where("user_mail = '" . $request->user_mail . "'")
			->getQuery()
            ->getSingleResult();
        if ($users != null) {
            if ($users->user_mail == $request->user_mail) {
                $error_message = "Account with this email already exist";
            }
            /*
            output error response
            */
            $response->setStatusCode(201, 'Non authoritative information');
            $response->setJsonContent(
                [
                    'data' => [
                        'error' => $error_message,
                    ]
                ]
            );
            return $response;
        }
        
        
        try {
            /*
            * check the required data present from the post json data
            * register new user
            */
            $accounts_users = new User();
            $accounts_users->user_name = $request->user_name;
            $accounts_users->user_mail = $request->user_mail;
            $accounts_users->user_pwd = $this->security->hash($request->user_pwd);
            if($accounts_users->save()){
                $this->auth->check([
					'email' => $accounts_users->user_mail,
					'password' => $request->user_pwd
                ]);
                /*
                output success response
                */
                $response->setStatusCode(200, 'Success');
                $response->setJsonContent(
                    [
                        'type' => 'success',
                        'message' => 'User has registered successfully !',
                        'detail' => $this->encodeToken($this->auth->getSessionIdentity()),
                    ]
                );
            }
            
        } catch (AuthException $e) {
            /*
            output error response
            */
            $response->setStatusCode(201, 'Error');
            $response->setJsonContent(
                [ 
                    $e->getMessage()
                ]
            );
        }
        return $response;
    }
    /*
    Login method
    */
	public function login()
	{ 
        /*
		get post data in raw json format
		*/
		$request = $this->request->getJsonRawBody();
        /*declare the response to send back */
        $response = new Response();
        /*
		check the required data present from the post json data
		*/
		if (isset($request->email) && isset($request->password)) {
            try {
                /*
                check authentication
                */
				$this->auth->check([
					'email' => $request->email,
					'password' => $request->password
                ]);
                /*
                output success response
                */
				$response->setStatusCode(200, 'Success');
				$response->setJsonContent(
					[
						'data' => [
                            'type' => 'success',
                            'message' => 'User has logged in successfully !',
                            'detail' => $this->encodeToken($this->auth->getSessionIdentity()),
							'user_name' =>  $request->email
						]
					]
                );
                return $response;
			} catch (AuthException $e) {
                /*
                output error response
                */
                $response->setStatusCode(201, 'Error');
                $response->setJsonContent(
                    [
                        'data' => [
                            'error' => $e->getMessage(),
                        ]
                    ]
                );
                return $response;
            }
        }
    }
  
}