<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Timezone;
use App\Models\ProductImages;
use App\Models\ProductPrice;
use App\Models\ProductShipping;
use App\Models\ProductOption;
use App\Models\ProductOptionImages;
use App\Models\MerchantStore;
use App\Models\Category;
use App\Models\ProductLikes;
use App\Models\ProductClick;
use App\Models\ProfilePicture;
use App\Models\Notifications;
use App\Models\Wishlists;
use App\Models\FollowStore;
use App\Models\ProductReports;
use DB;
use Config;
use Session;
use Socialite;
use App;

class ProductController extends Controller
{

    public function __construct()
    {   
        App::setLocale('en');
    }
      /**
     * View product details
     *@param  Get method request inputs
     *
     * @return Response Json 
     */
    public function product_details(Request $request)
    { 
      if($request->token !=''){
      
      try {   
          
           $user = $user_token = JWTAuth::toUser($request->token);
           $id = $user->id;  
           
           
          }
          catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([

            'success_message' => 'invalid_token',

            'status_code' => '0',

           ]);

          }
      }else{
        $id = '';
      }
       // $user_token = JWTAuth::parseToken()->authenticate();
       // $id         = $user_token->id;
       $user_details = User::where('id',$id)->first();
       $product_id = $request->product_id;
       $details_product= array();
       $rules      = array(
                      'product_id'   =>  'required',
                      );
       $validator  = Validator::make($request->all(), $rules);
           
        if($validator->fails()) 
       {
         $error = $validator->messages()->toArray();

            foreach($error as $er)
            {
                $error_msg[]=array($er);

            } 
  
            return response()->json([

                              'status_message'=>$error_msg['0']['0']['0'],

                              'status_code'=>'0'

                                   ] );
       }
        else
       {

         $product_details = Product::where('id',$product_id)->first();

         if(empty($product_details)){

            return  response()->json([

                              'status_message'=>'Invalid Product Id',

                              'status_code'    =>'0',

                              ]);

           }else{

           //increment user view product count
            if($id !='' && $product_details['user_id'] != $id){

                    $product_click = new ProductClick;

                    $product_click->user_id = $id;

                    $product_click->product_id = $product_id;

                    $product_click->created_at = date('Y-m-d H:i:s');

                    $product_click->save();

            }
            //end
           
           if(@$product_details->id !=''){

              //product details

              $details_product['product_id']  = $product_id ;
              
              $details_product['product_name']= $product_details->title;
              //product image details view first image
              $product_image  = ProductImages::where('product_id',$product_id)->first();
             
              @$image_default=$product_image->images_name; 
             
            $details_product['image_url'] =@$image_default;
            
            $details_product['product_price']= @$product_details->products_prices_details->price;
            
            $details_product['product_owner_name']= @$user_details->full_name;
           
            $details_product['description']= strip_tags(@$product_details->description);

            $details_product['is_video']        = $product_details->video_src !='' ? 'Yes' : 'No';

            $details_product['video_url']       = $product_details->video_src;

            $details_product['video_thumb']     = $product_details->video_thumb;

            //product options details
            $options['name'] = ProductOption::where('product_id',$product_id)->get();
            if(count($options['name'])){
              $product_option_id = [];
            foreach($options['name'] as $opt){
              @$product_option_id[] =['id' => $opt->id ,'name' =>$opt->option_name,'available_qty' => $opt->total_quantity,'price' => $opt->price];
             
            }
          }else{
            @$product_option_id=[];
          }
            $details_product['product_option'] = @$product_option_id ;

            //All product image details 
            $product_option_image['images']  = ProductImages::where('product_id',$product_id)->get();
             foreach($product_option_image['images'] as $images)
             { 
              
              @$image_option[]=$images->images_name; 
           
            }
            $details_product['images'] =@$image_option;

            $shipping_details = ProductShipping::where('product_id',$product_id)->get();
            
            $details_product['ship_from'] =@$shipping_details['0']->ships_from;
            
            foreach($shipping_details as $shipping){
             $arrival['time']  = $shipping->start_window .'-'.$shipping->end_window.' '."days";
             $arrival['country'] =$shipping->ships_to;
             $arrival['id'] =$shipping->id;
             $arrival_detail[] =@$arrival;
             $details_product['arrival'] = @$arrival_detail;
            }

            $details_product['return_policy'] = @$product_details->returns_policy->name;



            //liked user details

            $user_liked_list = ProductLikes::where('product_id',$product_id)->activeUser()->get();
   
            if(count($user_liked_list)){
              foreach($user_liked_list as $liked_list)
             { 
              $profile_picture = ProfilePicture::where('user_id',$liked_list->user_id)->first();
              @$profile_image=$profile_picture->src; 
              $liked_user['id']=$liked_list->user_id; 
              $liked_user['image_url']=@$profile_image!='' && @$profile_image!='null' ? @$profile_image : url('/image/profile.png');
              $liked_users[] = @$liked_user;
             }
            }else{
              $liked_users =[] ;
            }
            $details_product['liked_user_details'] = @$liked_users;
            //product like count
             $like_count = ProductLikes::where('product_id',$product_id)->activeUser()->count();
                  $details_product['like_count'] = @$like_count;

            //login user liked the product or not
            $is_liked = ProductLikes::where('product_id',$product_id)->where('user_id',$id)->count();
            $details_product['is_liked'] = @$is_liked;
            //login user wislist product or not
            $is_wishlist =Wishlists::where('product_id',$product_id)->where('user_id',$id)->count();
            $details_product['is_wishlist'] =@$is_wishlist;
            //store details

            $store_details = MerchantStore::where('user_id',$product_details->user_id)->activeUser()->first();

            if(count($store_details)){
              @$store_image_logo =  $store_details->logo_img; 

              $products = Product::where('user_id',$product_details->user_id)->where('products.admin_status','Approved')->where('total_quantity','<>','0')->where('sold_out','No')->where('status','Active')->activeUser()->limit(4)->get();
              foreach($products as $pro){
                $productimage  = ProductImages::where('product_id',$pro->id)->first();
                @$store_product['product_id'] = $pro->id;
                @$store_product['product_image'] = $productimage->images_name;
                //product like count
                $product_like_count = ProductLikes::where('product_id',$pro->id)->count();
                  $store_product['like_count'] = @$product_like_count;
                
                //login user like product or not
                 $product_is_liked = ProductLikes::where('product_id',$pro->id)->where('user_id',$id)->count();
                $store_product['is_liked'] = @$product_is_liked;
                  
                //view product option
                $product_options['name'] = ProductOption::where('product_id',$pro->id)->get();
                if(count(@$product_options['name'])){
                  @$product_option_detail = [];
                foreach($product_options['name'] as $product_option){
                  @$product_option_detail[] =['id' => $product_option->id ,'name' =>$product_option->option_name,'price' =>$product_option->price ];
                    }
                  }else{
                    @$product_option_detail = [];
                  }
                $store_product['options'] = @$product_option_detail;
                @$product_option_detail = '';
                $store_product['available_qty'] = $pro->total_quantity;
                $store_products[] = @$store_product;
              }
              @$is_follow = FollowStore::where('store_id',$store_details->id)->where('follower_id',$id)->count();
   
            $storedetails['id'] = $store_details->id;
            $storedetails['store_name'] = $store_details->store_name;
            $storedetails['image'] = $store_image_logo ;
            $storedetails['is_liked'] = '0' ;
            $storedetails['seller_name'] = @$user_details->full_name;
            $storedetails['is_follow'] = @$is_follow;
            $storedetails['product_details'] = $store_products;
            
            }else{
              $storedetails ='No Store Details';
            }
            $details_product['store_details'] = @$storedetails;


              return  response()->json([

                                'status_message'=>'Product details Listed Successfully',

                                'status_code'    =>'1',

                                'product_details'   =>$details_product

                                ]); 

           }else{
              return  response()->json([

                                'status_message'=>'Product details not Listed',

                                'status_code'    =>'0',

                                ]); 
           }

         }
        
       }
   }
    /**
     * Get Featured Product Image only
     *@param  Get method request inputs
     *
     * @return Response Json 
     */

    public function featured_product(Request $request)
    {
        $featured_product = Product::where('is_featured','Yes')->where('products.admin_status','Approved')->where('total_quantity','<>','0')->where('status','Active')->where('sold_out','No')->orderBy('id', 'desc')->take(10)->get();

        if(count($featured_product) != 0)
        {
          
          foreach ($featured_product as $featured) {
            $product_image[]    = $featured->image_name;            
          }

          return  response()->json([

                              'status_message'=>'Featured Product details Listed Successfully',

                              'status_code'    =>'1',

                              'product_details'   =>$product_image

                              ]); 
        }
        else{          
            return  response()->json([

                              'status_message'=>'Featured Product details not Listed',

                              'status_code'    =>'0',

                              ]); 
         }
    }

    /**
     * View Categories
     *@param  Get method request inputs
     *
     * @return Response Json 
     */
    public function category(Request $request)
    { 
     
      if($request->token !=''){
      
      try {   
          
           $user = $user_token = JWTAuth::toUser($request->token);
           $id = $user->id;  
           
           
          }
          catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([

            'success_message' => 'invalid_token',

            'status_code' => '0',

           ]);

          }
      }else{
        $id = '';
      }
       $categories = array();
       $category['name'] = Category::where('status','Active')->orderBy('id','asc')->get();
        foreach($category['name'] as $cate){
            $category_image = $cate->image_name !='' ? $cate->image_name : url('/').'/image/new-navigation.png';
            
             @$product_option_id['id'] = $cate->id;
             @$product_option_id['group_id'] = $cate->parent_id;
             @$product_option_id['name'] = $cate->title;
             @$product_option_id['image_url'] = $category_image;
           // @$product_option_name[] = $opt->option_name;
             $categories[] = $product_option_id;
          }
          
          return  response()->json([

                              'status_message'=> 'Categories Listed Successfully',

                              'status_code'    => '1',

                              'categories'     => $categories

                              ]); 


    }
    /**
    *Like an unlike the product
    * @param  Get method request inputs
    * @return Response Json 
    */
     public function like_product(Request $request)
    {  
         $user_token = JWTAuth::parseToken()->authenticate();
         $user_id    = $user_token->id;
         $rules      = array(
                            'product_id'      =>  'required',
                            );
         $validator  = Validator::make($request->all(), $rules);
           
         if($validator->fails()) 
         {
         $error = $validator->messages()->toArray();

            foreach($error as $er)
            {
                $error_msg[]=array($er);

            } 
  
            return response()->json([

                              'status_message'=>$error_msg['0']['0']['0'],

                              'status_code'=>'0'

                                   ] );
         }
         else
        {
            $product_id = $request->product_id;

            //check the product id valid

            $check_product =Product::where('id',$product_id)->get();

            if(!count($check_product)){
                return response()->json([

                     'status_message'    =>  'Invalid Product id',

                     'status_code'       =>  '0',

                                   ]);
            }

            $product = ProductLikes::where('user_id',$user_id)->where('product_id',$product_id)->get();

            $product_like_count = Product::where('id',$product_id)->first()->likes_count;

            if(count($product)){
                //Unlike Product
                $unlike_product = ProductLikes::where('user_id',$user_id)->where('product_id',$product_id)->first();

                $product_like = ProductLikes::find($unlike_product->id);

                $product_like->delete();

                $update_like_count['likes_count']=($product_like_count-1);

                Product::where('id',$product_id)->update($update_like_count);

                return response()->json([

                     'status_message'    =>  'Product Unliked',

                     'status_code'       =>  '1',

                                   ]);


            }else{
                //Like Product
                $product_like             = new ProductLikes;

                $product_like->user_id    = $user_id;

                $product_like->product_id = $product_id;

                $product_like->save();

                $update_like_count['likes_count']=($product_like_count+1);

                Product::where('id',$product_id)->update($update_like_count);

                $product=Product::where('id',$product_id)->first();     

                //store activity data in notification table         
                $activity_data = new Notifications;
                $activity_data->product_id =   $product_id;
                $activity_data->user_id = $user_id;
                $activity_data->notify_id = $product->user_id;
                $activity_data->notification_type  = "like_product";
                $activity_data->notification_message  = " likes your item";
                $activity_data->save();


                $store=MerchantStore::where('user_id',$product->user_id)->first();

                $already = FollowStore::where('store_id',$store->id)->where('follower_id',$user_id)->first();

                if(count($already) == 0)
                {
                    $follow_store = new FollowStore;
                    $follow_store->follower_id =$user_id;
                    $follow_store->store_id =$store->id;
                    $follow_store->save();

                    $activitydata = new Notifications;
                    $activitydata->follower_id =   $user_id;
                    $activitydata->store_id = $store->id;
                    $activitydata->notify_id = $store->user_id;
                    $activitydata->user_id = $user_id;
                    $activitydata->notification_type  = "store_follow";
                    $activitydata->notification_message  = "following your store";
                    $activitydata->save();
                }

                 return response()->json([

                     'status_message'    =>  'Product Liked',

                     'status_code'       =>  '1',

                                   ]); 
            }

        }

    }

    /**
    *Add wishlist the product
    * @param  Get method request inputs
    * @return Response Json 
    */
     public function wish_list(Request $request)
    {  
         $user_token = JWTAuth::parseToken()->authenticate();
         $user_id    = $user_token->id;
         $rules      = array(
                            'product_id'      =>  'required',
                            );
         $validator  = Validator::make($request->all(), $rules);
           
         if($validator->fails()) 
         {
         $error = $validator->messages()->toArray();

            foreach($error as $er)
            {
                $error_msg[]=array($er);

            } 
  
            return response()->json([

                              'status_message'=>$error_msg['0']['0']['0'],

                              'status_code'=>'0'

                                   ] );
         }
         else
        {
            $product_id = $request->product_id;

            //check the product id valid

            $check_product =Product::where('id',$product_id)->get();

            if(!count($check_product)){
                return response()->json([

                     'status_message'    =>  'Invalid Product id',

                     'status_code'       =>  '0',

                                   ]);
            }

            $wish_list = Wishlists::where('user_id',$user_id)->where('product_id',$product_id)->get();

            if(count($wish_list)){
                //remove wishlist Product
                $remove_wishlist = Wishlists::where('user_id',$user_id)->where('product_id',$product_id)->first();

                $wishlist_remove = Wishlists::find($remove_wishlist->id);

                $wishlist_remove->delete();

                return response()->json([

                     'status_message'    =>  'WishLists Removed',

                     'status_code'       =>  '1',

                                   ]);


            }else{
                //Wishlist Product
                $wishlist_product             = new Wishlists;

                $wishlist_product->user_id    = $user_id;

                $wishlist_product->product_id = $product_id;

                $wishlist_product->save();

                $products=Product::where('id',$product_id)->first();
                $activity_data = new Notifications;
                $activity_data->product_id =   $product_id;
                $activity_data->user_id = $user_id;
                $activity_data->notify_id = $products->user_id;
                $activity_data->notification_type  = "wishlist";
                $activity_data->notification_message  = "added your item";
                $activity_data->save();

                 return response()->json([

                     'status_message'    =>  'WishList added Successfully',

                     'status_code'       =>  '1',

                                   ]); 
            }

        }

    }

      /**
     * View wishlist user details
     *@param  Get method request inputs
     *
     * @return Response Json 
     */
    public function wishlist_details(Request $request)
    { 
    if(isset($request->page))
    { 
         $rules = array( 
                  'page'      =>   'required|integer|min:1',
                        );

    }
    $validator = Validator::make($request->all(), $rules);

         if ($validator->fails()) 
         {
            $error=$validator->messages()->toArray();

               foreach($error as $er)
               {
                    $error_msg[]=array($er);

               } 
  
                return response()->json([

                                'status_message'=>$error_msg['0']['0']['0'],

                                'status_code'=>'0'

                                        ] );
         }
        $user_token = JWTAuth::parseToken()->authenticate();
        $user_id         = @$request->user_id!='' ? $request->user_id : $user_token->id;
        
        if($request->page!='' && $request->page!='0')
        {
          $products = Product::where('status','Active')->activeUser()->where('total_quantity','<>','0')->where('products.admin_status','Approved')->where('sold_out','No')->with(['products_prices_details','users','products_like_details','wishlist'])->whereHas('wishlist',function($query) use($user_id){
            $query->whereRaw('user_id ='.$user_id);
                                    
                            });
        
         
        $products = $products->orderBy('id','desc')->paginate(10)->toJson();

         $data  = array(

                       'status_message' => 'Wish List Product Details Listed Successfully',

                       'status_code'     => '1'

                      );

        $data_success =  json_encode($data); 

        $totalcount   =  json_decode($products);
        if($totalcount->total==0 || empty($totalcount->data))
        {
            return response()->json([

                                      'status_message' => 'No Data Found',

                                      'status_code'     => '0'

                                     ]);
        }else
        {
          $data_result = json_decode($products,true);

          $count      =  count($data_result['data']);
          for($i=0;$i<$count;$i++)
            { 
                // product options details
              @$product_options = "";
             $options['name'] = ProductOption::where('product_id',$data_result['data'][$i]['id'])->get();
             if($options['name']->count())
             { 
              $product_option_id = [];
             foreach($options['name'] as $opt)
             {

              $product_option_id[] =['id' => $opt->id ,'name' =>$opt->option_name,'available_qty' => $opt->total_quantity,'price' => $opt->price];
          
             }

            $product_options = $product_option_id;
            $product_option_id = '';
             }
             
             
            $is_liked =ProductLikes::where('user_id',$user_id)->where('product_id',$data_result['data'][$i]['id'])->count();

     
               
             @$result_value[]=array(
                    
                     'id'               => $data_result['data'][$i]['id'], 

                     'name'             => $data_result['data'][$i]['title'], 

                     'description'      => strip_tags($data_result['data'][$i]['description']), 

                     'image_url'        => $data_result['data'][$i]['image_name'],

                     'price'            => $data_result['data'][$i]['price'],  

                     'seller_name'      => $data_result['data'][$i]['users']['full_name'],

                     'like_count'       => $data_result['data'][$i]

                                          ['like_count']!=null

                                          ? (string)$data_result['data'][$i]

                                          ['like_count']:'0',

                      'is_liked'        => @$is_liked,

                      'options'         => @$product_options !="" ? $product_options:[],

                      'available_qty'   => $data_result['data'][$i]['total_quantity']                  


                );
            
        }

           $result= array(
                          'total_page'  =>   $data_result['last_page'],

                          'data'        =>   $result_value
                        );

           $data = json_encode($result); 

           return json_encode(array_merge(json_decode($data_success, true),json_decode($data, true)),JSON_UNESCAPED_SLASHES);
        }
    }
    else
    {
        return response()->json([

                                 'status_message' => 'Undefind Page No',
                                 
                                 'status_code'     => '0'

                                ]);

   }


    }

    //Report Product
    public function report(Request $request)
    {
      $user_token = JWTAuth::parseToken()->authenticate();
      $user_id    = @$request->user_id !='' ? $request->user_id : $user_token->id;

      $product_id = $request->product_id;

      $report = new ProductReports;

      $report->user_id = $user_id;
      $report->product_id = $product_id;

      $report->save();

      return response()->json([
                                'status_message' => 'Product has been reported successfully',
                                'status_code'     => '1'
                              ]);
    }
        
}
