<?php

/**
 * Profile Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Profile
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App;
use App\Http\Controllers\Controller;
//use Auth;
use App\Http\Start\Helpers;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Follow;
use App\Models\FollowStore;
use App\Models\MerchantStore;
use App\Models\Notifications;
use App\Models\Product;
use App\Models\ProductLikes;
use App\Models\ProductOption;
use App\Models\ProfilePicture;
use App\Models\User;
use App\Models\Wishlists;
use App\Models\BlockUsers;
use DateTime;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class ProfileController extends Controller {
	public function __construct() {
		App::setLocale('en');
	}

	/**
	 * User Profile photo upload
	 *@param  Post method request inputs
	 *
	 * @return Response Json
	 */

	public function upload_profile_image(Request $request) {
		$this->helper = new Helpers;

		//check uploaded image is set or not
		if (isset($_FILES['image'])) {
			$errors = array();
			$acceptable = array(
				'image/jpeg',
				'image/jpg',
				'image/gif',
				'image/png',
			);

			if ((!in_array($_FILES['image']['type'], $acceptable)) && (!empty($_FILES["image"]["type"]))) {
				return response()->json([
					'status_message' => "Invalid file type. Only  JPG, GIF and PNG types are accepted.",
					'status_code' => "0",
				]);
			}

			$file_name = time() . '_' . $_FILES['image']['name'];
			$type = pathinfo($file_name, PATHINFO_EXTENSION);
			$file_tmp = $_FILES['image']['tmp_name'];
			$dir_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/temp_image';
			if (!file_exists($dir_name)) {
				//create file directory
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/temp_image', 0777, true);
			}

			$f_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/temp_image/' . $file_name;
			if (UPLOAD_DRIVER == 'cloudinary') {
				$c = $this->helper->cloud_upload($file_tmp);
				if ($c['status'] != "error") {
					$small = $c['message']['public_id'];
				} else {
					return response()->json([
						'status_message' => $c['message'],
						'status_code' => '0',
					]);
				}
			} else {
				if (move_uploaded_file($file_tmp, $f_name)) {
					//change compress image in 225*225
					$li = $this->helper->compress_image("image/users/temp_image/" . $file_name, "image/users/temp_image/" . $file_name, 80, 225, 225);
				}
				$b_name = basename($file_name, '.' . $type);
				$small = $b_name . '.' . $type;
			}

			return response()->json([
				'status_message' => "Profile Image Upload Successfully",
				'status_code' => "1",
				'user_image_name' => $small,
			]);
		}
	}
	/**
	 * User Image upload
	 *@param  Post method request inputs
	 *
	 * @return Response Json
	 */
	public function upload_image(Request $request) {
		$this->helper = new Helpers;
		$rules = array(
			'image_type' => 'required|in:1,2',
		);
		$validator = Validator::make($request->all(), $rules);
		if (!$validator->fails()) {
			//check uploaded image is set or not
			if (isset($_FILES['image'])) {
				$user_token = JWTAuth::toUser($_POST['token']);
				$user_id = $user_token->id;
				$image_type = $request->image_type;
				$errors = array();
				$acceptable = array(
					'image/jpeg',
					'image/jpg',
					'image/gif',
					'image/png',
				);
				if ((!in_array($_FILES['image']['type'], $acceptable)) && (!empty($_FILES["image"]["type"]))) {
					return response()->json([
						'status_message' => "Invalid file type. Only  JPG, GIF and PNG types are accepted.",
						'status_code' => "0",
					]);
				}
				$file_name = time() . '_' . $_FILES['image']['name'];
				$type = pathinfo($file_name, PATHINFO_EXTENSION);
				$file_tmp = $_FILES['image']['tmp_name'];

				$dir_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id;
				if (!file_exists($dir_name)) {
					mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id, 0777, true);
				}
				$f_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/users/' . $user_id . '/' . $file_name;
				if (UPLOAD_DRIVER == 'cloudinary') {
					$c = $this->helper->cloud_upload($file_tmp);
					if ($c['status'] != "error") {
						$small = $c['message']['public_id'];
					} else {
						return response()->json([
							'status_message' => $c['message'],
							'status_code' => '0',
						]);
					}
				} else {
					if (move_uploaded_file($file_tmp, $f_name)) {
						//change compress image in 225*225
						$li = $this->helper->compress_image("image/users/" . $user_id . '/' . $file_name, "image/users/" . $user_id . '/' . $file_name, 80, 225, 225);
					}

					$b_name = basename($file_name, '.' . $type);
					$small = $b_name . '.' . $type;
				}

				//upload image
				if ($image_type == '1') {
					$profile['src'] = $small;
					$profile['photo_source'] = 'Local';
				} else if ($image_type == '2') {
					$profile['cover_image_src'] = $small;
				}

				$profile['user_id'] = $user_id;
				$profile_picture_count = ProfilePicture::where('user_id', $user_id)->count();
				if ($profile_picture_count) {
					ProfilePicture::where('user_id', $user_id)->update($profile);
				} else {
					ProfilePicture::create($profile);
				}
				$profile_pic = ProfilePicture::where('user_id', $user_id);
				$user_image_url = @$profile_pic->first()->src;
				$user_cover_image_url = @$profile_pic->first()->cover_image_src;
				return response()->json([
					'status_message' => "Image Upload Successfully",
					'status_code' => "1",
					'user_image_url' => @$user_image_url != '' ? $user_image_url : '',
					'user_cover_image_url' => @$user_cover_image_url != '' ? $user_cover_image_url : '',
				]);
			}
		} else {
			$error = $validator->messages()->toArray();
			foreach ($error as $er) {
				$error_msg[] = array($er);
			}
			return response()->json([
				'status_message' => $error_msg['0']['0']['0'],
				'status_code' => '0',
			]);
		}
	}
	/**
	 * Edit User Profile
	 *
	 * @param  Get method inputs
	 * @return  Response in Json
	 */

	public function edit_profile(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();

		$id = $user_token->id;

		$rules = array(

			'full_name' => 'required | max:255',

			'dob' => 'date_format:"d-m-Y"| date |required',

			'gender' => 'required|in:Male,Female,Other',

		);

		$messages = array(

			'required' => ':attribute is required.',

			'regex' => 'Invalid Image Url.',

		);

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'status_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		} else {

			// Check User name is Already Exists or Not.
			$user_name = $request->user_name;

			$user_name_result = DB::table('users')->where('user_name', $user_name)->get();

			if (count($user_name_result) == 1) {
				if ($user_name_result[0]->id != $id) {
					return response()->json([

						'status_message' => 'User name Already Exists',

						'status_code' => '0',

					]);

				}
			}

			if (count($user_name_result) > 1) {

				return response()->json([

					'status_message' => 'User name Already Exists',

					'status_code' => '0',

				]);

			}

			//check Image Exists or Not.
			// $file_name       =basename($request->user_image_url);

			// $chck_image_name =DB::table('profile_picture')->where('user_id',$user_token->id)->where('src',$file_name)->get();
			// if(count($chck_image_name)!= 1 && $request->user_image_url!='')
			//  {

			//         $pro_pic=DB::table('profile_picture')->where('user_id',$user_token->id)

			//                      ->update(['src'=>$file_name,'photo_source'=>'Local']);
			//  }

			$edit_user = array(

				'full_name' => $request->full_name,

				'dob' => date("Y-m-d", strtotime($request->dob)),

				'website' => $request->web_site,

				'location' => $request->location,

				'bio' => $request->bio_data,

				'gender' => $request->gender,

			);
			// Update The User Details.
			$result = DB::table('users')->where('id', $id)->update($edit_user);

			$user_details = User::where('id', $id)->first();

			$createDate = new DateTime($user_details->created_at);
			//Check dob empty or not
			if ($user_details->dob == '0000-00-00') {

				$dob = '';
			} else {
				$c_dob = new DateTime($user_details->dob);

				$dob = $c_dob->format('d-m-Y');

			}
			$c_date = $createDate->format('M Y');

			$pro_pic = ProfilePicture::where('user_id', $id)->first();
			$pro_img = $pro_pic->src;
			$pro_cover_img = $pro_pic->cover_image_src;

			//follower count

			$follower_count = @Follow::with([
	                                        'follower_user' => function ($query){
	                                            $query->where('users.status','Active');
	                                        },
	                                        'following_users' => function($query){
	                                            $query->where('users.status','Active');
	                                        }
	                                    ])->whereHas('follower_user',function($query){
	                                        $query->where('users.status','Active');
	                                    })->whereHas('following_users',function($query){
	                                        $query->where('users.status','Active');
	                                    })->where('user_id',$id)->get()->count(); 

			//following count

			$following_count = @Follow::with([
	                                        'follower_user' => function ($query){
	                                            $query->where('users.status','Active');
	                                        },
	                                        'following_users' => function($query){
	                                            $query->where('users.status','Active');
	                                        }
	                                    ])->whereHas('follower_user',function($query){
	                                        $query->where('users.status','Active');
	                                    })->whereHas('following_users',function($query){
	                                        $query->where('users.status','Active');
	                                    })->where('follower_id',$id)->get()->count();

			$details_user['full_name'] = $user_details->full_name != ''

			? $user_details->full_name : '';

			$details_user['user_name'] = $user_details->user_name != ''

			? $user_details->user_name : '';

			$details_user['dob'] = $dob != '' ? $dob : '';

			$details_user['location'] = $user_details->location != ''

			? $user_details->location : '';

			$details_user['web_site'] = $user_details->website != ''

			? $user_details->website : '';

			$details_user['bio_data'] = $user_details->bio != ''

			? $user_details->bio : '';

			$details_user['gender'] = $user_details->gender != ''

			? $user_details->gender : '';

			$details_user['email'] = $user_details->email != ''

			? $user_details->email : '';

			$details_user['user_status'] = $user_details->status == '' ? '' : $user_details->status;

			$details_user['user_image_url'] = $pro_img;

			$details_user['cover_image_url'] = $pro_cover_img;

			$details_user['follower_count'] = @$follower_count;

			$details_user['following_count'] = @$following_count;

			$details_user['is_follow_user'] = '0';

			$details_user['is_follow_store'] = '0';

			return response()->json([

				'status_message' => 'User Details Updated Successfully',

				'status_code' => '1',

				'user_details' => @$details_user,

			]);

		}

	}

	/**
	 * Display the Profile details
	 *@param  Get method request inputs
	 *
	 * @return Response Json
	 */

	public function view_profile(Request $request) {

		$user_token = JWTAuth::parseToken()->authenticate();

		$details_user = array();
		if (@$request->user_id != '') {
			$user_id = $request->user_id;
		} else {
			$user_id = $user_token->id;
		}

		$user_details = User::where('id', $user_id)->first();

		$user_cart_count = User::where('id', $user_token->id)->first()->cart_count;

		$createDate = new DateTime($user_details->created_at);
		//Check dob empty or not
		if ($user_details->dob == '0000-00-00') {

			$dob = '';
		} else {
			$c_dob = new DateTime($user_details->dob);

			$dob = $c_dob->format('d-m-Y');

		}
		$c_date = $createDate->format('M Y');

		$pro_pic = ProfilePicture::where('user_id', $user_id)->first();
		$pro_img = $pro_pic->src;
		$pro_cover_img = $pro_pic->cover_image_src;

		//wishlist count

		$wishlist_count = WishLists::with(['users'=>function($query){
											$query->where('users.status','Active');
										}
									])->whereHas('wish_product_details', function ($query)  {
			$query->where('products.status', 'Active')->where('products.admin_status', 'Approved')->where('products.total_quantity', '<>', '0')->where('products.sold_out', 'No')->whereHas('users', function ($query)  {
				$query->where('users.status','Active');
			});
		})->where('user_id', $user_id)->count();

		//follower count

		$follower_count = @Follow::with([
                                        'follower_user' => function ($query){
                                            $query->where('users.status','Active');
                                        },
                                        'following_users' => function($query){
                                            $query->where('users.status','Active');
                                        }
                                    ])->whereHas('follower_user',function($query){
                                        $query->where('users.status','Active');
                                    })->whereHas('following_users',function($query){
                                        $query->where('users.status','Active');
                                    })->where('user_id',$user_id)->get()->count(); 

		//following count

		$following_count = @Follow::with([
                                        'follower_user' => function ($query){
                                            $query->where('users.status','Active');
                                        },
                                        'following_users' => function($query){
                                            $query->where('users.status','Active');
                                        }
                                    ])->whereHas('follower_user',function($query){
                                        $query->where('users.status','Active');
                                    })->whereHas('following_users',function($query){
                                        $query->where('users.status','Active');
                                    })->where('follower_id',$user_id)->get()->count();

		//is follow user

		$is_follow_user =  @Follow::with([
                                        'follower_user' => function ($query){
                                            $query->where('users.status','Active');
                                        },
                                        'following_users' => function($query){
                                            $query->where('users.status','Active');
                                        }
                                    ])->whereHas('follower_user',function($query){
                                        $query->where('users.status','Active');
                                    })->whereHas('following_users',function($query){
                                        $query->where('users.status','Active');
                                    })->where('follower_id', $user_token->id)->where('user_id',$user_id)->get()->count(); 

		//is follow store

		$merchant_store = MerchantStore::where('user_id', @$request->user_id)->first();

		$is_follow_store = FollowStore::where('follower_id', $user_token->id)->where('store_id', @$merchant_store->id)->count();

		$details_user['full_name'] = $user_details->full_name != ''

		? html_entity_decode($user_details->full_name) : '';

		$details_user['user_name'] = $user_details->user_name != ''

		? $user_details->user_name : '';

		$details_user['dob'] = $dob != '' ? $dob : '';

		$details_user['location'] = $user_details->location != ''

		? $user_details->location : '';

		$details_user['web_site'] = $user_details->website != ''

		? $user_details->website : '';

		$details_user['bio_data'] = $user_details->bio != ''

		? $user_details->bio : '';

		$details_user['gender'] = $user_details->gender != ''

		? $user_details->gender : '';

		$details_user['email'] = $user_details->email != ''

		? $user_details->email : '';

		$details_user['cart_count'] = $user_cart_count != ''

		? $user_cart_count : '';

		$details_user['user_status'] = $user_details->status == '' ? '' : $user_details->status;

		$details_user['user_liked_count'] = Product::where('status', 'Active')->with(['products_prices_details', 'users', 'products_like_details'])->whereHas('products_like_details', function ($query) use ($user_id) {
			$query->whereRaw('user_id =' . $user_id);

		})->activeUser()->count();
		$store_liked_count = 0;
		$products = Product::where('status', 'Active')->with(['products_prices_details', 'users', 'products_like_details'])->whereHas('products_like_details', function ($query) use ($user_id) {
			$query->whereRaw('user_id =' . $user_id);
		})->activeUser()->get();

		// foreach($products as $user_detail)
		// {
		// $users[] = $user_detail->user_id;
		// }

		//follow store user details
		$follow_store = FollowStore::where('follower_id', @$user_id)->get();

		if (count($follow_store)) {
			foreach ($follow_store as $is_follow_stores) {
				$merchant_user = MerchantStore::where('id', $is_follow_stores->store_id)->first();
				$users[] = $merchant_user->user_id;
			}

			$user_detail = User::whereIn('id', $users)->get();
			foreach ($user_detail as $user_details) {
				if($user_details->status != 'Inactive'){
				@$store_liked_count += MerchantStore::where('user_id', $user_details->id)->count();
			     }
			}
		}

		$details_user['is_blocked'] = BlockUsers::whereUserId($user_token->id)->whereBlockedUserId($user_id)->count();

		$details_user['user_image_url'] = $pro_img;

		$details_user['cover_image_url'] = $pro_cover_img;

		$details_user['follower_count'] = @$follower_count;

		$details_user['following_count'] = @$following_count;

		$details_user['wishlist_count'] = @$wishlist_count;

		$details_user['store_like_count'] = @$store_liked_count;

		$details_user['is_follow_user'] = @$is_follow_user;

		$details_user['is_follow_store'] = @$is_follow_store;

		$details_user['store_id'] = @$merchant_store->id != '' ? @$merchant_store->id : "";

		return response()->json([

			'status_message' => 'User Details Listed Successfully.',

			'status_code' => '1',

			'user_details' => $details_user,

		]);

	}
	/**
	 * View user store details and product details
	 *@param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function shop_page(Request $request) {

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
		$details_product_picks = array();
		$details_product_pick = array();
		$details_product_recommend = array();
		$detail_product_recommend = array();
		$details_product_popular = array();
		$detail_product_popular = array();
		$details_product_onsale = array();
		$detail_product_onsale = array();
		$details_product_new = array();
		$detail_product_new = array();
		$categories = array();

		//merchant store details
		$user_featured = User::where('status','Active')->where('featured', 'Yes')->where('type', 'merchant')->get();
		if (count($user_featured)) {
			foreach ($user_featured as $featured) {
				$merchant_store_details = MerchantStore::where('user_id', $featured->id)->first();
				$details_store['store_id'] = @$merchant_store_details->id;
				$details_store['store_image_url'] = @$merchant_store_details->header_img != '' ? @$merchant_store_details->header_img : url('/') . '/image/cover_image.jpg';
				$detail_store[] = @$details_store;
			}
		} else {
			$detail_store = [];
		}

		// editor picks product details
		$products_picks = Product::where('total_quantity', '<>', '0')->where('sold_out', 'No')->where('products.admin_status', 'Approved')->where('status', 'Active')->where('is_editor', 'Yes')->activeUser()->orderBy('id', 'desc');

		if($id != '') {
				$blocked_users = BlockUsers::whereUserId($id)->orWhere('blocked_user_id',$id)->get();
				$blocked_user_ids = array_column($blocked_users->toArray(), 'blocked_user_id');
				$user_ids = array_column($blocked_users->toArray(), 'user_id');

				$merged_ids = array_merge($blocked_user_ids, $user_ids);

				$products_picks = collect($products_picks->get())->filter(function ($value, $key) use($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();
				$products_picks = $products_picks->take(10);
		}
		else {
			$products_picks = $products_picks->limit(10)->get();
		}

		if (count($products_picks)) {
			foreach ($products_picks as $picks) {
				//login user like product or not
				$product_is_liked = ProductLikes::where('product_id', $picks->id)->where('user_id', $id)->count();

				//view product option
				$product_options['name'] = ProductOption::where('product_id', $picks->id)->get();
				if (count(@$product_options['name'])) {
					@$product_option_detail = [];
					foreach ($product_options['name'] as $product_option) {						
						@$product_option_detail[] = ['id' => $product_option->id, 'name' => $product_option->option_name];
					}
				} else {
					@$product_option_detail = [];
				}

				$details_product_picks['id'] = $picks['id'];
				$details_product_picks['name'] = $picks['title'];
				$details_product_picks['image_url'] = $picks['image_name'];
				$details_product_picks['price'] = $picks['price'];
				$details_product_picks['seller_name'] = $picks['user_name'];
				$details_product_picks['like_count'] = $picks['like_count'] != null ? (string) $picks['like_count'] : '0';
				$details_product_picks['is_liked'] = @$product_is_liked;
				$details_product_picks['options'] = @$product_option_detail;
				@$product_option_detail = '';
				$details_product_pick[] = $details_product_picks;
			}
		} else {

			$details_product_pick = [];
		}
		// Recommended product details
		$products_recommend = Product::where('total_quantity', '<>', '0')->where('sold_out', 'No')->where('products.admin_status', 'Approved')->where('status', 'Active')->where('is_recommend', 'Yes')->activeUser()->orderBy('id', 'desc');

		if($id != '') {
				$products_recommend = collect($products_recommend->get())->filter(function ($value, $key) use($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();
				$products_recommend = $products_recommend->take(10);
		}
		else {
			$products_recommend = $products_recommend->limit(10)->get();
		}

		if (count($products_recommend)) {
			foreach ($products_recommend as $recommend) {
				//login user like product or not
				$product_is_liked = ProductLikes::where('product_id', $recommend->id)->where('user_id', $id)->count();
				//view product option
				$product_options['name'] = DB::table('products_options')->where('product_id', $recommend->id)->get();
				if (count(@$product_options['name'])) {
					@$product_option_detail = [];
					foreach ($product_options['name'] as $product_option) {						
						@$product_option_detail[] = ['id' => $product_option->id, 'name' => $product_option->option_name];
					}
				} else {
					@$product_option_detail = [];
				}

				$details_product_recommend['id'] = $recommend['id'];
				$details_product_recommend['name'] = $recommend['title'];
				$details_product_recommend['image_url'] = $recommend['image_name'];
				$details_product_recommend['price'] = $recommend['price'];
				$details_product_recommend['seller_name'] = $recommend['user_name'];
				$details_product_recommend['like_count'] = $recommend['like_count'] != null ? (string) $recommend['like_count'] : '0';
				$details_product_recommend['is_liked'] = @$product_is_liked;
				$details_product_recommend['options'] = @$product_option_detail;
				@$product_option_detail = '';
				$detail_product_recommend[] = $details_product_recommend;

			}
		} else {

			$detail_product_recommend = [];
		}

		/* Popular Product Details */
		$products_popular = Product::where('total_quantity', '<>', '0')->where('sold_out', 'No')->where('products.admin_status', 'Approved')->where('status', 'Active')->where('is_popular', 'Yes')->activeUser()->orderBy('id', 'desc');

		if($id != '') {
				$products_popular = collect($products_popular->get())->filter(function ($value, $key) use($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();
				$products_popular = $products_popular->take(10);
		}
		else {
			$products_popular = $products_popular->limit(10)->get();
		}

		if (count($products_popular)) {

			foreach ($products_popular as $popular) {
				//login user like product or not
				$product_is_liked = ProductLikes::where('product_id', $popular->id)->where('user_id', $id)->count();

				//view product option
				$product_options['name'] = DB::table('products_options')->where('product_id', $popular->id)->get();
				if (count(@$product_options['name'])) {
					@$product_option_detail = [];
					foreach ($product_options['name'] as $product_option) {						
						@$product_option_detail[] = ['id' => $product_option->id, 'name' => $product_option->option_name];
					}
				} else {
					@$product_option_detail = [];
				}

				$details_product_popular['id'] = $popular['id'];
				$details_product_popular['name'] = $popular['title'];
				$details_product_popular['image_url'] = $popular['image_name'];
				$details_product_popular['price'] = $popular['price'];
				$details_product_popular['seller_name'] = $popular['user_name'];
				$details_product_popular['like_count'] = $popular['like_count'] != null ? (string) $popular['like_count'] : '0';
				$details_product_popular['is_liked'] = @$product_is_liked;
				$details_product_popular['options'] = @$product_option_detail;
				@$product_option_detail = '';
				$detail_product_popular[] = $details_product_popular;
			}
		} else {
			$detail_product_popular = [];
		}

		/* Onsale Product Details */
		$products_onsale = Product::where('total_quantity', '<>', '0')->where('sold_out', 'No')->where('products.admin_status', 'Approved')->where('status', 'Active')->activeUser()->orderBy('id', 'desc');

		if($id != '') {
				$products_onsale = collect($products_onsale->get())->filter(function ($value, $key) use($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();
				$products_onsale = $products_onsale->take(10);
		}
		else {
			$products_onsale = $products_onsale->limit(10)->get();
		}

		if (count($products_onsale)) {

			foreach ($products_onsale as $onsale) {
				//login user like product or not
				$product_is_liked = ProductLikes::where('product_id', $onsale->id)->where('user_id', $id)->count();

				//view product option
				$product_options['name'] = DB::table('products_options')->where('product_id', $onsale->id)->get();
				if (count(@$product_options['name'])) {
					@$product_option_detail = [];
					foreach ($product_options['name'] as $product_option) {						
						@$product_option_detail[] = ['id' => $product_option->id, 'name' => $product_option->option_name];
					}
				} else {
					@$product_option_detail = [];
				}

				$details_product_onsale['id'] = $onsale['id'];
				$details_product_onsale['name'] = $onsale['title'];
				$details_product_onsale['image_url'] = $onsale['image_name'];
				$details_product_onsale['price'] = $onsale['price'];
				$details_product_onsale['seller_name'] = $onsale['user_name'];
				$details_product_onsale['like_count'] = $onsale['like_count'] != null ? (string) $onsale['like_count'] : '0';
				$details_product_onsale['is_liked'] = @$product_is_liked;
				$details_product_onsale['options'] = @$product_option_detail;
				@$product_option_detail = '';
				$detail_product_onsale[] = $details_product_onsale;
			}
		} else {
			$detail_product_onsale = [];
		}

		/* New Product Details */
		$products_new = Product::where('total_quantity', '<>', '0')->where('sold_out', 'No')->where('products.admin_status', 'Approved')->where('status', 'Active')->activeUser()->orderBy('id', 'desc');

		if($id != '') {
				$products_new = collect($products_new->get())->filter(function ($value, $key) use($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();
				$products_new = $products_new->take(10);
		}
		else {
			$products_new = $products_new->limit(10)->get();
		}

		if (count($products_new)) {

			foreach ($products_new as $new_product) {
				//login user like product or not
				$product_is_liked = ProductLikes::where('product_id', $new_product->id)->where('user_id', $id)->count();

				//view product option
				$product_options['name'] = DB::table('products_options')->where('product_id', $new_product->id)->get();
				if (count(@$product_options['name'])) {
					@$product_option_detail = [];
					foreach ($product_options['name'] as $product_option) {						
						@$product_option_detail[] = ['id' => $product_option->id, 'name' => $product_option->option_name];
					}
				} else {
					@$product_option_detail = [];
				}

				$details_product_new['id'] = $new_product['id'];
				$details_product_new['name'] = $new_product['title'];
				$details_product_new['image_url'] = $new_product['image_name'];
				$details_product_new['price'] = $new_product['price'];
				$details_product_new['seller_name'] = $new_product['user_name'];
				$details_product_new['like_count'] = $new_product['like_count'] != null ? (string) $new_product['like_count'] : '0';
				$details_product_new['is_liked'] = @$product_is_liked;
				$details_product_new['options'] = @$product_option_detail;
				@$product_option_detail = '';
				$detail_product_new[] = $details_product_new;
			}
		} else {
			$detail_product_new = [];
		}

		/*Category details*/
		$category['name'] = Category::where('status', 'Active')->where('featured', 'Yes')->get();
		foreach ($category['name'] as $cate) {
			$category_image = $cate->image_name != '' ? $cate->image_name : url('/') . '/image/new-navigation.png';

			@$product_option_id['id'] = $cate->id;
			@$product_option_id['group_id'] = $cate->parent_id;
			@$product_option_id['name'] = $cate->title;
			@$product_option_id['image_url'] = $category_image;
			// @$product_option_name[] = $opt->option_name;
			$categories[] = $product_option_id;
		}

		return response()->json([

			'status_message' => 'Prodct details Listed Successfully',

			'status_code' => '1',

			'slide_image' => $detail_store,

			'picks' => $details_product_pick,

			'recommendations' => $detail_product_recommend,

			'popular' => $detail_product_popular,

			'onsale' => $detail_product_onsale,

			'new_product' => $detail_product_new,

			'categories' => $categories,

		]);

	}
	/**
	 * View user store details and product details
	 *@param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function store_profile(Request $request) {

		if (isset($request->page)) {

			$rules = array(
				'page' => 'required|integer|min:1',
				'price_from' => 'integer',

				'price_to' => 'integer',
			);
		}
		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'status_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		}
		 if($request->token !=''){
      
         try {   
          
           $user = $user_token = JWTAuth::toUser($request->token);
           $user_id = $user->id;  
           
          }
          catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([

            'success_message' => 'invalid_token',

            'status_code' => '0',

           ]);

          }
        }else{
        $user_id = '';
        }
		if ($request->page != '' && $request->page != '0') {
			if (@$request->store_id != '') {
				$store_id = $request->store_id;
			} else {
				$store = MerchantStore::where('user_id', $user_id)->first();
				if (count($store)) {
					$store_id = $store->id;
				}
			}
			if (@$store_id != '') {

				//store details
				$merchant_store_details = MerchantStore::where('id', $store_id)->first();

				if (count($merchant_store_details)) {

					//is follow user is store

					$is_follow = FollowStore::where('follower_id', $user_id)->where('store_id', $store_id)->count();

					$store_details['store_id'] = $merchant_store_details->id;
					$store_details['store_image'] = $merchant_store_details->logo_img != '' ? $merchant_store_details->logo_img : url('/') . '/image/user_pic.png';
					$store_details['store_cover_image'] = $merchant_store_details->header_img != '' ? $merchant_store_details->header_img : url('/') . '/image/cover_image.jpg';
					$store_details['seller_name'] = $merchant_store_details->merchant_user_details->full_name;
					$store_details['store_name'] = $merchant_store_details->store_name;
					$store_details['store_details'] = $merchant_store_details->description != '' && $merchant_store_details->description != null ? strip_tags($merchant_store_details->description) : "";
					$store_details['is_follow'] = @$is_follow;
					$store_details['is_liked'] = '0';
					$store_detail[] = $store_details;

					//product details

					//Price from

					if (@$request->sort_id == '0') {
						$order_by = 'true';
					}

					$price_from = @$request->price_from != '' ? @$request->price_from : '';

					$price_to = @$request->price_to != '' ? @$request->price_to : '';

					if ($merchant_store_details->user_id == $user_id) {
						$merchant_products = Product::where('status', 'Active');
					} else {
						$merchant_products = Product::where('products.admin_status', 'Approved')->where('total_quantity', '<>', '0')->where('sold_out', 'No')->where('status', 'Active')->activeUser();
					}

					//Popular product
					if (@$request->sort_id == '1') {
						$merchant_products = $merchant_products->where('products.likes_count', '<>', '0')->orderBy('products.likes_count', 'desc');
					}

					if (@$products_where) {
						$merchant_products = $merchant_products->where($products_where);
					}

					$merchant_products = $merchant_products->with(['products_prices_details' => function ($query) {},
						'users',
						'products_like_details'])
						->whereHas('products_prices_details', function ($query) {
						})->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id');
					$currency_rate = Currency::where('code', api_currency_code )->first()->rate;

					if ($price_from != '' && $price_to == '') {
						$product_price = DB::table('products')->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->leftjoin('products_options', 'products_options.product_id', '=', 'products.id')->join('currency', 'currency.code', '=', 'products_prices_details.currency_code')
							->whereRaw('round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * ' . $currency_rate . ')) >= ' . $price_from);
						$product_price1 = $product_price->pluck('products.id');
						$merchant_products = $merchant_products->wherein('products.id', array_unique($product_price1->toArray()));
					} elseif ($price_from != '' && $price_to != '') {
						$product_price = DB::table('products')->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->leftjoin('products_options', 'products_options.product_id', '=', 'products.id')->join('currency', 'currency.code', '=', 'products_prices_details.currency_code')
							->whereRaw('round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * ' . $currency_rate . ')) >= ' . $price_from . ' and round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * ' . $currency_rate . ')) <= ' . $price_to);

						$product_price1 = $product_price->pluck('products.id');
						$merchant_products = $merchant_products->wherein('products.id', array_unique($product_price1->toArray()));
					}

					$merchant_products = $merchant_products->where('user_id', $merchant_store_details->user_id);
					if (@$order_by == 'true') {
						$merchant_products = $merchant_products->orderBy('id', 'DESC');
					}
					//high to low price product
					if ($request->sort_id == 2) {
						$merchant_products = $merchant_products->orderBy('products_prices_details.price', 'DESC');
					}
					// low to high price product
					if ($request->sort_id == 3) {
						$merchant_products = $merchant_products->orderBy('products_prices_details.price', 'ASC');
					}
					$merchant_products = $merchant_products->paginate(10)->toJson();
					$data = array(

						'status_message' => 'Store Details Listed Successfully',

						'status_code' => '1',

					);
					$data_success = json_encode($data);

					$totalcount = json_decode($merchant_products);
					if ($totalcount->total == 0 || empty($totalcount->data)) {
						return response()->json([

							'status_message' => 'Store Details Listed Successfully',

							'status_code' => '1',

							'store_details' => $store_detail,

							'product_details' => [],

						]);
					} else {
						$data_result = json_decode($merchant_products, true);

						$count = count($data_result['data']);
						$result_value = array();
						for ($i = 0; $i < $count; $i++) {
							$data_result = json_decode($merchant_products, true);

							$count = count($data_result['data']);

							// product options details

							$product_options['name'] = ProductOption::where('product_id', $data_result['data'][$i]['id'])->get();
							if ($product_options['name']->count()) {
								$product_option_detail = array();
								foreach ($product_options['name'] as $product_option) {
									@$product_option_detail[] = ['id' => $product_option->id, 'name' => $product_option->option_name, 'available_qty' => $product_option->total_quantity, 'price' => $product_option->price];
								}
							} else {
								@$product_option_detail = [];
							}
							$product_options = @$product_option_detail;
							@$product_option_detail = '';

							$is_liked = ProductLikes::where('user_id', $user_id)->count();
							@$result_value[] = array(

								'id' => $data_result['data'][$i]['id'],

								'name' => $data_result['data'][$i]['title'],

								'image_url' => $data_result['data'][$i]['image_name'],

								'price' => $data_result['data'][$i]['price'],

								'seller_name' => $data_result['data'][$i]['users']['full_name'],

								'like_count' => $data_result['data'][$i]

								['like_count'] != null

								? (string) $data_result['data'][$i]

								['like_count'] : '0',
								'is_liked' => @$is_liked,

								'options' => @$product_options != null ? $product_options : [],

								'available_qty' => $data_result['data'][$i]['total_quantity']
							);

						}
					}
					$result = array(
						'total_page' => $data_result['last_page'],

						'store_details' => $store_detail,

						'product_details' => $result_value,

					);

					$data = json_encode($result);

					return json_encode(array_merge(json_decode($data_success, true), json_decode($data, true)), JSON_UNESCAPED_SLASHES);
				} else {
					return response()->json([

						'status_message' => 'No store detils',

						'status_code' => '0',
					]);
				}
			} else {
				return response()->json([

					'status_message' => 'No store detils',

					'status_code' => '0',
				]);
			}
		} else {
			return response()->json([

				'status_message' => 'Undefind Page No',

				'status_code' => '0',

			]);
		}
	}
	/*
		     * View user liked list
		     *@param  Get method request inputs
		     *
		     * @return Response Json
	*/
	public function liked_user_list(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$products_like = ProductLikes::where('product_id', $request->product_id)->activeUser()->orderBy('id', 'desc')->get();
		if (count($products_like)) {
			foreach ($products_like as $liked_list) {
				$profile_picture = ProfilePicture::where('user_id', $liked_list->user_id)->first();
				@$profile_image = $profile_picture->src;

				$user_details = User::where('id', $liked_list->user_id)->where('status','Active')->first();
				$liked_user['id'] = $liked_list->user_id;
				$liked_user['username'] = $user_details->user_name;
				$liked_user['full_name'] = $user_details->full_name;
				$liked_user['image_url'] = @$profile_image != '' ? @$profile_image : url('/image/profile.png');
				$liked_user['is_follower'] = '0';
				$liked_users[] = @$liked_user;
			}
			return response()->json([

				'status_message' => 'User details listed successfully',

				'status_code' => '1',

				'liked_user_details' => $liked_users,

			]);

		} else {
			return response()->json([

				'status_message' => 'No user liked the product',

				'status_code' => '0',

			]);
		}

	}
	/**
	 *follow and unfollow the store
	 * @param  Get method request inputs
	 * @return Response Json
	 */
	public function follow_store(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$rules = array(
			'store_id' => 'required',
		);
		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'status_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		} else {
			$store_id = $request->store_id;

			//check the store id valid

			$check_store = MerchantStore::where('id', $store_id)->first();

			if (!count($check_store)) {
				return response()->json([

					'status_message' => 'Invalid Store id',

					'status_code' => '0',

				]);
			}
			if ($check_store->user_id == $user_id) {
				return response()->json([

					'status_message' => 'You could not follow your own store ',

					'status_code' => '0',

				]);
			}

			$store = FollowStore::where('follower_id', $user_id)->where('store_id', $store_id)->get();

			if (count($store)) {
				//Unlike follow the product
				$unfollow_store = FollowStore::where('follower_id', $user_id)->where('store_id', $store_id)->first();

				$store_unfollow = FollowStore::find($unfollow_store->id);

				$store_unfollow->delete();

				return response()->json([

					'status_message' => 'Un Follow Store',

					'status_code' => '1',

				]);

			} else {
				//Follow store
				$follow_store = new FollowStore;

				$follow_store->follower_id = $user_id;

				$follow_store->store_id = $store_id;

				$follow_store->save();

				$store = MerchantStore::where('id', $store_id)->first();
				//store activity data in notification table
				$activity_data = new Notifications;
				$activity_data->follower_id = $user_id;
				$activity_data->store_id = $store_id;
				$activity_data->notify_id = $store->user_id;
				$activity_data->user_id = $user_id;
				$activity_data->notification_type = "store_follow";
				$activity_data->notification_message = "following your store";
				$activity_data->save();

				return response()->json([

					'status_message' => 'Following Store',

					'status_code' => '1',

				]);
			}

		}

	}
	/**
	 *follow and unfollow the user
	 * @param  Get method request inputs
	 * @return Response Json
	 */
	public function follow_user(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$rules = array(
			'user_id' => 'required',
		);
		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'status_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		} else {
			$id = $request->user_id;

			//check the user id valid

			$check_user = User::where('id', $id)->first();

			if (!count($check_user)) {
				return response()->json([

					'status_message' => 'Invalid User id',

					'status_code' => '0',

				]);
			}
			if ($id == $user_id) {
				return response()->json([

					'status_message' => 'You could not follow your own store ',

					'status_code' => '0',

				]);
			}

			$follow = Follow::where('follower_id', $user_id)->where('user_id', $id)->get();

			if (count($follow)) {
				//Unlike follow the user
				$unfollow_user = Follow::where('follower_id', $user_id)->where('user_id', $id)->first();

				$user_unfollow = Follow::find($unfollow_user->id);

				$user_unfollow->delete();

				return response()->json([

					'status_message' => 'Un Follow User',

					'status_code' => '1',

				]);

			} else {
				//Follow user
				$follow_user = new Follow;

				$follow_user->follower_id = $user_id;

				$follow_user->user_id = $id;

				$follow_user->save();

				$activity_data = new Notifications;
				$activity_data->follower_id = $user_id;
				$activity_data->user_id = $user_id;
				$activity_data->notify_id = $id;
				$activity_data->notification_type = "user_follow";
				$activity_data->notification_message = "following you";
				$activity_data->save();

				return response()->json([

					'status_message' => 'Following User',

					'status_code' => '1',

				]);
			}

		}

	}

	/**View Currency Details
		      *@param  Get method request inputs
		      *
		      *@return Response Json
	*/
	public function currency(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$currency = Currency::where('status', 'Active')->get();
		if (count(@$currency)) {
			foreach ($currency as $cur) {
				$currency_details[] = $cur->id . ',' . $cur->name . ',' . $cur->original_symbol;
				//dd($cur->symbol);
			}
			return response()->json([

				'status_message' => 'Currency details listed successfully',

				'status_code' => '1',

				'currency_details' => $currency_details,

			]);
		} else {

			return response()->json([

				'status_message' => 'No Currency',

				'status_code' => '0',

			]);
		}

	}

	/**
	 * View wishlist user details
	 *@param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function liked_store_list(Request $request) {
		if (isset($request->page)) {
			$rules = array(
				'page' => 'required|integer|min:1',
			);

		}
		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'status_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		}
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;

		if ($request->page != '' && $request->page != '0') {

			$products = Product::where('status', 'Active')->with(['products_prices_details', 'users', 'products_like_details'])->whereHas('products_like_details', function ($query) use ($user_id) {
				$query->whereRaw('user_id =' . $user_id);
			})->activeUser();

			$products = $products->orderByRaw('RAND(1234)')->paginate(10)->toJson();

			$data = array(

				'status_message' => 'Store Details Listed Successfully',

				'status_code' => '1',

			);

			$data_success = json_encode($data);

			$totalcount = json_decode($products);
			if ($totalcount->total == 0 || empty($totalcount->data)) {
				return response()->json([

					'status_message' => 'No Data Found',

					'status_code' => '0',

				]);
			} else {
				$data_result = json_decode($products, true);

				$count = count($data_result['data']);

				for ($i = 0; $i < $count; $i++) {
					$users[] = $data_result['data'][$i]['user_id'];
				};
				//is follow user is store
				$store_detail = MerchantStore::whereIn('user_id', $users)->get();
				foreach ($store_detail as $store_details) {

					$is_follow = FollowStore::where('follower_id', $user_id)->where('store_id', $store_details->id)->count();

					@$result_value[] = array(

						'id' => $store_details->id,

						'store_name' => $store_details->store_name,

						'store_details' => $store_details->description != '' && $store_details->description != null ? strip_tags($store_details->description) : "",

						'image_url' => $store_details->logo_img != '' ? $store_details->logo_img : url('/') . '/image/user_pic.png',

						'cover_image_url' => $store_details->header_img != '' ? $store_details->header_img : url('/') . '/image/profile.png',

						'seller_name' => $store_details->users->full_name,

						'is_follow' => @$is_follow,

					);

				}

				$result = array(
					'total_page' => $data_result['last_page'],

					'data' => $result_value,
				);

				$data = json_encode($result);

				return json_encode(array_merge(json_decode($data_success, true), json_decode($data, true)), JSON_UNESCAPED_SLASHES);
			}
		} else {
			return response()->json([

				'status_message' => 'Undefind Page No',

				'status_code' => '0',

			]);

		}

	}
	/*view follower ,following,likeduser,liked store list
		     *@param  Get method request inputs
		     *
		     * @return Response Json
	*/
	public function follower_details(Request $request) {
		if (isset($request->page)) {
			$rules = array(
				'page' => 'required|integer|min:1',
				'type' => 'required|in:0,1,2,3',
			);

		}
		if ($request->type == '2') {
			$rules['product_id'] = 'required';
		}
		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'status_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		}

		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $request->user_id != '' ? $request->user_id : $user_token->id;

		if ($request->page != '' && $request->page != '0') {
			//follower details
			if ($request->type == '0') {
				$products = @Follow::with([
                                        'follower_user' => function ($query){
                                            $query->where('users.status','Active');
                                        },
                                        'following_users' => function($query){
                                            $query->where('users.status','Active');
                                        }
                                    ])->whereHas('follower_user',function($query){
                                        $query->where('users.status','Active');
                                    })->whereHas('following_users',function($query){
                                        $query->where('users.status','Active');
                                    })->where('user_id', $user_id);

				$products = $products->orderByRaw('RAND(1234)')->paginate(10)->toJson();

				$data = array(

					'status_message' => 'Follower details Listed Successfully',

					'status_code' => '1',

				);

			}
			//following details
			else if ($request->type == '1') {
				$products = @Follow::with([
                                        'follower_user' => function ($query){
                                            $query->where('users.status','Active');
                                        },
                                        'following_users' => function($query){
                                            $query->where('users.status','Active');
                                        }
                                    ])->whereHas('follower_user',function($query){
                                        $query->where('users.status','Active');
                                    })->whereHas('following_users',function($query){
                                        $query->where('users.status','Active');
                                    })->where('follower_id', $user_id);

				$products = $products->orderByRaw('RAND(1234)')->paginate(10)->toJson();

				$data = array(

					'status_message' => 'Following details Listed Successfully',

					'status_code' => '1',

				);

			} //user liked list
			else if ($request->type == '2') {
				$products = ProductLikes::where('product_id', $request->product_id)->activeUser();

				$products = $products->orderByRaw('RAND(1234)')->paginate(10)->toJson();

				$data = array(

					'status_message' => 'User details listed successfully',

					'status_code' => '1',

				);

			}
			//liked stored list
			else if ($request->type == '3') {

				$products = FollowStore::where('follower_id', $user_id);

				$products = $products->orderByRaw('RAND(1234)')->paginate(10)->toJson();

				$data = array(

					'status_message' => 'Store Details Listed Successfully',

					'status_code' => '1',

				);

			}

			$data_success = json_encode($data);

			$totalcount = json_decode($products);
			if ($totalcount->total == 0 || empty($totalcount->data)) {
				return response()->json([

					'status_message' => 'No Data Found',

					'status_code' => '0',

				]);
			} else {
				$data_result = json_decode($products, true);

				$count = count($data_result['data']);

				//follower user details
				if ($request->type == '0') {
					foreach ($data_result['data'] as $follower_details) {

						//is follow
						$is_follow = Follow::where('follower_id', $user_id)->where('user_id', $follower_details['follower_id'])->count();
						$user = User::where('id', $follower_details['follower_id'])->where('status','Active')->first();
						$profile_picture = ProfilePicture::where('user_id', $user->id)->first();
						@$profile_image = $profile_picture->src;
						//store id
						@$store = MerchantStore::where('user_id', $user->id)->activeUser()->first();
						@$result_value[] = array(
							'id' => $user->id,

							'store_id' => $store->id != '' ? $store->id : "",

							'username' => $user->user_name,

							'full_name' => $user->full_name,

							'image_url' => @$profile_image != '' ? @$profile_image : url('/image/profile.png'),

							'is_follow' => @$is_follow,

						);

					}

				} //following user details
				elseif ($request->type == '1') {
					foreach ($data_result['data'] as $following_details) {
						//is follow
						// $is_follow =Follow::where('user_id',$user_id)->where('follower_id',$following_details->user_id)->count();
						$user = User::where('id', $following_details['user_id'])->where('status','Active')->first();
						$profile_picture = ProfilePicture::where('user_id', $user->id)->first();
						@$profile_image = $profile_picture->src;
						//store id
						@$store = MerchantStore::where('user_id', $user->id)->activeUser()->first();
						@$result_value[] = array(
							'id' => $user->id,

							'store_id' => $store->id != '' ? $store->id : "",

							'username' => $user->user_name,

							'full_name' => $user->full_name,

							'image_url' => @$profile_image != '' ? @$profile_image : url('/image/profile.png'),

							'is_follow' => '1',

						);

					}
				} //liked product user details
				elseif ($request->type == '2') {

					foreach ($data_result['data'] as $liked_list) {
						$is_follow = Follow::where('follower_id', $user_id)->where('user_id', $liked_list['user_id'])->count();

						$profile_picture = ProfilePicture::where('user_id', $liked_list['user_id'])->first();
						@$profile_image = $profile_picture->src;

						$user_details = User::where('id', $liked_list['user_id'])->where('status','Active')->first();

						//store id
						@$store = MerchantStore::where('user_id', $liked_list['user_id'])->activeUser()->first();

						@$result_value[] = array(
							'id' => $liked_list['user_id'],

							'store_id' => $store->id != '' ? $store->id : "",

							'username' => $user_details->user_name,

							'full_name' => $user_details->full_name,

							'image_url' => @$profile_image != '' ? @$profile_image : url('/image/profile.png'),

							'is_follow' => @$is_follow,

						);

					}

				} //liked product stored user details
				elseif ($request->type == '3') {
					// for($i=0;$i<$count;$i++)
					//   {
					//     $users[]=$data_result['data'][$i]['user_id'];
					//     };
					$is_follow_store = FollowStore::where('follower_id', $user_id)->get();
					if (count($is_follow_store)) {
						foreach ($is_follow_store as $is_follow_stores) {
							$merchant_user = MerchantStore::where('id', $is_follow_stores->store_id)->first();
							$users[] = $merchant_user->user_id;
						}
					}

					//is follow user is store
					$user_detail = User::whereIn('id', $users)->where('status','Active')->get();
					foreach ($user_detail as $user_details) {

						$profile_picture = ProfilePicture::where('user_id', $user_details->id)->first();
						@$profile_image = $profile_picture->src;

						$is_follow = Follow::where('follower_id', $user_id)->where('user_id', $user_details->id)->count();

						//store id
						@$store = MerchantStore::where('user_id', $user_details->id)->activeUser()->first();

						// dd($store);

						@$result_value[] = array(

							'id' => @$user_details->id,

							'store_id' => @$store->id != '' ? $store->id : "",

							'username' => @$user_details->user_name,

							'full_name' => @$user_details->full_name,

							'store_name' => @$store->store_name,

							'city' => @$store->user_address[0]->city,

							'country' => @$store->user_address[0]->country,

							'image_url' => @$store->logo_img,

							'is_follow' => 1,

						);

					}
				}

				$result = array(
					'total_page' => $data_result['last_page'],

					'data' => $result_value,
				);

				$data = json_encode($result);

				return json_encode(array_merge(json_decode($data_success, true), json_decode($data, true)), JSON_UNESCAPED_SLASHES);
			}
		} else {
			return response()->json([

				'status_message' => 'Undefind Page No',

				'status_code' => '0',

			]);

		}

	}

	//Block User
	public function block(Request $request)
	{
		$user_token = JWTAuth::parseToken()->authenticate();
      	$user_id    = @$request->user_id !='' ? $request->user_id : $user_token->id;

      	$blocked_user_id = $request->blocked_user_id;

      	$check_block_user = BlockUsers::whereUserId($user_id)->whereBlockedUserId($blocked_user_id)->get();

      	if($check_block_user->count())
      	{
      		BlockUsers::find($check_block_user[0]->id)->forceDelete();

      		return response()->json([
                                'status_message' => 'User has been unblocked successfully',
                                'status_code'     => '1'
                              ]);
      	}

      	$report = new BlockUsers;

      	$report->user_id = $user_id;
      	$report->blocked_user_id = $blocked_user_id;

      	$report->save();

      	return response()->json([
                                'status_message' => 'User has been blocked successfully',
                                'status_code'     => '1'
                              ]);
	}

	//Blocked Users List
	public function blocked_users()
	{
		$user_token = JWTAuth::parseToken()->authenticate();
      	$user_id    = @$request->user_id !='' ? $request->user_id : $user_token->id;
      	
      	$result = BlockUsers::whereUserId($user_id)->get();

      	if($result->count())
      	{
      		foreach($result as $row) {
      			$blocked_array[] = array(
      				'id' => $row->id,
      				'blocked_user_id' => $row->blocked_user_id,
      				'blocked_username' => $row->blocked_users->full_name,
      				'time' => @$row->created_at->diffForHumans()
      			);
      		}

      		return response()->json([
                                'status_message' => 'Blocked Users List',
                                'status_code'     => '1',
                                'data' => $blocked_array
                              ]);
      	}

      	return response()->json([
                                'status_message' => 'No Result',
                                'status_code'     => '0'
                              ]);

	}

}
