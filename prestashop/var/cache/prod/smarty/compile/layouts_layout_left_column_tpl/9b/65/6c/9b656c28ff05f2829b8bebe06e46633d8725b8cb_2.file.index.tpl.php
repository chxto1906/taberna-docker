<?php
/* Smarty version 3.1.33, created on 2019-08-26 12:11:39
  from '/html/themes/PRSD81/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d6412cb4b0911_15390646',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b656c28ff05f2829b8bebe06e46633d8725b8cb' => 
    array (
      0 => '/html/themes/PRSD81/templates/index.tpl',
      1 => 1566570714,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d6412cb4b0911_15390646 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20562741015d6412cb4acf56_88321641', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_21237769485d6412cb4ad8e5_58865277 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_10684680145d6412cb4aedc2_44792095 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_5410627485d6412cb4ae580_36489481 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10684680145d6412cb4aedc2_44792095', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_20562741015d6412cb4acf56_88321641 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_20562741015d6412cb4acf56_88321641',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_21237769485d6412cb4ad8e5_58865277',
  ),
  'page_content' => 
  array (
    0 => 'Block_5410627485d6412cb4ae580_36489481',
  ),
  'hook_home' => 
  array (
    0 => 'Block_10684680145d6412cb4aedc2_44792095',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21237769485d6412cb4ad8e5_58865277', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5410627485d6412cb4ae580_36489481', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
