<?php

class Stoa_Model_Post extends Geves_Model_Object
{
    protected $_doctype = 'Post';
    
    protected $_data = array(
        'title'            => null,
        'normalized_title' => null,
        'content'          => null,
        'created_at'       => null,
        'tags'             => array(),
        'published'        => false,
    );
    
    protected $_comments = null;
    
    public function isValid()
    {
        $valid = $this->_data['title'] &&
                 $this->_data['normalized_title'] && 
                 $this->_data['content'] &&
                 is_int($this->_data['created_at']) &&
                 is_array($this->_data['tags']);
                  
        return $valid;
    }
    
    public function save()
    {
        $this->_data['normalized_title'] = self::_normalizeTitle($this->_data['title']);
        if ($this->_data['created_at'] === null) 
            $this->_data['created_at'] = $_SERVER['REQUEST_TIME'];
            
        return parent::save();
    }
    
    protected function _loadComment($comment)
    {
        if ($this->_comments === null) $this->_comments = array();
        
        if ($comment instanceof Stoa_Model_Comment) {
            $this->_comments[] = $comment;
        } elseif (is_array($comment) && $comment['@doctype'] == 'Comment') {
            $this->_comments[] = new Stoa_Model_Comment($comment);
        } else {
            throw new ErrorException("Provided data does not seem to be a valid comment object");
        }
    }
    
    /**
     * Get the list of comments for this post
     * 
     * @return array
     */
    public function getComments()
    {
        if ($this->_comments === null) {
            // TODO: Lazy-load comments
            return array();
        }
        
        return $this->_comments;
    }

    /**
     * Get list of all posts
     * 
     * @todo Implement limit and paging 
     * 
     * @param integer $page
     * @param integer $perPage 
     */
    static public function getPostsList($page = 0, $perPage = 10)
    {
        $params = array(
            'descending' => true
        );
        $posts = self::getDb()->view('post', 'recent-posts', $params, __CLASS__);
        
        return $posts;
    }
    
    /**
     * Get list of all posts tagged with a specified tag
     * 
     * @todo  Implemented limit and paging
     * 
     * @param string  $tag
     * @param integer $page
     * @param integer $perPage
     */
    static public function getPostsListByTag($tag, $page = 0, $perPage = 10)
    {
        $params = array(
            'descending' => true,
            'startkey'   => array($tag, array()),
            'endkey'     => array($tag),
        );
        
        $posts = self::getDb()->view('post', 'by-tag', $params, __CLASS__);
        
        return $posts;
    }
    
    static public function getPostWithComments($postId)
    {
        $params = array(
            'descending' => true,
            'startkey'   => array($postId, array()),
            'endkey'     => array($postId)
        );
        
        $postAndComments = self::getDb()->view('post', 'with-comments', $params, Sopha_View_Result::RETURN_ARRAY);
        
        if (count($postAndComments) == 0) { 
            return null;
        }
        
        $post = $postAndComments->current();
        if ($post['@doctype'] != 'Post') {
            throw new ErrorException("Unexpected doctype in view result: " . $post['@doctype']);
        }
        $post = new self($post);
        
        for ($postAndComments->next(); $postAndComments->valid(); $postAndComments->next()) {
            $post->_loadComment($postAndComments->current());
        }
        
        return $post;
    }
    
    static protected function _normalizeTitle($title)
    {
        $normalized = strtolower(preg_replace(array('/[^\w\s]+/u', '/\s+/'), array('', '-'), $title));
        return $normalized;
    }
}