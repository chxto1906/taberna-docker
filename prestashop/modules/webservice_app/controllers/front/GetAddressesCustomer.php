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
        $multiple_addresses = array();
        $addresses = $this->context->customer->getAddresses($this->context->language->id);
        if (!empty($addresses)) {
            foreach ($addresses as $detail) {
                $address = new Address($detail['id_address']);
                $multiple_addresses[$total]['id_shipping_address'] = $address->id;
                $multiple_addresses[$total]['firstname'] = $address->firstname;
                $multiple_addresses[$total]['lastname'] = $address->lastname;
                $multiple_addresses[$total]['mobile_no'] = (!empty($address->phone_mobile)) ?
                    $address->phone_mobile . "," . $address->phone : $address->phone . "," . $address->phone_mobile;
                $multiple_addresses[$total]['mobile_no'] = rtrim($multiple_addresses[$total]['mobile_no'], ',');
                $multiple_addresses[$total]['company'] = $address->company;
                $multiple_addresses[$total]['address_1'] = $address->address1;
                $multiple_addresses[$total]['address_2'] = $address->address2;
                $multiple_addresses[$total]['city'] = $address->city;
                if ($address->id_state != 0) {
                    $multiple_addresses[$total]['state'] = State::getNameById($address->id_state);
                } else {
                    $multiple_addresses[$total]['state'] = "";
                }
                $multiple_addresses[$total]['country'] = Country::getNameById(
                    $this->context->language->id,
                    $address->id_country
                );
                $multiple_addresses[$total]['postcode'] = $address->postcode;
                $multiple_addresses[$total]['alias'] = $address->alias;
                unset($address);
                ++$total;
            }
            $this->status_code = 200;
            //$this->content['default_address'] = '1';
        }else{
            http_response_code(204);
            exit;
        }
        $this->content = $multiple_addresses;
        
    }


    



}

