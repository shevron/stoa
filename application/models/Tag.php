<?php

class Stoa_Model_Tag
{
    /**
     * Get an array of all the tags with an absolute popularity ranking
     * 
     * @retru array
     */
    static public function getPopularTags()
    {
        $return = array();
        
        $params = array(
            'group' => true
        );
        
        $tags = Geves_Model_Object::getDb()->view('tag', 'popular', $params);
        foreach($tags as $value) {
            $return[$tags->currentKey()] = $value;    
        }
        
        return $return;
    }
    
    /**
     * Get an array of all tags related to the specified tag, with relativity ranking
     * 
     * The relativity ranking designates how many posts include both tags
     * 
     * @param  string $tag
     * @return array
     */
    static public function getRelatedTags($tag)
    {
        $return = array();
        
        $params = array(
            'group'    => true,
            'startkey' => array($tag),
            'endkey'   => array($tag, array()),
        );
        
        $tags = Geves_Model_Object::getDb()->view('tag', 'related', $params);
        foreach($tags as $value) {
            $key = $tags->currentKey(); 
            $return[$key[1]] = $value;
        }
        
        return $return;
    }
}