<?php

class Stoa_View_Helper_TimeFormat extends Zend_View_Helper_Abstract
{
    public function timeFormat($time, $format = null)
    {
        if ($format === null) {
            $format = Stoa_Model_GlobalConfig::getConfig()->format->timestamp->long;
        }
        return date($format, $time);
    }
}