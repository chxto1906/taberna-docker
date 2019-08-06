<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:46:41
  from '/html/themes/PRSD81/templates/catalog/product.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4992d1daab73_23976983',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ddcaf02de329c2570d2d6b052e090a032bc3e26b' => 
    array (
      0 => '/html/themes/PRSD81/templates/catalog/product.tpl',
      1 => 1562076709,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:catalog/_partials/product-cover-thumbnails.tpl' => 1,
    'file:catalog/_partials/product-customization.tpl' => 1,
    'file:catalog/_partials/product-variants.tpl' => 1,
    'file:catalog/_partials/miniatures/pack-product.tpl' => 1,
    'file:catalog/_partials/product-discounts.tpl' => 1,
    'file:catalog/_partials/product-prices.tpl' => 1,
    'file:catalog/_partials/product-add-to-cart.tpl' => 1,
    'file:catalog/_partials/product-additional-info.tpl' => 1,
    'file:catalog/_partials/product-details.tpl' => 1,
    'file:catalog/_partials/miniatures/product-slider.tpl' => 1,
    'file:catalog/_partials/product-images-modal.tpl' => 1,
  ),
),false)) {
function content_5d4992d1daab73_23976983 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8536087325d4992d1cd3ea9_04220799', 'head_seo');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15833651175d4992d1cd8767_06434053', 'head');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16507488555d4992d1cee3f7_27850872', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'head_seo'} */
class Block_8536087325d4992d1cd3ea9_04220799 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head_seo' => 
  array (
    0 => 'Block_8536087325d4992d1cd3ea9_04220799',
  ),
);
public $prepend = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <link rel="canonical" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['canonical_url'], ENT_QUOTES, 'UTF-8');?>
">
<?php
}
}
/* {/block 'head_seo'} */
/* {block 'head'} */
class Block_15833651175d4992d1cd8767_06434053 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head' => 
  array (
    0 => 'Block_15833651175d4992d1cd8767_06434053',
  ),
);
public $append = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <meta property="og:type" content="product">
  <meta property="og:url" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['current_url'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="og:title" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page']->value['meta']['title'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="og:site_name" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="og:description" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page']->value['meta']['description'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="og:image" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['cover']['large']['url'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:pretax_price:amount" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['price_tax_exc'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:pretax_price:currency" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['currency']->value['iso_code'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:price:amount" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['price_amount'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:price:currency" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['currency']->value['iso_code'], ENT_QUOTES, 'UTF-8');?>
">
  <?php if (isset($_smarty_tpl->tpl_vars['product']->value['weight']) && ($_smarty_tpl->tpl_vars['product']->value['weight'] != 0)) {?>
  <meta property="product:weight:value" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['weight'], ENT_QUOTES, 'UTF-8');?>
">
  <meta property="product:weight:units" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['weight_unit'], ENT_QUOTES, 'UTF-8');?>
">
  <?php }
}
}
/* {/block 'head'} */
/* {block 'product_cover_thumbnails'} */
class Block_8048391845d4992d1cf2f65_15680972 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-cover-thumbnails.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php
}
}
/* {/block 'product_cover_thumbnails'} */
/* {block 'page_content'} */
class Block_1312429195d4992d1cf2203_38721949 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8048391845d4992d1cf2f65_15680972', 'product_cover_thumbnails', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_21060706395d4992d1cf1012_09471729 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section class="page-content" id="content">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1312429195d4992d1cf2203_38721949', 'page_content', $this->tplIndex);
?>

      </section>
      <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_title'} */
class Block_17729046385d4992d1d05a31_86410839 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['name'], ENT_QUOTES, 'UTF-8');
}
}
/* {/block 'page_title'} */
/* {block 'page_header'} */
class Block_13512723165d4992d1d051e1_81472666 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <h1 class="product_title h1" itemprop="name"><?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17729046385d4992d1d05a31_86410839', 'page_title', $this->tplIndex);
?>
</h1>
      <?php
}
}
/* {/block 'page_header'} */
/* {block 'page_header_container'} */
class Block_4242197775d4992d1d04899_72880281 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13512723165d4992d1d051e1_81472666', 'page_header', $this->tplIndex);
?>

      <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'product_description_short'} */
class Block_10946266685d4992d1d07662_08751854 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <div id="product-description-short-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id'], ENT_QUOTES, 'UTF-8');?>
" itemprop="description"><?php echo $_smarty_tpl->tpl_vars['product']->value['description_short'];?>
</div>
        <?php
}
}
/* {/block 'product_description_short'} */
/* {block 'product_comment'} */
class Block_15599756945d4992d1d0a0e7_68337063 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'displayProductExtra', null, null);
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductExtra'),$_smarty_tpl ) );
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
          <?php if ($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayProductExtra')) {?>
            <?php echo $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayProductExtra');?>

          <?php }?>
        <?php
}
}
/* {/block 'product_comment'} */
/* {block 'product_customization'} */
class Block_4189409325d4992d1d26305_87712750 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender("file:catalog/_partials/product-customization.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('customizations'=>$_smarty_tpl->tpl_vars['product']->value['customizations']), 0, false);
?>
        <?php
}
}
/* {/block 'product_customization'} */
/* {block 'product_variants'} */
class Block_11816584075d4992d1d3b836_33642410 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-variants.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php
}
}
/* {/block 'product_variants'} */
/* {block 'product_miniature'} */
class Block_5943611625d4992d1d56017_86238193 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/miniatures/pack-product.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('product'=>$_smarty_tpl->tpl_vars['product_pack']->value), 0, true);
?>
              <?php
}
}
/* {/block 'product_miniature'} */
/* {block 'product_pack'} */
class Block_8730322865d4992d1d3daf6_04164771 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php if ($_smarty_tpl->tpl_vars['packItems']->value) {?>
            <section class="product-pack">
              <h3 class="h4"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'This pack contains','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</h3>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['packItems']->value, 'product_pack');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['product_pack']->value) {
?>
              <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5943611625d4992d1d56017_86238193', 'product_miniature', $this->tplIndex);
?>

              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </section>
            <?php }?>
            <?php
}
}
/* {/block 'product_pack'} */
/* {block 'product_discounts'} */
class Block_14627962545d4992d1d5e0a4_98512589 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-discounts.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php
}
}
/* {/block 'product_discounts'} */
/* {block 'product_prices'} */
class Block_7279752845d4992d1d5f170_21090740 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-prices.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php
}
}
/* {/block 'product_prices'} */
/* {block 'product_add_to_cart'} */
class Block_15919902885d4992d1d60359_44998962 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-add-to-cart.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php
}
}
/* {/block 'product_add_to_cart'} */
/* {block 'product_additional_info'} */
class Block_20999036935d4992d1d61970_84447698 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-additional-info.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php
}
}
/* {/block 'product_additional_info'} */
/* {block 'product_refresh'} */
class Block_19704203355d4992d1d62b52_90645749 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <input class="product-refresh ps-hidden-by-js" name="refresh" type="submit" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Refresh','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
">
            <?php
}
}
/* {/block 'product_refresh'} */
/* {block 'product_buy'} */
class Block_19931257275d4992d1d37665_92559738 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['pages']['cart'], ENT_QUOTES, 'UTF-8');?>
" method="post" id="add-to-cart-or-refresh">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['static_token']->value, ENT_QUOTES, 'UTF-8');?>
">
            <input type="hidden" name="id_product" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id'], ENT_QUOTES, 'UTF-8');?>
" id="product_page_product_id">
            <input type="hidden" name="id_customization" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_customization'], ENT_QUOTES, 'UTF-8');?>
" id="product_customization_id">

            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11816584075d4992d1d3b836_33642410', 'product_variants', $this->tplIndex);
?>


            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8730322865d4992d1d3daf6_04164771', 'product_pack', $this->tplIndex);
?>


            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14627962545d4992d1d5e0a4_98512589', 'product_discounts', $this->tplIndex);
?>


            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7279752845d4992d1d5f170_21090740', 'product_prices', $this->tplIndex);
?>


            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15919902885d4992d1d60359_44998962', 'product_add_to_cart', $this->tplIndex);
?>


            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20999036935d4992d1d61970_84447698', 'product_additional_info', $this->tplIndex);
?>


            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19704203355d4992d1d62b52_90645749', 'product_refresh', $this->tplIndex);
?>

          </form>
          <?php
}
}
/* {/block 'product_buy'} */
/* {block 'hook_display_reassurance'} */
class Block_7822656615d4992d1d66653_17690998 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayReassurance'),$_smarty_tpl ) );?>

        <?php
}
}
/* {/block 'hook_display_reassurance'} */
/* {block 'product_comment_tab'} */
class Block_4460596565d4992d1d76d53_56136076 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'displayProductTab', null, null);
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductTab'),$_smarty_tpl ) );
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
              <?php if ($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayProductTab')) {?>
                <?php echo $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayProductTab');?>

              <?php }?>
            <?php
}
}
/* {/block 'product_comment_tab'} */
/* {block 'product_description'} */
class Block_5268044155d4992d1d7b668_77521486 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

             <div class="product-description"><?php echo $_smarty_tpl->tpl_vars['product']->value['description'];?>
</div>
             <?php
}
}
/* {/block 'product_description'} */
/* {block 'product_details'} */
class Block_21349601705d4992d1d7dce8_26875872 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

           <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-details.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
           <?php
}
}
/* {/block 'product_details'} */
/* {block 'product_attachments'} */
class Block_16231726225d4992d1d7f607_80195909 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

           <?php if ($_smarty_tpl->tpl_vars['product']->value['attachments']) {?>
           <div class="tab-pane fade in" id="attachments" role="tabpanel">
             <section class="product-attachments">
               <h3 class="h5 text-uppercase"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
</h3>
               <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['attachments'], 'attachment');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['attachment']->value) {
?>
               <div class="attachment">
                 <h4><a href="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['url'][0], array( array('entity'=>'attachment','params'=>array('id_attachment'=>$_smarty_tpl->tpl_vars['attachment']->value['id_attachment'])),$_smarty_tpl ) );?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['attachment']->value['name'], ENT_QUOTES, 'UTF-8');?>
</a></h4>
                 <p><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['attachment']->value['description'], ENT_QUOTES, 'UTF-8');?>
</p>
                 <a href="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['url'][0], array( array('entity'=>'attachment','params'=>array('id_attachment'=>$_smarty_tpl->tpl_vars['attachment']->value['id_attachment'])),$_smarty_tpl ) );?>
">
                   <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Download','d'=>'Shop.Theme.Actions'),$_smarty_tpl ) );?>
 (<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['attachment']->value['file_size_formatted'], ENT_QUOTES, 'UTF-8');?>
)
                 </a>
               </div>
               <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
             </section>
           </div>
           <?php }?>
           <?php
}
}
/* {/block 'product_attachments'} */
/* {block 'product_comment_tab_content'} */
class Block_7107810975d4992d1d98544_79070590 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                  <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'displayProductTabContent', null, null);
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductTabContent'),$_smarty_tpl ) );
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
                  <?php if ($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayProductTabContent')) {?>
                    <?php echo $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayProductTabContent');?>

                  <?php }?>
                <?php
}
}
/* {/block 'product_comment_tab_content'} */
/* {block 'product_tabs'} */
class Block_906058025d4992d1d67885_05079943 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <div class="tabs">
          <ul class="nav nav-tabs" role="tablist">
            <?php if ($_smarty_tpl->tpl_vars['product']->value['description']) {?>
            <li class="nav-item">
              <a class="nav-link<?php if ($_smarty_tpl->tpl_vars['product']->value['description']) {?> active<?php }?>" data-toggle="tab" href="#description" role="tab" aria-controls="description" <?php if ($_smarty_tpl->tpl_vars['product']->value['description']) {?> aria-selected="true"<?php }?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Description','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</a>
            </li>
            <?php }?>
            <li class="nav-item">
              <a class="nav-link<?php if (!$_smarty_tpl->tpl_vars['product']->value['description']) {?> active<?php }?>" data-toggle="tab" href="#product-details" role="tab" aria-controls="product-details" <?php if (!$_smarty_tpl->tpl_vars['product']->value['description']) {?> aria-selected="true"<?php }?>><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Product Details','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</a>
            </li>
            <?php if ($_smarty_tpl->tpl_vars['product']->value['attachments']) {?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#attachments" role="tab" aria-controls="attachments"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Attachments','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</a>
            </li>
            <?php }?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['extraContent'], 'extra', false, 'extraKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['extraKey']->value => $_smarty_tpl->tpl_vars['extra']->value) {
?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#extra-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['extraKey']->value, ENT_QUOTES, 'UTF-8');?>
" role="tab" aria-controls="extra-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['extraKey']->value, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['extra']->value['title'], ENT_QUOTES, 'UTF-8');?>
</a>
            </li>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

            <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4460596565d4992d1d76d53_56136076', 'product_comment_tab', $this->tplIndex);
?>

          </ul>

          <div class="tab-content" id="tab-content">
            <div class="tab-pane fade in<?php if ($_smarty_tpl->tpl_vars['product']->value['description']) {?> active<?php }?>" id="description" role="tabpanel">
             <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5268044155d4992d1d7b668_77521486', 'product_description', $this->tplIndex);
?>

           </div>

           <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21349601705d4992d1d7dce8_26875872', 'product_details', $this->tplIndex);
?>


           <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16231726225d4992d1d7f607_80195909', 'product_attachments', $this->tplIndex);
?>


           <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product']->value['extraContent'], 'extra', false, 'extraKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['extraKey']->value => $_smarty_tpl->tpl_vars['extra']->value) {
?>
           <div class="tab-pane fade in <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['extra']->value['attr']['class'], ENT_QUOTES, 'UTF-8');?>
" id="extra-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['extraKey']->value, ENT_QUOTES, 'UTF-8');?>
" role="tabpanel" <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['extra']->value['attr'], 'val', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
?> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8');?>
="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['val']->value, ENT_QUOTES, 'UTF-8');?>
"<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>>
             <?php echo $_smarty_tpl->tpl_vars['extra']->value['content'];?>

           </div>
           <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

           <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7107810975d4992d1d98544_79070590', 'product_comment_tab_content', $this->tplIndex);
?>

                
         </div>  
       </div>
       <?php
}
}
/* {/block 'product_tabs'} */
/* {block 'product_accessories'} */
class Block_11596602485d4992d1d9f2e4_64362156 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php if ($_smarty_tpl->tpl_vars['accessories']->value) {?>
          <!-- Custom start -->
          <section class="product-accessories products_block clearfix">
            <div class="products_block_inner">
            <div class="rv-titletab">
              <h2 class="tab_title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You might also like','d'=>'Shop.Theme.Catalog'),$_smarty_tpl ) );?>
</h2>
              </div>
              <div class="block_content">
                <div id="accessories-carousel" class="owl-carousel products">
                  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['accessories']->value, 'product_accessory', false, NULL, 'accessoriesProducts', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['product_accessory']->value) {
?>
                  <div class="item product-miniature js-product-miniature" data-id-product="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product'], ENT_QUOTES, 'UTF-8');?>
" data-id-product-attribute="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['id_product_attribute'], ENT_QUOTES, 'UTF-8');?>
" itemscope itemtype="http://schema.org/Product">
                    <?php $_smarty_tpl->_subTemplateRender("file:catalog/_partials/miniatures/product-slider.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('product'=>$_smarty_tpl->tpl_vars['product_accessory']->value), 0, true);
?>
                  </div>
                  <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </div>
              </div>
            </div>
          </section>
          <!-- Custom End -->
          <?php }?>
        <?php
}
}
/* {/block 'product_accessories'} */
/* {block 'product_footer'} */
class Block_2372670275d4992d1da6030_82189970 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayFooterProduct','product'=>$_smarty_tpl->tpl_vars['product']->value,'category'=>$_smarty_tpl->tpl_vars['category']->value),$_smarty_tpl ) );?>

  <?php
}
}
/* {/block 'product_footer'} */
/* {block 'product_images_modal'} */
class Block_12733376495d4992d1da78a4_00470840 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-images-modal.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  <?php
}
}
/* {/block 'product_images_modal'} */
/* {block 'page_footer'} */
class Block_17631127885d4992d1da8ff4_88147400 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <!-- Footer content -->
      <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_6465339965d4992d1da8898_12243131 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <footer class="page-footer">
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17631127885d4992d1da8ff4_88147400', 'page_footer', $this->tplIndex);
?>

    </footer>
  <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_16507488555d4992d1cee3f7_27850872 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_16507488555d4992d1cee3f7_27850872',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_21060706395d4992d1cf1012_09471729',
  ),
  'page_content' => 
  array (
    0 => 'Block_1312429195d4992d1cf2203_38721949',
  ),
  'product_cover_thumbnails' => 
  array (
    0 => 'Block_8048391845d4992d1cf2f65_15680972',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_4242197775d4992d1d04899_72880281',
  ),
  'page_header' => 
  array (
    0 => 'Block_13512723165d4992d1d051e1_81472666',
  ),
  'page_title' => 
  array (
    0 => 'Block_17729046385d4992d1d05a31_86410839',
  ),
  'product_description_short' => 
  array (
    0 => 'Block_10946266685d4992d1d07662_08751854',
  ),
  'product_comment' => 
  array (
    0 => 'Block_15599756945d4992d1d0a0e7_68337063',
  ),
  'product_customization' => 
  array (
    0 => 'Block_4189409325d4992d1d26305_87712750',
  ),
  'product_buy' => 
  array (
    0 => 'Block_19931257275d4992d1d37665_92559738',
  ),
  'product_variants' => 
  array (
    0 => 'Block_11816584075d4992d1d3b836_33642410',
  ),
  'product_pack' => 
  array (
    0 => 'Block_8730322865d4992d1d3daf6_04164771',
  ),
  'product_miniature' => 
  array (
    0 => 'Block_5943611625d4992d1d56017_86238193',
  ),
  'product_discounts' => 
  array (
    0 => 'Block_14627962545d4992d1d5e0a4_98512589',
  ),
  'product_prices' => 
  array (
    0 => 'Block_7279752845d4992d1d5f170_21090740',
  ),
  'product_add_to_cart' => 
  array (
    0 => 'Block_15919902885d4992d1d60359_44998962',
  ),
  'product_additional_info' => 
  array (
    0 => 'Block_20999036935d4992d1d61970_84447698',
  ),
  'product_refresh' => 
  array (
    0 => 'Block_19704203355d4992d1d62b52_90645749',
  ),
  'hook_display_reassurance' => 
  array (
    0 => 'Block_7822656615d4992d1d66653_17690998',
  ),
  'product_tabs' => 
  array (
    0 => 'Block_906058025d4992d1d67885_05079943',
  ),
  'product_comment_tab' => 
  array (
    0 => 'Block_4460596565d4992d1d76d53_56136076',
  ),
  'product_description' => 
  array (
    0 => 'Block_5268044155d4992d1d7b668_77521486',
  ),
  'product_details' => 
  array (
    0 => 'Block_21349601705d4992d1d7dce8_26875872',
  ),
  'product_attachments' => 
  array (
    0 => 'Block_16231726225d4992d1d7f607_80195909',
  ),
  'product_comment_tab_content' => 
  array (
    0 => 'Block_7107810975d4992d1d98544_79070590',
  ),
  'product_accessories' => 
  array (
    0 => 'Block_11596602485d4992d1d9f2e4_64362156',
  ),
  'product_footer' => 
  array (
    0 => 'Block_2372670275d4992d1da6030_82189970',
  ),
  'product_images_modal' => 
  array (
    0 => 'Block_12733376495d4992d1da78a4_00470840',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_6465339965d4992d1da8898_12243131',
  ),
  'page_footer' => 
  array (
    0 => 'Block_17631127885d4992d1da8ff4_88147400',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


<section id="main" itemscope itemtype="https://schema.org/Product">
  <meta itemprop="url" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product']->value['url'], ENT_QUOTES, 'UTF-8');?>
">

  <div class="row">
    <div class="col-md-5">
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21060706395d4992d1cf1012_09471729', 'page_content_container', $this->tplIndex);
?>

    </div>
    <div class="col-md-7">
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4242197775d4992d1d04899_72880281', 'page_header_container', $this->tplIndex);
?>


       <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10946266685d4992d1d07662_08751854', 'product_description_short', $this->tplIndex);
?>


      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15599756945d4992d1d0a0e7_68337063', 'product_comment', $this->tplIndex);
?>


      

      <div class="product-information">
       

        <?php if ($_smarty_tpl->tpl_vars['product']->value['is_customizable'] && count($_smarty_tpl->tpl_vars['product']->value['customizations']['fields'])) {?>
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4189409325d4992d1d26305_87712750', 'product_customization', $this->tplIndex);
?>

        <?php }?>

        <div class="product-actions">
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19931257275d4992d1d37665_92559738', 'product_buy', $this->tplIndex);
?>


        </div>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7822656615d4992d1d66653_17690998', 'hook_display_reassurance', $this->tplIndex);
?>

      </div>
      <div class="product-block-information">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_906058025d4992d1d67885_05079943', 'product_tabs', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11596602485d4992d1d9f2e4_64362156', 'product_accessories', $this->tplIndex);
?>

        </div>



    </div>
  </div>



  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2372670275d4992d1da6030_82189970', 'product_footer', $this->tplIndex);
?>


  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12733376495d4992d1da78a4_00470840', 'product_images_modal', $this->tplIndex);
?>


  <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6465339965d4992d1da8898_12243131', 'page_footer_container', $this->tplIndex);
?>

</section>

<?php
}
}
/* {/block 'content'} */
}
