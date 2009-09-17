<?php

class Stoa_View_Helper_TagsFormat extends Zend_View_Helper_Abstract
{
    public function tagsFormat(array $tags)
    {
        foreach($tags as $key => $tag) {
            $tags[$key] = '<a href="' . $this->view->baseUrl . '/tag/' . urlencode($tag) . '">' . 
                htmlspecialchars($tag) . '</a>';
        }
        
        return implode(', ', $tags);
    }
}