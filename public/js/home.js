var isMobile = false;
if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Opera Mobile|Kindle|Windows Phone|PSP|AvantGo|Atomic Web Browser|Blazer|Chrome Mobile|Dolphin|Dolfin|Doris|GO Browser|Jasmine|MicroB|Mobile Firefox|Mobile Safari|Mobile Silk|Motorola Internet Browser|NetFront|NineSky|Nokia Web Browser|Obigo|Openwave Mobile Browser|Palm Pre web browser|Polaris|PS Vita browser|Puffin|QQbrowser|SEMC Browser|Skyfire|Tear|TeaShark|UC Browser|uZard Web|wOSBrowser|Yandex.Browser mobile/i.test(navigator.userAgent)) isMobile = true;

$(document).on('click','.copy-to-clipboard',function(){
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($(this).attr('data-copy')).select();
	document.execCommand("copy");
	$temp.remove();
	$(this).text("Copied to clipboard");
});
function product_lazy(){
	setTimeout(function(){
		$(function($) {
			$('.lazy').Lazy({
				scrollDirection: 'vertical',
				effect: 'fadeIn',
				visibleOnly: true,
			});
		});

		$('.static_page_lazy').each(function(){
			var img = $(this);
			img.attr('src', img.data('src'));
		});
	},100);
}
app.controller('all_store', ['$scope', '$http', function($scope, $http) 
{
	$scope.currentPage = '0';
	$scope.all_stores_list=[];
	$scope.stores_loadMore = function() {
		if($scope.stores_busy) return;
		$scope.stores_busy = true;
		$("#store_loading").show();
		pageNumber=parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_stores/all'+'?page='+pageNumber, {}).then(function(response) 
		{
			$("#store_loading").hide();
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.stores_busy = true;
			}
			else
			{
				$scope.stores_busy = false;
			}
			if(response.data.data=="")
			{
				$("#stores-result-empty").show();
			}
			else
			{
				$("#stores-result-empty").hide();	
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.all_stores_list.push(value);
			});
		});
	};
	$scope.stores_loadMore();
}]);

app.controller('appController', ['$scope', '$http','$timeout', '$q', function($scope, $http,$timeout, $q) {
	$scope.category_browse = function() {
		$http.get(APP_URL+'/category_browse').then(function(response) { 
			$scope.categories_browse=response.data;
			setTimeout(function(){
   				$('.category_lazy').Lazy({
   					scrollDirection: 'vertical',
   					effect: 'fadeIn',
   					visibleOnly: true,
   				});
	   		},10);
		});
	};

	$scope.setGetParameter = function(paramName, paramValue) {
		var url = window.location.href;
		if (url.indexOf(paramName + "=") >= 0) {
			var prefix = url.substring(0, url.indexOf(paramName));
			var suffix = url.substring(url.indexOf(paramName));
			suffix = suffix.substring(suffix.indexOf("=") + 1);
			suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
			url = prefix + paramName + "=" + paramValue + suffix;
		}
		else {
			if (url.indexOf("?") < 0)
				url += "?" + paramName + "=" + paramValue;
			else
				url += "&" + paramName + "=" + paramValue;
		}
		history.pushState(null, null, url);
	};

	$scope.applyScope = function() {
		if(!$scope.$$phase) {
            $scope.$apply();
        }
	};

	$(document).ready(function() {
		$scope.category_browse();
	});
}]);

app.controller('storeController', ['$scope', '$http','$timeout', '$q', function($scope, $http,$timeout, $q) {
	$scope.FollowStore = function(store_id) {
		if(USER_ID == '') {
			$("#loginmodal").modal("show");
			return true;
		}

		$http.post(APP_URL+'/follow_store', { 
			store_id : store_id, 
			follower_id: USER_ID
		}).then(function(response) { 
			$('#follow').html(response.data.fol);
			$('.follower_cnt').html(response.data.follower_count);
		})
	};

	$scope.likeuserFollow = function(index) {		
		var follow_user = $('.check_login').attr('data_user');
		var user_id = $scope.all_product_like_user[index].users.id;
		if(USER_ID == '') {
			$("#loginmodal").modal("show");
			return true;
		}
		$scope.all_product_like_user[index].users.user_follow = "1";
		$http.post(APP_URL+'/follow', {follower_id:follow_user,user_id:user_id}).then(function(response) {
		});
	}

}]);

app.controller('productController', ['$scope', '$http','$timeout', '$q', function($scope, $http,$timeout, $q) {
	$scope.currentPage 		= 0;
	$(document).ready(function() {
		$scope.resetProducts();
		$scope.productLoadMore();
	});
	
	$scope.resetProducts = function() {
		$scope.currentPage 		= 0;
		$scope.detail_products 	= [];
		$scope.no_more_products = false;
		$scope.ajax_loading = false;
	};

	$scope.getUrl = function(type) {
		if(type == 'search') {
			return APP_URL+'/products_search';
		}
		if(type == 'category') {
			return APP_URL+'/get_products/'+$scope.searchby+'/'+$scope.category_id;
		}
		if(type == 'store') {
			return APP_URL+'/get_store_products';
		}
		return '';
	};

	$scope.getDataParams = function(type) {
		var data_params = {};
		if(type == 'search') {
			data_params = { search_key:$scope.search_key,search_for:$scope.search_for };
		}
		if(type == 'store') {
			data_params = {store:$scope.store_id};
		}
		if(type == 'category') {
			var price_range = 0;

			data_params = {price_range:price_range,type:$scope.category_type};
			if($scope.apply_price_filter) {
				var price_range = 0;
				if($scope.min_price >= 0 && $scope.max_price > 0 && $scope.min_price <= $scope.max_price) {
					price_range = $scope.min_price+"-"+$scope.max_price;
					data_params["apply_price_filter"] = true;
					data_params["price_range"] = price_range;
				}
				$scope.setGetParameter('price_range', price_range);
			}
		}
		return data_params;
	};

	$scope.updatePageCategory = function(category) {
		$scope.resetProducts();
		if(category == 'all') {
			$scope.category_type = "all";
		}
		else if(category == 'wishlist') {
			$scope.category_type = "wishlist";
		}
		else {
			$(".wish").removeClass("current");
			$(".df").addClass("current");
			$scope.category_id = category;
			$scope.category_type = category;
		}
		$scope.productLoadMore();
	};

	$scope.productLoadMore =  function() {
		if($scope.no_more_products) {
			return false;
		}
		if($scope.ajax_loading) {
            return false;
        }

		var url = $scope.getUrl($scope.load_more_type);
		
		if(url == '') {
			return true;
		}

		pageNumber = ++$scope.currentPage;
		$scope.ajax_loading = true;
		var data_params = $scope.getDataParams($scope.load_more_type);
		$http.post(url+'?page='+pageNumber, data_params)
		.then(function(response) {
			var response_data = response.data;
			$scope.currentPage = response_data.current_page;
			if(response_data.last_page == response_data.current_page) {
				$scope.no_more_products = true;
			}
			if(response_data.data.length > 0) {
				angular.forEach(response_data.data, function(value, key){
					$scope.detail_products.push(value);
				});
			}

			$scope.ajax_loading = false;
		});
	};

	$scope.product_like = function (product,index) {
		if(USER_ID == '') {
			$("#loginmodal").modal("show");
			return true;
		}

		$http.post(APP_URL+'/product_likes', {productid:product.id}).then(function(response) {
			$scope.detail_products[index] = response.data[0];
		});
	};

	$scope.pdu_like = function (product,index) {
		if(USER_ID == '') {
			$("#loginmodal").modal("show");
			return true;
		}
		$http.post(APP_URL+'/product_likes', {productid:product.id}).then(function(response) {
			$scope.detail_products[index] = response.data[0];
		});
	};

	$scope.wishlist= function(user_id,product_id) {

		if(USER_ID == '') {
			$("#loginmodal").modal("show");
			return true;
		}

		$http.post(APP_URL+'/wishlist_list', { user_id : user_id, product_id: product_id }).then(function(response) { 
			$('.wishlist_'+product_id).html(response.data.wish);
			if(response.data.wish_type == 'Saved to Wishlist') {
				$('.wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
			}
			else{
				$('.wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
			}
		})
	};

	$scope.initSlider = function() {
		var min_price = min_slider_price;
		var max_price = max_slider_price;
		$scope.price_range = symbol+ min_price + " - "+symbol + max_price + "+" ;
		$( "#slider-range" ).slider({
			range: true,
			min: min_slider_price,
			max: max_slider_price,
			values: [min_price, max_price],
			slide: function(event, ui) {
				$scope.price_range = symbol+ ui.values[0] + " - "+symbol + ui.values[1];
				if(ui.values[1] == max_slider_price) {
					$scope.price_range += '+'
				}
				$scope.min_price = ui.values[0];
				$scope.max_price = ui.values[1];
				$scope.applyScope();
			},
			change: function(event, ui) {
				$scope.resetProducts();
				$scope.apply_price_filter = true;
				$scope.productLoadMore();
			}
		});

		$(document).on('click', '#cls_pricefilter',function() {
			$('.cls_pricefilteroption').toggle();
		});	
	};

	$scope.Wishlist_View = function(user_id) {
		$(".df").removeClass("current");
		$(".wish").addClass("current");
		$scope.Wishlists = [];
	 	if(user_id==0) {
	   		$('.login_popup_head').trigger('click');
	   		return false;
		}
		var check_user_status = $('.check_login').hasClass('inactive-user');	
		$scope.updatePageCategory('wishlist');
	};

}]);

app.controller('products_details', ['$scope', '$http','$timeout', function($scope, $http,$timeout) {
	$scope.products_details_busy=false;
	$scope.detail_products_busy=false;
	$scope.currentPage = '0';
	$scope.currentpg = '0';
	$scope.detail_products=[];
	$scope.recently_thing=[];
	$scope.ajax_loading = false;

	$scope.show_liked_tab =  function() {
		$scope.currentPage     ='0';
		$scope.detail_products_onsale=false;
		$scope.onsale_product = [];
		$("#everything").removeClass("current");
		$("#liked").addClass("current");
		if($scope.detail_products_onsale) return;
		pageNumber = parseInt($scope.currentPage)+1;
		$scope.cls_onsale='liked';
		$http.post(APP_URL+'/get_onSale_product/liked?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null) {
				$scope.detail_products_onsale = true;
			}
			else
			{
				$scope.detail_products_onsale = false;
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.onsale_product.push(value);
			});
			product_lazy();
		});	
	}
	$scope.show_everything_tab =  function()
	{
		$scope.currentPage     ='0';
		$scope.detail_products_onsale=false;
		$("#liked").removeClass("current");
		$("#everything").addClass("current");
		if($scope.detail_products_onsale) return;
		$scope.detail_products_onsale = true;
		pageNumber = parseInt($scope.currentPage)+1;
		$scope.onsale_products = [];
		$scope.cls_onsale='everything';
		$http.post(APP_URL+'/get_onSale_product?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.detail_products_onsale = true;
			}
			else
			{
				$scope.detail_products_onsale = false;
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.onsale_products.push(value);
			});
			product_lazy();
		});	
	}
	if(typeof min_slider_price != 'undefined' )
	{
		$('.cls_pricefilter').ready(function(){
	    $( "#slider-range" ).slider({
	      range: true,
	      min: min_slider_price,
	      max: max_slider_price,
	      values: [ min_price, max_price ],
	      slide: function( event, ui ) {
	      	var price_range = symbol + ui.values[0] + " - "+symbol + ui.values[1];
	      	if(ui.values[1] == max_slider_price) {
	      		price_range += '+';
	      	}
	        $("#amount").val(price_range);
	        document.getElementById('mob_min_value').value = ui.values[0];
			document.getElementById('mob_max_value').value = ui.values[1];
	      },
	      change: function(event, ui) {
	      		$scope.currentPage = '0';
				$scope.currentpg = '0';
	      		$scope.detail_products=[];
	      		$scope.detail_products_busy=false;
	      		$scope.details_loadMore();
	      }
	    }); 
	    $( "#amount" ).val( symbol + $( "#slider-range" ).slider( "values", 0 ) +
	      " - "+symbol + $( "#slider-range" ).slider( "values", 1 ) +
	      " + " );
	    });

	  	function setGetParameter(paramName, paramValue) {
		    var url = window.location.href;
		    if (url.indexOf(paramName + "=") >= 0) {
		        var prefix = url.substring(0, url.indexOf(paramName));
		        var suffix = url.substring(url.indexOf(paramName));
		        suffix = suffix.substring(suffix.indexOf("=") + 1);
		        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
		        url = prefix + paramName + "=" + paramValue + suffix;
		    } else {
		        if (url.indexOf("?") < 0)
		            url += "?" + paramName + "=" + paramValue;
		        else
		            url += "&" + paramName + "=" + paramValue;
		    }
		    history.pushState(null, null, url);	
	  	}

		$( "#cls_pricefilter" ).click(function() { 
		$('.cls_pricefilteroption').toggle();
		});	
	}
	

   $(document).on("click", function(event){
        var $trigger = $(".cls_pricefilterli");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".cls_pricefilteroption").slideUp("fast");
        }            
 	 });

	$('#timeline ul.tabs li a').click(function(){
		var type = $(this).data('type');		
		if(type=='similar'){
			$('.recently').removeClass('current');
			$(this).addClass('current');
			// $(this).addClass('loading');
			// setTimeout(function () { 
			//     $('.similar').removeClass('loading');
			// }, 10);
			$('#timeline .inner.similars').show(0);
			$('#timeline .inner.recentlys').hide();
		}
		else{
			$('.similar').removeClass('current');
			$(this).addClass('current');
			// $(this).addClass('loading');
			// setTimeout(function () { 
			//     $('.recently').removeClass('loading');
			// }, 10);
		 	$('#timeline .inner.recentlys').show(0);
			$('#timeline .inner.similars').hide();
		}		
	});
	
	$(document).ready(function(){
		var popOverSettings = {
			placement: 'bottom',
			container: 'body'
		};	    
		$('[data-role="popover"]').popover(popOverSettings);
	});


	$scope.currentPage = '0';
	$scope.currentpg = '0';
	$scope.detail_products=[];
	$scope.recently_thing=[];

	$(document).on("click", function(event){
        var $trigger = $(".cls_pricefilterli");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".cls_pricefilteroption").slideUp("fast");
    }            
  });

	$scope.details_loadMore = function(type ='') {
		$('#progress').show(); //show progress bar
		if($scope.detail_products_busy) return;
		$scope.detail_products_busy = true;
		var category = $("#page_category").val();	
		var searchby = $('#searchby').val();	
		var price_range=0;
		if(document.getElementById("mob_max_value") != undefined)
		{
			var  max_price = document.getElementById("mob_max_value").value;  
			var  min_price = document.getElementById("mob_min_value").value; 
			var  max_data = $("#mob_max_value").val();
			if(max_price!="" && (min_price>=0 || max_price < max_data ))
				{
					price_range = min_price+"-"+max_price;
					setGetParameter('price_range', price_range);	
				}
				else
				{
					price_range = 0;
					setGetParameter('price_range', price_range);	
				}
		}
		if(type !== '')
		{
			type="wishlist";

		}
		if(typeof category == 'undefined' )
		{
			category= "all";
			$('all').addClass('current');
		}
   		if(category != '' && category != 'undefined' )
		{
			$('.'+category).addClass('current');
		} 
		if(typeof searchby != 'undefined' )
		{
			searchby = searchby;
		}
		else
		{
			searchby = 'all';
		}
		$scope.ajax_loading = true;
		pageNumber=parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_products/'+searchby+'/'+category+'?page='+pageNumber, {price_range:price_range,type:type}).then(function(response) 
		{
				$scope.currentPage=response.data.current_page;
				if(response.data.total == response.data.to || response.data.to == null)
				{
					$scope.detail_products_busy = true;
				}
				else
				{
					$scope.detail_products_busy = false;
				}
				angular.forEach(response.data.data, function(value, key){
					$scope.detail_products.push(value);
				});
		$scope.ajax_loading = false; 	
		});
	};

	$scope.details_loadMore();
	$scope.recently_viewed_things = function(){
		if($scope.recently_products_busy) return;
		$scope.recently_products_busy = true;

		$("#detail_loading").show();
		pageNumber=parseInt($scope.currentpg)+1;
		
		$http.post(APP_URL+'/recently_viewed_things?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentpg=response.data.current_page;

			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.recently_products_busy = true;
			}
			else
			{
				$scope.recently_products_busy = false;
			}

			angular.forEach(response.data.data, function(value, key){          
				$scope.recently_thing.push(value.products);
			});

		});
	}
	$scope.product_like = function (all,product_id)
	{
		var check_login = $('.product_like').hasClass('without_login');		
		var check_user_status = $('.product_like').hasClass('inactive-user');
		if(check_login == true)
		{
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else{
			$http.post(APP_URL+'/product_likes', {productid:product_id}).then(function(response) 
			{
				$scope.pro = response.data[0];
				all.like_count = response.data[0].like_count;
				all.like_user = response.data[0].like_user;
				all.user_like = response.data[0].user_like;
				product_lazy();
			});
		}
	};
	$scope.pdu_like = function (product,product_id)
	{
		var check_login = $('.product_like').hasClass('without_login');
		var check_user_status = $('.product_like').hasClass('inactive-user');
		if(check_login == true)
		{
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else{
			$http.post(APP_URL+'/product_likes', {productid:product_id}).then(function(response) 
			{
				$scope.pro = response.data[0];
				product.like_count = response.data[0].like_count;
				product.like_user = response.data[0].like_user;
				product.user_like = response.data[0].user_like;
				product_lazy();
			});
		}
	};
	$scope.product_like = function (detailed_product,detailed_product_id)
	{
		var check_login = $('.product_like').hasClass('without_login');

		var check_user_status = $('.product_like').hasClass('inactive-user');
		
		if(check_login == true)
		{
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else{
			$http.post(APP_URL+'/product_likes', {productid:detailed_product_id}).then(function(response) 
			{
				detailed_product.like_count = response.data[0].like_count;
				detailed_product.like_user = response.data[0].like_user;
				detailed_product.user_like = response.data[0].user_like;
				product_lazy();
			});
		}
	};
	$scope.details_load = function() {

		var check_right_sightbar = $('body').hasClass('popular-head');
		
		if(check_right_sightbar == true){
			if($scope.popular_products_busy) return;
			$scope.popular_products_busy = true;
			$("#popular_loading").show();
			var category=$("#page_category").val();
			if(category=="")
			{
				category="all";
			}
			pageNumber=parseInt($scope.currentPage)+1;
			$http.post(APP_URL+'/get_products/popular/'+category+'?page='+pageNumber, {}).then(function(response) 
			{
		    	$scope.currentPage=response.data.current_page;
		    	if(response.data.total == response.data.to || response.data.to==null)
		    	{
		    		$scope.popular_products_busy = true;
		    	}
		    	else
		    	{
		    		$scope.popular_products_busy = false;
		    	}
		    	if(response.data.data=="")
		    	{
		    		$("#popular-search-result-empty").show();
		    	}
		    	else
		    	{
		    		$("#popular-search-result-empty").hide();	
		    	}
		    	angular.forEach(response.data.data, function(value, key){
		    		$scope.popular_products.push(value);
		    	});

		    	product_lazy();

		    	$("#popular_loading").hide();
		    });
		}
	};
	$scope.likeuser_follow=function(index){
		var check_login = $('.check_login').hasClass('without_login');
		var follow_user=$('.check_login').attr('data_user');
		var check_user_status = $('.check_login').hasClass('inactive-user');
		
		var user_id=$scope.all_product_like_user[index].users.id;
		if(check_login == true)
		{
			$('#view_like_user').modal('hide');
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else
		{
			$scope.all_product_like_user[index].users.user_follow="1";
			$http.post(APP_URL+'/follow', {follower_id:follow_user,user_id:user_id}).then(function(response) 
			{
			});
		}
	}
	$scope.likeuser_unfollow=function(index){
		var check_login = $('.check_login').hasClass('without_login');
		var follow_user=$('.check_login').attr('data_user');
		var check_user_status = $('.check_login').hasClass('inactive-user');
		var user_id=$scope.all_product_like_user[index].users.id;
		if(check_login == true)
		{
			$('#view_like_user').modal('hide');
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else
		{
			$scope.all_product_like_user[index].users.user_follow="0";
			$http.post(APP_URL+'/follow', {follower_id:follow_user,user_id:user_id}).then(function(response) 
			{
			});
		}
	}
	$scope.like_user_loadMore = function(product_id){

		$scope.all_product_like_user=[];
		$("#no_likes").hide();
		$("#likes_loading").show();
		$http.post(APP_URL+'/get_products_likes', {product_id:product_id}).then(function(response) 
		{
			$scope.all_product_like_user=response.data;
			$("#likes_loading").hide();
		});
	}
	$scope.details_load();
	$scope.wishlist= function(user_id,product_id) {
		if(user_id==0) {
			$('.login_popup_head').trigger('click');
			return false;
		}

		var check_user_status = $('.check_login').hasClass('inactive-user');
		
		if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}

		$http.post(APP_URL+'/wishlist_list', 
		{ 
			user_id : user_id, 
			product_id: product_id
		}).then(function(response) 
		{ 
			if(response.data.wish_type=='Saved to Wishlist')
			{	           	
				$('#wishlist_'+product_id).html(response.data.wish);
				$('#wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
				$('.wishlist_'+product_id).html(response.data.wish);
				$('.wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
			}
			else{
				$('#wishlist_'+product_id).html(response.data.wish);
				$('#wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
				$('.wishlist_'+product_id).html(response.data.wish);
				$('.wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
			}
		})
	}; 
	$scope.Wishlist_View = function(user_id){
		$(".df").removeClass("current");
		$(".wish").addClass("current");
		$scope.Wishlists=[];
		 	if(user_id==0){
	   		$('.login_popup_head').trigger('click');
	   		return false;
   			}
   			var check_user_status = $('.check_login').hasClass('inactive-user');	
   			$scope.currentPage = '0'; 
			$scope.detail_products_busy=false;
			$scope.detail_products=[];
			$scope.details_loadMore(user_id);
	}

	$scope.show_product_tab =  function(id)
	{
		$('#page_category').val(id);
		$(".store_tab").removeClass("current");
		$(this).addClass("current");		
		$scope.currentPage = '0'; 
		$scope.detail_products_busy=false;
		$scope.detail_products=[];
		$scope.details_loadMore();
	}

	$scope.no_more_liked_users = false;
	$scope.likedUsersPage = 0;
	$scope.product_liked_users = [];
	$scope.showLikedUsers = function(product_id) {
		if($scope.no_more_liked_users) {
			return;
		}
		$scope.ajax_loading = true;
		pageNumber = ++$scope.likedUsersPage;
		$http.post(APP_URL+'/get_liked_users'+'?page='+pageNumber, {product_id:product_id}).then(function(response) {
			
			var response_data = response.data;
			$scope.likedUsersPage = response_data.current_page;
			if(response_data.last_page == response_data.current_page) {
				$scope.no_more_liked_users = true;
			}
			if(response_data.data.length > 0) {
				angular.forEach(response_data.data, function(value, key){
					$scope.product_liked_users.push(value);
				});
			}
			$scope.ajax_loading = false;
		});
	};

	$scope.followUser = function(user_id,index) {
		if(USER_ID == '') {
			$("#view_like_user").modal("hide");
			$("#loginmodal").modal("show");
			return true;
		}
		$http.post(APP_URL+'/follow', {follower_id:USER_ID,user_id:user_id}).then(function(response) {
			if(index >= 0) {
				$scope.product_liked_users[index].users.user_follow = response.data.following_status;
			}
			else {
				$scope.owner_follow = ($scope.owner_follow == "0") ? "1" : "0";
			}
		});
	};

	$scope.unfollowUser = function(user_id,index) {
		$scope.followUser(user_id,index);
	};

}]);

app.controller('userActivities', ['$scope', '$http','$timeout', '$compile', '$sce', function($scope, $http, $timeout, $compile, $sce) {
	$scope.ajax_loading = false;
	$scope.all_activity = [];
	$scope.currentPage = 0;

	$scope.activityLoadMore = function() {
		if($scope.noMoreActivities) {
			return;
		}

		pageNumber=parseInt($scope.currentPage)+1;
		$scope.ajax_loading = true;

		$http.post(APP_URL+'/get_activity?page='+pageNumber, {}).then(function(response) {
			$scope.currentPage = response.data.current_page;
			$scope.noMoreActivities = false;
			
			if(response.data.total == response.data.to || response.data.to==null) {
				$scope.noMoreActivities = true;
			}

			angular.forEach(response.data.data, function(value, key){	    		
				$scope.all_activity.push(value);
			});

			setTimeout( () => {
				$('.activity_product_slider').owlCarousel({
					nav:true,
					drag:true,
					autoplay:false,
					lazyLoad:false,
					items:1,
					dots:false,
					navText: ["<i class='icon-right-arrow' aria-hidden='true'></i>", "<i class='icon-right-arrow' aria-hidden='true'></i>"],
				});

				$('.activity_product_slider').owlCarousel({
					nav:true,
					drag:true,
					autoplay:false,
					lazyLoad:false,
					items:1,
					dots:false,
					navText: ["<i class='icon-right-arrow' aria-hidden='true'></i>", "<i class='icon-right-arrow' aria-hidden='true'></i>"],
				});
			});

			$scope.ajax_loading = false;
		});
	};

	$scope.product_like = function (product,index) {
		if(USER_ID == '') {
			$("#loginmodal").modal("show");
			return true;
		}

		$http.post(APP_URL+'/product_likes', {productid:product.id}).then(function(response) {
			likes_count = response.data[0].like_user.length;
			console.log($(".product_like_"+index));
			$(".product_like_"+index).text(likes_count)
		});
	};

	$scope.wishlist= function(user_id,product_id) {

		if(USER_ID == '') {
			$("#loginmodal").modal("show");
			return true;
		}

		$http.post(APP_URL+'/wishlist_list', { user_id : user_id, product_id: product_id }).then(function(response) { 
			$('.wishlist_'+product_id).html(response.data.wish);
			if(response.data.wish_type == 'Saved to Wishlist') {
				$('.wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
			}
			else{
				$('.wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
			}
		})
	};

}]);

app.controller('home_products', ['$scope', '$http','$timeout', '$compile', '$sce', function($scope, $http, $timeout, $compile, $sce) 
{
	$scope.all_product=[];
	$scope.products_busy=false;
	$scope.products_details_busy=false;
	$scope.store_products_busy=false;
	$scope.search_busy=false;
	$scope.currentPage = '0';
	$scope.you_liked=0;
	$scope.all_search=[];
	$scope.slider_products=[];
	$scope.all_store_product=[];
	$scope.slider_products_pager=[];
	$scope.popcurrentPage = '0';
	$scope.popcurrentpg = '0';
	$scope.like_products=[];
	$scope.recently_thing=[];
   
	$scope.load_store_products = function(){
		$(".storepro").show();
		$(".load-store").addClass('loading1');
		$http.post(APP_URL+'/store_products', {}).then(function(responses) 
		{
			$(".load-store").removeClass('loading1');
			$scope.store_products = responses.data.data;
			setTimeout(function(){
				cls_topbanner1(); 
				store_products();
			},10);
			$(".storepro").hide();
			if(responses.data.data=="")
			{
				$(".search-result-store").show();
			}
			else
			{
				$(".search-result-store").hide(); 
			}
		});
	}     
	$scope.load_store_products();

	$scope.store_productsMore = function() {
		if($scope.store_products_busy) return;
		$scope.store_products_busy = true;
		$("#product_result_empty").hide();
		$("#store_products_loading").show();
		var store_id=$("#store_id").val();
		pageNumber=parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_store_products?page='+pageNumber, {store:store_id}).then(function(response) 
		{
			$scope.get_store_products=true;
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.store_products_busy = true;
			}
			else
			{
				$scope.store_products_busy = false;
			}

			if(response.data.data=="")
			{
				$("#product_result_empty").show();
			}
			else
			{
				$("#product_result_empty").hide();	
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.all_store_product.push(value);

			});	        		   	
			$("#store_products_loading").hide();
		});
	};
	
	function cls_topbanner1() {
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
  	}
	$scope.all_items=[];
	$scope.pop_items=[];
	$scope.all_brands=[];
	$scope.all_people=[];
	$scope.all_activity=[];
	$scope.all_notification=[];
	$scope.all_product_like_user=[];
	$scope.current_like_product_id=0;
	$scope.notification_loadMore = function() {
		if($scope.notification_busy) return;
		$scope.notification_busy = true;

		$("#search-result-empty").hide();
		$("#activity_loading").show();
		pageNumber=parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_notification?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;

			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.notification_busy = true;
			}
			else
			{
				$scope.notification_busy = false;
			}


			if(response.data.data=="")
			{
				$("#search-result-empty").show();
			}
			else
			{
				$("#search-result-empty").hide();	
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.all_notification.push(value);
			});	        	


			$("#activity_loading").hide();
		});
	};

	$scope.ajax_loading = false;
	$scope.activity_loadMore = function() {
		if($scope.activity_busy) {
			return;
		}
		$scope.activity_busy = true;

		$("#search-result-empty").hide();
		$("#activity_loading").show();
		pageNumber=parseInt($scope.currentPage)+1;
		$scope.ajax_loading = true;

		$http.post(APP_URL+'/get_activity?page='+pageNumber, {}).then(function(response) {
			$scope.currentPage = response.data.current_page;
			$scope.activity_busy = false;
			
			if(response.data.total == response.data.to || response.data.to==null) {
				$scope.activity_busy = true;
			}

			angular.forEach(response.data.data, function(value, key){	    		
				$scope.all_activity.push(value);
			});

			$scope.ajax_loading = false;
		});
	};

	$(document).on('click','.activity .item_list dd a',function(){	

		activity_user = $(this).attr('data-activity_user');		

		activity_id   = $(this).attr('data-activity_id');

		target_id 	  = $(this).attr('data-target_id');
		
		group_id      = $(this).attr('data-group_id');

		$('.activity .item_list dd a.activity_user_'+activity_user).removeClass('active');

		$(this).addClass('active');

		$('.activity .user_'+activity_user+' li.group_'+group_id).hide();

		$('.activity .user_'+activity_user+' li#product_'+target_id).show();
		
	});

	$(document).on('click','.popshow',function(){
		$('.recently').removeClass('current');
		$(this).addClass('current');
		$('#timeline .inner.similars').show();
		$('#timeline .inner.recentlys').hide();
		$scope.details_loadMore();
		$scope.recently_viewed_things();		
	});

	$(window).on('scroll', function() {
		var check_popup = $('#overlay-thing').hasClass('shownhome');
		
		if(check_popup == true){

			$scope.details_loadMore();
			$scope.recently_viewed_things();
		}
	});

	$('#timeline ul.tabs li a').click(function(){
		var type = $(this).data('type');					
		if(type=='similar'){
			$('.recently').removeClass('current');
			$(this).addClass('current');
			$('#timeline .inner.similars').show();
			$('#timeline .inner.recentlys').hide();
		}
		else{
			$('.similar').removeClass('current');
			$(this).addClass('current');
			$('#timeline .inner.recentlys').show();
			$('#timeline .inner.similars').hide();

		}		
	});

	$scope.details_loadMore = function() {
		var  price_range = 0;
		if($scope.detail_products_busy) return;
		$scope.detail_products_busy = true;
		$("#detail_loading").show();
		var category=$("#page_category").val();
		if(category == "")
		{
			category ="all";
		}
		category = "all";
		pageNumber=parseInt($scope.popcurrentPage)+1;
		$http.post(APP_URL+'/get_products/all/'+category+'?page='+pageNumber, {}).then(function(response) 
		{
	    	//alert(response.data.total);
	    	$scope.popcurrentPage=response.data.current_page;
	    	if(response.data.total == response.data.to || response.data.to==null)
	    	{
	    		$scope.detail_products_busy = true;
	    	}
	    	else
	    	{
	    		$scope.detail_products_busy = false;
	    	}
	    	
	    	angular.forEach(response.data.data, function(value, key){
	    		$scope.like_products.push(value);
	    	});
	    	$("#detail_loading").hide();
	    });
	};

	$scope.recently_viewed_things = function(){
		if($scope.recently_products_busy) return;
		$scope.recently_products_busy = true;

		$("#detail_loading").show();
		pageNumber=parseInt($scope.popcurrentpg)+1;
		
		$http.post(APP_URL+'/recently_viewed_things?page='+pageNumber, {}).then(function(response) 
		{
			$scope.popcurrentpg=response.data.current_page;

			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.recently_products_busy = true;
			}
			else
			{
				$scope.recently_products_busy = false;
			}

			angular.forEach(response.data.data, function(value, key){          
				$scope.recently_thing.push(value.products);
			});

		});
	}

	$scope.activityuser_follow=function(user_id,id){
		var check_login = $('.check_login').hasClass('without_login');
		var follow_user=$('.check_login').attr('data_user');
		var check_user_status = $('.check_login').hasClass('inactive-user');
		
		if(check_login == true)
		{
			$('#view_like_user').modal('hide');
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else
		{			
			if($('#activity_'+id).hasClass('btns-gray-embo'))
			{
				$('#activity_'+id).removeClass('btns-gray-embo').addClass('btns-gray-embo3');
			}
			else 
			{
				$('#activity_'+id).removeClass('btns-gray-embo3').addClass('btns-gray-embo')
			}

			$http.post(APP_URL+'/follow', {follower_id:follow_user,user_id:user_id}).then(function(response) 
			{
				$('.user_followers_'+id).html(response.data.follower_count);
				$('.user_following_'+id).html(response.data.following_count);
			});
		}
	}

	$scope.activityuser_unfollow=function(user_id,id){
		var check_login = $('.check_login').hasClass('without_login');
		var follow_user=$('.check_login').attr('data_user');
		var check_user_status = $('.check_login').hasClass('inactive-user');
		
		if(check_login == true)
		{
			$('#view_like_user').modal('hide');
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else
		{
			follow_icon = $('#activity_'+id+' i').attr('class');

			if(follow_icon == 'icon icon icon-user-with-tick')
			{
				$('#activity_'+id).html('<i class="icon icon-male-user-with-plus-symbol1"></i>');
			}
			else 
			{
				$('#activity_'+id).html('<i class="icon icon icon-user-with-tick"></i>');
			}
			$http.post(APP_URL+'/follow', {follower_id:follow_user,user_id:user_id}).then(function(response) 
			{
				$('.user_followers_'+id).html(response.data.follower_count);
				$('.user_following_'+id).html(response.data.following_count);
			});
		}
	}

	$scope.likeuser_follow=function(index){
		var check_login = $('.check_login').hasClass('without_login');
		var follow_user=$('.check_login').attr('data_user');
		var check_user_status = $('.check_login').hasClass('inactive-user');
		
		var user_id=$scope.all_product_like_user[index].users.id;
		if(check_login == true)
		{
			$('#view_like_user').modal('hide');
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else
		{
			$scope.all_product_like_user[index].users.user_follow="1";
			$http.post(APP_URL+'/follow', {follower_id:follow_user,user_id:user_id}).then(function(response) 
			{
			});
		}
	}

	$scope.likeuser_unfollow=function(index){
		var check_login = $('.check_login').hasClass('without_login');
		var follow_user=$('.check_login').attr('data_user');
		var check_user_status = $('.check_login').hasClass('inactive-user');
		var user_id=$scope.all_product_like_user[index].users.id;
		if(check_login == true)
		{
			$('#view_like_user').modal('hide');
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else
		{
			$scope.all_product_like_user[index].users.user_follow="0";
			$http.post(APP_URL+'/follow', {follower_id:follow_user,user_id:user_id}).then(function(response) 
			{
			});
		}
	}

	$scope.like_user_loadMore = function(product_id) {
		$scope.all_product_like_user=[];
		$("#no_likes").hide();
		$("#likes_loading").show();
		$http.post(APP_URL+'/get_products_likes', {product_id:product_id}).then(function(response) {
			$scope.all_product_like_user=response.data;
			$("#likes_loading").hide();
		});
	}


	$scope.loadMore = function(status='',load='') {
		if($scope.products_busy) return;
		$scope.products_busy = true;
		$("#search-result-empty").hide();
		$("#products_loading").show();
		var liked_products;
		if($scope.you_liked==1)
		{
			liked_products="1";
		}
		else
		{
			liked_products="0";	
		}

		var category=$("#page_category").val();
		var min_value=$(".minvalue").val();
		var max_value=$(".maxvalue").val();
		var keyword=$(".keyword").val();
		keyword=check_undefined(keyword);
		max_value=check_undefined(max_value);
		var price_range=0;
		var page=$("#page_name").val();
		pageNumber=parseInt($scope.currentPage)+1;
		if(typeof category != 'undefined')
		{
			if(category=="")
			{
				category="all";	
			}
		}
		else
		{
			category="all";
		}
		if(typeof page != 'undefined')
		{
			if(page=="")
			{
				page="all";	
			}
		}
		else
		{
			page="all";
		}
		if(page=="onsale" || page == "browse")
		{
			price_range=0;
			if(max_value!="" && (min_value>=0 || max_value<$("#maximum_value").val()))
			{
				price_range=min_value+"-"+max_value;
				setGetParameter('price', price_range);	
			}
			else
			{
				price_range=0;
				setGetParameter('price', price_range);	
			}
			if(keyword!="")
			{
				setGetParameter('filter', keyword);
			}
			else
			{
				keyword="";
				setGetParameter('filter', keyword);	
			}
		}
		
		
		$http.post(APP_URL+'/get_products/'+page+'/'+category+'?page='+pageNumber, {price_range:price_range,keyword: keyword,like:liked_products,first_load:load}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.products_busy = true;
			}
			else
			{
				$scope.products_busy = false;
			}

			if(response.data.data=="")
			{
				$("#search-result-empty").show();
			}
			else
			{
				$("#search-result-empty").hide();	
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.all_product.push(value);
			});	 

			product_lazy();        	

			$("#products_loading").hide();
		});
	};

	//get search product details
	$scope.search_product = function()
	{
		if($scope.products_busy) return;
		$scope.products_busy = true;

		$("#search-result-empty").hide();
		$("#products_loading").show();

		var keyword = $("#search_key").val();
		var searchfor = $("#search_for").val();
		
		keyword=check_undefined(keyword);
		searchfor=check_undefined(searchfor);
		
		pageNumber=parseInt($scope.currentPage)+1;

		if(keyword !='')
		{
			$http.post(APP_URL+'/products_search?page='+pageNumber, {search_key:keyword,search_for:searchfor}).then(function(response) 
			{
				$scope.currentPage=response.data.current_page;
				if(response.data.total == response.data.to || response.data.to==null)
				{
					$scope.products_busy = true;
				}
				else
				{
					$scope.products_busy = false;
				}

				if(response.data.data=="")
				{
					$("#search-result-empty").show();
				}
				else
				{
					$("#search-result-empty").hide();	
				}
				angular.forEach(response.data.data, function(value, key){
					$scope.all_search.push(value);
				});	

				$("#products_loading").hide();
			});
		}
		else
		{
			$("#search-result-empty").show();
			$("#products_loading").hide();
		}
	}
	$scope.search_people = function(){

		$('.search_people').hide();
		
		$("#search-result-empty").hide();
		$("#products_loading").show();

		var keyword = $("#search_key").val();
		var searchfor = 'people';
		var  count = '3';
		keyword=check_undefined(keyword);
		searchfor=check_undefined(searchfor);
		
		pageNumber=1;
		if(keyword !='')
		{
			$http.post(APP_URL+'/products_search?page='+pageNumber, {search_key:keyword,search_for:searchfor,count:count}).then(function(responsed) 
			{
				$scope.currentPage=responsed.data.current_page;
				if(responsed.data.total == responsed.data.to || responsed.data.to==null)
				{
					$scope.products_busy = true;
				}
				else
				{
					$scope.products_busy = false;
				}

				if(responsed.data.data=="")
				{
					$('.search_people').hide();
					$('.topresult').hide();
				}
				else
				{
					$('.search_people').show();
					$('.topresult').show();
				}
				angular.forEach(responsed.data.data, function(value, key){
					$scope.all_people.push(value);
				});	
				$("#products_loading").hide();
			});
		}
		else
		{
			$("#search-result-empty").show();
			$("#products_loading").hide();
		}
	}
	$scope.search_brands = function(){
		
		$('.search_stores').hide();

		$("#search-result-empty").hide();
		$("#products_loading").show();

		var keyword = $("#search_key").val();
		var searchfor = 'brands';
		var  count = '3';
		keyword=check_undefined(keyword);
		searchfor=check_undefined(searchfor);
		
		pageNumber=1;
		if(keyword !='')
		{
			$http.post(APP_URL+'/products_search?page='+pageNumber, {search_key:keyword,search_for:searchfor,count:count}).then(function(responses) 
			{
				$scope.currentPage=responses.data.current_page;
				if(responses.data.total == responses.data.to || responses.data.to==null)
				{
					$scope.products_busy = true;
				}
				else
				{
					$scope.products_busy = false;
				}

				if(responses.data.data=="")
				{
					$('.search_stores').hide();
					$('.topresult').hide();
				}
				else
				{
					$('.search_stores').show();	
					$('.topresult').show();
				}
				angular.forEach(responses.data.data, function(value, key){
					$scope.all_brands.push(value);
				});	

				$("#products_loading").hide();	    	 
			});
		}
		else
		{
			$("#search-result-empty").show();
			$("#products_loading").hide();
		}

	}
	$scope.search_item = function(){
		if($scope.products_busy) return;
		$scope.products_busy = true;			

		$("#search-result-empty").hide();
		$("#products_loading").show();

		var keyword = $("#search_key").val();
		var searchfor = 'things';

		keyword=check_undefined(keyword);
		searchfor=check_undefined(searchfor);
		
		pageNumber=parseInt($scope.currentPage)+1;
		
		if(keyword !='')
		{
			
			$http.post(APP_URL+'/products_search?page='+pageNumber, {search_key:keyword,search_for:searchfor}).then(function(response) 
			{		    	

				$scope.currentPage=response.data.current_page;

				if(response.data.total == response.data.to || response.data.to==null)
				{
					$scope.products_busy = true;
				}
				else
				{
					$scope.products_busy = false;
				}


				angular.forEach(response.data.data, function(value, key){
					$scope.all_items.push(value);		    		
				});	

				if(!$scope.all_items.length && !$scope.all_brands.length && !$scope.all_people.length)
				{
					$("#search-result-empty").show();		    		
				}
				if($scope.all_items=="")
				{
					$('.search_item').hide();
		    		//$("#search-result-empty").show();
		    	}
		    	else
		    	{
		    		$('.search_item').show();
		    		$("#search-result-empty").hide();	
		    	}

		    	$("#products_loading").hide();	
		    });
		}
		else
		{
			$("#search-result-empty").show();
			$("#products_loading").hide();
		}

	}
	var keyword = $("#search_key").val();
	var searchfor = $("#search_for").val();
	$timeout(function () {
		$('.head-search').val(keyword);
	});
	if(typeof searchfor == 'undefined' || searchfor == '' && keyword !='')
	{
		$scope.search_brands();
		$scope.search_people();
	}
//get particular product details in popup

	$(document).on('click','.store_tab',function(){
		$(".store_tab").removeClass("current");
		$(this).addClass("current");
		var data=$(this).attr("data");
		if(data=="product")
		{
			$("#show_store_products").show();
			$("#store_about").hide();
		}
		else
		{
			$("#show_store_products").hide();
			$("#store_about").show();
		}
	});

	$scope.change_option = function(){
		var product_id =$('#product_id').val();
		var option = $('#product_option_'+product_id).val();
		$http.post(APP_URL+'/display_price', { option :option,product_id : product_id }).then(function(response) 
		{	
			$scope.price = response.data.price;
			$scope.retail_price = response.data.retail_price;
			$scope.discount = response.data.discount;
			$scope.total_quantity = response.data.quantity; 				
			$('#quantity').html(response.data.quantity);
		}); 
	}

	$(document).on('click','.popshow',function(e){

		e.preventDefault();

		if($scope.products_details_busy) return;			
		$scope.products_details_busy = true;	

		var product_id = this.id;
		$scope.slider_products=[];
		$scope.slider_products_pager=[];
		$scope.product_option_id= [];
		$scope.video_src = "";
		$scope.video_thumb = "";
		$scope.video_type = "";
		$('.thumb_bxslider li:not(:first)').remove();

		$http.post(APP_URL+'/home_product_details', {productid:product_id}).then(function(response) 
		{

			$scope.products_details_busy = false;


			$scope.product_video = response.data[0].video_src;
			if(response.data[0].video_src) {
				$scope.slider_products_pager.push(response.data[0].video_thumb+' ');
				$scope.video_src = response.data[0].video_src;
				$scope.video_thumb = response.data[0].video_thumb;
				$scope.video_type = response.data[0].video_type;
				$timeout(function () {
					$('.thumbnail-list li:first a').addClass('video-thumb');
				});
			}
			angular.forEach(response.data[0].product_photos, function(value, key){
				$scope.slider_products_pager.push(value.header_image);
				var zoomsliderclass="zoom_slider";

				if(isMobile)
					zoomsliderclass="";
				$('.thumb_bxslider').append('<li><span style="background-image:url('+value.compress_image+');" class="hide"></span><img src="'+value.home_full_image+'" class="'+zoomsliderclass+'" style="object-fit: contain; object-position: center;"></li>');		         
			});

			$timeout(function () {
				$("#overlay-thing .popup").animate({ scrollTop: 0 }, "slow");					
				zoomShowOptions['zIndex'] = 29;
				zoomShowOptions['zoomContainerAppendTo'] = '.popup.thing-detail';
				mySlider.reloadSlider(mySliderOptions);
				var popOverSettings = {
					placement: 'bottom',
					container: 'body'
				}

				$('[data-role="popover"]').popover(popOverSettings);
				$scope.$apply();

				$scope.products_details_busy = false;

			});
			$scope.product = response.data[0];
			$scope.product_title = response.data[0].title;
			$scope.description = response.data[0].description;
			$scope.price = response.data[0].price;  
			$scope.discount = response.data[0].original_discount;
			$scope.retail_price = response.data[0].original_retail_price;
			$scope.like_count = response.data[0].like_count;
			$scope.like_user = response.data[0].like_user;
			$scope.total_quantity = response.data[0].original_total_quantity; 
			$scope.product_id = response.data[0].id;
			$scope.shipping_country = $scope.product.products_shipping;

			$scope.product_option_id=[];
			if($scope.product.product_option !='') 
				$scope.product_option_id[0] = $scope.product.product_option[0].id;
					$('#qty_select').html($scope.product.qty_option);

					$('#popup_container .shipping .country-list ul li').remove();

					var items = [];

					if($scope.shipping_country.length > 0) {
						$('#popup_container .shipping .btn-area').show();
						$('#popup_container .shipping .country-list ul').show();
						$('#popup_container .shipping .country-list .terms').remove();
						$.each($scope.shipping_country, function(i, shipping_country) {

							items.push('<li><a><b data-start="'+shipping_country.start_window+'" data-end="'+shipping_country.end_window+'">'+shipping_country.ships_to+'</b></a></li> ');
							if(i == 0){	
								$('.international.shipping span.able').html(shipping_country.start_window+' - '+shipping_country.end_window+' days to <a class="shipping_country_list">'+shipping_country.ships_to+'</a>');			          	
							}
					   });  // close each()

						$('#popup_container .shipping .country-list ul').append( items.join('') );
						$('#popup_container .shipping .country-list ul li:first').addClass('current');
					}
					else{
						$('#popup_container .shipping .country-list ul').before('<div class="terms"><p>No Country found</p></div>');
						$('#popup_container .shipping .btn-area').hide();
						$('#popup_container .shipping .country-list ul').hide();
					}

					$('#overlay-thing').addClass('shownhome');
					$('#overlay-thing').scrollTop(0);
					$('body').addClass('pos-fix1');
				});
	});

	$(document).on('change','.every-select',function(){
		var category = $('.every-select').val();
		$scope.pop_items=[];
		pop_product(category);
	});

	// $(document).ready(function(){
	// 	$(".search-result-pop").hide();	
	// 	var category = $('.every-select').val();
	// 	$scope.pop_items=[];
	// pop_product(category);
	// });

	//like function product
	$scope.product_like = function (all,product_id)
	{
		var check_login = $('.product_like').hasClass('without_login');

		var check_user_status = $('.product_like').hasClass('inactive-user');

		if(check_login == true)
		{
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else{
			$http.post(APP_URL+'/product_likes', {productid:product_id}).then(function(response) 
			{
				$scope.pro = response.data[0];
				all.like_count = response.data[0].like_count;
				all.like_user = response.data[0].like_user;
				all.user_like = response.data[0].user_like;
				product_lazy();
				$('[data-role="popover"]').popover();
				$(document).on('.mouseover','.user_a',function(){
					var cl=$(this).attr("data-id");
					$("."+cl).trigger("click");
				})

			});
		}
	};

	//like function popup
	$scope.pdu_like = function (product,product_id)
	{
		var check_login = $('.product_like').hasClass('without_login');

		var check_user_status = $('.product_like').hasClass('inactive-user');

		if(check_login == true)
		{
			$('.ly-close').trigger('click');
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else{
			$http.post(APP_URL+'/product_likes', {productid:product_id}).then(function(response) 
			{
				$scope.pro = response.data[0];
				product.like_count = response.data[0].like_count;
				product.like_user = response.data[0].like_user;
				product.user_like = response.data[0].user_like;
				product_lazy();
			});
		}
	};


	//like view profile function product
	$scope.product_likes = function (all,product_id)
	{
		var check_login = $('.product_like').hasClass('without_login');

		var check_user_status = $('.product_like').hasClass('inactive-user');

		if(check_login == true)
		{
			$('.login_popup_head').trigger('click');
		}
		else if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
		}
		else{
			$http.post(APP_URL+'/product_likes', {productid:product_id}).then(function(response) 
			{
				$scope.pro = response.data[0];
				all.wish_product_details.like_count = response.data[0].like_count;
				all.wish_product_details.like_user = response.data[0].like_user;
				all.wish_product_details.user_like = response.data[0].user_like;
				product_lazy();

			});
		}
	};

	

	//get the Populer product details
	function pop_product(category){
		$('.load-popup-product').addClass('loading');
		$http.post(APP_URL+'/pop_products', {category:category}).then(function(responses) 
		{

			$('.load-popup-product').removeClass('loading1');
			$scope.popularproducts = responses.data.data;			

			angular.forEach(responses.data.data, function(value, key){
				$scope.pop_items.push(value);
			});	
			setTimeout(function(){
				$(function($) {
					$('.popular_lazy').Lazy({
						scrollDirection: 'vertical',
						effect: 'fadeIn',
						visibleOnly: true,
					});
				});
			},100);

			if(responses.data.data=="" || $scope.pop_items.length == '')
			{
				$(".search-result-pop").show();
			}
			else
			{
				$(".search-result-pop").hide();	
			}

		});
	}


	if(document.getElementById('slider')) {
		var slider = document.getElementById('slider');
		noUiSlider.create(slider, {
			start: [min_slider_price_value, max_slider_price_value],
			connect: true,
			step: 1,
			margin: 30,
			direction: 'ltr',
			range: {
				'min': min_slider_price,
				'max': max_slider_price
			}

		});


		var marginMin = $(".minprice").val(),
		marginMax = $(".maxprice").val();

		slider.noUiSlider.on('update', function ( values, handle ) {
			if ( handle ) 
			{
				if(max_slider_price==parseInt(values[handle]))
				{
					marginMax.innerHTML = parseInt(values[handle])+'+';
				}
				else
				{
					marginMax.innerHTML = parseInt(values[handle]);
				}

			} else {
				marginMin.innerHTML = parseInt(values[handle]);
			}
		});
		slider.noUiSlider.on('change', function ( values, handle ) {
			$(".minvalue").val(parseInt(values[ 0 ]));
			$(".minprice").html(parseInt(values[ 0 ]));
			if(max_slider_price==parseInt(values[ 1 ]))
			{
				$(".maxprice").html(parseInt(values[ 1 ]) + '+');	
			}
			else
			{
				$(".maxprice").html(parseInt(values[ 1 ]));
			}

			$(".maxvalue").val(parseInt(values[ 1 ]));


			$scope.all_product=[];
			$scope.products_busy=false;
			$scope.currentPage = '0';
			$scope.loadMore();
		});
	}

	if(document.getElementById('mob_slider')) {
		var mob_slider = document.getElementById('mob_slider');
		noUiSlider.create(mob_slider, {
			start: [min_slider_price_value, max_slider_price_value],
			connect: true,
			step: 1,
			margin: 30,
			direction: 'ltr',
			range: {
				'min': min_slider_price,
				'max': max_slider_price
			}

		});


		var marginMin = $(".minprice").val(),
		marginMax = $(".maxprice").val();

		mob_slider.noUiSlider.on('update', function ( values, handle ) {
			if ( handle ) 
			{
				if(max_slider_price==parseInt(values[handle]))
				{
					marginMax.innerHTML = parseInt(values[handle])+'+';
				}
				else
				{
					marginMax.innerHTML = parseInt(values[handle]);
				}

			} else {
				marginMin.innerHTML = parseInt(values[handle]);
			}
		});
		mob_slider.noUiSlider.on('change', function ( values, handle ) {
			$(".minvalue").val(parseInt(values[ 0 ]));
			$(".minprice").html(parseInt(values[ 0 ]));
			if(max_slider_price==parseInt(values[ 1 ]))
			{
				$(".maxprice").html(parseInt(values[ 1 ]) + '+');	
			}
			else
			{
				$(".maxprice").html(parseInt(values[ 1 ]));
			}

			$(".maxvalue").val(parseInt(values[ 1 ]));


			$scope.all_product=[];
			$scope.products_busy=false;
			$scope.currentPage = '0';
			$scope.loadMore('priceUpdate');
		});
	}


	$(document).on("keypress",'.keyword',function(e) {
		if(e.which == 13) {
			$(".keyword").val($(this).val());
			$scope.all_product=[];
			$scope.products_busy=false;
			$scope.currentPage = '0';
			$scope.loadMore('keyword');
		}
	});

	$(document).on("click",'.onsale_tab',function(e) {
		$(".onsale_tab").removeClass("current");
		$(this).addClass("current");
		if(isMobile)
			$('.menuitem .menu').hide();

		if($(this).attr("data")=="fancy")
		{
			$scope.you_liked=1;	
		}
		else
		{
			$scope.you_liked=0;
		}
		$scope.all_product=[];
		$scope.products_busy=false;
		$scope.currentPage = '0';
		$scope.loadMore('onSale');
	});

	function removeGetParameter(key, sourceURL) 
	{
		var rtn = sourceURL.split("?")[0],
		param,
		params_arr = [],
		queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
		if (queryString !== "") {
			params_arr = queryString.split("&");
			for (var i = params_arr.length - 1; i >= 0; i -= 1) {
				param = params_arr[i].split("=")[0];
				if (param === key) {
					params_arr.splice(i, 1);
				}
			}
			rtn = rtn + "?" + params_arr.join("&");
		}
		history.pushState(null, null, rtn);
	}

	function setGetParameter(paramName, paramValue)
	{
		var url = window.location.href;

		if (url.indexOf(paramName + "=") >= 0)
		{
			var prefix = url.substring(0, url.indexOf(paramName));
			var suffix = url.substring(url.indexOf(paramName));
			suffix = suffix.substring(suffix.indexOf("=") + 1);
			suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
			url = prefix + paramName + "=" + paramValue + suffix;
		}
		else
		{
			if (url.indexOf("?") < 0)
				url += "?" + paramName + "=" + paramValue;
			else
				url += "&" + paramName + "=" + paramValue;
		}
		history.pushState(null, null, url);
	}

	function check_undefined(variable)
	{
		if(typeof variable != 'undefined')
		{
			return variable;
		}
		else
		{
			return "";
		}
	}

	
	$scope.HomeFollowStore = function(store_id,user_id) {
		if(user_id==0){
			$('.login_popup_head').trigger('click');
			return false;
		}
		var check_user_status = $('.follow-store').hasClass('inactive-user');

		if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
			return false;
		}

		$http.post(APP_URL+'/follow_store', 
		{ 
			store_id : store_id, 
			follower_id: user_id
		}).then(function(response) 
		{ 

			$('#store_'+store_id).html(response.data.fol);
			$('.store_'+store_id).html(response.data.fol);


		})
	}

	$scope.ActivityFollowStore = function(id,store_id,user_id) {
		if(user_id==0){
			$('.login_popup_head').trigger('click');
			return false;
		}
		var check_user_status = $('.follow-store').hasClass('inactive-user');

		if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
			return false;
		}

		$http.post(APP_URL+'/follow_store', 
		{ 
			store_id : store_id, 
			follower_id: user_id
		}).then(function(response) 
		{ 

			if($('#activitystore_'+id).hasClass('btns-gray-embo1')){
				$('#activitystore_'+id).removeClass('btns-gray-embo1').addClass('btns-gray-embo4');  
			}
			else{
				$('#activitystore_'+id).removeClass('btns-gray-embo4').addClass('btns-gray-embo1');  
			}

			$('#store_'+store_id).html(response.data.fol);
			$('.store_'+store_id).html(response.data.fol);    
		})
	}


	$scope.FollowData = function(user_id,follower_id) {

		if(follower_id==0){
			$('.login_popup_head').trigger('click');
			return false;
		}

		var check_user_status = $('.user_follow').hasClass('inactive-user');

		if(check_user_status == true)
		{
			window.location.href=APP_URL+'/user_disabled';
			return false;
		}


		$http.post(APP_URL+'/follow', 
		{ 
			follower_id : follower_id, 
			user_id:user_id
		}).then(function(response) 
		{ 

			$('#follow_'+user_id).html(response.data.fol);
			$('#followers').html(response.data.following_count);
			$('#following').html(response.data.follower_count);

		})
	}     

	$scope.stores_View = function(){
		$http.post(APP_URL+'/user_follow_stores', { user_id :$('#user_id').val() }).then(function(response) 
		{	
			$scope.stores=response.data;
			if(response.data.data =='')
			{
				$('#search-result-empty').show();
			}

		}); 
	}

	$scope.stores_View();

	$scope.Wishlist_View = function(user_id,category_id){
		
			$scope.Wishlists=[];
			$scope.detail_products=[];
		 	if(user_id==0){
	   		$('.login_popup_head').trigger('click');
	   		return false;
   			}
   		var check_user_status = $('.check_login').hasClass('inactive-user');
	
		$http.post(APP_URL+'/user_wishlists', { user_id :user_id }).then(function(response) 
		{	
			$scope.Wishlis=response.data;	
			$scope.detail_products= $scope.Wishlis.wish_product_details;
	    }); 
	}

	$scope.Wishlist_View();

	$scope.wishlistView = function(){
		$http.post(APP_URL+'/user_view_wishlists', { user_id :$('#user_id').val() }).then(function(response) {	
			$scope.Wishlists=response.data;
	    }); 
	}

	$scope.wishlistView();


	$scope.show = 1;
   // $scope.tab1 = true;

   $scope.wishlist= function(user_id,product_id) {
   	if(user_id==0){
   		$('.login_popup_head').trigger('click');
   		return false;
   	}

   	var check_user_status = $('.check_login').hasClass('inactive-user');

   	if(check_user_status == true)
   	{
   		window.location.href=APP_URL+'/user_disabled';
   	}

   	$http.post(APP_URL+'/wishlist_list', 
   	{ 
   		user_id : user_id, 
   		product_id: product_id
   	}).then(function(response) 
   	{ 
   		if(response.data.wish_type=='Saved to Wishlist')
   		{
   			$('#wishlist_'+product_id).html(response.data.wish);
   			$('#wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
   			$('.wishlist_'+product_id).html(response.data.wish);
   			$('.wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
   		}
   		else{
   			$('#wishlist_'+product_id).html(response.data.wish);
   			$('#wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
   			$('.wishlist_'+product_id).html(response.data.wish);
   			$('.wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
   		}

   	})
   } 


   $scope.loadNewFilter = function (){
	   	$http.post(APP_URL+'/user_wishlists', { user_id :$('#user_id').val() }).then(function(response) 
	   	{	
   			$scope.Wishlists=response.data;
	   	});     
   }

  	function store_products() {
  		$('.store_prod').owlCarousel('destroy')
		$('.store_prod').owlCarousel({
	        loop:false,
		    margin:20,
		    nav:true,
		    dots:false,
		    autoWidth:true,
		    autoHeight:true,
		    responsiveClass:true,
		    responsive:{
		        0:{
		            items:1
		        },
		        600:{
		            items:3
		        },
		        1000:{
		            items:5,
		            slideBy:4
		        }
		    }
	    })
	}

	   $scope.header_slider = function(){
       
          $http.get(APP_URL+'/header_slider').then(function(response) 
          { 
            $scope.header_slider=response.data;
            setTimeout(function() {
              things();
            },50);
          });
      
      }
setTimeout(function(){

	$scope.header_slider();
},1000);
    

      function    things() {
            $('.things').owlCarousel('destroy');
            $('.things').owlCarousel({
                loop:false,
                dots:false,
			    margin:20,
			    nav:true,
			    autoWidth:true,
		    	autoHeight:true,
			    responsiveClass:true,
			    responsive:{
			        0:{
			            items:1
			        },
			        600:{
			            items:3
			        },
			        1000:{
			            items:5,
			            slideBy:4
			        }
			    }
            })
      }
    
}]);

app.controller('newest_product', ['$scope', '$http','$timeout', '$compile', '$sce', function($scope, $http, $timeout, $compile, $sce) 
{
	$scope.currentPage     ='0';
	$scope.currentpg       ='0';
	$scope.products_newest = [];
	$scope.popluar_products = [];
	$scope.recently_thing  = [];
	$scope.detail_products_busy=false;
	$scope.detail_products_popular=false;
	$scope.detail_products_onsale=false;
	$scope.onsale_products = [];
	$scope.new_loadMore = function() {
		if($scope.detail_products_busy) return;
		$scope.detail_products_busy = true;
		pageNumber=parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_newest_products?page='+pageNumber, {}).then(function(response) 
		{
				$scope.currentPage=response.data.current_page;
				if(response.data.total == response.data.to || response.data.to==null)
				{
					$scope.detail_products_busy = true;
				}
				else
				{
					$scope.detail_products_busy = false;
				}
				angular.forEach(response.data.data, function(value, key){
					$scope.products_newest.push(value);
				});
			product_lazy();
		});	
	}
	$scope.new_loadMore();

	$scope.popluar_loadMore = function() {
		if($scope.detail_products_popular) return;
		$scope.detail_products_popular = true;
		pageNumber = parseInt($scope.currentPage)+1;	
		$http.post(APP_URL+'/get_popular_products?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.detail_products_popular = true;
			}
			else
			{
				$scope.detail_products_popular = false;
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.popluar_products.push(value);
			});
			product_lazy();
		});	
	}
	$scope.popluar_loadMore();
	
	$scope.onsale_loadMore = function() {
		if($scope.detail_products_onsale) return;
		$scope.detail_products_onsale = true;
		pageNumber = parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_onSale_product?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.detail_products_onsale = true;
			}
			else
			{
				$scope.detail_products_onsale = false;
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.onsale_products.push(value);
			});
			product_lazy();
		});	
	}

	$scope.onsale_loadMore();

	$scope.show_everything_tab =  function()
	{
		$scope.currentPage     ='0';
		$scope.detail_products_onsale=false;
		$("#liked").removeClass("current");
		$("#everything").addClass("current");
		if($scope.detail_products_onsale) return;
		$scope.detail_products_onsale = true;
		pageNumber = parseInt($scope.currentPage)+1;
		$scope.onsale_products = [];
		$scope.cls_onsale='everything';
		$http.post(APP_URL+'/get_onSale_product?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.detail_products_onsale = true;
			}
			else
			{
				$scope.detail_products_onsale = false;
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.onsale_products.push(value);
			});
			product_lazy();
		});	
	}

	$scope.show_liked_tab =  function()
	{
		$scope.currentPage     ='0';
		$scope.detail_products_onsale=false;
		$scope.onsale_product = [];
		$("#everything").removeClass("current");
		$("#liked").addClass("current");
		if($scope.detail_products_onsale) return;
		pageNumber = parseInt($scope.currentPage)+1;
		$scope.cls_onsale='liked';
		$http.post(APP_URL+'/get_onSale_product/liked?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
			if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.detail_products_onsale = true;
			}
			else
			{
				$scope.detail_products_onsale = false;
			}
			angular.forEach(response.data.data, function(value, key){
				$scope.onsale_product.push(value);
			});
			product_lazy();
		});	
	}

	$scope.wishlist= function(user_id,product_id) {
	   	if(user_id==0){
	   		$('.login_popup_head').trigger('click');
	   		return false;
	   	}
	   	var check_user_status = $('.check_login').hasClass('inactive-user');
	   	if(check_user_status == true)
	   	{
	   		window.location.href=APP_URL+'/user_disabled';
	   	}

	   	$http.post(APP_URL+'/wishlist_list', 
	   	{ 
	   		user_id : user_id, 
	   		product_id: product_id
	   	}).then(function(response) 
	   	{ 
	   		if(response.data.wish_type=='Saved to Wishlist')
	   		{
	   			$('#wishlist_'+product_id).html(response.data.wish);
	   			$('#wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
	   			$('.wishlist_'+product_id).html(response.data.wish);
	   			$('.wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
	   		}
	   		else{
	   			$('#wishlist_'+product_id).html(response.data.wish);
	   			$('#wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
	   			$('.wishlist_'+product_id).html(response.data.wish);
	   			$('.wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
	   		}

	   	})
   } 
}]);


app.controller('FollowController',['$scope','$http',function($scope,$http) {
	$scope.FollowData = function() {
		var check_user_status = $('#follow').hasClass('inactive-user');
		if(check_user_status == true) {
			window.location.href=APP_URL+'/user_disabled';
			return false;
		}
		else {
			$http.post(APP_URL+'/follow', 
			{ 
				follower_id : $('#follower_id').val(), 
				user_id:$('#user_id').val()
			}).then(function(response) { 
				$('#follow').html(response.data.fol);
				$('#followers').html(response.data.following_count);
				$('#following').html(response.data.follower_count);
			})
		}
	}      
}]);

app.directive('myEnter', function () {
	return function (scope, element, attrs) {
		element.bind("keydown keypress", function (event) {
			if(event.which === 13) {
				scope.$apply(function (){
					scope.$eval(attrs.myEnter);
				});
				event.preventDefault();
			}
		});
	};
});

app.controller('messages', ['$scope', '$http','$timeout', function($scope, $http, $timeout) 
{
	$scope.all_messages=[];
	$scope.load_messages = function() {
		$('#message_text').removeAttr('readonly');
		$scope.all_messages=[];
		$("#message_loading").show();
		var group_id=$("#select_user").attr('data-group-id');
		$http.post(APP_URL+'/get_messages',{group_id:group_id}).then(function(response) 
			{$scope.all_messages=[];
				angular.forEach(response.data, function(value, key){
					if(key!="count")
					{
						$scope.all_messages.push(value);	
					}		        
				});
				$timeout(function() {
					var scroller = document.getElementById("message_detail");
					scroller.scrollTop = scroller.scrollHeight;
				}, 0, false);

				$("#message_loading").hide();
			});
	};

	$scope.search_sidebar = function(){
		$scope.get_sidebar();
	}
	$scope.get_sidebar = function() {
		$scope.message_sidebar=[];
		$("#message_side_loading").show();
		var search=$("#side_search").val()
		$http.post(APP_URL+'/messages/get_sidebar_user',{searchfor:search}).then(function(response) 
		{
			$scope.message_sidebar=response.data;
			if(response.data.length)
			{
				$("#message_side_empty").hide();	
			}
			else
			{
				$("#message_side_empty").show();	
			}
			$("#message_side_loading").hide();
		});
	}
	$scope.get_sidebar();
	
	$scope.show_user_message=function(group_id,user_to,user_from,to_name,from_name)
	{
		var now_user=$("#user_from").val();
		if(user_to!=now_user)
		{
			$("#select_user").val(to_name);
			$("#select_user").attr('data-id',user_to);			
		}
		else if(user_from!=now_user)
		{
			$("#select_user").val(from_name);
			$("#select_user").attr('data-id',user_from);
		}
		
		$("#select_user").attr('data-group-id',group_id);
		
		$scope.load_messages();
	}

	$scope.refresh_message = function()
	{
		$scope.get_sidebar();
		$scope.load_messages();
	}
	$scope.load_users = function()
	{
		$("#select_user").attr('data-id',"0");
		$scope.users_list=[];
		$("#show_user").show();
		var text=$("#select_user").val();
		$("#user_loading").show();
		$http.post(APP_URL+'/messages/get_message_user',{text:text}).then(function(response) 
		{
			$scope.users_list=response.data;
			if(response.data.length)
			{
				$("#user_empty").hide();	
			}
			else
			{
				$("#user_empty").show();	
			}

			$("#user_loading").hide();
		});
	}



	$scope.clear_results = function()
	{
		$scope.all_messages=[];
		$("#select_user").val("");
		$("#select_user").attr('data-id',0);
		$("#select_user").attr('data-group-id',"");
		$("#show_user").hide();
		$('#message_text').val("");
		$('#message_text').attr('readonly','readonly');
	}
	$scope.select_user = function(val,id,group_id)
	{
		$("#select_user").val(val);
		$("#select_user").attr('data-id',id);
		$("#select_user").attr('data-group-id',group_id);
		$("#show_user").hide();
		$scope.load_messages();
	}
	$scope.send_message = function()
	{
		var user=$("#select_user").val();
		var user_to=$("#select_user").attr('data-id');
		var message_text=$("#message_text").val();
		if(user!="" && user_to!="0")
		{
			var user_from=$("#user_from").val();
			
			
			$(".send_loading").show();
			$("#message_text").val("");
			$('#send_message').prop("disabled", true);
			$http.post(APP_URL+'/messages/send_message',{message:message_text,user_to:user_to}).then(function(response) 
			{
				$scope.get_sidebar();
				$(".send_loading").hide();
				$scope.all_messages.push(response.data);
				$("#select_user").attr('data-group-id',response.data.group_id);
				$timeout(function() {
					var scroller = document.getElementById("message_detail");
					scroller.scrollTop = scroller.scrollHeight;
				}, 0, false);


			});
		}
		else
		{
			$( "#select_user" ).focus();
		}
	}
	$('#message_text').attr('readonly','readonly');
	$("#message_text").keydown(function(){
		var data=$(this).val();
		if(data.length>0)
		{
			$('#send_message').prop("disabled", false);
		}
		else
		{
			$('#send_message').prop("disabled", true);
		}
	});

}]);

app.controller('featured_products', ['$scope', '$http','$timeout', function($scope, $http,$timeout) 
{
	$scope.currentPage = '0';
	$scope.products_busy=false;
	$scope.detail_products=[];

	$scope.wishlist= function(user_id,product_id) {
	   	if(user_id==0){
	   		$('.login_popup_head').trigger('click');
	   		return false;
	   	}

	   	var check_user_status = $('.check_login').hasClass('inactive-user');

	   	if(check_user_status == true)
	   	{
	   		window.location.href=APP_URL+'/user_disabled';
	   	}

	   	$http.post(APP_URL+'/wishlist_list', 
	   	{ 
	   		user_id : user_id, 
	   		product_id: product_id
	   	}).then(function(response) 
	   	{ 
	   		if(response.data.wish_type=='Saved to Wishlist')
	   		{
	   			$('#wishlist_'+product_id).html(response.data.wish);
	   			$('#wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
	   			$('.wishlist_'+product_id).html(response.data.wish);
	   			$('.wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
	   		}
	   		else{
	   			$('#wishlist_'+product_id).html(response.data.wish);
	   			$('#wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
	   			$('.wishlist_'+product_id).html(response.data.wish);
	   			$('.wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
	   		}

	   	})
   } 
	$scope.detailf_products = function() {
		if($scope.products_busy) return;
		$scope.products_busy = true;
		pageNumber=parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_feedproducts/featured'+'?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
	    	if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.products_busy = true;
			}
			else
			{
				$scope.products_busy = false;
			}
	    	angular.forEach(response.data.data, function(value, key){
				$scope.detail_products.push(value);
			});
		});
	};

	$scope.detailf_products();
}]);

app.controller('edit_products', ['$scope', '$http','$timeout', function($scope, $http,$timeout) 
{
	$scope.currentPage = '0';
	$scope.products_busy=false;
	$scope.editors_products=[];
	$scope.wishlist= function(user_id,product_id) {
	   	if(user_id==0){
	   		$('.login_popup_head').trigger('click');
	   		return false;
	   	}
	   	var check_user_status = $('.check_login').hasClass('inactive-user');
	   	if(check_user_status == true)
	   	{
	   		window.location.href=APP_URL+'/user_disabled';
	   	}

	   	$http.post(APP_URL+'/wishlist_list', 
	   	{ 
	   		user_id : user_id, 
	   		product_id: product_id
	   	}).then(function(response) 
	   	{ 
	   		if(response.data.wish_type=='Saved to Wishlist')
	   		{
	   			$('#wishlist_'+product_id).html(response.data.wish);
	   			$('#wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
	   			$('.wishlist_'+product_id).html(response.data.wish);
	   			$('.wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
	   		}
	   		else{
	   			$('#wishlist_'+product_id).html(response.data.wish);
	   			$('#wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
	   			$('.wishlist_'+product_id).html(response.data.wish);
	   			$('.wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
	   		}

	   	})
   };
	$scope.editor_products = function() {
		if($scope.products_busy) return;
		$scope.products_busy = true;
		pageNumber=parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_editorproducts/editor'+'?page='+pageNumber, {}).then(function(response) 
		{
			$scope.currentPage=response.data.current_page;
	    	if(response.data.total == response.data.to || response.data.to==null)
			{
				$scope.products_busy = true;
			}
			else
			{
				$scope.products_busy = false;
			}
	    	angular.forEach(response.data.data, function(value, key){
					$scope.editors_products.push(value);
			});
		});
	};
	$scope.editor_products();
}]);

app.controller('recommended_products', ['$scope', '$http','$timeout', function($scope, $http,$timeout) 
{
	$scope.currentPage = '0';
	$scope.products_busy=false;
	$scope.recommended_products=[];
	$scope.recommended_loadMore = function() {
		if($scope.products_busy) return;
		$scope.products_busy = true;
		pageNumber=parseInt($scope.currentPage)+1;
		$http.post(APP_URL+'/get_recommendedproducts/recommended'+'?page='+pageNumber, {}).then(function(response) 
		    {	
		    	$scope.currentPage=response.data.current_page;
		    	if(response.data.total == response.data.to || response.data.to==null)
				{
					$scope.products_busy = true;
				}
				else
				{
					$scope.products_busy = false;
				}
		    	angular.forEach(response.data.data, function(value, key){
						$scope.recommended_products.push(value);
				});
				product_lazy();
		    });	
	};
	$scope.recommended_loadMore();
	$scope.wishlist= function(user_id,product_id) {
   	if(user_id==0){
   		$('.login_popup_head').trigger('click');
   		return false;
   	}
   	var check_user_status = $('.check_login').hasClass('inactive-user');
   	if(check_user_status == true)
   	{
   		window.location.href=APP_URL+'/user_disabled';
   	}

   	$http.post(APP_URL+'/wishlist_list', 
   	{ 
   		user_id : user_id, 
   		product_id: product_id
   	}).then(function(response) 
   	{ 
   		if(response.data.wish_type=='Saved to Wishlist')
   		{
   			$('#wishlist_'+product_id).html(response.data.wish);
   			$('#wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
   			$('.wishlist_'+product_id).html(response.data.wish);
   			$('.wishlist_'+product_id).removeClass('icon-heart-o').addClass('icon-heart');
   		}
   		else{
   			$('#wishlist_'+product_id).html(response.data.wish);
   			$('#wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
   			$('.wishlist_'+product_id).html(response.data.wish);
   			$('.wishlist_'+product_id).removeClass('icon-heart').addClass('icon-heart-o');
   		}

   	})
   } 
}]);
