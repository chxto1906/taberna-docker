<?php

require_once _PS_MODULE_DIR_ . 'payphone/lib/common/Constants.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/api/PayphoneButton.php';
require_once _PS_MODULE_DIR_ . 'payphone/PaymentType.php';

/**
 * Controlador que genera el formulario con los datos de pago para envíar a Payphone.
 * Genera la orden y establece su estado en pendiente
 */
class PayphonePaymentModuleFrontController extends ModuleFrontController {

    public $ssl = true;
    public $display_column_left = false;
    public $payphone;

    /**
     * @see FrontController::initContent()
     */
    public function initContent() {
        parent::initContent();
        try {

            $cart_id = $this->context->cookie->payphone_cart_id;
            if ($cart_id == false) {
                $cart = $this->context->cart;
                $this->context->cookie->payphone_cart_id = (int) $cart->id;
            } else {
                $cart = new Cart($cart_id);
                if (!Validate::isLoadedObject($cart))
                    Tools::redirect('index.php?controller=order');
                unset($this->context->cookie->payphone_cart_id);
            }
            if (!$this->module->checkCurrency($cart))
                Tools::redirect('index.php?controller=order');

            if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
                Tools::redirect('index.php?controller=order&step=1');

            // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
            $authorized = false;
            foreach (Module::getPaymentModules() as $module)
                if ($module['name'] == 'payphone') {
                    $authorized = true;
                    break;
                }
            $this->payphone = new PayPhone();
            if (!$authorized)
                die($this->module->l('This payment method is not available.', 'validation'));

            $customer = new Customer($cart->id_customer);

            if (!Validate::isLoadedObject($customer))
                Tools::redirect('index.php?controller=order&step=1');

            $currency = $this->context->currency;
            $total = (float) $cart->getOrderTotal(true);

            $products_with_tax = array();
            $products_without_tax = array();
            foreach ($cart->getProducts() as $product) {
                if ($product['rate'] == 0)
                    $products_without_tax[] = $product;
                else
                    $products_with_tax[] = $product;
            }

            $amount_with_tax = 0;
            $tax = 0;
            $amount_without_tax = 0;

            if (sizeof($products_with_tax) > 0) {
                $iva_tax = floatval($products_with_tax[0]['rate'] / 100);
                $amount_with_tax_cart = $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING, $products_with_tax);
                $amount_with_tax = $amount_with_tax_cart / ($iva_tax + 1);
                $amount_with_tax = round($amount_with_tax, 2);
                $tax = $amount_with_tax_cart - $amount_with_tax;
                $tax = round($tax, 2);
            }

            if (sizeof($products_without_tax) > 0)
                $amount_without_tax = $cart->getOrderTotal(false, Cart::BOTH_WITHOUT_SHIPPING, $products_without_tax);

            $shipping_tax = $cart->getOrderTotal(true, Cart::ONLY_SHIPPING);
            $shipping_no_tax = $cart->getOrderTotal(false, Cart::ONLY_SHIPPING);

            if (abs($shipping_tax - $shipping_no_tax) == 0)
                $amount_without_tax += $shipping_tax;
            else {
                $tax += $shipping_tax - $shipping_no_tax;
                $amount_with_tax += $shipping_no_tax;
            }

            $amount = $amount_with_tax + $tax + $amount_without_tax;
            $amount = round($amount, 2);

            if ($cart_id == false) {
                $this->module->validateOrder((int) $cart->id, Configuration::get('PS_PAYPHONE_PENDING'), $total, $this->module->displayName, NULL, array(), (int) $currency->id, false, $customer->secure_key);
                $id_order = $this->module->currentOrder;
                $order = new Order($id_order);
                if (!Validate::isLoadedObject($order))
                    Tools::redirect('index.php?controller=order&step=1');
            } else {
                $id_order = Order::getOrderByCartId($cart->id);
                $order = new Order($id_order);
                if (!Validate::isLoadedObject($order)) {
                    Tools::redirect('index.php?controller=order&step=1');
                }
            }

            $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
            $link = $this->context->link->getModuleLink('payphone', 'validation', array(), Configuration::get('PS_SSL_ENABLED'));

            $data = new PrepareSaleRequestModel();
            $data->amount = $amount * 100;
            $data->amountWithTax = $amount_with_tax * 100;
            $data->tax = $tax * 100;
            $data->amountWithoutTax = $amount_without_tax * 100;
            $data->clientTransactionId = $order->reference;
            $data->responseUrl = $link;
            $data->lang = $lang->iso_code;
            $data->currency = $currency->iso_code;
            if (!empty(Configuration::get('PAYPHONE_STORE')))
                $data->storeId = Configuration::get('PAYPHONE_STORE');

            $config = ConfigurationManager::Instance();
            $config->Token = Configuration::get('PAYPHONE_TOKEN');
        } catch (Exception $e) {
            PrestaShopLogger::addLog("Php error processing payment", 4);
            return $this->showErrors($order, $this->payphone->translate("Server error when processing the transaction"));
        }

        try {
            $pb = new PayphoneButton();
            $response = $pb->Prepare($data);
            $type = Tools::getValue('payment_type');
            if (gettype($type) == "boolean" && !$type)
                $type = 0;
            if ($type == PaymentType::CARD && isset($response->payWithCard))
                Tools::redirect($response->payWithCard);
            else
                Tools::redirect($response->payWithPayPhone);
        } catch (PayPhoneWebException $e) {
            PrestaShopLogger::addLog("PayPhone check payment error = " . $e->ErrorList[0]->message, 3);
            return $this->showErrors($order, $e->ErrorList[0]->message);
        }
    }

    function showErrors($order, $message) {
        $this->changeOrderStatus($order, Configuration::get('PS_OS_CANCELED'));
        $errors[] = $message;
        $this->context->smarty->assign([
            'errors' => $errors,
        ]);
        return $this->setTemplate('module:' . $this->module->name . '/views/templates/front/errors-messages17.tpl');
    }

    function changeOrderStatus($order, $state) {
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
