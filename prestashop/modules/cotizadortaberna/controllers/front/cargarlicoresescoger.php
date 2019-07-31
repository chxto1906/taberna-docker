<?php

require_once _PS_MODULE_DIR_ . 'cotizadortaberna/models/cargar_licores_para_escoger.php';

class cotizadortabernacargarlicoresescogerModuleFrontController extends ModuleFrontController{
	public function initContent()
		{
			

			parent::initContent();
			$name_category = Tools::getValue('name_category');
			$query = Tools::getValue('query');
			$licores = new Cargar_licores_para_escoger();
			if ($query){
				$licores = $licores->search($name_category,$query);
			} else {
				$licores = $licores->find($name_category);
			}

			echo json_encode($licores);

			exit;
		}
}
?>