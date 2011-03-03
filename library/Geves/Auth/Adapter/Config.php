<?php

class Geves_Auth_Adapter_Config implements Zend_Auth_Adapter_Interface
{
    protected $_userinfo = null;

    protected $_username = null;

    protected $_password = null;

    public function __construct($username, $password, Zend_Config $options)
    {
        $this->_username = $username;
        $this->_password = $password;
        
        $this->_userinfo = $options->get('users', new Zend_Config(array()))
                                   ->get($username, null);
    }

    public function authenticate()
    {
        if (! $this->_userinfo) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null, 
                array("user does not exist"));
        }

        if ($this->_userinfo->passhash) {
            $password = hash($this->_userinfo->passhash, $this->_password);
        } else {
            $password = $this->_password;
        }

        if ($this->_userinfo->get('password', null) &&
            $this->_userinfo->password == $password) {
            
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_username);
        } else {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null,
                array("incorrect uesr name or password"));
        }
    }
}
