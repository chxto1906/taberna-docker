<?php
/* Smarty version 3.1.33, created on 2019-08-06 17:31:42
  from '/html/modules/rvwishlistblock/views/templates/hook/rvwishlist_top.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49ffce605327_33006150',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '933eca880e673d92c8dc4103cbc28c00d7e42d84' => 
    array (
      0 => '/html/modules/rvwishlistblock/views/templates/hook/rvwishlist_top.tpl',
      1 => 1561678042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49ffce605327_33006150 (Smarty_Internal_Template $_smarty_tpl) {
?>
	<div class="wishtlist_top" id="_desktop_wishlist">
		<a class="wishlist-logo" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getModuleLink('rvwishlistblock','mywishlist',array(),true),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Wishlists','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
" rel="nofollow">
			<i class="fa fa-heart" aria-hidden="true"></i>
			<span class="wishlist-title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Wishlist','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</span>
			<span class="cart-wishlist-number">(<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['count_product']->value, ENT_QUOTES, 'UTF-8');?>
)</span>
		</a>
	</div>
<?php }
}
