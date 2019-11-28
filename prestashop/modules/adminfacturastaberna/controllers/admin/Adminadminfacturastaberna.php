<?php
/**
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

class AdminadminfacturastabernaController extends ModuleAdminController
{
	private $months_ago = 1;

    public function __construct() {
         parent::__construct();
        //$module = "adminfacturastaberna";
        /*Tools::redirectAdmin('index.php?controller=AdminModules&configure='.$module.
            '&token='.Tools::getAdminTokenLite('AdminModules'));*/
            /*$this->context->smarty->assign(array(
					'email' => $email
				));*/
		

    }

    public function init() {
    	parent::init();
    }

    public function initContent() {
    	parent::initcontent();
    	
    	/*$pedidos = $this->getPedidos($this->months_ago);
    	$this->context->smarty->assign(array(
			'pedidos' => $pedidos,
			'months' => $this->months_ago
		));

    	$this->setTemplate('adminfacturastaberna.tpl');*/
    	
    }

    public function postProcess() {
    	$months = Tools::getValue("months_ago") ? Tools::getValue("months_ago") : $this->months_ago;
    	$pedidos = $this->getPedidos($months);
    	$this->context->smarty->assign(array(
			'pedidos' => $pedidos,
			'months' => $months
		));

    	$this->setTemplate('adminfacturastaberna.tpl');
    	
    }

    private function getPedidos($months_ago) {
    	$base_url = Context::getContext()->shop->getBaseURL(true);
    	$sql = "
        SELECT fc.id, o.id_order, DATE_FORMAT(fc.fecha_autorizacion, '%d/%m/%Y') as fecha_autorizacion, 
        CONCAT('$ ',fc.importe_total) AS importe_total, 
		REPLACE (fc.nombre_arch_pdf,'/home','".$base_url."facturas') as url_pdf,
		CONCAT(fc.establecimiento,fc.punto_emision,fc.secuencial) as num_fact,
		fc.numero_factura_sap as numero_factura_sap, fc.numero_documento_contable as numero_documento_contable
		FROM `factura_cabecera` fc
		INNER JOIN ps_orders o
		ON fc.id_order = o.id_order
		WHERE fc.estado = 'FINALIZADO' AND
		fc.fecha_autorizacion >= DATE_ADD(DATE_SUB(NOW(), INTERVAL ".$months_ago." MONTH), INTERVAL 1 DAY) AND
		fc.fecha_autorizacion <= DATE_SUB(NOW(), INTERVAL 0 MONTH) 
		ORDER BY fc.fecha_autorizacion DESC";

        return Db::getInstance()->executeS($sql);
    }
}