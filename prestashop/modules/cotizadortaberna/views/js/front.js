
    var seleccionado_licor_champana = false;
    var seleccionado_licor_vino_tinto = false;
    var seleccionado_licor_vino_blanco = false;
    var seleccionado_licor_ron = false;
    var seleccionado_licor_whisky = false;
    var seleccionado_licor_gin = false;
    var nombre_categoria;

    var id_producto_seleccionado_champana;
    var id_producto_seleccionado_vino_tinto;
    var id_producto_seleccionado_vino_blanco;
    var id_producto_seleccionado_whisky;
    var id_producto_seleccionado_ron;
    var id_producto_seleccionado_gin;

    var global_cantidad_champana, global_cantidad_vino_tinto, global_cantidad_vino_blanco, global_cantidad_whisky, global_cantidad_ron, global_cantidad_gin, global_invitados;


    let id_category = "all";

    //Champaña 46
    //Vino 44
    //Whisky 42
    //Ron 39
    //Gin 47

    //reiniciar_registros();
    $(document).ready(function (e) {

        //$('#slider_cotizador').tinycarousel({
        //});
        $('#contenedor_de_licores_seleccionados').hide();


        $("#btn_iniciar_sesion").click(function(e){
            login();
        });

        $("#btn_get_cart").click(function(e){
            var cart_id = $("#txt_cart_id").val();
            getCart(cart_id);
        });

        $("#btn_add_to_cart").click(function(e){
            var cart_id = $("#txt_cart_id").val();
            addCart(cart_id);
        });

        $("#btn_cerrar_sesion").click(function(e){
            cerrarSesion();
        });

        var authorizationToken = null;


        function login() {
            $.ajax({
                //data: {email: email_destino, base: img},
                url: 'http://tabernatest.tk/cuenca/zona-rosa/index.php?fc=module&module=webservice_app&controller=Login&email=chxto1906@gmail.com&password=chato1906',
                type: "GET",
                success: function (data,r,xhr) {
                    console.log("RESPONSE LOGIN");
                    console.dir(data);
                    authorizationToken = data.session_data;
                    var setCookie = xhr.getResponseHeader('Set-Cookie');
                    console.log('SET_COOKIE: '+setCookie);

                    //addCart(data.cart_id);
                    console.dir(r);
                    console.dir(xhr.getAllResponseHeaders());
                    console.dir(document.cookie);
                },
                error: function (error) {
                    console.log("ERROR");
                    console.dir(error);
                }
            });
        }

        
        function addCart(cart_id){

            $.ajax({
                //data: {email: email_destino, base: img},
                url: 'http://tabernatest.tk/cuenca/zona-rosa/index.php?fc=module&module=webservice_app&controller=AddToCart&id_product=3840&qty=2&cart_id='+cart_id,
                type: "GET",
                dataType: "json",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", authorizationToken);
                },
                success: function (data,r,xhr) {
                    console.log('RESPONSE ADD TO CART')
                    console.dir(data);
                    //getCart(cart_id);
                    console.dir(r);
                    console.dir(xhr);
                    console.dir(document.cookie);
                },
                error: function (error) {
                    console.log("ERROR");
                    console.dir(error);
                }
            });
        }

        function getCart(cart_id){

            $.ajax({
                //data: {email: email_destino, base: img},
                url: 'http://tabernatest.tk/cuenca/zona-rosa/index.php?fc=module&module=webservice_app&controller=GetCart&cart_id='+cart_id,
                type: "GET",
                dataType: "json",
                beforeSend: function(request) {
                    console.log('authorizationToken: '+authorizationToken);
                    request.setRequestHeader("Authorization", authorizationToken);
                },
                success: function (data,r,xhr) {
                    console.log('RESPONSE GET CART')
                    console.dir(data);
                    console.dir(r);
                    console.dir(xhr);
                    console.dir(document.cookie);
                },
                error: function (error) {
                    console.log("ERROR");
                    console.dir(error);
                }
            });
        }

        function cerrarSesion(){

            $.ajax({
                //data: {email: email_destino, base: img},
                url: 'http://tabernatest.tk/cuenca/zona-rosa/index.php?fc=module&module=webservice_app&controller=Logout',
                type: "GET",
                dataType: "json",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", authorizationToken);
                },
                success: function (data,r,xhr) {
                    console.log('RESPONSE LOGOUT')
                    console.dir(data);
                    console.dir(r);
                    console.dir(xhr);
                    console.dir(document.cookie);
                },
                error: function (error) {
                    console.log("ERROR");
                    console.dir(error);
                }
            });
        }






        // HENRY *******
        
        //$("#top-menu > li.current > a").css('color','#FFFFFF');
        //$("#lnk-cotizador-de-eventos > a").css('color','#ed2123');

        // HENRY *******


        //let source = $("#title-modulo1").html();
        //  pdfDoc.fromHTML(source,10,10);
        //  pdfDoc.save('Ejemplo.pdf');

        $('.inline_email').click(function () {
            //$('#verificacion_envio_email').html('');
            $('#verificacion_envio_email').hide();
        });

        $('#enviar_email_cotizacion').click(function(e) {
            $('#location-modal-enviar').modal({backdrop: 'static', keyboard: false});
        });

        
        $('#descargar_cotizacion').click(function(e) {
            e.preventDefault();
            console.log("Ingresó al link---");
            $("#descargar_cotizacion").html('<i class="material-icons">picture_as_pdf</i> DESCARGANDO...');
            $("#descargar_cotizacion").attr('disabled','disabled');
            $("#descargar_cotizacion").addClass('disabled');

            /*console.log("*************");
            console.dir(document.getElementById("pdf-cotizacion"));
            console.log("*************");
            console.dir(document.body);
            console.log("*************");*/

            html2canvas(document.body).then(function(canvas){
                console.log("Ingresó a callback");
                var img=canvas.toDataURL("image/png");
                let pdfDoc = new jsPDF('p','pt',[canvas.height,canvas.width]);
                pdfDoc.addImage(img,'png',0,-80,canvas.width,canvas.height);
                pdfDoc = addWaterMark(pdfDoc);
                $("#descargar_cotizacion").html('<i class="material-icons">picture_as_pdf</i> DESCARGAR LA COTIZACIÓN');
                $("#descargar_cotizacion").removeAttr('disabled');
                $("#descargar_cotizacion").removeClass('disabled');
                pdfDoc.save('cotizacion_'+dateNow()+'.pdf');
            });
        });


        function addWaterMark(doc) {
          var totalPages = doc.internal.getNumberOfPages();
          let texto = 'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ' +
                       'La Taberna Liquor Store La Taberna Liquor Store ';
          for (i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            //doc.addImage(imgData, 'PNG', 40, 40, 75, 75);
            doc.setTextColor(224,224,224);
            doc.text(0, doc.internal.pageSize.height - 50, texto);
            doc.text(0, doc.internal.pageSize.height - 70, texto);
            doc.text(0, doc.internal.pageSize.height - 90, texto);
            doc.text(0, doc.internal.pageSize.height - 110, texto);
            doc.text(0, doc.internal.pageSize.height - 130, texto);
            doc.text(0, doc.internal.pageSize.height - 150, texto);
            doc.text(0, doc.internal.pageSize.height - 170, texto);
            doc.text(0, doc.internal.pageSize.height - 190, texto);
            doc.text(0, doc.internal.pageSize.height - 210, texto);
            doc.text(0, doc.internal.pageSize.height - 230, texto);

            doc.text(0, 50, texto);
            doc.text(0, 70, texto);
            doc.text(0, 90, texto);
            doc.text(0, 110, texto);
            doc.text(0, 130, texto);
            doc.text(0, 150, texto);
          }

          return doc;
        }



        $('#btnEnviarEmailModal').click(function (e) {
            e.preventDefault();
            var email_destino = $("#email_destino_cotiza").val();
            $("#estado_enviando").css('visibility','visible');
            html2canvas(document.body).then(function(canvas){
                var img=canvas.toDataURL("image/png");
                $.ajax({
                    data: {email: email_destino, base: img},
                    url: '?fc=module&module=cotizadortaberna&controller=enviarcotizacionemail',
                    type: "POST",
                    dataType: "json",
                    beforeSend: function () {
                        $("#estado_enviando").css('visibility','visible');
                    },
                    success: function (data) {
                        $("#estado_enviando").css('visibility','hidden');
                        if (data != "")
                        {
                            var estado = data.resultado;
                            $("#verificacion_envio_email").html(estado);
                            $("#verificacion_envio_email").show();
                        } else
                        {
                            $("#verificacion_envio_email").html("<h5 style='color: red;'>ERROR DESCONOCIDO, SU COTIZACION NO FUE ENVIADA.</h5>");
                            $("#verificacion_envio_email").show();
                        }
                    },
                    error: function (error) {
                        $("#estado_enviando").css('visibility','hidden');
                        $("#verificacion_envio_email").html("<h5 style='color: red;'>ERROR, SU COTIZACION NO FUE ENVIADA.</h5>");
                        $("#verificacion_envio_email").show();
                    }
                });
            });

        });



        $('#buscar').click(function (e) {
            e.preventDefault();
            reiniciar_registros();
            $('#contenedor_de_licores_seleccionados').show();
            $('#showChampan').hide();
            $('#showVinoB').hide();
            $('#showVinoT').hide();
            $('#showWhisky').hide();
            $('#showRon').hide();
            $('#showGin').hide();
            $invitados = $('#invitados').val();
            global_invitados = $invitados;
            $champan = $('#champan').is(':checked');
            $vino = $('#vino').is(':checked');
            $ron = $('#ron').is(':checked');
            $gin = $('#gin').is(':checked');
            $whiskey = $('#whiskey').is(':checked');
            if ($invitados > 0 && $invitados != null) {
                if ($champan) {

                    var cantidad_champana = Math.ceil($invitados * 0.125);
                    $('#valChampan').html(cantidad_champana);
                    global_cantidad_champana = cantidad_champana;
                    $("#cantidad_champana_imprimir").html(cantidad_champana);
                    $('#showChampan').show();
                    $('#registro_champana').show();
                    $('#registro_champana_imprimir').show();
                    /*$('#precio_champana_cotiza').html('$0');
                     valorChampan=$('#precio_champana_cotiza').html();
                     valorChampan=valorChampan.substring(1,valorChampan.length);
                     $('#total_champana_cotiza').html('$'+(parseFloat(valorChampan)*cantidad_champana));*/
                }
                if ($vino) {
                    //var cantidad = $invitados * 0.25;
                    var cantidad = ($invitados / 8) * 1.5;

                    var cantidad_vinoblanco = Math.ceil(cantidad * 0.30);
                    $('#valVinoB').html(cantidad_vinoblanco);
                    global_cantidad_vino_blanco = cantidad_vinoblanco;

                    var cantidad_vinotinto = Math.ceil(cantidad * 0.70);
                    $('#valVinoT').html(cantidad_vinotinto);
                    global_cantidad_vino_tinto = cantidad_vinotinto;

                    $("#cantidad_vinoblanco_imprimir").html(cantidad_vinoblanco);
                    $("#cantidad_vinotinto_imprimir").html(cantidad_vinotinto);
                    /*
                     $('#precio_vino_tinto_cotiza').html('$0');
                     valorChampan=$('#precio_vino_tinto_cotiza').html();
                     valorChampan=valorChampan.substring(1,valorChampan.length);
                     $('#total_vino_tinto_cotiza').html('$'+(parseFloat(valorChampan)*cantidad_vinotinto));
                     $('#precio_vino_blanco_cotiza').html('$0');
                     valorChampan=$('#precio_vino_blanco_cotiza').html();
                     valorChampan=valorChampan.substring(1,valorChampan.length);
                     $('#total_vino_blanco_cotiza').html('$'+(parseFloat(valorChampan)*cantidad_vinoblanco));*/

                    $('#showVinoB').show();
                    $('#registro_vino_blanco').show();
                    $('#registro_vino_tinto').show();
                    $('#registro_vinoblanco_imprimir').show();
                    $('#registro_vinotinto_imprimir').show();


                    $('#showVinoT').show();
                }
                /*if ($whiskey ) {

                    var cantidad_whisky = Math.ceil($invitados * 0.222);
                    $('#valWhisky').html(cantidad_whisky);
                    global_cantidad_whisky = cantidad_whisky;

                    $("#cantidad_whisky_imprimir").html(cantidad_whisky);

                    $('#showWhisky').show();
                    $('#registro_whisky').show();
                    $('#registro_whisky_imprimir').show();
                }
                if ($ron) {
                    var cantidad_ron = Math.ceil($invitados * 0.125);
                    $('#valRon').html(cantidad_ron);
                    global_cantidad_ron = cantidad_ron;

                    $("#cantidad_ron_imprimir").html(cantidad_ron);
                    $('#showRon').show();
                    $('#registro_ron').show();
                    $('#registro_ron_imprimir').show();
                }*/

                if ($whiskey || $ron || $gin) {
                   let cantidad = ($invitados / 15) * 4
                   if ($whiskey && $ron && $gin) {
                        whiskyShow(cantidad, 0.70);
                        ronShow(cantidad, 0.20);
                        ginShow(cantidad, 0.10);
                   } else if ($whiskey && $ron && !$gin) {
                        whiskyShow(cantidad, 0.80);
                        ronShow(cantidad, 0.20);
                   } else if ($whiskey && !$ron && $gin) {
                        whiskyShow(cantidad, 0.85);
                        ginShow(cantidad, 0.15);
                   } else if ($whiskey && !$ron && !$gin){
                        whiskyShow(cantidad, 1);
                   } else if (!$whiskey && $ron && !$gin){
                        ronShow(cantidad, 1);
                   } else if (!$whiskey && !$ron && $gin){
                        ginShow(cantidad, 1);
                   } else if (!$whiskey && $ron && $gin){
                        ronShow(cantidad, 0.80);
                        ginShow(cantidad, 0.20);
                   }
                }


                sumar_cada_registro();
                var suma_new = sumar_precio_productos();
                $('#total_cotiza').html("$" + suma_new);
                $('#total_imprimir').html("$" + suma_new);
            }
        });

        $("#imprimir_cotiza").click(function () {
            $("#contenido_cotizador_imprimir").printArea();
        });

    //});

    function convertImgToBase64URL(url, callback, outputFormat){
        var img = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload = function(){
            var canvas = document.createElement('CANVAS'),
            ctx = canvas.getContext('2d'), dataURL;
            canvas.height = img.height;
            canvas.width = img.width;
            ctx.drawImage(img, 0, 0);
            dataURL = canvas.toDataURL(outputFormat);
            callback(dataURL);
            canvas = null; 
        };
        img.src = url;
    }

    function dateNow() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        if (dd < 10)
          dd = '0' + dd;
        if (mm < 10)
          mm = '0' + mm;
        return dd + '_' + mm + '_' + yyyy;
    }

    function ronShow(cantidad, porcentaje) {
        let cantidad_ron = Math.ceil(cantidad * porcentaje);
        global_cantidad_ron = cantidad_ron;
        $('#valRon').html(cantidad_ron);
        $("#cantidad_ron_imprimir").html(cantidad_ron);
        $('#showRon').show();
        $('#registro_ron').show();
        $('#registro_ron_imprimir').show();
    }

    function whiskyShow(cantidad, porcentaje) {
        let cantidad_whisky = Math.ceil(cantidad * porcentaje);
        global_cantidad_whisky = cantidad_whisky;
        $('#valWhisky').html(cantidad_whisky);
        $("#cantidad_whisky_imprimir").html(cantidad_whisky);
        $('#showWhisky').show();
        $('#registro_whisky').show();
        $('#registro_whisky_imprimir').show();
    }

    function ginShow(cantidad, porcentaje) {
        let cantidad_gin = Math.ceil(cantidad * porcentaje);
        global_cantidad_gin = cantidad_gin;
        $('#valGin').html(cantidad_gin);
        $("#cantidad_gin_imprimir").html(cantidad_gin);
        $('#showGin').show();
        $('#registro_gin').show();
        $('#registro_gin_imprimir').show();
    }

    function delay(callback, ms) {
      var timer = 0;
      return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
          callback.apply(context, args);
        }, ms || 0);
      };
    }


    $('.search_query_cotizador').keyup(delay(function (e) {
        e.preventDefault();
        if ($("#cargando_productos").is(":hidden")) {
            let excludeKeys = [
                37,38,39,40,32,16,20,27,13,18,93,91,17
            ];
            if ($.inArray(e.which,excludeKeys) == -1)
                cargar(nombre_categoria,e.target.value);
        }
    }, 1000));



    $('.selecciona').click(function () {
        var id = $(this).attr('id');
        switch (id)
        {
            case 'selecciona_champana':
                //CARGAR CHAMPANAS PARA SELECCIONAR//
                nombre_categoria = "Champaña";
                cargar(nombre_categoria);
                //=================================//
                break;
            case 'selecciona_vino_tinto':
                //CARGAR CHAMPANAS PARA SELECCIONAR//
                nombre_categoria = "Vino tinto";
                cargar(nombre_categoria);
                //=================================//
                break;
            case 'selecciona_vino_blanco':
                //CARGAR CHAMPANAS PARA SELECCIONAR//
                nombre_categoria = "Vino blanco";
                cargar(nombre_categoria);
                //=================================//
                break;
            case 'selecciona_whisky':
                //CARGAR CHAMPANAS PARA SELECCIONAR//
                nombre_categoria = "Whisky";
                cargar(nombre_categoria);
                //=================================//
                break;
            case 'selecciona_ron':
                //CARGAR CHAMPANAS PARA SELECCIONAR//
                nombre_categoria = "Ron";
                cargar(nombre_categoria);
                //=================================//
                break;
            case 'selecciona_gin':
                //CARGAR GINS PARA SELECCIONAR//
                nombre_categoria = "Gin";
                cargar(nombre_categoria);
                //=================================//
                break;
        }
    });

    $("#añadir_cotizacion_carrito").click(function (e) {


        resetBandera = false;

        if (id_producto_seleccionado_ron != null)
        {

            var idProduct = id_producto_seleccionado_ron;
            var cantidad = global_cantidad_ron;
            ajaxCart.add(idProduct, false, false, this, cantidad);

        }
        setTimeout(function () {
            //console.log('espera1');
        }, 500);

        if (id_producto_seleccionado_whisky != null)
        {

            var idProduct = id_producto_seleccionado_whisky;
            var cantidad = global_cantidad_whisky;
            ajaxCart.add(idProduct, false, false, this, cantidad);

        }
        setTimeout(function () {
            // console.log('espera2');
        }, 500);
        if (id_producto_seleccionado_champana != null)
        {

            var idProduct = id_producto_seleccionado_champana;
            var cantidad = global_cantidad_champana;
            ajaxCart.add(idProduct, false, false, this, cantidad);

        }
        setTimeout(function () {
            //  console.log('espera3');
        }, 500);
        if (id_producto_seleccionado_vino_tinto != null)
        {

            var idProduct = id_producto_seleccionado_vino_tinto;
            var cantidad = global_cantidad_vino_tinto;

            //alert(cantidad+' vino tinto');

            ajaxCart.add(idProduct, false, false, this, cantidad);

        }
        setTimeout(function () {
            // console.log('espera4');
        }, 500);
        if (id_producto_seleccionado_vino_blanco != null)
        {

            var idProduct = id_producto_seleccionado_vino_blanco;
            var cantidad = global_cantidad_vino_blanco;

            //alert(cantidad+' vino blanco');

            ajaxCart.add(idProduct, false, false, this, cantidad);


        }

        $(".anadir_carrito_oculto_cotiza").hide();
        $(".anadir_carrito_cotiza").show();
        return false;

    });



    


        function obtener_data_cotizacion() {

            var cadena = '<div><img src="'+base_url+'img/logo_mail-1.jpg" /></div><br><div><h2>Cotización para: ' + global_invitados + ' personas.</h2></div><br><table style="font-size: 20px;" border="1"><tr class="cabecera" style="background-color:red; font-size:18px; color: white;"><td>Cantidad</td><td>Categoría</td><td>Producto</td><td>Descripción</td><td>Precio</td><td>Total</td></tr>';
            if ($('#registro_champana').is(':visible'))
            {
                cadena += '<tr>' + document.getElementById('registro_champana_imprimir').innerHTML + '</tr>';
            }
            if ($('#registro_vino_tinto').is(':visible'))
            {
                cadena += '<tr>' + document.getElementById('registro_vinotinto_imprimir').innerHTML + '</tr>';
            }
            if ($('#registro_vino_blanco').is(':visible'))
            {
                cadena += '<tr>' + document.getElementById('registro_vinoblanco_imprimir').innerHTML + '</tr>';
            }
            if ($('#registro_whisky').is(':visible'))
            {
                cadena += '<tr>' + document.getElementById('registro_whisky_imprimir').innerHTML + '</tr>';
            }
            if ($('#registro_ron').is(':visible'))
            {
                cadena += '<tr>' + document.getElementById('registro_ron_imprimir').innerHTML + '</tr>';
            }
            if ($('#registro_gin').is(':visible'))
            {
                cadena += '<tr>' + document.getElementById('registro_gin_imprimir').innerHTML + '</tr>';
            }
            if ($('#total_cotiza').is(':visible'))
            {
                cadena += '<tr style="height: 100px; background-color:whitesmoke;">' + document.getElementById('registro_total_imprimir').innerHTML + '</tr>';
            }
            cadena += '</table>';

            return cadena;


        }

        function sumar_cada_registro() {

            if ($('#registro_champana').is(':visible') && isNaN($("#precio_champana_cotiza").html()) == true)
            {

                var precioOriginal = $('#precio_champana_cotiza').html();
                var precio = parseFloat(precioOriginal.replace('$', ''), 2);
                var total = global_cantidad_champana * precio;
                total = total.toFixed(2)
                $("#total_champana_cotiza div").html(total);
                $("#total_champana_imprimir").html("$" + total);
            }
            if ($('#registro_vino_blanco').is(':visible') && isNaN($("#precio_vino_blanco_cotiza").html()) == true)
            {
                var precioOriginal = $('#precio_vino_blanco_cotiza').html();
                var precio = parseFloat(precioOriginal.replace('$', ''), 2);
                var total = global_cantidad_vino_blanco * precio;
                total = total.toFixed(2)
                $("#total_vino_blanco_cotiza div").html(total);
                $("#total_vinoblanco_imprimir").html("$" + total);
            }
            if ($('#registro_vino_tinto').is(':visible') && isNaN($("#precio_vino_tinto_cotiza").html()) == true)
            {
                var precioOriginal = $('#precio_vino_tinto_cotiza').html();
                var precio = parseFloat(precioOriginal.replace('$', ''), 2);
                var total = global_cantidad_vino_tinto * precio;
                total = total.toFixed(2)
                $("#total_vino_tinto_cotiza div").html(total);
                $("#total_vinotinto_imprimir").html("$" + total);
            }
            if ($('#registro_whisky').is(':visible') && isNaN($("#precio_whisky_cotiza").html()) == true)
            {
                var precioOriginal = $('#precio_whisky_cotiza').html();
                var precio = parseFloat(precioOriginal.replace('$', ''), 2);
                var total = global_cantidad_whisky * precio;
                total = total.toFixed(2)
                $("#total_whisky_cotiza div").html(total);
                $("#total_whisky_imprimir").html("$" + total);
            }
            if ($('#registro_ron').is(':visible') && isNaN($("#precio_ron_cotiza").html()) == true)
            {
                var precioOriginal = $('#precio_ron_cotiza').html();
                var precio = parseFloat(precioOriginal.replace('$', ''), 2);
                var total = global_cantidad_ron * precio;
                total = total.toFixed(2)
                $("#total_ron_cotiza div").html(total);
                $("#total_ron_imprimir").html("$" + total);
            }
            if ($('#registro_gin').is(':visible') && isNaN($("#precio_gin_cotiza").html()) == true)
            {
                var precioOriginal = $('#precio_gin_cotiza').html();
                var precio = parseFloat(precioOriginal.replace('$', ''), 2);
                var total = global_cantidad_gin * precio;
                total = total.toFixed(2)
                $("#total_gin_cotiza div").html(total);
                $("#total_gin_imprimir").html("$" + total);
            }

        }


        

        function reiniciar_registros() {
            $("#registro_champana").hide();
            $("#registro_vino_tinto").hide();
            $("#registro_vino_blanco").hide();
            $("#registro_whisky").hide();
            $("#registro_ron").hide();
            $("#registro_gin").hide();


            $("#registro_champana_imprimir").hide();
            $("#registro_vinotinto_imprimir").hide();
            $("#registro_vinoblanco_imprimir").hide();
            $("#registro_whisky_imprimir").hide();
            $("#registro_ron_imprimir").hide();
            $("#registro_gin_imprimir").hide();

            /*
             $("#foto_champana_cotiza > img").attr('src','');
             $("#foto_vino_blanco_cotiza > img").attr('src','');
             $("#foto_vino_tinto_cotiza > img").attr('src','');
             $("#foto_whisky_cotiza > img").attr('src','');
             $("#foto_ron_cotiza > img").attr('src','');
             
             $("#descripcion_champana_cotiza").html("");
             $("#descripcion_whisky_cotiza").html("");
             $("#descripcion_ron_cotiza").html("");
             $("#descripcion_vino_blanco_cotiza").html("");
             $("#descripcion_vino_tinto_cotiza").html("");
             
             $("#precio_champana_cotiza").html("");
             $("#precio_whisky_cotiza").html("");
             $("#precio_ron_cotiza").html("");
             $("#precio_vino_tinto_cotiza").html("");
             $("#precio_vino_blanco_cotiza").html("");
             
             $("#total_champana_cotiza div").html('0');
             $("#total_whisky_cotiza div").html('0');
             $("#total_ron_cotiza div").html('0');
             $("#total_vino_blanco_cotiza div").html('0');
             $("#total_vino_tinto_cotiza div").html('0');
             
             $("#total_cotiza").html(0);
             
             seleccionado_licor_champana = false;
             seleccionado_licor_vino_tinto = false;
             seleccionado_licor_vino_blanco = false;
             seleccionado_licor_ron = false;
             seleccionado_licor_whisky = false;
             nombre_categoria = null;
             
             id_producto_seleccionado_champana = null;
             id_producto_seleccionado_vino_tinto = null;
             id_producto_seleccionado_vino_blanco = null;	
             id_producto_seleccionado_whisky = null;
             id_producto_seleccionado_ron = null;
             
             global_cantidad_champana = null;
             global_cantidad_vino_tinto=null; 
             global_cantidad_vino_blanco=null; 
             global_cantidad_whisky=null;
             global_cantidad_ron=null;
             $(".anadir_carrito_oculto_cotiza").show();
             $(".anadir_carrito_cotiza").hide();*/
        }

        function cargar(nombre_categoria,query=null)
        {
            if (nombre_categoria == "Vino tinto")
                var d = "name_category=tinto";
            else if (nombre_categoria == "Vino blanco")
                var d = "name_category=blanco";
            else
                var d = "name_category=" + nombre_categoria;

            if (query) {
                d = d + "&query="+query;
            }

            

            $.ajax({
                data: d,
                url: '?fc=module&module=cotizadortaberna&controller=cargarlicoresescoger',
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    /*$("#filtrando_espere").show();
                    $("#filtrando_no_resultados").hide();
                    $(".nombre_licor").hide();
                    $("#slider1").css('visibility', 'hidden');
                    $(".titulo_seleccionar_licor").hide();*/
                    $(".cantidad_products_cotizador").hide();
                    $(".carousel_de_licores_seleccionar").hide();
                    $("#cargando_productos").show();
                    $(".titulo-custom").hide();
                    $(".buttons").hide();
                },
                success: function (data) {

                    if (data.length > 0)
                        id_category = data[0].id_category;

                    $(".cantidad_products_cotizador").show();
                    $("#cantidad_products_cotizador").html(data.length);
                    $("#cargando_productos").hide();
                    $('#location-modal-cotizador').modal({backdrop: 'static', keyboard: false});

                    /*if (data === null || data.length == 0) {
                        $(".carousel_de_licores_seleccionar").empty();
                        $("#cargando_productos").hide();
                        $(".titulo-custom").hide();

                    } else {
                        $(".titulo-custom").show();
                    }*/



                    $("#slider1").css('visibility', 'visible');
                    $(".titulo_seleccionar_licor").show();
                    if (data != "")
                    {
                        $(".carousel_de_licores_seleccionar").empty();
                        $('.nombre_licor').html(nombre_categoria);


                        $.each(data, function (key, val) {
                            cargar_licores(data, key, nombre_categoria);
                        });

                        $(".carousel_de_licores_seleccionar").show();
                        $(".buttons").show();
                        $(".titulo-custom").show();

                        $(".nombre_licor").show();


                        //setTimeout(function () {

                            $("#slider1").tinycarousel({
                            });
                        //    $("#cargando_productos").hide();
                        //}, 100);

                      



                    }/* else {
                        $("#filtrando_no_resultados").show();
                    }
                    $("#filtrando_espere").hide();*/
                }
            });
        }

        function deseleccionar_productos() {
            $('.li_clases').css('border-bottom', '');
        }

        function cargar_licores(data, key, categoria_current)
        {

            var id_pro = data[key].id_product;

            var cantidad_stock = parseInt(data[key].cantidad_stock);


            if (data[key].id_image != null)
                var id_image = data[key].id_image;
            else
                var id_image = data[key].id_cover;
            var name_product = data[key].name_product;
            var id_category = data[key].id_category;
            var name_category = data[key].name_category;
            var precio = parseFloat(data[key].price, 10);
            if (data[key].impuesto != null)
                var impuesto = data[key].impuesto;
            else
                var impuesto = 0;
            if (data[key].impact != null)
                var impacto = data[key].impact;
            else
                var impacto = 0;
            var ImpuestoPrecio = (impuesto * precio) / 100;
            var ImpuestoImpacto = (impuesto * impacto) / 100;
            precio = parseFloat(precio) + parseFloat(ImpuestoPrecio) + parseFloat(impacto) + parseFloat(ImpuestoImpacto);
            var precioDosDecimales = "$" + precio.toFixed(2);
            if (data[key].volumen != null)
                var capacidad = data[key].volumen;
            else
                var capacidad = '';
            if (id_image != null) {
                var cantidad_caracteres_id = id_image.length;

                var numeros = id_image.split("");
                var cadenaUrl = "";
                for (var i = 0; i < cantidad_caracteres_id; i++)
                {
                    cadenaUrl = cadenaUrl + numeros[i] + "/";
                }
            } else {
                id_image = "";
                cadenaUrl = "es-default";
            }

            var cantidad_cotiza;
            if (categoria_current == "Vino tinto")
            {
                cantidad_cotiza = parseInt($("#valVinoT").html());
            } else if (categoria_current == "Vino blanco")
            {
                cantidad_cotiza = parseInt($("#valVinoB").html());
            } else if (categoria_current == "Ron")
            {
                cantidad_cotiza = parseInt($("#valRon").html());
            } else if (categoria_current == "Whisky")
            {
                cantidad_cotiza = parseInt($("#valWhisky").html());
            } else if (categoria_current == "Champaña")
            {
                cantidad_cotiza = parseInt($("#valChampan").html());
            } else if (categoria_current == "Gin")
            {
                cantidad_cotiza = parseInt($("#valGin").html());
            }


            //alert('cantidad_cotiza: '+cantidad_cotiza+' cantidad_stock: '+cantidad_stock);
            /*if (cantidad_cotiza > cantidad_stock)
            {
               // productos = '<li><div style="color: red; text-transform: uppercase;">Sin suficiente stock ' + '(' + key + '/' + data.length + ')' + '</div><div><a style="color: black; font-weight: bold;" href="{$base_dir_ssl}index.php?catalogo?categoria=' + id_category + '?producto=' + id_pro + '" target="_blank"><b>Seleccionar </b></a></div><div id="li_' + id_pro + '" ><input type="hidden" value="' + cantidad_stock + '"><img class="foto_de_producto" style="opacity: 0.2;" src="{$base_dir_ssl}img/p/' + cadenaUrl + id_image + '-large_default.jpg"><div class="detalles_producto" style="width: 100%"><div id="producto_cotizador_' + id_pro + '">' + name_product + '</div><div id="capacidad_producto_' + id_pro + '">' + capacidad + '</div><div id="precio_producto_' + id_pro + '">' + precioDosDecimales + '</div></div></div></li>';
                productos = '<li><div style="color: red; text-transform: uppercase;">Entrega en 5 días </div><div><a style="color: black; font-weight: bold;" href="{$base_dir_ssl}index.php?catalogo?categoria=' + id_category + '?producto=' + id_pro + '" target="_blank"></a></div><div id="li_' + id_pro + '" class="li_clases" onClick="selectLicor(this);"><input type="hidden" value="' + cantidad_stock + '"><img class="foto_de_producto" style="opacity: 0.2;" src="'+base_url+'img/p/' + cadenaUrl + id_image + '.jpg"><div class="detalles_producto" style="width: 100%"><div id="producto_cotizador_' + id_pro + '">' + name_product + '</div><div id="capacidad_producto_' + id_pro + '">' + capacidad + '</div><div id="precio_producto_' + id_pro + '">' + precioDosDecimales + '</div></div></div></li>';
            } else
            {*/
               // productos = '<li><br><div><a style="color: black; font-weight: bold;" href="{$base_dir_ssl}index.php?catalogo?categoria=' + id_category + '?producto=' + id_pro + '" target="_blank"><b>Selecciona el Producto  ( ' + key + '/' + data.length + ')  </b></a></div><div id="li_' + id_pro + '" class="li_clases"><input type="hidden" value="' + cantidad_stock + '"><img class="foto_de_producto" src="{$base_dir_ssl}img/p/' + cadenaUrl + id_image + '-large_default.jpg"><div class="detalles_producto" style="width: 100%"><div id="producto_cotizador_' + id_pro + '">' + name_product + '</div><div id="capacidad_producto_' + id_pro + '">' + capacidad + '</div><div id="precio_producto_' + id_pro + '">' + precioDosDecimales + '</div></div></div></li>';
                productos = '<li><br><div><a style="color: black; font-weight: bold;" href="{$base_dir_ssl}index.php?catalogo?categoria=' + id_category + '?producto=' + id_pro + '" target="_blank"></a></div><div id="li_' + id_pro + '" class="li_clases" onClick="selectLicor(this);"><input type="hidden" value="' + cantidad_stock + '"><img class="foto_de_producto" src="'+base_url+'img/p/' + cadenaUrl + id_image + '-home_default.jpg"><div class="detalles_producto" style="width: 100%"><div id="producto_cotizador_' + id_pro + '">' + name_product + '</div><div id="capacidad_producto_' + id_pro + '">' + capacidad + '</div><div id="precio_producto_' + id_pro + '">' + precioDosDecimales + '</div></div></div></li>';
            //}



            $(".carousel_de_licores_seleccionar").append(productos);




        }

            


            /*$(".li_clases").mouseover(function () {
                console.log("entroo");
                var nombre = nombre_categoria;
                switch (nombre)
                {
                    case 'Champaña':
                        //////CHAMPANA/////
                        if (seleccionado_licor_champana == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '7px solid #ed1c24');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_champana)
                            {
                            } else
                                $(this).css('border-bottom', '7px solid #BEBFC2');
                        }
                        ////==========///// 
                        break;
                    case 'Vino tinto':
                        //////VINO TINTO/////
                        if (seleccionado_licor_vino_tinto == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '7px solid #ed1c24');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_vino_tinto)
                            {
                            } else
                                $(this).css('border-bottom', '7px solid #BEBFC2');
                        }
                        ////==========///// 
                        break;
                    case 'Vino blanco':
                        //////VINO BLANCO/////
                        if (seleccionado_licor_vino_blanco == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '7px solid #ed1c24');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_vino_blanco)
                            {
                            } else
                                $(this).css('border-bottom', '7px solid #BEBFC2');
                        }
                        ////==========///// 
                        break;
                    case 'Ron':
                        //////RON/////
                        if (seleccionado_licor_ron == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '7px solid #ed1c24');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_ron)
                            {
                            } else
                                $(this).css('border-bottom', '7px solid #BEBFC2');
                        }
                        ////==========///// 
                        break;
                    case 'Whisky':
                        //////WHISKY/////
                        if (seleccionado_licor_whisky == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '7px solid #ed1c24');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_whisky)
                            {
                            } else
                                $(this).css('border-bottom', '7px solid #BEBFC2');
                        }
                        ////==========///// 
                        break;
                }


            }).mouseout(function () {

                var nombre = nombre_categoria;
                switch (nombre)
                {
                    case 'Champaña':
                        //////CHAMPANA/////
                        if (seleccionado_licor_champana == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_champana)
                            {
                            } else
                                $(this).css('border-bottom', '');
                        }
                        ////==========///// 
                        break;
                    case 'Vino tinto':
                        //////VINO TINTO/////
                        if (seleccionado_licor_vino_tinto == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_vino_tinto)
                            {
                            } else
                                $(this).css('border-bottom', '');
                        }
                        ////==========///// 
                        break;
                    case 'Vino blanco':
                        //////VINO BLANCO/////
                        if (seleccionado_licor_vino_blanco == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_vino_blanco)
                            {
                            } else
                                $(this).css('border-bottom', '');
                        }
                        ////==========///// 
                        break;
                    case 'Ron':
                        //////RON/////
                        if (seleccionado_licor_ron == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_ron)
                            {
                            } else
                                $(this).css('border-bottom', '');
                        }
                        ////==========///// 
                        break;
                    case 'Whisky':
                        //////WHISKY/////
                        if (seleccionado_licor_whisky == false)
                        {
                            deseleccionar_productos();
                            $(this).css('border-bottom', '');
                        } else
                        {
                            if ($(this).attr('id') == "li_" + id_producto_seleccionado_whisky)
                            {
                            } else
                                $(this).css('border-bottom', '');
                        }
                        ////==========/////
                }

            });*/
        });


        function selectLicor(el){
            var id = $(el).attr('id');
            var longitud_id = id.length;
            var nombre = nombre_categoria;
            switch (nombre)
            {
                case 'Champaña':
                    id_producto_seleccionado_champana = id.substring(3, longitud_id);
                    seleccionado_licor_champana = true;
                    break;
                case 'Vino tinto':
                    id_producto_seleccionado_vino_tinto = id.substring(3, longitud_id);
                    seleccionado_licor_vino_tinto = true;
                    break;
                case 'Vino blanco':
                    id_producto_seleccionado_vino_blanco = id.substring(3, longitud_id);
                    seleccionado_licor_vino_blanco = true;
                    break;
                case 'Ron':
                    id_producto_seleccionado_ron = id.substring(3, longitud_id);
                    seleccionado_licor_ron = true;
                    break;
                case 'Whisky':
                    id_producto_seleccionado_whisky = id.substring(3, longitud_id);
                    seleccionado_licor_whisky = true;
                    break;
                case 'Gin':
                    id_producto_seleccionado_gin = id.substring(3, longitud_id);
                    seleccionado_licor_gin = true;
                    break;
            }

            $(el).css('border-bottom', '7px solid #ed1c24');
            agregar_licor_seleccionado();
        }

        function agregar_licor_seleccionado() {
            var categoria_actual = nombre_categoria;
            var id_pro;
            switch (categoria_actual)
            {
                case 'Champaña':
                    if (id_producto_seleccionado_champana)
                    {
                        id_pro = id_producto_seleccionado_champana;
                        var producto = $("#li_" + id_pro + " img").attr('src');
                        var descripcion = $("#producto_cotizador_" + id_pro).html() + " " + $("#capacidad_producto_" + id_pro).html();

                        var cantidad_disponible = $("#li_" + id_pro + " input").val();

                        var precio = $("#precio_producto_" + id_pro).html();
                        var precio2 = precio.substring(1, precio.length);
                        var cantidad = global_cantidad_champana;
                        var total = precio2 * cantidad;
                        var result = Math.round(total * 100) / 100;

                        $("#foto_champana_cotiza > img").attr('src', producto);
                        $("#producto_champana_imprimir > img").attr('src', producto);

                        $("#descripcion_champana_cotiza").html(descripcion);
                        $("#descripcion_champana_imprimir").html(descripcion);


                        $("#precio_champana_cotiza").html(precio);
                        $("#precio_champana_imprimir").html(precio);

                        //$("#cantidad_champana_cotiza ").html(cantidad);
                        $("#total_champana_cotiza div").html(result);
                        $("#total_champana_imprimir").html("$" + result);


                        $("#registro_champana").fadeIn(500);
                        $("#registro_champana_imprimir").show();
                        $('#location-modal-cotizador').modal('toggle');
                        var total = sumar_precio_productos();
                        $('#total_cotiza').html("$" + total);
                        $('#total_imprimir').html("$" + total);

        
                            //$("#registro_champana").effect("highlight", {color: "#5F5E5E", mode: "show"}, 2000);
        
                    } else
                        alert('Seleccione alguna Champaña');
                    break;
                case 'Vino tinto':
                    if (id_producto_seleccionado_vino_tinto)
                    {
                        id_pro = id_producto_seleccionado_vino_tinto;
                        var producto = $("#li_" + id_pro + " img").attr('src');
                        var descripcion = $("#producto_cotizador_" + id_pro).html() + " " + $("#capacidad_producto_" + id_pro).html();
                        var precio = $("#precio_producto_" + id_pro).html();
                        var precio2 = precio.substring(1, precio.length);
                        var cantidad = global_cantidad_vino_tinto;
                        var total = precio2 * cantidad;

                        var result = Math.round(total * 100) / 100;
                        $("#foto_vino_tinto_cotiza > img").attr('src', producto);
                        $("#producto_vinotinto_imprimir > img").attr('src', producto);

                        $("#descripcion_vino_tinto_cotiza").html(descripcion);
                        $("#descripcion_vinotinto_imprimir").html(descripcion);

                        $("#precio_vino_tinto_cotiza").html(precio);
                        $("#precio_vinotinto_imprimir").html(precio);
                        //$("#cantidad_vino_tinto_cotiza").html(cantidad);
                        $("#total_vino_tinto_cotiza div").html(result);
                        $("#total_vinotinto_imprimir").html("$" + result);

                        $("#registro_vino_tinto").fadeIn(500);
                        $("#registro_vinotinto_imprimir").show();
                        $('#location-modal-cotizador').modal('toggle');
                        var total = sumar_precio_productos();
                        $('#total_cotiza').html("$" + total);
                        $('#total_imprimir').html("$" + total);

                        //$("#registro_vino_tinto").effect("highlight", {color: "#5F5E5E", mode: "show"}, 2000);

                    } else
                        alert('Seleccione algún Vino tinto');
                    break;
                case 'Vino blanco':
                    if (id_producto_seleccionado_vino_blanco)
                    {
                        id_pro = id_producto_seleccionado_vino_blanco;
                        var producto = $("#li_" + id_pro + " img").attr('src');
                        var descripcion = $("#producto_cotizador_" + id_pro).html() + " " + $("#capacidad_producto_" + id_pro).html();
                        var precio = $("#precio_producto_" + id_pro).html();
                        var precio2 = precio.substring(1, precio.length);
                        var cantidad = global_cantidad_vino_blanco;
                        var total = precio2 * cantidad;
                        var result = Math.round(total * 100) / 100;
                        $("#foto_vino_blanco_cotiza > img").attr('src', producto);
                        $("#producto_vinoblanco_imprimir > img").attr('src', producto);

                        $("#descripcion_vino_blanco_cotiza").html(descripcion);
                        $("#descripcion_vinoblanco_imprimir").html(descripcion);

                        $("#precio_vino_blanco_cotiza").html(precio);
                        $("#precio_vinoblanco_imprimir").html(precio);
                        //$("#cantidad_vino_blanco_cotiza").html(cantidad);
                        $("#total_vino_blanco_cotiza div").html(result);
                        $("#total_vinoblanco_imprimir").html("$" + result);

                        $("#registro_vino_blanco").fadeIn(500);
                        $("#registro_vinoblanco_imprimir").show();
                        $('#location-modal-cotizador').modal('toggle');
                        var total = sumar_precio_productos();
                        $('#total_cotiza').html("$" + total);
                        $('#total_imprimir').html("$" + total);


                        //$("#registro_vino_blanco").effect("highlight", {color: "#5F5E5E", mode: "show"}, 2000);

                    } else
                        alert('Seleccione algún Vino blanco');
                    break;
                case 'Whisky':
                    if (id_producto_seleccionado_whisky)
                    {
                        id_pro = id_producto_seleccionado_whisky;
                        var producto = $("#li_" + id_pro + " img").attr('src');
                        var descripcion = $("#producto_cotizador_" + id_pro).html() + " " + $("#capacidad_producto_" + id_pro).html();
                        var precio = $("#precio_producto_" + id_pro).html();
                        var precio2 = precio.substring(1, precio.length);
                        var cantidad = global_cantidad_whisky;
                        var total = precio2 * cantidad;
                        var result = Math.round(total * 100) / 100;
                        $("#foto_whisky_cotiza > img").attr('src', producto);
                        $("#producto_whisky_imprimir > img").attr('src', producto);


                        $("#descripcion_whisky_cotiza").html(descripcion);
                        $("#descripcion_whisky_imprimir").html(descripcion);

                        $("#precio_whisky_cotiza").html(precio);
                        $("#precio_whisky_imprimir").html(precio);
                        //$("#cantidad_whisky_cotiza").html(cantidad);
                        $("#total_whisky_cotiza div").html(result);
                        $("#total_whisky_imprimir").html("$" + result);

                        $("#registro_whisky").fadeIn(500);
                        $("#registro_whisky_imprimir").show();

                        $('#location-modal-cotizador').modal('toggle');
                        var total = sumar_precio_productos();
                        $('#total_cotiza').html("$" + total);
                        $('#total_imprimir').html("$" + total);

                        //$("#registro_whisky").effect("highlight", {color: "#5F5E5E", mode: "show"}, 2000);

                    } else
                        alert('Seleccione algún Whisky');
                    break;
                case 'Ron':
                    if (id_producto_seleccionado_ron)
                    {
                        id_pro = id_producto_seleccionado_ron;
                        var producto = $("#li_" + id_pro + " img").attr('src');
                        var descripcion = $("#producto_cotizador_" + id_pro).html() + " " + $("#capacidad_producto_" + id_pro).html();
                        var precio = $("#precio_producto_" + id_pro).html();
                        var precio2 = precio.substring(1, precio.length);
                        var cantidad = global_cantidad_ron;
                        var total = precio2 * cantidad;
                        var result = Math.round(total * 100) / 100;
                        $("#foto_ron_cotiza > img").attr('src', producto);
                        $("#producto_ron_imprimir > img").attr('src', producto);

                        $("#descripcion_ron_cotiza").html(descripcion);
                        $("#descripcion_ron_imprimir").html(descripcion);

                        $("#precio_ron_cotiza").html(precio);
                        $("#precio_ron_imprimir").html(precio);
                        //$("#cantidad_ron_cotiza").html(cantidad);
                        $("#total_ron_cotiza div").html(result);
                        $("#total_ron_imprimir").html("$" + result);

                        $("#registro_ron").fadeIn(500);
                        $("#registro_ron_imprimir").show();
                        $('#location-modal-cotizador').modal('toggle');
                        var total = sumar_precio_productos();
                        $('#total_cotiza').html("$" + total);
                        $('#total_imprimir').html("$" + total);

                        //$("#registro_ron").effect("highlight", {color: "#5F5E5E", mode: "show"}, 2000);
                    } else
                        alert('Seleccione algún Ron');
                    break;
                case 'Gin':
                    if (id_producto_seleccionado_gin)
                    {
                        id_pro = id_producto_seleccionado_gin;
                        var producto = $("#li_" + id_pro + " img").attr('src');
                        var descripcion = $("#producto_cotizador_" + id_pro).html() + " " + $("#capacidad_producto_" + id_pro).html();
                        var precio = $("#precio_producto_" + id_pro).html();
                        var precio2 = precio.substring(1, precio.length);
                        var cantidad = global_cantidad_gin;
                        var total = precio2 * cantidad;
                        var result = Math.round(total * 100) / 100;
                        $("#foto_gin_cotiza > img").attr('src', producto);
                        $("#producto_gin_imprimir > img").attr('src', producto);

                        $("#descripcion_gin_cotiza").html(descripcion);
                        $("#descripcion_gin_imprimir").html(descripcion);

                        $("#precio_gin_cotiza").html(precio);
                        $("#precio_gin_imprimir").html(precio);
                        //$("#cantidad_ron_cotiza").html(cantidad);
                        $("#total_gin_cotiza div").html(result);
                        $("#total_gin_imprimir").html("$" + result);

                        $("#registro_gin").fadeIn(500);
                        $("#registro_gin_imprimir").show();
                        $('#location-modal-cotizador').modal('toggle');
                        var total = sumar_precio_productos();
                        $('#total_cotiza').html("$" + total);
                        $('#total_imprimir').html("$" + total);

                        //$("#registro_ron").effect("highlight", {color: "#5F5E5E", mode: "show"}, 2000);
                    } else
                        alert('Seleccione algún Gin');
                    break;
            }
        }


        function sumar_precio_productos() {
            var suma_total = 0;
            if ($('#registro_champana').is(':visible') && isNaN($("#precio_champana_cotiza").html()) == true)
                    //if ($('#total_champana_cotiza').is(':visible') && $('#total_champana_cotiza').parents(':hidden').length == 1)
                    {
                        var suma_champana = $("#total_champana_cotiza div").html();
                    } else {
                var suma_champana = 0;
            }

            suma_total = suma_total + parseFloat(suma_champana);

            if ($('#registro_whisky').is(':visible') && isNaN($("#precio_whisky_cotiza").html()) == true)
                    //if ($('#total_whisky_cotiza').is(':visible') && $('#total_whisky_cotiza').parents(':hidden').length == 1)
                    {
                        var suma_whisky = $("#total_whisky_cotiza div").html();
                    } else {
                var suma_whisky = 0;
            }

            suma_total = suma_total + parseFloat(suma_whisky);

            if ($('#registro_ron').is(':visible') && isNaN($("#precio_ron_cotiza").html()) == true)
                    //if ($('#total_ron_cotiza').is(':visible') && $('#total_ron_cotiza').parents(':hidden').length == 1)
                    {
                        var suma_ron = $("#total_ron_cotiza div").html();
                    } else {
                var suma_ron = 0;
            }

            suma_total = suma_total + parseFloat(suma_ron);

            //   console.log("visible: " + $('#total_vino_tinto_cotiza').is(':visible'));
            //  console.log("parents: " + $('#total_vino_tinto_cotiza').parents(':hidden').length);
            if ($('#registro_vino_tinto').is(':visible') && isNaN($("#precio_vino_tinto_cotiza").html()) == true)
                    //if ($('#total_vino_tinto_cotiza').is(':visible') && $('#total_vino_tinto_cotiza').parents(':hidden').length == 1)
                    {
                        var suma_vino_tinto = $("#total_vino_tinto_cotiza div").html();
                    } else {
                var suma_vino_tinto = 0;
            }

            suma_total = suma_total + parseFloat(suma_vino_tinto);

            //if ($('#total_vino_blanco_cotiza').is(':visible') && $('#total_vino_blanco_cotiza').parents(':hidden').length == 1)
            if ($('#registro_vino_blanco').is(':visible') && isNaN($("#precio_vino_blanco_cotiza").html()) == true)
            {
                var suma_vino_blanco = $("#total_vino_blanco_cotiza div").html();
            } else {
                var suma_vino_blanco = 0;
            }

            suma_total = suma_total + parseFloat(suma_vino_blanco);


            if ($('#registro_gin').is(':visible') && isNaN($("#precio_gin_cotiza").html()) == true)
                    //if ($('#total_ron_cotiza').is(':visible') && $('#total_ron_cotiza').parents(':hidden').length == 1)
                    {
                        var suma_gin = $("#total_gin_cotiza div").html();
                    } else {
                var suma_gin = 0;
            }

            suma_total = suma_total + parseFloat(suma_gin);





            var result = Math.round(suma_total * 100) / 100;

            $(".anadir_carrito_oculto_cotiza").hide();
            $(".anadir_carrito_cotiza").show();


            $('#registro_total_imprimir').show();

            return result;
        }



