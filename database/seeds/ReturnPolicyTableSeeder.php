<?php
 
use Illuminate\Database\Seeder;

class ReturnPolicyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('return_policy')->delete();

        DB::table('return_policy')->insert(array(
          array('id' => '1','days' => '0','name' => 'No return','created_at' => NULL,'updated_at' => NULL),
          array('id' => '2','days' => '15','name' => '15 days return','created_at' => NULL,'updated_at' => NULL),
          array('id' => '3','days' => '30','name' => '30 days return','created_at' => NULL,'updated_at' => NULL)
        ));    
    }
}
