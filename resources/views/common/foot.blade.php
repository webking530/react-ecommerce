{!! Html::script('js/jquery-2.1.3.min.js') !!}
{!! Html::script('js/popper.min.js') !!}
{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('js/jquery-ui.js') !!}
{!! Html::script('js/common.js?v='.$site_version) !!}
{!! Html::script('js/lazyload.js?v='.$site_version) !!}
{!! Html::script('js/lightslider.js?v='.$site_version) !!}
{!! Html::script('js/jquery.cycle.all.js') !!}
{!! Html::script('js/owl.carousel.min.js') !!}
{!! Html::script('js/responsiveslides.min.js') !!}
{!! Html::script('js/tinymce/js/tinymce/tinymce.min.js') !!}
{!! Html::script('js/jquery.bxslider.min.js') !!}
{!! Html::script('js/jquery.bxslider.js') !!}
{!! Html::script('js/ezPlus.js') !!}
{!! Html::script('js/jquery.validate.js') !!}
{!! Html::script('js/nouislider.min.js') !!}
{!! Html::script('js/angular.js') !!}
{!! Html::script('js/angular-sanitize.js') !!}
{!! Html::script('js/me-lazyload.js') !!}
{!! Html::script('js/ng-infinite-scroll.min.js') !!}
{!! Html::script('js/slick.js') !!}
{!! Html::script('js/selectize.js') !!}
{!! Html::script('js/daterangepicker.js') !!}
{!! Html::script('js/jquery-popover-0.0.3.js') !!}
{!! Html::script('js/messages.js?v='.$site_version) !!}

@if (!isset($exception))
	@if (in_array(Route::current()->uri(), ['signup','login','forgot']))
		{!! Html::script('js/jquery.backstretch.min.js?v='.$site_version) !!}
	@endif

	@if (Route::current()->uri() == 'fancy_list')
		{!! Html::script('js/fotorama.js') !!}
	@endif
	<script type="text/javascript">
		var current_root_uri = '{{Route::current()->uri()}}';
		var CURRENT_ROUTE_NAME = '{{Route::currentRouteName()}}';
	</script>
@else
	<script type="text/javascript">
		var current_root_uri = '';
		var CURRENT_ROUTE_NAME = '';
	</script>
@endif

<script> 
	var app = angular.module('App', ['ngSanitize','infinite-scroll','me-lazyload']);
	var APP_URL = {!! json_encode(url('/')) !!};
	var USER_ID = '{!! Auth::id() !!}';
	$.datepicker.setDefaults($.datepicker.regional[ "en" ])
	var popOverSettings = {
		placement: 'bottom',
		container: 'body'
	};
	var popup_code  = {!! session('error_code') ? session('error_code') : 0  !!};
	var locale = '{!! session("language") !!}';
	Lang.setLocale(locale);

	$('[data-role="popover"]').popover(popOverSettings);
	
	$('ul').on('DOMNodeInserted', function (event) {
		$(event.target).find('[data-role="popover"]').popover(popOverSettings);
	});

	$(document).on('mouseenter','.user_a',function(){
		var cl=$(this).attr("data-id");
		$("."+cl).trigger("click");
	});

	$(document).on('click','.sub-more.share',function(){
		$(".sharable").hide();
		$(this).find(".sharable").show();
	});

	$(document).on('click','.more_List',function(){
		$(".sharable").hide();
		$(".copy-to-clipboard").text(Lang.get('messages.home.copy_link'));
	});

  	$(document).ready(function() {
  		$('#signupmodel').modal('hide');
  		$('#loginmodal').modal('hide');
  		$('#forgotmodal').modal('hide');
	    if(popup_code == 1) {
	    	//$('#signupmodel').modal('show');
	    	$('body').toggleClass('no_scroll');
	    }
	    else if(popup_code == 2) {
	    	$('#loginmodal').modal('show');
	    }
	    else if(popup_code == 4) {
	    	$('#forgotmodal').modal('show');
	    }
	});

	$(document).ready(function() {
		$('.cate1').owlCarousel({
			loop:false,
			margin:0,
			responsiveClass:true,
			autoHeight: true,
			navText: ["<i class='icon-right-arrow' aria-hidden='true'></i>", "<i class='icon-right-arrow' aria-hidden='true'></i>"],
			responsive:{
				0:{
					items:1,
					nav:true
				},
				425:{
					items:2,
					nav:true
				},
				736:{
					items:3,
					nav:true
				},
				992:{
					items:3,
					nav:true
				},
				1200:{
					items:5,
					nav:true
				}
			}
		});
	});
</script>

{!! $head_code !!}

{!! Html::script('js/home.js?v='.$site_version) !!}
{!! Html::script('js/orders.js?v='.$site_version) !!}

@if (!isset($exception))

	@if(Route::current()->uri() == 'merchant/all_products' || Route::current()->uri() == 'merchant/signup' || Route::current()->uri() == 'merchant/dashboard' || Route::current()->uri() == 'merchant/settings_general' || Route::current()->uri() == 'merchant/edit_product/{id}' || Route::current()->uri() == 'merchant/add_product' || Route::current()->uri() == 'merchant/settings_paid' || Route::current()->uri() == 'checkout' || Route::current()->uri() == 'merchant/settings_paid/transfers' || Route::current()->uri() == 'merchant/order' || Route::current()->uri() == 'merchant/order_return' || Route::current()->uri() == 'merchant/order/{id}')
		{!! Html::script('js/merchant.js?v='.$site_version) !!}
		{!! Html::script('js/bootstrap-drilldown-select.js') !!}
	@endif

	@if (Route::current()->uri() == 'cart' )
		{!! Html::script('js/cart.js?v='.$site_version) !!}
	@endif

	@if (Route::current()->uri() == 'merchant/insights' )
		{!! Html::script('js/insights.js?v='.$site_version) !!}
	@endif

	@if (Route::current()->uri() == 'purchases/{id?}' )
		{!! Html::script('js/purchases.js?v='.$site_version) !!}
	@endif

	@if (Route::current()->uri() == '/' || Route::current()->uri() == 'shop/{page?}/{category?}' ||  Route::current()->uri() == 'shop/{page}')
		<script type="text/javascript">
			var query = window.location.search.substring(1).split("&");
			var min_slider_price = 1 ;
			var max_slider_price = {!! $default_max_price !!} ;
			var min_price=1;
			var max_price = 0 
			var symbol=$('#symbol').val();
			max_price={!! $default_max_price !!};
			def_slider_price = {!! $default_max_price !!};
		</script>
	@endif

	@if(!Auth::check())
		<script type="text/javascript">
			var GOOGLE_CLIENT_ID  = "{{ GOOGLE_CLIENT_ID }}";
		</script>
		<script src="https://apis.google.com/js/api:client.js"></script>   
		<script src="{{ asset('js/googleapilogin.js?v='.$site_version) }}"></script>
	@endif

@endif

@stack('scripts')