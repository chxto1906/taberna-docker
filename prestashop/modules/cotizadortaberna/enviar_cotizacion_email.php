<?php
error_reporting(0);

header('Content-type: application/json');

include_once('../../config/config.inc.php');
include_once('../../init.php');

if (Tools::getValue('datos') && Tools::getValue('email'))
{
	$datos = Tools::getValue('datos');
	$email = Tools::getValue('email');

	$data = array(
		'{datos}' => $datos
	);

	$para = null;

	global $cookie;

	$nombre_user = $cookie->customer_lastname;
	$apellido_user = $cookie->customer_firstname;
		
	if (!$nombre_user)
		$para = null;
	else
		$para = $nombre_user.' '.$apellido_user;

	if (Validate::isEmail($email))
		{
			if (Mail::Send(4,'cotizacion',Mail::l('Cotización', 4),$data,$email,$para,null,null,null,null, _PS_MAIL_DIR_, false, 1))
				$jsondata['resultado'] = '<h3 style="color: green;">Su cotización fue enviada exitosamente.</h3>';
			else
				$jsondata['resultado'] = '<h3 style="color: red;">Su cotización no fue enviada debido a un error.</h3>';
		}
		else
			$jsondata['resultado'] = '<h3 style="color: red;">Se ha intentado enviar la cotización pero resulta un email inválido.</h3>';	




	$emails = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT cm.`email` as email
			FROM `'._DB_PREFIX_.'cotizaciones_mails` cm');

	foreach ($emails as $e) {
		$email_a_enviar = $e['email'];
		if (Validate::isEmail($email_a_enviar))
		{
			$subject = 'Cotización del cliente:';
			Mail::Send(4,'cotizacion',Mail::l($subject, 4),$data,$email_a_enviar,null,null,$para,null,null, _PS_MAIL_DIR_, false, 1);
		}
	}

	echo json_encode($jsondata);
	
}


?>