<?php

require_once 'ClassExcel/PHPExcel/IOFactory.php';
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaSAP.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');

class sincronizacionwebservicesReadExcelProductsModuleFrontController extends ModuleFrontController {



    public function initContent() {
        parent::initContent();

        /*$id_shop = "17";
        $reference = "10086823";
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        $cod_almacen = $gestionarController->get_code_by_id($id_shop);
        $id_product = $gestionarController->search_product_by_reference($reference);
        $product = new Product($id_product);
        var_dump($product);


        exit;*/


        $excelObject = PHPExcel_IOFactory::load(_PS_MODULE_DIR_."sincronizacionwebservices/controllers/front/lista_definitiva_articulos.xls");
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        
        //var_dump($excelObject);
        $getSheet = $excelObject->getActiveSheet()->toArray(null);
        $total = count($getSheet);
        $i = 0;
        $id_shop = Tools::getValue('id_shop');
        $desactive = Tools::getValue('desactive');
        $productsPrices = $gestionarController->_get_ws_products_catalogo();
        $productsPrices = !empty($productsPrices->ARTICULOS) ? $productsPrices->ARTICULOS : [];

        $productsStocks = $gestionarController->_get_ws_products();
        $productsStocks = !empty($productsStocks->STOCK) ? $productsStocks->STOCK : [];

        if ($productsPrices && $productsStocks) {

            if ($desactive == "1")
                $gestionarController->desactivate_all_products_for_shop($id_shop);

            foreach ($getSheet as $value) {
                $reference = $value["0"];
                $activar = trim(strtolower($value["2"]));
                $active = $activar == "x" ? 1 : 0;
                $this->proccess($id_shop,$reference,$active,$productsPrices,$productsStocks);
            }
        } else {
            echo "<br>SAP vacío<br>";
        }

        exit;
        $this->setTemplate('productos.tpl');
    }



    public function proccess($id_shop,$reference,$active,$productsPrices,$productsStocks) {
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        $cod_almacen = $gestionarController->get_code_by_id($id_shop);
        $id_product = $gestionarController->search_product_by_reference($reference);
        $stock = 0;
        if ($cod_almacen /*&& $id_product*/) {
            $articulo = $this->searchArrayStock($reference,$cod_almacen,$productsStocks);
            $productPrice = $this->searchArray($reference,$productsPrices);
            if ($articulo && $productPrice) {
                $stock = (int)$articulo->MARLIB;
                $stock = $stock - BUFFER_STOCK;
                echo "<br>STOCK seteado para product reference: $reference Es: $stock<br>";
		        echo "<br>### El active se puso en $active ###<br>";
                $marca = $productPrice->Marca;
                $precio = $productPrice->Precio;
                $categoria = $productPrice->Categoría;
                $nombre = $productPrice->NombreProducto;
                $added = $gestionarController->create_update_product($id_product,$reference,$marca,$precio,$categoria,$nombre);
                if ($added) {
                    if ($active==1)
                        $active = $stock <= 0 ? 0 : 1;
                    $message = "<br> Guardado producto reference: $reference correctamente <br>";
                    echo $message;
                } else {
                    $active = 0;
                    $message = "<br>No pudo ser actualizado el producto reference $reference con el precio: $precio <br>";
                    echo $message;
                }
            }else{
                $message = "<br>Active en 0 en producto $reference NO encontrado en SAP<br>";
                echo $message;
                $active = 0;
            }
        }else{
            $message = "<br>Active en 0 en producto $reference NO encontrado almacen o producto<br>";
            echo $message;
            $active = 0;
        }

        $message = "<br>ACTIVE al final es: $active <br>";
                    echo $message;

        StockAvailable::setQuantity($id_product, 0, (int) $stock, $id_shop);
        $activateProducts = $this->activateProducts($id_product, $active, $id_shop);
    }


    public function searchArrayStock($id, $cod_almacen, $array) {
       foreach ($array as $key) {
           if (($key->MARMAT == $id) && ($key->MARALM == $cod_almacen)) {
               return $key;
           }
       }
       return null;
    }

    public function searchArray($id, $array) {
       foreach ($array as $key) {
           if ($key->Codigo == $id) {
               return $key;
           }
       }
       return null;
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



