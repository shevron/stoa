<?php

abstract class Geves_Model_DbRecord
{
	/**
	 * Database adapter 
	 * 
	 * @var Zend_Db_Adapter_Abstract
	 */
	static protected $_adapter = null;
	
	/**
	 * Name of table 
	 * 
	 * @var string
	 */
	protected $_table;
	
	/**
	 * Name of primary key field 
	 * 
	 * @var string
	 */
	protected $_pkField = 'id';
	
	/**
	 * Field values
	 * 
	 * @var array
	 */
	protected $_data = array();

	/**
	 * Flag indicating whether this is a new record or not
	 * 
	 * @var boolean
	 */
	protected $_new = true;
	
	public function __construct(array $data = array())
	{
		foreach($data as $key => $value) {
			$this->_data[$key] = $value;
		}
		
		if (! $this->_table) {
            require_once 'Geves/Model/Exception.php';
            throw new Geves_Model_Exception("DbRecord classes must have a table name defined");
		}
	}
	
	/**
	 * Save the record in DB
	 * 
	 */
	public function save()
	{
		if (! ($db = self::getDbAdapter())) {
			require_once 'Geves/Model/Exception.php';
			throw new Geves_Model_Exception("Database adapter was not set");
		}
		
		if ($this->_new) {
			// New record, save using insert()
			$db->insert($this->_table, $this->_data);
			$id = $db->lastInsertId($this->_table, $this->_pkField);
			$this->_data[$this->_pkField] = $id;
			$this->_new = false;
		
		} else {
			// Existing record, save using update
			if (! isset($this->_data[$this->_pkField])) {
				require_once 'Geves/Model/Exception.php';
                throw new Geves_Model_Exception("Record is not new but primary key value is unknown");
			}
			
			$where = $db->quoteInto("{$this->_pkField} = ?", $this->_data[$this->_pkField]);
			$db->update($this->_table, $this->_data, $where);
		}
	}
	
	/**
	 * Magic method to set the value of a field
	 * 
	 * @param  string $key
	 * @param  mixed  $value
	 */
	public function __set($key, $value)
	{
		$this->_data[$key] = $value;
	}
	
	/**
	 * Magic method to get the value of a field
	 * 
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if (isset($this->_data[$key])) {
			return $this->_data[$key];
		} else {
			return null;
		}
	}

	/**
	 * Magic method to check if a value is defined
	 * 
	 * @param  string $key
	 * @return boolean
	 */
	public function __isset($key)
	{
		return isset($this->_data[$key]);
	}

	/**
	 * Magic method to unset values 
	 * 
	 * @param  string $key
	 */
	public function __unset($key)
	{
		unset($this->_data[$key]);
	}
	
	/**
	 * Set the default DB adapter
	 *  
	 * @param  Zend_Db_Adapter_Abstract $adapter
	 * @return void
	 */
    static public function setDbAdapter(Zend_Db_Adapter_Abstract $adapter)
    {
        self::$_adapter = $adapter;
    }

    /**
     * Get the default DB adapter 
     * 
     * @return Zend_Db_Adapter_Abstract
     */
    static public function getDbAdapter()
    {
        return self::$_adapter;
    }
}
