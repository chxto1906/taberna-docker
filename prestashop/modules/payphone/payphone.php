<?php

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . 'payphone/lib/api/PayphoneButton.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/Configuration.php';
require_once _PS_MODULE_DIR_ . 'payphone/PaymentType.php';

/**
 * Clase que define el módulo de pago PayPhone
 */
class PayPhone extends PaymentModule {

    protected $_html = '';
    protected $_postErrors = array();
    public $token;
    public $store;
    public $test;
    private $config = array('PAYPHONE_TOKEN', 'PAYPHONE_STORE');
    private $config_vars = array(
        'PAYPHONE_TOKEN' => 'Token is required.',
    );
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

    /**
     * Constructor de clase
     */
    public function __construct() {
        $this->name = 'payphone';
        $this->tab = 'payments_gateways';
        $this->version = '2.1.3';
        $this->author = 'Ornolis Vázquez';
        $this->controllers = array('payment', 'validation');
        $this->is_eu_compatible = 1;
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        $config_values = Configuration::getMultiple($this->config);

        if (!empty($config_values['PAYPHONE_TOKEN']))
            $this->token = $config_values['PAYPHONE_TOKEN'];
        if (!empty($config_values['PAYPHONE_STORE']))
            $this->store = $config_values['PAYPHONE_STORE'];

        parent::__construct();
        $this->displayName = $this->translate('PayPhone button');
        $this->description = $this->translate('Accept payments for your products via payphone button.');
        $this->confirmUninstall = $this->translate('Are you sure about removing?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        if (!isset($this->token))
            $this->warning = $this->translate('You must configure token parameter for this module');
        if (!count(Currency::checkPaymentCurrencies($this->id)))
            $this->warning = $this->translate('No currency has been set for this module.');
    }

    /**
     * Registra los hook requeridos para el pago
     * Crea una tabla en la base de datos para registar los datos del pago
     * @return boolean
     */
    public function install() {
        $tab = new Tab();
        $tab->active = 1;
        $tab->name = array();
        $tab->class_name = 'AdminPayphone';

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Controlador Admin para comprobar el pago con PayPhone';
        }

        $tab->id_parent = -1;
        $tab->module = $this->name;
        $tab->add();

        if (parent::install() &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('paymentOptions') &&
                $this->registerHook('paymentReturn') &&
                $this->registerHook('displayOrderDetail') &&
                $this->registerHook('displayAdminOrder') &&
                $this->_installDb() &&
                $this->_createOrderStates())
            return true;
        return false;
    }

    /**
     * Desinstala el módulo actual, eliminando las variables de configuración y la tabla en BD
     * @return boolean
     */
    public function uninstall() {
        $id_tab = (int) Tab::getIdFromClassName('AdminPayphone');
        $tab = new Tab((int) $id_tab);
        $result = $tab->delete();
        if (!$result)
            return false;
        return true;
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
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'payphone_transaction` (
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
     * Valida los variables de configuración
     */
    protected function _postValidation() {
        if (Tools::isSubmit('btnSubmit')) {
            foreach ($this->config_vars as $key => $value) {
                if (!Tools::getValue($key))
                    $this->_postErrors[] = $this->translate($value);
            }
        }
    }

    /**
     * Guarda los valores de la variables de configuración
     */
    protected function _postProcess() {
        if (Tools::isSubmit('btnSubmit')) {
            foreach ($this->config as $key) {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }
        $this->_html .= $this->displayConfirmation($this->translate('Settings updated'));
    }

    public function translate($text) {
        return $this->l($text, 'payphone');
    }

    /**
     * Devuelve el valor de las variables de configuración
     * @return array
     */
    public function getConfigFieldsValues() {
        $fields = array();
        foreach ($this->config as $key) {
            $fields[$key] = Tools::getValue($key, Configuration::get($key));
        }
        return $fields;
    }

    /**
     * Ejecuta el post del formulario de validación y muestra el mismo
     * @return html
     */
    public function getContent() {
        if (Tools::isSubmit('btnSubmit')) {
            $this->_postValidation();
            if (!count($this->_postErrors))
                $this->_postProcess();
            else
                foreach ($this->_postErrors as $err)
                    $this->_html .= $this->displayError($err);
        } else
            $this->_html .= '<br />';
        $this->_html .= '<p><b>' . $this->trans('Prestashop response URL', array(), 'Modules.PayPhone.Admin') . ":</b> " . $this->context->link->getModuleLink('payphone', 'validation', array(), Configuration::get('PS_SSL_ENABLED')) . "</p>";
        $this->_html .= $this->renderForm();

        return $this->_html;
    }

    public function hookdisplayHeader($params) {
        $this->context->controller->addCss(_PS_MODULE_DIR_ . 'payphone/views/css/payphone.css', 'all');
        $this->context->controller->addCss(_PS_MODULE_DIR_ . 'payphone/views/css/error.css', 'all');
    }

    /**
     * Hook que se ejecuta al efectuar el pago con el botón de pagos PayPhone para la versión 1.7 o superior
     * @param type $params
     * @return type
     */
    public function hookPaymentOptions($params) {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }
        unset($this->context->cookie->payphone_cart_id);

        $this->context->smarty->assign(array(
            'module_dir' => _MODULE_DIR_
        ));

        $paymentOptions = array();
        $payPhoneButtonOption = new PaymentOption();
        $payPhoneButtonOption->setCallToActionText($this->trans('Pagar con PayPhone', array(), 'Modules.PayPhone.Admin'))
                ->setAction($this->context->link->getModuleLink('payphone', 'payment', array('payment_type' => PaymentType::PAYPHONE), Configuration::get('PS_SSL_ENABLED')))
                ->setAdditionalInformation($this->fetch('module:payphone/views/templates/hook/payment17.tpl'));
        $paymentOptions[] = $payPhoneButtonOption;
        return $paymentOptions;
    }

    /**
     * Hook que se ejecuta para mostrar los resultados del pago con el botón de pagos PayPhone
     * @param type $params
     * @return boolean
     */
    public function hookPaymentReturn($params) {
        if (!$this->active) {
            return;
        }
        $paramOrder = $params['order'];
        if (!isset($paramOrder) || ($paramOrder->module != $this->name))
            return false;

        if (isset($paramOrder) && Validate::isLoadedObject($paramOrder) && isset($paramOrder->valid) && isset($paramOrder->reference)) {
            $sql = new DbQuery();
            $sql->select('*');
            $sql->from('payphone_transaction', 'pt');
            $sql->where('pt.client_user_id = ' . $paramOrder->id_cart);
            $data = Db::getInstance()->executeS($sql);

            $format = 'Y-m-d H:i:s';
            $date = DateTime::createFromFormat($format, $data[0]['date_add']);
            $total = Tools::displayPrice(floatval($data[0]['amount'] / 100), null, false);
            $this->smarty->assign(array(
                'transaction_id' => $data[0]['transaction_id'],
                'status' => $data[0]['transaction_status'],
                'date' => $date->format('d/m/Y H:i:s'),
                'amount' => $total,
                'authorization_code' => $data[0]['authorization_code'],
                'message' => $data[0]['message'],
                'phone_number' => $data[0]['phone_number'],
                'module_dir' => _MODULE_DIR_,
            ));
            $result = $this->fetch('module:payphone/views/templates/hook/order-confirmation17.tpl');
            return $result;
        }
    }

    /**
     * Hook que se ejecuta al mostrar los detalles de la orden desde el Admin, cuyo pago se realizó con PayPhone
     * @param type $params
     * @return html
     */
    public function hookDisplayAdminOrder($params) {
        $order = new Order($params['id_order']);
        if (!in_array($order->current_state, array(Configuration::get('PS_PAYPHONE_APPROVED'), Configuration::get('PS_PAYPHONE_PENDING'), Configuration::get('PS_PAYPHONE_REJECTED'))))
            return;
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('payphone_transaction', 'pt');
        $sql->where('pt.client_transaction_id = "' . $order->reference . '"');
        $data = Db::getInstance()->executeS($sql);
        $status = ($data == false) ? 'Pending' : $data[0]['transaction_status'];

        $link = $this->context->link->getAdminLink('AdminPayphone', true) . '&id_order=' . $order->id;
        $this->smarty->assign(array(
            'transaction_id' => $data[0]['transaction_id'],
            'authorization_code' => $data[0]['authorization_code'],
            'phone_number' => $data[0]['phone_number'],
            'message' => $data[0]['message'],
            'bin' => $data[0]['bin'],
            'status' => $status,
            'card_brand' => $data[0]['card_bran'],
            'check_payment_url' => $link,
        ));
        return $this->display(__FILE__, 'views/templates/hook/admin-order17.tpl');
    }

    /**
     * Hook que se ejecuta al mostrar los detalles de la orden al cliente cuyo pago se realizó con PayPhone
     * @param type $order
     * @return html
     */
    public function hookDisplayOrderDetail($order) {
        if (!in_array($order['order']->current_state, array(Configuration::get('PS_PAYPHONE_APPROVED'), Configuration::get('PS_PAYPHONE_PENDING'), Configuration::get('PS_PAYPHONE_REJECTED'))))
            return;
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('payphone_transaction', 'pt');
        $sql->where('pt.client_transaction_id = "' . $order['order']->reference . '"');
        $data = Db::getInstance()->executeS($sql);
        if ($data == false) {
            $status = 'Pending';
            $data[0]['transaction_id'] = "---";
            $data[0]['authorization_code'] = "---";
            $data[0]['phone_number'] = "---";
            $data[0]['message'] = "---";
            $data[0]['bin'] = "---";
            $data[0]['card_bran'] = "---";
        } else
            $status = $data[0]['transaction_status'];

        $link = $this->context->link->getModuleLink('payphone', 'validation', array('id_order' => $order['order']->id), Configuration::get('PS_SSL_ENABLED'));
        $this->smarty->assign(array(
            'transaction_id' => $data[0]['transaction_id'],
            'authorization_code' => $data[0]['authorization_code'],
            'phone_number' => $data[0]['phone_number'],
            'message' => $data[0]['message'],
            'bin' => $data[0]['bin'],
            'status' => $status,
            'card_brand' => $data[0]['card_bran'],
            'check_payment_url' => $link,
        ));
        return $this->display(__FILE__, 'views/templates/hook/order-detail17.tpl');
    }

    /**
     * Verifica si el plugin actual tiene habilitado la misma moneda que el carro de compra
     * @param type $cart Datos del carrito de compra
     * @return boolean
     */
    public function checkCurrency($cart) {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);

        if (is_array($currencies_module))
            foreach ($currencies_module as $currency_module)
                if ($currency_order->id == $currency_module['id_currency'])
                    return true;
        return false;
    }

    /**
     * Establece los campos del formulario de configuración usando el API de formularios
     * @return type
     */
    public function renderForm() {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->translate('Variables'),
                    'icon' => 'icon-envelope'
                ),
                'input' => array(
                    array(
                        'type' => 'textarea',
                        'label' => $this->translate('Token'),
                        'name' => 'PAYPHONE_TOKEN',
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->translate('Store id'),
                        'name' => 'PAYPHONE_STORE',
                        'desc' => $this->translate('Store id in payphone')
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions'),
                    'class' => 'btn btn-default pull-right'
                )
            ),
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = (int) Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }

    /**
     * Realiza un post al API de pago de PayPhone para verificar el estado de una transacción.
     * Actualiza la orden
     * @param type $id_order
     * @return boolean Si la llamada fue satisfactoria o no
     */
    public function updatePendingTransaction($id_order) {
        $order = new Order($id_order);
        if (!Validate::isLoadedObject($order))
            return false;
        $config = ConfigurationManager::Instance();
        $config->Token = $this->token;
        $pb = new PayphoneButton();
        try {
            $data = $pb->GetSaleByClientId($order->reference);
            if (is_array($data)) {
                $data = $data[0];
                $status = Configuration::get('PS_PAYPHONE_PENDING');
                if ($data->statusCode == 3)
                    $status = Configuration::get('PS_PAYPHONE_APPROVED');
                if ($data->statusCode == 2)
                    $status = Configuration::get('PS_PAYPHONE_REJECTED');
                if ($status != Configuration::get('PS_PAYPHONE_PENDING')) {
                    ShopUrl::cacheMainDomainForShop((int) $order->id_shop);
                    $order_state = new OrderState($status);
                    if (!Validate::isLoadedObject($order_state)) {
                        $this->errors[] = Tools::displayError('The new order status is invalid.');
                    }
                    $current_order_state = $order->getCurrentOrderState();
                    $history = new OrderHistory();
                    $history->id_order = $order->id;
                    $use_existings_payment = false;
                    if (!$order->hasInvoice()) {
                        $use_existings_payment = true;
                    }
                    $history->changeIdOrderState((int) $order_state->id, $order, $use_existings_payment);
                    $history->addWithemail(true);
                    $write = array();
                    $write['transaction_id'] = (int) $data->transactionId;
                    $write['message'] = ($data->statusCode == 3) ? pSQL("Pago recibido por el vendedor") : pSQL("Pago no efectuado");
                    $write['phone_number'] = pSQL($data->phoneNumber);
                    $write['transaction_status'] = pSQL($data->transactionStatus);
                    $write['client_transaction_id'] = pSQL($data->clientTransactionId);
                    $write['client_user_id'] = pSQL($order->id_cart);
                    $write['deferred'] = $data->deferred;
                    $write['amount'] = $data->amount;
                    $write['date_add'] = date("Y-m-d H:i:s");
                    if ($data->deferred)
                        $write['deferred_message'] = pSQL($data->deferredMessage);
                    if ($data->statusCode == 3) {
                        $write['bin'] = pSQL($data->bin);
                        $write['card_bran'] = pSQL($data->cardBrand);
                        $write['authorization_code'] = pSQL($data->authorizationCode);
                    }
                    Db::getInstance()->insert('payphone_transaction', $write);
                }
                return true;
            } else {
                if ($data->error->message == 'Transacción no encontrada') {
                    ShopUrl::cacheMainDomainForShop((int) $order->id_shop);
                    $order_state = new OrderState(Configuration::get('PS_OS_CANCELED'));

                    if (!Validate::isLoadedObject($order_state)) {
                        $this->errors[] = Tools::displayError('The new order status is invalid.');
                    }
                    $current_order_state = $order->getCurrentOrderState();
                    $history = new OrderHistory();
                    $history->id_order = $order->id;
                    $use_existings_payment = false;
                    if (!$order->hasInvoice()) {
                        $use_existings_payment = true;
                    }
                    $history->changeIdOrderState((int) $order_state->id, $order, $use_existings_payment);
                    $history->addWithemail(true);
                    return true;
                } else {
                    return $data->error->errors[0];
                }
            }
        } catch (PayPhoneWebException $e) {
            $this->context->cookie->payphone_cart_id = (int) $order->id_cart;
            return $e->ErrorList[0]->message;
        } catch (Exception $e) {
            $this->context->cookie->payphone_cart_id = (int) $order->id_cart;
            return $e->getMessage();
        }
    }

}
