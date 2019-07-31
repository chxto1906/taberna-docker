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

<section class="block products_block rv-animate-element left-to-right">
  <h4 class="title_block">
    {l s='Best Sellers' d='Shop.Theme.Catalog'}
  </h4>
  <div class="block_content products-block">
    <div id="bestseller-carousel" class="products owl-carousel">
      {$counter = 1}
      {$lastproduct = {count($products)}}
     {foreach from=$products item="product"}
      {if $counter%3 == 1} 
      <div class="multiple-item">
        {/if}
      {include file="catalog/_partials/miniatures/product-left.tpl" product=$product}
      {if ($counter%3 ==0 || $counter == $lastproduct)}
    </div>
    {/if}
    {$counter = $counter+1}
      {/foreach}
    </div>
    <a class="all-product-link" href="{$allBestSellers}">
      {l s='All best sellers' d='Shop.Theme.Catalog'}
    </a>
  </div>
</section>
