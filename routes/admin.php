<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin Routes
Route::group(['middleware' => 'guest:admin'], function () {
	Route::get('login', 'AdminController@login');
	Route::get('get', 'AdminController@get');
});

Route::post('authenticate', 'AdminController@authenticate');

Route::group(['middleware' => 'auth:admin'], function () {

	Route::get('/', function () {
		return Redirect::to('admin/dashboard');
	});

	Route::get('dashboard', 'AdminController@index');
	
	// Manage Admin Permission Routes
	Route::group(['middleware' => 'permission:manage-admin'], function () {
		// Admin Users
		Route::get('admin_users', 'AdminController@view');
		Route::match(array('GET', 'POST'), 'add_admin_user', 'AdminusersController@add');
		Route::match(array('GET', 'POST'), 'edit_admin_user/{id}', 'AdminusersController@update')->where('id', '[0-9]+');
		Route::get('delete_admin_user/{id}', 'AdminusersController@delete')->where('id', '[0-9]+');

		// Roles & permissions manage
		Route::get('roles', 'RolesController@index');
		Route::match(array('GET', 'POST'), 'add_role', 'RolesController@add');
		Route::match(array('GET', 'POST'), 'edit_role/{id}', 'RolesController@update')->where('id', '[0-9]+');
		Route::get('delete_role/{id}', 'RolesController@delete')->where('id', '[0-9]+');

	});

	// user management
	Route::get('users','UserController@index')->middleware('permission:view-users');
	Route::match(array('GET', 'POST'), 'add_user', 'UserController@add')->middleware('permission:create-users');

	Route::match(array('GET', 'POST'), 'edit_user/{id}', ['uses' => 'UserController@update','middleware' => 'permission:update-users'])->where('id', '[0-9]+');

	Route::get('delete_user/{id}', 'UserController@delete')->where('id', '[0-9]+')->middleware('permission:delete-users');

	Route::get('user_update', 'UserController@user_update');

	// Manage Category
	Route::group(['middleware' => 'permission:manage-category'], function () {

		Route::get('categories', 'CategoryController@index');

		Route::get('category_update', 'CategoryController@category_update');

		Route::match(array('GET', 'POST'), 'add_category', 'CategoryController@add');

		Route::match(array('GET', 'POST'), 'edit_category/{id}', 'CategoryController@update');

		Route::match(array('GET', 'POST'), 'delete_category/{id}', 'CategoryController@delete');

	});

	Route::group(['middleware' => 'permission:manage-join_us'], function () {
		Route::match(array('GET', 'POST'), 'join_us', 'AdminController@joinUs')->name("join_us");
	});	

	Route::post('products/change_currency', 'ProductController@change_currency');
	Route::post('products/edit_currency', 'ProductController@edit_currency');
	Route::post('products/check_price', 'ProductController@check_price');
	Route::post('products/check_option_price', 'ProductController@check_option_price');
	
	// Manage Products
	Route::get('products', 'ProductController@index')->middleware('permission:view-products');

	Route::get('products/add', 'ProductController@add')->middleware('permission:create-products');

	Route::match(array('GET', 'POST'), 'products/edit/{id}','ProductController@edit_product')->where('id', '[0-9]+')->middleware('permission:update-products');

	Route::match(array('GET', 'POST'), 'products/delete/{id}', 'ProductController@delete_product')->middleware('permission:delete-products')->where('id', '[0-9]+');

	Route::match(array('GET', 'POST'), 'products/product_add', 'ProductController@product_add')->middleware('permission:create-products');

	Route::group(['middleware' => 'permission:update-products'], function () {
		Route::post('products/delete_photo', 'ProductController@delete_product_photo');
		Route::post('product/add_video_mp4/{id}/{type?}', 'ProductController@add_product_video_mp4');
		Route::post('product/add_video_webm/{id}/{type?}', 'ProductController@add_product_video_webm');
		Route::post('product/add_video_thumb/{id}/{type?}', 'ProductController@add_video_thumb');
		Route::post('products/add_option_photos/{id}/{option}/{option_db}/{type?}', 'ProductController@add_product_option_photo');

		Route::post('products/add_photos/{id}/{product_id}/{type?}', 'ProductController@add_product_photo');
		Route::post('products/add_video/{id}/{type?}', 'ProductController@add_product_video');

		Route::get('products/get_product/{id}', 'ProductController@get_product');
		Route::get('set_approval', 'ProductController@set_approval');
		Route::get('status_update', 'ProductController@status_update');
		Route::get('set_update', 'ProductController@set_update');
		Route::match(array('GET', 'POST'), 'view_product/{id}', 'ProductController@view_product');
		Route::match(array('GET', 'POST'), 'delete_product/{id}', 'ProductController@delete');
	});

	// stores management
	Route::group(['middleware' => 'permission:manage-stores'], function () {
		Route::get('stores', 'StoreController@index');
		Route::get('store_update', 'StoreController@store_update');
	});

	// currency manage
	Route::group(['middleware' => 'permission:manage-currency'], function () {
		Route::get('currency', 'CurrencyController@index');
		Route::match(array('GET', 'POST'), 'add_currency', 'CurrencyController@add');
		Route::match(array('GET', 'POST'), 'edit_currency/{id}', 'CurrencyController@update')->where('id', '[0-9]+');
		Route::get('delete_currency/{id}', 'CurrencyController@delete')->where('id', '[0-9]+');
	});

	// Manage Coupon Code Routes
	Route::group(['middleware' => 'permission:manage-coupon_code'], function () {
		Route::get('coupon_code', 'CouponCodeController@index');
		Route::match(array('GET', 'POST'), 'add_coupon_code', 'CouponCodeController@add');
		Route::match(array('GET', 'POST'), 'edit_coupon_code/{id}', 'CouponCodeController@update')->where('id', '[0-9]+');
		Route::get('delete_coupon_code/{id}', 'CouponCodeController@delete');
	});

	// Api credentials
	Route::match(array('GET', 'POST'), 'api_credentials', 'ApiCredentialsController@index')->middleware('permission:manage-api_credentials');

	// Payment Gateway
	Route::match(array('GET', 'POST'), 'payment_gateway', 'PaymentGatewayController@index')->middleware('permission:manage-payment_gateway');

	// Site Settings
	Route::match(array('GET', 'POST'), 'site_settings', 'SiteSettingsController@index')->middleware('permission:manage-site_settings');

	// Manage Fee
	Route::match(array('GET', 'POST'), 'fees', 'FeesController@index')->middleware('permission:manage-site_settings');

	// Manage Orders
	Route::group(['middleware' => 'permission:manage-orders'], function () {
		Route::get('orders', 'OrderController@index');
		Route::get('view_order/{id}', 'OrderController@view_orders');
		Route::get('need_payout_info/{id}/{merchant_id}/{type}', 'OrderController@need_payout_info');
	});

	Route::group(['middleware' => 'permission:manage-return_policy'], function () {
		Route::get('returns_policy', 'ReturnpolicyController@index');
		Route::match(array('GET', 'POST'), 'add_return_policy', 'ReturnpolicyController@add_return_policy');
		Route::match(array('GET', 'POST'), 'edit_return_policy/{id?}', 'ReturnpolicyController@update');
		Route::get('delete_return_policy/{id}', 'ReturnpolicyController@delete');
	});

	// Manage reports
	Route::group(['middleware' => 'permission:manage-reports'], function () {
		Route::match(['GET', 'POST'], 'reports', 'ReportsController@index');
		Route::get('reports/export/{from}/{to}/{category}', 'ReportsController@export');
	});

	// Manage Country Routes
	Route::group(['middleware' => 'permission:manage-country'], function () {
		Route::get('country', 'CountryController@index');
		Route::match(array('GET', 'POST'), 'add_country', 'CountryController@add');
		Route::match(array('GET', 'POST'), 'edit_country/{id}', 'CountryController@update')->where('id', '[0-9]+');
		Route::get('delete_country/{id}', 'CountryController@delete')->where('id', '[0-9]+');
		Route::match(array('GET', 'POST'), 'update_country_status/{id}/{status}', 'CountryController@update_status')->where('id', '[0-9]+');
	});

	// Manage Country Routes
	Route::group(['middleware' => 'permission:manage-language'], function () {
		Route::get('language', 'LanguageController@index');
		Route::match(array('GET', 'POST'), 'add_language', 'LanguageController@add');
		Route::match(array('GET', 'POST'), 'edit_language/{id}', 'LanguageController@update')->where('id', '[0-9]+');
		Route::get('delete_language/{id}', 'LanguageController@delete')->where('id', '[0-9]+');
	});

	// Manage Pages Routes
	Route::group(['middleware' => 'permission:manage-pages'], function () {
		Route::get('pages', 'PagesController@index');
		Route::match(array('GET', 'POST'), 'add_page', 'PagesController@add');
		Route::match(array('GET', 'POST'), 'edit_page/{id}', 'PagesController@update')->where('id', '[0-9]+');
		Route::get('delete_page/{id}', 'PagesController@delete')->where('id', '[0-9]+');
	});

	// Manage Owe Amount Routes
	Route::group(['middleware' => 'permission:manage-owe_amount'], function () {
		Route::get('owe', 'OweController@index');
	});

	// Manage Email
	Route::group(['middleware' => 'permission:manage-emails'], function () {
		Route::match(array('GET', 'POST'), 'email_settings', 'EmailController@index');
		Route::match(array('GET', 'POST'), 'send_email', 'EmailController@send_email');
	});

	// Manage Meta
	Route::group(['middleware' => 'permission:manage-metas'], function () {
		Route::match(array('GET', 'POST'), 'metas', 'MetasController@index');
		Route::match(array('GET', 'POST'), 'edit_meta/{id}', 'MetasController@update')->where('id', '[0-9]+');
	});

	// Manage Home Page Slider Routes
	Route::group(['middleware' => 'permission:manage-home_page_sliders'], function () {
        Route::get('slider', 'SliderController@index');
        Route::match(array('GET', 'POST'), 'add_slider', 'SliderController@add');
        Route::match(array('GET', 'POST'), 'edit_slider/{id}', 'SliderController@update')->where('id', '[0-9]+');
        Route::get('delete_slider/{id}', 'SliderController@delete')->where('id', '[0-9]+');
    });

	// Manage Feature Page Slider Routes
	Route::group(['middleware' => 'permission:manage-our_favouritest'], function () { 
        Route::get('feature_slider', 'FeaturePageController@index');
        Route::match(array('GET', 'POST'), 'add_homepage_image', 'FeaturePageController@add');
        Route::match(array('GET', 'POST'), 'edit_homepage_image/{id}', 'FeaturePageController@update')->where('id', '[0-9]+');
        Route::get('delete_homepage_image/{id}', 'FeaturePageController@delete')->where('id', '[0-9]+');
    });

	Route::get('product_reports', 'ProductReportsController@index')->middleware('permission:manage-product_reports');
	Route::get('blocked_users', 'BlockUsersController@index');
	
	Route::get('logout', 'AdminController@logout');
});