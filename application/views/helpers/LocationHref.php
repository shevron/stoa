<?php

class Stoa_View_Helper_LocationHref extends Zend_View_Helper_Abstract
{
    public function locationHref(array $location)
    {
        return htmlspecialchars($location['name']);
    }
}
