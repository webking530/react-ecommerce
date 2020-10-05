<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Product;
use App\Models\User;
use App\Models\Country;
use App\Models\Category;
use App\Models\ProductImages;
use App\Models\ProductImagesTemp;
use App\Models\ProductPrice;
use App\Models\ProductShipping;
use App\Models\ProductOption;
use App\Models\ProductOptionImages;
use App\Models\Notifications;
use App\Models\ProductLikes;
use App\Models\ProductClick;
use App\Models\ProfilePicture;
use App\Models\ShippingAddress;
use App\Models\BillingAddress;
use App\Models\UserAddress;
use App\Models\MerchantStore;
use App\Models\StoreClick;
use App\Models\Cart;
use App\Models\Payouts;
use App\Models\Activity;
use App\Models\Follow;
use App\Models\FollowStore;
use App\Models\Wishlists;
use App\Models\Pages;
use App\Models\Messages;
use Validator;
use App\Models\Orders;
use App\Models\OrdersCancel;
use App\Models\OrdersReturn;
use App\Models\OrdersDetails;
use Hash;
use App\Http\Start\Helpers;
use Session;
use App\Http\Controllers\EmailController;

class StoreController extends BaseController
{

    protected $helper; // Global variable for Helpers instance

    public function __construct()
    {   
        $this->helper = new Helpers;
    }


    
    public function stores()
    {
        $data['page']='browse';
        $data['categories'] = Category::where("parent_id",0)->where('status','Active')->get();
        $data['no_store_url']=url('image/navigation3.png');
        $data['no_product_url']=url('image/profile.png');
        return view('store.view',$data);
    }

    public function view_store(Request $request)
    {
        $result = MerchantStore::ActiveUser()->findOrFail($request->id);  
        $product_count = Product::where('user_id',$result->user_id)->activeProduct()->count();

        $already_follow = FollowStore::where('store_id',$result->id)->where('follower_id',@Auth::id())->count();
        $following_count = FollowStore::where('store_id',$result->id)->count();
        $follow = ($already_follow > 0) ?trans('messages.home.following_store') : trans('messages.home.follow_store');

        //increment user view store count
        if(Auth::check() && $result->user_id != Auth::id()) {
            $store_click = new StoreClick;
            $store_click->user_id = Auth::id();
            $store_click->store_id = $result->id;
            $store_click->created_at = date('Y-m-d H:i:s');
            $store_click->save();
        }
        return view('shop.view')->with(['result'=>$result,'product_count'=>$product_count,'following_count'=>$following_count,'follow'=> $follow]);
    }

    public function get_store_product(Request $request)
    {
        $products = Product::select('id','user_id','return_policy','category_id','title')
            ->with([
                'products_prices_details.currency',
                'products_images' => function($query) {
                    $query->addSelect('image_name','product_id');
                },
                'wishlist' => function($query) {
                    $query->where('user_id',Auth::id());
                },
            ])
            ->where('user_id',$request->store)
            ->activeProduct();

        $products = $products->paginate(12)->toJson();

        $products = json_decode($products, true);
        return response()->json($products);        
    }
    
    public function get_stores(Request $request)
    {
        $products = MerchantStore::with([
            'products' => function($query) {
                $query->approved()->isActive()->with('products_images','products_prices_details');
            },
        ]);
        $products = $products->whereHas('users', function($query) {
            $query->activeOnly();
        });
        $products = $products->paginate(12)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }
    public function store_products()
    {
        $users_where['users.status']    = 'Active';
        $products = MerchantStore::select('id','store_name','logo_img','user_id','header_img')->with([
            'get_products' => function($query){
                $query->addSelect('id','user_id','return_policy','category_id','title')
                ->with(['products_images'=>function($query){
                    $query->addSelect('image_name','product_id');
                }]);
            },
            'users' => function($query) use($users_where) {
                                $query->where($users_where);
                        },
             'follow_store'=>function($query){ $query->addSelect('follower_id','id','store_id')->where('follower_id',Auth::id());}
        ])->whereHas('users', function($query) use($users_where) {
                                $query->where($users_where)->where('featured','Yes');
        })->get()->map(function($query)
        {
           return $query->setRelation('get_products', $query->get_products->take(4));
 
        });
        
        $products = $products->paginate(6)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }
    public function follow_store(Request $request)
    {
        $already = FollowStore::where('store_id',$request->store_id)->where('follower_id',$request->follower_id)->first();
        if(isset($already))
        {
            FollowStore::find($already->id)->delete();
            $data['fol'] = "<span class='follow_btn'>".trans('messages.home.follow_store')."</span>";
        }
        else
        {
            $follow_store = new FollowStore;
            $follow_store->follower_id =$request->follower_id;
            $follow_store->store_id =$request->store_id;
            $follow_store->save();
            $data['fol'] ="<span class='following_btn'>".trans('messages.home.following_store')."</span>";

            $store=MerchantStore::where('id',$request->store_id)->first();

            //store notification data in notification table         
                $notification_data = new Notifications;
                $notification_data->follower_id =   Auth::id();
                $notification_data->store_id = $request->store_id;
                $notification_data->notify_id = $store->user_id;
                $notification_data->user_id = Auth::id();
                $notification_data->notification_type  = "store_follow";
                $notification_data->notification_message  = "following your store";
                $notification_data->save();

            $check_activity = Activity::where('source_id',Auth::id())->where('target_id',$request->store_id)->where('source_type','store')->where('activity_type','following_store')->get();

            if($check_activity->count() == 0){
                //store activity data in Activity table         
                $activity_data                  = new Activity;
                $activity_data->source_id       = Auth::id();
                $activity_data->source_type     = "user";
                $activity_data->activity_type   = "following_store";
                $activity_data->target_id       = $request->store_id;
                $activity_data->save();
            }

        }

        $data['follower_count'] = FollowStore::where('store_id',$request->store_id)->count();
        return $data;
    }

}
