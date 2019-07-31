<div itemtype="#" itemscope="" class="sdsarticleCat clearfix">
	<div id="smartblogpost-{$post.id_post}">
			{assign var="options" value=null}
			{$options.id_post = $post.id_post} 
			{$options.slug = $post.link_rewrite}
		<div class="articleContent">
			<a itemprop="url" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" title="{$post.meta_title}" class="imageFeaturedLink">
				{assign var="activeimgincat" value='0'}
				{$activeimgincat = $smartshownoimg} 
				{if ($post.post_img != "no" && $activeimgincat == 0) || $activeimgincat == 1}
					<img itemprop="image" alt="{$post.meta_title}" src="{$link->getMediaLink($smart_module_dir)}/smartblog/images/{$post.post_img}-single-default.jpg" class="imageFeatured">
				{/if}
			</a>
		</div>
		<div class="sdsarticleHeader">
			<p class='sdstitle_block'><a title="{$post.meta_title}" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'>{$post.meta_title}</a></p>
		</div>
		{assign var="options" value=null}
		{$options.id_post = $post.id_post}
		{$options.slug = $post.link_rewrite}
		{assign var="catlink" value=null}
		{$catlink.id_category = $post.id_category}
		{$catlink.slug = $post.cat_link_rewrite}
		<span class="blogdetail">
			{l s='Posted by' mod='smartblog'} 
			{if $smartshowauthor ==1}&nbsp;
				<span class="author" itemprop="author"><i class="fa fa-user"></i>&nbsp;&nbsp;{if $smartshowauthorstyle != 0}{$post.firstname} {$post.lastname}{else}{$post.lastname} {$post.firstname}{/if}</span>&nbsp;&nbsp;
			{/if}
			<span class="articleSection" itemprop="articleSection"><a href="{smartblog::GetSmartBlogLink('smartblog_category',$catlink)}"><i class="fa fa-tags"></i>&nbsp;&nbsp;{if $title_category != ''}{$title_category}{else}{$post.cat_name}{/if}</a></span>&nbsp;&nbsp;
			<span class="blogcomment"><a title="{$post.totalcomment} Comments" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}#articleComments"><i class="fa fa-comments"></i>&nbsp;&nbsp;{$post.totalcomment} {l s=' Comments' mod='smartblog'}</a></span>
			{if $smartshowviewed ==1}&nbsp;
				<span class="viewed"><i class="fa fa-eye"></i>&nbsp;&nbsp;{l s=' views' mod='smartblog'} ({$post.viewed})</span>
			{/if}
		</span>
		<div class="sdsarticle-des">
			<span itemprop="description" class="clearfix">
				<div id="lipsum">
					{$post.short_description}
				</div>
			</span>
		</div>
		<div class="sdsreadMore">
			{assign var="options" value=null}
			{$options.id_post = $post.id_post}  
			{$options.slug = $post.link_rewrite}  
			<span class="more">
				<a title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="r_more">{l s='Read more' mod='smartblog'}</a>
			</span>
		</div>
	</div>
</div>