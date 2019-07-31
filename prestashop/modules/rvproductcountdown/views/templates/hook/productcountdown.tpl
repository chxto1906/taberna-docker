{*
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
<div class="productcountdown buttons_bottom_block" data-to="{$countdown.to|escape:'html':'UTF-8'}">
    <div class="rvpc-main days-diff-{$days_diff|intval} weeks-diff-{$weeks_diff|intval} {if $weeks_diff == 0 && $hide_zero_weeks}hide_zero_weeks{/if}">
        {if $countdown.name}<h4>{$countdown.name|escape:'html':'UTF-8'}</h4>{/if}
    </div>
</div>