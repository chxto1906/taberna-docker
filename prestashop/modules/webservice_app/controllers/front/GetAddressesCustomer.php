<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

        
class Webservice_AppGetAddressesCustomerModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $product = null;
    private $status_code = 401;

    public function initContent() {
    	parent::initContent();
        
        $response = new Response();

        if (!$this->context->customer->logged){
            $this->content = "Usuario no logueado";
        } else {
            $this->getCustomerAddresses();
        }

        $resultDecode = is_string($this->content) ? $this->content :(object) $this->content;

        echo $response->json_response($resultDecode,$this->status_code);

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
        $addresses = $this->context->customer->getAddresses($this->context->language->id);
        if (!empty($addresses)) {
            foreach ($addresses as $detail) {
                $address = new Address($detail['id_address']);
                $multiple_addresses['id_shipping_address'] = $address->id;
                $multiple_addresses['alias'] = $address->alias;
                $multiple_addresses['type_dni'] = $address->type_dni;
                $multiple_addresses['dni'] = $address->dni;
                $multiple_addresses['firstname'] = $address->firstname;
                $multiple_addresses['lastname'] = $address->lastname;
                $multiple_addresses['email'] = $address->email;
                $multiple_addresses['company'] = $address->company;
                $multiple_addresses['address_1'] = $address->address1;
                $multiple_addresses['address_2'] = $address->address2;
                $multiple_addresses['postcode'] = $address->postcode;
                $multiple_addresses['city'] = $address->city;
                $multiple_addresses['phone'] = $address->phone;
                $multiple_addresses['latitude'] = $address->latitude;
                $multiple_addresses['longitude'] = $address->longitude;

                $multiple_addresses['country'] = Country::getNameById(
                    $this->context->language->id,
                    $address->id_country
                );
                $items[] = $multiple_addresses;
                
                unset($address);
                ++$total;
            }
            $this->status_code = 200;
            //$this->content['default_address'] = '1';
        }else{
            http_response_code(204);
            exit;
        }
        $this->content = $items;
        
    }


    



}

