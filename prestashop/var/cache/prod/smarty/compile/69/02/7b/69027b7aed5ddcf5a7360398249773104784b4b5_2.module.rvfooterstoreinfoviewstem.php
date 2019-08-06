<?php
/* Smarty version 3.1.33, created on 2019-08-06 17:31:43
  from 'module:rvfooterstoreinfoviewstem' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49ffcfb95b56_52445367',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '69027b7aed5ddcf5a7360398249773104784b4b5' => 
    array (
      0 => 'module:rvfooterstoreinfoviewstem',
      1 => 1561678042,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49ffcfb95b56_52445367 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div id="rvfooterstoreinfo" class="footer-block col-lg-12 rv-animate-element bottom-to-top">
	<h4 class="title_block">
		<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'About Sports','mod'=>'rvfooterstoreinfo'),$_smarty_tpl ) );?>

	</h4>
	<div class="block_content toggle-footer">
		<div class="storeinfo_img">
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['base_url'], ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
">
				<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['rvfooterstoreinfo_img']->value, ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
" />
			</a>
		</div>
		
		<?php if (!empty($_smarty_tpl->tpl_vars['rvfooterstoreinfo_desc']->value)) {?>
	        <p class="storeinfo-desc">
    	    	<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['rvfooterstoreinfo_desc']->value, ENT_QUOTES, 'UTF-8');?>

    		</p>
        <?php }?>
	</div>
</div><?php }
}
