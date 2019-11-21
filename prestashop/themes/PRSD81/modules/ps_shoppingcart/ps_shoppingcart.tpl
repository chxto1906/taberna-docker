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
<div id="_desktop_cart">
  <div class="blockcart cart-preview" data-refresh-url="{$refresh_url}">
    <div class="shopping_cart">
      <a rel="nofollow" href="{$cart_url}" class="shoppingcart" title="add to cart">
        <span class="hidden-md-down">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</span>
        <span class="cart-products-count">
          <span class="cart_quantity_count">{$cart.products_count}</span>
          <span class="total_price">{$cart.totals.total.value}&nbsp;-</span>
           <span class="quantity">{$cart.products_count}</span>
          <span class="cart_product_txt_s" {if $cart.products_count == 1}style="display:none"{/if}>{l s='Items' d='Shop.Theme.Checkout'}</span>
          <span class="cart_product_txt" {if $cart.products_count != 1}style="display:none"{/if}>{l s='Item' d='Shop.Theme.Checkout'}</span>
        </span>
      </a>
      <div class="cart_block block exclusive">
        <div class="block_content">
          <div class="cart_block_list">
            {if $cart.products_count > 0}
            {if $cart.products}
            <ul class="products">
              {foreach from=$cart.products item='product' name='myLoop'}
              {assign var='productId' value=$product.id_product}
              {assign var='productAttributeId' value=$product.id_product_attribute}
              <li>
                <a class="cart-images" href="{$product.url}">
                  <img src="{$product.cover.bySize.cart_default.url}" alt="{$product.name|escape:'quotes'}">
                </a>
                <div class="cart-info">
                  <div class="product-name">
                    <span class="quantity-formated">
                      <span class="quantity">{$product.cart_quantity}</span>
                      &nbsp;x&nbsp;
                    </span>
                    <a class="cart_block_product_name" href="{$product.url}">{$product.name}</a>
                  </div>
                  <div class="price">{$product.price}</div>
                  {if isset($product.attributes)}
                  <div class="product-atributes">
                    {foreach from=$product.attributes key="attribute" item="value"}
                    <div class="atributes">
                      <span class="label">{$attribute}:</span>
                      <span class="value">{$value}</span>
                    </div>
                    {/foreach}
                  </div>
                  {/if}
                </div>
                <span class="remove_link">
                  <a class="remove-from-cart" rel="nofollow" href="{$product.remove_from_cart_url}" data-link-action="delete-from-cart" data-id-product="{$product.id_product|escape:'javascript'}" data-id-product-attribute="{$product.id_product_attribute|escape:'javascript'}" data-id-customization="{$product.id_customization|escape:'javascript'}">
                    {if !isset($product.is_gift) || !$product.is_gift}
                    <i class="fa fa-trash-o"></i>
                    {/if}
                  </a>
                </span>
              </li>
              {/foreach}
            </ul>
            <div class="cart-prices">
              <div class="price subtotal">
                <span class="label">{$cart.subtotals.products.label}</span>
                <span class="value">{$cart.subtotals.products.value}</span>
              </div>
              <div class="price shipping">
                <span class="label">{$cart.subtotals.shipping.label}</span>
                {if $cart.subtotals.shipping.value == "Gratis"}
                  <span class="value" style="color: #ed2123;">Por calcular</span>
                {else}
                  <span class="value">{$cart.subtotals.shipping.value}</span>
                {/if}
                
              </div>
              {if isset($delivery_time)}
                <div class="price delivery-time">
                  <span class="label">Tiempo entrega</span>
                  <span class="value">{$delivery_time} mins. (máximo)</span>
                </div>
              {/if}
              <div class="price tax">
                <span class="label">{$cart.subtotals.tax.label}</span>
                <span class="value">{$cart.subtotals.tax.value}</span>
              </div>
              <div class="price total">
                <span class="label">{$cart.totals.total.label}</span>
                <span class="value">{$cart.totals.total.value}</span>
              </div>
            </div>
            <div class="cart-buttons">
              <a href="{$cart_url}" class="btn btn-primary checkout">{l s='Checkout' d='Shop.Theme.Actions'}</a>
            </div>
            {/if}
            {else}
            <p class="cart_block_no_products{if $cart.products} unvisible{/if}">
              {l s='Your cart is empty' d='Shop.Theme.Checkout'}
            </p>
            {/if}
          </div>
        </div>
      </div>
    </div>
    <script>
      if (window.jQuery) {  
        $(document).ready(function(){
          accordionCart();
        });
      }
    </script>
  </div>
</div>