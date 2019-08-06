<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:08:28
  from '/html/modules/rvwishlistblock/views/templates/hook/my-account.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4989dc1f1ee7_16741400',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9a29b7ab599f4d86461cdc6bbb08c9c8cfd56424' => 
    array (
      0 => '/html/modules/rvwishlistblock/views/templates/hook/my-account.tpl',
      1 => 1561678042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4989dc1f1ee7_16741400 (Smarty_Internal_Template $_smarty_tpl) {
?>
<a class="col-lg-4 col-md-6 col-sm-6 col-xs-12" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getModuleLink('rvwishlistblock','mywishlist',array(),true),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'My wishlists','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
">
    <span class="link-item">
        <i class="material-icons">&#xE87D;</i>
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'My wishlists','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>

    </span>
</a><?php }
}
