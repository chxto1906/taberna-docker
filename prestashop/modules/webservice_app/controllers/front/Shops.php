<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
        
class Webservice_AppShopsModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        $response = new Response();

        $id_shop = Tools::getValue('id_shop');
        if (!$id_shop)
            $shops = $this->getShopsFull();
        else
            $shops = $this->getShopsFull($id_shop);

        echo $response->json_response($shops,200);

        exit;
    	$this->setTemplate('productos.tpl');
    }

    public function getShopsFull($id_shop=false) {
        //15 payment payphone

        $sql = "
        SELECT ps.id_shop, ps.name, psu.domain, psu.domain_ssl, psu.virtual_uri, psl.address1, psl.hours, pst.city, pst.postcode, pst.latitude, pst.longitude, pst.phone, pst.email
        FROM `ps_shop` as ps
        INNER JOIN `ps_shop_url` as psu
        ON ps.`id_shop` = psu.`id_shop`
        INNER JOIN `ps_store_shop` as pss
        ON ps.`id_shop` = pss.`id_shop`
        INNER JOIN `ps_store_lang` as psl
        ON pss.`id_store` = psl.`id_store`
        INNER JOIN `ps_store` as pst
        ON psl.`id_store` = pst.`id_store`
        WHERE ps.`active` = 1 AND pst.`active`=1";

        if ($id_shop) {
            $sql = $sql . " AND ps.id_shop = " . $id_shop;
        }

        $consultas = new Consultas();
        $shops = $consultas->list($sql,Tools::getValue('limit'));

        //$shops = Db::getInstance()->executeS($cadena) ;

        return $shops;
    }


}

