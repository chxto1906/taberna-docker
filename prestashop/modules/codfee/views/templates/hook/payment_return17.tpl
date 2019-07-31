{**
* Cash On Delivery With Fee
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2017 idnovate
*  @license   See above
*}
{if $success == true}
    <style type="text/css">
        table.std td, table.table_block td table.custom td {
            padding: 5px 9px;
        }
        table.std th, table.table_block table.custom th {
            padding: 5px 9px;
        }
        span.total_pending {
            font-weight: 900;
        }
        td.codfee_total {
            font-weight: 900;
        }
    
    </style>
        {l s='Payment is due upon receipt of order.' mod='codfee'}
        <br /><br /><span class="total_pending">{l s='Total payment pending:' mod='codfee'}</span> <span class="price total_pending">{$total|escape:'quotes':'UTF-8'}</span>
        {if $shop_message}
            <br /><br /><span>
                {$shop_message|escape:'quotes':'UTF-8' nofilter}
            </span>
        {else}
            <br />
        {/if}
        <br />{l s='For any questions or for further information, please contact us at' mod='codfee'} {if version_compare($smarty.const._PS_VERSION_, '1.5', '<')}<a href="{$base_dir|escape:'htmlall':'UTF-8'}contact-form.php">{l s='Customer support' mod='codfee'}</a>.{else}<a href="{$link->getPageLink('contact-form', true)|escape:'htmlall':'UTF-8'}">{l s='Customer support' mod='codfee'}</a>.{/if}
    </p>
    <p>
        {if version_compare($smarty.const._PS_VERSION_, '1.6', '<')}
            <a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}" class="button_large">{l s='Continue shopping' mod='codfee'}</a>
        {/if}
    </p>
{else}
    <p class="warning">
        {l s='We noticed a problem with your order. If you think this is an error, you can contact us at' mod='codfee'} {if version_compare($smarty.const._PS_VERSION_, '1.5', '<')}<a href="{$base_dir|escape:'htmlall':'UTF-8'}contact-form.php">{l s='Customer support' mod='codfee'}</a>.{else}<a href="{$link->getPageLink('contact-form', true)|escape:'htmlall':'UTF-8'}">{l s='Customer support' mod='codfee'}</a>.{/if}
    </p>
{/if}
