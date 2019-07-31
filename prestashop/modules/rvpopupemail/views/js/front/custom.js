/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

jQuery(document).ready(function($) {

    /* handle requery  to email subscription */
    if (jQuery.cookie('show_popup_email') == null) {
         jQuery.cookie('show_popup_email','show', { path: '/' });
    }

    $('#js_rvsubmitnewsletter').on('click',function(even){
        even.preventDefault();
        var requestData = {
            email:$('#rvemail').val(),
            action:$('#rvaction').val(),
        };
        var refresh_url =  $(this).closest('form').attr('data-url');

        $.post(refresh_url,requestData,function(data){
            $('.result_email_subscription').html(data);
        },'json');
    });

    /*set hidden/show emailsub */
    $('#rv_popup_hide').change(function() {
        if ($(this).is(":checked")){
           jQuery.cookie('show_popup_email','hidden', {  expires : 7 ,path: '/' });
        }else{
           jQuery.cookie('show_popup_email','show', { path: '/' });
		}

    });
    $('#popup_email_subscription .close, #bg_popup_email_subscription').on('click',function(){
    	$('#popup_email_subscription').addClass('bounceOutUp').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
        	$(this).hide().removeClass('bounceOutUp'); 
        });
        $('#bg_popup_email_subscription').hide();
        $('body').removeClass('modal-open'); 
    });

});

window.onload = function(){
	if (jQuery.cookie('show_popup_email') != 'hidden' && $('body').hasClass( "page-index" )) {
		 setTimeout(function(){
            $('#bg_popup_email_subscription').show();
            $('body').has('#bg_popup_email_subscription').addClass('modal-open');
            $('#popup_email_subscription').show().addClass('bounceInDown').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            	$(this).removeClass('bounceInDown');  
            });
        },2000);
	}
};

