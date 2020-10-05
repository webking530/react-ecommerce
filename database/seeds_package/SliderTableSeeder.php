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
    	
        DB::table('slider')->insert([
            ["image"=>"signup-back.png","order"=>0,"status"=>"Active","front_end"=>"Adminpage"],
            ["image"=>"signup-back2.png","order"=>1,"status"=>"Active","front_end"=>"Adminpage"],
            ["image"=>"signup-back3.png","order"=>2,"status"=>"Active","front_end"=>"Adminpage"],
            ["image"=>"signup-back.png","order"=>0,"status"=>"Active","front_end"=>"LoginPage"],
            ["image"=>"signup-back2.png","order"=>1,"status"=>"Active","front_end"=>"LoginPage"],
            ["image"=>"signup-back3.png","order"=>2,"status"=>"Active","front_end"=>"LoginPage"]
        ]);
    }
}
