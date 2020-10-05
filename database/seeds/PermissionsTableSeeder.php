<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        DB::table('permissions')->insert([
            ['id' => '1','name' => 'manage_admin','display_name' => 'Manage Admin','description' => 'Manage Admin Users'],
            ['id' => '2','name' => 'users','display_name' => 'View Users','description' => 'View Users'],
            ['id' => '3','name' => 'add_user','display_name' => 'Add User','description' => 'Add User'],
            ['id' => '4','name' => 'edit_user','display_name' => 'Edit User','description' => 'Edit User'],
            ['id' => '5','name' => 'delete_user','display_name' => 'Delete User','description' => 'Delete User'],
            ['id' => '6','name' => 'manage_category','display_name' => 'Manage Category','description' => 'Manage Category'],
            ['id' => '7','name' => 'products','display_name' => 'View Products','description' => 'View Products'],
            ['id' => '8','name' => 'add_product','display_name' => 'Add Product','description' => 'Add Product'],
            ['id' => '9','name' => 'edit_product','display_name' => 'Edit Product','description' => 'Edit Product'],
            ['id' => '10','name' => 'delete_product','display_name' => 'Delete Product','description' => 'Delete Product'],
            ['id' => '11','name' => 'manage_stores','display_name' => 'Manage Stores','description' => 'Manage Stores'],
            ['id' => '12','name' => 'api_credentials','display_name' => 'Api Credentials','description' => 'Api Credentials'],
            ['id' => '13','name' => 'payment_gateway','display_name' => 'Payment Gateway','description' => 'Payment Gateway'],
            ['id' => '14','name' => 'site_settings','display_name' => 'Site Settings','description' => 'Site Settings'],
            ['id' => '15','name' => 'manage_fees','display_name' => 'Manage Fees','description' => 'Manage Fees'],
            ['id' => '16','name' => 'manage_orders','display_name' => 'Manage Orders','description' => 'Manage Orders'],
            ['id' => '17','name' => 'manage_return_policy','display_name' => 'Manage Return Policy','description' => 'Manage Return Policy'],
            ['id' => '18','name' => 'reports','display_name' => 'Reports','description' => 'Reports'],
            ['id' => '19','name' => 'manage_country','display_name' => 'Manage Country','description' => 'Manage Country'],
            ['id' => '20','name' => 'manage_currency','display_name' => 'Manage Currency','description' => 'Manage Currency'],
            ['id' => '21','name' => 'manage_pages','display_name' => 'Manage Pages','description' => 'Manage Pages'],
            ['id' => '22','name' => 'manage_emails','display_name' => 'Manage Emails','description' => 'Manage Emails'],
            ['id' => '23','name' => 'manage_metas','display_name' => 'Manage Metas','description' => 'Manage Metas'],
            ['id' => '24','name' => 'manage_language','display_name' => 'Manage Language','description' => 'Manage Language'],
            ['id' => '25','name' => 'manage_coupon_code','display_name' => 'Manage Coupon Code','description' => 'Manage Coupon Code'],
            ['id' => '26','name' => 'product_reports','display_name' => 'Product Reports','description' => 'Product Reports'],
            ['id' => '27','name' => 'block_users','display_name' => 'Block Users','description' => 'Block Users'],
            ['id' => '28', 'name' => 'manage_home_page_sliders', 'display_name' => 'Manage Home Page Sliders', 'description' => 'Manage Home Page Sliders'],
            ['id' => '29', 'name' => 'manage_our_favouritest', 'display_name' => 'Manage Our Favouritest', 'description' => 'Manage Our Favouritest'],
            ['id' => '30', 'name' => 'join_us', 'display_name' => 'Join Us', 'description' => 'Join Us'],
        ]);
    }
}
