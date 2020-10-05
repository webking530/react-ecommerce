<?php

use Illuminate\Database\Seeder;

class ProductLikeTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('product_likes')->delete();

DB::table('product_likes')->insert(array(
  array('id' => '1','user_id' => '10003','product_id' => '1','created_at' => '2017-09-20 19:44:37'),
  array('id' => '2','user_id' => '10003','product_id' => '2','created_at' => '2017-09-20 19:44:46'),
  array('id' => '3','user_id' => '10003','product_id' => '4','created_at' => '2017-09-20 19:44:54'),
  array('id' => '4','user_id' => '10003','product_id' => '8','created_at' => '2017-09-20 19:45:03'),
  array('id' => '5','user_id' => '10003','product_id' => '19','created_at' => '2017-09-20 19:45:09'),
  array('id' => '6','user_id' => '10003','product_id' => '5','created_at' => '2017-09-20 19:45:26'),
  array('id' => '7','user_id' => '10004','product_id' => '23','created_at' => '2017-09-20 19:46:21'),
  array('id' => '8','user_id' => '10004','product_id' => '24','created_at' => '2017-09-20 19:46:27'),
  array('id' => '9','user_id' => '10004','product_id' => '25','created_at' => '2017-09-20 19:46:40'),
  array('id' => '10','user_id' => '10004','product_id' => '26','created_at' => '2017-09-20 19:46:47'),
  array('id' => '13','user_id' => '10004','product_id' => '11','created_at' => '2017-09-20 19:47:07'),
  array('id' => '14','user_id' => '10004','product_id' => '14','created_at' => '2017-09-20 19:47:16'),
  array('id' => '15','user_id' => '10002','product_id' => '3','created_at' => '2017-09-22 02:31:21'),
  array('id' => '16','user_id' => '10003','product_id' => '51','created_at' => '2020-02-03 19:18:26'),
  array('id' => '17','user_id' => '10003','product_id' => '10','created_at' => '2020-02-03 19:18:28'),
  array('id' => '18','user_id' => '10003','product_id' => '9','created_at' => '2020-02-03 19:18:29'),
  array('id' => '19','user_id' => '10008','product_id' => '54','created_at' => '2020-02-04 15:40:23')
));
}
}
