<?php

require_once 'ClassExcel/PHPExcel/IOFactory.php';
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaSAP.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');

class sincronizacionwebservicesReadExcelProductsModuleFrontController extends ModuleFrontController {



    public function initContent() {
        parent::initContent();


        $excelObject = PHPExcel_IOFactory::load(_PS_MODULE_DIR_."sincronizacionwebservices/controllers/front/lista_definitiva_articulos.xlsx");
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        
        //var_dump($excelObject);
        $getSheet = $excelObject->getActiveSheet()->toArray(null);
        $total = count($getSheet);
        $i = 0;
        $id_shop = Tools::getValue('id_shop');
        $parte = (int) Tools::getValue('parte');
        $cantidadDividir = count($getSheet)/4;
        $getSheet = array_chunk($getSheet, $cantidadDividir);
        $getSheet = $getSheet[$parte-1];


        $productsPrices = $gestionarController->_get_ws_products_catalogo();
        $productsPrices = !empty($productsPrices->ARTICULOS) ? $productsPrices->ARTICULOS : [];
        if ($parte == 1)
            $updateActiveResult = $gestionarController->desactivate_all_products_for_shop($id_shop);
        foreach ($getSheet as $value) {
            //if ($i > 2) {
                
                    $reference = $value["0"];
                    $activar = trim(strtolower($value["2"]));
                    $active = $activar == "x" ? 1 : 0;
                    if ($active == 1)
                        $this->proccess($id_shop,$reference,$active,$productsPrices);
                    //echo "<br> REFERENCE: $reference, ACTIVAR: $active <br>";
            //}
            //$i++;
        }

        exit;
        $this->setTemplate('productos.tpl');
    }



    public function proccess($id_shop,$reference,$active,$productsPrices) {
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        $cod_almacen = $gestionarController->get_code_by_id($id_shop);
        $id_product = $gestionarController->search_product_by_reference($reference);
        if ($cod_almacen && $id_product) {
            $articulo = $facturaSAP->recuperaArticulo($cod_almacen,$reference);
            if (is_array($articulo)) {
                if ($articulo[0] == "0"){
                    $stock = (int)$articulo[15];
                    $stock = $stock - BUFFER_STOCK;
                    echo "<br>STOCK seteado para product reference: $reference Es: $stock<br>";
                    if ($stock <= 0) {
                        $active = 0;
                    } else {
                        $productPrice = $this->searchArray($reference,$productsPrices);
                        if ($productPrice) {
                            $marca = $productPrice->Marca;
                            $precio = $productPrice->Precio;
                            $added = $gestionarController->create_update_product($id_product,$reference,$marca,$precio);
                            if ($added) {
                                $active = 1;
                                $message = "<br> Guardado producto reference: $reference correctamente <br>";
                                echo $message;
                            } else {
                                $active = 0;
                                $message = "<br>No pudo ser actualizado el producto reference $reference con el precio: $precio <br>";
                                echo $message;
                            }
                        } else {
                            $active = 0;
                        }
                    }
                } else {
                    $active = 0;
                }
            } else {
                $active = 0;
            }
            if ($active == 1)
                StockAvailable::setQuantity($id_product, 0, (int) $stock, $id_shop);
            $activateProducts = $this->activateProducts($id_product, $active, $id_shop);
        }
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



