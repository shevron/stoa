<?php

class PostController extends Zend_Controller_Action
{
    /**
     * Default action - list posts
     * 
     */
    public function indexAction()
    {
        $tag = $this->_getParam('tag');
        if ($tag) {
            $this->view->postList = Stoa_Model_Post::getPostsListByTag($tag);
            $this->view->tag = $tag;
        } else {
            $this->view->postList = Stoa_Model_Post::getPostsList();
        }
    }
    
    /**
     * Handle new post form presentation and processing
     * 
     */
    public function newAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $post = new Stoa_Model_Post();

        /** @todo role should come from identity, not be hard-coded **/
        if ($identity && $post->getAcl()->isAllowed('admin', null, 'create')) {
            $form = new Stoa_Form_NewPost();
        
            if ($this->_request->isPost()) {
                $params = $this->_request->getParams();
                if ($form->isValid($params)) {
                
                    // Form is valid, save in DB
                    $post->fromArray(array(
                        'title'        => $form->getValue('title'),
                        'content'      => $form->getValue('content'),
                        'content_type' => 'text/html',
                        'tags'         => $this->_splitPostTags($form->getValue('tags')),
                        'published'    => true,
                        'location'     => array(
                            'name'     => $form->getValue('location'),
                            'coords'   => array(0.0, 0.0)
                        )
                    ));
                
                    try {
                        $post->save();
                        $this->_redirect('/');
                    
                        // -- execution ends here --
                    
                    } catch (Sopha_Exception $ex) {
                        // TODO: Propagate error to user ?
                        throw $ex;
                    }
                }
            }
        
            $this->view->form = $form;

        } else {
            $this->view->error = new Geves_Message("You are not authorized to perform this operation"); 
        }
    }
    
    public function showAction()
    {
        $commentForm = new Stoa_Form_NewComment();
        $this->view->commentForm = $commentForm;
        
        if (($id = $this->_getParam('id')) != null) {
            $post = Stoa_Model_Post::getPostWithComments($id);
        } elseif (($title = $this->_getParam('title')) != null) {
            $post = Stoa_Model_Post::getPostWithCommentsByTitle($title);
        }
        
        if ($post) {
            $this->view->post = $post;
            $this->view->comments = $post->getComments();
            
            if ($this->_request->isPost()) {
                $params = $this->_request->getParams();
                if ($commentForm->isValid($params)) {
            
                    // Form is valid, save in DB
                    $comment = new Stoa_Model_Comment(array(
                        'post_id'      => $post->getId(),
                        'author'       => $commentForm->getValue('author'),
                        'author_email' => $commentForm->getValue('author_email'),
                        'author_url'   => $commentForm->getValue('author_url'),
                        'content'      => $commentForm->getValue('content'),
                    ));
                
                    try {
                        $comment->save();
                        $this->view->message = new Geves_Message('Thank you for posting a comment!', Geves_Message::INFO);
                        
                        // Push new comment to comments array
                        array_unshift($this->view->comments, $comment);
                        
                        // -- execution ends here --
                    
                    } catch (Sopha_Exception $ex) {
                        $this->view->message = new Geves_Message('Unable to save your comment: ' . $ex->getMessage());
                        $commentForm->populate($params);
                    }
                }
            }
            
        } else {
            // -- no such post --
            $this->view->message = new Geves_Message('Unable to find requested post. Maybe it was deleted?');
        }
    }
    
    /**
     * Split a string of tags into a normalized array of tags
     * 
     * @param  string $tags
     * @return array
     */
    protected function _splitPostTags($tags)
    {
        $intags = explode(',', $tags);
        $outtags = array();
        
        foreach ($intags as $tag) {
            $tag = trim($tag);
            if (! empty($tag)) $outtags[] = strtolower($tag);
        }
        
        return $outtags;
    }
}
