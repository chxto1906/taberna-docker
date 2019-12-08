<?php
 
if (!defined('_PS_VERSION_'))
        exit;

class navidadcatalogocatalogoModuleFrontController extends ModuleFrontController   {
 

 	public function initContent() {
 
        //global $smarty; 
        parent::initContent();

        
		$this->setTemplate('module:navidadcatalogo/views/templates/front/catalogo.tpl');
        
    }

    /*
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
	}

    */

    
 
}
 
?>
