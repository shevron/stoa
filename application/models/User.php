<?php

class Stoa_Model_User implements Zend_Acl_Role_Interface
{
    /**
     * Is the user authenticated?
     *
     * @var boolean
     */
    protected $_isAuthenticated = false;

    protected $_username = null;

    protected $_email = null;

    protected $_roles = array('guest');

    public function getUsername()
    {
        return $this->_username;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getRoles()
    {
        return $this->_roles;
    }

    public function getRoleId()
    {
        return $this->_roles[0];
    }

    public function isAuthenticated()
    {
        return $this->_isAuthenticated;
    }

    static public function authenticate($username, $password)
    {
        $adapter = self::_getAuthAdapter($username, $password);
        $result = Zend_Auth::getInstance()->authenticate($adapter);

        if ($result->isValid()) {
            $user = new self;
            $user->_username = $username;
            $user->_isAuthenticated = true;
            $user->_roles[0] = 'admin';
            return $user;
        } else {
            return null;
        }
    }

    static public function getCurrentUser()
    {
        return new self(); 
    }

    static protected function _getAuthAdapter($username, $password)
    {
        return new Geves_Auth_Adapter_Dummy($username, $password);
    }
}

