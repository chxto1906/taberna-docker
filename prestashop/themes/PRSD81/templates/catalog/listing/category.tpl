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
{extends file='catalog/listing/product-list.tpl'}

{block name='product_list_header'}
<div class="block-category card card-block">
  {if $category.image}
  <div class="category-cover">
    <img src="{$category.image.large.url}" alt="{$category.image.legend}">
  </div>
  {/if}
    <h1 class="h1">{$category.name}</h1>
  {if $category.description}
  <div id="category-description" class="text-muted">{$category.description nofilter}</div>
  {/if}
</div>

  {if isset($subcategories) && $subcategories}
    {if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories) }
    <div id="subcategories">
      <p class="subcategory-heading">{l s='Subcategories' d='Shop.Theme.Global'}</p>
      <div class="block_content row">
        <div id="subcategory-carousel" class="owl-carousel clearfix">
          {foreach from=$subcategories item=subcategory}
          <div class="item">
            <div class="subcategory-container">
              <div class="subcategory-image">
                <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
                  {if isset($subcategory.image.small.url) && $subcategory.id_image}
                  <img class="replace-2x" src="{$subcategory.image.large.url}" alt="{$subcategory.name|escape:'html':'UTF-8'}"  />
                  {else}
                  <img class="replace-2x" src="{$urls.img_cat_url}{$language.iso_code}-default-category_default.jpg" alt="{$subcategory.name|escape:'html':'UTF-8'}"  />
                  {/if}
                </a>
              </div>
              <div class="subcategory-content">
                  <a class="subcategory-name" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}">
                    {$subcategory.name|truncate:25:'...' nofilter}
                  </a>
              </div>
            </div>
          </div>
          {/foreach}
        </div>
      </div>
    </div>
    {/if}
  {/if}
{/block}
