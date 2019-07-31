<?php
/* Smarty version 3.1.33, created on 2019-07-31 17:24:36
  from '/html/themes/PRSD81/templates/catalog/listing/category.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d421524eb3e93_27496743',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'beb7de762b6cbab6a4864c817af5e4d7fac20429' => 
    array (
      0 => '/html/themes/PRSD81/templates/catalog/listing/category.tpl',
      1 => 1561678046,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d421524eb3e93_27496743 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11468274655d421524e9aea0_74153164', 'product_list_header');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'catalog/listing/product-list.tpl');
}
/* {block 'product_list_header'} */
class Block_11468274655d421524e9aea0_74153164 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_list_header' => 
  array (
    0 => 'Block_11468274655d421524e9aea0_74153164',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="block-category card card-block">
  <?php if ($_smarty_tpl->tpl_vars['category']->value['image']) {?>
  <div class="category-cover">
    <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value['image']['large']['url'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value['image']['legend'], ENT_QUOTES, 'UTF-8');?>
">
  </div>
  <?php }?>
    <h1 class="h1"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value['name'], ENT_QUOTES, 'UTF-8');?>
</h1>
  <?php if ($_smarty_tpl->tpl_vars['category']->value['description']) {?>
  <div id="category-description" class="text-muted"><?php echo $_smarty_tpl->tpl_vars['category']->value['description'];?>
</div>
  <?php }?>
</div>

  <?php if (isset($_smarty_tpl->tpl_vars['subcategories']->value) && $_smarty_tpl->tpl_vars['subcategories']->value) {?>
    <?php if ((isset($_smarty_tpl->tpl_vars['display_subcategories']->value) && $_smarty_tpl->tpl_vars['display_subcategories']->value == 1) || !isset($_smarty_tpl->tpl_vars['display_subcategories']->value)) {?>
    <div id="subcategories">
      <p class="subcategory-heading"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Subcategories','d'=>'Shop.Theme.Global'),$_smarty_tpl ) );?>
</p>
      <div class="block_content row">
        <div id="subcategory-carousel" class="owl-carousel clearfix">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['subcategories']->value, 'subcategory');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['subcategory']->value) {
?>
          <div class="item">
            <div class="subcategory-container">
              <div class="subcategory-image">
                <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getCategoryLink($_smarty_tpl->tpl_vars['subcategory']->value['id_category'],$_smarty_tpl->tpl_vars['subcategory']->value['link_rewrite']),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['subcategory']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" class="img">
                  <?php if (isset($_smarty_tpl->tpl_vars['subcategory']->value['image']['small']['url']) && $_smarty_tpl->tpl_vars['subcategory']->value['id_image']) {?>
                  <img class="replace-2x" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['subcategory']->value['image']['large']['url'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['subcategory']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"  />
                  <?php } else { ?>
                  <img class="replace-2x" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_cat_url'], ENT_QUOTES, 'UTF-8');
echo htmlspecialchars($_smarty_tpl->tpl_vars['language']->value['iso_code'], ENT_QUOTES, 'UTF-8');?>
-default-category_default.jpg" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['subcategory']->value['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"  />
                  <?php }?>
                </a>
              </div>
              <div class="subcategory-content">
                  <a class="subcategory-name" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getCategoryLink($_smarty_tpl->tpl_vars['subcategory']->value['id_category'],$_smarty_tpl->tpl_vars['subcategory']->value['link_rewrite']),'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                    <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['subcategory']->value['name'],25,'...' ));?>

                  </a>
              </div>
            </div>
          </div>
          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
      </div>
    </div>
    <?php }?>
  <?php }
}
}
/* {/block 'product_list_header'} */
}
