/**
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

jQuery(document).ready(function($){
	$(".rvall-pattern-show").click(function(){
		$('.rvall-pattern-show').removeClass('rv_custom_setting_active');
		$(this).addClass('rv_custom_setting_active');
		var pattern = $(this).attr('id');
		$(document).find('#rvcustomsetting_pattern').val(pattern);
	});

	var tab_number = $('#rvcustom-setting-tab-number').val();
	$('.rvadmincustom-setting').find('.panel').hide();
	$(tab_number).show();


	$('.rvadmincustom-setting-tab').click(function(event){
		var tab_number = $(this).attr('tab-number');
		$('.rvadmincustom-setting-tab').removeClass('rvadmincustom-setting-active');
		$(this).addClass('rvadmincustom-setting-active');
		$('#rvcustom-setting-tab-number').val(tab_number);
		$('.rvadmincustom-setting').find('.panel').hide();
		$(tab_number).show();
	});

	$('input[type=radio][name=RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS]').on('change', function() {

		if($('input#active_on[type=radio][name=RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
	        	$(this).closest('.form-group').next().show();
	        	$(this).closest('.form-group').next().next().hide();
	    }else{
	        	$(this).closest('.form-group').next().next().show();    	
	        	$(this).closest('.form-group').next().hide();
	    }
	});

	$('input[type=radio][name=RVCUSTOMSETTING_ADD_CONTAINER]').on('change', function() {
		 if ($('#RVCUSTOMSETTING_ADD_CONTAINER_on').is(':checked')) {
	        $(this).closest('.form-group').next().show();

	        if($('input#active_on[type=radio][name=RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
	        	$(this).closest('.form-group').next().next().show();
	        }else{
	        	$(this).closest('.form-group').next().next().next().show();    	
	        }
	     }else{
			$(this).closest('.form-group').next().hide();
			$(this).closest('.form-group').next().next().hide();
			$(this).closest('.form-group').next().next().next().hide();
	     }
	});

	
	$('input[type=radio][name=RVCUSTOMSETTING_THEME_OPTION]').on('change', function(){
		var val = $(this).val();
		if(val.match(/theme_custom/g)){
			$(this).closest('.form-group').parent().parent().parent().next().show();
			$(this).closest('.form-group').parent().parent().parent().next().next().show();	
		}else{
			$(this).closest('.form-group').parent().parent().parent().next().hide();
			$(this).closest('.form-group').parent().parent().parent().next().next().hide();	
		}
	
	});


});
window.onload=function(){	

	//theme option
	var val = $('input[type=radio][name=RVCUSTOMSETTING_THEME_OPTION]:checked').val();
	if(typeof val != "undefined"){
			if(val.match(/theme_custom/g)){
				$('input[type=radio][name=RVCUSTOMSETTING_THEME_OPTION]').closest('.form-group').parent().parent().parent().next().show();
				$('input[type=radio][name=RVCUSTOMSETTING_THEME_OPTION]').closest('.form-group').parent().parent().parent().next().next().show();	
			}else{
				$('input[type=radio][name=RVCUSTOMSETTING_THEME_OPTION]').closest('.form-group').parent().parent().parent().next().hide();
				$('input[type=radio][name=RVCUSTOMSETTING_THEME_OPTION]').closest('.form-group').parent().parent().parent().next().next().hide();	
			}

		//box layout and full layout
		if($('#RVCUSTOMSETTING_ADD_CONTAINER_off').is(':checked')){
			$('#RVCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().hide();
			$('#RVCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().hide();
			$('#RVCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().hide();
		}else{
			//bacground color or patten
			if($('input#active_on[type=radio][name=RVCUSTOMSETTING_BACKGROUND_IMAGE_PATTERN_STATUS]').is(':checked')){
		        	$('#RVCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().show();
		        	$('#RVCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().hide();
		    }else{
		        	$('#RVCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().next().show();    	
		        	$('#RVCUSTOMSETTING_ADD_CONTAINER_off').closest('.form-group').next().next().hide();
		    }
		}
	}


}
