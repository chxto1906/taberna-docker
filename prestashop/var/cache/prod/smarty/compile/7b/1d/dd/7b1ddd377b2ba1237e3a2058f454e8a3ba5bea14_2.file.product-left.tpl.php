<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:49:00
  from '/html/themes/PRSD81/templates/catalog/_partials/miniatures/product-left.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49935ccc14f9_47841379',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7b1ddd377b2ba1237e3a2058f454e8a3ba5bea14' => 
    array (
      0 => '/html/themes/PRSD81/templates/catalog/_partials/miniatures/product-left.tpl',
      1 => 1561678046,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49935ccc14f9_47841379 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<article class="product-miniature js-product-miniature" data-id-product="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product'], ENT_QUOTES, 'UTF-8');?>
" data-id-product-attribute="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product_attribute'], ENT_QUOTES, 'UTF-8');?>
" itemscope itemtype="http://schema.org/Product">
    <div class="product-container">
        <div class="thumbnail-container">
            <div class="thumbnail-inner">
                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15978449785d49935ccaf4f0_32328706', 'product_thumbnail');
?>

            </div>
        </div>
        <div class="product-description">
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12824829895d49935ccb74e2_46578014', 'product_name');
?>

            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1422109035d49935ccb8dc0_33205560', 'product_price_and_shipping');
?>

             <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5129329515d49935ccc0351_16184317', 'product_reviews');
?>

        </div>
    </div>
</article><?php }
/* {block 'product_thumbnail'} */
class Block_15978449785d49935ccaf4f0_32328706 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_thumbnail' => 
  array (
    0 => 'Block_15978449785d49935ccaf4f0_32328706',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
" class="thumbnail product-thumbnail">
                        <img src = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['bySize']['small_default']['url'], ENT_QUOTES, 'UTF-8');?>
" alt = "<?php if (!empty($_smarty_tpl->tpl_vars['product']->value['cover']['legend'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['legend'], ENT_QUOTES, 'UTF-8');
} else {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],30,'...' )), ENT_QUOTES, 'UTF-8');
}?>" data-full-size-image-url = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['large']['url'], ENT_QUOTES, 'UTF-8');?>
" width = "80" />
                        <?php if (isset($_smarty_tpl->tpl_vars['product']->value['images'][1])) {?>
                            <img class="replace-2x img_1 img-responsive" src = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['images'][1]['bySize']['small_default']['url'], ENT_QUOTES, 'UTF-8');?>
" alt = "<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],30,'...' )), ENT_QUOTES, 'UTF-8');?>
" title="" width = "80" />
                        <?php }?>
                    </a>
                <?php
}
}
/* {/block 'product_thumbnail'} */
/* {block 'product_name'} */
class Block_12824829895d49935ccb74e2_46578014 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_name' => 
  array (
    0 => 'Block_12824829895d49935ccb74e2_46578014',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <h1 class="h3 product-title" itemprop="name"><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>
</a></h1>
            <?php
}
}
/* {/block 'product_name'} */
/* {block 'product_price_and_shipping'} */
class Block_1422109035d49935ccb8dc0_33205560 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_price_and_shipping' => 
  array (
    0 => 'Block_1422109035d49935ccb8dc0_33205560',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <?php if ($_smarty_tpl->tpl_vars['product']->value['show_price']) {?>
                    <div class="product-price-and-shipping">
                        <?php if ($_smarty_tpl->tpl_vars['product']->value['has_discount']) {?>
                            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPriceBlock','product'=>$_smarty_tpl->tpl_vars['product']->value,'type'=>"old_price"),$_smarty_tpl ) );?>


                            <span class="regular-price"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['regular_price'], ENT_QUOTES, 'UTF-8');?>
</span>
                            <?php if ($_smarty_tpl->tpl_vars['product']->value['discount_type'] === 'percentage') {?>
                                <span class="discount-percentage"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['discount_percentage'], ENT_QUOTES, 'UTF-8');?>
</span>
                            <?php }?>
                        <?php }?>
                        <span itemprop="price" class="price"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['price'], ENT_QUOTES, 'UTF-8');?>
</span>
                    </div>
                <?php }?>
            <?php
}
}
/* {/block 'product_price_and_shipping'} */
/* {block 'product_reviews'} */
class Block_5129329515d49935ccc0351_16184317 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_reviews' => 
  array (
    0 => 'Block_5129329515d49935ccc0351_16184317',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductListReviews','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

            <?php
}
}
/* {/block 'product_reviews'} */
}
