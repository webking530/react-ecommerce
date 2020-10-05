<?php

/**
 * Product Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Product
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Start\Helpers;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Country;
use Auth;
use Validator;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Timezone;
use App\Models\Follow;
use App\Models\ProductImages;
use App\Models\Notifications;
use App\Models\ProductLikes;
use App\Models\ProductImagesTemp;
use App\Models\ProductPrice;
use App\Models\ProductClick;
use App\Models\Activity;
use App\Models\ProductShipping;
use App\Models\Wishlists;
use App\Models\ProductOption;
use App\Models\ProductOptionImages;
use App\Models\MerchantStore;
use App\Models\StoreClick;
use App\Models\FollowStore;
use App\Http\Helper\PaymentHelper;
use DB;
use Config;
use Session;
use Socialite; 

class ProductController extends BaseController
{
    public function __construct(PaymentHelper $payment)
    {   
        $this->helper = new Helpers;
        $this->array_category = array();
        $this->payment_helper = $payment;
        $this->paginate_count = 10;
    }
    

    /**
    * New Products
    *
    * @param array $request    Post values from List Your Space first page
    * @return products main view file
    */    
    public function view_products(Request $request,$category = null)
    {
        $data['result']         = Product::all();
        $data['categories']         = Category::where("parent_id",0)->get();
        $data['page'] = $request->page;
        
        return view('products.main', $data);
    }

    public function view_onsale(Request $request)
    {
        $data['result'] = Product::all();
        $data['categories']= Category::where("parent_id",0)->get();
        $data['page'] = $request->page;
        return view('products.onsale', $data);
    }
    public function getCategorychild($childs)
    {
        foreach($childs as $child)
        {
            array_push($this->array_category,$child->id);
            if($child->childs->count())
            {
                $this->getCategorychild($child->childs);
            }
        }
    }

    //get all product details
    public function get_products(Request $request)
    {
        $ordered = false;
        $users_where['users.status'] = 'Active';
        $products = Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')->with([
            'products_prices_details' => function($query){
                $query->with('currency');
            },
            'products_images','products_shipping',
            'wishlist' => function($query){
                $query->where('user_id',Auth::id());
            },
            'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },
        ])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved');

        if($request->filled('category') && $request->category != "all") {
            $category = Category::where("title",$request->category);

            if($category->count()) {
                $categories1 = Category::where("id",$category->first()->id)->where('status', 'Active')->get();
                foreach($categories1 as $category) {
                    array_push($this->array_category,$category->id);
                    if($category->childs->count()) {
                        $this->getCategorychild($category->childs);
                    }
                }
            }
            $products = $products->wherein('products.category_id',array_unique($this->array_category));
        }

        if($request->apply_price_filter || $request->price_range != "0") {
            $price_range = explode("-",$request->price_range);
            if(count($price_range) == 2) {
                $min= $price_range[0];
                $max= $price_range[1];
                if($min != "" && $min != null && $max != "" && $max != null) {
                    $max_price_check = $this->payment_helper->currency_convert('USD', Session::get('currency'),1000);
                    $currency_rate = Currency::where('code',Session::get('currency'))->first()->rate;
                    if($max >= $max_price_check) {
                        $product_price = DB::table('products')->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->leftjoin('products_options', 'products_options.product_id', '=', 'products.id')->join('currency', 'currency.code', '=', 'products_prices_details.currency_code')
                            ->whereRaw('round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * '.$currency_rate.')) >= '.$min);
                        $product_price1 = $product_price->pluck('products.id');
                    }
                    else {
                       $product_price = DB::table('products')->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->leftjoin('products_options', 'products_options.product_id', '=', 'products.id')->join('currency', 'currency.code', '=', 'products_prices_details.currency_code')
                            ->whereRaw('round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * '.$currency_rate.')) >= '.$min.' and round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * '.$currency_rate.')) <= '.$max );
                        $product_price1 = $product_price->pluck('products.id');
                    }
                    $products=$products->wherein('products.id',array_unique($product_price1->toArray()));
                }
            }
        }

        if($request->searchby != "")
        {
            if($request->keyword!="")
            {
                $products=$products->where('products.title','LIKE','%'.$request->keyword.'%');
            }
            if($request->like=="1")
            {
                $products = $products->WhereHas('products_like_details', function($query){
                            $query->where('product_likes.user_id',Auth::id()); 
                });
            }
        }   
        dd($request->searchby)  ;   
        switch ($request->searchby) {
            case 'newest':
                $products=$products->orderBy('products.id','desc');
                $ordered = true;
                break;
            case 'popular':
                $products=$products->where('products.likes_count','<>','0')->orderBy('products.likes_count','desc');
                $ordered = true;
                break;
            case 'editor':
                $products->where('products.is_editor','Yes');   
                break;
            case 'featured':
                $products->where('products.is_featured','Yes');   
                break;
            case 'recommended':
                $products->where('products.is_recommend','Yes');   
                break;
                        default:                
                break;
        }
        $products=$products->where('products.admin_status','Approved')->where('total_quantity','<>','0')->where('products.sold_out','No');
        if(!$ordered)
        {
            $products=$products->orderBy('products.id','desc');   
        }
        if(@$request->first_load)
            $products = $products->take(11);
        $products = $products->paginate(10)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }
    
    // get the search product details
    public function products_search(Request $request)
    {
        $search_key = $request->search_key ?? '';
        $search_for = $request->search_for ?? 'things';

        if(!in_array($search_for,["things","people","brands"])) {
            $search_for = "things";
        }

        if($search_for == 'things') {
            $search = Product::with(['products_prices_details.currency','product_photos','products_shipping','users','wishlist' => function($query) {
                $query->where('user_id',Auth::id());
            }])
            ->activeUser()
            ->activeProduct()
            ->isLike($search_key)
            ->orderBy('likes_count','desc');
        }

        if($search_for == 'people') {
            $search = User::activeOnly()
                ->isLike($search_key)
                ->where('full_name', 'LIKE', '%'.$search_key.'%')
                ->orderBy('id','DESC');
        }

        if($search_for == 'brands') {
            $search = MerchantStore::with([
                'merchant_user_details', 'follow_store' => function($query) {
                    $query->where('follower_id',Auth::id());
                }])
                ->activeUser()
                ->isLike($search_key)
                ->orderBy('user_id','DESC');
        }

        $count = $request->count ?? $this->paginate_count;

        $result = $search->paginate($count)->toJson();

        return response($result);
    }

    //get the particular product details
    public function productDetail(Request $request)
    {
        $products = Product::with('products_prices_details.currency','categories','products_images','product_photos','products_shipping')
        ->approved()->activeUser()->isActive()->findOrFail($request->id);

        $data['result'] = $products;
        if(Auth::check() && $products->user_id != Auth::id()) {
            $already_click = ProductClick::where("user_id",Auth::id())->where("product_id",$request->id)->count();
            if($already_click == 0) {
                $product_click = new ProductClick;
                $product_click->user_id = Auth::id();
                $product_click->product_id = $request->id;
                $product_click->created_at = date('Y-m-d H:i:s');
                $product_click->save();
            }
        }
        
        $data['quantity'] = $products->original_total_quantity;

        $data['price'] = $products->price;

        $data['retail_price'] = $products->original_retail_price;

        $data['discount'] = $products->original_discount;

        $data['code']     = $products->currency_symbol;

        $data['product_currency']   = $products->products_prices_details->code;

        $data['share_url']   = $products->share_url;

        $data['wishlist']    = $products->wishlist;

        $data['shipping_country']    = $products->products_shipping;

        $data['is_video']        = $products->video_src !='' ? 'Yes' : 'No';

        $data['video_url']       = $products->video_src;

        if(isset($products->video_thumb)) {
            $data['video_thumb']     = $products->video_thumb;
        }

        return view('products.details', $data);
    }
    
    public function ajax_search(Request $request)
    {
        $term = $request->term;
        
        $final = array();
            $people = User::where('full_name', 'LIKE', '%'.$term.'%')->where('users.status','!=','Inactive')->orderBy('users.id','ASC')->take(3)->get();

            foreach ($people as $query)
            {
                $results['users'] = [ 'id' => $query->id, 'username' => $query->user_name, 'full_name' => $query->full_name, 'src'=> $query->profile_picture['src'] ];
                $final['users'][] = $results['users'];
            }


            $stores = MerchantStore::with([
                    'merchant_user_details' => function($query){},
                    ])->activeUser()->where('store_name', 'LIKE', '%'.$term.'%')->orderBy('user_id','DESC')->take(3)->get();

            foreach ($stores as $brands)
            {
                $results['stores'] = [ 'id' => $brands->id, 'store_name' => $brands->store_name, 'logo_img' => $brands->logo_img, 'header_img'=> $brands->header_img, 'city' => $brands->user_address[0]['city'], 'country' => $brands->user_address[0]['country'] ];
                $final['stores'][] = $results['stores'];
            }

            $keywords = Product::where('products.admin_status','Approved')->where('products.status','Active')->where('products.sold_out','No')->where('title', 'LIKE', '%'.$term.'%')->activeUser()->take(5)->get();

            foreach ($keywords as $things)
            {
                $results['things'] = [ 'id' => $things->id, 'title' => $things->title ];
                $final['things'][] = $results['things'];
            }

            if(empty($final))
                {
                    $final['error'] = [ 'id' => '0', 'value' => 'No results found', 'question' => 'No results found'];
                }
            
        return json_encode($final);
            

    }
    public function pop_products(Request $request)
    {

        $users_where['users.status']    = 'Active';
        $products = Product::with([
            'products_prices_details' => function($query){
                $query->with('currency');
            },
            'products_images' => function($query){},
            'product_photos' => function($query){},
            'products_shipping' => function($query){},
            'wishlist' => function($query){ $query->where('user_id',Auth::id());},
            'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },
        ])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('status','Active');
       
        if(isset($request->category) && $request->category!="all")
        {
            $category=Category::where("title",$request->category);
            if($category->count())
            {

                $categories1=Category::where("id",$category->first()->id)->where('status', 'Active')->get();
                foreach($categories1 as $category)
                {
                    array_push($this->array_category,$category->id);
                    if($category->childs->count())
                    {
                        $this->getCategorychild($category->childs);
                    }
                }
            }
            $products=$products->wherein('products.category_id',array_unique($this->array_category));
        }
          $products=$products->where('products.admin_status','Approved')->where('total_quantity','<>','0')->where('products.likes_count','<>','0')->where('products.sold_out','No')->orderBy('products.likes_count','desc');
        

        $products = $products->paginate(6)->toJson();

        $products = json_decode($products, true);
         
        echo json_encode($products);

    }
    /**
     * Ajax Product like Detail 1
     *
     * @param array $request    Input values
     * @return json product like list
     */
    public function product_likes(Request $request)
    {
        $product_id = $request->productid;
        $user_id = Auth::id();
        if($product_id !='' && $user_id !=''){
            $already_liked = ProductLikes::where('user_id',$user_id)->where('product_id',$product_id)->get();
            $product_like_count = Product::where('id',$product_id)->first()->likes_count;


            if($already_liked->count())
            {
                $ProductLikes= ProductLikes::destroy($already_liked[0]->id);
                $update_like_count['likes_count']=($product_like_count-1);
                Product::where('id',$product_id)->update($update_like_count);
            }
            else
            {
                $product_likes = new ProductLikes;
                $product_likes->user_id = $user_id;
                $product_likes->product_id = $product_id;
                $product_likes->created_at = date('Y-m-d H:i:s');
                $product_likes->save(); 

                $update_like_count['likes_count']=($product_like_count+1);
                Product::where('id',$product_id)->update($update_like_count);

                $product = Product::where('id',$product_id)->first();               

                //store activity data in notification table         
                $notification_data = new Notifications;
                $notification_data->product_id =   $product_id;
                $notification_data->user_id = $user_id;
                $notification_data->notify_id = $product->user_id;
                $notification_data->notification_type  = "like_product";
                $notification_data->notification_message  = " likes your item";
                $notification_data->save();


                //store activity data in Activity table         
                $activity_data                  = new Activity;
                $activity_data->source_id       = $user_id;
                $activity_data->source_type     = "user";
                $activity_data->activity_type   = "like_product";
                $activity_data->target_id       = $product_id;
                $activity_data->save();

                $store=MerchantStore::where('user_id',$product->user_id)->first();

                $already = FollowStore::where('store_id',$store->id)->where('follower_id',$user_id)->first();

                if($already == "") {
                    $follow_store = new FollowStore;
                    $follow_store->follower_id =$user_id;
                    $follow_store->store_id =$store->id;
                    $follow_store->save();
                    
                    //store activity data in notification table         
                    $notificationdata = new Notifications;
                    $notificationdata->follower_id =   $user_id;
                    $notificationdata->store_id = $store->id;
                    $notificationdata->notify_id = $store->user_id;
                    $notificationdata->user_id = $user_id;
                    $notificationdata->notification_type  = "store_follow";
                    $notificationdata->notification_message  = "following your store";
                    $notificationdata->save();
                }                
           }
       }

        $products = Product::where('id',$product_id)->get();       
        $products = $products->toJson();
        $products = json_decode($products, true);
        return response()->json($products);
    }

    public function clear_temp_images()
    {
        $products_ids=ProductOptionImages::pluck('product_id')->toArray();
        ProductImagesTemp::whereNotIn('product_id', array_unique($products_ids))->delete();
    }

    /**
     * Ajax Product Detail Price  while choosing option
     *
     * @param array $request    Input values
     * @return json price list
     */
    public function display_price(Request $request)
    {
        return $this->payment_helper->price_details($request->option, $request->product_id);
    }


    public function cron_for_image_compression()
    {

           $product_images  = ProductImages::all();

            foreach ($product_images as $key => $value) 
            {

            $photo_src = explode('.', $value->image_name);

                if (count($photo_src) > 1) 
                {

                    $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $value->product_id.'/'.$value->image_name;

                    if (file_exists($filename)) 
                    {

                        $ext = substr( $value->image_name, strrpos( $value->image_name, "."));
                        $newfilename = basename($value->image_name, $ext);

                        $upload_path = "image/products/".$value->product_id."/".$value->image_name;
                        $resize_path = "image/products/".$value->product_id."/".$newfilename; 

                        $this->helper->compress_image($upload_path , "image/products/".$value->product_id."/".$newfilename."_compress".$ext , 90);        
                        $this->helper->resize_image($upload_path, 650,640,$resize_path.'_home_full');
                        $this->helper->resize_image($upload_path, 450,340,$resize_path.'_home_half');
                        $this->helper->resize_image($upload_path, 124,132,$resize_path.'_popular');
                        $this->helper->resize_image($upload_path, 104,104,$resize_path.'_header');

                    }
                      
                }
            }



            
    }

    public function cron_for_category_compression()
    {
          $product_images  = Category::orderBy('id','desc')->get();
            foreach ($product_images as $key => $value) 
            {
              $photo_src = explode('.', $value->original_image_name);
                if (count($photo_src) > 1) 
                {
                   $photo[] =  $photo_src;
                    $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/category/' . $value->id.'/'.$value->original_image_name;
                    if (file_exists($filename)) 
                    {
                        $ext = substr( $value->original_image_name, strrpos( $value->original_image_name, "."));
                        $newfilename = basename($value->original_image_name, $ext);
                        $upload_path = "image/category/".$value->id."/".$value->original_image_name;
                        $resize_path = "image/category/".$value->id."/".$newfilename; 
                        $this->helper->resize_image($upload_path, 104,104,$resize_path.'_header');
                    }
                }
            }
    }

    public function cron_for_local_to_cloudinary_category()
     {
          $category_images  = Category::get();


            foreach ($category_images as $key => $value) 
            {
                $photo_src = explode('.', $value->original_image_name);

                if (count($photo_src) > 1) 
                {
                    $file = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/category/'.$value->id.'/'.$value->original_image_name;

                    $resouce_type = 'image';
                   $folder_name = "category/".$value->id;

                    if (file_exists($file)) 
                    {
                         $c = $this->helper->cloud_upload($file,$resouce_type,$folder_name);

                            if($c['status']!="error")
                            {
                              $filename=$c['message']['public_id'];  

                              $product_img = Category::find($value->id);
                              $product_img->image_name = $filename;
                              $product_img->save();  
                            }

                    }

                 }

                $icon = explode('.', $value->original_icon_name);

                if (count($icon) > 1) 
                {
                    $file = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/category/'.$value->id.'/'.$value->original_icon_name;

                    $resouce_type = 'image';
                    $folder_name = "category/".$value->id;

                    if (file_exists($file)) 
                    {
                         $c = $this->helper->cloud_upload($file,$resouce_type,$folder_name);

                            if($c['status']!="error")
                            {
                              $filename=$c['message']['public_id'];  

                              $product_img = Category::find($value->id);
                              $product_img->icon_name = $filename;
                              $product_img->save();  
                            }

                    }

                 }
        

            }
        }

    public function cron_for_local_to_cloudinary()
     {
            $product_images  = ProductImages::orderBy('id','desc')->get();
            foreach ($product_images as $key => $value) 
            {
                $photo_src = explode('.', $value->image_name);
                if (count($photo_src) > 1) 
                {
                    $file = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $value->product_id.'/'.$value->image_name;
                    $resouce_type = 'image';
                    $folder_name = "products/".$value->product_id;
                    if (file_exists($file)) 
                    {
                        $c = $this->helper->cloud_upload($file,$resouce_type,$folder_name);
                            if($c['status']!="error")
                            {
                              $filename=$c['message']['public_id'];  
                              $product_img = ProductImages::find($value->id);
                              $product_img->image_name = $filename;
                              $product_img->save();  
                            }
                    }
                 }
            }
    }
   

    public function getCategoryProduct(Request $request)
    {
        $ordered = false;
        $users_where['users.status'] = 'Active';
        $products = Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')->with([
            'products_prices_details' => function($query){
                $query->with('currency');
            },
            'products_images','products_shipping',
            'wishlist' => function($query){
                $query->where('user_id',Auth::id());
            },
            'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },
        ])
        ->activeUser()
        ->ActiveProduct();

        if($request->type == 'wishlist') {
            $products = $products->whereHas('wishlist', function($query){
                $query->where('user_id',Auth::id());
            });
        }
        
        if(isset($request->category) && $request->category != "all")
        {
            $category = Category::where("id",$request->category);
            if($category->count())
            {
                $categories1=Category::where("id",$category->first()->id)->where('status', 'Active')->get();
                foreach($categories1 as $category)
                {
                    array_push($this->array_category,$category->id);
                    if($category->childs->count())
                    {
                        $this->getCategorychild($category->childs);
                    }
                }
            }
            $products= $products->wherein('category_id',array_unique($this->array_category));
        }
            if($request->searchby!="")
            {
                if($request->price_range!="0")
                {
                    $price_range=explode("-",$request->price_range);
                    $min=@$price_range[0];
                    $max=@$price_range[1];
                    if($min!="" && $min!=null && $max!="" && $max!=null)
                    {

                        $max_price_check = $this->payment_helper->currency_convert('USD', Session::get('currency'),1000);
                        $currency_rate = Currency::where('code',Session::get('currency'))->first()->rate;
                        if($max >= $max_price_check)
                        {
                            $product_price=DB::table('products')->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->leftjoin('products_options', 'products_options.product_id', '=', 'products.id')->join('currency', 'currency.code', '=', 'products_prices_details.currency_code')
                                ->whereRaw('round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * '.$currency_rate.')) >= '.$min);
                            $product_price1=$product_price->pluck('products.id');
                        }
                        else
                        {
                           $product_price=DB::table('products')->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->leftjoin('products_options', 'products_options.product_id', '=', 'products.id')->join('currency', 'currency.code', '=', 'products_prices_details.currency_code')
                                ->whereRaw('round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * '.$currency_rate.')) >= '.$min.' and round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * '.$currency_rate.')) <= '.$max );
                            $product_price1=$product_price->pluck('products.id');
                        }
                    $products=$products->wherein('products.id',array_unique($product_price1->toArray()));
                    }
                }
                if($request->keyword!="")
                {
                    $products=$products->where('products.title','LIKE','%'.$request->keyword.'%');
                }
                if($request->like=="1")
                {
                    $products = $products->WhereHas('products_like_details', function($query){
                                $query->where('product_likes.user_id',Auth::id()); 
                    });
                }
            }    
            switch ($request->searchby) {
            case 'newest':
                $products=$products->orderBy('products.id','desc');
                $ordered = true;
                break;
            case 'popular':
                $products=$products->where('products.likes_count','<>','0')->orderBy('products.likes_count','desc');
                $ordered = true;
                break;
            case 'editor':
                $products->where('products.is_editor','Yes');   
                break;
            case 'featured':
                $products->where('products.is_featured','Yes');   
                break;
            case 'recommended':
                $products->where('products.is_recommend','Yes');   
                break;
            case 'onsale':
                $products->whereHas('products_prices_details', function($query){
                    $query->where('products_prices_details.discount','>',0);
                    });
            default:           
                break;
            }

        $products=$products->activeProduct();
        if(!$ordered)
        {
            $products=$products->orderBy('products.id','desc');   
        }
        if(@$request->first_load) {
            $products = $products->take(11);
        }

        $products = $products->paginate(10)->toJson();
        $products = json_decode($products, true);
        return response()->json($products);
    }
    
    public function getFeedProduct(Request $request)
    {
        $featuredproduct=[] ;
        $ordered = false;
        $users_where['users.status']    = 'Active';
        $products=Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')->with(['products_prices_details.currency','products_images','products_shipping','wishlist'=> function($query){ $query->where('user_id',Auth::id());},'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved');
        if(isset($request->featured) && $request->featured!="all")
        {
            $products=$products->where('products.is_featured','Yes');
        }
        $products=$products->where('products.admin_status','Approved')->where('total_quantity','<>','0')->where('products.sold_out','No');
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

    public function getRecommendedProduct(Request $request)
    {
        $featuredproduct=[] ;
        $ordered = false;
        $users_where['users.status']    = 'Active';
        $products=Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')->with(['products_prices_details.currency','products_images','products_shipping','wishlist'=> function($query){ $query->where('user_id',Auth::id());},'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved');
        if(isset($request->featured) && $request->featured!="all")
        {
            $products=$products->where('products.is_featured','Yes');
        }
        $products=$products->where('products.admin_status','Approved')->where('total_quantity','<>','0')->where('products.sold_out','No');
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
    
    public function getEditorProduct(Request $request)
    {
        $featuredproduct=[] ;
        $ordered = false;
        $users_where['users.status']    = 'Active';
        $products=Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')->with(['products_prices_details.currency','products_images','products_shipping','wishlist'=> function($query){ $query->where('user_id',Auth::id());},'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved');
        if(isset($request->featured) && $request->featured!="all")
        {
            $products=$products->where('products.is_editor','Yes');
        }
        $products=$products->where('products.admin_status','Approved')->where('total_quantity','<>','0')->where('products.sold_out','No');
        if($request->first_load)
            $products = $products->take(11);
        $products = $products->paginate(10)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }

    public  function getNewestProducts(Request $request)
    {
        $featuredproduct=[] ;
        $ordered = false;
        $users_where['users.status']    = 'Active';
        $products=Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')->with(['products_prices_details.currency','products_images','products_shipping','wishlist'=> function($query){ $query->where('user_id',Auth::id());},'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved');
        $products=$products->orderBy('products.id','desc'); 
        if($request->first_load)
            $products = $products->take(11);
        $products = $products->paginate(10)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }

    public  function getPopularProducts(Request $request)
    {
        $featuredproduct=[] ;
        $ordered = false;
        $users_where['users.status']    = 'Active';
        $products=Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')->with(['products_prices_details.currency','products_images','products_shipping','wishlist'=> function($query){ $query->where('user_id',Auth::id());},'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved');
        $products=$products->where('products.is_popular','Yes');
        if($request->first_load)
            $products = $products->take(11);
        $products = $products->paginate(10)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }

    public  function getOnSaleProduct(Request $request)
    {
        $featuredproduct=[] ;
        $ordered = false;
        $users_where['users.status']    = 'Active';
        $products=Product::select('id','user_id','category_id','return_policy','title','total_quantity','sold','video_mp4','video_webm','video_thumb','views_count','likes_count','status','admin_status','sold_out','is_featured','is_popular','is_recommend','is_editor','is_header')->with(['products_prices_details.currency','products_images','products_shipping','wishlist'=> function($query){ $query->where('user_id',Auth::id());},'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved');
        if($request->first_load)
            $products = $products->take(11);
        $products = $products->paginate(10)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }
    
    public  function getOnSaleProductLiked(Request $request)
    {
        $featuredproduct=[] ;
        $ordered = false;
        $users_where['users.status']    = 'Active';
        $products=Product::with(['productLikes'])->get();
        $products= Product::join('product_likes','product_likes.product_id','products.id')->with(['products_prices_details.currency','products_images','products_shipping','wishlist'=> function($query){ $query->where('user_id',Auth::id());},'users' => function($query) use($users_where) {
                $query->where($users_where); 
            },])->whereHas('users', function($query) use($users_where) { $query->where($users_where);
        })->where('sold_out', 'No')->where('status','Active')->where('admin_status','Approved')->where('product_likes.user_id',Auth::user()->id);
        if($request->first_load)
        $products = $products->take(11);
        $products = $products->paginate(10)->toJson();
        $products = json_decode($products, true);
        echo json_encode($products);
    }
}

