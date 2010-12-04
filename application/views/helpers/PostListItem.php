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
        $postUrl = $this->view->baseUrl . '/post/' . $post->getId();
        $html = '<div class="post">' .
                    '<h3><a href="' . $postUrl . '">' . 
                        htmlspecialchars($post->title) . '</a></h3>' .
                    '<div class="post-info">' . 
                        '<span>' . $this->view->timeFormat($post->created_at) . '</span>' .
                        '<span>tags: ' . $this->view->tagsFormat($post->tags) . '</span>' . 
                    '</div>' .
                    '<div class="content">' . 
                        $this->_previewSubString($post->content, $postUrl) .
                    '</div>' . 
                '</div>';
        
        return $html;
    }
    
    /**
     * Get a preview of the post's content
     * 
     * @param  string  $text
     * @param  string  $url
     * @param  integer $limit
     * @return string
     */
    protected function _previewSubString($text, $url, $limit = 1000)
    {
        if (strlen($text) > $limit) {
            $text = $this->view->contentFormat(substr($text, 0, strpos($text, "\n", $limit))) . "\n" . 
                '<a href="' . $url . '" class="read-more">[read more...]</a>'; 
        } else {
            $text = $this->view->contentFormat($text);
        } 
        
        return $text; 
    }
}