<?php
/* Smarty version 3.1.33, created on 2019-07-31 17:26:43
  from 'module:psbestsellersviewstemplat' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4215a30877b0_04776594',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3681aa30d1f85f48e2cf4794b77200e697f706a9' => 
    array (
      0 => 'module:psbestsellersviewstemplat',
      1 => 1561678045,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
    'file:catalog/_partials/miniatures/product-left.tpl' => 1,
  ),
),false)) {
function content_5d4215a30877b0_04776594 (Smarty_Internal_Template $_smarty_tpl) {
?>
<section class="block products_block rv-animate-element left-to-right">
  <h4 class="title_block">
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Best Sellers','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>

  </h4>
  <div class="block_content products-block">
    <div id="bestseller-carousel" class="products owl-carousel">
      <?php $_smarty_tpl->_assignInScope('counter', 1);?>
      <?php ob_start();
echo htmlspecialchars(count($_smarty_tpl->tpl_vars['products']->value), ENT_QUOTES, 'UTF-8');
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('lastproduct', $_prefixVariable1);?>
     <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products']->value, 'product');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
?>
      <?php if ($_smarty_tpl->tpl_vars['counter']->value%3 == 1) {?> 
      <div class="multiple-item">
        <?php }?>
      <?php $_smarty_tpl->_subTemplateRender("file:catalog/_partials/miniatures/product-left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('product'=>$_smarty_tpl->tpl_vars['product']->value), 0, true);
?>
      <?php if (($_smarty_tpl->tpl_vars['counter']->value%3 == 0 || $_smarty_tpl->tpl_vars['counter']->value == $_smarty_tpl->tpl_vars['lastproduct']->value)) {?>
    </div>
    <?php }?>
    <?php $_smarty_tpl->_assignInScope('counter', $_smarty_tpl->tpl_vars['counter']->value+1);?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
    <a class="all-product-link" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['allBestSellers']->value, ENT_QUOTES, 'UTF-8');?>
">
      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'All best sellers','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>

    </a>
  </div>
</section>
<?php }
}
