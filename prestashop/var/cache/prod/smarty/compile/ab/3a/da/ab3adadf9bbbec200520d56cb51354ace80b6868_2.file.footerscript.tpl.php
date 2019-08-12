<?php
/* Smarty version 3.1.33, created on 2019-08-12 15:06:18
  from '/html/modules/yandexmetrica/views/templates/hook/footerscript.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d51c6ba203259_24948223',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ab3adadf9bbbec200520d56cb51354ace80b6868' => 
    array (
      0 => '/html/modules/yandexmetrica/views/templates/hook/footerscript.tpl',
      1 => 1561678042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d51c6ba203259_24948223 (Smarty_Internal_Template $_smarty_tpl) {
if (isset($_smarty_tpl->tpl_vars['ymetrica_id']->value) && $_smarty_tpl->tpl_vars['ymetrica_id']->value) {?>
        
                <!-- Yandex.Metrika counter for PrestaShop by http://twitter.com/jruizcantero -->
                <?php echo '<script'; ?>
 type="text/javascript">
                        (function (d, w, c) {
                                (w[c] = w[c] || []).push(function() {
                                        try {
                                                w.yaCounter<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sprintf("%s",$_smarty_tpl->tpl_vars['ymetrica_id']->value),'quotes' )),'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 = new Ya.Metrika({
                                                        id:'<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['ymetrica_id']->value,'quotes' )),'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
',
                                                        clickmap:true,
                                                        trackLinks:true,
                                                        accurateTrackBounce:true,
                                                        webvisor:true,
                                                        ecommerce:"dataLayer"
                                                });
                                        } catch(e) { }
                                });

                                var n = d.getElementsByTagName("script")[0],
                                        s = d.createElement("script"),
                                        f = function () { n.parentNode.insertBefore(s, n); };
                                s.type = "text/javascript";
                                s.async = true;
                                s.src = "https://mc.yandex.ru/metrika/watch.js";

                                if (w.opera == "[object Opera]") {
                                        d.addEventListener("DOMContentLoaded", f, false);
                                } else { f(); }
                        })(document, window, "yandex_metrika_callbacks");
                <?php echo '</script'; ?>
>
                <noscript><div><img src="https://mc.yandex.ru/watch/<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( sprintf("%s",$_smarty_tpl->tpl_vars['ymetrica_id']->value),'quotes' )),'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                <!-- /Yandex.Metrika counter for PrestaShop by http://twitter.com/jruizcantero -->
        
<?php }
}
}
