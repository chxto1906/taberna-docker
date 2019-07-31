/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
$(function(){
    $(document).on('change', '#rvpc_specific_price', function () {
        if ($(this).val()) {
            var from = $(this).find('option:selected').data('from');
            var to = $(this).find('option:selected').data('to');
            $('#rvpc_from').val(from);
            $('#rvpc_to').val(to);
        }
    });

    $(document).on('click', '#rvpc-reset-countdown',function(){
        var id_countdown = $(this).data('id-countdown');

        $('#rvproductcountdown').find('input[type=text], select').val('');

        $.ajax({
            url: rvpc_ajax_url,
            data: {ajax: true, action: 'removeCountdown', id_countdown: id_countdown},
            method: 'post',
            success: function () {
                location.reload();
            }
        });
    })
});
