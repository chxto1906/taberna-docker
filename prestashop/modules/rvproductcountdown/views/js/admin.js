/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
$(function () {
    $(document).on('click', '.remove-countdown', function(e) {
        if (!confirm(rvpc_remove_confirm_txt)) {
            return false;
        }

        var id_countdown = $(this).data('id-countdown');
        var $this = $(this);

        $.ajax({
            url: rvpc_ajax_url,
            data: {ajax: true, action: 'removeCountdown', id_countdown: id_countdown},
            method: 'post',
            success: function () {
                $this.parents('tr').fadeOut(500, function(){
                    $(this).remove();
                });
            }
        });
        
        e.preventDefault();
    });

    $('#rvpc_show_pro').on('click', function (e) {
        $('#rvpc_pro_features_content').slideDown();
        $(this).remove();

        e.preventDefault();
    });
});