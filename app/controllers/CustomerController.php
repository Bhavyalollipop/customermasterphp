<?php
namespace Acc\Controllers;
use Phalcon\Http\Response;
use Acc\Models\CusMain;
use Acc\Models\CusAddr;
use Acc\Auth\Exception as AuthException;
class CustomerController extends ControllerBase
{
    
    /**
     * customer list
     */
    public function index($page, $count){
        /*
		get post data in raw json format
		*/
		$request = $this->request->getJsonRawBody();
		/*
		declare the response to send back 
        */
        $token = $this->decodeToken($this->getToken());
        $response = new Response();
        try
        {
            $identity = $this->auth->tokenIdentity($token);
		
            /*
            checking for customer list
            */
            $total = $this->modelsManager->createBuilder()
                    ->columns("count(cus_id) as total")
                    ->addFrom("Acc\Models\CusMain")
                    ->getQuery()
                    ->getSingleResult();
            $customer = $this->modelsManager->createBuilder()
                        ->columns("cus_id,CONCAT(cus_fname,' ',cus_lname) as cus_name,cus_mail,cus_ph_no,cus_website")
                        ->addFrom("Acc\Models\CusMain")
                        ->limit($count)
                        ->offset(($page - 1) * $count)
                        ->getQuery()
                        ->execute();
            /*
            * output success response
            */
            $response->setStatusCode(200, 'Success');
            $response->setJsonContent(
                [
                    'type' => 'success',
                    'message' => 'Data retrived successfully!',
                    'total' => $total->total,
                    'list' => $customer
                ]
            );}
        catch(AuthException $e) {
            /**
            *output success response
            */
            $response->setStatusCode(201, 'Error');
            $response->setJsonContent(
                [
                    'status' => 'Please try after some time',
                    'data' => [ 
                        $e->getMessage()
                    ]
                ]
            ); 
        }
        // return response
        return $response;
    }
    /**
     * custoemr create
     */
    public function create(){
        /*
		get post data in raw json format
		*/
		$request = $this->request->getJsonRawBody();
		/*
		declare the response to send back 
        */
        $token = $this->decodeToken($this->getToken());
     
		$response = new Response();
        try
        {
            $identity = $this->auth->tokenIdentity($token);
           
            // check for an existing code
            $duplicate = $this->modelsManager->createBuilder()
                    ->columns("cus_mail")
                    ->addFrom("Acc\Models\CusMain")
                    ->where("cus_mail = '".$request->cus_mail."'")
                    ->getQuery()
                    ->getSingleResult();
            if($duplicate != null){
                $response->setStatusCode(201, 'Success');
                $response->setJsonContent(
                    [
                        'data' => [
                            'type' => 'error',
                            'error' => 'Customer with this email already exists!'
                        ]
                    ]
                );
                return $response;
            }
            $cus_main = new CusMain();
            $cus_main->cus_fname = $request->cus_fname;
            $cus_main->cus_lname = $request->cus_lname;
            $cus_main->cus_mail = $request->cus_mail;
            $cus_main->cus_ph_no = $request->cus_ph_no;
            $cus_main->cus_website = $request->cus_website;
            $cus_main->created_by = $identity["user_id"];
            $cus_main->updated_by = $identity["user_id"];
            $cus_main->save();
            
            /**
             *  customer address
             */
            $cus_addr = $request->cus_addr;
            foreach($cus_addr as $view_cus_addr){
                $cus_addr = new CusAddr();
                $cus_addr->cus_id = $cus_main->cus_id;
                $cus_addr->cus_addr_line_1 = $view_cus_addr->cus_addr_line_1;
                $cus_addr->cus_addr_line_2 = $view_cus_addr->cus_addr_line_2;
                $cus_addr->cus_landmark = $view_cus_addr->cus_landmark;
                $cus_addr->cus_city = $view_cus_addr->cus_city;
                $cus_addr->cus_state = $view_cus_addr->cus_state;
                $cus_addr->cus_country = $view_cus_addr->cus_country;
                $cus_addr->created_by = $identity["user_id"];
                $cus_addr->updated_by = $identity["user_id"];
                $cus_addr->save();
            }
            /*
            * output success response
            */
            $response->setStatusCode(200, 'Success');
            $response->setJsonContent(
                [
                    'type' => 'success',
                    'message' => 'Customer saved successfully!',
                    'cus_id' => $cus_main->cus_id
                ]
            );
        }
        catch(AuthException $e) {
            /**
            *output success response
            */
            $response->setStatusCode(201, 'Error');
            $response->setJsonContent(
                [
                    'status' => 'Please try after some time',
                    'data' => [ 
                        $e->getMessage()
                    ]
                ]
            ); 
        }
        return $response;
    }
    /**
     * custoemr update
     */
    public function update($id){
        /*
		get post data in raw json format
		*/
		$request = $this->request->getJsonRawBody();
		/*
		declare the response to send back 
        */
        $token = $this->decodeToken($this->getToken());
        $response = new Response();
        try
        {
            $identity = $this->auth->tokenIdentity($token);
          
            // check for an existing code
            $duplicate = $this->modelsManager->createBuilder()
                    ->columns("cus_mail")
                    ->addFrom("Acc\Models\CusMain")
                    ->where("cus_mail = '".$request->cus_mail."' and cus_id != '".$id."'")
                    ->getQuery()
                    ->getSingleResult();
                  
            if($duplicate != null){
                $response->setStatusCode(201, 'Success');
                $response->setJsonContent(
                    [
                        'data' => [
                            'type' => 'error',
                            'error' => 'Customer with this email already exists!'
                        ]
                    ]
                );
                return $response;
            }
            $cus_main = CusMain::findFirst($id);
            $cus_main->cus_fname = $request->cus_fname;
            $cus_main->cus_lname = $request->cus_lname;
            $cus_main->cus_mail = $request->cus_mail;
            $cus_main->cus_ph_no = $request->cus_ph_no;
            $cus_main->cus_website = $request->cus_website;
            $cus_main->created_by = $identity["user_id"];
            $cus_main->updated_by = $identity["user_id"];
            $cus_main->save();
          
            /**
             *  customer address
             */
            $cus_addr = $request->cus_addr;
            
            foreach($cus_addr as $view_cus_addr){
                $cus_addr =  CusAddr::findFirst($view_cus_addr->cus_addr_id);
                $cus_addr->cus_id = $cus_main->cus_id;
                $cus_addr->cus_addr_line_1 = $view_cus_addr->cus_addr_line_1;
                $cus_addr->cus_addr_line_2 = $view_cus_addr->cus_addr_line_2;
                $cus_addr->cus_landmark = $view_cus_addr->cus_landmark;
                $cus_addr->cus_city = $view_cus_addr->cus_city;
                $cus_addr->cus_state = $view_cus_addr->cus_state;
                $cus_addr->cus_country = $view_cus_addr->cus_country;
                $cus_addr->created_by = $identity["user_id"];
                $cus_addr->updated_by = $identity["user_id"];
                $cus_addr->save();
            }
           
            /*
            * output success response
            */
            $response->setStatusCode(200, 'Success');
            $response->setJsonContent(
                [
                    'type' => 'success',
                    'message' => 'Customer updated successfully!',
                    'cus_id' => $cus_main->cus_id
                ]
            );
        }
        catch(AuthException $e) {
            /**
            *output success response
            */
            $response->setStatusCode(201, 'Error');
            $response->setJsonContent(
                [
                    'status' => 'Please try after some time',
                    'data' => [ 
                        $e->getMessage()
                    ]
                ]
            ); 
        }
        return $response;
    }
    /**
     * view data
     */
    public function view($id){
         /*
		get post data in raw json format
		*/
		$request = $this->request->getJsonRawBody();
		/*
		declare the response to send back 
        */
        $token = $this->decodeToken($this->getToken());
        $response = new Response();
        try
        {
            $identity = $this->auth->tokenIdentity($token);
            $cus_main = CusMain::findFirst($id);
            $cus_addr = CusAddr::find("cus_id = ".$id);
              
            
            /*
            * output success response
            */
            $response->setStatusCode(200, 'Success');
            $response->setJsonContent(
                [
                    'type' => 'success',
                    'message' => 'Customer data retrived successfully!',
                    'cus_main' => $cus_main,
                    'cus_addr' => $cus_addr
                ]
            );
        }
        catch(AuthException $e) {
            /**
            *output success response
            */
            $response->setStatusCode(201, 'Error');
            $response->setJsonContent(
                [
                    'status' => 'Please try after some time',
                    'data' => [ 
                        $e->getMessage()
                    ]
                ]
            ); 
        }
        return $response;
    }
}