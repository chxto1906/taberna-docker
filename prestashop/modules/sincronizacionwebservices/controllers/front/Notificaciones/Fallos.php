<?php

include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');

Class Fallos {

	function notificar ($msg) {
		$data = array('{mensaje}' => $msg);
		$tos = EMAILS_TO;
		$para = "Sistema Taberna";
		Mail::Send(1,'fallos','FALLO EN TABERNA WEB',$data,$tos,$para,null,null,null,null, _PS_MAIL_DIR_, false, null);
	}
}