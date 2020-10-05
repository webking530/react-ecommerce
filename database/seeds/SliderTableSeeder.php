<?php

use Illuminate\Database\Seeder;

class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('slider')->delete();
    	
        DB::table('slider')->insert(array(
          array('id' => '1','image' => 'kvde5t72d8kl3zakajdl','order' => '0','status' => 'Active','front_end' => 'Adminpage','created_at' => NULL,'updated_at' => '2020-02-11 07:46:42'),
          array('id' => '2','image' => 'umsnox76rkwo71na1vxo','order' => '1','status' => 'Active','front_end' => 'Adminpage','created_at' => NULL,'updated_at' => '2020-02-11 07:46:32'),
          array('id' => '3','image' => 'axtueujrnlsfo4xzzf1b','order' => '2','status' => 'Active','front_end' => 'Adminpage','created_at' => NULL,'updated_at' => '2020-02-11 07:46:19'),
          array('id' => '4','image' => 'pvskimh35mgm1aso0ev7','order' => '0','status' => 'Active','front_end' => 'LoginPage','created_at' => NULL,'updated_at' => '2020-02-11 07:46:08'),
          array('id' => '5','image' => 'duf5yztwhyapw30ywisy','order' => '1','status' => 'Active','front_end' => 'LoginPage','created_at' => NULL,'updated_at' => '2020-02-11 07:45:57'),
          array('id' => '6','image' => 'hkpq7x8eqw6kl7vcnd2m','order' => '2','status' => 'Active','front_end' => 'LoginPage','created_at' => NULL,'updated_at' => '2020-02-11 07:45:45')
        ));
    }
}
