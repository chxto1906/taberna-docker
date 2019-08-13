<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('WS_SERVER', "http://179.49.47.4/wsTaberna.asmx?WSDL");
define('WS_SERVER_TMP', "http://ajeprd.eljuric.com:8000/sap/bc/srt/wsdl/bndg_E521C0849A3AD3F1BDCE005056916A29/wsdl11/allinone/ws_policy/document?sap-client=400?wsdl");

// PRODUCCION //

define('SERVICIO_FACTURACION', "PRD_WS_POS_FACTURA_BASE");
define('SERVICIO_RECAUDO', "PRD_WS_POS_RECAUDO");
define('SERVICIO_CONSULTA_LOTE_SERIE', "PRD_WS_POS_CONSULTA_LOTE_SERIE");
define('SERVICIO_CONSULTA_MATERIAL', "PRD_WS_POS_CONSULTA_MATERIAL3");
define('SERVICIO_CREACION_CLIENTE', "PRD_WS_POS_CREACION_CLIENTE");
define('SERVICIO_CONSULTA_CLIENTES', "PRD_WS_POS_CONSULTA_CLIENTES");

// PRUEBAS //

/*define('SERVICIO_FACTURACION', "QAS_WS_POS_FACTURA_BASE");
define('SERVICIO_RECAUDO', "QAS_WS_POS_RECAUDO");
define('SERVICIO_CONSULTA_LOTE_SERIE', "QAS_WS_POS_CONSULTA_LOTE_SERIE");
define('SERVICIO_CONSULTA_MATERIAL', "QAS_WS_POS_CONSULTA_MATERIAL3");
define('SERVICIO_CREACION_CLIENTE', "QAS_WS_POS_CREACION_CLIENTE");
define('SERVICIO_CONSULTA_CLIENTES', "QAS_WS_POS_CONSULTA_CLIENTES");*/


define('SERVICIO_ACTUALIZACION_AUTO_SRI_SOAP', "PRD_WS_UPDATE_DOCUMENTO");


// FALLOS EMAILS TO //
define('EMAILS_TO',array(
							'chxto1906@gmail.com',
							'hcampoverde@eljurilicores.com',
							'pjerves@eljurilicores.com'
						));

define('BUFFER_STOCK',3);


define('WS_LOGIN',"web_tabernas");
define('WS_PASSWORD',"Webt.2019");

define('WS_CLIENTE', 'http://ajeqas.eljuric.com:8001/sap/bc/srt/wsdl/bndg_E6AA7297E697EAF1BDA2005056A3E573/wsdl11/allinone/ws_policy/document?sap-client=200');


