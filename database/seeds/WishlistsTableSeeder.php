<?php

use Illuminate\Database\Seeder;

class WishlistsTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('wishlists')->delete();

DB::table('wishlists')->insert(array(
    array('id' => '1','user_id' => '10003','product_id' => '1','privacy' => '0'),
    array('id' => '2','user_id' => '10003','product_id' => '2','privacy' => '0'),
    array('id' => '3','user_id' => '10003','product_id' => '4','privacy' => '0'),
    array('id' => '4','user_id' => '10003','product_id' => '8','privacy' => '0'),
    array('id' => '5','user_id' => '10003','product_id' => '19','privacy' => '0'),
    array('id' => '6','user_id' => '10003','product_id' => '5','privacy' => '0'),
    array('id' => '7','user_id' => '10004','product_id' => '23','privacy' => '0'),
    array('id' => '8','user_id' => '10004','product_id' => '24','privacy' => '0'),
    array('id' => '9','user_id' => '10004','product_id' => '25','privacy' => '0'),
    array('id' => '10','user_id' => '10004','product_id' => '26','privacy' => '0'),
    array('id' => '12','user_id' => '10004','product_id' => '11','privacy' => '0'),
    array('id' => '13','user_id' => '10004','product_id' => '14','privacy' => '0'),
    array('id' => '14','user_id' => '10004','product_id' => '18','privacy' => '0')
  ));
}
}
