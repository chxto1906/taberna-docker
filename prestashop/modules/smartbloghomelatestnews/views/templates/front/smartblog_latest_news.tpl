<div id="smartblog_block" class="block products_block  clearfix">
    <div class="products_block_inner">
        <div class="rv-titletab">
            {if isset($smartshowhometitle) && $smartshowhometitle}
            <h2 class="tab_title">
                <a href="{smartblog::GetSmartBlogLink('smartblog')}">{$smartshowhometitle}</a>
            </h2>
            {/if}
        </div>
        <div class="sdsblog-box-content block_content row">
            {if isset($view_data) AND !empty($view_data)}
            {assign var='i' value=1}
            <div id="smartblog-carousel" class="owl-carousel product_list">
                {foreach from=$view_data item=post}
                {assign var="options" value=null}
                {$options.id_post = $post.id}
                {$options.slug = $post.link_rewrite}
                <div class="item sds_blog_post">
                    <div class="blog_post">
                        <span class="news_module_image_holder">
                            <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
                                <img alt="{$post.title}" class="feat_img_small" src="{$link->getMediaLink($smart_module_dir)}/smartblog/images/{$post.post_img}-home-default.jpg">
                                <span class="blog-hover"></span>
                            </a>
                            <span class="blogicons">
                                <a title="Click to view Full Image" href="{$link->getMediaLink($smart_module_dir)}/smartblog/images/{$post.post_img}-single-default.jpg" data-lightbox="blog-image" class="icon zoom">&nbsp;</a> 
                            </span>
                            <div class="smartbloginfo">
                             <span class="blog_date">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <span class="day_date">{$post.date_added|date_format:"%e"} </span>
                                <span class="day_month">{$post.date_added|date_format:"%b"}</span>
                                <span class="day_year">{$post.date_added|date_format:"%Y"}</span>
                            </span>
                        </div>
                    </span>
                    <div class="blog_content">
                        <div class="blog_inner">
                            <h4 class="sds_post_title"><a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.title}</a></h4>
                            <p class="desc">
                                {$post.short_description|escape:'htmlall':'UTF-8'|truncate:120:'...'}
                            </p>
                            <div class="read_more">
                                <a title="Click to view Read More" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="icon readmore">read more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {$i=$i+1}
            {/foreach}
        </div>
        {/if}
    </div>
</div>
</div>