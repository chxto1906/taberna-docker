<?php

/* models/vehicles.php */
//require_once _PS_MODULE_DIR_ . 'rvcategorysearch/RvCategorySearch.php';

class Cargar_licores_para_escoger extends ObjectModel {

    public $id_product;
    public $id_image;
    public $name_product;
    public $id_category;
    public $name_category;
    public $price;
    public $volumen;
    public $impact;
    public $impuesto;
    public $id_cover;
    public $cantidad_stock;
    public static $definition = array(
        'table' => 'licores_escoger_cotizador',
        'primary' => 'id_product',
        'multilang' => false,
        'fields' => array(
            'id_product' => array(
                'type' => ObjectModel :: TYPE_INT
            ),
            'id_image' => array(
                'type' => ObjectModel :: TYPE_INT
            ),
            'name_product' => array(
                'type' => ObjectModel :: TYPE_STRING
            ),
            'id_category' => array(
                'type' => ObjectModel :: TYPE_INT
            ),
            'name_category' => array(
                'type' => ObjectModel :: TYPE_STRING
            ),
            'price' => array(
                'type' => ObjectModel :: TYPE_INT
            ),
            'volumen' => array(
                'type' => ObjectModel :: TYPE_STRING
            ),
            'impact' => array(
                'type' => ObjectModel :: TYPE_INT
            ),
            'impuesto' => array(
                'type' => ObjectModel :: TYPE_INT
            ),
            'id_cover' => array(
                'type' => ObjectModel :: TYPE_INT
            ),
            'cantidad_stock' => array(
                'type' => ObjectModel :: TYPE_INT
            ),
        )
    );

    public static function find($name_category) {

        $id_store = (int) Context::getContext()->shop->id;
        $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        if (($name_category == "tinto") || ($name_category == "blanco")) {

            $sql = "SELECT 
               p.`id_product`,
               p.reference,
               psa.quantity AS cantidad_stock,
               pl.name AS name_product,
               pl.link_rewrite AS link_rewrite,
               p.`id_category_default` AS id_category,
               pcl.name AS name_category,
               ps.`price` AS price,
               t.rate AS impuesto,
               ps_image_shop.id_image AS id_image,
               ps_image_shop.id_image AS id_cover,
               ps.id_shop
               
           FROM
               `ps_product` AS p
                   INNER JOIN
               ps_product_lang AS pl ON p.`id_product` = pl.`id_product` 
               AND   pl.id_lang = $id_lang AND pl.id_shop = $id_store
                   INNER JOIN
               ps_product_shop AS ps ON p.`id_product` = ps.`id_product`
                   AND ps.id_shop = $id_store
                   INNER JOIN
               ps_stock_available AS psa ON p.`id_product` = psa.`id_product`
                   AND psa.id_shop = $id_store
                   INNER JOIN
               ps_category_lang AS pcl ON pcl.`id_category` = p.`id_category_default`
                   AND pcl.id_shop = $id_store and pcl.id_lang = $id_lang
                   LEFT JOIN
               ps_image_shop ON ps_image_shop.id_product = p.id_product
                   AND ps_image_shop.id_shop = $id_store
                   LEFT JOIN
               ps_tax_rules_group trg ON p.id_tax_rules_group = trg.id_tax_rules_group
                   AND trg.active = 1
                   LEFT JOIN
               ps_tax_rule tr ON trg.id_tax_rules_group = tr.id_tax_rules_group
                   LEFT JOIN
               ps_tax t ON tr.id_tax = t.id_tax AND t.active = 1
               
            WHERE
                    ps.active = 1 and pcl.name ='Vino'";


            //PrestaShopLogger::addLog($sql, 1, 120, "Product", 120, true);
        } else {
            if ($name_category == "Champaña")
                $name_category = "Champaña & Espumantes";
            $sql = "SELECT 
               p.`id_product`,
               p.reference,
               psa.quantity AS cantidad_stock,
               pl.name AS name_product,
               pl.link_rewrite AS link_rewrite,
               p.`id_category_default` AS id_category,
               pcl.name AS name_category,
               ps.`price` AS price,
               t.rate AS impuesto,
               ps_image_shop.id_image AS id_image,
               ps_image_shop.id_image AS id_cover,
               ps.id_shop
           FROM
               `ps_product` AS p
                   INNER JOIN
               ps_product_lang AS pl ON p.`id_product` = pl.`id_product` 
               AND   pl.id_lang = $id_lang AND pl.id_shop = $id_store
                   INNER JOIN
               ps_product_shop AS ps ON p.`id_product` = ps.`id_product`
                   AND ps.id_shop = $id_store
                   INNER JOIN
               ps_stock_available AS psa ON p.`id_product` = psa.`id_product`
                   AND psa.id_shop = $id_store
                   INNER JOIN
               ps_category_lang AS pcl ON pcl.`id_category` = p.`id_category_default`
                   AND pcl.id_shop = $id_store and pcl.id_lang = $id_lang
                   LEFT JOIN
               ps_image_shop ON ps_image_shop.id_product = p.id_product
                   AND ps_image_shop.id_shop = $id_store
                   LEFT JOIN
               ps_tax_rules_group trg ON p.id_tax_rules_group = trg.id_tax_rules_group
                   AND trg.active = 1
                   LEFT JOIN
               ps_tax_rule tr ON trg.id_tax_rules_group = tr.id_tax_rules_group
                   LEFT JOIN
               ps_tax t ON tr.id_tax = t.id_tax AND t.active = 1
            WHERE
                  ps.active = 1
                    and pcl.name= '$name_category'";
        }
        if ($rows = Db :: getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql)) {
          return ObjectModel :: hydrateCollection(__CLASS__, $rows);
        }
        return array();
    }


    public static function search($name_category, $query) {

      $rvcategorysearch = new RvCategorySearch();

      if (($name_category == "tinto") || ($name_category == "blanco")) {
        $id_cat = "44";
      } elseif ($name_category == "Champaña") {
        $id_cat = "46";
      } elseif ($name_category == "Whisky") {
        $id_cat = "42";
      } elseif ($name_category == "Ron") {
        $id_cat = "39";
      } elseif ($name_category == "Gin") {
        $id_cat = "47";
      }

      $searchResults = $rvcategorysearch->getSearchProduct($id_cat, Context::getContext()->language->id, $query);


      $result = array();
      foreach ($searchResults["result"] as $key) {

          $image_array = explode("-", $key["id_image"]);

          $data = array(
            "id_product"      =>  $key["id_product"],
            "reference"       =>  $key["reference"],
            "cantidad_stock"  =>  $key["quantity"],
            "name_product"    =>  $key["name"],
            "link_rewrite"    =>  $key["link_rewrite"],
            "id_category"     =>  $key["id_category_default"],
            "name_category"   =>  $key["category_name"],
            "price"           =>  $key["price_tax_exc"],
            "impuesto"        =>  $key["rate"],
            "id_image"        =>  $image_array[1],
            "id_cover"        =>  $image_array[1],
            "id_shop"         =>  $key["id_shop"]
          );

          $result[]=$data;
      }

      return $result;



    }

}

?>

