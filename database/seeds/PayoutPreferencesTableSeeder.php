<?php

use Illuminate\Database\Seeder;

class PayoutPreferencesTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('payout_preferences')->delete();

DB::table('payout_preferences')->insert([

 ['id' => '1','user_id' => '10001','address1' => ' 91 Western Road','address2' => 'Brighton','city' => 'England','state' => 'England','postal_code' => '343433','country' => 'GB','payout_method' => 'Paypal','paypal_email' => 'ajithsuperji@gmail.com','currency_code' => 'USD','default' => 'yes','created_at' => NULL,'updated_at' => NULL],
  ['id' => '2','user_id' => '10002','address1' => '  14 Tottenham Court Road	','address2' => 'London','city' => '  England','state' => '  England','postal_code' => '434343','country' => 'GB','payout_method' => 'Paypal','paypal_email' => 'ajithsuperji@gmail.com','currency_code' => 'USD','default' => 'yes','created_at' => NULL,'updated_at' => NULL],


	]);
}
}
