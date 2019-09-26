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


// PRODUCCION //

const token = "DW979R0e-Fl8HTwYwHWj6sSUPaRLSvBIKSHzTjfI7-PT94PWFYc2GPPVSDVXRDbrg6W1W_r4NxZBCSICeEZn9IENm_j9FG6iDiyCGkrn6SzbWvH8K578wVd-X_usATnacAEYFeJqqfnUG5KQ8RPnmoWJZJi4ng5Uo4UKnoA6Q_aFIX6PmUwbpcMrONW3Z3-PUvwSfW-P7rkg24OgO90y5arBMVo9nb5f-HO9z-nyXfmiK3lcjmYf3q87CmbH2CbB8_pTS6qFofy5TIZj73-kA-AIgukqyQw47aSLrjb_Vx76BkK1V2vwtu3F_IiFeaJH29t_bGcUKD60wGRsSd3_swH3tVs";
const url = "https://pay.payphonetodoesposible.com/api/transaction/Deferred";
const passCode = "b52c37acf1a941c395915197642e39c9";


// DESARROLLO //
/*
const token = "DW979R0e-Fl8HTwYwHWj6sSUPaRLSvBIKSHzTjfI7-PT94PWFYc2GPPVSDVXRDbrg6W1W_r4NxZBCSICeEZn9IENm_j9FG6iDiyCGkrn6SzbWvH8K578wVd-X_usATnacAEYFeJqqfnUG5KQ8RPnmoWJZJi4ng5Uo4UKnoA6Q_aFIX6PmUwbpcMrONW3Z3-PUvwSfW-P7rkg24OgO90y5arBMVo9nb5f-HO9z-nyXfmiK3lcjmYf3q87CmbH2CbB8_pTS6qFofy5TIZj73-kA-AIgukqyQw47aSLrjb_Vx76BkK1V2vwtu3F_IiFeaJH29t_bGcUKD60wGRsSd3_swH3tVs";
const url = "https://pay.payphonetodoesposible.com/api/transaction/Deferred";
const passCode = "2457a64ab9584d05b7ea90ec9f6a2b2f";*/

const datos = {
				cardNumber: "", 
				expirationMonth: "", 
				expirationYear: "", 
             	holderName: "", 
             	securityCode: ""
            };

$(".datos-tarj").change(function(e) {
	let name = e.target.id;
	let value = e.target.value;
	/*if (name == "cardNumber") {
		let wordArray = CryptoJS.enc.Utf8.parse(value);
		let bin = CryptoJS.enc.Base64.stringify(wordArray);
		let body = { "bin" : bin };
		clean();
		$.ajax({
	        data:  body, //datos que se envian a traves de ajax
	        url:   url, //archivo que recibe la peticion
	        type:  'post', //método de envio
	        beforeSend: function (xhr) {
	        	xhr.setRequestHeader("Authorization", "Bearer " + token);
	        	disabledDeferred();
	        },
	        success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
	        	if (response.length > 0){
		        	$.each(response, function( index, value ) {
						$("#deferred").append("<option value='"+value.code+"'>"+value.name+"</option>"); 
					});
				} else {
					$("#deferred").append("<option value='00000000'>Corriente</option>"); 
				}
				enabledDeferred();
	        }
		});
	}*/
	datos[name] = value;
	proccessDatosEncode();
});


function proccessDatosEncode() {
	datos["expirationMonth"] = $("#expirationMonth").val();
	datos["expirationYear"] = $("#expirationYear").val();
	let key = CryptoJS.enc.Utf8.parse(passCode);
	let iv = CryptoJS.enc.Utf8.parse(''); 
	let encrypted = CryptoJS.AES.encrypt(JSON.stringify(datos), key,{ iv: iv });
	let codificado = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
	console.dir(codificado);
	$("#data").val(codificado);
}

function clean() {
	$("#deferred").html("");
}


function enabledDeferred() {
	$("#deferred").removeAttr("disabled");
	$("#deferred").removeClass("disabled");
}

function disabledDeferred() {
	$("#deferred").attr("disabled","disabled");
	$("#deferred").addClass("disabled");
}


