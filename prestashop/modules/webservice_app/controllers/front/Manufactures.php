<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
        
class Webservice_AppManufacturesModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        try {
            $response = new Response();
            $status_code = 200;
            $manufacturer = new Manufacturer();
            $manufacturers = $manufacturer->getManufacturers(true);
            //var_dump($manufacturers);
            echo $response->json_response($manufacturers,$status_code);
            exit;
        } catch (Exception $e) {
            echo $response->json_response($e->getMessage(),500);
        }

    	$this->setTemplate('productos.tpl');
    }

    /*public function proccessCategories($categories) {
        $categoriesResult = array();
        $dataResult = [
            "id_category"    => null,
            "name"          => null,
            "description"   => null
        ];
        foreach ($categories as $category) {
            $dataResult["id_category"] = $category["id_category"]; 
            $dataResult["name"] = $category["name"];
            $dataResult["description"] = $category["description"];
            
            $categoriesResult[] = $dataResult;
        }
        return $categoriesResult;
    }*/


}

