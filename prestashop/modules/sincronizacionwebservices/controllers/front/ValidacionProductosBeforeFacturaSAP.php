<?php


include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaSAP.php';
require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php';
        
class sincronizacionwebservicesValidacionProductosBeforeFacturaSAPModuleFrontController extends ModuleFrontController {



    public function initContent() {
    	parent::initContent();
        
    	exit;
    	$this->setTemplate('productos.tpl');
    }

    public function validate($order) {
    	$valid = null;
    	$facturaSAP = new sincronizacionwebservicesFacturaSAPModuleFrontController();
        $products = $order->getProducts();
        $id_shop_current = (int)Context::getContext()->shop->id;
        $gestionarProductosController = new sincronizacionwebservicesGestionarProductosModuleFrontController();
        $cod_almacen = $gestionarProductosController->get_code_by_id($id_shop_current);
        $stock = 0;
        $existeArticulo = true;
        $disponible = true;
        $lote = true;
        $message = "";
        foreach ($products as $product) {
            //$resArticulo = $facturaSAP->checkStock($cod_almacen,$product["product_reference"]);
            $articulo = $facturaSAP->recuperaArticulo($cod_almacen,$product["product_reference"]);
            if (is_array($articulo)){
                if ($articulo[0] == "0"){
                    $flag = $facturaSAP->getFlag($articulo[9],$articulo[10]);
                    if (($flag == 3) || ($flag == 2)) {
                        $valido = $facturaSAP->validarLoteSerie($cod_almacen,$product["product_reference"],$flag,$articulo[19],$articulo[18]);
                        if ((int)$valido != 0 ){
                            $lote = false;
                            $message .= "- Producto: ".$product["product_name"].", reference: ".$product["product_reference"]." por el momento no se pudo validar existencia de lote";
                        }
                    }
                    //if (isset($resArticulo)){
                    //    if (isset($resArticulo->STOCK)) {
                    //        if (isset($resArticulo->STOCK[0])) {
                                //$stock = $resArticulo->STOCK[0]->MARLIB;
                    //        }
                    //    }
                    //}
                    //$stock - 10;  //BUFFER de Stock - Henry
                    $stock = $articulo[15];
                    $stock = $stock - BUFFER_STOCK;
                    if ((int)$stock < (int)$product["product_quantity"]) {
                        $disponible = false;
                        $message .= "- Producto: ".$product["product_name"].", reference: ".$product["product_reference"]." no tiene suficiente stock (".$stock<0?0:$stock.").";
                    }
                }else{
                    $existeArticulo = false;
                    $message .= "- Producto: ".$product["product_name"].", reference: ".$product["product_reference"]." por el momento no se encuentra disponible en esta tienda.";
                }
            }else{
                $existeArticulo = false;
                $message .= "- Producto: ".$product["product_name"].", reference: ".$product["product_reference"]." por el momento no se encuentra disponible en esta tienda.";
            }
        }
        if (!$lote || !$disponible || !$existeArticulo) {
            $valid = $message;
        }
        return $valid;
    }

}




