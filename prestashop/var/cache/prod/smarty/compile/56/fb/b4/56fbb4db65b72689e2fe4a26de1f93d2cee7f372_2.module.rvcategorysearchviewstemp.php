<?php
/* Smarty version 3.1.33, created on 2019-08-06 09:48:59
  from 'module:rvcategorysearchviewstemp' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d49935ba52938_62103074',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '56fbb4db65b72689e2fe4a26de1f93d2cee7f372' => 
    array (
      0 => 'module:rvcategorysearchviewstemp',
      1 => 1563288288,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d49935ba52938_62103074 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="_desktop_rvsearch" class="col-lg-10">
<div class="rvcategorysearch">
    <div id="category_search">
        <form id="searchbox" method="get" action="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['search_controller_url']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
            <div class="rvsearch-main">
            <input name="controller" value="search" type="hidden">
            <input name="orderby" value="position" type="hidden">
            <input name="orderway" value="desc" type="hidden">
            <?php if (isset($_smarty_tpl->tpl_vars['searchCategoryList']->value) && $_smarty_tpl->tpl_vars['searchCategoryList']->value) {?>
                <div class="searchboxform-control">
                    <select name="all_category" id="all_category">
                        <option value="all"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'All Categories','mod'=>'rvcategorysearch'),$_smarty_tpl ) );?>
</option>
                        <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['all_category']->value,'quotes','UTF-8' ));?>

                    </select>
                </div>
            <?php }?>
        </div>
            <button type="submit" name="submit_search" class="btn btn-primary button-search">
                <span>Buscar</span>
            </button>
            <div class="input-wrapper">
                <input class="search_query form-control" type="text" name="search_query" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search Our Catalog','mod'=>'rvcategorysearch'),$_smarty_tpl ) );?>
" value="<?php echo htmlspecialchars(stripslashes(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['search_query']->value,'htmlall','UTF-8' ))), ENT_QUOTES, 'UTF-8');?>
" autocomplete="off" />
            </div>
            <div id="rvajax_search" style="display:none">
                <input type="hidden" value="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['base_ssl']->value,'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
/ajax_search.php" class="ajaxUrl" />
            </div>
        </form>

    </div>
</div>
</div>
<?php }
}
