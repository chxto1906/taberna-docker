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
	{* <section class="rvspecialproducts products_block clearfix col-lg-10"> *}
	<section class="products_block clearfix block bottom-to-top rv-in-viewport">
		<div class="products_block_inner">
			<div class="rv-titletab">
				<div class="tab_title">{l s='Special Products' mod='rvspecialproducts'}</div>
			</div>
			{if isset($products) && $products}
			<!-- Custom start -->
			{assign var='sliderFor' value=5} <!-- Define Number of product for SLIDER -->
			{assign var='productCount' value=count($products)}
			<!-- Custom End -->

			<div class="block_content row">

				<!-- Custom start -->
				{if $slider == 1}
				<div id="special-carousel" class="owl-carousel products">
					{else}
					<div class="products grid">
						{/if}		
						<!-- Custom End -->
						{foreach from=$products item=product name=specialProducts}
						<div class="{if $slider == 1}item{else}ajax_block_product col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 {/if} product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
							{include file="catalog/_partials/miniatures/product-slider.tpl" product=$product}
						</div>
						{/foreach}
					</div>
				</div>
				{else}
				<div class="alert alert-info">{l s='No Special Products at this time.' mod='rvspecialproducts'}</div>
				{/if}
			</div>
		</section>