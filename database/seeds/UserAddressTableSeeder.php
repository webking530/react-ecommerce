<?php

use Illuminate\Database\Seeder;

class UserAddressTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('user_address')->delete();

DB::table('user_address')->insert(array(
  array('id' => '1','user_id' => '10001','address_line' => ' 91 Western Road','address_line2' => 'Brighton','city' => ' England','postal_code' => '343433','state' => 'England','country' => 'US','phone_number' => '34343434344','created_at' => '2017-09-13 20:03:37','updated_at' => '2020-01-31 20:29:45'),
  array('id' => '2','user_id' => '10002','address_line' => ' 14 Tottenham Court Road','address_line2' => 'London','city' => ' England','postal_code' => '434343','state' => 'England','country' => 'GB','phone_number' => '1234567895','created_at' => '2017-09-15 11:13:06','updated_at' => '2020-01-31 20:30:05'),
  array('id' => '3','user_id' => '10003','address_line' => ' 44-46 Morningside Road','address_line2' => 'Edinburgh','city' => 'Scotland','postal_code' => '123456','state' => 'Edinburgh','country' => 'US','phone_number' => '9876543215','created_at' => '2017-09-19 20:24:12','updated_at' => '2020-01-31 20:30:45'),
  array('id' => '4','user_id' => '10004','address_line' => ' 27 Colmore Row','address_line2' => 'Birmingham','city' => 'England','postal_code' => '456123','state' => 'Birmingham','country' => 'UK','phone_number' => '7894651235','created_at' => '2017-09-19 20:58:54','updated_at' => '2017-09-19 20:58:54'),
  array('id' => '5','user_id' => '10007','address_line' => '45, HELENS STREET','address_line2' => '','city' => 'KASHIS','postal_code' => '20724-0000','state' => 'Indiana','country' => 'US','phone_number' => '123456789','created_at' => '2020-02-04 10:53:42','updated_at' => '2020-02-04 10:53:42'),
  array('id' => '6','user_id' => '10008','address_line' => 'Nuevo León street','address_line2' => '','city' => 'Chennai','postal_code' => '67850','state' => 'TN','country' => 'IN','phone_number' => '854587887','created_at' => '2020-02-04 11:21:06','updated_at' => '2020-02-05 10:15:16')
));
}
}
