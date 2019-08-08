<?php
/* Smarty version 3.1.33, created on 2019-08-08 12:09:40
  from '/html/backoffice/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4c57542a6e49_20663009',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f0a777fa432cb525ea02f8156fff74b7bcd3c129' => 
    array (
      0 => '/html/backoffice/themes/default/template/content.tpl',
      1 => 1565108980,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4c57542a6e49_20663009 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
