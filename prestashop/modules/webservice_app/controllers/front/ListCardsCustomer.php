<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

class Webservice_AppListCardsCustomerModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
        parent::initContent();
        $response = new Response();
        $status_code = 401;
        
        $resultDecode = ["message" => "Usuario no logueado"];
        //http_response_code(204);
        //exit;

        if (Context::getContext()->customer->logged == true){
            $cards = $this->getCardsCustomer();
            if (!$cards){
                http_response_code(204);
                exit;
            }else{
                $status_code = 200;
                $resultDecode = $cards;
            }
        }

        //echo $this->context;
        echo $response->json_response($resultDecode,$status_code);

        exit;

        $this->setTemplate('productos.tpl');
    }

    public function getCardsCustomer() {
        $id_customer = $this->context->customer->id;
        $cards = Db::getInstance()->executeS('
        SELECT * 
        FROM '._DB_PREFIX_.'credit_card_tokens
        WHERE id_customer ='. $id_customer);

        return $cards;
    }

}

