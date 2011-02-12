<?php

class LocationController extends Zend_Controller_Action
{
    public function init()
    {
        if (Zend_Layout::getMvcInstance()->getMvcEnabled()) {
            Zend_Layout::getMvcInstance()->disableLayout();
        }
        
        $this->_helper->viewRenderer->setNoRender();
    }
    
    public function addressAction()
    {
        $lat = $this->_getParam('lat');
        $lng = $this->_getParam('lng');
        
        if (! ($lat && $lng)) { 
            return $this->_outputJson(array(
                'status' => 'ERROR', 
                'error'  => "lat/lon parameters missing"
            ));
        }
        

        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true";
        try {
            $addrInfo = Zend_Json::decode(file_get_contents($url));
        } catch (Zend_Json_Exception $ex) {
            return $this->_outputJson(array(
                'status' => 'ERROR',
                'error'  => "unable to decode json response: {$ex->getMessage()}"
            ));
        }
        
        if (! (is_array($addrInfo) && isset($addrInfo['status']))) {
            return $this->_outputJson(array(
                'status' => 'ERROR',
                'error'  => "returned data does not match expected format"
            ));
        }
        
        if ($addrInfo['status'] == 'ZERO_RESULTS') { 
            return $this->_outputJson(array(
                'status' => 'ZERO_RESULTS',
            ));
        }
        
        if ($addrInfo['status'] == 'OK') {
            $matches = array(); 
            $types = array('country', 'locality', 'sublocality', 'neighborhood', 'colloquial_area', 'natural_feature', 'airport', 'park', 'point_of_interest');
            foreach($addrInfo['results'] as $result) {
                if (count(array_intersect($types, $result['types']))) { 
                    $matches[] = $result['formatted_address'];
                }
            }
            
            if (! empty($matches)) { 
                return $this->_outputJson(array(
                    'status' => 'OK',
                    'results' => $matches
                ));
            } else {
                return $this->_outputJson(array(
                    'status' => 'ZERO_RESULTS'
                ));
            }
        }
        
        return $this->_outputJson(array(
        	'status' => 'ERROR', 
        	'message' => "unexpected status from remote service: '{$addrInfo['status']}'")
        );
    }
    
    protected function _outputJson(array $data)
    {
        $this->_response->setHeader('Content-type', 'application/json');
        echo Zend_Json::encode($data);
        return;
    }
}