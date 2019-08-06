<?php

require_once _PS_MODULE_DIR_ . 'mipilotoshipping/mipilotoshipping.php';

class sincronizacionwebservicesCheckPedidosPendientesModuleFrontController extends ModuleFrontController {



    public function initContent() {
    	parent::initContent();
        $respuesta = array('status'=>false);
        $id_shop = Tools::getValue('id_shop');
        if (isset($id_shop)){
            $pedidos = $this->getPedidos($id_shop);
            if (!empty($pedidos)){
                $data = $this->recorrerPedidos($pedidos);
                $respuesta = array('status'=>true,'result' => $data);
            }
        }
        echo json_encode($respuesta);
    	exit;
    	$this->setTemplate('productos.tpl');
    }

    private function recorrerPedidos($pedidos) {
        $mipiloto = new Mipilotoshipping();
        $dataResult = ["products"=>null,"num_guia"=>null,"motorizado"=>["nombre"=>null,"telefono"=>null],"id_order"=>null];
        $pedidosResult = array();
        foreach ($pedidos as $pedido) {
            $order = new Order($pedido["id_order"]);
            $products = $order->getProducts();
            $id_carrier = $order->id_carrier;
            $carrier = new Carrier($id_carrier);
            $num_guia = $pedido["num_guia"];
            $infoPedido = $mipiloto->infoPedido($num_guia);
            $total = 0;
            $items = array();
            foreach ($products as $product) {
                $prod = array('descripcion' => $product["product_name"],
                              'cantidad' => $product["product_quantity"]);
                $total += $product["product_quantity"];
                $items[] = $prod;
            }
            $dataResult["motorizado"]["nombre"] = "NO";
            $dataResult["motorizado"]["telefono"] = "NO";
            $dataResult["num_guia"] = $pedido["id_order"];

            $dataResult["products"] = $items;
            $dataResult["total"] = $total;

            if ($infoPedido) {
                $dataResult["num_guia"] = $num_guia;
                $dataResult["motorizado"]["nombre"] = $infoPedido->pedido->motorizado->nombre." ".$infoPedido->pedido->motorizado->apellido;
                $dataResult["motorizado"]["telefono"] = $infoPedido->pedido->motorizado->telefono;    
            }

            $dataResult["id_order"] = $pedido["id_order"];
            $dataResult["num_fct"] = $pedido["establecimiento"].$pedido["punto_emision"].$pedido["secuencial"];
            $dataResult["carrier"] = $carrier->name;
            $pedidosResult[] = $dataResult;
        }
        return $pedidosResult;
    }


    private function getPedidos($id_shop) {
        //15 payment payphone
        $pedidos = Db::getInstance()->executeS('
        SELECT * 
        FROM ps_orders as po
        INNER JOIN factura_cabecera as fc
        ON po.id_order = fc.id_order
        WHERE po.id_shop = '.$id_shop.' AND
        (po.current_state = 15 or po.current_state = 2)  AND po.print = "N" AND po.valid = 1 AND
        fc.numero_pedido IS NOT NULL AND fc.documento_contable_recaudo IS NOT NULL') ;

        return $pedidos;
    }



}

