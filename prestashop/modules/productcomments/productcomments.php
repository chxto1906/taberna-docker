<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(_PS_MODULE_DIR_.'productcomments/classes/ProductComment.php');
require_once(_PS_MODULE_DIR_.'productcomments/classes/ProductCommentCriterion.php');

class ProductComments extends Module
{
    private $_html = '';
    private $_postErrors = array();

    private $_productCommentsCriterionTypes = array();
    private $_baseUrl;

    public function __construct()
    {
        $this->name = 'productcomments';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'RV Templates';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->secure_key = Tools::encrypt($this->name);

        $this->displayName = $this->l('RV - Product Comments');
        $this->description = $this->l('Allows users to post reviews and rate products on specific criteria.');

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        $this->createTables();

        Configuration::updateValue('PRODUCTCOMMENT_ENABLE', 1);
        Configuration::updateValue('PRODUCTCOMMENT_MODERATE', 1);
        Configuration::updateValue('PRODUCTCOMMENT_ALLOW_GUESTS', 1);
        Configuration::updateValue('PRODUCTCOMMENT_MINIMAL_TIME', 30);
        Configuration::updateValue('PRODUCTCOMMENT_PRODUCTLIST', 1);
        Configuration::updateValue('PRODUCTCOMMENT_PRODUCTLIST_COUNTER', 0);
        Configuration::updateValue('PRODUCTCOMMENT_PRODUCTLIST_ZERO', 1);
        Configuration::updateValue('PRODUCTCOMMENT_USEFULL', 1);
        Configuration::updateValue('PRODUCTCOMMENT_REPORT', 1);

        return parent::install()
        && $this->registerHook('displayHeader')
        && $this->registerHook('displayProductListReviews')
        && $this->registerHook('displayProductTab')
        && $this->registerHook('displayProductTabContent')
        && $this->registerHook('displayBeforeBodyClosingTag')
        && $this->registerHook('displayProductExtra')
        && $this->registerHook('displayProductCommentCompare');
    }

    public function uninstall()
    {
        $this->deleteTables();

        Configuration::deleteByName('PRODUCTCOMMENT_ENABLE');
        Configuration::deleteByName('PRODUCTCOMMENT_MODERATE');
        Configuration::deleteByName('PRODUCTCOMMENT_ALLOW_GUESTS');
        Configuration::deleteByName('PRODUCTCOMMENT_MINIMAL_TIME');
        Configuration::deleteByName('PRODUCTCOMMENT_PRODUCTLIST');
        Configuration::deleteByName('PRODUCTCOMMENT_PRODUCTLIST_COUNTER');
        Configuration::deleteByName('PRODUCTCOMMENT_PRODUCTLIST_ZERO');
        Configuration::deleteByName('PRODUCTCOMMENT_USEFULL');
        Configuration::deleteByName('PRODUCTCOMMENT_REPORT');

        return parent::uninstall();
    }

    protected function createTables()
    {
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment` (
                `id_product_comment` int(10) unsigned NOT NULL auto_increment,
                `id_product` int(10) unsigned NOT NULL,
                `id_customer` int(10) unsigned NOT NULL,
                `id_guest` int(10) unsigned NULL,
                `title` varchar(64) NULL,
                `content` text NOT NULL,
                `customer_name` varchar(64) NULL,
                `grade` float unsigned NOT NULL,
                `validate` tinyint(1) NOT NULL,
                `deleted` tinyint(1) NOT NULL,
                `date_add` datetime NOT NULL,
                PRIMARY KEY (`id_product_comment`),
                KEY `id_product` (`id_product`),
                KEY `id_customer` (`id_customer`),
                KEY `id_guest` (`id_guest`)
            ) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8;
        ');

        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_criterion` (
                `id_product_comment_criterion` int(10) unsigned NOT NULL auto_increment,
                `id_product_comment_criterion_type` tinyint(1) NOT NULL,
                `active` tinyint(1) NOT NULL,
                PRIMARY KEY (`id_product_comment_criterion`)
            ) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8;
        ');

        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_criterion_product` (
                `id_product` int(10) unsigned NOT NULL,
                `id_product_comment_criterion` int(10) unsigned NOT NULL,
                PRIMARY KEY(`id_product`, `id_product_comment_criterion`),
                KEY `id_product_comment_criterion` (`id_product_comment_criterion`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ');

        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_criterion_lang` (
                `id_product_comment_criterion` INT(11) UNSIGNED NOT NULL ,
                `id_lang` INT(11) UNSIGNED NOT NULL ,
                `name` VARCHAR(64) NOT NULL ,
                PRIMARY KEY ( `id_product_comment_criterion` , `id_lang` )
            ) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8;
        ');

        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_criterion_category` (
                `id_product_comment_criterion` int(10) unsigned NOT NULL,
                `id_category` int(10) unsigned NOT NULL,
                PRIMARY KEY(`id_product_comment_criterion`, `id_category`),
                KEY `id_category` (`id_category`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ');

        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_grade` (
                `id_product_comment` int(10) unsigned NOT NULL,
                `id_product_comment_criterion` int(10) unsigned NOT NULL,
                `grade` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_product_comment`, `id_product_comment_criterion`),
                KEY `id_product_comment_criterion` (`id_product_comment_criterion`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ');

        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_usefulness` (
                `id_product_comment` int(10) unsigned NOT NULL,
                `id_customer` int(10) unsigned NOT NULL,
                `usefulness` tinyint(1) unsigned NOT NULL,
                PRIMARY KEY (`id_product_comment`, `id_customer`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ');

        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_report` (
                `id_product_comment` int(10) unsigned NOT NULL,
                `id_customer` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_product_comment`, `id_customer`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ');

        $res &= Db::getInstance()->execute('
            INSERT IGNORE INTO `'._DB_PREFIX_.'product_comment_criterion` VALUES (1, 1, 1);
        ');

        $res &= Db::getInstance()->execute('
            INSERT IGNORE INTO `'._DB_PREFIX_.'product_comment_criterion_lang` (`id_product_comment_criterion`, `id_lang`, `name`)
            (
                SELECT 1, l.`id_lang`, \'Quality\'
                FROM `'._DB_PREFIX_.'lang` l
            );
        ');

        return $res;
    }

    public function deleteTables()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'product_comment`,
            `'._DB_PREFIX_.'product_comment_criterion`,
            `'._DB_PREFIX_.'product_comment_criterion_product`,
            `'._DB_PREFIX_.'product_comment_criterion_lang`,
            `'._DB_PREFIX_.'product_comment_criterion_category`,
            `'._DB_PREFIX_.'product_comment_grade`,
            `'._DB_PREFIX_.'product_comment_usefulness`,
            `'._DB_PREFIX_.'product_comment_report`');
    }

    public function getCacheId($id_product = null)
    {
        return parent::getCacheId().'|'.(int) $id_product;
    }

    protected function _postProcess()
    {

        if (Tools::isSubmit('submitModerate')) {
            Configuration::updateValue('PRODUCTCOMMENT_ENABLE', (int) Tools::getValue('PRODUCTCOMMENT_ENABLE'));
            Configuration::updateValue('PRODUCTCOMMENT_MODERATE', (int) Tools::getValue('PRODUCTCOMMENT_MODERATE'));
            Configuration::updateValue('PRODUCTCOMMENT_ALLOW_GUESTS', (int) Tools::getValue('PRODUCTCOMMENT_ALLOW_GUESTS'));
            Configuration::updateValue('PRODUCTCOMMENT_MINIMAL_TIME', (int) Tools::getValue('PRODUCTCOMMENT_MINIMAL_TIME'));
            Configuration::updateValue('PRODUCTCOMMENT_PRODUCTLIST', (int) Tools::getValue('PRODUCTCOMMENT_PRODUCTLIST'));
            Configuration::updateValue('PRODUCTCOMMENT_PRODUCTLIST_COUNTER', (int) Tools::getValue('PRODUCTCOMMENT_PRODUCTLIST_COUNTER'));
            Configuration::updateValue('PRODUCTCOMMENT_PRODUCTLIST_ZERO', (int) Tools::getValue('PRODUCTCOMMENT_PRODUCTLIST_ZERO'));
            Configuration::updateValue('PRODUCTCOMMENT_USEFULL', (int) Tools::getValue('PRODUCTCOMMENT_USEFULL'));
            Configuration::updateValue('PRODUCTCOMMENT_REPORT', (int) Tools::getValue('PRODUCTCOMMENT_REPORT'));
            $this->_html .= $this->displayConfirmation($this->l('The settings have been updated.'));
        } elseif (Tools::isSubmit('productcomments')) {
            $id_product_comment = (int) Tools::getValue('id_product_comment');
            $comment = new ProductComment($id_product_comment);
            $comment->validate();
            ProductComment::deleteReports($id_product_comment);
        } elseif (Tools::isSubmit('deleteproductcomments')) {
            $id_product_comment = (int) Tools::getValue('id_product_comment');
            $comment = new ProductComment($id_product_comment);
            $comment->delete();
        } elseif (Tools::isSubmit('submitEditCriterion')) {
            $criterion = new ProductCommentCriterion((int) Tools::getValue('id_product_comment_criterion'));
            $criterion->id_product_comment_criterion_type = Tools::getValue('id_product_comment_criterion_type');
            $criterion->active = Tools::getValue('active');

            $languages = Language::getLanguages();
            $name = array();
            foreach ($languages as $key => $value) {
                $name[$value['id_lang']] = Tools::getValue('name_'.$value['id_lang']);
            }
            $criterion->name = $name;

            $criterion->save();

            /* Clear before reinserting data */
            $criterion->deleteCategories();
            $criterion->deleteProducts();
            if ($criterion->id_product_comment_criterion_type == 2) {
                if ($categories = Tools::getValue('categoryBox')) {
                    if (count($categories)) {
                        foreach ($categories as $id_category) {
                            $criterion->addCategory((int) $id_category);
                        }
                    }
                }
            } elseif ($criterion->id_product_comment_criterion_type == 3) {
                if ($products = Tools::getValue('ids_product')) {
                    if (count($products)) {
                        foreach ($products as $product) {
                            $criterion->addProduct((int) $product);
                        }
                    }
                }
            }
            if ($criterion->save()) {
                Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminModules').'&configure='.$this->name.'&conf=4');
            } else {
                $this->_html .= $this->displayError($this->l('The criterion could not be saved'));
            }
        } elseif (Tools::isSubmit('deleteproductcommentscriterion')) {
            $ProductCommentCriterion = new ProductCommentCriterion((int) Tools::getValue('id_product_comment_criterion'));
            if ($ProductCommentCriterion->id) {
                if ($ProductCommentCriterion->delete()) {
                    $this->_html .= $this->displayConfirmation($this->l('Criterion deleted.'));
                }
            }
        } elseif (Tools::isSubmit('statusproductcommentscriterion')) {
            $criterion = new ProductCommentCriterion((int) Tools::getValue('id_product_comment_criterion'));
            if ($criterion->id) {
                $criterion->active = (int) (!$criterion->active);
                $criterion->save();
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&conf=4&module_name='.$this->name);
        } elseif ($id_product_comment = (int) Tools::getValue('approveComment')) {
            $comment = new ProductComment($id_product_comment);
            $comment->validate();
        } elseif ($id_product_comment = (int) Tools::getValue('noabuseComment')) {
            ProductComment::deleteReports($id_product_comment);
        }

        $this->_clearcache('productcomments_reviews.tpl');
    }

    public function getContent()
    {
        $this->_html = '';
        if (Tools::isSubmit('updateproductcommentscriterion')) {
            $this->_html .= $this->renderCriterionForm((int) Tools::getValue('id_product_comment_criterion'));
        } else {
            $this->_postProcess();
            $this->_html .= $this->renderConfigForm();
            $this->_html .= $this->renderModerateLists();
            $this->_html .= $this->renderCriterionList();
            $this->_html .= $this->renderCommentsList();
        }

        $this->_setBaseUrl();
        $this->_productCommentsCriterionTypes = ProductCommentCriterion::getTypes();

        $this->context->controller->addJs($this->_path.'views/js/moderate.js');

        return $this->_html;
    }

    private function _setBaseUrl()
    {
        $this->_baseUrl = 'index.php?';
        foreach (Tools::getValue() as $k => $value) {
            if (!in_array($k, array('deleteCriterion', 'editCriterion'))) {
                $this->_baseUrl .= $k.'='.$value.'&';
            }
        }
        $this->_baseUrl = rtrim($this->_baseUrl, '&');
    }

    public function renderConfigForm()
    {
        $fields_form_1 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuration'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Product Comments'),
                        'name' => 'PRODUCTCOMMENT_ENABLE',
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
                        'is_bool' => true,
                        'label' => $this->l('All reviews must be validated by an employee'),
                        'name' => 'PRODUCTCOMMENT_MODERATE',
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
                        'is_bool' => true,
                        'label' => $this->l('Allow guest reviews'),
                        'name' => 'PRODUCTCOMMENT_ALLOW_GUESTS',
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
                        'label' => $this->l('Minimum time between 2 reviews from the same user'),
                        'name' => 'PRODUCTCOMMENT_MINIMAL_TIME',
                        'class' => 'fixed-width-xs',
                        'suffix' => 'seconds',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show reviews stars in Products List'),
                        'name' => 'PRODUCTCOMMENT_PRODUCTLIST',
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
                        'is_bool' => true,
                        'label' => $this->l('Show reviews counter in Products List'),
                        'name' => 'PRODUCTCOMMENT_PRODUCTLIST_COUNTER',
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
                        'is_bool' => true,
                        'label' => $this->l('Show Zero Product reviews in Products List'),
                        'name' => 'PRODUCTCOMMENT_PRODUCTLIST_ZERO',
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
                        'is_bool' => true,
                        'label' => $this->l('Display Usefull reviews button'),
                        'name' => 'PRODUCTCOMMENT_USEFULL',
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
                        'is_bool' => true,
                        'label' => $this->l('Allow Report reviews Button'),
                        'name' => 'PRODUCTCOMMENT_REPORT',
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
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitModerate',
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->name;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitProducCommentsConfiguration';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form_1));
    }

    public function renderModerateLists()
    {
        $return = null;

        if (Configuration::get('PRODUCTCOMMENT_MODERATE')) {
            $comments = ProductComment::getByValidate(0, false);

            if (count($comments) > 0) {
                $fields_list = $this->getStandardFieldList();

                if (version_compare(_PS_VERSION_, '1.6', '<')) {
                    $return .= '<h1>'.$this->l('Reviews waiting for approval').'</h1>';
                    $actions = array('enable', 'delete');
                } else {
                    $actions = array('approve', 'delete');
                }

                $helper = new HelperList();
                $helper->shopLinkType = '';
                $helper->simple_header = true;
                $helper->actions = $actions;
                $helper->show_toolbar = false;
                $helper->module = $this;
                $helper->listTotal = count($comments);
                $helper->identifier = 'id_product_comment';
                $helper->title = $this->l('Reviews waiting for approval');
                $helper->table = $this->name;
                $helper->token = Tools::getAdminTokenLite('AdminModules');
                $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

                $return .= $helper->generateList($comments, $fields_list);
            }
        }

        $comments = ProductComment::getReportedComments();

        if (count($comments) > 0) {
            $fields_list = $this->getStandardFieldList();

            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $return .= '<h1>'.$this->l('Reported Reviews').'</h1>';
                $actions = array('enable', 'delete');
            } else {
                $actions = array('delete', 'noabuse');
            }

            $helper = new HelperList();
            $helper->shopLinkType = '';
            $helper->simple_header = true;
            $helper->actions = $actions;
            $helper->show_toolbar = false;
            $helper->module = $this;
            $helper->listTotal = count($comments);
            $helper->identifier = 'id_product_comment';
            $helper->title = $this->l('Reported Reviews');
            $helper->table = $this->name;
            $helper->token = Tools::getAdminTokenLite('AdminModules');
            $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

            $return .= $helper->generateList($comments, $fields_list);
        }

        return $return;
    }

    public function renderCriterionList()
    {
        $criterions = ProductCommentCriterion::getCriterions($this->context->language->id, false, false);

        $fields_list = array(
            'id_product_comment_criterion' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'type' => 'text',
            ),
            'type_name' => array(
                'title' => $this->l('Type'),
                'type' => 'text',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->toolbar_btn['new'] = array(
            'href' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&module_name='.$this->name.'&updateproductcommentscriterion',
            'desc' => $this->l('Add New Criterion', null, null, false),
            );
        $helper->module = $this;
        $helper->identifier = 'id_product_comment_criterion';
        $helper->title = $this->l('Review Criteria');
        $helper->table = $this->name.'criterion';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        return $helper->generateList($criterions, $fields_list);
    }

    public function renderCommentsList()
    {
        $comments = ProductComment::getByValidate(1, false);
        $moderate = Configuration::get('PRODUCTCOMMENT_MODERATE');
        if (empty($moderate)) {
            $comments = array_merge($comments, ProductComment::getByValidate(0, false));
        }

        $fields_list = $this->getStandardFieldList();

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = array('delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->listTotal = count($comments);
        $helper->identifier = 'id_product_comment';
        $helper->title = $this->l('Approved Reviews');
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        return $helper->generateList($comments, $fields_list);
    }

    public function getConfigFieldsValues()
    {
        return array(
            'PRODUCTCOMMENT_ENABLE' => Tools::getValue('PRODUCTCOMMENT_ENABLE', Configuration::get('PRODUCTCOMMENT_ENABLE')),
            'PRODUCTCOMMENT_MODERATE' => Tools::getValue('PRODUCTCOMMENT_MODERATE', Configuration::get('PRODUCTCOMMENT_MODERATE')),
            'PRODUCTCOMMENT_ALLOW_GUESTS' => Tools::getValue('PRODUCTCOMMENT_ALLOW_GUESTS', Configuration::get('PRODUCTCOMMENT_ALLOW_GUESTS')),
            'PRODUCTCOMMENT_MINIMAL_TIME' => Tools::getValue('PRODUCTCOMMENT_MINIMAL_TIME', Configuration::get('PRODUCTCOMMENT_MINIMAL_TIME')),
            'PRODUCTCOMMENT_PRODUCTLIST' => Tools::getValue('PRODUCTCOMMENT_PRODUCTLIST', Configuration::get('PRODUCTCOMMENT_PRODUCTLIST')),
            'PRODUCTCOMMENT_PRODUCTLIST_COUNTER' => Tools::getValue('PRODUCTCOMMENT_PRODUCTLIST_COUNTER', Configuration::get('PRODUCTCOMMENT_PRODUCTLIST_COUNTER')),
            'PRODUCTCOMMENT_PRODUCTLIST_ZERO' => Tools::getValue('PRODUCTCOMMENT_PRODUCTLIST_ZERO', Configuration::get('PRODUCTCOMMENT_PRODUCTLIST_ZERO')),
            'PRODUCTCOMMENT_USEFULL' => Tools::getValue('PRODUCTCOMMENT_USEFULL', Configuration::get('PRODUCTCOMMENT_USEFULL')),
            'PRODUCTCOMMENT_REPORT' => Tools::getValue('PRODUCTCOMMENT_REPORT', Configuration::get('PRODUCTCOMMENT_REPORT')),
        );
    }

    public function getCriterionFieldsValues($id = 0)
    {
        $criterion = new ProductCommentCriterion($id);

        return array(
            'name' => $criterion->name,
            'id_product_comment_criterion_type' => $criterion->id_product_comment_criterion_type,
            'active' => $criterion->active,
            'id_product_comment_criterion' => $criterion->id,
        );
    }

    public function getStandardFieldList()
    {
        return array(
            'id_product_comment' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'title' => array(
                'title' => $this->l('Review title'),
                'type' => 'text',
            ),
            'content' => array(
                'title' => $this->l('Review'),
                'type' => 'text',
            ),
            'grade' => array(
                'title' => $this->l('Rating'),
                'type' => 'text',
                'suffix' => '/5',
            ),
            'customer_name' => array(
                'title' => $this->l('Author'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Product'),
                'type' => 'text',
            ),
            'date_add' => array(
                'title' => $this->l('Time of publication'),
                'type' => 'date',
            ),
        );
    }

    public function renderCriterionForm($id_criterion = 0)
    {
        $types = ProductCommentCriterion::getTypes();
        $query = array();
        foreach ($types as $key => $value) {
            $query[] = array(
                'id' => $key,
                'label' => $value,
            );
        }

        $criterion = new ProductCommentCriterion((int) $id_criterion);
        $selected_categories = $criterion->getCategories();

        $product_table_values = Product::getSimpleProducts($this->context->language->id);
        $selected_products = $criterion->getProducts();
        foreach ($product_table_values as $key => $product) {
            if (false !== array_search($product['id_product'], $selected_products)) {
                $product_table_values[$key]['selected'] = 1;
            }
        }

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $field_category_tree = array(
                'type' => 'categories_select',
                'name' => 'categoryBox',
                'label' => $this->l('Criterion will be restricted to the following categories'),
                'category_tree' => $this->initCategoriesAssociation(null, $id_criterion),
            );
        } else {
            $field_category_tree = array(
                'type' => 'categories',
                'label' => $this->l('Criterion will be restricted to the following categories'),
                'name' => 'categoryBox',
                'desc' => $this->l('Mark the boxes of categories to which this criterion applies.'),
                'tree' => array(
                    'use_search' => false,
                    'id' => 'categoryBox',
                    'use_checkbox' => true,
                    'selected_categories' => $selected_categories,
                ),
                'values' => array(
                    'trads' => array(
                        'Root' => Category::getTopCategory(),
                        'selected' => $this->l('Selected'),
                        'Collapse All' => $this->l('Collapse All'),
                        'Expand All' => $this->l('Expand All'),
                        'Check All' => $this->l('Check All'),
                        'Uncheck All' => $this->l('Uncheck All'),
                    ),
                    'selected_cat' => $selected_categories,
                    'input_name' => 'categoryBox[]',
                    'use_radio' => false,
                    'use_search' => false,
                    'disabled_categories' => array(),
                    'top_category' => Category::getTopCategory(),
                    'use_context' => true,
                ),
            );
        }

        $fields_form_1 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add new criterion'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_product_comment_criterion',
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Criterion name'),
                        'name' => 'name',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'id_product_comment_criterion_type',
                        'label' => $this->l('Application scope of the criterion'),
                        'options' => array(
                            'query' => $query,
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                    $field_category_tree,
                    array(
                        'type' => 'products',
                        'label' => $this->l('The criterion will be restricted to the following products'),
                        'name' => 'ids_product',
                        'values' => $product_table_values,
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Active'),
                        'name' => 'active',
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
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitEditCriterion',
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->name;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitEditCriterion';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getCriterionFieldsValues($id_criterion),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form_1));
    }

    private function _checkDeleteComment()
    {
        $action = Tools::getValue('delete_action');
        if (empty($action) === false) {
            $product_comments = Tools::getValue('delete_id_product_comment');

            if (count($product_comments)) {
                if ($action == 'delete') {
                    foreach ($product_comments as $id_product_comment) {
                        if (!$id_product_comment) {
                            continue;
                        }
                        $comment = new ProductComment((int) $id_product_comment);
                        $comment->delete();
                        ProductComment::deleteGrades((int) $id_product_comment);
                    }
                }
            }
        }
    }

    public function displayApproveLink($token, $id, $name = null)
    {
        $this->smarty->assign(array(
            'href' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&module_name='.$this->name.'&approveComment='.$id,
            'action' => $this->l('Approve'),
        ));

        return $this->display(__FILE__, 'views/templates/admin/list_action_approve.tpl');
    }

    public function displayNoabuseLink($token, $id, $name = null)
    {
        $this->smarty->assign(array(
            'href' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&module_name='.$this->name.'&noabuseComment='.$id,
            'action' => $this->l('Not abusive'),
        ));

        return $this->display(__FILE__, 'views/templates/admin/list_action_noabuse.tpl');
    }

    public function hookDisplayProductListReviews($params)
    {
        if (Configuration::get('PRODUCTCOMMENT_ENABLE') && Configuration::get('PRODUCTCOMMENT_PRODUCTLIST')) {
            $id_product = (int) $params['product']['id_product'];
                $average = ProductComment::getAverageGrade($id_product);
                $this->smarty->assign(array(
                    'product' => $params['product'],
                    'averageTotal' => round($average['grade']),
                    'ratings' => ProductComment::getRatings($id_product),
                    'nbComments' => (int) ProductComment::getCommentNumber($id_product),
                    'nbCommentsCounter' => Configuration::get('PRODUCTCOMMENT_PRODUCTLIST_COUNTER'),
                    'zeroCommentDisplay' => Configuration::get('PRODUCTCOMMENT_PRODUCTLIST_ZERO'),
                ));

            return $this->display(__FILE__, 'productcomments_reviews.tpl', $this->getCacheId($id_product));
        }
    }

    public function hookDisplayProductTab($params)
    {
        if (Configuration::get('PRODUCTCOMMENT_ENABLE')) {
            return $this->display(__FILE__, '/tab.tpl');
        }
    }

    public function hookDisplayProductTabContent($params)
    {
        if (Configuration::get('PRODUCTCOMMENT_ENABLE')) {
            $id_guest = (!$id_customer = (int) $this->context->cookie->id_customer) ? (int) $this->context->cookie->id_guest : false;
            $customerComment = ProductComment::getByCustomer((int) (Tools::getValue('id_product')), (int) $this->context->cookie->id_customer, true, (int) $id_guest);
            
            $this->context->smarty->assign(array(
                'logged' => $this->context->customer->isLogged(true),
                'action_url' => '',
                'comments' => ProductComment::getByProduct((int) Tools::getValue('id_product'), 1, null, $this->context->cookie->id_customer),
                'allow_guests' => (int) Configuration::get('PRODUCTCOMMENT_ALLOW_GUESTS'),
                'too_early' => ($customerComment && (strtotime($customerComment['date_add']) + Configuration::get('PRODUCTCOMMENT_MINIMAL_TIME')) > time()),
                'secure_key' => $this->secure_key,
                'moderation_active' => (int) Configuration::get('PRODUCTCOMMENT_MODERATE'),
                'commentUsefull' => Configuration::get('PRODUCTCOMMENT_USEFULL'),
                'commentReport' => Configuration::get('PRODUCTCOMMENT_REPORT'),
            ));

            return $this->display(__FILE__, '/productcomments.tpl');
        }
    }

    public function hookDisplayProductExtra($params)
    {
        if (Configuration::get('PRODUCTCOMMENT_ENABLE')) {
            $id_guest = (!$id_customer = (int) $this->context->cookie->id_customer) ? (int) $this->context->cookie->id_guest : false;
            $customerComment = ProductComment::getByCustomer((int) (Tools::getValue('id_product')), (int) $this->context->cookie->id_customer, true, (int) $id_guest);

            $average = ProductComment::getAverageGrade((int) Tools::getValue('id_product'));

            $this->context->smarty->assign(array(
                'secure_key' => $this->secure_key,
                'logged' => $this->context->customer->isLogged(true),
                'allow_guests' => (int) Configuration::get('PRODUCTCOMMENT_ALLOW_GUESTS'),
                'action_url' => '',
                'averageTotal' => round($average['grade']),
                'ratings' => ProductComment::getRatings((int) Tools::getValue('id_product')),
                'too_early' => ($customerComment && (strtotime($customerComment['date_add']) + Configuration::get('PRODUCTCOMMENT_MINIMAL_TIME')) > time()),
                'nbComments' => (int) ProductComment::getCommentNumber((int) Tools::getValue('id_product')),
            ));
            
            return $this->display(__FILE__, '/productcomments-extra.tpl');
        }
    }

    public function hookDisplayBeforeBodyClosingTag($params)
    {
        if ($this->context->controller->php_self == 'product' && Configuration::get('PRODUCTCOMMENT_ENABLE')) {
            $id_guest = (!$id_customer = (int) $this->context->cookie->id_customer) ? (int) $this->context->cookie->id_guest : false;
            $customerComment = ProductComment::getByCustomer((int) (Tools::getValue('id_product')), (int) $this->context->cookie->id_customer, true, (int) $id_guest);

            $product = $this->context->controller->getProduct();
            $image = Product::getCover((int) Tools::getValue('id_product'));
            $cover_image = $this->context->link->getImageLink($product->link_rewrite, $image['id_image'], ImageType::getFormatedName('home'));

            $this->context->smarty->assign(array(
                'logged' => $this->context->customer->isLogged(true),
                'action_url' => '',
                'comment_product' => $product,
                'comments' => ProductComment::getByProduct((int) Tools::getValue('id_product'), 1, null, $this->context->cookie->id_customer),
                'criterions' => ProductCommentCriterion::getByProduct((int) Tools::getValue('id_product'), $this->context->language->id),
                'allow_guests' => (int) Configuration::get('PRODUCTCOMMENT_ALLOW_GUESTS'),
                'id_product_comment_form' => (int) Tools::getValue('id_product'),
                'secure_key' => $this->secure_key,
                'productcomment_cover_image' => $cover_image,
            ));

            return $this->display(__FILE__, '/productcomments-modal.tpl');
        }
    }

    public function hookHeader()
    {
        if (Configuration::get('PRODUCTCOMMENT_ENABLE')) {
            $this->context->controller->registerJavascript('jquery-counter', 'modules/'.$this->name.'/views/js/jquery.textareaCounter.plugin.js', ['position' => 'bottom', 'priority' => 150]);
            $this->context->controller->registerJavascript('jquery-compare', 'modules/'.$this->name.'/views/js/productcomments.js', ['position' => 'bottom', 'priority' => 150]);
            $this->context->controller->registerJavascript('jquery-rating', 'modules/'.$this->name.'/views/js/jquery.rating.pack.js', ['position' => 'bottom', 'priority' => 150]);

            if ($this->context->controller->php_self == 'compare') {
                $this->context->controller->registerJavascript('jquery-comparison', 'modules/'.$this->name.'/views/js/products-comparison.js', ['position' => 'bottom', 'priority' => 150]);
            }

            Media::addJsDef(
                array(
                    'productcomments_controller_url' => $this->context->link->getModuleLink('productcomments'),
                    'productcomments_url_rewrite' => Configuration::get('PS_REWRITING_SETTINGS', 0),
                    'secure_key' => $this->secure_key,
                    'productcomment_added' => $this->l('Thanks for your Review. Your comment has been added!'),
                    'productcomment_added_moderation' => $this->l('Thanks for your Review. It will be available once approved by a moderator.'),
                    'moderation_active' => (int) Configuration::get('PRODUCTCOMMENT_MODERATE'),
                    'disable_comment' => $this->l('Criterion is not exists for this product.'),
                )
            );
        }
    }

    public function hookDisplayProductCommentCompare($params)
    {
        if (Configuration::get('PRODUCTCOMMENT_ENABLE')) {
            $list_grades = array();
            $list_product_grades = array();
            $list_product_average = array();
            $list_product_comment = array();

            foreach ($params['list_product'] as $id_product) {
                $id_product = (int) $id_product;
                $grades = ProductComment::getAveragesByProduct($id_product, $this->context->language->id);
                $criterions = ProductCommentCriterion::getByProduct($id_product, $this->context->language->id);
                $grade_total = 0;
                if (count($grades) > 0) {
                    foreach ($criterions as $criterion) {
                        if (isset($grades[$criterion['id_product_comment_criterion']])) {
                            $list_product_grades[$criterion['id_product_comment_criterion']][$id_product] = $grades[$criterion['id_product_comment_criterion']];
                            $grade_total += (float) ($grades[$criterion['id_product_comment_criterion']]);
                        } else {
                            $list_product_grades[$criterion['id_product_comment_criterion']][$id_product] = 0;
                        }

                        if (!array_key_exists($criterion['id_product_comment_criterion'], $list_grades)) {
                            $list_grades[$criterion['id_product_comment_criterion']] = $criterion['name'];
                        }
                    }

                    $list_product_average[$id_product] = $grade_total / count($criterions);
                    $list_product_comment[$id_product] = ProductComment::getByProduct($id_product, 0, 3);
                }
            }

            if (count($list_grades) < 1) {
                return false;
            }

            $this->context->smarty->assign(array(
                'grades' => $list_grades,
                'product_grades' => $list_product_grades,
                'list_ids_product' => $params['list_product'],
                'list_product_average' => $list_product_average,
                'product_comments' => $list_product_comment,
            ));

            return $this->display(__FILE__, '/products-comparison.tpl');
        }
    }

    public function initCategoriesAssociation($id_root = null, $id_criterion = 0)
    {
        if (is_null($id_root)) {
            $id_root = Configuration::get('PS_ROOT_CATEGORY');
        }
        $id_shop = (int) Tools::getValue('id_shop');
        $shop = new Shop($id_shop);
        if ($id_criterion == 0) {
            $selected_cat = array();
        } else {
            $pdc_object = new ProductCommentCriterion($id_criterion);
            $selected_cat = $pdc_object->getCategories();
        }

        if (Shop::getContext() == Shop::CONTEXT_SHOP && Tools::isSubmit('id_shop')) {
            $root_category = new Category($shop->id_category);
        } else {
            $root_category = new Category($id_root);
        }
        $root_category = array('id_category' => $root_category->id, 'name' => $root_category->name[$this->context->language->id]);

        $helper = new Helper();

        return $helper->renderCategoryTree($root_category, $selected_cat, 'categoryBox', false, true);
    }
}
