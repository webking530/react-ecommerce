<?php

use Illuminate\Database\Seeder;

class FollowStoreTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('follow_store')->delete();

DB::table('follow_store')->insert(array(
	  array('id' => '1','follower_id' => '10003','store_id' => '1','created_at' => '2017-09-20 19:44:27','updated_at' => '2017-09-20 19:44:27'),
	  array('id' => '2','follower_id' => '10003','store_id' => '2','created_at' => '2017-09-20 19:44:31','updated_at' => '2017-09-20 19:44:31'),
	  array('id' => '3','follower_id' => '10004','store_id' => '1','created_at' => '2017-09-20 19:46:29','updated_at' => '2017-09-20 19:46:29'),
	  array('id' => '4','follower_id' => '10004','store_id' => '2','created_at' => '2017-09-20 19:46:31','updated_at' => '2017-09-20 19:46:31'),
	  array('id' => '5','follower_id' => '10003','store_id' => '3','created_at' => '2020-02-03 19:18:26','updated_at' => '2020-02-03 19:18:26'),
	  array('id' => '6','follower_id' => '10008','store_id' => '5','created_at' => '2020-02-04 15:40:23','updated_at' => '2020-02-04 15:40:23')
	));
}
}
