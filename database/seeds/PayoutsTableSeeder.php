<?php

use Illuminate\Database\Seeder;

class PayoutsTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('payouts')->delete();

DB::table('payouts')->insert([

  ['id' => '1','order_id' => '6','order_detail_id' => '6','user_id' => '10002','user_type' => 'merchant','account' => 'Paypal','correlation_id' => NULL,'amount' => '89.00','subtotal' => '89.00','service' => '8.90','shipping' => '0.00','currency_code' => 'USD','status' => 'Future','created_at' => '2017-09-20 12:17:55','updated_at' => '2017-09-20 12:17:55'],
  ['id' => '2','order_id' => '4','order_detail_id' => '4','user_id' => '10001','user_type' => 'merchant','account' => 'Paypal','correlation_id' => NULL,'amount' => '329.00','subtotal' => '329.00','service' => '32.90','shipping' => '0.00','currency_code' => 'USD','status' => 'Future','created_at' => '2017-09-20 12:19:58','updated_at' => '2017-09-20 12:19:58'],
  ['id' => '3','order_id' => '2','order_detail_id' => '2','user_id' => '10001','user_type' => 'buyer','account' => 'Paypal','correlation_id' => NULL,'amount' => '548.90','subtotal' => '499.00','service' => '49.90','shipping' => '0.00','currency_code' => 'USD','status' => 'Future','created_at' => '2017-09-20 12:25:30','updated_at' => '2017-09-20 12:25:30'],
  ['id' => '4','order_id' => '9','order_detail_id' => '9','user_id' => '10002','user_type' => 'buyer','account' => 'Paypal','correlation_id' => NULL,'amount' => '49.50','subtotal' => '45.00','service' => '4.50','shipping' => '0.00','currency_code' => 'USD','status' => 'Future','created_at' => '2017-09-20 12:30:06','updated_at' => '2017-09-20 12:30:06'],
  ['id' => '6','order_id' => '3','order_detail_id' => '3','user_id' => '10003','user_type' => 'buyer','account' => 'Paypal','correlation_id' => NULL,'amount' => '399.00','subtotal' => '399.00','service' => '41.10','shipping' => '12.00','currency_code' => 'USD','status' => 'Future','created_at' => '2017-09-20 13:39:24','updated_at' => '2017-09-20 13:39:24'],
  ['id' => '8','order_id' => '10','order_detail_id' => '10','user_id' => '10004','user_type' => 'buyer','account' => 'Paypal','correlation_id' => NULL,'amount' => '700.00','subtotal' => '700.00','service' => '70.00','shipping' => '0.00','currency_code' => 'USD','status' => 'Future','created_at' => '2017-09-20 14:10:46','updated_at' => '2017-09-20 14:10:46'],


	]);
}
}
