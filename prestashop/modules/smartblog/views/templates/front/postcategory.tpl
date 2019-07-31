{extends file=$layout_category}
{block name='head_seo'}
    <title> {if $title_category != ''}{$title_category}{else}{l s='All Blog News' mod='smartblog'}{/if}</title>
{/block}

{block name='content'}
    {if $postcategory == ''}
        {if $title_category != ''}
            <p class="error">{l s='No Post in Category' mod='smartblog'}</p>
        {else}
            <p class="error">{l s='No Post in Blog' mod='smartblog'}</p>
        {/if}
    {else}
        {if $smartdisablecatimg == '1'}
            {assign var="activeimgincat" value='0'}
            {$activeimgincat = $smartshownoimg} 
            {if $title_category != ''}        
                {foreach from=$categoryinfo item=category}
                    <div id="sdsblogCategory">
                        {if ($cat_image != "no" && $activeimgincat == 0) || $activeimgincat == 1}
                            <img alt="{$category.meta_title}" src="{$link->getMediaLink($smart_module_dir)}/smartblog/images/category/{$cat_image}-single-default.jpg" class="imageFeatured">
                        {/if}
                        <div class="catDesc">
                            {$category.description}
                        </div>
                    </div>
                {/foreach}  
            {/if}
        {/if}
        <div id="smartblogcat" class="block">
            {foreach from=$postcategory item=post}
                {include file='module:smartblog/views/templates/front/category_loop.tpl' postcategory=$postcategory}
            {/foreach}
        </div>
        {if !empty($pagenums)}
            <div class="blog_pagination">
                <div class="pagination">
                    <div class="results">{l s='Showing' mod='smartblog'} {if $limit_start!=0}{$limit_start}{else}1{/if} {l s='to' mod='smartblog'} {if $limit_start+$limit >= $total}{$total}{else}{$limit_start+$limit}{/if} {l s='of' mod='smartblog'} {$total} ({$c} {l s='Pages' mod='smartblog'})</div>
                    <ul class="pagination_bottom">
                        {for $k=0 to $pagenums}
                            {if $title_category != ''}
                                {assign var="options" value=null}
                                {$options.page = $k+1}
                                {$options.id_category = $id_category}
                                {$options.slug = $cat_link_rewrite}
                            {else}
                                {assign var="options" value=null}
                                {$options.page = $k+1}
                            {/if}
                            {if ($k+1) == $c}
                                <li class="current"><a class="disabled">{$k+1}</a></li>
                            {else}
                                {if $title_category != ''}
                                    <li><a class="" href="{smartblog::GetSmartBlogLink('smartblog_category_pagination',$options)}">{$k+1}</a></li>
                                {else}
                                    <li><a class="" href="{smartblog::GetSmartBlogLink('smartblog_list_pagination',$options)}">{$k+1}</a></li>
                                {/if}
                            {/if}
                        {/for}
                    </ul>
                </div>
            </div>
        {/if}
    {/if}
    {if isset($smartcustomcss)}
        <style>
            {$smartcustomcss}
        </style>
    {/if}
{/block}