<?php
/* Smarty version 3.1.33, created on 2019-08-12 15:06:15
  from '/html/themes/PRSD81/templates/layouts/layout-left-column.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d51c6b7408bc6_89885343',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ebe9c2425b06994f6ef74ac03546ec1da1b3896d' => 
    array (
      0 => '/html/themes/PRSD81/templates/layouts/layout-left-column.tpl',
      1 => 1561678046,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d51c6b7408bc6_89885343 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_708285575d51c6b7404264_44790018', 'right_column');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21067984255d51c6b7405071_26601314', 'content_wrapper');
?>


<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'layouts/layout-both-columns.tpl');
}
/* {block 'right_column'} */
class Block_708285575d51c6b7404264_44790018 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'right_column' => 
  array (
    0 => 'Block_708285575d51c6b7404264_44790018',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'right_column'} */
/* {block 'content'} */
class Block_9869425165d51c6b7407582_67658955 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <p>Hello world! This is HTML5 Boilerplate.</p>
    <?php
}
}
/* {/block 'content'} */
/* {block 'content_wrapper'} */
class Block_21067984255d51c6b7405071_26601314 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content_wrapper' => 
  array (
    0 => 'Block_21067984255d51c6b7405071_26601314',
  ),
  'content' => 
  array (
    0 => 'Block_9869425165d51c6b7407582_67658955',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'index') {?>
		<div id="content-wrapper" class="left-column col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<?php } else { ?>
    	<div id="content-wrapper" class="left-column col-xs-12 col-sm-12 col-md-12 col-lg-10">
	<?php }?>
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayContentWrapperTop"),$_smarty_tpl ) );?>

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9869425165d51c6b7407582_67658955', 'content', $this->tplIndex);
?>

    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayContentWrapperBottom"),$_smarty_tpl ) );?>

  </div>
<?php
}
}
/* {/block 'content_wrapper'} */
}
