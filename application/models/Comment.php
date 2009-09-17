<?php

class Stoa_Model_Comment extends Geves_Model_Object
{
    protected $_doctype = 'Comment';

    protected $_data = array(
        'content'      => null,
        'created_at'   => null,
        'author'       => null,
        'author_email' => null,
        'author_url'   => null,
        'post_id'      => null
    );
    
    /**
     * Validate object, return true if valid, false if otherwise.
     * 
     * @return boolean
     */
    protected function isValid()
    {
        return $this->_data['content'] &&
               $this->_data['author'] &&
               $this->_data['author_email'] &&
               $this->_data['post_id'] &&
               is_int($this->_data['created_at']);
    }
    
    public function save()
    {
        if ($this->_data['created_at'] === null)
            $this->_data['created_at'] = $_SERVER['REQUEST_TIME'];
            
        return parent::save();
    }
}