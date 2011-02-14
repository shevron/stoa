<?php

class Stoa_Form_Login extends Stoa_Form_Abstract
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        $this->setMethod('post');
        
        $this->addElements(array(
            'username' => new Zend_Form_Element_Text(array(
                'name' => 'username',
                'required' => true,
                'filters' => array(
                    'StringTrim'
                ),
                'label' => 'User name or email:'
            )),
            
            'password' => new Zend_Form_Element_Password(array(
                'name' => 'password',
                'required' => true,
                'label'    => 'Password'
            )),
    
            'remember' => new Zend_Form_Element_Checkbox(array(
                'name' => 'remember_me',
                'description' => 'Keep me logged-in on this computer'
            )),
     
            'submit' => new Zend_Form_Element_Submit(array(
                'name'  => 'submit',
                'label' => 'Log In',
                'class' => 'button-big'
            ))
        ));
        
        $this->remember->getDecorator('Description')->setOptions(array(
        	'tag'   => 'span', 
        	'class' => 'checkbox-desc'
        ));
    }
}