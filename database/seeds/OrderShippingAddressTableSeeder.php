<?php

use Illuminate\Database\Seeder;

class OrderShippingAddressTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('orders_shipping_address')->delete();

DB::table('orders_shipping_address')->insert(array(
  array('id' => '1','order_id' => '1','name' => 'John','address_line' => '72, city central road, ','address_line2' => '2nd cross street,','address_nick' => 'Central station','city' => 'Bangalore','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '789456123','created_at' => NULL,'updated_at' => NULL),
  array('id' => '2','order_id' => '2','name' => 'Mick','address_line' => '67, hendry street,','address_line2' => '1st cross street,','address_nick' => 'London ','city' => 'London','postal_code' => '123546','state' => 'UK','country' => 'India','phone_number' => '56543213','created_at' => NULL,'updated_at' => NULL),
  array('id' => '3','order_id' => '3','name' => 'Mick','address_line' => '67, hendry street,','address_line2' => '1st cross street,','address_nick' => 'London ','city' => 'London','postal_code' => '123546','state' => 'UK','country' => 'India','phone_number' => '56543213','created_at' => NULL,'updated_at' => NULL),
  array('id' => '4','order_id' => '4','name' => 'Mick','address_line' => '67, hendry street,','address_line2' => '1st cross street,','address_nick' => 'London ','city' => 'London','postal_code' => '123546','state' => 'UK','country' => 'India','phone_number' => '56543213','created_at' => NULL,'updated_at' => NULL),
  array('id' => '5','order_id' => '5','name' => 'Mick','address_line' => '67, hendry street,','address_line2' => '1st cross street,','address_nick' => 'London ','city' => 'London','postal_code' => '123546','state' => 'UK','country' => 'India','phone_number' => '56543213','created_at' => NULL,'updated_at' => NULL),
  array('id' => '6','order_id' => '6','name' => 'John','address_line' => '72, city central road, ','address_line2' => '2nd cross street,','address_nick' => 'Central station','city' => 'Bangalore','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '789456123','created_at' => NULL,'updated_at' => NULL),
  array('id' => '7','order_id' => '7','name' => 'John','address_line' => '72, city central road, ','address_line2' => '2nd cross street,','address_nick' => 'Central station','city' => 'Bangalore','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '789456123','created_at' => NULL,'updated_at' => NULL),
  array('id' => '8','order_id' => '8','name' => 'John','address_line' => '72, city central road, ','address_line2' => '2nd cross street,','address_nick' => 'Central station','city' => 'Bangalore','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '789456123','created_at' => NULL,'updated_at' => NULL),
  array('id' => '9','order_id' => '9','name' => 'John','address_line' => '72, city central road, ','address_line2' => '2nd cross street,','address_nick' => 'Central station','city' => 'Bangalore','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '789456123','created_at' => NULL,'updated_at' => NULL),
  array('id' => '10','order_id' => '10','name' => 'John','address_line' => '72, city central road, ','address_line2' => '2nd cross street,','address_nick' => 'Central station','city' => 'Bangalore','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '789456123','created_at' => NULL,'updated_at' => NULL),
  array('id' => '11','order_id' => '11','name' => 'John','address_line' => '72, city central road, ','address_line2' => '2nd cross street,','address_nick' => 'Central station','city' => 'Bangalore','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '789456123','created_at' => NULL,'updated_at' => NULL)
));
}
}
