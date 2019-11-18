<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
header('Content-Type: application/json');
        
class Webservice_AppRemoveProductCartModuleFrontController extends ModuleFrontController {

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
        
        $cart_id = Tools::getValue('cart_id', 0);
        if (isset($this->context->cart->id)) {
            $cart_id = (int) $this->context->cart->id;
        }

        if (!(int) $cart_id) {
            $this->status_code = 400;
            $this->content = ["message" => "Es obligatorio el identificador del carrito ó estar logueado."];
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

        /**********************************/

        $resultDecode = is_string($this->content) ? $this->content :(object) $this->content;

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
            $this->content = ["message" => "No se pudo encontrar carrito."];
            return;
        } else {
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
            $elimina = $this->processDeleteProductInCart();
            if ($elimina){
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
    }


    /**
     * This process delete a product from the cart
     */
    public function processDeleteProductInCart()
    {
        $id_product = Tools::getValue('id_product', '');
        
        if (empty($id_product)) {
            $this->status_code = 400;
            $this->content = ["message" => "Es obligatorio el identificador del producto a eliminar."];
            return false;
        } else {
            $id_address_delivery = $this->context->cart->id_address_delivery;
            $id_customization = null;
            $id_product_attribute = null;
            $product = new Product((int) $id_product);
            if (!Validate::isLoadedObject($product)) {
                $this->status_code = 404;
                $this->content = ["message" => "No se ha encontrado producto."];
                return false;
            } else {
                $customization_product = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
                    'SELECT * FROM `' . _DB_PREFIX_ . 'customization`
                    WHERE `id_cart` = ' . (int) $this->context->cart->id.
                    ' AND `id_product` = ' . (int) $id_product .
                    ' AND `id_customization` != ' . (int) $id_customization
                );

                if ($customization_product && count($customization_product) > 0) {
                    $product = new Product((int) $id_product);
                    if ($id_product_attribute > 0) {
                        $minimal_quantity = (int) Attribute::getAttributeMinimalQty($id_product_attribute);
                    } else {
                        $minimal_quantity = (int) $product->minimal_quantity;
                    }

                    $total_quantity = 0;
                    foreach ($customization_product as $custom) {
                        $total_quantity += $custom['quantity'];
                    }

                    if ($total_quantity < $minimal_quantity) {
                        $this->content = ["message" => "Cantidad mínima requerida en caso de personalización del producto"];
                        return false;
                    }
                }

                if ($this->context->cart->deleteProduct(
                    $id_product,
                    $id_product_attribute,
                    $id_customization,
                    $id_address_delivery
                )) {

                    Hook::exec('actionAfterDeleteProductInCart', array(
                        'id_cart' => (int) $this->context->cart->id,
                        'id_product' => (int) $id_product,
                        'id_product_attribute' => (int) $id_product_attribute,
                        'customization_id' => (int) $id_customization,
                        'id_address_delivery' => $id_address_delivery
                    ));

                    if (!Cart::getNbProducts((int) $this->context->cart->id)) {
                        $this->context->cart->setDeliveryOption(null);
                        $this->context->cart->gift = 0;
                        $this->context->cart->gift_message = '';
                        $this->context->cart->update();
                    }
                    $this->content["message"] = "Producto eliminado correctamente.";
                    $this->status_code = 200;
                    return true;
                    //$this->content = "Producto eliminado correctamente.";
                } else {
                    $this->status_code = 400;
                    $this->content = ["message" => "No se pudo eliminar producto"];
                    //$this->content = 'Puede existir un producto en el carrito con un atributo similar';
                    return false;
                }
                CartRule::autoRemoveFromCart();
                CartRule::autoAddToCart();
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
        $customizedDatas = Product::getAllCustomizedDatas($this->context->cart->id);

        // override customization tax rate with real tax (tax rules)
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

        // Get available cart rules and unset the cart rules already in the cart
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
    



}

