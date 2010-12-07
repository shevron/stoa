<?php

class Stoa_View_Helper_ContentFormat extends Zend_View_Helper_Abstract
{
    /**
     * Format text into presentable HTML
     * 
     * For now, this merely converts linebreaks to <br /> tags
     * 
     * @param string $text
     * @return string
     */
    public function contentFormat($text)
    {
        return $text;
    }
}