<?php
/* Smarty version 3.1.33, created on 2019-08-06 17:31:42
  from '/html/themes/PRSD81/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49ffce32b394_78625323',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f51698f038bb3fbcff7144aa7ffa52c9c06a58fe' => 
    array (
      0 => '/html/themes/PRSD81/templates/page.tpl',
      1 => 1563466734,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49ffce32b394_78625323 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18744477295d49ffce315c25_38152778', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_12443143895d49ffce317141_37683954 extends Smarty_Internal_Block
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
class Block_4420869815d49ffce3164c3_38316263 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12443143895d49ffce317141_37683954', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_13901677715d49ffce324c35_28374142 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_11219114985d49ffce326d39_45243547 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_19705088635d49ffce321bf5_55811768 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13901677715d49ffce324c35_28374142', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11219114985d49ffce326d39_45243547', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_77856175d49ffce3298a3_31502038 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_11284383825d49ffce329098_38457100 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer data-html2canvas-ignore class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_77856175d49ffce3298a3_31502038', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_18744477295d49ffce315c25_38152778 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_18744477295d49ffce315c25_38152778',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_4420869815d49ffce3164c3_38316263',
  ),
  'page_title' => 
  array (
    0 => 'Block_12443143895d49ffce317141_37683954',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_19705088635d49ffce321bf5_55811768',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_13901677715d49ffce324c35_28374142',
  ),
  'page_content' => 
  array (
    0 => 'Block_11219114985d49ffce326d39_45243547',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_11284383825d49ffce329098_38457100',
  ),
  'page_footer' => 
  array (
    0 => 'Block_77856175d49ffce3298a3_31502038',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4420869815d49ffce3164c3_38316263', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19705088635d49ffce321bf5_55811768', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11284383825d49ffce329098_38457100', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
