{if $postcategory == ''}
    <p class="error">{l s='No Post in This Tag' mod='smartblog'}</p>
{else}
    <div id="smartblogcat" class="block">
        {foreach from=$postcategory item=post}
            {include file='module:smartblog/views/templates/front/category_loop.tpl' postcategory=$postcategory}
        {/foreach}
    </div>
{/if}
{if isset($smartcustomcss)}
    <style>
        {$smartcustomcss}
    </style>
{/if}
