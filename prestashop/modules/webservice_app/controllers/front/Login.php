<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
header('Content-Type: application/json');
        
class Webservice_AppLoginModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        $response = new Response();
        $status_code = 401;
        $email = Tools::getValue('email', '');
        $password = Tools::getValue('password', '');
        $cart_id = Tools::getValue('cart_id', '');

        if (!empty($cart_id)) {
            $this->context->cart->id_currency = $this->context->currency->id;
            $this->context->cart = new Cart($cart_id);
            $this->context->cookie->id_cart = (int) $this->context->cart->id;
            $this->context->cookie->write();
        }
        if (empty($email)) {
            $status_code = 400;
            $resultDecode = ["message" => "Email es requerido"];
        } elseif (!Validate::isEmail($email)) {
            $status_code = 400;
            $resultDecode = ["message" => "Dirección de email es inválido"];
        } elseif (empty($password)) {
            $status_code = 400;
            $resultDecode = ["message" => "Password es requerido"];
        } elseif (!Validate::isPasswd($password)) {
            $status_code = 400;
            $resultDecode = ["message" => "Password es incorrecto"];
        } else {
            $customer = new Customer();
            Hook::exec('actionBeforeAuthentication');
            $authentication = $customer->getByEmail(trim($email), trim($password));
            if (isset($authentication->active) && !$authentication->active) {
                $resultDecode = ["message" => "Cuenta no está activa"];
            } elseif (!$authentication || !$customer->id) {
                $resultDecode = ["message" => "Autenticación fallida"];
            } else {
                $this->context->cookie->id_customer = (int) ($customer->id);
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->logged = 1;
                $customer->logged = 1;
                $this->context->cookie->is_guest = $customer->isGuest();
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->email = $customer->email;

                $this->context->customer = $customer;


                if (empty($cart_id)) {
                    $id_cart = (int) Cart::lastNoneOrderedCart($this->context->customer->id);
                    $this->context->cart->id = $id_cart;
                }

                /*if (Configuration::get('PS_CART_FOLLOWING') &&
                        (empty($this->context->cookie->id_cart) ||
                        Cart::getNbProducts($this->context->cookie->id_cart) == 0) &&
                        $id_cart = (int) Cart::lastNoneOrderedCart($this->context->customer->id)) {
                    $this->context->cart = new Cart($id_cart);
                } else {*/
                    $id_carrier = (int) $this->context->cart->id_carrier;
                    if (!$this->context->cart->id_address_delivery) {
                        $this->context->cart->id_carrier = 0;
                        $this->context->cart->setDeliveryOption(null);
                        $d_id = (int) Address::getFirstCustomerAddressId((int) ($customer->id));
                        $this->context->cart->id_address_delivery = $d_id;
                        $i_id = (int) Address::getFirstCustomerAddressId((int) ($customer->id));
                        $this->context->cart->id_address_invoice = $i_id;
                    }
                //}
                $this->context->cart->id_customer = (int) $customer->id;
                $this->context->cart->secure_key = $customer->secure_key;

                if (isset($id_carrier) && $id_carrier && Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                    $delivery_option = array($this->context->cart->id_address_delivery => $id_carrier . ',');
                    $this->context->cart->setDeliveryOption($delivery_option);
                }

                $this->context->cart->id_currency = $this->context->currency->id;
                $this->context->cart->save();
                
                $this->context->cookie->id_cart = (int) $this->context->cart->id;
                $this->context->cookie->write();
                $this->context->cart->autosetProductAddress();

                Hook::exec('actionAuthentication', array('customer' => $this->context->customer));
                $wishlist_count = $this->getWishListCount($customer->id);

                $address = new Address();
                $first_address = $address->getFirstCustomerAddressId($authentication->id);
                $authentication->id_address = $first_address ? $first_address : '0';

                $authentication->customer_id = $customer->id;
                $authentication->wishlist_count = $wishlist_count;
                $authentication->cart_id = (int)$this->context->cart->id;
                $authentication->cart_count = Cart::getNbProducts($this->context->cookie->id_cart);

                $resultDecode = $this->proccessCustomer($authentication);
                $status_code = 200;

                CartRule::autoRemoveFromCart($this->context);
                CartRule::autoAddToCart($this->context);
            }
        }

        /*var_dump($this->context);
        exit;*/
        echo $response->json_response($resultDecode,$status_code);

        exit;

    	$this->setTemplate('productos.tpl');
    }

    /*
     * Function to get the wishlist item count
     * 
     * @param int $customer_id id of customer
     * @return int wishlist item count
     */
    public function getWishListCount($customer_id)
    {
        if (!Module::isInstalled('blockwishlist') || !Module::isEnabled('blockwishlist')) {
            $wishlist_count = 0;
        } else {
            $deafult_wishlist_id = $this->getDefaultWishlist($customer_id);
            if ($deafult_wishlist_id) {
                $wishlist_products = $this->getProductByIdCustomer(
                    $deafult_wishlist_id,
                    $customer_id,
                    $this->context->language->id
                );
                if (!$wishlist_products) {
                    $wishlist_count = 0;
                } else {
                    $wishlist_count = count($wishlist_products);
                }
            } else {
                $wishlist_count = 0;
            }
        }

        return $wishlist_count;
    }





    public function proccessCustomer($customer) {
        $context_session = array(
                                'customer_id'   => $customer->id,
                                'cart_id'       => $customer->cart_id,
                                'random'        => $this->generateRandom(5)
                            );
        $context_session_str = json_encode((object)$context_session);
        $context_session_encrypt = $this->openCypher('encrypt',$context_session_str);

        $dataResult = [
            "id_gender"     => $customer->id_gender,
            "last_name"     => $customer->lastname,
            "first_name"    => $customer->firstname,
            "birthday"      => $customer->birthday,
            "email"         => $customer->email,
            "id_address"    => $customer->id_address,
            "wishlist_count"=> $customer->wishlist_count,
            "session_data"  => $context_session_encrypt,
            "cart_count"    => $customer->cart_count,
            "cart_id"       => $customer->cart_id,
            #"customer_id"   => $customer->id
        ];

        $this->context->cart->valid_session = true;
        $this->context->cart->save();
        
        return (object) $dataResult;
    }


    /*public function checkUserPass($resultUser,$password) {

        $resultUser = json_decode($resultUser);
        $info = $resultUser->customers[0];
        $result = false;
        $salt = substr($info->passwd, strrpos($info->passwd, ':') + 1, 2);
        $ZCpassword = md5($this->COOKIE_KEY . $password) . ':' . $salt;

        // Check if password comparison is true or false
        if (password_verify($password, $info->passwd) == true)
            $result = true;
        return $result;
    }*/


}

