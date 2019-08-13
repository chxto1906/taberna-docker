<?php
/* Smarty version 3.1.33, created on 2019-08-13 15:12:50
  from '/html/themes/PRSD81/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5319c27d3cd4_30296473',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b656c28ff05f2829b8bebe06e46633d8725b8cb' => 
    array (
      0 => '/html/themes/PRSD81/templates/index.tpl',
      1 => 1561678046,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d5319c27d3cd4_30296473 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8009794885d5319c27c99c8_20588029', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_3890253005d5319c27cabe3_78789226 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_5661486785d5319c27ccc92_09554447 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_16901884375d5319c27cbd72_52526843 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5661486785d5319c27ccc92_09554447', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_8009794885d5319c27c99c8_20588029 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_8009794885d5319c27c99c8_20588029',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_3890253005d5319c27cabe3_78789226',
  ),
  'page_content' => 
  array (
    0 => 'Block_16901884375d5319c27cbd72_52526843',
  ),
  'hook_home' => 
  array (
    0 => 'Block_5661486785d5319c27ccc92_09554447',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3890253005d5319c27cabe3_78789226', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16901884375d5319c27cbd72_52526843', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
