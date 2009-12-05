<?php

/**
 * Validator class for validating URLs (mostly HTTP URLs). 
 * 
 * Uses Zend_Uri for checking and implementes Zend_Validate_Abstract, so it
 * can be used to validate Zend_Form elements etc.
 * 
 * 
 */

require_once 'Zend/Validate/Abstract.php';
require_once 'Zend/Uri.php';

class Geves_Validate_Url extends Zend_Validate_Abstract 
{
	const INVALID_URL = 'invalidUrl';
	
	protected $_messageTemplates = array(
	   self::INVALID_URL => "'%value%' is not a valid URL"
	);
	
	/**
     * Check the $value is a valid URL
     * 
     * @param  string $value
     * @return boolean
	 */
	function isValid($value) 
	{
		$value = (string) $value;
        $this->_setValue($value);
        
        if (! Zend_Uri::check($value)) {
        	$this->_error(self::INVALID_URL);
        	return false;
        }
        
        return true;
	}
}
