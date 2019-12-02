<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
header('Content-Type: application/json');
        
class Webservice_AppAddToCartModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $product = null;
    private $status_code = 400;

    public function initContent() {
    	parent::initContent();
        $log = new LoggerTools();
        $response = new Response();
        $id_product = Tools::getValue('id_product', '');
        $qty = Tools::getValue('qty', '');
        $operator = Tools::getValue('operator', '');
        if (!$operator) {
            $operator = "up";
        }

        if ($operator != "up" && $operator != "down") {
            $operator = "up";
        }

        
        $log->add("ID_PRODUCT: ".$id_product);
        $log->add("CANTIDAD: ".$qty);

        /**********************************/


        if (empty($id_product) || empty($qty)) {
            /*$this->content['cart_add_result'] = array(
                'status' => 'failure',
                'message' => parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Product data is missing'),
                    'AppAddToCart'
                )
            );
            $this->writeLog('Product data is missing.');*/
            $this->content = ["message" => "Los parámetros del producto para agregar al carrito son necesarios"];
        } else {
            //$id_product = $product_data->cart_products[0]->product_id;
            if (empty($id_product)) {
                $id_product = 0;
            }
            $this->product = new Product(
                $id_product,
                true,
                $this->context->language->id,
                $this->context->shop->id,
                $this->context
            );

            if (!Validate::isLoadedObject($this->product)) {
                /*$this->content['status'] = 'failure';
                $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                    Tools::getValue('iso_code', false),
                    $this->l('Product not found'),
                    'AppAddToCart'
                );
                $this->writeLog('Product with the provided data is not found.');*/
                $this->status_code = 404;
                $this->content = ["message" => "Producto no encontrado"];
            } else {

                /*$session_obj = $this->isSession();

                if ($session_obj){
                    $cart_id = $session_obj->cart_id;
                    $customer_id = $session_obj->customer_id;
                }else{*/
                    $cart_id = Tools::getValue('cart_id', '');

                    if (isset($this->context->cart->id)) {
                        $cart_id = $this->context->cart->id;
                    }

                //}


                if (empty($cart_id)) {
                    /* Add new cart to save product data */
                    $this->context->cart->id_currency = $this->context->currency->id;
                    $this->context->cart->add();
                    if ($this->context->cart->id) {
                        $this->context->cookie->id_cart = (int) $this->context->cart->id;
                    }
                } else {

                    $tmp_cart = new Cart($cart_id);
                    $shop_cart_id = $tmp_cart->id_shop;
                    $shop_id_context = $this->context->shop->id;



                    if ($shop_cart_id == $shop_id_context) {
                        $this->context->cart = new Cart($cart_id, false, null, null, $this->context);
                        if (!Validate::isLoadedObject($this->context->cart)) {
                            $this->context->cart->id_currency = $this->context->currency->id;
                            $this->context->cart->add();
                        }
                        $this->context->cart->id_currency = $this->context->currency->id;
                        if ($this->context->cart->id) {
                            $this->context->cookie->id_cart = (int) $this->context->cart->id;
                        }
                    } else {
                        $this->status_code = 401;
                        $this->content = ["message" => "El carrito al que quieres agregar el producto no pertenece a tu tienda actual"];
                    }
                }
                /*if ($this->product->customizable) {
                    $post_customizable_data = $product_data->customization_details;
                    if (!empty($post_customizable_data)) {
                        if (!$this->saveCustomizedData($post_customizable_data)) {
                            $this->content['status'] = 'failure';
                            $this->content['message'] = parent::getTranslatedTextByFileAndISO(
                                Tools::getValue('iso_code', false),
                                $this->l('Invalid Message'),
                                'AppAddToCart'
                            );
                            $this->writeLog('Invalid message in customization field');
                        }
                    }
                }*/
                //$qty = $product_data->cart_products[0]->minimal_quantity;
                //$id_product_attribute = $product_data->cart_products[0]->id_product_attribute;
                if ($this->status_code != 401) {

                    $id_product_attribute = 0;
                    
                    $log->add("Antes de addKbProduct -- Context CART_ID: ".$this->context->cart->id);


                    $this->addKbProduct($id_product, $id_product_attribute, $qty, $operator);
                    /*start:changes made by aayushi on 15th March 2019 to update cart count while adding product to the cart*/

                    if ($this->status_code == 200) {
                        $this->content['total_cart_items'] = Cart::getNbProducts($this->context->cart->id);
                        /*end:changes made by aayushi on 15th March 2019 to update cart count while adding product to the cart*/
                        $this->content['cart_id'] = (int)$this->context->cart->id;
                    }
                }
            }
        }

        //$this->content['install_module'] = '';
        //return $this->fetchJSONContent();



        /**********************************/

        //$resultDecode = is_string($this->content) ? $this->content :(object) $this->content;
        $log->add('***Response ADD to CART***');
        $log->add(implode(", ",$this->content));
        echo $response->json_response($this->content,$this->status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }


    /**
     * Add product into cart with provided quantity
     *
     * @param int $id_product product id
     * @param int $id_product_attribute product attruibute id
     * @param int $qty product quantity
     */
    public function addKbProduct($id_product, $id_product_attribute, $qty, $operator)
    {
        $log = new LoggerTools();
        if ($qty == 0) {
            $qty = 1;
        }
        if (!empty($id_product_attribute)) {
            $minimal_quantity = (int) Attribute::getAttributeMinimalQty($id_product_attribute);
        } else {
            $minimal_quantity = (int) $this->product->minimal_quantity;
        }
        if ($minimal_quantity == 0) {
            $minimal_quantity = 1;
        }
        if ((int) $qty < $minimal_quantity) {
            $this->status_code = 400;
            $this->content = ["message" => "Error al agregar producto en el carrito. Debes agregar la mínima cantidad: $minimal_quantity"];
        } else {
            /*$qty_original = Cart::getNbProducts($this->context->cart->id);
            if (($qty_original <= 1) && ($operator == "down")) {
                $this->status_code = 400;
                $this->content = ["message" => "No se puede disminuir a cantidad menor que 1"];
            } else {*/

                $log->add("Antes de updateQty -- Context CART_ID: ".$this->context->cart->id);
                $log->add("Antes de updateQty -- ID_PRODUCT: ".$id_product);
                $log->add("Antes de updateQty -- ID_PRODUCT_ATTIBUTE: ".$id_product_attribute);
                $log->add("Antes de updateQty -- OPERATOR: ".$operator);

                $update_status = $this->context->cart->updateQty($qty, $id_product, $id_product_attribute, false, $operator);
                if (!$update_status) {
                    $this->status_code = 400;
                    $this->content = [ "message" => "No se pudo agregar esa cantidad de productos en el carrito." ];
                } else {
                    $this->status_code = 200;
                }
            //}
        }
    }



}

