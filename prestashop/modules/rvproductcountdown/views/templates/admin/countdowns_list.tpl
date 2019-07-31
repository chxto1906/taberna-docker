{*
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
<div id="rvpc-countdowns-list">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-cogs"></i> {l s='Countdown list' mod='rvproductcountdown'}
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <div class="table-responsive-row">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{l s='Product' mod='rvproductcountdown'}</th>
                            <th>{l s='Name' mod='rvproductcountdown'}</th>
                            <th>{l s='From' mod='rvproductcountdown'}</th>
                            <th>{l s='To' mod='rvproductcountdown'}</th>
                            <th>{l s='State' mod='rvproductcountdown'}</th>
                            <th>{l s='Actions' mod='rvproductcountdown'}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$countdowns item=countdown}
                            <tr>
                                <td>#{$countdown.id_product|intval} {$countdown.product_name|escape:'html':'UTF-8'}</td>
                                <td>{$countdown.name|escape:'html':'UTF-8'}</td>
                                <td>{$countdown.from|escape:'html':'UTF-8'}</td>
                                <td>{$countdown.to|escape:'html':'UTF-8'}</td>
                                <td>
                                    {if !$countdown.expired}
                                        {if $countdown.active}
                                            <span class="label label-success">{l s='Enabled' mod='rvproductcountdown'}</span>
                                        {else}
                                            <span class="label label-danger">{l s='Disabled' mod='rvproductcountdown'}</span>
                                        {/if}
                                    {else}
                                        <span class="label label-default">{l s='Inactive' mod='rvproductcountdown'}</span>
                                    {/if}
                                </td>
                                <td>
                                    {* Edit btn *}
                                    <a href="{$product_link|escape:'html':'UTF-8'}&id_product={$countdown.id_product|intval}&updateproduct&key_tab={$key_tab|escape:'html':'UTF-8'}"
                                       title="{l s='Edit' mod='rvproductcountdown'}" class="edit btn btn-default" target="_blank">
                                        <i class="icon-pencil"></i>
                                    </a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    {* Remove btn *}
                                    <a href="#" title="{l s='Remove' mod='rvproductcountdown'}" class="edit btn btn-default remove-countdown" data-id-countdown="{$countdown.id_countdown|intval}">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var rvpc_ajax_url = "{$ajax_url|escape:'quotes':'UTF-8'}";
        var rvpc_remove_confirm_txt = "{l s='Are you sure you want to delete this countdown?' mod='rvproductcountdown'}";
    </script>
</div>
