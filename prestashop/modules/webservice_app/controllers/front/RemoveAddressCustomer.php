<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

        
class Webservice_AppRemoveAddressCustomerModuleFrontController extends ModuleFrontController {

    public $log = null;
    private $status_code = 400;

    public function initContent() {
    	parent::initContent();
        
        $response = new Response();

        if (!$this->context->customer->logged){
            $this->content = ["message" => "Usuario no logueado"];
        } else {
            $id_address = Tools::getValue('id_address', null);
            if ($id_address)
                $this->removeAddress($id_address);
            else
                $this->content = ["message" => "Se necesita el identificador de la direccion a eliminar"];
        }

        echo $response->json_response($this->content,$this->status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }


    public function removeAddress($id_address){
        $address = new Address((int) $id_address);   
        if ($address->id_customer == $this->context->customer->id) {
            $id = $address->id;
            $ok = $address->delete();

            if ($ok) {
                if ($this->context->cart->id_address_invoice == $id) {
                    unset($this->context->cart->id_address_invoice);
                }
                if ($this->context->cart->id_address_delivery == $id) {
                    unset($this->context->cart->id_address_delivery);
                    $this->context->cart->updateAddressId(
                        $id,
                        Address::getFirstCustomerAddressId($this->context->customer->id)
                    );
                }
                $this->status_code = 200;
                $this->content = ["message" => "Dirección eliminada correctamente"];
            } else {
                $this->content = ["message" => "No se pudo eliminar dirección"]; 
            }   
        } else {
            $this->status_code = 401;
            $this->content = ["message" => "Dirección no pertenece al cliente en sesión. Imposible eliminarla."];
        }

    }
    

}

