<?php
/* Smarty version 3.1.33, created on 2019-07-31 17:26:40
  from '/html/modules/rvproductcountdown/views/templates/hook/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4215a0928ba0_22885955',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1fdf2c314d26e81ac7928637f3036463bcc60ca5' => 
    array (
      0 => '/html/modules/rvproductcountdown/views/templates/hook/header.tpl',
      1 => 1561678042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4215a0928ba0_22885955 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    <?php if ($_smarty_tpl->tpl_vars['show_weeks']->value) {?>
        var rvpc_labels = ['weeks', 'days', 'hours', 'minutes', 'seconds'];
        var rvpc_labels_lang = {
            'weeks': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'weeks','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
',
            'days': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'days','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
',
            'hours': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'hours','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
',
            'minutes': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'minutes','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
',
            'seconds': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'seconds','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
'
        };
    <?php } else { ?>
    var rvpc_labels = ['days', 'hours', 'minutes', 'seconds'];
    var rvpc_labels_lang = {
        'days': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'days','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
',
        'hours': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'hours','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
',
        'minutes': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'minutes','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
',
        'seconds': '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'seconds','mod'=>'rvproductcountdown'),$_smarty_tpl ) );?>
'
    };
    <?php }?>
    var rvpc_show_weeks = <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['show_weeks']->value), ENT_QUOTES, 'UTF-8');?>
;
<?php echo '</script'; ?>
><?php }
}
