<?php

use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('orders')->delete();

DB::table('orders')->insert(array(
  array('id' => '1','buyer_id' => '10004','subtotal' => '299.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '29.90','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '328.90','currency_code' => 'USD','transaction_id' => '4AL35397TA946331V','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 16:36:11','updated_at' => '2017-09-20 16:36:11'),
  array('id' => '2','buyer_id' => '10003','subtotal' => '499.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '49.90','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '548.90','currency_code' => 'USD','transaction_id' => '0NR83374EA033744K','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 17:05:32','updated_at' => '2017-09-20 17:05:32'),
  array('id' => '3','buyer_id' => '10003','subtotal' => '399.00','shipping_fee' => '12.00','incremental_fee' => '0.00','service_fee' => '41.10','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '452.10','currency_code' => 'USD','transaction_id' => '23C2628273454470F','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 17:31:07','updated_at' => '2017-09-20 17:31:07'),
  array('id' => '4','buyer_id' => '10003','subtotal' => '329.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '32.90','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '361.90','currency_code' => 'USD','transaction_id' => '83629320088222038','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 17:32:34','updated_at' => '2017-09-20 17:32:34'),
  array('id' => '5','buyer_id' => '10003','subtotal' => '55.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '5.50','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '60.50','currency_code' => 'USD','transaction_id' => '6SM98677RU9714316','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 17:33:44','updated_at' => '2017-09-20 17:33:44'),
  array('id' => '6','buyer_id' => '10004','subtotal' => '89.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '8.90','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '97.90','currency_code' => 'USD','transaction_id' => '5KR35686UF406884K','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 17:38:05','updated_at' => '2017-09-20 17:38:05'),
  array('id' => '7','buyer_id' => '10004','subtotal' => '150.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '15.00','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '165.00','currency_code' => 'USD','transaction_id' => '1A600878ND108423H','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 17:39:18','updated_at' => '2017-09-20 17:39:18'),
  array('id' => '8','buyer_id' => '10004','subtotal' => '62.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '6.20','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '68.20','currency_code' => 'USD','transaction_id' => '3JH99121C5336132J','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 17:45:26','updated_at' => '2017-09-20 17:45:26'),
  array('id' => '9','buyer_id' => '10004','subtotal' => '45.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '4.50','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '49.50','currency_code' => 'USD','transaction_id' => '21K04160VA8029707','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 17:58:59','updated_at' => '2017-09-20 17:58:59'),
  array('id' => '10','buyer_id' => '10004','subtotal' => '700.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '70.00','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '770.00','currency_code' => 'USD','transaction_id' => '3SY83077VW075503G','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-20 19:33:55','updated_at' => '2017-09-20 19:33:55'),
  array('id' => '11','buyer_id' => '10004','subtotal' => '299.00','shipping_fee' => '0.00','incremental_fee' => '0.00','service_fee' => '29.90','merchant_fee' => NULL,'coupon_code' => '','coupon_amount' => NULL,'total' => '328.90','currency_code' => 'USD','transaction_id' => '85J263456Y4809318','customer_id' => NULL,'card_id' => NULL,'paymode' => 'PayPal','created_at' => '2017-09-21 22:53:14','updated_at' => '2017-09-21 22:53:14')
));
}
}
