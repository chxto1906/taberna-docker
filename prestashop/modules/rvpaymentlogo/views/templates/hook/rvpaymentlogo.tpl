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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($cms_payment_logo) && $cms_payment_logo}
	<div id="payment_logo_block_left" class="payment_logo_block col-lg-4 col-md-12">
	<span>Payment</span>
		{*
		<a href="{$link->getCMSLink($cms_payment_logo)|escape:'html'}">
			<img src="{$path}views/img/payphone_icon.png" alt="Payphone" width="40" height="25">
			<img src="{$path}views/img/visa.png" alt="Visa" width="40" height="25" />
			<img src="{$path}views/img/mastercard.png" alt="Mastercard" width="40" height="25" />
			
				<img src="{$path}views/img/paypal.png" alt="Paypal" width="40" height="25" />
				<img src="{$path}views/img/amex.png" alt="American Express" width="40" height="25" />
				<img src="{$path}views/img/discover.png" alt="Discover" width="40" height="25" />
				<img src="{$path}views/img/jcb.png" alt="JCB" width="40" height="25" />
		</a>
		*}
	</div>
{/if}