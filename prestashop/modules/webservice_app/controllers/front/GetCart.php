<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';

        
class Webservice_AppGetCartModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $product = null;
    private $status_code = 400;
    private $img_1 = 'large';
    private $img_2 = 'medium';
    private $img_3 = '_default';

    public function initContent() {
    	parent::initContent();
        
        $response = new Response();
        $log = new LoggerTools();
        /*$session_obj = $this->isSession();



        if ($session_obj){
            $cart_id = $session_obj->cart_id;
            $customer_id = $session_obj->customer_id;
            $customer = new Customer((int) $customer_id);
            //Context::updateCustomer($customer);
            $this->context->customer = $customer;
            $this->context->customer->save();
            $this->context->cookie->id_customer = $customer_id;
            $this->context->cookie->write();
        }else{*/
            $cart_id = (int) Tools::getValue('cart_id', '');
            if (isset($this->context->cart->id)) {
                $cart_id = (int) $this->context->cart->id;
            }
            /*if ($this->context->customer->id){
                $customer = new Customer((int) $this->context->customer->id);
                $customer->logout();
                $this->context->customer = null;
            }
        }*/

        

        if (!(int) $cart_id) {
            $this->status_code = 404;
            $this->content = ["message" => "Carrito no encontrado"];
        } else {
            if (Tools::getIsset('order_id')) {
                $oldCart = new Cart((int) $cart_id);
                $duplication = $oldCart->duplicate();
                if (!$duplication) {
                    $this->content = ["message" => "No se pudo renovar la orden"];
                } elseif (!$duplication['success']) {
                    $this->content = ["message" => "Algunos ítems ya no están disponibles y no podemos renovar la orden"];
                } else {
                    $this->context->cart = $duplication['cart'];
                    $this->context->cookie->id_cart = $duplication['cart']->id;
                    $this->context->cookie->write();
                    $this->getCartData();
                }
            } else {
                $this->context->cart = new Cart(
                    (int) $cart_id,
                    false,
                    null,
                    null,
                    $this->context
                );
                $this->getCartData();
            }
        }
        /*$this->content['install_module'] = '';
        return $this->fetchJSONContent();*/

        /**********************************/

        //$resultDecode = is_string($this->content) ? $this->content :(object) $this->content;
        $log->add('***Response GET CART***');
        $log->add(implode(", ",$this->content));
        $log->add("total_cart_items: ".$this->content["cart"]["total_cart_items"]);
        echo $response->json_response($this->content,$this->status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }


    /**
     * Validate Cart and get its data
     */
    public function getCartData()
    {
        if (!Validate::isLoadedObject($this->context->cart)) {
            $this->status_code = 404;
            $this->content = ["message" => "Carrito no encontrado"];
        } else {
            $this->status_code = 200;
            $this->context->cart->autosetProductAddress();
            $this->context->cart->update();
            $this->context->cookie->id_cart = (int) $this->context->cart->id;
            $this->context->cookie->write();

            if (isset($this->context->customer->id)
                && $this->context->customer->id
                && isset($this->context->cart->id_customer)
                && $this->context->cart->id_customer) {
                if ($this->context->cart->id_customer != $this->context->customer->id) {
                    $customer = new Customer($this->context->cart->id_customer);
                    $this->context->customer = $customer;
                }
            }
            $this->context->cart->id_customer = (int) $this->context->customer->id;
            $this->context->cart->secure_key = $this->context->customer->secure_key;
            $this->context->cart->update();
            CartRule::autoAddToCart();
            $this->context->cart->update();
            if ($this->context->cart->isVirtualCart()) {
                $this->context->cart->gift = 0;
                $this->context->cart->update();
            }

            /********/
            $cart_data = $this->fetchList();
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

            $this->content['total_cart_items'] = Cart::getNbProducts($this->context->cart->id);
            $currency = Currency::getCurrency((int) $this->context->cart->id_currency);
            $minimal_purchase = Tools::convertPrice((float) Configuration::get('PS_PURCHASE_MINIMUM'), $currency);
            if ($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) < $minimal_purchase) {
                $this->content['minimum_purchase_message'] = 
                        'Se requiere una compra mínima de '.Tools::displayPrice($minimal_purchase, $currency).' para validar su pedido, el total actual es: '.Tools::displayPrice(
                                $this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS),
                                $currency
                            );
            } else {
                $this->content['minimum_purchase_message'] = null;
            }

            $this->content['totals'] = $cart_total_details;
            /* Get available cart rules and unset the cart rules already in the cart */
            
            $this->content['cart_id'] = $this->context->cart->id;
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


    public function getFrontTemplateDir() {
        return _PS_MODULE_DIR_.'webservice_app/views/templates/front/';
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



     /*
      * Fetch cart summary
      * 
      * @return array cart summary
      */
    public function fetchList()
    {
        $summary = $this->context->cart->getSummaryDetails();

        $customizedDatas = Product::getAllCustomizedDatas($this->context->cart->id);

        /* override customization tax rate with real tax (tax rules) */
        if ($customizedDatas) {
            foreach ($summary['products'] as &$productUpdate) {
                /* Changes started by rishabh jain on 3rd sep 2018
                * Added urlencode perimeter in image link if enabled by admin
                */
                /*if (Configuration::get('KB_MOBILEAPP_URL_ENCODING') == 1) {
                    $productUpdate['image'] = $this->context->link->getImageLink(
                        urlencode($productUpdate['link_rewrite']),
                        $productUpdate['id_image'],
                        $this->getImageType('medium')
                    );
                } else {*/
                    $productUpdate['image'] = $this->context->link->getImageLink(
                        $productUpdate['link_rewrite'],
                        $productUpdate['id_image'],
                        $this->getImageType('medium')
                    );
                //}
                /* Changes over */

                $productId = (int) isset($productUpdate['id_product']) ?
                    $productUpdate['id_product'] : $productUpdate['product_id'];
                $productAttributeId = (int) isset($productUpdate['id_product_attribute']) ?
                    $productUpdate['id_product_attribute'] : $productUpdate['product_attribute_id'];

                if (isset($customizedDatas[$productId][$productAttributeId])) {
                    $productUpdate['tax_rate'] = Tax::getProductTaxRate(
                        $productId,
                        $this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}
                    );
                }
            }

            Product::addCustomizationPrice($summary['products'], $customizedDatas);
        }

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
        unset($summary['carrier']);

        return array('summary' => $summary, 'customized_data' => $customizedDatas);
    }


    



}

