/*
 * Custom code goes here.
 * A template should always ship with an empty custom.js
 */

 var responsiveflag = false;
 var responsiveflag1 = false;
 var rtl1 = false;

 $(document).ready(function () {
 	if ($("body").hasClass("lang-rtl")) {
 		rtl1 = true;
 	}
 	responsiveResize();
 	accordionCart();
 	bindGrid();
 	rvimglazyload();
 	$('.owl-carousel').on('translate.owl.carousel', function(e) {
 		rvimglazyload()
 	});
 	$(window).resize(responsiveResize);

 	initialize_owl($('#rv_featured_product, #rv_new_product, #rv_bestseller_product, #rv_special_product'));
 	initialize_owl($('#featuredproduct-carousel'));
 	
 	
 	initialize_owl($('#crossselling-carousel'));
 	initialize_owl($('#productscategory-carousel'));
 	prdImgCarousel("#main .product-images");
 	function initialize_owl(el) {
 		el.owlCarousel({
 			responsiveClass:true,
 			nav:true,
 			loop: true,
 			rtl: rtl1,
 			navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 			responsive:{
 				0:{
 					items:1
 				},
 				544:{
 					items:2
 				},
 				768:{
 					items:3
 				},
 				992:{
 					items:4
 				},
 				1200:{
 					items:5
 				}
 			}
 		});
 	}

 	/*======  Carousel For all left pro..and  leftbanner ==== */
 	product_banner($('#newproduct-carousel, #bestseller-carousel, #leftbanner-carousel'));
 	function product_banner(el) {
 		el.owlCarousel({
 			responsiveClass:true,
 			nav:true,
 			loop: false,
 			autoplay: !0,
 			rtl: rtl1,
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
 	}


		 /*======  Carousel For special-carousel ==== */
		 var rv_special_product= $("#special-carousel");
		 rv_special_product.owlCarousel({
		 	responsiveClass:true,
		 	nav:true,
		 	loop: true,
		 	rtl: rtl1,
		 	navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
		 	responsive:{
		 		0:{
		 			items:1
		 		},
		 		544:{
		 			items:2
		 		},
		 		768:{
		 			items:3
		 		},
		 		992:{
		 			items:4
		 		},
		 		1200:{
		 			items:4
		 		}
		 	}
		 });

 	/*======  Carousel For rv_cat_featured ==== */
 	var rv_category = $("#rv_cat_featured");
 	rv_category.owlCarousel({
 		responsiveClass:true,
 		autoplay: !0,
 		loop: !0,
 		nav:true,
 		rtl: rtl1,
 		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 		responsive:{
 			0:{
 				items:1
 			},
 			544:{
 				items:2
 			},
 			768:{
 				items:2
 			},
 			992:{
 				items:3
 			},
 			1200:{
 				items:3
 			}
 		}
 	});


 	/*======  Carousel For accessories ==== */
 	var rvaccessories = $("#accessories-carousel");
 	rvaccessories.owlCarousel({
 		responsiveClass:true,
 		nav:true,
 		loop: false,
 		autoplay: !0,
 		rtl: rtl1,
 		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 		responsive:{
 			0:{
 				items:1
 			},
 			544:{
 				items:3
 			},
 			768:{
 				items:3
 			},
 			992:{
 				items:5
 			},
 			1200:{
 				items:5
 			}
 		}
 	});

 	

 	/*======  Carousel For accessories ==== */
 	var rvservices = $("#rvservice-carousel");
 	rvservices.owlCarousel({
 		responsiveClass: !0,
 		nav: !1,
 		autoplay: !0,
 		loop: !0,
 		rtl: rtl1,
 		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 		responsive: {
 			0:{
 				items:1
 			},
 			544:{
 				items:3
 			},
 			768:{
 				items:3
 			},
 			992:{
 				items:4
 			},
 			1200:{
 				items:4
 			}
 		}
 	});

 	/*======  Carousel For Top Banner ==== */
 	var rvtopbanners = $("#rvtopbanners-carousel");
 	rvtopbanners.owlCarousel({
 		responsiveClass: !0,
 		nav: !1,
 		autoplay: !0,
 		loop: !0,
 		rtl: rtl1,
 		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 		responsive: {
 			0: {
 				items: 2
 			},
 			544: {
 				items: 3
 			},
 			768: {
 				items: 3
 			},
 			992: {
 				items: 3
 			},
 			1200: {
 				items: 3	
 			}
 		}
 	});

 	/*======  Carousel For Manufacturer Logo ==== */
 	var rvmanufacturer = $("#manufacturer-carousel");
 	rvmanufacturer.owlCarousel({
 		responsiveClass:true,
 		nav:true,
 		autoplay: !0,
 		loop: !0,
 		rtl: rtl1,
 		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 		responsive:{
 			0:{
 				items:1
 			},
 			544:{
 				items:3
 			},
 			768:{
 				items:4
 			},
 			992:{
 				items:4
 			},
 			1200:{
 				items:5
 			}
 		}
 	});

 	/*======  Carousel For Smart Blog ==== */
 	var rvsmartblog = $("#smartblog-carousel");
 	rvsmartblog.owlCarousel({
 		responsiveClass:true,
 		autoplay: !0,
 		loop: !0,
 		nav:true,
 		rtl: rtl1,
 		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 		responsive:{
 			0:{
 				items:1
 			},
 			544:{
 				items:2
 			},
 			768:{
 				items:2
 			},
 			992:{
 				items:3
 			},
 			1200:{
 				items:3
 			}
 		}
 	});

 	/*======  Carousel For Sub-Category List ==== */
 	var rvsubcategory = $("#subcategory-carousel");
 	rvsubcategory.owlCarousel({
 		responsiveClass:true,
 		nav:true,
 		rtl: rtl1,
 		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 		responsive:{
 			0:{
 				items:2
 			},
 			544:{
 				items:3
 			},
 			768:{
 				items:3
 			},
 			992:{
 				items:3
 			},
 			1200:{
 				items:4
 			}
 		}
 	});

 	/*======  Carousel For instablock ==== */
 	var rvinsta = $("#instablock-carousel");
 	rvinsta.owlCarousel({
 		responsiveClass: !0,
 		nav: !0,
 		loop: !0,
 		autoplay: !0,
 		autoplayTimeout: 5000,
 		rtl: rtl1,
 		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
 		responsive: {
 			0: {
 				items: 1
 			},
 			544: {
 				items: 2
 			},
 			768: {
 				items: 3
 			},
 			992: {
 				items: 4
 			},
 			1200: {
 				items: 5
 			},
 			1400: {
 				items: 6
 			}
 		}
 	});

 	var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
 	if(!isMobile) {
 		if($(".parallax").length) {
 			$(".parallax").sitManParallex({  invert: false });
 		};
 	} else {
 		$(".parallax").sitManParallex({  invert: true });
 	}

 	/*====== Search Toggle ==== */
 	$(".searchtoggle").on("click", function (event) {
 		$(this).toggleClass('active').parent().find('.rvsearchtoggle').stop().slideToggle('medium');
 		event.stopPropagation();
 	});

 	/*====== Back to Top Button ==== */
 	$(window).scroll(function() {
 		if ($(this).scrollTop() > 500) {
 			$('.backtotop').fadeIn(500);
 		} else {
 			$('.backtotop').fadeOut(500);
 		}
 	});							
 	$('.backtotop').click(function(e) {
 		e.preventDefault();		
 		$('html, body').animate({scrollTop: 0}, 800);
 	});

 });

function prdImgCarousel(prdId) {
	$(prdId).owlCarousel({
		responsiveClass:true,
		nav:true,
		rtl: rtl1,
		navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
		responsive:{
			0:{
				items:2
			},
			480:{
				items:3
			},
			768:{
				items:2
			},
			992:{
				items:3
			},
			1200:{
				items:4
			}
		}
	});
}

$(window).load(function() {
	productElevateZoom();
});

function productElevateZoom()
{
	$('#rvzoom').elevateZoom({
		zoomType: "inner",
		cursor: "crosshair",
		gallery: "rv-gellery",
		galleryActiveClass: "active",
		zoomWindowFadeIn: 500,
		zoomWindowFadeOut: 500
	});
}
/*====== Top menu ==== */
$('.topmenu .title_block').click(function() {
	$('.topmenu .title_block').toggleClass('active');
	$('.topmenu .menu.js-top-menu').slideToggle("2000")
});

function accordionCart()
{

	$('#header .cart_block ul.products').slimScroll({
		height: '100%'
	});
}
function accordionFooter(status)
{
	if(status == 'enable')
	{
		$('#footer h4.title_block').on('click', function(e){
			$(this).toggleClass('active').parent().find('.toggle-footer').stop().slideToggle('medium');
			e.preventDefault();
		})
		$('#footer').addClass('accordion').find('.toggle-footer').slideUp('fast');
	}
	else
	{
		$('#footer h4.title_block').removeClass('active').off().parent().find('.toggle-footer').removeAttr('style').slideDown('fast');
		$('#footer').removeClass('accordion');
	}
}

function accordion(status)
{
	if(status == 'enable')
	{
		var accordion_selector = '#right-column .block .title_block, #left-column .block .title_block';

		$(accordion_selector).on('click', function(e){
			$(this).toggleClass('active').parent().find('.block_content').stop().slideToggle('medium');
			e.preventDefault();
		});
		$('#right-column, #left-column').addClass('accordion').find('.block .block_content').slideUp('fast');
	}
	else
	{
		$('#right-column .block .title_block, #left-column .block .title_block').removeClass('active').off().parent().find('.block_content').removeAttr('style').slideDown('fast');
		$('#left-column, #right-column').removeClass('accordion');
	}
}

function responsiveResize()
{
	if ($(window).width() <= 991 && responsiveflag == false) {
		accordion('enable');
		accordionFooter('enable');
		responsiveflag = true;
	} else if ($(window).width() >= 992) {
		accordion('disable');
		accordionFooter('disable');
		responsiveflag = false;
	}

}


function bindGrid()
{
	var view = $.totalStorage('display');

	if (view && view != 'grid')
		display(view);
	else
		$('.display').find('#grid').addClass('selected');

	$(document).on('click', '#grid', function(e){
		e.preventDefault();
		$('#products div.products').animate({ opacity: 0 }, 400);
		setTimeout(function() { display('grid'); }, 400)
		$('#products div.products').animate({ opacity: 1 }, 400);
	});

	$(document).on('click', '#list', function(e){
		e.preventDefault();
		$('#products div.products').animate({ opacity: 0 }, 400)
		setTimeout(function() {display('list');  }, 400)
		$('#products div.products').animate({ opacity: 1 }, 400);
	});
}

function display(addClass) 
{
	var removeClass = "list" , addClassArticle = "col-xs-12 col-sm-6 col-md-4 col-lg-6 col-xl-3", removeClassArticle = "col-xs-12";
	var addClassThumnail = "" , addClassPrdtDesc = "", isGrid = true;
	if(addClass == "list"){
		addClassPrdtDesc = "col-xs-12 col-sm-6 col-md-8 col-lg-8";addClassThumnail = "col-xs-12 col-sm-6 col-md-4 col-lg-4";
		isGrid = false; removeClass = "grid";
		addClassArticle = "col-xs-12"; removeClassArticle = "col-xs-12 col-sm-6 col-md-4 col-lg-6 col-xl-3";
	}
	$('#products div.products').removeClass(removeClass).addClass(addClass);
	$('#products div.products > article').removeClass(removeClassArticle).addClass(addClassArticle);
	$('#products div.products > article').each(function(index, element) {
		var html = '';
		html += '<div class="product-container">';
		if(!isGrid){  html += '<div class="row">'; }
		html += '<div class="thumbnail-container ' + addClassThumnail + '">' + $(element).find('.thumbnail-container').html() + '</div>';
		if(!isGrid)	{
			html += '<div class="product-description ' + addClassPrdtDesc + '">';
			html += '<h1 class="h3 product-title" itemprop="name">'+ $(element).find('.product-title').html() + '</h1>';

			var hookcomment = $(element).find('.comments_note').html();
			if (hookcomment != null) {
				html += '<div class="comments_note" itemprop="aggregateRating" itemscope="" itemtype="https://schema.org/AggregateRating">' + hookcomment + '</div>'
			}

			var price = $(element).find('.product-price-and-shipping').html();
			if (price != null) {
				html += '<div class="product-price-and-shipping">'+ price + '</div>';
			}
			html += '<p class="product-desc" itemprop="description">'+ $(element).find('.product-desc').html() + '</p>';
			var colorList = $(element).find('.highlighted-informations').html();
			if (colorList != null) {
				html += '<div class="highlighted-informations hidden-sm-down">'+ colorList +'</div>';
			}
		}
		if(!isGrid)	{
			// html += '<div class="product-cart-btn">'+ $(element).find('.product-cart-btn').html() +'</div>';
			html += '<div class="product-buttons">'+ $(element).find('.product-buttons').html() +'</div>';
		}
		html += '</div>';
		html += '</div>';
		if(isGrid)	{
			html += '</div>';
		}
		$(element).html(html);
	});
	$('.display').find('li#'+ addClass).addClass('selected');
	$('.display').find('li#'+ removeClass).removeAttr('class');
	rvimglazyload();
	$.totalStorage('display', addClass);
}

var max_link = 5;
var items = $('#rvheaderlink_block ul li');
var surplus = items.slice(max_link, items.length);
surplus.wrapAll('<li class="more item"><ul class="more-link clearfix">');
$('.more').prepend('<a href="#" class="top" title="More">More</a>');
$('.more').mouseover(function() {
	$(this).children('ul').addClass('show-link')
})
$('.more').mouseout(function() {
	$(this).children('ul').removeClass('show-link')
});


/*--------- Start js for menu scroll to nav-full-width -------------*/
function header() {
	if (jQuery(window).width() > 1200) {
		if (jQuery(this).scrollTop() > 300) {
			jQuery('.header-top').addClass("fixed");
			jQuery('.nav-full-width .topmenu').prependTo(".header-top .position-static");
		} else {
			jQuery('.header-top').removeClass("fixed");
			jQuery('.header-top .position-static .topmenu').prependTo(".nav-full-width .position-static");
		}
	} else {
		jQuery('.header-top').removeClass("fixed");
		jQuery('.header-top .position-static .topmenu').prependTo(".nav-full-width .position-static");
	}
}
$(document).ready(function() {
	header()
});
jQuery(window).resize(function() {
	header()
});
jQuery(window).scroll(function() {
	header()
});

/*--------- Start js for link-block -------------*/

function accordionMenu(status) {
	if ($(document).width() <= 991) {
		$('#rvheaderlink_block ul.bullet').appendTo('#mobile_top_menu_wrapper #_mobile_top_menu');
	} 
	else {
		$('#_desktop_top_menu ul.bullet').appendTo('#rvheaderlink_block .rvheaderlink_content');
		$('#checkout #mobile_top_menu_wrapper #_mobile_top_menu ul.bullet').appendTo('#rvheaderlink_block .rvheaderlink_content');
	}
}
$(document).ready(function() {
	accordionMenu();
});
$(window).resize(function() {
	accordionMenu();
});

/*--------- End js for link-block -------------*/

function stickyHeader() {
	var fixed = $(".full-header");
	var height = parseInt(fixed.height());
	var outerHeight = parseInt($("#header").height());
	$(window).scroll(function() {
		if ($(window).width() > 991) {
			if ($(this).scrollTop() > 300) {
				fixed.addClass("fixed");
				$('header#header').addClass("header-length").css('marginTop', height)
			} else {
				fixed.removeClass("fixed");
				$('header#header').removeClass("header-length").removeAttr('style')
			}
		} else {
			fixed.removeClass("fixed");
			$('header').removeClass("header-length").removeAttr('style')
		}
	})
}
$(document).ready(function() { stickyHeader() });
$(window).resize(function() { stickyHeader() });

function rvimglazyload() {
	jQuery('img.lazy').Lazy({
		scrollDirection: 'vertical',
		visibleOnly: !0,
		onError: function(element) {
			console.log('error loading ' + element.data('src'))
		},
		afterLoad: function(element) {
			element.addClass('loaded');
			element.attr({
				'data-lazyloading': 1
			})
		},
	})
}



/******* HENRY ********/

$('.item-change-tienda').click(function(e){
  	urlRedirect = e.target.title;
  	if (urlRedirect != "none")
  		window.location.href = urlRedirect;
});




$(window).scroll(function () {
	rv_animated_contents();
});
$(window).load(function () {
	rv_animated_contents();
});

function rv_animated_contents() {
	$(".rv-animate-element:in-viewport").each(function (i) {
		var $this = $(this);
		if (!$this.hasClass('rv-in-viewport')) {
			setTimeout(function () {
				$this.addClass('rv-in-viewport');
			}, 30 * i);
		}
	});
}


/*======  rv_cookie ==== */

var IsAllowCookie = false; 
$(document).on('click', '#allowcookie', function(e){
	IsAllowCookie = true;
  	document.getElementsByClassName("footer-container-after-inner")[0].classList.remove("cookie-fixed");	
});

$(window).scroll(function() {    
	var scroll = $(window).scrollTop();
	if (scroll >= 300) {
		$(".cookie-notice").addClass("fixed");
		if ($( ".cookie-notice" ).hasClass( "fixed" ) && $( ".cookie-notice" ).css( "display" ) != "none"  && !IsAllowCookie) {
			$(".footer-container-after .footer-container-after-inner").addClass("cookie-fixed");
		}
	} else {
		$(".cookie-notice").removeClass("fixed");
		$(".footer-container-after .footer-container-after-inner").removeClass("cookie-fixed");
	} 
	IsAllowCookie = false;
});




	/* -----------Start carousel For RV-Megamenu Product ----------- */

	var rvmegamenu_single_pro = $("#rv-menu-horizontal .product-block .ul-column");
	rvmegamenu_single_pro.addClass("owl-carousel");
	rvmegamenu_single_pro.owlCarousel({
		responsiveClass:true,
		autoplay: !0,
		stopOnHover: true,
		nav:true,
		rtl: rtl1,
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
		
		
		var rvmegamenu_multy_pro = $("#rv-menu-horizontal .rvproduct-block");
		rvmegamenu_multy_pro.addClass("owl-carousel");
		rvmegamenu_multy_pro.owlCarousel({
			responsiveClass:true,
			autoplay: !0,
			stopOnHover: true,
			nav:true,
			rtl: rtl1,
			navText: ['<i class="fa icon"></i>', '<i class="fa icon"></i>'],
			responsive:{
				0:{
					items:1
				},
				544:{
					items:2
				},
				768:{
					items:3
				},
				992:{
					items:4
				},
				1200:{
					items:4
				}
			}
		});	
		
				/* -----------End carousel For RV-Megamenu Product ----------- */



   /* ---------------- start Templatetrip more menu ----------------------*/
	if($(document).width() <= 1599){
	var max_elem = 8;
	}  
	else if ($(document).width() >= 1600){
	var max_elem = 5;
	}
    var menu = $('.rv-menu-horizontal li.level-1');
    if (menu.length > max_elem) {
        $('.rv-menu-horizontal .main-menu .menu-content').append('<li class="level-1 more"><div class="more-menu"><span class="categories">More<i class="material-icons">&#xE145;</i></span></div></li>');
    }

    $('.rv-menu-horizontal .main-menu .menu-content .more-menu').click(function() {
        if ($(this).hasClass('active')) {
            menu.each(function(j) {
                if (j >= max_elem) {
                    $(this).slideUp(200);
                }
            });
            $(this).removeClass('active');
            //$(this).children('div').css('display', 'block');
            $('.more-menu').html('<span class="categories">More<i class="material-icons">&#xE145;</i></span>');
        } else {
            menu.each(function(j) {
                if (j >= max_elem) {
                    $(this).slideDown(200);
                }
            });
            $(this).addClass('active');
            $('.more-menu').html('<span class="categories">Less <i class="material-icons">&#xE15B;</i></span>');
        }
    });

    menu.each(function(j) {
        if (j >= max_elem) {
            $(this).css('display', 'none');
        }
    });

    /* ---------------- End Templatetrip more menu ----------------------*/