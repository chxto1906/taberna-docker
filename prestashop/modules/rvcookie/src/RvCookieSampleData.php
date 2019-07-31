<?php
/**
*  @author    RV Templates
*  @copyright 2017-2018 Copyright (c) permanent, RV Templates
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL.
*  @version   1.0.0
*
* NOTICE OF LICENSE
*
* Don't use this module on several shops. The license provided by PrestaShop Addons
* for all its modules is valid only once for a single shop.
*/

require_once _PS_MODULE_DIR_.'rvcookie/classes/rvcookieClass.php';

class RvCookieSampleData
{
	public function initData($base_url)
	{
        $return = true;
        $tab_texts = array(
            array(
                'text' => '<span>By continuing use this site, you agree to the
                                <a href="#">
                                    <strong>Privacy Policy</strong>
                                </a> 
                                and our use of cookies.
                            </span>'
            ),
        );

        $shops_ids = Shop::getShops(true, null, true);

        foreach ($tab_texts as $tab) {
            $rvcookie = new rvcookieClass();
            foreach (Language::getLanguages(false) as $lang) {
                $rvcookie->text[$lang['id_lang']] = $tab['text'];
            }
            foreach ($shops_ids as $id_shop) {
                $rvcookie->id_shop = $id_shop;
                $return &= $rvcookie->add();
            }
        }

        return $return;
	}
}
