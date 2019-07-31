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
  <div id="rvdealoftheday" class="products_block clearfix container rv-animate-element right-to-left">
    <div class="products_block_inner">
     <div class="rv-titletab">
      {if isset($RV_DEALSOFDAY_TITLE) && $RV_DEALSOFDAY_TITLE}
      <h2 class="tab_title">{$RV_DEALSOFDAY_TITLE}</h2>
      {/if}
    </div>
    {if isset($specials) && $specials}
    <!-- Custom start -->
    {assign var='productCount' value=count($specials)}
    {assign var=item value=0}
    {assign var=maxItem value=$no_prod}
    <!-- Custom End -->

    <div class="row">
      <div class="dealofdayimage col-lg-5 col-md-12">
        <div id="dealoftheday-carousel" class="owl-carousel product_list">
          {foreach from=$specials item='product' name='specialProducts'}
          {if $item == $maxItem}
          {break}
          {/if}
          {if $item%2 == 0}
          <div class="item js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
            <div class="product-container">
              <div class="row">
                <div class="thumbnail-container col-sm-6 col-lg-6">
                  <div class="thumbnail-inner">
                    {block name='product_thumbnail'}
                    <a href="{$product.url}" class="thumbnail product-thumbnail">
                      <img class="mainimage" src = "{$product.cover.bySize.medium_default.url}" alt = "{$product.cover.legend}" data-full-size-image-url = "{$product.cover.large.url}" />
                      {if isset($product.images[1])}
                      <img class="replace-2x img_1 img-responsive" src = "{$product.images[1].bySize.medium_default.url}" alt = "{$product.name|truncate:30:'...'}" title="" />
                      {/if}
                    </a>
                    {/block}
                    {if $addImages == 1}
                    {block name='product_images'}
                    <div class="product_list_thumb">
                      <ul id="thumb_carousel" class="owl-carousel product-images">
                        {foreach from=$product.images item=image}
                        <li class="thumb-container">
                          <a href="javascript:void(0);" source="{$image.bySize.medium_default.url}" class="{if $image.id_image == $product.cover.id_image} selected {/if}">
                            <img class="thumb" src="{$image.bySize.small_default.url}" alt="{$image.legend}" title="{$image.legend}" width="80" itemprop="image" >
                          </a>
                        </li>
                        {/foreach}
                      </ul>
                    </div>
                    {/block}
                    {/if}
                    <div class="product-buttons">
                     <div class="product-cart-btn">
                      <form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
                        <div class="product-quantity">
                          <input type="hidden" name="token" value="{$static_token}" class="token">
                          <input type="hidden" name="id_product" value="{$product.id_product}">
                          <input type="hidden" name="qty" value="1" />
                          <button class="btn btn-primary ajax_add_to_cart_button add-to-cart" data-button-action="add-to-cart" {if !$product.quantity}disabled{/if}>
                           <i class="fa fa-shopping-bag" aria-hidden="true"></i>
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
             </div>
             {block name='product_flags'}
             <ul class="product-flags">
              {foreach from=$product.flags item=flag}
              <li class="{$flag.type}">{$flag.label}</li>
              {/foreach}
            </ul>
            {/block}

          </div>
          <div class="product-description col-sm-6 col-lg-6">

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
          <div class="dealofday_title">{l s='The Offer Ends In...' mod='rvdealoftheday'}</div>
          {if ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' < $product.specific_prices.to )}
          <div class="dealofday">
            <div class="timer" data-date="{$product.specific_prices.to|date_format:'%Y/%m/%d %H:%M:%S'}">
              <div class="days_container counter">
                <span class="days_text text">{l s='Days' mod='rvdealoftheday'}</span>
                <div class="date">
                  <span class="number" data-days>0</span>
                </div>
              </div>
              <div class="hours_container counter">
                <span class="hours_text text">{l s='Hours' mod='rvdealoftheday'}</span>
                <div class="date">
                  <span class="number" data-hours>0</span>
                </div>
              </div>
              <div class="minutes_container counter">
                <span class="minutes_text text">{l s='Mins' mod='rvdealoftheday'}</span>
                <div class="date">
                  <span class="number" data-minutes>0</span>
                </div>
              </div>
              <div class="seconds_container counter">
                <span class="seconds_text text">{l s='Secs' mod='rvdealoftheday'}</span>
                <div class="date">
                  <span class="number" data-seconds>0</span>
                </div>
              </div>
            </div>
          </div>
          {/if}
        </div>
      </div>
    </div>
  </div>
  {/if} 
  {assign var=item value=$item+1}
  {/foreach}
</div>
</div>
<div class="dealofdaybannner col-lg-2 col-md-12">
  <a href="#">
    <img src="../themes/PRSD81/assets/img/dealofday_image.png" alt="" />
  </a>
</div>
<div class="dealofdayimage col-lg-5 col-md-12">
 <div id="dealoftheday-carousel-odd" class="owl-carousel product_list">
  {foreach from=$specials item='product' name='specialProducts'}
  {if $item == $maxItem}
  {break}
  {/if}
  {if $item%2 != 0}
  <div class="item js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
    <div class="product-container">
      <div class="row">
        <div class="thumbnail-container col-sm-6 col-lg-6">
          <div class="thumbnail-inner">
            {block name='product_thumbnail'}
            <a href="{$product.url}" class="thumbnail product-thumbnail">
              <img class="mainimage" src = "{$product.cover.bySize.medium_default.url}" alt = "{$product.cover.legend}" data-full-size-image-url = "{$product.cover.large.url}" />
              {if isset($product.images[1])}
              <img class="replace-2x img_1 img-responsive" src = "{$product.images[1].bySize.medium_default.url}" alt = "{$product.name|truncate:30:'...'}" title="" />
              {/if}
            </a>
            {/block}
            {if $addImages == 1}
            {block name='product_images'}
            <div class="product_list_thumb">
              <ul id="thumb_carousel" class="owl-carousel product-images">
                {foreach from=$product.images item=image}
                <li class="thumb-container">
                  <a href="javascript:void(0);" source="{$image.bySize.medium_default.url}" class="{if $image.id_image == $product.cover.id_image} selected {/if}">
                    <img class="thumb" src="{$image.bySize.small_default.url}" alt="{$image.legend}" title="{$image.legend}" width="80" itemprop="image" >
                  </a>
                </li>
                {/foreach}
              </ul>
            </div>
            {/block}
            {/if}
            <div class="product-buttons">
             <div class="product-cart-btn">
              <form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
                <div class="product-quantity">
                  <input type="hidden" name="token" value="{$static_token}" class="token">
                  <input type="hidden" name="id_product" value="{$product.id_product}">
                  <input type="hidden" name="qty" value="1" />
                  <button class="btn btn-primary ajax_add_to_cart_button add-to-cart" data-button-action="add-to-cart" {if !$product.quantity}disabled{/if}>
                   <i class="fa fa-shopping-bag" aria-hidden="true"></i>
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
     </div>
     {block name='product_flags'}
     <ul class="product-flags">
      {foreach from=$product.flags item=flag}
      <li class="{$flag.type}">{$flag.label}</li>
      {/foreach}
    </ul>
    {/block}

  </div>
  <div class="product-description col-sm-6 col-lg-6">

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
   <div class="dealofday_title">{l s='The Offer Ends In...' mod='rvdealoftheday'}</div>
  {if ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' < $product.specific_prices.to )}
  <div class="dealofday">
    <div class="timer" data-date="{$product.specific_prices.to|date_format:'%Y/%m/%d %H:%M:%S'}">
      <div class="days_container counter">
        <span class="days_text text">{l s='Days' mod='rvdealoftheday'}</span>
        <div class="date">
          <span class="number" data-days>0</span>
        </div>
      </div>
      <div class="hours_container counter">
        <span class="hours_text text">{l s='Hours' mod='rvdealoftheday'}</span>
        <div class="date">
          <span class="number" data-hours>0</span>
        </div>
      </div>
      <div class="minutes_container counter">
        <span class="minutes_text text">{l s='Mins' mod='rvdealoftheday'}</span>
        <div class="date">
          <span class="number" data-minutes>0</span>
        </div>
      </div>
      <div class="seconds_container counter">
        <span class="seconds_text text">{l s='Secs' mod='rvdealoftheday'}</span>
        <div class="date">
          <span class="number" data-seconds>0</span>
        </div>
      </div>
    </div>
  </div>
  {/if}
</div>
</div>
</div>
</div>
{/if} 
{assign var=item value=$item+1}
{/foreach}
</div>
</div>

</div>
{else}
<div class="alert alert-info">{l s='No Special Products at this time.' mod='rvdealoftheday'}</div>
{/if}
</div>
</div>