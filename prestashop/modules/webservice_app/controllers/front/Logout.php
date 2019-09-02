<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

class Webservice_AppLogoutModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        $response = new Response();
        $status_code = 400;
        $resultDecode = "No se pudo cerrar sesión";
        
        if (Context::getContext()->customer->logged == true)
            $customer = new Customer((int) Context::getContext()->customer->id);
            $customer->logout();
            $status_code = 200;
            $resultDecode = "OK";
        }

        //echo $this->context;
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
                                'cart_id'       => $customer->cart_id
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
            "cart_id"       => $customer->cart_id
        ];
        
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

