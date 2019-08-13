<?php
/* Smarty version 3.1.33, created on 2019-08-13 15:12:49
  from 'module:rvmanufacturerblockviewst' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5319c1bf8d99_35037628',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b169f2d764d70fcba6371fa145c5367e23506a97' => 
    array (
      0 => 'module:rvmanufacturerblockviewst',
      1 => 1565285090,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d5319c1bf8d99_35037628 (Smarty_Internal_Template $_smarty_tpl) {
?>    <div id="rvmanufacturerblock" class="clearfix rv-animate-element bottom-to-top">
        <div class="container">
            <div class="row">
                <h2 class="homepage-heading"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Our Brands','mod'=>'rvmanufacturerblock'),$_smarty_tpl ) );?>
</h2>
                <?php if (isset($_smarty_tpl->tpl_vars['rvmanufacturer']->value) && $_smarty_tpl->tpl_vars['rvmanufacturer']->value) {?>
                <!-- Custom start -->
                <?php $_smarty_tpl->_assignInScope('sliderFor', 6);?> <!-- Define Number of product for SLIDER -->
                <?php $_smarty_tpl->_assignInScope('productCount', count($_smarty_tpl->tpl_vars['rvmanufacturer']->value));?>
                <!-- Custom End -->

                <div class="block_content row">
                    <ul id="manufacturer-carousel" class="owl-carousel product_list">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rvmanufacturer']->value, 'manufacturer', false, NULL, 'manufacturerList', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['manufacturer']->value) {
?>
			<?php if ($_smarty_tpl->tpl_vars['manufacturer']->value['image']) {?> 
                        <li class="item">
                            <div class="manufacturer_image">
                                <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['manufacturer']->value['link'], ENT_QUOTES, 'UTF-8');?>
">
                                    <img width="170" height="65" class="img-responsive" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['manufacturer']->value['image'], ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['manufacturer']->value['name'], ENT_QUOTES, 'UTF-8');?>
" />                                
				</a>
                            </div>
                            <?php if ($_smarty_tpl->tpl_vars['rvmanufacturername']->value) {?>
                            <div class="manufacturer_name">	
                                <h5 itemprop="name">
                                    <a class="manufacturer-name img-responsive" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['manufacturer']->value['link'], ENT_QUOTES, 'UTF-8');?>
" itemprop="url">
                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['manufacturer']->value['name'], ENT_QUOTES, 'UTF-8');?>

                                    </a>
                                </h5>
                            </div>
                            <?php }?>
                        </li>
			<?php }?>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </ul>
                </div>
                <?php } else { ?>
                <div class="alert alert-info"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'There are no manufacturers.','mod'=>'rvmanufacturerblock'),$_smarty_tpl ) );?>
</div>
                <?php }?>
            </div>
        </div>
    </div>
<?php }
}
