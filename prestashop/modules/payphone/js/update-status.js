/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $("#check-payment").on('click', function (ev) {
        ev.preventDefault();
        $.ajax({
            type: 'POST',
            url: baseDir + 'modules/payphone/ajax.php',
            data: 'method=updateTransaction&id_order=' + $('#id_order').val(),
            dataType: 'json',
            success: function (response) {
                console.log(response);
            },
            done: function (data, textStatus, jqXHR) {
                console.log(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(jqXHR.responseText);
            }});
    })
});



