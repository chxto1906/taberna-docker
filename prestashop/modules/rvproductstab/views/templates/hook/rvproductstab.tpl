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
	<div id="rvproductstab" class="products_block clearfix container">
		<div class="products_block_inner">
			<div class="rv-titletab">
				<h2 class="tab_title hidden-md-down">{l s='Top Products' mod='rvproductstab'}</h2>
				<ul id="rvproduct-tabs" class="nav nav-tabs">
					{$count=0}
					{foreach from=$rvproducttab item=productTab name=rvProductTab}
					<li class="nav-item {if $smarty.foreach.rvProductTab.first}first_item{elseif $smarty.foreach.rvProductTab.last}last_item{else}{/if}">
						<a class="nav-link {if $smarty.foreach.rvProductTab.first}active{/if}" href="#tab_{$productTab.id}" data-toggle="tab">{$productTab.name}</a>
					</li>
					{$count= $count+1}
					{/foreach}
				</ul>
			</div>
			<div class="tab-content">
				{foreach from=$rvproducttab item=productTab name=rvProductTab}
				<div id="tab_{$productTab.id}" class="tab_content tab-pane {if $smarty.foreach.rvProductTab.first}active{/if}">
					{if isset($productTab.productInfo) && $productTab.productInfo}
					<div class="block_content row">
						<!-- Custom start -->
						{if $slider == 1}
						<div id="rv_{$productTab.id}" class="owl-carousel product_list">
							{else}
							<div class="product_list grid">
								{/if}		
								<!-- Custom End -->
								{$counter = 1}
								{$lastproduct = {count($productTab.productInfo)}}
								{foreach from=$productTab.productInfo item=product name=rvProductTab}
								{if $slider == 1 && $counter%2 ==1} 
								<div class="multiple-item">
									{/if}
									<div class="{if $slider == 1}item {else} ajax_block_product col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 {/if} product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
										{include file="catalog/_partials/miniatures/product-slider.tpl" product=$product}
									</div>
									{if $slider == 1  && ($counter%2 ==0 || $counter == $lastproduct)}
								</div>
								{/if}
								{$counter = $counter+1}
								{/foreach}
							</div>
						</div>
						{else}
						<div class="alert alert-info">{l s='No Products in current tab at this time.' mod='rvproductstab'}</div>
						{/if}
					</div>
					{/foreach}
				</div>
			</div>
		</div>