<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:32:58
  from '/html/backoffice/themes/new-theme/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d498f9a5bf087_86536644',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2f24734921b50d21dcb5fa4dd7ddf1b32ba39f9b' => 
    array (
      0 => '/html/backoffice/themes/new-theme/template/content.tpl',
      1 => 1561677885,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d498f9a5bf087_86536644 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div id="ajax_confirmation" class="alert alert-success" style="display: none;"></div>


<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
  <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

<?php }
}
}
