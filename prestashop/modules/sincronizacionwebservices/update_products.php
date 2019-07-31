<?php
echo "Iniciando Proceso";
$url = "https://www.lataberna.com.ec/cuenca/remigio-crespo/?fc=module&module=sincronizacionwebservices&controller=UpdateStockProductos";
///$agent = 'Mozilla/8.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
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
