<?php

require_once _PS_MODULE_DIR_ . 'payphone/lib/api/PayphoneButton.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/request/ConfirmSaleRequestModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/response/SaleGetResponseModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/common/PayPhoneWebException.php';
require_once _PS_MODULE_DIR_ . 'mipilotoshipping/mipilotoshipping.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/TabernaSOAP.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaSAP.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaDB.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php';
require_once _PS_ROOT_DIR_ . '/config/taberna_config_facturacion.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
/**
 * Clase controladora del front-end para validar el pago después de regresar de Payphone
 */
class PayPhoneValidationModuleFrontController extends ModuleFrontController {

    private $data;
    private $payphone;
    public $log = null;
    /**
     * @see FrontController::initContent()
     */
    public function initContent() {
        $this->log = new LoggerTools();
        $this->payphone = new PayPhone();
        if ($this->payphone->active) {
            parent::initContent();
            if (Tools::getValue('id_order') != null)
                $this->checkPayment(Tools::getValue('id_order'));
            else
                $this->paymentResponse();
        }
    }

    /**
     * Verificar si el pago ha sido aprobado o cancelado estando en estado pendiente
     * @param type $id_order
     */
    private function checkPayment($id_order) {
        $data = $this->payphone->updatePendingTransaction($id_order);
        if (gettype($data) == "boolean" && $data)
            Tools::redirect('index.php?controller=history');
        else {
            $error = $this->payphone->l($data);
            $this->context->controller->addCss(_PS_MODULE_DIR_ . 'payphone/views/css/error.css', 'all');
            PrestaShopLogger::addLog("PayPhone check payment error = " . $error, 3);
            return $this->showErrors($error);
        }
    }

    /**
     * Obtiene el resultado de la respuesta del botón de pagos
     * Actualiza la orden según la respuesta y guarda datos de la respuesta en la BD
     */
    private function paymentResponse() {
        $id = Tools::getValue('id');
        $clientTransactionId = Tools::getValue('clientTransactionId');
        $msg = Tools::getValue('msg');
        if ($msg) {
            $cart = new Cart($this->context->cookie->payphone_cart_id);
            if (Validate::isLoadedObject($cart)) {
                $order = $this->getOrder($cart->id);
                if (Validate::isLoadedObject($order)) {
                    $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
                }
            }
            return $this->showErrors($msg);
        }
        if (!$id || !$clientTransactionId) {
            PrestaShopLogger::addLog("PayPhone validation controller error = id or clienteTransactionId null", 2);
            return $this->showErrors($this->payphone->l('Error connecting to PayPhone', 'validation'));
        }

        $config = ConfigurationManager::Instance();
        $config->Token = Configuration::get('PAYPHONE_TOKEN');
        $pb = new PayphoneButton();
        try {
            $result = $pb->Confirm($id);
            $cart = new Cart($this->context->cookie->payphone_cart_id);
            if (!Validate::isLoadedObject($cart))
                return;
            $order = $this->getOrder($cart->id);
            if (!Validate::isLoadedObject($order))
                return;
            $customer = new Customer($cart->id_customer);
            if (!Validate::isLoadedObject($customer))
                return;
            if ($result->statusCode == 3) {
                $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_APPROVED'));
            } elseif ($result->statusCode == 2) {
                $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
            }
            $data = $result;
            $write = array();
            $write['transaction_id'] = (int) $data->transactionId;
            if ($data->statusCode == 2)
                $write['message'] = $data->message;
            else
                $write['message'] = "Aprobada";
            $write['phone_number'] = pSQL($data->phoneNumber);
            $write['transaction_status'] = pSQL($data->transactionStatus);
            $write['client_transaction_id'] = pSQL($data->clientTransactionId);
            $write['client_user_id'] = pSQL($cart->id);
            $write['deferred'] = $data->deferred;
            $write['amount'] = $data->amount;
            $write['date_add'] = date("Y-m-d H:i:s");
            if ($data->deferred)
                $write['deferred_message'] = pSQL($data->deferredMessage);
            if ($data->statusCode == 3) {
                $write['bin'] = pSQL($data->bin);
                $write['card_bran'] = pSQL($data->cardBrand);
                $write['authorization_code'] = pSQL($data->authorizationCode);
            }
            Db::getInstance()->insert('payphone_transaction', $write);



            if ($data->statusCode != 3) {
                return $this->showErrors($this->payphone->l('Error con Payphone en el proceso de pago.Se ha cancelado su pedido. Vuelva a intentarlo más tarde.', 'validation'));
            }
            
            /********************** ADD HENRY EN UN PRINCIPIO *********************/           
            // add for Henry Campoverde
                /*$guia_numero = $this->notificarAMiPiloto($order,$cart);
                $this->log->add("*** guia_numero: ".$guia_numero);
                if ($guia_numero){
                    $resFacturacion = $this->processFacturacionDB($order,$cart,$write);
                    $this->log->add("processFacturacionDB : ".$resFacturacion);
                    if (!$resFacturacion) {
                        $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
                        $this->eliminarPedidoMiPiloto($guia_numero);
                        $this->reversePayphone($data->transactionId);
                        return $this->showErrors($this->payphone->l('Error en el proceso de pago. Se ha cancelado tu pedido y se ha revertido tu pago. Vuelve a intentarlo más tarde.', 'validation'));
                    } else {
                        $this->addNumGuiaMiPilotoOrder($order,$guia_numero);
                        Tools::redirect('index.php?controller=order-confirmation&id_cart=' . (int) $cart->id . '&id_module=' . (int) $this->module->id . '&id_order=' . $order->id . '&guia=' .$guia_numero. '&hora_llegada='. $hora_llegada . '&key=' . $customer->secure_key);
                    }
                } else {
                    $this->reversePayphone($data->transactionId);
                }*/



            /********************** ADD HENRY NUEVO *********************/           
            // add for Henry Campoverde
                //$guia_numero = $this->notificarAMiPiloto($order,$cart);
                //$this->log->add("*** guia_numero: ".$guia_numero);
                
                $facturaDB = new sincronizacionwebservicesFacturaDBModuleFrontController();
                $resFacturacion = $facturaDB->processFacturacionDB($order,$cart,$write);

                //$resFacturacion = $this->processFacturacionDB($order,$cart,$write);
                $this->log->add("processFacturacionDB : ".$resFacturacion);
                if (!$resFacturacion) {
                    $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
                    //$this->eliminarPedidoMiPiloto($guia_numero);
                    $this->reversePayphone($data->transactionId);
                    return $this->showErrors($this->payphone->l('Ocurrió un inconveniente en el proceso de pago. Se ha cancelado tu pedido y se ha revertido tu pago. Disculpa las molestias. Vuelve a intentarlo más tarde.', 'validation'));
                } else {
                    //$this->addNumGuiaMiPilotoOrder($order,$guia_numero);
                    Tools::redirect('index.php?controller=order-confirmation&id_cart=' . (int) $cart->id . '&id_module=' . (int) $this->module->id . '&id_order=' . $order->id . '&hora_llegada='. $hora_llegada . '&key=' . $customer->secure_key);
                }
                

            
        } catch (PayPhoneWebException $e) {
            PrestaShopLogger::addLog("PayPhone validation controller error = " . $e->ErrorList[0]->message, 2);
            $this->showErrors($e->ErrorList[0]->message);
        }
    }

    public function reversePayphone($transactionId) {
        $config = ConfigurationManager::Instance();
        $config->Token = Configuration::get('PAYPHONE_TOKEN');
        $pb = new PayphoneButton();
        try {
            $result = $pb->Reverse($transactionId);
        } catch (PayPhoneWebException $e) {
            PrestaShopLogger::addLog("PayPhone validation controller error = " . $e->ErrorList[0]->message, 2);
            $this->showErrors($e->ErrorList[0]->message);
        }
    }


    private function addNumGuiaMiPilotoOrder($order,$num_guia) {
        $orderDB = Db::getInstance()->executeS("
        UPDATE ps_orders o
        SET num_guia=".$num_guia." WHERE o.id_order =". $order->id);

        return $orderDB;
    }

    private function getQuantityProductsCart($cart) {
        $quantity_total = 0;
        $products = $cart->getProducts(true);
        foreach ($products as $product) {
            $quantity_total += (int)$product["cart_quantity"];
        }
        return $quantity_total;
    }


    private function getTipoVehiculo($quantity) {
        $tipo = 1;
        if ($quantity > LIMITE_CANTIDAD_PRODUCTOS_TIPO_VEHICULO) {
            $tipo = 2;
        }
        return $tipo;
    }

    private function getTipoProducto($quantity) {
        $tipo = 2;
        if ($quantity > LIMITE_CANTIDAD_PRODUCTOS_TIPO_VEHICULO) {
            $tipo = 3;
        }
        return $tipo;
    }

    private function notificarAMiPiloto($order,$cart){
    // MI piloto Henry Campoverde add
        $result = null;
        $mipiloto = new Mipilotoshipping();
        $resultadoAgendar = $mipiloto->agendarPedido($order,$cart);
        $guia_numero=0; $hora_llegada = 0;
        if (!empty($resultadoAgendar)){
            $guia_numero = $resultadoAgendar->guia_numero;
            $quantity = $this->getQuantityProductsCart($cart);
            $tipo_vehiculo = $this->getTipoVehiculo($quantity); //1-Moto 2-Carro
            $tipo_producto = $this->getTipoProducto($quantity); //1-Sobre 2-Pequeno 3-Grande 4-Comida
            $tiempo_llegada = 60;
            $resultadoActivar = $mipiloto->activarPedido($guia_numero,$tipo_vehiculo,$tipo_producto,$tiempo_llegada);
            if (!empty($resultadoActivar)){
                $guia_numero = $resultadoActivar->guia_numero;
                $hora_llegada = $resultadoActivar->hora_llegada;
                //$this->changeOrderStatus($order, 17); 
                $result = $guia_numero;
            }
        }
        return $result;
    // FIN MI Piloto
    }

    private function eliminarPedidoMiPiloto($guia_numero){
    // MI piloto Henry Campoverde add
        $result = null;
        $mipiloto = new Mipilotoshipping();
        $resultadoEliminado = $mipiloto->eliminarPedido($guia_numero);
        $guia_numero=0; $hora_llegada = 0;
        if (!empty($resultadoEliminado)){
            $guia_numero = $resultadoEliminado->guia_numero;
            if ($guia_numero)
                $result = true;
        }
        return $result;
    // FIN MI Piloto
    }


    private function generateParamsCustomer($address){
        
        $customer = Context::getContext()->customer;
        if ($address->type_dni == "Cédula")
            $type_dni = "02";
        elseif ($address->type_dni == "Ruc") {
            $type_dni = "01";
        }else{
            $type_dni = "03";
        }
        
        return array(
            "PaBarr"    =>  DISTRITO, //distrito
            "PaBran1"   =>  "",
            "PA_BRSCH"  =>  "",
            "PaCelu"    =>  $address->phone_mobile,
            "PaEmail"   =>  $address->email,
            "PaExt1"    =>  "",
            "PaExt2"    =>  "",
            "PaFamst"   =>  "0",
            "PaFax"     =>  "",
            "PaFityp"   =>  ($address->type_dni == "Cédula" || $address->type_dni == "Pasaporte") ? "PN" : "PJ",
            "PaKdgrp"   =>  "01",
            "PaKtokd"   =>  "DRET",
            "PaParge"   =>  $customer->id_gender,
            "PaParh1"   =>  "I",
            "PaPobl"    =>  "101",
            "PaPriap"   =>  $address->lastname,
            "PaPrinom"  =>  $address->firstname,
            "PaRecco"   =>  "SI",
            "PaRegi"    =>  "01",
            "PaStcd1"   =>  $address->dni,
            "PaStcdt"   =>  $type_dni,
            "PaStr1"    =>  $address->address1,
            "PaStr2"    =>  "",
            "PaStr3"    =>  "",
            "PaTel1"    =>  $address->phone,
            "PaTel2"    =>  "000",
            "PaTrata"   =>  "002",
            "PA_UCAJA"  =>  CEDULA_CAJERO,
            "PA_VKBUR"  =>  "ZPRU"
        );
    }


    private function processFacturacionDB($order,$cart,$write){
        $response = false;
        $tabernaSoap = new sincronizacionwebservicesTabernaSoapModuleFrontController();
        $id_address_invoice = $cart->id_address_invoice;
        $address = new Address($id_address_invoice);
        $dni = $address->dni;
        $type_dni = $address->type_dni;
        $existCustomer = $tabernaSoap->existCustomer($dni);
        if ($existCustomer["status"] == 0){ // Si existe cliente, obtenemos los datos
            $datosCliente = $existCustomer["result"];
            if ($this->processCreateRowsFacturacionDB($order,$cart,$address,$write)){
                $response = true;
            }
            //$params = $this->generateParamsCustomer($address);
        }elseif ($existCustomer["status"] == 1){ // No existe el cliente debemos crearlo
            $params = $this->generateParamsCustomer($address);
            $res_creacion_cliente = $tabernaSoap->createCustomer($params);
            if (trim($res_creacion_cliente[0]) != "1" ){
                if ($this->processCreateRowsFacturacionDB($order,$cart,$address,$write)){
                    $response = true;
                }
            }
        }

        return $response;
    }


    private function processCreateRowsFacturacionDB($order,$cart,$address,$write){
        $result = false;
        $secuencial = $this->getSecuencialFactura();
        $data = $this->generateDataCabeceraFacturacion($order,$cart,$address,$secuencial,$write);
        $responseCabecera = $this->insertDataFactura($data,'factura_cabecera');
        if ($responseCabecera){
            $products = $order->getProducts();
            $lenProducts = count($products);
            $cont = 0;
            foreach ($products as $product) {
                $data = $this->generateDataDetalleFacturacion($order,$cart,$address,$secuencial,$product);
                if($this->insertDataFactura($data, 'factura_detalle')){
                    $cont++;
                }
            }
            if ($cont == $lenProducts){
                //if ($cart->id_carrier == "29") {
                if ($cart->id_carrier == "15") {
                    $data = $this->generateDataDetallePagoFacturacion($order,$secuencial);
                    if ($this->insertDataFactura($data, 'factura_detalle_pago'))
                        $result = true;
                } else {
                    $data = $this->generateDataItemTransporte($order,$secuencial);
                    if ($this->insertDataFactura($data, 'factura_detalle'))
                    {
                        $data = $this->generateDataDetallePagoFacturacion($order,$secuencial);
                        if ($this->insertDataFactura($data, 'factura_detalle_pago'))
                            $result = true;
                    }
                }
            }
            
        }
        return $result;
    }

    private function generateDataCabeceraFacturacion($order,$cart,$address,$secuencial,$write){
        $shop = Context::getContext()->shop;
        $address1 = $shop->address->address1;
        if ($address->type_dni == "Cédula")
            $type_dni = "05";
        elseif ($address->type_dni == "Ruc") {
            $type_dni = "04";
        }else{
            $type_dni = "06";
        }
        return array(
            'ruc_empresa' => RUC_EMPRESA,
            'ambiente' => AMBIENTE,
            'documento' => DOCUMENTO,
            'establecimiento' => ESTABLECIMIENTO,
            'punto_emision' => PUNTO_EMISION,
            'secuencial' => $secuencial,
            'razonsocial' => RAZON_SOCIAL,
            'nombre_comercial' => NOMBRE_COMERCIAL,
            'direccion_matriz' => DIRECCION_MATRIZ,
            'fecha_emision' => date("Y-m-d"),
            'direccion_establecimiento' => $shop->name . ' ' .$address1,
            'contribuyente_especial' => 0,
            'obligado_contabilidad' => 'SI',
            'tipo_id_comprador' => $type_dni,
            'razonsocial_comprador' => $address->firstname . " ". $address->lastname,
            'identificacion_comprador' => $address->dni,
            'total_sin_impuestos' => $order->total_paid_tax_excl,
            'total_descuentos' => 0,
            'descuento_adicional' => 0,
            'propina' => 0,
            'importe_total' => $order->total_paid,
            'moneda' => 'dolar',
            'direccion' => $address->address1,
            'telefono' => $address->phone,
            'email' => $address->email,
            'transaction_id_pay' => $write["transaction_id"],
            'id_shop' => $shop->id,
            'id_order' => $order->id
        );
    }


    private function generateDataItemTransporte($order,$secuencial){
        return array(
            'ruc_empresa' => RUC_EMPRESA,
            'ambiente' => AMBIENTE,
            'documento' => DOCUMENTO,
            'establecimiento' => ESTABLECIMIENTO,
            'punto_emision' => PUNTO_EMISION,
            'secuencial' => $secuencial,
            'codigo_principal' => SERV_VARIOS_TABERNAS,
            'descripcion' => SERV_VARIOS_TABERNAS_DESCRIPCION,
            'cantidad' => 1,
            'precio' => $order->total_shipping_tax_excl,
            'descuento' => 0,
            'total_sin_impuesto' => $order->total_shipping_tax_excl,
            'codigo_impuesto_iva' => 2,
            'codigo_porcentaje_iva' => 2,
            'tarifa_iva' => 12,
            'base_imponible_iva' => $order->total_shipping_tax_excl,
            'valor_iva' => $order->total_shipping_tax_incl - $order->total_shipping_tax_excl
        );   
    }

    private function generateDataDetalleFacturacion($order,$cart,$address,$secuencial,$product){
        return array(
            'ruc_empresa' => RUC_EMPRESA,
            'ambiente' => AMBIENTE,
            'documento' => DOCUMENTO,
            'establecimiento' => ESTABLECIMIENTO,
            'punto_emision' => PUNTO_EMISION,
            'secuencial' => $secuencial,
            'codigo_principal' => $product["product_reference"],
            'descripcion' => $product["product_name"],
            'cantidad' => $product["product_quantity"],
            'precio' => $product["product_price"],
            'descuento' => 0,
            'total_sin_impuesto' => $product["total_price_tax_excl"],
            'codigo_impuesto_iva' => 2,
            'codigo_porcentaje_iva' => 2,
            'tarifa_iva' => 12,
            'base_imponible_iva' => $product["total_price_tax_excl"],
            'valor_iva' => $product["total_price_tax_incl"] - $product["total_price_tax_excl"]
        );
    }


    private function generateDataDetallePagoFacturacion($order,$secuencial){
        return array(
            'ruc_empresa' => RUC_EMPRESA,
            'ambiente' => AMBIENTE,
            'documento' => DOCUMENTO,
            'establecimiento' => ESTABLECIMIENTO,
            'punto_emision' => PUNTO_EMISION,
            'secuencial' => $secuencial,
            'forma_pago' => '20',
            'descripcion' => 'OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO',
            'valor' => $order->total_paid_tax_incl,
            'plazo' => 0,
            'tiempo' => 'DIAS'
        );
    }


    private function getSecuencialFactura(){
        $secuencial = 1;
        $result = Db::getInstance()->executeS('
         SELECT max(CONVERT(secuencial,UNSIGNED INTEGER)) as `secuencial`
         FROM `factura_cabecera` ');

        if (!empty($result)){
            $secuencial = $result[0]["secuencial"] + 1;
        }
        $secuencial = str_pad($secuencial, 9, "0", STR_PAD_LEFT);
        return $secuencial;
    }


    private function insertDataFactura($data,$table){
        $result = false;
        try {
            if (Db::getInstance()->insert($table, $data, false, true, Db::INSERT, false)){
                $result = true;
            }
        } catch (PayPhoneWebException $e) {
            PrestaShopLogger::addLog("No se guardó ". $table ." en facturacion DB", 2);
            $this->showErrors("No se guardó ". $table ." en facturacion DB");
        }
        return $result;
    }


    /**
     * Cambia el estado de la orden
     * @param type $order
     * @param type $state
     */
    private function changeOrderStatus($order, $state) {
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
    }

    /**
     * Obtiene una orden dado el identicador del carrito de compra
     * @param int $cart_id
     * @return $order @see Order
     */
    private function getOrder($cart_id) {
        $order_id = Order::getOrderByCartId($cart_id);
        $order = new Order($order_id);
        if (!Validate::isLoadedObject($order)) {
            $this->errors[] = Tools::displayError('The order cannot be found within your database.');
            return;
        }
        return $order;
    }

    protected function showErrors($message) {
        $errors[] = $message;
        $this->context->smarty->assign([
            'errors' => $errors,
        ]);
        return $this->setTemplate('module:' . $this->module->name . '/views/templates/front/errors-messages17.tpl');
    }

}
