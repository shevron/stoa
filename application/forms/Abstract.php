<?php

/**
 * Stoa base form class 
 *  
 * @author shahar
 *
 */

abstract class Stoa_Form_Abstract extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        // Set up decorators
        $this->clearDecorators();
        $this->addDecorator('FormElements')
             ->addDecorator(
                array('container' => 'HtmlTag'), 
                array('tag' => 'dl'))
             ->addDecorator(
                array('endtag' => 'HtmlTag'), 
                array('tag' => 'div', 'class' => 'form-end', 'placement' => 'append'))
             ->addDecorator('Form');
        
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('Errors'),
            array('Label', array('tag'=>'dt')),
            array('HtmlTag', array('tag' => 'dd'))
        ));
    }
}