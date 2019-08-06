<?php
/**
* 2007-2019 PrestaShop
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
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Tarjetas_payphone extends PaymentModule
{
    protected $config_form = false;
    private $payphone_pending = array(
        'PS_PAYPHONE_PENDING' => array(
            'es' => 'Pendiente de pago con Payphone',
            'en' => 'Pending Payphone payment'));
    private $payphone_green = array(
        'PS_PAYPHONE_APPROVED' => array(
            'es' => 'Pago con Payphone aprobado',
            'en' => 'Accepted Payphone payment'),
    );
    private $payphone_red = array(
        'PS_PAYPHONE_REJECTED' => array(
            'es' => 'Pago con Payphone cancelado',
            'en' => 'Canceled or error Payphone payment'),
    );


    public function __construct()
    {
        $this->name = 'tarjetas_payphone';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'Henry Campoverde';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Payphone Api Tarjetas');
        $this->description = $this->l('Pago con tarjetas de crédito utilizando el API de Payphone');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (extension_loaded('curl') == false)
        {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }


        Configuration::updateValue('TARJETAS_PAYPHONE_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            //$this->registerHook('payment') &&
            $this->registerHook('paymentOptions') &&
            $this->registerHook('paymentReturn') &&
            $this->_installDb() &&
            $this->_createOrderStates();
    }

    public function uninstall()
    {
        Configuration::deleteByName('TARJETAS_PAYPHONE_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitTarjetas_payphoneModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }


    /**
     * Ejecuta la creación de estados de orden para el botón de pagos
     */
    private function _createOrderStates() {
        $this->_createPayphonePaymentStatus($this->payphone_pending, '#03a3e3', '', false, false, '', false);
        $this->_createPayphonePaymentStatus($this->payphone_green, '#4dbe50', 'payment', true, true, true, true);
        $this->_createPayphonePaymentStatus($this->payphone_red, '#fb4f38', 'payment_error', false, true, false, true);
        return true;
    }

    /**
     * Crea un estado de orden de compra
     * @param type $array Arreglo con el nombre de la orden en diversos idiomas
     * @param type $color Color que mostrará la orden
     * @param type $template Plantilla para el envío de correo
     * @param type $invoice Determina si el estado genera factura
     * @param type $send_email Determina si se envía correo
     * @param type $paid Determina si la orden ha sido pagada
     * @param type $logable Determina si se escribe en el log 
     */
    private function _createPayphonePaymentStatus($array, $color, $template, $invoice, $send_email, $paid, $logable) {
        foreach ($array as $key => $value) {
            $ow_status = Configuration::get($key);
            if ($ow_status === false) {
                $order_state = new OrderState();
            } else
                $order_state = new OrderState((int) $ow_status);

            $langs = Language::getLanguages();

            foreach ($langs as $lang) {
                if ($lang['iso_code'] == 'es' || $lang['iso_code'] == 'en') {
                    $name = $value[$lang['iso_code']];
                    $name_clean = utf8_encode(html_entity_decode($name));
                    $order_state->name[$lang['id_lang']] = $name_clean;
                }
            }

            $order_state->invoice = $invoice;
            $order_state->send_email = $send_email;

            if ($template != '')
                $order_state->template = $template;

            if ($paid != '')
                $order_state->paid = $paid;

            $order_state->logable = $logable;
            $order_state->color = $color;
            $order_state->save();

            Configuration::updateValue($key, (int) $order_state->id);

            Tools::copy(dirname(__FILE__) . '/views/images/' . $key . '.gif', _PS_ROOT_DIR_ . '/img/os/' . (int) $order_state->id . '.gif');
        }
    }

    /**
     * Crea una tabla en la BD para registrar los datos del pago
     * @return type
     */
    private function _installDb() {
        return Db::getInstance()->Execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'payphone_transaction_api` (
                `id` INT(11) unsigned NOT NULL auto_increment,
                `transaction_id` INT(11) unsigned NOT NULL,
                `message` VARCHAR(255) NOT NULL,
                `authorization_code` VARCHAR(100) NULL,
                `phone_number` VARCHAR(20) NOT NULL,
                `transaction_status` VARCHAR(50) NOT NULL,
                `client_transaction_id` VARCHAR(50) NOT NULL,
                `client_user_id` VARCHAR(50) NULL,
                `deferred` TINYINT(1) NULL,
                `deferred_message` VARCHAR(255) NULL,
                `amount` VARCHAR(10) NOT NULL,
                `bin` VARCHAR(6) NULL,
                `card_bran` VARCHAR(30) NULL,
                `date_add` DATETIME NOT NULL,
                PRIMARY KEY (`id`)) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
    }


    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTarjetas_payphoneModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
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
                        'label' => $this->l('Live mode'),
                        'name' => 'TARJETAS_PAYPHONE_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'TARJETAS_PAYPHONE_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'TARJETAS_PAYPHONE_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'TARJETAS_PAYPHONE_LIVE_MODE' => Configuration::get('TARJETAS_PAYPHONE_LIVE_MODE', true),
            'TARJETAS_PAYPHONE_ACCOUNT_EMAIL' => Configuration::get('TARJETAS_PAYPHONE_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'TARJETAS_PAYPHONE_ACCOUNT_PASSWORD' => Configuration::get('TARJETAS_PAYPHONE_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/aes.js');
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    /**
     * This method is used to render the payment button,
     * Take care if the button should be displayed or not.
     */
    /*public function hookPayment($params)
    {
        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);

        if (in_array($currency->iso_code, $this->limited_currencies) == false)
            return false;

        $this->smarty->assign('module_dir', $this->_path);

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }*/


    /**
     * This method is used to render the payment button,
     * Take care if the button should be displayed or not.
     */
    public function hookPaymentOptions($params)
    {
        /*$currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);

        if (in_array($currency->iso_code, $this->limited_currencies) == false)
            return false;

        $this->smarty->assign('module_dir', $this->_path);

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');*/
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }
        //$this->smarty->assign('module_dir', $this->_path);
        $newOption = new PaymentOption();
        $newOption->setModuleName($this->name)
                  ->setCallToActionText($this->l('Pagar con Tarjeta'))
                  ->setAction($this->context->link->getModuleLink('tarjetas_payphone', 'validation', array(), true))
                  ->setForm($this->generateForm());
                  //->setAdditionalInformation($this->fetch('module:tarjetas_payphone/views/templates/hook/paymentAdditional.tpl'));
        $payment_options = [
            $newOption
        ];

        return $payment_options;

    }

    protected function generateForm()
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = sprintf("%02d", $i);
        }
        $years = [];
        for ($i = 0; $i <= 10; $i++) {
            $years[] = date('Y', strtotime('+'.$i.' years'));
        }

        $this->context->smarty->assign([
            'action' => $this->context->link->getModuleLink($this->name, 'validation', array(), true),
            'months' => $months,
            'years' => $years

        ]);
        return $this->context->smarty->fetch('module:tarjetas_payphone/views/templates/front/payment_form.tpl');
    }


    public function checkCurrency($cart)
    {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);
        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * This hook is used to display the order confirmation page.
     */
    public function hookPaymentReturn($params)
    {
        //var_dump($params);
        if ($this->active == false)
            return;

        $order = $params['order'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR'))
            $this->smarty->assign('status', 'ok');

        $this->smarty->assign(array(
            'id_order' => $order->id,
            'reference' => $order->reference,
            'params' => $params,
            'total' => Tools::displayPrice($params['total_paid'], $params['id_currency'], false),
        ));

        return $this->display(__FILE__, 'views/templates/hook/confirmation.tpl');
    }
}
