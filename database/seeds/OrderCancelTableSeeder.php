<?php

use Illuminate\Database\Seeder;

class OrderCancelTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('order_cancel')->delete();

DB::table('order_cancel')->insert([

['id' => '1','order_id' => '2','cancel_reason' => 'For Test','created_at' => '2017-09-20 12:25:30','updated_at' => '2017-09-20 12:25:30'],
  ['id' => '2','order_id' => '9','cancel_reason' => 'For Test','created_at' => '2017-09-20 12:30:06','updated_at' => '2017-09-20 12:30:06'],


	]);
}
}
