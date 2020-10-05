<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DateTime;
use DateTimeZone;
use Config;
use JWTAuth;
use Session;


class Messages extends Model
{
    protected $table = 'messages';

    public $fillable = ['user_from','user_to','messages','read','group_id']; 

    protected $appends = ['created_date','unread_count','all_count','created_time'];

    // Get All Messages
    public static function all_messages($user_id)
    {
        return Messages::where('user_to', $user_id)->groupby('user_from','user_to')->orderBy('id','desc')->get();
    }

    // Get All Message Count
    public  function getAllCountAttribute()
    {
        return Messages::where('user_to', $this->attributes['user_to'])->get()->count();
    }


    // Get Unread Message Count
    public  function getUnreadCountAttribute()
    {
        return Messages::where('user_to', $this->attributes['user_to'])->where('read', '0')->get()->count();
    }


    // Join to User table
    public function user_from()
    {
        return $this->belongsTo('App\Models\User','user_from','id');
    }
    // Join to User table
    public function user_to()
    {
        return $this->belongsTo('App\Models\User','user_to','id');
    }
    
    // Get Created at Time for Message
    public function getCreatedDateAttribute()
    {      
        return date('M d',strtotime($this->attributes['created_at']));
    }

        // Get Created at Time for Message
    public function getCreatedTimeAttribute()
    {      
          //Check user login from mobile or web.Access from payment,message controller from API
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
