<?php

/**
 * An extension to Zend_Auth_Storage_Session which lazy-starts the session only
 * if it has already been started or if a write to session is required.
 * 
 * @author shahar
 */
class Geves_Auth_Storage_LazySession extends Zend_Auth_Storage_Session
{
    public function __construct($namespace = self::NAMESPACE_DEFAULT, $member = self::MEMBER_DEFAULT)
    {
        $this->_namespace = $namespace;
        $this->_member    = $member;
        
        $sessName = Zend_Session::getOptions('name');
        if (isset($_COOKIE[$sessName])) { 
            $this->_session = new Zend_Session_Namespace($namespace);
        }
    }
    
    public function isEmpty()
    {
        if ($this->_session) { 
            return parent::isEmpty();
        } else {
            return true;
        }
    }
    
    public function read()
    {
        if ($this->_session) { 
            return parent::read();
        } else {
            return null;
        }
    }
    
    public function write($contents)
    {
        if (! $this->_session) { 
            $this->_session = new Zend_Session_Namespace($this->_namespace);
        }
        
        parent::write($contents);
    }

    public function clear()
    {
        if ($this->_session) parent::clear();
    }
}