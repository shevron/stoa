<?php

class Stoa_View_Helper_PostListItem extends Zend_View_Helper_Abstract
{
    /**
     * Build the HTML for a post list item
     * 
     * @param  Stoa_Model_Post $post
     * @return string
     */
    public function postListItem(Stoa_Model_Post $post)
    {
        $html = '<div class="post">' .
                    '<h3><a href="' . $this->view->baseUrl . '/post/' . $post->getId() . '">' . 
                        htmlspecialchars($post->title) . '</a></h3>' .
                    '<div class="post-info">' . 
                        '<span>' . $this->view->timeFormat($post->created_at) . '</span>' .
                        '<span>tags: ' . $this->view->tagsFormat($post->tags) . '</span>' . 
                    '</div>' .
                    '<div class="content">' . 
                        $this->_previewSubString($post->content) .
                    '</div>' . 
                '</div>';
        
        return $html;
    }
    
    /**
     * Get a preview of the post's content
     * 
     * @param  string  $text
     * @param  integer $limit
     * @return string
     */
    protected function _previewSubString($text, $limit = 1000)
    {
        if (strlen($text) > $limit) {
            $text = $this->view->contentFormat(substr($text, 0, strpos($text, "\n", $limit))) . "\n" . 
                '<span class="more">[read more...]</a>'; 
        } else {
            $text = $this->view->contentFormat($text);
        } 
        
        return $text; 
    }
}