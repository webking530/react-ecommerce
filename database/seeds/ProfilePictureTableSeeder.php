<?php

use Illuminate\Database\Seeder;

class ProfilePictureTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('profile_picture')->delete();

DB::table('profile_picture')->insert(array(
  array('user_id' => '10001','src' => 'mzkrcbeitfrj4xohxluy','cover_image_src' => 'i4h25lr8hkvu8l1eduiw','photo_source' => 'Local'),
  array('user_id' => '10002','src' => 'vghtiepiejn8lv6subvw','cover_image_src' => 'pfcgqhvnavaqsge8d1gs','photo_source' => 'Local'),
  array('user_id' => '10003','src' => 'ddvcqlusrqj9fppogltc','cover_image_src' => 'uc5pknxtms0xy9hxypci','photo_source' => 'Local'),
  array('user_id' => '10004','src' => 'nhaqtasc1mbh9i3xdim6','cover_image_src' => 'pouxtt1zx7z15bzxomcv','photo_source' => 'Local'),
  array('user_id' => '10005','src' => 'soc1jb8ymaztl7vrjwc0','cover_image_src' => NULL,'photo_source' => 'Local'),
  array('user_id' => '10007','src' => 'yg82x2djtgwj9opa8faa','cover_image_src' => NULL,'photo_source' => 'Local'),
  array('user_id' => '10008','src' => 'mz3jj35beysqvvequdjz','cover_image_src' => 'axhgsmr8rdy2cduaqhdp','photo_source' => 'Local')
));
}
}
