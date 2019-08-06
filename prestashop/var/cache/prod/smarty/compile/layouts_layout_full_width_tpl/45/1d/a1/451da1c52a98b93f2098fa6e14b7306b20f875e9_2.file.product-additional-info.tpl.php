<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:46:43
  from '/html/themes/PRSD81/templates/catalog/_partials/product-additional-info.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4992d371b3b6_48695643',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '451da1c52a98b93f2098fa6e14b7306b20f875e9' => 
    array (
      0 => '/html/themes/PRSD81/templates/catalog/_partials/product-additional-info.tpl',
      1 => 1561678046,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4992d371b3b6_48695643 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="product-additional-info">
  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductAdditionalInfo','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPage'),$_smarty_tpl ) );?>

</div>
<?php }
}
