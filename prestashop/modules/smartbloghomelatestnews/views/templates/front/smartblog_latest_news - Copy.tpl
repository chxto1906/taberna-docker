<div id="smartblog_block" class="block products_block container clearfix">
    <div class="products_block_inner">
        {if isset($smartshowhometitle) && $smartshowhometitle}
            <h2 class="homepage-heading">
                <a href="{smartblog::GetSmartBlogLink('smartblog')}">{$smartshowhometitle}</a>
            </h2>
        {/if}
        <div class="sdsblog-box-content block_content row">
            {if isset($view_data) AND !empty($view_data)}
                {assign var='i' value=1}
                <ul id="smartblog-carousel" class="owl-carousel product_list">
                    {foreach from=$view_data item=post}
                        {assign var="options" value=null}
                        {$options.id_post = $post.id}
                        {$options.slug = $post.link_rewrite}
                        <li class="item sds_blog_post">
                            <div class="blog_post row">
                                <span class="news_module_image_holder col-md-6 col-sm-6 col-xs-12">
                                    <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
                                        <img alt="{$post.title}" class="feat_img_small" src="{$modules_dir}smartblog/images/{$post.post_img}-home-default.jpg">
                                        <span class="blog-hover"></span>
                                    </a>
                                    <span class="blogicons">
                                        <a title="Click to view Full Image" href="{$modules_dir}/smartblog/images/{$post.post_img}-single-default.jpg" data-lightbox="blog-image" class="icon zoom">&nbsp;</a> 
                                        <a title="Click to view Read More" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="icon readmore">&nbsp;</a>
                                    </span>
                                </span>
                                <div class="blog_content col-md-6 col-sm-6 col-xs-12">
                                    <div class="blog_inner">
                                        <h4 class="sds_post_title"><a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.title}</a></h4>
                                        <p class="desc">
                                            {$post.short_description|escape:'htmlall':'UTF-8'|truncate:110:'...'}
                                        </p>
                                        <div class="smartbloginfo">
                                            <span class="blog_date">
                                                <i class="icon icon-calendar"></i>
                                                <span class="day_date">{$post.date_added|date_format:"%e"} -</span>
                                                <span class="day_month">{$post.date_added|date_format:"%b"} -</span>
                                                <span class="day_year">{$post.date_added|date_format:"%Y"}</span>
                                            </span>

											<span class="author">
												<i class="icon icon-user"></i> {if $post.smartshowauthor ==1} {if $post.smartshowauthorstyle != 0}{$post.firstname}{$post.lastname}{/if}{/if}
											</span>
											<span class="view">
												<i class="icon icon-eye-open"></i> {$post.viewed} {if $post.viewed > 1} {l s='Views' mod='smartbloghomelatestnews'} {else} {l s='View' mod='smartbloghomelatestnews'} {/if}
											</span>
											<span class="comment">
                                                <i class="icon icon-comments-o"></i>&nbsp;&nbsp;
                                                {if $post.countcomment < 1} {l s='0' mod='smartbloghomelatestnews'} {else} {$post.countcomment} {/if} 
                                                {if $post.countcomment > 1} {l s='Comments' mod='smartbloghomelatestnews'} {else} {l s='Comment' mod='smartbloghomelatestnews'} {/if}
                                            </span>
                                        </div>
                                        <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}"  class="r_more">{l s='Read More' mod='smartbloghomelatestnews'}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {$i=$i+1}
                    {/foreach}
                </ul>
            {/if}
        </div>
        <div class="customNavigation">
            <a class="btn prev tdsmartblog_prev">{l s='Prev' mod='smartbloghomelatestnews'}</a>
            <a class="btn next tdsmartblog_next">{l s='Next' mod='smartbloghomelatestnews'}</a>
        </div>
    </div>
</div>