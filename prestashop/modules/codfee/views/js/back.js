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
 *  @copyright 2017 idnovate
 *  @license   See above
 */

jQuery('document').ready(function() {
    $("select[name=type]").change(function() {
        toggleFields($(this).attr('name'));
    });
    toggleFields('type');
});

function toggleFields(fieldName)
{
    if ($('#'+fieldName).val() !== '3') {
        $('.form-group').each(function() {
            if ($(this).find('.toggle_'+fieldName).length > 0) {
                if (!$(this).hasClass('translatable-field')) {
                    $(this).slideDown('slow');
                }
                if (id_language) {
                    if ($(this).hasClass('lang-'+id_language)) {
                        $(this).slideDown('slow');
                    }
                } else {
                    if ($(this).hasClass('lang-1')) {
                        $(this).slideDown('slow');
                    }
                }
            }
        });
        $("input[name='round']").parent().parent().parent().show();
        $("input[name='free_on_freeshipping']").parent().parent().parent().show();
    } else {
        $("input[name='round']").parent().parent().parent().hide();
        $("input[name='free_on_freeshipping']").parent().parent().parent().hide();
        $('.form-group').each(function() {
            if ($(this).find('.toggle_'+fieldName).length > 0) {
                $(this).slideUp();
            }
        });
    }
}
