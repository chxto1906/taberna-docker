{*
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if $services}
	<div id="rvservices" class="block clearfix rv-animate-element bottom-to-top">
		<div class="main_content">
			<div id="rvservice-carousel" class="owl-carousel">
				{foreach from=$services item=service}
						<div class="service-inner">
							{if $service.file_name}
								<div class="service-img">
									<img src="{$IMGPATH}{$service.file_name}" alt="{$service.file_name}"/>
								</div>
							{/if}
							<div class="service-content">
								{if $service.title}
									<div class="service-text">
										{$service.title}
									</div>
								{/if}
								{if $service.desc}
									<div class="service-desc">
										{$service.desc}
									</div>
								{/if}
							</div>
						</div>
				{/foreach}
			</div>
		</div>
	</div>
{/if}
