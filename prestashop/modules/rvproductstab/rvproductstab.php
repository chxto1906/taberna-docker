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
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class Rvproductstab extends Module implements WidgetInterface
{
    private $templateFile;
    public function __construct()
    {
        $this->name = 'rvproductstab';
        $this->version = '1.0.0';
        $this->author = 'RV Templates';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array(
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_,
        );
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('RV - Products Tab');
        $this->description = $this->l('Displays Products tab as Grid or Slider');
        $this->templateFile = 'module:rvproductstab/views/templates/hook/rvproductstab.tpl';
    }

    public function install()
    {
        $this->_clearCache('*');

        return parent::install()
            && Configuration::updateValue('RVPRODUCTSTAB_SLIDER', 1)
            && Configuration::updateValue('RVPRODUCTSTAB_NEW', 1)
            && Configuration::updateValue('RVPRODUCTSTAB_FEATURED', 1)
            && Configuration::updateValue('RVPRODUCTSTAB_BEST', 1)
            && Configuration::updateValue('RVPRODUCTSTAB_SPECIAL', 0)
            && Configuration::updateValue('RVPRODUCTSTAB_NBR', 10)
            && $this->registerHook('actionProductAdd')
            && $this->registerHook('actionProductUpdate')
            && $this->registerHook('actionProductDelete')
            && $this->registerHook('categoryUpdate')
            && $this->registerHook('actionOrderStatusPostUpdate')
            && $this->registerHook('displayHome')
            && ProductSale::fillProductSales();
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall()
            && Configuration::deleteByName('RVPRODUCTSTAB_SLIDER')
            && Configuration::deleteByName('RVPRODUCTSTAB_NEW')
            && Configuration::deleteByName('RVPRODUCTSTAB_FEATURED')
            && Configuration::deleteByName('RVPRODUCTSTAB_BEST')
            && Configuration::deleteByName('RVPRODUCTSTAB_SPECIAL')
            && Configuration::deleteByName('RVPRODUCTSTAB_NBR');

    }

    public function hookActionProductAdd($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionProductUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionProductDelete($params)
    {
        $this->_clearCache('*');
    }

    public function hookCategoryUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache($this->templateFile);
    }

    public function getContent()
    {
        $output = '';
        $errors = array();
        if (Tools::isSubmit('submitRvproductstabModule')) {
            $nbr = (int)Tools::getValue('RVPRODUCTSTAB_NBR');

            if (!$nbr || $nbr <= 0 || !Validate::isInt($nbr)) {
                $errors[] = $this->l('An invalid number of products has been specified.');
            }

            if (isset($errors) && count($errors)) {
                $output = $this->displayError(implode('<br />', $errors));
            } else {
                Configuration::updateValue('RVPRODUCTSTAB_SLIDER', (int)(Tools::getValue('RVPRODUCTSTAB_SLIDER')));
                Configuration::updateValue('RVPRODUCTSTAB_NEW', (int)(Tools::getValue('RVPRODUCTSTAB_NEW')));
                Configuration::updateValue('RVPRODUCTSTAB_FEATURED', (int)(Tools::getValue('RVPRODUCTSTAB_FEATURED')));
                Configuration::updateValue('RVPRODUCTSTAB_BEST', (int)(Tools::getValue('RVPRODUCTSTAB_BEST')));
                Configuration::updateValue('RVPRODUCTSTAB_SPECIAL', (int)(Tools::getValue('RVPRODUCTSTAB_SPECIAL')));
                Configuration::updateValue('RVPRODUCTSTAB_NBR', (int)($nbr));
                $this->_clearCache('*');
                $output .= $this->displayConfirmation($this->l('Your settings have been updated.'));
            }
        }

        return $output.$this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display Products as Slider'),
                        'name' => 'RVPRODUCTSTAB_SLIDER',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Display Slider or Grid.(Note:Slider is working if "Number of product" is set more than 5)'),
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
                        'label' => $this->l('Show New Products'),
                        'name' => 'RVPRODUCTSTAB_NEW',
                        'class' => 'fixed-width-xs',
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
                        'label' => $this->l('Show Featured Products'),
                        'name' => 'RVPRODUCTSTAB_FEATURED',
                        'class' => 'fixed-width-xs',
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
                        'label' => $this->l('Show Best Seller Products'),
                        'name' => 'RVPRODUCTSTAB_BEST',
                        'class' => 'fixed-width-xs',
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
                        'label' => $this->l('Show Special Products'),
                        'name' => 'RVPRODUCTSTAB_SPECIAL',
                        'class' => 'fixed-width-xs',
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
                        'col' => 2,
                        'type' => 'text',
                        'label' => $this->l('Products to display'),
                        'name' => 'RVPRODUCTSTAB_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Define the number of products to be displayed in this block.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                ),
            ),
        );
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRvproductstabModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
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
            'RVPRODUCTSTAB_SLIDER' => Tools::getValue('RVPRODUCTSTAB_SLIDER', Configuration::get('RVPRODUCTSTAB_SLIDER')),
            'RVPRODUCTSTAB_NEW' => Tools::getValue('RVPRODUCTSTAB_NEW', Configuration::get('RVPRODUCTSTAB_NEW')),
            'RVPRODUCTSTAB_FEATURED' => Tools::getValue('RVPRODUCTSTAB_FEATURED', Configuration::get('RVPRODUCTSTAB_FEATURED')),
            'RVPRODUCTSTAB_BEST' => Tools::getValue('RVPRODUCTSTAB_BEST', Configuration::get('RVPRODUCTSTAB_BEST')),
            'RVPRODUCTSTAB_SPECIAL' => Tools::getValue('RVPRODUCTSTAB_SPECIAL', Configuration::get('RVPRODUCTSTAB_SPECIAL')),
            'RVPRODUCTSTAB_NBR' => Tools::getValue('RVPRODUCTSTAB_NBR', Configuration::get('RVPRODUCTSTAB_NBR')),
        );
    }

    public function renderWidget($hookName, array $configuration)
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

    public function getWidgetVariables($hookName, array $configuration)
    {
        $newProducts = $this->getNewProducts();
        $specialProducts = $this->getSpecialProducts();
        $bestSeller = $this->getBestSellers();
        $featureProduct = $this->getFeaturedProducts();

        if(!$newProducts) $newProducts = null;
        if(!$bestSeller) $bestSeller = null;
        if(!$specialProducts) $specialProducts = null;
        
        $rvproducttab = array();
        if(Configuration::get('RVPRODUCTSTAB_FEATURED')) {
            $rvproducttab[] = array('id' => 'featured_product', 'name' => $this->l('Featured'), 'productInfo' => $featureProduct);
        }
        if(Configuration::get('RVPRODUCTSTAB_NEW')) {
            $rvproducttab[] = array('id' => 'new_product', 'name' => $this->l('New Arrival'), 'productInfo' => $newProducts);
        }
        if(Configuration::get('RVPRODUCTSTAB_SPECIAL')) {
            $rvproducttab[] = array('id' => 'special_product', 'name' => $this->l('Specials'), 'productInfo' => $specialProducts);
        }
        if(Configuration::get('RVPRODUCTSTAB_BEST')) {
            $rvproducttab[] = array('id' => 'bestseller_product', 'name' => $this->l('Best seller'), 'productInfo' => $bestSeller);
        }

        return array(
            'rvproducttab' => $rvproducttab,
            'no_prod' => (int)Configuration::get('RVPRODUCTSTAB_NBR'),
            'slider' => (int)Configuration::get('RVPRODUCTSTAB_SLIDER'),
        );
    }

    public function getFeaturedProducts($origin=false)
    {
        $category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);


        $nb = (int)Configuration::get('RV_FEATURED_PRODUCTS_NBR');


        $result = $category->getProducts((int) $this->context->language->id, 1, ($nb ? $nb : 12), 'position');

        /* HENRY */

        if ($origin) {
            return $result;
        }

        /* HENRY */

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
        foreach ($result as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $products_for_template;
    }

    public function getNewProducts($origin=false)
    {
        if (Configuration::get('PS_CATALOG_MODE')) {
            return false;
        }

        $newProducts = false;
        $nb = Configuration::get('RVPRODUCTSTAB_NBR');
        $newProducts = Product::getNewProducts((int) $this->context->language->id, 0, ($nb ? $nb : 8));

        /* HENRY */

        if ($origin) {
            return $newProducts;
        }

        /* HENRY */

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
        if (is_array($newProducts)) {
            foreach ($newProducts as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
        }
        return $products_for_template;
    }

    public function getBestSellers($origin=false)
    {
        if (Configuration::get('PS_CATALOG_MODE')) {
            return false;
        }

        $searchProvider = new BestSalesProductSearchProvider(
            $this->context->getTranslator()
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nProducts = (int) Configuration::get('RVPRODUCTSTAB_NBR');

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1)
        ;

        $query->setSortOrder(SortOrder::random());

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        /* HENRY */

        if ($origin) {
            return $result->getProducts;
        }

        /* HENRY */

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

        foreach ($result->getProducts() as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    private function getSpecialProducts()
    {
        $nb = Configuration::get('RVPRODUCTSTAB_NBR');
        $products = Product::getPricesDrop((int)Context::getContext()->language->id, 0, ($nb ? $nb : 8));
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
            foreach ($products as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
        }
        return $products_for_template;
    }
}