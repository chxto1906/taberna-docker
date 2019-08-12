<?php
/* Smarty version 3.1.33, created on 2019-08-12 15:06:15
  from '/html/themes/PRSD81/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d51c6b72e97f0_39609878',
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
function content_5d51c6b72e97f0_39609878 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21165658205d51c6b72e37a4_95026073', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_18268711205d51c6b72e4803_56457795 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_10696754635d51c6b72e6a34_10107316 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_1272196675d51c6b72e5c50_04027927 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10696754635d51c6b72e6a34_10107316', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_21165658205d51c6b72e37a4_95026073 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_21165658205d51c6b72e37a4_95026073',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_18268711205d51c6b72e4803_56457795',
  ),
  'page_content' => 
  array (
    0 => 'Block_1272196675d51c6b72e5c50_04027927',
  ),
  'hook_home' => 
  array (
    0 => 'Block_10696754635d51c6b72e6a34_10107316',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18268711205d51c6b72e4803_56457795', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1272196675d51c6b72e5c50_04027927', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
