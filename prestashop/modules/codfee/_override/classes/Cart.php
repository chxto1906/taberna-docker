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
*  @copyright 2017 idnovate
*  @license   See above
*/

class Cart extends CartCore
{
    public function getPackageShippingCost($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null)
    {
        if (Module::isEnabled('codfee')) {
            if (count($product_list) > 0) {
                if (isset($product_list[0]['id_order'])) {
                    $order = new Order((int)$product_list[0]['id_order']);
                    if ($order->module == 'codfee') {
                        include_once(_PS_MODULE_DIR_.'codfee/codfee.php');
                        $cart = new Cart((int)$order->id_cart);
                        $codfee_wt = 0;
                        $carrier = new Carrier((int)$id_carrier);
                        $module = new Codfee();
                        $codfee = (float)Tools::ps_round($module->getFeeAmountFromOrderId($order->id), 4);
                        if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                            $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                            $codfee_wt = (float)Tools::ps_round(($codfee / (1 + (($order->carrier_tax_rate) / 100))), 4);
                        }
                        $shipping_cost_ps = parent::getPackageShippingCost($id_carrier, $use_tax, $default_country, $product_list, $id_zone);
                        if ($use_tax) {
                            return (float)Tools::ps_round($shipping_cost_ps + $codfee, 4);
                        } else {
                            return (float)Tools::ps_round($shipping_cost_ps + $codfee_wt, 4);
                        }
                    }
                }
            }
        }
        return parent::getPackageShippingCost($id_carrier, $use_tax, $default_country, $product_list, $id_zone);
    }
}
