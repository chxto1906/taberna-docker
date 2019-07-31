<?php
	
	//require '../config/config.inc.php';
	//Dispatcher::getInstance()->dispatch();

	require_once _PS_MODULE_DIR_ . 'rvproductstab/rvproductstab.php';

	class Categories {

	    public static function listar($category_id) {
	    	$rvproductstab = new Rvproductstab();
	        $products = $rvproductstab->getFeaturedProducts(true);
	    }
	}
?>