<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_ROOT_DIR_ . '/config/error_intentos.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';

class CurlMiPiloto {

	public $log = null;

	public function __construct()
    {
    	$this->log = new LoggerTools();
    	$this->api_key = Configuration::get('MIPILOTOSHIPPING_ACCOUNT_API_KEY');
    	$this->url_base = "https://mipiloto.com.ec/apikey";
    }

	public function ciudades(String $url) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->url_base."/ciudades",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => false,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "X-Authorization: ".$this->api_key
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return array("status" => 0, "message" => "cURL Error #:" . $err);
		} else {
		  return array("status" => 1, "message" => $response);
		}
	}

	public function cotizar($body) {

		$attempts = 0;
        $respuesta = array("status" => 0, "result" => null);

        do {

			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->url_base."/cotizar",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => false,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => $body,
			  CURLOPT_HTTPHEADER => array(
			    "X-Authorization: ".$this->api_key
			  ),
			));

			$this->log->add("BODYYY cotizar al API MIpiloto: ".json_encode($body));

			$response = curl_exec($curl);
			$this->log->add("RESPUESTA cotizar mi piloto API: ".$response);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$this->log->add("HTTPCODE return de cotizar mi piloto API: ".$httpcode);
			$err = curl_error($curl);

			$this->log->add("¿¿ERROR en cotizar mi piloto API???: ".$err);

			curl_close($curl);
			if ($err) {
				$attempts++;
                $this->log->add("ERROR AL COTIZAR MIPILOTO API intento: ".$attempts);
                $respuesta = array("status" => 0, "result" => "cURL Error #:" . $err);
                sleep(1);
                continue;
			} else {
				if ($httpcode == 200){
			  		$respuesta = array("status" => 1, "result" => $response);
			  		break;
			  	}else{
			  		$attempts++;
	                $this->log->add("ERROR de statusCode AL COTIZAR MIPILOTO API intento: ".$attempts);
	                $respuesta = array("status" => 0, "result" => "cURL Status :" . $httpcode);
	                sleep(1);
	                continue;
			  	}
			}

		} while($attempts < NUMERO_INTENTOS);

		if ($attempts == NUMERO_INTENTOS) {
            $this->log->add("CATCH cotizar MIPILOTO API despues de $attempts intentos, no se pudo corregir error");
            $respuesta = array("status" => 0, "result" => "cURL Status :" . $httpcode);
            //exit;
        }

        return $respuesta;
	}


	public function infoPedido($num_guia) {

		$attempts = 0;
		$respuesta = array("status" => 0, "result" => null);

		do {

			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->url_base."/pedido/".$num_guia,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => false,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "X-Authorization: ".$this->api_key
			  ),
			));

			$response = curl_exec($curl);
			$this->log->add("RESPUESTA infoPedido mi piloto API: ".$response);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$err = curl_error($curl);

			curl_close($curl);
			if ($err) {
			  	$attempts++;
                $this->log->add("ERROR AL infoPedido MIPILOTO API intento: ".$attempts);
                $respuesta = array("status" => 0, "result" => "cURL Error #:" . $err);
                sleep(1);
                continue;
			} else {
				if ($httpcode == 200){
			  		$respuesta = array("status" => 1, "result" => json_decode($response));
			  		break;
				}else{
			  		$attempts++;
                	$this->log->add("ERROR de statusCode infoPedido MIPILOTO API intento: ".$attempts);
			  		$respuesta = array("status" => 0, "result" => "cURL Status :" . $httpcode);
			  	}
			}
		} while($attempts < NUMERO_INTENTOS);

		if ($attempts == NUMERO_INTENTOS) {
            $this->log->add("CATCH infoPedido MIPILOTO API despues de $attempts intentos, no se pudo corregir error");
            $respuesta = array("status" => 0, "result" => "cURL Status :" . $httpcode);
            //exit;
        }

        return $respuesta;
	}


	public function activarPedido($guia_numero,$tipo_vehiculo,$tipo_producto,$tiempo_llegada) {
		
		$attempts = 0;
		$respuesta = array("status" => 0, "result" => null);

		do {

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->url_base."/pedido/activa",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => false,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => array(
			  						'guia_numero' => $guia_numero,
			  						'tipo_vehiculo' => $tipo_vehiculo,
			  						'tipo_producto' => $tipo_producto,
			  						'tiempo_llegada' => $tiempo_llegada),
			  CURLOPT_HTTPHEADER => array(
			    "X-Authorization: ".$this->api_key
			  ),
			));

			$response = curl_exec($curl);
			$this->log->add("RESPUESTA activarPedido mi piloto API: ".$response);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {

				$attempts++;
                $this->log->add("ERROR AL activarPedido MIPILOTO API intento: ".$attempts);
                $respuesta = array("status" => 0, "result" => "cURL Error #:" . $err);
                sleep(1);
                continue;
			} else {
			  $respuesta = array("status" => 1, "result" => $response);
			  break;
			}
		} while($attempts < NUMERO_INTENTOS);

		if ($attempts == NUMERO_INTENTOS) {
            $this->log->add("CATCH activarPedido MIPILOTO API despues de $attempts intentos, no se pudo corregir error");
            $respuesta = array("status" => 0, "result" => "cURL :" . $err);
            //exit;
        }

        return $respuesta;
	}


	public function eliminarPedido($guia_numero){

		$attempts = 0;
		$respuesta = array("status" => 0, "result" => null);

		do {

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->url_base."/pedido/".$guia_numero,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => false,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "DELETE",
			  CURLOPT_POSTFIELDS => array(
			  						'guia_numero' => $guia_numero),
			  CURLOPT_HTTPHEADER => array(
			    "X-Authorization: ".$this->api_key
			  ),
			));

			$response = curl_exec($curl);
			$this->log->add("RESPUESTA eliminarPedido mi piloto API: ".$response);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				$attempts++;
                $this->log->add("ERROR AL eliminarPedido MIPILOTO API intento: ".$attempts);
                $respuesta = array("status" => 0, "result" => "cURL Error #:" . $err);
                sleep(1);
                continue;
			} else {
				if ($httpcode == 200){
			  		$respuesta = array("status" => 1, "result" => $response);
			  		break;
				}else{

			  		$attempts++;
	                $this->log->add("ERROR AL eliminarPedido MIPILOTO API intento: ".$attempts);
	                $respuesta = array("status" => 0, "result" => "cURL Status :" . $httpcode);
	                sleep(1);
	                continue;
			  	}
			}

		} while($attempts < NUMERO_INTENTOS);

		if ($attempts == NUMERO_INTENTOS) {
            $this->log->add("CATCH eliminarPedido MIPILOTO API despues de $attempts intentos, no se pudo corregir error");
            $respuesta = array("status" => 0, "result" => "cURL httpcode :" . $httpcode);
            //exit;
        }

        return $respuesta;
	}


	public function hacerPedido($body,$efectivo) {

		$attempts = 0;
		$respuesta = array("status" => 0, "result" => null);

		$params = array(
  					'latitud_recibe' => $body["latitud_recibe"],
  					'longitud_recibe' => $body["longitud_recibe"],
  					'persona_recibe' => $body["persona_recibe"],
  					'telefono_recibe' => $body["telefono_recibe"],
  					'local_recibe' => $body["local_recibe"],
  					'direccion_recibe' => $body["direccion_recibe"],
  					'referencia_recibe' => $body["referencia_recibe"],
  					'latitud_entrega' => $body["latitud_entrega"],
  					'longitud_entrega' => $body["longitud_entrega"],
  					'persona_entrega' => $body["persona_entrega"],
  					'telefono_entrega' => $body["telefono_entrega"],
  					'direccion_entrega' => $body["direccion_entrega"],
  					'referencia_entrega' => $body["referencia_entrega"],
  					'cedula_entrega' => $body["cedula_entrega"],
  					'tiempo_llegada' => $body["tiempo_llegada"],
  					'email_usuario' => $body["email_usuario"]);
		if ($efectivo)
			$params["monto"] = $body["monto"];

		do {

			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->url_base."/pedido",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => false,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => $params,
			  CURLOPT_HTTPHEADER => array(
			    "X-Authorization: ".$this->api_key
			  ),
			));



			$response = curl_exec($curl);

			echo "RESPUESTA DE MIPILOTO  api";
			var_dump($response);

			$this->log->add("RESPUESTA hacerPedido mi piloto API: ".$response);

			$err = curl_error($curl);

			if ($err) {
				$attempts++;
                $this->log->add("ERROR AL eliminarPedido MIPILOTO API intento: ".$attempts);
                $respuesta = array("status" => 0, "result" => "cURL Error #:" . $err);
                sleep(1);
                continue;
			} else {
			  $respuesta = array("status" => 1, "result" => $response);
			  break;
			}

		} while($attempts < NUMERO_INTENTOS);

		if ($attempts == NUMERO_INTENTOS) {
            $this->log->add("CATCH hacerPedido MIPILOTO API despues de $attempts intentos, no se pudo corregir error");
            $respuesta = array("status" => 0, "result" => "cURL httpcode :" . $httpcode);
            //exit;
        }

        return $respuesta;

	}
	
}