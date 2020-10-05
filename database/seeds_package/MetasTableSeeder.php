<?php

use Illuminate\Database\Seeder;

class MetasTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('metas')->delete();

		DB::table('metas')->insert(array(
		  array('id' => '1','url' => '/','title' => 'Join me on Spiffy! Discover amazing stuff, collect the things you love, buy it all in one place. ','description' => 'Join me on Spiffy! Discover amazing stuff, collect the things you love, buy it all in one place. ','keywords' => ''),
		  array('id' => '2','url' => 'shop/{page?}/{category?} ','title' => 'Join me on Spify! Discover amazing stuff, collect the things you love, buy it all in one place.','description' => 'Join me on Spify! Discover amazing stuff, collect the things you love, buy it all in one place.','keywords' => ''),
		  array('id' => '3','url' => 'chart','title' => 'Chart ','description' => 'Chart','keywords' => ''),
		  array('id' => '4','url' => 'check_order_email','title' => 'Order Email ','description' => 'Order Email','keywords' => ''),
		  array('id' => '5','url' => 'search ','title' => 'Search','description' => 'Join me on Spify! Discover amazing stuff, collect the things you love, buy it all in one place.','keywords' => ''),
		  array('id' => '6','url' => 'stores ','title' => 'Stores','description' => 'Stores ','keywords' => ''),
		  array('id' => '7','url' => 'activity','title' => 'Activity Feed','description' => 'Activity ','keywords' => ''),
		  array('id' => '8','url' => 'notification','title' => 'Notifications','description' => 'Activity ','keywords' => ''),
		  array('id' => '9','url' => 'forgot ','title' => 'Forgot Password ','description' => 'Forgot Password','keywords' => ''),
		  array('id' => '10','url' => 'added','title' => 'Added ','description' => 'Added','keywords' => ''),
		  array('id' => '11','url' => 'following_stores ','title' => 'Following Stores','description' => 'Following Stores ','keywords' => ''),
		  array('id' => '12','url' => 'wishlist ','title' => 'Wishlist','description' => 'Wishlist ','keywords' => ''),
		  array('id' => '13','url' => 'change_password','title' => 'Change Password ','description' => 'Change Password','keywords' => ''),
		  array('id' => '14','url' => 'users/confirm_email/{code?}','title' => 'Confirm Email ','description' => 'Confirm Email','keywords' => ''),
		  array('id' => '15','url' => 'users/set_password/{secret?} ','title' => 'Set Password','description' => 'Set Password ','keywords' => ''),
		  array('id' => '16','url' => 'shipping ','title' => 'Shipping','description' => 'Shipping ','keywords' => ''),
		  array('id' => '17','url' => 'checkout ','title' => 'Checkout','description' => 'Checkout ','keywords' => ''),
		  array('id' => '18','url' => 'messages ','title' => 'Messages','description' => 'Messages ','keywords' => ''),
		  array('id' => '19','url' => 'ProductController','title' => 'Product ','description' => 'Product','keywords' => ''),
		  array('id' => '20','url' => 'store/{id}','title' => 'Store ','description' => 'Store','keywords' => ''),
		  array('id' => '21','url' => 'payments/success ','title' => 'Payment Sucess','description' => 'Payment Sucess ','keywords' => ''),
		  array('id' => '22','url' => 'payments/cancel','title' => 'Payment Cancel','description' => 'Payment Cancel ','keywords' => ''),
		  array('id' => '23','url' => 'merchant/login ','title' => 'Merchant Login','description' => 'Merchant Login ','keywords' => ''),
		  array('id' => '24','url' => 'merchant/signup','title' => 'Merchant Signup ','description' => 'Merchant Signup','keywords' => ''),
		  array('id' => '25','url' => 'merchant/dashboard ','title' => 'Merchant Dashboard','description' => 'Merchant Dashboard ','keywords' => ''),
		  array('id' => '26','url' => 'merchant/all_products','title' => 'Merchant All Product','description' => 'Merchant All Product ','keywords' => ''),
		  array('id' => '27','url' => 'merchant/add_product ','title' => 'Add Product ','description' => 'Add Product','keywords' => ''),
		  array('id' => '28','url' => 'merchant/order ','title' => 'Merchant Order','description' => 'Merchant Order ','keywords' => ''),
		  array('id' => '29','url' => 'merchant/order/{id}','title' => 'Merchant Order','description' => 'Merchant Order ','keywords' => ''),
		  array('id' => '30','url' => 'merchant/edit_product/{id} ','title' => 'Edit Product','description' => 'Edit Product ','keywords' => ''),
		  array('id' => '31','url' => 'merchant/get_product/{id}','title' => 'Get Product ','description' => 'Get Product','keywords' => ''),
		  array('id' => '32','url' => 'merchant/settings_general','title' => 'Merchant Settings ','description' => 'Merchant Settings','keywords' => ''),
		  array('id' => '33','url' => 'merchant/settings','title' => 'Settings','description' => 'Settings ','keywords' => ''),
		  array('id' => '34','url' => 'merchant/product_active','title' => 'Active Product','description' => 'Active Product ','keywords' => ''),
		  array('id' => '35','url' => 'merchant/product_inactive','title' => 'Inactive Product','description' => 'Inactive Product ','keywords' => ''),
		  array('id' => '36','url' => 'merchant/product_soldout ','title' => 'Soldout ','description' => 'Soldout','keywords' => ''),
		  array('id' => '37','url' => 'merchant/product_expired ','title' => 'Expired ','description' => 'Expired','keywords' => ''),
		  array('id' => '38','url' => 'merchant/organization_product','title' => 'Organization','description' => 'Organization ','keywords' => ''),
		  array('id' => '39','url' => 'merchant/collections ','title' => 'Merchant Collections','description' => 'Merchant Collections ','keywords' => ''),
		  array('id' => '40','url' => 'merchant/insights','title' => 'Insights','description' => 'Insights ','keywords' => ''),
		  array('id' => '41','url' => 'merchant/order_all ','title' => 'Order All ','description' => 'Order All','keywords' => ''),
		  array('id' => '42','url' => 'merchant/order_completed ','title' => 'Order Completed ','description' => 'Order Completed','keywords' => ''),
		  array('id' => '43','url' => 'merchant/order_cancelled ','title' => 'Order Cancelled ','description' => 'Order Cancelled','keywords' => ''),
		  array('id' => '44','url' => 'merchant/order_affiliate ','title' => 'Affilliate','description' => 'Affilliate ','keywords' => ''),
		  array('id' => '45','url' => 'merchant/order_customer','title' => 'Customer','description' => 'Customer ','keywords' => ''),
		  array('id' => '46','url' => 'merchant/order_return','title' => 'Return','description' => 'Return ','keywords' => ''),
		  array('id' => '47','url' => 'merchant/settings_notify ','title' => 'Notify','description' => 'Notify ','keywords' => ''),
		  array('id' => '48','url' => 'merchant/settings_policy ','title' => 'Policy','description' => 'Policy ','keywords' => ''),
		  array('id' => '49','url' => 'merchant/settings_paid ','title' => 'Getting Paid','description' => 'Getting Paid ','keywords' => ''),
		  array('id' => '50','url' => 'merchant/settings_paid/transfers ','title' => 'Transfer','description' => 'Transfer ','keywords' => ''),
		  array('id' => '51','url' => 'edit_profile ','title' => 'Edit Profile','description' => 'Edit Profile ','keywords' => ''),
		  array('id' => '52','url' => 'cart ','title' => 'Cart','description' => 'Cart ','keywords' => ''),
		  array('id' => '53','url' => 'payment','title' => 'Payment ','description' => 'Payment','keywords' => ''),
		  array('id' => '54','url' => 'checkout ','title' => 'Checkout','description' => 'Checkout ','keywords' => ''),
		  array('id' => '55','url' => 'purchases','title' => 'Purchases ','description' => 'Purchases','keywords' => ''),
		  array('id' => '56','url' => 'purchases/{id?}','title' => 'Purchases ','description' => 'Purchases','keywords' => ''),
		  array('id' => '57','url' => 'invoice/{id?}','title' => 'Invoice ','description' => 'Invoice','keywords' => ''),
		  array('id' => '58','url' => 'orders ','title' => 'Orders','description' => 'Orders ','keywords' => ''),
		  array('id' => '59','url' => 'login','title' => 'Login ','description' => 'Login','keywords' => ''),
		  array('id' => '60','url' => 'signup','title' => 'Signup ','description' => 'Signup','keywords' => ''),
		  array('id' => '61','url' => 'settings','title' => 'Settings ','description' => 'Settings','keywords' => ''),
		  array('id' => '62','url' => 'profile/{uname}','title' => 'UserProfile ','description' => 'UserProfile','keywords' => ''),
		  array('id' => '63','url' => 'profile/{uname?}/{view_detail?}','title' => 'UserProfile ','description' => 'UserProfile','keywords' => '')
		));
	}
}