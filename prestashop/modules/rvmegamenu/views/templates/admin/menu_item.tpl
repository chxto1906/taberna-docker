{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

<form id="module_form" class="defaultForm form-horizontal" action="index.php?controller=AdminModules&amp;configure=rvmegamenu&amp;token={Tools::getAdminTokenLite('AdminModules')|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data" novalidate="">
<div class="panel"><h3><i class="icon-list-ul"></i> {l s='Menu Item' mod='rvmegamenu'}</h3>
	<div class="form-wrapper" id="menuContent" >
		<div class="form-group rv-type-link">
			<label class="control-label col-lg-3">{l s='Type Link' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				<div class="radio rv-radio">
					<label><input type="radio" name="type_link" id="type_link_custom" value="1" {if $menu->type_link == 1}checked="checked" {/if}>Custom Link</label>
				</div>
				<div class="radio rv-radio">
					<label><input type="radio" name="type_link" id="type_link_ps" value="0" {if $menu->type_link == 0}checked="checked" {/if}>PrestaShop Link</label>
				</div>
			</div>
		</div>
		
		<div class="form-group rv-menu-title" {if $menu->type_link == 0}style="display:none"{/if}>
			<label class="control-label col-lg-3">{l s='Title' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				{foreach from=$languages item=language}
					{if $languages|count > 1}
						<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_language}style="display:none"{/if}>
					{/if}
					<div class="col-lg-10">
					<input type="text" class="title" id="title_{$language.id_lang|intval}" name="title_{$language.id_lang|intval}" value="{if isset($menu->title[$language.id_lang|intval])}{$menu->title[$language.id_lang|intval]}{else}menu title{/if}"/>
					</div>
					{if $languages|count > 1}
						<div class="col-lg-2">
							<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
								{$language.iso_code|escape:'html':'UTF-8'}
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								{foreach from=$languages item=lang}
								<li><a href="javascript:hideOtherLanguage({$lang.id_lang|intval});javascript:changeLangInfor({$lang.id_lang|intval});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
								{/foreach}
							</ul>
						</div>
					{/if}
					{if $languages|count > 1}
						</div>
					{/if}
				{/foreach}
			</div>
		</div>
		
		<div class="form-group rv-menu-link" {if $menu->type_link == 0}style="display:none"{/if}>
			<label class="control-label col-lg-3">{l s='Link' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				{foreach from=$languages item=language}
					{if $languages|count > 1}
						<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_language}style="display:none"{/if}>
					{/if}
					<div class="col-lg-10">
					<input type="text" id="link_{$language.id_lang|intval}" name="link_{$language.id_lang|intval}" value="{if isset($menu->link[$language.id_lang|intval])}{$menu->link[$language.id_lang|intval]}{else}#{/if}"/>
					</div>
					{if $languages|count > 1}
						<div class="col-lg-2">
							<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
								{$language.iso_code|escape:'html':'UTF-8'}
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								{foreach from=$languages item=lang}
								<li><a href="javascript:hideOtherLanguage({$lang.id_lang|intval});javascript:changeLangInfor({$lang.id_lang|intval});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
								{/foreach}
							</ul>
						</div>
					{/if}
					{if $languages|count > 1}
						</div>
					{/if}
				{/foreach}
			</div>
		</div>
		<div class="form-group ps_link" {if $menu->type_link == 1}style="display:none"{/if}>
			<label class="control-label col-lg-3">{l s='PrestaShop Link' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				<select class="form-control fixed-width-lg" name="ps_link" id="ps_link">
					{$all_options|escape:'quotes':'UTF-8'}
				</select>
			</div>
			<script type="text/javascript">
				{if $menu->type_link == 0}
				$(document).ready(function(){
					{if isset($menu->link[$id_language]) &&  $menu->link[$id_language] != ''}
						var ps_link_val = '{$menu->link[$id_language]|escape:"html":"UTF-8"}';
					{else}
						var ps_link_val = 'CAT3';
					{/if}
					$("#ps_link").val(ps_link_val);
				});
				{/if}
			</script>
		</div>
		
		<div class="form-group show_sub" {if $menu->type_link == 1}style="display:none"{/if}>
			<label class="control-label col-lg-3">{l s='Show Sub Categories' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				<span class="switch prestashop-switch fixed-width-lg">
					<input type="radio" name="dropdown" id="dropdown_on" value="1" {if (isset($menu->dropdown) && $menu->dropdown == 1)}checked="checked"{/if}>
					<label for="dropdown_on">Yes</label>
					<input type="radio" name="dropdown" id="dropdown_off" value="0" {if (isset($menu->dropdown) && $menu->dropdown == 0) || !$menu->dropdown}checked="checked"{/if}>
					<label for="dropdown_off">No</label>
					<a class="slide-button btn"></a>
				</span>	
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3">{l s='Sub Title' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				{foreach from=$languages item=language}
					{if $languages|count > 1}
						<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_language}style="display:none"{/if}>
					{/if}
					<div class="col-lg-10">
					<input type="text" class="subtitle" id="subtitle_{$language.id_lang|intval}" name="subtitle_{$language.id_lang|intval}" value="{if $menu->subtitle[$language.id_lang|intval]}{$menu->subtitle[$language.id_lang|intval]}{/if}"/>
					</div>
					{if $languages|count > 1}
						<div class="col-lg-2">
							<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
								{$language.iso_code|escape:'html':'UTF-8'}
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								{foreach from=$languages item=lang}
								<li><a href="javascript:hideOtherLanguage({$lang.id_lang|intval});javascript:changeLangInfor({$lang.id_lang|intval});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
								{/foreach}
							</ul>
						</div>
					{/if}
					{if $languages|count > 1}
						</div>
					{/if}
				{/foreach}
			</div>
		</div>
		
		<div class="form-group rv-type-icon">
			<label class="control-label col-lg-3">{l s='Type Icon' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				<div class="radio rv-radio">
					<label><input type="radio" name="type_icon" id="type_icon_fw" value="1" {if $menu->type_icon == 1}checked="checked"{/if}>Material Icons Icon</label>
				</div>
				<div class="radio rv-radio">
					<label><input type="radio" name="type_icon" id="type_icon_img" value="0" {if $menu->type_icon == 0}checked="checked"{/if}>Image Icon</label>
				</div>
			</div>
		</div>
		
		<div class="form-group rv-fw-icon" {if $menu->type_icon == 0}style="display:none"{/if}>
			<label class="control-label col-lg-3">{l s='Material Icons Icon' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				<input type="text" class="icon_font" id="icon_font" name="icon_font" value="{if $menu->icon && $menu->type_icon == 1}{$menu->icon|escape:'html':'UTF-8'}{/if}"/>
				<p>{l s='Put class icon of Material Icons at :' mod='rvmegamenu'} <a href="https://material.io/tools/icons/?style=baseline">https://material.io/tools/icons/?style=baseline.</a> ex: icon-sun</p>
			</div>
		</div>
		<div class="form-group rv-img-icon" {if $menu->type_icon == 1}style="display:none"{/if}>
			<label class="control-label col-lg-3">{l s='Image Icon' mod='rvmegamenu'}</label>
			<div class="col-lg-9">
				<div class="col-lg-6">
					<input id="icon_img" type="file" name="icon_img" class="hide">
					<div class="dummyfile input-group">
						<span class="input-group-addon"><i class="icon-file"></i></span>
						<input id="icon_img-name" type="text" name="filename" readonly="">
						<span class="input-group-btn">
							<button id="icon_img-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
								<i class="icon-folder-open"></i> {l s='Add file' mod='rvmegamenu'}				</button>
										</span>
					</div>
					{if $menu->type_icon == 0 && isset($menu->icon) && $menu->icon != ''}
						<img src="{$image_baseurl|escape:'html':'UTF-8'}{$menu->icon|escape:'html':'UTF-8'}" class="img-thumbnail"/>
						{if isset($menu->id)}
							<a href="index.php?controller=AdminModules&amp;configure=rvmegamenu&amp;tab_module=front_office_features&amp;module_name=rvmegamenu&amp;token={Tools::getAdminTokenLite('AdminModules')|escape:'html':'UTF-8'}&amp;removeIcon=1&amp;id_rvmegamenu={$menu->id|intval}" id="del_icon">Remove</a>
						{/if}
					{/if}
						<script type="text/javascript">
						$(document).ready(function(){
							$('#icon_img-selectbutton').click(function(e) {
								$('#icon_img').trigger('click');
							});

							$('#icon_img-name').click(function(e) {
								$('#icon_img').trigger('click');
							});

							$('#icon_img-name').on('dragenter', function(e) {
								e.stopPropagation();
								e.preventDefault();
							});

							$('#icon_img-name').on('dragover', function(e) {
								e.stopPropagation();
								e.preventDefault();
							});

							$('#icon_img-name').on('drop', function(e) {
								e.preventDefault();
								var files = e.originalEvent.dataTransfer.files;
								$('#icon_img')[0].files = files;
								$(this).val(files[0].name);
							});

							$('#icon_img').change(function(e) {
								if ($(this)[0].files !== undefined)
								{
									var files = $(this)[0].files;
									var name  = '';

									$.each(files, function(index, value) {
										name += value.name+', ';
									});

									$('#icon_img-name').val(name.slice(0, -2));
								}
								else // Internet Explorer 9 Compatibility
								{
									var name = $(this).val().split(/[\\/]/);
									$('#icon_img-name').val(name[name.length-1]);
								}
							});

							if (typeof icon_img_max_files !== 'undefined')
							{
								$('#icon_img').closest('form').on('submit', function(e) {
									if ($('#icon_img')[0].files.length > icon_img_max_files) {
										e.preventDefault();
										alert('You can upload a maximum of  files');
									}
								});
							}
						});
					</script>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Align Of SubMenu' mod='ttmegamenu'}</label>
		<div class="col-lg-9">
			<select class="form-control fixed-width-lg" name="align_sub" id="align_sub">
				<option value="rv-sub-left">{l s='Left' mod='rvmegamenu'}</option>
				<option value="rv-sub-right">{l s='Right' mod='rvmegamenu'}</option>
				<option value="rv-sub-center">{l s='Center' mod='rvmegamenu'}</option>
				<option value="rv-sub-auto">{l s='Auto' mod='rvmegamenu'}</option>
			</select>
			<script type="text/javascript">
				$(document).ready(function(){
					{if isset($menu->align_sub) && $menu->align_sub != ''}
						var align_sub_val = '{$menu->align_sub|escape:"html":"UTF-8"}';
					{else}
						var align_sub_val = 'rv-sub-auto';
					{/if}
					$('#align_sub').val(align_sub_val);
				});
			</script>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Width Of SubMenu' mod='rvmegamenu'}</label>
		<div class="col-lg-9">
			<select class="form-control fixed-width-lg" name="width_sub" id="width_sub">
				<option value="col-sm-2">col-sm-2</option>
				<option value="col-sm-3">col-sm-3</option>
				<option value="col-sm-4">col-sm-4</option>
				<option value="col-sm-5">col-sm-5</option>
				<option value="col-sm-6">col-sm-6</option>
				<option value="col-sm-7">col-sm-7</option>
				<option value="col-sm-8">col-sm-8</option>
				<option value="col-sm-9">col-sm-9</option>
				<option value="col-sm-10">col-sm-10</option>
				<option value="col-sm-11">col-sm-11</option>
				<option value="col-sm-12">col-sm-12</option>
			</select>
			<script type="text/javascript">
				$(document).ready(function() {
					{if isset($menu->width_sub) &&  $menu->width_sub != ''}
						var width_sub_val = '{$menu->width_sub|escape:"html":"UTF-8"}';
					{else}
						var width_sub_val = 'col-sm-12';
					{/if}
					$("#width_sub").val(width_sub_val);
				});
			</script>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Class' mod='rvmegamenu'}</label>
		<div class="col-lg-9">
			<input type="text" class="class" id="class" name="class" value="{if $menu->class}{$menu->class|escape:'html':'UTF-8'}{/if}"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Active' mod='rvmegamenu'}</label>
		<div class="col-lg-9">
			<span class="switch prestashop-switch fixed-width-lg">
				<input type="radio" name="active" id="active_on" value="1" {if (isset($menu->active) &&  $menu->active != 0) || !$menu->active}checked="checked"{/if}>
				<label for="active_on">Yes</label>
				<input type="radio" name="active" id="active_off" value="0" {if isset($menu->active) && $menu->active == 0}checked="checked"{/if}>
				<label for="active_off">No</label>
				<a class="slide-button btn"></a>
			</span>	
		</div>
	</div>
	
	<div class="panel-footer" style="margin-bottom:10px;">
		<input type="hidden" name="id_rvmegamenu" id="id_rvmegamenu" value="{if isset($menu->id)}{$menu->id|intval}{/if}"/>
		<button type="submit" value="1" id="module_form_submit_btn" name="submitMenuItem" class="btn btn-default pull-right">
			<i class="process-icon-save"></i> Save
		</button>
		<a href="index.php?controller=AdminModules&amp;configure=rvmegamenu&amp;token={$token|escape:'html':'UTF-8'}" class="btn btn-default">
		<i class="process-icon-back"></i> Back to list</a>
	</div>
	
</div>
</form>