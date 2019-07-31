<?php

require_once _PS_MODULE_DIR_ . 'mipilotoshipping/mipilotoshipping.php';

class sincronizacionwebservicesCheckStatusPedidosModuleFrontController extends ModuleFrontController {



    public function initContent() {
    	parent::initContent();
        $pedidos = $this->getPedidos();
        if (!empty($pedidos)){
            $data = $this->recorrerPedidos($pedidos);
        }
        $respuesta = array('status'=>true);
        echo json_encode($respuesta);
    	exit;
    	$this->setTemplate('productos.tpl');
    }

    private function recorrerPedidos($pedidos) {
        $mipiloto = new Mipilotoshipping();
        //$dataResult = ["products"=>null,"num_guia"=>null,"motorizado"=>["nombre"=>null,"telefono"=>null],"id_order"=>null];
        
        foreach ($pedidos as $pedido) {
            $order = new Order($pedido["id_order"]);
            
            $num_guia = $pedido["num_guia"];
            $infoPedido = $mipiloto->infoPedido($num_guia);
            $estado_pedido_id = $infoPedido->pedido->estado->id;
            $estado_pedido = $infoPedido->pedido->estado->estado;

            if ($estado_pedido_id == 5) {
                $this->changeOrderStatus($order,5);
            } else if (($estado_pedido_id == 10) || ($estado_pedido_id == 11) || ($estado_pedido_id == 12) || ($estado_pedido_id == 14) || ($estado_pedido_id == 17) ) {
                $this->changeOrderStatus($order,6);
            }

            
        }
    }

    private function changeOrderStatus($order, $state) {
        ShopUrl::cacheMainDomainForShop((int) $order->id_shop);
        $order_state = new OrderState($state);

        if (!Validate::isLoadedObject($order_state)) {
            $this->errors[] = Tools::displayError('The new order status is invalid.');
        }
        $current_order_state = $order->getCurrentOrderState();
        $history = new OrderHistory();
        $history->id_order = $order->id;
        $use_existings_payment = false;
        if (!$order->hasInvoice()) {
            $use_existings_payment = true;
        }
        $history->changeIdOrderState((int) $order_state->id, $order, $use_existings_payment);
        $history->addWithemail(true);
    }


    private function getPedidos() {
        //15 payment payphone
        $pedidos = Db::getInstance()->executeS('
        SELECT * 
        FROM `ps_orders` as po
        WHERE po.current_state = 15 AND 
        po.print = "S" AND
        po.num_guia is not null ');

        return $pedidos;
    }



}

