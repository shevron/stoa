<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRequest(array $options = array())
    {
        // Ensure front controller instance is present, and fetch it
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController'); /* @var $front Zend_Controller_Front */

        // Set up routes
        $front->setDefaultControllerName('post');
        $front->getRouter()->addConfig(
            new Zend_Config(include APPLICATION_PATH . '/configs/routes.php')
        );
        
        // Initialize the request object
        $request = new Zend_Controller_Request_Http();

        // Add it to the front controller
        $front->setRequest($request);

        // Bootstrap will store this value in the 'request' key of its container
        return $request;
    }
    
    protected function _initView()
    {
        $this->bootstrap(array('request', 'session'));
        
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle()->setSeparator(' » ');

        // Save the base URL
        $view->baseUrl = $this->getResource('request')->getBaseUrl();
        
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);
        
        // Add some stylesheet
        $view->headLink()->appendStylesheet($view->baseUrl . '/css/default.css');

        $view->addHelperPath(APPLICATION_PATH . '/views/helpers', 'Stoa_View_Helper_');
        
        // Add the Zendx_Jquery helper path
        //$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
        //$view->jQuery()->addStylesheet($view->baseUrl . '/css/ui-lightness/jquery-ui-1.7.2.custom.css');
        
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
    
    protected function _initDb()
    {
        $dbConfig = $this->_options['couchdb'];
        Geves_Model_Object::setDbConfig($dbConfig);
        
        if ($dbConfig['validate']) {
            // Make sure the DB exists
            try {
                $db = Sopha_Db::createDb($dbConfig['dbname'], $dbConfig['hostname'], $dbConfig['port']);
            
            } catch (Sopha_Db_Exception $ex) {
                if ($ex->getCode() != 412) {
                    throw $ex;
                }
                $db = Geves_Model_Object::getDb();
            }

            // Load design documents and create them
            $views = require APPLICATION_PATH . '/configs/couchdb-views.php';
            if (! is_array($views)) {
                throw new ErrorException("Unable to configure database views: \$views is not properly defined");
            }
        
            foreach ($views as $view => $viewData) {
                try {
                    $db->create($viewData, array('_design', $view));
                    
                } catch (Sopha_Db_Exception $ex) {
                    if ($ex->getCode() == 409) { // document already exists
                        if ($dbConfig['replaceViews']) {
                            $view = $db->retrieve(array('_design', $view));
                            $view->fromArray($viewData);
                            $db->update($view);
                        }
                    } else {
                        throw $ex;
                    }
                }
            }
        }
    }
    
    protected function _initAutoloader()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Stoa_',
            'basePath'  => APPLICATION_PATH
        ));
        return $autoloader;
    }
    
    protected function _initConfig()
    {
        Zend_Registry::set('config', $this->_options);
    }

    /**
     * Initialize the session 
     * 
     * @return Zend_Session_Namespace
     */
    protected function _initSession()
    {
        $this->bootstrap(array('autoloader', 'request', 'config'));
        
        $sessionOpts = $this->getOption('session');
        if ($sessionOpts && isset($sessionOpts['name'])) { 
            $sessName = $sessionOpts['name']; 
        } else {
            $sessName = 'stoaSession';
        }
        
        Zend_Session::setOptions(array(
           'name'              => $sessName,
           'use_only_cookies'  => true,
           'cookie_path'       => $this->getResource('request')->getBaseUrl() 
        ));
            
        // We only start the session automatically if it was already started 
        // at some point
        if (isset($_COOKIE[$sessName])) {
            return new Zend_Session_Namespace('stoa');
        }
    }
}
