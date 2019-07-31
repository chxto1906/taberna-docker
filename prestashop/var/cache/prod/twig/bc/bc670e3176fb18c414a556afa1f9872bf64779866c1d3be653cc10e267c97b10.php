<?php

/* __string_template__c97b5ed4a6c682d65378cdb3117342bbbbfe1ae2978aea0f600c57a9723973f2 */
class __TwigTemplate_394363b2b95e031bebdad7b5913ff90aa4b803fcfc325c1ca4b1942b855b4dcd extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
            'extra_stylesheets' => array($this, 'block_extra_stylesheets'),
            'content_header' => array($this, 'block_content_header'),
            'content' => array($this, 'block_content'),
            'content_footer' => array($this, 'block_content_footer'),
            'sidebar_right' => array($this, 'block_sidebar_right'),
            'javascripts' => array($this, 'block_javascripts'),
            'extra_javascripts' => array($this, 'block_extra_javascripts'),
            'translate_javascripts' => array($this, 'block_translate_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"es\">
<head>
  <meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=0.75, maximum-scale=0.75, user-scalable=0\">
<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">
<meta name=\"robots\" content=\"NOFOLLOW, NOINDEX\">

<link rel=\"icon\" type=\"image/x-icon\" href=\"/cuenca/zona-rosa/img/favicon.ico\" />
<link rel=\"apple-touch-icon\" href=\"/cuenca/zona-rosa/img/app_icon.png\" />

<title>Dirección de correo electrónico • La Taberna Liquor Store</title>

  <script type=\"text/javascript\">
    var help_class_name = 'AdminEmails';
    var iso_user = 'es';
    var lang_is_rtl = '0';
    var full_language_code = 'es';
    var full_cldr_language_code = 'es-ES';
    var country_iso_code = 'EC';
    var _PS_VERSION_ = '1.7.5.0';
    var roundMode = 2;
    var youEditFieldFor = '';
        var new_order_msg = 'Se ha recibido un nuevo pedido en tu tienda.';
    var order_number_msg = 'Número de pedido: ';
    var total_msg = 'Total: ';
    var from_msg = 'Desde: ';
    var see_order_msg = 'Ver este pedido';
    var new_customer_msg = 'Un nuevo cliente se ha registrado en tu tienda.';
    var customer_name_msg = 'Nombre del cliente: ';
    var new_msg = 'Un nuevo mensaje ha sido publicado en tu tienda.';
    var see_msg = 'Leer este mensaje';
    var token = '769c3784f40e6169a39a56fb9d98231c';
    var token_admin_orders = 'dbb72eaa94bdc0673a84495fc3eabd26';
    var token_admin_customers = '4df75016f3ffbbbd91855ae27fc5176d';
    var token_admin_customer_threads = 'ed773bb9a7110e7244a868e23eaaf619';
    var currentIndex = 'index.php?controller=AdminEmails';
    var employee_token = '53a65bf0e89b2cd3575d07d55a328a1b';
    var choose_language_translate = 'Selecciona el idioma';
    var default_language = '1';
    var admin_modules_link = '/cuenca/zona-rosa/backoffice/index.php/improve/modules/catalog/recommended?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc';
    var tab_modules_list = 'sendinblue,digitaleo,triggmine,newsletter,mynewsletter,mailchimp,mailchimpintegration,salesmanago,etranslation,bablic,smartsupp,onehopsmsservice,ps_emailalerts';
    var update_success_msg = 'Actualización correcta';
    var errorLogin = 'PrestaShop no pudo iniciar sesión en Addons. Por favor verifica tus datos de acceso y tu conexión de Internet.';
    var search_product_msg = 'Buscar un producto';
  </script>

      <link href=\"/cuenca/zona-rosa/modules/rvcustomsetting/views/css/back.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/cuenca/zona-rosa/backoffice/themes/new-theme/public/theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/cuenca/zona-rosa/js/jquery/plugins/chosen/jquery.chosen.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/cuenca/zona-rosa/backoffice/themes/default/css/vendor/nv.d3.css\" rel=\"stylesheet\" type=\"text/css\"/>
  
  <script type=\"text/javascript\">
var baseAdminDir = \"\\/cuenca\\/zona-rosa\\/backoffice\\/\";
var baseDir = \"\\/cuenca\\/zona-rosa\\/\";
var currency = {\"iso_code\":\"USD\",\"sign\":\"\$\",\"name\":\"d\\u00f3lar estadounidense\",\"format\":\"#,##0.00\\u00a0\\u00a4\"};
var host_mode = false;
var show_new_customers = \"1\";
var show_new_messages = false;
var show_new_orders = \"1\";
</script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/js/jquery/jquery-1.11.0.min.js\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/js/jquery/jquery-migrate-1.2.1.min.js\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/modules/rvcustomsetting/views/js/back.js\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/backoffice/themes/new-theme/public/main.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/js/jquery/plugins/jquery.chosen.js\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/js/admin.js?v=1.7.5.0\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/js/cldr.js\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/js/tools.js?v=1.7.5.0\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/backoffice/public/bundle.js\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/js/vendor/d3.v3.min.js\"></script>
<script type=\"text/javascript\" src=\"/cuenca/zona-rosa/backoffice/themes/default/js/vendor/nv.d3.min.js\"></script>

  <style>
.icon-AdminSmartBlog:before{
  content: \"\\f14b\";
   }
 
</style>

";
        // line 81
        $this->displayBlock('stylesheets', $context, $blocks);
        $this->displayBlock('extra_stylesheets', $context, $blocks);
        echo "</head>
<body class=\"lang-es adminemails\">


<header id=\"header\">
  <nav id=\"header_infos\" class=\"main-header\">

    <button class=\"btn btn-primary-reverse onclick btn-lg unbind ajax-spinner\"></button>

        
        <i class=\"material-icons js-mobile-menu\">menu</i>
    <a id=\"header_logo\" class=\"logo float-left\" href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminDashboard&amp;token=9f7961176c40f863b31a257fab2deaaf\"></a>
    <span id=\"shop_version\">1.7.5.0</span>

    <div class=\"component\" id=\"quick-access-container\">
      <div class=\"dropdown quick-accesses\">
  <button class=\"btn btn-link btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" id=\"quick_select\">
    Acceso rápido
  </button>
  <div class=\"dropdown-menu\">
          <a class=\"dropdown-item\"
         href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=b27eb783900516922144bfb17a2f2dbd\"
                 data-item=\"Evaluación del catálogo\"
      >Evaluación del catálogo</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php/improve/modules/manage?token=cf0fd669f787795841a96fe25ec1634a\"
                 data-item=\"Módulos instalados\"
      >Módulos instalados</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCategories&amp;addcategory&amp;token=bd693f96506a2d2ac6439fd62dec44cf\"
                 data-item=\"Nueva categoría\"
      >Nueva categoría</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php/sell/catalog/products/new?token=cf0fd669f787795841a96fe25ec1634a\"
                 data-item=\"Nuevo\"
      >Nuevo</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=257afe4e77e36fefeb73c90cdf2267c7\"
                 data-item=\"Nuevo cupón de descuento\"
      >Nuevo cupón de descuento</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminOrders&amp;token=dbb72eaa94bdc0673a84495fc3eabd26\"
                 data-item=\"Pedidos\"
      >Pedidos</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?http://localhost/?controller=AdminModules&amp;&amp;configure=smartblog&amp;token=998e86a2ceaae0601c85ab1709527974\"
                 data-item=\"Smart Blog Setting\"
      >Smart Blog Setting</a>
        <div class=\"dropdown-divider\"></div>
          <a
        class=\"dropdown-item js-quick-link\"
        href=\"#\"
        data-rand=\"160\"
        data-icon=\"icon-AdminAdvancedParameters\"
        data-method=\"add\"
        data-url=\"index.php/configure/advanced/emails/?-Z7PuYGKT7LWc\"
        data-post-link=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminQuickAccesses&token=71469ba23cc0328585ee6958e7e88d6b\"
        data-prompt-text=\"Por favor, renombre este acceso rápido:\"
        data-link=\"Direcci&oacute;n de correo...\"
      >
        <i class=\"material-icons\">add_circle</i>
        Añadir esta página a Acceso rápido
      </a>
        <a class=\"dropdown-item\" href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminQuickAccesses&token=71469ba23cc0328585ee6958e7e88d6b\">
      <i class=\"material-icons\">settings</i>
      Administrar accesos rápidos
    </a>
  </div>
</div>
    </div>
    <div class=\"component\" id=\"header-search-container\">
      <form id=\"header_search\"
      class=\"bo_search_form dropdown-form js-dropdown-form collapsed\"
      method=\"post\"
      action=\"/cuenca/zona-rosa/backoffice/index.php?controller=AdminSearch&amp;token=7c5fadf87d2fa63bdf0935e7863c89ac\"
      role=\"search\">
  <input type=\"hidden\" name=\"bo_search_type\" id=\"bo_search_type\" class=\"js-search-type\" />
    <div class=\"input-group\">
    <input type=\"text\" class=\"form-control js-form-search\" id=\"bo_query\" name=\"bo_query\" value=\"\" placeholder=\"Buscar (p. ej.: referencia de producto, nombre de cliente...)\">
    <div class=\"input-group-append\">
      <button type=\"button\" class=\"btn btn-outline-secondary dropdown-toggle js-dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
        toda la tienda
      </button>
      <div class=\"dropdown-menu js-items-list\">
        <a class=\"dropdown-item\" data-item=\"toda la tienda\" href=\"#\" data-value=\"0\" data-placeholder=\"¿Qué estás buscando?\" data-icon=\"icon-search\"><i class=\"material-icons\">search</i> toda la tienda</a>
        <div class=\"dropdown-divider\"></div>
        <a class=\"dropdown-item\" data-item=\"Catálogo\" href=\"#\" data-value=\"1\" data-placeholder=\"Nombre del producto, SKU, referencia...\" data-icon=\"icon-book\"><i class=\"material-icons\">store_mall_directory</i> Catálogo</a>
        <a class=\"dropdown-item\" data-item=\"Clientes por nombre\" href=\"#\" data-value=\"2\" data-placeholder=\"Email, nombre...\" data-icon=\"icon-group\"><i class=\"material-icons\">group</i> Clientes por nombre</a>
        <a class=\"dropdown-item\" data-item=\"Clientes por dirección IP\" href=\"#\" data-value=\"6\" data-placeholder=\"123.45.67.89\" data-icon=\"icon-desktop\"><i class=\"material-icons\">desktop_mac</i> Clientes por dirección IP</a>
        <a class=\"dropdown-item\" data-item=\"Pedidos\" href=\"#\" data-value=\"3\" data-placeholder=\"ID del pedido\" data-icon=\"icon-credit-card\"><i class=\"material-icons\">shopping_basket</i> Pedidos</a>
        <a class=\"dropdown-item\" data-item=\"Facturas\" href=\"#\" data-value=\"4\" data-placeholder=\"Número de factura\" data-icon=\"icon-book\"><i class=\"material-icons\">book</i> Facturas</a>
        <a class=\"dropdown-item\" data-item=\"Carritos\" href=\"#\" data-value=\"5\" data-placeholder=\"ID carrito\" data-icon=\"icon-shopping-cart\"><i class=\"material-icons\">shopping_cart</i> Carritos</a>
        <a class=\"dropdown-item\" data-item=\"Módulos\" href=\"#\" data-value=\"7\" data-placeholder=\"Nombre del módulo\" data-icon=\"icon-puzzle-piece\"><i class=\"material-icons\">extension</i> Módulos</a>
      </div>
      <button class=\"btn btn-primary\" type=\"submit\"><span class=\"d-none\">BÚSQUEDA</span><i class=\"material-icons\">search</i></button>
    </div>
  </div>
</form>

<script type=\"text/javascript\">
 \$(document).ready(function(){
    \$('#bo_query').one('click', function() {
    \$(this).closest('form').removeClass('collapsed');
  });
});
</script>
    </div>

            <div class=\"component\" id=\"header-shop-list-container\">
        <div id=\"shop-list\" class=\"shop-list dropdown ps-dropdown stores\">
    <button class=\"btn btn-link\" type=\"button\" data-toggle=\"dropdown\">
      <span class=\"selected-item\">
        <i class=\"material-icons visibility\">visibility</i>
                  All shops
                <i class=\"material-icons arrow-down\">arrow_drop_down</i>
      </span>
    </button>
    <div class=\"dropdown-menu dropdown-menu-right ps-dropdown-menu\">
      <ul class=\"items-list\"><li class=\"active\"><a class=\"dropdown-item\" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=\">Todas las tiendas</a></li><li class=\"group\"><a class=\"dropdown-item\" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=g-3\">grupo La Taberna MultiStore</a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-16\">La Taberna (Ambato)</a><a class=\"link-shop\" href=\"/ambato/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-14\">La Taberna (Loja)</a><a class=\"link-shop\" href=\"/loja/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-15\">La Taberna (Machala)</a><a class=\"link-shop\" href=\"/machala/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-1\">La Taberna (Manta)</a><a class=\"link-shop\" href=\"/manta/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-12\">La Taberna Brasil (Quito)</a><a class=\"link-shop\" href=\"/quito/brasil/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-7\">La Taberna Cumbaya (Quito)</a><a class=\"link-shop\" href=\"/quito/cumbaya/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-5\">La Taberna Estadio (Cuenca)</a><a class=\"link-shop\" href=\"/cuenca/estadio/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-10\">La Taberna Kennedy Norte (Guayaquil)</a><a class=\"link-shop\" href=\"/guayaquil/kennedy-norte/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-6\">La Taberna Orellana (Quito)</a><a class=\"link-shop\" href=\"/quito/la-taberna-orellana/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-4\">La Taberna Remigio Crespo (Cuenca)</a><a class=\"link-shop\" href=\"/cuenca/remigio-crespo/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-11\">La Taberna Samborondon (Guayaquil)</a><a class=\"link-shop\" href=\"/guayaquil/samborondon/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-17\">La Taberna Showroom (Cuenca)</a><a class=\"link-shop\" href=\"/cuenca/showroom/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-9\">La Taberna Urdesa (Guayaquil)</a><a class=\"link-shop\" href=\"/guayaquil/urdesa/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc&amp;setShopContext=s-3\">La Taberna Zona Rosa (Cuenca)</a><a class=\"link-shop\" href=\"/cuenca/zona-rosa/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li></ul>

    </div>
  </div>
    </div>
          <div class=\"component header-right-component\" id=\"header-notifications-container\">
        <div id=\"notif\" class=\"notification-center dropdown dropdown-clickable\">
  <button class=\"btn notification js-notification dropdown-toggle\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">notifications_none</i>
    <span id=\"notifications-total\" class=\"count hide\">0</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right js-notifs_dropdown\">
    <div class=\"notifications\">
      <ul class=\"nav nav-tabs\" role=\"tablist\">
                          <li class=\"nav-item\">
            <a
              class=\"nav-link active\"
              id=\"orders-tab\"
              data-toggle=\"tab\"
              data-type=\"order\"
              href=\"#orders-notifications\"
              role=\"tab\"
            >
              Pedidos<span id=\"_nb_new_orders_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"customers-tab\"
              data-toggle=\"tab\"
              data-type=\"customer\"
              href=\"#customers-notifications\"
              role=\"tab\"
            >
              Clientes<span id=\"_nb_new_customers_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"messages-tab\"
              data-toggle=\"tab\"
              data-type=\"customer_message\"
              href=\"#messages-notifications\"
              role=\"tab\"
            >
              Mensajes<span id=\"_nb_new_messages_\"></span>
            </a>
          </li>
                        </ul>

      <!-- Tab panes -->
      <div class=\"tab-content\">
                          <div class=\"tab-pane active empty\" id=\"orders-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              No hay pedidos nuevos por ahora :(<br>
              ¿Has revisado tus <strong><a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCarts&action=filterOnlyAbandonedCarts&token=886ecc044e15aae0e3f99b55b20ec207\">carritos abandonados</a></strong>?<br>?. ¡Tu próximo pedido podría estar ocultándose allí!
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"customers-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              No hay clientes nuevos por ahora :(<br>
              ¿Se mantiene activo en las redes sociales en estos momentos?
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"messages-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              No hay mensajes nuevo por ahora.<br>
              ¡Eso significa más tiempo para otras cosas!
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                        </div>
    </div>
  </div>
</div>

  <script type=\"text/html\" id=\"order-notification-template\">
    <a class=\"notif\" href='order_url'>
      #_id_order_ -
      de <strong>_customer_name_</strong> (_iso_code_)_carrier_
      <strong class=\"float-sm-right\">_total_paid_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"customer-notification-template\">
    <a class=\"notif\" href='customer_url'>
      #_id_customer_ - <strong>_customer_name_</strong>_company_ - registrado <strong>_date_add_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"message-notification-template\">
    <a class=\"notif\" href='message_url'>
    <span class=\"message-notification-status _status_\">
      <i class=\"material-icons\">fiber_manual_record</i> _status_
    </span>
      - <strong>_customer_name_</strong> (_company_) - <i class=\"material-icons\">access_time</i> _date_add_
    </a>
  </script>
      </div>
        <div class=\"component\" id=\"header-employee-container\">
      <div class=\"dropdown employee-dropdown\">
  <div class=\"rounded-circle person\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">account_circle</i>
  </div>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <div class=\"text-center employee_avatar\">
      <img class=\"avatar rounded-circle\" src=\"http://profile.prestashop.com/hcampoverde%40eljurilicores.com.jpg\" />
      <span>Henry Camopverde Borja</span>
    </div>
    <a class=\"dropdown-item employee-link profile-link\" href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminEmployees&amp;id_employee=1&amp;updateemployee=1&amp;token=53a65bf0e89b2cd3575d07d55a328a1b\">
      <i class=\"material-icons\">settings_applications</i>
      Tu perfil
    </a>
    <a class=\"dropdown-item employee-link\" id=\"header_logout\" href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminLogin&amp;logout=1&amp;token=64832d319c80ed7b72e65deafbff9367\">
      <i class=\"material-icons\">power_settings_new</i>
      <span>Cerrar sesión</span>
    </a>
  </div>
</div>
    </div>

      </nav>
  </header>

<nav class=\"nav-bar d-none d-md-block\">
  <span class=\"menu-collapse\">
    <i class=\"material-icons\">chevron_left</i>
    <i class=\"material-icons\">chevron_left</i>
  </span>

  <ul class=\"main-menu\">

          
                
                
        
          <li class=\"link-levelone \" data-submenu=\"1\" id=\"tab-AdminDashboard\">
            <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminDashboard&amp;token=9f7961176c40f863b31a257fab2deaaf\" class=\"link\" >
              <i class=\"material-icons\">trending_up</i> <span>Inicio</span>
            </a>
          </li>

        
                
                                  
                
        
          <li class=\"category-title \" data-submenu=\"2\" id=\"tab-SELL\">
              <span class=\"title\">Vender</span>
          </li>

                          
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"3\" id=\"subtab-AdminParentOrders\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminOrders&amp;token=dbb72eaa94bdc0673a84495fc3eabd26\" class=\"link\">
                    <i class=\"material-icons mi-shopping_basket\">shopping_basket</i>
                    <span>
                    Pedidos
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-3\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"4\" id=\"subtab-AdminOrders\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminOrders&amp;token=dbb72eaa94bdc0673a84495fc3eabd26\" class=\"link\"> Pedidos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"5\" id=\"subtab-AdminInvoices\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/sell/orders/invoices/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Facturas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"6\" id=\"subtab-AdminSlip\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminSlip&amp;token=784153de81311b0b2c882ddd142f9a48\" class=\"link\"> Facturas por abono
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"7\" id=\"subtab-AdminDeliverySlip\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/sell/orders/delivery-slips/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Albaranes de entrega
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"8\" id=\"subtab-AdminCarts\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCarts&amp;token=886ecc044e15aae0e3f99b55b20ec207\" class=\"link\"> Carritos de compra
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"9\" id=\"subtab-AdminCatalog\">
                  <a href=\"/cuenca/zona-rosa/backoffice/index.php/sell/catalog/products?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\">
                    <i class=\"material-icons mi-store\">store</i>
                    <span>
                    Catálogo
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-9\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"10\" id=\"subtab-AdminProducts\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/sell/catalog/products?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Productos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"11\" id=\"subtab-AdminCategories\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCategories&amp;token=bd693f96506a2d2ac6439fd62dec44cf\" class=\"link\"> Categorías
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"12\" id=\"subtab-AdminTracking\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminTracking&amp;token=b53eee5d890c9e35fa342b597157c161\" class=\"link\"> Monitoreo
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"13\" id=\"subtab-AdminParentAttributesGroups\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminAttributesGroups&amp;token=9f590de31f9614fb8bffaa8291f13b56\" class=\"link\"> Atributos y Características
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"16\" id=\"subtab-AdminParentManufacturers\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminManufacturers&amp;token=5bfbcd26d8fc7d6fa5ac6bc2ce16ccde\" class=\"link\"> Marcas y Proveedores
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"19\" id=\"subtab-AdminAttachments\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminAttachments&amp;token=e9c1e59582f91026a664be7f4cfc0d34\" class=\"link\"> Archivos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"20\" id=\"subtab-AdminParentCartRules\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCartRules&amp;token=257afe4e77e36fefeb73c90cdf2267c7\" class=\"link\"> Descuentos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"23\" id=\"subtab-AdminStockManagement\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/sell/stocks/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Stocks
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"24\" id=\"subtab-AdminParentCustomer\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCustomers&amp;token=4df75016f3ffbbbd91855ae27fc5176d\" class=\"link\">
                    <i class=\"material-icons mi-account_circle\">account_circle</i>
                    <span>
                    Clientes
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-24\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"25\" id=\"subtab-AdminCustomers\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCustomers&amp;token=4df75016f3ffbbbd91855ae27fc5176d\" class=\"link\"> Clientes
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"26\" id=\"subtab-AdminAddresses\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminAddresses&amp;token=1175ee8962c5058a0573ebed9e1345d2\" class=\"link\"> Direcciones
                              </a>
                            </li>

                                                                                                                          </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"28\" id=\"subtab-AdminParentCustomerThreads\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCustomerThreads&amp;token=ed773bb9a7110e7244a868e23eaaf619\" class=\"link\">
                    <i class=\"material-icons mi-chat\">chat</i>
                    <span>
                    Servicio al Cliente
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-28\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"29\" id=\"subtab-AdminCustomerThreads\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCustomerThreads&amp;token=ed773bb9a7110e7244a868e23eaaf619\" class=\"link\"> Servicio al Cliente
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"30\" id=\"subtab-AdminOrderMessage\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminOrderMessage&amp;token=c9b8ab0f852fd3c951c7c68257b3e536\" class=\"link\"> Mensajes de Pedidos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"31\" id=\"subtab-AdminReturn\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminReturn&amp;token=6b8d0c9b0200649d3158e709c64b608f\" class=\"link\"> Devoluciones de mercancía
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone\" data-submenu=\"32\" id=\"subtab-AdminStats\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminStats&amp;token=b27eb783900516922144bfb17a2f2dbd\" class=\"link\">
                    <i class=\"material-icons mi-assessment\">assessment</i>
                    <span>
                    Estadísticas
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                    </li>
                          
        
                
                                  
                
        
          <li class=\"category-title \" data-submenu=\"42\" id=\"tab-IMPROVE\">
              <span class=\"title\">Personalizar</span>
          </li>

                          
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"43\" id=\"subtab-AdminParentModulesSf\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminPsMboModule&amp;token=db93ef6121e403ff80d6fc4f2bc3b929\" class=\"link\">
                    <i class=\"material-icons mi-extension\">extension</i>
                    <span>
                    Módulos
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-43\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"44\" id=\"subtab-AdminParentModulesCatalog\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminPsMboModule&amp;token=db93ef6121e403ff80d6fc4f2bc3b929\" class=\"link\"> Catálogo de Módulos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"47\" id=\"subtab-AdminModulesSf\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/modules/manage?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Module Manager
                              </a>
                            </li>

                                                                                                                          </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"52\" id=\"subtab-AdminParentThemes\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminThemes&amp;token=c96087ca6589777f9ea2b9b651d17909\" class=\"link\">
                    <i class=\"material-icons mi-desktop_mac\">desktop_mac</i>
                    <span>
                    Diseño
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-52\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"123\" id=\"subtab-AdminThemesParent\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminThemes&amp;token=c96087ca6589777f9ea2b9b651d17909\" class=\"link\"> Tema y logotipo
                              </a>
                            </li>

                                                                                                                              
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"130\" id=\"subtab-AdminPsMboTheme\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminPsMboTheme&amp;token=a980af68cd476b180e311b9d58437384\" class=\"link\"> Catálogo de Temas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"55\" id=\"subtab-AdminCmsContent\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCmsContent&amp;token=2f04700ad7bcccaa22621fb0c2120e3c\" class=\"link\"> Páginas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"56\" id=\"subtab-AdminModulesPositions\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/design/modules/positions/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Posiciones
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"57\" id=\"subtab-AdminImages\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminImages&amp;token=db69585e6e4cc51e90e74a493a8c2a15\" class=\"link\"> Ajustes de imágenes
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"122\" id=\"subtab-AdminLinkWidget\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminLinkWidget&amp;token=1e0f23ee33de464e02705b5288757efa\" class=\"link\"> Link Widget
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"58\" id=\"subtab-AdminParentShipping\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCarriers&amp;token=b1183276aeff97d79ada0d152fabd371\" class=\"link\">
                    <i class=\"material-icons mi-local_shipping\">local_shipping</i>
                    <span>
                    Transporte
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-58\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"59\" id=\"subtab-AdminCarriers\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminCarriers&amp;token=b1183276aeff97d79ada0d152fabd371\" class=\"link\"> Transportistas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"60\" id=\"subtab-AdminShipping\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/shipping/preferences?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Preferencias
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"61\" id=\"subtab-AdminParentPayment\">
                  <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/payment/payment_methods?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\">
                    <i class=\"material-icons mi-payment\">payment</i>
                    <span>
                    Pago
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-61\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"62\" id=\"subtab-AdminPayment\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/payment/payment_methods?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Métodos de pago
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"63\" id=\"subtab-AdminPaymentPreferences\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/payment/preferences?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Preferencias
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"64\" id=\"subtab-AdminInternational\">
                  <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/international/localization/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\">
                    <i class=\"material-icons mi-language\">language</i>
                    <span>
                    Internacional
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-64\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"65\" id=\"subtab-AdminParentLocalization\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/international/localization/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Localización
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"70\" id=\"subtab-AdminParentCountries\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminZones&amp;token=d0502f7e5295ef5c3b1acca2df07d6cb\" class=\"link\"> Ubicaciones Geográficas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"74\" id=\"subtab-AdminParentTaxes\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminTaxes&amp;token=26c90846b2d71e755431421a5ce2559d\" class=\"link\"> Impuestos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"77\" id=\"subtab-AdminTranslations\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/improve/international/translations/settings?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Traducciones
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                          
        
                
                                  
                
        
          <li class=\"category-title -active\" data-submenu=\"78\" id=\"tab-CONFIGURE\">
              <span class=\"title\">Configurar</span>
          </li>

                          
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"79\" id=\"subtab-ShopParameters\">
                  <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/shop/preferences/preferences?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\">
                    <i class=\"material-icons mi-settings\">settings</i>
                    <span>
                    Parámetros de la tienda
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-79\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"80\" id=\"subtab-AdminParentPreferences\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/shop/preferences/preferences?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Configuración
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"83\" id=\"subtab-AdminParentOrderPreferences\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/shop/order-preferences/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Configuración de Pedidos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"86\" id=\"subtab-AdminPPreferences\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/shop/product-preferences/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Configuración de Productos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"87\" id=\"subtab-AdminParentCustomerPreferences\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/shop/customer-preferences/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Ajustes sobre clientes
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"91\" id=\"subtab-AdminParentStores\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminContacts&amp;token=5f210f6c786f93af84b8a25be8745e85\" class=\"link\"> Contacto
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"94\" id=\"subtab-AdminParentMeta\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/shop/seo-urls/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Tráfico &amp; SEO
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"98\" id=\"subtab-AdminParentSearchConf\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminSearchConf&amp;token=693b0444e21c2070b45ba94f84093a14\" class=\"link\"> Buscar
                              </a>
                            </li>

                                                                                                                          </ul>
                                    </li>
                                        
                
                                                
                                                    
                <li class=\"link-levelone has_submenu -active open ul-open\" data-submenu=\"101\" id=\"subtab-AdminAdvancedParameters\">
                  <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/system-information/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\">
                    <i class=\"material-icons mi-settings_applications\">settings_applications</i>
                    <span>
                    Parámetros Avanzados
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_up
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-101\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"102\" id=\"subtab-AdminInformation\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/system-information/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Información
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"103\" id=\"subtab-AdminPerformance\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/performance/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Rendimiento
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"104\" id=\"subtab-AdminAdminPreferences\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/administration/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Administración
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo -active\" data-submenu=\"105\" id=\"subtab-AdminEmails\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Dirección de correo electrónico
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"106\" id=\"subtab-AdminImport\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/import/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Importar
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"107\" id=\"subtab-AdminParentEmployees\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminEmployees&amp;token=53a65bf0e89b2cd3575d07d55a328a1b\" class=\"link\"> Equipo
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"111\" id=\"subtab-AdminParentRequestSql\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminRequestSql&amp;token=bf8d6210cf3832d97699cb87f795bd60\" class=\"link\"> Base de datos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"114\" id=\"subtab-AdminLogs\">
                              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/logs/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" class=\"link\"> Registros/Logs
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"115\" id=\"subtab-AdminWebservice\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminWebservice&amp;token=9ffda88d25be911300ba27b175e32767\" class=\"link\"> Webservice
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"116\" id=\"subtab-AdminShopGroup\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminShopGroup&amp;token=74ec783020ef562d59ae34c77c993257\" class=\"link\"> Multitienda
                              </a>
                            </li>

                                                                                                                          </ul>
                                    </li>
                          
        
                
                                  
                
        
          <li class=\"category-title \" data-submenu=\"133\" id=\"tab-AdminSmartBlogMenu\">
              <span class=\"title\">SMARTBLOG</span>
          </li>

                          
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"134\" id=\"subtab-AdminSmartBlog\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminBlogCategory&amp;token=9bb63cf41ccf9e71525cdc30669b4762\" class=\"link\">
                    <i class=\"material-icons mi-content_paste\">content_paste</i>
                    <span>
                    Blog
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-134\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"135\" id=\"subtab-AdminBlogCategory\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminBlogCategory&amp;token=9bb63cf41ccf9e71525cdc30669b4762\" class=\"link\"> Blog Category
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"136\" id=\"subtab-AdminBlogcomment\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminBlogcomment&amp;token=4d2ed230e70f740b9eb050059bc76b39\" class=\"link\"> Blog Comments
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"137\" id=\"subtab-AdminBlogPost\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminBlogPost&amp;token=c4411bc0f7979c2970b10258486db146\" class=\"link\"> Blog Post
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"138\" id=\"subtab-AdminImageType\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminImageType&amp;token=b3bbf7819c55424833d0dcfe25fc9554\" class=\"link\"> Image Type
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"139\" id=\"subtab-AdminAboutUs\">
                              <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminAboutUs&amp;token=e2b02405d633a11cc196b8ba4ae32d57\" class=\"link\"> AboutUs
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                          
        
                
                                  
                
        
          <li class=\"category-title \" data-submenu=\"140\" id=\"tab-AdminRVTemplates\">
              <span class=\"title\">RV Templates</span>
          </li>

                          
                
                                                
                
                <li class=\"link-levelone\" data-submenu=\"141\" id=\"subtab-Adminrvcustomsetting\">
                  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=Adminrvcustomsetting&amp;token=1dad375b73c20d2150d978e7a7e0fb86\" class=\"link\">
                    <i class=\"material-icons mi-extension\">extension</i>
                    <span>
                    Custom Setting
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                    </li>
                          
        
            </ul>
  
</nav>

<div id=\"main-div\">

  
    
<div class=\"header-toolbar\">
  <div class=\"container-fluid\">

    
      <nav aria-label=\"Breadcrumb\">
        <ol class=\"breadcrumb\">
                      <li class=\"breadcrumb-item\">Parámetros Avanzados</li>
          
                      <li class=\"breadcrumb-item active\">
              <a href=\"/cuenca/zona-rosa/backoffice/index.php/configure/advanced/emails/?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\" aria-current=\"page\">Dirección de correo electrónico</a>
            </li>
                  </ol>
      </nav>
    

    <div class=\"title-row\">
      
          <h1 class=\"title\">
            Dirección de correo electrónico          </h1>
      

      
        <div class=\"toolbar-icons\">
          <div class=\"wrapper\">
             

<script>
    
    var isSymfonyContext = true;
    var admin_module_ajax_url_psmbo = 'http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminPsMboModule&token=db93ef6121e403ff80d6fc4f2bc3b929';
    var controller = 'AdminEmails';
    
    if (isSymfonyContext === false) {
        
        \$(document).ready(function() {
            
            \$('.process-icon-modules-list').parent('a').prop('href', admin_module_ajax_url_psmbo);
            
            \$('.fancybox-quick-view').fancybox({
                type: 'ajax',
                autoDimensions: false,
                autoSize: false,
                width: 600,
                height: 'auto',
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            });
        });
    }
\t
\t\$(document).on('click', '#page-header-desc-configuration-modules-list', function(event) {
\t\tevent.preventDefault();
\t\topenModalOrRedirect(isSymfonyContext);
\t});
\t
\t\$('.process-icon-modules-list').parent('a').unbind().bind('click', function (event) {
\t\tevent.preventDefault();
\t\topenModalOrRedirect(isSymfonyContext);
\t});
    
    function openModalOrRedirect(isSymfonyContext) {
        if (isSymfonyContext === false) {
            \$('#modules_list_container').modal('show');
            openModulesList();
        } else {
            window.location.href = admin_module_ajax_url_psmbo;
        }
    }
\t
    function openModulesList() {
        \$.ajax({
            type: 'POST',
            url: admin_module_ajax_url_psmbo,
            data: {
                ajax : true,
                action : 'GetTabModulesList',
                controllerName: controller
            },
            success : function(data) {
                \$('#modules_list_container_tab_modal').html(data).slideDown();
                \$('#modules_list_loader').hide();
            },
        });
    }
\t
\t
</script>

                                                                              <a
                class=\"btn btn-outline-secondary \"
                id=\"page-header-desc-configuration-modules-list\"
                href=\"/cuenca/zona-rosa/backoffice/index.php/improve/modules/catalog?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\"                title=\"Módulos recomendados\"
                              >
                Módulos recomendados
              </a>
            
            
                              <a class=\"btn btn-outline-secondary btn-help btn-sidebar\" href=\"#\"
                   title=\"Ayuda\"
                   data-toggle=\"sidebar\"
                   data-target=\"#right-sidebar\"
                   data-url=\"/cuenca/zona-rosa/backoffice/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop.com%252Fes%252Fdoc%252FAdminEmails%253Fversion%253D1.7.5.0%2526country%253Des/Ayuda?_token=1MUCa1jQjAI6gcOXCRP4_UtuYYfCwY-Z7PuYGKT7LWc\"
                   id=\"product_form_open_help\"
                >
                  Ayuda
                </a>
                                    </div>
        </div>
      
    </div>
  </div>

  
    
</div>
    
    <div class=\"content-div  \">

      

      
                        
      <div class=\"row \">
        <div class=\"col-sm-12\">
          <div id=\"ajax_confirmation\" class=\"alert alert-success\" style=\"display: none;\"></div>


  ";
        // line 1216
        $this->displayBlock('content_header', $context, $blocks);
        // line 1217
        echo "                 ";
        $this->displayBlock('content', $context, $blocks);
        // line 1218
        echo "                 ";
        $this->displayBlock('content_footer', $context, $blocks);
        // line 1219
        echo "                 ";
        $this->displayBlock('sidebar_right', $context, $blocks);
        // line 1220
        echo "
           
<div class=\"modal fade\" id=\"modules_list_container\">
\t<div class=\"modal-dialog\">
\t\t<div class=\"modal-content\">
\t\t\t<div class=\"modal-header\">
\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
\t\t\t\t<h3 class=\"modal-title\">Módulos y Servicios recomendados</h3>
\t\t\t</div>
\t\t\t<div class=\"modal-body\">
\t\t\t\t<div id=\"modules_list_container_tab_modal\" style=\"display:none;\"></div>
\t\t\t\t<div id=\"modules_list_loader\"><i class=\"icon-refresh icon-spin\"></i></div>
\t\t\t</div>
\t\t</div>
\t</div>
</div>
        </div>
      </div>

    </div>

  
</div>

<div id=\"non-responsive\" class=\"js-non-responsive\">
  <h1>¡Oh no!</h1>
  <p class=\"mt-3\">
    La versión para móviles de esta página no está disponible todavía.
  </p>
  <p class=\"mt-2\">
    Por favor, utiliza un ordenador de escritorio hasta que esta página sea adaptada para dispositivos móviles.
  </p>
  <p class=\"mt-2\">
    Gracias.
  </p>
  <a href=\"http://localhost/cuenca/zona-rosa/backoffice/index.php?controller=AdminDashboard&amp;token=9f7961176c40f863b31a257fab2deaaf\" class=\"btn btn-primary py-1 mt-3\">
    <i class=\"material-icons\">arrow_back</i>
    Atrás
  </a>
</div>
<div class=\"mobile-layer\"></div>

  <div id=\"footer\" class=\"bootstrap\">
    
</div>


  <div class=\"bootstrap\">
    <div class=\"modal fade\" id=\"modal_addons_connect\" tabindex=\"-1\">
\t<div class=\"modal-dialog modal-md\">
\t\t<div class=\"modal-content\">
\t\t\t\t\t\t<div class=\"modal-header\">
\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
\t\t\t\t<h4 class=\"modal-title\"><i class=\"icon-puzzle-piece\"></i> <a target=\"_blank\" href=\"https://addons.prestashop.com/?utm_source=back-office&utm_medium=modules&utm_campaign=back-office-ES&utm_content=download\">PrestaShop Addons</a></h4>
\t\t\t</div>
\t\t\t
\t\t\t<div class=\"modal-body\">
\t\t\t\t\t\t<!--start addons login-->
\t\t\t<form id=\"addons_login_form\" method=\"post\" >
\t\t\t\t<div>
\t\t\t\t\t<a href=\"https://addons.prestashop.com/es/login?email=hcampoverde%40eljurilicores.com&amp;firstname=Henry&amp;lastname=Camopverde+Borja&amp;website=http%3A%2F%2Flocalhost%2Fcuenca%2Fzona-rosa%2F&amp;utm_source=back-office&amp;utm_medium=connect-to-addons&amp;utm_campaign=back-office-ES&amp;utm_content=download#createnow\"><img class=\"img-responsive center-block\" src=\"/cuenca/zona-rosa/backoffice/themes/default/img/prestashop-addons-logo.png\" alt=\"Logo PrestaShop Addons\"/></a>
\t\t\t\t\t<h3 class=\"text-center\">Conecta tu tienda con el mercado de PrestaShop para importar automáticamente todas tus compras de Addons.</h3>
\t\t\t\t\t<hr />
\t\t\t\t</div>
\t\t\t\t<div class=\"row\">
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<h4>¿No tienes una cuenta?</h4>
\t\t\t\t\t\t<p class='text-justify'>¡Descubre el poder de PrestaShop Addons! Explora el Marketplace oficial de PrestaShop y encuentra más de 3.500 módulos y temas innovadores que optimizan las tasas de conversión, aumentan el tráfico, fidelizan a los clientes y maximizan tu productividad</p>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<h4>Conectarme a PrestaShop Addons</h4>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">
\t\t\t\t\t\t\t\t\t<span class=\"input-group-text\"><i class=\"icon-user\"></i></span>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<input id=\"username_addons\" name=\"username_addons\" type=\"text\" value=\"\" autocomplete=\"off\" class=\"form-control ac_input\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">
\t\t\t\t\t\t\t\t\t<span class=\"input-group-text\"><i class=\"icon-key\"></i></span>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<input id=\"password_addons\" name=\"password_addons\" type=\"password\" value=\"\" autocomplete=\"off\" class=\"form-control ac_input\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<a class=\"btn btn-link float-right _blank\" href=\"//addons.prestashop.com/es/forgot-your-password\">He olvidado mi contraseña</a>
\t\t\t\t\t\t\t<br>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t\t<div class=\"row row-padding-top\">
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<a class=\"btn btn-default btn-block btn-lg _blank\" href=\"https://addons.prestashop.com/es/login?email=hcampoverde%40eljurilicores.com&amp;firstname=Henry&amp;lastname=Camopverde+Borja&amp;website=http%3A%2F%2Flocalhost%2Fcuenca%2Fzona-rosa%2F&amp;utm_source=back-office&amp;utm_medium=connect-to-addons&amp;utm_campaign=back-office-ES&amp;utm_content=download#createnow\">
\t\t\t\t\t\t\t\tCrear una Cuenta
\t\t\t\t\t\t\t\t<i class=\"icon-external-link\"></i>
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<button id=\"addons_login_button\" class=\"btn btn-primary btn-block btn-lg\" type=\"submit\">
\t\t\t\t\t\t\t\t<i class=\"icon-unlock\"></i> Iniciar sesión
\t\t\t\t\t\t\t</button>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t\t<div id=\"addons_loading\" class=\"help-block\"></div>

\t\t\t</form>
\t\t\t<!--end addons login-->
\t\t\t</div>


\t\t\t\t\t</div>
\t</div>
</div>

  </div>

";
        // line 1343
        $this->displayBlock('javascripts', $context, $blocks);
        $this->displayBlock('extra_javascripts', $context, $blocks);
        $this->displayBlock('translate_javascripts', $context, $blocks);
        echo "</body>
</html>";
    }

    // line 81
    public function block_stylesheets($context, array $blocks = array())
    {
    }

    public function block_extra_stylesheets($context, array $blocks = array())
    {
    }

    // line 1216
    public function block_content_header($context, array $blocks = array())
    {
    }

    // line 1217
    public function block_content($context, array $blocks = array())
    {
    }

    // line 1218
    public function block_content_footer($context, array $blocks = array())
    {
    }

    // line 1219
    public function block_sidebar_right($context, array $blocks = array())
    {
    }

    // line 1343
    public function block_javascripts($context, array $blocks = array())
    {
    }

    public function block_extra_javascripts($context, array $blocks = array())
    {
    }

    public function block_translate_javascripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "__string_template__c97b5ed4a6c682d65378cdb3117342bbbbfe1ae2978aea0f600c57a9723973f2";
    }

    public function getDebugInfo()
    {
        return array (  1422 => 1343,  1417 => 1219,  1412 => 1218,  1407 => 1217,  1402 => 1216,  1393 => 81,  1385 => 1343,  1260 => 1220,  1257 => 1219,  1254 => 1218,  1251 => 1217,  1249 => 1216,  110 => 81,  28 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "__string_template__c97b5ed4a6c682d65378cdb3117342bbbbfe1ae2978aea0f600c57a9723973f2", "");
    }
}
