{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

<!-- Module Megamenu-->
<div class="container_rv_megamenu col-sm-3" id="_desktop_megamenu">
<div id="rv-menu-horizontal" class="rv-menu-horizontal clearfix">
{$id_lang = Context::getContext()->language->id}
	<div class="title-menu-mobile"><span>{l s='Top Categories' mod='rvmegamenu'}</span><i class="material-icons">&#xE5D2;</i></div>
	<div class="main-menu">
	<ul class="menu-content">
		{foreach from=$menus item=menu name=menus}
			{if isset($menu.type) && $menu.type == 'CAT' && $menu.dropdown == 1}
				{$menu.sub_menu|escape:'quotes':'UTF-8' nofilter}
			{else}
				<li class="level-1 {$menu.class|escape:'html':'UTF-8'}{if count($menu.sub_menu) > 0} parent{/if}">
					<a href="{$menu.link|escape:'html':'UTF-8'}" class="rvinnermenu">
					{if $menu.type_icon == 0 && $menu.icon != ''}
						<img class="img-icon" src="{$icon_path|escape:'html':'UTF-8'}{$menu.icon|escape:'html':'UTF-8'}" alt=""/>
					{elseif  $menu.type_icon == 1 && $menu.icon != ''}
						<i class="material-icons">{$menu.icon|escape:'html':'UTF-8'}</i>
					{/if}
					<span class="catagory">{$menu.title|escape:'html':'UTF-8'}</span>
					{if $menu.subtitle != ''}<div class="menu-subtitle">{$menu.subtitle|escape:'html':'UTF-8'}</div>{/if}
					</a>
					<span class="icon-drop-mobile"></span>
					{if isset($menu.sub_menu) && count($menu.sub_menu) > 0}
						<div class="rv-sub-menu menu-dropdown col-xs-12 {$menu.width_sub|escape:'html':'UTF-8'} {$menu.align_sub|escape:'html':'UTF-8'}">
							{foreach from=$menu.sub_menu item= menu_row name=menu_row}
								<div class="rv-menu-row row  {$menu_row.class|escape:'html':'UTF-8'}">
									{if isset($menu_row.list_col) && count($menu_row.list_col) > 0}
										{foreach from=$menu_row.list_col item= menu_col name=menu_col}
											<div class="rv-menu-col col-xs-12 {$menu_col.width|escape:'html':'UTF-8'} {$menu_col.class|escape:'html':'UTF-8'} {$menu.type|escape:'quotes':'UTF-8'}">
												{if count($menu_col.list_menu_item) > 0}
													<ul class="ul-column">
													{foreach from=$menu_col.list_menu_item item= sub_menu_item name=sub_menu_item}
														<li class="menu-item {if $sub_menu_item.type_item == 1} item-header{else} item-line{/if} {if $sub_menu_item.type_link == 4}product-block{/if}">
															{if $sub_menu_item.type_link == 4}
																{$id_lang = Context::getContext()->language->id}
																{$id_lang = Context::getContext()->language->id}
																{foreach from = $sub_menu_item.product item=product name=product}
																<article class="product-miniature js-product-miniature">
																<div class="products">
																<div class="thumbnail-container clearfix">
																<div class="rvproduct-image">
																		<a class="product_img_link product-thumbnail" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
																			<img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}"/>
																			
																		</a>																	
																	
																{hook h='displayProductCountdown' product=$product}
																</div>
																<div class="rvproduct-desc">
																	<div class="product-description">
																	<h3 class="product-title">
																		<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
																			{$product.name|truncate:15:''|escape:'html':'UTF-8'}
																		</a>
																	</h3>
																	
																	<div class="content_price product-price-and-shipping">
																	{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
																		<span class="price {if isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0} special-price{/if}">												
																				{Product::convertAndFormatPrice($product.price)}

																				</span>
																				{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
																			{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
																					<p class="discount-percentage discount-product" href="{$product.link|escape:'html':'UTF-8'}">
																								<span class="sale">
																								{if $product.specific_prices && $product.specific_prices.reduction_type == 'percentage'}
																								-{$product.specific_prices.reduction|escape:'quotes':'UTF-8' * 100}%
																								{else}
																								-{$product.price_without_reduction-$product.price|floatval}
																								{/if}
																								</span>
																							</p>
																			{/if}
																			{/if}
																		{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
																				<span class="old-price regular-price">
																				{Product::convertAndFormatPrice($product.price_without_reduction)}
																				</span>
																		{/if}
																			{hook h="displayProductPriceBlock" product=$product type="price"}
																			{hook h="displayProductPriceBlock" product=$product type="unit_price"}
																	{/if}
																</div>
																	</div>
																</div>
																</div>
																</div>
																</article>
																{/foreach}
															{else if $sub_menu_item.type_link == 3}
																<a href="{$sub_menu_item.link|escape:'html':'UTF-8'}">{$sub_menu_item.title|escape:'html':'UTF-8'}</a>
																<div class="html-block">
																	{$sub_menu_item.text|escape:'quotes':'UTF-8' nofilter}
																</div>
															{else}
																<a href="{$sub_menu_item.link|escape:'html':'UTF-8'}">{$sub_menu_item.title|escape:'html':'UTF-8' nofilter}</a>
															{/if}
														</li>
													{/foreach}
													</ul>
												{/if}
											</div>
										{/foreach}
									{/if}
								</div>
							{/foreach}
						</div>
					{/if}
				</li>
			{/if}
		{/foreach}

		{if Module::isInstalled("smartblog") && Module::isInstalled("smartbloghomelatestnews") && (Module::isEnabled("smartblog")) && (Module::isEnabled("smartbloghomelatestnews"))}
		<li class="rvblog level-1">
			<a class="dropdown-item" href="{$link->getPageLink('smartblog')|escape:'html':'UTF-8'}" >{l s='blog' d='Shop.Theme.Global'}</a>
		</li>
		{/if}
	
		 
	</ul>
</div>
</div>
</div>
<!-- /Module Megamenu -->