<?php

set_time_limit(30000000);

class sincronizacionwebservicesUpdatePriceProductosModuleFrontController extends ModuleFrontController {

    public function initContent() {
        parent::initcontent();
        include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
        include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        echo "Empieza " . date('m/d/Y h:i:s a', time()) . "<br>";
        $message = "Empieza actualizacion de Prices o Creación";
        $gestionarController->addLog($message, 1, 612, 612);
        $shops_ = Shop::getShops(true, null, true);
        $shops = array_keys($shops_);
        $total = 0;
        $index = 0;

        $products = $gestionarController->_get_ws_products_catalogo();
        $products = !empty($products->ARTICULOS) ? $products->ARTICULOS : [];


        echo "<br> CANTIDAD PRODUCTOS CATALOGO: ".count($products)." <br>";

        /*
        $gestionarController->deactivate_all_products_all_stores();
        foreach ($products as $product) {
            echo "<hr/>";
            $index++;
            $total++;
            $reference = $product->Codigo;
            $NombreProducto = $product->NombreProducto;
            echo $NombreProducto."<br>";
            $Marca = $product->Marca;
            $Categoria = $product->Categoría;
            $Precio = $product->Precio;
            $id_product = $gestionarController->search_product_by_reference($reference);
            echo "Categoria: ".$Categoria."<br>";
            echo "Precio: ".$Precio."<br>";
            echo "Marca: ".$Marca."<br>";
            if (!$id_product) {
                $message = "<br> $index.-  No existe el producto reference: $reference en la tienda <br>";
                echo $message;
                //$gestionarController->addLog($message, 3, 612, 612);
                //continue;
            }else{
                echo "<br>id encontrado: $id_product";
                $message = "<br> $index.-  Si existe el producto reference: $reference <br>";
                echo $message;
                //$gestionarController->addLog($message, 3, 612, 612);
            }
            $added = $gestionarController->create_update_product($id_product,$reference,$NombreProducto,$Marca,$Categoria,$Precio);
            $gestionarController->addLog($message, 1, 614, 614);
            if ($added) {
                $message = "<br> $index.-  Guardado producto reference: $reference correctamente <br>";
                echo $message;
                // $gestionarController->addLog($message, 1, 612, 612);
            } else {
                $message = "<br>$index.-  No pudo ser actualizado el producto reference $reference con el precio: $Precio <br>";
                echo $message;
                //$gestionarController->addLog($message, 1, 612, 612);
            }
        }

        /*foreach ($shops as $id_shop) {
            //$code = $gestionarController->get_code_by_id($id_shop);
            $products_by_shop = $gestionarController->_get_ws_products($code);
            $store = Shop::getShop($id_shop);
            $message = "<br>Actualizando  productos de tienda " . $store['name'] . " <br><br>";
            $gestionarController->addLog($message, 1, 612, 612);
            echo $message;

            $index = 0;
            foreach ($products_by_shop->ARTICULOS as $item) {
                $index++;

                $total++;

                $reference = $item->CODIGO;
                
             
                
                $active = (int) $item->ACTIVO;
                $price = $item->PRECIO;
                $stock = (int) $item->STOCK;
                $message= $index . "- $reference  stock $stock, price: $price, active: $active , tienda :" . $store['name'] . "  </br>";
                
                print $message;
                
                $gestionarController->addLog($message, 3, 612, 612);


                $id = $gestionarController->search_product_by_reference($reference);
                if (!$id) {
                    $message = "<br> $index.-  No existe el producto $reference en la tienda " . $store['name'] . " <br>";
                    echo $message;
                    $gestionarController->addLog($message, 3, 612, 612);
                    continue;
                }else{
                    $message = "<br> $index.-  Si existe el producto $reference en la tienda " . $store['name'] . " <br>";
                    echo $message;
                    $gestionarController->addLog($message, 3, 612, 612);
                }

                if ($stock == '0') {
                    $active = 0;
                    $message = "El producto  $reference tiene Stock 0 cambiando activo = 0";
                    echo "<br>$message</br>";
                    $gestionarController->addLog($message, 1, 614, 614);
                }else{
                    $message = "El producto  $reference tiene Stock $stock cambiando activo= $active";
                    echo "<br>$message</br>";
                    $gestionarController->addLog($message, 1, 614, 614);
                }



                $added = $gestionarController->update_stock_available_price_state($reference, $stock, $price, $active, $id_shop);
                
                $message = "El producto  has sido agregado = ".$added;
              
                $gestionarController->addLog($message, 1, 614, 614);
                

                if ($added) {
                    $message = "<br> $index.-  Actualizado  stock y precio de producto $reference correctamente <br>";
                    echo $message;
                    // $gestionarController->addLog($message, 1, 612, 612);
                } else {
                    $message = "<br>$index.-  No pudo ser actualizado el producto $reference en la tienda " . $store['name'] . " con la cantidad $stock <br>";
                    echo $message;
                    $gestionarController->addLog($message, 1, 612, 612);
                }
                
               
            }
            
            
        }*/
        $message = "Finaliza actualizacion de un total de $total productos";
        //$gestionarController->addLog($message, 1, 612, 612);
        echo "Finaliza " . date('m/d/Y h:i:s a', time()) . "<br>";
        //$this->reindex_products($gestionarController);
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
