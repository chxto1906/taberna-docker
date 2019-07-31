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
class CodFeeValidationModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        $cart = $this->context->cart;
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'codfee') {
                $authorized = true;
                break;
            }
        }
        
        if (!$authorized) {
            die($this->module->l('This payment method is not available.', 'validation'));
        }
        
        $cashOnDelivery = new CodFee();
        $customer = new Customer($cart->id_customer);
        $codfeeconf = new CodfeeConfiguration(Tools::getValue('c'));
        if (!$codfeeconf->id_codfee_configuration) {
            die($this->module->l('This payment method is not available.', 'validation'));
        }
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
        $order_total = (float)$cart->getOrderTotal(true, Cart::BOTH);
        $total = $fee + $order_total;
        $cart->additional_shipping_cost = $fee;

        if ($codfeeconf->type == '3') {
            $displayName = $this->module->l('Cash on pickup', 'validation');
        } else {
            $displayName = $cashOnDelivery->displayName;
        }
        
        if (version_compare(_PS_VERSION_, '1.7.4', '>=')) {
            $cashOnDelivery->validateOrder174((int)$this->context->cart->id, $codfeeconf->initial_status, $total, $fee, $displayName, null, null, null, false, $cart->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$cashOnDelivery->id.'&id_order='.$cashOnDelivery->currentOrder.'&key='.$cart->secure_key.'&c='.$codfeeconf->id);
        } elseif (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $cashOnDelivery->validateOrder17((int)$this->context->cart->id, $codfeeconf->initial_status, $total, $fee, $displayName, null, null, null, false, $cart->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$cashOnDelivery->id.'&id_order='.$cashOnDelivery->currentOrder.'&key='.$cart->secure_key.'&c='.$codfeeconf->id);
        } elseif (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $cashOnDelivery->validateOrder16((int)$this->context->cart->id, $codfeeconf->initial_status, $total, $fee, $displayName, null, null, null, false, $cart->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$cashOnDelivery->id.'&id_order='.$cashOnDelivery->currentOrder.'&key='.$cart->secure_key.'&c='.$codfeeconf->id);
        } elseif (version_compare(_PS_VERSION_, '1.5.3', '>=')) {
            $cashOnDelivery->validateOrder153((int)$this->context->cart->id, $codfeeconf->initial_status, $total, $fee, $displayName, null, null, null, false, $cart->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$cashOnDelivery->id.'&id_order='.$cashOnDelivery->currentOrder.'&key='.$cart->secure_key.'&c='.$codfeeconf->id);
        } else {
            $cashOnDelivery->validateOrder15((int)$this->context->cart->id, $codfeeconf->initial_status, $total, $fee, $displayName, null, null, null, false, $cart->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$cashOnDelivery->id.'&id_order='.$cashOnDelivery->currentOrder.'&key='.$cart->secure_key.'&c='.$codfeeconf->id);
        }
    }
}
