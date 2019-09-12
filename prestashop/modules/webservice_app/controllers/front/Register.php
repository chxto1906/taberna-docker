<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

        
class Webservice_AppRegisterModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        $response = new Response();
        $status_code = 400;
        $user_data = (object) [
                        "gender"        =>  Tools::getValue('gender', ''),
                        "first_name"    =>  Tools::getValue('first_name', ''),
                        "last_name"     =>  Tools::getValue('last_name', ''),
                        "email"         =>  Tools::getValue('email', ''),
                        "birthday"      =>  Tools::getValue('birthday', ''),
                        "password"      =>  Tools::getValue('password', '')
                    ];

        //$user_data = Tools::getValue('signup', Tools::jsonEncode(array()));
        //$user_data = Tools::jsonDecode($user_data);
        $cart_id = Tools::getValue('cart_id', '');
        if (!empty($cart_id)) {
            $this->context->cart->id_currency = $this->context->currency->id;
            $this->context->cart = new Cart($cart_id);
            $this->context->cookie->id_cart = (int) $this->context->cart->id;
            $this->context->cookie->write();
        }
        if ($user_data->first_name && 
            $user_data->last_name && 
            $user_data->email && 
            $user_data->password) {
            if (!Validate::isName($user_data->first_name)) {
                $this->content = ["message" => 'Nombre inválido.'];
            } elseif (!Validate::isName($user_data->last_name)) {
                $this->content = ["message" => 'Apellido inválido.'];
            } elseif (!Validate::isEmail($user_data->email)) {
                $this->content = ["message" => 'Dirección de email inválida.'];
            } elseif (!Validate::isPasswd($user_data->password)) {
                $this->content = ["message" => 'Password inválido.'];
            } else {
                $this->addUser($user_data);
            }
        } else {
            $this->content = ["message" => 'Hace falta información para el registro de usuario.'];
        }


        //$resultDecode = is_string($this->content) ? $this->content :(object) $this->content;

        echo $response->json_response($this->content,$this->status_code);

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


    /**
     * Add user information to DB and Context
     *
     *@param array $user_data user information
     */
    public function addUser($user_data)
    {
        if (!empty($user_data)) {
            if (Customer::customerExists(strip_tags($user_data->email))) {
                $this->content = ["message" => 'Dirección de email ya está registrada en nuestra tienda.'];
            } else {
                $col_query = 'SHOW COLUMNS FROM ' . _DB_PREFIX_ . 'customer LIKE "id_lang"';
                $col_result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($col_query);
                if (count($col_result) == 1) {
                    $col_exist = 1;
                } else {
                    $col_exist = 0;
                }
                //$user_data->gender = $user_data->title;
                if (!empty($user_data->birthday)) {
                    $dob = array();
                    $dob = explode("/", $user_data->birthday);
                    $user_data->birthday = $dob[2] . "-" . $dob[1] . "-" . $dob[0];
                }
                $insertion_time = date('Y-m-d H:i:s', time());
                $original_passd = $user_data->password;
                $passd = Tools::encrypt($original_passd);
                $secure_key = md5(uniqid(rand(), true));
                /* condition added by rishabh on 18th sep 2018
                    to fix gender issue in ios app
                    */
                if ($user_data->gender != '') {
                    $gender_qry = '(select id_gender from ' . _DB_PREFIX_ . 'gender '
                            . 'where type = ' . pSQL($user_data->gender) . ')';
                    $gender = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($gender_qry);
                    if (empty($gender)) {
                        $user_data->gender = 0;
                    }
                }
                //changes over
                $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'customer SET 
                    id_shop_group = ' . (int) $this->context->shop->id_shop_group . ', 
                    id_shop = ' . (int) $this->context->shop->id . ', 
                    id_gender = ' . (int) $user_data->gender . ', 
                    id_default_group = ' . (int) Configuration::get('PS_CUSTOMER_GROUP') . ',';
                if ($col_exist == 1) {
                    $sql .= 'id_lang = ' . (int) $this->context->language->id . ',';
                }
                $sql .= 'id_risk = 0, 
                    firstname = "' . pSQL(strip_tags($user_data->first_name)) . '", 
                    lastname = "' . pSQL(strip_tags($user_data->last_name)) . '", 
                    email = "' . pSQL(strip_tags($user_data->email)) . '", 
                    passwd = "' . pSQL($passd) . '", 
                    birthday = "' . pSQL($user_data->birthday) . '", 
                    max_payment_days = 0, 
                    secure_key = "' . pSQL($secure_key) . '", 
                    active = 1, date_add = "' . pSQL($insertion_time) . '", date_upd = "' . pSQL($insertion_time) . '"';

                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
                $id_customer = Db::getInstance(_PS_USE_SQL_SLAVE_)->Insert_ID();
                $customer = new Customer();
                $customer->id = $id_customer;
                $customer->firstname = ucwords($user_data->first_name);
                $customer->lastname = ucwords($user_data->last_name);
                $customer->passwd = $passd;
                $customer->email = $user_data->email;
                $customer->secure_key = $secure_key;
                $customer->birthday = $user_data->birthday;
                $customer->id_gender = $user_data->gender;
                $customer->is_guest = 0;
                $customer->active = 1;
                $customer->logged = 1;
                $customer->id_shop = $this->context->shop->id;
                $customer->id_lang = $this->context->language->id;
                        

                $customer->cleanGroups();
                $customer->addGroups(array((int) Configuration::get('PS_CUSTOMER_GROUP')));

                $this->sendConfirmationMail($customer, $original_passd);
                //Update Context
                $this->context->customer = $customer;
                $this->context->cookie->id_customer = (int) $customer->id;
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->logged = 1;
                $this->context->cookie->email = $customer->email;
                $this->context->cookie->is_guest = $customer->is_guest;
                //Cart
                $id_carrier = (int) $this->context->cart->id_carrier;
                $this->context->cart->id_carrier = 0;
                $this->context->cart->setDeliveryOption(null);
                $this->context->cart->id_address_delivery = (int)
                        Address::getFirstCustomerAddressId((int) $customer->id);
                $this->context->cart->id_address_invoice = (int)
                        Address::getFirstCustomerAddressId((int) $customer->id);
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
                $first_address = $address->getFirstCustomerAddressId($customer->id);
                $customer->id_address = $first_address ? $first_address : '0';

                $customer->customer_id = $customer->id;
                $customer->wishlist_count = $wishlist_count;
                $customer->cart_id = (int)$this->context->cart->id;
                $customer->cart_count = Cart::getNbProducts($this->context->cookie->id_cart);

                $this->proccessCustomer($customer);
                $status_code = 200;

                CartRule::autoRemoveFromCart($this->context);
                CartRule::autoAddToCart($this->context);
            }
        }
    }

    /**
     * Send confirmation mail after successfully registration
     *
     * @param Object Customer $customer customer object
     * @param string $passd customer password
     */
    protected function sendConfirmationMail($customer, $passd)
    {
        if (!Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
            return true;
        }

        return Mail::Send(
            $this->context->language->id,
            'account',
            Mail::l('Welcome!'),
            array(
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{email}' => $customer->email,
                '{passwd}' => $passd
            ),
            $customer->email,
            $customer->firstname . ' ' . $customer->lastname
        );
    }





    public function proccessCustomer($customer) {
        $context_session = array(
                                'customer_id'   => $customer->id,
                                'cart_id'       => $customer->cart_id
                            );
        $context_session_str = json_encode((object)$context_session);
        $context_session_encrypt = $this->openCypher('encrypt',$context_session_str);

        $this->content = [
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
        
        return (object) $this->content;
    }




}

