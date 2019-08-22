<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

        
class Webservice_AppLoginModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        try {
            $response = new Response();
            $status_code = 200;
            
            $email = Tools::getValue("email");
            $password = Tools::getValue("password");

            $status_code = 400;
            $resultDecode = "Email y contraseña son obligatorios";

            if ($email && $password) {
                $resultDecode = "Email y contraseña incorrectos";                
                $status_code = 401;
                $customer = new Customer();

                $customer = $customer->getByEmail($email,$password);

                $address = new Address();
                $first_address = $address->getFirstCustomerAddressId($customer->id);

                //$address = $customer->getAddresses(1);
                //var_dump($address);
                if ($customer) {
                    $customer->id_address = $first_address ? $first_address : null;
                    $customer = $this->proccessCustomer($customer);
                    $status_code = 200;
                    $resultDecode = $customer;
                }
            }

            echo $response->json_response($resultDecode,$status_code);
        } catch (Exception $e) {
            echo $response->json_response($e->getMessage(),500);
        }

        exit;

    	$this->setTemplate('productos.tpl');
    }


    public function proccessCustomer($customer) {
        $categoriesResult = array();
        $dataResult = [
            "id"            => $customer->id,
            "secure_key"    => $customer->secure_key,
            "id_gender"     => $customer->id_gender,
            "last_name"     => $customer->lastname,
            "first_name"    => $customer->firstname,
            "birthday"      => $customer->birthday,
            "email"         => $customer->email,
            "id_address"    => $customer->id_address
        ];
        
        return (object) $dataResult;
    }


    /*public function checkUserPass($resultUser,$password) {

        $resultUser = json_decode($resultUser);
        $info = $resultUser->customers[0];
        $result = false;
        $salt = substr($info->passwd, strrpos($info->passwd, ':') + 1, 2);
        $ZCpassword = md5($this->COOKIE_KEY . $password) . ':' . $salt;

        // Check if password comparison is true or false
        if (password_verify($password, $info->passwd) == true)
            $result = true;
        return $result;
    }*/


}

