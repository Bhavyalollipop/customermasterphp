<?php
namespace Acc\Controllers;
use Phalcon\Mvc\Controller;
class ControllerBase extends Controller
{
 /**
     * Encode token.
     */
    public function encodeToken($data)
    {
        // Encode token
        $token_encoded = $this->jwt->encode($data, $this->tokenConfig['secret']);
        return $token_encoded;
    }
    /**
     * Decode token.
     */
    public function decodeToken($token)
    {
        // Decode token
        $token = $this->jwt->decode($token,$this->tokenConfig['secret'], array('HS256'));
        return $token;
    }
    /**
     * Returns token from the request.
     * Uses token URL query field, or Authorization header
     */
    public function getToken()
    {
        $authHeader = $this->request->getHeader('Authorization');
        $authQuery = $this->request->getQuery('token');
        return $authQuery ? $authQuery : $this->parseBearerValue($authHeader);
    }
    protected function parseBearerValue($string)
    {
        if (strpos(trim($string), 'Bearer') !== 0) {
            return null;
        }
        return preg_replace('/.*\s/', '', $string);
    }  
}   
?>