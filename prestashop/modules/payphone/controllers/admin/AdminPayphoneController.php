<?php

/**
 * Controlador del Backend para verificar el estado de la transacción
 */
class AdminPayphoneController extends ModuleAdminController {

    public function __construct() {
        if (!Tools::getValue('token'))
            die('Invalid token');
        parent::__construct();
        $this->postProcess();
    }

    /**
     * Realiza una llamada a una función del módulo para actualizar el estado de la orden en caso de estar pendiente
     * Redirecciona al historial de órdenes del panel de administración
     */
    public function postProcess() {
        $data = $this->module->updatePendingTransaction(Tools::getValue("id_order"));
        Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminOrders') . '&id_order='
                . (int) Tools::getValue("id_order") . '&vieworder');
    }
}