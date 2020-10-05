<?php

use Illuminate\Database\Seeder;

class FeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fees')->delete();
        DB::table('fees')->insert(['name' => 'service_fee', 'value' => '10']);
        DB::table('fees')->insert(['name' => 'merchant_fee', 'value' => '10']);
    }
}
