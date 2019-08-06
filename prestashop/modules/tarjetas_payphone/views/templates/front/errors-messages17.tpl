{extends "$layout"}
{block name="content"} 
<div class="alert alert-danger">
    <p class="payphone-error">{l s='Unexpected payment error' mod='tarjetas_payphone'}</p><br>
    <p class="payphone-error-heading">{l s='Unfortunately your order could not be processed at this time:' mod='tarjetas_payphone'}</p>
    <ul class="alert alert-danger">
        {foreach from=$errors item='error'}
            <li>{$error}.</li>
        {/foreach}
    </ul>
</div>
{/block}