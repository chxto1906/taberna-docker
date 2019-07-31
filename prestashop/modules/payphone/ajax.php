<?php

require_once _PS_MODULE_DIR_ . 'payphone/lib/security/PayPhoneEncrypt.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/common/Constants.php';
require_once(dirname(__FILE__) . '../../../config/config.inc.php');
require_once(dirname(__FILE__) . '../../../init.php');

switch (Tools::getValue('method')) {
    case 'updateTransaction' :
        die(Tools::jsonEncode(array('result' => 'my_value')));
        break;
    default:
        exit;
}

exit;
