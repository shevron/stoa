<?php

class Stoa_Form_NewComment extends Stoa_Form_Abstract
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        $this->addElementPrefixPath('Geves_Validate', 'Geves/Validate/', 'validate');
        
        $this->setMethod('post');
        
        $author = new Zend_Form_Element_Text(array(
            'name' => 'author',
            'required' => true,
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                'length' => array(
                    'validator' => 'StringLength',
                    'options' => array('min' => 4, 'max' => 200)
                ),
                'content' => array(
                    'validator' => 'Regex',
                    'options' => array('pattern' => '/^[^\p{C}]+$/u')
                )
            ),
            'label' => 'Your name'
        ));
        
        $email = new Zend_Form_Element_Text(array(
            'name'     => 'author_email',
            'required' => true,
            'filters'  => array(
                'StringTrim'
            ),
            'validators' => array(
                'email' => array(
                    'validator' => 'EmailAddress',
                )
            ),
            'label' => 'Your e-mail address',
            'description' => 'will not be viewed or shared'
        )); 
        
        $website = new Zend_Form_Element_Text(array(
            'name'     => 'author_url',
            'required' => false,
            'filters'  => array(
                'StringTrim'
            ),
            'validators' => array(
                'url' => array(
                    'validator' => 'Url',
                )
            ),
            'label' => 'Your web site',
            'description' => 'we will link to this address, if you provide one'
        ));
        
        $text = new Zend_Form_Element_Textarea(array(
            'name' => 'content',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                'length' => array(
                    'validator' => 'StringLength',
                    'options' => array(
                    	'min' => 10,
                        'messages' => array(
                            Zend_Validate_StringLength::TOO_SHORT => 'Comment is too short - we expect 10 characters at least'
                        )
                    )
                ),
                'content' => array(
                    'validator' => 'Regex',
                    'options' => array('pattern' => '/^(?:[^\p{C}]|\s)+$/u')
                )
            ),
            'label' => 'Comment',
            'rows'  => 8
        ));
        
        $submit = new Zend_Form_Element_Submit(array(
            'name'  => 'submit',
            'label' => 'Post Comment',
            'class' => 'button-big'
        ));
        
        $this->addElement($author)
             ->addElement($email)
             ->addElement($website)
             ->addElement($text)
             ->addElement($submit);
    }
}
