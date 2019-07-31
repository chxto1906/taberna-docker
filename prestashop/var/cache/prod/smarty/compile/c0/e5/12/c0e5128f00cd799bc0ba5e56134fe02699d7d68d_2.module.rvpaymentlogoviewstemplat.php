<?php
/* Smarty version 3.1.33, created on 2019-07-31 17:26:41
  from 'module:rvpaymentlogoviewstemplat' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4215a1645268_56375131',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c0e5128f00cd799bc0ba5e56134fe02699d7d68d' => 
    array (
      0 => 'module:rvpaymentlogoviewstemplat',
      1 => 1561678042,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4215a1645268_56375131 (Smarty_Internal_Template $_smarty_tpl) {
if (isset($_smarty_tpl->tpl_vars['cms_payment_logo']->value) && $_smarty_tpl->tpl_vars['cms_payment_logo']->value) {?>
	<div id="payment_logo_block_left" class="payment_logo_block col-lg-4 col-md-12">
	<span>Payment</span>
		<a href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['link']->value->getCMSLink($_smarty_tpl->tpl_vars['cms_payment_logo']->value),'html' )), ENT_QUOTES, 'UTF-8');?>
">
			<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8');?>
views/img/payphone_icon.png" alt="Payphone" width="40" height="25">
			<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8');?>
views/img/visa.png" alt="Visa" width="40" height="25" />
			<img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8');?>
views/img/mastercard.png" alt="Mastercard" width="40" height="25" />
					</a>
	</div>
<?php }
}
}
