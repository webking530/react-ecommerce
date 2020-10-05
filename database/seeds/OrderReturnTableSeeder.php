<?php

use Illuminate\Database\Seeder;

class OrderReturnTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('order_return')->delete();

DB::table('order_return')->insert([
  ['id' => '1','order_id' => '3','return_reason' => 'Damaged','status' => 'Approved','created_at' => '2017-09-20 13:36:59','updated_at' => '2017-09-20 13:39:24'],
  ['id' => '2','order_id' => '10','return_reason' => 'Product Damaged','status' => 'Approved','created_at' => '2017-09-20 14:10:06','updated_at' => '2017-09-20 14:10:46'],


	]);
}
}
