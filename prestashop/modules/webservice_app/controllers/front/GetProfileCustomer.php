<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

        
class Webservice_AppGetProfileCustomerModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $status_code = 400;

    public function initContent() {
    	parent::initContent();
        $response = new Response();

        if (!$this->context->customer->logged){
            $this->content = ["message" => "Usuario no logueado"];
        } else {
            $this->getCustomerInfo();
        }

        echo $response->json_response($this->content,$this->status_code);
        exit;

    	$this->setTemplate('productos.tpl');
    }


    public function getCustomerInfo() {
        $customer = new Customer((int) $this->context->customer->id);
        if ($customer->id){
            $user_data = [
                "gender"        =>  $customer->id_gender,
                "first_name"    =>  $customer->firstname,
                "last_name"     =>  $customer->lastname,
                "email"         =>  $customer->email,
                "birthday"      =>  $this->processBirthday($customer->birthday)
            ];
            $this->content = $user_data;
            $this->status_code = 200;
        }
    }

    public function processBirthday($birthday=null) {
        $res = null;
        if ($birthday){
            $dob = array();
            $dob = explode("-", $birthday);
            $res = $dob[2] . "/" . $dob[1] . "/" . $dob[0];    
        }
        return $res;
    }


}

