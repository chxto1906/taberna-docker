<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/cotizadortaberna.php');
$cotizadortaberna = new Cotizadortaberna();
echo $cotizadortaberna->buscar_licores($_GET['name_category']);
//echo json_encode(Modelos::filterModelos(1));


?>