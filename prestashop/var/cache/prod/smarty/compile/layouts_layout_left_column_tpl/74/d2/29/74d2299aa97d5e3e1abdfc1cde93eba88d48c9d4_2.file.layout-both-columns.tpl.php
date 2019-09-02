<?php
/* Smarty version 3.1.33, created on 2019-09-02 11:37:13
  from '/html/themes/PRSD81/templates/layouts/layout-both-columns.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d6d453956be72_61707106',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '74d2299aa97d5e3e1abdfc1cde93eba88d48c9d4' => 
    array (
      0 => '/html/themes/PRSD81/templates/layouts/layout-both-columns.tpl',
      1 => 1566570714,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:_partials/head.tpl' => 1,
    'file:catalog/_partials/product-activation.tpl' => 1,
    'file:_partials/header.tpl' => 1,
    'file:_partials/notifications.tpl' => 1,
    'file:_partials/breadcrumb.tpl' => 1,
    'file:_partials/footer.tpl' => 1,
    'file:_partials/javascript.tpl' => 1,
  ),
),false)) {
function content_5d6d453956be72_61707106 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['language']->value['iso_code'], ENT_QUOTES, 'UTF-8');?>
">

  <head>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7615255735d6d45394ece21_39498449', 'head');
?>

  </head>

  <body id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page']->value['page_name'], ENT_QUOTES, 'UTF-8');?>
" class="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'classnames' ][ 0 ], array( $_smarty_tpl->tpl_vars['page']->value['body_classes'] )), ENT_QUOTES, 'UTF-8');?>
" <?php if (Configuration::get('RVCUSTOMSETTING_ADD_CONTAINER')) {?> style='<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayBackgroundBody"),$_smarty_tpl ) );?>
' <?php }?>>

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18690931865d6d45394f50e4_07114872', 'hook_after_body_opening_tag');
?>


    <?php if (Configuration::get('RVFRONTSIDE_THEME_SETTING_SHOW')) {?>
      <div class="rvtheme-control">
        <div class="rvcontrol-icon">
            <i class='material-icons'>&#xe8b8;</i>
        </div> 
          <div class="rvcontrol-wrapper">
            <div>
                <button class="rvcontrol-reset">reset</button>
            </div>
            <table>
              <tr class="rvselect-theme rvall-theme-content">
                <td>
                  <div class="rvselect-theme-name">Select Theme</div>
                </td>
                <td>
                  <select class="rvselect-theme-select" id="select_theme">
                    <option value="default_theme">Theme 1</option>
                    <option value="theme_custom">Custom</option>
                  </select>
                </td>  
              </tr>

              <tr class="rvtheme-color-one rvall-theme-content">
                <td>
                  <div class="rvcolor-theme-name">Custome Color 1</div>
                </td>
                <td>
                  <div class="rvtheme-color-box">
                    <input type="text" id="themecolor1" class="rvtheme-color-box-1" data-control="saturation" >
                  </div>
                </td>    
              </tr>

                 <tr class="rvtheme-color-two rvall-theme-content">  
                  <td>
                    <div class="rvcolor-theme-name">Custome Color 2</div>
                  </td>
                  <td>
                    <div class="rvtheme-color-box">
                      <input type="text" id="themecolor2" class="rvtheme-color-box-2" data-control="saturation">
                    </div>
                  </td>  
                </tr>

                <tr class="rvtheme-box-layout rvall-theme-content">
                  <td>
                      <div class="rvtheme-layout-name">Box-Layout</div>
                  </td>
                  <td>
                      <label class="checkbox-inline rvtheme-option rvtheme-box-layout-option">
                        <input type="checkbox" data-toggle="toggle">
                      </label>
                   </td>   
                </tr>
                <tr class="rvtheme-background-patten rvall-theme-content">
                  <td>
                      <div class="rvtheme-background-pattern-name"> Background Pattern</div>
                  </td>
                  <td>
                    <div class="rvtheme-all-pattern-wrapper">
                        <div class="rvtheme-all-pattern">
                            <div id="pattern1" class="rvtheme-pattern-image rvtheme-pattern-image1" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern1.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern2" class="rvtheme-pattern-image rvtheme-pattern-image2" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern2.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern3" class="rvtheme-pattern-image rvtheme-pattern-image3" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern3.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern4" class="rvtheme-pattern-image rvtheme-pattern-image4" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern4.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern5" class="rvtheme-pattern-image rvtheme-pattern-image5" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern5.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern6" class="rvtheme-pattern-image rvtheme-pattern-image6" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern6.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern7" class="rvtheme-pattern-image rvtheme-pattern-image7" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern7.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern8" class="rvtheme-pattern-image rvtheme-pattern-image8" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern8.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern9" class="rvtheme-pattern-image rvtheme-pattern-image9" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern9.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern10" class="rvtheme-pattern-image rvtheme-pattern-image10" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern10.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern11" class="rvtheme-pattern-image rvtheme-pattern-image11" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern11.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern12" class="rvtheme-pattern-image rvtheme-pattern-image12" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern12.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern13" class="rvtheme-pattern-image rvtheme-pattern-image13" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern13.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern14" class="rvtheme-pattern-image rvtheme-pattern-image14" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern14.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern15" class="rvtheme-pattern-image rvtheme-pattern-image15" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern15.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern16" class="rvtheme-pattern-image rvtheme-pattern-image16" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern16.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern17" class="rvtheme-pattern-image rvtheme-pattern-image17" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern17.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern18" class="rvtheme-pattern-image rvtheme-pattern-image18" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern18.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern19" class="rvtheme-pattern-image rvtheme-pattern-image19" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern19.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern20" class="rvtheme-pattern-image rvtheme-pattern-image20" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern20.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern21" class="rvtheme-pattern-image rvtheme-pattern-image21" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern21.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern22" class="rvtheme-pattern-image rvtheme-pattern-image22" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern22.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern23" class="rvtheme-pattern-image rvtheme-pattern-image23" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern23.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern24" class="rvtheme-pattern-image rvtheme-pattern-image24" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern24.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern25" class="rvtheme-pattern-image rvtheme-pattern-image25" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern25.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern26" class="rvtheme-pattern-image rvtheme-pattern-image26" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern26.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern27" class="rvtheme-pattern-image rvtheme-pattern-image27" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern27.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern28" class="rvtheme-pattern-image rvtheme-pattern-image28" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern28.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern29" class="rvtheme-pattern-image rvtheme-pattern-image29" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern29.png')"></div>
                        </div>
                        <div class="rvtheme-all-pattern">
                              <div id="pattern30" class="rvtheme-pattern-image rvtheme-pattern-image30" style="background-image:url('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['img_url'], ENT_QUOTES, 'UTF-8');?>
pattern/pattern30.png')"></div>
                        </div>


                        </div>
                        <p class="notice">custome background also available in admin.</p>
                   </td>   
                </tr>

                <tr class="rvtheme-background-color rvall-theme-content">  
                  <td>
                    <div class="rvbgcolor-theme-name">Background color</div>
                  </td>
                  <td>
                    <div class="rvtheme-bgcolor-box">
                      <input type="text" id="themebgcolor2" data-control="saturation" class="rvtheme-bgcolor-box-2">
                    </div>
                  </td>  
                </tr>


              <tr class="rvtheme-right-sticky rvall-theme-content">
                <td>
                    <div class="rvtheme-right-sticky-name">Right Stickt Enable</div>
                </td>
                <td>
                    <label class="checkbox-inline rvtheme-option rvtheme-right-sticky-option">
                      <input type="checkbox" checked data-toggle="toggle">
                    </label>
                 </td>   
              </tr>

            </table>
          </div>
      </div>
  <?php }?> 

    <main id="page" class="<?php if (Configuration::get('RVCUSTOMSETTING_ADD_CONTAINER')) {?>container rvbox-layout<?php }?>">
      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18699125955d6d4539527c42_07038228', 'product_activation');
?>


      <header id="header">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17833537395d6d4539528db2_02199671', 'header');
?>

      </header>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13258833605d6d4539529c38_36157359', 'notifications');
?>


       <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] != 'index') {?>
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1325559345d6d453952b458_36811428', 'breadcrumb');
?>

      <?php }?>
      
      <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'index') {?>
      <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'displayTopColumn', null, null);
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayTopColumn'),$_smarty_tpl ) );
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
      <?php if ($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayTopColumn')) {?>
        <div id="top_column">
          <?php echo $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayTopColumn');?>

        </div>
      <?php }?>
      <?php }?>

      <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'index') {?>
      <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'displayHomeTop', null, null);
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayHomeTop'),$_smarty_tpl ) );
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
      <?php if ($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayHomeTop')) {?>
        <div id="top_home">
          <?php echo $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayHomeTop');?>

        </div>
      <?php }?>
      <?php }?>

      <section id="wrapper">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayWrapperTop"),$_smarty_tpl ) );?>

        <div class="container">
          <div class="row">
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4577029295d6d453953e795_10640467', "left_column");
?>


          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14078578925d6d45395455a4_81392968', "content_wrapper");
?>


          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6220891305d6d45395539c8_31310093', "right_column");
?>

        </div>
        </div>
        <div class="container">
          <div class="row">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayWrapperBottom"),$_smarty_tpl ) );?>

          </div>
        </div>
      </section>

      <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'index') {?>
        <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'displayHomeBottom', null, null);
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayHomeBottom'),$_smarty_tpl ) );
$_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
        <?php if ($_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayHomeBottom')) {?>
          <div id="bottom_home">
            <?php echo $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'displayHomeBottom');?>

          </div>
        <?php }?>
      <?php }?>

      <div class="container">
        <div id="_mobile_left_column"></div>
        <div id="_mobile_right_column"></div>
        <div class="clearfix"></div>
      </div>

      <footer id="footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19352106365d6d453955e9b0_98036065', "footer");
?>

      </footer>

    </main>
    <a class="backtotop" href="#" title="Back to Top" style="display:none;">&nbsp;</a>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17444264375d6d4539565d31_23626995', 'javascript_bottom');
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11055980545d6d4539569a88_62166641', 'hook_before_body_closing_tag');
?>

  </body>

</html>
<?php }
/* {block 'head'} */
class Block_7615255735d6d45394ece21_39498449 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head' => 
  array (
    0 => 'Block_7615255735d6d45394ece21_39498449',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php $_smarty_tpl->_subTemplateRender('file:_partials/head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'head'} */
/* {block 'hook_after_body_opening_tag'} */
class Block_18690931865d6d45394f50e4_07114872 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'hook_after_body_opening_tag' => 
  array (
    0 => 'Block_18690931865d6d45394f50e4_07114872',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayAfterBodyOpeningTag'),$_smarty_tpl ) );?>

    <?php
}
}
/* {/block 'hook_after_body_opening_tag'} */
/* {block 'product_activation'} */
class Block_18699125955d6d4539527c42_07038228 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'product_activation' => 
  array (
    0 => 'Block_18699125955d6d4539527c42_07038228',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:catalog/_partials/product-activation.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
      <?php
}
}
/* {/block 'product_activation'} */
/* {block 'header'} */
class Block_17833537395d6d4539528db2_02199671 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_17833537395d6d4539528db2_02199671',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php $_smarty_tpl->_subTemplateRender('file:_partials/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php
}
}
/* {/block 'header'} */
/* {block 'notifications'} */
class Block_13258833605d6d4539529c38_36157359 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'notifications' => 
  array (
    0 => 'Block_13258833605d6d4539529c38_36157359',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:_partials/notifications.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
      <?php
}
}
/* {/block 'notifications'} */
/* {block 'breadcrumb'} */
class Block_1325559345d6d453952b458_36811428 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'breadcrumb' => 
  array (
    0 => 'Block_1325559345d6d453952b458_36811428',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php $_smarty_tpl->_subTemplateRender('file:_partials/breadcrumb.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php
}
}
/* {/block 'breadcrumb'} */
/* {block "left_column"} */
class Block_4577029295d6d453953e795_10640467 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'left_column' => 
  array (
    0 => 'Block_4577029295d6d453953e795_10640467',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


              <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] != 'index') {?>
                  <div id="_desktop_left_column" class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                    <div id="left-column">
                  <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'product') {?>
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayLeftColumnProduct'),$_smarty_tpl ) );?>

                  <?php } else { ?>
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayLeftColumn"),$_smarty_tpl ) );?>

                  <?php }?>
                    </div>
                  </div>
              <?php }?>
          <?php
}
}
/* {/block "left_column"} */
/* {block "content"} */
class Block_3096484985d6d453954e8d7_83959886 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                  <p>Hello world! This is HTML5 Boilerplate.</p>
                <?php
}
}
/* {/block "content"} */
/* {block "content_wrapper"} */
class Block_14078578925d6d45395455a4_81392968 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content_wrapper' => 
  array (
    0 => 'Block_14078578925d6d45395455a4_81392968',
  ),
  'content' => 
  array (
    0 => 'Block_3096484985d6d453954e8d7_83959886',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <div id="content-wrapper" class="left-column right-column col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayContentWrapperTop"),$_smarty_tpl ) );?>

                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3096484985d6d453954e8d7_83959886', "content", $this->tplIndex);
?>

                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayContentWrapperBottom"),$_smarty_tpl ) );?>

            </div>
          <?php
}
}
/* {/block "content_wrapper"} */
/* {block "right_column"} */
class Block_6220891305d6d45395539c8_31310093 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'right_column' => 
  array (
    0 => 'Block_6220891305d6d45395539c8_31310093',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

              <div id="_desktop_right_column" class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                <div id="right-column">
              <?php if ($_smarty_tpl->tpl_vars['page']->value['page_name'] == 'product') {?>
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayRightColumnProduct'),$_smarty_tpl ) );?>

              <?php } else { ?>
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayRightColumn"),$_smarty_tpl ) );?>

              <?php }?>
            </div>
              </div>
          <?php
}
}
/* {/block "right_column"} */
/* {block "footer"} */
class Block_19352106365d6d453955e9b0_98036065 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_19352106365d6d453955e9b0_98036065',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php $_smarty_tpl->_subTemplateRender("file:_partials/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php
}
}
/* {/block "footer"} */
/* {block 'javascript_bottom'} */
class Block_17444264375d6d4539565d31_23626995 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'javascript_bottom' => 
  array (
    0 => 'Block_17444264375d6d4539565d31_23626995',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php $_smarty_tpl->_subTemplateRender("file:_partials/javascript.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('javascript'=>$_smarty_tpl->tpl_vars['javascript']->value['bottom']), 0, false);
?>
      <?php if (Configuration::get('RVFRONTSIDE_THEME_SETTING_SHOW')) {?>
        <!-- START THEME_CONTROL -->
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['js_url'], ENT_QUOTES, 'UTF-8');?>
jquery.minicolors.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"><?php echo '</script'; ?>
>
        <!-- END THEME_CONTROL -->
      <?php }?>
    <?php
}
}
/* {/block 'javascript_bottom'} */
/* {block 'hook_before_body_closing_tag'} */
class Block_11055980545d6d4539569a88_62166641 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'hook_before_body_closing_tag' => 
  array (
    0 => 'Block_11055980545d6d4539569a88_62166641',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayBeforeBodyClosingTag'),$_smarty_tpl ) );?>

    <?php
}
}
/* {/block 'hook_before_body_closing_tag'} */
}
