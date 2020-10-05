<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$this->call(CategoryTableSeeder::class);
		$this->call(CountryTableSeeder::class);
		$this->call(LanguageTableSeeder::class);
		$this->call(CurrencyTableSeeder::class);
		$this->call(FeesTableSeeder::class);
		$this->call(SiteSettingsTableSeeder::class);
		$this->call(LaravelEntrustSeeder::class);
		$this->call(ApiCredentialsTableSeeder::class);
		$this->call(JoinUsTableSeeder::class);
		$this->call(TimezoneTableSeeder::class);
		$this->call(PaymentGatewayTableSeeder::class);
		$this->call(EmailSettingsTableSeeder::class);
		$this->call(PagesTableSeeder::class);
		$this->call(MetasTableSeeder::class);
		$this->call(ReturnPolicyTableSeeder::class);
		$this->call(SliderTableSeeder::class);
		$this->call(FeatureImageTableSeeder::class);
    }
}