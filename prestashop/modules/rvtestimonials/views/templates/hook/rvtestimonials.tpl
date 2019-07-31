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

	{if $rvtestimonials.rvauto == 1}
	{assign var='autoplay_value' value='true'}
	{else}
	{assign var='autoplay_value' value='false'}
	{/if}
	{if $rvtestimonials.slides}
	<div id="rvtestimonials" class="clearfix rv-animate-element left-to-right" data-pause="{$autoplay_value}">
		<div class="homepage-heading">{l s='Testimonials' mod='rvtestimonials'}</div>
		<div class="testimonial_inner">
			{assign var=item value=1}
			<div id="testimonial-carousel" class="owl-carousel owl-theme">
				{foreach from=$rvtestimonials.slides item=slide}
				<div class="testimonial-item">
					<div class="testimonial-image">
						<img src="{$slide.image_url}" />
					</div>
					<div class="testimonial-content">
						{if isset($slide.title) && trim($slide.title) != ''}
						<span class="testimonial-name">{$slide.title}</span>
						{/if}
						{if isset($slide.legend) && trim($slide.legend) != ''}
						<span class="testimonial-designation">{$slide.legend}</span>
						<hr>
						{/if}
						{if isset($slide.description) && trim($slide.description) != ''}
						<div class="testimonial-text">{$slide.description}</div>
						{/if}
					</div>
				</div>
				{assign var=item value=$item+1}
				{/foreach}
			</div>
		</div>
	</div>
	{/if}
