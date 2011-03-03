<?php

class Stoa_Model_User implements Zend_Acl_Role_Interface
{
    /**
     * Authentication and user management configuration
     *
     * @var Zend_Config
     */
    static protected $_config = null;

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
            
            Zend_Auth::getInstance()->getStorage()->write($user);
            return $user;

        } else {
            return null;
        }
    }

    static public function getCurrentUser()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity();
 
        } else {
            return new self(); 
        }
    }

    static public function logoutCurrentUser()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
    }

    static public function setConfig(Zend_Config $config)
    {
        self::$_config = $config;
    }

    static public function getConfig()
    {
        return self::$_config;
    }

    static protected function _getAuthAdapter($username, $password)
    {
        $config = self::$_config; 
        if ($config) {
            $adapter = $config->adapter;
            $options = $config->options;
        } else {
            $adapter = 'dummy';
            $options = new Zend_Config(array());
        }

        switch($adapter) {
            case 'config':
                return new Geves_Auth_Adapter_Config($username, $password, $options);
                break;

            case 'dummy':
            default:
                return new Geves_Auth_Adapter_Dummy($username, $password);
                break;
        }
    }
}

