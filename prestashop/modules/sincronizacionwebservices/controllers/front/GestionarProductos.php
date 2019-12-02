<?php
    
    error_reporting(E_STRICT | E_ALL) ;
    require_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/nusoap.php');
    set_time_limit(30000000);
    
    
    class sincronizacionwebservicesGestionarProductosModuleFrontController extends ModuleFrontController {
        
        public function initContent() {
            
            parent::initcontent();
            
            
            include_once(_PS_MODULE_DIR_ . 'sincronizacionwebservices/include/config.php');
            
            $action = Tools::getValue('action', "");
            if ($action == "sync") {
                $this->SincronizarNuevosProductos();
            }
            
            
            
            exit;
            
            
            
            
            
            $this->setTemplate('productos.tpl');
            
            
            /* $name = "demo actualizado";
             $ean13 = "7861234567895";
             $reference = "01049675005";
             $category_name = "TEQUILA";
             $price = 101;
             $active = true;
             $description_short = "descripcion corta ud";
             $description = "descripcion larga up";
             $url_image = "https://www.lataberna.com.ec/cuenca/remigio-crespo/img/la-taberna-logo-1452529412.jpg";
             $meta_title = "";
             $meta_keywords = "";
             $width = 0;
             $height = 0;
             $depth = 0;
             $weight = 0;
             $update = false;
             $wholesale_price = 101;
             $incremet_price = 0;
             
             $attributes = array(
             array(
             "atributes" => array(
             array("name" => "Sabor", "value" => "Fresa"),
             array("name" => "Origen", "value" => "Escocia"),
             array("name" => "A�ejamiento", "value" => "Est�ndar")
             ),
             "price" => $incremet_price,
             "reference" => $reference,
             "ean13" => $ean13,
             "wholesale_price" => $wholesale_price,
             ),
             array(
             "atributes" => array(
             array("name" => "Sabor", "value" => "Naranja"),
             array("name" => "Color", "value" => "Blanco"),
             array("name" => "A�ejamiento", "value" => "Est�ndar")
             ),
             "price" => 10,
             "reference" => $reference,
             "ean13" => $ean13,
             "wholesale_price" => $wholesale_price,
             ),
             ); */
        }

        public function url_exists($url) {
            $handle = curl_init($url);
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

            /* Get the HTML or whatever is linked in $url. */
            $response = curl_exec($handle);

            /* Check for 404 (file not found). */
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            return $httpCode;

            curl_close($handle);
        }

        public function disactive_products_with_out_image(){
            $id_images_array = $this->get_all_images();
            //var_dump($id_images_array);
            foreach ($id_images_array as $id_images_row) {
                $id = (int) $id_images_row["id_image"];
                $id_product = (int) $id_images_row["id_product"];
                $product = new Product($id_product);
                $image = new Image($id);
                $path = $this->context->link->getImageLink($product->link_rewrite, $id, ImageType::getFormatedName('home'));
                $exist = $this->url_exists($path);

                echo "id_product: $id_product, id_image:$id, path: $path, Exist img: $exist <br>";
            }
        }




        
        public function delete_images_by_product_id($id_product) {
            $id_images_array = $this->get_images_by_product_id($id_product);
            foreach ($id_images_array as $id_images_row) {
                $id = (int) $id_images_row["id_image"];
                $image = new Image($id);
                $result = true;
                print $id . "\n";
                
                if ($result) {
                    $image->deleteImage(true);
                    $image->deleteProductAttributeImage();
                    $query1 = "DELETE FROM  `ps_image_lang` WHERE  `id_image` =$id";
                    if (!Db::getInstance()->execute($query1)) {
                        echo "No se pudo" . $query1;
                    }
                    
                    $query2 = "DELETE FROM  `ps_image_shop` WHERE  `id_image` =$id";
                    if (!Db::getInstance()->execute($query2)) {
                        echo "No se pudo" . $query2;
                    }
                    
                    $query3 = "DELETE FROM  `ps_image` WHERE  `id_image` =$id";
                    if (!Db::getInstance()->execute($query3)) {
                        echo "No se pudo" . $query3;
                    }
                }
            }
        }
        
        public function get_images_by_product_id($id_product) {
            $sql = "SELECT id_image FROM  `ps_image` WHERE  `id_product` = $id_product";
            return Db::getInstance()->executeS($sql);
        }

        public function get_all_images() {
            $sql = "SELECT * FROM  `ps_image` ";
            return Db::getInstance()->executeS($sql);
        }




        public function create_update_product_new($id_product,$reference,$nombre,$marca,$categoria,$precio,$stock,$id_shop){

            $id_category = $this->get_associated_category_by_label($categoria);
            $id_manufacturer = $this->get_or_create_manufactured($marca);

            echo "<br>ID_CATEGORY: $id_category<br>";
            echo "<br>ID_MANUFACTURER: $id_manufacturer<br>";

            $shops = Shop::getShops(true, null, true);
            if (!$id_product){
                $product = new Product();
                $product->reference = $reference;
                $product->name = array((int) Configuration::get('PS_LANG_DEFAULT') => $nombre);
                $product->price = $precio;
                $product->ean13 = null;
                $product->id_tax_rules_group = 1;
                $product->id_manufacturer = $id_manufacturer;
                $product->id_supplier = 0;
                $product->quantity = 1;
                $product->minimal_quantity = 1;
                $product->additional_shipping_cost = 0;
                $product->wholesale_price = 0;
                $product->ecotax = 0;
                $product->out_of_stock = 0;
                $product->active = false;
                $product->id_category_default = $id_category;
                $product->category = $id_category;
                $product->available_for_order = 1;
                $product->show_price = 1;
                $product->on_sale = 0;
                $product->online_only = 0;
                $product->meta_title = $nombre;
                $product->meta_keywords = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $nombre);
                $product->description = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $nombre);
                $product->description_short = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $nombre);
                $product->link_rewrite = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($nombre));
                $product->tags = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($nombre));

                $message = "<br>Listo para crear nuevo producto reference: $reference <br>";
                echo $message;

                //echo "<br>******PRODUCT********<br>";
                //var_dump($product);*/
                echo "<br>NO existe product con reference: $reference<br>";

            } else {
                $product = new Product($id_product);
                $product->price = (float)$precio;

                echo "<br>Actualizando producto id_producto: $id_product, reference: $reference, price: $precio<br>";
                //$product->id_tax_rules_group = 1;
                $product->id_manufacturer = $id_manufacturer;
                //$product->active = false;
                //$product->available_for_order = 1;
                //$product->show_price = 1;
                $product->id_category_default = $id_category;
                $product->category = $id_category;
                //$product->on_sale = 0;
                //$product->online_only = 0;
                //$product->quantity = 1;
                //$product->minimal_quantity = 1;
                /*$product->meta_title = $NombreProducto;
                $product->meta_keywords = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->description = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->description_short = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->link_rewrite = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($NombreProducto));
                $product->tags = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($NombreProducto));*/
                $message = "<br>Listo para actualizar producto reference: $reference<br>";
                echo $message;
                //echo "<br>******PRODUCT A ACTUALIZAR********<br>";
                //var_dump($product);
            }

            try {
                if ($product->save()) {
                    echo "<b>Pasado el save()</b>";
                    
                    if (!$id_product){
                        $product->associateTo($shops);
                        //$this->update_date_add($product);
                        
                    }
                    $product->addToCategories(array($id_category));
                    $id_product = $product->id;
                    StockAvailable::setQuantity($id_product, 0, (int) $stock, $id_shop);
                    $active = (int)$stock > 0 ? 1 : 0;
                    echo "<br>ACTIVE: $active<br>";
                    $activateProducts = $this->activateProducts($id_product, $active, $id_shop);
                    return true;
                } else {
                    echo "<b>NO el save()</b>";
                    return false;
                }
            } catch (PrestaShopException $e) {
                $message = 'Message: ' . $e->getMessage();
                echo "<br>" . $message . "<br>";
                $this->addLog($message, 3, $reference);
                return false;
            }
        }


        public function activateProducts($id_product, $active, $id_shop) {

            echo "<br>ID_PRODUCT: $id_product, ACTIVE: $active, ID_SHOP: $id_shop<br>";

            $sql = 'UPDATE ps_product_shop SET active= ' . (int) $active . ' WHERE `id_shop` = '.              (int) $id_shop.' and  `id_product` =' . (int) $id_product;
        
            if (!Db::getInstance()->execute($sql)) {
                $message = "Error el ejecutar $sql, ¿porqué?";
                echo "<br>".$message."<br>";
                return false;
            } else {
                return true;
            }
        }


        public function create_update_product($id_product,$reference,$marca,$precio){

            //$id_category = $this->get_associated_category_by_label($Categoria);
            $id_manufacturer = $this->get_or_create_manufactured($marca);

            //echo "<br>ID_CATEGORY: $id_category<br>";
            //echo "<br>ID_MANUFACTURER: $id_manufacturer<br>";

            //$shops = Shop::getShops(true, null, true);
            if (!$id_product){
                /*$product = new Product();
                $product->reference = $reference;
                $product->name = array((int) Configuration::get('PS_LANG_DEFAULT') => $NombreProducto);
                $product->price = $Precio;
                $product->ean13 = null;
                $product->id_tax_rules_group = 1;
                $product->id_manufacturer = $id_manufacturer;
                $product->id_supplier = 0;
                $product->quantity = 1;
                $product->minimal_quantity = 1;
                $product->additional_shipping_cost = 0;
                $product->wholesale_price = 0;
                $product->ecotax = 0;
                $product->out_of_stock = 0;
                $product->active = false;
                $product->id_category_default = $id_category;
                $product->category = $id_category;
                $product->available_for_order = 1;
                $product->show_price = 1;
                $product->on_sale = 0;
                $product->online_only = 0;
                $product->meta_title = $NombreProducto;
                $product->meta_keywords = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->description = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->description_short = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->link_rewrite = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($NombreProducto));
                $product->tags = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($NombreProducto));

                $message = "<br>Listo para crear nuevo producto reference: $reference <br>";
                echo $message;

                //echo "<br>******PRODUCT********<br>";
                //var_dump($product);*/
                echo "<br>NO existe product con reference: $reference<br>";

            }else{
                $product = new Product($id_product);
                $product->price = (float)$precio;
                $product->id_tax_rules_group = 1;
                echo "<br>Actualizando producto id_producto: $id_product, reference: $reference, price: $precio<br>";
                //$product->id_tax_rules_group = 1;
                $product->id_manufacturer = $id_manufacturer;
                //$product->active = false;
                $product->available_for_order = 1;
                $product->show_price = 1;
                //$product->id_category_default = $id_category;
                //$product->category = $id_category;
                //$product->on_sale = 0;
                //$product->online_only = 0;
                //$product->quantity = 1;
                //$product->minimal_quantity = 1;
                /*$product->meta_title = $NombreProducto;
                $product->meta_keywords = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->description = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->description_short = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $NombreProducto);
                $product->link_rewrite = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($NombreProducto));
                $product->tags = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($NombreProducto));*/
                $message = "<br>Listo para actualizar producto reference: $reference<br>";
                echo $message;
                //echo "<br>******PRODUCT A ACTUALIZAR********<br>";
                //var_dump($product);
            }

            try {
                if ($product->save()) {
                    echo "<b>Pasado el save()</b>";
                    //$product->associateTo($shops);
                    //if (!$id_product){
                      //  $this->update_date_add($product);
                       // $product->addToCategories(array($id_category));
                    //}
                    return true;
                } else {
                    echo "<b>NO el save()</b>";
                    return false;
                }
            } catch (PrestaShopException $e) {
                $message = 'Message: ' . $e->getMessage();
                echo "<br>" . $message . "<br>";
                $this->addLog($message, 3, $reference);
                return false;
            }
        }



        
        private function SincronizarNuevosProductos() {
            echo "Empieza " . date('m/d/Y h:i:s a', time()) . "<br>";
            $new_products_by_taberna = $this->_get_ws_new_products();
            
            
            
            
            
            
            $total = count($new_products_by_taberna->ARTICULOS) - 1;
            echo "Hay un total de $total productos <br>";
            $message = "Empieza sincronizacion de productos, se encontraron un total de $total productos nuevos";
            $this->addLog($message);
            // $offset = (int) Tools::getValue('offset', 0);
            $limit = 50000;
            $index = 0;
            $iteraccion = 0;
            echo "<pre>";
            while ($index < $total and $iteraccion < $limit) {
                $index++;
                $iteraccion++;
                $item = $new_products_by_taberna->ARTICULOS[$index];
                //print $index . "-" . $item->COD_TIENDA . "</br>";
                $code = $item->COD_TIENDA;
                $iva = $item->IVA;
                
                
                $id_shop = $this->get_id__by_code($code);
                if (!$id_shop) {
                    $message = "La taberna $code no existe";
                    //$this->addLog($message, 4);
                    continue;
                }
                $name = $item->NOMBRE;
                $reference = ($item->REFERENCIA);
                $ean13 = $item->EAN13;
                $description_short = $item->DES_CORTA;
                $description = $item->DES_LARGA;
                $tags = $item->ETIQUETA;
                $active = $item->ACTIVO;
                $meta_title = $item->META_TITLE;
                $meta_keywords = $item->META_DESC;
                $category_name = $item->CATEGORIA;
                
                $url_image = $item->URLIMG;
                $price = $item->PRECIO;
                $stock = $item->CANTIDAD;
                $manufactured = $item->MARCA;
                $features = array();
                if (isset($item->CARACTERISTICAS) and ! empty($item->CARACTERISTICAS)) {
                    $features = $item->CARACTERISTICAS;
                }
                $added = false;
                $added = $this->saveProduct($name, $iva, $ean13, $reference, $category_name, $price, $manufactured, $description_short, $description, $url_image, $meta_title, $meta_keywords, $tags, $features);
                if ($added) {
                    $message = "<br>$index.-  Agregado producto $reference";
                    echo $message;
                } else {
                    $message = "<br>$index.-  No pudo ser agregado el producto $reference en la tienda $id_shop <br>";
                    echo $message;
                    $this->addLog($message, 3);
                }
            }
            echo "</pre>";
            
            
            
            
            
            $message = "Finaliza la Sincronizacion de $total productos";
            $this->addLog($message, 1);
            echo "<br>Finaliza " . date('m/d/Y h:i:s a', time()) . "<br>";
            
            return;
        }
        
        public function saveProduct($name, $iva, $ean13, $reference, $category_name, $price, $manufactured, $description_short, $description = "", $url_image, $meta_title = "", $meta_keywords = "", $tags = "", $features = "", $width = 0, $height = 0, $depth = 0, $weight = 0, $attributes = array(), $update = false,$store_id) {
            // $id = $this->search_product_by_ean13($ean13);
            $id = $this->search_product_by_reference($reference);
            $shops = Shop::getShops(true, null, true);
            if ($id) {
                
                $message = "<br>El producto $reference, ya existe<br>";
                echo $message;
                $object = new Product($id);
                $object->id_manufacturer = $this->get_or_create_manufactured($manufactured);
                $object->price = $price;
                

                if ($object->save()){
                    echo "<br>Actualizando...<br>";
                    $object->associateTo($shops);
                }
                
                
                /*echo "<br>Verificando Si el producto ya cuenta con una imagen <br>";
                $this->_set_image_products($object, $url_image, false);
                echo "<br>Finalizada la verificacion<br>";*/
                
                
                //           $this->addLog($message);
                return true;
            }
            
            
            
            $object = new Product();
            
            $id_category = $this->get_associated_category_by_label($category_name);
            $object->reference = $reference;
            $object->name = array((int) Configuration::get('PS_LANG_DEFAULT') => $name);
            $object->price = $price;
            $object->ean13 = $ean13;
            $object->id_tax_rules_group = 1;
            $object->id_manufacturer = $this->get_or_create_manufactured($manufactured);
            $object->id_supplier = 0;
            $object->quantity = 1;
            $object->minimal_quantity = 1;
            $object->additional_shipping_cost = 0;
            $object->wholesale_price = 0;
            $object->ecotax = 0;
            $object->width = $width;
            $object->height = $height;
            $object->depth = $depth;
            $object->weight = $weight;
            $object->out_of_stock = 0;
            $object->active = false;
            $object->id_category_default = $id_category;
            $object->category = $id_category;
            $object->available_for_order = 1;
            $object->show_price = 1;
            $object->on_sale = 0;
            $object->online_only = 0;
            
            $object->meta_title = $meta_title;
            $object->meta_keywords = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $meta_keywords);
            $object->description = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $description);
            $object->description_short = array((int) (Configuration::get('PS_LANG_DEFAULT')) => $description_short);
            $object->link_rewrite = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($name));
            $object->tags = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($tags));
            
            
            
            
            
            
            try {
                if ($object->save()) {
                    $object->addToCategories(array($id_category,));
                    //$this->_set_image_products($object, $url_image, $update);
                    // $this->create_attributtes_combinations($object, $attributes);
                    $this->add_features_to_product($object->id, $features);
                    $object->associateTo($shops);
                    $this->update_date_add($object);
                    return true;
                } else {
                    return false;
                }
            } catch (PrestaShopException $e) {
                $message = 'Message: ' . $e->getMessage();
                echo "<br>" . $message . "<br>";
                $this->addLog($message, 3, $reference);
                return false;
            }
        }
        
        /* } else {
         $message = "Ya existe el producto ean13: " . $ean13;
         echo $message;
         PrestaShopLogger::addLog($message, 1, 611, "Product", 611, true);
         return false;
         } */
        
        private function update_date_add($product) {
            $date = date('Y-m-d h:i:s');
            echo "<br>FECHA: $date<br>";
            $sql = 'UPDATE ps_product_shop SET  `date_add` =  "' . $date . '" , date_upd =  "' . $date . '", id_category_default = "' . $product->id_category_default . '", price= "' . $product->price . '" , id_tax_rules_group = "' . $product->id_tax_rules_group . '", available_for_order="'.$product->available_for_order.'" , show_price="'. $product->show_price .'", active= "' . $product->active . '" , on_sale= "' .$product->on_sale . '", wholesale_price= "' . $product->wholesale_price . '"  WHERE  `id_product` =' . $product->id;
            // print_r($sql);
            if (!Db::getInstance()->execute($sql)) {
                return false;
            } else {
                return true;
            }
        }
        
        private function add_features_to_product($id_product, $features = array()) {
            if (!empty($features)) {
                foreach ($features->CARACTERISTICAS as $feature) {
                    $name = $feature->NOMBRE;
                    $value = $feature->VALOR;
                    $id_feature = $this->get_or_create_featured_id_by_name($name);
                    $id_feature_value = $this->get_or_create_featured_value_id_by_name_and_value($id_feature, $value);
                    Product::addFeatureProductImport($id_product, $id_feature, $id_feature_value);
                }
            }
        }
        
        private function create_attributtes_combinations($product, $array_attributes = array(), $update = false) {
            
            foreach ($array_attributes as $array) {
                
                $attributes = $array["atributes"];
                
                $array_id_attributes = array();
                foreach ($attributes as $attribute) {
                    $name = $attribute["name"];
                    $value = $attribute["value"];
                    $id_attribute_group = $this->get_or_create_attribute_group_id_by_name($name);
                    $id_attribute = $this->get_or_create_attribute_id_by_name_and_group($id_attribute_group, $value);
                    $array_id_attributes[] = $id_attribute;
                }
                $price = $array["price"];
                $reference = $array["reference"];
                $ean13 = $array["ean13"];
                $wholesale_price = $array["wholesale_price"];
                
                if ($product->productAttributeExists($array_id_attributes)) {
                    echo "Ya existe esta combinacion";
                    //
                    //                $this->errors[] = Tools::displayError('You must be logged in to request a custom order');
                    //
                    //                foreach ($array_id_attributes as $id_product_attribute) {
                    //                    print_r($product->deleteAttributeCombination($id_product_attribute));
                    //                }
                    continue;
                }
                
                
                
                $weight = 0;
                $unit_impact = 0;
                $ecotax = 0;
                $quantity = 10;
                $id_images = null;
                $id_supplier = null;
                $default = false;
                $location = null;
                $upc = null;
                $minimal_quantity = 1;
                $id_shop_list = Shop::getShops(true, null, true);
                
                
                $id_product_attribute = (int) $product->addCombinationEntity(
                                                                             $wholesale_price, $price, $weight, $unit_impact, $ecotax, $quantity, $id_images, $reference, $id_supplier, $ean13, $default, $location, $upc, $minimal_quantity, $id_shop_list);
                $combination = new Combination($id_product_attribute);
                
                $combination->setAttributes($array_id_attributes);
                
                
                
                StockAvailable::setProductDependsOnStock($product->id, true, null, $id_product_attribute);
                // StockAvailable::setProductOutOfStock($product->id,  false, null, $id_product_attribute);
                //id_product_attribute::cleanDeadCombinations();
                //Product::save();
                
                $shops_ = Shop::getShops(true, null, true);
                $shops = array_keys($shops_);
                foreach ($shops as $id_shop) {
                    StockAvailable::setQuantity($product->id, $id_product_attribute, 1, $id_shop);
                }
            }
            
            $product->checkDefaultAttributes();
        }
        
        private function _set_image_products($product, $url_image, $update = false) {
            try {
                if ($url_image == "" or $url_image == null) {
                    $message = "No ha enviado una url de imagen remota para descargar";
                    $this->addLog($message);
                    return;
                }
                $image_exists = false;
                
                $query = "SELECT * FROM `ps_image` WHERE `id_product` = $product->id";
                
                
                $row = Db::getInstance()->getRow($query);
                if ($row) {
                    $image_exists = true;
                }
                
                //echo "Eliminado imagen del producto $object->id";
                // $this->delete_images_by_product_id($object->id);
                
                if ($image_exists == true) {
                    if ($update == true) {
                        $this->delete_images_by_product_id($product->id);
                    } else {
                        echo "Ya cuenta con una imagen y no se ha establecido para actualizacion";
                        return;
                    }
                } else {
                    echo "<br>Este producto no cuenta con una imagen <br>";
                }
                
                
                $url = $this->_get_image_remote($url_image);
                
                if (empty($url) || $url == "" || $url == null) {
                    $message = "No existe la imagen local o no es valida " . $url;
                    echo $message;
                    return;
                }
                
                $image = new Image();
                $image->id_product = $product->id;
                $image->position = Image::getHighestPosition($product->id) + 1;
                //$image->cover = true;
                $shops = Shop::getShops(true, null, true);
                if (($image->validateFields(false, true)) === true &&
                    ($image->validateFieldsLang(false, true)) === true && $image->add()) {
                    $image->associateTo($shops);
                    if (!self::copyImg($product->id, $image->id, $url, 'products', false)) {
                        $image->delete();
                    }
                    $this->set_image_cover_for_all_stores($product->id, $image->id);
                }
            } catch (PrestaShopException $e) {
                $message = 'Error al establecer imagen por defecto: Message: ' . $e->getMessage();
                echo "<br>" . $message . "<br>";
                $this->addLog($message, 4, 612, 612);
                return false;
            }
        }
        
        /**
         * Set image to cover for all the stores
         * @param type $id_product
         * @param type $id_image
         */
        protected function set_image_cover_for_all_stores($id_product, $id_image, $cover = 1) {
            $query = "UPDATE  `ps_image_shop` SET  `cover` = $cover,`id_product` =$id_product WHERE id_image =$id_image";
            if (!Db::getInstance()->execute($query)) {
                $message = 'Error al ejectutar: ' . $query;
                echo "<br>" . $message . "<br>";
                $this->addLog($message, 4, 612, 612);
            }
        }
        
        protected static function copyImg($id_entity, $id_image = null, $url, $entity = 'products') {
            $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
            $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));
            
            switch ($entity) {
                default:
                case 'products':
                    $image_obj = new Image($id_image);
                    $path = $image_obj->getPathForCreation();
                    break;
                case 'categories':
                    $path = _PS_CAT_IMG_DIR_ . (int) $id_entity;
                    break;
            }
            $url = str_replace(' ', '%20', trim($url));
            
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($url))
                return false;
            
            // 'file_exists' doesn't work on distant file, and getimagesize make the import slower.
            // Just hide the warning, the traitment will be the same.
            if (@copy($url, $tmpfile)) {
                ImageManager::resize($tmpfile, $path . '.jpg');
                $images_types = ImageType::getImagesTypes($entity);
                foreach ($images_types as $image_type)
                ImageManager::resize($tmpfile, $path . '-' . stripslashes($image_type['name']) . '.jpg', $image_type['width'], $image_type['height']);
                
                if (in_array($image_type['id_image_type'], $watermark_types))
                    Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
            }
            else {
                unlink($tmpfile);
                return false;
            }
            unlink($tmpfile);
            return true;
        }
        
        /**
         * Descarga una copia de la imagen remota
         * @param type $url from remote image
         * @return string path local
         */
        private function _get_image_remote($url) {
            $ruta_local = "/tmp/";
            //$ruta_local = "/home/taberna/web/upload/";
            if (!is_writable($ruta_local)) {
                $message = 'La ruta "' . $ruta_local . '" no es escribible<br>';
                echo $message;
                $this->addLog($message);
                return "";
            }
            echo "verificando Imagen <br>";
            // echo "<img src='$ruta_local'  alt='$ruta_local'/>";
            
            $ruta_local = $ruta_local . "img_" . rand() . ".jpg";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            
            $rawdata = curl_exec($ch);
            
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            
            if ($code != "200") {
                $message = "La imagen $url no existe ErrorCode: $code";
                echo $message;
                //$this->addLog($message);
                return null;
            } else {
                print_r($code);
            }
            
            if ($rawdata) {
                $fp = fopen($ruta_local, 'w');
                fwrite($fp, $rawdata);
                fclose($fp);
            } else {
                (curl_error($ch));
                $ruta_local = null;
            }
            curl_close($ch);
            return $ruta_local;
        }
        
        /**
         * if not find anything by default return the category "Varios"
         * @param type $name_ Category Name
         * @return type String
         */
        private function get_associated_category_by_label($label) {
            $name = utf8_encode($label);
            $id_category = $this->get_category_by_description($name);
            if (!$id_category) {
                //$default_category = "Varios";
                //$id_category = $this->find_category_by_name($default_category);
                $id_category = null;
                return $id_category;
            } else {
                return $id_category;
            }
        }
        
        /**
         * Get or Create a category by Name
         * @param type $name_
         * @return type id
         */
        private function get_or_create_category($name_) {
            $name = utf8_encode($name_);
            $id_category = $this->find_category_by_name($name);
            if (!$id_category) {
                $object = new Category();
                $object->name = array((int) Configuration::get('PS_LANG_DEFAULT') => $name);
                $object->id_parent = 36;
                $object->link_rewrite = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($name));
                $object->add();
                $shops = Shop::getShops(true, null, true);
                $object->associateTo($shops);
                return $object->id;
            } else {
                return $id_category;
            }
        }
        
        /**
         * Get or Create a manufactured by name
         * @param type $name_
         * @return type int
         */
        private function get_or_create_manufactured($name) {
            // $name = utf8_encode($name_);
            $arrayMarcas = array('JOHNNIE WALKER RED L' => 'JOHNNIE WALKER',
                                 'JOHNNIE WALKER BLACK' => 'JOHNNIE WALKER',
                                 'JOHNNIE DOUBLE BLACK' => 'JOHNNIE WALKER');
            if (array_key_exists($name,$arrayMarcas) == 1)
                $name = $arrayMarcas[$name];

            $id_manufactured = Manufacturer::getIdByName($name);
            if (!$id_manufactured) {
                $object = new Manufacturer();
                $object->name = $name;
                $object->active = true;
                $object->link_rewrite = array((int) (Configuration::get('PS_LANG_DEFAULT')) => Tools::link_rewrite($name));
                $object->add();
                $shops = Shop::getShops(true, null, true);
                $object->associateTo($shops);
                return $object->id;
            } else {
                $object = new Manufacturer($id_manufactured);
                $object->active = true;
                $object->save();
                return $id_manufactured;
            }
        }
        
        /**
         * Search a remote Category label in the description field of our local Categories
         * @param type $description
         * @return type int
         */
        private function get_category_by_description($description) {
            $arrayCategories = array('VINOS FRUTALES' => 'ESPUMANTE',
                                 'CERVEZA NACIONAL' => 'CERVEZA',
                                 'AGUA' => 'BEBIDA',
                                 'CAVA' => 'ESPUMANTE',
                                 'COCKTAIL' => 'COCTELERA',
                                 'COKTAIL' => 'COCTELERA',
                                 'COGNAC' => 'COÑAC',
                                 'LICOR DE HIERBAS' => 'APERITIVO',
                                 'ENERGIZANTE' => 'MIXER',
                                 'VINOS' => 'VINO',
                                 'CERVEZA IMPORTADA' => 'CERVEZA',
                                 'CHAMPAGNE' => 'CHAMPAÑA',
                                 'JEREZ' => 'VINO',
                                 'OPORTO' => 'VINO',
                                 'GRAPA' => 'APERITIVO',
                                 'BEBIDA RTD' => 'CERVEZA',
                                 'RTD' => 'CERVEZA',
                                 'GASEOSAS' => 'BEBIDA',
                                 'CIGARRILLOS' => 'SNACKS',
                                 'HIELO' => 'SNACKS',
                                 'CONFITERIA' => 'SNACKS',
                                 'VERMOUTH' => 'CREMAS',
                                 'CONSERVAS' => 'SNACKS',
                                 'ACCESORIOS' => 'SNACKS',
                                 'JUGOS' => 'BEBIDA',
                                 'MEZCAL' => 'TEQUILA',
                                 'SAKE' => 'AGUARDIENTE',
                                 'CRISTALERIA' => 'SNACKS',
                                 'TBC' => 'SNACKS',
                                 'ALIMENTOS' => 'SNACKS',
                                 'CERVEZAS' => 'CERVEZA'
                                 );
            if (array_key_exists($description,$arrayCategories) == 1)
                $description = $arrayCategories[$description];

            $sql = "SELECT c.id_category
            FROM ps_category c
            JOIN `ps_category_lang` cl ON c.id_category = cl.id_category
            WHERE cl.name like '%" . $description . "%'
            AND cl.id_lang = " . Configuration::get('PS_LANG_DEFAULT');
            $row = Db::getInstance()->getRow($sql);
            if ($row) {
                echo "<br>RESULT CATEGORIA:<br>";
                var_dump($row);
                echo "<br>----<br>";
                echo "<br>DESCRIPTION: $description<br>";
                //echo "<br>ID_CATEGORY: $row["id_category"]<br>";
                return (int) $row["id_category"];
            } else {
                return null;
            }
        }
        
        /**
         * Search local Category by Name Field
         * @param type $name Name of Category
         * @return type int
         */
        private function find_category_by_name($name) {
            $sql = "SELECT c.id_category
            FROM ps_category c
            JOIN `ps_category_lang` cl ON c.id_category = cl.id_category
            WHERE cl.name = '" . $name . "'
            AND cl.id_lang = " . Configuration::get('PS_LANG_DEFAULT');
            $row = Db::getInstance()->getRow($sql);
            if ($row) {
                return (int) $row["id_category"];
            } else {
                return null;
            }
        }
        
        /**
         * Get products of the Web Services to Update Stock and State
         * @param type $taberna_codigo
         * @return type object
         */
        public function _get_ws_products() {
            

            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;
            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call('Stock', array(), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                exit;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                    return json_decode($response["StockResult"]);
                }
            }




            /*
            try {
                $client = new SoapClient(WS_SERVER);
                $response = $client->__soapCall("Stock",array());
                return json_decode($response->StockResult);
            } catch (SoapFault $fault) {
                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                exit;
            }*/
        }


        /**
         * Get products of the Web Services to Update Stock and State
         * @param type $taberna_codigo
         * @return type object
         */
        public function _get_ws_products_catalogo() {
            
            
            $client = new nusoap_client(WS_SERVER, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;
            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            }
            $response = $client->call('Catalogo', array(), '', '', false, true);
            // Check for a fault
            if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($response);
                echo '</pre>';
                exit;
            } else {
                $err = $client->getError();
                if ($err) {
                    echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                    return json_decode($response["CatalogoResult"]);
                }
            }




            /*try {
                $context = stream_context_create(array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
                )
                ));

                $client = new SoapClient(WS_SERVER,array('stream_context' => $context));

                $response = $client->__soapCall("Catalogo",array());
                return json_decode($response->CatalogoResult);
            } catch (SoapFault $fault) {
                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                exit;
            }*/
        }


        


        public function merge_two_arrays($array1,$array2) {
            $data = array();
            $arrayAB = array_merge($array1,$array2);
            foreach ($arrayAB as $value) {
                $id = $value['Codigo'];
                if (!isset($data[$id])) {
                    $data[$id] = array();
                }
                $data[$id] = array_merge($data[$id],$value);
            }
            return $data;
        }

        
        /**
         * Get all products from the web services
         * @param type $taberna_codigo
         * @return type object
         */
        private function _get_ws_new_products($taberna_codigo = null) {
            $client = new SoapClient(WS_SERVER);
            $params = array(
                            "Taberna" => $taberna_codigo,
                            );
            $response = $client->__soapCall("ArticulosTab", array($params));
            
            return json_decode($response->ArticulosTabResult);
        }
        
        /**
         * Update Stock, Price, State by reference field and Shop
         * @param type $id_product
         * @param type $stock
         * @param type boolean
         */
        public function update_stock_available_price_state($reference, $stock, $price, $active, $id_shop, $id_tax_rules_group = 1) {
            
            try {
                $id_product = $this->search_product_by_reference($reference);
                $message = "<br>El producto  $reference tiene el id= ". $id_product."<br>";
                //$this->addLog($message, 1, 614, 614);
                echo $message;
                if ($id_product) {
                    
                    $id_lang = Configuration::get('PS_LANG_DEFAULT');
                    $product = new Product($id_product, true, $id_lang, $id_shop);
                    $product->id_tax_rules_group = $id_tax_rules_group;
                    
                    
                    if ($price != "no")
                        if($product->id_shop_default == $id_shop){
                            $product->price=$price;
                            $product->save();
                        }
                    
                    
                    
                    //if (true) {
                        //$message = "El producto  $reference ha sido guardado";
                        //$this->addLog($message, 1, 614, 614);
                        //echo "<br>$message<br>";

                        echo "INICIANDO ACTUALIZACION: id_product: $id_product, stock: $stock, id_shop: $id_shop, active: $active, precio: $price";

                        StockAvailable::setQuantity($id_product, 0, (int) $stock, $id_shop);
                         
                        $this->update_product_status_by_store($product->id, $product->id_tax_rules_group, $id_shop, $price, $active);
                    /*    return true;
                    } else {
                        $message = "El producto  $reference no ha sido guardado";
                        echo "<br>$message<br>";

                        //$this->addLog($message, 1, 614, 614);
                        
                        return false;
                    }*/
                    
                    
                    
                    return true;
                } else {
                    return false;
                }
            } catch (PrestaShopException $e) {
                $message = 'Message: ' . $e->getMessage();
                echo "<br>" . $message . "<br>";
                $this->addLog($message, 1, 612, 612);
                return false;
            }
        }
        
        /**
         * Search product by ean13 field
         * @param type valid $ean13
         */
        private function search_product_by_ean13($ean13) {
            return Product::getIdByEan13($ean13);
        }
        
        /**
         * Search product by reference field
         * @param type $reference
         * @return type Product Object
         */
        public function search_product_by_reference($reference) {
            $query = new DbQuery();
            $query->select('p.id_product');
            $query->from('product', 'p');
            //$query->where('p.reference = \'' . pSQL($reference) . '\' AND p.id_shop_default = '.$id_shop);
            $query->where('p.reference = \'' . pSQL($reference) . '\'');
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
        }

        /**
         * Search product by reference field
         * @param type $reference
         * @return type Product Object
         */
        public function search_product_reference_by_id($id_product) {
            $query = new DbQuery();
            $query->select('p.reference');
            $query->from('product', 'p');
            //$query->where('p.reference = \'' . pSQL($reference) . '\' AND p.id_shop_default = '.$id_shop);
            $query->where('p.id_product = \'' . pSQL($id_product) . '\'');
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
        }


        public function deactivate_all_products_all_stores(){
            $sql = 'UPDATE ps_product_shop SET active= "0" ';
            if (!Db::getInstance()->execute($sql)) {
                $message = "Error el ejecutar $sql";
                echo $message;
                $this->addLog($message, 4, 612, 612);
                return false;
            } else {
                return true;
            }
        }

        public function desactivate_all_products_for_shop($id_shop){
            $sql = 'UPDATE ps_product_shop SET active="0" WHERE id_shop='.$id_shop;
            if (!Db::getInstance()->execute($sql)) {
                $message = "Error el ejecutar $sql";
                echo $message;
                $this->addLog($message, 4, 612, 612);
                return false;
            } else {
                return true;
            }
        }

        
        /**
         * Update the porduct Price and product Status by Shop
         * @param type $id_product
         * @param type $id_shop
         * @param type $price
         * @param type $active
         * @return boolean
         */
        private function update_product_status_by_store($id_product, $id_tax_rules_group, $id_shop, $price, $active) {
            
            if ($price != "no")
                $sql = 'UPDATE ps_product_shop SET price= "' . $price . '", id_tax_rules_group = "' . (int) $id_tax_rules_group . '"  WHERE  `id_product` =' . (int) $id_product . " and id_shop = " . (int) $id_shop;
            else {
                if ($active == 0) {
                    $sql = 'UPDATE ps_product_shop SET active= "' . $active . '" , id_tax_rules_group = "' . (int) $id_tax_rules_group . '"  WHERE  `id_product` =' . (int) $id_product . " and id_shop = " . (int) $id_shop;                    
                } else {
                    $sql = 'UPDATE ps_product_shop SET id_tax_rules_group = "' . (int) $id_tax_rules_group . '"  WHERE  `id_product` =' . (int) $id_product . " and id_shop = " . (int) $id_shop;
                }
            }

            //$sql = 'UPDATE ps_product_shop SET active= "' . $active . '" , id_tax_rules_group = "' . (int) $id_tax_rules_group . '"  WHERE  `id_product` =' . (int) $id_product . " and id_shop = " . (int) $id_shop;



            $this->addLog($sql, 1, 613, 614);
            
            if (!Db::getInstance()->execute($sql)) {
                $message = "Error el ejecutar $sql";
                echo $message;
                $this->addLog($message, 4, 612, 612);
                return false;
            } else {
                return true;
            }
        }
        
        /**
         * Get Attribute or Create if not exists
         * @param type $name_
         * @param type $notNull
         * @return type int
         */
        private function get_or_create_attribute_group_id_by_name($name_, $notNull = false) {
            $name = utf8_encode($name_);
            $id_lang = Configuration::get('PS_LANG_DEFAULT');
            $sql = 'SELECT *
            FROM  `ps_attribute_group_lang`
            WHERE  `name` =  "' . $name . '"
            AND id_lang =  "' . $id_lang . '"';
            $row = Db::getInstance()->getRow($sql);
            if ($row) {
                return (int) $row["id_attribute_group"];
            } else {
                $newAttGroup = new AttributeGroupCore();
                $newAttGroup->name = array((int) Configuration::get('PS_LANG_DEFAULT') => $name);
                $newAttGroup->public_name = array((int) Configuration::get('PS_LANG_DEFAULT') => $name);
                $newAttGroup->is_color_group = 0;
                $newAttGroup->group_type = "select";
                $newAttGroup->position = AttributeGroupCore::getHigherPosition() + 1;
                $newAttGroup->add();
                $shops = Shop::getShops(true, null, true);
                $newAttGroup->associateTo($shops);
                return $newAttGroup->id;
            }
        }
        
        /**
         * Get or Create a Featured by name
         * @param type $name
         * @return type int
         */
        private function get_or_create_featured_id_by_name($name) {
            $id_feature = Feature::addFeatureImport($name);
            return $id_feature;
        }
        
        /**
         * Get or Create a Featured Value by name and featured id
         * @param type $id_feature
         * @param type $value
         * @return type int
         */
        private function get_or_create_featured_value_id_by_name_and_value($id_feature, $value) {
            $id_lang = Configuration::get('PS_LANG_DEFAULT');
            $id_feature_value = FeatureValue::addFeatureValueImport($id_feature, $value, null, $id_lang);
            return $id_feature_value;
        }
        
        /**
         * 
         * @param type $id_attribute_group
         * @param type $name_
         * @return type int
         */
        private function get_or_create_attribute_id_by_name_and_group($id_attribute_group, $name_) {
            $name = utf8_encode($name_);
            $id_lang = Configuration::get('PS_LANG_DEFAULT');
            $sql = 'SELECT a.id_attribute
            FROM  `ps_attribute` a
            JOIN  `ps_attribute_lang` al ON a.id_attribute = al.id_attribute
            WHERE a.id_attribute_group ="' . $id_attribute_group . '"
            AND al.name =  "' . $name . '"
            AND id_lang ="' . $id_lang . '"';
            $row = Db::getInstance()->getRow($sql);
            if ($row) {
                return (int) $row["id_attribute"];
            } else {
                $newAttribute = new AttributeCore();
                $newAttribute->name = array((int) Configuration::get('PS_LANG_DEFAULT') => $name);
                $newAttribute->id_attribute_group = $id_attribute_group;
                $newAttribute->position = AttributeGroupCore::getHigherPosition() + 1;
                $newAttribute->add();
                $shops = Shop::getShops(true, null, true);
                $newAttribute->associateTo($shops);
                return $newAttribute->id;
            }
        }
        

        



        /**
         * 
         * @param type $id  taberna interna
         * @return int de taberna remota
         */
        public function get_code_by_id($id) {
            switch ($id) {

                // Sierra SUR
                case 3: return 2931;  // Cuenca Gran Colombia Zona Rosa
                case 4: return 2930;  // Cuenca Remigio Crespo
                case 5: return 2932;  // Cuenca Estadio
                case 17: return 2942; // Cuenca Showroom
                case 18: return 2945; // Cuenca Winery
                case 14: return 2937; // Loja


                // Sierra NORTE
                case 6: return 2933;  // Quito Orellana
                case 7: return 2934;  // Quito Cumbayá
                case 12: return 2956; // Quito Brasil
                case 19: return 2957; // Quito Eloy Alfaro
                case 16: return 2941; // Ambato
                case 20: return 2962; // Quito El Condado
                case 21: return 2960; // Quito Winery Eloy Alfaro
                case 22: return 2963; // Santo Domingo 
                case 23: return 2964; // Quito Boliche Botellas


                // Costa
                case 9: return 2935;  // Guayaquil Urdesa
                case 11: return 2938; // Guayaquilil Samborondón
                case 1: return 2936;  // Manta
                case 15: return 2943; // Machala
                case 10: return 2939; // Guayaquil Hilton Kennedy Norte
                case 24: return 2959; // Manta Plazaventura
                case 25: return 2961; // Playas

                
                
                
                
                
                default: return null; // 



            }
        }
        
        /**
         * 
         * @param type $id  taberna interna
         * @return int de taberna remota
         */
        public function get_id__by_code($code) {
            switch ($code) {
                case 2931: return 3;
                case 2930: return 4;
                case 2932: return 5;
                case 2933: return 6;
                case 2934: return 7;
                case 2935: return 9;
                case 2936: return 1;
                case 2937: return 14;
                case 2938: return 11;
                case 2939: return 10;
                case 2941: return 16;
                case 2943: return 15;
                case 2956: return 12;
                case 2942: return 17;
                case 2945: return 18;
                case 2957: return 19;
                case 2962: return 20;
                case 2960: return 21;
                case 2963: return 22;
                case 2964: return 23;
                case 2959: return 24;
                case 2961: return 25;


                default: return null;
            }
        }


        /**
         * 
         * @param type $id  taberna interna
         * @return int de taberna remota
         */
        public function get_oficina_venta__by_code($code) {
            switch ($code) {
                case 2930: return "0930";
                case 2931: return "0931";
                case 2932: return "0932";
                case 2933: return "0933";
                case 2934: return "0934";
                case 2935: return "0935";
                case 2936: return "0936";
                case 2937: return "0937";
                case 2938: return "0938";
                case 2939: return "0939";
                case 2941: return "0941";
                case 2943: return "0943";
                case 2956: return "0952";
                case 2942: return "0942";

                case 2945: return "0945";
                case 2957: return "0957";
                case 2962: return "0962";
                case 2960: return "0960";
                case 2963: return "0963";
                case 2964: return "0964";
                case 2959: return "0959";
                case 2961: return "0961";
                
                default: return null;
            }
        }

        
        public function addLog($message, $gravedad = 1, $codigo_objecto = 611, $codigo_error = 611) {
            PrestaShopLogger::addLog($message, $gravedad, $codigo_error, "Product", $codigo_objecto, true);
        }
        
    }

