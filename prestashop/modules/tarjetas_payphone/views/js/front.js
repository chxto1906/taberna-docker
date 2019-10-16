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
/*
const token = "DW979R0e-Fl8HTwYwHWj6sSUPaRLSvBIKSHzTjfI7-PT94PWFYc2GPPVSDVXRDbrg6W1W_r4NxZBCSICeEZn9IENm_j9FG6iDiyCGkrn6SzbWvH8K578wVd-X_usATnacAEYFeJqqfnUG5KQ8RPnmoWJZJi4ng5Uo4UKnoA6Q_aFIX6PmUwbpcMrONW3Z3-PUvwSfW-P7rkg24OgO90y5arBMVo9nb5f-HO9z-nyXfmiK3lcjmYf3q87CmbH2CbB8_pTS6qFofy5TIZj73-kA-AIgukqyQw47aSLrjb_Vx76BkK1V2vwtu3F_IiFeaJH29t_bGcUKD60wGRsSd3_swH3tVs";
const url = "https://pay.payphonetodoesposible.com/api/transaction/Deferred";
const passCode = "b52c37acf1a941c395915197642e39c9";*/


// DESARROLLO //
const token = "DW979R0e-Fl8HTwYwHWj6sSUPaRLSvBIKSHzTjfI7-PT94PWFYc2GPPVSDVXRDbrg6W1W_r4NxZBCSICeEZn9IENm_j9FG6iDiyCGkrn6SzbWvH8K578wVd-X_usATnacAEYFeJqqfnUG5KQ8RPnmoWJZJi4ng5Uo4UKnoA6Q_aFIX6PmUwbpcMrONW3Z3-PUvwSfW-P7rkg24OgO90y5arBMVo9nb5f-HO9z-nyXfmiK3lcjmYf3q87CmbH2CbB8_pTS6qFofy5TIZj73-kA-AIgukqyQw47aSLrjb_Vx76BkK1V2vwtu3F_IiFeaJH29t_bGcUKD60wGRsSd3_swH3tVs";
const url = "https://pay.payphonetodoesposible.com/api/transaction/Deferred";
const passCode = "2457a64ab9584d05b7ea90ec9f6a2b2f";
let validate = false;
let type_card = null;
const datos = {
				cardNumber: "", 
				expirationMonth: "", 
				expirationYear: "", 
             	holderName: "", 
             	securityCode: ""
            };

$("#new_target").click(function(e) {
	e.preventDefault();
	$(".card_id").prop("checked", false);
	$("#id_card").val("");
	$("#cardHolder").val("");
	$("#content-form-pay").toggle();
});

$("#cancelar-new-pay").click(function(e) {
	e.preventDefault();
	$("#content-form-pay").hide();
});

$(".card_id").change(function(e) {
	let value = e.target.value;
	console.log(e.target.dataset.holder);
	let holder = encripta(e.target.dataset.holder,false);

	$("#id_card").val(value);
	$("#cardHolder").val(holder);
	$("#content-form-pay").hide();
});

function validateRequired() {
	let resulta = false;
	if (datos.cardNumber != "" && datos.expirationMonth != "" &&
	 datos.expirationYear != "" && datos.holderName != "" && datos.securityCode != "") {
		resulta = true;
	}
	return resulta;
}

/*$("#add-new-pay").click(function(e) {
	console.log("ingresóoo");
	e.preventDefault();
	if (validateRequired()) {
		if (validate) {
			saveCardDB();
		}else{
			alert("Número de tarjeta no es válido. Aceptamos VISA y MASTERCARD.");
		}
	}else{
		alert("Ingresa los datos completos de tu tarjeta");
	}
});*/

function saveCardDB() {
	let card_add = datos;
	let lastDigits = card_add.cardNumber;
	card_add.cardNumber = lastDigits.substring(lastDigits.length-4,lastDigits.length);
	$.ajax({
        data: {datos: card_add},
        url: '?fc=module&module=tarjetas_payphone&controller=cards',
        type: "POST",
        dataType: "json",
        beforeSend: function () {
            //$("#estado_enviando").css('visibility','visible');
        },
        success: function (data) {
            alert('Tarjeta agregada correctamente');
        },
        error: function (error) {
            alert("Ocurrión un problema al agregar tu tarjeta. Vuelve a intentarlo más tarde.")
        }
    });
}

function validateCard(value) {
	type_card = null;
	let cardnoVisa = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
	let cardnoMaster = /^(?:5[1-5][0-9]{14})$/;
	let result = false;
	if(value.match(cardnoVisa)){
		result = true;
		type_card = "VISA";
	}else{
		if(value.match(cardnoMaster)){
	    	result = true;
	    	type_card = "MASTERCARD";
	    }
	}
	return result;
}


$(".datos-tarj").change(function(e) {
	let name = e.target.id;
	let value = e.target.value;

	if (name == "cardNumber") {
		validate = validateCard(value);
		if (!validate){
			$("#cardNumber").addClass("error-pay-input");
			$("#bad-validation").show();
			$("#ok-validation").hide();
		}else{
			$("#cardNumber").removeClass("error-pay-input");
			$("#bad-validation").hide();
			$("#ok-validation").show();
			let lastDig = value.substring(value.length-4,value.length);
			$("#lastDigits").val(lastDig);
			$("#type_card").val(type_card);
		}
	}

	datos[name] = value;
	proccessDatosEncode();
});


function proccessDatosEncode() {
	datos["expirationMonth"] = $("#expirationMonth").val();
	datos["expirationYear"] = $("#expirationYear").val();
	//datos["add_card"] = $("#add_card").val();
	/*let key = CryptoJS.enc.Utf8.parse(passCode);
	let iv = CryptoJS.enc.Utf8.parse(''); 
	let encrypted = CryptoJS.AES.encrypt(JSON.stringify(datos), key,{ iv: iv });
	let codificado = encrypted.ciphertext.toString(CryptoJS.enc.Base64);*/

	let codificado = encripta(datos,true);
	console.dir(codificado);
	$("#data").val(codificado);
}


function encripta(valor, op) {
	let key = CryptoJS.enc.Utf8.parse(passCode);
	let iv = CryptoJS.enc.Utf8.parse('');
	let valorPro = op ? JSON.stringify(valor) : valor;
	let encrypted = CryptoJS.AES.encrypt(valorPro, key,{ iv: iv });
	let codificado = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
	return codificado;
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


