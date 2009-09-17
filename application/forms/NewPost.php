<?php

class Stoa_Form_NewPost extends Stoa_Form_Abstract
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        $this->setMethod('post');
        
        $title = new Zend_Form_Element_Text(array(
            'name' => 'title',
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
            'label' => 'Post Title'
        ));
        
        $tags = new Zend_Form_Element_Text(array(
            'name' => 'tags',
            'required' => false,
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                'content' => array(
                    'validator' => 'Regex',
                    'options' => array('pattern' => '/^[\w\-, ]+$/u')
                )
            ),
            'label' => 'Tags',
            'description' => 'Comma separated list of tags for this post'
        ));
        
        $text = new Zend_Form_Element_TextArea(array(
            'name' => 'content',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                'length' => array(
                    'validator' => 'StringLength',
                    'options' => array('min' => 10)
                ),
                'content' => array(
                    'validator' => 'Regex',
                    'options' => array('pattern' => '/^(?:[^\p{C}]|\s)+$/u')
                )
            ),
            'label' => 'Content'
        ));
        
        $submit = new Zend_Form_Element_Submit(array(
            'name'  => 'submit',
            'label' => 'Create Post',
            'class' => 'button-big'
        ));
        
        $title->getDecorator('Label')->setOption('class', 'input-big');
        $title->getDecorator('HtmlTag')->setOption('class', 'input-big');
        
        $this->addElement($title)
             ->addElement($text)
             ->addElement($tags)
             ->addElement($submit);
    }
}