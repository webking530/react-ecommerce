<?php

/**
 * User Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    User
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;
use DB;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $guard = 'users';

    protected $table = 'users';

    // protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name','username','store_name','email', 'password','already_selling','product_categories','dob','gender','location','bio','website','status','type'
    ];
    protected $appends = ['cover_image_name','image_name','cart_count','original_image_name','original_cover_image_name','original_user_name','original_full_name','group_id','store_name','user_follow','user_follower','user_following','user_address'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function user_facebook_authenticate($email, $fb_id)
    {
        $user = User::where(function($query) use($email, $fb_id)
        {
            $query->where('email', $email)->Where('fb_id', $fb_id);
        });
        return $user;
    }

    public static function user_fb_id_authenticate($email, $fb_id)
    {
        $user = User::where(function($query) use($email, $fb_id)
        {
            $query->where('fb_id', $fb_id);
        });
        return $user;
    }

    public function scopeActiveOnly($query)
    {
        return $query->where('status','Active');
    }

    public function scopeisLike($query,$search_key)
    {
        return $query->where('full_name', 'LIKE', '%'.$search_key.'%');
    }

    public function getDobArrayAttribute(){
        $dob_array = explode('-', @$this->attributes['dob']);
        return $dob_array;
    }
     // Join with profile_picture table
    public function profile_picture()
    {
        return $this->belongsTo('App\Models\ProfilePicture','id','user_id');
    }
    // Join with user address table
    public function getUserAddressAttribute()
    {
        return UserAddress::where('user_id',$this->attributes['id'])->first();
    }

    public function getStatusAttribute(){

        $status = @$this->attributes['status'];

        if($status == 'Null'){
            $status = '';
        }
        
        return @$status;

    }

    public function getUserFollowAttribute()
    {
        return Follow::where('user_id',$this->attributes['id'])->where('follower_id',Auth::id())->count();
    }


    //users follower count
    public function getUserFollowerAttribute()
    {
        return Follow::where('user_id',$this->attributes['id'])->count();
    }
    //users following count
    public function getUserFollowingAttribute()
    {
        return Follow::where('follower_id',$this->attributes['id'])->count();
    }



    // Inbox unread message count
    public function inbox_count()
    {
        return Messages::where('user_to', $this->attributes['id'])->where('read', "0")->count();
    }

        // Inbox unread notify count
    public function notify_count()
    {
        return Notifications::where('notify_id', $this->attributes['id'])->where('read', "0")->count();
    }

    // userdetails
    public function original_detail()
    {
        return User::where('id', $this->attributes['id'])->first();
    }

    // store details
    public function store_detail()
    {
        return MerchantStore::where('user_id', $this->attributes['id'])->first();
    }


    //get cart count 
    public function getCartCountAttribute()
    {
        $users_where['users.status']    = 'Active';
        
        return Cart::with([
                'product_details' => function($query) {
                    $query->with([
                        'products_prices_details' => function($query){
                            $query->with('currency');
                        },
                        'products_shipping' => function($query){},
                        'product_photos'  =>function($query){},
                        'product_option'  =>function($query){},
                        'users' => function($query)  {},
                        ]);
                },                 
            ])->where('user_id', $this->attributes['id'])->count();
    }

    // Get cover  image_name URL
    public function getCoverImageNameAttribute()
    {
        $result = ProfilePicture::where('user_id', $this->attributes['id'])->where('cover_image_src','!=','');

        if($result->count() == 0)
            return url("image/cover_image.jpg");
        else
            return url("image/users/".$this->attributes['id']."/".$result->first()->cover_image_src);
    }
    // Get profile image_name URL
    public function getImageNameAttribute()
    {
        $result = ProfilePicture::where('user_id', $this->attributes['id'])->where('src','!=','');

        if($result->count() == 0)
            return url('image/profile.png');
        else
            return url("image/users/".$this->attributes['id']."/".$result->first()->src);
    }

    // Get profile original image_name URL
    public function getOriginalImageNameAttribute()
    {
        $result = ProfilePicture::where('user_id', $this->attributes['id']);

        if($result->count() == 0)
            return url('image/profile.png');
        else
            return $result->first()->src;
    }

        // Get cover  image_name URL
    public function getOriginalCoverImageNameAttribute()
    {
        $result = ProfilePicture::where('user_id', $this->attributes['id']);
        return @$result->first()->cover_image_src;
    }

    // Get store logo image_name URL
    public function store_logo()
    {
        $result = MerchantStore::where('user_id', $this->attributes['id'])->where('logo_img','<>',NULL);

        if($result->count() == 0)
            return url("image")."/store_img.png";
        else
            return $result->first()->logo_img;
    }
    // Get store store_name URL
    public function getStoreNameAttribute()
    {
        $result = MerchantStore::where('user_id', $this->attributes['id']);
        return @$result->first()->store_name;
    }


    // Get profile original image_name URL
    public function getOriginalFullNameAttribute()
    {
        $result = User::where('id', $this->attributes['id']);

        if($result->count() == 0)
            return "Test";
        else
            return $result->first()->full_name;
    }


    // Get profile original image_name URL
    public function getOriginalUserNameAttribute()
    {
         $result = User::where('id', $this->attributes['id']);

        if($result->count() == 0)
            return "Test1234";
        else
            return $result->first()->user_name;
    }

     // Get profile original image_name URL
    public function getGroupIdAttribute()
    {
        $user_from=Auth::id();
        $get_group_message_user_from=Messages::where('user_to', $this->attributes['id'])->where('user_from',$user_from);
        $get_group_message_user_to=Messages::where('user_to',$user_from)->where('user_from',$this->attributes['id']);
        if($get_group_message_user_from->count())
        {
            $group_id=$get_group_message_user_from->first()->group_id;
        }
        elseif($get_group_message_user_to->count())
        {
            $group_id=$get_group_message_user_to->first()->group_id;   
        }
        else
        {
            $group_id="";
        }
        return $group_id;
    }

    public static function clearUserSession($user_id){
        $session_id = Session::getId();

        $sessions = DB::table('sessions')->where('user_id', $user_id)->where('id', '!=', $session_id)->delete();

        $current_session = DB::table('sessions')->where('id', $session_id)->first();
        if($current_session){
            $current_session_data = unserialize(base64_decode($current_session->payload));
            foreach ($current_session_data as $key => $value) {
                if('login_user_' == substr($key, 0, 11)){
                    if(Session::get($key) == $user_id){
                        Session::forget($key);
                        Session::save(); 
                        DB::table('sessions')->where('id', $session_id)->update(array('user_id' => NULL));;
                    }
                }
            }
        }
        return true;
    }

    // Join with products table
    public function products_details()
    {
        return $this->belongsTo('App\Models\Product','target_id','id');
    }

    // Join with products table
    public function products()
    {
        return $this->belongsTo('App\Models\Product','user_id','id');
    }

    // Join with product like table
    public function product_likes()
    {
        // return $this->belongsTo('App\Models\ProductLikes','user_id','id');
        return $this->hasMany('App\Models\ProductLikes','user_id','id');
    }

    // delete for users relationship data (for all table) $this->attributes['id']
    public function Delete_All_User_Relationship()
    {  
        if($this->attributes['id'] !='')
         {
           
            $notifications = Notifications::where('user_id', $this->attributes['id']);
            if(@$notifications->count()){ $notifications->delete();}; 

            $messages = Messages::where('user_to', $this->attributes['id']);
            if(@$messages->count()){ $messages->delete();}; 

            $message = Messages::where('user_from', $this->attributes['id']);
            if(@$message->count()){ $message->delete();}; 

            $followstore = FollowStore::where('follower_id', $this->attributes['id']);
            if(@$followstore->count()){ $followstore->delete();}; 

            $payout = PayoutPreferences::where('user_id', $this->attributes['id']);
            if(@$payout->count()){ $payout->delete();}; 

            $user_shipping = ShippingAddress::where('user_id', $this->attributes['id']);
            if(@$user_shipping->count()){ $user_shipping->delete();}; 

            $user_billing = BillingAddress::where('user_id', $this->attributes['id']);
            if(@$user_billing->count()){ $user_billing->delete();}; 

            $followstores = FollowStore::where('follower_id', $this->attributes['id']);
            if(@$followstores->count()){ $followstores->delete();}; 

            $follow = Follow::where('user_id', $this->attributes['id']);
            if(@$follow->count()){ $follow->delete();};

            $wishlist = Wishlists::where('user_id', $this->attributes['id']);
            if(@$wishlist->count()){ $wishlist->delete();}; 

            $productclick = ProductClick::where('user_id', $this->attributes['id']);
            if(@$productclick->count()){ $productclick->delete();}; 

            $productlikes = ProductLikes::where('user_id', $this->attributes['id']);
            if(@$productlikes->count()){ $productlikes->delete();}; 

            $follower = Follow::where('follower_id', $this->attributes['id']);
            if(@$follower->count()){ $follower->delete();};

            $merchantstore = MerchantStore::where('user_id', $this->attributes['id'])->first();
            
            $followstores = FollowStore::where('store_id', @$merchantstore->id);
            if(@$followstores->count()){ $followstores->delete();}; 

            $storeclick = StoreClick::where('store_id', @$merchantstore->id);
            
            $storeclick_user = StoreClick::where('user_id', $this->attributes['id']);

            if(@$storeclick->count()){ @$storeclick->delete();};
            if(@$storeclick_user->count()){ @$storeclick_user->delete();};

            if(@$merchantstore !=''){ $merchantstore->delete();};

            $useraddress = UserAddress::where('user_id', $this->attributes['id']);
            if(@$useraddress->count()){ $useraddress->delete();};

            $usersverification = UsersVerification::where('user_id', $this->attributes['id']);
            if(@$usersverification->count()){ $usersverification->delete();};

            $cart = Cart::where('user_id', $this->attributes['id']);
            if(@$cart->count()){ $cart->delete();}  

            User::find($this->attributes['id'])->delete();

            return true;
         }       
           
    }  

}
