<?php
/* Smarty version 3.1.33, created on 2019-08-08 12:22:34
  from '/html/themes/PRSD81/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4c5a5a4de875_03991352',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f51698f038bb3fbcff7144aa7ffa52c9c06a58fe' => 
    array (
      0 => '/html/themes/PRSD81/templates/page.tpl',
      1 => 1565108983,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4c5a5a4de875_03991352 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_350544105d4c5a5a4c5771_51153949', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_20007431545d4c5a5a4c6d53_15444092 extends Smarty_Internal_Block
{
public $callsChild = 'true';
public $hide = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <header data-html2canvas-ignore class="page-header">
          <h1><?php 
$_smarty_tpl->inheritance->callChild($_smarty_tpl, $this);
?>
</h1>
        </header>
      <?php
}
}
/* {/block 'page_title'} */
/* {block 'page_header_container'} */
class Block_19889027855d4c5a5a4c6367_28623962 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20007431545d4c5a5a4c6d53_15444092', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_6369308415d4c5a5a4d7291_90620367 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_21466022185d4c5a5a4d89c2_45710421 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_11148691045d4c5a5a4c9225_00954400 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6369308415d4c5a5a4d7291_90620367', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21466022185d4c5a5a4d89c2_45710421', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_10582540115d4c5a5a4dc683_03046152 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_19178972875d4c5a5a4db984_94333000 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer data-html2canvas-ignore class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10582540115d4c5a5a4dc683_03046152', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_350544105d4c5a5a4c5771_51153949 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_350544105d4c5a5a4c5771_51153949',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_19889027855d4c5a5a4c6367_28623962',
  ),
  'page_title' => 
  array (
    0 => 'Block_20007431545d4c5a5a4c6d53_15444092',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_11148691045d4c5a5a4c9225_00954400',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_6369308415d4c5a5a4d7291_90620367',
  ),
  'page_content' => 
  array (
    0 => 'Block_21466022185d4c5a5a4d89c2_45710421',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_19178972875d4c5a5a4db984_94333000',
  ),
  'page_footer' => 
  array (
    0 => 'Block_10582540115d4c5a5a4dc683_03046152',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19889027855d4c5a5a4c6367_28623962', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11148691045d4c5a5a4c9225_00954400', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19178972875d4c5a5a4db984_94333000', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
