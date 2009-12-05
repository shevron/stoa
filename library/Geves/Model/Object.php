<?php

/**
 * Geves Library - Model Object
 * 
 * 
 */

require_once 'Sopha/Db.php';
require_once 'Sopha/Document.php';

abstract class Geves_Model_Object extends Sopha_Document
{
    /**
     * The single Sopha_Db CouchDB connection adapter of this app
     *
     * @var Sopha_Db|null
     */
    static private $_couch = null;
    
    /**
     * A list of interfaces this object implements. Usually used by CouchDB views.
     * 
     * @var array
     */
    protected $_implements = array();
    
    /**
     * The document type. Should be pretty much unique for each model class
     * 
     * @var string
     */
    protected $_doctype = null; 
    
    /**
     * Overload constructor to set some default behavior and set doctype
     * and list of interfaces the object implements
     * 
     * @param array $data 
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data, null, self::getDb());
        
        if (! isset($this->_data['@doctype'])) {
            if (! $this->_doctype) $this->_doctype = get_class($this);
            $this->_data['@doctype'] = $this->_doctype;
        }
        
        if (! is_array($this->_implements)) {
            require_once 'Geves/Model/Exception.php';
            throw new Geves_Model_Exception("\$_implements is expected to " . 
                "be an array, but it is '" . gettype($this->_implements) . "'");
        }
        $this->_data['@implements'] = $this->_implements;
    }
    
    /**
     * Overload save to validate first
     *
     * @return void
     */
    public function save()
    {
        if ($this->isValid()) {
            return parent::save();
        } else {
            require_once 'Geves/Model/Exception.php';
            throw new Geves_Model_Exception("Object is not a valid " . 
                get_class($this) . " object and cannot be saved in DB");
        }
    }
    
    /**
     * Validate object, return true if valid, false if otherwise.
     * 
     * Should be implemented by subclasses, by default will simply return 'true'
     * irrigardless of the data
     *
     * @return boolean
     */
    protected function isValid()
    {
        return true; 
    }
    
    /**
     * Get the single CouchDB instance of this application
     *
     * @return Sopha_Db
     */
    static public function getDb()
    {
        if (! self::$_couch instanceof Sopha_Db) {
            throw new Geves_Model_Exception("No DB configuration was set");
        }
        
        return self::$_couch;
    }
    
    /**
     * Set the DB configuration and create the DB connection adapter
     * 
     * @param  array | Zend_Config $config
     */
    static public function setDbConfig($config) 
    {
    	if ($config instanceof Zend_Config) {
    		$config = $config->toArray();
    	}

    	if (! isset($config['dbname'])) {
    		throw new Geves_Model_Exception("No DB name was set, unable to init Sopha_Db");
    	}
    	
    	$hostname = (isset($config['hostname']) ? $config['hostname'] : null);
    	$port     = (isset($config['port']) ? $config['port'] : null);
    	self::$_couch = new Sopha_Db($config['dbname'], $hostname, $port);
    }
}
