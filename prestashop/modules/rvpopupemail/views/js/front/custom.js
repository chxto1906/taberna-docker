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


    var jssor_1_SlideoTransitions = [
              [{b:0,d:20020,x:1000}],
              [{b:0,d:1620,x:800}],
              [{b:0,d:1000,x:-767,e:{x:6}},{b:21000,d:1000,x:-807,e:{x:5}}],
              [{b:0,d:520,r:-360}],
              [{b:0,d:520,r:-360}],
              [{b:-1,d:1,o:-0.35}],
              [{b:100,d:100,o:-1,e:{o:32}},{b:2300,d:100,o:1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:100,d:100,o:1,e:{o:32}},{b:200,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:200,d:100,o:1,e:{o:32}},{b:300,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:300,d:100,o:1,e:{o:32}},{b:400,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:400,d:100,o:1,e:{o:32}},{b:500,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:500,d:100,o:1,e:{o:32}},{b:600,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:600,d:100,o:1,e:{o:32}},{b:700,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:700,d:100,o:1,e:{o:32}},{b:800,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:800,d:100,o:1,e:{o:32}},{b:900,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:900,d:100,o:1,e:{o:32}},{b:1000,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1000,d:100,o:1,e:{o:32}},{b:1100,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1100,d:100,o:1,e:{o:32}},{b:1200,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1200,d:100,o:1,e:{o:32}},{b:1300,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1300,d:100,o:1,e:{o:32}},{b:1400,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1400,d:100,o:1,e:{o:32}},{b:1500,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1500,d:100,o:1,e:{o:32}},{b:1600,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1600,d:100,o:1,e:{o:32}},{b:1700,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1700,d:100,o:1,e:{o:32}},{b:1800,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1800,d:100,o:1,e:{o:32}},{b:1900,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:1900,d:100,o:1,e:{o:32}},{b:2000,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:2000,d:100,o:1,e:{o:32}},{b:2100,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:2100,d:100,o:1,e:{o:32}},{b:2200,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:2200,d:100,o:1,e:{o:32}},{b:2300,d:100,o:-1,e:{o:32}}],
              [{b:-1,d:1,o:-1},{b:100,d:600,o:0.2},{b:700,d:4300,o:0.2}],
              [{b:0,d:1160,x:783,e:{x:6}}],
              [{b:1160,d:840,x:667,y:34,e:{x:12,y:3}}],
              [{b:2780,d:520,x:-272,e:{x:6}},{b:4000,d:600,x:276,e:{x:5}}],
              [{b:3300,d:640,y:-145,e:{y:6}},{b:4000,d:600,y:149,e:{y:5}}],
              [{b:2020,d:760,y:-319,e:{y:6}},{b:4000,d:600,x:-320,e:{x:5}}],
              [{b:0,d:2000,x:-320,y:1200}],
              [{b:0,d:3000,x:-320,y:1200}],
              [{b:0,d:4000,x:-320,y:1200}]
            ];

            var jssor_1_options = {
              $AutoPlay: 1,
              $LazyLoading: 1,
              $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions,
                $Breaks: [
                  [{d:2000,b:21000}],
                  [{d:10000,b:5000}],
                  [{d:2000,b:4000}],
                  [{d:5000,b:5000}]
                ],
                $Controls: [{r:0},{r:0},{r:0},{r:0},{r:100},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100,e:2400},{r:100},{r:0},{r:0},{r:0}]
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };
    var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
    //ScaleSlider();
    var MAX_WIDTH = 980;
    function ScaleSlider() {
        var containerElement = jssor_1_slider.$Elmt.parentNode;
        var containerWidth = containerElement.clientWidth;

        if (containerWidth) {

            var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

            jssor_1_slider.$ScaleWidth(expectedWidth);
        }
        else {
            window.setTimeout(ScaleSlider, 30);
        }
    }


    //$(window).bind("load", ScaleSlider);
    //$(window).bind("resize", ScaleSlider);
    //$(window).bind("orientationchange", ScaleSlider);


});

var that = this;




/*#region responsive code begin*/







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

