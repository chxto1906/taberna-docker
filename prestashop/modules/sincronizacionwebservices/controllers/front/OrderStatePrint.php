<?php

require_once _PS_MODULE_DIR_ . 'mipilotoshipping/mipilotoshipping.php';

class sincronizacionwebservicesOrderStatePrintModuleFrontController extends ModuleFrontController {



    public function initContent() {
    	parent::initContent();
        $respuesta = array('status'=>false);
        $id_order = Tools::getValue('id_order');
        if (isset($id_order)){
            $actualizar = $this->actualizarOrder($id_order);
            if ($actualizar){
                $respuesta = array('status'=>true);
            }
        }

        //echo "*********** SIIIIIII ENTRO **********";

        echo json_encode($respuesta);
    	exit;
    	$this->setTemplate('productos.tpl');
    }

    private function actualizarOrder($id_order) {
        
        $orderDB = Db::getInstance()->executeS('
        UPDATE ps_orders o
        SET print="S" WHERE o.id_order ='. $id_order);

        return $orderDB;

    }

    private function recorrerPedidos($pedidos) {
        $mipiloto = new Mipilotoshipping();
        $dataResult = ["products"=>null,"num_guia"=>null,"motorizado"=>["nombre"=>null,"telefono"=>null]];
        $items = array();
        foreach ($pedidos as $pedido) {
            $order = new Order($pedido["id_order"]);
            $products = $order->getProducts();
            $num_guia = $pedido["num_guia"];
            $infoPedido = $mipiloto->infoPedido($num_guia);
            foreach ($products as $product) {
                $prod = array('descripcion' => $product["product_name"],
                              'cantidad' => $product["product_quantity"]);
                $items[] = $prod;
            }
            $dataResult["products"] = $items;
            $dataResult["num_guia"] = $num_guia;
            $dataResult["motorizado"]["nombre"] = $infoPedido->pedido->motorizado->nombre." ".$infoPedido->pedido->motorizado->apellido;
            $dataResult["motorizado"]["telefono"] = $infoPedido->pedido->motorizado->telefono;
        }
        return $dataResult;
    }


    private function getPedidos($id_shop) {
        //15 payment payphone
        $pedidos = Db::getInstance()->executeS('
        SELECT * 
        FROM `ps_orders` as po
        WHERE po.id_shop = '.$id_shop.' AND
        po.num_guia is NOT null AND
        po.current_state = 15 AND po.print = "S"');

        return $pedidos;
    }



}

