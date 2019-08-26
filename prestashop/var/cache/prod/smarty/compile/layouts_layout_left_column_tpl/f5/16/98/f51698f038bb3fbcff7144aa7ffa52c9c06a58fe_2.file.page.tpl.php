<?php
/* Smarty version 3.1.33, created on 2019-08-26 12:11:39
  from '/html/themes/PRSD81/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d6412cb4f9321_00120056',
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
function content_5d6412cb4f9321_00120056 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16542179475d6412cb4ea7d9_35018088', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_18631664525d6412cb4eb997_29222488 extends Smarty_Internal_Block
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
class Block_21067811315d6412cb4eb0b3_89298408 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18631664525d6412cb4eb997_29222488', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_4859607395d6412cb4f5c86_60882663 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_9481369855d6412cb4f6823_19378565 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_2401871505d6412cb4f5291_77675418 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4859607395d6412cb4f5c86_60882663', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9481369855d6412cb4f6823_19378565', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_11721541535d6412cb4f8125_32419952 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_19324494495d6412cb4f7942_41167610 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer data-html2canvas-ignore class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11721541535d6412cb4f8125_32419952', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_16542179475d6412cb4ea7d9_35018088 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_16542179475d6412cb4ea7d9_35018088',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_21067811315d6412cb4eb0b3_89298408',
  ),
  'page_title' => 
  array (
    0 => 'Block_18631664525d6412cb4eb997_29222488',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_2401871505d6412cb4f5291_77675418',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_4859607395d6412cb4f5c86_60882663',
  ),
  'page_content' => 
  array (
    0 => 'Block_9481369855d6412cb4f6823_19378565',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_19324494495d6412cb4f7942_41167610',
  ),
  'page_footer' => 
  array (
    0 => 'Block_11721541535d6412cb4f8125_32419952',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21067811315d6412cb4eb0b3_89298408', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2401871505d6412cb4f5291_77675418', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19324494495d6412cb4f7942_41167610', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
