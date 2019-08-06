<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:49:06
  from '/html/modules/rvwishlistblock/views/templates/hook/footerlink.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49936249b1f5_89668521',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c35bf851ea940f69f7375313b47f3555e4a71340' => 
    array (
      0 => '/html/modules/rvwishlistblock/views/templates/hook/footerlink.tpl',
      1 => 1561678042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49936249b1f5_89668521 (Smarty_Internal_Template $_smarty_tpl) {
?>
<li>
    <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getModuleLink('rvwishlistblock','mywishlist',array(),true),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'My wishlists','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'My Wishlist','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>

    </a>
</li>
<?php }
}
