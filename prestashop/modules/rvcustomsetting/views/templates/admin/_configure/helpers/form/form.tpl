{*
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

{extends file="helpers/form/form.tpl"}

{block name="input"}

    {if $input.type == 'file_upload_3'}
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-9">
                    <div class="rvcmsall-pattern-show">
                        {$i=1}
                        {while $i <= 30}
                            {$tmp = 'pattern'|cat:$i}
                            <div class="rvall-pattern-show {if $background_pattern == $tmp}rv_custom_setting_active{/if}" id="pattern{$i|escape:'htmlall':'UTF-8'}" style="background:url({$path|escape:'htmlall':'UTF-8'}pattern{$i|escape:'htmlall':'UTF-8'}.png)"></div>
                            {$i=$i+1}
                        {/while}
                        <div class="col-lg-12 rvall-pattern-custom-pattern" style="padding: 0;">
                            <input type="file" name="rvcustomsetting_custom_pattern" title="Add One Custom Pattern">
                            <input type="hidden" id="rvcustomsetting_pattern" name="rvcustomsetting_pattern" value="{$background_pattern|escape:'htmlall':'UTF-8'}">

                            {if $custom_pattern}
                                <div class="rvall-pattern-show custom_pattern {if $background_pattern == 'custompattern'}rv_custom_setting_active{/if}" id="custompattern" style="background:url({$path|escape:'htmlall':'UTF-8'}{$custom_pattern|escape:'htmlall':'UTF-8'})"></div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
            <p class="help-block">
                {l s='Choose Your Pattern or Update Your Custom Pattern.' mod='rvcustomsetting'}
            </p>
        </div>
    {/if}

    {if $input.type == 'custom_theme_option'}
        <div class="col-lg-9" id="RVCUSTOMSETTING_THEME_OPTION">
            <div class="form-group">
                <div class="col-lg-12">

                    <input type="radio" id="RVCUSTOMSETTING_THEME_OPTION1" name="RVCUSTOMSETTING_THEME_OPTION" value="default_theme" checked> 
                    <div class="color-wrapper theme1">
                        <div class="first"></div>
                        <div class="second"></div>
                    </div>
                    <p>
                        <label for="RVCUSTOMSETTING_THEME_OPTION1">{l s='Default Theme' mod='rvcustomsetting'}</label>
                    </p>

                    <input type="radio" id="RVCUSTOMSETTING_THEME_OPTION_CUSTOM" name="RVCUSTOMSETTING_THEME_OPTION" value="theme_custom" {if $fields_value[$input.name] == 'theme_custom'} checked {/if}>
                    <div class="color-wrapper theme_custom">
                        <div class="first" style="background-color: {Configuration::get('RVCUSTOMSETTING_THEME_COLOR_1')|escape:'htmlall':'UTF-8'}"></div>
                        <div class="second" style="background-color: {Configuration::get('RVCUSTOMSETTING_THEME_COLOR_2')|escape:'htmlall':'UTF-8'}"></div>
                    </div>
                    <p>
                        <label for="RVCUSTOMSETTING_THEME_OPTION_CUSTOM">{l s='Custom' mod='rvcustomsetting'}</label>
                    </p>

                </div>
                    <p class="help-block">
                        {l s='Choose Front Side Theme.' mod='rvcustomsetting'}
                    </p>
            </div>
        </div>
    {/if}

    {if $input.type == 'custom_color'}
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="input-group">
                            <input type="text" data-hex="true" class="color mColorPickerInput mColorPicker" name="RVCUSTOMSETTING_THEME_COLOR_1" value="#0f0010" id="color_0" style="background-color: rgb(255, 255, 255); color: black;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}

    {$smarty.block.parent}
{/block}
