<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
header('Content-Type: application/json');
        
class Webservice_AppUpdateProfileCustomerModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $status_code = 400;

    public function initContent() {
    	parent::initContent();
        $response = new Response();

        if (!$this->context->customer->logged){
            $this->content = ["message" => "Usuario no logueado"];
        } else {
            $this->prepareUpdateCustomerInfo();
        }

        echo $response->json_response($this->content,$this->status_code);
        exit;

    	$this->setTemplate('productos.tpl');
    }


    public function prepareUpdateCustomerInfo() {
        $user_data = (object) [
            "gender"        =>  Tools::getValue('gender', ''),
            "first_name"    =>  Tools::getValue('first_name', ''),
            "last_name"     =>  Tools::getValue('last_name', ''),
            "email"         =>  Tools::getValue('email', ''),
            "birthday"      =>  Tools::getValue('birthday', ''),
            "password"      =>  Tools::getValue('password', ''),
            "new_password"  =>  Tools::getValue('new_password','')
        ];
        $customer = new Customer((int) $this->context->customer->id);
        if ($user_data->password) {
            $authentication = $customer->getByEmail(trim($customer->email), trim($user_data->password));
            if ($authentication) {
                /*------------------------------*/
                if ($user_data->first_name) {
                    if (!Validate::isName($user_data->first_name)) {
                        $this->content = ["message" => 'Nombre inválido.'];
                        return;
                    }
                    $customer->firstname = ucwords($user_data->first_name);
                }
                if ($user_data->last_name) {
                    if (!Validate::isName($user_data->last_name)) {
                        $this->content = ["message" => 'Apellido inválido.'];
                        return;
                    }
                    $customer->lastname = ucwords($user_data->last_name);
                }
                if ($user_data->email) {
                    if (!Validate::isEmail($user_data->email)) {
                        $this->content = ["message" => 'Dirección de email inválida.'];
                        return;
                    }
                    if ($customer->email != $user_data->email){
                        if (Customer::customerExists(strip_tags($user_data->email))) {
                            $this->content = ["message" => "Dirección de email ya está registrada en nuestra tienda."];
                            return;
                        }
                        $customer->email = $user_data->email;
                    }
                }

                if ($user_data->new_password) {
                    if (!Validate::isPasswd($user_data->new_password)) {
                        $this->content = ["message" => 'Contraseña nueva ingresada no es válida.'];
                        return;
                    }
                    $passd = Tools::encrypt($user_data->new_password);
                    $customer->passwd = $passd;
                }

                if (!empty($user_data->birthday)) {
                    $dob = array();
                    $dob = explode("/", $user_data->birthday);
                    $customer->birthday = $dob[2] . "-" . $dob[1] . "-" . $dob[0];
                }
                if (!empty($user_data->gender)) {
                    $customer->id_gender = $user_data->gender;
                }
     
                $this->updateCustomerInfo($customer);
                /*------------------------------*/


            } else {
                $this->content = ["message" => 'Tu contraseña actual ingresada es incorrecta.'];
                $this->status_code = 401;
            }

        } else {
            $this->content = ["message" => 'Se necesita de tu contraseña actual para modificar datos.'];
            $this->status_code = 401;
        }

    }

    public function updateCustomerInfo($customer)
    {
        if ($customer->update(true)) {
            /* Changes over */
            $this->context->customer = $customer;
            $this->context->cookie->id_customer = (int) $customer->id;
            $this->context->cookie->customer_lastname = $customer->lastname;
            $this->context->cookie->customer_firstname = $customer->firstname;
            $this->context->cookie->passwd = $customer->passwd;
            $this->context->cookie->logged = 1;
            $this->context->cookie->email = $customer->email;
            $this->context->cookie->is_guest = $customer->is_guest;
            
            $this->content = ["message" => "Datos de cliente actualizados correctamente"];
            $this->status_code = 200;

        } else {
            $this->content = ["message" => "No se pudo actualizar datos del cliente"];
        }
        
    }




}

