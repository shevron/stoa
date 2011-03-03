<?php

class UserController extends Zend_Controller_Action
{
    /**
     * Show the log-in form or allow users to log in
     * 
     */
    public function loginAction()
    {
        if (Stoa_Model_User::getCurrentUser()->isAuthenticated()) {
            // User is already logged in
            $this->_redirect('/');
        }
        
        $form = new Stoa_Form_Login();
        
        if ($this->_request->isPost()) { 
            if ($form->isValid($this->_request->getPost())) {
                $user = Stoa_Model_User::authenticate(
                    $form->getValue('username'),
                    $form->getValue('password')
                );
                
                if ($user) { 
                    $this->_redirect('/');
                } else {
                    $this->view->message = new Geves_Message("Wrong user name or password");
                }
            }
        }
        
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        /** @todo: XSRF protection **/
        /* $token = $this->_getParam('requestToken'); */
        
        Stoa_Model_User::logoutCurrentUser();
        $this->_redirect('/');
    }
}
