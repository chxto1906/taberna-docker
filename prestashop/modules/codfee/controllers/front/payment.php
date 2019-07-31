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
*  @copyright 2018 idnovate
*  @license   See above
*/

/**
 * @since 1.5.0
 */
class CodFeePaymentModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;
    public $display_column_right = false;

    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        $this->imageType = 'png';
        $this->name = 'codfee';
        parent::initContent();
        
        $cart = $this->context->cart;
        
        $cashOnDelivery = new CodFee();
        $codfeeconf = new CodfeeConfiguration(Tools::getValue('c'));
        if (!$codfeeconf->id_codfee_configuration) {
            die($this->module->l('This payment method is not available.', 'payment'));
        }
        $customer = new Customer((int)$cart->id_customer);
        $group = new Group((int)$customer->id_default_group);
        if ($group->price_display_method == '1') {
            $price_display_method = false;
        } else {
            $price_display_method = true;
        }
        $fee = (float)Tools::ps_round((float)$cashOnDelivery->getFeeCost($cart, (array)$codfeeconf, $price_display_method), 2);
        if ($codfeeconf->free_on_freeshipping == '1' && $cart->getOrderTotal(true, Cart::ONLY_SHIPPING) == 0) {
            $fee = (float)0.00;
        }
        if ($codfeeconf->free_on_freeshipping == '1' && count($cart->getCartRules(CartRule::FILTER_ACTION_SHIPPING)) > 0) {
            $fee = (float)0.00;
        }
        $order_total = $cart->getOrderTotal(true, 3);
        $total = $fee + $order_total;
        $cart->additional_shipping_cost = $fee;
        $this->taxes_included = (Configuration::get('PS_TAX') == '0' ? false : true);
        
        if (file_exists(_PS_TMP_IMG_DIR_.$this->name.'_'.$codfeeconf->id_codfee_configuration.'.'.$this->imageType)) {
            $payment_logo_url = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/tmp/'.$this->name.'_'.$codfeeconf->id_codfee_configuration.'.'.$this->imageType;
        } else {
            $payment_logo_url = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/codfee/views/img/payment.png';
        }
        
        $authorized = false;

        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'codfee') {
                $authorized = true;
                break;
            }
        }
        
        if (!$authorized) {
            die($this->module->l('This payment method is not available.', 'payment'));
        }
        
        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        
        if (!$cashOnDelivery->_checkCurrency($cart)) {
            Tools::redirect('index.php?controller=order');
        }
        
        $conv_rate = (float)$this->context->currency->conversion_rate;

        $modulePretty = false;
        if ($cashOnDelivery->isModuleActive('prettyurls') || $cashOnDelivery->isModuleActive('purls') || $cashOnDelivery->isModuleActive('fsadvancedurl') || $cashOnDelivery->isModuleActive('smartseoplus')) {
            $modulePretty = true;
        }
        $this_path_ssl = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
        if ($modulePretty !== false) {
            $validation_ctrl = $this_path_ssl.'index.php?fc=module&module='.$this->name.'&controller=validation&c='.$codfeeconf->id_codfee_configuration.'&id_lang='.$this->context->language->id;
        } else {
            $validation_ctrl = $this->context->link->getModuleLink($this->name, 'validation', array('c' => $codfeeconf->id_codfee_configuration), true);
        }

        $this->context->smarty->assign(array(
            'nbProducts' => $cart->nbProducts(),
            'order_total' => number_format((float)$order_total, 2, '.', ''),
            'cartwithoutshipping' => Tools::displayPrice($cart->getOrderTotal(true, Cart::ONLY_PRODUCTS), $this->context->currency),
            'shipping_cost' => Tools::displayPrice($cart->getOrderTotal(true, Cart::ONLY_SHIPPING), $this->context->currency),
            'fee' => Tools::displayPrice($fee, $this->context->currency),
            'fee_amount' => $fee,
            'fee_type' => $codfeeconf->type,
            'free_fee' => Tools::displayPrice((float)$codfeeconf->amount_free * (float)$conv_rate, $this->context->currency),
            'free_on_freeshipping' =>$codfeeconf->free_on_freeshipping,
            'total' => Tools::displayPrice($total, $this->context->currency),
            'discounts_val' => $cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS),
            'wrapping_val' => $cart->getOrderTotal(true, Cart::ONLY_WRAPPING),
            'discounts' => Tools::displayPrice($cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS), $this->context->currency),
            'wrapping' => Tools::displayPrice($cart->getOrderTotal(true, Cart::ONLY_WRAPPING), $this->context->currency),
            'ps_version' => _PS_VERSION_,
            'payment_logo' => $payment_logo_url,
            'taxes_included' => ($this->taxes_included) ? $this->module->l('(taxes included)', 'payment') : '',
            'validation_ctrl'   => $validation_ctrl,
            'this_path' => $this->module->getPathUri(),
            'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
        ));

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $this->context->smarty->assign(array(
                'currency' => new Currency((int)$cart->id_currency)
            ));
        }

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->display(__FILE__, 'views/templates/front/codfee_val.tpl');
        } elseif (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $this->setTemplate('module:codfee/views/templates/front/codfee_val17.tpl');
        } else {
            $this->setTemplate('codfee_val.tpl');
        }
    }
}
