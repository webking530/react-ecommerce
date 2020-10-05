<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('language')->delete();
    	
        DB::table('language')->insert([
        	    ['name' => 'Deutsch','value' => 'de','default_language' => '0','status' => 'Active'],
                ['name' => 'English','value' => 'en','default_language' => '1','status' => 'Active'],
                ['name' => 'Español','value' => 'es','default_language' => '0','status' => 'Active'],
                ['name' => 'Français','value' => 'fr','default_language' => '0','status' => 'Active'],               
                ['name' => 'Português','value' => 'pt','default_language' => '0','status' => 'Active'],  
        	]);
    }
}
