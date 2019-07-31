<?php
/**
*  @author    RV Templates
*  @copyright 2017-2018 Copyright (c) permanent, RV Templates
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL.
*  @version   1.0.0
*
* NOTICE OF LICENSE
*
* Don't use this module on several shops. The license provided by PrestaShop Addons
* for all its modules is valid only once for a single shop.
*/

if (!defined('_PS_VERSION_')) {
	exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

include_once(dirname(__FILE__).'/src/RvCookieSampleData.php');
require_once _PS_MODULE_DIR_.'rvcookie/classes/rvcookieClass.php';

class RvCookie extends Module implements WidgetInterface
{
        private $templateFile;

	public function __construct()
	{
		$this->name = 'rvcookie';
		$this->tab = 'front_office_features';
		$this->author = 'RV Templates';
		$this->version = '1.0.0';
        $this->bootstrap = true;
        $this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('RV - Cookies Law');
		$this->description = $this->l('This module display allow information about Cookies in your shop');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        
        $this->templateFile = 'module:rvcookie/views/templates/hook/rvcookie.tpl';
	}
	public function install()
	{
               $this->installDB();

        $sample_data = new RvCookieSampleData();
        $base_url = _PS_BASE_URL_.__PS_BASE_URI__;
        $sample_data->initData($base_url);
        
         return  parent::install() &&
         $this->registerHook('displayCookieFooter');
	}

	public function uninstall()
	{
		return parent::uninstall() && $this->uninstallDB();
	}

	public function installDB()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rvcookie` (
			`id_rvcookie` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_shop` int(10) unsigned DEFAULT NULL,
			PRIMARY KEY (`id_rvcookie`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
	);

		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rvcookie_lang` (
			`id_rvcookie` INT UNSIGNED NOT NULL,
			`id_lang` int(10) unsigned NOT NULL ,
			`text` text NOT NULL,
			PRIMARY KEY (`id_rvcookie`, `id_lang`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
	);

		return $return;
	}

    public function uninstallDB($drop_table = true)
    {
    	$ret = true;
    	if ($drop_table) {
    		$ret &=  Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rvcookie`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rvcookie_lang`');
    	}

    	return $ret;
    }

    public function getContent()
    {
    	$output = '';

    	if (Tools::isSubmit('savervcookie')) {
    		if (!Tools::getValue('text_'.(int)Configuration::get('PS_LANG_DEFAULT'), false)) {
    			$output = $this->displayError($this->l('Please fill out all fields.')) . $this->renderForm();
    		} else {
    			$update = $this->processSaveCmsInfo();

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

    public function processSaveCmsInfo()
    {
        $rvcookie = new rvcookieClass(Tools::getValue('id_rvcookie', 1));

        $text = array();
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $text[$lang['id_lang']] = Tools::getValue('text_'.$lang['id_lang']);
        }

        $rvcookie->text = $text;

        if (Shop::isFeatureActive() && !$rvcookie->id_shop) {
            $saved = true;
            $shop_ids = Shop::getShops();
            foreach ($shop_ids as $id_shop) {
                $rvcookie->id_shop = $id_shop;
                $saved &= $rvcookie->add();
            }
        } else {
            $saved = $rvcookie->save();
        }

        return $saved;
    }

    protected function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Cookie block'),
            ),
            'input' => array(
                'id_rvcookie' => array(
                    'type' => 'hidden',
                    'name' => 'id_rvcookie'
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

        if (Shop::isFeatureActive() && Tools::getValue('id_rvcookie') == false) {
            $fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso_theme'
            );
        }


        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'rvcookie';
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
        $helper->submit_action = 'savervcookie';

        $helper->fields_value = $this->getFormValues();

        return $helper->generateForm(array(array('form' => $fields_form)));
    }

    public function getFormValues()
    {
        $fields_value = array();
        $id_rvcookie = 1;

        foreach (Language::getLanguages(false) as $lang) {
            $rvcookie = new rvcookieClass((int)$id_rvcookie);
            $fields_value['text'][(int)$lang['id_lang']] = $rvcookie->text[(int)$lang['id_lang']];
        }

        $fields_value['id_rvcookie'] = $id_rvcookie;

        return $fields_value;
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId(''))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId(''));
    }

    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
    	$sql = 'SELECT r.`id_rvcookie`, r.`id_shop`, rl.`text`
    	FROM `'._DB_PREFIX_.'rvcookie` r
    	LEFT JOIN `'._DB_PREFIX_.'rvcookie_lang` rl ON (r.`id_rvcookie` = rl.`id_rvcookie`)
    	WHERE `id_lang` = '.(int)$this->context->language->id.' AND  `id_shop` = '.(int)$this->context->shop->id;

    	return array(
    		'rvcookie' => Db::getInstance()->getRow($sql),
    	);
    }
}

