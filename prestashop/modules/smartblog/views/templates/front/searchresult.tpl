{if $postcategory == ''}
    {include file='module:smartblog/views/templates/front/search-not-found.tpl' postcategory=$postcategory}
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

