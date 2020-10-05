<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DateTime;
use DateTimeZone;
use Config;
use JWTAuth;
use Session;


class Notifications extends Model
{
    protected $table = 'notifications';

    public $fillable = ['order_id','order_details_id','user_id','follower_id','product_id','store_id','notification_type_status','notificaton_type_status','notification_message','read']; 

    protected $appends = ['created_date','created_time','trans_message'];

    
    public function orders()
    {
        return $this->belongsTo('App\Models\Orders','order_id','id');
    }
    
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function notify_id()
    {
        return $this->belongsTo('App\Models\User','notify_id','id');
    }
    
    public function merchant_store()
    {
        return $this->belongsTo('App\Models\MerchantStore','store_id','id');
    }
    public function products()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    public function products_images()
    {
        return $this->belongsTo('App\Models\ProductImages','product_id','product_id');
    }
    public function getTransMessageAttribute()
    {        
        if(@$this->attributes['notification_message'] == 'Placed the orders for you products' ){
            return trans('messages.home.placed_orders_products');
        }
        else if(@$this->attributes['notification_message'] == 'Spiffy featured your things'){  
            return  SITE_NAME.' '.trans('messages.home.featured_your_things'); 
        }
        else if(@$this->attributes['notification_message'] == 'processing your order'){
            return trans('messages.home.processing_your_order');
        }
        else if(@$this->attributes['notification_message'] == 'finished the order, Ready for Shipping'){
            return trans('messages.home.finished_the_order');
        }
        else if(@$this->attributes['notification_message'] == 'Cancelled the order'){
            return trans('messages.home.cancelled_the_order');
        }
        else if(@$this->attributes['notification_message'] == 'returned the order'){
            return trans('messages.home.returned_the_order');
        }
        else if(@$this->attributes['notification_message'] == 'accepeted your return order'){
            return trans('messages.home.accepeted_return_order');
        }
        else if(@$this->attributes['notification_message'] == 'following your store'){
            return trans('messages.home.following_your_store');
        }
        else if(@$this->attributes['notification_message'] == ' likes your item'){
            return trans('messages.home.likes_your_item');
        }
        else if(@$this->attributes['notification_message'] == 'following you'){
            return trans('messages.home.following_you');
        }
        else if(@$this->attributes['notification_message'] == 'added your item'){
            return trans('messages.home.added_your_item');
        }
        else if(@$this->attributes['notification_message'] == 'Order Return is rejected'){
            return trans('messages.home.order_return_rejected');
        }
        else if(@$this->attributes['notification_message'] == 'Received Payout amount from admin for order'){
            return trans('messages.home.received_payout_amount');
        }
        else if(@$this->attributes['notification_message'] == 'Received refund amount from admin for order'){
            return trans('messages.home.received_refund_amount');
        }
    }

    // Get Created at Time for notifications
    public function getCreatedDateAttribute()
    {      
        return date('M d',strtotime($this->attributes['created_at']));
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
