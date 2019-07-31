/**
* Cash On Delivery With Fee
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2018 idnovate
*  @license   See above
*/

$(document).ready(function() {
    function updateTotalsWithFee() {
        var cart_total = $('#js-checkout-summary .cart-summary-line.cart-total').last();
        var total_ori_html = cart_total.html();
        var taxes_ori_html = $('#js-checkout-summary .cart-summary-line.cart-total').next().html();
        $("input[name='payment-option']").click(function () {
            var button_name = $("input[name='codfee_id']").next().next().next().next().next().next().next().next().next().next().attr('id');
            if ('pay-with-' + this.id === button_name) {
                $('#cart-subtotal-codfee').remove();
                $('#cart-subtotalsum-codfee').remove();
                $('<div class="cart-summary-line cart-summary-subtotals" id="cart-subtotal-codfee">' +
                    '<span class="label">' + $("input[name='codfee_text']").val() + '</span>' +
                    '<span class="value price">' + $("input[name='codfee_fee']").val() + '</span>' +
                    '</div>').insertAfter('#js-checkout-summary #cart-subtotal-shipping');
                $('<div class="cart-summary-line cart-summary-subtotals" id="cart-subtotalsum-codfee">' +
                    '<span class="label">' + $("input[name='codfee_text']").val() + '</span>' +
                    '<span class="value price">' + $("input[name='codfee_fee']").val() + '</span>' +
                    '</div>').insertBefore($('#js-checkout-summary .cart-summary-line.cart-total').prev());
                $('#js-checkout-summary .cart-summary-line.cart-total').find('span.value').html($("input[name='codfee_total']").val());
                if ($("input[name='codfee_tax_enabled']").val() == '1' && $("input[name='codfee_tax_display']").val() == '1') {
                    $('#js-checkout-summary .cart-summary-line.cart-total').next().find('span.value').html($("input[name='codfee_taxes']").val());
                    $('#js-checkout-summary .cart-summary-line.cart-total').prev().find('span.value').html($("input[name='codfee_taxes']").val());
                }
            } else {
                $('#cart-subtotal-codfee').remove();
                $('#cart-subtotalsum-codfee').remove();
                $('#js-checkout-summary .cart-summary-line.cart-total').first().html(total_ori_html);
                $('#js-checkout-summary .cart-summary-line.cart-total').next().html(taxes_ori_html);
            }
        });
    }
    function updateOrderSummaryWithFee() {
        var total_ori_html = $('.order-confirmation-table table tr td').last().html();
        var taxes_ori_html = $('.order-confirmation-table table tr').last().prev().find('td').last().html();
        $("input[name='payment-option']").click(function () {
            var button_name = $("input[name='codfee_id']").next().next().next().next().next().next().next().next().next().next().attr('id');
            if ('pay-with-' + this.id === button_name) {
                $('tr.cart-order-summary-codfee').remove();
                $('<tr class="cart-order-summary-codfee">' +
                    '<td>' + $("input[name='codfee_text']").val() + '</td>' +
                    '<td>' + $("input[name='codfee_fee']").val() + '</td>' +
                    '</tr>').insertBefore($('.order-confirmation-table table tr').last().prev());
                $('.order-confirmation-table table tr td').last().html($("input[name='codfee_total']").val());
                if ($("input[name='codfee_tax_enabled']").val() == '1' && $("input[name='codfee_tax_display']").val() == '1') {
                    $('.order-confirmation-table table tr').last().prev().find('td').last().html($("input[name='codfee_taxes']").val());
                }
            } else {
                $('tr.cart-order-summary-codfee').remove();
                $('.order-confirmation-table table tr td').last().html(total_ori_html);
                $('.order-confirmation-table table tr').last().prev().find('td').last().html(taxes_ori_html);
            }
        });
    }
    updateTotalsWithFee();
    updateOrderSummaryWithFee();
});
