<?php
/**
* Cash On Delivery With Fee
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2017 idnovate
*  @license   See above
*/

class CodfeeConfiguration extends ObjectModel
{
    public $id_codfee_configuration;
    public $name;
    public $type;
    public $amount_calc;
    public $fix;
    public $percentage;
    public $min;
    public $max;
    public $order_min;
    public $order_max;
    public $min_weight;
    public $max_weight;
    public $amount_free;
    public $groups;
    public $carriers;
    public $countries;
    public $zones;
    public $categories;
    public $manufacturers;
    public $suppliers;
    public $initial_status;
    public $show_conf_page;
    public $free_on_freeshipping;
    public $hide_first_order;
    public $only_stock;
    public $round;
    public $show_productpage;
    public $active = false;
    public $payment_text;
    public $priority;
    public $position;
    public $payment_size;
    public $id_shop;
    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'codfee_configuration',
        'primary' => 'id_codfee_configuration',
        'multilang' => true,
        'fields' => array(
            'name' =>                   array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 100),
            'type' =>                   array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'amount_calc' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'fix' =>                    array('type' => self::TYPE_FLOAT),
            'percentage' =>             array('type' => self::TYPE_FLOAT),
            'min' =>                    array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'max' =>                    array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'order_min' =>              array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'order_max' =>              array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'amount_free' =>            array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'min_weight' =>             array('type' => self::TYPE_FLOAT, 'copy_post' => false),
            'max_weight' =>             array('type' => self::TYPE_FLOAT, 'copy_post' => false),
            'groups' =>                 array('type' => self::TYPE_STRING, 'size' => 250, 'required' => true),
            'carriers' =>               array('type' => self::TYPE_STRING, 'size' => 250, 'required' => true),
            'countries' =>              array('type' => self::TYPE_STRING, 'size' => 1000, 'required' => true),
            'zones' =>                  array('type' => self::TYPE_STRING, 'size' => 250, 'required' => true),
            'categories' =>             array('type' => self::TYPE_STRING, 'size' => 2000, 'required' => true),
            'manufacturers' =>          array('type' => self::TYPE_STRING, 'size' => 250, 'required' => true),
            'suppliers' =>              array('type' => self::TYPE_STRING, 'size' => 250, 'required' => true),
            'initial_status' =>         array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'show_conf_page' =>         array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'free_on_freeshipping' =>   array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'hide_first_order' =>       array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'only_stock' =>             array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'round' =>                  array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'show_productpage' =>       array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'active' =>                 array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'payment_text' =>           array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'priority' =>               array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'position' =>               array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'payment_size' =>           array('type' => self::TYPE_STRING, 'size' => 10),
            'id_shop' =>                array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'date_add' =>               array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false),
            'date_upd' =>               array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false),
        ),
    );

    public function __construct($id = null)
    {
        $this->image_dir = _PS_TMP_IMG_DIR_;
        parent::__construct($id);
    }

    public function add($autodate = true, $null_values = true)
    {
        $this->id_shop = ($this->id_shop) ? $this->id_shop : Context::getContext()->shop->id;
        $success = parent::add($autodate, $null_values);
        return $success;
    }

    public function toggleStatus()
    {
        parent::toggleStatus();
        return Db::getInstance()->execute('
        UPDATE `'._DB_PREFIX_.bqSQL($this->def['table']).'`
        SET `date_upd` = NOW()
        WHERE `'.bqSQL($this->def['primary']).'` = '.(int)$this->id);
    }

    public function delete()
    {
        if (parent::delete()) {
            return $this->deleteImage();
        }
    }

    /*
     * @return Array() with the selected fee data | false if no match conditions fee
     */
    public function getFeeConfiguration($id_shop, $id_lang, array $customer_groups, $carrier = false, $country, $zone, $products, array $manufacturers, array $suppliers, $order_total = 0, $all = false)
    {
        $fee_confs = Db::getInstance()->executeS(
            'SELECT c.*, cl.`payment_text`
            FROM `'._DB_PREFIX_.'codfee_configuration` c
            LEFT JOIN `'._DB_PREFIX_.'codfee_configuration_lang` cl ON (c.`id_codfee_configuration` = cl.`id_codfee_configuration` AND cl.`id_lang` = '.(int)$id_lang.')
            WHERE c.`id_shop` = '.(int)$id_shop.'
            AND c.`active` = 1
            ORDER BY `position` ASC;'
        );
        $confs_ok = array();
        $context = Context::getContext();
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $currency = new Currency($context->cart->id_currency);
            $conv_rate = (float)$currency->conversion_rate;
        } else {
            $conv_rate = (float)$context->currency->conversion_rate;
        }
        $cart = new Cart((int)$context->cart->id);
        foreach ($fee_confs as $key => $conf) {
            if ($cart->id > 0) {
                $total_weight = $cart->getTotalWeight();
            } else {
                $total_weight = -1;
            }
            if (($conf['max_weight'] > 0 && $total_weight < $conf['max_weight']) || ($conf['max_weight'] == 0)) {
                if (($conf['min_weight'] > 0 && $conf['min_weight'] <= $total_weight) || ($conf['min_weight'] == 0)) {
                } else {
                    continue;
                }
            } else {
                continue;
            }
            $order_max = $conf['order_max'] * (float)$conv_rate;
            $order_min = $conf['order_min'] * (float)$conv_rate;
            if (($order_max > 0 && $order_total < $order_max) || ($order_max == 0)) {
                if (($order_min > 0 && $order_min <= $order_total) || ($order_min == 0)) {
                    if ($conf['groups'] == 'all' && ($conf['carriers'] == 'all' || $carrier === false) && $conf['countries'] == 'all' && $conf['zones'] == 'all' && $conf['categories'] == 'all' && $conf['manufacturers'] == 'all' && $conf['suppliers'] == 'all') {
                        if ($all) {
                            $confs_ok[] = $conf;
                            continue;
                        } else {
                            return $conf;
                        }
                    }
                    $filter_groups = true;
                    if ($conf['groups'] !== 'all') {
                        $groups_array = explode(';', $conf['groups']);
                        foreach ($customer_groups as $group) {
                            if (!in_array($group, $groups_array)) {
                                $filter_groups = false;
                            } else {
                                $filter_groups = true;
                                break;
                            }
                        }
                        if (!$filter_groups) {
                            continue;
                        }
                    }
                    $filter_carriers = true;
                    if ($conf['carriers'] !== 'all') {
                        $carriers_array = explode(';', $conf['carriers']);
                        if (!in_array($carrier, $carriers_array)) {
                            if ($carrier === false || $carrier === null) {
                                $filter_carriers = true;
                            } else {
                                $filter_carriers = false;
                                continue;
                            }
                        }
                    }
                    $filter_countries = true;
                    if ($conf['countries'] !== 'all') {
                        $countries_array = explode(';', $conf['countries']);
                        if (!in_array($country->id, $countries_array)) {
                            $filter_countries = false;
                            continue;
                        }
                    }
                    $filter_zones = true;
                    if ($conf['zones'] !== 'all') {
                        $zones_array = explode(';', $conf['zones']);
                        if (!in_array($zone, $zones_array)) {
                            $filter_zones = false;
                            continue;
                        }
                    }
                    $filter_categories = true;
                    if ($conf['categories'] !== 'all') {
                        $categories_array = explode(';', $conf['categories']);
                        foreach ($products as $product) {
                            $categories = Product::getProductCategories($product['id_product']);
                            foreach ($categories as $category) {
                                if (!in_array($category, $categories_array)) {
                                    $filter_categories = false;
                                } else {
                                    $filter_categories = true;
                                    break;
                                }
                            }
                            if (!$filter_categories) {
                                break;
                            }
                        }
                    } else {
                        $filter_categories = true;
                    }
                    $filter_manufacturers = true;
                    if ($conf['manufacturers'] !== 'all') {
                        $manufacturers_array = explode(';', $conf['manufacturers']);
                        foreach ($manufacturers as $manufacturer) {
                            if (!in_array($manufacturer, $manufacturers_array)) {
                                $filter_manufacturers = false;
                                break;
                            }
                        }
                        if (!$filter_manufacturers) {
                            continue;
                        }
                    } else {
                        $filter_manufacturers = true;
                    }
                    $filter_suppliers = true;
                    if ($conf['suppliers'] !== 'all') {
                        $suppliers_array = explode(';', $conf['suppliers']);
                        foreach ($suppliers as $supplier) {
                            if (!in_array($supplier, $suppliers_array)) {
                                $filter_suppliers = false;
                                break;
                            }
                        }
                        if (!$filter_suppliers) {
                            continue;
                        }
                    } else {
                        $filter_suppliers = true;
                    }
                    if ($filter_groups && $filter_carriers && $filter_countries && $filter_zones && $filter_categories && $filter_manufacturers && $filter_suppliers) {
                        if ($all) {
                            $confs_ok[] = $conf;
                            continue;
                        } else {
                            return $conf;
                        }
                    }
                }
            }
        }
        return $confs_ok;
    }
}
