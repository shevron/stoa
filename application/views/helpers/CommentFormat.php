<?php

class Stoa_View_Helper_CommentFormat extends Zend_View_Helper_Abstract
{
    public function commentFormat(Stoa_Model_Comment $comment)
    {
        if ($comment->author_url) {
            $author = '<a href="' . $comment->author_url . '">' . 
                htmlspecialchars($comment->author) . '</a>'; 
        } else {
            $author = htmlspecialchars($comment->author);
        }
        
        return 
            '<div class="comment">' . 
                '<div class="comment-info">' .
                    '<span class="comment-author">' . $author . '</span>' . 
                    '<span class="comment-time">' . $this->view->timeFormat($comment->created_at) . '</span>' .
                '</div>' . 
                '<div class="comment-content">' . $this->view->contentFormat($comment->content) . '</div>' . 
            '</div>';
    }
}