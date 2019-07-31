<?php
/**
* 2007-2018 PrestaShop
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
* @author    PrestaShop SA    <contact@prestashop.com>
* @copyright 2007-2018 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Adapter\Translator;
use PrestaShop\PrestaShop\Adapter\LegacyContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class HomeNewTab extends Module
{
    public function __construct()
    {
        $this->name = 'homenewtab';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Prestahero';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array(
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_,
        );
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('New products in tab');
        $this->description = $this->l('Displays tab in the central column of your homepage with recently added products.');
    }

    public static function psversion()
    {
        $version = _PS_VERSION_;
        $exp = $explode = explode(".", $version);
        return $exp[1];
    }


    public function install()
    {
        $this->_clearCache('*');
        Configuration::updateValue('HOME_NEW_NBR', 8);

        return parent::install() && $this->registerHook('addproduct') && $this->registerHook('updateproduct') && $this->registerHook('deleteproduct') && $this->registerHook('categoryUpdate') && $this->registerHook('displayHomeTab') && $this->registerHook('displayHomeTabContent');
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        $errors = array();
        if (Tools::isSubmit('submithomenewtab')) {
            $nbr = Tools::getValue('HOME_NEW_NBR');
            if (!Validate::isInt($nbr) || $nbr <= 0) {
                $errors[] = $this->l('The number of products is invalid. Please enter a positive number.');
            }

            if (isset($errors) && count($errors)) {
                $output = $this->displayError(implode('<br />', $errors));
            } else {
                Configuration::updateValue('HOME_NEW_NBR', (int)$nbr);
                Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('homenewtab.tpl'));
                $output = $this->displayConfirmation($this->l('Your settings have been updated.'));
            }
        }
        return $output . $this->renderForm();
    }

    public function getProducts()
    {
        $nProducts = Configuration::get('HOME_NEW_NBR');
        $products_for_template =  Product::getNewProducts((int)$this->context->language->id, 0, (int)$nProducts);
        return $products_for_template;
    }

    public function hookAddProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookUpdateProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookDeleteProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookCategoryUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache('homenewtab.tpl', 'homenewtab');
    }

    public function hookdisplayHomeTab($params)
    {
        return $this->display(__file__, 'tab.tpl');
    }

    public function prepareBlocksProducts($block)
    {
        $blocks_for_template = array();
        $products_for_template = array();

        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(new ImageRetriever($this->context->link), $this->context->link, new PriceFormatter(), new ProductColorsRetriever(), $this->context->getTranslator());
        $products_for_template = array();

        if ($block) {
            foreach ($block as $rawProduct) {
                $products_for_template[] = $presenter->present($presentationSettings, $assembler->assembleProduct($rawProduct), $this->context->language);
            }
        }
        return $products_for_template;
    }


    public function hookdisplayHomeTabContent($params)
    {
        $this->smarty->assign($this->getAssignmentVariables());
        return $this->display(__FILE__, 'homenewtab.tpl');
    }

    public function getAssignmentVariables()
    {
        return array(
            'products' => $this->prepareBlocksProducts($this->getProducts()),
            'homeSize' => Image::getSize(ImageType::getFormattedName('home')),
            'allnewProductsLink' => Context::getContext()->link->getPageLink('new-products'),

        );
    }


    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'description' => $this->l('Module will display products only if you will have some recently added ones'),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Number of products to be displayed'),
                        'name' => 'HOME_NEW_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Set the number of products that you would like to display on homepage (default: 8).'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = (int)Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submithomenewtab';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'HOME_NEW_NBR' => Tools::getValue('HOME_NEW_NBR', (int)Configuration::get('HOME_NEW_NBR')),
        );
    }
}
