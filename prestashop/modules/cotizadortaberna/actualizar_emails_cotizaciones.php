<?php
error_reporting(0);

header('Content-type: application/json');

include_once('../../config/config.inc.php');
include_once('../../init.php');

if (Tools::getValue('registros'))
{
	
	$registros = Tools::getValue('registros');
	$estado = true;
	foreach ($registros as $registro)
	{
		$id = $registro['id'];
		$email = $registro['email'];
		$update = actualizarMails($id,$email);
		if ($update == false)
			$estado = false;
		
	}

	if ($estado == true)
		{
			$jsondata['respuesta'] = 'Se han actualizado correctamente todos los emails';
			$jsondata['estado'] = 1;
		}
	else
		{
			$jsondata['respuesta'] = 'Algun/os email/s no se ha actualizado correctamente';
			$jsondata['estado'] = 0;
		}

	echo json_encode($jsondata);

}

if (Tools::getValue('mail_new'))
{
	$mail_new = Tools::getValue('mail_new');

	if (Db::getInstance()->insert('cotizaciones_mails', array(
		'email' => pSQL($mail_new)
	)))
	{
		$jsondata['respuesta'] = 1;
		$id=obtener_id();
		$jsondata['id'] = $id;
	}
	else
		$jsondata['respuesta'] = 0;

	echo json_encode($jsondata);
}

if (Tools::getValue('id_email_eliminar'))
{
	$id = Tools::getValue('id_email_eliminar');
	if (Db::getInstance()->delete('cotizaciones_mails', 'id = '.$id))
		$jsondata['respuesta'] = 1;
	else
		$jsondata['respuesta'] = 0;	
	
	echo json_encode($jsondata);	
}


function obtener_id(){
	$max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT max(cm.`id`) as cantidad
			FROM `'._DB_PREFIX_.'cotizaciones_mails` cm');
	return $max[0]['cantidad'];
}

function actualizarMails($id,$email){
	if (Db::getInstance()->update('cotizaciones_mails', array(
	    'email' => pSQL($email)
	),'id = '.$id))
		return true;
	else
		return false;
}

/*function yaExiste($id){
	$max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT count(cm.`id`) as existe
			FROM `'._DB_PREFIX_.'cotizaciones_mails` cm
			WHERE cm.`id` = '.(int)$id
		);
	return $max[0]['existe'];
}*/


?>