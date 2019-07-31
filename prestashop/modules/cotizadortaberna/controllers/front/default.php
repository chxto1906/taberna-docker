<?php

class cotizadortabernadefaultModuleFrontController extends ModuleFrontController{
	public function initContent()
		{
			
            var_dump($this->context->cart);
            
            /*$order_id = Order::getOrderByCartId($cart_id);
        	$order = new Order($order_id);*/
		}
}
?>