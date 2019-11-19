<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
header('Content-Type: application/json');

class Webservice_AppLogoutModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        $operation = false;
        $response = new Response();
        $status_code = 400;
        $resultDecode = ["message" => "No se pudo cerrar sesión"];
        
        if (Context::getContext()->customer->logged == true){
            $customer = new Customer((int) Context::getContext()->customer->id);
            $customer->logout();
            $cart = new Cart((int) Context::getContext()->cart->id);
            $count_products = Cart::getNbProducts($cart->id);
            if ($count_products == 0) {
                $operation = $cart->delete();
            }else{
                $cart->valid_session = false;
                $operation = $cart->save();
            }
            if ($operation){
                $status_code = 200;
                $resultDecode = ["message" => "Sesión cerrada correctamente"];
            }
        }

        //echo $this->context;
        echo $response->json_response($resultDecode,$status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }

}

