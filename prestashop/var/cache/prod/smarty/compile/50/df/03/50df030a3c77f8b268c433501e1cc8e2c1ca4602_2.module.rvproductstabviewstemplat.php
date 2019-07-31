<?php
/* Smarty version 3.1.33, created on 2019-07-31 17:26:32
  from 'module:rvproductstabviewstemplat' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d421598558527_08223951',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '50df030a3c77f8b268c433501e1cc8e2c1ca4602' => 
    array (
      0 => 'module:rvproductstabviewstemplat',
      1 => 1563291815,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
    'file:catalog/_partials/miniatures/product-slider.tpl' => 1,
  ),
),false)) {
function content_5d421598558527_08223951 (Smarty_Internal_Template $_smarty_tpl) {
?>	<div id="rvproductstab" class="products_block clearfix container">
		<div class="products_block_inner">
			<div class="rv-titletab">
				<h2 class="tab_title hidden-md-down"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Top Products','mod'=>'rvproductstab'),$_smarty_tpl ) );?>
</h2>
				<ul id="rvproduct-tabs" class="nav nav-tabs">
					<?php $_smarty_tpl->_assignInScope('count', 0);?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rvproducttab']->value, 'productTab', false, NULL, 'rvProductTab', array (
  'first' => true,
  'last' => true,
  'index' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['productTab']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['index'];
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['total'];
?>
					<li class="nav-item <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first'] : null)) {?>first_item<?php } elseif ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['last'] : null)) {?>last_item<?php } else {
}?>">
						<a class="nav-link <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first'] : null)) {?>active<?php }?>" href="#tab_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productTab']->value['id'], ENT_QUOTES, 'UTF-8');?>
" data-toggle="tab"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productTab']->value['name'], ENT_QUOTES, 'UTF-8');?>
</a>
					</li>
					<?php $_smarty_tpl->_assignInScope('count', $_smarty_tpl->tpl_vars['count']->value+1);?>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</ul>
			</div>
			<div class="tab-content">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rvproducttab']->value, 'productTab', false, NULL, 'rvProductTab', array (
  'first' => true,
  'last' => true,
  'index' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['productTab']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['index'];
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['total'];
?>
				<div id="tab_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productTab']->value['id'], ENT_QUOTES, 'UTF-8');?>
" class="tab_content tab-pane <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first'] : null)) {?>active<?php }?>">
					<?php if (isset($_smarty_tpl->tpl_vars['productTab']->value['productInfo']) && $_smarty_tpl->tpl_vars['productTab']->value['productInfo']) {?>
					<div class="block_content row">
						<!-- Custom start -->
						<?php if ($_smarty_tpl->tpl_vars['slider']->value == 1) {?>
						<div id="rv_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['productTab']->value['id'], ENT_QUOTES, 'UTF-8');?>
" class="owl-carousel product_list">
							<?php } else { ?>
							<div class="product_list grid">
								<?php }?>		
								<!-- Custom End -->
								<?php $_smarty_tpl->_assignInScope('counter', 1);?>
								<?php ob_start();
echo htmlspecialchars(count($_smarty_tpl->tpl_vars['productTab']->value['productInfo']), ENT_QUOTES, 'UTF-8');
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('lastproduct', $_prefixVariable1);?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['productTab']->value['productInfo'], 'product', false, NULL, 'rvProductTab', array (
  'first' => true,
  'last' => true,
  'index' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['index'];
$_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_rvProductTab']->value['total'];
?>
								<?php if ($_smarty_tpl->tpl_vars['slider']->value == 1 && $_smarty_tpl->tpl_vars['counter']->value%2 == 1) {?> 
								<div class="multiple-item">
									<?php }?>
									<div class="<?php if ($_smarty_tpl->tpl_vars['slider']->value == 1) {?>item <?php } else { ?> ajax_block_product col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 <?php }?> product-miniature js-product-miniature" data-id-product="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product'], ENT_QUOTES, 'UTF-8');?>
" data-id-product-attribute="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product_attribute'], ENT_QUOTES, 'UTF-8');?>
" itemscope itemtype="http://schema.org/Product">
										<?php $_smarty_tpl->_subTemplateRender("file:catalog/_partials/miniatures/product-slider.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('product'=>$_smarty_tpl->tpl_vars['product']->value), 0, true);
?>
									</div>
									<?php if ($_smarty_tpl->tpl_vars['slider']->value == 1 && ($_smarty_tpl->tpl_vars['counter']->value%2 == 0 || $_smarty_tpl->tpl_vars['counter']->value == $_smarty_tpl->tpl_vars['lastproduct']->value)) {?>
								</div>
								<?php }?>
								<?php $_smarty_tpl->_assignInScope('counter', $_smarty_tpl->tpl_vars['counter']->value+1);?>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
						</div>
						<?php } else { ?>
						<div class="alert alert-info"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No Products in current tab at this time.','mod'=>'rvproductstab'),$_smarty_tpl ) );?>
</div>
						<?php }?>
					</div>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</div>
			</div>
		</div><?php }
}
