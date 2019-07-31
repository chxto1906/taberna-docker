{*
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="rvfooterstoreinfo" class="footer-block col-lg-12 rv-animate-element bottom-to-top">
	<h4 class="title_block">
		{l s='About Sports' mod='rvfooterstoreinfo'}
	</h4>
	<div class="block_content toggle-footer">
		<div class="storeinfo_img">
			<a href="{$urls.base_url}" title="{$shop.name}">
				<img src="{$rvfooterstoreinfo_img}" alt="{$shop.name}" />
			</a>
		</div>
		
		{if !empty($rvfooterstoreinfo_desc)}
	        <p class="storeinfo-desc">
    	    	{$rvfooterstoreinfo_desc}
    		</p>
        {/if}
	</div>
</div>