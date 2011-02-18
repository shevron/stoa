<?php

class Stoa_View_Helper_TagCloud extends Zend_View_Helper_Abstract
{
    public function tagCloud($tag = null)
    {
        if ($tag) {
            $tags = Stoa_Model_Tag::getRelatedTags($tag);
        } else {
            $tags = Stoa_Model_Tag::getPopularTags();
        }

	if (empty($tags)) return;
        
        $minWeight = min($tags);
        $maxWeight = max($tags);
        
        $cloud = new Zend_Tag_Cloud();
        
        foreach($tags as $tagName => $tagWeight) {
            $cloud->appendTag(array(
                'title'  => $tagName,
                'weight' => $tagWeight,
                'params' => array('url' => $this->view->baseUrl . '/tag/' . urlencode($tagName))
            ));
        }
        
        $cloud->getCloudDecorator()->setHtmlTags(array(
            'ul' => array('class' => 'tag-cloud')
        ));
        
        $cloud->getTagDecorator()->setOptions(array(
            'fontSizeUnit' => '%',
            'maxFontSize'  => 200,
            'minFontSize'  => 100
        ));
        
        return (string) $cloud;
    }
}
