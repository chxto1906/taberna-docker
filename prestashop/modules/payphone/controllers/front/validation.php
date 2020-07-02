<?php

require_once _PS_MODULE_DIR_ . 'payphone/lib/api/PayphoneButton.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/request/ConfirmSaleRequestModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/response/SaleGetResponseModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/common/PayPhoneWebException.php';

/**
 * Clase controladora del front-end para validar el pago después de regresar de Payphone
 */
class PayPhoneValidationModuleFrontController extends ModuleFrontController {

    private $data;
    private $payphone;

    /**
     * @see FrontController::initContent()
     */
    public function initContent() {
        $this->payphone = new PayPhone();
        if ($this->payphone->active) {
            parent::initContent();
            if (Tools::getValue('id_order') != null)
                $this->checkPayment(Tools::getValue('id_order'));
            else
                $this->paymentResponse();
        }
    }

    /**
     * Verificar si el pago ha sido aprobado o cancelado estando en estado pendiente
     * @param type $id_order
     */
    private function checkPayment($id_order) {
        $data = $this->payphone->updatePendingTransaction($id_order);
        if (gettype($data) == "boolean" && $data)
            Tools::redirect('index.php?controller=history');
        else {
            $error = $this->payphone->l($data);
            $this->context->controller->addCss(_PS_MODULE_DIR_ . 'payphone/views/css/error.css', 'all');
            PrestaShopLogger::addLog("PayPhone check payment error = " . $error, 3);
            return $this->showErrors($error);
        }
    }

    /**
     * Obtiene el resultado de la respuesta del botón de pagos
     * Actualiza la orden según la respuesta y guarda datos de la respuesta en la BD
     */
    private function paymentResponse() {
        $id = Tools::getValue('id');
        $clientTransactionId = Tools::getValue('clientTransactionId');
        $msg = Tools::getValue('msg');
        if ($msg) {
            $cart = new Cart($this->context->cookie->payphone_cart_id);
            if (Validate::isLoadedObject($cart)) {
                $order = $this->getOrder($cart->id);
                if (Validate::isLoadedObject($order)) {
                    $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
                }
            }
            return $this->showErrors($msg);
        }
        if (!$id || !$clientTransactionId) {
            PrestaShopLogger::addLog("PayPhone validation controller error = id or clienteTransactionId null", 2);
            return $this->showErrors($this->payphone->l('Error connecting to PayPhone', 'validation'));
        }

        $config = ConfigurationManager::Instance();
        $config->Token = Configuration::get('PAYPHONE_TOKEN');
        $pb = new PayphoneButton();
        try {
            $result = $pb->Confirm($id);
            $cart = new Cart($this->context->cookie->payphone_cart_id);
            if (!Validate::isLoadedObject($cart))
                return;
            $order = $this->getOrder($cart->id);
            if (!Validate::isLoadedObject($order))
                return;
            $customer = new Customer($cart->id_customer);
            if (!Validate::isLoadedObject($customer))
                return;
            if ($result->statusCode == 3) {
                $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_APPROVED'));
            } elseif ($result->statusCode == 2) {
                $this->changeOrderStatus($order, Configuration::get('PS_PAYPHONE_REJECTED'));
            }
            $data = $result;
            $write = array();
            $write['transaction_id'] = (int) $data->transactionId;
            if ($data->statusCode == 2)
                $write['message'] = $data->message;
            else
                $write['message'] = "Aprobada";
            $write['phone_number'] = pSQL($data->phoneNumber);
            $write['transaction_status'] = pSQL($data->transactionStatus);
            $write['client_transaction_id'] = pSQL($data->clientTransactionId);
            $write['client_user_id'] = pSQL($cart->id);
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
            Tools::redirect('index.php?controller=order-confirmation&id_cart=' . (int) $cart->id . '&id_module=' . (int) $this->module->id . '&id_order=' . $order->id . '&key=' . $customer->secure_key);
        } catch (PayPhoneWebException $e) {
            PrestaShopLogger::addLog("PayPhone validation controller error = " . $e->ErrorList[0]->message, 2);
            $this->showErrors($e->ErrorList[0]->message);
        }
    }

    /**
     * Cambia el estado de la orden
     * @param type $order
     * @param type $state
     */
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

    /**
     * Obtiene una orden dado el identicador del carrito de compra
     * @param int $cart_id
     * @return $order @see Order
     */
    private function getOrder($cart_id) {
        $order_id = Order::getOrderByCartId($cart_id);
        $order = new Order($order_id);
        if (!Validate::isLoadedObject($order)) {
            $this->errors[] = Tools::displayError('The order cannot be found within your database.');
            return;
        }
        return $order;
    }

    protected function showErrors($message) {
        $errors[] = $message;
        $this->context->smarty->assign([
            'errors' => $errors,
        ]);
        return $this->setTemplate('module:' . $this->module->name . '/views/templates/front/errors-messages17.tpl');
    }

}
