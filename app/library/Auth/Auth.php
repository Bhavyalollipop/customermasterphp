<?php
namespace Acc\Auth;
use Acc\Models\User;
use \Phalcon\Di\Injectable;
use Phalcon\Http\Response;

class Auth extends Injectable
{
    public function tokenIdentity($token)
    {
        if (isset($token->user_id) && $token->user_id != '') {
            /**
             * Checking Mail ID present 
             */
            $token_data = [
                'user_id' => $token->user_id,
                'user_name' => $token->user_name,
                'user_mail' => $token->user_mail
            ];
            return $token_data;
        } else {
            throw new Exception('Invalid token');
        }
    }
    public function check($credentials)
    {   
        $user = User::findFirstByUserMail($credentials['email']);
        /*set response*/
        $response = new Response();
        /**
         * Checking Mail ID present 
         */
        if ($user == false) {
            throw new Exception('No such email id exists');
        }
        /**
         * Checking Password present 
         */
        if (!$this->security->checkHash($credentials['password'], $user->user_pwd)) {
            throw new Exception('Wrong email/password combination');
        }
        $this->session->set('auth-identity', [
            'user_id' => $user->user_id,
            'user_name' => $user->user_name,
            'user_mail' => $user->user_mail
        ]);
    }

    public function getSessionIdentity(){
        return $this->session->get('auth-identity');
    }
}