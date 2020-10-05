<?php

use Illuminate\Database\Seeder;

class BillingAddressTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('billing_address')->delete();

DB::table('billing_address')->insert([

['id' => '1','user_id' => '10004','name' => 'John','address_line' => '72. City central road,','address_line2' => '2nd cross street, ','address_nick' => 'City central road','city' => 'tamilnadu','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '7894561232','is_default' => 'yes','created_at' => '2017-09-20 10:19:39','updated_at' => '2017-09-20 10:19:39'],
  ['id' => '2','user_id' => '10003','name' => 'Mick','address_line' => 'The Business Centre','address_line2' => ' 61 Wellfield Road','address_nick' => '1st Wellfield road','city' => 'Romania','postal_code' => '123456','state' => 'Romania','country' => 'Romania','phone_number' => '7894567889','is_default' => 'yes','created_at' => '2017-09-20 11:34:36','updated_at' => '2017-09-20 11:34:36'],
  ['id' => '3','user_id' => '10001','name' => 'Tony
','address_line' => '91 Western Road','address_line2' => 'Brighton  ','address_nick' => 'England','city' => 'England','postal_code' => '123456','state' => 'England','country' => 'US','phone_number' => 'England','is_default' => 'yes','created_at' => '2017-09-20 11:34:36','updated_at' => '2017-09-20 11:34:36'],
  ['id' => '4','user_id' => '10002','name' => 'Test','address_line' => '  14 Tottenham Court Road	
','address_line2' => 'London','address_nick' => 'England','city' => 'England','postal_code' => '433425','state' => 'England','country' => 'US','phone_number' => '7894561234','is_default' => 'yes','created_at' => '2017-09-20 11:34:36','updated_at' => '2017-09-20 11:34:36'],


	]);
}
}
