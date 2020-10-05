<?php

use Illuminate\Database\Seeder;

class ProductImagesTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('products_images')->delete();

    DB::table('products_images')->insert(array(
      array('id' => '91','product_id' => '10','image_name' => 'cpxpnylnfdlytbpdullr','created_at' => '2020-01-31 19:11:16','updated_at' => '2020-01-31 19:11:16'),
      array('id' => '92','product_id' => '11','image_name' => 'ww4lifsaq314icbsnfaa','created_at' => '2020-01-31 19:11:33','updated_at' => '2020-01-31 19:11:33'),
      array('id' => '93','product_id' => '12','image_name' => 'ocoieq1qucdfhteq9fgw','created_at' => '2020-01-31 19:11:44','updated_at' => '2020-01-31 19:11:44'),
      array('id' => '94','product_id' => '13','image_name' => 'vvrcfieymdzx7d7fqvzu','created_at' => '2020-01-31 19:12:10','updated_at' => '2020-01-31 19:12:10'),
      array('id' => '95','product_id' => '14','image_name' => 'hcffzwetfudeqvyzvfi9','created_at' => '2020-01-31 19:12:24','updated_at' => '2020-01-31 19:12:24'),
      array('id' => '96','product_id' => '15','image_name' => 'ot20bv74oqkkhrxb46iv','created_at' => '2020-01-31 19:12:35','updated_at' => '2020-01-31 19:12:35'),
      array('id' => '97','product_id' => '16','image_name' => 'uzybylfwc13dmw2aandd','created_at' => '2020-01-31 19:12:47','updated_at' => '2020-01-31 19:12:47'),
      array('id' => '98','product_id' => '17','image_name' => 'ongro8tnnehamst4ohvv','created_at' => '2020-01-31 19:13:02','updated_at' => '2020-01-31 19:13:02'),
      array('id' => '99','product_id' => '18','image_name' => 'frtziyb3dmkwuvfra8pq','created_at' => '2020-01-31 19:13:51','updated_at' => '2020-01-31 19:13:51'),
      array('id' => '100','product_id' => '32','image_name' => 'h5h87horbsuzeqnymm17','created_at' => '2020-01-31 19:13:53','updated_at' => '2020-01-31 19:13:53'),
      array('id' => '101','product_id' => '33','image_name' => 'jrg4ojvsp4mkio37qdb8','created_at' => '2020-01-31 19:14:23','updated_at' => '2020-01-31 19:14:23'),
      array('id' => '102','product_id' => '34','image_name' => 'vdmrd7pqkxyweypsokge','created_at' => '2020-01-31 19:14:25','updated_at' => '2020-01-31 19:14:25'),
      array('id' => '103','product_id' => '35','image_name' => 'wycypqrtlku6zw517tr5','created_at' => '2020-01-31 19:14:36','updated_at' => '2020-01-31 19:14:36'),
      array('id' => '104','product_id' => '36','image_name' => 'ea4mqtetyrwqsk1jkuoi','created_at' => '2020-01-31 19:14:49','updated_at' => '2020-01-31 19:14:49'),
      array('id' => '105','product_id' => '37','image_name' => 'mhzbgyviehftntsncvyj','created_at' => '2020-01-31 19:14:59','updated_at' => '2020-01-31 19:14:59'),
      array('id' => '106','product_id' => '38','image_name' => 'rmyp0bka44jzsyti9w2m','created_at' => '2020-01-31 19:15:30','updated_at' => '2020-01-31 19:15:30'),
      array('id' => '107','product_id' => '39','image_name' => 'iatzx4l2uwbv6xp8fvcs','created_at' => '2020-01-31 19:15:33','updated_at' => '2020-01-31 19:15:33'),
      array('id' => '108','product_id' => '40','image_name' => 'flrveitxffu9edptbzj9','created_at' => '2020-01-31 19:15:35','updated_at' => '2020-01-31 19:15:35'),
      array('id' => '109','product_id' => '1','image_name' => 'azob1qy5xam9bkyhekv3','created_at' => '2020-01-31 19:40:52','updated_at' => '2020-01-31 19:40:52'),
      array('id' => '110','product_id' => '2','image_name' => 'zglr3ycmtrcmengcanys','created_at' => '2020-01-31 19:41:04','updated_at' => '2020-01-31 19:41:04'),
      array('id' => '111','product_id' => '3','image_name' => 'jtw4yr9pahv7jp71cu7i','created_at' => '2020-01-31 19:41:33','updated_at' => '2020-01-31 19:41:33'),
      array('id' => '112','product_id' => '4','image_name' => 'z7qa32xfq6bvqfrtfzyn','created_at' => '2020-01-31 19:42:02','updated_at' => '2020-01-31 19:42:02'),
      array('id' => '114','product_id' => '6','image_name' => 'r8fiucsfqxtjzxxd0slb','created_at' => '2020-01-31 19:42:26','updated_at' => '2020-01-31 19:42:26'),
      array('id' => '115','product_id' => '5','image_name' => 'cu8vaidzntd9r6jvkwvg','created_at' => '2020-01-31 19:43:09','updated_at' => '2020-01-31 19:43:09'),
      array('id' => '116','product_id' => '7','image_name' => 'pjbgqmlnzl3ks8lvfwie','created_at' => '2020-01-31 19:43:40','updated_at' => '2020-01-31 19:43:40'),
      array('id' => '117','product_id' => '8','image_name' => 'nwedwk4ufmboyolpeglo','created_at' => '2020-01-31 19:44:30','updated_at' => '2020-01-31 19:44:30'),
      array('id' => '118','product_id' => '9','image_name' => 'dn0txrsxt3mxmzib52jd','created_at' => '2020-01-31 19:44:33','updated_at' => '2020-01-31 19:44:33'),
      array('id' => '119','product_id' => '19','image_name' => 'valrj0knsgfzynnvgxyc','created_at' => '2020-01-31 19:45:43','updated_at' => '2020-01-31 19:45:43'),
      array('id' => '120','product_id' => '20','image_name' => 'uov7b00zc8jg7uapylvd','created_at' => '2020-01-31 19:45:58','updated_at' => '2020-01-31 19:45:58'),
      array('id' => '121','product_id' => '21','image_name' => 'jwhlnd8nsxggujoy4m4b','created_at' => '2020-01-31 19:46:10','updated_at' => '2020-01-31 19:46:10'),
      array('id' => '122','product_id' => '22','image_name' => 'eccprdji9qlipoiiu7fx','created_at' => '2020-01-31 19:46:22','updated_at' => '2020-01-31 19:46:22'),
      array('id' => '123','product_id' => '23','image_name' => 'hgianihgd5oo9rovdljw','created_at' => '2020-01-31 19:46:37','updated_at' => '2020-01-31 19:46:37'),
      array('id' => '124','product_id' => '24','image_name' => 'ewajqf7kfry4oyfg2pfe','created_at' => '2020-01-31 19:46:49','updated_at' => '2020-01-31 19:46:49'),
      array('id' => '125','product_id' => '25','image_name' => 'kphanfxhbhvys1tysgr3','created_at' => '2020-01-31 19:47:05','updated_at' => '2020-01-31 19:47:05'),
      array('id' => '126','product_id' => '26','image_name' => 'vkinck7ziqdl49lha7hq','created_at' => '2020-01-31 19:47:27','updated_at' => '2020-01-31 19:47:27'),
      array('id' => '127','product_id' => '27','image_name' => 'ymzzrxo15wkdlm6v9rlz','created_at' => '2020-01-31 19:48:29','updated_at' => '2020-01-31 19:48:29'),
      array('id' => '128','product_id' => '28','image_name' => 'evbrrqki7txswdtkkh8e','created_at' => '2020-01-31 19:49:07','updated_at' => '2020-01-31 19:49:07'),
      array('id' => '129','product_id' => '30','image_name' => 'fwzlrvrbjbwsfxsux3vc','created_at' => '2020-01-31 19:49:53','updated_at' => '2020-01-31 19:49:53'),
      array('id' => '130','product_id' => '31','image_name' => 'pmtxnltng2mfmbwiawfi','created_at' => '2020-01-31 19:56:19','updated_at' => '2020-01-31 19:56:19'),
      array('id' => '131','product_id' => '41','image_name' => 'a9s3kdhowmhk6wypdxkq','created_at' => '2020-01-31 19:57:52','updated_at' => '2020-01-31 19:57:52'),
      array('id' => '132','product_id' => '42','image_name' => 'ixscvnskubsqwkqk4lfh','created_at' => '2020-01-31 19:59:50','updated_at' => '2020-01-31 19:59:50'),
      array('id' => '133','product_id' => '43','image_name' => 'khikk5j8hr5izx4ynffm','created_at' => '2020-01-31 20:00:43','updated_at' => '2020-01-31 20:00:43'),
      array('id' => '135','product_id' => '44','image_name' => 'qeozx05ygel6q0xtzpts','created_at' => '2020-01-31 20:08:08','updated_at' => '2020-01-31 20:08:08'),
      array('id' => '136','product_id' => '45','image_name' => 'v0h8kaijdfnklvmjo6fy','created_at' => '2020-01-31 20:08:44','updated_at' => '2020-01-31 20:08:44'),
      array('id' => '137','product_id' => '46','image_name' => 'x7mjkxdpklprvulgw6vm','created_at' => '2020-02-03 11:29:41','updated_at' => '2020-02-03 11:29:41'),
      array('id' => '138','product_id' => '47','image_name' => 'y4jjoh0ce8l3z59abolq','created_at' => '2020-02-03 12:05:58','updated_at' => '2020-02-03 12:05:58'),
      array('id' => '139','product_id' => '47','image_name' => 'mxvwaavyjicyhrlccg2k','created_at' => '2020-02-03 12:05:58','updated_at' => '2020-02-03 12:05:58'),
      array('id' => '140','product_id' => '48','image_name' => 'hgf5vxcmjjvmpfzy1nbw','created_at' => '2020-02-03 12:12:05','updated_at' => '2020-02-03 12:12:05'),
      array('id' => '141','product_id' => '48','image_name' => 'kthpxfxqdpw8twrp7hky','created_at' => '2020-02-03 12:12:05','updated_at' => '2020-02-03 12:12:05'),
      array('id' => '142','product_id' => '48','image_name' => 'neldbgum5mewfhyjs3f1','created_at' => '2020-02-03 12:12:05','updated_at' => '2020-02-03 12:12:05'),
      array('id' => '143','product_id' => '48','image_name' => 'jxw81ph8tivgs9w1176p','created_at' => '2020-02-03 12:12:05','updated_at' => '2020-02-03 12:12:05'),
      array('id' => '144','product_id' => '49','image_name' => 'lhwob7aflu2rigvlzrhr','created_at' => '2020-02-03 18:54:49','updated_at' => '2020-02-03 18:54:49'),
      array('id' => '145','product_id' => '50','image_name' => 'ptzpdqkeiod6wexmkbhm','created_at' => '2020-02-03 19:12:02','updated_at' => '2020-02-03 19:12:02'),
      array('id' => '146','product_id' => '51','image_name' => 'v2upujj0ga4pryn0uusy','created_at' => '2020-02-03 19:17:56','updated_at' => '2020-02-03 19:17:56'),
      array('id' => '147','product_id' => '52','image_name' => 'ddbt91zxzk9pnrd9kezx','created_at' => '2020-02-04 11:02:23','updated_at' => '2020-02-04 11:02:23'),
      array('id' => '148','product_id' => '53','image_name' => 'anc7l3bdzpw3j4hh36h2','created_at' => '2020-02-04 11:04:54','updated_at' => '2020-02-04 11:04:54'),
      array('id' => '149','product_id' => '54','image_name' => 'ln3wgjkwcrq4y5cvtmrz','created_at' => '2020-02-04 11:26:23','updated_at' => '2020-02-04 11:26:23'),
      array('id' => '150','product_id' => '55','image_name' => 'xuy230itom028uqht60r','created_at' => '2020-02-04 11:39:53','updated_at' => '2020-02-04 11:39:53')
  ));
}
}
