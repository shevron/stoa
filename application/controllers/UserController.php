<?php

class UserController extends Zend_Controller_Action
{
    protected $_session;
    
    public function init() 
    {
        if (Zend_Session::isStarted()) { 
            $this->_session = new Zend_Session_Namespace('stoa');
        } else {
            $this->_session = null;
        }
    }
    
    /**
     * Show the log-in form or allow users to log in
     * 
     */
    public function loginAction()
    {
        if ($this->_session) { 
            // User is already logged in
            $this->_redirect('/');
        }
        
        $form = new Stoa_Form_Login();
        
        if ($this->_request->isPost()) { 
            if ($form->isValid($this->_request->getPost())) {
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($this->_getAuthAdapter(
                    $form->getValue('username'),
                    $form->getValue('password')
                ));
                
                if ($result->isValid()) { 
                    $this->_redirect('/');
                } else {
                    $this->view->message = new Geves_Message(array_shift($result->getMessages()));
                }
            }
        }
        
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $token = $this->_getParam('requestToken');

        /** @todo: XSRF protection **/
        if ($this->_session) {
            Zend_Auth::getInstance()->clearIdentity();
            Zend_Session::destroy();
        }

        $this->_redirect('/');
    }
    
    protected function _getAuthAdapter($username, $password)
    {
        return new Geves_Auth_Adapter_Dummy($username, $password);
    }
}
