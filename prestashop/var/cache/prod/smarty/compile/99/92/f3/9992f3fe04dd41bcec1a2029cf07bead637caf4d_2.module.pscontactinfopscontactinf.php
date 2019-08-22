<?php
/* Smarty version 3.1.33, created on 2019-08-22 10:00:35
  from 'module:pscontactinfopscontactinf' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5eae13a13ce9_44192320',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9992f3fe04dd41bcec1a2029cf07bead637caf4d' => 
    array (
      0 => 'module:pscontactinfopscontactinf',
      1 => 1561678046,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d5eae13a13ce9_44192320 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="block-contact footer-block col-lg-4 rv-animate-element right-to-left">
  <h4 class="title_block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Store information','d'=>'Shop.Theme.Global'),$_smarty_tpl ) );?>
</h4>
  <div class="toggle-footer">
    <div class="block">
      <div class="icon rvaddress"><i class="fa fa-map-marker"></i></div>
      <div class="data rvaddress"><?php echo $_smarty_tpl->tpl_vars['contact_infos']->value['address']['formatted'];?>
</div>
    </div>

    <?php if ($_smarty_tpl->tpl_vars['contact_infos']->value['phone']) {?>
    <div class="block">
      <div class="icon phone"><i class="fa fa-phone-square"></i></div>
      <div class="data phone">
        <a href="tel:<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['contact_infos']->value['phone'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['contact_infos']->value['phone'], ENT_QUOTES, 'UTF-8');?>
</a>
      </div>
    </div>
    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['contact_infos']->value['fax']) {?>
    <div class="block">
      <div class="icon fax"><i class="fa fa-fax"></i></div>
      <div class="data fax">
        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['contact_infos']->value['fax'], ENT_QUOTES, 'UTF-8');?>

      </div>
    </div>
    <?php }?>
    
    <?php if ($_smarty_tpl->tpl_vars['contact_infos']->value['email']) {?>
    <div class="block">
      <div class="icon email"><i class="fa fa-envelope"></i></div>
      <div class="data email">
        <a href="mailto:<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['contact_infos']->value['email'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['contact_infos']->value['email'], ENT_QUOTES, 'UTF-8');?>
</a>
      </div>
    </div>
    <?php }?>
  </div>
</div>
<?php }
}
