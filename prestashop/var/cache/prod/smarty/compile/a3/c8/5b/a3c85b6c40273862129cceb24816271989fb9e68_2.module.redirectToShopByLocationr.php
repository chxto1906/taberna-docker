<?php
/* Smarty version 3.1.33, created on 2019-08-13 15:12:50
  from 'module:redirectToShopByLocationr' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5319c2db7b13_90418468',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a3c85b6c40273862129cceb24816271989fb9e68' => 
    array (
      0 => 'module:redirectToShopByLocationr',
      1 => 1561678042,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d5319c2db7b13_90418468 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="location-modal" class="modal fade" tabindex="-1" role="dialog" data-show="true" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-content-tienda">
      <div class="modal-header modal-header-tienda">
        <img class="img-pin-pop-up" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module_dir']->value, ENT_QUOTES, 'UTF-8');?>
views/img/pin_map.png">
        <img class="img-responsive img-fluid" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['img_logo']->value, ENT_QUOTES, 'UTF-8');?>
">
        
      </div>
      <div class="modal-body modal-body-tienda">
        <!-- 1 -->
        
        <h4 id="text-tienda-modal"></h4>
        <select id="tiendas_select_popup">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['shops']->value, 'shop');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['shop']->value) {
?>
            <option value="http://<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['domain'], ENT_QUOTES, 'UTF-8');?>
/<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['virtual_uri'], ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
</option>
          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>
        <h5 class="permitir-ubicacion-text">Ó permite al navegador obtener tu localización.</h5>
                

      </div>
      <div class="modal-footer">
        <section class="section-pop-up">
          <input type="checkbox" class="form-checkbox" id="check-one"><label class="label-redirect" for="check-one">Confirmo que soy mayor de 18 años</label>
        </section>
        <br>

        <button id="btnCancelarModal" type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Cancelar','d'=>'redirectToShopByLocation'),$_smarty_tpl ) );?>
</button>
        <button id="btnAceptarModalTienda" type="button" class="btn btn-primary disabled" disabled="disabled"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Continuar','d'=>'redirectToShopByLocation'),$_smarty_tpl ) );?>
</button>
      </div>
    </div>
  </div>
</div><?php }
}
