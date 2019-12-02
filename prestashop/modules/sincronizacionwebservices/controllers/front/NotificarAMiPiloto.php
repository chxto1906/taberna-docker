<?php
    
//require_once _PS_MODULE_DIR_ . 'mipilotoshipping/curl/curl_mipiloto.php';




include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/TabernaSOAP.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
require_once _PS_ROOT_DIR_ . '/config/taberna_config_facturacion.php';
require_once _PS_ROOT_DIR_ . '/config/error_intentos.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
require_once _PS_MODULE_DIR_ . 'mipilotoshipping/mipilotoshipping.php';
require_once _PS_MODULE_DIR_ . 'rvproductstab/rvproductstab.php';
require_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/nusoap.php');
        
class sincronizacionwebservicesNotificarAMiPilotoModuleFrontController extends ModuleFrontController {

    public $log = null;

    public function initContent() {
    	parent::initContent();

        include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');


        echo "Empieza Notificar A Mi PILOTO - Aux " . date('m/d/Y G:i:s a', time()) . "<br>";
        echo "<hr>";
        
        $id_carrier = $this->context->cart->id_carrier;
        echo "<br>ID_CARRIER: ".$id_carrier."<br>";

        $this->log = new LoggerTools();

        
        $this->log->add("Empieza FacturaSAP " . date('m/d/Y G:i:s a', time()) . "<br>");

        $factura_id = Tools::getValue('factura_id');
        
        $factura = $this->getFactura($factura_id);
        //var_dump($factura);
        //exit;
        if (!empty($factura)){
            $this->recorrerFactura($factura);
        }else{
            echo "<br>NO ENCONTRADO Num. FACTURA<br>";
        }
        
        $respuesta = array('status'=>true);
        echo json_encode($respuesta);

    	exit;
    	$this->setTemplate('productos.tpl');
    }


    private function recorrerFactura($facturas) {
        $in = 0;
        foreach ($facturas as $factura) {
            $in++;

            //$detalle = $this->getDetalleFacturas($factura["establecimiento"],$factura["punto_emision"],$factura["secuencial"]);

            $payment = $this->getPaymentPayphone($factura["transaction_id_pay"]);
            echo "<br>***PAYMENT payphone return***<br>";
            var_dump($payment);
            $efectivo = count($payment) > 0 ? false : true;
            $payment[0] = count($payment) > 0 ? $payment[0] : "";

            echo "<br>PAYENT[0] **<br>";
            var_dump($payment[0]);

            echo "<br>¿EFECTIVO?: $efectivo<br>";

            $order = new Order($factura["id_order"]);

            if ($order->id){

                $guia_numero = $this->notificarAMiPiloto($order,$efectivo);
                if ($guia_numero) {
                    $agregadoGuiaOrder = $this->addNumGuiaMiPilotoOrder($order,$guia_numero);
                    if ($agregadoGuiaOrder) {
                        $this->log->add("Se ha agregado correctamente a la orden: ".$factura["id_order"]." el número de guía: ".$guia_numero);    
                    }else{
                        $this->log->add("NO se pudo agregar correctamente a la orden: ".$factura["id_order"]." el número de guía: ".$guia_numero);
                    }
                } else {
                    $this->log->add("No se pudo obtener el guia_numero desde Mi Piloto, por lo tanto NO se notificó pedido a Mi Piloto. Reportar inmediatamente error a Mi Piloto. ORDEN Número: ".$factura["id_order"]);
                }
            }

        }
    }


    

    private function notificarAMiPiloto($order,$efectivo){
    // MI piloto Henry Campoverde add

        $env = realpath("/var/.env");
        $config = parse_ini_file($env, true);
        if ($config["FACTURACION_AMBIENTE"] == 2) {
            echo "AMBIENTE PRODUCCION";
            $log = new LoggerTools();
            $result = null;
            $mipiloto = new Mipilotoshipping();
            $resultadoAgendar = $mipiloto->agendarPedido($order,$efectivo);
            $guia_numero=0; $hora_llegada = 0;
            $log->add("entro a notificarAMiPiloto");
            if ($resultadoAgendar){
                $guia_numero = $resultadoAgendar->guia_numero;
                $quantity = $this->getQuantityProductsCart($order);
                $tipo_vehiculo = $this->getTipoVehiculo($quantity); //1-Moto 2-Carro
                $tipo_producto = $this->getTipoProducto($quantity); //1-Sobre 2-Pequeno 3-Grande 4-Comida
                $tiempo_llegada = 60;
                $resultadoActivar = $mipiloto->activarPedido($guia_numero,$tipo_vehiculo,$tipo_producto,$tiempo_llegada);
                if (!empty($resultadoActivar)){
                    echo "<br>RESULTADO ACTIVAR ***<br>";
                    var_dump($resultadoActivar);
                    if (isset($resultadoActivar->guia_numero)) {
                        $guia_numero = $resultadoActivar->guia_numero;
                        $hora_llegada = $resultadoActivar->hora_llegada;
                        //$this->changeOrderStatus($order, 17); 
                        $result = $guia_numero;    
                    } else {
                        $log->add("NO devuelve numero_guia para poder activar PEDIDO MI Piloto");
                    }
                }
            }
        }else{
            $result = "desarrollo";
        }
        return $result;
    // FIN MI Piloto
    }


    private function addNumGuiaMiPilotoOrder($order,$num_guia) {
        $orderDB = Db::getInstance()->executeS("
        UPDATE ps_orders o
        SET num_guia=".$num_guia." WHERE o.id_order =". $order->id);

        return $orderDB;
    }

    private function getQuantityProductsCart($order) {
        $quantity_total = 0;
        $products = $order->getProducts();
        foreach ($products as $product) {
            $quantity_total += (int)$product["product_quantity"];
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
    


    private function getPaymentPayphone($transaction_id_pay){
        $payment = Db::getInstance()->executeS('
        SELECT * 
        FROM '._DB_PREFIX_.'payphone_transaction AS pt
        WHERE pt.transaction_id ='. (int) $transaction_id_pay);

        return $payment;
    }

    private function getDetalleFacturas($establecimiento,$punto_emision,$secuencial){
        $detalles = Db::getInstance()->executeS('
        SELECT * 
        FROM factura_detalle AS fd
        WHERE fd.establecimiento ="'. $establecimiento . '" AND fd.punto_emision = "'. 
        $punto_emision . '" AND fd.secuencial ="' . $secuencial.'"');

        return $detalles;
    }

    

    private function getFactura($id) {
        $facturas = Db::getInstance()->executeS('
        SELECT * 
        FROM factura_cabecera AS fc
        WHERE id = '.$id);

        return $facturas;
    }

    


}

