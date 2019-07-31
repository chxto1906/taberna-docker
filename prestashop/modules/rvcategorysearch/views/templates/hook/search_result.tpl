{*
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

<div class="ajax-search-content">
    <div class="items-list">
        {if $searchResults}
            {$num = 0}
            {foreach from=$searchResults item=product key=key}
                {$num = $num+1}
                {if $num > $searchLimit}
                    {break}
                {/if}
                <div class="item">
                    <a href="{$product.link|escape:'quotes':'UTF-8'}">
                        {if isset($searchImage) && $searchImage}
                            <img src="{$link->getImageLink($product.link_rewrite|escape:'quotes':'UTF-8',$product.id_image ,'small_default')}" alt="{$product.name|escape:'htmlall':'UTF-8'}" class="searchImg" />
                        {/if}
                        {if isset($searchCategoryName) && $searchCategoryName}
                            <span class="category-name">
                                {$product.category_name|escape:'html':'UTF-8'}
                            </span>
                        {/if}
                        <h5 class="product-name">
                            {$product.name|escape:'html':'UTF-8'}
                        </h5>
                        {if isset($searchPrice) && $searchPrice}
                            <span class="content_price product-price-and-shipping">
                                {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                                    <span itemprop="price" class="price {if isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0} special-price{/if}">
                                        {Product::convertAndFormatPrice($product.price|escape:'quotes':'UTF-8')}
                                    </span>
                                    {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                                        <span class="sale-percentage" href="{$product.link|escape:'html':'UTF-8'}">
                                            {if $product.specific_prices && $product.specific_prices.reduction_type == 'percentage'}
                                               -{$product.specific_prices.reduction|escape:'quotes':'UTF-8' * 100}%
                                            {/if}
                                        </span>
                                        <span class="old-price regular-price">
                                            {Product::convertAndFormatPrice($product.price_without_reduction|escape:'quotes':'UTF-8')}
                                        </span>
                                    {/if}
                                {/if}
                            </span>
                        {/if}
                    </a>
                </div>
            {/foreach}
        {else}
            <span class="noresult">{l s='No Products found!' mod='rvcategorysearch'}</span>
        {/if}
    </div>
</div>
