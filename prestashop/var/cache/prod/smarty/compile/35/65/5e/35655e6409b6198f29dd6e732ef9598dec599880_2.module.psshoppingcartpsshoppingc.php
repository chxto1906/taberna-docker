<?php
/* Smarty version 3.1.33, created on 2019-08-06 17:31:43
  from 'module:psshoppingcartpsshoppingc' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49ffcf8dd0c4_47049785',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '35655e6409b6198f29dd6e732ef9598dec599880' => 
    array (
      0 => 'module:psshoppingcartpsshoppingc',
      1 => 1561678046,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49ffcf8dd0c4_47049785 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="_desktop_cart">
  <div class="blockcart cart-preview" data-refresh-url="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['refresh_url']->value, ENT_QUOTES, 'UTF-8');?>
">
    <div class="shopping_cart">
      <a rel="nofollow" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart_url']->value, ENT_QUOTES, 'UTF-8');?>
" class="shoppingcart" title="add to cart">
        <span class="hidden-md-down"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shopping Cart','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</span>
        <span class="cart-products-count">
          <span class="cart_quantity_count"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['products_count'], ENT_QUOTES, 'UTF-8');?>
</span>
          <span class="total_price"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['totals']['total']['value'], ENT_QUOTES, 'UTF-8');?>
&nbsp;-</span>
           <span class="quantity"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['products_count'], ENT_QUOTES, 'UTF-8');?>
</span>
          <span class="cart_product_txt_s" <?php if ($_smarty_tpl->tpl_vars['cart']->value['products_count'] == 1) {?>style="display:none"<?php }?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Items','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</span>
          <span class="cart_product_txt" <?php if ($_smarty_tpl->tpl_vars['cart']->value['products_count'] != 1) {?>style="display:none"<?php }?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Item','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>
</span>
        </span>
      </a>
      <div class="cart_block block exclusive">
        <div class="block_content">
          <div class="cart_block_list">
            <?php if ($_smarty_tpl->tpl_vars['cart']->value['products_count'] > 0) {?>
            <?php if ($_smarty_tpl->tpl_vars['cart']->value['products']) {?>
            <ul class="products">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['cart']->value['products'], 'product', false, NULL, 'myLoop', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
?>
              <?php $_smarty_tpl->_assignInScope('productId', $_smarty_tpl->tpl_vars['product']->value['id_product']);?>
              <?php $_smarty_tpl->_assignInScope('productAttributeId', $_smarty_tpl->tpl_vars['product']->value['id_product_attribute']);?>
              <li>
                <a class="cart-images" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
">
                  <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['bySize']['cart_default']['url'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],'quotes' )), ENT_QUOTES, 'UTF-8');?>
">
                </a>
                <div class="cart-info">
                  <div class="product-name">
                    <span class="quantity-formated">
                      <span class="quantity"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cart_quantity'], ENT_QUOTES, 'UTF-8');?>
</span>
                      &nbsp;x&nbsp;
                    </span>
                    <a class="cart_block_product_name" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');?>
</a>
                  </div>
                  <div class="price"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['price'], ENT_QUOTES, 'UTF-8');?>
</div>
                  <?php if (isset($_smarty_tpl->tpl_vars['product']->value['attributes'])) {?>
                  <div class="product-atributes">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['attributes'], 'value', false, 'attribute');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['attribute']->value => $_smarty_tpl->tpl_vars['value']->value) {
?>
                    <div class="atributes">
                      <span class="label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['attribute']->value, ENT_QUOTES, 'UTF-8');?>
:</span>
                      <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value, ENT_QUOTES, 'UTF-8');?>
</span>
                    </div>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                  </div>
                  <?php }?>
                </div>
                <span class="remove_link">
                  <a class="remove-from-cart" rel="nofollow" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['remove_from_cart_url'], ENT_QUOTES, 'UTF-8');?>
" data-link-action="delete-from-cart" data-id-product="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['id_product'],'javascript' )), ENT_QUOTES, 'UTF-8');?>
" data-id-product-attribute="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['id_product_attribute'],'javascript' )), ENT_QUOTES, 'UTF-8');?>
" data-id-customization="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['id_customization'],'javascript' )), ENT_QUOTES, 'UTF-8');?>
">
                    <?php if (!isset($_smarty_tpl->tpl_vars['product']->value['is_gift']) || !$_smarty_tpl->tpl_vars['product']->value['is_gift']) {?>
                    <i class="fa fa-trash-o"></i>
                    <?php }?>
                  </a>
                </span>
              </li>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </ul>
            <div class="cart-prices">
              <div class="price subtotal">
                <span class="label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['subtotals']['products']['label'], ENT_QUOTES, 'UTF-8');?>
</span>
                <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['subtotals']['products']['value'], ENT_QUOTES, 'UTF-8');?>
</span>
              </div>
              <div class="price shipping">
                <span class="label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['subtotals']['shipping']['label'], ENT_QUOTES, 'UTF-8');?>
</span>
                <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['subtotals']['shipping']['value'], ENT_QUOTES, 'UTF-8');?>
</span>
              </div>
              <?php if (isset($_smarty_tpl->tpl_vars['delivery_time']->value)) {?>
                <div class="price delivery-time">
                  <span class="label">Tiempo entrega</span>
                  <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['delivery_time']->value, ENT_QUOTES, 'UTF-8');?>
 mins. (máximo)</span>
                </div>
              <?php }?>
              <div class="price tax">
                <span class="label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['subtotals']['tax']['label'], ENT_QUOTES, 'UTF-8');?>
</span>
                <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['subtotals']['tax']['value'], ENT_QUOTES, 'UTF-8');?>
</span>
              </div>
              <div class="price total">
                <span class="label"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['totals']['total']['label'], ENT_QUOTES, 'UTF-8');?>
</span>
                <span class="value"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart']->value['totals']['total']['value'], ENT_QUOTES, 'UTF-8');?>
</span>
              </div>
            </div>
            <div class="cart-buttons">
              <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cart_url']->value, ENT_QUOTES, 'UTF-8');?>
" class="btn btn-primary checkout"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Checkout','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</a>
            </div>
            <?php }?>
            <?php } else { ?>
            <p class="cart_block_no_products<?php if ($_smarty_tpl->tpl_vars['cart']->value['products']) {?> unvisible<?php }?>">
              <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Your cart is empty','d'=>'Shop.Theme.Checkout'),$_smarty_tpl ) );?>

            </p>
            <?php }?>
          </div>
        </div>
      </div>
    </div>
    <?php echo '<script'; ?>
>
      if (window.jQuery) {  
        $(document).ready(function(){
          accordionCart();
        });
      }
    <?php echo '</script'; ?>
>
  </div>
</div><?php }
}
