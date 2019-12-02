<?php

require_once 'ClassExcel/PHPExcel/IOFactory.php';
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaSAP.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');

class sincronizacionwebservicesProductosActivarModuleFrontController extends ModuleFrontController {



    public function initContent() {
        parent::initContent();


        $excelObject = PHPExcel_IOFactory::load(_PS_MODULE_DIR_."sincronizacionwebservices/controllers/front/excelcolombia.xlsx");
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        
        //var_dump($excelObject);
        $getSheet = $excelObject->getActiveSheet()->toArray(null);
        $total = count($getSheet);
        $i = 0;
        $id_shop = Tools::getValue('id_shop');

        foreach ($getSheet as $value) {
            $reference = $value["3"];
            $active = 1;
            if ($active == 1)
                $this->proccess($id_shop,$reference,$active);
        }

        exit;
        $this->setTemplate('productos.tpl');
    }



    public function proccess($id_shop,$reference,$active) {
        $gestionarController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $cod_almacen = $gestionarController->get_code_by_id($id_shop);
        $id_product = $gestionarController->search_product_by_reference($reference);
        if ($cod_almacen && $id_product) {
            if ($active == 1)
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



