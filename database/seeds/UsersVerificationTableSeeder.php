<?php

use Illuminate\Database\Seeder;

class UsersVerificationTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('users_verification')->delete();

DB::table('users_verification')->insert(array(
  array('user_id' => '10001','email' => 'no','facebook' => 'no','google' => 'no','linkedin' => 'no','phone' => 'no','facebook_id' => NULL,'google_id' => NULL,'linkedin_id' => NULL),
  array('user_id' => '10002','email' => 'no','facebook' => 'no','google' => 'no','linkedin' => 'no','phone' => 'no','facebook_id' => NULL,'google_id' => NULL,'linkedin_id' => NULL),
  array('user_id' => '10003','email' => 'no','facebook' => 'no','google' => 'no','linkedin' => 'no','phone' => 'no','facebook_id' => NULL,'google_id' => NULL,'linkedin_id' => NULL),
  array('user_id' => '10004','email' => 'no','facebook' => 'no','google' => 'no','linkedin' => 'no','phone' => 'no','facebook_id' => NULL,'google_id' => NULL,'linkedin_id' => NULL),
  array('user_id' => '10007','email' => 'no','facebook' => 'no','google' => 'no','linkedin' => 'no','phone' => 'no','facebook_id' => NULL,'google_id' => NULL,'linkedin_id' => NULL),
  array('user_id' => '10008','email' => 'no','facebook' => 'no','google' => 'no','linkedin' => 'no','phone' => 'no','facebook_id' => NULL,'google_id' => NULL,'linkedin_id' => NULL)
));
}
}
