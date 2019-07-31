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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- Module ImageSlider -->
{if $rvimageslider.rvpoh == 1}
  {assign var='autoplay_value' value='true'}
{else}
  {assign var='autoplay_value' value='false'}
{/if}
{if $rvimageslider.slides}
  <div id="rvimageslider" data-interval="{$rvimageslider.rvspeed}" data-pause="{$autoplay_value}" class="container">
    <div class="rvnivo-slider">
      <div id="slider" class="">
        {foreach from=$rvimageslider.slides item=slide name='rvimageslider'}
          {if $slide.active}
            <a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend|escape:'html':'UTF-8'}">
              <img src="{$slide.image_url}" alt="{$slide.legend|escape}" />
            </a>
          {/if}
        {/foreach}
      </div>
    </div>
  </div>
{/if}
<!-- Module ImageSlider -->