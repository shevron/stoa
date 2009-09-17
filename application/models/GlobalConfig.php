<?php

class Stoa_Model_GlobalConfig 
{
    /**
     * Single Zend_Config instance
     * 
     * @var Zend_Config 
     */
    static protected $_config = null;
    
    /**
     * This is a static class!
     * 
     * @throws ErrorException
     */
    protected function __construct()
    {
        throw new ErrorException('This class should not be instantiated');
    }
    
    /**
     * Get the global configuration object
     * 
     * @return Zend_Config
     */
    static public function getConfig()
    {
        if (self::$_config == null) {
            self::$_config = new Zend_Config(array(
            
                // TODO: Move this to DB / INI file
                'format' => array(
                    'timestamp' => array(
                        'long'  => 'D M jS Y, h:ia'  
                     )
                )
            ));
        }
        
        return self::$_config;
    }
}