<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';

require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/ValidacionProductosBeforeFacturaSAP.php';
require_once _PS_MODULE_DIR_ . 'ps_cashondelivery/ps_cashondelivery.php';
require_once _PS_MODULE_DIR_ . 'ps_cashondelivery/controllers/front/validation.php';
header('Content-Type: application/json');
        
class Webservice_AppPaymentContraEntregaModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $product = null;
    private $status_code = 400;
    public $module = null;

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
            $this->process_payment();
        }
        
        echo $response->json_response($this->content,$this->status_code);
    }

    public function process_payment() {

        $this->log = new LoggerTools();
        $moduleCash = new Ps_Cashondelivery();
        if ($this->context->cart->id_customer == 0 || $this->context->cart->id_address_delivery == 0 || $this->context->cart->id_address_invoice == 0 || !$moduleCash->active) {
            $this->content = ["message" => "Carrito no es válido o módulo de pago está deshabilitado"];
            return;
        }

        // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'ps_cashondelivery') {
                $authorized = true;
                break;
            }
        }
        if (!$authorized) {
            $this->content = ["message" => "Este método de pago no está disponible"];
            return;
        }
        $customer = new Customer($this->context->cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            $this->content = ["message" => "Cliente del carrito no es válido."];
            return;
        }

        $resValidateHorario = $this->isOpen();
        if (!$resValidateHorario){
            $this->content = ["message" => "Tienda se encuentra fuera de su horario de atención."];
            return;
        }

        $customer = new Customer((int)$this->context->cart->id_customer);
        $total = $this->context->cart->getOrderTotal(true, Cart::BOTH);
        $cart = $this->context->cart;
        $resValidateArticulos = $this->validateArticulosSAP($cart);
        if ($resValidateArticulos) {
            $this->content = ["message" => $resValidateArticulos];
            return;
        }

        try{

            $respValido = $moduleCash->validateOrder((int)$this->context->cart->id, Configuration::get('PS_OS_PREPARATION'), $total, $moduleCash->displayName, null, array(), null, false, $customer->secure_key,null,true);

            if ($respValido !== true){
                $this->content["message"] = $respValido;
                $this->process_session_data();
                return;
            }
        }catch (Exception $e) {
            $this->content["message"] = "Ocurrió un problema al procesar el pago";
            $this->process_session_data();
            echo $response->json_response($this->content,400);
            return;
        }

        /********************** ADD HENRY NUEVO *********************/           
        // add for Henry Campoverde
        //$guia_numero = $this->notificarAMiPiloto($order,$cart);
        //$this->log->add("*** guia_numero: ".$guia_numero);

        $id_order = Order::getOrderByCartId($cart->id);
        $order = new Order($id_order);
        if (!Validate::isLoadedObject($order)) {
            $this->content = ["message" => "No se pudo validar orden"];
            $this->process_session_data();
            return;
        }
        
        $facturaDB = new sincronizacionwebservicesFacturaDBModuleFrontController();
        $write = array("transaction_id" => $id_order);
        $resFacturacion = $facturaDB->processFacturacionDB($order,$cart,$write);

        
        $this->log->add("EFECTIVO contraentrega - processFacturacionDB : ".$resFacturacion);
        if (!$resFacturacion) {
            $this->changeOrderStatus($order, 6); //6 Order Cancelada
            //$this->eliminarPedidoMiPiloto($guia_numero);
            //$this->reversePayphone($data->transactionId);
            $this->content = ["message" => 'Ocurrió un inconveniente al guardar datos de la orden. Disculpa las molestias. Vuelve a intentarlo más tarde.'];
            $this->process_session_data();
        } else {
            //$this->addNumGuiaMiPilotoOrder($order,$guia_numero);
            $this->changeOrderStatus($order, 2, true);
            //Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
            $this->status_code = 201;
            $this->content = ["message" => "Se ha procesado el pago correctamente."];
            $this->process_session_data();
        }

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
                                'cart_id'       => $customer->cart_id,
                                'random'        => $this->generateRandom(5)
                            );
        $context_session_str = json_encode((object)$context_session);
        $context_session_encrypt = $this->openCypher('encrypt',$context_session_str);


        $this->content["new_session_data"] = $context_session_encrypt;

        
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


    function validateArticulosSAP($order) {
        $ValidacionProductosBeforefacturaSAP = new sincronizacionwebservicesValidacionProductosBeforeFacturaSAPModuleFrontController();
        return $ValidacionProductosBeforefacturaSAP->validate($order);
    }





}

