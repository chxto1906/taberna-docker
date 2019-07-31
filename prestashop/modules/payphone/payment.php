<?php
/**
 * Archivo requerido para compatibilidad con versiones anteriores
 * Ejecuta el controlador front-end Payment
 */
$useSSL = true;
require('../../config/config.inc.php');
Tools::displayFileAsDeprecated();

// init front controller in order to use Tools::redirect
$controller = new FrontController();
$controller->init();

Tools::redirect(Context::getContext()->link->getModuleLink('payphone', 'payment'));
