<!doctype html>
    <html lang="{$language.iso_code}">

    <head>
      {block name='head'}
        {include file='_partials/head.tpl'}
      {/block}
    </head>

    <body id="{$page.page_name}" class="{$page.body_classes|classnames}">

      {hook h='displayAfterBodyOpeningTag'}

      <main>

        <header data-html2canvas-ignore id="header">
          {block name='header'}
            {include file='_partials/header.tpl'}
          {/block}
        </header>
        <aside id="notifications">
            <div class="container">
            </div>
        </aside>
        <section id="top_column">
          <div class="container">


            
            {block name="content"}
                <div style="display:block; margin:0px auto;" class="grid_9 alpha omega clearfix">
                    
                    {*
                    <div class="banner-cotiza row" style="text-align:center">
                        <img class="img-responsive" src="../../modules/cotizadortaberna/images/cotizador.png">
                    </div> *}
                    <br>
                    
                    {*<button id="btn_iniciar_sesion">OK</button>
                    <button id="btn_add_to_cart">Error</button>
                    <input id="txt_cart_id" type="text" placeholder="cart_id" name="txt_cart_id">
                    <button id="btn_get_cart">Get Cart</button>
                    <button id="btn_cerrar_sesion">Cerrar Sesión</button>*}
                    
                    

                    <h1 id="title-modulo1" class="title-modulo">COTIZACIÓN:</h1>
                    
                                <!--h4>Invitados</h4-->
                                <div class="box011 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <span class="step-cotizador badge badge-secondary">1</span>
                                    <h4>Número de invitados</h4>
                                    <input class="invita-oao" type="number" required id="invitados" value="" placeholder="# de invitados">
                                </div>
                                <div class="box011 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <span class="step-cotizador badge badge-secondary">2</span>
                                    <h4>Tipo de licores</h4>
                                    <div class='row row-check-licores'>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <input type="checkbox" id="champan" value="Champaña">
                                            <label class="label-cotizador" for="champan">Champaña</label>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <input type="checkbox" id="vino" value="vino">          
                                            <label class="label-cotizador" for="vino">Vino</label>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <input type="checkbox" id="whiskey" value="Whiskey">
                                            <label class="label-cotizador" for="whiskey">Whisky</label>

                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <input type="checkbox" id="ron" value="Ron">
                                            <label class="label-cotizador" for="ron">Ron</label>

                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <input type="checkbox" id="gin" value="Gin">
                                            <label class="label-cotizador" for="gin">Gin</label>

                                        </div>
                                    </div>
                                </div>
                                <div data-html2canvas-ignore class="box011 box010 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <span class="step-cotizador badge badge-secondary">3</span>
                                    <h4>Cotiza tu pedido</h4>
                                    <button id="buscar" class="button nuevo-co">COTIZAR</button>
                                </div>
                </div>


                <div id="contenido_cotizador_imprimir" style='text-align: center; display: none;'>
                    <table border="1">
                        <tr>
                            <td>Cantidad</td>
                            <td>Categoría</td>
                            <td>Producto</td>
                            <td>Descripción</td>
                            <td>Precio</td>
                            <td>Total</td>
                        </tr>
                        <tr id='registro_champana_imprimir' style='display: none;'>
                            <td id='cantidad_champana_imprimir'></td>
                            <td id='categoria_champana_imprimir'>Champaña</td>
                            <td id='producto_champana_imprimir'><img src="" width='100px' height='120px'></td>
                            <td id='descripcion_champana_imprimir'></td>
                            <td id='precio_champana_imprimir'></td>
                            <td id='total_champana_imprimir'></td>
                        </tr>
                        <tr id='registro_vinotinto_imprimir' style='display: none;'>
                            <td id='cantidad_vinotinto_imprimir'></td>
                            <td id='categoria_vinotinto_imprimir'>Vino tinto</td>
                            <td id='producto_vinotinto_imprimir'><img src="" width='100px' height='120px'></td>
                            <td id='descripcion_vinotinto_imprimir'></td>
                            <td id='precio_vinotinto_imprimir'></td>
                            <td id='total_vinotinto_imprimir'></td>
                        </tr>
                        <tr id='registro_vinoblanco_imprimir' style='display: none;'>
                            <td id='cantidad_vinoblanco_imprimir'></td>
                            <td id='categoria_vinoblanco_imprimir'>Vino blanco</td>
                            <td id='producto_vinoblanco_imprimir'><img src="" width='100px' height='120px'></td>
                            <td id='descripcion_vinoblanco_imprimir'></td>
                            <td id='precio_vinoblanco_imprimir'></td>
                            <td id='total_vinoblanco_imprimir'></td>
                        </tr>
                        <tr id='registro_whisky_imprimir' style='display: none;'>
                            <td id='cantidad_whisky_imprimir'></td>
                            <td id='categoria_whisky_imprimir'>Whisky</td>
                            <td id='producto_whisky_imprimir'><img src="" width='100px' height='120px'></td>
                            <td id='descripcion_whisky_imprimir'></td>
                            <td id='precio_whisky_imprimir'></td>
                            <td id='total_whisky_imprimir'></td>
                        </tr>
                        <tr id='registro_ron_imprimir' style='display: none;'>
                            <td id='cantidad_ron_imprimir'></td>
                            <td id='categoria_ron_imprimir'>Ron</td>
                            <td id='producto_ron_imprimir'><img src="" width='100px' height='120px'></td>
                            <td id='descripcion_ron_imprimir'></td>
                            <td id='precio_ron_imprimir'></td>
                            <td id='total_ron_imprimir'></td>
                        </tr>
                        <tr id='registro_gin_imprimir' style='display: none;'>
                            <td id='cantidad_gin_imprimir'></td>
                            <td id='categoria_gin_imprimir'>Gin</td>
                            <td id='producto_gin_imprimir'><img src="" width='100px' height='120px'></td>
                            <td id='descripcion_gin_imprimir'></td>
                            <td id='precio_gin_imprimir'></td>
                            <td id='total_gin_imprimir'></td>
                        </tr>
                        <tr id='registro_total_imprimir'>
                            <td colspan='6' style='text-align: right;'>
                                TOTAL:
                                <span id='total_imprimir' colspan='6' style='text-align: right;'></span>
                            </td>
                        </tr>
                    </table>
                </div>


                <div id='contenedor_de_licores_seleccionados' style='display: none;'>
                    <div id="pdf-cotizacion" class="table-responsive">
                        <table style='text-align: center' class="table-producto-cotiza table">
                            <thead>
                                <tr>
                                    <td>Cantidad</td>
                                    <td class="hidden-xs-down">Categoría</td>
                                    <td>Producto</td>
                                    <td class="hidden-xs-down">Descripción</td>
                                    <td>Precio</td>
                                    <td>Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id='registro_champana' >
                                    <td id='cantidad_champana_cotiza'>
                                        <span id="valChampan"></span>
                                    </td>
                                    <td class="hidden-xs-down" id='categoria_champana_cotiza'>
                                        <b>Champaña</b>
                                    </td>
                                    <td id='foto_champana_cotiza' class="td_foto">
                                        <img src="../../img/404.gif" style='width: 50px; height: 50px;'>
                                        <div data-html2canvas-ignore id="showChampan" style="float: right; display: none;">
                                            <a title='Seleccione su Champaña' class='inline selecciona' id='selecciona_champana' href="#popup_licores">
                                                <img src="/modules/cotizadortaberna/images/ver-producto.png" />
                                            </a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs-down" id='descripcion_champana_cotiza'>

                                    </td>
                                    <td id='precio_champana_cotiza'>

                                    </td>

                                    <td id='total_champana_cotiza'>
                                        <span>$</span><div style='display: inline;'>0</div>
                                    </td>
                                </tr>
                                <tr id='registro_vino_tinto'>
                                    <td id='cantidad_vino_tinto_cotiza'>
                                        <span id="valVinoT"></span>
                                    </td>
                                    <td class="hidden-xs-down" id='categoria_vino_tinto_cotiza'>
                                        <b>Vino tinto</b>
                                    </td>
                                    <td id='foto_vino_tinto_cotiza' class="td_foto">
                                        <img src="../../img/404.gif" style='width: 50px; height: 50px;'>
                                        <div data-html2canvas-ignore id="showVinoT" style="float: right; display: none;">
                                            <a title='Seleccione su Vino tinto' class='inline selecciona' id='selecciona_vino_tinto' href="#popup_licores">
                                                <img src="/modules/cotizadortaberna/images/ver-producto.png" style=''/>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs-down" id='descripcion_vino_tinto_cotiza'>

                                    </td>
                                    <td id='precio_vino_tinto_cotiza'>

                                    </td>

                                    <td id='total_vino_tinto_cotiza'>
                                        <span>$</span><div style='display: inline;'>0</div>
                                    </td>
                                </tr>
                                <tr id='registro_vino_blanco'>
                                    <td id='cantidad_vino_blanco_cotiza'>
                                        <span id="valVinoB"></span>
                                    </td>
                                    <td class="hidden-xs-down" id='categoria_vino_blanco_cotiza'>
                                        <b>Vino blanco</b>
                                    </td>
                                    <td id='foto_vino_blanco_cotiza' class="td_foto">
                                        <img src="../../img/404.gif" style='width: 50px; height: 50px;'>
                                        <div data-html2canvas-ignore id="showVinoB" style="float: right; display: none;">
                                            <a title='Seleccione su Vino blanco' class='inline selecciona' id='selecciona_vino_blanco' href="#popup_licores">
                                                <img src="/modules/cotizadortaberna/images/ver-producto.png" style=''/>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs-down" id='descripcion_vino_blanco_cotiza'>

                                    </td>
                                    <td id='precio_vino_blanco_cotiza'>

                                    </td>

                                    <td id='total_vino_blanco_cotiza'>
                                        <span>$</span><div style='display: inline;'>0</div>
                                    </td>
                                </tr>
                                <tr id='registro_whisky'>
                                    <td id='cantidad_whisky_cotiza'>
                                        <span id="valWhisky"></span>
                                    </td>
                                    <td class="hidden-xs-down" id='categoria_whisky_cotiza'>
                                        <b>Whisky</b>
                                    </td>
                                    <td id='foto_whisky_cotiza' class="td_foto">
                                        <img src="../../img/404.gif" style='width: 50px; height: 50px;'>
                                        <div data-html2canvas-ignore id="showWhisky" style="float: right; display: none;">
                                            <a title='Seleccione su Whisky' class='inline selecciona' id='selecciona_whisky' href="#popup_licores">
                                                <img src="/modules/cotizadortaberna/images/ver-producto.png" style=''/>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs-down" id='descripcion_whisky_cotiza'>

                                    </td>
                                    <td id='precio_whisky_cotiza'>

                                    </td>

                                    <td id='total_whisky_cotiza'>
                                        <span>$</span><div style='display: inline;'>0</div>
                                    </td>
                                </tr>
                                <tr id='registro_ron'>
                                    <td id='cantidad_ron_cotiza'>
                                        <span id="valRon"></span>
                                    </td>
                                    <td class="hidden-xs-down" id='categoria_ron_cotiza'>
                                        <b>Ron</b>
                                    </td>
                                    <td id='foto_ron_cotiza' class="td_foto">
                                        <img src="../../img/404.gif" style='width: 50px; height: 50px;'>
                                        <div data-html2canvas-ignore id="showRon" style="float: right; display: none;">
                                            <a title='Seleccione su Ron' class='inline selecciona' id='selecciona_ron' href="#popup_licores">
                                                <img src="/modules/cotizadortaberna/images/ver-producto.png" style=''/>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs-down" id='descripcion_ron_cotiza'>

                                    </td>
                                    <td id='precio_ron_cotiza'>

                                    </td>

                                    <td id='total_ron_cotiza'>
                                        <span>$</span><div style='display: inline;'>0</div>
                                    </td>
                                </tr>
                                <tr id='registro_gin'>
                                    <td id='cantidad_gin_cotiza'>
                                        <span id="valGin"></span>
                                    </td>
                                    <td class="hidden-xs-down"  id='categoria_gin_cotiza'>
                                        <b>Gin</b>
                                    </td>
                                    <td id='foto_gin_cotiza' class="td_foto">
                                        <img src="../../img/404.gif" style='width: 50px; height: 50px;'>
                                        <div data-html2canvas-ignore id="showGin" style="float: right; display: none;">
                                            <a title='Seleccione su Gin' class='inline selecciona' id='selecciona_gin' href="#popup_licores">
                                                <img src="/modules/cotizadortaberna/images/ver-producto.png" style=''/>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs-down" id='descripcion_gin_cotiza'>

                                    </td>
                                    <td id='precio_gin_cotiza'>

                                    </td>

                                    <td id='total_gin_cotiza'>
                                        <span>$</span><div style='display: inline;'>0</div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td class="hidden-xs-down"></td>
                                    <td></td>
                                    <td class="hidden-xs-down"></td>
                                    <td></td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style=''><h2>TOTAL</h2></td>
                                            </tr>
                                            <tr>
                                                <td id='total_cotiza' style='font-size: 24px; font-weight: normal; border: 1px solid #565656;'>
                                                    0
                                                </td>
                                            </tr>
                                            
                                        </table>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div data-html2canvas-ignore class="col-xs-12 col-lg-12">
                            <p>
                                <button class="btn-cotizacion btn btn-lg btn-danger" data-toggle="collapse" href="#contenedorEmail" role="button">
                                    <i class="material-icons">description</i> 
                                    ENVIAR COTIZACIÓN A EMAIL
                                </button>
                                <button class="btn-cotizacion btn btn-lg btn-danger" id="descargar_cotizacion">
                                    <i class="material-icons">picture_as_pdf</i>
                                    DESCARGAR LA COTIZACIÓN
                                </button>
                            </p>
                            <div class="row" style="padding: 0px 16px;">
                              <div class="col">
                                <div class="collapse multi-collapse" id="contenedorEmail">
                                  <div class="">
                                    
                                    <div id='estado_enviando' style='visibility: hidden;height: 30px;'>
                                        <img src="{$base_dir_ssl}img/spiner.gif" style='margin-left: 60px;'>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4>Enviar email a:</h4>
                                            <input type='email' required  placeholder='Ingresa email' id='email_destino_cotiza' value="{$email}" style='height: 30px; width: 200px;'>  
                                        </div>
                                    </div>
                                    <div id='verificacion_envio_email' style='padding: 7px;height: 30px;'></div>

                                    <button id="btnEnviarEmailModal" type="button" class="btn btn-secondary">Enviar</button>

                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div data-html2canvas-ignore class="col-xs-12 col-lg-12">
                            
                        </div>
                    </div>
                    <!--table style='margin-left: 76%;'>
                        <tr>
                            <td><a id='imprimir_cotiza' class='a-cot' style=' display:none;'><img src="../../modules/blockmenuinf/images/print.png"></a>
                                <a class='inline_email' href='#destino_envio_cotizador' id='enviar_email_cotiza'><img src="../../modules/blockmenuinf/images/mail.png"></a>
                            </td>
                        </tr>

                    </table-->
                </div>
                </div>


                <div style='display: none;'>
                    <div id='destino_envio_cotizador' style='font-size: 16px;'>
                        <div id='estado_enviando' style='display: none;'>
                            <img src="{$base_dir_ssl}img/spiner.gif" style='margin-left: 60px;'><span style='font-size: 12px'> Enviando...</span>
                        </div>
                        <div>
                            <form id='form_envio_cotizador'>
                                <table style='border-collapse: separate; border-spacing: 20px;'>
                                    {if $email == 'none' || !$email}
                                        <tr>
                                            <td>
                                                <label for='email_destino_cotiza2'>Ingrese su email:</label>
                                            </td>
                                            <td>
                                                <input type='email' required  placeholder='Email válido' id='email_destino_cotiza2' style='height: 30px; width: 200px;'>         
                                            </td>
                                        </tr>
                                    {else}
                                        <tr>
                                            <td>
                                                EL cotizador será enviado a su email: 
                                                <br>

                                                <input type='hidden' value='{$email}' id='email_destino_cotiza2'>            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div style='font-weight: bold;color: red;font-weight: bold;'>{$email}</div>
                                            </td>
                                        </tr>
                                    {/if}
                                    <tr>
                                        <td>
                                            <label for='email_destino_cotiza'>Ingrese su nombre:</label>
                                        </td>
                                        <td>
                                            <input type='text' required  placeholder='Nombre' id='nombre_cotiza' style='height: 30px; width: 200px;'>           
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for='email_destino_cotiza'>Ingrese su telefono:</label>
                                        </td>
                                        <td>
                                            <input type='text' required  placeholder='Telefono' id='telefono_destino_cotiza' style='height: 30px; width: 200px;'>           
                                        </td>
                                    </tr>
                                </table>
                                <input type='submit' id='enviar_cotizador_email_destino' value='ENVIAR' style='border-color: red;width: 100px;height: 30px;bottom: 0px;right: 0px;position: absolute;'>
                                <div id='verificacion_envio_email' style='width: 100%; height: 50px;'>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                {* ========LISTA DE LICORES PARA SELECCIONAR======= *}

                <div id="location-modal-cotizador" class="modal fade" tabindex="-1" role="dialog" data-show="true" data-keyboard="false">
                    <div class="modal-dialog modal-lg modal-taberna" role="document">
                        <div class="modal-content">
                            <div class="modal-header modal-header-tienda">
                                <div class="modal-body modal-body-tienda">
                                    <div class="input-wrapper">
                                        <input id="search_query_cotizador" class="search_query_cotizador form-control" type="text" name="search_query" placeholder="¿Qué licor buscas?" value="" autocomplete="off">
                                    </div>

                                    <div class='titulo_seleccionar_licor'>
                                        <h3 class="titulo-custom">
                                            Selecciona tu <span class='nombre_licor'></span>
                                        </h3>
                                    </div>
                                    <div id="slider1">
                                        
                                        <div class="viewport ">
                                            <div id='cargando_productos' style='display: none;'><img src="{$base_dir_ssl}img/spiner.gif"></div>
                                            <ul class="overview carousel_de_licores_seleccionar"></ul>
                                        </div>
                                        <a class="buttons prev" href="#">&#60;</a>
                                        <a class="buttons next" href="#">&#62;</a>
                                    </div>

                                    <div class='cantidad_products_cotizador' style="display: none;">
                                        <h5 class="titulo-cantidad-productos-cotizador">
                                            <span id="cantidad_products_cotizador"></span> productos encontrados.
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="btnCancelarModal" type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Cancelar' d='cotizadortaberna'}</button>
                            </div>
                        </div>
                    </div>
                </div>


                {* ===========MODAL COTIZACIÓN============= *}

                <div data-html2canvas-ignore id="location-modal-enviar" class="modal fade" tabindex="-1" role="dialog" data-show="true" data-keyboard="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header modal-header-tienda">
                                <div class="modal-body modal-body-tienda">
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                
                                <button id="btnCancelarModal" type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Cancelar' d='cotizadortaberna'}</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div style="display:none">



                    <div id="popup_licores" style=''>
                        <div id='cargando_productos' style='display: none;'><img src="{$base_dir_ssl}img/spiner.gif"> <span> Cargando...</span></div>
                        <div class='titulo_seleccionar_licor'>
                            <h3 class="titulo-custom">Seleccione su <span class='nombre_licor'></span></h3>
                        </div>
                        <div>
                            <div id="slider_seleccionar_licores" >
                                <a class="buttons prev" href="#">left</a>


                                <div class="viewport">

                                    <ul class="overview carousel_de_licores_seleccionar" >
                                    </ul>

                                    <div id='filtrando_espere' style='display: none; text-align: center; margin-top: 100px;'>
                                        <img src="{$base_dir_ssl}img/spiner.gif" width='70px'>
                                        <span style='font-size: 16px;'>Buscando, por favor espere...</span>
                                    </div>
                                    <div id='filtrando_no_resultados' style='display: none; text-align: center; margin-top: 100px;'>
                                        <span style='font-size: 16px;'>No existen resultados.</span>
                                    </div>
                                </div>
                                <a class="buttons next" href="#">right</a>
                            </div>
                            {*<div>
                            <button id='agregar_licor_seleccionado'>AGREGAR</button>
                            </div>*}

                        </div>
                    </div>        
                </div>
            
            {/block}

          </div>
        </section>

        <footer data-html2canvas-ignore id="footer">
          {block name="footer"}
            {include file="_partials/footer.tpl"}
          {/block}
        </footer>

      </main>

      {hook h='displayBeforeBodyClosingTag'}

      {block name='javascript_bottom'}
        {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
      {/block}

    </body>

    </html>



