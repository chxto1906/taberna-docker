<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

        
class Webservice_AppForgotPasswordModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $product = null;
    private $status_code = 400;

    public function initContent() {
    	parent::initContent();
        
        $response = new Response();

        $email = Tools::getValue("email");

        if ($email) {
            $this->proccessForgotPassword($email);
        }else{
            $this->content = ["message" => "No se ha ingresado el email"];
        }

        //$resultDecode = is_string($this->content) ? $this->content :(object) $this->content;

        echo $response->json_response($this->content,$this->status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }

    public function proccessForgotPassword($email) {
        if (!($email = trim(Tools::getValue('email'))) || !Validate::isEmail($email)) {
            $this->content = ["message" => "Email no válido"];
            return;
        } else {
            $customer = new Customer();
            $customer->getByemail($email);
            $min_time = (int) Configuration::get('PS_PASSWD_TIME_FRONT');
            if (!Validate::isLoadedObject($customer)) {
                $this->content = ["message" => "No existe una cuenta registrada con ese email"];
                return;
            } elseif (!$customer->active) {
                $this->content = ["message" => "No puedes recuperar la contraseña de esta cuenta"];
                return;
            } elseif ((strtotime($customer->last_passwd_gen . '+' . ($min_time) . ' minutes') - time()) > 0) {
                $this->content = ["message" => "Puedes generar tu contraseña solo cada ".$min_time." minuto(s)"];
                return;
            } else {
                $customer->stampResetPasswordToken();
                $customer->update();

                $mail_params = array(
                    '{email}' => $customer->email,
                    '{lastname}' => $customer->lastname,
                    '{firstname}' => $customer->firstname,
                    '{url}' => $this->context->link->getPageLink(
                        'password',
                        true,
                        null,
                        'token=' . $customer->secure_key . '&id_customer=' . (int) $customer->id . '&reset_token=' . $customer->reset_password_token
                    )
                );
                if (Mail::Send(
                    $this->context->language->id,
                    'password_query',
                    Mail::l('Password query confirmation'),
                    $mail_params,
                    $customer->email,
                    $customer->firstname . ' ' . $customer->lastname
                )) {

                    $this->content = ["message" => "Se ha enviado un correo de confirmación a su dirección: ".$customer->email];
                    $this->status_code = 200;
                    return;
                } else {

                    $this->content = ["message" => "Ha ocurrido un error al enviar el email"];
                    return;
                }
            }
        }
    }



}

