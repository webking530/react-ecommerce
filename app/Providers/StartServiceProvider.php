<?php

/**
 * StartService Provider
 *
 * @package     Spiffy
 * @subpackage  Provider
 * @category    Service
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Config;
use Schema;
use Auth;
use App;
use Session;
use App\Models\SiteSettings;
use App\Models\Currency;
use App\Models\Language;
use App\Models\PaymentGateway;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\Category;
use App\Models\User;
use App\Models\Pages;
use App\Models\Admin;

class StartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    	if(env('DB_DATABASE') != '') {

            if(Schema::hasTable('currency')) {
                $this->currency();
            }

            if(Schema::hasTable('language')) {
                $this->language();
            }

            if(Schema::hasTable('site_settings')) {
                $this->site_settings();
            }
           
            if(Schema::hasTable('pages')) {
                $this->pages();
            }

            if(Schema::hasTable('join_us')) {
                $this->joinUs();
            }

            if(Schema::hasTable('categories')) {
                $this->header_categories();
            }

        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
       
    }
	
    public function header_categories()
    {
        $header_categories = Category::where("parent_id",0)->where('status','Active')->get();
        View::share('header_categories',$header_categories);
    }

    // Share Currency Details to whole software
    public function currency()
    {
        // Currency code lists for footer
        $currency = Currency::where('status', '=', 'Active')->get();
        View::share('currency', $currency);

        //Payout currency list for payout prefrence
        $payout_currency = Currency::where('status', '=', 'Active')->pluck('code', 'code');
        View::share('payout_currency', $payout_currency);
        
        // IP based user details
        $ip = getenv("REMOTE_ADDR");

        $valid =  preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $ip);

        if($valid)
        {

            $result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
            
            // Default Currency code for footer
            if($result['geoplugin_currencyCode']) {
                $default_currency = Currency::where('status', '=', 'Active')->where('code', '=', $result['geoplugin_currencyCode'])->first();
                if(!@$default_currency)
                    $default_currency = Currency::where('status', '=', 'Active')->where('default_currency', '=', '1')->first();
            }
            else
            {
                $default_currency = Currency::where('status', '=', 'Active')->where('default_currency', '=', '1')->first();
            }


            $default_code = $result['geoplugin_countryCode'];
            $default_country = $result['geoplugin_countryName'];

        }
        else
        {

            $default_currency = Currency::where('status', '=', 'Active')->where('default_currency', '=', '1')->first();
            if($default_currency){
                $default_country = Country::where('status','Active')->first()->long_name;
                $default_code = Country::where('status','Active')->first()->short_name;
            }

        }


        
        if(!@$default_currency)
            $default_currency = Currency::where('status', '=', 'Active')->first();
        if($default_currency){
        Session::put('currency', $default_currency->code);
        $symbol = Currency::original_symbol($default_currency->code);
        Session::put('symbol', $symbol);

      
        define('default_currency', $default_currency);      
        define('default_country', $default_country);
        define('default_code', $default_code);
        define('countryCode', $default_code);
        define('countryName',$default_country);


        define('default_currency_code', $default_currency->code); 
        //echo $default_country;exit;
        View::share('default_currency', $default_currency);      
        View::share('default_country', $default_country);
        View::share('default_code', $default_code);
        View::share('countryCode', $default_code);
        View::share('countryName',$default_country);
        View::share('default_currency_code',$default_currency->code);

         $default_currency = Currency::where('status', '=', 'Active')->where('default_currency', '=', '1')->first();

        define('api_currency_code', $default_currency->code); 
	}




    }

    // Share Language Details to whole software
    public function language()
    {
        // Language lists for footer
        $language = Language::where('status', '=', 'Active')->get();
        View::share('language', $language);
        
        // Default Language for footer
        $default_language = Language::where('status', '=', 'Active')->where('default_language', '=', '1')->limit(1)->get();
        if(isset($default_language[0])){
            View::share('default_language', $default_language);
            view::share('default_language_name', $default_language[0]->name);
            if($default_language->count() > 0) {            
                Session::put('language', $default_language[0]->value);
                Session::put('language_name', $default_language[0]->name);
                App::setLocale($default_language[0]->value);
            }
        }
    }

    // Share Static Pages data to whole software
    public function pages()
    {
        // Pages lists for footer
        $company_pages = Pages::select('id','url', 'name')->where('status', '=', 'Active')->get();
       
        View::share('company_pages', $company_pages);
    }

    public function get_image_url($src)
    {
        $photo_src=explode('.',$src);

        if(count($photo_src)>1) {
            return asset('image/logos/'.$src.'?v='.str_random(4));
        }

        $options['secure']=TRUE;
        if(!isset($data)) {

        }
        else if($data=='bannerimage') {
            $options['width']=640;
            $options['height']=166;
        }
        else if($data=='sidebarimage') {
            $options['width']=286;
            $options['height']=164;
        }
        else if($data=='mobile_logo') {
            $options['width']=150;
            $options['height']=31;
        }

        $options['quality']=20;
        $options['crop']='scale';
        $options['fetch_format']='auto';
        Config::set('cloudder.scaling', array());
        return $src=\Cloudder::show($src,$options);
    }

    public function get_favicon_url($src)
    {
        $photo_src=explode('.',$src);

        if(count($photo_src)>1) {
            return asset('image/logos/'.$src.'?v='.str_random(4));
        }

        $options['secure']=TRUE;
        $options['height']=16;
        $options['width']=16;
        Config::set('cloudder.scaling', array());
        return $src=\Cloudder::show($src,$options);
    }

    public function get_video_url($src)
    {
        $photo_src = explode('.',$src);

        if(count($photo_src)>1) {
            return asset('uploads/video/'.$src.'?v='.str_random(4));
        }
        $options['secure']=TRUE;
        $options['resource_type']="video";
        Config::set('cloudder.scaling', array());
        return $src=\Cloudder::show($src,$options);
    }

    // Share Site Settings data to whole software
    public function site_settings()
    {
        $site_settings = SiteSettings::all();
                
        View::share('site_settings', $site_settings);

        if(env('DB_DATABASE') != '') {
            if(Schema::hasTable('admin')) {
                if(isset($site_settings[0])){
                    $admin_email = Admin::first()->email;
                    View::share('admin_email', $admin_email);
                }
            }
        }

        $cod_payment_gateway=PaymentGateway::where('site','PaymentMethod')->where('name','cod_enabled')->first();
        $cos_payment_gateway=PaymentGateway::where('site','PaymentMethod')->where('name','cos_enabled');
        if($cod_payment_gateway) {
            define('COD_STATUS', $cod_payment_gateway->value);
            View::share('cod_status', $cod_payment_gateway->value);
        }

        if($cos_payment_gateway->count()) {
            define('COS_STATUS',@$cos_payment_gateway->first()->value);
            View::share('cos_status', @$cos_payment_gateway->first()->value);
        }
        if(isset($site_settings[0])){        
        define('SITE_NAME', $site_settings[0]->value);
        define('LOGO_URL',  $this->get_image_url($site_settings[3]->value));
        define('EMAIL_LOGO_URL',  $this->get_image_url($site_settings[4]->value));
        define('SITE_URL', $site_settings[2]->value);
        define('ADMIN_URL', $site_settings[6]->value);
        define('UPLOAD_DRIVER', $site_settings[7]->value);

        Config::set('cloudder.scaling', array());
        View::share('site_name', $site_settings[0]->value);
        View::share('site_version', $site_settings[1]->value);
        View::share('site_version', str_random(4)); // load js and css without cache
        View::share('site_url', $site_settings[2]->value);       
        View::share('logo',  $this->get_image_url($site_settings[3]->value)); 
        View::share('mobile_logo',  $this->get_image_url($site_settings[3]->value,'mobile_logo'));        
        View::share('email_logo',  $this->get_image_url($site_settings[4]->value));
        View::share('favicon',  $this->get_favicon_url($site_settings[5]->value));
        View::share('head_code', $site_settings[8]->value);

       if($site_settings[2]->value == '' && @$_SERVER['HTTP_HOST']){
            $url = "http://".$_SERVER['HTTP_HOST'];
            $url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

            SiteSettings::where('name','site_url')->update(['value' =>  $url]);
        }

        $user_agent='Other';
        if(isset($_SERVER['HTTP_USER_AGENT'])) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($agent, 'Opera') || strpos($user_agent, 'OPR/')) $user_agent= 'Opera';
            elseif (strpos($agent, 'Edge')) $user_agent= 'Edge';
            elseif (strpos($agent, 'Chrome')) $user_agent= 'Chrome';
            elseif (strpos($agent, 'Safari')) $user_agent= 'Safari';
            elseif (strpos($agent, 'Firefox')) $user_agent= 'Firefox';
            elseif (strpos($agent, 'MSIE') || strpos($user_agent, 'Trident/7')) $user_agent= 'Internet Explorer';
        }
        
        define('USER_AGENT',$user_agent);        
	}
    }
    protected function joinUs()
    {
        $join_us = resolve('join_us');
        View::share('join_us',$join_us->where("value","!=",""));
    }

}