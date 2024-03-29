<?php

require_once _PS_MODULE_DIR_ . 'rvproductstab/rvproductstab.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
require_once _PS_MODULE_DIR_ . 'rvcategorysearch/rvcategorysearch.php';
header('Content-Type: application/json');

class Webservice_AppProductsModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $page = 1;
    public $qty_by_page = 10;
    public $by = null;
    public $type = null;

    public function __construct($response = array()) {
        parent::__construct($response);
        $this->display_header = false;
        $this->display_header_javascript = false;
        $this->display_footer = false;
    }

    public function initContent() {
    	parent::initContent();
        try {
            $response = new Response();
            $status_code = 200;
            $products = [];
            //$this->limit = Tools::getValue('limit')?Tools::getValue('limit'):$this->limit;
            $id_product = Tools::getValue("id_product");
            if ($id_product) {
                $products = $this->getProduct($id_product,$response);
            } else {
                if ($this->valid_params(["by"])) {
                    $by = Tools::getValue("by");
                    switch ($by) {
                        case "classification":
                            if ($this->valid_params(["type"])) {
                                $type = Tools::getValue("type");
                                $products = $this->getProductsByClassification($type);
                            } else {
                                $products = ["message" => "Validación fallida. Parámetro: type es obligatorio."];
                                $status_code = 400;
                            }
                            break;
                        case "categories":
                            if ($this->valid_params(["type","page"])) {
                                $type = Tools::getValue("type");
                                $products = $this->getProductsByCategories($type,$response);
                            } else {
                                $products = ["message" => "Validación fallida. Parámetros: type, page son obligatorios."];
                                $status_code = 400;
                            }
                            break;
                        case "search":
                            if ($this->valid_params(["query"])) {
                                $query = Tools::getValue("query");
                                $products = $this->getProductsBySearch($query);
                            } else {
                                $products = ["message" => "Validación fallida. Parámetro: query es obligatorio."];
                                $status_code = 400;
                            }
                            break;
                        default:
                            $products = [];
                    }
                } else {
                    $products = ["message" => "Validación fallida. Parámetro: by es obligatorio."];
                    $status_code = 400;
                }
            }

            if ($products){
                echo $response->json_response($products,$status_code);
            }else{
                http_response_code(204);
                exit;
            }

            exit;
        } catch (Exception $e) {
            echo $response->json_response($e->getMessage(),500);
            exit;
        }
    	$this->setTemplate('productos.tpl');
    }


    public function getProductsBySearch($query) {
        $rvcategorysearch = new RvCategorySearch();
        $id_category = "all";
        $id_manufacturer = Tools::getValue("id_manufacturer");

        if (Tools::getValue("id_category"))
            $id_category = Tools::getValue("id_category");    
        $searchResults = $rvcategorysearch->getSearchProduct($id_category, Context::getContext()->language->id, $query, 1, 1, 'position', 'asc', false, true, null, $id_manufacturer);
        return $this->proccessProducts($searchResults["result"]);
    }


    public function getProductsByClassification($type) {
        $rvproductstab = new Rvproductstab();
        if ($type == "mejorvendidos") {
            $products = $rvproductstab->getBestSellers(true);
        } elseif ($type == "recienllegados") {
            $products = $rvproductstab->getNewProducts(true);
        } elseif ($type == "destacados") {
            $products = $rvproductstab->getFeaturedProducts(true);
        }
        return $this->proccessProducts($products);
    }

    public function getProduct($id_product,$response) {
        $result = ["message" => "Producto no encontrado"];
        $id_shop = (int)Context::getContext()->shop->id;
        $product = new Product((int) $id_product,true,1,$id_shop,Context::getContext());
        if (isset($product->id)) {
            $price_tax_exc = $product->getPriceStatic($id_product,false,null,2);
            $price_tax_inc = $product->getPriceStatic($id_product,true,null,2);
            $products = array([
                "id_product"    =>  $product->id,
                "name"          =>  $product->name,
                "description"   =>  $product->description,
                "price_tax_exc" =>  $this->formatPrice($price_tax_exc),
                "price_tax_inc"   =>  $this->formatPrice($price_tax_inc),
                "quantity"      =>  $product->quantity,
                "reference"     =>  $product->reference,
                "manufacturer_name" => $product->manufacturer_name,
                "link_rewrite"  =>  $product->link_rewrite,
                "id_category_default"   => $product->id_category_default
            ]);
            $result = $this->proccessProducts($products,true);
            return $result;
        } else {
            echo $response->json_response($result,404);
            exit;
        }
    }

    public function getProductsByCategories($category_id,$response) {

        $arrayPage = explode(",",Tools::getValue("page"));
        $total = Tools::getValue("total");
        $page = $arrayPage[0];
        $qty_by_page = $arrayPage[1];
        $products = [];
        $category = new Category((int)$category_id);

        if (isset($category->id)){
            if ($total) {
                $products = $category->getProducts(1,0,0,"id_product","ASC",true,true,false,1,true);
            } else {
                if ($page && $qty_by_page) {
                    $products = $category->getProducts(1,$page,$qty_by_page,"id_product","ASC",false,true,false,1,true);
                } else {
                    echo $response->json_response(["message" => "Formato inválido del parámetro page."],400);
                    exit;
                }    
            }

            if (is_array($products))
                $products = $this->proccessProducts($products);
        }else{
            $products = ["message" => "Categoría no encontrada para listar productos."];
            echo $response->json_response($products,404);
            exit;  
        }

        return $products;
    }


    public function valid_params($params=[]) {
        $result = true;
        foreach ($params as $param) {
            $pa = Tools::getValue($param);
            if (!Tools::getValue($param)){
                $result = false;
                break;
            }
        }
        return $result;
    }

    public function proccessProducts($products=null, $unique = false) {
        $productsResult = array();
        if (!$products) {
            http_response_code(204);
            exit;
        } else {
            $dataResult = [
                "id_product"                => null,
                "name"                      => null,
                "description"               => null,
                "price_tax_exc"             => null,
                "price_tax_inc"   => null,
                "stock"                  => null,
                "reference"                 => null,
                "image_small"               => null,
                "image_home"                => null,
                "image_large"               => null
            ];
            foreach ($products as $product) {
                $dataResult["id_product"] = $product["id_product"]; 
                $dataResult["name"] = $product["name"];
                $dataResult["description"] = $product["description"];
                $producto = new Product($product["id_product"]);
                $price_tax_exc = $producto->getPriceStatic($product["id_product"],false,null,2);
                $price_tax_inc = $producto->getPriceStatic($product["id_product"],true,null,2);

                $dataResult["price_tax_exc"] = $this->formatPrice($price_tax_exc);
                $dataResult["price_tax_inc"] = $this->formatPrice($price_tax_inc);
                $dataResult["stock"] = $product["quantity"];
                $dataResult["reference"] = $product["reference"];
                $dataResult["manufacturer_name"] = $product["manufacturer_name"];
                $images = Product::getCover($product["id_product"]);
                $image_url_small = $this->context->link->getImageLink($product["link_rewrite"], $images['id_image'], ImageType::getFormatedName('small'));
                $image_url_home = $this->context->link->getImageLink($product["link_rewrite"], $images['id_image'], ImageType::getFormatedName('home'));
                $image_url_large = $this->context->link->getImageLink($product["link_rewrite"], $images['id_image'], ImageType::getFormatedName('large'));
                $dataResult["image_small"] = $image_url_small;
                $dataResult["image_home"] = $image_url_home;
                $dataResult["image_large"] = $image_url_large;
                $dataResult["id_category"] = $product["id_category_default"];
                $productsResult[] = $dataResult;
            }
        }

        $productsResult = $unique ? $productsResult[0] : $productsResult;

        return $productsResult;
    }

    public function formatPrice($price, $curr = '')
    {
        return Tools::displayPrice(
            $price,
            $this->context->currency,
            false,
            $this->context
        );
    }


}

