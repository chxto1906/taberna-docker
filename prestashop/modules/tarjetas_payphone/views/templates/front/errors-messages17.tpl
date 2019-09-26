{extends "$layout"}
{block name="content"} 
<div class="alert alert-danger">
    <p class="payphone-error">Error inesperado</p><br>
    <p class="payphone-error-heading">Lamentablemente, su pedido no se pudo procesar en este momento</p>
    <ul class="alert alert-danger">
        {foreach from=$errors item='error'}
            <li>{$error}.</li>
        {/foreach}
    </ul>
</div>
{/block}