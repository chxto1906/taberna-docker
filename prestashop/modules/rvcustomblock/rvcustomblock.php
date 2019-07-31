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

*  @copyright 2007-2017 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)

*  International Registered Trademark & Property of PrestaShop SA

*/



if (!defined('_PS_VERSION_')) {

    exit;

}



use PrestaShop\PrestaShop\Core\Module\WidgetInterface;



include_once(dirname(__FILE__).'/src/RvCustomSampleData.php');

require_once _PS_MODULE_DIR_.'rvcustomblock/classes/rvcustomblockClass.php';



class Rvcustomblock extends Module implements WidgetInterface

{

    private $templateFile;



    public function __construct()

    {

        $this->name = 'rvcustomblock';

        $this->version = '1.0.0';

        $this->author = 'RV Templates';

        $this->bootstrap = true;

        $this->need_instance = 0;



        parent::__construct();



        $this->displayName = $this->l('RV - Custom Block');

        $this->description = $this->l('Adds custom information blocks in your store.');



        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);



        $this->templateFile = 'module:rvcustomblock/views/templates/hook/rvcustomblock.tpl';

    }



    public function install()

    {

        $this->installDB();



        $sample_data = new RvCustomSampleData();

        $base_url = _PS_BASE_URL_.__PS_BASE_URI__;

        $sample_data->initData($base_url);



        return  parent::install() &&

            $this->registerHook('displayHome');

    }



    public function uninstall()

    {

        return parent::uninstall() && $this->uninstallDB();

    }



    public function installDB()

    {

        $return = true;

        $return &= Db::getInstance()->execute('

                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rvcustomblockinfo` (

                `id_rvcustomblockinfo` INT UNSIGNED NOT NULL AUTO_INCREMENT,

                `id_shop` int(10) unsigned DEFAULT NULL,

                PRIMARY KEY (`id_rvcustomblockinfo`)

            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'

        );



        $return &= Db::getInstance()->execute('

                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rvcustomblockinfo_lang` (

                `id_rvcustomblockinfo` INT UNSIGNED NOT NULL,

                `id_lang` int(10) unsigned NOT NULL ,

                `text` text NOT NULL,

                PRIMARY KEY (`id_rvcustomblockinfo`, `id_lang`)

            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'

        );



        return $return;

    }



    public function uninstallDB($drop_table = true)

    {

        $ret = true;

        if ($drop_table) {

            $ret &=  Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rvcustomblockinfo`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rvcustomblockinfo_lang`');

        }



        return $ret;

    }



    public function getContent()

    {

        $output = '';



        if (Tools::isSubmit('savervcustomblock')) {

            if (!Tools::getValue('text_'.(int)Configuration::get('PS_LANG_DEFAULT'), false)) {

                $output = $this->displayError($this->l('Please fill out all fields.')) . $this->renderForm();

            } else {

                $update = $this->processSaveCustomInfo();



                if (!$update) {

                    $output = '<div class="alert alert-danger conf error">'

                        .$this->l('An error occurred on saving.')

                        .'</div>';

                } else {

                    $output = $this->displayConfirmation($this->l('The settings have been updated.'));

                }



                $this->_clearCache($this->templateFile);

            }

        }



        return $output.$this->renderForm();

    }



    public function processSaveCustomInfo()

    {

        $rvcustomblockinfo = new rvcustomblockClass(Tools::getValue('id_rvcustomblockinfo', 1));



        $text = array();

        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {

            $text[$lang['id_lang']] = Tools::getValue('text_'.$lang['id_lang']);

        }



        $rvcustomblockinfo->text = $text;



        if (Shop::isFeatureActive() && !$rvcustomblockinfo->id_shop) {

            $saved = true;

            $shop_ids = Shop::getShops();

            foreach ($shop_ids as $id_shop) {

                $rvcustomblockinfo->id_shop = $id_shop;

                $saved &= $rvcustomblockinfo->add();

            }

        } else {

            $saved = $rvcustomblockinfo->save();

        }



        return $saved;

    }



    protected function renderForm()

    {

        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');



        $fields_form = array(

            'tinymce' => true,

            'legend' => array(

                'title' => $this->l('Custom block'),

            ),

            'input' => array(

                'id_rvcustomblockinfo' => array(

                    'type' => 'hidden',

                    'name' => 'id_rvcustomblockinfo'

                ),

                'content' => array(

                    'type' => 'textarea',

                    'label' => $this->l('Text block'),

                    'lang' => true,

                    'name' => 'text',

                    'cols' => 40,

                    'rows' => 10,

                    'class' => 'rte',

                    'autoload_rte' => true,

                ),

            ),

            'submit' => array(

                'title' => $this->l('Save'),

            ),

            'buttons' => array(

                array(

                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),

                    'title' => $this->l('Back to list'),

                    'icon' => 'process-icon-back'

                )

            )

        );



        if (Shop::isFeatureActive() && Tools::getValue('id_rvcustomblockinfo') == false) {

            $fields_form['input'][] = array(

                'type' => 'shop',

                'label' => $this->l('Shop association'),

                'name' => 'checkBoxShopAsso_theme'

            );

        }





        $helper = new HelperForm();

        $helper->module = $this;

        $helper->name_controller = 'rvcustomblock';

        $helper->identifier = $this->identifier;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        foreach (Language::getLanguages(false) as $lang) {

            $helper->languages[] = array(

                'id_lang' => $lang['id_lang'],

                'iso_code' => $lang['iso_code'],

                'name' => $lang['name'],

                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)

            );

        }



        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $helper->default_form_language = $default_lang;

        $helper->allow_employee_form_lang = $default_lang;

        $helper->toolbar_scroll = true;

        $helper->title = $this->displayName;

        $helper->submit_action = 'savervcustomblock';



        $helper->fields_value = $this->getFormValues();



        return $helper->generateForm(array(array('form' => $fields_form)));

    }



    public function getFormValues()

    {

        $fields_value = array();

        $id_rvcustomblockinfo = 1;



        foreach (Language::getLanguages(false) as $lang) {

            $rvcustomblockinfo = new rvcustomblockClass((int)$id_rvcustomblockinfo);

            $fields_value['text'][(int)$lang['id_lang']] = $rvcustomblockinfo->text[(int)$lang['id_lang']];

        }



        $fields_value['id_rvcustomblockinfo'] = $id_rvcustomblockinfo;



        return $fields_value;

    }



    public function renderWidget($hookName = null, array $configuration = array())

    {

        if ($this->context->controller->php_self == 'index') {

            if (!$this->isCached($this->templateFile, $this->getCacheId(''))) {

                $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

            }



            return $this->fetch($this->templateFile, $this->getCacheId(''));

        }

    }



    public function getWidgetVariables($hookName = null, array $configuration = array())

    {

        $sql = 'SELECT r.`id_rvcustomblockinfo`, r.`id_shop`, rl.`text`

            FROM `'._DB_PREFIX_.'rvcustomblockinfo` r

            LEFT JOIN `'._DB_PREFIX_.'rvcustomblockinfo_lang` rl ON (r.`id_rvcustomblockinfo` = rl.`id_rvcustomblockinfo`)

            WHERE `id_lang` = '.(int)$this->context->language->id.' AND  `id_shop` = '.(int)$this->context->shop->id;



        return array(

            'rvcustomblockinfos' => Db::getInstance()->getRow($sql),

        );

    }

}

