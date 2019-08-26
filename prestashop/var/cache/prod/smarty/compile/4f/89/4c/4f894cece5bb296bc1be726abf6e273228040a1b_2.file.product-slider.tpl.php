<?php
/* Smarty version 3.1.33, created on 2019-08-26 12:11:38
  from '/html/themes/PRSD81/templates/catalog/_partials/miniatures/product-slider.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d6412ca6f5e35_75646749',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4f894cece5bb296bc1be726abf6e273228040a1b' => 
    array (
      0 => '/html/themes/PRSD81/templates/catalog/_partials/miniatures/product-slider.tpl',
      1 => 1566570714,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:catalog/_partials/variant-links.tpl' => 1,
  ),
),false)) {
function content_5d6412ca6f5e35_75646749 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<div class="product-container">
    <div class="thumbnail-container">
        <div class="thumbnail-inner">
            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5451519065d6412ca6bbd65_83350873', 'product_thumbnail');
?>

            <div class="product-buttons">
                <div class="product-cart-btn">
                    <form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['pages']['cart'], ENT_QUOTES, 'UTF-8');?>
" method="post" class="add-to-cart-or-refresh">
                        <div class="product-quantity">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['static_token']->value, ENT_QUOTES, 'UTF-8');?>
" class="token">
                            <input type="hidden" name="id_product" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product'], ENT_QUOTES, 'UTF-8');?>
">
                            <input type="hidden" name="qty" value="1" />
                            <button class="btn btn-primary ajax_add_to_cart_button add-to-cart" data-button-action="add-to-cart" <?php if (!$_smarty_tpl->tpl_vars['product']->value['quantity']) {?>disabled<?php }?>>
                                <i class="fa fa-shopping-bag"></i>
                                <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add to cart','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                            </button>
                        </div>
                    </form>
                </div>
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayRvWishlist','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayRvCompareButton','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

                <div class="product-quick-btn">
                    <a href="#" class="quick-view btn btn-primary" data-link-action="quickview" >
                        <i class="fa fa-eye"></i>
                        <span class="lblquickview"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quick view','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</span>
                    </a>
                </div>
            </div>
            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductCountdown','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16943966835d6412ca6d31a5_36271425', 'product_flags');
?>

            <div class="product-description">
                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2495835175d6412ca6daef9_45455357', 'product_reviews');
?>


                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17061415505d6412ca6dc3d9_55661818', 'product_name');
?>


                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3732072015d6412ca6dde84_84447676', 'product_price_and_shipping');
?>


                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10862201665d6412ca6e54b3_28458373', 'product_desc');
?>


                <div class="highlighted-informations<?php if (!$_smarty_tpl->tpl_vars['product']->value['main_variants']) {?> no-variants<?php }?>">
                    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5973478955d6412ca6f3860_08682026', 'product_variants');
?>

                </div>
            </div>
        </div>
    </div>
</div><?php }
/* {block 'product_thumbnail'} */
class Block_5451519065d6412ca6bbd65_83350873 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_thumbnail' => 
  array (
    0 => 'Block_5451519065d6412ca6bbd65_83350873',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
" class="thumbnail product-thumbnail">
                <img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['bySize']['home_default']['url'], ENT_QUOTES, 'UTF-8');?>
" alt = "<?php if (!empty($_smarty_tpl->tpl_vars['product']->value['cover']['legend'])) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['legend'], ENT_QUOTES, 'UTF-8');
} else {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],30,'...' )), ENT_QUOTES, 'UTF-8');
}?>" data-full-size-image-url = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['large']['url'], ENT_QUOTES, 'UTF-8');?>
"  data-lazyloading="0" />
                <?php if (isset($_smarty_tpl->tpl_vars['product']->value['images'][1])) {?>
                <img class="replace-2x img_1 img-responsive lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src = "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['images'][1]['bySize']['home_default']['url'], ENT_QUOTES, 'UTF-8');?>
"  alt = "<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],30,'...' )), ENT_QUOTES, 'UTF-8');?>
"  data-lazyloading="0" alt="Image" />
                <?php }?>
            </a>
            <?php
}
}
/* {/block 'product_thumbnail'} */
/* {block 'product_flags'} */
class Block_16943966835d6412ca6d31a5_36271425 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_flags' => 
  array (
    0 => 'Block_16943966835d6412ca6d31a5_36271425',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <ul class="product-flags">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['flags'], 'flag');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['flag']->value) {
?>
                <li class="product-flag <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['flag']->value['type'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['flag']->value['label'], ENT_QUOTES, 'UTF-8');?>
</li>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </ul>
            <?php
}
}
/* {/block 'product_flags'} */
/* {block 'product_reviews'} */
class Block_2495835175d6412ca6daef9_45455357 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_reviews' => 
  array (
    0 => 'Block_2495835175d6412ca6daef9_45455357',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductListReviews','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

                <?php
}
}
/* {/block 'product_reviews'} */
/* {block 'product_name'} */
class Block_17061415505d6412ca6dc3d9_55661818 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_name' => 
  array (
    0 => 'Block_17061415505d6412ca6dc3d9_55661818',
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
class Block_3732072015d6412ca6dde84_84447676 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_price_and_shipping' => 
  array (
    0 => 'Block_3732072015d6412ca6dde84_84447676',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <?php if ($_smarty_tpl->tpl_vars['product']->value['show_price']) {?>
                <div class="product-price-and-shipping">
                    <?php if ($_smarty_tpl->tpl_vars['product']->value['has_discount']) {?>
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPriceBlock','product'=>$_smarty_tpl->tpl_vars['product']->value,'type'=>"old_price"),$_smarty_tpl ) );?>


                    <span class="sr-only"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Regular price','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</span>
                    <span class="regular-price"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['regular_price'], ENT_QUOTES, 'UTF-8');?>
</span>
                    <?php if ($_smarty_tpl->tpl_vars['product']->value['discount_type'] === 'percentage') {?>
                    <span class="discount-percentage"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['discount_percentage'], ENT_QUOTES, 'UTF-8');?>
</span>
                    <?php }?>
                    <?php }?>

                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPriceBlock','product'=>$_smarty_tpl->tpl_vars['product']->value,'type'=>"before_price"),$_smarty_tpl ) );?>


                    <span class="sr-only"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Price','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</span>
                    <span itemprop="price" class="price"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['price'], ENT_QUOTES, 'UTF-8');?>
</span>

                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPriceBlock','product'=>$_smarty_tpl->tpl_vars['product']->value,'type'=>'unit_price'),$_smarty_tpl ) );?>


                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductPriceBlock','product'=>$_smarty_tpl->tpl_vars['product']->value,'type'=>'weight'),$_smarty_tpl ) );?>

                </div>
                <?php }?>
                <?php
}
}
/* {/block 'product_price_and_shipping'} */
/* {block 'product_desc'} */
class Block_10862201665d6412ca6e54b3_28458373 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_desc' => 
  array (
    0 => 'Block_10862201665d6412ca6e54b3_28458373',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <p class="product-desc" itemprop="description">
                    <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( strip_tags($_smarty_tpl->tpl_vars['product']->value['description_short']),360,'...' )), ENT_QUOTES, 'UTF-8');?>

                </p>
                <?php
}
}
/* {/block 'product_desc'} */
/* {block 'product_variants'} */
class Block_5973478955d6412ca6f3860_08682026 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_variants' => 
  array (
    0 => 'Block_5973478955d6412ca6f3860_08682026',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <?php if ($_smarty_tpl->tpl_vars['product']->value['main_variants']) {?>
                    <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/variant-links.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('variants'=>$_smarty_tpl->tpl_vars['product']->value['main_variants']), 0, false);
?>
                    <?php }?>
                    <?php
}
}
/* {/block 'product_variants'} */
}
