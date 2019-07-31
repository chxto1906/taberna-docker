<?php

set_time_limit(30000000);

include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaSAP.php');

class sincronizacionwebservicesRecuperarArticulosModuleFrontController extends ModuleFrontController {

    public function initContent() {
        parent::initcontent();

        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        //$facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        echo "Empieza " . date('m/d/Y h:i:s a', time()) . "<br>";
        $message = "Empieza actualizacion de Prices o Creación";

        $id_shop = Tools::getValue('id_shop');
        $parte = Tools::getValue('parte');
        $reset = Tools::getValue('reset');

        if ($id_shop && $parte) {

            //$gestionarController->addLog($message, 1, 612, 612);
            if ($reset=="si"){
                echo "<br>PRODUCTOS DESACTIVADOS <br>";
                $gestionarController->desactivate_all_products_for_shop($id_shop);
            }
            $shops_ = Shop::getShops(true, null, true);

            $shops = array_keys($shops_);
            $total = 0;
            $index = 0;
            $stock = 0;
            $active = 0;
            $products = $gestionarController->_get_ws_products_catalogo();
            $products = !empty($products->ARTICULOS) ? $products->ARTICULOS : [];



            $cantidadDividir = count($products)/2;
            $products = array_chunk($products, $cantidadDividir);
            $products = $products[$parte-1];

            echo "<br> CANTIDAD PRODUCTOS CATALOGO: ".count($products)." <br>";

            //$gestionarController->desactivate_all_products_for_shop($id_shop);
            //foreach ($shops as $id_shop) {
            $store = Shop::getShop($id_shop);
            $message = "<br>Actualizando  productos de tienda " . $store['name'] . " <br><br>";
            echo $message;
            $index = 0;
            
            foreach ($products as $product) {
                echo "<hr/>";
                $index++;
                $total++;
                $reference = $product->Codigo;
                echo "REFERENCE: ".$reference."<br>";
                $id_product = $gestionarController->search_product_by_reference($reference);
                $cod_almacen = $gestionarController->get_code_by_id($id_shop);
                $stock = $this->checkStock($cod_almacen,$reference);


                echo "ID_PRODUCT: ".$id_product."<br>";
                $NombreProducto = $product->NombreProducto;
                echo "NOMBRE PRODUCTO: ".$NombreProducto."<br>";
                $Marca = $product->Marca;
                echo "MARCA: ".$Marca."<br>";
                $Categoria = $product->Categoría;
                echo "CATEGORÍA: ".$Categoria."<br>";
                $Precio = $product->Precio;
                echo "PRECIO: ".$Precio."<br>";
                
                if (!$id_product) {
                    $message = "<br> $index.-  No existe el producto reference: $reference en la tienda <br>";
                    echo $message;
                    //$gestionarController->addLog($message, 3, 612, 612);
                    //continue;
                } else {
                    echo "<br>ID PRODUCT ENCONTRADO: $id_product <br>";
                    $message = "<br> $index.-  Si existe el producto reference: $reference <br>";
                    echo $message;
                    //$gestionarController->addLog($message, 3, 612, 612);
                }

                $added = $gestionarController->create_update_product_new($id_product,$reference,$NombreProducto,$Marca,$Categoria,$Precio,$stock,$id_shop);
                //$gestionarController->addLog($message, 1, 614, 614);
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
        //}        
            $message = "Finaliza actualizacion de un total de $total productos";
            //$gestionarController->addLog($message, 1, 612, 612);
            echo "Finaliza " . date('m/d/Y h:i:s a', time()) . "<br>";
            //$this->reindex_products($gestionarController);
        } else {
            echo "<br>***FALTA EL ID_SHOP***<br>";
        }
        exit;
    }


    function checkStock($cod_almacen,$reference) {
        $stock = 0;
        $facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        $articulo = $facturaSAP->recuperaArticulo($cod_almacen,$reference);
        if (is_array($articulo)) {
            if ($articulo[0] == "0"){
                $stock = $articulo[15];
                $stock = $stock - BUFFER_STOCK;
                echo "<br>STOCK para product Reference: $reference es: $stock<br>";
            }
        }
        return $stock;
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
