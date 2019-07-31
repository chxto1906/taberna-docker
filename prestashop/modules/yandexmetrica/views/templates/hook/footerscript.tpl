{*
* Integrate Yandex Metrica script into PrestaShop. DISCLAIMER; NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE WITH YOUR MERCHANT AGREEMENT. USE AT YOUR OWN RISK.
*
* @author    Jose Antonio Ruiz <jruizcantero@gmail.com>
* @copyright 2007-2018
* @license   This product is licensed for one customer to use on one domain. Site developer has the
*                         right to modify this module to suit their needs, but can not redistribute the module in
*                         whole or in part. Any other use of this module constitues a violation of the user agreement.
*}

{if isset($ymetrica_id) && $ymetrica_id}
        {literal}
                <!-- Yandex.Metrika counter for PrestaShop by http://twitter.com/jruizcantero -->
                <script type="text/javascript">
                        (function (d, w, c) {
                                (w[c] = w[c] || []).push(function() {
                                        try {
                                                w.yaCounter{/literal}{$ymetrica_id|string_format:"%s"|escape:'quotes'|escape:'htmlall':'UTF-8'}{literal} = new Ya.Metrika({
                                                        id:{/literal}'{$ymetrica_id|escape:'quotes'|escape:'htmlall':'UTF-8'}'{literal},
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
                </script>
                <noscript><div><img src="https://mc.yandex.ru/watch/{/literal}{$ymetrica_id|string_format:"%s"|escape:'quotes'|escape:'htmlall':'UTF-8'}{literal}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                <!-- /Yandex.Metrika counter for PrestaShop by http://twitter.com/jruizcantero -->
        {/literal}
{/if}
