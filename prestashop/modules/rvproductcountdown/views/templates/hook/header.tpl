{*
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
<script type="text/javascript">
    {if $show_weeks}
        var rvpc_labels = ['weeks', 'days', 'hours', 'minutes', 'seconds'];
        var rvpc_labels_lang = {
            'weeks': '{l s='weeks' mod='rvproductcountdown'}',
            'days': '{l s='days' mod='rvproductcountdown'}',
            'hours': '{l s='hours' mod='rvproductcountdown'}',
            'minutes': '{l s='minutes' mod='rvproductcountdown'}',
            'seconds': '{l s='seconds' mod='rvproductcountdown'}'
        };
    {else}
    var rvpc_labels = ['days', 'hours', 'minutes', 'seconds'];
    var rvpc_labels_lang = {
        'days': '{l s='days' mod='rvproductcountdown'}',
        'hours': '{l s='hours' mod='rvproductcountdown'}',
        'minutes': '{l s='minutes' mod='rvproductcountdown'}',
        'seconds': '{l s='seconds' mod='rvproductcountdown'}'
    };
    {/if}
    var rvpc_show_weeks = {$show_weeks|intval};
</script>