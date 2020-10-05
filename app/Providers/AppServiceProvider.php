<?php

namespace App\Providers;

use Config;
use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Validator;
use View;
use App\Models\User;
use App\Models\JoinUs;
use App\Models\SiteSettings;
use App\Models\ApiCredentials;
use App\Models\Currency;
use Auth;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		foreach(glob(app_path() . '/Helpers/*.php') as $file) {
            require_once $file;
        }
        
		\Illuminate\Support\Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
			$page = $page ?: \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);

			return new \Illuminate\Pagination\LengthAwarePaginator($this->forPage($page, $perPage), $total ?: $this->count(), $perPage, $page, [
				'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
				'pageName' => $pageName,
			]);
		});
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		if (env('DB_DATABASE') != '') {
			$this->bindModels();
			$this->shareCommonData();
			if (Schema::hasTable('site_settings')) {
				$site_settings = DB::table('site_settings')->get();
				if(isset($site_settings[0])){
					View::share('site_name', $site_settings[0]->value);
					Config::set(['site_name' => $site_settings[0]->value]);
				}
			}
			if (Schema::hasTable('api_credentials')) {

				$google_result = DB::table('api_credentials')->where('site', 'Google')->get();
				$twitter_result = DB::table('api_credentials')->where('site', 'Twitter')->get();
				$fb_result = DB::table('api_credentials')->where('site', 'Facebook')->get();
				$cloudinary_result = DB::table('api_credentials')->where('site', 'Cloudinary')->get();

				if ( isset($google_result[0])){
					Config::set(['services.google' => [
						'client_id' => $google_result[0]->value,
						'client_secret' => $google_result[1]->value,
						'redirect' => url('/googleAuthenticate'),
					],
					]);
					Config::set(['services.twitter' => [
						'client_id' => $twitter_result[0]->value,
						'client_secret' => $twitter_result[1]->value,
						'include_email' => 'true',
						'include_entities' => 'false', 
						'skip_status' => 'true',
						'redirect' => url('/twitterAuthenticate'),
					],
					]);

					Config::set(['services.facebook' => [
						'client_id' => $fb_result[0]->value,
						'client_secret' => $fb_result[1]->value,
						'redirect' => url('/facebookAuthenticate'),
					],
					]);

					/*Set Cloudinary configuration*/
					Config::set(['cloudder' => [
						'cloudName' => $cloudinary_result[0]->value,
						'apiKey' => $cloudinary_result[1]->value,
						'apiSecret' => $cloudinary_result[2]->value,
						'baseUrl' => $cloudinary_result[3]->value . $cloudinary_result[0]->value,
						'secureUrl' => $cloudinary_result[4]->value . $cloudinary_result[0]->value,
						'apiBaseUrl' => $cloudinary_result[5]->value . $cloudinary_result[0]->value,
					],
					]);
					if(!defined('FB_CLIENT_ID')) {
						define('FB_CLIENT_ID', $fb_result[0]->value);
					}

					// Share Google Credentials
					$google_client = api_credentials('client_id','Google');
					if(!defined('GOOGLE_CLIENT_ID')) {
						define('GOOGLE_CLIENT_ID', $google_client);
					}
					
				}
			}
			if (Schema::hasTable('users')) {
				view()->composer('*', function ($view) {
					$user = User::where('id', @Auth::user()->id)->first();
					if(@$user->status == 'Inactive'){
						Auth::guard('web')->logout();
					}
				});
			}

		}
          

		// Custom Validation for File Extension
		Validator::extend('extensionval', function ($attribute, $value, $parameters) {
			$ext = strtolower($value->getClientOriginalExtension());
			if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
				return true;
			} else {
				return false;
			}
		});

		if (env('DB_DATABASE') != '') {
			if (Schema::hasTable('email_settings')) {
				$result = DB::table('email_settings')->get();
				if(isset($result[0])){
					Config::set([
						'mail.driver' => $result[0]->value,
						'mail.host' => $result[1]->value,
						'mail.port' => $result[2]->value,
						'mail.from' => ['address' => $result[3]->value,
							'name' => $result[4]->value],
						'mail.encryption' => $result[5]->value,
						'mail.username' => $result[6]->value,
						'mail.password' => $result[7]->value,

					]);

					if ($result[0]->value == 'mailgun') {

						Config::set([
							'services.mailgun.domain' => $result[8]->value,
							'services.mailgun.secret' => $result[9]->value,
						]);
					}
				}
			}
		}
	}

	protected function bindModels()
	{
		if (Schema::hasTable('site_settings')) {
            $this->app->singleton('site_settings', function ($app) {
                $site_settings = SiteSettings::get();
                return $site_settings;
            });
        }

        if (Schema::hasTable('api_credentials')) {
            $this->app->singleton('api_credentials', function ($app) {
                $api_credentials = ApiCredentials::get();
                return $api_credentials;
            });
        }

        if (Schema::hasTable('currency')) {
            $this->app->singleton('currency', function ($app) {
                $currency = Currency::get();
                return $currency;
            });
        }

        if (Schema::hasTable('join_us')) {
            $this->app->singleton('join_us', function ($app) {
                $join_us = JoinUs::get();
                return $join_us;
            });
        }
	}

	protected function shareCommonData()
	{
		View::share('no_store_url', url('image/cover_image.jpg'));
		// View::share('no_product_url', url('image/profile.png'));
		View::share('no_product_url', url('image/new-navigation.png'));
	}
}
