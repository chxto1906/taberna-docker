<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
header('Content-Type: application/json');
    
class Webservice_AppShopsModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        $response = new Response();
        $id_shop = Tools::getValue("shop_id");
        if (!$id_shop){
            $shops = $this->getShopsFull();
            if ($shops){
                echo $response->json_response($shops,200);
            }else{
                http_response_code(204);
                exit;
            }    
        }else{
            $shop = $this->getShopsFull($id_shop);
            if ($shop){
                echo $response->json_response($shop,200);
            }else{
                $shops = ["message" => "Tienda no encontrada"];
                echo $response->json_response($shops,404);
            }
        }

        exit;
    	$this->setTemplate('productos.tpl');
    }

    public function getShopsFull($id_shop=false) {
        //15 payment payphone

        $sql = "
        SELECT ps.id_shop, ps.name, psu.domain, psu.domain_ssl, psu.virtual_uri, psl.address1, pst.city, pst.postcode, pst.latitude, pst.longitude, pst.phone, pst.email
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
        if (!$id_shop) {
            $result = array();
            foreach ($shops as $shop) {
                $result[$shop['city']][] = $shop;
            }
        }else{
            $result = $shops[0];
        }

        //$shops = Db::getInstance()->executeS($cadena) ;

        return $result;
    }


}

