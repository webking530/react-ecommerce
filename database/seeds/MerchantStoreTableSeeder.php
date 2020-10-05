<?php

use Illuminate\Database\Seeder;

class MerchantStoreTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('merchant_store')->delete();

DB::table('merchant_store')->insert(array(
		  array('id' => '1','user_id' => '10001','store_name' => 'Odella Design Studio','tagline' => '','description' => '<p><span class="_Tgc">A potential customer has liked your icon enough to tap through, they\'ve checked out your screenshots and are still interested, until they come to your drab App <strong>Store description</strong> when they hit the back button and go to download your competitor\'s app.</span></p>','logo_img' => 'eagvru2q3uehphlxom2e','header_img' => 'mb79l6b2nmdbewvqmtxq','created_at' => '2017-09-13 20:03:37','updated_at' => '2020-01-31 20:29:45','deleted_at' => NULL),
		  array('id' => '2','user_id' => '10002','store_name' => 'Japan Trend Shop','tagline' => '','description' => '<p><span class="_Tgc">A potential customer has liked your icon enough to tap through, they\'ve checked out your screenshots and are still interested, until they come to your drab App <strong>Store description</strong> when they hit the back button and go to download your competitor\'s app.</span></p>','logo_img' => 'prd1ad7ko5tse1ldowmm','header_img' => 'atkphdfvkbx3x47spc12','created_at' => '2017-09-15 11:13:06','updated_at' => '2020-02-03 20:14:39','deleted_at' => NULL),
		  array('id' => '3','user_id' => '10003','store_name' => 'Dea stylish women\'s wear shop','tagline' => '','description' => '<p><span class="_Tgc">Our sleek line of tech products and accessories enable you to live a #LifeMadeEasy.</span></p>','logo_img' => 'spify_banner2_ewjulq','header_img' => 'nr7bsqnwamfvjdxjv6p2','created_at' => '2017-09-15 11:13:06','updated_at' => '2020-01-31 20:36:15','deleted_at' => NULL),
		  array('id' => '4','user_id' => '10007','store_name' => 'Cristina Jewelry shop','tagline' => '','description' => '<p style="margin: 0px; padding: 0px 0px 10px; border: 0px; font-size: 15px; vertical-align: baseline; list-style: none; quotes: none; outline: none; text-size-adjust: none; color: #373d48; font-family: \'Hanken Grotesk\', \'Helvetica Neue\', sans-serif;">We shine a light on the little things that make up your day, so you can see them from a new perspective. It is our aim to bring beauty and originality to the home and office and to help you organize your surroundings with a smile. Our products are expertly designed yet affordable, practical yet humorous.</p>','logo_img' => 'qxkxjfidq91wcv7dt88r','header_img' => 'e9qkre8itumxjmgg8mp3','created_at' => '2020-02-04 10:53:42','updated_at' => '2020-02-04 15:01:25','deleted_at' => NULL),
		  array('id' => '5','user_id' => '10008','store_name' => 'Modern Art store ','tagline' => '','description' => '<p><span style="color: #373d48; font-family: \'Hanken Grotesk\', \'Helvetica Neue\', sans-serif; font-size: 15px;">We create colorful and vibrant ARTS for the young-at-heart, the playful and the unconventional. We aim to challenge your perspective and inspire you to live in the now, not the norm.</span></p>','logo_img' => 'oji6udahs6i1r0r3xsoo','header_img' => 'tj9zi4rfpfxzhe4sqg06','created_at' => '2020-02-04 11:21:06','updated_at' => '2020-02-04 15:04:04','deleted_at' => NULL)
		),
	);
}
}
