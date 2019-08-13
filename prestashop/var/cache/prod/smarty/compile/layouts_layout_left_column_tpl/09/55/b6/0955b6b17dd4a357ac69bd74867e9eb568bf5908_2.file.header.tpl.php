<?php
/* Smarty version 3.1.33, created on 2019-08-13 15:12:50
  from '/html/themes/PRSD81/templates/_partials/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5319c2ba8793_69810447',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0955b6b17dd4a357ac69bd74867e9eb568bf5908' => 
    array (
      0 => '/html/themes/PRSD81/templates/_partials/header.tpl',
      1 => 1563464623,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d5319c2ba8793_69810447 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>



<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13144444325d5319c2b92051_35822175', 'header_banner');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6048257015d5319c2b94622_51515925', 'header_nav');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10459020225d5319c2b95980_82008269', 'header_top');
?>


<?php }
/* {block 'header_banner'} */
class Block_13144444325d5319c2b92051_35822175 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header_banner' => 
  array (
    0 => 'Block_13144444325d5319c2b92051_35822175',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <div class="header-banner">
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayBanner'),$_smarty_tpl ) );?>

  </div>
<?php
}
}
/* {/block 'header_banner'} */
/* {block 'header_nav'} */
class Block_6048257015d5319c2b94622_51515925 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header_nav' => 
  array (
    0 => 'Block_6048257015d5319c2b94622_51515925',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <?php
}
}
/* {/block 'header_nav'} */
/* {block 'header_top'} */
class Block_10459020225d5319c2b95980_82008269 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header_top' => 
  array (
    0 => 'Block_10459020225d5319c2b95980_82008269',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div data-html2canvas-ignore class="full-header">
  <div data-html2canvas-ignore class="header-top">
    <div class="container">
      
        <div id="header_logo" class="">
          <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['base_url'], ENT_QUOTES, 'UTF-8');?>
">
            <img class="logo img-responsive" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['logo'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
">
          </a>
        </div>
          <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayTop'),$_smarty_tpl ) );?>

        <div class="clearfix"></div>
      
    </div>
  </div>
  <div class="nav-full-width">
    <div data-html2canvas-ignore class="container">
      <div class="position-static">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayMegamenu'),$_smarty_tpl ) );?>

        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayNavFullWidth'),$_smarty_tpl ) );?>

        </div>
        <div class="hidden-lg-up text-xs-center mobile">
          <div class="menu-icon">
            <div class="cat-title"><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Menu','d'=>'Shop.Theme'),$_smarty_tpl ) );?>
</span></div>
          </div>
          <div id="_mobile_cart" class=""></div>
          <div id="_mobile_rvsearch" style="float: none !important;" class="col-xs-12 col-sm-12"></div>
          
          <div class="clearfix"></div>
        </div>
        <div id="menuCanvas" class="rvclose"></div>
        <div id="mobile_top_menu_wrapper" class="hidden-lg-up">
         
          <div class="header-collapse">
          <div class="menu-close rvclose"><i class="material-icons">clear</i></div>
          <div id="_mobile_user_info"></div>
          <div class="rvmenu">
            <div class="cat-title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Menu','d'=>'Shop.Theme'),$_smarty_tpl ) );?>
 :</div>
            <div class="js-top-menu mobile" id="_mobile_top_menu"></div> 
          </div>
          <div class="responsive-content mobile">
            <div id="_mobile_wishlist"></div>
            <div id="_mobile_compare"></div>
            <div id="_mobile_headercontact"></div>
            <!--  <div class="js-top-menu mobile" id="_mobile_megamenu"></div> -->
          </div>
          </div>
        </div>
    </div>
    <div data-html2canvas-ignore class="container-fluid estas-comprando">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 6px;">
          <span class="hidden-md-down">
            COMPRANDO EN LA TIENDA: &nbsp;
          </span>
          <!--<img width="14px" class="img-responsive" src="/img/pin.png"> -->
          <span style="font-weight: bold;">
            <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
&nbsp;&nbsp;&nbsp;
            <span data-html2canvas-ignore style="position: relative;">
              <?php if ($_smarty_tpl->tpl_vars['isOpen']->value == true) {?>
                <div class='pin bounce' title="Tienda abierta"></div>
              <?php } else { ?>
                <div class='pinOff bounce' title="Tienda cerrada"></div>
              <?php }?>
              <div class='pulse'></div>
            </span>
          </span>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <span data-html2canvas-ignore class="dropdown dropleft">
            <button class="btn btn-cambiar-tienda btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #f2f2f2 !important;">
              Cambiar de tienda
            </button>
            <div class="dropdown-menu" style="font-size: 0.8em !important;" id="change-tiendas" aria-labelledby="dropdownMenuButton">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['shops']->value, 'shop');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['shop']->value) {
?>
                <?php if ($_smarty_tpl->tpl_vars['shop']->value['id_shop'] != $_smarty_tpl->tpl_vars['shop_current']->value) {?>
                  <a class="dropdown-item d-item item-change-tienda" title="http://<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['domain'], ENT_QUOTES, 'UTF-8');?>
/<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['virtual_uri'], ENT_QUOTES, 'UTF-8');?>
" href="#"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
</a>
                <?php }?>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </div>
          </span>
          
          
          
      </div>
      
    </div>
  </div>

</div>
<?php
}
}
/* {/block 'header_top'} */
}
