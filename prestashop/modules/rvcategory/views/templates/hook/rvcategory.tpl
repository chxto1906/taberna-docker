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
	*  @author PrestaShop SA <contact@prestashop.com>
	*  @copyright  2007-2018 PrestaShop SA
	*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
	*  International Registered Trademark & Property of PrestaShop SA
	*}
	{assign var="rv_cnt" value="1"}
	{assign var="rv_total" value="0"}
	{foreach from=$rv_categories item="item_category"}
	{$rv_total = $rv_total+1}
	{/foreach}

	<div id="rv_category" class="rv_category products_block">
		<div class="products_block_inner">
			<div class="rv-titletab">
				<h3 class="tab_title">{l s='Browse Categories' mod='rvcategory'}</h3>
			</div>
			<div class="row">
				{if isset($rv_categories) && $rv_categories|@count > 0}
				<div class="list_carousel responsive clearfix">
					<div id="rv_cat_featured" class="product-list owl-carousel">
						{foreach from=$rv_categories item=item_category name=rv_categories}
						{assign var=category value=$item_category.category}
						<div class="item {if $smarty.foreach.item_category.first|intval}first_item{elseif $smarty.foreach.item_category.last|intval}last_item{/if}">
							<div class="content col-sm-12">
								<div class="rv_cat_content">
									{if isset($rv_config->showimg) && $rv_config->showimg == 1}
									<div class="cat-img col-sm-5 col-xs-5">
										<a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" title="{$category->name|escape:'html':'UTF-8'}">
											{if $category->id_image && $item_category.cat_thumb == 1}
											<img src="{$path_ssl|escape:'html':'UTF-8'}img/c/{$category->id_category|intval}_thumb.jpg" alt=""/>
											{else}
											<img class="replace-2x" src="{$path_ssl|escape:'html':'UTF-8'}img/c/{$iso_code|escape:'html':'UTF-8'}.jpg" alt=""/>
											{/if}
										</a>
									</div>
									{/if}
									
									<div class="rvcat-content col-sm-7 col-xs-7">
										<div class="cat-infor">
											<h4 class="title">
												<a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}">
													{$category->name|escape:'html':'UTF-8'}
												</a>
											</h4>	
										</div>
										{if count($item_category.sub_cat > 0) && isset($rv_config->showsub) && $rv_config->showsub == 1}
										<div class="sub-cat">	
											<ul>
												{foreach from = $item_category.sub_cat item=sub_cat name=sub_cat_info}
												<li>
													<a href="{$link->getCategoryLink($sub_cat.id_category, $sub_cat.link_rewrite)|escape:'html':'UTF-8'}" title="{$sub_cat.name|escape:'html':'UTF-8'}">{$sub_cat.name|escape}</a>
												</li>
												{/foreach}
											</ul>
										</div>
										{/if}
									</div>
								</div>
							</div>
						</div>
						{/foreach}
					</div>
				</div>
				{else}
				<p class="alert alert-warning">{l s='There is no category' mod='rvcategory'}</p>
				{/if}
			</div>
		</div>
	</div>