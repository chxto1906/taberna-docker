<?php

set_time_limit(30000000);

class sincronizacionwebservicesUpdateStockProductosModuleFrontController extends ModuleFrontController {

    public function initContent() {
        parent::initcontent();
        include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
        include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        //$updateActiveResult = $gestionarController->deactivate_all_products_all_stores();
        //if ($updateActiveResult){
            echo "Empieza " . date('m/d/Y h:i:s a', time()) . "<br>";
            $message = "Empieza actualizacion de Stocks";
            $gestionarController->addLog($message, 1, 612, 612);
            $shops_ = Shop::getShops(true, null, true);
            $shops = array_keys($shops_);
            $total = 0;
            $index = 0;
            $stocks_for_store = $gestionarController->_get_ws_products();
            $stocks_for_store = !empty($stocks_for_store->STOCK) ? $stocks_for_store->STOCK : [];
            foreach ($stocks_for_store as $product_stock) {
                    $index++;
                    $reference = $product_stock->MARMAT;
                    $stock = $product_stock->MARLIB;
                    $maralm = $product_stock->MARALM;
                    echo "<br>MARALM ".$maralm."<br>";
                    $store_id = $gestionarController->get_id__by_code($maralm);
                    echo "<br>ALMACEN WEBSERVICES: $maralm<br>";
                    echo "<br>id_by_code found: $store_id</br>";
                    if (!empty($store_id)) {
                        $store = Shop::getShop($store_id);
                        echo "STORE ENCONTRADO ==> store id: ".$store_id." - con reference: ".$reference." - y stock: ".$stock;
                        $message = "<br>Actualizando  productos de tienda " . $store['name'] . " <br><br>";
                        //$gestionarController->addLog($message, 1, 612, 612);
                        echo $message;
                        $id = $gestionarController->search_product_by_reference($reference);
                        if (!$id) {
                            $message = "<br> $index.-  No existe el producto reference: $reference en la tienda " . $store['name'] . "<br>";
                            echo $message;
                            //$gestionarController->addLog($message, 3, 612, 612);
                            continue;
                        }else{
                            echo "<br>id encontrado: $id";
                            $message = "<br> $index.-  Si existe el producto reference: $reference en la tienda " . $store['name'] . " <br>";
                            echo $message;
                            //$gestionarController->addLog($message, 3, 612, 612);
                        }
                        if (($stock == '0') || ($stock == 0)) {
                            $active = 0;
                            $message = "El producto  $reference tiene Stock 0 cambiando activo = 0";
                            echo "<br>$message</br>";
                            //$gestionarController->addLog($message, 1, 614, 614);
                        }else{
                            $active = 1;
                            $message = "El producto  $reference tiene Stock $stock cambiando activo= $active";
                            echo "<br>$message</br>";
                            //$gestionarController->addLog($message, 1, 614, 614);
                        }
                        $added = $gestionarController->update_stock_available_price_state($reference, $stock, "no", $active, $store_id);
                        $message = "<br>El producto con reference: $reference  has sido actualizado = ".$added."<br>";
                        echo $message;
                        $gestionarController->addLog($message, 1, 614, 614);
                        if ($added) {
                            $message = "<br> $index.-  Actualizado  stock de producto reference: $reference correctamente <br>";
                            echo $message;
                            // $gestionarController->addLog($message, 1, 612, 612);
                        } else {
                            $message = "<br>$index.-  No pudo ser actualizado el producto reference $reference en la tienda " . $store['name'] . " con la cantidad de stock: $stock <br>";
                            echo $message;
                            //$gestionarController->addLog($message, 1, 612, 612);
                        }
                    }else{
                        $message = "<br>Store de webservice: $maralm NO ENCONTRADO <br>";
                        echo $message;
                    }
            }
            $message = "Finaliza actualizacion de un total de $total productos";
            //$gestionarController->addLog($message, 1, 612, 612);
            echo "Finaliza " . date('m/d/Y h:i:s a', time()) . "<br>";
            $this->reindex_products($gestionarController);
        /*}else{
            $message = "No se pudo actualizar a active = 0 a todos los productos";
            echo $message;
        }*/
        exit;
    }

    function reindex_products($gestionarController) {
        $message = "Iniciando Proceso de Reindexacion";
        echo $message;
        $gestionarController->addLog($message, 1, 612, 612);
        $url = "http://localhost/backoffice/searchcron.php?token=b0FM0YkO";
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
        $message = "Concluido Proceso con estado: " . $status;
        echo $message;
        $gestionarController->addLog($message, 1, 612, 612);
    }

}

