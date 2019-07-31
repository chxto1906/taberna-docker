<?php
/**
* Integrate Yandex Metrica script into PrestaShop. DISCLAIMER; NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE WITH YOUR MERCHANT AGREEMENT. USE AT YOUR OWN RISK.
*
* @author    Jose Antonio Ruiz <jruizcantero@gmail.com>
* @copyright 2007-2018
* @license   This product is licensed for one customer to use on one domain. Site developer has the
*                         right to modify this module to suit their needs, but can not redistribute the module in
*                         whole or in part. Any other use of this module constitues a violation of the user agreement.
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class YandexMetrica extends Module
{
    public function __construct()
    {
        $this->tab = 'analytics_stats';
        $this->name = 'yandexmetrica';
        $this->displayName = 'Yandex Metrica';
        $this->description = $this->l('Integrate Yandex Metrica script into PrestaShop');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your Yandex Metrica details?');
        $this->author = 'Jose Antonio Ruiz';
        $this->version = '1.1.3';
        
        $min_ps_version = '1.5.0.0';
        $max_ps_version = _PS_VERSION_;
        if (version_compare(Tools::substr(_PS_VERSION_, 0, 5), '1.5.6.1', '<')) {
            //Using max=_PS_VERSION_ fails ps_versions_compliancy() if PS<1.5.6.1 (http://forge.prestashop.com/browse/PSCFV-10990)
            $max_ps_version = '1.5.6.1';
        }
        $this->ps_versions_compliancy = array('min' => $min_ps_version, 'max' => $max_ps_version);
        
        $this->need_instance = 1;
        $this->module_key = '936f129a17c179aaad0111b2f447ecc7';

        parent::__construct();

        if ($this->id && !Configuration::get('YMETRICA_COUNTER_NUMBER')) {
            $this->warning = $this->l('You have not yet set your Yandex Metrica Counter Number');
        }
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('displayFooter')) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !Configuration::deleteByName('YMETRICA_COUNTER_NUMBER')) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submit'.$this->name)) {
            $ymetricaid = Tools::getValue('ymetrica_id_field');

            if (!$ymetricaid || empty($ymetricaid) || !Validate::isCleanHtml($ymetricaid) || !Validate::isUnsignedInt($ymetricaid)) {
                $output .= $this->displayError($this->l('Invalid Yandex Metrica Counter Number'));
            } else {
                Configuration::updateValue('YMETRICA_COUNTER_NUMBER', $ymetricaid);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output.$this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),

            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Yandex Metrica Counter Number (ID):'),
                    'name' => 'ymetrica_id_field',
                    'size' => 20,
                    'required' => true,
                    //'desc' => $this->l('Example: 321456987'),
                ),
            ),

            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;           // false -> remove toolbar
        $helper->toolbar_scroll = true;         // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['ymetrica_id_field'] = Configuration::get('YMETRICA_COUNTER_NUMBER');

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayFooter($params)
    {
        $ymetricaid = '';

        $checkymid = Configuration::get('YMETRICA_COUNTER_NUMBER');

        if (Validate::isCleanHtml($checkymid) && Validate::isUnsignedInt($checkymid)) {
            $ymetricaid = $checkymid;
        }

        $this->context->smarty->assign('ymetrica_id', $ymetricaid);

        return $this->display(__FILE__, '/views/templates/hook/footerscript.tpl');
    }
}
