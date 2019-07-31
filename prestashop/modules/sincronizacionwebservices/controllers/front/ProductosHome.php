<?php

require_once _PS_MODULE_DIR_ . 'rvproductstab/rvproductstab.php';
        
class sincronizacionwebservicesProductosHomeModuleFrontController extends ModuleFrontController {

    public $log = null;

    public function initContent() {
    	parent::initContent();

        $rvproductstab = new Rvproductstab();
        $products = [];

        $type = Tools::getValue('type');

        if ($type == "mejorvendidos") {
            $products = $rvproductstab->getBestSellers(true);
        } elseif ($type == "recienllegados") {
            $products = $rvproductstab->getNewProducts(true);
        } elseif ($type == "destacados") {
            $products = $rvproductstab->getFeaturedProducts(true);
        }

        if (!$products) {
            $products = [];
        }

        echo json_encode($products);

    	exit;
    	$this->setTemplate('productos.tpl');
    }


}

