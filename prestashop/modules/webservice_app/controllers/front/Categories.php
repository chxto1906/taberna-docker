<?php

require_once _PS_MODULE_DIR_ . 'webservice_app/sql/Consultas.php';
require_once _PS_MODULE_DIR_ . 'webservice_app/response/Response.php';
header('Content-Type: application/json');
        
class Webservice_AppCategoriesModuleFrontController extends ModuleFrontController {

    public $log = null;
    public $limit = "0";

    public function initContent() {
    	parent::initContent();
        try {
            $response = new Response();
            $status_code = 200;

            $home = Tools::getValue("home",0);
            $categories = array();
            if ($home == 1){
                $whisky = array('id_category' => "42", 
                                    'name' => "Whisky",
                                    "image_url" => Context::getContext()->shop->getBaseURL(true)."img/c/home_app/whisky.jpg"
                                    );
                $ron = array('id_category' => "39", 
                                    'name' => "Ron",
                                    "image_url" => Context::getContext()->shop->getBaseURL(true)."img/c/home_app/ron.jpg"
                                    );
                $tequila = array('id_category' => "41", 
                                    'name' => "Tequila",
                                    "image_url" => Context::getContext()->shop->getBaseURL(true)."img/c/home_app/tequila.jpg"
                                    );
                $gin = array('id_category' => "47", 
                                    'name' => "Gin",
                                    "image_url" => Context::getContext()->shop->getBaseURL(true)."img/c/home_app/gin.jpg"
                                    );
                $mixers = array('id_category' => "75", 
                                    'name' => "Mixers",
                                    "image_url" => Context::getContext()->shop->getBaseURL(true)."img/c/home_app/mixers.jpg"
                                    );
                array_push($categories, $whisky);
                array_push($categories, $ron);
                array_push($categories, $tequila);
                array_push($categories, $gin);
                array_push($categories, $mixers);
            } else {

                $category = new Category(36);
                $categories = $category->getSubCategories(1);
                
                $categories = $this->proccessCategories($categories,$home);
            }
            if ($categories)
                echo $response->json_response($categories,$status_code);
            else{
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

    public function proccessCategories($categories,$home) {
        $categoriesResult = array();
        $dataResult = [
            "id_category"    => null,
            "name"          => null,
            //"description"   => null
        ];
        foreach ($categories as $category) {
            $dataResult["id_category"] = $category["id_category"]; 
            $dataResult["name"] = $category["name"];

            if ($home==1)
                $dataResult["image_url"] = Context::getContext()->shop->getBaseURL(true);

            //$dataResult["description"] = $category["description"];
            
            $categoriesResult[] = $dataResult;
        }
        return $categoriesResult;
    }


}

