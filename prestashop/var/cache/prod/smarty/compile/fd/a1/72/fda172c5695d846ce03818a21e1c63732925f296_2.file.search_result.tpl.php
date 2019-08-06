<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:43:38
  from '/html/modules/rvcategorysearch/views/templates/hook/search_result.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49921a7e9486_88173480',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fda172c5695d846ce03818a21e1c63732925f296' => 
    array (
      0 => '/html/modules/rvcategorysearch/views/templates/hook/search_result.tpl',
      1 => 1561678042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49921a7e9486_88173480 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="ajax-search-content">
    <div class="items-list">
        <?php if ($_smarty_tpl->tpl_vars['searchResults']->value) {?>
            <?php $_smarty_tpl->_assignInScope('num', 0);?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['searchResults']->value, 'product', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['product']->value) {
?>
                <?php $_smarty_tpl->_assignInScope('num', $_smarty_tpl->tpl_vars['num']->value+1);?>
                <?php if ($_smarty_tpl->tpl_vars['num']->value > $_smarty_tpl->tpl_vars['searchLimit']->value) {?>
                    <?php break 1;?>
                <?php }?>
                <div class="item">
                    <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['link'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                        <?php if (isset($_smarty_tpl->tpl_vars['searchImage']->value) && $_smarty_tpl->tpl_vars['searchImage']->value) {?>
                            <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getImageLink(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['link_rewrite'],'quotes','UTF-8' )),$_smarty_tpl->tpl_vars['product']->value['id_image'],'small_default'), ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="searchImg" />
                        <?php }?>
                        <?php if (isset($_smarty_tpl->tpl_vars['searchCategoryName']->value) && $_smarty_tpl->tpl_vars['searchCategoryName']->value) {?>
                            <span class="category-name">
                                <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['category_name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                            </span>
                        <?php }?>
                        <h5 class="product-name">
                            <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                        </h5>
                        <?php if (isset($_smarty_tpl->tpl_vars['searchPrice']->value) && $_smarty_tpl->tpl_vars['searchPrice']->value) {?>
                            <span class="content_price product-price-and-shipping">
                                <?php if (isset($_smarty_tpl->tpl_vars['product']->value['show_price']) && $_smarty_tpl->tpl_vars['product']->value['show_price'] && !isset($_smarty_tpl->tpl_vars['restricted_country_mode']->value)) {?>
                                    <span itemprop="price" class="price <?php if (isset($_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction']) && $_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction'] > 0) {?> special-price<?php }?>">
                                        <?php echo htmlspecialchars(Product::convertAndFormatPrice(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['price'],'quotes','UTF-8' ))), ENT_QUOTES, 'UTF-8');?>

                                    </span>
                                    <?php if (isset($_smarty_tpl->tpl_vars['product']->value['specific_prices']) && $_smarty_tpl->tpl_vars['product']->value['specific_prices'] && isset($_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction']) && $_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction'] > 0) {?>
                                        <span class="sale-percentage" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['link'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                                            <?php if ($_smarty_tpl->tpl_vars['product']->value['specific_prices'] && $_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction_type'] == 'percentage') {?>
                                               -<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['specific_prices']['reduction'],'quotes','UTF-8' ))*100, ENT_QUOTES, 'UTF-8');?>
%
                                            <?php }?>
                                        </span>
                                        <span class="old-price regular-price">
                                            <?php echo htmlspecialchars(Product::convertAndFormatPrice(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product']->value['price_without_reduction'],'quotes','UTF-8' ))), ENT_QUOTES, 'UTF-8');?>

                                        </span>
                                    <?php }?>
                                <?php }?>
                            </span>
                        <?php }?>
                    </a>
                </div>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php } else { ?>
            <span class="noresult"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No Products found!','mod'=>'rvcategorysearch'),$_smarty_tpl ) );?>
</span>
        <?php }?>
    </div>
</div>
<?php }
}
