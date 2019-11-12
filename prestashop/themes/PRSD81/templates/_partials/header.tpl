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

{* <div id="spinner"></div> *}

{*
{if Configuration::get('RVCUSTOMSETTING_RIGHT_STICKY_OPTION_STATUS')}
{block name='right_sticky'}
    <div class="rvright-sticky">
      <div class="sticky-content">
      <div class="sticky-inner">
        <div class="sticky-cart">
          <a href="{$urls.pages.cart}?action=show">
            <i class="material-icons">shopping_cart</i>
            <span>{l s='Add to Cart' d='Shop.Theme.Checkout'}</span>
          </a>
        </div>

        {hook h='displayRightWishlist'}
        
        {hook h='displayRightCompare'}

        <div class="sticky-account">
          <a class="account" href="{$urls.pages.my_account}" title="{l s='My account' d='Shop.Theme.Customeraccount'}" rel="nofollow">
            <i class="material-icons">account_circle</i>
            <span>{l s='My Account' d='Shop.Theme.Actions'}</span>
          </a>
        </div>
        </div>
      </div>
    </div>

{/block}
{/if} 
*}

{block name='header_banner'}
  <div class="header-banner">
    {hook h='displayBanner'}
  </div>
{/block}

{block name='header_nav'}

  {*<nav class="header-nav">
    <div class="container">
      <div class="inner-header">
      
        <div class="col-lg-4 col-sm-12 col-md-6 left-nav">
          {hook h='displayNav1'}
        </div>
        
        <div class="col-lg-8 col-sm-12 col-md-6 right-nav">
          {hook h='displayNav2'}
        </div>
      </div>
   
    </div>
  </nav>*}
{/block}

{block name='header_top'}
<div data-html2canvas-ignore class="full-header">
  <div data-html2canvas-ignore class="header-top">
    <div class="container">
      
        <div id="header_logo" class="">
          <a href="{$urls.base_url}">
            <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
          </a>
        </div>
          {hook h='displayTop'}
        <div class="clearfix"></div>
      
    </div>
  </div>
  <div class="nav-full-width">
    <div data-html2canvas-ignore class="container">
      <div class="position-static">
        {hook h='displayMegamenu'}
        {hook h='displayNavFullWidth'}
        </div>
        <div class="hidden-lg-up text-xs-center mobile">
          <div class="menu-icon">
            <div class="cat-title"><span>{l s='Menu' d='Shop.Theme'}</span></div>
          </div>
          <div id="_mobile_cart" class=""></div>
          <div id="_mobile_rvsearch" style="float: none !important;" class="col-xs-12 col-sm-12"></div>
          
          <div class="clearfix"></div>
        </div>
        <div id="menuCanvas" class="rvclose"></div>
        <div id="mobile_top_menu_wrapper" class="hidden-lg-up">
         
          <div class="header-collapse">
          <div class="menu-close rvclose"><i class="material-icons">clear</i></div>
          <div id="_mobile_user_info"></div>
          <div class="rvmenu">
            <div class="cat-title">{l s='Menu' d='Shop.Theme'} :</div>
            <div class="js-top-menu mobile" id="_mobile_top_menu"></div> 
          </div>
          <div class="responsive-content mobile">
            <div id="_mobile_wishlist"></div>
            <div id="_mobile_compare"></div>
            <div id="_mobile_headercontact"></div>
            <!--  <div class="js-top-menu mobile" id="_mobile_megamenu"></div> -->
          </div>
          </div>
        </div>
    </div>
    <div data-html2canvas-ignore class="container-fluid estas-comprando">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 6px;">
          <span class="hidden-md-down">
            COMPRANDO EN LA TIENDA: &nbsp;
          </span>
          <!--<img width="14px" class="img-responsive" src="/img/pin.png"> -->
          <span style="font-weight: bold;">
            {$shop.name}&nbsp;&nbsp;&nbsp;
            <span data-html2canvas-ignore style="position: relative;">
              {if $isOpen == true}
                <div class='pin bounce' title="Tienda abierta"></div>
              {else}
                <div class='pinOff bounce' title="Tienda cerrada"></div>
              {/if}
              <div class='pulse'></div>
            </span>
          </span>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <span data-html2canvas-ignore class="dropdown dropleft">
            <button class="btn btn-cambiar-tienda btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #f2f2f2 !important;">
              Cambiar de tienda
            </button>
            <div class="dropdown-menu" style="font-size: 0.8em !important;" id="change-tiendas" aria-labelledby="dropdownMenuButton">
              {foreach from=$shops key=k item=shop}
                {if $shops[$k-1].city|lower eq $shops[$k].city|lower}
                  <a class="dropdown-item d-item item-change-tienda" title="http://{$shop.domain}/{$shop.virtual_uri}" href="#">{$shop.name}</a>
                {else}
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" style="background: #5f5f5f;color: #fff;" >{$shop.city|upper}</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item d-item item-change-tienda" title="http://{$shop.domain}/{$shop.virtual_uri}" href="#">{$shop.name}</a>
                {/if} 
              {/foreach}
            </div>
          </span>
          
          
          
      </div>
      
    </div>
  </div>

</div>
{/block}

