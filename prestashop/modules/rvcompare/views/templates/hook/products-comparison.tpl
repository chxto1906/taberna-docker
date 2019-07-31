{*
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

{extends file=$layout}

{block name='breadcrumb_title'}
  <h1 class="breadcrumb-title">{l s='Product Comparison' mod='rvcompare'}</h1>
{/block}

{block name='content'}
	<section id="main">
		{capture name=path}{l s='Product Comparison' mod='rvcompare'}{/capture}
		<h1 class="h1 rvcomparetitle">{l s='Product Comparison' mod='rvcompare'}</h1>
		{if $hasProduct}
			<div class="products_block table-responsive">
				<table id="product_comparison" class="table table-bordered">
					<tr>
						<td class="rv_empty compare_extra_information">
							<span>{l s='Features' mod='rvcompare'}</span>
						</td>
						{foreach from=$products item=product name=for_products}
							{assign var='replace_id' value=$product.id|cat:'|'}
							<td class="product-miniature js-product-miniature  product-{$product.id_product} product-block" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
								<div class="remove">
									<a class="cmp_remove" href="{$link->getPageLink('products-comparison', true)|escape:'html':'UTF-8'}" title="{l s='Remove' mod='rvcompare'}" data-id-product="{$product.id_product}">
										<i class="fa fa-trash"></i>
									</a>
								</div>
								<div class="thumbnail-container">
									{block name='product_thumbnail'}
										<a href="{$product.url}" class="thumbnail product-thumbnail">
											<img src = "{$product.cover.bySize.home_default.url}" alt = "{$product.cover.legend}" data-full-size-image-url = "{$product.cover.large.url}" />
										</a>
									{/block}
								</div>
								<div class="product-description">
									{block name='product_name'}
										<h1 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name}</a></h1>
									{/block}

									{block name='product_price_and_shipping'}
										{if $product.show_price}
											<div class="product-price-and-shipping">
												{if $product.has_discount}
												{hook h='displayProductPriceBlock' product=$product type="old_price"}

												<span class="regular-price">{$product.regular_price}</span>
												{if $product.discount_type === 'percentage'}
													<span class="discount-percentage">{$product.discount_percentage}</span>
												{/if}
												{/if}

												{hook h='displayProductPriceBlock' product=$product type="before_price"}

												<span itemprop="price" class="price">{$product.price}</span>

												{hook h='displayProductPriceBlock' product=$product type='unit_price'}

												{hook h='displayProductPriceBlock' product=$product type='weight'}
											</div>
										{/if}
									{/block}

									{block name='product_desc'}
										<p class="product-desc" itemprop="description">
											{$product.description_short|strip_tags:'UTF-8'|truncate:70:'...'}
										</p>
									{/block}

									<div class="highlighted-informations{if !$product.main_variants} no-variants{/if}">
										{block name='product_variants'}
											{if $product.main_variants}
												{include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
											{/if}
										{/block}
									</div>
									<div class="cart-button">
										{include file='catalog/_partials/customize/button-cart.tpl'}
									</div>
								</div>
							</td>
						{/foreach}
					</tr>
					{if $ordered_features}
						{foreach from=$ordered_features item=feature}
						<tr>
							{cycle values='comparison_feature_odd,comparison_feature_even' assign='classname'}
							<td class="{$classname} feature-name" >
								<strong>{$feature.name|escape:'html':'UTF-8'}</strong>
							</td>
							{foreach from=$products item=product name=for_products}
								{assign var='product_id' value=$product.id}
								{assign var='feature_id' value=$feature.id_feature}
								{if isset($product_features[$product_id])}
									{assign var='tab' value=$product_features[$product_id]}
									<td class="{$classname} comparison_infos product-{$product.id}">{if (isset($tab[$feature_id]))}{$tab[$feature_id]|escape:'html':'UTF-8'}{/if}</td>
								{else}
									<td class="{$classname} comparison_infos product-{$product.id}"></td>
								{/if}
							{/foreach}
						</tr>
						{/foreach}
					{else}
						<tr>
							<td></td>
							<td colspan="{$products|@count}" class="text-center">{l s='No features to compare' mod='rvcompare'}</td>
						</tr>
					{/if}
					{hook h='displayProductCommentCompare' list_product=$list_product}
				</table>
			</div> <!-- end products_block -->
		{else}
			<p class="alert alert-warning">{l s='There are no products selected for comparison.' mod='rvcompare'}</p>
		{/if}
		<ul class="footer_link">
			<li>
				<a class="btn btn-primary" href="{$urls.base_url}">
					<span>{l s='Continue Shopping' mod='rvcompare'}</span>
				</a>
			</li>
		</ul>
	</section>
{/block}

