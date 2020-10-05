	<?php

	use Illuminate\Database\Seeder;

	class ShippingAddressTableSeeder extends Seeder
	{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
	DB::table('shipping_address')->delete();

	DB::table('shipping_address')->insert(array(
		  array('id' => '1','user_id' => '10004','name' => 'John','address_line' => '72, city central road, ','address_line2' => '2nd cross street,','address_nick' => 'Central station','city' => 'Bangalore','postal_code' => '123456','state' => 'karnataka','country' => 'India','phone_number' => '789456123','is_default' => 'yes','created_at' => '2017-09-20 15:43:52','updated_at' => '2017-09-20 15:43:52'),
		  array('id' => '2','user_id' => '10003','name' => 'Mick','address_line' => '67, hendry street,','address_line2' => '1st cross street,','address_nick' => 'London ','city' => 'London','postal_code' => '123546','state' => 'UK','country' => 'India','phone_number' => '56543213','is_default' => 'yes','created_at' => '2017-09-20 16:42:46','updated_at' => '2017-09-20 16:42:46'),
		  array('id' => '3','user_id' => '10001','name' => 'Tony 
		','address_line' => '91 Western Road','address_line2' => 'Brighton ','address_nick' => 'England ','city' => 'England','postal_code' => '123456','state' => 'England','country' => 'US','phone_number' => '1234657988','is_default' => 'yes','created_at' => '2017-09-20 17:04:36','updated_at' => '2017-09-20 17:04:36'),
		  array('id' => '4','user_id' => '10002','name' => 'Test ','address_line' => '  14 Tottenham Court Road	
		','address_line2' => 'London','address_nick' => 'England','city' => 'England','postal_code' => '433433','state' => 'England','country' => 'US','phone_number' => '4567895462','is_default' => 'yes','created_at' => '2017-09-20 17:04:36','updated_at' => '2017-09-20 17:04:36')
		));
	}
	}
