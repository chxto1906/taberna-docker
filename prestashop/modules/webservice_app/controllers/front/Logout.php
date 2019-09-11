<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

class Webservice_AppLogoutModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        $response = new Response();
        $status_code = 400;
        $resultDecode = ["message" => "No se pudo cerrar sesión"];
        
        if (Context::getContext()->customer->logged == true)
            $customer = new Customer((int) Context::getContext()->customer->id);
            $customer->logout();
            $status_code = 200;
            $resultDecode = ["message" => "OK"];
        }

        //echo $this->context;
        echo $response->json_response($resultDecode,$status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }

}

