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

<div class="block-contact footer-block col-lg-4 rv-animate-element right-to-left">
  <h4 class="title_block">{l s='Store information' d='Shop.Theme.Global'}</h4>
  <div class="toggle-footer">
    <div class="block">
      <div class="icon rvaddress"><i class="fa fa-map-marker"></i></div>
      <div class="data rvaddress">{$contact_infos.address.formatted nofilter}</div>
    </div>

    {if $contact_infos.phone}
    <div class="block">
      <div class="icon phone"><i class="fa fa-phone-square"></i></div>
      <div class="data phone">
        <a href="tel:{$contact_infos.phone}">{$contact_infos.phone}</a>
      </div>
    </div>
    {/if}

    {if $contact_infos.fax}
    <div class="block">
      <div class="icon fax"><i class="fa fa-fax"></i></div>
      <div class="data fax">
        {$contact_infos.fax}
      </div>
    </div>
    {/if}
    
    {if $contact_infos.email}
    <div class="block">
      <div class="icon email"><i class="fa fa-envelope"></i></div>
      <div class="data email">
        <a href="mailto:{$contact_infos.email}">{$contact_infos.email}</a>
      </div>
    </div>
    {/if}
  </div>
</div>
