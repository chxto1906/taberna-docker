<?php
/**
* Cash On Delivery With Fee
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2019 idnovate
*  @license   See above
*/

include_once(_PS_MODULE_DIR_.'codfee/classes/CodfeeConfiguration.php');
//use PrestaShop\PrestaShop\Adapter\StockManager;
class CodFee extends PaymentModule
{
    private $_html = '';
    private $_postErrors = array();

    public function __construct()
    {
        $this->name = 'codfee';
        $this->tab = 'payments_gateways';
        $this->version = '3.3.1';
        $this->author = 'idnovate';
        $this->module_key = '3b802d29c8d730c7b17aa2970ab57c95';
        $this->author_address = '0xd89bcCAeb29b2E6342a74Bc0e9C82718Ac702160';
        $this->addons_id_product = '6337';
        $this->page = basename(__FILE__, '.php');
        $this->bootstrap = true;

        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        $this->is_eu_compatible = 1;

        $this->tabClassName = 'AdminCodfeeConfiguration';
        $this->imageType = 'png';

        parent::__construct();

        $this->secure_key = Tools::encrypt($this->name);
        //$this->ps_versions_compliancy = array('min' => '1.4', 'max' => _PS_VERSION_);
        $this->displayName = $this->l('Cash on delivery with fee');

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $this->description = $this->l('Accept cash on delivery payments with extra fee and cash on pickup payments.');
        } else {
            $this->description = $this->l('Accept cash on delivery payments with extra fee and cash on pickup payments. Configurable by customer group, carrier, country, zone, category, manufacturer and supplier.');
        }

        $this->confirmUninstall = $this->l('Are you sure you want to delete the module and the related data?');

        /* Backward compatibility */
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
        }

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE')) {
                $this->warning = $this->l('You have to enable non PrestaShop modules at ADVANCED PARAMETERS - PERFORMANCE');
            }
        }
    }

    public function install()
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            if (!parent::install()
                || !Configuration::updateValue('COD_FEE_TAX', 0)
                || !Configuration::updateValue('COD_FEE', 0)
                || !Configuration::updateValue('COD_FREE_FEE', 0)
                || !Configuration::updateValue('COD_FEE_TYPE', 0)
                || !Configuration::updateValue('COD_FEE_MIN', 0)
                || !Configuration::updateValue('COD_FEE_MAX', 0)
                || !Configuration::updateValue('COD_FEE_MIN_AMOUNT', 0)
                || !Configuration::updateValue('COD_FEE_MAX_AMOUNT', 0)
                || !Configuration::updateValue('COD_FEE_CARRIERS', 0)
                || !Configuration::updateValue('COD_FEE_STATUS', Configuration::get('PS_OS_PREPARATION'))
                || !Configuration::updateValue('COD_SHOW_CONF', 1)
                || !$this->registerHook('payment')
                || !$this->registerHook('paymentReturn')
                || !$this->registerHook('orderDetailDisplayed')
                || !$this->registerHook('displayBackOfficeHeader')
                || !$this->registerHook('adminOrder')
                || !$this->registerHook('PDFInvoice')
                || !$this->registerHook('header')
                || (Hook::get('displayPaymentEU') && !$this->registerHook('displayPaymentEU'))) {
                return false;
            }
            return true;
        } elseif (version_compare(_PS_VERSION_, '1.5', '>=')) {
            if (!parent::install()
                || !$this->initSQLCodfeeConfiguration()
                || (version_compare(_PS_VERSION_, '1.7', '<') && !$this->registerHook('payment'))
                || !$this->registerHook('paymentReturn')
                || (version_compare(_PS_VERSION_, '1.7', '<') && Hook::get('displayPaymentEU') && !$this->registerHook('displayPaymentEU'))
                || !$this->registerHook('displayPDFInvoice')
                || !$this->registerHook('displayOrderDetail')
                || !$this->registerHook('displayAdminOrder')
                || !$this->registerHook('actionValidateOrder')
                || !$this->registerHook('displayBackOfficeHeader')
                || !$this->registerHook('displayRightColumnProduct')
                || !$this->registerHook('displayLeftColumnProduct')
                || (version_compare(_PS_VERSION_, '1.7', '>=') && !$this->registerHook('paymentOptions'))
                || (version_compare(_PS_VERSION_, '1.7', '>=') && !$this->registerHook('displayProductAdditionalInfo'))
                || !$this->registerHook('header')) {
                return false;
            }
            if (version_compare(_PS_VERSION_, '1.7', '<')) {
                $this->addTab($this->displayName, $this->tabClassName, -1);
            }
            return true;
        } else {
            return false;
        }
    }

    public function uninstall()
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            if (!parent::uninstall()
                || !Configuration::deleteByName('COD_FEE_TAX')
                || !Configuration::deleteByName('COD_FEE')
                || !Configuration::deleteByName('COD_FREE_FEE')
                || !Configuration::deleteByName('COD_FEE_TYPE')
                || !Configuration::deleteByName('COD_FEE_MIN')
                || !Configuration::deleteByName('COD_FEE_MAX')
                || !Configuration::deleteByName('COD_FEE_MIN_AMOUNT')
                || !Configuration::deleteByName('COD_FEE_MAX_AMOUNT')
                || !Configuration::deleteByName('COD_FEE_CARRIERS')
                || !Configuration::deleteByName('COD_FEE_STATUS')
                || !Configuration::deleteByName('COD_SHOW_CONF')) {
                return false;
            }
            return true;
        } else {
            if (!parent::uninstall()
                || !$this->uninstallSQL()) {
                return false;
            }
            if (version_compare(_PS_VERSION_, '1.7', '<')) {
                $this->removeTab($this->tabClassName);
            }
            return true;
        }
    }

    public function getContent()
    {
        if (Tools::getValue('submitCOD')) {
            if (!count($this->_postErrors)) {
                Configuration::updateValue('COD_FEE_TAX', (float)str_replace(',', '.', Tools::getValue('fee_tax') ? Tools::getValue('fee_tax') : 0));
                Configuration::updateValue('COD_FEE', (float)str_replace(',', '.', Tools::getValue('fee') ? Tools::getValue('fee') : 0));
                Configuration::updateValue('COD_FREE_FEE', (float)str_replace(',', '.', Tools::getValue('free_fee') ? Tools::getValue('free_fee') : 0));
                Configuration::updateValue('COD_FEE_TYPE', (float)str_replace(',', '.', Tools::getValue('feetype') ? Tools::getValue('feetype') : 0));
                Configuration::updateValue('COD_FEE_MIN', (float)str_replace(',', '.', Tools::getValue('feemin') ? Tools::getValue('feemin') : 0));
                Configuration::updateValue('COD_FEE_MAX', (float)str_replace(',', '.', Tools::getValue('feemax') ? Tools::getValue('feemax') : 0));
                Configuration::updateValue('COD_FEE_MIN_AMOUNT', (float)str_replace(',', '.', Tools::getValue('minimum_amount') ? Tools::getValue('minimum_amount') : 0));
                Configuration::updateValue('COD_FEE_MAX_AMOUNT', (float)str_replace(',', '.', Tools::getValue('maximum_amount') ? Tools::getValue('maximum_amount') : 0));
                Configuration::updateValue('COD_FEE_CARRIERS', trim(Tools::getValue('id_carriers'), ';'));
                Configuration::updateValue('COD_FEE_STATUS', Tools::getValue('fee_status'));
                Configuration::updateValue('COD_SHOW_CONF', Tools::getValue('show_conf'));
                $this->displayConf();
            } else {
                $this->displayErrors();
            }
        }

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $this->displayFormSettings();
        } else {
            // check if the tab was not created in the installation
            $id_tab = Tab::getIdFromClassName($this->tabClassName);
            if (!$id_tab) {
                $this->addTab($this->displayName, $this->tabClassName);
            }
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return Tools::redirectAdmin('index.php?tab=' . $this->tabClassName . '&token=' . Tools::getAdminTokenLite($this->tabClassName));
            } else {
                return Tools::redirectAdmin('index.php?controller=' . $this->tabClassName . '&token=' . Tools::getAdminTokenLite($this->tabClassName));
            }
            return $this->display(__FILE__, 'admin.tpl');
        }

        $this->context->smarty->assign(array(
            'displayName'   => $this->displayName,
            'cf_path'       => $this->_path,
            'html'          => $this->_html,
        ));

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->display(__FILE__, 'views/templates/admin/admin.tpl');
        } else {
            return $this->display(__FILE__, 'admin.tpl');
        }
    }

    public function displayFormSettings()
    {
        $conf = Configuration::getMultiple(array('COD_FEE_TAX','COD_FEE','COD_FREE_FEE','COD_FEE_TYPE','COD_FEE_MIN','COD_FEE_MAX','COD_FEE_MIN_AMOUNT','COD_FEE_MAX_AMOUNT','COD_FEE_CARRIERS','COD_FEE_STATUS', 'COD_SHOW_CONF'));
        $fee_tax = Tools::getValue('fee_tax') ? Tools::getValue('fee_tax') : $conf['COD_FEE_TAX'] != '' ? $conf['COD_FEE_TAX'] : '0';
        $fee = Tools::getValue('fee') ? Tools::getValue('fee') :  $conf['COD_FEE'] != '' ? $conf['COD_FEE'] : '0';
        $free_fee = Tools::getValue('free_fee') ? Tools::getValue('free_fee') : $conf['COD_FREE_FEE'] != '' ? $conf['COD_FREE_FEE'] : '0';
        $feetype = Tools::getValue('feetype') ? Tools::getValue('feetype') : $conf['COD_FEE_TYPE'] != '' ? $conf['COD_FEE_TYPE'] : '0';
        $feemin = Tools::getValue('feemin') ? Tools::getValue('feemin') : $conf['COD_FEE_MIN'] != '' ? $conf['COD_FEE_MIN'] : '0';
        $feemax = Tools::getValue('feemax') ? Tools::getValue('feemax') : $conf['COD_FEE_MAX'] != '' ? $conf['COD_FEE_MAX'] : '0';
        $minimum_amount = Tools::getValue('minimum_amount') ? Tools::getValue('minimum_amount') : $conf['COD_FEE_MIN_AMOUNT'] != '' ? $conf['COD_FEE_MIN_AMOUNT'] : '0';
        $maximum_amount = Tools::getValue('maximum_amount') ? Tools::getValue('maximum_amount') : $conf['COD_FEE_MAX_AMOUNT'] != '' ? $conf['COD_FEE_MAX_AMOUNT'] : '0';
        $id_carriers = Tools::getValue('id_carriers') ? Tools::getValue('id_carriers') : $conf['COD_FEE_CARRIERS'] != '' ? $conf['COD_FEE_CARRIERS'] : '';
        $show_conf = Tools::getValue('show_conf') ? Tools::getValue('show_conf') : $conf['COD_SHOW_CONF'] != '' ? $conf['COD_SHOW_CONF'] : '';

        $carriers = null;
        $html_carriers = '';
        $carriers = Carrier::getCarriers($this->context->cookie->id_lang, true, false, false, null, PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);
        $statuses = OrderState::getOrderStates((int)$this->context->cookie->id_lang);
        $id_carriers_selected_array = explode(';', $id_carriers);
        foreach ($carriers as $carrier) {
            $selected = 0;
            $carrier_id_key = 'id_carrier';
            foreach ($id_carriers_selected_array as $key => $id) {
                if ($id_carriers_selected_array[$key] == $carrier[$carrier_id_key]) {
                    $selected = 1;
                    continue;
                }
            }
            $html_carriers .= '<div class="row" style="clear:both"><label for="id_carrier'.$carrier[$carrier_id_key].'" >'.$carrier['name'].'</label>
                                <div class="margin-form">
                                    <input type="checkbox" onChange="setIdCarriers();" id="id_carrier'.$carrier[$carrier_id_key].'" name="id_carrier" value="'.htmlentities($carrier[$carrier_id_key], ENT_COMPAT, 'UTF-8').'" '.($selected == 1 ? 'checked="checked" ' : '').'/>
                                </div></div>';
        }

        $html_carriers .= '<input type="hidden" id="id_carriers" name="id_carriers" value="'.trim(htmlentities($id_carriers, ENT_COMPAT, 'UTF-8'), ';').'"/>';

        $status_html = '';
        $default_status = Configuration::get('PS_OS_PREPARATION');
        foreach ($statuses as $status) {
            if (Configuration::get('COD_FEE_STATUS') == $status['id_order_state']) {
                $default_status = $status['name'];
            }
            if (Configuration::get('COD_FEE_STATUS') != $status['id_order_state']) {
                $status_html .= '<option value='.htmlentities($status['id_order_state'], ENT_COMPAT, 'UTF-8').'>'.$status['name'].'</option>';
            }
        }

        $default_currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));

        $this->_html .= '
        <script type="text/javascript">
            function viewOptions(select){
                if (select.value == "0")
                {
                    document.getElementById("amount1").style.display="block";
                    document.getElementById("amount2").style.display="block";
                    document.getElementById("percentage1").style.display="none";
                    document.getElementById("percentage2").style.display="none";
                    document.getElementById("minimumfee1").style.display="none";
                    document.getElementById("minimumfee2").style.display="none";
                    document.getElementById("maximumfee1").style.display="none";
                    document.getElementById("maximumfee2").style.display="none";
                }
                if (select.value == "1")
                {
                    document.getElementById("amount1").style.display="none";
                    document.getElementById("amount2").style.display="none";
                    document.getElementById("percentage1").style.display="block";
                    document.getElementById("percentage2").style.display="block";
                    document.getElementById("minimumfee1").style.display="block";
                    document.getElementById("minimumfee2").style.display="block";
                    document.getElementById("maximumfee1").style.display="block";
                    document.getElementById("maximumfee2").style.display="block";
                }
                if (select.value == "2")
                {
                    document.getElementById("amount1").style.display="block";
                    document.getElementById("amount2").style.display="block";
                    document.getElementById("percentage1").style.display="block";
                    document.getElementById("percentage2").style.display="block";
                    document.getElementById("minimumfee1").style.display="block";
                    document.getElementById("minimumfee2").style.display="block";
                    document.getElementById("maximumfee1").style.display="block";
                    document.getElementById("maximumfee2").style.display="block";
                }
            }
            function setIdCarriers(){
                var ids = "";
                $("input[name=id_carrier]").each(function(){
                    if ($(this).attr("checked"))
                    {
                        ids = $(this).attr("value")+";"+ids;
                    }
                });
                document.getElementById("id_carriers").value=ids;
            }
        </script>
        <form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="form" id="form">
        <fieldset>
            <legend><img src="../img/admin/contact.gif" />'.$this->l('Fee settings').'</legend>
            <label>'.$this->l('Type:').'</label>
            <div class="margin-form">
                <select name="feetype" onChange="viewOptions(this)">
                    <option value="0" '.(!$feetype || $feetype == '0' ? 'selected' : '').' >'.$this->l('Fixed').'</option>
                    <option value="1" '.($feetype == '1' ? 'selected' : '').' >'.$this->l('Percentage').'</option>
                    <option value="2" '.($feetype == '2' ? 'selected' : '').' >'.$this->l('Fixed').'+'.$this->l('Percentage').'</option>
                </select>
            </div>
            <label id="amount1" '.(!$feetype || $feetype == '0' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').'>'.$this->l('Amount:').'</label>
            <div class="margin-form">
                <div id="amount2" '.(!$feetype || $feetype == '0' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').' ><input type="text" size="10" name="fee" value="'.htmlentities($fee, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Fixed amount to add to the cost of the order.').'</span></div>
            </div>
            <label id="percentage1" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').'>'.$this->l('Percentage:').'</label>
            <div class="margin-form">
                <div id="percentage2" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').' ><input type="text" size="10" name="fee_tax" value="'.htmlentities($fee_tax, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">%</span><br /><span>'.$this->l('Percentage to add to the cost of the order.').'</span></div>
            </div>
            <label id="minimumfee1" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').'>'.$this->l('Minimum Fee:').'</label>
            <div class="margin-form">
                <div id="minimumfee2" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').' ><input type="text" size="10" name="feemin" value="'.htmlentities($feemin, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Minimum fee to add to the cost of the order.').'</span></div>
            </div>
            <label id="maximumfee1" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').'>'.$this->l('Maximum Fee:').'</label>
            <div class="margin-form">
                <div id="maximumfee2" '.($feetype == '1' || $feetype == '2' ? 'style="display:block;"' : 'style="display:none;"').' ><input type="text" size="10" name="feemax" value="'.htmlentities($feemax, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Maximum fee to add to the cost of the order.').'</span></div>
            </div>
            <center><input type="submit" name="submitCOD" value="'.$this->l('Update settings').'" class="button" /></center>
        </fieldset>
        <br />
        <fieldset>
            <legend><img src="../img/admin/contact.gif" />'.$this->l('Order amount settings').'</legend>
            <label id="minimumamount">'.$this->l('Minimum amount:').'</label>
            <div class="margin-form">
                <div id="minimumamount" ><input type="text" size="10" name="minimum_amount" value="'.htmlentities($minimum_amount, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Minimum amount to be available this payment method (0 to disable).').'</span></div>
            </div>
            <label id="maximumamount">'.$this->l('Maximum amount:').'</label>
            <div class="margin-form">
                <div id="maximumamount" ><input type="text" size="10" name="maximum_amount" value="'.htmlentities($maximum_amount, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Maximum amount to be available this payment method (0 to disable).').'</span></div>
            </div>
            <label id="freefee">'.$this->l('Minimum amount for free fee:').'</label>
            <div class="margin-form">
                <div id="freefee" ><input type="text" size="10" name="free_fee" value="'.htmlentities($free_fee, ENT_COMPAT, 'UTF-8').'" />&nbsp;<span class="currency">'.$default_currency->sign.'</span><br /><span>'.$this->l('Amount from which the fee is free (0 to disable).').'</span></div>
            </div>
            <br /><center><input type="submit" name="submitCOD" value="'.$this->l('Update settings').'" class="button" /></center>
        </fieldset>
        <br />
        <fieldset>
            <legend><img src="../img/admin/contact.gif" />'.$this->l('Delivery options settings').'</legend>
            <p class="clear">'.$this->l('Select delivery options with enabled cash on delivery payment option:').'</p><br />
            '.$html_carriers.'
                    <br />
            <label>'.$this->l('Show confirmation page').'</label>
            <div class="margin-form">
                <input type="checkbox" id="show_conf" name="show_conf" value="1" '.($show_conf == 1 ? 'checked="checked" ' : '').'/>
            </div>
            <br />
            <label>'.$this->l('Initial order status').'</label>
            <div class="margin-form">
                <select name="fee_status">
                    <option value="'.(Configuration::get('COD_FEE_STATUS') ? Configuration::get('COD_FEE_STATUS') : '').'">'.$default_status.'</option>
                    '.$status_html.'
                </select>
                <p class="clear">'.$this->l('Initial status of validated order.').'</p>
            </div>
            <center><input type="submit" name="submitCOD" value="'.$this->l('Update settings').'" class="button" /></center>
        </fieldset>
        </form><br />';
    }

    public function displayConf()
    {
        $this->_html .= '
        <div class="bootstrap"><div class="conf confirm alert alert-success">
            '.$this->l('Settings updated').'
        </div></div>';
    }

    public function displayErrors()
    {
        $nbErrors = count($this->_postErrors);
        $this->_html .= '
        <div class="alert error">
            <h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
            <ol>';
        foreach ($this->_postErrors as $error) {
            $this->_html .= '<li>'.$error.'</li>';
        }
        $this->_html .= '
            </ol>
        </div>';
    }

    public function hookPaymentReturn($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->hookPaymentReturn14($params);
        } elseif (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return $this->hookPaymentReturn16($params);
        } else {
            return $this->hookPaymentReturn16($params);
        }
    }

    public function hookPaymentReturn14($params)
    {
        if (!$this->active) {
            return;
        }

        $state = $params['objOrder']->getCurrentState();

        if ($state == Configuration::get('COD_FEE_STATUS') || $state == Configuration::get('PS_OS_OUTOFSTOCK')) {
            $this->context->smarty->assign(array(
                'total'         => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
                'success'       => true,
                'id_order'      => $params['objOrder']->id
            ));
        } else {
            $this->context->smarty->assign('status', false);
        }

        return $this->display(__FILE__, 'views/templates/hook/payment_return14.tpl');
    }

    public function hookPaymentReturn16($params)
    {
        if (!$this->active) {
            return false;
        }

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $order = $params['objOrder'];
        } else {
            $order = $params['order'];
        }

        $state = $order->getCurrentState();
        $codfeeconf = new CodfeeConfiguration(Tools::getValue('c'));

        if ($state == $codfeeconf->initial_status || $state == Configuration::get('PS_OS_OUTOFSTOCK')) {

            $products = $order->getProducts();

            if (version_compare(_PS_VERSION_, '1.7', '<')) {
                $total_to_pay = $params['total_to_pay'];
            } else {
                $total_to_pay = $order->getOrdersTotalPaid();
            }

            if ($codfeeconf->payment_text[$this->context->language->id] && $codfeeconf->payment_text[$this->context->language->id] != '') {
                $shop_message = $codfeeconf->payment_text[$this->context->language->id];
            } else {
                $shop_message = false;
            }

            if (!$codfeeconf) {
                $fee = 0;
            }

            $cart = new Cart((int)$order->id_cart);
            
            $customer = new Customer((int)$cart->id_customer);
            $group = new Group((int)$customer->id_default_group);
            if ($group->price_display_method == '1') {
                $price_display_method = false;
            } else {
                $price_display_method = true;
            }

            $fee = (float)Tools::ps_round((float)$this->getFeeCost($cart, (array)$codfeeconf, $price_display_method), 2);
            if ($codfeeconf->free_on_freeshipping == '1' && $cart->getOrderTotal(true, Cart::ONLY_SHIPPING) == 0) {
                $fee = (float)0.00;
            }
            if ($codfeeconf->free_on_freeshipping == '1' && count($cart->getCartRules(CartRule::FILTER_ACTION_SHIPPING)) > 0) {
                $fee = (float)0.00;
            }
            Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.bqSQL('orders').'`
            SET `codfee` = '.$fee.'
            WHERE `'.bqSQL('id_order').'` = '.(int)$order->id);

            $currency = new Currency($order->id_currency);

            $this->context->smarty->assign(array(
                'total'         => Tools::displayPrice($total_to_pay, $currency, false),
                'success'       => true,
                'id_order'      => $order->id,
                'shop_message'  => $shop_message,
                'total_to_pay'  => Tools::displayPrice($total_to_pay, $currency),
                'order'         => $order,
                'order_products'=> $products,
                'fee_type'      => $codfeeconf->type,
                'codfee'        => $fee,
                'shop_name'     => Configuration::get('PS_SHOP_NAME'),
                'status'        => 'ok'
            ));
        } else {
            $this->context->smarty->assign(array(
                'success'       => false,
                'status'        => false
            ));
        }

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->display(__FILE__, 'views/templates/hook/payment_return.tpl');
        } elseif (version_compare(_PS_VERSION_, '1.7', '>=')) {
            return $this->display(__FILE__, 'payment_return17.tpl');
        } else {
            return $this->display(__FILE__, 'payment_return.tpl');
        }
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (version_compare(_PS_VERSION_, '1.6', '<') === true) {
            $this->context->controller->setMedia();
            $this->context->controller->addJs($this->_path.'views/js/back.js');
        }
    }

    public function hookHeader($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            Tools::addCSS($this->_path.'views/css/codfee_1.4.css', 'all');
            //Tools::addJS(($this->_path).'views/js/codfee.js');
        } elseif (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->controller->addCSS($this->_path.'views/css/codfee_1.6.css', 'all');
            if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                $this->context->controller->addJS(($this->_path).'views/js/codfee17.js');
            }
            if ($this->isModuleActive('onepagecheckout') || Configuration::get('PS_ORDER_PROCESS_TYPE') == '0') {
                $this->context->controller->addJS(($this->_path).'views/js/codfee16.js');
            }
            if ($this->isModuleActive('advancedeucompliance') && Configuration::get('AEUC_FEAT_ADV_PAYMENT_API') && Configuration::get('AEUC_FEAT_ADV_PAYMENT_API') == '1') {
                $this->context->controller->addJS(($this->_path).'views/js/codfeeUE.js');
            }
        } else {
            $this->context->controller->addCSS($this->_path.'views/css/codfee_1.5.css', 'all');
            //$this->context->controller->addJS(($this->_path).'views/js/codfee.js');
        }
    }

    public function hookPaymentOptions($params)
    {
        return $this->hookPayment($params);
    }

    public function hookPayment($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->hookPayment14($params);
        } elseif (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return $this->hookPayment16($params);
        } else {
            return $this->hookPayment16($params);
        }
    }

    public function hookPayment16($params)
    {
        if (!$this->active || $params['cart']->isVirtualCart()) {
            return false;
        }
        $id_lang = $params['cart']->id_lang;
        $id_shop = $params['cart']->id_shop;
        $customer = new Customer((int)$params['cart']->id_customer);
        $customer_groups = $customer->getGroupsStatic($customer->id);
        $carrier = new Carrier((int)$params['cart']->id_carrier);
        $carrier_ref = $carrier->id_reference;
        $address = new Address((int)$params['cart']->id_address_delivery);
        $country = new Country((int)$address->id_country);
        if ($address->id_state > 0) {
            $zone = State::getIdZone((int)$address->id_state);
        } else {
            $zone = $country->getIdZone((int)$country->id);
        }
        $manufacturers = '';
        $suppliers = '';
        $products = $params['cart']->getProducts();
        foreach ($products as $product) {
            $manufacturers .= $product['id_manufacturer'].';';
            $suppliers .= $product['id_supplier'].';';
        }
        $manufacturers = explode(';', trim($manufacturers, ';'));
        $manufacturers = array_unique($manufacturers, SORT_REGULAR);
        $suppliers = explode(';', trim($suppliers, ';'));
        $suppliers = array_unique($suppliers, SORT_REGULAR);
        $group = new Group((int)$customer->id_default_group);

        if ($group->price_display_method == '1') {
            $price_display_method = false;
        } else {
            $price_display_method = true;
        }

        $order_total = $params['cart']->getOrderTotal($price_display_method, 3);
        $order_total_with_taxes = $params['cart']->getOrderTotal(true, 3);

        $codfeeconfs = new CodfeeConfiguration();
        $codfeeconfs = $codfeeconfs->getFeeConfiguration($id_shop, $id_lang, $customer_groups, $carrier_ref, $country, $zone, $products, $manufacturers, $suppliers, $order_total, true);

        if (!$codfeeconfs) {
            return false;
        }

        $tpl = '';
        $payment_options = array();
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $currency = new Currency($params['cart']->cart->id_currency);
            $conv_rate = (float)$currency->conversion_rate;
        } else {
            $conv_rate = (float)$this->context->currency->conversion_rate;
        }
        foreach ($codfeeconfs as $codfeeconf) {
            if ($codfeeconf['hide_first_order'] == '1') {
                $customer_stats = $customer->getStats();
                if ($customer_stats['nb_orders'] == '0') {
                    return false;
                }
            }
            if ($codfeeconf['only_stock'] == '1') {
                $no_stock = false;
                foreach ($products as $product) {
                    if (StockAvailable::getQuantityAvailableByProduct($product['id_product'], $product['id_product_attribute']) <= 0) {
                        $no_stock = true;
                        break;
                    }
                }
                if ($no_stock) {
                    continue;
                }
            }
            if ($params['cart']->id > 0) {
                $total_weight = $params['cart']->getTotalWeight();
            } else {
                $total_weight = -1;
            }
            if (($codfeeconf['max_weight'] > 0 && $total_weight < $codfeeconf['max_weight']) || ($codfeeconf['max_weight'] == 0)) {
                if (($codfeeconf['min_weight'] > 0 && $codfeeconf['min_weight'] <= $total_weight) || ($codfeeconf['min_weight'] == 0)) {
                } else {
                    continue;
                }
            } else {
                continue;
            }
            $order_max = $codfeeconf['order_max'] * (float)$conv_rate;
            $order_min = $codfeeconf['order_min'] * (float)$conv_rate;
            if (($order_max > 0 && $order_total < $order_max) || ($order_max == 0)) {
                if (($order_min > 0 && $order_min <= $order_total) || ($order_min == 0)) {
                    $fee = (float)Tools::ps_round((float)$this->getFeeCost($params['cart'], $codfeeconf, $price_display_method), 2);
                    if ($codfeeconf['free_on_freeshipping'] == '1' && $params['cart']->getOrderTotal($price_display_method, Cart::ONLY_SHIPPING) == 0) {
                        $fee = (float)0.00;
                    }
                    if ($codfeeconf['free_on_freeshipping'] == '1' && count($params['cart']->getCartRules(CartRule::FILTER_ACTION_SHIPPING)) > 0) {
                        $fee = (float)0.00;
                    }
                    $order_total_withoutshipping = $params['cart']->getOrderTotal($price_display_method, Cart::BOTH_WITHOUT_SHIPPING);
                    $shipping_cost = $order_total - $order_total_withoutshipping;
                    $total = $fee + $order_total;
                    $currency = new Currency($params['cart']->id_currency);
                    $this_path_ssl = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
                    if (file_exists(_PS_TMP_IMG_DIR_.$this->name.'_'.$codfeeconf['id_codfee_configuration'].'.'.$this->imageType)) {
                        $payment_logo_url = $this_path_ssl.'img/tmp/'.$this->name.'_'.$codfeeconf['id_codfee_configuration'].'.'.$this->imageType;
                        if (version_compare(_PS_VERSION_, '1.6', '<')) {
                            $payment_logo_url = $this_path_ssl.'img/tmp/'.$codfeeconf['id_codfee_configuration'].'.'.$this->imageType;
                        }
                    } else {
                        $payment_logo_url = $this_path_ssl.'modules/codfee/views/img/payment.png';
                    }
                    $modulePretty = false;
                    if ($this->isModuleActive('prettyurls') || $this->isModuleActive('purls') || $this->isModuleActive('fsadvancedurl') || $this->isModuleActive('smartseoplus')) {
                        $modulePretty = true;
                    }
                    if ($modulePretty !== false) {
                        $payment_ctrl = $this_path_ssl.'index.php?fc=module&module='.$this->name.'&controller=payment&c='.$codfeeconf['id_codfee_configuration'].'&id_lang='.$id_lang;
                        $validation_ctrl = $this_path_ssl.'index.php?fc=module&module='.$this->name.'&controller=validation&c='.$codfeeconf['id_codfee_configuration'].'&id_lang='.$id_lang;
                        $action = $this_path_ssl.'index.php?fc=module&module='.$this->name.'&controller=validation&c='.$codfeeconf['id_codfee_configuration'].'&id_lang='.$id_lang.'&confirm=1';
                    } else {
                        $payment_ctrl = $this->context->link->getModuleLink($this->name, 'payment', array('c' => $codfeeconf['id_codfee_configuration']), true);
                        $validation_ctrl = $this->context->link->getModuleLink($this->name, 'validation', array('c' => $codfeeconf['id_codfee_configuration']), true);
                        $action = $this->context->link->getModuleLink($this->name, 'validation', array('confirm' => true));
                    }
                    if ($codfeeconf['type'] == '3') {
                        $cta_text = sprintf($this->l('Pay upon cash on pickup'));
                        $label_text = sprintf($this->l('Pay upon cash on pickup'));
                    } else {
                        if ($price_display_method) {
                            $cta_text = sprintf($this->l('Pay with cash on delivery: %s + %s (COD fee) = %s'), Tools::displayPrice($order_total, $currency, false), Tools::displayPrice($fee, $currency, false), Tools::displayPrice($total, $currency, false));
                        } else {
                            $codfee_taxes = 0;
                            $taxes = $params['cart']->getOrderTotal(true) - $params['cart']->getOrderTotal(false);
                            if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                                $carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                                $codfee_taxes = $fee - ($fee / (1 + (($carrier_tax_rate) / 100)));
                            }
                            $fee_wt = $fee - $codfee_taxes;
                            $total = $fee_wt + $order_total;
                            $cta_text = sprintf($this->l('Pay with cash on delivery: %s + %s (COD fee) = %s'), Tools::displayPrice($order_total, $currency, false), Tools::displayPrice($fee_wt, $currency, false), Tools::displayPrice($total, $currency, false));
                        }
                        $label_text = sprintf($this->l('Cash on delivery fee'));
                    }
                    $this->context->smarty->assign(array(
                        'this_path'         => $this->_path,
                        'order_total'       => number_format((float)$order_total, 2, '.', ''),
                        'fee'               => number_format((float)$fee, 2, '.', ''),
                        'shipping_cost'     => number_format((float)$shipping_cost, 2, '.', ''),
                        'total'             => number_format((float)$total, 2, '.', ''),
                        'payment_text'      => preg_replace('/[\n|\r|\n\r]/i', '', $codfeeconf['payment_text']),
                        'payment_logo'      => $payment_logo_url,
                        'label_text'        => $label_text,
                        'cta_text'          => $cta_text,
                        'codfee_id'         => 'codfeeid_'.$codfeeconf['id_codfee_configuration'],
                        'codfeeconf_class'  => 'codfeeconf_'.$codfeeconf['id_codfee_configuration'],
                        'show_conf_page'    => $codfeeconf['show_conf_page'],
                        'payment_size'      => $codfeeconf['payment_size'],
                        'payment_ctrl'      => $payment_ctrl,
                        'validation_ctrl'   => $validation_ctrl,
                        'this_path_ssl'     => $this_path_ssl.'modules/'.$this->name.'/',
                        'action'            => $action
                    ));
                    if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                        $newOption = new \PrestaShop\PrestaShop\Core\Payment\PaymentOption();
                        if ($codfeeconf['show_conf_page'] == '0') {
                            if ($modulePretty !== false) {
                                $action_ctrl = $this_path_ssl.'index.php?fc=module&module='.$this->name.'&controller=validation&c='.$codfeeconf['id_codfee_configuration'].'&id_lang='.$id_lang;
                            } else {
                                $action_ctrl = $this->context->link->getModuleLink($this->name, 'validation', array('c' => $codfeeconf['id_codfee_configuration']), true);
                            }
                        } else {
                            if ($modulePretty !== false) {
                                $action_ctrl = $this_path_ssl.'index.php?fc=module&module='.$this->name.'&controller=payment&c='.$codfeeconf['id_codfee_configuration'].'&id_lang='.$id_lang;
                            } else {
                                $action_ctrl = $this->context->link->getModuleLink($this->name, 'payment', array('c' => $codfeeconf['id_codfee_configuration']), true);
                            }
                        }
                        $codfee_taxes = 0;
                        $taxes = $params['cart']->getOrderTotal(true) - $params['cart']->getOrderTotal(false);
                        if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                            $carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                            $codfee_taxes = $fee - ($fee / (1 + (($carrier_tax_rate) / 100)));
                        }
                        $fee_wt = $fee - $codfee_taxes;
                        $inputs = array();
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_id', 'value' => $codfeeconf['id_codfee_configuration']);
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_base', 'value' => Tools::displayPrice($order_total, $currency, false));
                        if ($price_display_method) {
                            $inputs[] = array('type' => 'hidden', 'name' => 'codfee_fee', 'value' => Tools::displayPrice($fee, $currency, false));
                            $inputs[] = array('type' => 'hidden', 'name' => 'codfee_total', 'value' => Tools::displayPrice($total, $currency, false));
                        } else {
                            $inputs[] = array('type' => 'hidden', 'name' => 'codfee_fee', 'value' => Tools::displayPrice($fee_wt, $currency, false));
                            $inputs[] = array('type' => 'hidden', 'name' => 'codfee_total', 'value' => Tools::displayPrice($fee_wt + $order_total, $currency, false));
                        }
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_total_with_taxes', 'value' => Tools::displayPrice($fee + $order_total_with_taxes, $currency, false));
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_taxes', 'value' => Tools::displayPrice($taxes + $codfee_taxes, $currency, false));
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_fee_wt', 'value' => Tools::displayPrice($fee_wt, $currency, false));
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_price_display_method', 'value' => $price_display_method);
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_text', 'value' => $this->l('Cash on delivery fee'));
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_tax_enabled', 'value' => Configuration::get('PS_TAX'));
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_tax_display', 'value' => Configuration::get('PS_TAX_DISPLAY'));
                        $newOption->setCallToActionText($cta_text)
                                  ->setAdditionalInformation(preg_replace('/[\n|\r|\n\r]/i', '', $codfeeconf['payment_text']))
                                  ->setAction($action_ctrl)
                                  ->setModuleName($this->name)
                                  ->setInputs($inputs);
                        array_push($payment_options, $newOption);
                    }
                    if (Configuration::get('AEUC_FEAT_ADV_PAYMENT_API') && Configuration::get('AEUC_FEAT_ADV_PAYMENT_API') == '1' && $this->isModuleActive('advancedeucompliance')) {
                        if (!in_array('hookAdvancedPaymentOptions', array_column(debug_backtrace(), 'function'))) {
                            continue;
                        }
                        if ($codfeeconf['show_conf_page'] == '1') {
                            $action = $this->context->link->getModuleLink($this->name, 'payment', array('c' => $codfeeconf['id_codfee_configuration']), true);
                        } else {
                            $action = $this->context->link->getModuleLink($this->name, 'validation', array('c' => $codfeeconf['id_codfee_configuration']), true);
                        }
                        $formHtml = '<form method="POST" action="'.$action.'">';
                        $inputs = array();
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_base', 'value' => Tools::displayPrice($order_total, $currency, false));
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_fee', 'value' => Tools::displayPrice($fee, $currency, false));
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_total', 'value' => Tools::displayPrice($total, $currency, false));
                        $inputs[] = array('type' => 'hidden', 'name' => 'codfee_included', 'value' => $this->l('Cash on delivery fee:').' '.Tools::displayPrice($fee, $currency, false));
                        foreach ($inputs as $input) {
                            $formHtml .= '<input type="'.$input['type'].'" name="'.$input['name'].'" value="'.$input['value'].'" />';
                        }
                        $formHtml .= '</form>';
                        $formHtml .= '<script>
                                        $("div#HOOK_ADVANCED_PAYMENT").find("input[name=\"codfee_base\"]").parent().parent().prev().find("img").css("vertical-align", "super");
                                        $("div#HOOK_ADVANCED_PAYMENT").find("input[name=\"codfee_base\"]").parent().parent().prev().find("span.payment_option_cta").css("max-width", "320px").css("display", "inline-block");
                                      </script>';
                        return array(
                            'cta_text' => $cta_text,
                            'logo' => $payment_logo_url,
                            'action' => $action,
                            'form' => $formHtml
                        );
                    }
                    if (version_compare(_PS_VERSION_, '1.7', '<')) {
                        //$tpl .= $this->context->smarty->fetch(dirname(__FILE__).'/views/templates/hook/payment.tpl');
                        $tpl .= $this->display(__FILE__, 'views/templates/hook/payment.tpl');
                    }
                }
            }
        }
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            if (isset($payment_options) && !empty($payment_options)) {
                return $payment_options;
            } else {
                return false;
            }
        } else {
            return $tpl;
        }
    }

    public function hookPayment14($params)
    {
        $minimum_amount = Configuration::get('COD_FEE_MIN_AMOUNT');
        $maximum_amount = Configuration::get('COD_FEE_MAX_AMOUNT');
        $fee = (float)Tools::ps_round((float)$this->getFeeCost($params['cart']), 2);
        $cartcost = $params['cart']->getOrderTotal(true, 3);
        $cartwithoutshipping = $params['cart']->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
        $shippingcost = $cartcost - $cartwithoutshipping;
        $total = $fee + $cartcost;
        $id_carriers_selected_array = explode(';', Configuration::get('COD_FEE_CARRIERS'));
        $carrier_selected = new Carrier($params['cart']->id_carrier);
        $this_path_ssl = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
        $payment_logo_url = $this_path_ssl.'modules/codfee/views/img/payment.png';

        $this->context->smarty->assign(array(
            'this_path'         => $this->_path,
            'cartcost'          => number_format((float)$cartcost, 2, '.', ''),
            'fee'               => number_format((float)$fee, 2, '.', ''),
            'minimum_amount'    => number_format((float)$minimum_amount, 2, '.', ''),
            'maximum_amount'    => number_format((float)$maximum_amount, 2, '.', ''),
            'shippingcost'      => number_format((float)$shippingcost, 2, '.', ''),
            'total'             => number_format((float)$total, 2, '.', ''),
            'show_conf_page'    => Configuration::get('COD_SHOW_CONF'),
            'carriers_array'    => $id_carriers_selected_array,
            'carrier_selected'  => $carrier_selected->id,
            'payment_text'      => '',
            'payment_logo'      => $payment_logo_url,
            'this_path_ssl'     => $this_path_ssl.'modules/'.$this->name.'/'
        ));

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }

    public function hookDisplayPaymentEU($params)
    {
        if (!$this->active) {
            return false;
        }

        if (version_compare(_PS_VERSION_, '1.5', '>=')) {
            return $this->hookDisplayPaymentEU16($params);
        }

        foreach ($params['cart']->getProducts() as $product) {
            $pd = ProductDownload::getIdFromIdProduct((int)($product['id_product']));
            if ($pd && Validate::isUnsignedInt($pd)) {
                return false;
            }
        }

        $minimum_amount = Configuration::get('COD_FEE_MIN_AMOUNT');
        $maximum_amount = Configuration::get('COD_FEE_MAX_AMOUNT');
        $fee = (float)Tools::ps_round((float)$this->getFeeCost($params['cart']), 2);
        $cartcost = $params['cart']->getOrderTotal(true, 3);
        $cartwithoutshipping = $params['cart']->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
        $shippingcost = $cartcost - $cartwithoutshipping;
        $total = $fee + $cartcost;
        $id_carriers_selected_array = explode(';', Configuration::get('COD_FEE_CARRIERS'));
        $carrier_selected = new Carrier($params['cart']->id_carrier);
        $currency = (int)$params['cart']->id_currency;

        return array(
            'this_path' => $this->_path,
            'cartcost' => number_format((float)$cartcost, 2, '.', ''),
            'fee' => number_format((float)$fee, 2, '.', ''),
            'minimum_amount' => number_format((float)$minimum_amount, 2, '.', ''),
            'maximum_amount' => number_format((float)$maximum_amount, 2, '.', ''),
            'shippingcost' => number_format((float)$shippingcost, 2, '.', ''),
            'total' => number_format((float)$total, 2, '.', ''),
            'carriers_array' => $id_carriers_selected_array,
            'carrier_selected' => version_compare(_PS_VERSION_, '1.5', '<') ? $carrier_selected->id : $carrier_selected->id_reference,
            'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/',
            'cta_text' => $this->l('Pay with cash on delivery:').' '.$this->convertSign(Tools::displayPrice($cartcost, $currency, false)).' + '.$this->convertSign(Tools::displayPrice($fee, $currency, false)).' '.$this->l('(COD fee)').' = '.$this->convertSign(Tools::displayPrice($total, $currency, false)),
            'logo' => Media::getMediaPath(dirname(__FILE__).'/img/codfee.gif'),
            'action' => $this->context->link->getModuleLink($this->name, 'validation', array('confirm' => true))
        );
    }

    public function hookDisplayPaymentEU16($params)
    {
        if (!$this->active) {
            return;
        }

        if ($this->hookPayment($params) == null) {
            return null;
        }

        return $this->hookPayment($params);
    }

    /**
    * Displays the COD fee on the invoice (PS 1.4 versions)
    *
    * @param $params contains an instance of OrderInvoice
    * @return string
    *
    */
    public function hookPDFInvoice($params)
    {
        $order = new Order($params['id_order']);
        if ($order->module == 'codfee') {
            $currency = new Currency($order->id_currency);
            $cart = new Cart($order->id_cart);
            $codfee = number_format((float)$this->getFeeCost($cart), 2, '.', '');
            if ($codfee > 0) {
                $pdf = $params['pdf'];
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(0, 40, utf8_decode($this->l('Cash on delivery fee applied:')).' '.$this->convertSign(Tools::displayPrice($codfee, $currency, false)), 0, 0, 'C');
                $pdf->Ln(5);
            }
        }
    }

    /**
    * Displays the COD fee on the invoice (PS 1.5+ versions)
    *
    * @param $params contains an instance of OrderInvoice
    * @return string
    *
    */
    public function hookDisplayPDFInvoice($params)
    {
        $order_invoice = $params['object'];
        if (!($order_invoice instanceof OrderInvoice)) {
            return;
        }
        $order = new Order((int)$order_invoice->id_order);
        $return = '';
        if ($order->module == 'codfee') {
            $codfee = $this->getFeeFromOrderId($order->id);
            $currency = new Currency($order->id_currency);
            if ($codfee > 0) {
                $use_taxes = Configuration::get('PS_TAX');
                $return = sprintf($this->l('Cash on delivery fee applied:').' '.Tools::displayPrice($codfee, $currency, false));
                if ($use_taxes) {
                    $codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
                    $return .= ' '.sprintf($this->l('(%s of tax included)'), Tools::displayPrice($codfee - $codfee_wt, $currency, false));
                }
            }
        }
        return $return;
    }

    public function hookDisplayOrderDetail($params)
    {
        $order = $params['order'];
        if (!($order instanceof Order)) {
            return '';
        }
        $codfee = $this->getFeeFromOrderId($order->id);
        $currency = new Currency($order->id_currency);
        $return = '';
        if ($order->module == 'codfee' && $codfee > 0) {
            $return = sprintf('<p class="dark"><strong>'.$this->l('This order has a cash on delivery fee applied of %s'), Tools::displayPrice($codfee, $currency, false).'</p></strong>');
        }
        return $return;
    }

    public function hookDisplayAdminOrder($params)
    {
        $order = new Order($params['id_order']);
        if (!($order instanceof Order)) {
            return;
        }
        $codfee = $this->getFeeFromOrderId($order->id);
        $currency = new Currency($order->id_currency);
        $return = '';
        if ($order->module == 'codfee' && $codfee > 0) {
            $return = sprintf('<script language="JavaScript">$(document).ready(function() {$("tr#total_shipping").children().next("td.amount").html("'.Tools::displayPrice($order->total_shipping_tax_incl, $currency, false)).'" + " ('.sprintf($this->l('COD fee applied %s'), Tools::displayPrice($codfee, $currency, false)).')")});</script>';
        }
        return $return;
    }

    public function hookOrderDetailDisplayed($params)
    {
        $order = $params['order'];
        if (!($order instanceof Order)) {
            return;
        }
        $currency = new Currency($order->id_currency);
        $cart = new Cart($order->id_cart);
        $codfee = number_format((float)$this->getFeeCost($cart), 2, '.', '');
        $return = '';
        if ($order->module == 'codfee' && $codfee > 0) {
            $return = sprintf('<p class="dark"><strong>'.$this->l('This order has a cash on delivery fee applied of %s'), Tools::displayPrice($codfee, $currency, false).'</p></strong>');
        }
        return $return;
    }

    public function hookAdminOrder($params)
    {
        $order = new Order($params['id_order']);
        if (!($order instanceof Order)) {
            return;
        }
        $currency = new Currency($order->id_currency);
        $cart = new Cart($order->id_cart);
        $codfee = number_format((float)$this->getFeeCost($cart), 2, '.', '');
        $return = '';
        if ($order->module == 'codfee' && $codfee > 0) {
            $return = sprintf('<script language="JavaScript">$(document).ready(function() {$("tr#total_shipping").children().next("td.amount").html("'.Tools::displayPrice($order->total_shipping, $currency, false).'" + " ('.$this->l('COD fee applied %s'), Tools::displayPrice($codfee, $currency, false).')")});</script>');
        }
        return $return;
    }

    public function hookDisplayLeftColumnProduct()
    {
        return false;
        //return $this->hookDisplayRightColumnProduct();
    }

    //PS17
    public function hookDisplayProductAdditionalInfo()
    {
        return $this->hookDisplayRightColumnProduct();
    }

    public function hookDisplayRightColumnProduct()
    {
        $product = new Product((int)Tools::getValue('id_product'));
        if (!$this->active ||
            $product->is_virtual) {
            return '';
        }
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;
        $customer = new Customer((int)$this->context->customer->id);
        if ($customer->id) {
            $customer_groups = $customer->getGroupsStatic($customer->id);
        } else {
            $current_group = GroupCore::getCurrent();
            $customer_groups = array($current_group->id);
        }
        $cart = $this->context->cart;
        $codfeeconfs = new CodfeeConfiguration();
        $products = array(array('id_product' => $product->id));
        $manufacturers = array($product->id_manufacturer);
        $suppliers = array($product->id_supplier);
        if ($cart->id) {
            $carrier = new Carrier($cart->id_carrier);
            $carrier = $carrier->id_reference;
            $address = new Address($cart->id_address_delivery);
            $country = new Country((int)$address->id_country);
            if ($address->id_state > 0) {
                $zone = State::getIdZone((int)$address->id_state);
            } else {
                $zone = $country->getIdZone((int)$country->id);
            }
            $order_total = $cart->getOrderTotal(true, 3);
            $codfeeconfs = $codfeeconfs->getFeeConfiguration($id_shop, $id_lang, $customer_groups, $carrier, $country, $zone, $products, $manufacturers, $suppliers, $order_total,true);
        } else {
            $country = new Country((int)$this->context->country->id);
            $zone = $country->getIdZone((int)$country->id);
            $order_total = 0;
            $codfeeconfs = $codfeeconfs->getFeeConfiguration($id_shop, $id_lang, $customer_groups, false, $country, $zone, $products, $manufacturers, $suppliers, $order_total,true);
        }
        if (!$codfeeconfs) {
            return '';
        } else {
            foreach ($codfeeconfs as $codfeeconf) {
                if ($codfeeconf['show_productpage'] == '1') {
                    $product_price = $product->getPrice();
                    if ($product_price > (float)$codfeeconf['order_max'] && (float)$codfeeconf['order_max'] > 0) {
                        return '';
                    }
                    $this_path_ssl = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
                    if (file_exists(_PS_TMP_IMG_DIR_.$this->name.'_'.$codfeeconf['id_codfee_configuration'].'.'.$this->imageType)) {
                        $icon_logo_url = $this_path_ssl.'img/tmp/'.$this->name.'_'.$codfeeconf['id_codfee_configuration'].'.'.$this->imageType;
                        if (version_compare(_PS_VERSION_, '1.6', '<')) {
                            $icon_logo_url = $this_path_ssl.'img/tmp/'.$codfeeconf['id_codfee_configuration'].'.'.$this->imageType;
                        }
                    } else {
                        $icon_logo_url = $this_path_ssl.'modules/codfee/views/img/product_icon.png';
                    }
                    $this->context->smarty->assign(array(
                        'icon' => $icon_logo_url,
                        'codfee_type' => $codfeeconf['type'],
                    ));

                    return $this->display(__FILE__, 'product.tpl');
                }
            }
        }
        return '';
    }

    public function getFeeAmountFromOrderId($id_order)
    {
        return Db::getInstance()->getValue(
            'SELECT `codfee`
            FROM `'._DB_PREFIX_.'orders` o
            WHERE o.`id_order` = '.(int)$id_order.';'
        );
    }

    public function validateOrder174(
        $id_cart,
        $id_order_state,
        $amount_paid,
        $codfee,
        $payment_method = 'Unknown',
        $message = null,
        $extra_vars = array(),
        $currency_special = null,
        $dont_touch_amount = false,
        $secure_key = false,
        Shop $shop = null
    ) {
        if (self::DEBUG_MODE) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Function called', 1, null, 'Cart', (int)$id_cart, true);
        }

        if (!isset($this->context)) {
            $this->context = Context::getContext();
        }
        $this->context->cart = new Cart((int)$id_cart);
        $this->context->customer = new Customer((int)$this->context->cart->id_customer);
        // The tax cart is loaded before the customer so re-cache the tax calculation method
        $this->context->cart->setTaxCalculationMethod();

        $this->context->language = new Language((int)$this->context->cart->id_lang);
        $this->context->shop = ($shop ? $shop : new Shop((int)$this->context->cart->id_shop));
        ShopUrl::resetMainDomainCache();
        $id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
        $this->context->currency = new Currency((int)$id_currency, null, (int)$this->context->shop->id);
        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
            $context_country = $this->context->country;
        }

        $order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
        if (!Validate::isLoadedObject($order_status)) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Order Status cannot be loaded', 3, null, 'Cart', (int)$id_cart, true);
            throw new PrestaShopException('Can\'t load Order status');
        }

        if (!$this->active) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Module is not active', 3, null, 'Cart', (int)$id_cart, true);
            die(Tools::displayError());
        }

        // Does order already exists ?
        if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $this->context->cart->secure_key) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Secure key does not match', 3, null, 'Cart', (int)$id_cart, true);
                die(Tools::displayError());
            }

            // For each package, generate an order
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
            $package_list = $this->context->cart->getPackageList();
            $cart_delivery_option = $this->context->cart->getDeliveryOption();

            // If some delivery options are not defined, or not valid, use the first valid option
            foreach ($delivery_option_list as $id_address => $package) {
                if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package)) {
                    foreach ($package as $key => $val) {
                        $cart_delivery_option[$id_address] = $key;
                        break;
                    }
                }
            }

            $order_list = array();
            $order_detail_list = array();

            do {
                $reference = Order::generateReference();
            } while (Order::getByReference($reference)->count());

            $this->currentOrderReference = $reference;

            $cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH) + $codfee, 2);

            foreach ($cart_delivery_option as $id_address => $key_carriers) {
                foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data) {
                    foreach ($data['package_list'] as $id_package) {
                        // Rewrite the id_warehouse
                        $package_list[$id_address][$id_package]['id_warehouse'] = (int)$this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int)$id_carrier);
                        $package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
                    }
                }
            }
            // Make sure CartRule caches are empty
            CartRule::cleanCache();
            $cart_rules = $this->context->cart->getCartRules();
            foreach ($cart_rules as $cart_rule) {
                if (($rule = new CartRule((int)$cart_rule['obj']->id)) && Validate::isLoadedObject($rule)) {
                    if ($error = $rule->checkValidity($this->context, true, true)) {
                        $this->context->cart->removeCartRule((int)$rule->id);
                        if (isset($this->context->cookie) && isset($this->context->cookie->id_customer) && $this->context->cookie->id_customer && !empty($rule->code)) {
                            Tools::redirect('index.php?controller=order&submitAddDiscount=1&discount_name='.urlencode($rule->code));
                        } else {
                            $rule_name = isset($rule->name[(int)$this->context->cart->id_lang]) ? $rule->name[(int)$this->context->cart->id_lang] : $rule->code;
                            $error = $this->trans('The cart rule named "%1s" (ID %2s) used in this cart is not valid and has been withdrawn from cart', array($rule_name, (int)$rule->id), 'Admin.Payment.Notification');
                            PrestaShopLogger::addLog($error, 3, '0000002', 'Cart', (int)$this->context->cart->id);
                        }
                    }
                }
            }

            foreach ($package_list as $id_address => $packageByAddress) {
                foreach ($packageByAddress as $id_package => $package) {
                    /** @var Order $order */
                    $order = new Order();
                    $order->product_list = $package['product_list'];

                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $address = new Address((int)$id_address);
                        $this->context->country = new Country((int)$address->id_country, (int)$this->context->cart->id_lang);
                        if (!$this->context->country->active) {
                            throw new PrestaShopException('The delivery address country is not active.');
                        }
                    }

                    $carrier = null;
                    if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier'])) {
                        $carrier = new Carrier((int)$package['id_carrier'], (int)$this->context->cart->id_lang);
                        $order->id_carrier = (int)$carrier->id;
                        $id_carrier = (int)$carrier->id;
                    } else {
                        $order->id_carrier = 0;
                        $id_carrier = 0;
                    }

                    $order->id_customer = (int)$this->context->cart->id_customer;
                    $order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
                    $order->id_address_delivery = (int)$id_address;
                    $order->id_currency = $this->context->currency->id;
                    $order->id_lang = (int)$this->context->cart->id_lang;
                    $order->id_cart = (int)$this->context->cart->id;
                    $order->reference = $reference;
                    $order->id_shop = (int)$this->context->shop->id;
                    $order->id_shop_group = (int)$this->context->shop->id_shop_group;

                    $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
                    $order->payment = $payment_method;
                    if (isset($this->name)) {
                        $order->module = $this->name;
                    }
                    $order->recyclable = $this->context->cart->recyclable;
                    $order->gift = (int)$this->context->cart->gift;
                    $order->gift_message = $this->context->cart->gift_message;
                    $order->mobile_theme = $this->context->cart->mobile_theme;
                    $order->conversion_rate = $this->context->currency->conversion_rate;
                    $amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
                    $order->total_paid_real = 0;

                    $order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts = $order->total_discounts_tax_incl;

                    $order->total_shipping_tax_excl = (float)$this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list);
                    $order->total_shipping_tax_incl = (float)$this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list);
                    $order->total_shipping = $order->total_shipping_tax_incl;

                    $codfee_wt = 0;
                    if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                        $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                        $codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
                    }

                    $order->total_shipping_tax_excl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list) + $codfee_wt), 4);
                    $order->total_shipping_tax_incl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list) + $codfee), 4);
                    $order->total_shipping = $order->total_shipping_tax_incl;

                    $order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping = $order->total_wrapping_tax_incl;

                    $order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier) + $codfee_wt, _PS_PRICE_COMPUTE_PRECISION_);
                    $order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier) + $codfee, _PS_PRICE_COMPUTE_PRECISION_);
                    $order->total_paid = $order->total_paid_tax_incl;
                    $order->round_mode = Configuration::get('PS_PRICE_ROUND_MODE');
                    $order->round_type = Configuration::get('PS_ROUND_TYPE');
                    $order->invoice_date = '0000-00-00 00:00:00';
                    $order->delivery_date = '0000-00-00 00:00:00';
                    $order->codfee = $codfee;

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Creating order
                    $result = $order->add();

                    if (!$result) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order cannot be created', 3, null, 'Cart', (int)$id_cart, true);
                        throw new PrestaShopException('Can\'t save Order');
                    }

                    // Amount paid by customer is not the right one -> Status = payment error
                    // We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
                    // if ($order->total_paid != $order->total_paid_real)
                    // We use number_format in order to compare two string
                    if ($order_status->logable && number_format($cart_total_paid, _PS_PRICE_COMPUTE_PRECISION_) != number_format($amount_paid, _PS_PRICE_COMPUTE_PRECISION_)) {
                        $id_order_state = Configuration::get('PS_OS_ERROR');
                    }

                    $order_list[] = $order;

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - OrderDetail is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Insert new Order detail list using cart for the current order
                    $order_detail = new OrderDetail(null, null, $this->context);
                    $order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
                    $order_detail_list[] = $order_detail;

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - OrderCarrier is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Adding an entry in order_carrier table
                    if (!is_null($carrier)) {
                        $order_carrier = new OrderCarrier();
                        $order_carrier->id_order = (int)$order->id;
                        $order_carrier->id_carrier = (int)$id_carrier;
                        $order_carrier->weight = (float)$order->getTotalWeight();
                        $order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
                        $order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
                        $order_carrier->add();
                    }
                }
            }

            // The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                $this->context->country = $context_country;
            }

            if (!$this->context->country->active) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Country is not active', 3, null, 'Cart', (int)$id_cart, true);
                throw new PrestaShopException('The order address country is not active.');
            }

            if (self::DEBUG_MODE) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Payment is about to be added', 1, null, 'Cart', (int)$id_cart, true);
            }

            // Register Payment only if the order status validate the order
            if ($order_status->logable) {
                // $order is the last order loop in the foreach
                // The method addOrderPayment of the class Order make a create a paymentOrder
                // linked to the order reference and not to the order id
                if (isset($extra_vars['transaction_id'])) {
                    $transaction_id = $extra_vars['transaction_id'];
                } else {
                    $transaction_id = null;
                }

                if (!$order->addOrderPayment($amount_paid, null, $transaction_id)) {
                    PrestaShopLogger::addLog('PaymentModule::validateOrder - Cannot save Order Payment', 3, null, 'Cart', (int)$id_cart, true);
                    throw new PrestaShopException('Can\'t save Order Payment');
                }
            }

            // Next !
            $only_one_gift = false;
            $cart_rule_used = array();
            $products = $this->context->cart->getProducts();

            // Make sure CartRule caches are empty
            CartRule::cleanCache();
            foreach ($order_detail_list as $key => $order_detail) {
                /** @var OrderDetail $order_detail */

                $order = $order_list[$key];
                if (isset($order->id)) {
                    if (!$secure_key) {
                        $message .= '<br />'.$this->trans('Warning: the secure key is empty, check your payment account before validation', array(), 'Admin.Payment.Notification');
                    }
                    // Optional message to attach to this order
                    if (isset($message) & !empty($message)) {
                        $msg = new Message();
                        $message = strip_tags($message, '<br>');
                        if (Validate::isCleanHtml($message)) {
                            if (self::DEBUG_MODE) {
                                PrestaShopLogger::addLog('PaymentModule::validateOrder - Message is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                            }
                            $msg->message = $message;
                            $msg->id_cart = (int)$id_cart;
                            $msg->id_customer = (int)($order->id_customer);
                            $msg->id_order = (int)$order->id;
                            $msg->private = 1;
                            $msg->add();
                        }
                    }

                    // Insert new Order detail list using cart for the current order
                    //$orderDetail = new OrderDetail(null, null, $this->context);
                    //$orderDetail->createList($order, $this->context->cart, $id_order_state);

                    // Construct order detail table for the email
                    $products_list = '';
                    $virtual_product = true;

                    $product_var_tpl_list = array();
                    foreach ($order->product_list as $product) {
                        $price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}, $specific_price, true, true, null, true, $product['id_customization']);
                        $price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}, $specific_price, true, true, null, true, $product['id_customization']);

                        $product_price = Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt;

                        $product_var_tpl = array(
                            'id_product' => $product['id_product'],
                            'reference' => $product['reference'],
                            'name' => $product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : ''),
                            'price' => Tools::displayPrice($product_price * $product['quantity'], $this->context->currency, false),
                            'quantity' => $product['quantity'],
                            'customization' => array()
                        );

                        if (isset($product['price']) && $product['price']) {
                            $product_var_tpl['unit_price'] = Tools::displayPrice($product_price, $this->context->currency, false);
                            $product_var_tpl['unit_price_full'] = Tools::displayPrice($product_price, $this->context->currency, false)
                                .' '.$product['unity'];
                        } else {
                            $product_var_tpl['unit_price'] = $product_var_tpl['unit_price_full'] = '';
                        }

                        $customized_datas = Product::getAllCustomizedDatas((int)$order->id_cart, null, true, null, (int)$product['id_customization']);
                        if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                            $product_var_tpl['customization'] = array();
                            foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']][$order->id_address_delivery] as $customization) {
                                $customization_text = '';
                                if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD])) {
                                    foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text) {
                                        $customization_text .= '<strong>'.$text['name'].'</strong>: '.$text['value'].'<br />';
                                    }
                                }

                                if (isset($customization['datas'][Product::CUSTOMIZE_FILE])) {
                                    $customization_text .= $this->trans('%d image(s)', array(count($customization['datas'][Product::CUSTOMIZE_FILE])), 'Admin.Payment.Notification').'<br />';
                                }

                                $customization_quantity = (int)$customization['quantity'];

                                $product_var_tpl['customization'][] = array(
                                    'customization_text' => $customization_text,
                                    'customization_quantity' => $customization_quantity,
                                    'quantity' => Tools::displayPrice($customization_quantity * $product_price, $this->context->currency, false)
                                );
                            }
                        }

                        $product_var_tpl_list[] = $product_var_tpl;
                        // Check if is not a virutal product for the displaying of shipping
                        if (!$product['is_virtual']) {
                            $virtual_product &= false;
                        }
                    } // end foreach ($products)

                    $product_list_txt = '';
                    $product_list_html = '';
                    if (count($product_var_tpl_list) > 0) {
                        $product_list_txt = $this->getEmailTemplateContent('order_conf_product_list.txt', Mail::TYPE_TEXT, $product_var_tpl_list);
                        $product_list_html = $this->getEmailTemplateContent('order_conf_product_list.tpl', Mail::TYPE_HTML, $product_var_tpl_list);
                    }

                    $cart_rules_list = array();
                    $total_reduction_value_ti = 0;
                    $total_reduction_value_tex = 0;
                    foreach ($cart_rules as $cart_rule) {
                        $package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);

                        $values = array(
                            'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package) + ($cart_rule['obj']->free_shipping == 1 ? $codfee : 0),
                            'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package) + ($cart_rule['obj']->free_shipping == 1 ? $codfee_wt : 0)
                        );

                        // If the reduction is not applicable to this order, then continue with the next one
                        if (!$values['tax_excl']) {
                            continue;
                        }

                        // IF
                        //  This is not multi-shipping
                        //  The value of the voucher is greater than the total of the order
                        //  Partial use is allowed
                        //  This is an "amount" reduction, not a reduction in % or a gift
                        // THEN
                        //  The voucher is cloned with a new value corresponding to the remainder
                        if (count($order_list) == 1 && $values['tax_incl'] > ($order->total_products_wt - $total_reduction_value_ti) && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0) {
                            // Create a new voucher from the original
                            $voucher = new CartRule((int)$cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
                            unset($voucher->id);

                            // Set a new voucher code
                            $voucher->code = empty($voucher->code) ? substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
                            if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2]) {
                                $voucher->code = preg_replace('/'.$matches[0].'$/', '-'.(intval($matches[1]) + 1), $voucher->code);
                            }

                            // Set the new voucher value
                            if ($voucher->reduction_tax) {
                                $voucher->reduction_amount = ($total_reduction_value_ti + $values['tax_incl']) - $order->total_products_wt;

                                // Add total shipping amout only if reduction amount > total shipping
                                if ($voucher->free_shipping == 1 && $voucher->reduction_amount >= $order->total_shipping_tax_incl) {
                                    $voucher->reduction_amount -= $order->total_shipping_tax_incl;
                                }
                            } else {
                                $voucher->reduction_amount = ($total_reduction_value_tex + $values['tax_excl']) - $order->total_products;

                                // Add total shipping amout only if reduction amount > total shipping
                                if ($voucher->free_shipping == 1 && $voucher->reduction_amount >= $order->total_shipping_tax_excl) {
                                    $voucher->reduction_amount -= $order->total_shipping_tax_excl;
                                }
                            }
                            if ($voucher->reduction_amount <= 0) {
                                continue;
                            }

                            if ($this->context->customer->isGuest()) {
                                $voucher->id_customer = 0;
                            } else {
                                $voucher->id_customer = $order->id_customer;
                            }

                            $voucher->quantity = 1;
                            $voucher->reduction_currency = $order->id_currency;
                            $voucher->quantity_per_user = 1;
                            if ($voucher->add()) {
                                // If the voucher has conditions, they are now copied to the new voucher
                                CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);
                                $orderLanguage = new Language((int) $order->id_lang);

                                $params = array(
                                    '{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
                                    '{voucher_num}' => $voucher->code,
                                    '{firstname}' => $this->context->customer->firstname,
                                    '{lastname}' => $this->context->customer->lastname,
                                    '{id_order}' => $order->reference,
                                    '{order_name}' => $order->getUniqReference()
                                );
                                Mail::Send(
                                    (int)$order->id_lang,
                                    'voucher',
                                    Context::getContext()->getTranslator()->trans(
                                        'New voucher for your order %s',
                                        array($order->reference),
                                        'Emails.Subject',
                                        $orderLanguage->locale
                                    ),
                                    $params,
                                    $this->context->customer->email,
                                    $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                    null, null, null, null, _PS_MAIL_DIR_, false, (int)$order->id_shop
                                );
                            }

                            $values['tax_incl'] = $order->total_products_wt - $total_reduction_value_ti;
                            $values['tax_excl'] = $order->total_products - $total_reduction_value_tex;
                            if (1 == $voucher->free_shipping) {
                                 $values['tax_incl'] += $order->total_shipping_tax_incl;
                                 $values['tax_excl'] += $order->total_shipping_tax_excl;  
                            }
                        }
                        $total_reduction_value_ti += $values['tax_incl'];
                        $total_reduction_value_tex += $values['tax_excl'];

                        $order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values, 0, $cart_rule['obj']->free_shipping);

                        if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used)) {
                            $cart_rule_used[] = $cart_rule['obj']->id;

                            // Create a new instance of Cart Rule without id_lang, in order to update its quantity
                            $cart_rule_to_update = new CartRule((int)$cart_rule['obj']->id);
                            $cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
                            $cart_rule_to_update->update();
                        }

                        $cart_rules_list[] = array(
                            'voucher_name' => $cart_rule['obj']->name,
                            'voucher_reduction' => ($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false)
                        );
                    }

                    $cart_rules_list_txt = '';
                    $cart_rules_list_html = '';
                    if (count($cart_rules_list) > 0) {
                        $cart_rules_list_txt = $this->getEmailTemplateContent('order_conf_cart_rules.txt', Mail::TYPE_TEXT, $cart_rules_list);
                        $cart_rules_list_html = $this->getEmailTemplateContent('order_conf_cart_rules.tpl', Mail::TYPE_HTML, $cart_rules_list);
                    }

                    // Specify order id for message
                    $old_message = Message::getMessageByCartId((int)$this->context->cart->id);
                    if ($old_message && !$old_message['private']) {
                        $update_message = new Message((int)$old_message['id_message']);
                        $update_message->id_order = (int)$order->id;
                        $update_message->update();

                        // Add this message in the customer thread
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int)$order->id_customer;
                        $customer_thread->id_shop = (int)$this->context->shop->id;
                        $customer_thread->id_order = (int)$order->id;
                        $customer_thread->id_lang = (int)$this->context->language->id;
                        $customer_thread->email = $this->context->customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();

                        $customer_message = new CustomerMessage();
                        $customer_message->id_customer_thread = $customer_thread->id;
                        $customer_message->id_employee = 0;
                        $customer_message->message = $update_message->message;
                        $customer_message->private = 1;

                        if (!$customer_message->add()) {
                            $this->errors[] = $this->trans('An error occurred while saving message', array(), 'Admin.Payment.Notification');
                        }
                    }

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Hook validateOrder is about to be called', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Hook validate order
                    Hook::exec('actionValidateOrder', array(
                        'cart' => $this->context->cart,
                        'order' => $order,
                        'customer' => $this->context->customer,
                        'currency' => $this->context->currency,
                        'orderStatus' => $order_status
                    ));

                    foreach ($this->context->cart->getProducts() as $product) {
                        if ($order_status->logable) {
                            ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);
                        }
                    }

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order Status is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Set the order status
                    $new_history = new OrderHistory();
                    $new_history->id_order = (int)$order->id;
                    $new_history->changeIdOrderState((int)$id_order_state, $order, true);
                    $new_history->addWithemail(true, $extra_vars);

                    // Switch to back order if needed
                    if (Configuration::get('PS_STOCK_MANAGEMENT') && 
                            ($order_detail->getStockState() || 
                            $order_detail->product_quantity_in_stock < 0)) {
                        $history = new OrderHistory();
                        $history->id_order = (int)$order->id;
                        $history->changeIdOrderState(Configuration::get($order->valid ? 'PS_OS_OUTOFSTOCK_PAID' : 'PS_OS_OUTOFSTOCK_UNPAID'), $order, true);
                        $history->addWithemail();
                    }

                    unset($order_detail);

                    // Order is reloaded because the status just changed
                    $order = new Order((int)$order->id);

                    // Send an e-mail to customer (one order = one email)
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id) {
                        $invoice = new Address((int)$order->id_address_invoice);
                        $delivery = new Address((int)$order->id_address_delivery);
                        $delivery_state = $delivery->id_state ? new State((int)$delivery->id_state) : false;
                        $invoice_state = $invoice->id_state ? new State((int)$invoice->id_state) : false;

                        $data = array(
                        '{firstname}' => $this->context->customer->firstname,
                        '{lastname}' => $this->context->customer->lastname,
                        '{email}' => $this->context->customer->email,
                        '{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
                        '{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
                        '{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array(
                            'firstname'    => '<span style="font-weight:bold;">%s</span>',
                            'lastname'    => '<span style="font-weight:bold;">%s</span>'
                        )),
                        '{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array(
                                'firstname'    => '<span style="font-weight:bold;">%s</span>',
                                'lastname'    => '<span style="font-weight:bold;">%s</span>'
                        )),
                        '{delivery_company}' => $delivery->company,
                        '{delivery_firstname}' => $delivery->firstname,
                        '{delivery_lastname}' => $delivery->lastname,
                        '{delivery_address1}' => $delivery->address1,
                        '{delivery_address2}' => $delivery->address2,
                        '{delivery_city}' => $delivery->city,
                        '{delivery_postal_code}' => $delivery->postcode,
                        '{delivery_country}' => $delivery->country,
                        '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                        '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
                        '{delivery_other}' => $delivery->other,
                        '{invoice_company}' => $invoice->company,
                        '{invoice_vat_number}' => $invoice->vat_number,
                        '{invoice_firstname}' => $invoice->firstname,
                        '{invoice_lastname}' => $invoice->lastname,
                        '{invoice_address2}' => $invoice->address2,
                        '{invoice_address1}' => $invoice->address1,
                        '{invoice_city}' => $invoice->city,
                        '{invoice_postal_code}' => $invoice->postcode,
                        '{invoice_country}' => $invoice->country,
                        '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                        '{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
                        '{invoice_other}' => $invoice->other,
                        '{order_name}' => $order->getUniqReference(),
                        '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), null, 1),
                        '{carrier}' => ($virtual_product || !isset($carrier->name)) ? $this->trans('No carrier', array(), 'Admin.Payment.Notification') : $carrier->name,
                        '{payment}' => Tools::substr($order->payment, 0, 255),
                        '{products}' => $product_list_html,
                        '{products_txt}' => $product_list_txt,
                        '{discounts}' => $cart_rules_list_html,
                        '{discounts_txt}' => $cart_rules_list_txt,
                        '{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
                        '{total_products}' => Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? $order->total_products : $order->total_products_wt, $this->context->currency, false),
                        '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
                        '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false).'<br />'.sprintf($this->l('COD fee included:').' '.Tools::displayPrice($codfee, $this->context->currency, false)),
                        '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false),
                        '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false));

                        if (is_array($extra_vars)) {
                            $data = array_merge($data, $extra_vars);
                        }

                        // Join PDF invoice
                        if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number) {
                            $order_invoice_list = $order->getInvoicesCollection();
                            Hook::exec('actionPDFInvoiceRender', array('order_invoice_list' => $order_invoice_list));
                            $pdf = new PDF($order_invoice_list, PDF::TEMPLATE_INVOICE, $this->context->smarty);
                            $file_attachement['content'] = $pdf->render(false);
                            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang, null, $order->id_shop).sprintf('%06d', $order->invoice_number).'.pdf';
                            $file_attachement['mime'] = 'application/pdf';
                        } else {
                            $file_attachement = null;
                        }

                        if (self::DEBUG_MODE) {
                            PrestaShopLogger::addLog('PaymentModule::validateOrder - Mail is about to be sent', 1, null, 'Cart', (int)$id_cart, true);
                        }

                        $orderLanguage = new Language((int) $order->id_lang);

                        if (Validate::isEmail($this->context->customer->email)) {
                            Mail::Send(
                                (int)$order->id_lang,
                                'order_conf',
                                Context::getContext()->getTranslator()->trans(
                                    'Order confirmation',
                                    array(),
                                    'Emails.Subject',
                                    $orderLanguage->locale
                                ),
                                $data,
                                $this->context->customer->email,
                                $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                null,
                                null,
                                $file_attachement,
                                null, _PS_MAIL_DIR_, false, (int)$order->id_shop
                            );
                        }
                    }

                    // updates stock in shops
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();
                        foreach ($product_list as $product) {
                            // if the available quantities depends on the physical stock
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                // synchronizes
                                StockAvailable::synchronize($product['product_id'], $order->id_shop);
                            }
                        }
                    }

                    $order->updateOrderDetailTax();

                    // sync all stock
                    (new \PrestaShop\PrestaShop\Adapter\StockManager())->updatePhysicalProductQuantity(
                        (int)$order->id_shop,
                        (int)Configuration::get('PS_OS_ERROR'),
                        (int)Configuration::get('PS_OS_CANCELED'),
                        null,
                        (int)$order->id
                    );
                } else {
                    $error = $this->trans('Order creation failed', array(), 'Admin.Payment.Notification');
                    PrestaShopLogger::addLog($error, 4, '0000002', 'Cart', intval($order->id_cart));
                    die($error);
                }
            } // End foreach $order_detail_list

            // Use the last order as currentOrder
            if (isset($order) && $order->id) {
                $this->currentOrder = (int)$order->id;
            }

            if (self::DEBUG_MODE) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - End of validateOrder', 1, null, 'Cart', (int)$id_cart, true);
            }

            return true;
        } else {
            $error = $this->trans('Cart cannot be loaded or an order has already been placed using this cart', array(), 'Admin.Payment.Notification');
            PrestaShopLogger::addLog($error, 4, '0000001', 'Cart', intval($this->context->cart->id));
            die($error);
        }
    }

    public function validateOrder17($id_cart, $id_order_state, $amount_paid, $codfee, $payment_method = 'Unknown', $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null)
    {
        if (self::DEBUG_MODE) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Function called', 1, null, 'Cart', (int)$id_cart, true);
        }

        if (!isset($this->context)) {
            $this->context = Context::getContext();
        }

        smartyRegisterFunction($this->context->smarty, 'function', 'displayPrice', array('Tools', 'displayPriceSmarty'));

        $this->context->cart = new Cart((int)$id_cart);
        $this->context->customer = new Customer((int)$this->context->cart->id_customer);
        // The tax cart is loaded before the customer so re-cache the tax calculation method
        $this->context->cart->setTaxCalculationMethod();

        $this->context->language = new Language((int)$this->context->cart->id_lang);
        $this->context->shop = ($shop ? $shop : new Shop((int)$this->context->cart->id_shop));
        ShopUrl::resetMainDomainCache();
        $id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
        $this->context->currency = new Currency((int)$id_currency, null, (int)$this->context->shop->id);
        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
            $context_country = $this->context->country;
        }

        $order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
        if (!Validate::isLoadedObject($order_status)) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Order Status cannot be loaded', 3, null, 'Cart', (int)$id_cart, true);
            throw new PrestaShopException('Can\'t load Order status');
        }

        if (!$this->active) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Module is not active', 3, null, 'Cart', (int)$id_cart, true);
            die(Tools::displayError());
        }

        // Does order already exists ?
        if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $this->context->cart->secure_key) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Secure key does not match', 3, null, 'Cart', (int)$id_cart, true);
                die(Tools::displayError());
            }

            // For each package, generate an order
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
            $package_list = $this->context->cart->getPackageList();
            $cart_delivery_option = $this->context->cart->getDeliveryOption();

            // If some delivery options are not defined, or not valid, use the first valid option
            foreach ($delivery_option_list as $id_address => $package) {
                if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package)) {
                    foreach ($package as $key => $val) {
                        $cart_delivery_option[$id_address] = $key;
                        break;
                    }
                }
            }

            $order_list = array();
            $order_detail_list = array();

            do {
                $reference = Order::generateReference();
            } while (Order::getByReference($reference)->count());

            $this->currentOrderReference = $reference;

            $cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH) + $codfee, 2);

            foreach ($cart_delivery_option as $id_address => $key_carriers) {
                foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data) {
                    foreach ($data['package_list'] as $id_package) {
                        // Rewrite the id_warehouse
                        $package_list[$id_address][$id_package]['id_warehouse'] = (int)$this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int)$id_carrier);
                        $package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
                    }
                }
            }
            // Make sure CartRule caches are empty
            CartRule::cleanCache();
            $cart_rules = $this->context->cart->getCartRules();
            foreach ($cart_rules as $cart_rule) {
                if (($rule = new CartRule((int)$cart_rule['obj']->id)) && Validate::isLoadedObject($rule)) {
                    if ($error = $rule->checkValidity($this->context, true, true)) {
                        $this->context->cart->removeCartRule((int)$rule->id);
                        if (isset($this->context->cookie) && isset($this->context->cookie->id_customer) && $this->context->cookie->id_customer && !empty($rule->code)) {
                            Tools::redirect('index.php?controller=order&submitAddDiscount=1&discount_name='.urlencode($rule->code));
                        } else {
                            $rule_name = isset($rule->name[(int)$this->context->cart->id_lang]) ? $rule->name[(int)$this->context->cart->id_lang] : $rule->code;
                            $error = sprintf(Tools::displayError('CartRule ID %1s (%2s) used in this cart is not valid and has been withdrawn from cart'), (int)$rule->id, $rule_name);
                            PrestaShopLogger::addLog($error, 3, '0000002', 'Cart', (int)$this->context->cart->id);
                        }
                    }
                }
            }

            foreach ($package_list as $id_address => $packageByAddress) {
                foreach ($packageByAddress as $id_package => $package) {
                    /** @var Order $order */
                    $order = new Order();
                    $order->product_list = $package['product_list'];

                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $address = new Address((int)$id_address);
                        $this->context->country = new Country((int)$address->id_country, (int)$this->context->cart->id_lang);
                        if (!$this->context->country->active) {
                            throw new PrestaShopException('The delivery address country is not active.');
                        }
                    }

                    $carrier = null;
                    if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier'])) {
                        $carrier = new Carrier((int)$package['id_carrier'], (int)$this->context->cart->id_lang);
                        $order->id_carrier = (int)$carrier->id;
                        $id_carrier = (int)$carrier->id;
                    } else {
                        $order->id_carrier = 0;
                        $id_carrier = 0;
                    }

                    $order->id_customer = (int)$this->context->cart->id_customer;
                    $order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
                    $order->id_address_delivery = (int)$id_address;
                    $order->id_currency = $this->context->currency->id;
                    $order->id_lang = (int)$this->context->cart->id_lang;
                    $order->id_cart = (int)$this->context->cart->id;
                    $order->reference = $reference;
                    $order->id_shop = (int)$this->context->shop->id;
                    $order->id_shop_group = (int)$this->context->shop->id_shop_group;

                    $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
                    $order->payment = $payment_method;
                    if (isset($this->name)) {
                        $order->module = $this->name;
                    }
                    $order->recyclable = $this->context->cart->recyclable;
                    $order->gift = (int)$this->context->cart->gift;
                    $order->gift_message = $this->context->cart->gift_message;
                    $order->mobile_theme = $this->context->cart->mobile_theme;
                    $order->conversion_rate = $this->context->currency->conversion_rate;
                    $amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
                    $order->total_paid_real = 0;

                    $order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts = $order->total_discounts_tax_incl;

                    $codfee_wt = 0;
                    if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                        $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                        $codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
                    }

                    $order->total_shipping_tax_excl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list) + $codfee_wt), 4);
                    $order->total_shipping_tax_incl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list) + $codfee), 4);
                    $order->total_shipping = $order->total_shipping_tax_incl;

                    $order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping = $order->total_wrapping_tax_incl;

                    $order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier) + $codfee_wt, _PS_PRICE_COMPUTE_PRECISION_);
                    $order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier) + $codfee, _PS_PRICE_COMPUTE_PRECISION_);
                    $order->total_paid = $order->total_paid_tax_incl;
                    $order->round_mode = Configuration::get('PS_PRICE_ROUND_MODE');
                    $order->round_type = Configuration::get('PS_ROUND_TYPE');
                    $order->invoice_date = '0000-00-00 00:00:00';
                    $order->delivery_date = '0000-00-00 00:00:00';
                    $order->codfee = $codfee;

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Creating order
                    $result = $order->add();

                    if (!$result) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order cannot be created', 3, null, 'Cart', (int)$id_cart, true);
                        throw new PrestaShopException('Can\'t save Order');
                    }

                    // Amount paid by customer is not the right one -> Status = payment error
                    // We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
                    // if ($order->total_paid != $order->total_paid_real)
                    // We use number_format in order to compare two string
                    if ($order_status->logable && number_format($cart_total_paid, _PS_PRICE_COMPUTE_PRECISION_) != number_format($amount_paid, _PS_PRICE_COMPUTE_PRECISION_)) {
                        $id_order_state = Configuration::get('PS_OS_ERROR');
                    }

                    $order_list[] = $order;

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - OrderDetail is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Insert new Order detail list using cart for the current order
                    $order_detail = new OrderDetail(null, null, $this->context);
                    $order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
                    $order_detail_list[] = $order_detail;

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - OrderCarrier is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Adding an entry in order_carrier table
                    if (!is_null($carrier)) {
                        $order_carrier = new OrderCarrier();
                        $order_carrier->id_order = (int)$order->id;
                        $order_carrier->id_carrier = (int)$id_carrier;
                        $order_carrier->weight = (float)$order->getTotalWeight();
                        $order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
                        $order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
                        $order_carrier->add();
                    }
                }
            }

            // The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                $this->context->country = $context_country;
            }

            if (!$this->context->country->active) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Country is not active', 3, null, 'Cart', (int)$id_cart, true);
                throw new PrestaShopException('The order address country is not active.');
            }

            if (self::DEBUG_MODE) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Payment is about to be added', 1, null, 'Cart', (int)$id_cart, true);
            }

            // Register Payment only if the order status validate the order
            if ($order_status->logable) {
                // $order is the last order loop in the foreach
                // The method addOrderPayment of the class Order make a create a paymentOrder
                // linked to the order reference and not to the order id
                if (isset($extra_vars['transaction_id'])) {
                    $transaction_id = $extra_vars['transaction_id'];
                } else {
                    $transaction_id = null;
                }

                if (!$order->addOrderPayment($amount_paid, null, $transaction_id)) {
                    PrestaShopLogger::addLog('PaymentModule::validateOrder - Cannot save Order Payment', 3, null, 'Cart', (int)$id_cart, true);
                    throw new PrestaShopException('Can\'t save Order Payment');
                }
            }

            // Next !
            //$only_one_gift = false;
            $cart_rule_used = array();
            //$products = $this->context->cart->getProducts();

            // Make sure CartRule caches are empty
            CartRule::cleanCache();
            foreach ($order_detail_list as $key => $order_detail) {
                /** @var OrderDetail $order_detail */

                $order = $order_list[$key];
                if (isset($order->id)) {
                    if (!$secure_key) {
                        $message .= '<br />'.Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
                    }
                    // Optional message to attach to this order
                    if (isset($message) & !empty($message)) {
                        $msg = new Message();
                        $message = strip_tags($message, '<br>');
                        if (Validate::isCleanHtml($message)) {
                            if (self::DEBUG_MODE) {
                                PrestaShopLogger::addLog('PaymentModule::validateOrder - Message is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                            }
                            $msg->message = $message;
                            $msg->id_cart = (int)$id_cart;
                            $msg->id_customer = (int)($order->id_customer);
                            $msg->id_order = (int)$order->id;
                            $msg->private = 1;
                            $msg->add();
                        }
                    }

                    // Insert new Order detail list using cart for the current order
                    //$orderDetail = new OrderDetail(null, null, $this->context);
                    //$orderDetail->createList($order, $this->context->cart, $id_order_state);

                    // Construct order detail table for the email
                    //$products_list = '';
                    $virtual_product = true;

                    $product_var_tpl_list = array();
                    foreach ($order->product_list as $product) {
                        $price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}, $specific_price, true, true, null, true, $product['id_customization']);
                        $price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}, $specific_price, true, true, null, true, $product['id_customization']);

                        $product_price = Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt;

                        $product_var_tpl = array(
                            'id_product' => $product['id_product'],
                            'reference' => $product['reference'],
                            'name' => $product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : ''),
                            'price' => Tools::displayPrice($product_price * $product['quantity'], $this->context->currency, false),
                            'quantity' => $product['quantity'],
                            'customization' => array()
                        );

                        if (isset($product['price']) && $product['price']) {
                            $product_var_tpl['unit_price'] = Tools::displayPrice($product['price'], $this->context->currency, false);
                            $product_var_tpl['unit_price_full'] = Tools::displayPrice($product['price'], $this->context->currency, false)
                                .' '.$product['unity'];
                        } else {
                            $product_var_tpl['unit_price'] = $product_var_tpl['unit_price_full'] = '';
                        }

                        $customized_datas = Product::getAllCustomizedDatas((int)$order->id_cart, null, true, null, (int)$product['id_customization']);
                        if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                            $product_var_tpl['customization'] = array();
                            foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']][$order->id_address_delivery] as $customization) {
                                $customization_text = '';
                                if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD])) {
                                    foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text) {
                                        $customization_text .= '<strong>'.$text['name'].'</strong>: '.$text['value'].'<br />';
                                    }
                                }

                                if (isset($customization['datas'][Product::CUSTOMIZE_FILE])) {
                                    $customization_text .= $this->trans('%d image(s)', array(count($customization['datas'][Product::CUSTOMIZE_FILE])), 'Admin.Payment.Notification').'<br />';
                                }

                                $customization_quantity = (int)$customization['quantity'];

                                $product_var_tpl['customization'][] = array(
                                    'customization_text' => $customization_text,
                                    'customization_quantity' => $customization_quantity,
                                    'quantity' => Tools::displayPrice($customization_quantity * $product_price, $this->context->currency, false)
                                );
                            }
                        }

                        $product_var_tpl_list[] = $product_var_tpl;
                        // Check if is not a virutal product for the displaying of shipping
                        if (!$product['is_virtual']) {
                            $virtual_product &= false;
                        }
                    } // end foreach ($products)

                    $product_list_txt = '';
                    $product_list_html = '';
                    if (count($product_var_tpl_list) > 0) {
                        $product_list_txt = $this->getEmailTemplateContent('order_conf_product_list.txt', Mail::TYPE_TEXT, $product_var_tpl_list);
                        $product_list_html = $this->getEmailTemplateContent('order_conf_product_list.tpl', Mail::TYPE_HTML, $product_var_tpl_list);
                    }

                    $cart_rules_list = array();
                    $total_reduction_value_ti = 0;
                    $total_reduction_value_tex = 0;
                    foreach ($cart_rules as $cart_rule) {
                        $package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
                        $values = array(
                            'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package) + ($cart_rule['obj']->free_shipping == 1 ? $codfee : 0),
                            'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package) + ($cart_rule['obj']->free_shipping == 1 ? $codfee_wt : 0)
                        );

                        // If the reduction is not applicable to this order, then continue with the next one
                        if (!$values['tax_excl']) {
                            continue;
                        }

                        // IF
                        //  This is not multi-shipping
                        //  The value of the voucher is greater than the total of the order
                        //  Partial use is allowed
                        //  This is an "amount" reduction, not a reduction in % or a gift
                        // THEN
                        //  The voucher is cloned with a new value corresponding to the remainder
                        if (count($order_list) == 1 && $values['tax_incl'] > ($order->total_products_wt - $total_reduction_value_ti) && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0) {
                            // Create a new voucher from the original
                            $voucher = new CartRule((int)$cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
                            unset($voucher->id);

                            // Set a new voucher code
                            $voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
                            if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2]) {
                                $voucher->code = preg_replace('/'.$matches[0].'$/', '-'.((int)($matches[1]) + 1), $voucher->code);
                            }

                            // Set the new voucher value
                            if ($voucher->reduction_tax) {
                                $voucher->reduction_amount = ($total_reduction_value_ti + $values['tax_incl']) - $order->total_products_wt;

                                // Add total shipping amout only if reduction amount > total shipping
                                if ($voucher->free_shipping == 1 && $voucher->reduction_amount >= $order->total_shipping_tax_incl) {
                                    $voucher->reduction_amount -= $order->total_shipping_tax_incl;
                                }
                            } else {
                                $voucher->reduction_amount = ($total_reduction_value_tex + $values['tax_excl']) - $order->total_products;

                                // Add total shipping amout only if reduction amount > total shipping
                                if ($voucher->free_shipping == 1 && $voucher->reduction_amount >= $order->total_shipping_tax_excl) {
                                    $voucher->reduction_amount -= $order->total_shipping_tax_excl;
                                }
                            }
                            if ($voucher->reduction_amount <= 0) {
                                continue;
                            }

                            if ($this->context->customer->isGuest()) {
                                $voucher->id_customer = 0;
                            } else {
                                $voucher->id_customer = $order->id_customer;
                            }

                            $voucher->quantity = 1;
                            $voucher->reduction_currency = $order->id_currency;
                            $voucher->quantity_per_user = 1;
                            $voucher->free_shipping = 0;
                            if ($voucher->add()) {
                                // If the voucher has conditions, they are now copied to the new voucher
                                CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);

                                $params = array(
                                    '{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
                                    '{voucher_num}' => $voucher->code,
                                    '{firstname}' => $this->context->customer->firstname,
                                    '{lastname}' => $this->context->customer->lastname,
                                    '{id_order}' => $order->reference,
                                    '{order_name}' => $order->getUniqReference()
                                );
                                Mail::Send(
                                    (int)$order->id_lang,
                                    'voucher',
                                    sprintf(Mail::l('New voucher for your order %s', (int)$order->id_lang), $order->reference),
                                    $params,
                                    $this->context->customer->email,
                                    $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                    null,
                                    null,
                                    null,
                                    null,
                                    _PS_MAIL_DIR_,
                                    false,
                                    (int)$order->id_shop
                                );
                            }

                            $values['tax_incl'] = $order->total_products_wt - $total_reduction_value_ti;
                            $values['tax_excl'] = $order->total_products - $total_reduction_value_tex;
                        }
                        $total_reduction_value_ti += $values['tax_incl'];
                        $total_reduction_value_tex += $values['tax_excl'];

                        $order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values, 0, $cart_rule['obj']->free_shipping);

                        if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used)) {
                            $cart_rule_used[] = $cart_rule['obj']->id;

                            // Create a new instance of Cart Rule without id_lang, in order to update its quantity
                            $cart_rule_to_update = new CartRule((int)$cart_rule['obj']->id);
                            $cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
                            $cart_rule_to_update->update();
                        }

                        $cart_rules_list[] = array(
                            'voucher_name' => $cart_rule['obj']->name,
                            'voucher_reduction' => ($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false)
                        );
                    }

                    $cart_rules_list_txt = '';
                    $cart_rules_list_html = '';
                    if (count($cart_rules_list) > 0) {
                        $cart_rules_list_txt = $this->getEmailTemplateContent('order_conf_cart_rules.txt', Mail::TYPE_TEXT, $cart_rules_list);
                        $cart_rules_list_html = $this->getEmailTemplateContent('order_conf_cart_rules.tpl', Mail::TYPE_HTML, $cart_rules_list);
                    }

                    // Specify order id for message
                    $old_message = Message::getMessageByCartId((int)$this->context->cart->id);
                    if ($old_message && !$old_message['private']) {
                        $update_message = new Message((int)$old_message['id_message']);
                        $update_message->id_order = (int)$order->id;
                        $update_message->update();

                        // Add this message in the customer thread
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int)$order->id_customer;
                        $customer_thread->id_shop = (int)$this->context->shop->id;
                        $customer_thread->id_order = (int)$order->id;
                        $customer_thread->id_lang = (int)$this->context->language->id;
                        $customer_thread->email = $this->context->customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();

                        $customer_message = new CustomerMessage();
                        $customer_message->id_customer_thread = $customer_thread->id;
                        $customer_message->id_employee = 0;
                        $customer_message->message = $update_message->message;
                        $customer_message->private = 1;

                        if (!$customer_message->add()) {
                            $this->errors[] = Tools::displayError('An error occurred while saving message');
                        }
                    }

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Hook validateOrder is about to be called', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Hook validate order
                    Hook::exec('actionValidateOrder', array(
                        'cart' => $this->context->cart,
                        'order' => $order,
                        'customer' => $this->context->customer,
                        'currency' => $this->context->currency,
                        'orderStatus' => $order_status
                    ));

                    foreach ($this->context->cart->getProducts() as $product) {
                        if ($order_status->logable) {
                            ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);
                        }
                    }

                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order Status is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }

                    // Set the order status
                    $new_history = new OrderHistory();
                    $new_history->id_order = (int)$order->id;
                    $new_history->changeIdOrderState((int)$id_order_state, $order, true);
                    $new_history->addWithemail(true, $extra_vars);

                    // Switch to back order if needed
                    if (Configuration::get('PS_STOCK_MANAGEMENT') && 
                            ($order_detail->getStockState() || 
                            $order_detail->product_quantity_in_stock < 0)) {
                        $history = new OrderHistory();
                        $history->id_order = (int)$order->id;
                        $history->changeIdOrderState(Configuration::get($order->valid ? 'PS_OS_OUTOFSTOCK_PAID' : 'PS_OS_OUTOFSTOCK_UNPAID'), $order, true);
                        $history->addWithemail();
                    }

                    unset($order_detail);

                    // Order is reloaded because the status just changed
                    $order = new Order((int)$order->id);

                    // Send an e-mail to customer (one order = one email)
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id) {
                        $invoice = new Address((int)$order->id_address_invoice);
                        $delivery = new Address((int)$order->id_address_delivery);
                        $delivery_state = $delivery->id_state ? new State((int)$delivery->id_state) : false;
                        $invoice_state = $invoice->id_state ? new State((int)$invoice->id_state) : false;

                        $data = array(
                        '{firstname}' => $this->context->customer->firstname,
                        '{lastname}' => $this->context->customer->lastname,
                        '{email}' => $this->context->customer->email,
                        '{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
                        '{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
                        '{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array(
                            'firstname'    => '<span style="font-weight:bold;">%s</span>',
                            'lastname'    => '<span style="font-weight:bold;">%s</span>'
                        )),
                        '{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array(
                                'firstname'    => '<span style="font-weight:bold;">%s</span>',
                                'lastname'    => '<span style="font-weight:bold;">%s</span>'
                        )),
                        '{delivery_company}' => $delivery->company,
                        '{delivery_firstname}' => $delivery->firstname,
                        '{delivery_lastname}' => $delivery->lastname,
                        '{delivery_address1}' => $delivery->address1,
                        '{delivery_address2}' => $delivery->address2,
                        '{delivery_city}' => $delivery->city,
                        '{delivery_postal_code}' => $delivery->postcode,
                        '{delivery_country}' => $delivery->country,
                        '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                        '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
                        '{delivery_other}' => $delivery->other,
                        '{invoice_company}' => $invoice->company,
                        '{invoice_vat_number}' => $invoice->vat_number,
                        '{invoice_firstname}' => $invoice->firstname,
                        '{invoice_lastname}' => $invoice->lastname,
                        '{invoice_address2}' => $invoice->address2,
                        '{invoice_address1}' => $invoice->address1,
                        '{invoice_city}' => $invoice->city,
                        '{invoice_postal_code}' => $invoice->postcode,
                        '{invoice_country}' => $invoice->country,
                        '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                        '{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
                        '{invoice_other}' => $invoice->other,
                        '{order_name}' => $order->getUniqReference(),
                        '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), null, 1),
                        '{carrier}' => ($virtual_product || !isset($carrier->name)) ? $this->trans('No carrier', array(), 'Admin.Payment.Notification') : $carrier->name,
                        '{payment}' => Tools::substr($order->payment, 0, 32),
                        '{products}' => $product_list_html,
                        '{products_txt}' => $product_list_txt,
                        '{discounts}' => $cart_rules_list_html,
                        '{discounts_txt}' => $cart_rules_list_txt,
                        '{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
                        '{total_products}' => Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? $order->total_products : $order->total_products_wt, $this->context->currency, false),
                        '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
                        '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false).'<br />'.sprintf($this->l('COD fee included:').' '.Tools::displayPrice($codfee, $this->context->currency, false)),
                        '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false),
                        '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false));

                        if (is_array($extra_vars)) {
                            $data = array_merge($data, $extra_vars);
                        }

                        // Join PDF invoice
                        $file_attachement = array();
                        if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number) {
                            $order_invoice_list = $order->getInvoicesCollection();
                            Hook::exec('actionPDFInvoiceRender', array('order_invoice_list' => $order_invoice_list));
                            $pdf = new PDF($order_invoice_list, PDF::TEMPLATE_INVOICE, $this->context->smarty);
                            $file_attachement['content'] = $pdf->render(false);
                            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang, null, $order->id_shop).sprintf('%06d', $order->invoice_number).'.pdf';
                            $file_attachement['mime'] = 'application/pdf';
                        } else {
                            $file_attachement = null;
                        }

                        if (self::DEBUG_MODE) {
                            PrestaShopLogger::addLog('PaymentModule::validateOrder - Mail is about to be sent', 1, null, 'Cart', (int)$id_cart, true);
                        }

                        $orderLanguage = new Language((int) $order->id_lang);

                        if (Validate::isEmail($this->context->customer->email)) {
                            Mail::Send(
                                (int)$order->id_lang,
                                'order_conf',
                                Context::getContext()->getTranslator()->trans(
                                    'Order confirmation',
                                    array(),
                                    'Emails.Subject',
                                    $orderLanguage->locale
                                ),
                                $data,
                                $this->context->customer->email,
                                $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                null,
                                null,
                                $file_attachement,
                                null, _PS_MAIL_DIR_, false, (int)$order->id_shop
                            );
                        }
                    }

                    // updates stock in shops
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();
                        foreach ($product_list as $product) {
                            // if the available quantities depends on the physical stock
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                // synchronizes
                                StockAvailable::synchronize($product['product_id'], $order->id_shop);
                            }
                        }
                    }

                    $order->updateOrderDetailTax();
                } else {
                    $error = Tools::displayError('Order creation failed');
                    PrestaShopLogger::addLog($error, 4, '0000002', 'Cart', (int)($order->id_cart));
                    die($error);
                }
            } // End foreach $order_detail_list

            // Use the last order as currentOrder
            if (isset($order) && $order->id) {
                $this->currentOrder = (int)$order->id;
            }

            if (self::DEBUG_MODE) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - End of validateOrder', 1, null, 'Cart', (int)$id_cart, true);
            }

            return true;
        } else {
            $error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
            PrestaShopLogger::addLog($error, 4, '0000001', 'Cart', (int)($this->context->cart->id));
            die($error);
        }
    }

    /* Back Office orders - 1.5+ */
    public function hookActionValidateOrder($params)
    {
        $cart = $params['cart'];
        $order = $params['order'];
        $controller = $this->context->controller;
        if ((isset($controller->controller_type) && $controller->controller_type == 'modulefront')
            || (isset($controller->page_name) && $controller->page_name == 'module-codfee-validation')) {
            return;
        } elseif ($order->module == 'codfee') {
            $id_lang = $order->id_lang;
            $id_shop = $order->id_shop;
            $customer = new Customer((int)$order->id_customer);
            $customer_groups = $customer->getGroupsStatic((int)$customer->id);
            $carrier = new Carrier((int)$order->id_carrier);
            $address = new Address((int)$order->id_address_delivery);
            $country = new Country((int)$address->id_country);
            if ($address->id_state > 0) {
                $zone = State::getIdZone((int)$address->id_state);
            } else {
                $zone = $country->getIdZone((int)$country->id);
            }
            $manufacturers = '';
            $suppliers = '';
            $products = $order->getProducts();
            foreach ($products as $product) {
                $manufacturers .= $product['id_manufacturer'].';';
                $suppliers .= $product['id_supplier'].';';
            }
            $manufacturers = explode(';', trim($manufacturers, ';'));
            $manufacturers = array_unique($manufacturers, SORT_REGULAR);
            $suppliers = explode(';', trim($suppliers, ';'));
            $suppliers = array_unique($suppliers, SORT_REGULAR);
            $group = new Group((int)$customer->id_default_group);
            if ($group->price_display_method == '1') {
                $price_display_method = false;
            } else {
                $price_display_method = true;
            }
            $order_total = $cart->getOrderTotal($price_display_method, 3);

            $codfeeconf = new CodfeeConfiguration();
            $codfeeconf = $codfeeconf->getFeeConfiguration($id_shop, $id_lang, $customer_groups, $carrier->id_reference, $country, $zone, $products, $manufacturers, $suppliers, $order_total);
            if (!$codfeeconf) {
                return;
            } else {
                $fee = (float)Tools::ps_round((float)$this->getFeeCost($cart, $codfeeconf, $price_display_method), 2);
                if ($codfeeconf['free_on_freeshipping'] == '1' && $cart->getOrderTotal($price_display_method, Cart::ONLY_SHIPPING) == 0) {
                    $fee = (float)0.00;
                }
                if ($codfeeconf['free_on_freeshipping'] == '1' && count($cart->getCartRules(CartRule::FILTER_ACTION_SHIPPING)) > 0) {
                    $fee = (float)0.00;
                }
            }

            $id_order = Order::getOrderByCartId($cart->id);
            Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.bqSQL('orders').'`
            SET `codfee` = '.$fee.'
            WHERE `'.bqSQL('id_order').'` = '.(int)$id_order);

            $codfee_wt = 0;
            if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                $codfee_wt = $fee / (1 + (($order->carrier_tax_rate) / 100));
            }
            $order->total_shipping_tax_excl = (float)Tools::ps_round($order->total_shipping_tax_excl + $codfee_wt, 2);
            $order->total_shipping_tax_incl = (float)Tools::ps_round($order->total_shipping_tax_incl + $fee, 2);
            $order->total_shipping = $order->total_shipping_tax_incl;
            $order->total_paid_tax_excl = (float)Tools::ps_round($order->total_paid_tax_excl + $codfee_wt, 2);
            $order->total_paid_tax_incl = (float)Tools::ps_round($order->total_paid_tax_incl + $fee, 2);
            $order->total_paid = $order->total_paid_tax_incl;
            $order->update();
        }
    }

    public function hookOrderConfirmation($params)
    {

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $order = $params['objOrder'];
        } else {
            $order = $params['order'];
        }
        $products = $order->getProducts();

        $this->context->smarty->assign(array(
            'order'=> $order,
            'order_products' => $products
        ));

        return $this->display(__FILE__, 'confirmation.tpl');
    }

    public function execPayment($cart)
    {
        if (!$this->_checkCurrency($cart)) {
            return;
        } else {
            $cashOnDelivery = new CodFee();
            $fee = (float)Tools::ps_round((float)$cashOnDelivery->getFeeCost($cart), 2);
            $cartcost = $cart->getOrderTotal(true, 3);
            $cartwithoutshipping = $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
            $shippingcost = $cartcost - $cartwithoutshipping;
            $total = $fee + $cartcost;
            $cart->additional_shipping_cost = $fee;

            if (Tools::isSubmit('paymentSubmit')) {
                $authorized = false;
                if (version_compare(_PS_VERSION_, '1.4.4', '>=')) {
                    $modules = Module::getPaymentModules();
                } else {
                    $modules = $this->_getPaymentModules($cart);
                }

                foreach ($modules as $module) {
                    if ($module['name'] == 'codfee') {
                        $authorized = true;
                        break;
                    }
                }

                if (!$authorized) {
                    die($this->module->l('This payment method is not available.'));
                }

                $id_currency = (int)Tools::getValue('id_currency');
                if (version_compare(_PS_VERSION_, '1.5', '<')) {
                    $this->validateOrder14($cart->id, Configuration::get('COD_FEE_STATUS'), $total, $fee, $this->displayName, null, null, $id_currency, false, $cart->secure_key);
                }

                $this->context->smarty->assign(array(
                    'total'         => $total,
                    'success'       => true,
                    'currency'      => new Currency((int)$cart->id_currency),
                ));

                return $this->display(__FILE__, 'views/templates/hook/payment_return14.tpl');
            }

            $currency = new Currency($cart->id_currency);
            $conv_rate = (float)$currency->conversion_rate;
            $carriers = explode(';', Configuration::get('COD_FEE_CARRIERS'));
            $this_path_ssl = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
            $payment_logo_url = $this_path_ssl.'modules/codfee/views/img/payment.png';

            $this->context->smarty->assign(array(
                'this_path' => $this->_path,
                'nbProducts' => $cart->nbProducts(),
                'cartcost' => number_format((float)$cartcost, 2, '.', ''),
                'cartwithoutshipping' => number_format((float)$cartwithoutshipping, 2, '.', ''),
                'shippingcost' => number_format((float)$shippingcost, 2, '.', ''),
                'fee' => number_format((float)$fee, 2, '.', ''),
                'free_fee' => (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2),
                'currency' => new Currency((int)$cart->id_currency),
                'total' => number_format((float)$total, 2, '.', ''),
                'payment_text'      => '',
                'payment_logo'      => $payment_logo_url,
                'carrier' => $cart->id_carrier,
                'carriers' => $carriers,
                'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/codfee/'
            ));

            $this->context->smarty->assign('this_path', __PS_BASE_URI__.'modules/codfee/');

            return $this->display(__FILE__, 'views/templates/front/codfee_val14.tpl');
        }
    }

    public function getFeeCost($cart, $codfeeconf = false, $price_display_method = 0)
    {
        if (!$codfeeconf) {
            return $this->getFeeCost14($cart);
        }
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $currency = new Currency($cart->id_currency);
            $conv_rate = (float)$currency->conversion_rate;
        } else {
            $conv_rate = (float)$this->context->currency->conversion_rate;
        }

        $fee = 0;
        switch ($codfeeconf['amount_calc']) {
            case '0':
                $cartvalue = (float)$cart->getOrderTotal($price_display_method, 3);
                break;
            case '1':
                $cartvalue = (float)$cart->getOrderTotal($price_display_method, 4);
                break;
            case '2':
                $cartvalue = (float)$cart->getOrderTotal($price_display_method, 5);
                break;
            default:
                $cartvalue = (float)$cart->getOrderTotal($price_display_method, 3);
        }
        if ($codfeeconf['type'] == '0') {
            $free_fee = (float)Tools::ps_round((float)$codfeeconf['amount_free'] * (float)$conv_rate, 2);
            if (($free_fee < $cartvalue) && ($free_fee != 0)) {
                $fee = (float)0;
            } else {
                $fee = (float)Tools::ps_round((float)$codfeeconf['fix'] * (float)$conv_rate, 2);
            }
        } else if ($codfeeconf['type'] == '1') {
            $minimalfee = (float)Tools::ps_round((float)$codfeeconf['min'] * (float)$conv_rate, 2);
            $maximalfee = (float)Tools::ps_round((float)$codfeeconf['max'] * (float)$conv_rate, 2);
            $free_fee = (float)Tools::ps_round((float)$codfeeconf['amount_free'] * (float)$conv_rate, 2);
            $percent = (float)$codfeeconf['percentage'];
            $percent = $percent / 100;
            $fee = $cartvalue * $percent;

            if (($fee < $minimalfee) && ($minimalfee != 0)) {
                $fee = $minimalfee;
            } elseif (($fee > $maximalfee) && ($maximalfee != 0)) {
                $fee = $maximalfee;
            }

            if (($free_fee < $cartvalue) && ($free_fee != 0)) {
                $fee = 0;
            }
        } else if ($codfeeconf['type'] == '2') {
            $minimalfee = (float)Tools::ps_round((float)$codfeeconf['min'] * (float)$conv_rate, 2);
            $maximalfee = (float)Tools::ps_round((float)$codfeeconf['max'] * (float)$conv_rate, 2);
            $free_fee = (float)Tools::ps_round((float)$codfeeconf['amount_free'] * (float)$conv_rate, 2);
            $percent = (float)$codfeeconf['percentage'];
            $percent = $percent / 100;
            $fee_tax = (float)Tools::ps_round((float)$codfeeconf['fix'] * (float)$conv_rate, 2);
            $fee = ($cartvalue * $percent) + $fee_tax;

            if (($fee < $minimalfee) && ($minimalfee != 0)) {
                $fee = $minimalfee;
            } else if (($fee > $maximalfee) && ($maximalfee != 0)) {
                $fee = $maximalfee;
            }

            if (($free_fee < $cartvalue) && ($free_fee != 0)) {
                $fee = 0;
            }
        }
        if ($codfeeconf['round'] == '1') {
            $cart_not_rounded = $cartvalue + $fee;
            $cart_rounded = ceil($cart_not_rounded);
            $diff_to_addfee = $cart_rounded - $cart_not_rounded;
            if ($diff_to_addfee > 0) {
                $fee = $fee + $diff_to_addfee;
            }
        }
        return (float)$fee;
    }

    public function getFeeCost14($cart, $price_display_method = 0)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $currency = new Currency($cart->id_currency);
            $conv_rate = (float)$currency->conversion_rate;
        } else {
            $conv_rate = (float)$this->context->currency->conversion_rate;
        }

        if (Configuration::get('COD_FEE_TYPE') == 0) {
            $free_fee = (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2);
            $cartvalue = (float)$cart->getOrderTotal($price_display_method, 3);

            if (($free_fee < $cartvalue) && ($free_fee != 0)) {
                return (float)0;
            } else {
                return (float)Tools::ps_round((float)Configuration::get('COD_FEE') * (float)$conv_rate, 2);
            }
        } else if (Configuration::get('COD_FEE_TYPE') == 1) {
            $minimalfee = (float)Tools::ps_round((float)Configuration::get('COD_FEE_MIN') * (float)$conv_rate, 2);
            $maximalfee = (float)Tools::ps_round((float)Configuration::get('COD_FEE_MAX') * (float)$conv_rate, 2);
            $free_fee = (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2);
            $cartvalue = (float)$cart->getOrderTotal($price_display_method, 3);
            $percent = (float)Configuration::get('COD_FEE_TAX');
            $percent = $percent / 100;
            $fee = $cartvalue * $percent;

            if (($fee < $minimalfee) && ($minimalfee != 0)) {
                $fee = $minimalfee;
            } elseif (($fee > $maximalfee) && ($maximalfee != 0)) {
                $fee = $maximalfee;
            }

            if (($free_fee < $cartvalue) && ($free_fee != 0)) {
                $fee = 0;
            }

            return (float)$fee;
        } else if (Configuration::get('COD_FEE_TYPE') == 2) {
            $minimalfee = (float)Tools::ps_round((float)Configuration::get('COD_FEE_MIN') * (float)$conv_rate, 2);
            $maximalfee = (float)Tools::ps_round((float)Configuration::get('COD_FEE_MAX') * (float)$conv_rate, 2);
            $free_fee = (float)Tools::ps_round((float)Configuration::get('COD_FREE_FEE') * (float)$conv_rate, 2);
            $cartvalue = (float)$cart->getOrderTotal($price_display_method, 3);
            $percent = (float)Configuration::get('COD_FEE_TAX');
            $percent = $percent / 100;
            $fee_tax = (float)Tools::ps_round((float)Configuration::get('COD_FEE') * (float)$conv_rate, 2);
            $fee = ($cartvalue * $percent) + $fee_tax;

            if (($fee < $minimalfee) && ($minimalfee != 0)) {
                $fee = $minimalfee;
            } else if (($fee > $maximalfee) && ($maximalfee != 0)) {
                $fee = $maximalfee;
            }

            if (($free_fee < $cartvalue) && ($free_fee != 0)) {
                $fee = 0;
            }

            return (float)$fee;
        }
    }

    public function _checkCurrency($cart)
    {
        $currency_order = new Currency((int)$cart->id_currency);
        $currencies_module = $this->getCurrency();

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
    * @param Object Address $the_address that needs to be txt formated
    * @return String the txt formated address block
    */

    protected function _getFormatedAddress14(Address $the_address, $line_sep, $fields_style = array())
    {
        return AddressFormat::generateAddress($the_address, array('avoid' => array()), $line_sep, ' ', $fields_style);
    }

    private function convertSign($s)
    {
        return str_replace(array('€', '£', '¥'), array(chr(128), chr(163), chr(165)), $s);
    }

    private function _getPaymentModules($cart)
    {
        $id_customer = (int)($cart->id_customer);
        $billing = new Address((int)($cart->id_address_invoice));

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
        SELECT DISTINCT h.`id_hook`, m.`name`, hm.`position`
            FROM `'._DB_PREFIX_.'module_country` mc
            LEFT JOIN `'._DB_PREFIX_.'module` m ON m.`id_module` = mc.`id_module`
            INNER JOIN `'._DB_PREFIX_.'module_group` mg ON (m.`id_module` = mg.`id_module`)
            INNER JOIN `'._DB_PREFIX_.'customer_group` cg on (cg.`id_group` = mg.`id_group` AND cg.`id_customer` = '.(int)($id_customer).')
            LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON hm.`id_module` = m.`id_module`
            LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook`
        WHERE h.`name` = \'payment\'
        AND mc.id_country = '.(int)($billing->id_country).'
        AND m.`active` = 1
        ORDER BY hm.`position`, m.`name` DESC');

        return $result;
    }

    protected function initSQLCodfeeConfiguration()
    {
        Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_configuration` (
            `id_codfee_configuration` int(10) unsigned NOT NULL auto_increment,
            `name` VARCHAR(100) NULL,
            `type` int(1) unsigned NOT NULL DEFAULT "1",
            `amount_calc` tinyint(1) unsigned NOT NULL DEFAULT "9",
            `fix` decimal(10,3) NULL DEFAULT "0.000",
            `percentage` decimal(10,3) NULL DEFAULT "0.000",
            `min` decimal(10,3) NULL DEFAULT "0.000",
            `max` decimal(10,3) NULL DEFAULT "0.000",
            `order_min` decimal(10,3) NULL DEFAULT "0.000",
            `order_max` decimal(10,3) NULL DEFAULT "0.000",
            `amount_free` decimal(10,3) NULL DEFAULT "0.000",
            `groups` VARCHAR(250) NULL,
            `carriers` VARCHAR(250) NULL,
            `countries` VARCHAR(1000) NULL,
            `zones` VARCHAR(250) NULL,
            `categories` VARCHAR(2000) NULL,
            `manufacturers` VARCHAR(250) NULL,
            `suppliers` VARCHAR(250) NULL,
            `initial_status` int(1) unsigned NOT NULL DEFAULT "3",
            `show_conf_page` tinyint(1) unsigned NOT NULL DEFAULT "1",
            `free_on_freeshipping` tinyint(1) unsigned NOT NULL DEFAULT "0",
            `hide_first_order` tinyint(1) unsigned NOT NULL DEFAULT "0",
            `only_stock` tinyint(1) unsigned NOT NULL DEFAULT "0",
            `round` tinyint(1) unsigned NOT NULL DEFAULT "0",
            `show_productpage` tinyint(1) unsigned NOT NULL DEFAULT "0",
            `active` tinyint(1) unsigned NOT NULL DEFAULT "0",
            `priority` int(1) unsigned DEFAULT "0",
            `position` tinyint(1) unsigned NOT NULL DEFAULT "0",
            `payment_size` varchar(10) NOT NULL DEFAULT "col-md-12",
            `min_weight` decimal(10,3) NULL DEFAULT "0.000",
            `max_weight` decimal(10,3) NULL DEFAULT "0.000",
            `id_shop` tinyint(1) unsigned NOT NULL DEFAULT "0",
            `date_add` DATETIME,
            `date_upd` DATETIME,
        PRIMARY KEY (`id_codfee_configuration`),
        KEY `id_codfee_configuration` (`id_codfee_configuration`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;');

        Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_configuration_lang` (
            `id_codfee_configuration` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned,
            `payment_text` text NULL,
        PRIMARY KEY (`id_codfee_configuration`, `id_lang`),
        KEY `id_codfee_configuration` (`id_codfee_configuration`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;');

        Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_configuration_shop` (
            `id_codfee_configuration` int(10) unsigned NOT NULL,
            `id_shop` int(11) unsigned NOT NULL,
        PRIMARY KEY (`id_codfee_configuration`, `id_shop`),
        KEY `id_codfee_configuration` (`id_codfee_configuration`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;');

        try {
            Db::getInstance()->Execute('
            ALTER TABLE `'.pSQL(_DB_PREFIX_.'orders').'`
                ADD `codfee` decimal(10,3) NOT NULL DEFAULT "0.000";');
        } catch (Exception $e) {
            return true;
        }

        return true;
    }

    protected function uninstallSQL()
    {
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_configuration`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_configuration_lang`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_configuration_shop`');
        //Db::getInstance()->Execute('ALTER TABLE `'.pSQL(_DB_PREFIX_.'orders').'` DROP `codfee`');
        return true;
    }

    private function addTab($tabName, $tabClassName, $idTabParent = -1)
    {
        $id_tab = Tab::getIdFromClassName($tabClassName);
        $tabNames = array();

        if (!$id_tab) {
            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                $langs = Language::getlanguages(false);

                foreach ($langs as $l) {
                    $tabNames[$l['id_lang']] = Tools::substr($tabName, 0, 32);
                }

                $tab = new Tab();
                $tab->module = $this->name;
                $tab->name = $tabNames;
                $tab->class_name = $tabClassName;
                $tab->id_parent = -1;

                if (!$tab->save()) {
                    return false;
                }
            } else {
                $tab = new Tab();
                $tab->class_name = $tabClassName;
                $tab->id_parent = -1;
                $tab->module = $this->name;
                $languages = Language::getLanguages();

                foreach ($languages as $language) {
                    $tab->name[$language['id_lang']] =Tools::substr($this->l($tabName), 0, 32);
                }

                if (!$tab->add()) {
                    return false;
                }
            }
        }
        return true;
    }

    private function removeTab($tabClass)
    {
        $idTab = Tab::getIdFromClassName($tabClass);

        if ($idTab) {
            $tab = new Tab($idTab);
            $tab->delete();
            return true;
        }

        return false;
    }

    public function getFeeFromOrderId($id_order)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT o.`codfee` FROM `'._DB_PREFIX_.'orders` o WHERE o.`id_order` = '.(int)$id_order.';');
    }

    public function setFeeFromOrderId($id_order)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT o.`codfee` FROM `'._DB_PREFIX_.'orders` o WHERE o.`id_order` = '.(int)$id_order.';');
    }

    public function validateOrder16($id_cart, $id_order_state, $amount_paid, $codfee, $payment_method = 'Unknown', $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null)
    {
        $this->context->cart = new Cart($id_cart);
        $this->context->customer = new Customer($this->context->cart->id_customer);
        $this->context->language = new Language($this->context->cart->id_lang);
        $this->context->shop = ($shop ? $shop : new Shop($this->context->cart->id_shop));
        ShopUrl::resetMainDomainCache();
        $id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
        $this->context->currency = new Currency($id_currency, null, $this->context->shop->id);
        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
            $context_country = $this->context->country;
        }
        $order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
        if (!Validate::isLoadedObject($order_status)) {
            throw new PrestaShopException('Can\'t load Order state status');
        }
        if (!$this->active) {
            die(Tools::displayError());
        }
        // Does order already exists ?
        if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $this->context->cart->secure_key) {
                die(Tools::displayError());
            }
            // For each package, generate an order
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
            $package_list = $this->context->cart->getPackageList();
            $cart_delivery_option = $this->context->cart->getDeliveryOption();
            // If some delivery options are not defined, or not valid, use the first valid option
            foreach ($delivery_option_list as $id_address => $package) {
                if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package)) {
                    foreach ($package as $key => $val) {
                        $cart_delivery_option[$id_address] = $key;
                        break;
                    }
                }
            }
            $order_list = array();
            $order_detail_list = array();
            $reference = Order::generateReference();
            $this->currentOrderReference = $reference;
            $order_creation_failed = false;
            $cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH) + $codfee, 2);
            foreach ($cart_delivery_option as $id_address => $key_carriers) {
                foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data) {
                    foreach ($data['package_list'] as $id_package) {
                        // Rewrite the id_warehouse
                        $package_list[$id_address][$id_package]['id_warehouse'] = (int)$this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int)$id_carrier);
                        $package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
                    }
                }
            }
            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            foreach ($package_list as $id_address => $packageByAddress) {
                foreach ($packageByAddress as $id_package => $package) {
                    $order = new Order();
                    $order->product_list = $package['product_list'];

                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $address = new Address($id_address);
                        $this->context->country = new Country($address->id_country, $this->context->cart->id_lang);
                    }

                    $carrier = null;
                    if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier'])) {
                        $carrier = new Carrier($package['id_carrier'], $this->context->cart->id_lang);
                        $order->id_carrier = (int)$carrier->id;
                        $id_carrier = (int)$carrier->id;
                    } else {
                        $order->id_carrier = 0;
                        $id_carrier = 0;
                    }

                    $order->id_customer = (int)$this->context->cart->id_customer;
                    $order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
                    $order->id_address_delivery = (int)$id_address;
                    $order->id_currency = $this->context->currency->id;
                    $order->id_lang = (int)$this->context->cart->id_lang;
                    $order->id_cart = (int)$this->context->cart->id;
                    $order->reference = $reference;
                    $order->id_shop = (int)$this->context->shop->id;
                    $order->id_shop_group = (int)$this->context->shop->id_shop_group;
                    $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
                    $order->payment = $payment_method;
                    if (isset($this->name)) {
                        $order->module = $this->name;
                    }
                    $order->recyclable = $this->context->cart->recyclable;
                    $order->gift = (int)$this->context->cart->gift;
                    $order->gift_message = $this->context->cart->gift_message;
                    $order->mobile_theme = $this->context->cart->mobile_theme;
                    $order->conversion_rate = $this->context->currency->conversion_rate;
                    $amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
                    $order->total_paid_real = 0;

                    $order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts = $order->total_discounts_tax_incl;

                    $codfee_wt = 0;
                    if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                        $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                        $codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
                    }
                    $order->total_shipping_tax_excl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list) + $codfee_wt), 4);
                    $order->total_shipping_tax_incl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list) + $codfee), 4);
                    $order->total_shipping = $order->total_shipping_tax_incl;

                    $order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping = $order->total_wrapping_tax_incl;

                    $order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier) + $codfee_wt, 2);
                    $order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier) + $codfee, 2);
                    $order->total_paid = $order->total_paid_tax_incl;
                    $order->invoice_date = '0000-00-00 00:00:00';
                    $order->delivery_date = '0000-00-00 00:00:00';
                    $order->codfee = $codfee;
                    // Creating order
                    $result = $order->add();
                    if (!$result) {
                        throw new PrestaShopException('Can\'t save Order');
                    }
                    // Amount paid by customer is not the right one -> Status = payment error
                    // We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
                    // if ($order->total_paid != $order->total_paid_real)
                    // We use number_format in order to compare two string
                    if ($order_status->logable && number_format($cart_total_paid, 2) != number_format($amount_paid, 2)) {
                        $id_order_state = Configuration::get('PS_OS_ERROR');
                    }
                    $order_list[] = $order;
                    // Insert new Order detail list using cart for the current order
                    $order_detail = new OrderDetail(null, null, $this->context);
                    $order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
                    $order_detail_list[] = $order_detail;
                    // Adding an entry in order_carrier table
                    if (!is_null($carrier)) {
                        $order_carrier = new OrderCarrier();
                        $order_carrier->id_order = (int)$order->id;
                        $order_carrier->id_carrier = (int)$id_carrier;
                        $order_carrier->weight = (float)$order->getTotalWeight();
                        $order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
                        $order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
                        $order_carrier->add();
                    }
                }
            }

            // The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                $this->context->country = $context_country;
            }
            // Register Payment only if the order status validate the order
            if ($order_status->logable) {
                // $order is the last order loop in the foreach
                // The method addOrderPayment of the class Order make a create a paymentOrder
                //  linked to the order reference and not to the order id
                if (isset($extra_vars['transaction_id'])) {
                    $transaction_id = $extra_vars['transaction_id'];
                } else {
                    $transaction_id = null;
                }

                if (!$order->addOrderPayment($amount_paid, null, $transaction_id)) {
                    throw new PrestaShopException('Can\'t save Order Payment');
                }
            }
            // Next !
            $cart_rule_used = array();
            $cart_rules = $this->context->cart->getCartRules();

            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            foreach ($order_detail_list as $key => $order_detail) {
                $order = $order_list[$key];
                if (!$order_creation_failed && isset($order->id)) {
                    if (!$secure_key) {
                        $message .= '<br />'.Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
                    }
                    // Optional message to attach to this order
                    if (isset($message) & !empty($message)) {
                        $msg = new Message();
                        $message = strip_tags($message, '<br>');
                        if (Validate::isCleanHtml($message)) {
                            $msg->message = $message;
                            $msg->id_order = (int)$order->id;
                            $msg->private = 1;
                            $msg->add();
                        }
                    }
                    // Insert new Order detail list using cart for the current order
                    //$orderDetail = new OrderDetail(null, null, $this->context);
                    //$orderDetail->createList($order, $this->context->cart, $id_order_state);
                    // Construct order detail table for the email
                    $products_list = '';
                    $virtual_product = true;
                    foreach ($order->product_list as $key => $product) {
                        $price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                        $price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                        $customization_quantity = 0;
                        $customized_datas = Product::getAllCustomizedDatas((int)$order->id_cart);
                        if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                            $customization_text = '';
                            foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']][$order->id_address_delivery] as $customization) {
                                if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD])) {
                                    foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text) {
                                        $customization_text .= $text['name'].': '.$text['value'].'<br />';
                                    }
                                }
                                if (isset($customization['datas'][Product::CUSTOMIZE_FILE])) {
                                    $customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])).'<br />';
                                }
                                $customization_text .= '---<br />';
                            }
                            $customization_text = rtrim($customization_text, '---<br />');
                            $customization_quantity = (int)$product['customization_quantity'];
                            $products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
                                <td style="padding: 0.6em 0.4em;width: 15%;">'.$product['reference'].'</td>
                                <td style="padding: 0.6em 0.4em;width: 30%;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').' - '.Tools::displayError('Customized').(!empty($customization_text) ? ' - '.$customization_text : '').'</strong></td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
                                <td style="padding: 0.6em 0.4em; width: 15%;">'.$customization_quantity.'</td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
                            </tr>';
                        }
                        if (!$customization_quantity || (int)$product['cart_quantity'] > $customization_quantity) {
                            $products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
                                <td style="padding: 0.6em 0.4em;width: 15%;">'.$product['reference'].'</td>
                                <td style="padding: 0.6em 0.4em;width: 30%;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').'</strong></td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(Product::getTaxCalculationMethod((int)$this->context->customer->id) == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
                                <td style="padding: 0.6em 0.4em; width: 15%;">'.((int)$product['cart_quantity'] - $customization_quantity).'</td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(((int)$product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
                            </tr>';
                        }
                        // Check if is not a virutal product for the displaying of shipping
                        if (!$product['is_virtual']) {
                            $virtual_product &= false;
                        }
                    } // end foreach ($products)
                    $cart_rules_list = '';
                    $total_reduction_value_ti = 0;
                    $total_reduction_value_tex = 0;
                    foreach ($cart_rules as $cart_rule) {
                        $package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
                        $values = array(
                            'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package) + ($cart_rule['obj']->free_shipping == 1 ? $codfee : 0),
                            'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package) + ($cart_rule['obj']->free_shipping == 1 ? $codfee_wt : 0)
                        );
                        // If the reduction is not applicable to this order, then continue with the next one
                        if (!$values['tax_excl']) {
                            continue;
                        }
                        /* IF
                        ** - This is not multi-shipping
                        ** - The value of the voucher is greater than the total of the order
                        ** - Partial use is allowed
                        ** - This is an "amount" reduction, not a reduction in % or a gift
                        ** THEN
                        ** The voucher is cloned with a new value corresponding to the remainder
                        */
                        if (count($order_list) == 1 && $values['tax_incl'] > ($order->total_products_wt - $total_reduction_value_ti) && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0) {
                            // Create a new voucher from the original
                            $voucher = new CartRule($cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
                            unset($voucher->id);
                            // Set a new voucher code
                            $voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
                            if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2]) {
                                $voucher->code = preg_replace('/'.$matches[0].'$/', '-'.((int)$matches[1] + 1), $voucher->code);
                            }
                            // Set the new voucher value
                            if ($voucher->reduction_tax) {
                                $voucher->reduction_amount = $values['tax_incl'] - ($order->total_products_wt - $total_reduction_value_ti) - ($voucher->free_shipping == 1 ? $order->total_shipping_tax_incl : 0);
                            } else {
                                $voucher->reduction_amount = $values['tax_excl'] - ($order->total_products - $total_reduction_value_tex) - ($voucher->free_shipping == 1 ? $order->total_shipping_tax_excl : 0);
                            }
                            $voucher->id_customer = $order->id_customer;
                            $voucher->quantity = 1;
                            $voucher->quantity_per_user = 1;
                            $voucher->free_shipping = 0;
                            if ($voucher->add()) {
                                // If the voucher has conditions, they are now copied to the new voucher
                                CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);
                                $params = array(
                                    '{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
                                    '{voucher_num}' => $voucher->code,
                                    '{firstname}' => $this->context->customer->firstname,
                                    '{lastname}' => $this->context->customer->lastname,
                                    '{id_order}' => $order->reference,
                                    '{order_name}' => $order->getUniqReference()
                                );
                                Mail::Send(
                                    (int)$order->id_lang,
                                    'voucher',
                                    sprintf(Mail::l('New voucher regarding your order %s', (int)$order->id_lang), $order->reference),
                                    $params,
                                    $this->context->customer->email,
                                    $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                    null,
                                    null,
                                    null,
                                    null,
                                    _PS_MAIL_DIR_,
                                    false,
                                    (int)$order->id_shop
                                );
                            }
                            $values['tax_incl'] -= $values['tax_incl'] - $order->total_products_wt;
                            $values['tax_excl'] -= $values['tax_excl'] - $order->total_products;
                        }
                        $total_reduction_value_ti += $values['tax_incl'];
                        $total_reduction_value_tex += $values['tax_excl'];
                        $order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values, 0, $cart_rule['obj']->free_shipping);
                        if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used)) {
                            $cart_rule_used[] = $cart_rule['obj']->id;
                            // Create a new instance of Cart Rule without id_lang, in order to update its quantity
                            $cart_rule_to_update = new CartRule($cart_rule['obj']->id);
                            $cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
                            $cart_rule_to_update->update();
                        }
                        $values['tax_incl'] = $values['tax_incl'] - ($cart_rule['obj']->free_shipping == 1 ? $codfee : 0);
                        $values['tax_excl'] = $values['tax_excl'] - ($cart_rule['obj']->free_shipping == 1 ? $codfee_wt : 0);
                        $cart_rules_list .= '
                        <tr>
                            <td colspan="4" style="padding:0.6em 0.4em;text-align:right">'.Tools::displayError('Voucher name:').' '.$cart_rule['obj']->name.'</td>
                            <td style="padding:0.6em 0.4em;text-align:right">'.($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false).'</td>
                        </tr>';
                    }
                    // Specify order id for message
                    $old_message = Message::getMessageByCartId((int)$this->context->cart->id);
                    if ($old_message) {
                        $update_message = new Message((int)$old_message['id_message']);
                        $update_message->id_order = (int)$order->id;
                        $update_message->update();
                        // Add this message in the customer thread
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int)$order->id_customer;
                        $customer_thread->id_shop = (int)$this->context->shop->id;
                        $customer_thread->id_order = (int)$order->id;
                        $customer_thread->id_lang = (int)$this->context->language->id;
                        $customer_thread->email = $this->context->customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();
                        $customer_message = new CustomerMessage();
                        $customer_message->id_customer_thread = $customer_thread->id;
                        $customer_message->id_employee = 0;
                        $customer_message->message = $update_message->message;
                        $customer_message->private = 0;
                        if (!$customer_message->add()) {
                            $this->errors[] = Tools::displayError('An error occurred while saving message');
                        }
                    }
                    // Hook validate order
                    Hook::exec('actionValidateOrder', array(
                        'cart' => $this->context->cart,
                        'order' => $order,
                        'customer' => $this->context->customer,
                        'currency' => $this->context->currency,
                        'orderStatus' => $order_status
                    ));
                    foreach ($this->context->cart->getProducts() as $product) {
                        if ($order_status->logable) {
                            ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);
                        }
                    }

                    // Switch to back order if needed
                    if (Configuration::get('PS_STOCK_MANAGEMENT') && $order_detail->getStockState()) {
                        $history = new OrderHistory();
                        $history->id_order = (int)$order->id;
                        $history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
                        $history->addWithemail();
                    } else {
                        $new_history = new OrderHistory();
                        $new_history->id_order = (int)$order->id;
                        $new_history->changeIdOrderState((int)$id_order_state, $order, true);
                        $new_history->addWithemail(true, $extra_vars);
                    }
                    unset($order_detail);
                    // Order is reloaded because the status just changed
                    $order = new Order($order->id);
                    // Send an e-mail to customer (one order = one email)
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id) {
                        $invoice = new Address($order->id_address_invoice);
                        $delivery = new Address($order->id_address_delivery);
                        $delivery_state = $delivery->id_state ? new State($delivery->id_state) : false;
                        $invoice_state = $invoice->id_state ? new State($invoice->id_state) : false;
                        $data = array(
                        '{firstname}' => $this->context->customer->firstname,
                        '{lastname}' => $this->context->customer->lastname,
                        '{email}' => $this->context->customer->email,
                        '{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
                        '{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
                        '{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array(
                            'firstname' => '<span style="font-weight:bold;">%s</span>',
                            'lastname'  => '<span style="font-weight:bold;">%s</span>'
                        )),
                        '{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array(
                                'firstname' => '<span style="font-weight:bold;">%s</span>',
                                'lastname'  => '<span style="font-weight:bold;">%s</span>'
                        )),
                        '{delivery_company}' => $delivery->company,
                        '{delivery_firstname}' => $delivery->firstname,
                        '{delivery_lastname}' => $delivery->lastname,
                        '{delivery_address1}' => $delivery->address1,
                        '{delivery_address2}' => $delivery->address2,
                        '{delivery_city}' => $delivery->city,
                        '{delivery_postal_code}' => $delivery->postcode,
                        '{delivery_country}' => $delivery->country,
                        '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                        '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
                        '{delivery_other}' => $delivery->other,
                        '{invoice_company}' => $invoice->company,
                        '{invoice_vat_number}' => $invoice->vat_number,
                        '{invoice_firstname}' => $invoice->firstname,
                        '{invoice_lastname}' => $invoice->lastname,
                        '{invoice_address2}' => $invoice->address2,
                        '{invoice_address1}' => $invoice->address1,
                        '{invoice_city}' => $invoice->city,
                        '{invoice_postal_code}' => $invoice->postcode,
                        '{invoice_country}' => $invoice->country,
                        '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                        '{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
                        '{invoice_other}' => $invoice->other,
                        '{order_name}' => $order->getUniqReference(),
                        '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), null, 1),
                        '{carrier}' => $virtual_product ? Tools::displayError('No carrier') : $carrier->name,
                        '{payment}' => Tools::substr($order->payment, 0, 32),
                        '{products}' => $this->formatProductAndVoucherForEmail($products_list),
                        '{discounts}' => $this->formatProductAndVoucherForEmail($cart_rules_list),
                        '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false),
                        '{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
                        '{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
                        '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
                        '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false).'<br />'.sprintf($this->l('COD fee included:').' '.Tools::displayPrice($codfee, $this->context->currency, false)),
                        '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false));
                        if (is_array($extra_vars)) {
                            $data = array_merge($data, $extra_vars);
                        }
                        // Join PDF invoice
                        $file_attachement = array();
                        if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number) {
                            $pdf = new PDF($order->getInvoicesCollection(), PDF::TEMPLATE_INVOICE, $this->context->smarty);
                            $file_attachement['content'] = $pdf->render(false);
                            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang, null, $order->id_shop).sprintf('%06d', $order->invoice_number).'.pdf';
                            $file_attachement['mime'] = 'application/pdf';
                        } else {
                            $file_attachement = null;
                        }
                        if (Validate::isEmail($this->context->customer->email)) {
                            Mail::Send(
                                (int)$order->id_lang,
                                'order_conf',
                                Mail::l('Order confirmation', (int)$order->id_lang),
                                $data,
                                $this->context->customer->email,
                                $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                null,
                                null,
                                $file_attachement,
                                null,
                                _PS_MAIL_DIR_,
                                false,
                                (int)$order->id_shop
                            );
                        }
                    }
                    // updates stock in shops
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();
                        foreach ($product_list as $product) {
                            // if the available quantities depends on the physical stock
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                // synchronizes
                                StockAvailable::synchronize($product['product_id'], $order->id_shop);
                            }
                        }
                    }
                } else {
                    $error = Tools::displayError('Order creation failed');
                    Logger::addLog($error, 4, '0000002', 'Cart', (int)$order->id_cart);
                    die($error);
                }
            } // End foreach $order_detail_list
            // Use the last order as currentOrder
            $this->currentOrder = (int)$order->id;
            return true;
        } else {
            $error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
            Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
            die($error);
        }
    }

    public function validateOrder153($id_cart, $id_order_state, $amount_paid, $codfee, $payment_method = 'Unknown', $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null)
    {
        $this->context->cart = new Cart($id_cart);
        $this->context->customer = new Customer($this->context->cart->id_customer);
        $this->context->language = new Language($this->context->cart->id_lang);
        $this->context->shop = ($shop ? $shop : new Shop($this->context->cart->id_shop));
        $id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
        $this->context->currency = new Currency($id_currency, null, $this->context->shop->id);
        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
            $context_country = $this->context->country;
        }

        $order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
        if (!Validate::isLoadedObject($order_status)) {
            throw new PrestaShopException('Can\'t load Order state status');
        }

        if (!$this->active) {
            die(Tools::displayError());
        }
        // Does order already exists ?
        if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $this->context->cart->secure_key) {
                die(Tools::displayError());
            }

            // For each package, generate an order
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
            $package_list = $this->context->cart->getPackageList();
            $cart_delivery_option = $this->context->cart->getDeliveryOption();

            // If some delivery options are not defined, or not valid, use the first valid option
            foreach ($delivery_option_list as $id_address => $package) {
                if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package)) {
                    foreach ($package as $key => $val) {
                        $cart_delivery_option[$id_address] = $key;
                        break;
                    }
                }
            }

            $order_list = array();
            $order_detail_list = array();
            $reference = Order::generateReference();
            $this->currentOrderReference = $reference;

            $order_creation_failed = false;
            $cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH) + $codfee, 2);

            if ($this->context->cart->orderExists()) {
                $error = Tools::displayError('An order has already been placed using this cart.');
                Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
                die($error);
            }

            foreach ($cart_delivery_option as $id_address => $key_carriers) {
                foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data) {
                    foreach ($data['package_list'] as $id_package) {
                        // Rewrite the id_warehouse
                        $package_list[$id_address][$id_package]['id_warehouse'] = (int)$this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int)$id_carrier);
                        $package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
                    }
                }
            }
            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            foreach ($package_list as $id_address => $packageByAddress) {
                foreach ($packageByAddress as $id_package => $package) {
                    $order = new Order();
                    $order->product_list = $package['product_list'];

                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $address = new Address($id_address);
                        $this->context->country = new Country($address->id_country, $this->context->cart->id_lang);
                    }

                    $carrier = null;
                    if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier'])) {
                        $carrier = new Carrier($package['id_carrier'], $this->context->cart->id_lang);
                        $order->id_carrier = (int)$carrier->id;
                        $id_carrier = (int)$carrier->id;
                    } else {
                        $order->id_carrier = 0;
                        $id_carrier = 0;
                    }

                    $order->id_customer = (int)$this->context->cart->id_customer;
                    $order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
                    $order->id_address_delivery = (int)$id_address;
                    $order->id_currency = $this->context->currency->id;
                    $order->id_lang = (int)$this->context->cart->id_lang;
                    $order->id_cart = (int)$this->context->cart->id;
                    $order->reference = $reference;
                    $order->id_shop = (int)$this->context->shop->id;
                    $order->id_shop_group = (int)$this->context->shop->id_shop_group;

                    $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
                    $order->payment = $payment_method;
                    if (isset($this->name)) {
                        $order->module = $this->name;
                    }
                    $order->recyclable = $this->context->cart->recyclable;
                    $order->gift = (int)$this->context->cart->gift;
                    $order->gift_message = $this->context->cart->gift_message;
                    $order->conversion_rate = $this->context->currency->conversion_rate;
                    $amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
                    $order->total_paid_real = 0;

                    $order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);

                    $order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts = $order->total_discounts_tax_incl;

                    $codfee_wt = 0;

                    if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                        $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                        $codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
                    }

                    $order->total_shipping_tax_excl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list) + $codfee_wt), 4);
                    $order->total_shipping_tax_incl = (float)Tools::ps_round((float)($this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list) + $codfee), 4);
                    $order->total_shipping = $order->total_shipping_tax_incl;

                    $order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping = $order->total_wrapping_tax_incl;

                    $order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier) + $codfee_wt, 2);
                    $order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier) + $codfee, 2);
                    $order->total_paid = $order->total_paid_tax_incl;

                    $order->invoice_date = '1970-01-01 00:00:00';
                    $order->delivery_date = '1970-01-01 00:00:00';

                    $order->codfee = $codfee;

                    // Creating order
                    $result = $order->add();

                    if (!$result) {
                        throw new PrestaShopException('Can\'t save Order');
                    }

                    // Amount paid by customer is not the right one -> Status = payment error
                    // We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
                    // if ($order->total_paid != $order->total_paid_real)
                    // We use number_format in order to compare two string
                    if ($order_status->logable && number_format($cart_total_paid, 2) != number_format($amount_paid, 2)) {
                        $id_order_state = Configuration::get('PS_OS_ERROR');
                    }

                    $order_list[] = $order;

                    // Insert new Order detail list using cart for the current order
                    $order_detail = new OrderDetail(null, null, $this->context);
                    $order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
                    $order_detail_list[] = $order_detail;

                    // Adding an entry in order_carrier table
                    if (!is_null($carrier)) {
                        $order_carrier = new OrderCarrier();
                        $order_carrier->id_order = (int)$order->id;
                        $order_carrier->id_carrier = (int)$id_carrier;
                        $order_carrier->weight = (float)$order->getTotalWeight();
                        $order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
                        $order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
                        $order_carrier->add();
                    }
                }
            }

            // The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                $this->context->country = $context_country;
            }

            // Register Payment only if the order status validate the order
            if ($order_status->logable) {
                // $order is the last order loop in the foreach
                // The method addOrderPayment of the class Order make a create a paymentOrder
                //  linked to the order reference and not to the order id
                if (isset($extra_vars['transaction_id'])) {
                    $transaction_id = $extra_vars['transaction_id'];
                } else {
                    $transaction_id = null;
                }

                if (!$order->addOrderPayment($amount_paid, null, $transaction_id)) {
                    throw new PrestaShopException('Can\'t save Order Payment');
                }
            }

            // Next !
            $cart_rule_used = array();
            $products = $this->context->cart->getProducts();
            $cart_rules = $this->context->cart->getCartRules();

            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            foreach ($order_detail_list as $key => $order_detail) {
                $order = $order_list[$key];
                if (!$order_creation_failed & isset($order->id)) {
                    if (!$secure_key) {
                        $message .= '<br />'.Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
                    }
                    // Optional message to attach to this order
                    if (isset($message) & !empty($message)) {
                        $msg = new Message();
                        $message = strip_tags($message, '<br>');
                        if (Validate::isCleanHtml($message)) {
                            $msg->message = $message;
                            $msg->id_order = (int)$order->id;
                            $msg->private = 1;
                            $msg->add();
                        }
                    }

                    // Insert new Order detail list using cart for the current order
                    //$orderDetail = new OrderDetail(null, null, $this->context);
                    //$orderDetail->createList($order, $this->context->cart, $id_order_state);

                    // Construct order detail table for the email
                    $products_list = '';
                    $virtual_product = true;
                    $customized_datas = array();
                    foreach ($products as $key => $product) {
                        $price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                        $price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

                        $customization_quantity = 0;
                        if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                            $customization_text = '';
                            foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']] as $customization) {
                                if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD])) {
                                    foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text) {
                                        $customization_text .= $text['name'].': '.$text['value'].'<br />';
                                    }
                                }

                                if (isset($customization['datas'][Product::CUSTOMIZE_FILE])) {
                                    $customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])).'<br />';
                                }

                                $customization_text .= '---<br />';
                            }

                            $customization_text = rtrim($customization_text, '---<br />');

                            $customization_quantity = (int)$product['customizationQuantityTotal'];
                            $products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
                                <td style="padding: 0.6em 0.4em;width: 15%;">'.$product['reference'].'</td>
                                <td style="padding: 0.6em 0.4em;width: 30%;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').' - '.Tools::displayError('Customized').(!empty($customization_text) ? ' - '.$customization_text : '').'</strong></td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
                                <td style="padding: 0.6em 0.4em; width: 15%;">'.$customization_quantity.'</td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
                            </tr>';
                        }

                        if (!$customization_quantity || (int)$product['cart_quantity'] > $customization_quantity) {
                            $products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
                                <td style="padding: 0.6em 0.4em;width: 15%;">'.$product['reference'].'</td>
                                <td style="padding: 0.6em 0.4em;width: 30%;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').'</strong></td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
                                <td style="padding: 0.6em 0.4em; width: 15%;">'.((int)$product['cart_quantity'] - $customization_quantity).'</td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">'.Tools::displayPrice(((int)$product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
                            </tr>';
                        }

                        // Check if is not a virutal product for the displaying of shipping
                        if (!$product['is_virtual']) {
                            $virtual_product &= false;
                        }
                    } // end foreach ($products)

                    $cart_rules_list = '';
                    foreach ($cart_rules as $cart_rule) {
                        $package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
                        $values = array(
                            'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL, $package),
                            'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL, $package)
                        );

                        // If the reduction is not applicable to this order, then continue with the next one
                        if (!$values['tax_excl']) {
                            continue;
                        }

                        $order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values);

                        /* IF
                        ** - This is not multi-shipping
                        ** - The value of the voucher is greater than the total of the order
                        ** - Partial use is allowed
                        ** - This is an "amount" reduction, not a reduction in % or a gift
                        ** THEN
                        ** The voucher is cloned with a new value corresponding to the remainder
                        */
                        if (count($order_list) == 1 && $values['tax_incl'] > $order->total_products_wt && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0) {
                            // Create a new voucher from the original
                            $voucher = new CartRule($cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
                            unset($voucher->id);

                            // Set a new voucher code
                            $voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
                            if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2]) {
                                $voucher->code = preg_replace('/'.$matches[0].'$/', '-'.((int)$matches[1] + 1), $voucher->code);
                            }

                            // Set the new voucher value
                            if ($voucher->reduction_tax) {
                                $voucher->reduction_amount = $values['tax_incl'] - $order->total_products_wt;
                            } else {
                                $voucher->reduction_amount = $values['tax_excl'] - $order->total_products;
                            }

                            $voucher->id_customer = $order->id_customer;
                            $voucher->quantity = 1;
                            if ($voucher->add()) {
                                // If the voucher has conditions, they are now copied to the new voucher
                                CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);

                                $params = array(
                                    '{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
                                    '{voucher_num}' => $voucher->code,
                                    '{firstname}' => $this->context->customer->firstname,
                                    '{lastname}' => $this->context->customer->lastname,
                                    '{id_order}' => $order->reference,
                                    '{order_name}' => $order->getUniqReference()
                                );
                                Mail::Send(
                                    (int)$order->id_lang,
                                    'voucher',
                                    sprintf(Mail::l('New voucher regarding your order %s', (int)$order->id_lang), $order->reference),
                                    $params,
                                    $this->context->customer->email,
                                    $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                    null,
                                    null,
                                    null,
                                    null,
                                    _PS_MAIL_DIR_,
                                    false,
                                    (int)$order->id_shop
                                );
                            }
                        }

                        if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used)) {
                            $cart_rule_used[] = $cart_rule['obj']->id;

                            // Create a new instance of Cart Rule without id_lang, in order to update its quantity
                            $cart_rule_to_update = new CartRule($cart_rule['obj']->id);
                            $cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
                            $cart_rule_to_update->update();
                        }

                        $cart_rules_list .= '
                        <tr>
                            <td colspan="4" style="padding:0.6em 0.4em;text-align:right">'.Tools::displayError('Voucher name:').' '.$cart_rule['obj']->name.'</td>
                            <td style="padding:0.6em 0.4em;text-align:right">'.($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false).'</td>
                        </tr>';
                    }

                    // Specify order id for message
                    $old_message = Message::getMessageByCartId((int)$this->context->cart->id);
                    if ($old_message) {
                        $update_message = new Message((int)$old_message['id_message']);
                        $update_message->id_order = (int)$order->id;
                        $update_message->update();

                        // Add this message in the customer thread
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int)$order->id_customer;
                        $customer_thread->id_shop = (int)$this->context->shop->id;
                        $customer_thread->id_order = (int)$order->id;
                        $customer_thread->id_lang = (int)$this->context->language->id;
                        $customer_thread->email = $this->context->customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();

                        $customer_message = new CustomerMessage();
                        $customer_message->id_customer_thread = $customer_thread->id;
                        $customer_message->id_employee = 0;
                        $customer_message->message = htmlentities($update_message->message, ENT_COMPAT, 'UTF-8');
                        $customer_message->private = 0;

                        if (!$customer_message->add()) {
                            $this->errors[] = Tools::displayError('An error occurred while saving message');
                        }
                    }

                    // Hook validate order
                    Hook::exec('actionValidateOrder', array(
                        'cart' => $this->context->cart,
                        'order' => $order,
                        'customer' => $this->context->customer,
                        'currency' => $this->context->currency,
                        'orderStatus' => $order_status
                    ));

                    foreach ($this->context->cart->getProducts() as $product) {
                        if ($order_status->logable) {
                            ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);
                        }
                    }

                    if (Configuration::get('PS_STOCK_MANAGEMENT') && $order_detail->getStockState()) {
                        $history = new OrderHistory();
                        $history->id_order = (int)$order->id;
                        $history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
                        $history->addWithemail();
                    }

                    // Set order state in order history ONLY even if the "out of stock" status has not been yet reached
                    // So you migth have two order states
                    $new_history = new OrderHistory();
                    $new_history->id_order = (int)$order->id;
                    $new_history->changeIdOrderState((int)$id_order_state, $order, true);
                    $new_history->addWithemail(true, $extra_vars);

                    unset($order_detail);

                    // Order is reloaded because the status just changed
                    $order = new Order($order->id);

                    // Send an e-mail to customer (one order = one email)
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id) {
                        $invoice = new Address($order->id_address_invoice);
                        $delivery = new Address($order->id_address_delivery);
                        $delivery_state = $delivery->id_state ? new State($delivery->id_state) : false;
                        $invoice_state = $invoice->id_state ? new State($invoice->id_state) : false;

                        $data = array(
                        '{firstname}' => $this->context->customer->firstname,
                        '{lastname}' => $this->context->customer->lastname,
                        '{email}' => $this->context->customer->email,
                        '{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
                        '{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
                        '{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array('firstname' => '<span style="font-weight:bold;">%s</span>', 'lastname'  => '<span style="font-weight:bold;">%s</span>')),
                        '{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array('firstname' => '<span style="font-weight:bold;">%s</span>', 'lastname'  => '<span style="font-weight:bold;">%s</span>')),
                        '{delivery_company}' => $delivery->company,
                        '{delivery_firstname}' => $delivery->firstname,
                        '{delivery_lastname}' => $delivery->lastname,
                        '{delivery_address1}' => $delivery->address1,
                        '{delivery_address2}' => $delivery->address2,
                        '{delivery_city}' => $delivery->city,
                        '{delivery_postal_code}' => $delivery->postcode,
                        '{delivery_country}' => $delivery->country,
                        '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                        '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
                        '{delivery_other}' => $delivery->other,
                        '{invoice_company}' => $invoice->company,
                        '{invoice_vat_number}' => $invoice->vat_number,
                        '{invoice_firstname}' => $invoice->firstname,
                        '{invoice_lastname}' => $invoice->lastname,
                        '{invoice_address2}' => $invoice->address2,
                        '{invoice_address1}' => $invoice->address1,
                        '{invoice_city}' => $invoice->city,
                        '{invoice_postal_code}' => $invoice->postcode,
                        '{invoice_country}' => $invoice->country,
                        '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                        '{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
                        '{invoice_other}' => $invoice->other,
                        '{order_name}' => $order->getUniqReference(),
                        '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), (int)$order->id_lang, 1),
                        '{carrier}' => $virtual_product ? Tools::displayError('No carrier') : $carrier->name,
                        '{payment}' => Tools::substr($order->payment, 0, 32),
                        '{products}' => $this->formatProductAndVoucherForEmail($products_list),
                        '{discounts}' => $this->formatProductAndVoucherForEmail($cart_rules_list),
                        '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false),
                        '{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
                        '{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
                        '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
                        '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false).'<br />'.sprintf($this->l('COD fee included:').' '.Tools::displayPrice($codfee, $this->context->currency, false)),
                        '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false));

                        if (is_array($extra_vars)) {
                            $data = array_merge($data, $extra_vars);
                        }

                        // Join PDF invoice
                        $file_attachement = array();
                        if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number) {
                            $pdf = new PDF($order->getInvoicesCollection(), PDF::TEMPLATE_INVOICE, $this->context->smarty);
                            $file_attachement['content'] = $pdf->render(false);
                            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang).sprintf('%06d', $order->invoice_number).'.pdf';
                            $file_attachement['mime'] = 'application/pdf';
                        } else {
                            $file_attachement = null;
                        }

                        if (Validate::isEmail($this->context->customer->email)) {
                            Mail::Send(
                                (int)$order->id_lang,
                                'order_conf',
                                Mail::l('Order confirmation', (int)$order->id_lang),
                                $data,
                                $this->context->customer->email,
                                $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                null,
                                null,
                                $file_attachement,
                                null,
                                _PS_MAIL_DIR_,
                                false,
                                (int)$order->id_shop
                            );
                        }
                    }

                    // updates stock in shops
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();
                        foreach ($product_list as $product) {
                            // if the available quantities depends on the physical stock
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                // synchronizes
                                StockAvailable::synchronize($product['product_id'], $order->id_shop);
                            }
                        }
                    }
                } else {
                    $error = Tools::displayError('Order creation failed');
                    Logger::addLog($error, 4, '0000002', 'Cart', (int)$order->id_cart);
                    die($error);
                }
            } // End foreach $order_detail_list
            // Use the last order as currentOrder
            $this->currentOrder = (int)$order->id;
            return true;
        } else {
            $error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
            Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
            die($error);
        }
    }

    /**
    * Validate an order in database
    * Function called from a payment module
    * VERSION 1.5
    *
    * @param integer $id_cart Value
    * @param integer $id_order_state Value
    * @param float $amount_paid Amount really paid by customer (in the default currency)
    * @param string $payment_method Payment method (eg. 'Credit card')
    * @param string $message Message to attach to order
    */
    public function validateOrder15($id_cart, $id_order_state, $amount_paid, $codfee, $payment_method = 'Unknown', $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null)
    {
        $this->context->cart = new Cart($id_cart);
        $this->context->customer = new Customer($this->context->cart->id_customer);
        $this->context->language = new Language($this->context->cart->id_lang);
        $this->context->shop = ($shop ? $shop : new Shop($this->context->cart->id_shop));
        $id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
        $this->context->currency = new Currency($id_currency, null, $this->context->shop->id);
        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
            $context_country = $this->context->country;
        }

        $order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
        if (!Validate::isLoadedObject($order_status)) {
            throw new PrestaShopException('Can\'t load Order state status');
        }

        if (!$this->active) {
            die(Tools::displayError());
        }
        // Does order already exists ?
        if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $this->context->cart->secure_key) {
                die(Tools::displayError());
            }

            // For each package, generate an order
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
            $package_list = $this->context->cart->getPackageList();
            $cart_delivery_option = $this->context->cart->getDeliveryOption();

            // If some delivery options are not defined, or not valid, use the first valid option
            foreach ($delivery_option_list as $id_address => $package) {
                if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package)) {
                    foreach ($package as $key => $val) {
                        $cart_delivery_option[$id_address] = $key;
                        break;
                    }
                }
            }

            $order_list = array();
            $order_detail_list = array();
            $reference = Order::generateReference();
            $this->currentOrderReference = $reference;

            $order_creation_failed = false;
            $cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH) + $codfee, 2);

            if ($this->context->cart->orderExists()) {
                $error = Tools::displayError('An order has already been placed using this cart.');
                Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
                die($error);
            }

            foreach ($cart_delivery_option as $id_address => $key_carriers) {
                foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data) {
                    foreach ($data['package_list'] as $id_package) {
                        $package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
                    }
                }
            }

            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            foreach ($package_list as $id_address => $packageByAddress) {
                foreach ($packageByAddress as $id_package => $package) {
                    $order = new Order();
                    $order->product_list = $package['product_list'];

                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $address = new Address($id_address);
                        $this->context->country = new Country($address->id_country, $this->context->cart->id_lang);
                    }

                    $carrier = null;
                    if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier'])) {
                        $carrier = new Carrier($package['id_carrier'], $this->context->cart->id_lang);
                        $order->id_carrier = (int)$carrier->id;
                        $id_carrier = (int)$carrier->id;
                    } else {
                        $order->id_carrier = 0;
                        $id_carrier = 0;
                    }

                    $order->id_customer = (int)$this->context->cart->id_customer;
                    $order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
                    $order->id_address_delivery = (int)$id_address;
                    $order->id_currency = $this->context->currency->id;
                    $order->id_lang = (int)$this->context->cart->id_lang;
                    $order->id_cart = (int)$this->context->cart->id;
                    $order->reference = $reference;
                    $order->id_shop = (int)$this->context->shop->id;
                    $order->id_shop_group = (int)$this->context->shop->id_shop_group;

                    $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
                    $order->payment = $payment_method;
                    if (isset($this->name)) {
                        $order->module = $this->name;
                    }
                    $order->recyclable = $this->context->cart->recyclable;
                    $order->gift = (int)$this->context->cart->gift;
                    $order->gift_message = $this->context->cart->gift_message;
                    $order->conversion_rate = $this->context->currency->conversion_rate;
                    $amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
                    $order->total_paid_real = 0;

                    $order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);

                    $order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts = $order->total_discounts_tax_incl;

                    $codfee_wt = 0;

                    if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                        $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                        $codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
                    }

                    $order->total_shipping_tax_excl = (float)Tools::ps_round(($this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list) + $codfee_wt), 2);
                    $order->total_shipping_tax_incl = (float)Tools::ps_round(($this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list) + $codfee), 2);
                    $order->total_shipping = $order->total_shipping_tax_incl;

                    $order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping = $order->total_wrapping_tax_incl;

                    $order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier) + $codfee_wt, 2);
                    $order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier) + $codfee, 2);
                    $order->total_paid = $order->total_paid_tax_incl;

                    $order->invoice_date = '1970-01-01 00:00:00';
                    $order->delivery_date = '1970-01-01 00:00:00';

                    $order->codfee = $codfee;

                    // Creating order
                    $result = $order->add();

                    if (!$result) {
                        throw new PrestaShopException('Can\'t save Order');
                    }

                    // Amount paid by customer is not the right one -> Status = payment error
                    // We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
                    // if ($order->total_paid != $order->total_paid_real)
                    // We use number_format in order to compare two string
                    if ($order_status->logable && number_format($cart_total_paid, 2) != number_format($amount_paid, 2)) {
                        $id_order_state = Configuration::get('PS_OS_ERROR');
                    }

                    $order_list[] = $order;

                    // Insert new Order detail list using cart for the current order
                    $order_detail = new OrderDetail(null, null, $this->context);
                    $order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
                    $order_detail_list[] = $order_detail;

                    // Adding an entry in order_carrier table
                    if (!is_null($carrier)) {
                        $order_carrier = new OrderCarrier();
                        $order_carrier->id_order = (int)$order->id;
                        $order_carrier->id_carrier = (int)$id_carrier;
                        $order_carrier->weight = (float)$order->getTotalWeight();
                        $order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
                        $order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
                        $order_carrier->add();
                    }
                }
            }

            // The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                $this->context->country = $context_country;
            }

            // Register Payment only if the order status validate the order
            if ($order_status->logable) {
                // $order is the last order loop in the foreach
                // The method addOrderPayment of the class Order make a create a paymentOrder
                //  linked to the order reference and not to the order id
                if (!$order->addOrderPayment($amount_paid)) {
                    throw new PrestaShopException('Can\'t save Order Payment');
                }
            }

            // Next !
            $cart_rule_used = array();
            $products = $this->context->cart->getProducts();
            $cart_rules = $this->context->cart->getCartRules();

            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            foreach ($order_detail_list as $key => $order_detail) {
                $order = $order_list[$key];
                if (!$order_creation_failed & isset($order->id)) {
                    if (!$secure_key) {
                        $message .= '<br />'.Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
                    }
                    // Optional message to attach to this order
                    if (isset($message) & !empty($message)) {
                        $msg = new Message();
                        $message = strip_tags($message, '<br>');
                        if (Validate::isCleanHtml($message)) {
                            $msg->message = $message;
                            $msg->id_order = (int)$order->id;
                            $msg->private = 1;
                            $msg->add();
                        }
                    }

                    // Insert new Order detail list using cart for the current order
                    //$orderDetail = new OrderDetail(null, null, $this->context);
                    //$orderDetail->createList($order, $this->context->cart, $id_order_state);

                    // Construct order detail table for the email
                    $products_list = '';
                    $virtual_product = true;
                    $customized_datas = array();
                    foreach ($products as $key => $product) {
                        $price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                        $price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

                        $customization_quantity = 0;
                        if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                            $customization_text = '';
                            foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']] as $customization) {
                                if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD])) {
                                    foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text) {
                                        $customization_text .= $text['name'].': '.$text['value'].'<br />';
                                    }
                                }

                                if (isset($customization['datas'][Product::CUSTOMIZE_FILE])) {
                                    $customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])).'<br />';
                                }

                                $customization_text .= '---<br />';
                            }

                            $customization_text = rtrim($customization_text, '---<br />');

                            $customization_quantity = (int)$product['customizationQuantityTotal'];
                            $products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
                                <td style="padding: 0.6em 0.4em;">'.$product['reference'].'</td>
                                <td style="padding: 0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').' - '.Tools::displayError('Customized').(!empty($customization_text) ? ' - '.$customization_text : '').'</strong></td>
                                <td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
                                <td style="padding: 0.6em 0.4em; text-align: center;">'.$customization_quantity.'</td>
                                <td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
                            </tr>';
                        }

                        if (!$customization_quantity || (int)$product['cart_quantity'] > $customization_quantity) {
                            $products_list .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
                                <td style="padding: 0.6em 0.4em;">'.$product['reference'].'</td>
                                <td style="padding: 0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').'</strong></td>
                                <td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false).'</td>
                                <td style="padding: 0.6em 0.4em; text-align: center;">'.((int)$product['cart_quantity'] - $customization_quantity).'</td>
                                <td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(((int)$product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false).'</td>
                            </tr>';
                        }

                        // Check if is not a virutal product for the displaying of shipping
                        if (!$product['is_virtual']) {
                            $virtual_product &= false;
                        }
                    } // end foreach ($products)

                    $cart_rules_list = '';
                    foreach ($cart_rules as $cart_rule) {
                        $package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
                        $values = array(
                            'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL, $package),
                            'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL, $package)
                        );

                        // If the reduction is not applicable to this order, then continue with the next one
                        if (!$values['tax_excl']) {
                            continue;
                        }

                        $order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values);

                        /* IF
                        ** - This is not multi-shipping
                        ** - The value of the voucher is greater than the total of the order
                        ** - Partial use is allowed
                        ** - This is an "amount" reduction, not a reduction in % or a gift
                        ** THEN
                        ** The voucher is cloned with a new value corresponding to the remainder
                        */
                        if (count($order_list) == 1 && $values['tax_incl'] > $order->total_products_wt && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0) {
                            // Create a new voucher from the original
                            $voucher = new CartRule($cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
                            unset($voucher->id);

                            // Set a new voucher code
                            $voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
                            if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2]) {
                                $voucher->code = preg_replace('/'.$matches[0].'$/', '-'.((int)$matches[1] + 1), $voucher->code);
                            }

                            // Set the new voucher value
                            if ($voucher->reduction_tax) {
                                $voucher->reduction_amount = $values['tax_incl'] - $order->total_products_wt;
                            } else {
                                $voucher->reduction_amount = $values['tax_excl'] - $order->total_products;
                            }

                            $voucher->id_customer = $order->id_customer;
                            $voucher->quantity = 1;
                            if ($voucher->add()) {
                                // If the voucher has conditions, they are now copied to the new voucher
                                CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);

                                $params = array(
                                    '{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
                                    '{voucher_num}' => $voucher->code,
                                    '{firstname}' => $this->context->customer->firstname,
                                    '{lastname}' => $this->context->customer->lastname,
                                    '{id_order}' => $order->reference,
                                    '{order_name}' => $order->getUniqReference()
                                );
                                Mail::Send(
                                    (int)$order->id_lang,
                                    'voucher',
                                    sprintf(Mail::l('New voucher regarding your order %s', (int)$order->id_lang), $order->reference),
                                    $params,
                                    $this->context->customer->email,
                                    $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                    null,
                                    null,
                                    null,
                                    null,
                                    _PS_MAIL_DIR_,
                                    false,
                                    (int)$order->id_shop
                                );
                            }
                        }

                        if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used)) {
                            $cart_rule_used[] = $cart_rule['obj']->id;

                            // Create a new instance of Cart Rule without id_lang, in order to update its quantity
                            $cart_rule_to_update = new CartRule($cart_rule['obj']->id);
                            $cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
                            $cart_rule_to_update->update();
                        }

                        $cart_rules_list .= '
                        <tr style="background-color:#EBECEE;">
                            <td colspan="4" style="padding:0.6em 0.4em;text-align:right">'.Tools::displayError('Voucher name:').' '.$cart_rule['obj']->name.'</td>
                            <td style="padding:0.6em 0.4em;text-align:right">'.($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false).'</td>
                        </tr>';
                    }

                    // Specify order id for message
                    $old_message = Message::getMessageByCartId((int)$this->context->cart->id);
                    if ($old_message) {
                        $message = new Message((int)$old_message['id_message']);
                        $message->id_order = (int)$order->id;
                        $message->update();

                        // Add this message in the customer thread
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int)$order->id_customer;
                        $customer_thread->id_shop = (int)$this->context->shop->id;
                        $customer_thread->id_order = (int)$order->id;
                        $customer_thread->id_lang = (int)$this->context->language->id;
                        $customer_thread->email = $this->context->customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();

                        $customer_message = new CustomerMessage();
                        $customer_message->id_customer_thread = $customer_thread->id;
                        $customer_message->id_employee = 0;
                        $customer_message->message = htmlentities($message->message, ENT_COMPAT, 'UTF-8');
                        $customer_message->private = 0;

                        if (!$customer_message->add()) {
                            $this->errors[] = Tools::displayError('An error occurred while saving message');
                        }
                    }

                    // Hook validate order
                    Hook::exec('actionValidateOrder', array(
                        'cart' => $this->context->cart,
                        'order' => $order,
                        'customer' => $this->context->customer,
                        'currency' => $this->context->currency,
                        'orderStatus' => $order_status
                    ));

                    foreach ($this->context->cart->getProducts() as $product) {
                        if ($order_status->logable) {
                            ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);
                        }
                    }

                    if (Configuration::get('PS_STOCK_MANAGEMENT') && $order_detail->getStockState()) {
                        $history = new OrderHistory();
                        $history->id_order = (int)$order->id;
                        $history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
                        $history->addWithemail();
                    }

                    // Set order state in order history ONLY even if the "out of stock" status has not been yet reached
                    // So you migth have two order states
                    $new_history = new OrderHistory();
                    $new_history->id_order = (int)$order->id;
                    $new_history->changeIdOrderState((int)$id_order_state, $order, true);
                    $new_history->addWithemail(true, $extra_vars);

                    unset($order_detail);

                    // Order is reloaded because the status just changed
                    $order = new Order($order->id);

                    // Send an e-mail to customer (one order = one email)
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id) {
                        $invoice = new Address($order->id_address_invoice);
                        $delivery = new Address($order->id_address_delivery);
                        $delivery_state = $delivery->id_state ? new State($delivery->id_state) : false;
                        $invoice_state = $invoice->id_state ? new State($invoice->id_state) : false;

                        $data = array(
                        '{firstname}' => $this->context->customer->firstname,
                        '{lastname}' => $this->context->customer->lastname,
                        '{email}' => $this->context->customer->email,
                        '{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
                        '{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
                        '{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array('firstname' => '<span style="color:#DB3484; font-weight:bold;">%s</span>', 'lastname'  => '<span style="color:#DB3484; font-weight:bold;">%s</span>')),
                        '{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array('firstname' => '<span style="color:#DB3484; font-weight:bold;">%s</span>', 'lastname'  => '<span style="color:#DB3484; font-weight:bold;">%s</span>')),
                        '{delivery_company}' => $delivery->company,
                        '{delivery_firstname}' => $delivery->firstname,
                        '{delivery_lastname}' => $delivery->lastname,
                        '{delivery_address1}' => $delivery->address1,
                        '{delivery_address2}' => $delivery->address2,
                        '{delivery_city}' => $delivery->city,
                        '{delivery_postal_code}' => $delivery->postcode,
                        '{delivery_country}' => $delivery->country,
                        '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                        '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
                        '{delivery_other}' => $delivery->other,
                        '{invoice_company}' => $invoice->company,
                        '{invoice_vat_number}' => $invoice->vat_number,
                        '{invoice_firstname}' => $invoice->firstname,
                        '{invoice_lastname}' => $invoice->lastname,
                        '{invoice_address2}' => $invoice->address2,
                        '{invoice_address1}' => $invoice->address1,
                        '{invoice_city}' => $invoice->city,
                        '{invoice_postal_code}' => $invoice->postcode,
                        '{invoice_country}' => $invoice->country,
                        '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                        '{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
                        '{invoice_other}' => $invoice->other,
                        '{order_name}' => $order->getUniqReference(),
                        '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), (int)$order->id_lang, 1),
                        '{carrier}' => $virtual_product ? Tools::displayError('No carrier') : $carrier->name,
                        '{payment}' => Tools::substr($order->payment, 0, 45),
                        '{products}' => $this->formatProductAndVoucherForEmail($products_list),
                        '{discounts}' => $this->formatProductAndVoucherForEmail($cart_rules_list),
                        '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false),
                        '{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
                        '{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
                        '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
                        '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false).'<br />'.sprintf($this->l('COD fee included:').' '.Tools::displayPrice($codfee, $this->context->currency, false)),
                        '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false));

                        if (is_array($extra_vars)) {
                            $data = array_merge($data, $extra_vars);
                        }

                        // Join PDF invoice
                        $file_attachement = array();
                        if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number) {
                            $pdf = new PDF($order->getInvoicesCollection(), PDF::TEMPLATE_INVOICE, $this->context->smarty);
                            $file_attachement['content'] = $pdf->render(false);
                            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang).sprintf('%06d', $order->invoice_number).'.pdf';
                            $file_attachement['mime'] = 'application/pdf';
                        } else {
                            $file_attachement = null;
                        }

                        if (Validate::isEmail($this->context->customer->email)) {
                            Mail::Send(
                                (int)$order->id_lang,
                                'order_conf',
                                Mail::l('Order confirmation', (int)$order->id_lang),
                                $data,
                                $this->context->customer->email,
                                $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                null,
                                null,
                                $file_attachement,
                                null,
                                _PS_MAIL_DIR_,
                                false,
                                (int)$order->id_shop
                            );
                        }
                    }

                    // updates stock in shops
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();
                        foreach ($product_list as $product) {
                            // if the available quantities depends on the physical stock
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                // synchronizes
                                StockAvailable::synchronize($product['product_id'], $order->id_shop);
                            }
                        }
                    }
                } else {
                    $error = Tools::displayError('Order creation failed');
                    Logger::addLog($error, 4, '0000002', 'Cart', (int)$order->id_cart);
                    die($error);
                }
            } // End foreach $order_detail_list
            // Use the last order as currentOrder
            $this->currentOrder = (int)$order->id;
            return true;
        } else {
            $error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
            Logger::addLog($error, 4, '0000001', 'Cart', (int)$this->context->cart->id);
            die($error);
        }
    }

    /**
    * Validate an order in database
    * Function called from a payment module
    * VERSION 1.4
    *
    * @param integer $id_cart Value
    * @param integer $id_order_state Value
    * @param float $amountPaid Amount really paid by customer (in the default currency)
    * @param string $paymentMethod Payment method (eg. 'Credit card')
    * @param string $message Message to attach to order
    */
    public function validateOrder14($id_cart, $id_order_state, $amountPaid, $codfee, $paymentMethod = 'Unknown', $message = null, $extraVars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false)
    {
        $cart = new Cart((int)($id_cart));
        // Does order already exists ?
        if (Validate::isLoadedObject($cart) && $cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $cart->secure_key) {
                die(Tools::displayError());
            }

            // Copying data from cart
            $order = new Order();
            $order->id_carrier = (int)($cart->id_carrier);
            $order->id_customer = (int)($cart->id_customer);
            $order->id_address_invoice = (int)($cart->id_address_invoice);
            $order->id_address_delivery = (int)($cart->id_address_delivery);
            $vat_address = new Address((int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
            $order->id_currency = ($currency_special ? (int)($currency_special) : (int)($cart->id_currency));
            $order->id_lang = (int)($cart->id_lang);
            $order->id_cart = (int)($cart->id);
            $customer = new Customer((int)($order->id_customer));
            $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($customer->secure_key));
            $order->payment = $paymentMethod;
            if (isset($this->name)) {
                $order->module = $this->name;
            }
            $order->recyclable = $cart->recyclable;
            $order->gift = (int)($cart->gift);
            $order->gift_message = $cart->gift_message;
            $currency = new Currency($order->id_currency);
            $order->conversion_rate = $currency->conversion_rate;
            $amountPaid = !$dont_touch_amount ? Tools::ps_round((float)($amountPaid), 2) : $amountPaid;
            $order->total_paid_real = $amountPaid;
            $order->total_products = (float)($cart->getOrderTotal(false, Cart::ONLY_PRODUCTS));
            $order->total_products_wt = (float)($cart->getOrderTotal(true, Cart::ONLY_PRODUCTS));
            $order->total_discounts = (float)(abs($cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS)));
            $order->total_shipping = (float)($cart->getOrderShippingCost() + $codfee);
            $order->carrier_tax_rate = (float)Tax::getCarrierTaxRate($cart->id_carrier, (int)$cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
            $codfee_wt = $codfee / (1 + (($order->carrier_tax_rate) / 100));
            $order->total_wrapping = (float)(abs($cart->getOrderTotal(true, Cart::ONLY_WRAPPING)));
            $order->total_paid = (float)(Tools::ps_round((float)($cart->getOrderTotal(true, Cart::BOTH) + $codfee), 2));
            $order->invoice_date = '0000-00-00 00:00:00';
            $order->delivery_date = '0000-00-00 00:00:00';
            $order->codfee = $codfee;
            // Amount paid by customer is not the right one -> Status = payment error
            // We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
            // if ($order->total_paid != $order->total_paid_real)
            // We use number_format in order to compare two string
            if (number_format($order->total_paid, 2) != number_format($order->total_paid_real, 2)) {
                $id_order_state = Configuration::get('PS_OS_ERROR');
            }
            // Creating order
            if ($cart->OrderExists() == false) {
                $result = $order->add();
            } else {
                $errorMessage = Tools::displayError('An order has already been placed using this cart.');
                Logger::addLog($errorMessage, 4, '0000001', 'Cart', (int)$order->id_cart);
                die($errorMessage);
            }

            // Next !
            if ($result && isset($order->id)) {
                if (!$secure_key) {
                    $message .= $this->l('Warning : the secure key is empty, check your payment account before validation');
                }
                // Optional message to attach to this order
                if (isset($message) && !empty($message)) {
                    $msg = new Message();
                    $message = strip_tags($message, '<br>');
                    if (Validate::isCleanHtml($message)) {
                        $msg->message = $message;
                        $msg->id_order = (int)$order->id;
                        $msg->private = 1;
                        $msg->add();
                    }
                }

                // Insert products from cart into order_detail table
                $products = $cart->getProducts();
                $productsList = '';
                $db = Db::getInstance();
                $query = 'INSERT INTO `'._DB_PREFIX_.'order_detail`
                    (`id_order`, `product_id`, `product_attribute_id`, `product_name`, `product_quantity`, `product_quantity_in_stock`, `product_price`, `reduction_percent`, `reduction_amount`, `group_reduction`, `product_quantity_discount`, `product_ean13`, `product_upc`, `product_reference`, `product_supplier_reference`, `product_weight`, `tax_name`, `tax_rate`, `ecotax`, `ecotax_tax_rate`, `discount_quantity_applied`, `download_deadline`, `download_hash`)
                VALUES ';

                $customizedDatas = Product::getAllCustomizedDatas((int)($order->id_cart));
                Product::addCustomizationPrice($products, $customizedDatas);
                $outOfStock = false;

                $storeAllTaxes = array();
                $specificPrice = null;

                foreach ($products as $key => $product) {
                    $productQuantity = (int)(Product::getQuantity((int)($product['id_product']), ($product['id_product_attribute'] ? (int)($product['id_product_attribute']) : null)));
                    $quantityInStock = ($productQuantity - (int)($product['cart_quantity']) < 0) ? $productQuantity : (int)($product['cart_quantity']);
                    if ($id_order_state != Configuration::get('PS_OS_CANCELED') && $id_order_state != Configuration::get('PS_OS_ERROR')) {
                        if (Product::updateQuantity($product, (int)$order->id)) {
                            $product['stock_quantity'] -= $product['cart_quantity'];
                        }
                        if ($product['stock_quantity'] < 0 && Configuration::get('PS_STOCK_MANAGEMENT')) {
                            $outOfStock = true;
                        }

                        Product::updateDefaultAttribute($product['id_product']);
                    }
                    $price = Product::getPriceStatic((int)($product['id_product']), false, ($product['id_product_attribute'] ? (int)($product['id_product_attribute']) : null), 6, null, false, true, $product['cart_quantity'], false, (int)($order->id_customer), (int)($order->id_cart), (int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                    $price_wt = Product::getPriceStatic((int)($product['id_product']), true, ($product['id_product_attribute'] ? (int)($product['id_product_attribute']) : null), 2, null, false, true, $product['cart_quantity'], false, (int)($order->id_customer), (int)($order->id_cart), (int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));

                    /* Store tax info */
                    $id_country = (int)Country::getDefaultCountryId();
                    $id_state = 0;
                    $id_county = 0;
                    $id_address = $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
                    $address_infos = Address::getCountryAndState($id_address);
                    if ($address_infos['id_country']) {
                        $id_country = (int)($address_infos['id_country']);
                        $id_state = (int)$address_infos['id_state'];
                        $id_county = (int)County::getIdCountyByZipCode($address_infos['id_state'], $address_infos['postcode']);
                    }
                    $allTaxes = TaxRulesGroup::getTaxes((int)Product::getIdTaxRulesGroupByIdProduct((int)$product['id_product']), $id_country, $id_state, $id_county);
                    $nTax = 0;
                    foreach ($allTaxes as $res) {
                        if (!isset($storeAllTaxes[$res->id])) {
                            $storeAllTaxes[$res->id] = array();
                            $storeAllTaxes[$res->id]['amount'] = 0;
                        }
                        $storeAllTaxes[$res->id]['name'] = $res->name[(int)$order->id_lang];
                        $storeAllTaxes[$res->id]['rate'] = $res->rate;

                        if (!$nTax++) {
                            $storeAllTaxes[$res->id]['amount'] += ($price * ($res->rate * 0.01)) * $product['cart_quantity'];
                        } else {
                            $priceTmp = $price_wt / (1 + ($res->rate * 0.01));
                            $storeAllTaxes[$res->id]['amount'] += ($price_wt - $priceTmp) * $product['cart_quantity'];
                        }
                    }
                    /* End */

                    // Add some informations for virtual products
                    $deadline = '0000-00-00 00:00:00';
                    $download_hash = null;
                    if ($id_product_download = ProductDownload::getIdFromIdProduct((int)($product['id_product']))) {
                        $productDownload = new ProductDownload((int)($id_product_download));
                        $deadline = $productDownload->getDeadLine();
                        $download_hash = $productDownload->getHash();
                    }

                    // Exclude VAT
                    if (Tax::excludeTaxeOption()) {
                        $product['tax'] = 0;
                        $product['rate'] = 0;
                        $tax_rate = 0;
                    } else {
                        $tax_rate = Tax::getProductTaxRate((int)($product['id_product']), $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                    }

                    $ecotaxTaxRate = 0;
                    if (!empty($product['ecotax'])) {
                        $ecotaxTaxRate = Tax::getProductEcotaxRate($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                    }

                    $product_price = (float)Product::getPriceStatic((int)($product['id_product']), false, ($product['id_product_attribute'] ? (int)($product['id_product_attribute']) : null), (Product::getTaxCalculationMethod((int)($order->id_customer)) == PS_TAX_EXC ? 2 : 6), null, false, false, $product['cart_quantity'], false, (int)($order->id_customer), (int)($order->id_cart), (int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}), $specificPrice, false, false);

                    $group_reduction = (float)GroupReduction::getValueForProduct((int)$product['id_product'], $customer->id_default_group) * 100;
                    if (!$group_reduction) {
                        $group_reduction = Group::getReduction((int)$order->id_customer);
                    }

                    $quantityDiscount = SpecificPrice::getQuantityDiscount((int)$product['id_product'], Shop::getCurrentShop(), (int)$cart->id_currency, (int)$vat_address->id_country, (int)$customer->id_default_group, (int)$product['cart_quantity']);
                    $unitPrice = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, 1, false, (int)$order->id_customer, null, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                    $quantityDiscountValue = $quantityDiscount ? ((Product::getTaxCalculationMethod((int)$order->id_customer) == PS_TAX_EXC ? Tools::ps_round($unitPrice, 2) : $unitPrice) - $quantityDiscount['price'] * (1 + $tax_rate / 100)) : 0.00;
                    $query .= '('.(int)($order->id).',
                        '.(int)($product['id_product']).',
                        '.(isset($product['id_product_attribute']) ? (int)($product['id_product_attribute']) : 'null').',
                        \''.pSQL($product['name'].((isset($product['attributes']) && $product['attributes'] != null) ? ' - '.$product['attributes'] : '')).'\',
                        '.(int)($product['cart_quantity']).',
                        '.$quantityInStock.',
                        '.$product_price.',
                        '.(float)(($specificPrice && $specificPrice['reduction_type'] == 'percentage') ? $specificPrice['reduction'] * 100 : 0.00).',
                        '.(float)(($specificPrice && $specificPrice['reduction_type'] == 'amount') ? (!$specificPrice['id_currency'] ? Tools::convertPrice($specificPrice['reduction'], $order->id_currency) : $specificPrice['reduction']) : 0.00).',
                        '.$group_reduction.',
                        '.$quantityDiscountValue.',
                        '.(empty($product['ean13']) ? 'null' : '\''.pSQL($product['ean13']).'\'').',
                        '.(empty($product['upc']) ? 'null' : '\''.pSQL($product['upc']).'\'').',
                        '.(empty($product['reference']) ? 'null' : '\''.pSQL($product['reference']).'\'').',
                        '.(empty($product['supplier_reference']) ? 'null' : '\''.pSQL($product['supplier_reference']).'\'').',
                        '.(float)($product['id_product_attribute'] ? $product['weight_attribute'] : $product['weight']).',
                        \''.(empty($tax_rate) ? '' : pSQL($product['tax'])).'\',
                        '.(float)($tax_rate).',
                        '.(float)Tools::convertPrice((float)$product['ecotax'], (int)$order->id_currency).',
                        '.(float)$ecotaxTaxRate.',
                        '.(($specificPrice && $specificPrice['from_quantity'] > 1) ? 1 : 0).',
                        \''.pSQL($deadline).'\',
                        \''.pSQL($download_hash).'\'),';

                    $customizationQuantity = 0;
                    if (isset($customizedDatas[$product['id_product']][$product['id_product_attribute']])) {
                        $customizationText = '';
                        foreach ($customizedDatas[$product['id_product']][$product['id_product_attribute']] as $customization) {
                            if (isset($customization['datas'][_CUSTOMIZE_TEXTFIELD_])) {
                                foreach ($customization['datas'][_CUSTOMIZE_TEXTFIELD_] as $text) {
                                    $customizationText .= $text['name'].':'.' '.$text['value'].'<br />';
                                }
                            }

                            if (isset($customization['datas'][_CUSTOMIZE_FILE_])) {
                                $customizationText .= count($customization['datas'][_CUSTOMIZE_FILE_]).' '.Tools::displayError('image(s)').'<br />';
                            }

                            $customizationText .= '---<br />';
                        }

                        $customizationText = rtrim($customizationText, '---<br />');

                        $customizationQuantity = (int)($product['customizationQuantityTotal']);
                        $productsList .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
                            <td style="padding: 0.6em 0.4em;">'.$product['reference'].'</td>
                            <td style="padding: 0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').' - '.$this->l('Customized').(!empty($customizationText) ? ' - '.$customizationText : '').'</strong></td>
                            <td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? $price : $price_wt, $currency, false).'</td>
                            <td style="padding: 0.6em 0.4em; text-align: center;">'.$customizationQuantity.'</td>
                            <td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice($customizationQuantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? $price : $price_wt), $currency, false).'</td>
                        </tr>';
                    }

                    if (!$customizationQuantity || (int)$product['cart_quantity'] > $customizationQuantity) {
                        $productsList .= '<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
                            <td style="padding: 0.6em 0.4em;">'.$product['reference'].'</td>
                            <td style="padding: 0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : '').'</strong></td>
                            <td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? $price : $price_wt, $currency, false).'</td>
                            <td style="padding: 0.6em 0.4em; text-align: center;">'.((int)($product['cart_quantity']) - $customizationQuantity).'</td>
                            <td style="padding: 0.6em 0.4em; text-align: right;">'.Tools::displayPrice(((int)($product['cart_quantity']) - $customizationQuantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? $price : $price_wt), $currency, false).'</td>
                        </tr>';
                    }
                } // end foreach ($products)
                $query = rtrim($query, ',');
                $result = $db->Execute($query);

                /* Add carrier tax */
                $shippingCostTaxExcl = $cart->getOrderShippingCost((int)$order->id_carrier, false) + $codfee_wt;
                $allTaxes = TaxRulesGroup::getTaxes((int)Carrier::getIdTaxRulesGroupByIdCarrier((int)$order->id_carrier), $id_country, $id_state, $id_county);
                $nTax = 0;

                foreach ($allTaxes as $res) {
                    if (!isset($res->id)) {
                        continue;
                    }

                    if (!isset($storeAllTaxes[$res->id])) {
                        $storeAllTaxes[$res->id] = array();
                    }
                    if (!isset($storeAllTaxes[$res->id]['amount'])) {
                        $storeAllTaxes[$res->id]['amount'] = 0;
                    }
                    $storeAllTaxes[$res->id]['name'] = $res->name[(int)$order->id_lang];
                    $storeAllTaxes[$res->id]['rate'] = $res->rate;

                    if (!$nTax++) {
                        $storeAllTaxes[$res->id]['amount'] += ($shippingCostTaxExcl * (1 + ($res->rate * 0.01))) - $shippingCostTaxExcl;
                    } else {
                        $priceTmp = $order->total_shipping / (1 + ($res->rate * 0.01));
                        $storeAllTaxes[$res->id]['amount'] += $order->total_shipping - $priceTmp;
                    }
                }

                /* Store taxes */
                foreach ($storeAllTaxes as $t) {
                    Db::getInstance()->Execute('
                    INSERT INTO '._DB_PREFIX_.'order_tax (id_order, tax_name, tax_rate, amount)
                    VALUES ('.(int)$order->id.', \''.pSQL($t['name']).'\', '.(float)($t['rate']).', '.(float)$t['amount'].')');
                }

                // Insert discounts from cart into order_discount table
                $discounts = $cart->getDiscounts();
                $discountsList = '';
                $total_discount_value = 0;
                $shrunk = false;
                $params = array();
                foreach ($discounts as $discount) {
                    $objDiscount = new Discount((int)$discount['id_discount']);
                    $value = $objDiscount->getValue(count($discounts), $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS), $order->total_shipping, $cart->id);
                    if ($objDiscount->id_discount_type == 2 && in_array($objDiscount->behavior_not_exhausted, array(1,2))) {
                        $shrunk = true;
                    }

                    if ($shrunk && ($total_discount_value + $value) > ($order->total_products_wt + $order->total_shipping + $order->total_wrapping)) {
                        $amount_to_add = ($order->total_products_wt + $order->total_shipping + $order->total_wrapping) - $total_discount_value;
                        if ($objDiscount->id_discount_type == 2 && $objDiscount->behavior_not_exhausted == 2) {
                            $voucher = new Discount();
                            foreach ($objDiscount as $key => $discountValue) {
                                $voucher->$key = $discountValue;
                            }
                            $voucher->name = 'VSRK'.(int)$order->id_customer.'O'.(int)$order->id;
                            $voucher->value = (float)$value - $amount_to_add;
                            $voucher->add();
                            $params['{voucher_amount}'] = Tools::displayPrice($voucher->value, $currency, false);
                            $params['{voucher_num}'] = $voucher->name;
                            $params['{firstname}'] = $customer->firstname;
                            $params['{lastname}'] = $customer->lastname;
                            $params['{id_order}'] = $order->id;
                            @Mail::Send((int)$order->id_lang, 'voucher', Mail::l('New voucher regarding your order #', (int)$order->id_lang).$order->id, $params, $customer->email, $customer->firstname.' '.$customer->lastname);
                        }
                    } else {
                        $amount_to_add = $value;
                    }
                    $order->addDiscount($objDiscount->id, $objDiscount->name, $amount_to_add);
                    $total_discount_value += $amount_to_add;
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED')) {
                        $objDiscount->quantity = $objDiscount->quantity - 1;
                    }
                    $objDiscount->update();

                    $discountsList .= '<tr style="background-color:#EBECEE;">
                            <td colspan="4" style="padding: 0.6em 0.4em; text-align: right;">'.$this->l('Voucher code:').' '.$objDiscount->name.'</td>
                            <td style="padding: 0.6em 0.4em; text-align: right;">'.($value != 0.00 ? '-' : '').Tools::displayPrice($value, $currency, false).'</td>
                    </tr>';
                }

                // Specify order id for message
                $oldMessage = Message::getMessageByCartId((int)($cart->id));
                if ($oldMessage) {
                    $message = new Message((int)$oldMessage['id_message']);
                    $message->id_order = (int)$order->id;
                    $message->update();
                }

                // Hook new order
                $orderStatus = new OrderState((int)$id_order_state, (int)$order->id_lang);
                if (Validate::isLoadedObject($orderStatus)) {
                    Hook::newOrder($cart, $order, $customer, $currency, $orderStatus);
                    foreach ($cart->getProducts() as $product) {
                        if ($orderStatus->logable) {
                            ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);
                        }
                    }
                }

                if (isset($outOfStock) && $outOfStock && Configuration::get('PS_STOCK_MANAGEMENT')) {
                    $history = new OrderHistory();
                    $history->id_order = (int)$order->id;
                    $history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), (int)$order->id);
                    $history->addWithemail();
                }

                // Set order state in order history ONLY even if the "out of stock" status has not been yet reached
                // So you migth have two order states
                $new_history = new OrderHistory();
                $new_history->id_order = (int)$order->id;
                $new_history->changeIdOrderState((int)$id_order_state, (int)$order->id);
                $new_history->addWithemail(true, $extraVars);

                // Order is reloaded because the status just changed
                $order = new Order($order->id);

                // Send an e-mail to customer
                if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $customer->id) {
                    $invoice = new Address((int)($order->id_address_invoice));
                    $delivery = new Address((int)($order->id_address_delivery));
                    $carrier = new Carrier((int)($order->id_carrier), $order->id_lang);
                    $delivery_state = $delivery->id_state ? new State((int)($delivery->id_state)) : false;
                    $invoice_state = $invoice->id_state ? new State((int)($invoice->id_state)) : false;

                    $data = array(
                    '{firstname}' => $customer->firstname,
                    '{lastname}' => $customer->lastname,
                    '{email}' => $customer->email,
                    '{delivery_block_txt}' => $this->_getFormatedAddress14($delivery, "\n"),
                    '{invoice_block_txt}' => $this->_getFormatedAddress14($invoice, "\n"),
                    '{delivery_block_html}' => $this->_getFormatedAddress14($delivery, '<br />', array('firstname' => '<span style="color:#DB3484; font-weight:bold;">%s</span>', 'lastname'  => '<span style="color:#DB3484; font-weight:bold;">%s</span>')),
                    '{invoice_block_html}' => $this->_getFormatedAddress14($invoice, '<br />', array('firstname' => '<span style="color:#DB3484; font-weight:bold;">%s</span>', 'lastname'  => '<span style="color:#DB3484; font-weight:bold;">%s</span>')),
                    '{delivery_company}' => $delivery->company,
                    '{delivery_firstname}' => $delivery->firstname,
                    '{delivery_lastname}' => $delivery->lastname,
                    '{delivery_address1}' => $delivery->address1,
                    '{delivery_address2}' => $delivery->address2,
                    '{delivery_city}' => $delivery->city,
                    '{delivery_postal_code}' => $delivery->postcode,
                    '{delivery_country}' => $delivery->country,
                    '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                    '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
                    '{delivery_other}' => $delivery->other,
                    '{invoice_company}' => $invoice->company,
                    '{invoice_vat_number}' => $invoice->vat_number,
                    '{invoice_firstname}' => $invoice->firstname,
                    '{invoice_lastname}' => $invoice->lastname,
                    '{invoice_address2}' => $invoice->address2,
                    '{invoice_address1}' => $invoice->address1,
                    '{invoice_city}' => $invoice->city,
                    '{invoice_postal_code}' => $invoice->postcode,
                    '{invoice_country}' => $invoice->country,
                    '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                    '{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
                    '{invoice_other}' => $invoice->other,
                    '{order_name}' => sprintf('#%06d0', (int)($order->id)),
                    '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), (int)($order->id_lang), 1),
                    '{carrier}' => $carrier->name,
                    '{payment}' => Tools::substr($order->payment, 0, 45),
                    '{products}' => $productsList,
                    '{discounts}' => $discountsList,
                    '{total_paid}' => Tools::displayPrice($order->total_paid, $currency, false),
                    '{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $currency, false),
                    '{total_discounts}' => Tools::displayPrice($order->total_discounts, $currency, false),
                    '{total_shipping}' => Tools::displayPrice($order->total_shipping, $currency, false).'<br />'.sprintf($this->l('COD fee included:').' '.Tools::displayPrice($codfee, $this->context->currency, false)),
                    '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $currency, false));

                    if (is_array($extraVars)) {
                        $data = array_merge($data, $extraVars);
                    }

                    // Join PDF invoice
                    $fileAttachment = array();
                    if ((int)(Configuration::get('PS_INVOICE')) && Validate::isLoadedObject($orderStatus) && $orderStatus->invoice && $order->invoice_number) {
                        $fileAttachment['content'] = PDF::invoice($order, 'S');
                        $fileAttachment['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)($order->id_lang)).sprintf('%06d', $order->invoice_number).'.pdf';
                        $fileAttachment['mime'] = 'application/pdf';
                    } else {
                        $fileAttachment = null;
                    }

                    if (Validate::isEmail($customer->email)) {
                        Mail::Send((int)$order->id_lang, 'order_conf', Mail::l('Order confirmation', (int)$order->id_lang), $data, $customer->email, $customer->firstname.' '.$customer->lastname, null, null, $fileAttachment);
                    }
                }
                $this->currentOrder = (int)$order->id;
                return true;
            } else {
                $errorMessage = Tools::displayError('Order creation failed');
                Logger::addLog($errorMessage, 4, '0000002', 'Cart', (int)$order->id_cart);
                die($errorMessage);
            }
        } else {
            $errorMessage = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
            Logger::addLog($errorMessage, 4, '0000001', 'Cart', (int)$cart->id);
            die($errorMessage);
        }
    }

    public function isModuleActive($name_module, $function_exist = false)
    {
        if (version_compare(_PS_VERSION_, '1.7.2', '>=')) {
            return false;
        }
        if (Module::isInstalled($name_module)) {
            $module = Module::getInstanceByName($name_module);
            if ((Validate::isLoadedObject($module) && $module->active) 
                || (Validate::isLoadedObject($module) && $name_module == 'prettyurls')
                || (Validate::isLoadedObject($module) && $name_module == 'purls')
                || (Validate::isLoadedObject($module) && $name_module == 'fsadvancedurl')
                || (Validate::isLoadedObject($module) && $name_module == 'smartseoplus')
            ) {
                if ($function_exist) {
                    if (method_exists($module, $function_exist)) {
                        return $module;
                    } else {
                        return false;
                    }
                }
                return $module;
            }
        }
        return false;
    }
}
