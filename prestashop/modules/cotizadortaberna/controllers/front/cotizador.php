<?php
 
if (!defined('_PS_VERSION_'))
        exit;

class cotizadortabernacotizadorModuleFrontController extends ModuleFrontController   {
 

 	public function initContent() {
 
        //global $smarty; 
        parent::initContent();
        
        if ($this->context->customer->isLogged())
			$email = $this->context->customer->email;
		else
			$email = null;
		$this->context->smarty->assign(array(
					'email' => $email
				));
		$this->setTemplate('module:cotizadortaberna/views/templates/front/licores.tpl');
        
    }


 	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS($this->module->path .'css/style.css');
		$this->addJS($this->module->path .'views/js/jquery.tiny.min.js');
		Media::addJsDef(
                array(
                    'base_url' => _PS_BASE_URL_.__PS_BASE_URI__)
                );

		$this->addJS($this->module->path .'views/js/front.js');
	        /*(
	       	    'modules/cotizadortaberna/views/js/jquery.tiny.min.js',
	       	    'modules/cotizadortaberna/views/js/front.js',
	       	    'modules/cotizadortaberna/css/style.css'
	        );*/
	}

    

    
 
}
 
?>
