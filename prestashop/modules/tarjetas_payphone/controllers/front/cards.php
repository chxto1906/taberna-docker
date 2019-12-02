<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
include_once _PS_ROOT_DIR_."/classes/form/ValidateCedulaEcuador.php";

        
class Tarjetas_payphoneCardsModuleFrontController extends ModuleFrontController {

    public $log = null;
    private $status_code = 400;
    private $datos = null;

    public function initContent() {
        parent::initContent();
    
        exit;

        $this->setTemplate('productos.tpl');
    }

    public function postProcess() {
        $response = new Response();
        $isValidRequiredParams = $this->isValidRequiredParams();

        if (!$this->context->customer->logged) {
            $this->content = "Usuario no logueado";
        } else {
            if ($isValidRequiredParams) {
                $this->processSubmitKbAddress($isValidRequiredParams);
            } else {
                $this->content = ["message" => "Faltan parámetros que son obligatorios para agregar una dirección."];
            }
        }

        echo $response->json_response($this->content,$this->status_code);
    }


    public function isValidRequiredParams() {

        $response = null;
        if (Tools::getIsset("datos")) {
            $datos = Tools::getIsset("datos","");
            if ($datos)
            if (Tools::getValue("datos"))
            
        }
        

        return $response;
    }


    /**
     * Set submitted value to an address object
     *
     */
    public function processSubmitKbAddress($address_data)
    {
        //$address_data = Tools::getValue('shipping_address', Tools::jsonEncode(array()));
        //$address_data = Tools::jsonDecode($address_data);
        if (!empty($address_data)) {
            $id_address = Tools::getValue('id_shipping_address', 0);
            $address = new Address((int) $id_address);
            $data = $address_data;
            $address->alias = $data->alias;
            $address->type_dni = $data->type_dni;
            $address->dni = $data->dni;
            $address->firstname = $data->firstname;
            $address->lastname = $data->lastname;
            $address->email = $data->email;
            $address->company = $data->company;
            $address->address1 = $data->address1;
            $address->address2 = $data->address2;
            $address->postcode = $data->postcode;
            $address->city = $data->city;
            $address->id_country = $data->country_id;
            $address->country = $data->country;
            $address->phone = $data->phone;
            $address->latitude = $data->latitude;
            $address->longitude = $data->longitude;

            if ($this->validateKbPostAddress($address)) {
                /* If we edit this address, delete old address and create a new one */
                if (Validate::isLoadedObject($address)) {
                    $address_old = $address;
                    if (Customer::customerHasAddress($this->context->customer->id, (int) $address_old->id)) {
                        $address->id = (int) $address_old->id;
                        $address->date_add = $address_old->date_add;
                    }
                }


                /* Save address */
                if ($address->save()) {
                    /* update guest customer firstname and lastname */
                    /*if ($this->context->cookie->is_guest == 1) {
                        if ($this->context->cookie->id_customer) {
                            $customer = new Customer((int) $this->context->cookie->id_customer);
                            $customer->firstname = $address->firstname;
                            $customer->lastname = $address->firstname;
                            $customer->update('true');
                        }
                    }*/
                    /* Update id address of the current cart if necessary */
                    if (isset($address_old) && $address_old->isUsed()) {
                        $this->context->cart->updateAddressId($address_old->id, $address->id);
                    } else {
                        /* Update cart address */
                        $this->context->cart->autosetProductAddress();
                    }

                    if (Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                        $this->context->cart->id_address_invoice = (int) $address->id;
                    }

                    if ($id_address != 0) {
                        $this->content = ["message" => "Dirección ha sido actualizada correctamente."];
                        $this->status_code = 200;
                    } else {
                        $this->content = ["message" => "Dirección ha sido creada correctamente."];
                        $this->status_code = 201;
                    }
                    $this->content['cart_id'] = $this->context->cart->id;
                    $addresses = $this->context->customer->getAddresses($this->context->language->id);
                    $this->content['shipping_address_count'] = count($addresses);
                    $this->context->cart->id_currency = $this->context->currency->id;
                    $this->context->cart->update();
                    $this->context->cookie->id_cart = (int) $this->context->cart->id;
                    $this->context->cookie->write();

                    $this->content['id_shipping_address'] = (int) $address->id;
                } else {
                    $this->content = ["message" => "Ha ocurrido un error al momento de guardar dirección"];
                }
            }
        }
    }


    /**
     * @param string $dni Cedula to validate
     *
     * @return bool
     */
    public static function isCedula($cedula)
    {
        $validate = new ValidarIdentificacion();
        return $validate -> validarCedula($cedula);
    }

    /**
     * @param string $dni Cedula to validate
     *
     * @return bool
     */
    public static function isRuc($ruc)
    {
        $validate = new ValidarIdentificacion();
        $resValidate = $validate -> validarRucPersonaNatural($ruc);
        if ($resValidate == false)
            $resValidate = $validate -> validarRucSociedadPrivada($ruc);
            if ($resValidate == false){
                $resValidate = $validate -> validarRucSociedadPublica($ruc);
            }
        return $resValidate;
    }


    /**
     * Validate address object
     *
     * @param object $address address object
     * @return bool
     */
    public function validateKbPostAddress(&$address)
    {
        $errors = false;
        //$errors = $address->validateController();
        $address->id_customer = (int) $this->context->customer->id;
        if ($address->id_country) {
            /* Check country */
            if (!($country = new Country($address->id_country)) || !Validate::isLoadedObject($country)) {
                $this->content = ["message" => "No se ha encontrado el país"];
                $errors = true;
            }

            if ((int) $country->contains_states && !(int) $address->id_state) {
                $this->content = ["message" => "País requiere seleccionar estado."];
                $errors = true;
            }

            if (!$country->contains_states) {
                $address->id_state = 0;
            }

            if (!$country->active) {
                $this->content = ["message" => 'País no está activado'];
                $errors = true;
            }

            $is_valid = true;
            if ($address->type_dni == "Ruc"){
                $is_valid = $this->isRuc($address->dni);
                if (!$is_valid){
                    $this->content = ["message" => "Ruc no válido"];
                    $errors = true;
                }
            } elseif ($address->type_dni == "Cédula") {
                //$is_valid = $this->isCedula($dni);
                if (!$this->isCedula($address->dni)){
                    $this->content = ["message" => "Cédula no válida"];
                    $is_valid = false;
                    $errors = true;
                }
            }


            $postcode = $address->postcode;
            /* Check zip code format */
            if ($country->zip_code_format && !$country->checkZipCode($postcode)) {
                $this->content = ["message" => 'Formato del código postal es incorrecto: Ejm: EC010203'];
                $errors = true;
            } elseif (empty($postcode) && $country->need_zip_code) {
                $this->content = ["message" => 'Código postal es requerido'];
                $errors = true;
            } elseif ($postcode && !Validate::isPostCode($postcode)) {
                $this->content = ["message" => 'Código postal es inválido'];
                $errors = true;
            }

            /* Check country DNI */
            /*if ($country->isNeedDni() && (!$address->dni || !Validate::isDniLite($address->dni))) {
                $this->content = "El número de identificación es incorrecto o ya está siendo utilizada.";
            } elseif (!$country->isNeedDni()) {
                $address->dni = null;
            }*/
        }
        /* Check if the alias exists */
        if (!$this->context->customer->is_guest && !empty($address->alias) && (int) $this->context->customer->id > 0) {
            if (isset($address->id) && !empty($address->id)) {
                $id_address = $address->id;
            } else {
                $id_address = 0;
            }

            if (Address::aliasExist($address->alias, (int) $id_address, (int) $this->context->customer->id)) {
                $this->content = ["message" => "Alias ya está siendo utilizado, ingreso otro."];
                $errors = true;
            }
        }
        /* Don't continue this process if we have errors ! */
        if ($errors) {
            return false;
        } else {
            return true;
        }
    }



}

