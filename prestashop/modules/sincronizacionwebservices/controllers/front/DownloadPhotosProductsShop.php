<?php
    
//require_once _PS_MODULE_DIR_ . 'mipilotoshipping/curl/curl_mipiloto.php';




include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/TabernaSOAP.php');
include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/GestionarProductos.php');
require_once _PS_ROOT_DIR_ . '/config/taberna_config_facturacion.php';
require_once _PS_ROOT_DIR_ . '/config/error_intentos.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';
require_once _PS_MODULE_DIR_ . 'mipilotoshipping/mipilotoshipping.php';
require_once _PS_MODULE_DIR_ . 'rvproductstab/rvproductstab.php';
require_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/nusoap.php');
include_once('az.multi.upload.class.php');
        
class sincronizacionwebservicesDownloadPhotosProductsShopModuleFrontController extends ModuleFrontController {

    public $log = null;
    private $img_1 = 'large';
    private $img_2 = 'medium';
    private $img_3 = '_default';

    public function getProducts() {
        $products = Db::getInstance()->executeS('
        SELECT pl.name, pl.link_rewrite, pi.id_image 
        FROM '._DB_PREFIX_.'product_shop as ps
        INNER JOIN '._DB_PREFIX_.'product_lang pl ON ps.id_product = pl.id_product
        INNER JOIN '._DB_PREFIX_.'image pi ON ps.id_product = pi.id_product
        WHERE ps.active = 1 AND ps.id_shop=3 AND pl.id_shop=3 AND pi.cover=1');

        return $products;
    }

    public function generate_path($id_image) {
        $cadena="/";
        $id_image = str_split($id_image);
        foreach ($id_image as $num) {
            $cadena.=$num."/";
        }
        return $cadena;
    }

    public function initContent() {
    	parent::initContent();
        $id_shop = Tools::getValue("id_shop") ? Tools::getValue("id_shop") : 3;
        $file_name_photos = "fotos_productos_taberna_".$id_shop.".zip";
        $zip = new ZipArchive;
        $products = $this->getProducts();
        if ($zip->open($file_name_photos,  ZipArchive::CREATE)) {
            foreach ($products as $product) {
                $url_image = $this->context->link->getImageLink(
                        $product['link_rewrite'],
                        $product['id_image']);
                $s = explode("/", $url_image);
                $path_to = $this->generate_path($s[5]);
                $n = $zip->addFile(getcwd().'/img/p'.$path_to.$s[5].'.jpg',$s[5].'.jpg');
            }
            $zip->close();

            header("Content-type: application/zip"); 
            header("Content-Disposition: attachment; filename=".basename($file_name_photos));
            header("Content-length: " . filesize($file_name_photos));
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
            readfile($file_name_photos);
            unlink($file_name_photos);
        }

    	exit;
    	$this->setTemplate('productos.tpl');
    }

    
    


}

