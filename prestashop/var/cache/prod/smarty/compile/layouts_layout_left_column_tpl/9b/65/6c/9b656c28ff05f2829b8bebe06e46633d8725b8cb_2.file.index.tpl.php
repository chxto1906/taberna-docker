<?php
/* Smarty version 3.1.33, created on 2019-08-06 17:31:42
  from '/html/themes/PRSD81/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49ffce2d6df1_05011673',
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
function content_5d49ffce2d6df1_05011673 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8474602495d49ffce2cb5b0_23683396', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_6651571585d49ffce2d2c96_35796484 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_18794749205d49ffce2d4e86_09806674 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_14147056425d49ffce2d41a9_46546484 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18794749205d49ffce2d4e86_09806674', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_8474602495d49ffce2cb5b0_23683396 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_8474602495d49ffce2cb5b0_23683396',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_6651571585d49ffce2d2c96_35796484',
  ),
  'page_content' => 
  array (
    0 => 'Block_14147056425d49ffce2d41a9_46546484',
  ),
  'hook_home' => 
  array (
    0 => 'Block_18794749205d49ffce2d4e86_09806674',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6651571585d49ffce2d2c96_35796484', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14147056425d49ffce2d41a9_46546484', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
