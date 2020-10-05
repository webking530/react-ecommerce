<?php

use Illuminate\Database\Seeder;

class ProductClickTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('product_click')->delete();

DB::table('product_click')->insert(array(
  array('id' => '1','user_id' => '10004','product_id' => '1','created_at' => '2017-09-20 15:41:39'),
  array('id' => '2','user_id' => '10003','product_id' => '3','created_at' => '2017-09-20 16:38:02'),
  array('id' => '3','user_id' => '10003','product_id' => '23','created_at' => '2017-09-20 17:07:33'),
  array('id' => '4','user_id' => '10003','product_id' => '25','created_at' => '2017-09-20 17:13:46'),
  array('id' => '5','user_id' => '10003','product_id' => '25','created_at' => '2017-09-20 17:13:46'),
  array('id' => '6','user_id' => '10003','product_id' => '31','created_at' => '2017-09-20 17:17:03'),
  array('id' => '7','user_id' => '10003','product_id' => '10','created_at' => '2017-09-20 17:24:41'),
  array('id' => '8','user_id' => '10003','product_id' => '10','created_at' => '2017-09-20 17:29:19'),
  array('id' => '9','user_id' => '10003','product_id' => '4','created_at' => '2017-09-20 17:29:58'),
  array('id' => '10','user_id' => '10003','product_id' => '4','created_at' => '2017-09-20 17:29:58'),
  array('id' => '11','user_id' => '10003','product_id' => '5','created_at' => '2017-09-20 17:31:41'),
  array('id' => '12','user_id' => '10003','product_id' => '5','created_at' => '2017-09-20 17:31:41'),
  array('id' => '13','user_id' => '10003','product_id' => '7','created_at' => '2017-09-20 17:32:58'),
  array('id' => '14','user_id' => '10004','product_id' => '37','created_at' => '2017-09-20 17:34:37'),
  array('id' => '15','user_id' => '10004','product_id' => '14','created_at' => '2017-09-20 17:38:32'),
  array('id' => '16','user_id' => '10004','product_id' => '11','created_at' => '2017-09-20 17:44:23'),
  array('id' => '17','user_id' => '10004','product_id' => '12','created_at' => '2017-09-20 17:57:12'),
  array('id' => '18','user_id' => '10003','product_id' => '4','created_at' => '2017-09-20 19:03:55'),
  array('id' => '19','user_id' => '10004','product_id' => '32','created_at' => '2017-09-20 19:13:21'),
  array('id' => '20','user_id' => '10004','product_id' => '1','created_at' => '2017-09-21 22:51:21'),
  array('id' => '21','user_id' => '10004','product_id' => '30','created_at' => '2017-09-21 23:10:21'),
  array('id' => '22','user_id' => '10002','product_id' => '23','created_at' => '2020-01-31 17:59:35'),
  array('id' => '23','user_id' => '10002','product_id' => '6','created_at' => '2020-02-03 16:34:08'),
  array('id' => '24','user_id' => '10002','product_id' => '41','created_at' => '2020-02-03 16:34:24'),
  array('id' => '25','user_id' => '10002','product_id' => '3','created_at' => '2020-02-03 17:44:58'),
  array('id' => '26','user_id' => '10002','product_id' => '42','created_at' => '2020-02-03 18:02:05'),
  array('id' => '27','user_id' => '10002','product_id' => '51','created_at' => '2020-02-04 10:13:57'),
  array('id' => '28','user_id' => '10002','product_id' => '9','created_at' => '2020-02-04 10:14:26'),
  array('id' => '29','user_id' => '10003','product_id' => '9','created_at' => '2020-02-04 10:17:24'),
  array('id' => '30','user_id' => '10007','product_id' => '51','created_at' => '2020-02-04 11:12:34'),
  array('id' => '31','user_id' => '10008','product_id' => '32','created_at' => '2020-02-04 12:28:40'),
  array('id' => '32','user_id' => '10008','product_id' => '41','created_at' => '2020-02-04 12:28:52'),
  array('id' => '33','user_id' => '10008','product_id' => '52','created_at' => '2020-02-04 12:29:18'),
  array('id' => '34','user_id' => '10008','product_id' => '53','created_at' => '2020-02-04 12:29:20'),
  array('id' => '35','user_id' => '10008','product_id' => '51','created_at' => '2020-02-04 12:36:26'),
  array('id' => '36','user_id' => '10008','product_id' => '45','created_at' => '2020-02-04 15:40:59'),
  array('id' => '37','user_id' => '10002','product_id' => '4','created_at' => '2020-02-04 16:11:44')
));
}
}
