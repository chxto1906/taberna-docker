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

class RvHeaderContact extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'rvheadercontact';
        $this->version = '1.0.0';
        $this->author = 'RV Templates';
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('RV - Header Contact');
        $this->description = $this->l('Displays store contact information.');

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:rvheadercontact/views/templates/hook/rvheadercontact.tpl';
    }

    public function install()
    {

        Configuration::updateValue('RVHEADERCONTACT_PHONE', '(+0) 0123-456-789');

        $text1 = array();
        foreach (Language::getLanguages(false) as $lang) {
            $text1[$lang['id_lang']] = 'Call us :';
        }
        Configuration::updateValue('RVHEADERCONTACT_TITLE', $text1);

        return parent::install() &&
            $this->registerHook('displayTop');
    }

    public function uninstall()
    {
        Configuration::deleteByName('RVHEADERCONTACT_TITLE');
        Configuration::deleteByName('RVHEADERCONTACT_PHONE');

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
        $helper->submit_action = 'submitRvHeaderContact';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
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
                        'type' => 'text',
                        'label' => $this->l('Phone Title'),
                        'name' => 'RVHEADERCONTACT_TITLE',
                        'lang' => true,
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'label' => $this->l('Phone Number'),
                        'name' => 'RVHEADERCONTACT_PHONE',
                        'prefix' => '<i class="icon icon-phone"></i>',
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
        if (!($languages = Language::getLanguages(true))) {
            return false;
        }

        $data = array(
            'RVHEADERCONTACT_PHONE' => Tools::getValue('RVHEADERCONTACT_PHONE', Configuration::get('RVHEADERCONTACT_PHONE')),
        );

        foreach ($languages as $lang) {
            $data['RVHEADERCONTACT_TITLE'][$lang['id_lang']] = Configuration::get('RVHEADERCONTACT_TITLE', $lang['id_lang']);
        }

        return $data;
    }

    protected function postProcess()
    {
        if (((bool)Tools::isSubmit('submitRvHeaderContact')) == true) {
            if (!($languages = Language::getLanguages(true))) {
                return false;
            }

            $text1 = array();
            foreach ($languages as $lang) {
                $text1[$lang['id_lang']] = Tools::getValue('RVHEADERCONTACT_TITLE_'.$lang['id_lang']);
            }

            Configuration::updateValue('RVHEADERCONTACT_TITLE', $text1);
            Configuration::updateValue('RVHEADERCONTACT_PHONE', Tools::getValue('RVHEADERCONTACT_PHONE'));

            return $this->displayConfirmation($this->l('The settings have been updated.'));
            $this->_clearCache($this->templateFile);
        }
        return '';
    }

    public function renderWidget($hookName = null, array $configuration = [])
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

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $id_lang = $this->context->cart->id_lang;
        return array(
            'rvheadercontact_title' => Configuration::get('RVHEADERCONTACT_TITLE', $id_lang),
            'rvheadercontact_phone' => Configuration::get('RVHEADERCONTACT_PHONE'),
        );
    }
}
