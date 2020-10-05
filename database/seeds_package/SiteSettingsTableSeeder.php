<?php

use Illuminate\Database\Seeder;

class SiteSettingsTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {

		DB::table('site_settings')->delete();

		DB::table('site_settings')->insert(array(
          array('id' => '1','name' => 'site_name','value' => 'spiffy'),
          array('id' => '2','name' => 'version','value' => '1.5'),
          array('id' => '3','name' => 'site_url','value' => ''),
          array('id' => '4','name' => 'logo','value' => 'ikj5c1wvzhrciujfriah'),
          array('id' => '5','name' => 'email_logo','value' => 'nhc2vo2334xinigx6fac'),
          array('id' => '6','name' => 'favicon','value' => 'd8pxqs2vbu1jqi9sn2j8'),
          array('id' => '7','name' => 'admin_prefix','value' => 'admin'),
          array('id' => '8','name' => 'upload_driver','value' => 'cloudinary'),
          array('id' => '9','name' => 'head_code','value' => '')
        ));
	}
}
