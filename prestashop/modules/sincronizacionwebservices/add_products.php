<?php

echo "Iniciando Proceso";
$url = "https://www.lataberna.com.ec/cuenca/remigio-crespo/?fc=module&module=sincronizacionwebservices&controller=GestionarProductos&action=sync";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_TIMEOUT, 30000000);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
$content = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
print_r($status);
print_r($content);
curl_close($ch);
echo "Concluido Proceso";



