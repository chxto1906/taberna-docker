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
        
class sincronizacionwebservicesFacturaSAPModuleFrontController extends ModuleFrontController {

    public $log = null;

    public function initContent() {
    	parent::initContent();

        include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');

        echo "Empieza FacturaSAP " . date('m/d/Y G:i:s a', time()) . "<br>";
        echo "<hr>";
        
        $id_carrier = $this->context->cart->id_carrier;
        echo "<br>ID_CARRIER: ".$id_carrier."<br>";

        $this->log = new LoggerTools();

        
        $this->log->add("Empieza FacturaSAP " . date('m/d/Y G:i:s a', time()) . "<br>");

        $auth_sri = Tools::getValue('auth_sri');

        if (!$auth_sri){
            $facturas = $this->getFacturas();
            if (!empty($facturas)){
                $this->recorrerFacturas($facturas);
            }
        } else {
            $facturas = $this->getFacturasCompletas();
            if (!empty($facturas)){
                $this->recorrerFacturasCompletas($facturas);
            }
        }
        $respuesta = array('status'=>true);
        echo json_encode($respuesta);

    	exit;
    	$this->setTemplate('productos.tpl');
    }

    
    private function recorrerFacturasCompletas($facturas) {
        foreach ($facturas as $factura) {
            $dataUpdateAutoSRISAP = $this->generateDataAutoSRISAP($factura);
            $resAutoSRI = $this->doActualizacionAutoSriSAP($dataUpdateAutoSRISAP);
            echo "<br>****ACTUALIZACION AUTO SRI: ";
            var_dump($resAutoSRI);
        }
    }


    private function recorrerFacturas($facturas) {
        $in = 0;
        $env = realpath("/var/.env");
        $config = parse_ini_file($env, true);
        foreach ($facturas as $factura) {
            $in++;
            var_dump($factura);
            $detalle = $this->getDetalleFacturas($factura["establecimiento"],$factura["punto_emision"],$factura["secuencial"]);


            $payment = $this->getPaymentPayphone($factura["transaction_id_pay"]);
            echo "<br>***PAYMENT payphone return***<br>";
            var_dump($payment);
            $efectivo = count($payment) > 0 ? false : true;
            $payment[0] = count($payment) > 0 ? $payment[0] : "";

            echo "<br>PAYENT[0] **<br>";
            var_dump($payment[0]);

            echo "<br>¿EFECTIVO?: $efectivo<br>";

            $dataFacturaSAP = $this->generateDataFacturaSAP($factura,$detalle,$payment[0]);
            echo '<pre>' . var_export($dataFacturaSAP, true) . '</pre>';
            echo "<hr>";
            var_dump(json_encode($dataFacturaSAP));


            ///////////////// MI PILOTO /////////////////
                $order = new Order($factura["id_order"]);
                if (!$order->num_guia) {
                    //modificar cuando se agregue otro método de envío //HENRY
                    if ($order->id_carrier == $config["CARRIER_MIPILOTO_ID"])
                        $guia_numero = $this->notificarAMiPiloto($order,$efectivo);
                    else
                        $guia_numero = "no-guia";
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
            ///////////////// MI PILOTO /////////////////



            $this->log->add("generateDataFacturaSAP: ".json_encode($dataFacturaSAP));
            echo "factura pedido: ".$factura["numero_pedido"];
            if (is_array($dataFacturaSAP)){
                if (empty($factura["numero_pedido"])){

                    echo "<br>Encontrada factura con numero_pedido vacío.<br>";
                    $resDoFacturaSAP = $this->doFacturaSAP($dataFacturaSAP);
                    echo "RESPUESTA factura sap<hr>";
                    var_dump($resDoFacturaSAP);
                    $this->log->add("doFacturaSAP: ".json_encode($resDoFacturaSAP));
                    if (is_array($resDoFacturaSAP)) {
                        if (trim($resDoFacturaSAP["0"]) == "0") {
                            $resSaveRespuestaFacturaSapdb = $this->save_respuesta_facturaSAP_db($resDoFacturaSAP,$factura);
                            $this->log->add("save_respuesta_facturaSAP_db: ".$resSaveRespuestaFacturaSapdb);
                            if ($resSaveRespuestaFacturaSapdb) {
                                $this->log->add("inicia recaudo");
                                $this->recaudoSap($resDoFacturaSAP,$factura,$payment[0],$efectivo);
                            }else{
                                echo "<br>No se pudo guardar FacturaSAP<br>";
                                $this->log->add("<br>No se pudo guardar FacturaSAP<br>");
                            }
                        }else{
                            echo "<br>doFacturaSAP no devuelve 0<br>";
                            $this->log->add("<br>doFacturaSAP no devuelve 0<br>");
                        }
                    }else{
                        echo "<br>doFacturaSAP responde y no es array, debe responder array con 0<br>";
                        $this->log->add("<br>doFacturaSAP responde y no es array, debe responder array con 0<br>");
                    }
                }else{
                    if (empty($factura["documento_contable_recaudo"])){
                        $dataResSapFactura = [
                            "0",
                            $factura["numero_pedido"],
                            $factura["nota_entrega"],
                            $factura["numero_factura_sap"],
                            $factura["numero_documento_contable"]
                        ];
                        $this->log->add("recaudoSap cuando ya está hecho la factura");
                        $this->recaudoSap($dataResSapFactura,$factura,$payment[0],$efectivo);
                    }
                }
            }else{
                echo "<br>No se pudo generar la data para la Factura SAP webservice.<br>";
                $this->log->add("<br>No se pudo generar la data para la Factura SAP webservice.<br>");
            }
        }
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
        SET num_guia='".$num_guia."' WHERE o.id_order =". $order->id);

        return $orderDB;
    }


    private function recaudoSap($resDoFacturaSAP,$factura,$payment,$efectivo) {
        $env = realpath("/var/.env");
        $config = parse_ini_file($env, true);

        $dataRecaudoSAP = $this->generateDataRecaudoSAP($resDoFacturaSAP,$factura,$payment,$efectivo);

        $resDoRecaudoSAP = $this->doRecaudoSAP($dataRecaudoSAP);
        $this->log->add($resDoRecaudoSAP);
        if (is_array($resDoRecaudoSAP)) {
            echo "fffffff--Recaudo--<br>";
            var_dump($resDoRecaudoSAP);
            if (trim($resDoRecaudoSAP["0"]) == "0") {
                $resSaveRespuestaRecaudoSapdb = $this->save_respuesta_recaudoSAP_db($resDoRecaudoSAP,$factura);
                if ($resSaveRespuestaRecaudoSapdb) {
                    echo "<br>Recaudo en el SAP generado exitosamente. Factura cabecera id: ".$factura["id"]."<br>"; 
                    $this->log->add("<br>Recaudo en el SAP generado exitosamente. Factura cabecera id: ".$factura["id"]."<br>");   

                    /*$order = new Order($factura["id_order"]);
                    //modificar cuando se agregue otro método de envío //HENRY
                    if ($order->id_carrier == $config["CARRIER_MIPILOTO_ID"])
                        $guia_numero = $this->notificarAMiPiloto($order,$efectivo);
                    else
                        $guia_numero = "no-guia";
                    if ($guia_numero) {
                        $agregadoGuiaOrder = $this->addNumGuiaMiPilotoOrder($order,$guia_numero);
                        if ($agregadoGuiaOrder) {
                            $this->log->add("Se ha agregado correctamente a la orden: ".$factura["id_order"]." el número de guía: ".$guia_numero);    
                        }else{
                            $this->log->add("NO se pudo agregar correctamente a la orden: ".$factura["id_order"]." el número de guía: ".$guia_numero);
                        }
                    } else {
                        $this->log->add("No se pudo obtener el guia_numero desde Mi Piloto, por lo tanto NO se notificó pedido a Mi Piloto. Reportar inmediatamente error a Mi Piloto. ORDEN Número: ".$factura["id_order"]);
                    }*/
                }else{
                    echo "<br>No se pudo guardar RecaudoSAP em DB<br>";
                    $this->log->add("<br>No se pudo guardar RecaudoSAP em DB<br>");
                }
            }else{
                echo "<br>doRecaudoSAP no devuelve 0<br>";
                $this->log->add("<br>doRecaudoSAP no devuelve 0<br>");
            }
        }else{
            echo "<br>doRecaudoSAP responde y no es array, debe responder array con 0<br>";
            $this->log->add("<br>doRecaudoSAP responde y no es array, debe responder array con 0<br>");
        }
    }

    private function generateDataAutoSRISAP($factura){
        $datetime = new DateTime($factura["fecha_autorizacion"]);
        $year = $datetime->format('Y');
        return array(
                'PAutorizacion'   => $factura["numero_autorizacion"],
                'PDocumento'      => $factura["numero_factura_sap"],
                'PFacma'          => "",
                'PFautorizacion'  => "",
                'PGjahr'          => $year,
                'PNoautorizado'   => "",
                'PXblnr'          => $factura["establecimiento"].$factura["punto_emision"].$factura["secuencial"],
                'PTipod'          => "01"
            );
    }


    private function save_respuesta_facturaSAP_db($resDoFacturaSAP,$factura){

    
        $facturaSap = Db::getInstance()->executeS("
        UPDATE factura_cabecera fc SET numero_pedido='".$resDoFacturaSAP[1]."',nota_entrega='".$resDoFacturaSAP[2]."',numero_factura_sap='".$resDoFacturaSAP[3]."',numero_documento_contable='".$resDoFacturaSAP[4]."' WHERE fc.id =". $factura["id"]);

        return $facturaSap;
    }

    private function save_respuesta_recaudoSAP_db($resDoRecaudoSAP,$factura){
        $recaudoSap = Db::getInstance()->executeS("
        UPDATE factura_cabecera fc
        SET documento_contable_recaudo=".$resDoRecaudoSAP[1]." WHERE fc.id =". $factura["id"]);

        return $recaudoSap;
    }


    private function doRecaudoSAP($data) {

        $attempts = 0;
        $faultG = null;

        do {


            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = $data;
            echo "<br>DATOS RECAUDO<br>";
            var_dump($params);

            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call(SERVICIO_RECAUDO, array($params), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                $attempts++;
                $this->log->add("FAULT doRecaudoSAP intento: ".$attempts);
                sleep(1);
                continue;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                    $attempts++;
                    $this->log->add("ERROR doRecaudoSAP intento: ".$attempts);
                    sleep(1);
                    continue;
                } else {
                    echo "<br>--Response Service SOAP Recaudo--<br>";
                    var_dump($response);
                    echo "<br>---------------------------------<br>";
                    $this->log->add("Response Service SOAP Recaudo: ".json_encode($response));
                    $res_service = SERVICIO_RECAUDO."Result";
                    $res = $response[$res_service];
                    return explode("|",$res);
                }
            }






            /*try {
                $client = new SoapClient(WS_SERVER);
                $params = $data;

                echo "<br>DATOS RECAUDO<br>";
                var_dump($params);

                $response = $client->__soapCall(SERVICIO_RECAUDO, array($params));

                echo "<br>--Response Service SOAP Recaudo--<br>";
                var_dump($response);
                echo "<br>---------------------------------<br>";

                $this->log->add("Response Service SOAP Recaudo: ".json_encode($response));
                $res_service = SERVICIO_RECAUDO."Result";
                //$res = $response->QAS_WS_POS_RECAUDOResult;
                $res = $response->{$res_service};
                return explode("|",$res);
            } catch (SoapFault $fault) {
                $attempts++;
                $this->log->add("CATCH doRecaudoSAP intento: ".$attempts);
                $faultG = $fault;
                sleep(1);
                continue;
                //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                
            }*/

            break;

        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS) {
            $this->log->add("CATCH doRecaudoSAP despues de $attempts intentos, no se pudo corregir error");
            //trigger_error("SOAP Fault: (faultcode: {$faultG->faultcode}, faultstring: {$faultG->faultstring})", E_USER_ERROR);
            //exit;
        }
    }


    private function doActualizacionAutoSriSAP($data) {

        $attempts = 0;
        $faultG = null;

        do {



            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = $data;
            echo "<br>PARAMS ACTUALIZACION AUTO SRI SOAP<br>";
            var_dump($params);

            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call(SERVICIO_ACTUALIZACION_AUTO_SRI_SOAP, array($params), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                $attempts++;
                $this->log->add("FAULT doActualizacionAutoSriSAP intento: ".$attempts);
                sleep(1);
                continue;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                    $attempts++;
                    $this->log->add("ERROR doActualizacionAutoSriSAP intento: ".$attempts);
                    sleep(1);
                    continue;
                } else {
                    echo "<br>--Response Service SOAP Actualizacion Auto SRI SOAP--<br>";
                    var_dump($response);
                    $this->log->add("Response Service SOAP Actualizacion Auto SRI SOAP: ");
                    echo "<br>---------------------------------<br>";
                    
                    return $response;
                }
            }




            /*
            try {
                $client = new SoapClient(WS_SERVER);
                $params = $data;

                echo "<br>PARAMS ACTUALIZACION AUTO SRI SOAP<br>";
                var_dump($params);

                $response = $client->__soapCall(SERVICIO_ACTUALIZACION_AUTO_SRI_SOAP, array($params));

                echo "<br>--Response Service SOAP Actualizacion Auto SRI SOAP--<br>";
                var_dump($response);
                $this->log->add("Response Service SOAP Actualizacion Auto SRI SOAP: ");
                echo "<br>---------------------------------<br>";

                return $response;

            } catch (SoapFault $fault) {
                $attempts++;
                $this->log->add("CATCH doActualizacionAutoSriSAP intento: ".$attempts);
                $faultG = $fault;
                sleep(1);
                continue;
            }*/

            break;

        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS) {
            $this->log->add("CATCH doActualizacionAutoSriSAP despues de $attempts intentos, no se pudo corregir error");
        }
    }



    private function doFacturaSAP($data) {

        $attempts = 0;
        $faultG = null;

        do {



            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = array('jsonData' => json_encode($data));
            echo "<hr>";
            echo "<br>Params doFacturaSAP<br>";
            var_dump($params);
            echo "<hr>";

            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call(SERVICIO_FACTURACION, array($params), '', '', false, true);

            echo "<br>FACTURACION Responseeeee *<br>";
            var_dump($response);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                $attempts++;
                $this->log->add("FAULT doFacturaSAP intento: ".$attempts);
                sleep(1);
                continue;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                    $attempts++;
                    $this->log->add("ERROR doFacturaSAP intento: ".$attempts);
                    sleep(1);
                    continue;
                } else {
                    $res_service = SERVICIO_FACTURACION."Result";
                    $res = $response[$res_service];
                    $this->log->add("Respuesta doFacturaSAP desde api rest: ".$res);
                    return explode("|",$res);
                }
            }





            /*
            try {
                $client = new SoapClient(WS_SERVER);
                $params = array('jsonData' => json_encode($data));
                echo "<hr>";
                echo "<br>Params doFacturaSAP<br>";
                var_dump($params);
                echo "<hr>";
                $response = $client->__soapCall(SERVICIO_FACTURACION, array($params));
                $res_service = SERVICIO_FACTURACION."Result";
                //$res = $response->QAS_WS_POS_FACTURA_BASEResult;
                $res = $response->{$res_service};

                $this->log->add("Respuesta doFacturaSAP desde api rest: ".$res);
                return explode("|",$res);
            } catch (SoapFault $fault) {
                $attempts++;
                $this->log->add("CATCH doFacturaSAP intento: ".$attempts);
                $faultG = $fault;
                sleep(1);
                continue;
                //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                
            }*/

            break;
        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS) {
            $this->log->add("CATCH doFacturaSAP despues de $attempts intentos, no se pudo corregir error");
        }
    }


    public function checkStock($cod_almacen,$cod_prod) {
        
        $attempts = 0;
        $faultG = null;
        $log = new LoggerTools();

        do {

            
            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = array("ALMACEN" => $cod_almacen,
                                "PRODUCTO" => $cod_prod);

            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call('Unitario', array($params), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                $attempts++;
                $this->log->add("FAULT checkStock intento: ".$attempts);
                sleep(1);
                continue;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                    $attempts++;
                    $this->log->add("ERROR checkStock intento: ".$attempts);
                    sleep(1);
                    continue;
                } else {
                    $res = json_decode($response["UnitarioResult"]);
                    $log->add("Respuesta checkStock: ".$response["UnitarioResult"]);
                    return $res;
                }
            }




            /*try {

                $client = new SoapClient(WS_SERVER);
                $params = array("ALMACEN" => $cod_almacen,
                                "PRODUCTO" => $cod_prod);
                $response = $client->__soapCall("Unitario", array($params));
                $res = json_decode($response->UnitarioResult);

                

                $log->add("Respuesta checkStock: ".$response->UnitarioResult);
                return $res;

                //return explode("|",$res);
            } catch (SoapFault $fault) {
                $attempts++;
                $this->log->add("CATCH checkStock intento: ".$attempts);
                $faultG = $fault;
                sleep(1);
                continue;
                //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                
            }*/

            break;

        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS){
            $this->log->add("CATCH checkStock despues de $attempts intentos, no se pudo corregir error");
        }


    }


    public function checkCombo($centro,$cod_prod) {
        
        $attempts = 0;
        $faultG = null;
        $log = new LoggerTools();

        do {

            try {

                $client = new SoapClient(WS_SERVER_TMP);
                $params = array("PCentro" => $centro,
                                "PMatnr" => $cod_prod);
                $response = $client->__soapCall("ZsdrfcPosConsultaCombos", array($params));
                $res = json_decode($response->ZsdrfcPosConsultaCombosResult);

                var_dump($res);

                $log->add("Respuesta combo: ".$response->ZsdrfcPosConsultaCombosResult);

                return $res;

                //return explode("|",$res);
            } catch (SoapFault $fault) {
                $attempts++;
                $this->log->add("CATCH checkCombo intento: ".$attempts);
                $faultG = $fault;
                sleep(1);
                continue;
                //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                
            }

            break;

        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS){
            $this->log->add("CATCH checkCombo despues de $attempts intentos, no se pudo corregir error");
        }


    }


    public function recuperaArticulo($cod_almacen,$cod_prod,$canal_venta="20") {
        
        $attempts = 0;
        $faultG = null;
        $log = new LoggerTools();

        do {



            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;


            $log->add("----------");
                $log->add("PLgort: ".$cod_almacen);
                $log->add("PMatnr: ".$cod_prod);
                $log->add("PVtweg: ".$canal_venta);
                $log->add("----------");

            $params = array("PLgort" => $cod_almacen,
                            "PMatnr" => $cod_prod,
                            "PVtweg" => $canal_venta);


            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call(SERVICIO_CONSULTA_MATERIAL, array($params), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                $attempts++;
                $log->add("FAULT recuperaArticulo intento: ".$attempts);
                sleep(1);
                continue;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                    $attempts++;
                    $log->add("ERROR recuperaArticulo intento: ".$attempts);
                    sleep(1);
                    continue;
                } else {
                    $res_service = SERVICIO_CONSULTA_MATERIAL."Result";
                    $res = $response[$res_service];
                    $log->add("RESPUESTA recuperaArticulo: ".$res);
                    return explode("|",$res);
                }
            }



            /*

            try {
                $log->add("----------");
                $log->add("PLgort: ".$cod_almacen);
                $log->add("PMatnr: ".$cod_prod);
                $log->add("PVtweg: ".$canal_venta);
                $log->add("----------");

                $client = new SoapClient(WS_SERVER);
                $params = array("PLgort" => $cod_almacen,
                                "PMatnr" => $cod_prod,
                                "PVtweg" => $canal_venta);
                $response = $client->__soapCall(SERVICIO_CONSULTA_MATERIAL, array($params));
                $res_service = SERVICIO_CONSULTA_MATERIAL."Result";
                //$res = $response->QAS_WS_POS_CONSULTA_MATERIAL3Result;
                $res = $response->{$res_service};
                $log->add("RESPUESTA recuperaArticulo: ".$res);
                return explode("|",$res);
            } catch (SoapFault $fault) {
                $attempts++;
                $log->add("CATCH recuperaArticulo intento: ".$attempts);
                $faultG = $fault;
                sleep(1);
                continue;
            }*/

            break;

        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS){
            $log->add("CATCH recuperaArticulo despues de $attempts intentos, no se pudo corregir error");
        }
    }

    /**** henry ****/
    public function validarLoteSerie($cod_almacen,$cod_prod,$flag=3,$lote="",$serie="") {
        
        $attempts = 0;
        $faultG = null;

        $log = new LoggerTools();

        do {



            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = array("PFlag"  => $flag,
                            "PLgort" => $cod_almacen,
                            "PLote"  => $lote,
                            "PMatnr" => $cod_prod,
                            "PSeri"  => $serie);

            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call(SERVICIO_CONSULTA_LOTE_SERIE, array($params), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                $attempts++;
                $log->add("FAULT validarLoteSerie intento: ".$attempts);
                sleep(1);
                continue;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                    $attempts++;
                    $log->add("ERROR validarLoteSerie intento: ".$attempts);
                    sleep(1);
                    continue;
                } else {
                    $res_service = SERVICIO_CONSULTA_LOTE_SERIE."Result";
                    $res = $response[$res_service];
                    $log->add("RESPUESTA validarLoteSerie: ".$res);
                    return $res;
                }
            }

            /*try {
                $client = new SoapClient(WS_SERVER);
                $params = array("PFlag"  => $flag,
                                "PLgort" => $cod_almacen,
                                "PLote"  => $lote,
                                "PMatnr" => $cod_prod,
                                "PSeri"  => $serie);
                $response = $client->__soapCall(SERVICIO_CONSULTA_LOTE_SERIE, array($params));
                $res_service = SERVICIO_CONSULTA_LOTE_SERIE."Result";
                //return $response->QAS_WS_POS_CONSULTA_LOTE_SERIEResult;
                
                $res = $response->{$res_service};
                $log->add("RESPUESTA validarLoteSerie: ".$res);
                return $res;
                //return explode("|",$res);
            } catch (SoapFault $fault) {
                $attempts++;
                $log->add("CATCH validarLoteSerie intento: ".$attempts);
                $faultG = $fault;
                sleep(1);
                continue;
            }*/

            break;

        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS){
            $log->add("CATCH validarLoteSerie despues de $attempts intentos, no se pudo corregir error");
            //exit;
        }

    }

    public function getFlag($indicador_lote, $perfil_serie) {
        $flag = 1;
        if ($indicador_lote == "X"){
            $flag = 3;
        }elseif (!empty($perfil_serie)) {
            $flag = 2;
        }
        return $flag;
    }

    private function generateItemsFormatSAP($detalle,$factura){
        $items = array();
        //$id_shop_current = (int)Context::getContext()->shop->id;
        $id_shop_current = (int)$factura["id_shop"];
        $gestionarProductosController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $cod_almacen = $gestionarProductosController->get_code_by_id($id_shop_current);
        //$cod_almacen = "2930"; // recuperar de cada taberna
        $is_reproceso = "";
        $error = false;
        foreach ($detalle as $item ) {
            $articulo = $this->recuperaArticulo($cod_almacen,$item["codigo_principal"]);
            if (is_array($articulo)){
                if ($articulo[0] == "0"){
                    $flag = $this->getFlag($articulo[9],$articulo[10]);
                    $arr = array(   "ALMACEN"   => $cod_almacen,
                                    "CANTIDAD"  => $item["cantidad"],
                                    "CATEGORIA" => "",
                                    "CENTRO"    => $articulo[17],
                                    "COMBO"     => "N",
                                    "FLAG"      => $flag,
                                    "LOTE"      => $articulo[19],
                                    "MATERIAL"  => $item["codigo_principal"],
                                    "Pordscto"  => 0,
                                    "Serie"     => $articulo[18],
                                    "Valor"     => $item["precio"]
                            );

                    /*if (($flag == 3) || ($flag == 2)) {
                        $valido = $this->validarLoteSerie($cod_almacen,$item["codigo_principal"],$flag,$articulo[19],$articulo[18]);
                        if ((int)$valido == 1 ){
                            $is_reproceso = "X";
                        }
                    }*/

                    $items[] = $arr;
                }else{
                    $error = true;
                }
            }else{
                $error = true;
            }
        }
        return array("items" => $items,"error" => $error);
    }

    private function generateDataRecaudoSAP($data,$factura,$payment,$efectivo){

        $tabernaSOAPController = new sincronizacionwebservicesTabernaSoapModuleFrontController();
        $resCliente = $tabernaSOAPController->existCustomer($factura["identificacion_comprador"]);
        //$id_shop_current = (int)Context::getContext()->shop->id;
        $id_shop_current = (int)$factura["id_shop"];
        $gestionarProductosController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $cod_almacen = $gestionarProductosController->get_code_by_id($id_shop_current);
        $oficina_venta = $gestionarProductosController->get_oficina_venta__by_code($cod_almacen);
        $codigo_cliente_sap = null;
        if ($resCliente["status"] == 0){
            $cliente = $resCliente["result"];
            $codigo_cliente_sap = $cliente[1];
        }

        $tipo_tarj = $efectivo ? "" : TIPO_TARJETA;
        $bin = isset($payment["bin"]) ? $payment["bin"] : "";
        $texto = $efectivo ? "W" : "D;".$payment["transaction_id"].";".$bin.";;0;PAYPHONE_TARJETAS";
        $vp = $efectivo ? "W" : "Y";

        return array(
                'DOC_SAP'       =>  $data[4],
                'FECHA'         =>  date("dmY"),
                'Cliente'       =>  $codigo_cliente_sap,
                'CedulaCajero'  =>  CEDULA_CAJERO,
                'OficinaVenta'  =>  $oficina_venta,
                'Monto'         =>  $factura["importe_total"],
                'Texto'         =>  $texto,
                'Tipot'         =>  $tipo_tarj,
                'Vp'            =>  $vp
            );
    }

    private function generateDataFacturaSAP($factura,$detalle,$payment){
        $tabernaSOAPController = new sincronizacionwebservicesTabernaSoapModuleFrontController();
        $resCliente = $tabernaSOAPController->existCustomer($factura["identificacion_comprador"]);
        echo "<br>factura['identificacion_comprador'] : ".$factura["identificacion_comprador"];
        echo "<br>****Res CLiente al GenerarDataFacturaSAP***<br>";
        var_dump($resCliente);

        $id_shop_current = (int)$factura["id_shop"];
        $gestionarProductosController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $cod_almacen = $gestionarProductosController->get_code_by_id($id_shop_current);
        $oficina_venta = $gestionarProductosController->get_oficina_venta__by_code($cod_almacen);
        $codigo_cliente_sap = null;
        if ($resCliente["status"] == 0){
            $cliente = $resCliente["result"];
            $codigo_cliente_sap = $cliente[1];
        }
        $numero_factura = $factura["establecimiento"].
                          $factura["punto_emision"].
                          $factura["secuencial"];
        $numero_autorizacion = $factura["numero_autorizacion"];
        $resItems = $this->generateItemsFormatSAP($detalle,$factura);
        $items = $resItems["items"];
        $errorItems = $resItems["error"];
        //$is_reproceso = $resItems["is_reproceso"];
        if (!$errorItems){
            return array(   'WCHECK'        => "",//$is_reproceso,
                            'WFACSRI'       => $numero_factura,
                            'WNOSRI'        => $numero_autorizacion,
                            'WFECHA'        => date("dmY"),
                            'WITEMS'        => $items,
                            'WKUNNR'        => $codigo_cliente_sap,
                            'WPARTNER'      => CODIGO_VENDEDOR, // codigo del vendedor
                            'WREPROCESO'    => "",//$is_reproceso,
                            'WSERIES'       => "",
                            'WSPART'        => SECTOR, //sector taberna
                            'WUCAJA'        => CEDULA_CAJERO, // cedula usuario pos
                            'WUREPROCESO'   => "",//$is_reproceso == "X" ? "0104534649" : "",
                            'WVKBUR'        => $oficina_venta,
                            'WVKORG'        => ORGANIZACION_VENTA, // organización de venta
                            'WVTWEG'        => "20", // canal de venta, siempre retail=20
                            'WZTERM'        => "PPOS"
                        );
        } else {
            return false;
        }
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

    private function getFacturas() {
        $facturas = Db::getInstance()->executeS('
        SELECT * 
        FROM factura_cabecera AS fc
        WHERE (fc.numero_pedido is null OR fc.documento_contable_recaudo is null)
        AND fc.transaction_id_pay is not null ');

        return $facturas;
    }

    private function getFacturasCompletas() {
        $facturas = Db::getInstance()->executeS('
        SELECT * 
        FROM factura_cabecera AS fc
        WHERE (fc.numero_pedido is not null AND fc.documento_contable_recaudo is not null AND fc.transaction_id_pay is not null AND (fecha_autorizacion BETWEEN CURDATE() - INTERVAL 2 DAY AND CURDATE() - INTERVAL 1 SECOND) ) ');

        return $facturas;
    }


}

