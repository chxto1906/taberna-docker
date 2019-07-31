<?php
    
//require_once _PS_MODULE_DIR_ . 'mipilotoshipping/curl/curl_mipiloto.php';

require_once _PS_ROOT_DIR_ . '/config/error_intentos.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
require_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/nusoap.php');

include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
class sincronizacionwebservicesTabernaSoapModuleFrontController extends ModuleFrontController {

    public $log = null;


    public function initContent() {
    	parent::initContent();

        $this->log = new LoggerTools();
        //var_dump(Context::getContext()->address);

    	exit;
    	$this->setTemplate('productos.tpl');
    }

    public function existCustomer($cedula){
    	$res = array("status" => 0, "result" => null);

        $attempts = 0;
        $faultG = null;

        $log = new LoggerTools();

        do {

        	

            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;
            $params = array("identificacion" => $cedula);
            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call(SERVICIO_CONSULTA_CLIENTES, array($params), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                $attempts++;
                $log->add("FAULT existCustomer intento: ".$attempts);
                $res = array("status" => 3, "result" => $result); // Error return 3
                sleep(1);
                continue;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                    $attempts++;
                    $log->add("ERROR existCustomer intento: ".$attempts);
                    $res = array("status" => 3, "result" => $result); // Error return 3
                    sleep(1);
                    continue;
                } else {
                    $res_service = SERVICIO_CONSULTA_CLIENTES."Result";
                    $result = $response[$res_service];

                    $log->add("RESPONSE existCustomer: ".$result);

                    echo "<br>****existCustomer****<br>";
                    var_dump($result);
                    $result = explode("|", $result);
                    if ($result[0] == "1"){
                        $res = array("status" => 1, "result" => null); // 1 = No existe
                        break;
                    }elseif ($result[0] == "0") {
                        $res = array("status" => 0, "result" => $result); // 0 = Si existe
                        break;
                    }else{
                        $attempts++;
                        $log->add("Error en existCustomer, responde: ".$result);
                        $res = array("status" => $result[0], "result" => "error"); // Number error return
                        sleep(1);
                        continue;
                    }
                }
            }



            /*try {
                $client = new SoapClient(WS_SERVER);
                $params = array("identificacion" => $cedula);
                $response = $client->__soapCall(SERVICIO_CONSULTA_CLIENTES, array($params));

                $res_service = SERVICIO_CONSULTA_CLIENTES."Result";
                //$result = $response->QAS_WS_POS_CONSULTA_CLIENTESResult;
                $result = $response->{$res_service};

                $log->add("RESPONSE existCustomer: ".$result);

                echo "<br>****existCustomer****<br>";
                var_dump($result);
                $result = explode("|", $result);
                if ($result[0] == "1"){
                	$res = array("status" => 1, "result" => null); // 1 = No existe
                    break;
                }elseif ($result[0] == "0") {
                    $res = array("status" => 0, "result" => $result); // 0 = Si existe
                    break;
                }else{
                    $attempts++;
                    $log->add("Error en existCustomer, responde: ".$result);
                    $res = array("status" => $result[0], "result" => "error"); // Number error return
                    sleep(1);
                    continue;
                }
            } catch (SoapFault $fault) {
                $attempts++;
                $log->add("CATCH existCustomer intento: ".$attempts);
                $res = array("status" => 3, "result" => $result); // Error return 3
                $faultG = $fault;
                sleep(1);
                continue;
                //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                
            }*/

            break;

        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS) {
            $log->add("CATCH existCustomer despues de $attempts intentos, no se pudo corregir error");
        }
        return $res;

    }

    public function createCustomer($params){

        $attempts = 0;
        $faultG = null;
        $log = new LoggerTools();

        do {

        	

            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;
            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call(SERVICIO_CREACION_CLIENTE, array($params), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                $attempts++;
                $log->add("FAULT createCustomer intento: ".$attempts);
                sleep(1);
                continue;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';

                    $attempts++;
                    $log->add("ERROR createCustomer intento: ".$attempts);
                    sleep(1);
                    continue;

                } else {
                    $res_service = SERVICIO_CREACION_CLIENTE."Result";
                    $result = $response[$res_service];
                    $log->add("RESPONSE createCustomer: ".$result);
                    echo "<br>****RESULT CREATE CUSTOMER****<br>";
                    var_dump($result);
                    $result = explode("|",$result);
                    break;
                }
            }





            /*try {
                $client = new SoapClient(WS_SERVER);
                $response = $client->__soapCall(SERVICIO_CREACION_CLIENTE, array($params));
                $res_service = SERVICIO_CREACION_CLIENTE."Result";
                //$result = $response->QAS_WS_POS_CREACION_CLIENTE;
                


                $result = $response->{$res_service};

                $log->add("RESPONSE createCustomer: ".$result);

                echo "<br>****RESULT CREATE CUSTOMER****<br>";
                var_dump($result);
                
                $result = explode("|",$result);
                break;
                //return $result;

            } catch (SoapFault $fault) {
                $attempts++;
                $log->add("CATCH createCustomer intento: ".$attempts);
                $faultG = $fault;
                sleep(1);
                continue;
                //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                
            }*/

        } while($attempts < NUMERO_INTENTOS);

        if ($attempts == NUMERO_INTENTOS) {
            $log->add("CATCH createCustomer despues de $attempts intentos, no se pudo corregir error");
        }
        return $result;
    }


}

