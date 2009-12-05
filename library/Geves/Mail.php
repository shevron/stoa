<?php

class Geves_Mail extends Zend_Mail
{
	/**
	 * Layout object
	 * 
	 * @var Zend_Layout
	 */
	protected $_layout;
	
	/**
	 * Template directory
	 * 
	 * @var string
	 */
	protected $_templateDir;
	
	/**
	 * Set a layout for this HTML file
	 * 
	 * @param  string $layoutFile
	 * @return Geves_Mail
	 */
	public function setLayout($layoutFile)
	{
        $this->_layout = new Zend_Layout();
        $this->_layout->setLayout($layoutFile);
        return $this;
	}
	
	/**
	 * Set the template directory
	 * 
	 * @param  string $dir
	 * @return Manto_Mail
	 */
	public function setScriptPath($dir)
	{
		$this->_templateDir = $dir;
		return $this;
	}
	
	/**
	 * Set the e-mail's body from a template
	 * 
	 * @param  string $tempalte
	 * @param  array  $params
	 * @return Geves_Mail
	 */
	public function fromTemplate($template, array $params)
	{
		$view = new Zend_View();
		$view->setScriptPath($this->_templateDir);
		$view->assign($params);
		
		if ($this->_layout) {
			$this->_layout->content = $view->render($template . '.phtml');
			$html = $this->_layout->render();
		} else {
			$html = $view->render($template . '.phtml');
		}
		
		$this->setBodyHtml($html);
		$this->setBodyText(strip_tags($html));
		
		return $this;
	}
}