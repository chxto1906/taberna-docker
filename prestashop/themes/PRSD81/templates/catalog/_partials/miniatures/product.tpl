{**
 * 2007-2017 PrestaShop
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
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 <article class="product-miniature js-product-miniature col-xs-12 col-sm-6 col-md-4 col-lg-6 col-xl-3" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
  <div class="product-container">
    <div class="thumbnail-container">
      <div class="thumbnail-inner">
        {block name='product_thumbnail'}
        <a href="{$product.url}" class="thumbnail product-thumbnail">
         <img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src = "{$product.cover.bySize.home_default.url}" alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}" data-full-size-image-url = "{$product.cover.large.url}" data-lazyloading="0" />
         {if isset($product.images[1])}
         <img class="replace-2x img_1 img-responsive lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src = "{$product.images[1].bySize.home_default.url}"  alt = "{$product.name|truncate:30:'...'}"  data-lazyloading="0" alt="Image" />
         {/if}
       </a>
       {/block}
       <div class="product-buttons">
        <div class="product-cart-btn">
          <form action="" method="post" class="add-to-cart-or-refresh">
            <div class="product-quantity">
              <input type="hidden" name="token" value="" class="token">
              <input type="hidden" name="id_product" value="{$product.id_product}">
              <input type="hidden" name="qty" value="1" />
              <button class="btn btn-primary ajax_add_to_cart_button add-to-cart" data-button-action="add-to-cart" {if !$product.quantity}disabled{/if}>
                <i class="fa fa-shopping-bag"></i>
                <span>{l s='Add to cart' d='Shop.Theme.Actions'}</span>
              </button>
            </div>
          </form>
        </div> 
        {hook h='displayRvWishlist' product=$product}
        {hook h='displayRvCompareButton' product=$product}
        <div class="product-quick-btn">
          <a href="#" class="quick-view btn btn-primary" data-link-action="quickview" >
            <i class="fa fa-eye"></i>
            <span class="lblquickview">{l s='Quick view' d='Shop.Theme.Actions'}</span>
          </a>
        </div>
      </div>
      {hook h='displayProductCountdown' product=$product}
      {block name='product_flags'}
      <ul class="product-flags">
        {foreach from=$product.flags item=flag}
          {if $flag.label != 'Nuevo'}
            <li class="product-flag {$flag.type}">{$flag.label}</li>
          {/if}
        {/foreach}
      </ul>
      {/block}
      
      <div class="product-description listprd">
        {block name='product_name'}
        <h1 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name}</a></h1>
        {/block}

        {block name='product_reviews'}
        {hook h='displayProductListReviews' product=$product}
        {/block}

        {block name='product_price_and_shipping'}
        {if $product.show_price}
        <div class="product-price-and-shipping">
          {if $product.has_discount}
          {hook h='displayProductPriceBlock' product=$product type="old_price"}

          <span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
          <span class="regular-price">{$product.regular_price}</span>
          {if $product.discount_type === 'percentage'}
          <span class="discount-percentage">{$product.discount_percentage}</span>
          {/if}
          {/if}

          {hook h='displayProductPriceBlock' product=$product type="before_price"}

          <span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
          <span itemprop="price" class="price">{$product.price}</span>

          {hook h='displayProductPriceBlock' product=$product type='unit_price'}

          {hook h='displayProductPriceBlock' product=$product type='weight'}
        </div>
        {/if}
        {/block}

        {block name='product_desc'}
        <p class="product-desc" itemprop="description">
          {$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
        </p>
        {/block}
        <div class="highlighted-informations{if !$product.main_variants} no-variants{/if}">
          {block name='product_variants'}
          {if $product.main_variants}
          {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
          {/if}
          {/block}
        </div>
      </div>
    </div>
  </div>
</div>
</article>
