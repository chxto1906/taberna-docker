<?php

include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaSAP.php');
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/Notificaciones/Fallos.php');

set_time_limit(300000000);

class sincronizacionwebservicesUpdateStockProductosNewModuleFrontController extends ModuleFrontController {

    public function initContent() {
        parent::initcontent();

        $id_shop = Tools::getValue('id_shop');
        $desde = Tools::getValue('desde');
        $hasta = Tools::getValue('hasta');
        $id_product_esp = Tools::getValue('id_product');
        echo "<br>ID_PRODUCT receptado: $id_product_esp<br>";
        $log = new LoggerTools();
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $productsPrices = $gestionarController->_get_ws_products_catalogo();
        $productsPrices = !empty($productsPrices->ARTICULOS) ? $productsPrices->ARTICULOS : [];
        if (count($productsPrices) > 0) {
            $log->add("Empieza UpdateStockProductosNew " . date('m/d/Y G:i:s a', time()) . "<br>");
                $i = 0;
                if (!empty($id_product_esp)) {
                    $product = array('reference' => $id_product_esp );
                    echo "<br>Entró a procesar solo un producto: $id_product_esp<br>";
                    $this->procesar($id_shop,$product,1,$log,1,$productsPrices);
                } else {
                    $products = $this->getProductsByShop($id_shop,$desde,$hasta);
                    $cc = count($products);
                    echo "<br>Entró a procesar varios productos. CANTIDAD: $cc<br>";
                    $lenProducts = count($products);
                    foreach ($products as $product) {
                        $i++;
                        $this->procesar($id_shop,$product,$i,$log,$lenProducts,$productsPrices);
                    }    
                }
        } else {
            $fallo = new Fallos();
            $fallo->notificar("WebService SOAP Catálogo devuelve vacío. Verificar Base de Datos inmediatamente. ID_SHOP: ".$id_shop);
        }
        exit;
    }

    public function procesar($id_shop,$product,$i,$log,$lenProducts,$productsPrices) {
        $active = 0;
        $stock = 0;
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        $reference = $product["reference"];
        $ini = substr($reference,0,1);
        $id_product = $gestionarController->search_product_by_reference($reference);
        $cod_almacen = $gestionarController->get_code_by_id($id_shop);

        $articulo = $facturaSAP->recuperaArticulo($cod_almacen,$reference);
        if (is_array($articulo)) {
            if ($articulo[0] == "0"){
                $stock = $articulo[15];
                $stock = $stock - BUFFER_STOCK;
                echo "<br>STOCK seteado para product reference: $reference Es: $stock<br>";
                if ($stock <= 0) {
                    $active = 0;
                } else {
                    $productPrice = $this->searchArray($reference,$productsPrices);
                    if ($productPrice) {
                        //$NombreProducto = $productPrice->NombreProducto;
                        $marca = $productPrice->Marca;
                        //$Categoria = $productPrice->Categoría;
                        $precio = $productPrice->Precio;

                        //$marca = $articulo[7];
                        //$precio = $articulo[12];
                        $added = $gestionarController->create_update_product($id_product,$reference,$marca,$precio);
                        if ($added) {
                            $active = 1;
                            $message = "<br> Guardado producto reference: $reference correctamente <br>";
                            echo $message;
                            // $gestionarController->addLog($message, 1, 612, 612);
                        } else {
                            $message = "<br>No pudo ser actualizado el producto reference $reference con el precio: $precio <br>";
                            echo $message;
                            //$gestionarController->addLog($message, 1, 612, 612);
                        }
                    }
                }
            }
        }
            
        if ($active == 1)
            StockAvailable::setQuantity($id_product, 0, (int) $stock, $id_shop);
        $activateProducts = $this->activateProducts($id_product, $active, $id_shop);

        $log->add("**** CANTIDAD DE PRODUCTOS: $lenProducts *****");
        $log->add("**** PRODUCTO: $reference i: $i *****");

        echo "<br>RESULTADO DE ACTIVAR O DESACTIVAR PRODUCTO del producto reference: $reference en shop id: $id_shop . RESULTADO: $activateProducts<br>";
    }


    public function searchArray($id, $array) {
       foreach ($array as $key) {
           if ($key->Codigo == $id) {
               return $key;
           }
       }
       return null;
    }



    public function getProductsByShop($id_shop, $desde, $hasta) {

        $products = Db::getInstance()->executeS('SELECT p.reference 
            FROM `ps_product_shop` ps
            INNER JOIN `ps_product` p on ps.id_product = p.id_product
            WHERE ps.`id_shop`='.$id_shop.' AND substr(p.reference,1,1) <> "5"
            AND substr(p.reference,1,1) <> "4" AND ps.active = 1 
            LIMIT '.$desde.','.$hasta);    
        
        return $products;

    }

    public function activateProducts($id_product, $active, $id_shop) {

        echo "<br>ID_PRODUCT: $id_product, ACTIVE: $active, ID_SHOP: $id_shop<br>";

        $sql = 'UPDATE ps_product_shop SET active= ' . (int) $active . ' WHERE `id_shop` = '.              (int) $id_shop.' and  `id_product` =' . (int) $id_product;
    
        if (!Db::getInstance()->execute($sql)) {
            $message = "Error el ejecutar $sql, ¿porqué?";
            echo "<br>".$message."<br>";
            return false;
        } else {
            return true;
        }
    }

}

