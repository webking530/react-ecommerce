<?php

use Illuminate\Database\Seeder;

class OrderBillingAddressTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('orders_billing_address')->delete();

DB::table('orders_billing_address')->insert(array(
  array('id' => '1','order_id' => '1','name' => 'John','address_line' => '72. City central road,','address_line2' => '2nd cross street, ','address_nick' => 'City central road','city' => 'tamilnadu','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '7894561232','created_at' => NULL,'updated_at' => NULL),
  array('id' => '2','order_id' => '2','name' => 'Mick','address_line' => 'The Business Centre','address_line2' => ' 61 Wellfield Road','address_nick' => '1st Wellfield road','city' => 'Romania','postal_code' => '123456','state' => 'Romania','country' => 'Romania','phone_number' => '7894567889','created_at' => NULL,'updated_at' => NULL),
  array('id' => '3','order_id' => '3','name' => 'Mick','address_line' => 'The Business Centre','address_line2' => ' 61 Wellfield Road','address_nick' => '1st Wellfield road','city' => 'Romania','postal_code' => '123456','state' => 'Romania','country' => 'Romania','phone_number' => '7894567889','created_at' => NULL,'updated_at' => NULL),
  array('id' => '4','order_id' => '4','name' => 'Mick','address_line' => 'The Business Centre','address_line2' => ' 61 Wellfield Road','address_nick' => '1st Wellfield road','city' => 'Romania','postal_code' => '123456','state' => 'Romania','country' => 'Romania','phone_number' => '7894567889','created_at' => NULL,'updated_at' => NULL),
  array('id' => '5','order_id' => '5','name' => 'Mick','address_line' => 'The Business Centre','address_line2' => ' 61 Wellfield Road','address_nick' => '1st Wellfield road','city' => 'Romania','postal_code' => '123456','state' => 'Romania','country' => 'Romania','phone_number' => '7894567889','created_at' => NULL,'updated_at' => NULL),
  array('id' => '6','order_id' => '6','name' => 'John','address_line' => '72. City central road,','address_line2' => '2nd cross street, ','address_nick' => 'City central road','city' => 'tamilnadu','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '7894561232','created_at' => NULL,'updated_at' => NULL),
  array('id' => '7','order_id' => '7','name' => 'John','address_line' => '72. City central road,','address_line2' => '2nd cross street, ','address_nick' => 'City central road','city' => 'tamilnadu','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '7894561232','created_at' => NULL,'updated_at' => NULL),
  array('id' => '8','order_id' => '8','name' => 'John','address_line' => '72. City central road,','address_line2' => '2nd cross street, ','address_nick' => 'City central road','city' => 'tamilnadu','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '7894561232','created_at' => NULL,'updated_at' => NULL),
  array('id' => '9','order_id' => '9','name' => 'John','address_line' => '72. City central road,','address_line2' => '2nd cross street, ','address_nick' => 'City central road','city' => 'tamilnadu','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '7894561232','created_at' => NULL,'updated_at' => NULL),
  array('id' => '10','order_id' => '10','name' => 'John','address_line' => '72. City central road,','address_line2' => '2nd cross street, ','address_nick' => 'City central road','city' => 'tamilnadu','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '7894561232','created_at' => NULL,'updated_at' => NULL),
  array('id' => '11','order_id' => '11','name' => 'John','address_line' => '72. City central road,','address_line2' => '2nd cross street, ','address_nick' => 'City central road','city' => 'tamilnadu','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '7894561232','created_at' => NULL,'updated_at' => NULL)
));
}
}
