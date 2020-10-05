<?php

/**
 * Site Settings Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Site Settings
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use App\Http\Start\Helpers;
use App\Models\Currency;
use App\Models\Language;
use Validator;
use Image;
use Artisan;
use App;
use DB;
use Session;


class SiteSettingsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Site Settings Data
     *
     * @return redirect     to site_settings
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result']   = SiteSettings::get();
            $data['default_upload_driver'] = SiteSettings::where('name','upload_driver')->first()->value;
            $data['currency'] = Currency::where('status','Active')->pluck('code', 'id');
            $data['language'] = Language::where('status','Active')->pluck('name', 'id');
            $data['default_currency'] = Currency::where('default_currency',1)->first()->id;
            $data['default_language'] = Language::where('default_language',1)->first()->id;
            // $data['paypal_currency'] = Currency::where(['status' => 'Active', 'paypal_currency' => 'Yes'])->pluck('code', 'code');
            return view('admin.site_settings', $data);
        }
        else if($request->submit)
        {
            // Site Settings Validation Rules
            $rules = array(
                    'site_name' => 'required',
                    'logo'         => 'image|mimes:jpg,png,jpeg,gif',
                    'email_logo'   => 'image|mimes:jpg,png,jpeg,gif',
                    'favicon'   => 'image|mimes:jpg,png,jpeg,gif',
                    'default_language'  => 'exists:language,id,status,Active'
                    );

            // Site Settings Validation Custom Names
            $attributes = array(
                        'site_name'   => 'Site Name',
                        'logo'        => 'logo Image',
                        'email_logo'  => 'Email logo',
                        'favicon'     => 'favicon logo',
                       
                        );


            $validator = Validator::make($request->all(), $rules, [], $attributes);

            $default_language = $request->default_language;
            
            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput();
            }
            else
            {
                $image          =   $request->file('logo');              
                $email_image     =   $request->file('email_logo');
                $favicon        =   $request->file('favicon');

                
                if($image) 
                {
                    $extension      =   $image->getClientOriginalExtension();
                    $filename       =   'logo' . '.' . $extension;
                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $last_src=SiteSettings::where(['name' => 'logo'])->first()->value;
                        $c=$this->helper->cloud_upload($image,$last_src);
                        if($c['status']!="error")
                        {
                            $filename=$c['message']['public_id'];    
                        }
                        else
                        {
                            flashMessage('danger', $c['message']);
                            return redirect('admin/site_settings');
                        }
                    }
                    else
                    {
                        $success = $image->move('image/logos/',$filename);
                        if(!$success)
                            return back()->withError('Could not upload Image');
                    }

                    SiteSettings::where(['name' => 'logo'])->update(['value' => $filename]);
                }
                                
                if($email_image) 
                {
                    $extension      =   $email_image->getClientOriginalExtension();
                    $filename       =   'email_logo' . '.' . $extension;
                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $last_src=SiteSettings::where(['name' => 'email_logo'])->first()->value;
                        $c=$this->helper->cloud_upload($email_image,$last_src);
                        if($c['status']!="error")
                        {
                            $filename=$c['message']['public_id'];    
                        }
                        else
                        {
                            flashMessage('danger', $c['message']);
                            return redirect('admin/site_settings');
                        }
                    }
                    else
                    {
                        // $resize = Image::make($email_image->getRealPath())->resize('92','18');            
                        $success = $email_image->move('image/logos/',$filename);
                        if(!$success)
                            return back()->withError('Could not upload Image');
                    }

                    SiteSettings::where(['name' => 'email_logo'])->update(['value' => $filename]);
                }

                if($favicon) 
                {
                    $extension      =   $favicon->getClientOriginalExtension();
                    $filename       =   'favicon' . '.' . $extension;
                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $last_src=SiteSettings::where(['name' => 'favicon'])->first()->value;
                        $c=$this->helper->cloud_upload($favicon,$last_src);
                        if($c['status']!="error")
                        {
                            $filename=$c['message']['public_id'];    
                        }
                        else
                        {
                            flashMessage('danger', $c['message']);
                            return redirect('admin/site_settings');
                        }
                    }
                    else
                    {        
                        $success = $favicon->move('image/logos/',$filename);
            
                        if(!$success)
                            return back()->withError('Could not upload favicon');
                    }

                    SiteSettings::where(['name' => 'favicon'])->update(['value' => $filename]);
                }
           
             
                SiteSettings::where(['name' => 'site_name'])->update(['value' => $request->site_name]);
                SiteSettings::where(['name' => 'head_code'])->update(['value' => $request->head_code]);
                SiteSettings::where(['name' => 'version'])->update(['value' => $request->version]);
                SiteSettings::where(['name' => 'upload_driver'])->update(['value' => $request->upload_driver]);

                Currency::where('status','Active')->update(['default_currency'=>'0']);
                Language::where('status','Active')->update(['default_language'=>'0']);

                Currency::where('id', $request->default_currency)->update(['default_currency'=>1]);
                Language::where('id', $request->default_language)->update(['default_language'=>1]);

                $default_currency_code=Currency::where('default_currency',1)->first()->code;
                Session::put('currency', $default_currency_code);
                $symbol = Currency::original_symbol($default_currency_code);
                Session::put('symbol', $symbol);

                flashMessage('success', 'Updated Successfully');
                return redirect('admin/site_settings');
            }
        }
        else
        {
            return redirect('admin/site_settings');
        }
    }
}
