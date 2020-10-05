$(document).ready(function() {
	var current_language_name = $('.current_language').data('name');
	$('.current_lang_name').html(current_language_name);

	var isMobile = false;
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Opera Mobile|Kindle|Windows Phone|PSP|AvantGo|Atomic Web Browser|Blazer|Chrome Mobile|Dolphin|Dolfin|Doris|GO Browser|Jasmine|MicroB|Mobile Firefox|Mobile Safari|Mobile Silk|Motorola Internet Browser|NetFront|NineSky|Nokia Web Browser|Obigo|Openwave Mobile Browser|Palm Pre web browser|Polaris|PS Vita browser|Puffin|QQbrowser|SEMC Browser|Skyfire|Tear|TeaShark|UC Browser|uZard Web|wOSBrowser|Yandex.Browser mobile/i.test(navigator.userAgent)) {
		isMobile = true;
	}

	$(".cls_leftmenu i.nav-bar").click(function() {
		$(".show-list").toggle();
		$('.mobile_menu').toggleClass('open');
		$('body').toggleClass('no_scroll');
		$('.cls_ulshow').removeClass('active');
		$('.cls_topcategory').toggleClass('d-block');
		setTimeout(function() {
			$(function($) {
				$('.category_lazy').Lazy({
					scrollDirection: 'vertical',
					effect: 'fadeIn',
					visibleOnly: true,
				});
			});
		},100);
	});

	$('.cls_topbanner').owlCarousel('destroy')
	$('.cls_topbanner').owlCarousel({
		loop:true,
		nav:true,
		drag:false,
		lazyLoad:false,
		autoplay:true,
		mouseDrag:false,
		touchDrag:false,
		navText: [
		'<span class="cls_owlprev"></span>',
		'<span class="cls_owlnext"></span>'
		],
		items:1
	});

	$(".show-list .search_cnt i.icon-right-arrow").click(function() {
		$('.show-list .search_cnt').toggle();
	});

	setTimeout(function() {
		$(".flash-container").fadeOut(4000);
	}, 1000);

	$('.logo-show').click(function(event) {
		event.stopPropagation();
	});

	$('.show-list').click(function(event) {
		event.stopPropagation();
	});

	$(".glob-sel").click(function() {
		$(".glob-content").show();
		$(".credit-display").show();
		$(".follow-content").hide();
		$(".referral-display").hide();
		$(".giftcard-display").hide();
		$(".glob-sel").addClass("active");
		$(".follow-sel").removeClass("active");
		$(".follow-sel-1").removeClass("active");
	});

	$(".follow-sel").click(function() { 
		$(".follow-content").show();
		$(".referral-display").show();
		$(".glob-content").hide();
		$(".credit-display").hide();
		$(".giftcard-display").hide();
		$(".follow-sel").addClass("active");
		$(".glob-sel").removeClass("active");
		$(".follow-sel-1").removeClass("active");
	});

	$(".follow-sel-1").click(function() {
		$(".referral-display").hide();
		$(".glob-content").hide();
		$(".credit-display").hide();
		$(".giftcard-display").show();
		$(".follow-sel").removeClass("active");
		$(".glob-sel").removeClass("active");
		$(".follow-sel-1").addClass("active");
	});

	$(".activity").click(function() {
		$(".activity-content").show();
		$(".activity-content-you").hide();
		$(".activity").addClass("current");
		$(".activity-you").removeClass("current");
	});

	$(".activity-you").click(function() {
		$(".activity-content-you").show();
		$(".activity-content").hide();
		$(".activity-you").addClass("current");
		$(".activity").removeClass("current");
	});

	$(document).on('click','.dash-settings',function(e) {
		e.stopPropagation();
		var order_id = $(this).attr('id');
		$('.setting-popup').not("#setting-popup-"+order_id).hide();
		$("#setting-popup-"+order_id +'.setting-popup').toggle();
	});

	$(".every").click(function(){
		$(".menu").toggle();
	});

	$('.cls_refine').click(function() {
		$('.menuitem').toggleClass("cls_resign");
	});

	$('.cls_catename').click(function(event){
		event.stopPropagation();
		$(".every1").toggleClass('cls_open');
		$(".caefilter").removeClass('cls_open');
	});
	$('.cls_seafilter1').click(function(event){
		event.stopPropagation();
		$(".caefilter").toggleClass('cls_open');
		$(".every1").removeClass('cls_open');
	});

	$(".every1").on("click", function (event) {
		event.stopPropagation();
	});
	$(".caefilter").on("click", function (event) {
		event.stopPropagation();
	});

	$(document).on("click", function () {
		$(".every1").removeClass('cls_open');
		$(".caefilter").removeClass('cls_open');
	});

	// $(document).mouseup(function(e) {
	// 	var container =$('.setting-popup');
	// 	var element =$('.dash-settings');
	// 	if (!container.is(e.target) && container.has(e.target).length === 0 && !element.is(e.target) && element.has(e.target).length === 0) {
	// 		container.hide();
	// 	} 
	// });

	$(".menu-title").click(function(e){
		$(".menu-content").toggle();
		e.stopPropagation();
	});

	$("cover-photo").mouseout(function(){
		$(".menu-content-cover").hide();
	});

	$(".menu-title-cover").click(function(e){
		$(".menu-content-cover").toggle();
		e.stopPropagation();
	});

	$('.add-fancy-div').click(function(event){
		event.stopPropagation();
	});

	$('.dash-profile').click(function(event){
		event.stopPropagation();
	});

	$('.menu-content').click(function(event){
		event.stopPropagation();
	});

	$('.menu-content-cover').click(function(event){
		event.stopPropagation();
	});

	$('.more').click(function(event){
		$('.detail').addClass('show');
		$('.more').hide();
		$('.less').show();
	});

	$('.less').click(function(event){
		$('.detail').removeClass('show');
		$('.more').show();
		$('.less').hide();
	});

	$(document).on('click','.policy_detailed',function(event){
		var return_policy_details = $('.return_policy_details').text();
		$('#popup_container').addClass('policy_detail');
		$('#popup_container').css({
			'top' : '0px',
			'opacity' : '1',
			'display' : 'block'
		});
		$('#popup_container .policy_detail').show();
		$('#popup_container .policy_detail .terms p').text(return_policy_details);
	});

	$(document).on('click','.shipping_country_list',function(event){
		var cur_country = $(this).text().trim();
		$('.popup.shipping li a').find('b').removeClass('current');   
		$('#popup_container').addClass('shipping_country');
		$('#popup_container').css({
			'top' : '0px',
			'opacity' : '1',
			'display' : 'block'
		});
		$('#popup_container .shipping').show();
		$('.popup.shipping li a b:contains("'+cur_country+'")').addClass('current');    
	});

	$(document).on('click','.popup.shipping li a',function(){        
		$('.popup.shipping li a').find('b').removeClass('current');
		$(this).find('b').addClass('current');    
	});

	$(document).on('click','.shipping_country_save',function(){
		start_day = $('.popup.shipping li a').find('.current').attr('data-start');
		end_day = $('.popup.shipping li a').find('.current').attr('data-end');
		country = $('.popup.shipping li a').find('.current').text();    

		$('.international.shipping span.able').html(start_day+' - '+end_day+' days to <a class="shipping_country_list">'+country+'</a>')

		$('#popup_container').removeClass('shipping_country');
		$('#popup_container').css({
			'top' : '0px',
			'opacity' : '0',
			'display' : 'none'
		});
		$('#popup_container .shipping').hide();
		$("body").removeClass("no_scroll");
	});

	$('.ly-close1').click(function(event){
		if ( $('#popup_container').hasClass( "policy_detail" ) || $('#popup_container').hasClass( "shipping_country" )) {
			$('#popup_container').removeClass('policy_detail');
			$('#popup_container').removeClass('shipping_country');
			$('#popup_container').css({
				'top' : '0px',
				'opacity' : '0',
				'display' : 'none'
			});
			$('#popup_container .policy_detail').hide();
			$('#popup_container .shipping').hide();
		}
	});

	$(function() {
		var $slideShows = $('#s1').cycle({ 
			timeout: 0,
		});

		setTimeout(nextSlide, 4000);

		function nextSlide() {
			$slideShows.cycle('next');
			setTimeout(nextSlide, 4000);
		}
	});

	$(function() {
		var $slideShowtwo = $('#s2').cycle({ 
			timeout: 0,
		});

		setTimeout(nextSlide, 8000);

		function nextSlide() {
			$slideShowtwo.cycle('next');
			setTimeout(nextSlide, 8000);
		}
	});

	$(function() {
		var $slideShowthree = $('#s3').cycle({ 
			timeout: 0,
		});

		setTimeout(nextSlide, 6000);

		function nextSlide() {
			$slideShowthree.cycle('next');
			setTimeout(nextSlide, 6000);
		}
	});

	$(function() {
		var $slideShowfour = $('#s4').cycle({ 
			timeout: 0,
		});
		setTimeout(nextSlide, 5000);
		function nextSlide() {
			$slideShowfour.cycle('next');
			setTimeout(nextSlide, 5000);
		}
	});

	$(function() {
		var $slideShowfive = $('#s5').cycle({ 
			timeout: 0,
		});

		setTimeout(nextSlide, 7000);

		function nextSlide() {
			$slideShowfive.cycle('next');
			setTimeout(nextSlide, 7000);
		}
	});

	$(function() {
		var $slideShowsix = $('#s6').cycle({ 
			timeout: 0,
		});

		setTimeout(nextSlide, 3000);

		function nextSlide() {
			$slideShowsix.cycle('next');
			setTimeout(nextSlide, 3000);
		}
	});

	$('#recently_viewed_things .bx-cart').bxSlider({
		nextSelector: '#recently_next',
		prevSelector: '#recently_prev',
		minSlides: 4,
		maxSlides: 4,
		slideWidth: 230,
		controls: true,
		infiniteLoop: false,
		onSliderLoad: function(){$('.bx-cart li img').css('display', 'block');$('.bx-cart li').css('margin', '5px');}
	});

	ezPlus = null;
	zoomOpen = false;
	zoomShowOptions = {
		constrainType:false, 
		constrainSize:274, 
		zoomType: "lens",
		containLensZoom: true, 
		lensBorderColour :"#649ed7",
		lensBorderSize :2,
		borderColour :"#649ed7",
		borderSize :2,
		cursor: 'pointer',
		lensSize : 246,
		zoomLevel: 0.5,
		minZoomLevel: 0.3,
		maxZoomLevel: 0.9,
		responsive: false,
		scrollZoom:false,
		zIndex:9,
	};

	function zoom_show(element) {
		$(element).ezPlus(zoomShowOptions);
		ezPlus = $(element).data('ezPlus');
		if(ezPlus)
			ezPlus.closeAll();
	}
	function zoomOff() {
		if(ezPlus) {
			zoomOpen = false;
			ezPlus.closeAll(); 
		}
	}
	function zoomOn() {
		if(ezPlus) {
			zoomOpen = true;
			ezPlus.openAll();
		}
	}

	mySliderOptions = {
		minSlides: 1,
		maxSlides: 1,
		touchEnabled:false,
		easing:"swing",
		pagerCustom: '#bx-pager', 
		onSliderLoad: function(active_index, event) {
			active_element = $('.thumb_bxslider').children().eq(active_index+1);
			element = $(active_element).find('img');
			if(!isMobile) {
				zoom_show(element);
			}
		},
		onSlideBefore : function(element, old_index, active_index) {
			ezPlus = null;
			$('.zoomContainer').remove();
		},
		onSlideAfter : function(element, old_index, active_index) {
			active_element = $('.thumb_bxslider').children().eq(active_index+1);
			element = $(active_element).find('img');
			if(!isMobile) {
				zoom_show(element);
			}
		}
	};
	if(CURRENT_ROUTE_NAME == 'product_detail') {
		setTimeout ( () => {
			$('.thumb_bxslider').bxSlider(mySliderOptions);
		},10);
	}
	
	$(document).on('click', '.zoomContainer', function() {
		zoomOff();
	});

	$(document).on('click', '.zoom_slider', function() {
		zoomOn();
	});

	$(function() {
		$(".rslides").responsiveSlides();
	});

	$(".rslides").responsiveSlides({
		auto: false,
		speed: 700,
		timeout: 4000,
		pager: false,
		nav: false,
		random: false,
		pause: false,
		pauseControls: true,
		prevText: "Previous",
		nextText: "Next",
		maxwidth: "",
		navContainer: "",
		manualControls: "",
		namespace: "rslides",
		before: function(){},
		after: function(){}
	});

	$(".close_bill").click(function(){
		$("body").removeClass("pos-fix");
		$(".payment-popup-bill").hide();
	})

	$(".close_shipping").click(function(){
		$("body").removeClass("pos-fix");
		$(".payment-popup1").hide();
	})              

	$('.btn-payment').click(function(e){
		e.preventDefault();
		$("body").addClass("pos-fix");
		$(".payment-popup").show();
	});

	$('.btn-create').click(function(e){
		e.preventDefault();
		$("body").addClass("pos-fix");
		$(".payment-popup3").show();
	});

	$('.btn-ship').click(function(e){
		e.preventDefault();
		$("body").addClass("pos-fix");
		$(".payment-popup1").show();
	});

	$('.change_currency').click(function(e){
		e.preventDefault();
		$("body").addClass("pos-fix");
		$(".currency-popup").show();
	});

	$(document).on('click','.order_popup',function(e){
		e.preventDefault();
		var order_id = $(this).attr('id');
		var order_action = $(this).attr('data-value');
		var order_action_value = $(this).attr('data-msg');
		$("body").addClass("pos-fix");
		// $('.setting-popup').hide();
		$(".order-action-popup").show();
		$('#order_id').val(order_id);
		$('#order_action').val(order_action_value);
		$('.order_title').html(order_action);
	});

	$(document).on('click','.merchant_cancel_popup',function(e){
		e.preventDefault();
		var order_id = $(this).attr('id');
		var order_action = $(this).data('value');
		$("body").addClass("pos-fix");
		$('.setting-popup').hide();
		$(".merchant-cancel-popup").show();
		$('#order_detail_id').val(order_id);
	});            

	$('.btn-shipping').click(function(e){
		e.preventDefault();
		$("body").addClass("pos-fix");
		$(".shipping-popup").show();
	});

	$('.btn-edit').click(function(e){
		e.preventDefault();
		$("body").addClass("pos-fix");
		$(".shipping-popup").show();
	});

	$(".model_popup_logimg").click(function(e) {
		e.preventDefault();
		$("body").addClass("pos-fix");
		$(".profile-img-popup").show();
	});

	$('.model_popup_coverimg').click(function(e) {
		e.preventDefault();
		$("body").removeClass("pos-fix");
		$(".profile-cover-img-popup").show(); 
	});

	$('.login-back').click(function(event){
		$("body").removeClass("no_scroll");
		$(".login_popup").hide(); 
	});

	$('.add-fancy-back').click(function(event){
		$("body").removeClass("pos-fix");
		$(".fancy-web-popup").hide(); 
		$(".fancy-upload-popup").hide(); 
		$(".payment-popup").hide();
		$(".payment-popup1").hide();
		$(".payment-popup2").hide();
		$(".payment-popup3").hide();
		$(".payment-popup4").hide();
		$(".shipping-popup").hide();
		$(".currency-popup").hide();
		$(".profile-cover-img-popup").hide();
		$(".profile-img-popup").hide();
	});               

	$('.merchant-cancel-close').click(function(event){
		$("body").removeClass("pos-fix");
		$(".merchant-cancel-popup").hide();
		$('#reason_msg').val('');
	})

	$('.ly-close,.cancel_buyer,.close_payment').click(function(event){
		$("body").removeClass("pos-fix");
		$(".fancy-web-popup").hide(); 
		$(".fancy-upload-popup").hide(); 
		$(".payment-popup").hide();
		$(".payment-popup1").hide();
		$(".payment-popup2").hide();
		$(".payment-methodpop").hide();
		$(".payment-popup3").hide();
		$(".payment-popup4").hide();
		$(".shipping-popup").hide();
		$(".currency-popup").hide();
		$(".profile-cover-img-popup").hide();
		$(".profile-img-popup").hide();
		$(".order-action-popup").hide();
		$(".order_popup").show();
		$('#reason_msg').val('');
	});

	$('.btn-back-error').click(function(event){
		$(".payment-popup2").hide();
	})

	$('.btn-back').click(function(event){                    
		$("body").removeClass("pos-fix");
		$(".fancy-web-popup").hide(); 
		$(".fancy-upload-popup").hide();
		$(".payment-popup").hide();
		$(".payment-popup1").hide();
		$(".payment-methodpop").hide();
		$(".payment-popup2").hide();
		$(".payment-popup3").hide();
		$(".payment-popup4").hide();
		$(".payment-popup5").hide();
		$(".shipping-popup").hide();
		$(".currency-popup").hide();
		$(".profile-cover-img-popup").hide();
		$(".profile-img-popup").hide();
	});

	$('.login-content').click(function(event){
		event.stopPropagation();
	});

	$('.fancy-content').click(function(event){
		event.stopPropagation();
	});

	$('.fancy-upload-content').click(function(event){
		event.stopPropagation();
	});

	$('.payment-content').click(function(event){
		event.stopPropagation();
	});

	$('.payment-content1').click(function(event){
		event.stopPropagation();
	});

	$('.payment-content2').click(function(event){
		event.stopPropagation();
	});

	$('.payment-content3').click(function(event){
		event.stopPropagation();
	});

	$('.currency-content').click(function(event){
		event.stopPropagation();
	});

	$('.shipping-content').click(function(event){
		event.stopPropagation();
	});

	$('.upload-img-content').click(function(event){
		event.stopPropagation();
	});

	$('.upload-cover-img-content').click(function(event){
		event.stopPropagation();
	});

	$('.signup_popup_head').click(function(e){
		e.preventDefault();
		$(".signup_popup").show();
		$(".login_popup").hide();
	});

	$('.signup').click(function(e){
		e.preventDefault();
		$("#loginmodal").modal('hide');
		$("#forgotmodal").modal('hide');
		$("body").addClass("no_scroll");
		setTimeout( () => $("#signupmodel").modal('show'),1000);
	});

	$('.forgotmodal').click(function(e){
		e.preventDefault();
		$("body").addClass("no_scroll");
		$("#loginmodal").modal('hide');
		$(".cls_loginpop").modal('hide');
		$(".cls_loginpop").hide();
		setTimeout( () => {
			$(".cls_forgotpop").show(); 
			$("body").addClass("no_scroll"); 
		},200);
	});

	$('.loginsign').click(function(e){
		e.preventDefault();
		$("body").addClass("no_scroll");
		$("#signupmodel").modal('hide');
		setTimeout( () => $("#loginmodal").modal('show'),600);
	})

	$('.login-back').click(function(event){
		$("body").removeClass("no_scroll");
		$(".signup_popup").hide(); 
	});

	$('.signup-content').click(function(event){
		event.stopPropagation();
	});

	$('.btn-continue').click(function(event){
		$('.step2').show();
		$('.step1').hide();
	});

	$('._back').click(function(event){
		$('.step2').hide();
		$('.step1').show();
	});

	$('._continue').click(function(event){
		$('.step2').hide();
		$('.step1').hide();
		$('.popup_get_started').hide();
	});

	$('.fb').click(function(event){
		$('.connect.fb').show();
		$('.connect.tw').hide();
		$('.connect.gg').hide();
		$('.connect.gm').hide();

		$('.find_sns li a.fb').addClass('current');
		$('.find_sns li a.tw').removeClass('current');
		$('.find_sns li a.gg').removeClass('current');
		$('.find_sns li a.gm').removeClass('current');
	});

	$('.tw').click(function(event){
		$('.connect.fb').hide();
		$('.connect.tw').show();
		$('.connect.gg').hide();
		$('.connect.gm').hide();
		$('.find_sns li a.fb').removeClass('current');
		$('.find_sns li a.tw').addClass('current');
		$('.find_sns li a.gg').removeClass('current');
		$('.find_sns li a.gm').removeClass('current');
	});

	$('.gg').click(function(event){
		$('.connect.fb').hide();
		$('.connect.tw').hide();
		$('.connect.gg').show();
		$('.connect.gm').hide();
		$('.find_sns li a.fb').removeClass('current');
		$('.find_sns li a.tw').removeClass('current');
		$('.find_sns li a.gg').addClass('current');
		$('.find_sns li a.gm').removeClass('current');
	});

	$('.gm').click(function(event){
		$('.connect.fb').hide();
		$('.connect.tw').hide();
		$('.connect.gg').hide();
		$('.connect.gm').show();
		$('.find_sns li a.fb').removeClass('current');
		$('.find_sns li a.tw').removeClass('current');
		$('.find_sns li a.gg').removeClass('current');
		$('.find_sns li a.gm').addClass('current');
	});

	$(document).on('click','.add_to_cart',function() {
		var check_login = $(this).hasClass('without_login');
		if(check_login == true) {
			$('.ly-close').trigger('click');
			$('.login_popup_head').trigger('click');
		}
		else {
			var id         = $('#product_id').val();
			var quantity   = $('#quantity').val();
			var option     = $('.product_option').val();
			var _token     = $('input[name="_token"]').val();

			var formData   = {productid:id,quantity:quantity,option:option, _token : _token};

			$.ajax({
				url: APP_URL+'/add_to_cart',
				type: "POST",
				data: formData,       
				cache: false,       
				success: function(data){                             
					if(data == 1){
						window.location.href= APP_URL+'/cart';
					}
					else{
						$('.ly-close').trigger('click');
						$('.login_popup_head').trigger('click');
					}              
				}
			});
		}
	});

	$(document).on('click','.add_cart',function(){
		var check_login = $(this).hasClass('without_login');
		var product_id = $(this).data('id')
		if(check_login == true) {
			$('.ly-close').trigger('click');
			$('.login_popup_head').trigger('click');
		}
		else {
			var id         = product_id;
			var quantity   = 1;
			var option     = '';
			var _token     = $('input[name="_token"]').val();

			var formData   = {productid:id,quantity:quantity,option:option, _token : _token};

			$.ajax({
				url: APP_URL+'/add_to_cart',
				type: "POST",
				data: formData,       
				cache: false,       
				success: function(data){                             
					if(data == 1){
						window.location.href= APP_URL+'/cart';
					}
					else{
						$('.ly-close').trigger('click');
						$('.login_popup_head').trigger('click');
					}              
				}
			});
		}
	});

	$('.dash-car').mouseover(function(){
		$('.msg-popup').show();
	});
	$('.dash-car').mouseout(function(){
		$('.msg-popup').hide();
	});
	$('.msg-popup').mouseover(function(){
		$('.msg-popup').show();
	}); 
	$('.msg-popup').mouseout(function(){
		$('.msg-popup').hide();
	});   
	$('.dash-merchant').mouseover(function(){
		$('.msg-popup5').show();
	});
	$('.dash-merchant').mouseout(function(){
		$('.msg-popup5').hide();
	});
	$('.msg-popup5').mouseover(function(){
		$('.msg-popup5').show();
	}); 
	$('.msg-popup5').mouseout(function(){
		$('.msg-popup5').hide();
	});  

	$('.show_activity,.cls_activity').on('click', function(e) {
		if (e.target !== this) {
			return;
		}
		$('.msg-popup2').toggle();
	});

	/*$('.msg-popup2').on('mouseout', function(e) {
		$('.msg-popup2').hide();
	});*/

	$(".add-fancy").click(function(){
		$(".msg-popup3").hide();
		$(".add-fancy-div").show();
	});
	$(".fancy-head").click(function(){
		$(".add-fancy-div").hide();
		$(".msg-popup3").show();         
	});
	$('#figure-img').mouseover(function(){
		$('#hover-img').show();
	});
	$('#figure-img').mouseout(function(){
		$('#hover-img').hide();
	});
	$('.fancy-blue').hover(function(){
		$('.fancy-blue').hide();
		$('.unfancy').show();
	});
	$('.fancy-blue').mouseout(function(){
		$('.unfancy').hide();
		$('.fancy-blue').show();
	});
	$('.menu-title-cover').mouseover(function(){
		$('.menu-title-cover em').show();
	});
	$('.menu-title-cover').mouseout(function(){
		$('.menu-title-cover em').hide();
	});
	$('.profile-photo-img').mouseover(function(){
		$('.photo-change').show();
	});
	$('.profile-photo-img').mouseout(function(){
		$('.photo-change').hide();
	});
	$('.cover-photo').mouseover(function(){
		$('.menu-title-cover').show();
	});
	$('.cover-photo').mouseout(function(){
		$('.menu-title-cover').hide();
	});
	$('.photo-change').hover(function(){
		$('.photo-change em').show();
	});
	$('.photo-change').mouseout(function(){
		$('.photo-change em').hide();
	});
	$('.follow-btn').mouseover(function(){
		$('.follow-btn').addClass('hide');
		$('.unfollow-btn').removeClass('hide');
	});
	$('.follow-btn').mouseout(function(){
		$('.follow-btn').removeClass('hide');
		$('.unfollow-btn').addClass('hide');
	});

	$(window).scroll(function() {
		if($(window).scrollTop() >= 100) {
			$("#scroll-to-top").show();
		}
		else {
			$("#scroll-to-top").hide();
		}
	});

	$("#scroll-to-top").on("click", function() {
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});     

	$('.google-find_f').click(function(){
		$(this).addClass('current');
		$('.facebook-find_f').removeClass('current');
		$('.twitter-find_f').removeClass('current');
		$('.gmail-find_f').removeClass('current');
		$('.connect-sns.twitter').addClass('hide');
		$('.connect-sns.facebook').addClass('hide');
		$('.connect-sns.google').removeClass('hide');
		$('.connect-sns.gmail').addClass('hide');
	});
	$('.gmail-find_f').click(function(){
		$(this).addClass('current');
		$('.facebook-find_f').removeClass('current');
		$('.twitter-find_f').removeClass('current');
		$('.google-find_f').removeClass('current');
		$('.connect-sns.google').addClass('hide');
		$('.connect-sns.twitter').addClass('hide');
		$('.connect-sns.facebook').addClass('hide');
		$('.connect-sns.gmail').removeClass('hide');
	});
	$('.facebook-find_f').click(function(){
		$(this).addClass('current');
		$('.gmail-find_f').removeClass('current');
		$('.twitter-find_f').removeClass('current');
		$('.google-find_f').removeClass('current');
		$('.connect-sns.google').addClass('hide');
		$('.connect-sns.twitter').addClass('hide');
		$('.connect-sns.facebook').removeClass('hide');
		$('.connect-sns.gmail').addClass('hide');
	});
	$('.twitter-find_f').click(function(){
		$(this).addClass('current');
		$('.facebook-find_f').removeClass('current');
		$('.google-find_f').removeClass('current');
		$('.gmail-find_f').removeClass('current');
		$('.connect-sns.twitter').removeClass('hide');
		$('.connect-sns.facebook').addClass('hide');
		$('.connect-sns.google').addClass('hide');
		$('.connect-sns.gmail').addClass('hide');
	}); 
	$('.ic-q').mouseover(function(){
		$('.tooltip-que').show();
	});
	$('.ic-q').mouseout(function(){
		$('.tooltip-que').hide();
	});
	$('#items-1').click(function(){
		$('#items-1 a').addClass('current');
		$('#items-2 a').removeClass('current');
		$('#items-show1').removeClass('hide');
		$('#items-show2').addClass('hide');
	});
	$('#items-2').click(function(){
		$('#items-2 a').addClass('current');
		$('#items-1 a').removeClass('current');
		$('#items-show2').removeClass('hide');
		$('#items-show1').addClass('hide');
	});
	$('.select-amex-payment ').click(function(){
		$('.amex-express-btn').show();
		$('.prced-btn').hide();
	});
	$('.select-card-payment').click(function(){
		$('.amex-express-btn').hide();
		$('.prced-btn').show();
	});
	$('.select-bitcoin-payment').click(function(){
		$('.amex-express-btn').hide();
		$('.prced-btn').show();
	});

	$('.ship2-li').click(function(){             
		$(this).addClass('selected');
		$('.ship1-li').removeClass('selected');
	});
	$('.ship1-li').click(function(){
		$(this).addClass('selected');
		$('.ship2-li').removeClass('selected');
	});
	$(".slide-img").mouseover(function(){          
		var hover_id = $(this).data('id')
		$('.fig-hover'+hover_id).css('bottom','0px', 'important')        
	});
	$(".slide-img").mouseout(function(){
		var hover_id = $(this).data('id')
		$('.fig-hover'+hover_id).css('bottom','-70px', 'important')        
	});
	$(".btns-green-embo").click(function(){
		$(".btns-green-embo").attr("aria-selected", 'true');       
	});
	$(".bill-sel").click(function(){
		$('.bill-add').show();
	});
	$(".bill-sel-close").click(function(){
		$('.bill-add').hide();
	});

	$(".btn-use-payment").click(function(){
		$('.ship-add').hide();
		$('.payment-add').hide();
		$('.review-add').show();        
	});

	$(".btn-use-payment-menthod").click(function(){
		$('.ship-add').hide();
		$('.payment-add').hide();
		$('.payment-add1').css('display','none');
		$('.review-add').show();        
	});

	$(".change-link").click(function(){
		$('.ship-add').show();
		$('.payment-add').hide();
		$('.review-add').hide();
		$('.payment-add1').css('display','none');
	});

	$(".change-bill").click(function(){
		$('.ship-add').hide();
		$('.payment-add').show();
		$('.review-add').hide();
		$('.payment-add1').hide();
	});

	$(".change-payment").click(function(){
		$('.ship-add').hide();
		$('.payment-add').hide();
		$('.review-add').hide();
		$('.payment-add1').show();
		$('.edit_payment_details').show();
		$('.new_payment').hide();
	});

	$(".btn-ship-payment").click(function(){
		$('.ship-add').hide();
		$('.payment-add').show();
		$('.payment-add1').hide();
	});

	$('#closed').click(function(){
		$('#overlay-thing').removeClass('shownhome');
		$('body').removeClass('pos-fix');

	});

	app.filter('trustAsResourceUrl', ['$sce', function($sce) {
		return function(val) {
			return $sce.trustAsResourceUrl(val);
		};
	}])

	app.controller('login_signup', ['$scope', '$http','$rootScope', function($scope, $http,$rootScope) {
		$scope.responsive_category_browse_load = 0
		$scope.category_browse = function() {
			if ($scope.responsive_category_browse_load==0) {
				$scope.responsive_category_browse_load = 1
				$http.get(APP_URL+'/category_browse').then(function(response) { 

					$scope.categories_browse=response.data;
					$rootScope.$emit("setCategoryDetails", $scope.categories_browse);
					setTimeout(function(){
						$(function($) {
							$('.category_lazy').Lazy({
								scrollDirection: 'vertical',
								effect: 'fadeIn',
								visibleOnly: true,
							});
						});
					},10);

				});
			}
		}

		function slider() {
			$('.header-slider').owlCarousel({
				loop:false,
				margin:20,
				responsiveClass:true,
				responsive:{
					0:{
						items:3,
						slideBy:3,
						nav:true
					},
					360:{
						items:4,
						slideBy:4,
						nav:true
					},
					568:{
						items:6,
						slideBy:6,
						nav:true
					},
					736:{
						items:8,
						slideBy:8,
						nav:true
					},
					1024:{
						items:10,
						slideBy:10,
						nav:true
					},
					1200:{
						items:15,
						slideBy:15,
						nav:true
					}
				}
			});
		}

		function top_slider() {
			$('.header-slider').owlCarousel({
				loop:false,
				margin:15,
				lazyLoad:true,
				responsiveClass:true,
				navText : ["<i class='icon icon-right-arrow custom-rotate'></i>","<i class='icon icon-right-arrow'></i>"],    
				responsive:{
					0:{
						items:3,
						slideBy:3,
						nav:true
					},
					360:{
						items:4,
						slideBy:4,
						nav:true
					},
					568:{
						items:6,
						slideBy:6,
						nav:true
					},
					736:{
						items:8,
						slideBy:8,
						nav:true
					},
					1024:{
						items:10,
						slideBy:10,
						nav:true
					},
					1200:{
						items:15,
						slideBy:15,
						nav:true
					}
				}
			});
		}

		$scope.header_notification=[];
		$scope.header_activity=[];
		$scope.merchant_activity=[];
		$scope.notificationPage = '0';
		$scope.activityPage = '0';
		$scope.merchantactivityPg = '0';

		$scope.get_activity_header = function(){
			if($scope.header_activity_busy) return;
			$scope.header_activity_busy = true;

			$('.activity_loading_header').show();
			$(".activity_empty").hide();

			pageNum=parseInt($scope.activityPage)+1;

			$http.post(APP_URL+'/get_activity_header?page='+pageNum, {}).then(function(response) 
			{
				$scope.activityPage=response.data.current_page;

				if(response.data.total == response.data.to || response.data.to==null)
				{
					$scope.header_activity_busy = true;
				}
				else
				{
					$scope.header_activity_busy = false;
				}

				if(response.data.data=="" || response.data =='')
				{
					$(".activity_empty").show();
				}
				else
				{
					$(".activity_empty").hide();
				}
				angular.forEach(response.data.data, function(value, key){         
					$scope.header_activity.push(value);
				});

				$(".activity_loading_header").hide();

			});
		};

		$scope.get_notification_header=function()
		{
			if($scope.header_notification_busy) return;
			$scope.header_notification_busy = true;

			$(".notifation_empty").hide();

			$(".notification_loading_header").show();

			pageNumber=parseInt($scope.notificationPage)+1;

			$http.post(APP_URL+'/get_notification_header?page='+pageNumber, {}).then(function(response) 
			{
				$scope.notificationPage=response.data.current_page;

				if(response.data.total == response.data.to || response.data.to==null)
				{
					$scope.header_notification_busy = true;
				}
				else
				{
					$scope.header_notification_busy = false;
				}


				if(response.data.data=="")
				{
					$(".notifation_empty").show();
				}
				else
				{
					$(".notifation_empty").hide();
				}
				angular.forEach(response.data.data, function(value, key){
					$scope.header_notification.push(value);
				});           
				$(".notification_loading_header").hide();
			});
		}

		$scope.get_merchant_header=function()
		{
			if($scope.merchant_notification_busy) return;
			$scope.merchant_notification_busy = true;

			$(".activity_empty").hide();

			$(".activity_loading_header").show();

			pageNumber=parseInt($scope.merchantactivityPg)+1;

			$http.post(APP_URL+'/get_merchant_header?page='+pageNumber, {}).then(function(response) {
				$scope.merchantactivityPg = response.data.current_page;

				if(response.data.total == response.data.to || response.data.to==null) {
					$scope.merchant_notification_busy = true;
				}
				else {
					$scope.merchant_notification_busy = false;
				}

				if(response.data.data=="") {
					$(".activity_empty").show();
				}
				else {
					$(".activity_empty").hide();
				}
				angular.forEach(response.data.data, function(value, key){
					$scope.merchant_activity.push(value);
				});           
				$(".activity_loading_header").hide();
			});
		}

		$('#language_header li a').click(function(){
			$http.post(APP_URL + "/set_session", {
				language: $(this).data('value')
			}).then(function(data) {
				location.reload();
			});
		})

		var previous_currency = $('.prev_currency').val();

		$('#currency_hearder li a').click(function() {
			$http.post(APP_URL + "/set_session", {
				currency: $(this).data('value'),
				previous_currency: previous_currency
			}).then(function(data) {
				location.reload();
			});
		});

		$scope.email_check = function(form) 
		{    
			var email_id = $('#email_id').val();

			var name = email_id.split("@");

			var user_name = name['0'].replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');

			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

			var check = emailReg.test(email_id);

			if(email_id == '')
			{
				$scope.required_email = 1;
				$scope.exist_mail     = '';
				$scope.invalid_email  = '';

			}
			else
			{
				if(check != 1)
				{
					$scope.required_email = '';
					$scope.exist_mail     = '';
					$scope.invalid_email  = 1;
				}
				else
				{
					$http.post(APP_URL+'/user/check_email', { email_id:email_id}).then(function(response) 
					{
						if(response.data == 1)
						{
							$scope.exist_mail = response.data;
							$scope.required_email = '';
							$scope.invalid_email  = '';
						}
						else
						{
							$scope.exist_mail     = '';
							$scope.required_email = '';
							$scope.invalid_email  = '';

							$(".signup_popup1 .signup-content").hide();
							$("#signupmodel").hide();
							$(".sign_register").css('display','block');
							
							$http.post(APP_URL+'/user/check_username', { user_name:user_name}).then(function(response) 
							{
								if(response.data == 1)
								{
									var number = Math.floor((Math.random() * 1000000) + 1);;
									user_name = user_name + number;
								}

								$('#email_signup').val(email_id);
								$('#user_name').val(user_name);

							});
						}

					});
				}
			}

		};

		$scope.ajax_search = function()
		{
			var search_key = $scope.search_key;

			$http.post(APP_URL+'/ajax_search', { term:search_key }).then(function(response) 
			{
				$scope.searchusers =[];
				$scope.searchbrands = [];
				$scope.searchthings = [];
				$scope.searchusers = response.data.users;
				$scope.searchbrands = response.data.stores;
				$scope.searchthings = response.data.things;
				$scope.key = search_key;
				$('.result').show();
				if(response.data.users)
				{
					$('#user-lists').show();
				}
				else if(!response.data.users)
				{
					$('#user-lists').hide();
				}
				if(response.data.stores)
				{
					$('#stores-lists').show();
				}
				else if(!response.data.stores)
				{
					$('#stores-lists').hide();
				}
				if(response.data.things)
				{
					$('.keywords').show();
				}

				if(response.data.error){
					$('.result').hide();
				}

			});
		}

		$('body').click(function(){
			$('.result').hide();
		});

		$(document).on('change','.change_option',function(){
			var option = $(this).val();
			var product_id =$('#product_id').val();

			$http.post(APP_URL+'/display_price', { option :option,product_id : product_id }).then(function(response) 
			{      
				var currency_sym = $('#currency_sym_'+product_id).html();
				if(response.data.price !=''){
					$('#product_price_'+product_id).text(response.data.price);
				}
				if(response.data.retail_price !='') {
					$('#product_retail_price_'+product_id).html('<em>'+currency_sym+' '+response.data.retail_price+'</em>');
				}
				else{
					$('#product_retail_price_'+product_id).text('');
				}
				if(response.data.discount !='') {
					$('#product_discount_'+product_id).html('( Save '+response.data.discount+' %)');
				}
				else
				{
					$('#product_discount_'+product_id).text('');
				}      
				$('#quantity').html(response.data.quantity);
			});

		});

		$scope.new_signup = function(signup) 
		{

			var email_id = $('#email_id').val();

			var name = email_id.split("@");

			var user_name = name['0'];

			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

			var check = emailReg.test(email_id);

			if(email_id == '')
			{
				$scope.required_email = 1;
				$scope.exist_mail     = '';
				$scope.invalid_email  = '';

			}
			else
			{
				if(check != 1)
				{
					$scope.required_email = '';
					$scope.invalid_email  = 1;
				}
				else
				{
					$http.post('user/check_email', { email_id:email_id}).then(function(response) 
					{
						if(response.data == 1)
						{
							$scope.exist_mail = response.data;
							$scope.required_email = '';
							$scope.invalid_email  = '';
						}
						else
						{
							$scope.exist_mail     = '';
							$scope.required_email = '';
							$scope.invalid_email  = '';
							$(".signup-content").hide();
							$('#signupmodel').modal('hide');
							setTimeout( () => {
								$("#login_step2").modal('show');
							},500);
							$http.post(APP_URL+'/user/check_username', { user_name:user_name}).then(function(response) 
							{
								if(response.data == 1)
								{
									var number = Math.floor((Math.random() * 1000000) + 1);;
									user_name = user_name + number;
								}

								$('#email_signup').val(email_id);
								$('#user_name').val(user_name);

							});
						}
					});
				}
			}
		};

		$('#resend_confirmation').click(function(){
			var user_id = $('#user_id').val();
			$http.post(APP_URL+'/user/resend_confirmation', { user_id:user_id}).then(function(response) 
			{ 
				var email_id = response.data.email_id;
				var status   = response.data.status;
				var msg      = response.data.msg;
				if(status){
					$('.resend_confirmation').html(msg).css('color','green').show();
				}
				else
				{
					$('.resend_confirmation').html(msg).css('color','green').show();
				}
			});
		});

	}]);

app.controller('login_signup1', ['$scope', '$http','$rootScope', function($scope, $http,$rootScope) {

}]);

app.controller('sub_category', ['$scope', '$http','$rootScope', function($scope, $http,$rootScope) {
	$scope.sub_categories = function(){
		var pageURL = window.location.href;
		var lastURLSegment = pageURL.substr(pageURL.lastIndexOf('/') + 1);
		console.log(pageURL);
	}
	$scope.sub_categories();
}]);

});

$(".store_page .category").mouseover(function() {
	$('.category').addClass('opened');      
});
$(".store_page .category").mouseout(function() {
	$('.category').removeClass('opened');      
});

$(".btns-gray-embo").click(function() {
	$('.user_img').removeClass('user_imgnn');      
});

$(".btn-cancel").click(function() {
	$('.user_img').addClass('user_imgnn');      
});

$(document).ready(function() {
	$('.logpop .icon-close').click(function() {
		$('.login_popup').hide();
	});

	$('.logpop .icon-close').click(function() {
		$('.signup_popup').hide();
	});
});

$(document).ready(function(){  
	var scope_controls=""; 

	var isMobile = false;
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Opera Mobile|Kindle|Windows Phone|PSP|AvantGo|Atomic Web Browser|Blazer|Chrome Mobile|Dolphin|Dolfin|Doris|GO Browser|Jasmine|MicroB|Mobile Firefox|Mobile Safari|Mobile Silk|Motorola Internet Browser|NetFront|NineSky|Nokia Web Browser|Obigo|Openwave Mobile Browser|Palm Pre web browser|Polaris|PS Vita browser|Puffin|QQbrowser|SEMC Browser|Skyfire|Tear|TeaShark|UC Browser|uZard Web|wOSBrowser|Yandex.Browser mobile/i.test(navigator.userAgent)) {
		isMobile = true;
	}
	
	if(isMobile)
		scope_controls="controls autoplay";

	app.directive('player', ['$sce', '$compile', '$timeout', function ($sce, $compile, $timeout) {
		'use strict';
		return {
			restrict: 'E',
			scope: {
				videos: '='
			},
			link: function (scope, element, attrs) {

				scope.hover_muted = true;
				scope.user_muted = false;
				scope.playsinline = false;

				var video = element.find('video');
				var player_inner_div = element.find('.player_inner_div');
				element.addClass('player');
				scope.playing = false;
				scope.trustSrc = function(src) {
					return $sce.trustAsResourceUrl(src);
				}
				video.on('timeupdate', function (e) {
					scope.$apply(function () {
						scope.percent = (video[0].currentTime / video[0].duration) * 100;
					});
				});
				player_inner_div.on('mouseover', function(){
					if(!scope.user_muted)
					{
						video.prop('muted', false);
						scope.hover_muted = false;
					}
				})
				player_inner_div.on('mouseout', function(){
					video.prop('muted', true)
					scope.hover_muted = true;
				})
				player_inner_div.on('click', function(){
					video.prop('muted', true)
					scope.hover_muted = true;
				})
				scope.video_scroll_video_play_pause = function() {
					if(isInViewport(video.get(0))) {
						setTimeout(function(){
							video.get(0).play();                 
						},500);            
					}
					else {
						video.get(0).pause(); 
					}

				}
				scope.toggle_user_muted = function($event){
					$event.preventDefault();
					$event.stopPropagation();
					var new_user_muted = !scope.user_muted ? true : false;
					scope.user_muted = new_user_muted;
					video.prop('muted', new_user_muted);
				}
				$(window).on('scroll', function() {
					$timeout(function () {
						scope.video_scroll_video_play_pause();
					});
				});
				$(document).ready(function(){
					$timeout(function () {
						scope.video_scroll_video_play_pause();
					});

				});
			},
			template: '<div class="player_inner_div" style="position: relative" >'
			+ '<video '+ scope_controls +' loop="true" muted="true" playsinline="true" poster="{{ trustSrc(videos[0].poster) }}" class="img-responsive" preload="none" >' +

			'<source ng-repeat="item in videos" ng-src="{{ trustSrc(item.src) }}" poster="{{ trustSrc(item.poster) }}" type="video/{{ item.type }}" />' +
			'</video>' +
			'<div class="mute_area" style="position:absolute; bottom: 30px; right: 30px; color: #fff; font-size: 30px;" >'+
			'<span style="background:none;" class="fa mobe_fa" ng-click="toggle_user_muted($event);" ng-class="(!hover_muted && !user_muted) ? \'fa-volume-up\' : \'fa-volume-off\' " ></span>'+
			'</div>'+
			'</div>'+
			'</div>'
		};
	}]);

	app.directive('popupplayer', ['$sce', '$compile', function ($sce, $compile) {
		'use strict';
		return {
			restrict: 'E',
			scope: {
				videos: '='
			},
			template: '<div class="video_player"></div>',
			link: function (scope, element, attrs) {
				scope.trustSrc = function(src) {
					return $sce.trustAsResourceUrl(src);
				}
				element.find('.video_player').append( $compile('<player videos="'+scope.videos+'" />')(scope) );
			},
		}
	}]);
})

function isInViewport(element, options) {
	let {top, bottom, left, right} = element.getBoundingClientRect()
	let settings = $.extend({
		tolerance: 0,
		viewport: window
	}, options)
	let isVisibleFlag = false
	let $viewport = settings.viewport.jquery ? settings.viewport : $(settings.viewport)
	if (!$viewport.length) {
		console.warn('isInViewport: The viewport selector you have provided matches no element on page.')
		console.warn('isInViewport: Defaulting to viewport as window')
		$viewport = $(window)
	}
	const $viewportHeight = $viewport.height()
	let $viewportWidth = $viewport.width()
	const typeofViewport = $viewport[0].toString()
	if ($viewport[0] !== window && typeofViewport !== '[object Window]' && typeofViewport !== '[object DOMWindow]') {
		const viewportRect = $viewport[0].getBoundingClientRect()
		top = top - viewportRect.top
		bottom = bottom - viewportRect.top
		left = left - viewportRect.left
		right = right - viewportRect.left
		isInViewport.scrollBarWidth = isInViewport.scrollBarWidth || 10
		$viewportWidth -= isInViewport.scrollBarWidth
	}
	settings.tolerance = ~~Math.round(parseFloat(settings.tolerance))
	if (settings.tolerance < 0) {
settings.tolerance = $viewportHeight + settings.tolerance // viewport height - tol
}
if (right <= 0 || left >= $viewportWidth) {
	return isVisibleFlag
}
isVisibleFlag = settings.tolerance ? top <= settings.tolerance && bottom >= settings.tolerance : bottom > 0 && top <= $viewportHeight
return isVisibleFlag
}

$(document).ready(function(){            
	$(".ly-close1,.ly-close12").click(function(){
		$(".policy_detail").hide();
	});
});

$(".ly-close").click(function(){
	$('body').removeClass('no_scroll');
});

$(".nav-bar.icon-close,.clt").click(function(){
	$('body').removeClass('no_scroll');
});

$(".login-back").click(function(){
	$('body').addClass('no_scroll');
});

$(document).ready(function(){ 
	$('.policy_detailed').click(function(){
		$("body").addClass('no_scroll');
	});
	$('.shipping_country_list').click(function(){
		$("body").addClass('no_scroll');
	});
	$(".ly-close12").click(function(){
		$('body').removeClass('no_scroll');
	});
});

$(".ly-close1").click(function(){
	$('.policy_detail').removeClass('shipping_country');
});

$(".ly-close13").click(function(){
	$('.shipping_country').hide();
});

$(".pro_detail_page .ly-close1").click(function(){
	$('body').removeClass('no_scroll');
});

$(".pro_detail_page .shipping_country_list").click(function(){
	$('body').addClass('no_scroll');
});

$(".error_close").click(function(){
	$('.newflash').hide();
});

$(document).ready(function() {
	function header_active() {
		var header_height = $('header').outerHeight();
		var header_slider = $('.header-slider-wrap').outerHeight();
		$('main').css("margin-top", header_height);
	}

	$(window).on('load', function() {
		header_active();
	});

	$(window).scroll(function() {
		header_active();
	});

	$(window).resize(function() {
		header_active();
	});

	$(document).mouseup(function(e) {
		var container = $(".share-dropdown");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			$('.share-dropdown').hide();
			$('.share-dropdown-btn').removeClass('active');
		}
	});
});


$(document).ready(function(){
	$(window).scroll(function () {   
		var header_height = $('header').outerHeight() + 14;
		var right_sidebar = $('.right-sidebar').outerWidth();
		var cls_right_sidebar = $('.cls_left_sidebar').outerHeight(); 
		if($(window).scrollTop() > cls_right_sidebar) {
			$('.cls_left_sidebar').css('position','fixed');
			$('.cls_left_sidebar').css('bottom','0'); 
			$('.cls_left_sidebar').css('margin-top', header_height); 

			$('.cls_right_sidebar').css('position','fixed');
			$('.cls_right_sidebar').css('bottom','0'); 
			$('.cls_right_sidebar').css('margin-top', header_height); 
			$('.cls_right_sidebar').css('width', right_sidebar); 

		}

		else if ($(window).scrollTop() <= cls_right_sidebar) {
			$('.cls_left_sidebar').css('position','');
			$('.cls_left_sidebar').css('bottom','0');
			$('.cls_left_sidebar').css('margin-top', '');

			$('.cls_right_sidebar').css('position','');
			$('.cls_right_sidebar').css('bottom','0');
			$('.cls_right_sidebar').css('margin-top', '');
		}  
	});
	var merchant_header = $('#merchant-header').outerHeight();
	$('.merchant_signup').css('margin-top', merchant_header); 
	$('.cls_dashmain').css('margin-top', merchant_header); 
});

$(document).on('click', '.cls_sharemenu li', function (e) {
	e.stopPropagation();
});
$(document).ready(function(){
	$('.cls_li').hover(
		function(){ $(this).addClass('cls_li_hover') },
		function(){ $(this).removeClass('cls_li_hover') }
		)

	$(".onsale_title").click(function() {
		$(this).parent().toggleClass('active').siblings().removeClass('active');
	});
});

$(document).ready(function(){

	function footerAlign() {
		var footerHeight = $('#cls_footer').outerHeight();
		$('body').css('padding-bottom', footerHeight);
		$('#cls_footer').css('height', footerHeight);
	}
	$(document).ready(function(){
		footerAlign();
	});

	$( window ).resize(function() {
		footerAlign();
	});

	$(".cls_resuser").click(function(){
		if($(".cls_resuserul").hasClass('d-none')) {
			$(".cls_resuserul").removeClass('d-none');
			$(".cls_resdashul").addClass('d-none');
		} else {
			$(".cls_resuserul").addClass('d-none');
		}
	})

	$(".cls_resdash").click(function(){
		if($(".cls_resdashul").hasClass('d-none')) {
			$(".cls_resdashul").removeClass('d-none');
			$(".cls_resuserul").addClass('d-none');
		} else {
			$(".cls_resdashul").addClass('d-none');
		}
	})

	$(' .cls_li .onsale_category').each(function () {
		if ($(this).is(":empty")) {
			$(this).closest('li').addClass('cls_ulhide');
		}
		else
		{
			$(this).closest('li').addClass('cls_ulshow');
		}
	});

	$(".onsale_title").click(function(e){
		if ($(window).width() < 514) {
	     if($(this).parent().hasClass('cls_ulshow'))
			e.preventDefault();cls_ulshow
	    }
		
	});

	$(document).on('click.bs.dropdown.data-api', '.cls_share', function (e) {
		e.stopPropagation();
	});

	var cls_setting1 = $('.cls_setting1').outerWidth();
	$('.cls_profileedit').css('width',cls_setting1);
});

$(window).on('load', function() {
	$(".se-pre-con").fadeOut("slow");
	$('body').removeClass('cls_load');
});


$('.horizon-prev').click(function(event) {
	event.preventDefault();
	$('.cls_ul').animate({
		scrollLeft: "-=775px"
	}, "slow");
});

$('.horizon-next').click(function(event) {
	event.preventDefault();
	$('.cls_ul').animate({
		scrollLeft: "+=775px"
	}, "slow");
});

$(document).ready(function(){
	var cls_li = 0;
	var cls_inner = $('.cls_inner').outerWidth()
	$('.cls_li').each(function(index) {
		cls_li += parseInt($(this).width(), 10);
	});
	if (cls_inner <= cls_li) {
		$('.cls_inner').removeClass("cls_innerarrow")
	}
	else {
		$('.cls_inner').addClass("cls_innerarrow")
	}
});