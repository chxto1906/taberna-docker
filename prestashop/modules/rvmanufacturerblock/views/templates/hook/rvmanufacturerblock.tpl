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
    <div id="rvmanufacturerblock" class="clearfix rv-animate-element bottom-to-top">
        <div class="container">
            <div class="row">
                <h2 class="homepage-heading">{l s='Our Brands' mod='rvmanufacturerblock'}</h2>
                {if isset($rvmanufacturer) && $rvmanufacturer}
                <!-- Custom start -->
                {assign var='sliderFor' value=6} <!-- Define Number of product for SLIDER -->
                {assign var='productCount' value=count($rvmanufacturer)}
                <!-- Custom End -->

                <div class="block_content row">
                    <ul id="manufacturer-carousel" class="owl-carousel product_list">
                        {foreach from=$rvmanufacturer item=manufacturer name=manufacturerList}
			{if $manufacturer['image']} 
                        <li class="item">
                            <div class="manufacturer_image">
                                <a href="{$manufacturer['link']}">
                                    <img width="170" height="85" class="img-responsive" src="{$manufacturer['image']}" title="{$manufacturer['name']}" />                                
				</a>
                            </div>
                            {if $rvmanufacturername}
                            <div class="manufacturer_name">	
                                <h5 itemprop="name">
                                    <a class="manufacturer-name img-responsive" href="{$manufacturer['link']}" itemprop="url">
                                        {$manufacturer['name']}
                                    </a>
                                </h5>
                            </div>
                            {/if}
                        </li>
			{/if}
                        {/foreach}
                    </ul>
                </div>
                {else}
                <div class="alert alert-info">{l s='There are no manufacturers.' mod='rvmanufacturerblock'}</div>
                {/if}
            </div>
        </div>
    </div>
