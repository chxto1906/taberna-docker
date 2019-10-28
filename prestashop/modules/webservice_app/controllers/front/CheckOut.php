<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

        
class Webservice_AppCheckOutModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $product = null;
    private $status_code = 401;
    private $img_1 = 'large';
    private $img_2 = 'medium';
    private $img_3 = '_default';

    public function initContent() {
    	parent::initContent();
        
        $response = new Response();
        
        /*var_dump(Module::getPaymentModules());
        exit;*/
        //die($this->context->cart->id_address_delivery);
        if (!$this->context->customer->logged){
            $this->content = ["message" => "Usuario no logueado"];
        } else {
            $this->getCheckOut();
        }

        //$resultDecode = is_string($this->content) ? $this->content :(object) $this->content;

        echo $response->json_response($this->content,$this->status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }


    public function getCheckOut() {
        if (!Validate::isLoadedObject($this->context->cart)) {
            $this->content = ["message" => "No fue posible cargar carrito."];
        } else {
            $id_shipping = Tools::getValue('id_shipping_address', '');
            if ($id_shipping == '') {
                if ($this->context->cart->id_address_delivery > 0) {
                    $id_shipping = $this->context->cart->id_address_delivery;
                } else {
                    $id_shipping = Address::getFirstCustomerAddressId(
                        (int) $this->context->cookie->id_customer
                    );
                }
            }
            $id_billing = Tools::getValue('id_billing_address', '');
            if ($id_billing == '') {
                if ($this->context->cart->id_address_invoice > 0) {
                    $id_billing = $this->context->cart->id_address_invoice;
                } else {
                    $id_billing = Address::getFirstCustomerAddressId(
                        (int) $this->context->cookie->id_customer
                    );
                }
            }
            
            if ($this->getShippingAddress($id_shipping)) {
                if ($this->getBillingAddress($id_billing)) {
                    $this->context->cart->id_currency = $this->context->currency->id;
                    $this->context->cart->id_carrier = 0;
                    if (Tools::getIsset('shipping_method')) {
                        $shipping_method = Tools::getValue('shipping_method');
                        $id_carrier = array(
                            $this->context->cart->id_address_delivery => $shipping_method . ','
                        );
                        $this->context->cart->setDeliveryOption($id_carrier);
                    }
                    /* Update cookie value after selecting shipping for particular product */
                    /*if (Tools::getIsset('pp_shippings')) {
                        $carriers_array = array();
                        $selected_carriers = Tools::getValue('pp_shippings', Tools::jsonEncode(array()));
                        $selected_carriers = Tools::jsonDecode($selected_carriers);
                        if (!empty($selected_carriers)) {
                            foreach ($selected_carriers as $data) {
                                $carriers_array[$data->product_id] = $data->shipping_id;
                            }
                            $this->context->cookie->kb_selected_carrier = serialize($carriers_array);
                        }
                    }*/


                    $this->context->cart->update();
                    $this->context->cookie->id_cart = (int) $this->context->cart->id;
                    $this->context->cookie->write();
                    //$this->content['checkout_page']['per_products_shipping'] = "0";
                    //$this->content['checkout_page']['per_products_shipping_methods'] = array();
                    $this->getKbCarrierList();
                    $cart_data = $this->fetchList();
                    /*Set currency code and cart total */
                    $this->content['total_cost'] = (float)Tools::ps_round(
                        (float)$this->context->cart->getOrderTotal(true, Cart::BOTH),
                        2
                    );
                    //$this->content['currency_code'] = $this->context->currency->iso_code;
                    //$this->content['currency_symbol'] = $this->context->currency->sign;
                    $cart_summary = $cart_data['summary'];
                    $customized_data = $cart_data['customized_data'];
                    $cart_products = array();
                    $index = 0;
                    foreach ($cart_summary['products'] as $product) {
                        $quantity_displayed = 0;
                        $extra_product_line = false;
                        $customization = false;
                        $product_obj = new Product(
                            (int) $product['id_product'],
                            true,
                            $this->context->language->id,
                            $this->context->shop->id
                        );
                        $cart_products[$index] = array(
                            'product_id' => $product_obj->id,
                            'name' => $product['name'],
                            'description' => $product['description'],
                            'stock' => StockAvailable::getQuantityAvailableByProduct(
                                    $product_obj->id,
                                    $product['id_product_attribute']
                                )
                        );
                        $cart_products[$index]['image_small'] = $this->context->link->getImageLink(
                            $product['link_rewrite'],
                            $product['id_image'],
                            $this->getImageType('small')
                        );
                        $cart_products[$index]['image_home'] = $this->context->link->getImageLink(
                            $product['link_rewrite'],
                            $product['id_image'],
                            $this->getImageType('home')
                        );
                        $cart_products[$index]['image_large'] = $this->context->link->getImageLink(
                            $product['link_rewrite'],
                            $product['id_image'],
                            $this->getImageType('large')
                        );
                        /* Changes over */
                        $p_id = $product['id_product'];
                        $p_aid = $product['id_product_attribute'];
                        $da_id = $product['id_address_delivery'];
                        $cart_products[$index]['price_tax_inc'] = $this->formatPrice($product['price_with_reduction']);
                        $cart_products[$index]['price_tax_exc'] = $this->formatPrice($product['price_with_reduction_without_tax']);                
                        $cart_products[$index]['quantity'] = $product['cart_quantity'];
                        $cart_products[$index]['reference'] = $product['reference'];
                        $cart_products[$index]['manufacturer_name'] = $product["manufacturer_name"];
                        $index++;
                    }
                    

                    $this->status_code = 200;
                    /*$this->content['status'] = "success";
                    $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                        Tools::getValue('iso_code', false),
                        $this->l('Cart information loaded successfully'),
                        'AppCheckout'
                    );
                    $this->writeLog("Cart information loaded successfully");*/

                    $this->content['products'] = $cart_products;

                    $cart_total_details = array();
                    $cart_total_details[] = array(
                        'name' => 'Total productos (impuestos excl.)',
                        'value' => $this->formatPrice($cart_summary['total_products'])
                    );
                    if ($cart_summary['total_discounts'] > 0) {
                        $cart_total_details[] = array(
                            'name' => 'Total descuentos',
                            'value' => "-" .$this->formatPrice($cart_summary['total_discounts'])
                        );
                    }
                    if ($cart_summary['total_wrapping'] > 0 && $this->context->cart->gift) {
                        $cart_total_details[] = array(
                            'name' => 'Total papel de regalo',
                            'value' => $this->formatPrice($cart_summary['total_wrapping'])
                        );
                    }
                    if ($cart_summary['total_shipping'] > 0) {
                        $cart_total_details[] = array(
                            'name' => 'Total envío',
                            'value' => $this->formatPrice($cart_summary['total_shipping'])
                        );
                    } else {
                        if (!$cart_summary['is_virtual_cart']) {
                            $cart_total_details[] = array(
                                'name' => 'Total envío',
                                'value' => 'Envío gratis'
                            );
                        }
                    }

                    $cart_total_details[] = array(
                        'name' => 'Tiempo entrega',
                        'value' => $this->context->cart->getOrderDeliveryTime()." mins. (máximo)"
                    );

                    if ($cart_summary['total_tax'] > 0) {
                        $cart_total_details[] = array(
                            'name' => 'Total impuestos',
                            'value' => $this->formatPrice($cart_summary['total_tax'])
                        );
                    }
                    $cart_total_details[] = array(
                        'name' => 'Precio Total',
                        'value' => $this->formatPrice($cart_summary['total_price'])
                    );

                    

                    


                    $currency = Currency::getCurrency((int) $this->context->cart->id_currency);
                    $minimal_purchase = Tools::convertPrice(
                        (float) Configuration::get('PS_PURCHASE_MINIMUM'),
                        $currency
                    );

                    if ($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) < $minimal_purchase) {
                        $this->content['minimum_purchase_message'] = 
                        'Se requiere una compra mínima de '.Tools::displayPrice($minimal_purchase, $currency).' para validar su pedido, el total actual es: '.Tools::displayPrice(
                                $this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS),
                                $currency
                            );
                    } else {
                        $this->content['minimum_purchase_message'] = "";
                    }

                    $this->content['totals'] = $cart_total_details;
                }
            }
        }
    }



    /**
     * Fetch cart summary
     *
     * @return array cart summary
     */
    public function fetchList()
    {
        $summary = $this->context->cart->getSummaryDetails();

        

        $cart_product_context = $this->context->cloneContext();
        foreach ($summary['products'] as $key => &$product) {
            $product['quantity'] = $product['cart_quantity']; // for compatibility with 1.2 themes

            if ($cart_product_context->shop->id != $product['id_shop']) {
                $cart_product_context->shop = new Shop((int) $product['id_shop']);
            }
            $null = '';
            $product['price_without_specific_price'] = Product::getPriceStatic(
                $product['id_product'],
                !Product::getTaxCalculationMethod(),
                $product['id_product_attribute'],
                6,
                null,
                false,
                false,
                1,
                false,
                null,
                null,
                null,
                $null,
                true,
                true,
                $cart_product_context
            );

            if (Product::getTaxCalculationMethod()) {
                $product['is_discounted'] = Tools::ps_round(
                    $product['price_without_specific_price'],
                    _PS_PRICE_COMPUTE_PRECISION_
                ) != Tools::ps_round(
                    $product['price'],
                    _PS_PRICE_COMPUTE_PRECISION_
                );
            } else {
                $product['is_discounted'] = Tools::ps_round(
                    $product['price_without_specific_price'],
                    _PS_PRICE_COMPUTE_PRECISION_
                ) != Tools::ps_round(
                    $product['price_wt'],
                    _PS_PRICE_COMPUTE_PRECISION_
                );
            }
        }

        /* Get available cart rules and unset the cart rules already in the cart */
        $available_cart_rules = CartRule::getCustomerCartRules(
            $this->context->language->id,
            (isset($this->context->cart->id_customer) ? $this->context->cart->id_customer : 0),
            true,
            true,
            true,
            $this->context->cart,
            false,
            true
        );
        $cart_cart_rules = $this->context->cart->getCartRules();
        foreach ($available_cart_rules as $key => $available_cart_rule) {
            foreach ($cart_cart_rules as $cart_cart_rule) {
                if ($available_cart_rule['id_cart_rule'] == $cart_cart_rule['id_cart_rule']) {
                    unset($available_cart_rules[$key]);
                    continue 2;
                }
            }
        }

        unset($summary['delivery']);
        unset($summary['delivery_state']);
        unset($summary['invoice']);
        unset($summary['invoice_state']);
        unset($summary['formattedAddresses']);

        return array('summary' => $summary);
    }



    /**
     * Get list of available carriers on store
     *
     */
    public function getKbCarrierList()
    {
        $delivery_option_list = $this->context->cart->getDeliveryOptionList();
        $delivery_option = $this->context->cart->getDeliveryOption(null, false, false);
        $is_virtual_cart = $this->context->cart->isVirtualCart();
        $free_shipping = false;
        foreach ($this->context->cart->getCartRules() as $rule) {
            if ($rule['free_shipping'] && !$rule['carrier_restriction']) {
                $free_shipping = true;
                break;
            }
        }
        $carrier_array = array();
        if ($is_virtual_cart) {
            $this->content['shipping_available'] = "1";
            $this->content['shipping_message'] = 'No se requiere método de envío';
            $this->content['shipping_methods'] = array();
        } else {
            $index = 0;
            foreach ($delivery_option_list as $id_address => $option_list) {
                foreach ($option_list as $key => $option) {
                    if (isset($delivery_option[$id_address]) && $delivery_option[$id_address] == $key) {
                        $this->content['default_shipping'] = rtrim($key, ",");
                    }
                    if ($option['unique_carrier']) {
                        foreach ($option['carrier_list'] as $carrier) {
                            $carrier_array[$index]['name'] = $carrier['instance']->name;
                        }
                    } elseif (!$option['unique_carrier']) {
                        $carrier_name = '';
                        foreach ($option['carrier_list'] as $carrier) {
                            $carrier_name .= $carrier['instance']->name . "&";
                        }
                        $carrier_array[$index]['name'] = rtrim($carrier_name, "&");
                    }
                    if ($option['total_price_with_tax']
                        && (isset($option['is_free'])
                        && $option['is_free'] == 0)
                        && !$free_shipping) {
                        $carrier_array[$index]['price'] = $this->formatPrice($option['total_price_with_tax']);
                    } else {
                        $carrier_array[$index]['price'] = "Gratis";
                    }
                    if ($option['unique_carrier']
                        && isset($carrier['instance']->delay[$this->context->language->id])) {
                        $lang_id = $this->context->language->id;
                        $carrier_array[$index]['delay_text'] = $carrier['instance']->delay[$lang_id];
                    } else {
                        $carrier_array[$index]['delay_text'] = "";
                    }
                    $carrier_array[$index]['code'] = rtrim($key, ",");
                    $index++;
                }
            }
            //start: added by aayushi on 1 Nov 2018 to not to allow transport methods which are disabled in module
            $updated_carrier_array = array();
            $disabled_carrier_array_list = Tools::unSerialize(Configuration::get('KB_DISABLED_SHIPPING'));
            if (!empty($disabled_carrier_array_list)) {
                foreach ($carrier_array as $key => $value) {
                    if (!in_array($value['code'], $disabled_carrier_array_list)) {
                        $updated_carrier_array[] = $carrier_array[$key];
                    }
                }
            } else {
                $updated_carrier_array = $carrier_array;
            }
            //die(print_r($updated_carrier_array));
            //end: added by aayushi on 1 Nov 2018 to not to allow transport methods which are disabled in module
            if (empty($carrier_array)) {
                $this->content['shipping_available'] = "0";
                $this->content['shipping_message'] = 'No hay método de entrega disponible';
                $this->content['shipping_methods'] = array();
            } else {
                $this->content['shipping_available'] = "1";
                $this->content['shipping_message'] = "";
                //$this->content['checkout_page']['shipping_methods'] = $carrier_array;
                //start: added by aayushi on 1 Nov 2018 to not to allow transport methods which are disabled in module
                $this->content['shipping_methods'] = $updated_carrier_array;
                //end: added by aayushi on 1 Nov 2018 to not to allow transport methods which are disabled in module
            }
        }
    }




    /**
     * Set the selected billing address to cart context and set the address details in billing_address paramter
     *
     * @param int $id_billing billing addres id
     * @return bool
     */
    public function getBillingAddress($id_billing)
    {
        $address = new Address($id_billing);
        if (!validate::isLoadedObject($address)) {
            $this->content['status'] = 'failure';
            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                Tools::getValue('iso_code', false),
                $this->l('Unable to get Billing address details'),
                'AppCheckout'
            );
            $this->writeLog('Address object is not valid');
            return false;
        } else {
            $this->context->cart->id_address_invoice = (int) $id_billing;
            $this->context->cart->autosetProductAddress();
            CartRule::autoRemoveFromCart($this->context);
            CartRule::autoAddToCart($this->context);

            if (!$this->context->cart->update()) {
                $this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('An error occurred while updating your cart.'),
                    'AppCheckout'
                );
                $this->writeLog('An error occurred while updating your cart.');
                return false;
            }

            $billing_address = array();
            $billing_address['id_shipping_address'] = $address->id;
            $billing_address['firstname'] = $address->firstname;
            $billing_address['lastname'] = $address->lastname;
            $billing_address['mobile_no'] = (!empty($address->phone_mobile)) ?
                $address->phone_mobile . "," . $address->phone : $address->phone . "," . $address->phone_mobile;
            $billing_address['mobile_no'] = rtrim($billing_address['mobile_no'], ',');
            $billing_address['company'] = $address->company;
            $billing_address['address_1'] = $address->address1;
            $billing_address['address_2'] = $address->address2;
            $billing_address['city'] = $address->city;
            if ($address->id_state != 0) {
                $billing_address['state'] = State::getNameById($address->id_state);
            } else {
                $billing_address['state'] = "";
            }
            $billing_address['country'] = Country::getNameById(
                $this->context->language->id,
                $address->id_country
            );
            $billing_address['postcode'] = $address->postcode;
            $billing_address['alias'] = $address->alias;
            $this->content['billing_address'] = $billing_address;
            return true;
        }
    }



    /**
     * Set the selected address to cart context and set the address details in shipping_address paramter
     *
     * @param int $id_shipping shipping address id
     */
    public function getShippingAddress($id_shipping)
    {
        $address = new Address($id_shipping);
        if (!validate::isLoadedObject($address)) {
            $this->status_code = 400;
            $this->content['message'] = 'No se pueden obtener detalles de la dirección de envío';
            return false;
        } else {
            $this->context->cart->id_address_delivery = (int) $id_shipping;
            $this->context->cart->autosetProductAddress();
            CartRule::autoRemoveFromCart($this->context);
            CartRule::autoAddToCart($this->context);

            if (!$this->context->cart->update()) {
                $this->status_code = 400;
                $this->content['message'] = 'Se produjo un error al actualizar su carrito.';
                return false;
            }

            if (!$this->context->cart->isMultiAddressDelivery()) {
                $this->context->cart->setNoMultishipping();
            }
            $errors = array();
            $address_without_carriers = $this->context->cart->getDeliveryAddressesWithoutCarriers(false, $errors);
            if (count($address_without_carriers) && !$this->context->cart->isVirtualCart()) {
                $flag_error_message = false;
                foreach ($errors as $error) {
                    if ($error == Carrier::SHIPPING_WEIGHT_EXCEPTION && !$flag_error_message) {
                        $this->status_code = 400;
                        $this->content['message'] = 'La selección de productos no puede ser entregada por los transportistas disponibles: '.' Es muy pesado. Modifique su carrito para reducir su peso';
                        $flag_error_message = true;
                    } elseif ($error == Carrier::SHIPPING_PRICE_EXCEPTION && !$flag_error_message) {
                        $this->status_code = 400;
                        $this->content['message'] = 'La selección del producto no puede ser entregada por los transportistas disponibles. Por favor modifique su carrito.';
                        $flag_error_message = true;
                    } elseif ($error == Carrier::SHIPPING_SIZE_EXCEPTION && !$flag_error_message) {
                        $this->status_code = 400;
                        $this->content['message'] = 'La selección de productos no puede ser entregada por los transportistas disponibles:  su tamaño no encaja. Modifique su carrito para reducir su tamaño.';
                        $flag_error_message = true;
                    }
                }
                if (count($address_without_carriers) > 1 && !$flag_error_message) {
                    $this->status_code = 400;
                    $this->content['message'] = 
                    'No hay transportistas que envíen a algunas direcciones que seleccionó.';
                    return false;
                } elseif ($this->context->cart->isMultiAddressDelivery() && !$flag_error_message) {
                    $this->status_code = 400;
                    $this->content['message'] = 'No hay transportistas que entreguen a una dirección que seleccionó.';
                    return false;
                } elseif (!$flag_error_message) {
                    $this->status_code = 400;
                    $this->content['message'] = 'No hay transportistas que entreguen a la dirección que seleccionó.';
                    return false;
                }
            }
            $shipping_address = array();
            $shipping_address['id_shipping_address'] = $address->id;
            $shipping_address['firstname'] = $address->firstname;
            $shipping_address['lastname'] = $address->lastname;
            $shipping_address['mobile_no'] = (!empty($address->phone_mobile)) ?
                $address->phone_mobile . "," . $address->phone : $address->phone . "," . $address->phone_mobile;
            $shipping_address['mobile_no'] = rtrim($shipping_address['mobile_no'], ',');
            $shipping_address['company'] = $address->company;
            $shipping_address['address_1'] = $address->address1;
            $shipping_address['address_2'] = $address->address2;
            $shipping_address['city'] = $address->city;
            if ($address->id_state != 0) {
                $shipping_address['state'] = State::getNameById($address->id_state);
            } else {
                $shipping_address['state'] = "";
            }
            $shipping_address['country'] = Country::getNameById(
                $this->context->language->id,
                $address->id_country
            );
            $shipping_address['postcode'] = $address->postcode;
            $shipping_address['alias'] = $address->alias;
            $this->content['shipping_address'] = $shipping_address;
            return true;
        }
    }


    /*
     * To get the type of image
     * 
     * @param string $type type of image large/medium/default
     * @return string 
     */
    public function getImageType($type = 'large')
    {
        if ($type == 'large') {
            return $this->img_1 . $this->img_3;
        } elseif ($type == 'medium') {
            return $this->img_2 . $this->img_3;
        } else {
            return $this->img_1 . $this->img_3;
        }
    }


    /*
     * Function to format price in API's
     * 
     * @param float $price
     * @param object $curr
     * @return string formated price
     */
    public function formatPrice($price, $curr = '')
    {
        return Tools::displayPrice(
            $price,
            $this->context->currency,
            false,
            $this->context
        );
    }


}

