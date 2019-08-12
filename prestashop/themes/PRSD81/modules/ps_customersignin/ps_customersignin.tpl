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
  <div  id="_desktop_user_info">
    <div class="user-info">
      <div class="user-info-inner dropdown js-dropdown">
        <i class="fa fa-user-circle hidden-lg-up" aria-hidden="true"></i>
        <span class="user-logo expand-more _gray-darker" data-toggle="dropdown">
          {if $logged}
            <span>{l s='Mi cuenta' d='Shop.Theme.Global'}</span> 
          {else}
            <span>{l s='Ingresa' d='Shop.Theme.Global'}</span>
          {/if}
        </span>
        
        <ul class="dropdown-menu" aria-labelledby="dLabel">
          {if $logged}
          <li>
            <a class="account dropdown-item" href="{$my_account_url}" title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">
              <span>{$customerName}</span>
            </a>
          </li>
          <li>
            <a class="logout dropdown-item" href="{$logout_url}" rel="nofollow" title="{l s='Sign out' d='Shop.Theme.Actions'}">
              <span>{l s='Sign out' d='Shop.Theme.Actions'}</span>
            </a>
          </li>
          {else}
          <li>
            <a class="login dropdown-item" href="{$my_account_url}" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">
              <span>{l s='Sign in' d='Shop.Theme.Actions'}</span>
            </a>
          </li>
          <li>
            <a class="login dropdown-item" href="{$create_account_url}" title="Regístrate ahora" rel="nofollow">
              <span>{l s='Sign Up' d='Shop.Theme.Global'}</span>
            </a>
          </li>
          {/if}
          {* {hook h='displayRvWishlistBtn'} *}
          {hook h='displayRvCompareHeader'}
        </ul>
      </div>
    </div>
  </div>
