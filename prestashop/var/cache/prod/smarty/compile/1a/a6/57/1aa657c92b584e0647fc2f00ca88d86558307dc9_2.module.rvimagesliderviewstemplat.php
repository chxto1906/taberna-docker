<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:43:22
  from 'module:rvimagesliderviewstemplat' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49920aced451_02527493',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1aa657c92b584e0647fc2f00ca88d86558307dc9' => 
    array (
      0 => 'module:rvimagesliderviewstemplat',
      1 => 1561678042,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49920aced451_02527493 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Module ImageSlider -->
<?php if ($_smarty_tpl->tpl_vars['rvimageslider']->value['rvpoh'] == 1) {?>
  <?php $_smarty_tpl->_assignInScope('autoplay_value', 'true');
} else { ?>
  <?php $_smarty_tpl->_assignInScope('autoplay_value', 'false');
}
if ($_smarty_tpl->tpl_vars['rvimageslider']->value['slides']) {?>
  <div id="rvimageslider" data-interval="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['rvimageslider']->value['rvspeed'], ENT_QUOTES, 'UTF-8');?>
" data-pause="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['autoplay_value']->value, ENT_QUOTES, 'UTF-8');?>
" class="container">
    <div class="rvnivo-slider">
      <div id="slider" class="">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rvimageslider']->value['slides'], 'slide', false, NULL, 'rvimageslider', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['slide']->value) {
?>
          <?php if ($_smarty_tpl->tpl_vars['slide']->value['active']) {?>
            <a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['url'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['legend'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
              <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['slide']->value['image_url'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['slide']->value['legend'] )), ENT_QUOTES, 'UTF-8');?>
" />
            </a>
          <?php }?>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
      </div>
    </div>
  </div>
<?php }?>
<!-- Module ImageSlider --><?php }
}
