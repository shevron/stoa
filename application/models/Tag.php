<?php

class Stoa_Model_Tag
{
    /**
     * Get an array of all the tags with an absolute popularity ranking
     * 
     * @retru array
     */
    public function getPopularTags()
    {
        $return = array();
        
        $params = array(
            'group' => true
        );
        
        $tags = Geves_Model_Object::getDb()->view('tag', 'popular', $params); //, Sopha_View_Result::RETURN_ARRAY
        foreach($tags as $key => $value) {
            $return[$key] = $value;    
        }
        
        return $return;
    }
}