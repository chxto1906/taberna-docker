<?php
/* Smarty version 3.1.33, created on 2019-08-26 12:11:39
  from '/html/themes/PRSD81/templates/_partials/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d6412cbc51545_49917672',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f3f7092a341d2e1bfddd30859d9d2abdb80038c3' => 
    array (
      0 => '/html/themes/PRSD81/templates/_partials/footer.tpl',
      1 => 1566570714,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d6412cbc51545_49917672 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
 <div class="footer-container-before">
  <div class="container">
    <div class="row">
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9539853045d6412cbc43306_30546292', 'hook_footer_before');
?>

    </div>
  </div>
</div>
<div data-html2canvas-ignore class="footer-container">
  <div class="container">
    <div class="row">
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5338819845d6412cbc45e54_86590617', 'hook_footer');
?>

    </div>
  </div>
</div>
<div data-html2canvas-ignore class="footer-container-after rv-animate-element bottom-to-top">
  <div class="container">
    <div class="row">
      <div class="footer-container-after-inner">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7921353295d6412cbc47ac9_55818281', 'hook_footer_after');
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8684957855d6412cbc48ae6_29543975', 'copyright_link');
?>

      </div>
    </div>
  </div>
</div>

<?php }
/* {block 'hook_footer_before'} */
class Block_9539853045d6412cbc43306_30546292 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'hook_footer_before' => 
  array (
    0 => 'Block_9539853045d6412cbc43306_30546292',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooterBefore'),$_smarty_tpl ) );?>

      <?php
}
}
/* {/block 'hook_footer_before'} */
/* {block 'hook_footer'} */
class Block_5338819845d6412cbc45e54_86590617 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'hook_footer' => 
  array (
    0 => 'Block_5338819845d6412cbc45e54_86590617',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooter'),$_smarty_tpl ) );?>

      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayDownloadApps'),$_smarty_tpl ) );?>

      <?php
}
}
/* {/block 'hook_footer'} */
/* {block 'hook_footer_after'} */
class Block_7921353295d6412cbc47ac9_55818281 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'hook_footer_after' => 
  array (
    0 => 'Block_7921353295d6412cbc47ac9_55818281',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooterAfter'),$_smarty_tpl ) );?>

        <?php
}
}
/* {/block 'hook_footer_after'} */
/* {block 'copyright_link'} */
class Block_8684957855d6412cbc48ae6_29543975 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'copyright_link' => 
  array (
    0 => 'Block_8684957855d6412cbc48ae6_29543975',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <div class="copyright col-lg-4 col-md-12">
          <!--<a class="_blank" href="#">-->
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'%copyright% %year% - Ecommerce software by %prestashop%','sprintf'=>array('%prestashop%'=>'PrestaShop™','%year%'=>date('Y'),'%copyright%'=>'©'),'d'=>'Shop.Theme.Global'),$_smarty_tpl ) );?>

          <!--</a>-->
        </div>
        <?php
}
}
/* {/block 'copyright_link'} */
}
