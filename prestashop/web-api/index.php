<?php



require_once 'flight/Flight.php';
require_once 'classes/Classifications.php';


Flight::route('GET /', function(){
    echo 'API La Taberna 2019';
});

Flight::route('GET /stores/@store_id/products/classification/@name', function($store_id,$name){
    $classifications = new Classifications();
    echo json_encode($classifications->listar($store_id,$name));
});


Flight::start();














