<?php
/* Smarty version 3.1.33, created on 2019-08-13 15:12:50
  from '/html/themes/PRSD81/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5319c288b109_25900641',
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
function content_5d5319c288b109_25900641 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10288824255d5319c2856f39_80420012', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_8898359455d5319c285bcb7_85786150 extends Smarty_Internal_Block
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
class Block_3761923105d5319c2858500_00475966 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8898359455d5319c285bcb7_85786150', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_10252221345d5319c2883571_22384614 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_8716097805d5319c2885c42_60035737 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_5036013585d5319c2880d16_95191956 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10252221345d5319c2883571_22384614', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8716097805d5319c2885c42_60035737', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_14411509545d5319c28895f9_05203133 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_3871588565d5319c2888905_92099170 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer data-html2canvas-ignore class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14411509545d5319c28895f9_05203133', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_10288824255d5319c2856f39_80420012 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_10288824255d5319c2856f39_80420012',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_3761923105d5319c2858500_00475966',
  ),
  'page_title' => 
  array (
    0 => 'Block_8898359455d5319c285bcb7_85786150',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_5036013585d5319c2880d16_95191956',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_10252221345d5319c2883571_22384614',
  ),
  'page_content' => 
  array (
    0 => 'Block_8716097805d5319c2885c42_60035737',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_3871588565d5319c2888905_92099170',
  ),
  'page_footer' => 
  array (
    0 => 'Block_14411509545d5319c28895f9_05203133',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3761923105d5319c2858500_00475966', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5036013585d5319c2880d16_95191956', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3871588565d5319c2888905_92099170', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
