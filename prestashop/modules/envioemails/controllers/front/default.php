<?php

class envioemailsdefaultModuleFrontController extends ModuleFrontController{
	public function initContent()
		{
			
            $order = Tools::getValue('order');
            $cart = Tools::getValue('cart');
            

            echo "ORDER<hr>";
            var_dump($order);

            /*$order_id = Order::getOrderByCartId($cart_id);
        	$order = new Order($order_id);*/
		}
}
?>