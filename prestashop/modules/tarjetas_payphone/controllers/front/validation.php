<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/ValidacionProductosBeforeFacturaSAP.php';
require_once _PS_MODULE_DIR_ . 'tarjetas_payphone/models/PrepareCreateRequestModel.php';
require_once _PS_MODULE_DIR_ . 'tarjetas_payphone/models/PrepareCreateRequestTokenModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/controllers/front/validation.php';


class Tarjetas_payphoneValidationModuleFrontController extends ModuleFrontController
{

    protected $token = null;
    protected $url = null;
    protected $urlToken = null;
    protected $passCode = null;
    private $storeId = null;

    public function initContent() {
        parent::initContent();
        //return $this->showErrors("Tienda se encuentra fuera de su horario de atención.");
    }


    /**
     * This class should be use by your Instant Payment
     * Notification system to validate the order remotely
     */
    public function postProcess()
    {
        //return true;
        $env = realpath("/var/.env");
        $config = parse_ini_file($env, true);
        $this->token = $config["PAYPHONE_API_TOKEN"];
        $this->url = $config["PAYPHONE_API_URL"];
        $this->urlToken = $config["PAYPHONE_API_TOKEN_URL"];
        $this->passCode = $config["PAYPHONE_API_PASSCODE"];
        $this->storeId = $config["PAYPHONE_API_STORE_ID"];

        //return $this->showErrors($order,"Tienda se encuentra fuera de su horario de atención.");
        $log = new LoggerTools();
        $cart = $this->context->cart;
        $datos_card_token = null;
        //return $this->module->validateOrder($cart_id, $payment_status, $amount, $module_name, $message, array(), $currency_id, false, $secure_key);

        try {
            $add_card = Tools::getIsset('add_card') ? Tools::getValue("add_card") : null;

            $id_card = Tools::getValue('id_card',null);
            $cardHolder = Tools::getValue('cardHolder',null);
            $dataEnc = Tools::getValue('data');
            $log->add("TOOLS dataEnc: ".$dataEnc);
            $log->add("id_card: ".$id_card);


            $deferredType = Tools::getValue('deferred') ? Tools::getValue('deferred') : "00000000";
            $log->add("TOOLS deferredType: ".$deferredType);

            if (!$id_card){
                if (!$dataEnc && !$deferredType){
                    return $this->showErrors(null,"Datos de la tarjeta incorrectos.");
                }
            }else{
                $datos_card_token = $this->getDataCard($id_card);
            }

            if ($this->module->active == false) {
                die;
            }

            $cart = $this->context->cart;
            $cart_id = (int) $cart->id;


            if (!Validate::isLoadedObject($cart))
                Tools::redirect('index.php?controller=order');

            if (!$this->module->checkCurrency($cart))
                Tools::redirect('index.php?controller=order');

            if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
                Tools::redirect('index.php?controller=order&step=1');

            $authorized = false;
            foreach (Module::getPaymentModules() as $module)
                if ($module['name'] == 'tarjetas_payphone') {
                    $authorized = true;
                    break;
                }

            if (!$authorized)
                    die($this->module->l('This payment method is not available.', 'validation'));

            $customer = new Customer($cart->id_customer);

            $resValidateHorario = $this->isOpen();
            if (!$resValidateHorario){
                return $this->showErrors(null,"Tienda se encuentra fuera de su horario de atención.");
            }

            //return $this->showErrors(null,"Tienda se encuentra fuera de su horario de atención.");

            if (!Validate::isLoadedObject($customer))
                Tools::redirect('index.php?controller=order&step=1');

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
            if ($resValidateArticulos){
                return $this->showErrors(null,$resValidateArticulos);
            }

            $this->module->validateOrder((int) $cart->id, Configuration::get('PS_PAYPHONE_PENDING'), $total, $this->module->displayName, NULL, array(), (int) $currency->id, false, $customer->secure_key);

            $id_order = Order::getOrderByCartId($cart->id);
            $order = new Order($id_order);
            if (!Validate::isLoadedObject($order)) {
                Tools::redirect('index.php?controller=order&step=1');
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
            return $this->showErrors($order, "Error en el servidor al procesar la transacción");
        }


        try {
            $json = json_encode($data);
            $responsePay = $this->ApiPay($json);

            //->cardToken
            //var_dump($responsePay);
            //exit;
            
            if (isset($responsePay->authorizationCode)) {
               $this->asentarPagoEnDB($responsePay,$order,$cart,$customer,$data,$total,$add_card,$dataEnc,$datos_card_token); 
            } else {
                $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
                return $this->showErrors(null, "OCURRIÓ UN INCONVENIENTE AL PROCESAR LA TRANSACCIÓN"); 
            }
        } catch (Exception $e) {
            PrestaShopLogger::addLog("PayPhone check payment error", 3);
            return $this->showErrors($order, "PayPhone check payment error");
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
            //$this->eliminarPedidoMiPiloto($guia_numero);
            $this->reversePayphone($data->transactionId);
            return $this->showErrors(null,'Ocurrió un inconveniente en el proceso de pago. Se ha cancelado tu pedido y se ha revertido tu pago. Disculpa las molestias. Vuelve a intentarlo más tarde.');
        } else {
            //$this->addNumGuiaMiPilotoOrder($order,$guia_numero);
            $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_APPROVED'),true);
            //$this->module->validateOrder((int) $cart->id, Configuration::get('PS_PAYPHONE_PENDING'), $total, $this->module->displayName, NULL, array(), (int) $currency->id, false, $customer->secure_key);

            if ($dataEnc && !$datos_card_token)
                if ($add_card == "on")
                    $this->saveCard($data->cardToken);

            Tools::redirect('index.php?controller=order-confirmation&id_cart=' . (int) $cart->id . '&id_module=' . (int) $this->module->id . '&id_order=' . $order->id . '&hora_llegada='. $hora_llegada . '&key=' . $customer->secure_key . '&cct='.$data->cardToken);
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

        Db::getInstance()->insert('credit_card_tokens', $write);
    }


    private function reversePayphone($transactionId) {
        $payphone = new PayPhoneValidationModuleFrontController();
        $payphone->reversePayphone($transactionId);
    }


    public function ApiPay($post_data, $content_type = "application/json") {
        $log = new LoggerTools();

        $log->add("REQUEST API Payphone DATA: ".$post_data);
        $log->add("URL: ".$this->url);

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


    function validateProductos($order){
        $resValidateArticulos = $this->validateArticulosSAP($order);
        if ($resValidateArticulos){
            return $this->showErrors($order,$resValidateArticulos);
        }
    }


    function validateArticulosSAP($order) {
        $ValidacionProductosBeforefacturaSAP = new sincronizacionwebservicesValidacionProductosBeforeFacturaSAPModuleFrontController();
        return $ValidacionProductosBeforefacturaSAP->validate($order);
    }

    /*function validateHorario() {
        $ValidacionProductosBeforefacturaSAP = new sincronizacionwebservicesValidacionProductosBeforeFacturaSAPModuleFrontController();
        return $ValidacionProductosBeforefacturaSAP->validate($order);
    }*/


    protected function getHourStoreCurrent()
    {

        return  Db::getInstance()->executeS('
         SELECT sl.`hours`
         FROM `ps_store_shop` as stsh
         LEFT JOIN `ps_shop` as sh
         ON sh.`id_shop` = stsh.`id_shop`
         LEFT JOIN `ps_store_lang` as sl
         ON stsh.`id_store` = sl.`id_store`
         WHERE sh.`active` = 1 AND sh.`deleted` = 0 
         AND sl.`id_lang` = 1 AND stsh.`id_shop` = 4');
    }


    function showErrors($order, $message) {

        if ($order)
            $this->changeOrderStatus($order, Configuration::get('PS_OS_CANCELED'));
        $errors[] = $message;
        $this->context->smarty->assign([
            'errors' => $errors,
        ]);
        return $this->setTemplate('module:' . $this->module->name . '/views/templates/front/errors-messages17.tpl');
    }


    function changeOrderStatus($order, $state, $send_email_hook=false) {
        ShopUrl::cacheMainDomainForShop((int) $order->id_shop);
        $order_state = new OrderState($state);

        if (!Validate::isLoadedObject($order_state)) {
            $this->errors[] = Tools::displayError('The new order status is invalid.');
        }
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


    protected function isValidOrder()
    {
        /*
         * Add your checks right there
         */
        return true;
    }
}


class ErrorResponseModelTarjetas {

    public $message;
    public $errorCode;
    public $errorDescription;
    public $errorDescriptions;

}



