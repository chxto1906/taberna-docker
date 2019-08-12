<?php
/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class RvCategorySearch extends Module implements WidgetInterface
{
    private $templateFile;
    private $html;

    public function __construct()
    {
        $this->name = 'rvcategorysearch';
        $this->tab = 'search_filter';
        $this->version = '1.0.0';
        $this->author = 'RV Templates';
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('RV - Ajax Category Search');
        $this->description = $this->l('Adds Product Search function to store.');

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:rvcategorysearch/views/templates/hook/rvcategorysearch.tpl';
    }

    public function install()
    {
        Configuration::updateValue('RVCATEGORYSEARCH_CATEGORYLIST', 1);
        Configuration::updateValue('RVCATEGORYSEARCH_IMAGE', 1);
        Configuration::updateValue('RVCATEGORYSEARCH_CATEGORYNAME', 1);
        Configuration::updateValue('RVCATEGORYSEARCH_PRICE', 1);
        Configuration::updateValue('RVCATEGORYSEARCH_MAX_ITEM', '10');

        return parent::install()
            && $this->registerHook('displayTop')
            && $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('RVCATEGORYSEARCH_CATEGORYLIST');
        Configuration::deleteByName('RVCATEGORYSEARCH_IMAGE');
        Configuration::deleteByName('RVCATEGORYSEARCH_CATEGORYNAME');
        Configuration::deleteByName('RVCATEGORYSEARCH_PRICE');
        Configuration::deleteByName('RVCATEGORYSEARCH_MAX_ITEM');

        return parent::uninstall();
    }

    public function getContent()
    {
        return $this->postProcess().$this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRvCategorySearch';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Category Dropdown'),
                        'name' => 'RVCATEGORYSEARCH_CATEGORYLIST',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display Image in Search Result'),
                        'name' => 'RVCATEGORYSEARCH_IMAGE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display Category Name in Search Result'),
                        'name' => 'RVCATEGORYSEARCH_CATEGORYNAME',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display Price in Search Result'),
                        'name' => 'RVCATEGORYSEARCH_PRICE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Products to Display'),
                        'validation' => 'isUnsignedId',
                        'name' => 'RVCATEGORYSEARCH_MAX_ITEM',
                        'required' => true,
                        'cast' => 'intval',
                        'desc' => $this->l('Set the maximum number of products to display in search result.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        $data = array(
            'RVCATEGORYSEARCH_CATEGORYLIST' => Tools::getValue('RVCATEGORYSEARCH_CATEGORYLIST', Configuration::get('RVCATEGORYSEARCH_CATEGORYLIST')),
            'RVCATEGORYSEARCH_IMAGE' => Tools::getValue('RVCATEGORYSEARCH_IMAGE', Configuration::get('RVCATEGORYSEARCH_IMAGE')),
            'RVCATEGORYSEARCH_CATEGORYNAME' => Tools::getValue('RVCATEGORYSEARCH_CATEGORYNAME', Configuration::get('RVCATEGORYSEARCH_CATEGORYNAME')),
            'RVCATEGORYSEARCH_PRICE' => Tools::getValue('RVCATEGORYSEARCH_PRICE', Configuration::get('RVCATEGORYSEARCH_PRICE')),
            'RVCATEGORYSEARCH_MAX_ITEM' => Tools::getValue('RVCATEGORYSEARCH_MAX_ITEM', Configuration::get('RVCATEGORYSEARCH_MAX_ITEM')),
        );
        return $data;
    }

    protected function postProcess()
    {
        if (((bool)Tools::isSubmit('submitRvCategorySearch')) == true) {
            Configuration::updateValue('RVCATEGORYSEARCH_CATEGORYLIST', (int)(Tools::getValue('RVCATEGORYSEARCH_CATEGORYLIST')));
            Configuration::updateValue('RVCATEGORYSEARCH_IMAGE', (int)(Tools::getValue('RVCATEGORYSEARCH_IMAGE')));
            Configuration::updateValue('RVCATEGORYSEARCH_CATEGORYNAME', (int)(Tools::getValue('RVCATEGORYSEARCH_CATEGORYNAME')));
            Configuration::updateValue('RVCATEGORYSEARCH_PRICE', (int)(Tools::getValue('RVCATEGORYSEARCH_PRICE')));
            Configuration::updateValue('RVCATEGORYSEARCH_MAX_ITEM', (int)(Tools::getValue('RVCATEGORYSEARCH_MAX_ITEM')));
            $this->_clearCache($this->templateFile);
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }
        return '';
    }

    public function hookDisplayHeader()
    {
        if (Configuration::get('PS_SEARCH_AJAX')) {
            $this->context->controller->addJqueryPlugin('autocomplete');
        }

        $this->context->controller->registerJavascript('module-search', 'modules/'.$this->name.'/views/js/rvcategorysearch.js', ['position' => 'bottom', 'priority' => 150]);

        if (Configuration::get('PS_SEARCH_AJAX')) {
            Media::addJsDef(
                array(
                    'searchUrl' => $this->context->link->getPageLink('search', Tools::usingSecureMode()),
                    'limitCharacter' => $this->l('Enter at least 3 Character.'),
                )
            );
        }
    }

    public function renderWidget($hookName, array $configuration)
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId(''))) {
            $variables = $this->getWidgetVariables($hookName, $configuration);
            if (empty($variables)) {
                return false;
            }
            $this->smarty->assign($variables);
        }
        return $this->fetch($this->templateFile, $this->getCacheId(''));
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        return [
            'all_category' => $this->getCategoryOption(),
            'categorysearch_type' => 'top',
            'search_query' => (string)Tools::getValue('search_query'),
            'path_ssl' => _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name,
            'base_ssl' => $this->context->link->getBaseLink().'modules/'.$this->name,
            'search_controller_url' => $this->context->link->getPageLink('search'),
            'searchCategoryList' => Configuration::get('RVCATEGORYSEARCH_CATEGORYLIST'),
        ];
    }

    public function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id)) {
            return;
        }
        if ($recursive) {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
            $spacer = '';
            if ($category->level_depth > 0) {
                $spacer = str_repeat('&nbsp;', 2 * ((int)$category->level_depth - 1));
            }
        }
        $shop = (object)Shop::getShop((int)$category->getShopID());
        if ($category->id != Configuration::get('PS_ROOT_CATEGORY')) {
            $html .= '<option value="'.(int)$category->id.'">'.$spacer.$category->name.'</option>';
        }
        if (isset($children) && count($children)) {
            foreach ($children as $child) {
                $html .= $this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'], $recursive);
            }
        }
        return $html;
    }

    public function getSearchProduct($id_cat, $id_lang, $expr, $page_number = 1, $page_size = 1, $order_by = 'position', $order_way = 'asc', $ajax = false, $use_cookie = true, Context $context = null, $id_manufacturer = null)
    {
        if ($id_cat == 'all') {
            $id_cat = 0;
        }

        if (!$context) {
            $context = Context::getContext();
        }
        $db = Db::getInstance(_PS_USE_SQL_SLAVE_);

        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($page_size < 1) {
            $page_size = 1;
        }

        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            return false;
        }

        $intersect_array = array();
        $score_array = array();
        $words = explode(' ', Search::sanitize($expr, $id_lang, false, $context->language->iso_code));

        foreach ($words as $key => $word) {
            if (!empty($word) && Tools::strlen($word) >= (int)Configuration::get('PS_SEARCH_MINWORDLEN')) {
                $word = str_replace(array('%', '_'), array('\\%', '\\_'), $word);
                $start_search = Configuration::get('PS_SEARCH_START') ? '%': '';
                $end_search = Configuration::get('PS_SEARCH_END') ? '': '%';

                $intersect_array[] = 'SELECT si.id_product
                    FROM '._DB_PREFIX_.'search_word sw
                    LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
                    WHERE sw.id_lang = '.(int)$id_lang.'
                    AND sw.id_shop = '.$context->shop->id.'
                    AND sw.word LIKE
                    '.($word[0] == '-'
                        ? ' \''.$start_search.pSQL(Tools::substr($word, 1, PS_SEARCH_MAX_WORD_LENGTH)).$end_search.'\''
                        : ' \''.$start_search.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).$end_search.'\''
                    );

                if ($word[0] != '-') {
                    $score_array[] = 'sw.word LIKE \''.$start_search.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).$end_search.'\'';
                }
            } else {
                unset($words[$key]);
            }
        }

        if (!count($words)) {
            return ($ajax ? array() : array('total' => 0, 'result' => array()));
        }

        $score = '';
        if (is_array($score_array) && !empty($score_array)) {
            $score = ',(
                SELECT SUM(weight)
                FROM '._DB_PREFIX_.'search_word sw
                LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
                WHERE sw.id_lang = '.(int)$id_lang.'
                AND sw.id_shop = '.$context->shop->id.'
                AND si.id_product = p.id_product
                AND ('.implode(' OR ', $score_array).')
            ) position';
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = 'AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
        }

        $results = $db->executeS('
            SELECT cp.`id_product`
            FROM `'._DB_PREFIX_.'category_product` cp
            '.(Group::isFeatureActive() ? 'INNER JOIN `'._DB_PREFIX_.'category_group` cg ON cp.`id_category` = cg.`id_category`' : '').'
            INNER JOIN `'._DB_PREFIX_.'category` c ON cp.`id_category` = c.`id_category`
            INNER JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
            '.Shop::addSqlAssociation('product', 'p', false).'
            WHERE c.`active` = 1
            AND product_shop.`active` = 1
            AND product_shop.`active` = 1
            '.($id_cat ? 'AND c.`id_category` = '.$id_cat.'':'').'
            AND product_shop.`visibility` IN ("both", "search")
            AND product_shop.indexed = 1
            '.$sql_groups, true, false);

        $eligible_products = array();
        foreach ($results as $row) {
            $eligible_products[] = $row['id_product'];
        }
        foreach ($intersect_array as $query) {
            $eligible_products2 = array();
            foreach ($db->executeS($query, true, false) as $row) {
                $eligible_products2[] = $row['id_product'];
            }

            $eligible_products = array_intersect($eligible_products, $eligible_products2);
            if (!count($eligible_products)) {
                return ($ajax ? array() : array('total' => 0, 'result' => array()));
            }
        }

        $eligible_products = array_unique($eligible_products);

        $product_pool = '';
        foreach ($eligible_products as $id_product) {
            if ($id_product) {
                $product_pool .= (int)$id_product.',';
            }
        }
        if (empty($product_pool)) {
            return ($ajax ? array() : array('total' => 0, 'result' => array()));
        }
        $product_pool = ((strpos($product_pool, ',') === false) ? (' = '.(int)$product_pool.' ') : (' IN ('.rtrim($product_pool, ',').') '));

        if ($ajax) {
            $sql = 'SELECT DISTINCT p.id_product, pl.name pname, cl.name cname,
                cl.link_rewrite crewrite, pl.link_rewrite prewrite '.$score.'
                FROM '._DB_PREFIX_.'product p
                INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                    p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
                )
                '.Shop::addSqlAssociation('product', 'p').'
                INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (
                    product_shop.`id_category_default` = cl.`id_category`
                    AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
                )
                WHERE p.`id_product` '.$product_pool.'
                ORDER BY position DESC LIMIT 100';
            return $db->executeS($sql, true, false);
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]).'.`'.pSQL($order_by[1]).'`';
        }
        $alias = '';
        if ($order_by == 'price') {
            $alias = 'product_shop.';
        } elseif (in_array($order_by, array('date_upd', 'date_add'))) {
            $alias = 'p.';
        }

        $cond_manufacturer = '';

        if ($id_manufacturer) {
            $cond_manufacturer = ' AND m.`id_manufacturer` = '.$id_manufacturer;
        }


        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity,
            pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`, pl.`description`,
            image_shop.`id_image` id_image, il.`legend`, m.`name` manufacturer_name '.$score.',
            DATEDIFF(
                p.`date_add`,
                DATE_SUB(
                    "'.date('Y-m-d').' 00:00:00",
                    INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
                )
            ) > 0 new'.(Combination::isFeatureActive() ? ', product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.`id_product_attribute`,0) id_product_attribute' : '').'
            FROM '._DB_PREFIX_.'product p
            '.Shop::addSqlAssociation('product', 'p').'
            INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                p.`id_product` = pl.`id_product`
                AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
            )
            '.(Combination::isFeatureActive() ? 'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
                ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$context->shop->id.')':'').'
            '.Product::sqlStock('p', 0).'
            LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
            LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
            ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$context->shop->id.')
            LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
            WHERE p.`id_product` '.$product_pool.$cond_manufacturer.'
            GROUP BY product_shop.id_product
            '.($order_by ? 'ORDER BY  '.$alias.$order_by : '').($order_way ? ' '.$order_way : '').'
            LIMIT 1000';

        $result = $db->executeS($sql, true, false);

        $sql = 'SELECT COUNT(*)
        FROM '._DB_PREFIX_.'product p
        '.Shop::addSqlAssociation('product', 'p').'
        INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
        )
        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
        WHERE p.`id_product` '.$product_pool;
        $total = $db->getValue($sql, false);

        if (!$result) {
            $result_properties = false;
        } else {
            $result_properties = Product::getProductsProperties((int)$id_lang, $result);
        }

        return array('total' => $total,'result' => $result_properties);
    }
}
