<?php
/*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 */

require_once _PS_MODULE_DIR_ . 'sincronizacionwebservices/controllers/front/FacturaDB.php';
require_once _PS_ROOT_DIR_ . '/logs/LoggerTools.php';

class Ps_CashondeliveryValidationModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $log = null;

    public function postProcess()
    {
        $this->log = new LoggerTools();

        if ($this->context->cart->id_customer == 0 || $this->context->cart->id_address_delivery == 0 || $this->context->cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');
        }

        // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'ps_cashondelivery') {
                $authorized = true;
                break;
            }
        }
        if (!$authorized) {
            die(Tools::displayError('This payment method is not available.'));
        }
        $customer = new Customer($this->context->cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');
        }
        $customer = new Customer((int)$this->context->cart->id_customer);
        $total = $this->context->cart->getOrderTotal(true, Cart::BOTH);
        $this->module->validateOrder((int)$this->context->cart->id, Configuration::get('PS_OS_PREPARATION'), $total, $this->module->displayName, null, array(), null, false, $customer->secure_key);


        $facturaDB = new sincronizacionwebservicesFacturaDBModuleFrontController();
        $resFacturacion = $facturaDB->processFacturacionDB($order,$cart,$write);

        $resValidateArticulos = $this->validateArticulosSAP($order);
        $resValidateHorario = $this->isOpen();
        if ($resValidateArticulos){
            die(Tools::displayError($resValidateArticulos));
        }
        if (!$resValidateHorario){
            die(Tools::displayError("Tienda se encuentra fuera de su horario de atención."));
        }


        //$resFacturacion = $this->processFacturacionDB($order,$cart,$write);
        $this->log->add("processFacturacionDB : ".$resFacturacion);
        if (!$resFacturacion) {
            $this->changeOrderStatus($order, 8);
            die(Tools::displayError('Ocurrió un inconveniente en el proceso de pago. Se ha cancelado tu pedido. Disculpa las molestias. Vuelve a intentarlo más tarde.'));
        } else {
            //$this->addNumGuiaMiPilotoOrder($order,$guia_numero);
            Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?key='.$customer->secure_key.'&id_cart='.(int)$this->context->cart->id.'&id_module='.(int)$this->module->id.'&id_order='.(int)$this->module->currentOrder);
        }
        
    }

    private function changeOrderStatus($order, $state) {
        ShopUrl::cacheMainDomainForShop((int) $order->id_shop);
        $order_state = new OrderState($state);

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
    }
}
