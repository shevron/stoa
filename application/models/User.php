<?php

class Stoa_Model_User implements Zend_Acl_Role_Interface
{
    public function getUsername()
    {

    }

    public function getEmail()
    {

    }

    public function getRoles()
    {

    }

    public function getRoleName()
    {

    }

    public function isAuthenticated()
    {

    }

    static public function getCurrentUser()
    {
        return new self(); 
    }
}
