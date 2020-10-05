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
    	$this->call(CurrencyTableSeeder::class);
		$this->call(UserTableSeeder::class);
		$this->call(UsersVerificationTableSeeder::class);
		$this->call(UserAddressTableSeeder::class);
		$this->call(ShippingAddressTableSeeder::class);
		$this->call(BillingAddressTableSeeder::class);
		$this->call(MerchantStoreTableSeeder::class);
		$this->call(ProfilePictureTableSeeder::class);
		$this->call(ReturnPolicyTableSeeder::class);
		$this->call(CategoryTableSeeder::class);
		$this->call(ProductsTableSeeder::class);
		$this->call(ProductsPriceDetailsTableSeeder::class);
		$this->call(ProductImagesTableSeeder::class);
		$this->call(ProductShippingTableSeeder::class);
		$this->call(ProductClickTableSeeder::class);
		$this->call(ProductLikeTableSeeder::class);
		$this->call(CountryTableSeeder::class);
		$this->call(LanguageTableSeeder::class);
		$this->call(FeesTableSeeder::class);
		$this->call(OrderTableSeeder::class);
		$this->call(OrderDetailsTableSeeder::class);
		$this->call(OrderShippingAddressTableSeeder::class);
		$this->call(OrderBillingAddressTableSeeder::class);
		$this->call(OrderReturnTableSeeder::class);
		$this->call(OrderCancelTableSeeder::class);
		$this->call(PayoutsTableSeeder::class);
		$this->call(SiteSettingsTableSeeder::class);
		$this->call(LaravelEntrustSeeder::class);
		$this->call(ApiCredentialsTableSeeder::class);
		$this->call(JoinUsTableSeeder::class);
		$this->call(TimezoneTableSeeder::class);
		$this->call(PaymentGatewayTableSeeder::class);
		$this->call(EmailSettingsTableSeeder::class);
		$this->call(PagesTableSeeder::class);
		$this->call(MetasTableSeeder::class);
		$this->call(NotificationsTableSeeder::class);
		$this->call(FollowStoreTableSeeder::class);
		$this->call(WishlistsTableSeeder::class);
		$this->call(PayoutPreferencesTableSeeder::class);
		$this->call(SliderTableSeeder::class);
		$this->call(FeatureImageTableSeeder::class);
    }
}