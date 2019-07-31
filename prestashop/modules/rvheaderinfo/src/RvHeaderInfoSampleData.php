<?php
/**
* 2007-2016 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2016 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once _PS_MODULE_DIR_.'rvheaderinfo/classes/rvheaderinfoClass.php';

class RvHeaderInfoSampleData
{
	public function initData($base_url)
	{
        $return = true;
        $tab_texts = array(
            array(
                'text' => '<div class="welcome-info">Welcome to Smart Electro store</div>'
            ),
        );

        $shops_ids = Shop::getShops(true, null, true);

        foreach ($tab_texts as $tab) {
            $rvheaderinfo = new rvheaderinfoClass();
            foreach (Language::getLanguages(false) as $lang) {
                $rvheaderinfo->text[$lang['id_lang']] = $tab['text'];
            }
            foreach ($shops_ids as $id_shop) {
                $rvheaderinfo->id_shop = $id_shop;
                $return &= $rvheaderinfo->add();
            }
        }

        return $return;
	}
}
