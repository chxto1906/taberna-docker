<?php
/**
*  @author    RV Templates
*  @copyright 2015-2017 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

include_once('../../config/config.inc.php');
require_once(dirname(__FILE__).'/classes/RvWishList.php');
require_once(dirname(__FILE__).'/rvwishlistblock.php');

$context = Context::getContext();
$action = Tools::getValue('action');
$add = (!strcmp($action, 'add') ? 1 : 0);
$delete = (!strcmp($action, 'delete') ? 1 : 0);
$id_wishlist = (int)Tools::getValue('id_wishlist');
$id_product = (int)Tools::getValue('id_product');
$quantity = (int)Tools::getValue('quantity');
if (Tools::getIsset('group')) {
    $id_product_attribute = (int)Product::getIdProductAttributesByIdAttributes($id_product, Tools::getValue('group'));
} else {
    $id_product_attribute=0;
}

/* Instance of module class for translations */
$module = new RvWishListBlock();

if (Configuration::get('PS_TOKEN_ENABLE') == 1 && strcmp(Tools::getToken(false), Tools::getValue('token')) && $context->customer->isLogged() === true) {
    echo $module->l('Invalid token', 'cart');
}
if ($context->customer->isLogged()) {
    if ($id_wishlist && RvWishList::exists($id_wishlist, $context->customer->id) === true) {
        $context->cookie->id_wishlist = (int)$id_wishlist;
    }

    if ((int)$context->cookie->id_wishlist > 0 && !RvWishList::exists($context->cookie->id_wishlist, $context->customer->id)) {
        $context->cookie->id_wishlist = '';
    }

    if (empty($context->cookie->id_wishlist) === true || $context->cookie->id_wishlist == false) {
        $context->smarty->assign('error', true);
    }
    if (($add || $delete) && empty($id_product) === false) {
        if (!isset($context->cookie->id_wishlist) || $context->cookie->id_wishlist == '') {
            $wishlist = new RvWishList();
            $wishlist->id_shop = $context->shop->id;
            $wishlist->id_shop_group = $context->shop->id_shop_group;
            $wishlist->default = 1;

            $mod_wishlist = new RvWishListBlock();
            $wishlist->name = $mod_wishlist->default_wishlist_name;
            $wishlist->id_customer = (int)$context->customer->id;
            list($us, $s) = explode(' ', microtime());
            srand($s * $us);
            $wishlist->token = Tools::strtoupper(Tools::substr(sha1(uniqid(rand(), true)._COOKIE_KEY_.$context->customer->id), 0, 16));
            $wishlist->add();
            $context->cookie->id_wishlist = (int)$wishlist->id;
        }
        if ($add && $quantity) {
            RvWishList::addProduct($context->cookie->id_wishlist, $context->customer->id, $id_product, $id_product_attribute, $quantity);
            echo (int)Db::getInstance()->getValue('SELECT count(id_wishlist_product) FROM '._DB_PREFIX_.'rvwishlist w, '._DB_PREFIX_.'rvwishlist_product wp where w.id_wishlist = wp.id_wishlist and w.id_customer='.(int)$context->customer->id);
            die();
        } else if ($delete) {
            RvWishList::removeProduct($context->cookie->id_wishlist, $context->customer->id, $id_product, $id_product_attribute);
        }
    }
    $context->smarty->assign('products', RvWishList::getProductByIdCustomer($context->cookie->id_wishlist, $context->customer->id, $context->language->id, null, true));
    $context->smarty->assign('link', $context->link);
    if (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/rvwishlistblock/views/templates/hook/rvwishlist-ajax.tpl')) {
        $context->smarty->display(_PS_THEME_DIR_.'modules/rvwishlistblock/views/templates/hook/rvwishlist-ajax.tpl');
    } elseif (Tools::file_exists_cache(dirname(__FILE__).'/views/templates/hook/rvwishlist-ajax.tpl')) {
        $context->smarty->display(dirname(__FILE__).'/views/templates/hook/rvwishlist-ajax.tpl');
    } else {
        echo $module->l('No template found', 'cart');
    }
} else {
    echo $module->l('You must be logged in to manage your wishlist.', 'cart');
}
