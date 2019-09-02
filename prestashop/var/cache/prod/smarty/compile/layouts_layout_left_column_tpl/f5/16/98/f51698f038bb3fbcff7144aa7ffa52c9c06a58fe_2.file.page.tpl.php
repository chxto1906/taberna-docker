<?php
/* Smarty version 3.1.33, created on 2019-09-02 11:37:13
  from '/html/themes/PRSD81/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d6d45393ec728_23261828',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f51698f038bb3fbcff7144aa7ffa52c9c06a58fe' => 
    array (
      0 => '/html/themes/PRSD81/templates/page.tpl',
      1 => 1566570714,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d6d45393ec728_23261828 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14084632805d6d45393d7c78_06438507', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_20840386365d6d45393d9a34_32749807 extends Smarty_Internal_Block
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
class Block_6526998225d6d45393d8956_85937898 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20840386365d6d45393d9a34_32749807', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_4706911945d6d45393e95d3_84568120 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_2927102735d6d45393ea2d5_56171488 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_6736847765d6d45393e8aa8_92927459 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4706911945d6d45393e95d3_84568120', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2927102735d6d45393ea2d5_56171488', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_7716392675d6d45393eb744_40156109 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_7946688195d6d45393eb119_72324619 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer data-html2canvas-ignore class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7716392675d6d45393eb744_40156109', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_14084632805d6d45393d7c78_06438507 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_14084632805d6d45393d7c78_06438507',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_6526998225d6d45393d8956_85937898',
  ),
  'page_title' => 
  array (
    0 => 'Block_20840386365d6d45393d9a34_32749807',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_6736847765d6d45393e8aa8_92927459',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_4706911945d6d45393e95d3_84568120',
  ),
  'page_content' => 
  array (
    0 => 'Block_2927102735d6d45393ea2d5_56171488',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_7946688195d6d45393eb119_72324619',
  ),
  'page_footer' => 
  array (
    0 => 'Block_7716392675d6d45393eb744_40156109',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6526998225d6d45393d8956_85937898', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6736847765d6d45393e8aa8_92927459', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7946688195d6d45393eb119_72324619', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
