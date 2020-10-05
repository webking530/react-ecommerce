<?php

use Illuminate\Database\Seeder;

class ProductsPriceDetailsTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 * 
 * @return void
 */
public function run()
{
DB::table('products_prices_details')->delete();

DB::table('products_prices_details')->insert(array(
  array('product_id' => '1','sku' => '','price' => '299','retail_price' => '310','discount' => '3.55','length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-13 20:16:00','updated_at' => '2020-02-04 13:16:39'),
  array('product_id' => '2','sku' => '','price' => '1254','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-14 14:34:14','updated_at' => '2020-01-31 19:41:04'),
  array('product_id' => '3','sku' => '10','price' => '499','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-14 14:38:17','updated_at' => '2020-01-31 19:41:33'),
  array('product_id' => '4','sku' => '','price' => '399','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-14 14:49:05','updated_at' => '2020-02-03 18:20:46'),
  array('product_id' => '5','sku' => '','price' => '329','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-14 14:59:58','updated_at' => '2020-02-03 18:21:03'),
  array('product_id' => '6','sku' => '','price' => '390','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-14 20:00:31','updated_at' => '2020-01-31 19:42:26'),
  array('product_id' => '7','sku' => '','price' => '55','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-14 20:11:05','updated_at' => '2020-01-31 19:43:40'),
  array('product_id' => '8','sku' => '','price' => '4','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-15 10:55:15','updated_at' => '2020-01-31 19:44:30'),
  array('product_id' => '9','sku' => '','price' => '750','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-15 11:11:14','updated_at' => '2020-01-31 19:44:33'),
  array('product_id' => '10','sku' => '','price' => '46','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-15 14:27:40','updated_at' => '2020-01-31 19:11:16'),
  array('product_id' => '11','sku' => '','price' => '62','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 15:17:41','updated_at' => '2020-01-31 19:11:33'),
  array('product_id' => '12','sku' => '','price' => '45','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 15:22:15','updated_at' => '2020-01-31 19:11:44'),
  array('product_id' => '13','sku' => '','price' => '200','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 15:24:35','updated_at' => '2020-01-31 19:12:10'),
  array('product_id' => '14','sku' => '','price' => '150','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 15:27:51','updated_at' => '2020-01-31 19:12:24'),
  array('product_id' => '15','sku' => '','price' => '28','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 15:37:13','updated_at' => '2020-01-31 19:12:35'),
  array('product_id' => '16','sku' => '','price' => '40','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:07:54','updated_at' => '2020-01-31 19:12:47'),
  array('product_id' => '17','sku' => '','price' => '250','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:11:16','updated_at' => '2020-01-31 19:13:02'),
  array('product_id' => '18','sku' => '','price' => '54','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:13:40','updated_at' => '2020-01-31 19:13:51'),
  array('product_id' => '19','sku' => '','price' => '925','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:27:54','updated_at' => '2020-01-31 19:45:43'),
  array('product_id' => '20','sku' => '','price' => '5700','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:31:48','updated_at' => '2020-01-31 19:45:58'),
  array('product_id' => '21','sku' => '','price' => '389','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:35:20','updated_at' => '2020-01-31 19:46:10'),
  array('product_id' => '22','sku' => '','price' => '1049','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:41:33','updated_at' => '2020-01-31 19:46:22'),
  array('product_id' => '23','sku' => '','price' => '1900','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:45:12','updated_at' => '2020-01-31 19:46:37'),
  array('product_id' => '24','sku' => '','price' => '33','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:49:23','updated_at' => '2020-01-31 19:46:49'),
  array('product_id' => '25','sku' => '','price' => '60','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 18:53:20','updated_at' => '2020-01-31 19:47:05'),
  array('product_id' => '26','sku' => '','price' => '1570','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 20:15:21','updated_at' => '2020-01-31 19:47:27'),
  array('product_id' => '27','sku' => '','price' => '59','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 20:31:53','updated_at' => '2020-01-31 19:48:29'),
  array('product_id' => '28','sku' => '','price' => '15','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 20:48:36','updated_at' => '2020-01-31 19:49:07'),
  array('product_id' => '30','sku' => '','price' => '65','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 20:53:45','updated_at' => '2020-01-31 19:49:53'),
  array('product_id' => '31','sku' => '','price' => '75','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 20:56:27','updated_at' => '2020-01-31 19:56:19'),
  array('product_id' => '32','sku' => '','price' => '700','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:03:14','updated_at' => '2020-02-03 18:31:26'),
  array('product_id' => '33','sku' => '','price' => '500','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:05:04','updated_at' => '2020-02-03 18:29:39'),
  array('product_id' => '34','sku' => '','price' => '200','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:07:54','updated_at' => '2020-02-03 18:30:27'),
  array('product_id' => '35','sku' => '','price' => '58','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:14:57','updated_at' => '2020-02-03 18:28:17'),
  array('product_id' => '36','sku' => '','price' => '54','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:17:00','updated_at' => '2020-02-03 18:30:58'),
  array('product_id' => '37','sku' => '','price' => '89','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:20:05','updated_at' => '2020-01-31 19:14:59'),
  array('product_id' => '38','sku' => '','price' => '55','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:23:18','updated_at' => '2020-01-31 19:15:30'),
  array('product_id' => '39','sku' => '','price' => '246','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:25:40','updated_at' => '2020-02-03 18:29:56'),
  array('product_id' => '40','sku' => '','price' => '54','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2017-09-19 21:29:20','updated_at' => '2020-02-03 18:30:40'),
  array('product_id' => '41','sku' => '','price' => '200','retail_price' => '300','discount' => '33.33','length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2018-01-05 13:36:19','updated_at' => '2020-01-31 19:57:52'),
  array('product_id' => '42','sku' => '','price' => '500','retail_price' => '800','discount' => '37.50','length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2018-01-05 14:00:09','updated_at' => '2020-02-03 18:26:09'),
  array('product_id' => '43','sku' => '','price' => '250','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2018-01-05 14:02:41','updated_at' => '2020-02-03 18:23:41'),
  array('product_id' => '44','sku' => '','price' => '450','retail_price' => '600','discount' => '25.00','length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2018-01-05 14:05:22','updated_at' => '2020-01-31 20:08:08'),
  array('product_id' => '45','sku' => '','price' => '800','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2018-01-05 14:07:59','updated_at' => '2020-02-03 18:24:25'),
  array('product_id' => '46','sku' => '','price' => '100','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-03 11:29:41','updated_at' => '2020-02-03 18:56:16'),
  array('product_id' => '47','sku' => '50','price' => '4104','retail_price' => NULL,'discount' => NULL,'length' => '9.00','width' => '6.00','height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-03 12:05:58','updated_at' => '2020-02-03 12:06:30'),
  array('product_id' => '48','sku' => '','price' => '4104','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-03 12:12:05','updated_at' => '2020-02-03 12:12:05'),
  array('product_id' => '49','sku' => '','price' => '20','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-03 18:54:49','updated_at' => '2020-02-03 18:55:03'),
  array('product_id' => '50','sku' => '','price' => '20','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-03 19:12:02','updated_at' => '2020-02-03 19:12:02'),
  array('product_id' => '51','sku' => '','price' => '50','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-03 19:17:56','updated_at' => '2020-02-03 19:17:56'),
  array('product_id' => '52','sku' => '','price' => '20','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-04 11:02:23','updated_at' => '2020-02-04 11:02:23'),
  array('product_id' => '53','sku' => '','price' => '20','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-04 11:04:54','updated_at' => '2020-02-04 11:04:54'),
  array('product_id' => '54','sku' => '','price' => '50','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-04 11:26:23','updated_at' => '2020-02-04 11:26:23'),
  array('product_id' => '55','sku' => '','price' => '15','retail_price' => NULL,'discount' => NULL,'length' => NULL,'width' => NULL,'height' => NULL,'weight' => NULL,'currency_code' => 'USD','created_at' => '2020-02-04 11:39:53','updated_at' => '2020-02-04 11:39:53')
  ));


}
}

