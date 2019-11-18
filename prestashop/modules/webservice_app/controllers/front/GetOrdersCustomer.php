<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
header('Content-Type: application/json');
        
class Webservice_AppGetOrdersCustomerModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";
    private $status_code = 400;

    public function initContent() {
    	parent::initContent();
        $response = new Response();

        if (!$this->context->customer->logged){
            $this->status_code = 401;
            $this->content = ["message" => "Usuario no logueado"];
        } else {
        	$month_ago = Tools::getValue("month_ago",1);
        	$month_ago = (int) $month_ago < 1 ? 1 : $month_ago;
            $this->getOrdersCustomer($month_ago);
        }

        echo $response->json_response($this->content,$this->status_code);
        exit;

    	$this->setTemplate('productos.tpl');
    }

    public function getOrdersCustomer($month_ago=1) {

    	$base_url = Context::getContext()->shop->getBaseURL(true);
    	$sql = "
        SELECT o.id_order, DATE_FORMAT(fc.fecha_autorizacion, '%d/%m/%Y') as fecha_autorizacion, 
        CONCAT('$ ',fc.importe_total) AS importe_total, 
		REPLACE (fc.nombre_arch_pdf,'/home','".$base_url."facturas') as url_pdf
		FROM `factura_cabecera` fc
		INNER JOIN ps_orders o
		ON fc.id_order = o.id_order
		WHERE fc.estado = 'FINALIZADO' AND
		fc.fecha_autorizacion >= DATE_ADD(DATE_SUB(NOW(), INTERVAL ".$month_ago." MONTH), INTERVAL 1 DAY) AND
		fc.fecha_autorizacion <= DATE_SUB(NOW(), INTERVAL 0 MONTH) AND 
		o.id_customer = ".$this->context->customer->id. " ORDER BY fc.fecha_autorizacion DESC";

        $consultas = new Consultas();
        $orders = $consultas->list($sql);

        if ($orders){
        	$this->content = $orders;
        	$this->status_code = 200;
        } else {
        	http_response_code(204);
            exit;
        }

    }


}

