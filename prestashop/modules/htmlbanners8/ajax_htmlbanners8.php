<?php
/**
* 2007-2018 PrestaShop
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
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('htmlbanners8.php');

$hero_slider = new HtmlBanners8();
$slides = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $hero_slider->secure_key || !Tools::getValue('action')) {
    die(1);
}

if (Tools::getValue('action') == 'updateSlidesPosition' && Tools::getValue('slides')) {
    $slides = Tools::getValue('slides');

    foreach ($slides as $position => $id_slide) {
        $res = Db::getInstance()->execute(
            '
			UPDATE `'._DB_PREFIX_.'htmlbanners8_slides` SET `position` = '.(int)$position.'
			WHERE `id_htmlbanners8_slides` = '.(int)$id_slide
        );
    }

    $hero_slider->clearCache();
}