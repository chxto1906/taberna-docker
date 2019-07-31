<?php
/* Smarty version 3.1.33, created on 2019-07-31 17:26:01
  from 'module:rvwishlistblockviewstempl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d42157952dde5_60711413',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '592db0304398379224c920815e728ee5bfb37953' => 
    array (
      0 => 'module:rvwishlistblockviewstempl',
      1 => 1561678042,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d42157952dde5_60711413 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16439280125d42157948ffb1_30163037', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'my_account_links'} */
class Block_13864310985d421579528f85_76934048 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['pages']['my_account'], ENT_QUOTES, 'UTF-8');?>
" class="account-link">
                        <i class="fa fa-angle-left"></i>
                        <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Back to your account','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</span>
                    </a>
                    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['urls']->value['pages']['index'], ENT_QUOTES, 'UTF-8');?>
" class="account-link">
                        <i class="fa fa-home"></i>
                        <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Home','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</span>
                    </a>
                <?php
}
}
/* {/block 'my_account_links'} */
/* {block 'content'} */
class Block_16439280125d42157948ffb1_30163037 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_16439280125d42157948ffb1_30163037',
  ),
  'my_account_links' => 
  array (
    0 => 'Block_13864310985d421579528f85_76934048',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/html/vendor/smarty/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

        <div id="mywishlist">
            <h1 class="h1"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'My wishlists','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</h1>
            <?php if (isset($_smarty_tpl->tpl_vars['errors']->value) && $_smarty_tpl->tpl_vars['errors']->value) {?>
                <div class="alert alert-danger">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['errors']->value, 'error', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['error']->value) {
?>
                        <div class="error"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['error']->value, ENT_QUOTES, 'UTF-8');?>
</div>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </div>
            <?php }?>

            <?php if (intval($_smarty_tpl->tpl_vars['id_customer']->value) != 0) {?>
                <form method="post" class="card card-block" id="form_wishlist">
                    <fieldset>
                        <h3 class="page-subheading"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'New wishlist','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</h3>
                        <div class="form-group">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['token']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
                            <label class="align_right" for="name">
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Name','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>

                            </label>
                            <input type="text" id="name" name="name" class="inputTxt form-control" value="<?php if (isset($_POST['name']) && count($_smarty_tpl->tpl_vars['errors']->value) > 0) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_POST['name'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
}?>" />
                        </div>
                        <p class="submit">
                            <button id="submitWishlist" class="btn btn-primary" type="submit" name="submitWishlist">
                                <span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Save','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</span>
                            </button>
                        </p>
                    </fieldset>
                </form>
                <?php if ($_smarty_tpl->tpl_vars['wishlists']->value) {?>
                    <div id="block-history" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="first_item"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Name','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</th>
                                    <th class="item mywishlist_first"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qty','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</th>
                                    <th class="item mywishlist_first"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Viewed','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</th>
                                    <th class="item mywishlist_second"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Created','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</th>
                                    <th class="item mywishlist_second"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Direct Link','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</th>
                                    <th class="item mywishlist_second"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Default','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</th>
                                    <th class="last_item mywishlist_first"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delete','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>
</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['wishlists']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
                                    <tr id="wishlist_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id_wishlist']), ENT_QUOTES, 'UTF-8');?>
">
                                        <td style="width:200px;">
                                            <a href="#" onclick="javascript:event.preventDefault();WishlistManage('block-order-detail', '<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id_wishlist']), ENT_QUOTES, 'UTF-8');?>
');">
                                                <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['name'],30,'...' )),'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                            </a>
                                        </td>
                                        <td class="bold align_center">
                                            <?php $_smarty_tpl->_assignInScope('n', 0);?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['nbProducts']->value, 'nb', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['nb']->value) {
?>
                                                <?php if ($_smarty_tpl->tpl_vars['nb']->value['id_wishlist'] == $_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id_wishlist']) {?>
                                                    <?php $_smarty_tpl->_assignInScope('n', intval($_smarty_tpl->tpl_vars['nb']->value['nbProducts']));?>
                                                <?php }?>
                                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                            <?php if ($_smarty_tpl->tpl_vars['n']->value) {?>
                                                <?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['n']->value), ENT_QUOTES, 'UTF-8');?>

                                            <?php } else { ?>
                                                0
                                            <?php }?>
                                        </td>
                                        <td><?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['counter']), ENT_QUOTES, 'UTF-8');?>
</td>
                                        <td><?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['date_add'],"%Y-%m-%d"), ENT_QUOTES, 'UTF-8');?>
</td>
                                        <td>
                                            <a href="#" onclick="javascript:event.preventDefault();WishlistManage('block-order-detail', '<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id_wishlist']), ENT_QUOTES, 'UTF-8');?>
');">
                                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'View','mod'=>'rvwishlistblock'),$_smarty_tpl ) );?>

                                            </a>
                                        </td>
                                        <td class="wishlist_default">
                                            <?php if ((($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['default'] !== null )) && $_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['default'] == 1) {?>
                                                <p class="is_wish_list_default">
                                                    <i class="fa fa-check-square"></i>
                                                </p>
                                            <?php } else { ?>
                                                <a href="#" onclick="javascript:event.preventDefault();(WishlistDefault('wishlist_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id_wishlist']), ENT_QUOTES, 'UTF-8');?>
', '<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id_wishlist']), ENT_QUOTES, 'UTF-8');?>
'));">
                                                    <i class="fa fa-square"></i>
                                                </a>
                                            <?php }?>
                                        </td>
                                        <td class="wishlist_delete">
                                            <a class="icon" href="#" onclick="javascript:event.preventDefault();return (WishlistDelete('wishlist_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id_wishlist']), ENT_QUOTES, 'UTF-8');?>
', '<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['wishlists']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id_wishlist']), ENT_QUOTES, 'UTF-8');?>
', '<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Do you really want to delete this wishlist ?','mod'=>'rvwishlistblock','js'=>1),$_smarty_tpl ) );?>
'));">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php
}
}
?>
                            </tbody>
                        </table>
                    </div>
                    <div id="block-order-detail">&nbsp;</div>
                <?php }?>
            <?php }?>
            <div class="page-footer clearfix">
                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13864310985d421579528f85_76934048', 'my_account_links', $this->tplIndex);
?>

            </div>
        </div>
<?php
}
}
/* {/block 'content'} */
}
