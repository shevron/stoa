<?php

require_once dirname(__FILE__) . '/../bootstrap.php'; 

class Stoa_Form_NewPostTest extends PHPUnit_Framework_TestCase
{
    protected $_form = null;
    
    public function setUp()
    {
        $this->_form = new Stoa_Form_NewPost();
    }
    
    public function tearDown()
    {
        $this->_form = null;
    }
}