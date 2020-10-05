<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:api');

//TokenAuthController
Route::get('expire_token', 'Api\TokenAuthController@token_expire');
Route::get('signup', 'Api\TokenAuthController@signup');

Route::get('authenticate', 'Api\TokenAuthController@authenticate');

Route::get('token', 'Api\TokenAuthController@token');

Route::get('login', 'Api\TokenAuthController@login');

Route::get('socialsignup', 'Api\TokenAuthController@socialsignup');

Route::get('user_check_socialsignup', 'Api\TokenAuthController@user_check_signup');

Route::get('change_email', 'Api\TokenAuthController@change_email');

Route::get('change_password', 'Api\TokenAuthController@change_password');
Route::get('featured_product', 'Api\ProductController@featured_product');

Route::group(['middleware' => 'jwt.auth'], function () {

	Route::get('logout', 'Api\TokenAuthController@logout');

//profileController

	Route::get('edit_profile', 'Api\ProfileController@edit_profile');

	Route::get('view_profile', 'Api\ProfileController@view_profile');

	Route::get('liked_user_list', 'Api\ProfileController@liked_user_list');

	Route::get('currency', 'Api\ProfileController@currency');

	Route::get('follower_details', 'Api\ProfileController@follower_details');

	Route::get('follow_store', 'Api\ProfileController@follow_store');

	Route::get('follow_user', 'Api\ProfileController@follow_user');

//liked product store details
	Route::get('liked_store_list', 'Api\ProfileController@liked_store_list');

//productController

	Route::get('like_product', 'Api\ProductController@like_product');

	Route::get('wish_list', 'Api\ProductController@wish_list');

	Route::get('wishlist_details', 'Api\ProductController@wishlist_details');

//CartController

	Route::get('add_cart', 'Api\CartController@add_cart');

	Route::get('shipping_address', 'Api\CartController@shipping_address');

	Route::get('view_shipping_address', 'Api\CartController@view_shipping_address');

	Route::get('shopping_cart', 'Api\CartController@shopping_cart');

	Route::get('order_detail', 'Api\CartController@order_detail');

//PaymentController

	Route::get('purchase_order', 'Api\PaymentController@purchase_order');

	Route::get('order_cancel', 'Api\PaymentController@order_cancel');

	Route::get('order_return', 'Api\PaymentController@order_return');

//Report Product
	Route::get('report_product', 'Api\ProductController@report');

//Block User
	Route::get('block_user', 'Api\ProfileController@block');

//Blocked Users List
	Route::get('blocked_users', 'Api\ProfileController@blocked_users');

});

Route::match(array('GET', 'POST'), 'upload_profile_image', 'Api\ProfileController@upload_profile_image');

Route::match(array('GET', 'POST'), 'upload_image', 'Api\ProfileController@upload_image');

Route::get('shop_page', 'Api\ProfileController@shop_page');

Route::match(array('GET', 'POST'), 'store_profile', 'Api\ProfileController@store_profile');

//SearchController

Route::match(array('GET', 'POST'), 'product_search', 'Api\SearchController@product_search');

Route::match(array('GET', 'POST'), 'editor_picks', 'Api\SearchController@editor_picks');

Route::get('search', 'Api\SearchController@search');

//ProductController
Route::get('product_details', 'Api\ProductController@product_details');

Route::get('category', 'Api\ProductController@category');
