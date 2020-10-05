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
use App\Models\Activity;
use App\Models\ProductLikes;
use App\Models\ProductClick;
use App\Models\ProfilePicture;
use App\Models\ShippingAddress;
use App\Http\Helper\PaymentHelper;
use App\Models\BillingAddress;
use App\Models\UserAddress;
use App\Models\MerchantStore;
use App\Models\Cart;
use App\Models\Payouts;
use App\Models\Follow;
use App\Models\FollowStore;
use App\Models\Wishlists;
use App\Models\Pages;
use App\Models\Messages;
use App\Models\Currency;
use App\Models\Language;
use Validator;
use App\Models\Orders;
use App\Models\OrdersCancel;
use App\Models\OrdersReturn;
use App\Models\OrdersDetails;
use App\Models\Slider;
use Hash;
use App\Http\Start\Helpers;
use Session;
use DB;
use App;
use Carbon\Carbon;
use App\Http\Controllers\EmailController;
use App\Models\Feature;

class HomeController extends BaseController
{

    protected $helper; // Global variable for Helpers instance

    public function __construct(PaymentHelper $payment)
    {   
        $this->helper = new Helpers;
        $this->payment_helper = $payment;
    }

    public function index(Request $request)
    {   
        if($request->uname !='') {
            $data['user'] =User::where('user_name',$request->uname)->where('status','Active')->firstOrFail();
         
            $already = Follow::where('follower_id',Auth::user()->id)->where('user_id',$data['user']->id)->first();

            $data['follower_count'] = Follow::with(['follower_user' => function ($query) {
                $query->where('users.status','Active');
            },'following_users' => function($query){
                $query->where('users.status','Active');
            }])
            ->whereHas('follower_user',function($query){
                $query->where('users.status','Active');
            })
            ->whereHas('following_users',function($query) {
                 $query->where('users.status','Active');
             })
            ->where('follower_id',$data['user']->id)
            ->count();

            $data['following_count'] = Follow::with(['follower_user' => function ($query){$query->where('users.status','Active'); },'following_users' => function($query) {
                $query->where('users.status','Active');
            }])
            ->whereHas('follower_user',function($query) {
                $query->where('users.status','Active');
            })
            ->whereHas('following_users',function($query) {
                $query->where('users.status','Active');
            })
            ->where('user_id',$data['user']->id)
            ->count();

            if(isset($already)){
                $data['follow']=trans('messages.home.following');
            }
            else{
                $data['follow']=trans('messages.home.follow');
            }
        }
        $feature= Feature::orderBy('order', 'asc')->get();
        foreach($feature as $value) {
            $data[$value->title]=$value->image_url;
            $data['desc'.$value->title]=$value->description;
        }  
        $data['feature']=$feature;
        $data['categories'] = Category::where("parent_id",0)->where('status','Active')->get();
        $users_where['users.status']    = 'Active';
        
        if($request->uname && isset($data['user'])) {
            $data['page'] = 'view_profile';
            $data['like_count'] =ProductLikes::with([
                'products' =>function($query) use($users_where){
                    $query->with([
                        'products_prices_details' => function($query){
                            $query->with('currency');
                        },
                        'products_shipping' => function($query){},
                        'product_photos'  =>function($query){},
                        'product_option'  =>function($query){},
                        'wishlist' => function($query){ $query->where('user_id',Auth::id());},
                         'users' => function($query) use($users_where) {$query->where($users_where); },
                        ])->where('products.status','Active');
                },
                'users'  => function($query) use($users_where){
                    $query->where($users_where);
                },
            ])->whereHas('products', function($query) use($users_where) { $query->where('products.status','Active')->where('products.admin_status','Approved')->where('products.total_quantity','<>','0')->where('products.sold_out','No')->whereHas('users', function($query1) use($users_where) { $query1->where($users_where);});})->where('user_id',$data['user']['id'])->orderBy('id','desc')->get();
        }
        elseif($request->uname && isset($data['user']) && $request->view_detail)
        {
            $data['page'] = 'view_profile';            
         if($request->view_detail == 'added'){
               $data['like_count'] = Product::with([
            'products_images','products_prices_details'])->activeUser()->where('user_id',$data['user']['id'])->get(); 
            }
        }
        else
        {
            $data['page']="home";            
        }  
        if($request->filter != '')
            $data['keyword'] = $request->filter;
        else
        $data['keyword'] = '';
        $data['default_min_price'] = 0;
        $data['default_max_price'] = $this->payment_helper->currency_convert('USD', Session::get('currency') ,11000);
        if($request->price != '')
        {
            $price = explode('-', $request->price);
            $data['min_value'] = @$price[0];
            $data['max_value'] = @$price[1] ? $price[1] : $this->payment_helper->currency_convert('USD', Session::get('currency') ,1000);
        } 
        else
        {
            $data['min_value'] = 0;
            $data['max_value'] = $this->payment_helper->currency_convert('USD', Session::get('currency') ,1000);
        }
        if($request->price == '')
        {
            $data['min_value'] = $data['default_min_price'];
            $data['max_value'] = $data['default_max_price'];
        }elseif(Session::get('previous_currency')){
            $data['min_value'] = $this->payment_helper->currency_convert(Session::get('previous_currency'), Session::get('currency'), $data['min_value']); 
            $data['max_value'] = $this->payment_helper->currency_convert(Session::get('previous_currency'), Session::get('currency'), $data['max_value']); 
        } else {
            $data['min_value'] = $this->payment_helper->currency_convert('', Session::get('currency'), $data['min_value']);
            $data['max_value'] = $this->payment_helper->currency_convert('', Session::get('currency'), $data['max_value']);
        }
        $users_where['users.status']    = 'Active';
        $tproducts = MerchantStore::select('id','store_name','logo_img','user_id','header_img')->with([
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

        $sproduct = MerchantStore::select('id','store_name','logo_img','user_id','header_img')->with([
            'get_products' => function($query) {
                $query->addSelect('id','user_id','return_policy','category_id','title')
                ->with(['products_images'=>function($query) {
                    $query->addSelect('image_name','product_id');
                }]);
            },
            'users' => function($query) {
                $query->activeOnly();
            },
            'follow_store'=>function($query) {
                $query->addSelect('follower_id','id','store_id')->where('follower_id',Auth::id());
            }
        ])
        ->whereHas('users', function($query) {
            $query->activeOnly();
        })
        ->get()->map(function($query)  {
           return $query->setRelation('get_products', $query->get_products->take(4));
        }); 
        $data['topbanner'] = $tproducts;
        $data['bottombanner'] = $sproduct;
        Session::forget('previous_currency');
        return view('products.main', $data);    
    }

   
    public function follow(Request $request)
    {
        $already = Follow::where('follower_id',$request->follower_id)->where('user_id',$request->user_id)->first();
        if(isset($already)) {
            Follow::find($already->id)->delete();
            $data['fol'] = trans('messages.home.follow');
            $data['following_status'] = "0";
        }
        else{
            $follow = new Follow;
            $follow->follower_id =$request->follower_id;
            $follow->user_id =$request->user_id;
            $follow->save();
            $data['fol'] =trans('messages.home.following');
            $data['following_status'] = "1";
            //store notification data in notification table
            $notification_data = new Notifications;
            $notification_data->follower_id =  $request->follower_id;
            $notification_data->user_id = $request->follower_id;
            $notification_data->notify_id = $request->user_id;
            $notification_data->notification_type  = "user_follow";
            $notification_data->notification_message  = "following you";
            $notification_data->save();
            $check_activity = Activity::where('source_id',Auth::id())->where('target_id',$request->user_id)->where('source_type','user')->where('activity_type','following_user')->get();
            if($check_activity->count() == 0){
                //store activity data in Activity table         
                $activity_data                  = new Activity;
                $activity_data->source_id       = Auth::id();
                $activity_data->source_type     = "user";
                $activity_data->activity_type   = "following_user";
                $activity_data->target_id       = $request->user_id;
                $activity_data->save();
            }
        } 
        $data['follower_count'] = Follow::with(['follower_user' => function ($query){$query->where('users.status','Active');},'following_users' => function($query){$query->where('users.status','Active');}])->whereHas('follower_user',function($query){$query->where('users.status','Active');})->whereHas('following_users',function($query){$query->where('users.status','Active');})->where('follower_id',$request->user_id)->count();
        $data['following_count'] = Follow::with(['follower_user' => function ($query){$query->where('users.status','Active');},'following_users' => function($query){$query->where('users.status','Active');}])->whereHas('follower_user',function($query){$query->where('users.status','Active');})->whereHas('following_users',function($query){$query->where('users.status','Active');})->where('user_id',$request->user_id)->count();                         
        return $data;
    }

    public function wishlist_list(Request $request)
    {

       $already = Wishlists::where('user_id',$request->user_id)->where('product_id',$request->product_id)->first();

        if($already != '') {
            Wishlists::find($already->id)->delete();
            $data['wish'] ="<span>".trans('messages.home.save_wishlist')."<small>".trans('messages.home.save_your_wishlist')."</small></span>";
            $data['wish_type'] = 'Save to Wishlist ';
        }
        else {

            $wishlist = new Wishlists;
            $wishlist->user_id =$request->user_id;
            $wishlist->product_id =$request->product_id;

            $wishlist->save();

            $products=Product::where('id',$request->product_id)->first();
            //store activity data in notification table         
            $activity_data = new Notifications;
            $activity_data->product_id =   $request->product_id;
            $activity_data->user_id = $request->user_id;
            $activity_data->notify_id = $products->user_id;
            $activity_data->notification_type  = "wishlist";
            $activity_data->notification_message  = "added your item";
            $activity_data->save();

            $data['wish'] ="<span>".trans('messages.home.saved_wishlist')."<small>".trans('messages.home.click_unsave')."</small></span>";
            $data['wish_type'] = 'Saved to Wishlist';
        }

        return $data;
    }

    public function home_product(Request $request)
    {
        $products = Product::with([
            'products_prices_details' => function($query){},
            'product_photos' => function($query){},
            'products_shipping' => function($query){},
        ])->where('products.admin_status','Approved')->where('products.status','Active')->get();
        $products = $products->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }
    public function home_product_details(Request $request)
    {   
        $productid = $request->productid;
        $products= Product::with([
            'products_prices_details' => function($query){
                $query->with('currency');
            },
            'products_shipping' => function($query){},
            'product_photos'  =>function($query){},
            'product_option'  =>function($query){},
            'wishlist' => function($query){
                $query->where('user_id',Auth::id());
            },
        ])->where(['products.id'=> $productid, 'products.admin_status'=> 'Approved', 'products.status'=>'Active'])->get();

        //increment user view product count
        if(Auth::id() && $products[0]['user_id'] != Auth::id()){

                $product_click = new ProductClick;

                $product_click->user_id = Auth::id();

                $product_click->product_id = $productid;

                $product_click->created_at = date('Y-m-d H:i:s');

                $product_click->save();

        }
        //end
        $products = $products->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }
    public function signup()
    { 
        $user_data = Session::get('user_data');
        $error_code = Session::get('error_code');

        if($user_data && empty($error_code)){
            Session::put('error_code',1);
        }
        return view('home.signup');
    }

    public function login(Request $request)
    {          
        $data['next'] = @$request->next;
        Session::forget('user_data');
        Session::forget('error_code');
        $request->session()->put('url.intended',url()->previous());
        return view('home.login');
    }   

    public function send_messages($user_from,$user_to,$message="Hai")
    {
        $get_group_message_user_from=Messages::where('user_to',$user_to)->where('user_from',$user_from);
        $get_group_message_user_to=Messages::where('user_to',$user_from)->where('user_from',$user_to);
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
            if(Messages::count())
            {
                $max_group_id=Messages::orderBy('group_id','desc')->first()->group_id;
                $group_id= $max_group_id+1;
            }
            else
            {
                $group_id= "1000";    
            }
        }
        $message_data['user_from']=$user_from;
        $message_data['user_to']=$user_to;
        $message_data['message']=$message;
        $message_data['group_id']=$group_id;
        $message_data['read']='0';
        $message_data['created_at'] = date('Y-m-d H:i:s');
        $message_data['updated_at'] =  date('Y-m-d H:i:s');
        Messages::insert($message_data);
    }

    /**
     * Set session for Currency & Language while choosing header dropdowns
     *
     */
    public function set_session(Request $request)
    {
        if($request->currency) {        
            $currency=Currency::where('status','Active')->where('code',$request->currency);
            if($currency->first())
            {
                Session::put('currency', $request->currency);
                Session::put('previous_currency',$request->previous_currency);
                $symbol = Currency::original_symbol($request->currency);
                Session::put('symbol', $symbol);
            }
        }
        else if($request->language) {
            Session::put('language', $request->language);
            $language_name_first = Language::where('value', $request->language)->first();
            if($language_name_first)
            {
                $language_name=$language_name_first->name;
                session::put('language_name',$language_name);
                App::setLocale($request->language);
            }
        }
    }

    /**
     * View Static Pages
     *
     * @param array $request  Input values
     * @return Static page view file
     */
    
    public function static_pages(Request $request)
    {
        if($request->segment(1) == 'admin') {
            return redirect('admin/dashboard');
        }

        if($request->token!='') {
            Session::put('get_token',$request->token); 
        }

        $pages = Pages::where(['url'=>$request->name, 'status'=>'Active'])->firstOrFail();

        $data['content'] = str_replace(['SITE_NAME', 'SITE_URL'], [SITE_NAME, url('/')], $pages->content);
        $data['title'] = $pages->name;

        return view('home.static_pages', $data);
    }
    
    public function static_populer(Request $request)
    {      
        return view('home.static_populer');
    }

    public function staticCategory(Request $request)
    {   
        $data['page']=$request->page;  
        $category_id= Category::where('categories.title',$request->category)->select('image_name','id',"parent_id")->firstOrFail();
        $parent_id = isset($category_id->id)? $category_id->id : $category_id['id'];
        $data['categories'] = Category::where("parent_id",0)->where('status','Active')->get();
        $data['subcategory']=  $subcat = Category::child_categories($parent_id);
        $parent = Category::get_parent_categories($category_id);
        $title = $parent->pluck('title')->toArray() ; 
        if(empty($title))
        {
            $data['category']=$request->category;
        }
        else
        {
            $data['category']=implode("/", $title).'/'.$request->category;
        }
        $data['category_id']= $category_id->id;
        $data['banner_img']=isset($category_id->image_name)? $category_id->image_name : $category_id['image_name'];
        $price = $this->payment_helper->priceRange($request->min_price,$request->max_price); 
        $data = array_merge($data,$price);
        return view('home.static_category',$data);
    }

    public function get_notification()
    {
        $update_notify['read']="1";
        Notifications::where('notify_id',Auth::id())->update($update_notify);
        $notification_feed = Notifications::with([
                        'orders'=>function($query){},
                        'users'=>function($query){},
                        'notify_id'=>function($query){
                            $query->where('users.status','Active');
                        },
                        'products'=>function($query){
                             $query->where('products.status','Active')->where('products.admin_status','Approved')->where('products.total_quantity','<>','0')->where('products.sold_out','No');
                        }
                        ])->where(function($query){
            $query->whereHas('products', function ($query) {
            $query->where('products.status', 'Active')->where('products.admin_status', 'Approved')->where('products.total_quantity', '<>', '0')->where('products.sold_out', 'No');
            });
        })->whereHas('notify_id',function($query){
                            $query->where('users.status','Active');
                        })->where('notification_type','!=','add_product')->where('notification_type','!=','follow_like')->where('notifications.notify_id',Auth::id())->orderBy('id','desc');        

        $notification = $notification_feed->paginate(20)->toJson();
        $notification = json_decode($notification, true);
        
        echo json_encode($notification);
    }

    public function get_notification_header()
    {
        $update_notify['read']="1";

        Notifications::where('notify_id',Auth::id())->update($update_notify);

        $notification_feed = Notifications::with([
                        'orders'=>function($query){},
                        'users'=>function($query){},
                        'notify_id'=>function($query){
                            $query->where('users.status','Active');
                        },
                        'products'=>function($query){
                             $query->where('products.status','Active')->where('products.admin_status','Approved')->where('products.total_quantity','<>','0')->where('products.sold_out','No');
                        }
                        ])->whereHas('notify_id',function($query){
                            $query->where('users.status','Active');
                        })->where('notification_type','!=','add_product')->where('notification_type','!=','follow_like')->where('notifications.notify_id',Auth::id())->orderBy('id','desc');                      
        $notification = $notification_feed->paginate(20)->toJson();
        $notification = json_decode($notification, true);
        
        echo json_encode($notification);
    }

    public function get_merchant_header()
    {
        $update_notify['read']="1";

        Notifications::where('notify_id',Auth::id())->update($update_notify);

        $notification_feed = Notifications::with([
                        'orders'=>function($query){},
                        'users'=>function($query){},
                        'notify_id'=>function($query){
                            $query->where('users.status','Active');
                        },
                        'products'=>function($query){
                             $query->where('products.status','Active')->where('products.admin_status','Approved')->where('products.total_quantity','<>','0')->where('products.sold_out','No');
                        }
                        ])->whereHas('notify_id',function($query){
                            $query->where('users.status','Active');
                        })->where('notification_type','!=','add_product')->where('notification_type','!=','follow_like')->where('notification_type','!=','user_follow')->where('notifications.notify_id',Auth::id())->orderBy('id','desc');        

        $notification = $notification_feed->paginate(20)->toJson();
        $notification = json_decode($notification, true);
        
        return response()->json($notification);
    }

    /**
     * Ajax Activity Feed
     *
     * @param array No values
     * @return json activity list
     */ 
    public function get_activity_header()
    {
        $user_id =  Auth::id();

        $followstore = FollowStore::where('follower_id',$user_id)->get();
        $followinguser = Follow::where('follower_id',$user_id)->get();

        $following_user_list =[];
        $store_list = [];
        $user_list = [];

        if($followinguser->count() != 0) {
            $following_user_list = $followinguser->pluck('user_id')->toArray();
        }
        
        if($followstore->count() !=0) {
            $store_list = $followstore->pluck('store_id')->toArray();
        }

        $user_list = $following_user_list;

        if(empty($store_list) || empty($user_list)) {
            return response()->json([]);
        }

        $activities = Activity::with([
            'source_store' => function($query) {   
                $query->with([
                    'users' =>function($query) {
                        $query->activeOnly();
                    },
                    'follow_store'=>function($query) {
                        $query->where('follower_id',Auth::id());
                    }
                ])
                ->where('user_id','!=',Auth::id());
            },
            'source_user'  => function($query) {
                $query->activeOnly();
            },
            'target_user'  => function($query) {
                $query->activeOnly();
            },
            'target_store' => function($query) {
                $query->with([
                    'users' =>function($query) {
                        $query->activeOnly();
                    },
                    'products' => function($query) {
                        $query->activeProduct();
                    },
                    'follow_store'=>function($query) {
                        $query->where('follower_id',Auth::id());
                    }
                ]);
            },
            'target_product' => function($query) {
                $query->with([
                    'products_prices_details.currency',
                    'products_images',
                    'product_photos',
                    'products_shipping',
                    'products_like_details' => function($query) {
                        $query->where('product_likes.user_id',Auth::id());
                    },
                    'wishlist' => function($query) {
                        $query->where('user_id',Auth::id());
                    },
                    'users' => function($query)  {
                        $query->activeOnly();
                    },
                ])
                ->activeProduct();
            },
        ])
        ->where(function($query) use($user_list, $store_list) {
            $query->where(function($query) use($user_list) {
                $query->where('source_type', 'user')->whereIn('source_id', $user_list);
            })
            ->orWhere(function($query) use($store_list) {
                $query->where('source_type', 'store')->whereIn('source_id', $store_list);
            });
        })
        ->orderBy('activity.created_at','desc')
        ->get();

        $activities = $activities->filter(function ($item, $key) {
            if ($item->source_type == 'user') {
                $item->whereHas('source_user',function($query){
                    $query->activeOnly()->where('activity.source_type','user');
                });
            }
            
            if($item->source_type == 'store'){
                $item->WhereHas('source_store',function($query){
                    $query->where('activity.source_type','store')->whereHas('users',function($query){
                        $query->activeOnly();
                    });
                });
            }

            if($item->activity_type == 'like_product' || $item->activity_type == 'add_product') {
                $item->whereHas('target_product',function($query){
                    $query->where('activity.activity_type','add_product')->orWhere('activity.activity_type','like_product')->where('products.admin_status','Approved')->where('products.total_quantity','<>','0')->where('products.sold_out','No')->where('products.status','Active')->where('products.id','activity.target_id')->whereHas('users',function($query){
                        $query->activeOnly();
                    });
                });
            }

            if($item->activity_type == 'following_store'){
                $item->whereHas('source_user',function($query){
                    $query->activeOnly();
                })
                ->WhereHas('target_store',function($query){
                    $query->where('activity.activity_type','following_store')->where('merchant_store.id','activity.target_id');
                });
            }

            if($item->activity_type == 'following_user') {
                $item->whereHas('target_user',function($query){
                    $query->activeOnly();
                });
            }

            if($item->activity_type == 'add_product') {
                return !empty($item->source_store) && !empty($item->target_product) && !empty($item->target_product->users);
            }
            else if($item->activity_type == 'like_product'){
                return !empty($item->source_user) && !empty($item->target_product) && !empty($item->target_product->users);
            }
            else if($item->activity_type == 'following_store'){
                return !empty($item->source_user) && !empty($item->target_store) && !empty($item->target_store->users);
            }
            else if($item->activity_type == 'following_user'){
                return !empty($item->source_user) && !empty($item->target_user);
            }
        });


        $activities_feed =  $activities->groupBy('date')->transform(function($item, $k) {
            return $item->groupBy('source_type')->transform(function($item1,$i){
                return $item1->groupBy('activity_type')->transform(function($item2,$j){
                    return $item2->groupBy('source_id');
                });
            });
        });

        $activities_feed = $activities_feed->paginate(20)->toJson();

        $activities_feed = json_decode($activities_feed, true);

        return response()->json($activities_feed);
    }

    public function recently_viewed_things()
    {
        $user_id = Auth::id();
        $users_where['users.status']    = 'Active';
        $recently_viewed_things = ProductClick::with([
                'products' =>function($query) use($users_where){
                    $query->with([
                        'products_prices_details' => function($query){
                            $query->with('currency');
                        },
                        'products_shipping' => function($query){},
                        'product_photos'  =>function($query){},
                        'product_option'  =>function($query){},
                        'wishlist' => function($query){ $query->where('user_id',Auth::id());},
                        'users' => function($query) use($users_where) {$query->where($users_where); },
                        ])->where('products.status','Active');
                },
            ])->whereHas('products', function($query) use($users_where) { $query->where('products.status','Active')->where('products.admin_status','Approved')->where('products.total_quantity','<>','0')->where('products.sold_out','No')->whereHas('users', function($query1) use($users_where) { $query1->where($users_where);});})->select(DB::raw('max(created_at) as created'),'product_id')->where('product_click.user_id', $user_id)->orderBy('product_click.created_at','desc')->groupBy('product_click.product_id');
        $products = $recently_viewed_things->paginate(15)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }

    public function get_products_likes(Request $request)
    {
        $product_id=$request->product_id;
        $products = Product::with([
                'users'=>function($query){}
                ])->where('id',$product_id);
         $product_likes = ProductLikes::with([
                        'users'=>function($query){$query->activeOnly();}
                        ])->whereHas('users',function($query){
                            $query->activeOnly();
                        })
                        ->where('product_id',$product_id)->orderBy('id','desc');
        $product_likes = $product_likes->get()->toArray();
        $products = $products->get()->toArray();
        return array_merge($products,$product_likes);
    }

    public function productLikedUsers(Request $request)
    {        
        $product_likes = ProductLikes::with('users')->where('product_id',$request->product_id)
            ->paginate(10)
            ->toJson();
        $product_likes = json_decode($product_likes, true);
        
        return response()->json($product_likes);
    }

    public function cloud_upload()
    {
        $product_images=DB::table('profile_picture')->get();
        foreach ($product_images as $value) {
            $file_tmp='http://spiffy.trioangle.com/image/users/'.$value->user_id.'/'.$value->src;
            $file_ctmp='http://spiffy.trioangle.com/image/users/'.$value->user_id.'/'.$value->cover_image_src;
            $c=$this->helper->cloud_upload($file_tmp);
            $ce=$this->helper->cloud_upload($file_ctmp);
            $file_name  = $c['message']['public_id'];    
            $file_cname = $ce['message']['public_id'];    
            $data['user_id'] = $value->user_id;
            $data['src'] = $file_name;
            $data['cover_image_src'] = $file_cname;
            $data['photo_source'] = $value->photo_source; 
            $final[]=json_encode($data);
        }
        $e=implode(',',$final);
        dd($e);
    }

    public function banner_image()
    {
        $slider = Slider::whereStatus('Active')->orderBy('order', 'asc')->whereFrontEnd('LoginPage')->get(); 
        $rows['succresult'] = $slider->pluck('image_url');
        return json_encode($rows);
    }

    public function getFeature(Request $request)
    {
        $data['page']='browse'; 
        $data['title']=ucwords($request->page);
        $data['categories'] = Category::where("parent_id",0)->where('status','Active')->get();
        $data['feature'] = Feature::where('title', $request->page)->firstOrFail();
        $price = $this->payment_helper->priceRange($request->min_price,$request->max_price); 
        $data = array_merge($data,$price);
        return view('feeds.featured',$data);
    }

    public  function search(request $request)
    {
        $data['search_key'] = $request->search_key ?? "";
        $data['search_for'] = $request->search_for ?? "things";
        return view('feeds.search',$data);   
    }

    public function activity()
    {
        $data['page']='browse';  
        $data['categories'] = Category::where("parent_id",0)->where('status','Active')->get();
        return view('home.activity',$data);
    }

    public function userPage(Request $request)
    {
        $data['title']='UserProfile';
        $users_where['users.status']    = 'Active';
        $data['user'] = User::where('user_name',$request->uname)->where('status','!=','Inactive')->firstorFail();
        $user = isset($data['user']->id) ? $data['user']->id : $data['user']['id']; 
        $already = Follow::where('follower_id',Auth::id())->where('user_id',$user)->first();
        $data['follower_count'] = Follow::with(['follower_user' => function ($query){$query->where('users.status','Active');},'following_users' => function($query){ $query->where('users.status','Active');}])->whereHas('follower_user',function($query){$query->where('users.status','Active');
            })->whereHas('following_users',function($query){
            $query->where('users.status','Active'); })->where('follower_id',$data['user']->id)->count();
        $data['following_count'] = Follow::with([ 'follower_user' => function ($query){$query->where('users.status','Active'); },'following_users' => function($query){
            $query->where('users.status','Active');} ])->whereHas('follower_user',function($query){$query->where('users.status','Active');})->whereHas('following_users',function($query){$query->where('users.status','Active');})->where('user_id',$data['user']->id)->count();  
        if(isset($already)){
                    $data['follow']=trans('messages.home.following');
                }
                else{
                    $data['follow']=trans('messages.home.follow');
                }
        $data['page'] = 'view_profile';
        $data['like_count'] =ProductLikes::with([
            'products' =>function($query) use($users_where){
                $query->with([
                    'products_prices_details' => function($query){
                        $query->with('currency');
                    },
                    'products_shipping' => function($query){},
                    'product_photos'  =>function($query){},
                    'product_option'  =>function($query){},
                    'wishlist' => function($query){ $query->where('user_id',Auth::id());},
                     'users' => function($query) use($users_where) {$query->where($users_where); },
                    ])->where('products.status','Active');
            },
            'users'  => function($query) use($users_where){
                $query->where($users_where);
            },
        ])->whereHas('products', function($query) use($users_where) { $query->where('products.status','Active')->where('products.admin_status','Approved')->where('products.total_quantity','<>','0')->where('products.sold_out','No')->whereHas('users', function($query1) use($users_where) { $query1->where($users_where);});})->where('user_id',$data['user']['id'])->orderBy('id','desc')->get();
        if($request->view_detail == 'added'){
               $data['like_count'] = Product::with([
            'products_images','products_prices_details'])->activeUser()->where('user_id',$data['user']['id'])->get(); 
            }
        return view('user.view_profile',$data);
    }

    public function wishlistProduct(Request $request)
    {
       $ordered = false;
       $already = Wishlists::where('user_id',$request->user_id)->get();
       if($already->count() > 0)
       {
           $products=Product::join('wishlists','wishlists.product_id','products.id')->where('wishlists.user_id','=',$request->user_id)->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved');;
            if(!$ordered)
            {
                $products=$products->orderBy('products.id','desc');   
            }
            if($request->first_load)
                $products = $products->take(11);
            $products = $products->paginate(10)->toJson();
            $products = json_decode($products, true);
            echo json_encode($products);
       }
    }

    public  function notification()
    {
        return view('home.notification');
    }

    public function clearLog()
    {
        exec('echo "" > ' . storage_path('logs/laravel.log'));
    }

    public function showLog()
    {
        $log_file = "logs/laravel.log";
        if(env('APP_LOG') == 'daily') {
            // $log_file = "logs/laravel".date('Ymd').".log";
        }
        $contents = \File::get(storage_path($log_file));
        echo '<pre>'.$contents.'</pre>';
    }
}
