/**
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
jQuery(document).ready(function ($) {
		 document.documentElement.style.setProperty('--rv-brand-primary', rv_brand_primary);	  
		 document.documentElement.style.setProperty('--rv-brand-secondary', rv_brand_secondary);	
});


 if(RVFRONTSIDE_THEME_SETTING_SHOW == "1"){
    jQuery(document).ready(function($){

    var customeColor = true;
    var cssPath = prestashop.urls.css_url;

    if (typeof(Storage) == "undefined") {
      alert('Sorry! No Web Storage support..');
    }

    Storage.prototype.setObj = function(key, obj) {//set method
        return this.setItem(key, JSON.stringify(obj))
    }
    Storage.prototype.getObj = function(key) {//get method
        return JSON.parse(this.getItem(key))
    }

    function getUrlVars()//get param
	{
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}

    var demo_theme = getUrlVars()["demo-theme"];
    if(typeof demo_theme != "undefined"){
    	resetCustomSetting();
    	//console.log(demo_theme);
    	if(demo_theme == "default"){
    	}else if(demo_theme == "full-width"){
    	}else if(demo_theme == "box-width"){
    		localStorage.setObj("box-layout", true);
    	}
    }

    $('#themecolor1').minicolors();
    $('#themecolor2').minicolors();
    $('#themebgcolor2').minicolors();

    

    // ***************************START THEME_CONTROL
    function resetCustomSetting(){
      	localStorage.setObj("themeControl", true);
        localStorage.setObj("theme", "default_theme");
        localStorage.setObj("box-layout", false);
        localStorage.setObj("right-sticky", true);
        localStorage.setObj("themeColor1", rv_brand_primary);//default color1
        localStorage.setObj("themeColor2", rv_brand_secondary);//default color2
        localStorage.setObj("theme-bg-pattern", "url("+prestashop.urls.img_url+"pattern/pattern14.png)");//save localStorage
        localStorage.setObj("theme-bg-color", '#dd0000');//default color2
        localStorage.setObj("theme-bg-status", true);//patten bg
    }
    function ColorLuminance(hex, lum) {

      // validate hex string
      hex = String(hex).replace(/[^0-9a-f]/gi, '');
      if (hex.length < 6) {
        hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
      }
      lum = lum || 0;

      // convert to decimal and change luminosity
      var rgb = "#", c, i;
      for (i = 0; i < 3; i++) {
        c = parseInt(hex.substr(i*2,2), 16);
        c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
        rgb += ("00"+c).substr(c.length);
      }

      return rgb;
    }
    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    var IsRvtheme_control = false;
    $('html').on('click',function(){
       if ($('.rvtheme-control').hasClass('active') && !IsRvtheme_control) {
          $('.rvtheme-control').removeClass('active')
        } 
       IsRvtheme_control = false;
    });

    $('.rvcontrol-icon').on('click',function(event) {
       if ($('.rvtheme-control').hasClass('active'))
          $('.rvtheme-control').removeClass('active')
        else 
          $('.rvtheme-control').addClass('active')
         event.stopPropagation();
    });

    $('.rvtheme-control').on('click',function(event){
       IsRvtheme_control = true;
    });

      $('#toggle-one').bootstrapToggle();
      $('.rvtheme-color-one, .rvtheme-color-two').hide();
      $('.rvtheme-background-patten, .rvtheme-background-color').hide();
      

      $('.rvcontrol-reset').on('click',function(e){
        resetCustomSetting();
        location.reload(); 
      });

      
      // ***************************END THEME_CONTROL

      function setCustomeTheme(){
        if(customeColor){//update on load color selection box
          customeColor = false;
          $('#themecolor1').val(localStorage.getObj("themeColor1"));
          $('#themecolor2').val(localStorage.getObj("themeColor2"));  
          
          $('#themecolor1').parent().find('.minicolors-swatch-color').css('background-color',localStorage.getObj("themeColor1"));
          $('#themecolor2').parent().find('.minicolors-swatch-color').css('background-color',localStorage.getObj("themeColor2"));
          
        }
          var themeColor1 = $('#themecolor1').val();
          var themeColor2 = $('#themecolor2').val();

          localStorage.setObj("themeColor1", themeColor1);//save localStorage
          localStorage.setObj("themeColor2", themeColor2);//save localStorage

          document.documentElement.style.setProperty('--rv-brand-primary', themeColor1);	  
		  document.documentElement.style.setProperty('--rv-brand-secondary', themeColor2);
      }

      $('#themecolor1, #themecolor2').on('change',function(e){
        setCustomeTheme();
      });

	 function setDefaultTheme(){
        document.documentElement.style.setProperty('--rv-brand-primary', rv_default_brand_primary);	  
		document.documentElement.style.setProperty('--rv-brand-secondary', rv_default_brand_secondary);	
      }

      $('.rvtheme-control #select_theme').on('change',function(e){
        e.preventDefault();
        var themeVal = $('.rvtheme-control #select_theme').val();

        localStorage.setObj("theme", themeVal);//save localStorage

        if(themeVal.match("default_theme")){
			setDefaultTheme();
        	$('.rvtheme-color-one, .rvtheme-color-two').hide();
        }else if(themeVal.match(/theme_custom/g)){
            $('.rvtheme-color-one, .rvtheme-color-two').show();
              setCustomeTheme();  
        }
      });

      $('.rvtheme-box-layout-option').on('click',function(e){
        if($(this).find('.toggle.btn.btn-default').hasClass('off')){

            $('#page').addClass('container rvbox-layout');

            $('.rvtheme-background-patten, .rvtheme-background-color').show();
            $('body').css('background-image',localStorage.getObj("theme-bg-pattern"));
            localStorage.setObj("box-layout", true);//save localStorage
        }else{
            $('#page').removeClass('container rvbox-layout');

            $('.rvtheme-background-patten, .rvtheme-background-color').hide();
            $('body').css('background-image',"");
            localStorage.setObj("box-layout", false);//save localStorage
        }
      });
      $('.rvtheme-pattern-image').on('click',function(e){
          $('body').css('background-image',$(this).css('background-image'));
          localStorage.setObj("theme-bg-pattern", $(this).css('background-image'));//save localStorage
          $('body').css('background-color','');
          localStorage.setObj("theme-bg-status",true);
      });

      $('#themebgcolor2').on('change',function(e){
          $('body').css('background-color',$(this).val());
          $('body').css('background-image',"");     
          localStorage.setObj("theme-bg-status",false);
          localStorage.setObj("theme-bg-color",$(this).val());

          
      });


      $('.rvtheme-right-sticky-option').on('click',function(e){
        if($(this).find('.toggle.btn.btn-default').hasClass('off')){
          $('.rvright-sticky').show();     
          localStorage.setObj("right-sticky", true);//save localStorage 
        }else{
          $('.rvright-sticky').hide();
          localStorage.setObj("right-sticky", false);//save localStorage  
        }
      });


      $(window).load(function(){
        if(!localStorage.getObj("themeControl")){
          resetCustomSetting();
        }

        $('.rvtheme-control #select_theme option[value="'+localStorage.getObj("theme")+'"]').prop('selected',true);

          
        if(localStorage.getObj("theme").match(/theme_custom/g)){
            $('.rvtheme-color-one, .rvtheme-color-two').show();
            setCustomeTheme();
          }else{
            $('.rvtheme-color-one, .rvtheme-color-two').hide();
            setDefaultTheme();
        }

        if(localStorage.getObj("box-layout")){
          $('.rvtheme-background-patten, .rvtheme-background-color').show();

          if(localStorage.getObj("theme-bg-status")){
            $('body').css('background-image',localStorage.getObj("theme-bg-pattern"));
          }else{
            $('body').css('background-color',localStorage.getObj("theme-bg-color"));
          }
          
          $('.rvtheme-box-layout-option').trigger('click');
        }
        $('#themebgcolor2').val(localStorage.getObj("theme-bg-color"));
          $('#themebgcolor2').parent().find('.minicolors-swatch-color').css('background-color',localStorage.getObj("theme-bg-color"));

        if(!localStorage.getObj("right-sticky")){
          $('.rvtheme-right-sticky-option').trigger('click');
        }
      });
    });
 }


