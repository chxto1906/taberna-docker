/*
* 2007-2017 PrestaShop
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
*  @copyright 2007-2017 PrestaShop SA
*  @version   Release: $Revision$
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
var rtl1 = false;
jQuery(document).ready(function ($) {
    $(".timer").rvcountdown();
    if ($("body").hasClass("lang-rtl")) {
        rtl1 = true;
    }

    $("#dealoftheday-carousel").owlCarousel({
        rtl: rtl1,
        responsiveClass: true,
        nav: true,
        navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
        responsive:{
            0:{
                items:1
            },
            544:{
                items:1
            },
            768:{
                items:1
            },
            992:{
                items:1
            },
            1200:{
                items:1
            }
        }
    });

      $("#dealoftheday-carousel-odd").owlCarousel({
        rtl: rtl1,
        responsiveClass: true,
        nav: true,
        navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
        responsive:{
            0:{
                items:1
            },
            544:{
                items:1
            },
            768:{
                items:1
            },
            992:{
                items:1
            },
            1200:{
                items:1
            }
        }
    });

    

    var rvthumbcarousel = $(".product_list_thumb #thumb_carousel");
    rvthumbcarousel.owlCarousel({
        responsiveClass:true,
        nav:true,
        rtl: rtl1,
        mouseDrag: false,
        navText: ['<i class="material-icons"></i>', '<i class="material-icons"></i>'],
        responsive:{
        	0:{
        		items:2
        	},
        	544:{
        		items:3
        	},
        	768:{
        		items:2
        	},
        	992:{
        		items:2 
        	},
        	1200:{
        		items:2
        	}
        }
    });


    function displayImageThumb(imgThumb)
    {
        if (imgThumb.prop('href'))
        {
            var new_src = imgThumb.attr('source');

            if (imgThumb.parent().parent().parent().parent().parent().parent().parent().find('.mainimage').prop('src') != new_src)
            {
                imgThumb.parent().parent().parent().parent().parent().parent().parent().find('.mainimage').attr({
                    'src' : new_src,
                });
            }
            $(imgThumb).parent().parent().parent().find('.thumb-container > a').removeClass('selected');
            $(imgThumb).addClass('selected');
        }
    }

    $(function(){
        $('.product_list_thumb .thumb-container > a').click(function() {
            displayImageThumb($(this));
        });
    });
});
