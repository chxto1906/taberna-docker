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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class RvDealoftheDay extends Module implements WidgetInterface
{
    private $templateFile;
    public function __construct()
    {
        $this->name = 'rvdealoftheday';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = '';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('RV - Special Deals of Products block');
        $this->description = $this->l('Adds a block displaying your current discounted products.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:rvdealoftheday/views/templates/hook/rvdealoftheday.tpl';
    }

    public function install()
    {
        $languages = $this->context->language->getLanguages();
        $blockTitle = array();

        foreach ($languages as $lang) {
            $blockTitle[$lang['id_lang']] = 'Deal of the Day';
        }

        $success = (parent::install()
            && $this->registerHook('header')
            && $this->registerHook('addproduct')
            && $this->registerHook('updateproduct')
            && $this->registerHook('deleteproduct')
            && Configuration::updateValue("RV_DEALSOFDAY_TITLE", $blockTitle)
            && Configuration::updateValue('RV_DEALSOFDAY_NBR', 6)
            && Configuration::updateValue('RV_DEALSOFDAY_IMAGES', 1)
            && $this->registerHook('displayHome')
        );

        $this->_clearCache('*');

        return $success;
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
        $blockTitle = array();
        $languages = $this->context->language->getLanguages();

        if (Tools::isSubmit('submitRvDealoftheDay')) {
            $nbr = (int)Tools::getValue('RV_DEALSOFDAY_NBR');

            foreach ($languages as $lang) {
                $blockTitle[$lang['id_lang']] = Tools::getValue('RV_DEALSOFDAY_TITLE_'.$lang['id_lang']);
            }

            if (!$nbr || $nbr <= 0 || !Validate::isInt($nbr)) {
                $errors[] = $this->l('An invalid number of products has been specified.');
            }

            if (isset($errors) && count($errors)) {
                $output = $this->displayError(implode('<br />', $errors));
            } else {
                Configuration::updateValue('RV_DEALSOFDAY_TITLE', $blockTitle);
                Configuration::updateValue('RV_DEALSOFDAY_NBR', (int)($nbr));
                Configuration::updateValue('RV_DEALSOFDAY_IMAGES', (int)(Tools::getValue('RV_DEALSOFDAY_IMAGES')));
                $this->_clearCache('*');
                $output = $this->displayConfirmation($this->l('Your settings have been updated.'));
            }
        }
        return $output.$this->renderForm();
    }

    public function hookHeader($params)
    {
        $this->context->controller->registerJavascript('modules-countdown', 'modules/'.$this->name.'/views/js/countdown.js', ['position' => 'bottom', 'priority' => 150]);
        $this->context->controller->registerJavascript('modules-rvdealoftheday', 'modules/'.$this->name.'/views/js/rvdealoftheday.js', ['position' => 'bottom', 'priority' => 150]);
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

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Title'),
                        'name' => 'RV_DEALSOFDAY_TITLE',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Products to display'),
                        'name' => 'RV_DEALSOFDAY_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Define the number of products to be displayed in this block.'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display Additional Images'),
                        'name' => 'RV_DEALSOFDAY_IMAGES',
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
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRvDealoftheDay';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $languages = $this->context->language->getLanguages();

        $result =  array(
            'RV_DEALSOFDAY_NBR' => Tools::getValue('RV_DEALSOFDAY_NBR', Configuration::get('RV_DEALSOFDAY_NBR')),
            'RV_DEALSOFDAY_IMAGES' => Tools::getValue('RV_DEALSOFDAY_IMAGES', Configuration::get('RV_DEALSOFDAY_IMAGES')),
        );

        foreach ($languages as $lang) {
            $result['RV_DEALSOFDAY_TITLE'][$lang['id_lang']] = Configuration::get('RV_DEALSOFDAY_TITLE', $lang['id_lang']);
        }

        return $result;
    }

    protected function getCacheId($name = null)
    {
        if ($name === null) {
            $name = 'rvdealoftheday';
        }
        return parent::getCacheId($name.'|'.date('Ymd'));
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache($this->templateFile);
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if ($this->context->controller->php_self == 'index') {
            if (!$this->isCached($this->templateFile, $this->getCacheId(''))) {
                $variables = $this->getWidgetVariables($hookName, $configuration);
                if (empty($variables)) {
                    return false;
                }
                $this->smarty->assign($variables);
            }
            return $this->fetch($this->templateFile, $this->getCacheId(''));
        }
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $id_lang = (int)Context::getContext()->language->id;
        $products = $this->getSpecialProducts();
        if (!empty($products)) {
            return array(
                'specials' => $products,
                'no_prod' => (int)Configuration::get('RV_DEALSOFDAY_NBR'),
                'addImages' => (int)Configuration::get('RV_DEALSOFDAY_IMAGES'),
                'RV_DEALSOFDAY_TITLE' => Configuration::get('RV_DEALSOFDAY_TITLE', $id_lang),
            );
        }
        return false;
    }

    private function getSpecialProducts()
    {
        $nbProducts = Product::getPricesDrop((int)Context::getContext()->language->id, null, null, true);
        $products = Product::getPricesDrop((int)Context::getContext()->language->id, 0, $nbProducts, false, 'date_upd', 'asc');
        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );
        $products_for_template = array();
        if (is_array($products)) {
            $timer_products_for_template = null;
            foreach ($products as $rawProduct) {
                if (isset($rawProduct['specific_prices']['to']) && $rawProduct['specific_prices']['to'] != '0000-00-00 00:00:00') {
                    $timer_products_for_template[] = $presenter->present(
                        $presentationSettings,
                        $assembler->assembleProduct($rawProduct),
                        $this->context->language
                    );
                } else {
                    $discount_products_for_template[] = $presenter->present(
                        $presentationSettings,
                        $assembler->assembleProduct($rawProduct),
                        $this->context->language
                    );
                }
            }

            if (count($timer_products_for_template)) {
                $products_for_template = $timer_products_for_template;
            } else {
                $products_for_template = $discount_products_for_template;
            }
        }
        return $products_for_template;
    }
}
