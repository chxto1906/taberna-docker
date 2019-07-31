{*
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
<div id="module_rvproductcountdown" class="">
    <input type="hidden" name="submitted_tabs[]" value="{$module_name|escape:'html':'UTF-8'}" />
    <input type="hidden" name="{$module_name|escape:'html':'UTF-8'}-submit" value="1" />

    <div class="row">
        <div class="col-lg-12 col-xl-4">
            <fieldset class="form-group">
                <label class="form-control-label">{l s='Enabled:' mod='rvproductcountdown'}</label>
                <div id="rvpc_active">
                    <div class="radio">
                        <label class="">
                            <input type="radio" id="rvpc_active_1" name="rvpc_active" value="1" {if isset($countdown_data.active) && $countdown_data.active}checked{/if}>
                            {l s='Yes' mod='rvproductcountdown'}
                        </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label class="">
                            <input type="radio" id="rvpc_active_0" name="rvpc_active" value="0" {if !isset($countdown_data.active) || (isset($countdown_data.active) && !$countdown_data.active)}checked{/if}>
                            {l s='No' mod='rvproductcountdown'}
                        </label>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-lg-12 col-xl-4">
            <fieldset class="form-group">
                <label class="form-control-label">{l s='Name:' mod='rvproductcountdown'}</label>
                <div class="translations tabbable" id="rvpc_name_wrp">
                    <div class="translationsFields tab-content ">
                        {foreach from=$languages item=language name=rvpc_lang_foreach}
                            <div class="translationsFields-rvpc_name tab-pane translation-label-{$language.iso_code|escape:'html':'UTF-8'} {if $smarty.foreach.rvpc_lang_foreach.first}active{/if}">
                                <input type="text"
                                       id="rvpc_name_{$language.id_lang|intval}"
                                       name="rvpc_name_{$language.id_lang|intval}"
                                       class="form-control"
                                       value="{if isset($countdown_data['name'][$language.id_lang])}{$countdown_data['name'][$language.id_lang]|escape:'html':'UTF-8'}{/if}"
                                />
                            </div>
                        {/foreach}
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xl-4">
            <fieldset class="form-group">
                <label class="form-control-label">{l s='Display:' mod='rvproductcountdown'}</label>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <span class="input-group-addon">{l s='from' mod='rvproductcountdown'}</span>
                            <input type="text" name="rvpc_from" class="rvpc-datepicker form-control" value="{if isset($countdown_data.from)}{$countdown_data.from|escape:'html':'UTF-8'}{/if}" style="text-align: center;" id="rvpc_from">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <span class="input-group-addon">{l s='to' mod='rvproductcountdown'}</span>
                            <input type="text" name="rvpc_to" class="rvpc-datepicker form-control" value="{if isset($countdown_data.to)}{$countdown_data.to|escape:'html':'UTF-8'}{/if}" style="text-align: center;" id="rvpc_to">
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $(".rvpc-datepicker").datetimepicker({
                            sideBySide: true,
                            format: 'YYYY-MM-DD HH:mm:ss',
                            useCurrent: false
                        });
                    });
                </script>
            </fieldset>
        </div>
        <div class="col-lg-12 col-xl-4">
            <fieldset class="form-group">
                <label class="form-control-label">{l s='Use dates from specific prices:' mod='rvproductcountdown'}</label>
                <div id="rvpc_specific_price_wrp">
                    <select name="rvpc_specific_price" id="rvpc_specific_price" class="form-control">
                        <option value="">--</option>
                        {foreach from=$specific_prices item=specific_price}
                            <option value="{$specific_price.id_specific_price|intval}"
                                    data-from="{$specific_price.from|escape:'html':'UTF-8'}"
                                    data-to="{$specific_price.to|escape:'html':'UTF-8'}">
                                {l s='from' mod='rvproductcountdown'}: {$specific_price.from|escape:'html':'UTF-8'}&nbsp;&nbsp;&nbsp;
                                {l s='to' mod='rvproductcountdown'}: {$specific_price.to|escape:'html':'UTF-8'}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </fieldset>
        </div>
    </div>

    {if isset($countdown_data.id_countdown)}
        <div class="form-group">
            <div class="row">
                <div class="col-lg-12 col-xl-4">
                    <fieldset class="form-group">
                        <div>
                            <button type="button" id="rvpc-reset-countdown" class="btn btn-default" data-id-countdown="{$countdown_data.id_countdown|intval}">{l s='Reset & remove' mod='rvproductcountdown'}</button>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    {/if}

    <script type="text/javascript">
        var rvpc_ajax_url = "{$ajax_url|escape:'quotes':'UTF-8'}";
    </script>
</div>
