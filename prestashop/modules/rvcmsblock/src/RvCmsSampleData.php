<?php
/**
* 2007-2017 PrestaShop
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
* @copyright 2007-2017 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once _PS_MODULE_DIR_.'rvcmsblock/classes/rvcmsblockClass.php';

class RvCmsSampleData
{
	public function initData($base_url)
	{
        $return = true;
        $tab_texts = array(
            array(
                'text' => ' <div class="rvcms_outer row">
    <div class="rvbannerinner">
        <div class="rvcmsbanner1">
            <div class="bannercms-container col-sm-12 col-xs-12 col-lg-12">
                <a href="#">
                    <img src="../themes/PRSD81/assets/img/top-banner-1.png" alt="">
                </a>
            </div>
        </div>
    </div>
</div>'
    		),
        );

        $shops_ids = Shop::getShops(true, null, true);

        foreach ($tab_texts as $tab) {
            $rvcmsblockinfo = new rvcmsblockClass();
            foreach (Language::getLanguages(false) as $lang) {
                $rvcmsblockinfo->text[$lang['id_lang']] = $tab['text'];
            }
            foreach ($shops_ids as $id_shop) {
                $rvcmsblockinfo->id_shop = $id_shop;
                $return &= $rvcmsblockinfo->add();
            }
        }

        return $return;
	}
}
