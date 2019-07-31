<?php
/**
*  @author    RV Templates
*  @copyright 2015-2017 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/classes/RvWishList.php');
require_once(dirname(__FILE__).'/rvwishlistblock.php');

$context = Context::getContext();

/* Instance of module class for translations */
$module = new RvWishListBlock();

if (Configuration::get('PS_TOKEN_ENABLE') == 1 && strcmp(Tools::getToken(false), Tools::getValue('token')) && $context->customer->isLogged() === true) {
    exit($module->l('invalid token', 'sendwishlist'));
}
if ($context->customer->isLogged()) {
    $id_wishlist = (int)Tools::getValue('id_wishlist');
    if (empty($id_wishlist) === true) {
        exit($module->l('Invalid wishlist', 'sendwishlist'));
    }
    $email_list = Tools::getValue('emails');
    $sendStatus='';
    foreach ($email_list as $key => $value) {
        $to = $value;
        $wishlist = RvWishList::exists($id_wishlist, $context->customer->id, true);
        if ($wishlist === false) {
            exit($module->l('Invalid wishlist', 'sendwishlist'));
        }
        if (RvWishList::addEmail($id_wishlist, $to) === false) {
            exit($module->l('Wishlist send error', 'sendwishlist'));
        }

        $toName = strval(Configuration::get('PS_SHOP_NAME'));
        $customer = $context->customer;
        if (Validate::isLoadedObject($customer)) {
            if (Mail::Send(
                $context->language->id,
                'wishlist',
                sprintf(Mail::l('Message from %1$s %2$s', $context->language->id), $customer->lastname, $customer->firstname),
                array(
                    '{lastname}' => $customer->lastname,
                    '{firstname}' => $customer->firstname,
                    '{wishlist}' => $wishlist['name'],
                    '{message}' => $context->link->getModuleLink('rvwishlistblock', 'view', array('token' => $wishlist['token']))
                    ),
                $to,
                $toName,
                null,
                $customer->firstname.' '.$customer->lastname,
                null,
                null,
                dirname(__FILE__).'/mails/'
            )) {
                $sendStatus[$key] = 1;
            } else {
                $sendStatus[$key] = 0;
            }
        }
    }
    echo Tools::jsonEncode($sendStatus);
}
