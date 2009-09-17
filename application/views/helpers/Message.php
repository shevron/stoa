<?php

class Stoa_View_Helper_Message extends Zend_View_Helper_Abstract
{
    public function message(Geves_Message $message)
    {
        return '<div class="message ' . $message->getClass() . '">' . 
            htmlspecialchars($message->getMessage()) . 
            '</div>';  
    }
}