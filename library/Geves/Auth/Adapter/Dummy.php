<?php

class Geves_Auth_Adapter_Dummy implements Zend_Auth_Adapter_Interface
{
    protected $_username = null;
    
    protected $_password = null;
    
    public function __construct($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
    }
    
    public function authenticate()
    {
        if ($this->_username == $this->_password) {
            $result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_username); 
        } else {
            $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, 
                null, array("Wrong user name or password"));
        }
        
        return $result; 
    }
}
