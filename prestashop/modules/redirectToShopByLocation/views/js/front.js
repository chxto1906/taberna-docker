/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
let urlRedirect = "#";

$( function(){

	let cancelado = localStorage.getItem("mayor");
	if ((cancelado == "false") || (!cancelado)){
		if (localStorage.getItem("id_shop_locate") != id_shop_current) {

		    $('#location-modal').modal({backdrop: 'static', keyboard: false});

		    $('#btnAceptarModalTienda').click(function(e){
		    	if ($("#check-one").prop('checked')){
		    		urlRedirect = $("#tiendas_select_popup").val();
		    		localStorage.setItem('mayor',true);
		    		window.location.href = urlRedirect;	
		    	}else{
		    		localStorage.setItem('mayor',false);
		    		alert("Confirma que eres mayor de edad.");
		    	}
		    });

		    $("#check-one").click(function(e){
		    	if ($("#check-one").prop('checked')){
		    		$("#btnAceptarModalTienda").removeAttr("disabled").removeClass("disabled");
		    	}else{
		    		$("#btnAceptarModalTienda").attr("disabled","disabled").addClass("disabled");
		    	}
		    });

		    $('#btnCancelarModal').click(function(e){
		    	//localStorage.setItem("cancelado_modal",true);
		    	window.location.href = "http://www.google.com";
		    });







		    getLocation();
		}
	}

} )



function getLocation() {
  if (navigator.geolocation) {
  	$("#text-tienda-modal").html("Selecciona tu taberna:");
    navigator.geolocation.getCurrentPosition(showPosition,errorPosition);
  } else {
  	$('#location-modal').modal('toggle');
    alert("Este navegador no soporta Geolocalización.");
  }
}


function errorPosition(error){

	//$('#location-modal').modal('toggle');
	$("#text-tienda-modal").html("Selecciona tu taberna:");
	$("#store-localizated").hide();
	$("#tiendas_select_popup").show();
	$(".permitir-ubicacion-text").hide();

}


function showPosition(position) {

	//$("#logo-do").attr("src",module_dir+"views/img/loading.gif?t="+$.now());
	let latitude = position.coords.latitude;
	let longitude = position.coords.longitude;

	shops.map(function(o,i) { 
		o.distance =  distHaversine(
			{latitude:o.latitude,longitude:o.longitude},
			{latitude:latitude,longitude:longitude}
		); 
	});

	let shop = shops.reduce(function(res, obj) {
	    return (obj.distance < res.distance) ? obj : res;
	});

	//console.log(id_shop_current+" - "+shop.id_shop);

	//localStorage.setItem("id_shop_locate", shop.id_shop);


	/*if (id_shop_current == shop.id_shop){
		//console.log("Intentando cerrar modal...");
		$('#location-modal').modal('toggle');
		//$("#btnCancelarModal").click();
	}*/

	//$("#text-tienda-modal").html(shop.name);
	$("#text-tienda-modal").hide();
	$("#store-localizated").hide();
	$(".permitir-ubicacion-text").hide();
	//$("#btnAceptarModalTienda").removeAttr("disabled").removeClass("disabled");
	//$("#logo-do").attr("src",module_dir+"views/img/check.gif?t="+$.now());
	urlRedirect = "http://"+shop.domain+"/"+shop.virtual_uri;
	$("#tiendas_select_popup").val(urlRedirect);

}

let rad = function(x) {return x*Math.PI/180;}
function distHaversine(p1, p2) {
    let R = 6371; // earth's mean radius in km
    let dLat = rad(p2.latitude - p1.latitude);
    let dLong = rad(p2.longitude - p1.longitude);

    let a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(rad(p1.latitude)) * Math.cos(rad(p2.latitude)) * Math.sin(dLong/2) * Math.sin(dLong/2);
    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    let d = R * c;

    return d;
}









