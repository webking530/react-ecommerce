<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

//cron manage
Route::get('currency_cron', 'CronController@currency');
Route::get('update_owe_cron', 'CronController@update_owe_amount');

Route::group(['middleware' => ['locale']], function () {
	Route::get('things/{id}', 'ProductController@productDetail')->name('product_detail');
});

Route::group(['middleware' => ['canInstall', 'locale']], function () {
	Route::get('/', 'HomeController@index');
	Route::get('static_populer', 'HomeController@static_populer');
	Route::get('header_slider', 'UserController@header_slider');
	Route::get('category_browse', 'UserController@category_browse');
	Route::get('cron_for_image_compression', 'ProductController@cron_for_image_compression');
	Route::get('cron_for_category_compression', 'ProductController@cron_for_category_compression');
	Route::get('cron_for_local_to_cloudinary', 'ProductController@cron_for_local_to_cloudinary');
	Route::get('cron_for_local_to_cloudinary_category', 'ProductController@cron_for_local_to_cloudinary_category');
	Route::get('product_slider', 'UserController@product_slider');
	Route::post('get_products_likes', 'HomeController@get_products_likes');
	Route::post('get_liked_users', 'HomeController@productLikedUsers');
});

Route::group(['middleware' => 'locale'], function () {
	Route::post('set_session', 'HomeController@set_session');
	Route::get('shop/{page}','HomeController@getFeature');
	Route::get('shop/{page?}/{category?}', 'HomeController@staticCategory')->where('category','.+');

	Route::get('check_order_email', 'HomeController@check_order_email');
	Route::get('search', 'HomeController@search');
	Route::get('stores', 'StoreController@stores');
	Route::post('ajax_search', 'ProductController@ajax_search');
	Route::get('profile/{uname}', 'HomeController@userPage');

	Route::post('products_search', 'ProductController@products_search');

	Route::view('forgot', 'home.forgot');

	Route::get('users/confirm_email/{code?}', 'UserController@confirm_email');

	Route::Post('forgot_password', 'UserController@forgot_password');

	Route::get('users/set_password/{secret?}', 'UserController@set_password');

	Route::post('users/set_password', 'UserController@set_password');

	Route::post('home_product', 'HomeController@home_product');

	Route::post('get_products/{searchby?}/{category?}', 'ProductController@getCategoryProduct');

    Route::post('get_newest_products', 'ProductController@getNewestProducts');

    Route::post('get_popular_products', 'ProductController@getPopularProducts');

    Route::post('get_onSale_product','ProductController@getOnSaleProduct');
    Route::post('get_onSale_product/liked','ProductController@getOnSaleProductLiked');
	
	// Product Based
	// Route::post('get_products/{category?}', 'ProductController@getCategoryProduct'); 
	Route::post('shop/featured', 'ProductController@getFeedProduct'); 
	Route::post('get_editorproducts/editor', 'ProductController@getEditorProduct'); 
	Route::post('get_recommendedproducts/recommended', 'ProductController@getRecommendedProduct'); 
	Route::post('get_notification', 'HomeController@get_notification');

	Route::post('get_activity', 'HomeController@get_activity_header');
	Route::post('get_activity_header', 'HomeController@get_activity_header');

	Route::post('get_notification_header', 'HomeController@get_notification_header');

	Route::post('get_merchant_header', 'HomeController@get_merchant_header');


	Route::post('recently_viewed_things', 'HomeController@recently_viewed_things');

	Route::post('get_stores/{searchby?}/{category?}', 'StoreController@get_stores');

	Route::post('get_store_products/{id?}', 'StoreController@get_store_product');

	Route::get('store/{id}', 'StoreController@view_store');

	Route::post('home_product', 'HomeController@home_product');

	Route::post('home_product_details', 'HomeController@home_product_details');

	Route::post('pop_products', 'ProductController@pop_products');

	Route::post('store_products', 'StoreController@store_products');

	Route::post('user_wishlists', 'UserController@user_wishlists');

	Route::post('user_view_wishlists', 'UserController@userWishlists');
	
	Route::post('user_follow_stores', 'UserController@user_follow_stores');

	Route::get('payments/success', 'PaymentController@success');

	Route::get('payments/cancel', 'PaymentController@cancel');

	Route::post('checkout_payment', 'PaymentController@create_booking');

	Route::post('payments/payout', 'PaymentController@payout');

	Route::post('payments/refund', 'PaymentController@refund');

	Route::post('get_price_details', 'MerchantController@get_price_details');

	Route::post('get_review_orders', 'MerchantController@get_review_orders');

	Route::post('get_billing_details', 'MerchantController@get_billing_details');

	Route::post('get_shipping_details', 'MerchantController@get_shipping_details');

	Route::post('display_price', 'ProductController@display_price');

	Route::get('user_disabled', 'UserController@user_disabled');

	//Stripe payout prefrences
	Route::match(['get', 'post'], 'users/update_payout_preferences/{id}', 'MerchantController@update_payout_preferences')->where('id', '[0-9]+');
});

// User Side
Route::group(['middleware' => ['guest:web', 'locale']], function () {
	//Home Controller
	Route::get('login', 'HomeController@login');
	Route::get('signup', 'HomeController@signup');
	Route::get('banner_image', 'HomeController@banner_image');

	// User Controller
	Route::post('user/check_email', 'UserController@check_email');

	Route::post('user/check_users', 'UserController@check_users');

	Route::post('user/check_username', 'UserController@check_username');

	Route::post('create', 'UserController@create');

	Route::post('authenticate', 'UserController@authenticate');

	Route::get('facebookAuthenticate', 'UserController@facebookAuthenticate');

	Route::get('facebookLogin', 'UserController@facebookLogin');

	Route::get('googleAuthenticate', 'UserController@googleAuthenticate');

	Route::get('twitterAuthenticate', 'UserController@twitterAuthenticate');

	Route::get('twitterLogin', 'UserController@twitterLogin');

});

Route::group(['middleware' => ['auth:web', 'locale']], function () {

	// Route::get('logout', 'UserController@logout');
	Route::get('logout', function () {
			Auth::guard('web')->logout();
			return Redirect::to('login');
	});


	Route::post('product_likes', 'ProductController@product_likes');

	Route::post('follow', 'HomeController@follow');

	Route::post('follow_store', 'StoreController@follow_store');

	Route::post('wishlist_list', 'HomeController@wishlist_list');

	Route::post('wishlist_product', 'HomeController@wishlistProduct');

	Route::get('notification', 'HomeController@index');

	Route::get('activity', 'HomeController@activity')->name('activity');

	Route::post('upload_cover_img', 'UserController@upload_cover_image');

	Route::get('remove_cover_img', 'UserController@remove_cover_image');

	Route::post('upload_profile_img', 'UserController@upload_profile_image');

	Route::get('edit_profile', 'UserController@edit_profile');

	Route::get('user_settings', 'UserController@user_settings');

	Route::post('users/update', 'UserController@update');

	Route::Post('buyer/update_password', 'UserController@update_password');

	Route::post('user/resend_confirmation', 'UserController@resend_confirmation');

	Route::get('cart', 'CartController@cart');

	Route::post('add_to_cart', 'CartController@add_to_cart');

	Route::post('cart_update', 'CartController@cart_update');

	Route::post('remove_cart', 'CartController@remove_cart');

	Route::post('cart_product', 'CartController@cart_product');

	Route::post('apply_coupon', 'CartController@apply_coupon');

	Route::post('remove_cover_imageupon', 'CartController@remove_coupon');

	Route::match(array('GET', 'POST'), 'checkout', 'CartController@checkout');

	Route::post('add_shipping_details', 'MerchantController@add_shipping_details');

	Route::post('add_billing_details', 'MerchantController@add_billing_details');

	Route::post('add_payment_method', 'MerchantController@add_payment_method');

	Route::post('get_payment_method', 'MerchantController@get_payment_method');

	Route::get('purchases', 'OrdersController@purchases');

	Route::get('change_password', 'UserController@change_password');

	Route::get('purchases/{id?}', 'OrdersController@view_order');

	Route::post('purchases_details', 'OrdersController@purchases_details');

	Route::post('purchases_action', 'OrdersController@purchases_action');

	Route::get('invoice/{id?}', 'OrdersController@invoice_details');

	Route::get('messages', 'MessageController@index');

	Route::post('messages/get_message_user/{text?}', 'MessageController@get_message_user');

	Route::post('messages/get_sidebar_user', 'MessageController@get_sidebar_user');

	Route::post('get_messages', 'MessageController@get_messages');

	Route::post('messages/send_message', 'MessageController@send_messages');
});

Route::get('clear__l-log', 'HomeController@clearLog');
Route::get('show__l-log', 'HomeController@showLog');

Route::get('{name}', 'HomeController@static_pages');