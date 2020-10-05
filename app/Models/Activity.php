<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DateTime;
use DateTimeZone;
use Config;
use JWTAuth;
use Session;


class Activity extends Model
{
    protected $table = 'activity';

    public $fillable = ['source_id','source_type','activity_type','target_id','read']; 

    protected $appends = ['created_date','created_time','date'];

    //join with Merchant Store Table
    public function source_store(){
        return $this->belongsTo('App\Models\MerchantStore','source_id','id');
    }

    //join with Users Table
    public function source_user(){
        return $this->belongsTo('App\Models\User','source_id','id');
    }

    //join with Merchant Store Table
    public function target_store(){
        return $this->belongsTo('App\Models\MerchantStore','target_id','id');
    }

    //join with Product Table
    public function target_product(){
        return $this->belongsTo('App\Models\Product','target_id','id');
    }

    //join with Product Table
    public function target_user(){
        return $this->belongsTo('App\Models\User','target_id','id');
    }
    
    // Get Created at Time for notifications
    public function getCreatedDateAttribute()
    {      
        return date('M d',strtotime($this->attributes['created_at']));
    }

    // Get Created at Time for notifications
    public function getDateAttribute()
    {      
        return date('Y-m-d',strtotime($this->attributes['created_at']));
    }

    // Get Created at Time for notifications
    public function getCreatedTimeAttribute()
    {      
          //Check user login from mobile or web.Access from payment,notifications controller from API
         if(Session::get('get_token')!='')
        {   
            $user = JWTAuth::toUser(Session::get('get_token'));


            $new_str = new DateTime($this->attributes['created_at'], new DateTimeZone(Config::get('app.timezone')));
            $new_str->setTimeZone(new DateTimeZone($user->timezone));

            if(date('d-m-Y') == date('d-m-Y',strtotime($this->attributes['created_at'])))
                return $new_str->format('h:i A');
            else
                return date('M d',strtotime($this->attributes['created_at']));
        }
        else
        {
            
            $new_str = new DateTime($this->attributes['created_at'], new DateTimeZone(Config::get('app.timezone')));
            if(Auth::id())
            $new_str->setTimeZone(new DateTimeZone(Auth::user()->timezone));

            if(date('d-m-Y') == date('d-m-Y',strtotime($this->attributes['created_at'])))
                return $new_str->format('h:i A');
            else
                return date('M d',strtotime($this->attributes['created_at']));
        }     
        
    }
}
