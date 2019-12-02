<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
header('Content-Type: application/json');
        
class Webservice_AppGetAddressesCustomerModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $product = null;
    private $status_code = 401;

    public function initContent() {
    	parent::initContent();
        
        $response = new Response();

        if (!$this->context->customer->logged){
            $this->content = ["message" => "Usuario no logueado"];
        } else {
            $this->getCustomerAddresses();
        }

        //$resultDecode = is_string($this->content) ? $this->content :(object) $this->content;

        echo $response->json_response($this->content,$this->status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }

    /**
     * Get list of addresses of customer
     *
     * @param string $email email address of the customer
     */
    public function getCustomerAddresses()
    {
        $total = 0;
        $items = array();
        $multiple_addresses = array();
        $id_address = Tools::getValue('id_address');
        if (!$id_address) {
            $addresses = $this->context->customer->getSimpleAddresses($this->context->language->id);
            if (!empty($addresses)) {
                foreach ($addresses as $detail) {
                    $address = new Address($detail['id']);
                    $items[] = $this->proccessAddress($address);
                    unset($address);
                    ++$total;
                }
                $this->status_code = 200;
            }else{
                http_response_code(204);
                exit;
            }
        } else {
            $address = new Address((int) $id_address);
            if ($address->id){
                $this->status_code = 200;
                $items = $this->proccessAddress($address);
                unset($address);
            }else{
                $this->status_code = 404;
                $this->content = ["message" => "Dirección no encontrada"];
                return;
            }
            
        }
        $this->content = $items;
        
    }


    public function proccessAddress($address) {
        return [
                "id_shipping_address"   =>  $address->id,
                "alias"                 =>  $address->alias,
                "type_dni"              =>  $address->type_dni,
                "dni"                   =>  $address->dni,
                "firstname"             =>  $address->firstname,
                "lastname"              =>  $address->lastname,
                "email"                 =>  $address->email,
                "company"               =>  $address->company,
                "address1"              =>  $address->address1,
                "address2"              =>  $address->address2,
                "postcode"              =>  $address->postcode,
                "city"                  =>  $address->city,
                "country_id"            =>  $address->id_country,
                "country"               =>  Country::getNameById(
                                                $this->context->language->id,
                                                $address->id_country
                                            ),
                "phone"                 =>  $address->phone,
                "latitude"              =>  $address->latitude,
                "longitude"             =>  $address->longitude,
                
            ];
    }



}

