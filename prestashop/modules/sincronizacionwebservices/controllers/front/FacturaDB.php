<?php

require_once _PS_MODULE_DIR_ . 'payphone/lib/api/PayphoneButton.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/request/ConfirmSaleRequestModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/response/SaleGetResponseModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/common/PayPhoneWebException.php';
require_once _PS_MODULE_DIR_ . 'mipilotoshipping/mipilotoshipping.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/TabernaSOAP.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaSAP.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php';
require_once _PS_ROOT_DIR_ . '/config/taberna_config_facturacion.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
        
class sincronizacionwebservicesFacturaDBModuleFrontController extends ModuleFrontController {

    public $log = null;

    public function initContent() {
    	parent::initContent();

    	exit;
    	$this->setTemplate('productos.tpl');
    }

    public function processFacturacionDB($order,$cart,$write){
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


    public function processCreateRowsFacturacionDB($order,$cart,$address,$write){
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

    public function getSecuencialFactura(){
        $secuencial = 1;
        $result = Db::getInstance()->executeS('
         SELECT max(CONVERT(secuencial,UNSIGNED INTEGER)) as `secuencial`
         FROM `factura_cabecera` ');

        if (!empty($result)){
            $secuencial = $result[0]["secuencial"] + 1;
            if ($secuencial >= 20)
                $secuencial = $secuencial + 1;
        }
        $secuencial = str_pad($secuencial, 9, "0", STR_PAD_LEFT);
        return $secuencial;
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

    public function generateDataCabeceraFacturacion($order,$cart,$address,$secuencial,$write){
        //$shop = Context::getContext()->shop;
        $shop = $this->context->shop;
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

    public function insertDataFactura($data,$table){
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


    public function generateDataDetalleFacturacion($order,$cart,$address,$secuencial,$product){
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

    


}

