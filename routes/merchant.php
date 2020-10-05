<?php

/*
|--------------------------------------------------------------------------
| Merchant Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

// Merchant Controller
Route::get('merchant', 'MerchantController@index');
Route::get('merchant/login', 'MerchantController@index');

// merchant signup
Route::post('merchant/create', 'MerchantController@create');
Route::get('merchant/signup', 'MerchantController@signup');

// Merchant Controller
Route::group(['middleware' => ['auth:web', 'locale', 'merchant_auth']], function () {

	Route::get('merchant/dashboard', 'MerchantController@dashboard');

	Route::get('merchant/all_products', 'MerchantController@all_products');

	Route::post('merchant/product_search', 'MerchantController@product_search');

	Route::post('merchant/product/status_update', 'MerchantController@product_status_update');

	Route::get('merchant/get_product/{id}', 'MerchantController@get_product');

	Route::post('merchant/change_currency', 'MerchantController@change_currency');

	Route::get('merchant/add_product', 'MerchantController@add_product');

	Route::post('merchant/product_add', 'MerchantController@product_add');

	Route::get('merchant/edit_product/{id}', 'MerchantController@edit_product');

	Route::post('merchant/product_update/{id}', 'MerchantController@product_update');

	Route::post('merchant/product_list', 'MerchantController@product_list');

	Route::post('merchant/product/add_photos/{id}/{product_id}/{type?}', 'MerchantController@add_product_photo');

	Route::post('merchant/product/add_option_photos/{id}/{option}/{option_db}/{type?}', 'MerchantController@add_product_option_photo');

	Route::post('merchant/product/delete', 'MerchantController@product_delete');

	Route::post('product/delete_photo', 'MerchantController@delete_product_photo');

	Route::post('product/delete_option', 'MerchantController@delete_option');

	Route::post('product/clear_temp_images', 'ProductController@clear_temp_images');

	Route::post('merchant/product/add_video_mp4/{id}/{type?}', 'MerchantController@add_product_video_mp4');
	Route::post('merchant/product/add_video_webm/{id}/{type?}', 'MerchantController@add_product_video_webm');
	Route::post('merchant/product/add_video_thumb/{id}/{type?}', 'MerchantController@add_video_thumb');

	// Merchant Order
	Route::get('merchant/order', 'MerchantController@order');

	Route::post('merchant/merchant_action', 'MerchantController@merchant_action');

	Route::post('merchant/orders_details_view', 'MerchantController@orders_details_view');

	Route::post('merchant/orders_view', 'MerchantController@orders_view');

	Route::post('merchant/order_search', 'MerchantController@order_search');

	Route::post('merchant/return_search', 'MerchantController@return_search');

	Route::get('merchant/order/{id}', 'MerchantController@view_order');

	Route::get('merchant/order_return', 'MerchantController@order_return');

	Route::post('merchant/return_request', 'MerchantController@return_request');

	Route::post('merchant/order/status_update', 'MerchantController@order_status_update');

	Route::post('merchant/order_return/status_update', 'MerchantController@order_return_status_update');

	// Merchant Insights

	Route::get('merchant/insights', 'MerchantController@insights');

	Route::post('merchant/insight_summary', 'MerchantController@insight_summary');

	// Merchant Settings
	Route::get('merchant/settings', 'MerchantController@settings');

	Route::get('merchant/settings_general', 'MerchantController@settings_general');

	Route::post('merchant/update_seller_profile', 'MerchantController@update_seller_profile');

	Route::post('merchant/store/get_store_data', 'MerchantController@get_store_data');

	Route::post('merchant/store/save_brand', 'MerchantController@update_brand');

	Route::post('merchant/store/update_logo/{id}', 'MerchantController@add_store_logo');

	Route::post('merchant/store/update_header/{id}', 'MerchantController@add_store_header');

	Route::post('merchant/store/delete_store_logo', 'MerchantController@delete_store_logo');

	Route::post('merchant/store/delete_store_header', 'MerchantController@delete_store_header');

	Route::get('merchant/settings_paid', 'MerchantController@settings_paid')->name('settings_paid');

	Route::get('merchant/settings_paid/transfers', 'MerchantController@payout_history');

	Route::post('merchant/settings_paid/transfers', 'MerchantController@transfers');

	Route::post('merchant/add_payout_preferences', 'MerchantController@add_payout_preferences');

	Route::match(['get', 'post'], 'merchant/stripe_payout_preferences', 'MerchantController@stripe_payout_preferences');

	Route::post('merchant/get_payout_preferences', 'MerchantController@get_payout_preferences');

	Route::post('merchant/default_pay', 'MerchantController@default_pay');

	Route::post('merchant/remove_pay', 'MerchantController@remove_pay');
});