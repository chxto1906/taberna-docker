<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/ValidacionProductosBeforeFacturaSAP.php';
require_once _PS_MODULE_DIR_ . 'tarjetas_payphone/models/PrepareCreateRequestModel.php';
require_once _PS_MODULE_DIR_ . 'tarjetas_payphone/models/PrepareCreateRequestTokenModel.php';
require_once _PS_MODULE_DIR_ . 'tarjetas_payphone/tarjetas_payphone.php';
require_once _PS_MODULE_DIR_ . 'payphone/controllers/front/validation.php';

        
class Webservice_AppPaymentApiPayphoneModuleFrontController extends ModuleFrontController {

    public $log = null;
    private $status_code = 400;

    protected $token = null;
    protected $url = null;
    protected $urlToken = null;
    protected $passCode = null;
    private $storeId = null;

    public function initContent() {
    	parent::initContent();
    
        exit;

    	$this->setTemplate('productos.tpl');
    }

    public function postProcess() {
        $response = new Response();
        if (!$this->context->customer->logged) {
            $this->status_code = 401;
            $this->content = "Usuario no logueado";
        } else {
            $env = realpath("/var/.env");
            $config = parse_ini_file($env, true);
            $this->token = $config["PAYPHONE_API_TOKEN"];
            $this->url = $config["PAYPHONE_API_URL"];
            $this->urlToken = $config["PAYPHONE_API_TOKEN_URL"];
            $this->passCode = $config["PAYPHONE_API_PASSCODE"];
            $this->storeId = $config["PAYPHONE_API_STORE_ID"];

            $this->process_payment();
        }
        
        echo $response->json_response($this->content,$this->status_code);
    }

    public function process_payment() {

        $log = new LoggerTools();
        $cart = $this->context->cart;
        $datos_card_token = null;

        try {
            $add_card = Tools::getIsset('add_card') ? Tools::getValue("add_card") : null;
            $id_card = Tools::getValue('id_card',null);
            $cardHolder = Tools::getValue('cardHolder',null);
            $dataEnc = strval(Tools::getValue('data'));

            $log->add("WebService TOOLS dataEnc: ".$dataEnc);
            $log->add("WebService id_card: ".$id_card);

            $deferredType = Tools::getValue('deferred') ? Tools::getValue('deferred') : "00000000";
            $log->add("WebService TOOLS deferredType: ".$deferredType);

            if (!$id_card){
                if (!$dataEnc){
                    $this->content = ["message" => "Datos de la tarjeta incorrectos."];
                    return;
                }
            }else{
                $datos_card_token = $this->getDataCard($id_card);
            }

            if (Module::isEnabled("tarjetas_payphone") == false) {
                $this->content = ["message" => "Modulo de pago inactivo"];
                return;
            }

            $moduleInstance = new Tarjetas_payphone();

            $cart = $this->context->cart;
            $cart_id = (int) $cart->id;

            if (!Validate::isLoadedObject($cart)){
                $this->content = ["message" => "Carrito no es válido."];
                return;
            }

            if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$moduleInstance->active) {
                $this->content = ["message" => "Carrito no es válido"];
                return;
            }

            $authorized = false;
            foreach (Module::getPaymentModules() as $module)
                if ($module['name'] == 'tarjetas_payphone') {
                    $authorized = true;
                    break;
                }

            if (!$authorized){
                $this->content = ["message" => "Este método de pago no está disponible"];
                return;
            }

            $customer = new Customer($cart->id_customer);

            $resValidateHorario = $this->isOpen();
            if (!$resValidateHorario) {
                $this->content = ["message" => "Tienda se encuentra fuera de su horario de atención."];
                return;
            }

            if (!Validate::isLoadedObject($customer)) {
                $this->content = ["message" => "Cliente del carrito no es válido."];
                return;
            }

            $currency = $this->context->currency;
            $total = (float) $cart->getOrderTotal(true);
            $products_with_tax = array();
            $products_without_tax = array();
            foreach ($cart->getProducts() as $product) {
                if ($product['rate'] == 0)
                    $products_without_tax[] = $product;
                else
                    $products_with_tax[] = $product;
            }

            $amount_with_tax = 0;
            $tax = 0;
            $amount_without_tax = 0;

            if (sizeof($products_with_tax) > 0) {
                $iva_tax = floatval($products_with_tax[0]['rate'] / 100);
                $amount_with_tax_cart = $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING, $products_with_tax);
                $amount_with_tax = $amount_with_tax_cart / ($iva_tax + 1);
                $amount_with_tax = round($amount_with_tax, 2);
                $tax = $amount_with_tax_cart - $amount_with_tax;
                $tax = round($tax, 2);
            }

            if (sizeof($products_without_tax) > 0)
                $amount_without_tax = $cart->getOrderTotal(false, Cart::BOTH_WITHOUT_SHIPPING, $products_without_tax);

            $shipping_tax = $cart->getOrderTotal(true, Cart::ONLY_SHIPPING);
            $shipping_no_tax = $cart->getOrderTotal(false, Cart::ONLY_SHIPPING);

            if (abs($shipping_tax - $shipping_no_tax) == 0)
                $amount_without_tax += $shipping_tax;
            else {
                $tax += $shipping_tax - $shipping_no_tax;
                $amount_with_tax += $shipping_no_tax;
            }

            $amount = $amount_with_tax + $tax + $amount_without_tax;
            $amount = round($amount, 2);
                
            $resValidateArticulos = $this->validateArticulosSAP($cart);
            if ($resValidateArticulos) {
                $this->content = ["message" => $resValidateArticulos];
                return;
            }
            
            $respValido = $moduleInstance->validateOrder((int) $cart->id, Configuration::get('PS_PAYPHONE_PENDING'), $total, $moduleInstance->displayName, NULL, array(), (int) $currency->id, false, $customer->secure_key, NULL, true);

            if ($respValido !== true){
                $this->process_session_data();
                $this->content["message"] = $respValido;
                return;
            }

            $id_order = Order::getOrderByCartId($cart->id);
            $order = new Order($id_order);
            if (!Validate::isLoadedObject($order)) {
                $this->content = ["message" => "No se pudo validar orden"];
                $this->process_session_data();
                return;
            }

            $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
            $link = $this->context->link->getModuleLink('tarjetas_payphone', 'validation', array(), Configuration::get('PS_SSL_ENABLED'));

            $valorTax = round($tax,2) * 100;
            $valorAmount = round($amount,2) * 100;
            $valorAmountWithTax = round($amount_with_tax,2) * 100;
            $valorAmountWithOutTax = round($amount_without_tax,2) * 100;

            $id_address_invoice = $cart->id_address_invoice;
            $address = new Address($id_address_invoice);

            $log = new LoggerTools();
            if ($datos_card_token){
                $data = new PrepareCreateRequestTokenModel();
                $data->cardToken = $datos_card_token["cardToken"];
                $data->cardHolder = $cardHolder;
                $log->add("cardToken: ".$data->cardToken);
                $log->add("cardHolder: ".$cardHolder);
                $this->url = $this->urlToken;
            }else{
                $data = new PrepareCreateRequestModel();
                $data->data = $dataEnc;
                $log->add("data: ".$data->data);
            }

            $data->email = $address->email;
            $data->phoneNumber = $address->phone;
            $data->documentId = $address->dni;
            $data->amount = "" . $valorAmount;
            $data->amountWithTax = "" . $valorAmountWithTax; 
            $data->amountWithoutTax = "" . $valorAmountWithOutTax;
            $data->tax = "" . $valorTax;
            $data->clientTransactionId = $order->reference;
            $data->storeId = $this->storeId;
            $data->deferredType = $deferredType;


            $log->add("data: ".$data->data);
            $log->add("email: ".$data->email);
            $log->add("phoneNumber: ".$data->phoneNumber);
            $log->add("documentId: ".$data->documentId);
            $log->add("amount: ".$data->amount);
            $log->add("amountWithTax: ".$data->amountWithTax);
            $log->add("amountWithoutTax: ".$data->amountWithoutTax);
            $log->add("tax: ".$data->tax);
            $log->add("clientTransactionId: ".$data->clientTransactionId);
            $log->add("storeId: ".$data->storeId);
            $log->add("deferredType: ".$data->deferredType);



            // REPONSE ESPERADO:

            /*

            "cardToken": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtaWQiOiJGSTVnc2h6ckJScWJVc3BiSS9YTGNIUDgwZUxoLzh0U0tNOGs0ZHg3cDlYN3lQb2ZoRE9WZmpJZXJSS1FOa291IiwibmJmIjoxNTY0Nzc2MzA2LCJleHAiOjE5MjQ3NzI3MDYsImlhdCI6MTU2NDc3NjMwNiwiaXNzIjoiUGF5UGhvbmUiLCJhdWQiOiJodHRwczovL3BheS5wYXlwaG9uZXRvZG9lc3Bvc2libGUuY29tLyJ9.d-4DTGfNiEzDzYkIJJPcG8NFNobWWhjm14ry0dS2VD8",
            "authorizationCode": "TT2120734",
            "messageCode": 0,
            "status": "Approved",
            "statusCode": 3,
            "transactionId": 2120734,
            "clientTransactionId": "100"

            */



        } catch (Exception $e) {
            PrestaShopLogger::addLog("Php error processing payment api payphone", 4);
            $this->content = ["message" => "Error en el servidor al procesar la transacción"];
            return;
        }


        try {
            $json = json_encode($data);
            $responsePay = $this->ApiPay($json);
            
            if (isset($responsePay->authorizationCode)) {
               $this->asentarPagoEnDB($responsePay,$order,$cart,$customer,$data,$total,$add_card,$dataEnc,$datos_card_token); 
            } else {
                $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
                $this->content = ["message" => "Ocurrió un inconveniente al procesar la Transacción."];
                $this->process_session_data();
            }
        } catch (Exception $e) {
            PrestaShopLogger::addLog("PayPhone check payment error", 3);
            $this->content = ["message" => "Ocurrió un inconveniente al procesar la Transacción."];
        
        }
    }


    public function getDataCard($card_id){
        $card = Db::getInstance()->executeS('
        SELECT * 
        FROM '._DB_PREFIX_.'credit_card_tokens
        WHERE id ='. $card_id);

        $res = is_array($card) ? $card[0] : null;
        return $res;
    }


    public function process_session_data() {
        
        $cart = $this->context->cart;
        $customer = new Customer($cart->id_customer);
        $this->context->cookie->id_cart = null;
        $this->context->cookie->id_customer = (int) ($customer->id);
        $this->context->cookie->customer_lastname = $customer->lastname;
        $this->context->cookie->customer_firstname = $customer->firstname;
        $this->context->cookie->logged = 1;
        $customer->logged = 1;
        $this->context->cookie->is_guest = $customer->isGuest();
        $this->context->cookie->passwd = $customer->passwd;
        $this->context->cookie->email = $customer->email;

        $this->context->customer = $customer;
        $this->context->cart->add();
        $id_cart = $this->context->cart->id;
        $this->context->cart = new Cart($id_cart);
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

        $address = new Address();
        $first_address = $address->getFirstCustomerAddressId($customer->id);
        $customer->id_address = $first_address ? $first_address : '0';

        $customer->customer_id = $customer->id;
        //$customer->wishlist_count = $wishlist_count;
        $customer->cart_id = (int)$this->context->cart->id;
        $customer->cart_count = Cart::getNbProducts($this->context->cookie->id_cart);

        $this->proccessCustomer($customer);
        
        CartRule::autoRemoveFromCart($this->context);
        CartRule::autoAddToCart($this->context);

    }

    public function proccessCustomer($customer) {
        $context_session = array(
                                'customer_id'   => $customer->id,
                                'cart_id'       => $customer->cart_id
                            );
        $context_session_str = json_encode((object)$context_session);
        $context_session_encrypt = $this->openCypher('encrypt',$context_session_str);


        $this->content["new_session_data"] = $context_session_encrypt;

        
    }


    public function ApiPay($post_data, $content_type = "application/json") {
        $log = new LoggerTools();

        $log->add("REQUEST API Payphone DATA: ".$post_data);


        $curl = curl_init($this->url);
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $this->token;
        $headers[] = 'Content-Type: ' . $content_type;
        /*if (!empty($config->Lang))
            $headers[] = 'Accept-Language: ' . $config->Lang;*/
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        $curl_response = curl_exec($curl);

        $info = curl_getinfo($curl);
        curl_close($curl);

        $errors = array();
        switch ($info['http_code']) {
            case 201:
                $log->add("Response API CALL: ".$curl_response);
                return json_decode($curl_response);
            case 0:
                $temp = new ErrorResponseModelTarjetas();
                $temp->message = 'Lo sentimos por favor verifique su internet o cadena de conexi&oacute;n.';
                $errors[] = $temp;
                return null;
            default :
                $errors = json_decode($curl_response);
                if (!is_array($errors)) {
                    $temp = $errors;
                    $errors = array();
                    $errors[] = $temp;
                }
                $log->add("Response API CALL error: ".$curl_response);

                return null;
        }
    }


    public function asentarPagoEnDB($responsePay,$order,$cart,$customer,$dataRequest,$total,$add_card,$dataEnc,$datos_card_token) {
        $log = new LoggerTools();
        $data = $responsePay;
        $write = array();
        $write['transaction_id'] = (int) $data->transactionId;
        $write['message'] = "Aprobada";
        $write['phone_number'] = pSQL($dataRequest->phoneNumber);
        $write['transaction_status'] = pSQL($data->status);
        $write['client_transaction_id'] = pSQL($data->clientTransactionId);
        $write['client_user_id'] = pSQL($cart->id);
        $write['deferred'] = $dataRequest->deferredType;
        $write['amount'] = $dataRequest->amount;
        $write['date_add'] = date("Y-m-d H:i:s");
        $write['authorization_code'] = pSQL($data->authorizationCode);
        
        Db::getInstance()->insert('payphone_transaction', $write);
            
        $facturaDB = new sincronizacionwebservicesFacturaDBModuleFrontController();
        $resFacturacion = $facturaDB->processFacturacionDB($order,$cart,$write);

        //$resFacturacion = $this->processFacturacionDB($order,$cart,$write);
        $log->add("Asentar DB facturacion processFacturacionDB : ".$resFacturacion);
        if (!$resFacturacion) {
            $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
            $this->reversePayphone($data->transactionId);
            $this->content = ["message" => 'Ocurrió un inconveniente en el proceso de pago. Se ha cancelado tu pedido y se ha revertido tu pago. Disculpa las molestias. Vuelve a intentarlo más tarde.'];
            $this->process_session_data();
        } else {
            //$this->addNumGuiaMiPilotoOrder($order,$guia_numero);
            $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_APPROVED'), true);
            //$this->module->validateOrder((int) $cart->id, Configuration::get('PS_PAYPHONE_PENDING'), $total, $this->module->displayName, NULL, array(), (int) $currency->id, false, $customer->secure_key);
            //Tools::redirect('index.php?controller=order-confirmation&id_cart=' . (int) $cart->id . '&id_module=' . (int) $this->module->id . '&id_order=' . $order->id . '&hora_llegada='. $hora_llegada . '&key=' . $customer->secure_key);
            if ($dataEnc && !$datos_card_token)
                if ($add_card == "on")
                    $this->saveCard($data->cardToken);

            $this->status_code = 201;
            $this->content = ["message" => "Se ha procesado el pago correctamente."];
            $this->process_session_data();
        }

    }


    public function saveCard($cardToken) {
        $dueDate = null;
        $last_digits = Tools::getValue("lastDigits");
        $type_card = Tools::getValue("type_card");
        $holder_name = Tools::getValue("holderName");
        $expirationMonth = Tools::getValue("expirationMonth");
        $expirationYear = Tools::getValue("expirationYear");
        if ($expirationMonth && $expirationYear)
            $dueDate = $expirationMonth."/".$expirationYear;

        $write = array();
        $write["cardToken"] = $cardToken;
        $write["description"] = $holder_name;
        $write["type"] = $type_card;
        $write["lastDigits"] = $last_digits;
        $write["dueDate"] = $dueDate;
        $write["id_customer"] = $this->context->customer->id;

        if ($last_digits && $type_card && $holder_name && $dueDate)
            Db::getInstance()->insert('credit_card_tokens', $write);
    }

    function changeOrderStatus($order, $state, $send_email_hook=false) {
        ShopUrl::cacheMainDomainForShop((int) $order->id_shop);
        $order_state = new OrderState($state);
        $current_order_state = $order->getCurrentOrderState();
        $history = new OrderHistory();
        $history->id_order = $order->id;
        $use_existings_payment = false;
        if (!$order->hasInvoice()) {
            $use_existings_payment = true;
        }
        $history->changeIdOrderState((int) $order_state->id, $order, $use_existings_payment);
        $history->addWithemail(true);

        if ($send_email_hook) {
            $order_status = new OrderState((int) $state, (int) $this->context->language->id);
            // Hook validate order
            Hook::exec('actionValidateOrder', array(
                'cart' => $this->context->cart,
                'order' => $order,
                'customer' => $this->context->customer,
                'currency' => $this->context->currency,
                'orderStatus' => $order_status,
            ));
        }
        
    }

    private function reversePayphone($transactionId) {
        $payphone = new PayPhoneValidationModuleFrontController();
        $payphone->reversePayphone($transactionId);
    }

    function validateArticulosSAP($order) {
        $ValidacionProductosBeforefacturaSAP = new sincronizacionwebservicesValidacionProductosBeforeFacturaSAPModuleFrontController();
        return $ValidacionProductosBeforefacturaSAP->validate($order);
    }





}

class ErrorResponseModelTarjetas {

    public $message;
    public $errorCode;
    public $errorDescription;
    public $errorDescriptions;

}

