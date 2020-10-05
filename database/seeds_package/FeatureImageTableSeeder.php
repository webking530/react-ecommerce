<?php

use Illuminate\Database\Seeder;

class FeatureImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('feature')->delete();

        DB::table('feature')->insert(array(
          array('id' => '1','title' => 'Featured','description' => 'Find Things Distinct From The Featured List','image' => 'hzpdtpmq8v1a8amvekbc','order' => '1'),
          array('id' => '2','title' => 'Recommended','description' => 'Update Your Purchase List With The Product Recommended','image' => 'nxmfncnllv27g060h7jb','order' => '2'),
          array('id' => '3','title' => 'Popular','description' => 'Voguish Products On Top!','image' => 'ka7jjlmkmm9sypfiixaw','order' => '3'),
          array('id' => '4','title' => 'Newest','description' => 'Stacking Up The Most Fresh Product Among The Heap.','image' => 'xt6ylvbe6xmc9uesoumz','order' => '4'),
          array('id' => '5','title' => 'Editor','description' => 'The Unique Editor Picks On The Way!','image' => 'gikejrkepef0bcrnxy8a','order' => '5'),
          array('id' => '6','title' => 'Onsale','description' => 'Find Products On Sale With Better Discounts.','image' => 'yipbxcariybubfeuw6ft','order' => '6')
        ));
    }
}