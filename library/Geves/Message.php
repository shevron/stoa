<?php

class Geves_Message 
{
    const INFO    = 'info';
    const WARNING = 'warning';
    const ERROR   = 'error';
    
    protected $_message = '';
    
    protected $_class = self::ERROR;
    
    public function __construct($message, $class = self::ERROR)
    {
        $this->_message = $message;
        $this->_class   = $class;
    }
    
    public function getMessage()
    {
        return $this->_message;
    }
    
    public function getClass()
    {
        return $this->_class;
    }
}