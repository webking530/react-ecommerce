<?php

namespace App\Http\Controllers\Api;

use App;
use App\Http\Controllers\Controller;
use App\Models\BlockUsers;
use App\Models\Category;
use App\Models\Currency;
use App\Models\MerchantStore;
use App\Models\Product;
use App\Models\ProductLikes;
use App\Models\ProductOption;
use App\Models\ProfilePicture;
use App\Http\Helper\PaymentHelper;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class SearchController extends Controller {

	protected $PaymentHelper;  // Global variable for instance of Helpers



	public function __construct(PaymentHelper $payment) {

		$this->payment_helper = $payment;
		$this->array_category = array();
		App::setLocale('en');
	}
	/**
	 * View Search
	 *@param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function product_search(Request $request) {

		if (isset($request->page)) {

			$rules = array(

				'page' => 'required|integer|min:1',

				'type' => 'required|in:Featured,ForYou,UserLikedList',

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
		if ($request->token != '') {

			try {

				$user = $user_token = JWTAuth::toUser($request->token);

				$id = $user->id;

			} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

				return response()->json([

					'success_message' => 'invalid_token',

					'status_code' => '0',

				]);

			}
		} else {
			$id = '';
		}

		if ($request->page != '' && $request->page != '0') {
			if ($request->type == 'Featured') {
				$products_where['products.is_featured'] = 'Yes';
			}
			if ($request->type == 'ForYou') {
				$products_where['products.is_recommend'] = 'Yes';
			}
			if ($request->type == 'UserLikedList') {
				$userid = @$request->user_id != '' ? $request->user_id : $id;
				$products = Product::select('*', 'products.id as product_id')->where('status', 'Active')->whereHas('products_like_details', function ($query) use ($userid) {
					$query->whereRaw('user_id =' . $userid);
				})->activeUser();
			} else {
				$products = Product::select('*', 'products.id as product_id')->where('status', 'Active')->where('products.admin_status', 'Approved')->where('total_quantity', '<>', '0')->where('sold_out', 'No')->where(@$products_where)->activeUser();
			}

			$products = $products->with(['products_prices_details', 'users', 'products_like_details'])->orderBy('products.id', 'desc');

			if ($id != '') {
				$blocked_users = BlockUsers::whereUserId($id)->orWhere('blocked_user_id', $id)->get();
				$blocked_user_ids = array_column($blocked_users->toArray(), 'blocked_user_id');
				$user_ids = array_column($blocked_users->toArray(), 'user_id');

				$merged_ids = array_merge($blocked_user_ids, $user_ids);

				$products = collect($products->get())->filter(function ($value, $key) use ($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();
			}

			$products = $products->paginate(10)->toJson();

			$data = array(

				'status_message' => 'Product Details Listed Successfully',

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

				$first_key = key($data_result['data']);

				end($data_result['data']);

				$last_key = key($data_result['data']);

				for ($i = $first_key; $i <= $last_key; $i++) {
					// product options details
					@$product_options = "";
					$options['name'] = ProductOption::where('product_id', $data_result['data'][$i]['product_id'])->get();
					if ($options['name']->count()) {
						$product_option_id = [];
						foreach ($options['name'] as $opt) {
							$product_option_id[] = ['id' => $opt->id, 'name' => $opt->option_name, 'available_qty' => $opt->total_quantity, 'price' => $opt->price];

						}

						$product_options = $product_option_id;
						$product_option_id = '';
					}

					$is_liked = ProductLikes::where('user_id', $id)->where('product_id', $data_result['data'][$i]['product_id'])->count();

					if ($request->type == 'UserLikedList') {

						$user_id = @$request->user_id != '' ? $request->user_id : $id;
						if ($user_id != '') {
							//user product liked list
							$user_liked_list = ProductLikes::where('user_id', $user_id)->get();
							if (count($user_liked_list)) {
								foreach ($user_liked_list as $liked_list_id) {

									if ($data_result['data'][$i]['product_id'] == $liked_list_id->product_id) {
										@$result_value[] = array(

											'id' => $data_result['data'][$i]['product_id'],

											'name' => $data_result['data'][$i]['title'],

											'description' => strip_tags($data_result['data'][$i]['description']),

											'image_url' => $data_result['data'][$i]['image_name'],

											'price' => $data_result['data'][$i]['price'],

											'seller_name' => $data_result['data'][$i]['users']['full_name'],

											'like_count' => $data_result['data'][$i]

											['like_count'] != null

											? (string) $data_result['data'][$i]

											['like_count'] : '0',
											'is_liked' => @$is_liked,

											'options' => @$product_options != "" ? $product_options : [],

											'available_qty' => $data_result['data'][$i]['total_quantity'],

											'is_video' => $data_result['data'][$i]['video_src'] != '' ? 'Yes' : 'No',

											'video_url' => $data_result['data'][$i]['video_src'],

											'video_thumb' => $data_result['data'][$i]['video_thumb'],

										);
									}
								}
							} else {
								return response()->json([

									'status_message' => 'No Data Found',

									'status_code' => '0',

								]);
							}
						}

					} else {

						@$result_value[] = array(

							'id' => $data_result['data'][$i]['product_id'],

							'name' => $data_result['data'][$i]['title'],

							'description' => strip_tags($data_result['data'][$i]['description']),

							'image_url' => $data_result['data'][$i]['image_name'],

							'price' => $data_result['data'][$i]['price'],

							'seller_name' => $data_result['data'][$i]['users']['full_name'],

							'like_count' => $data_result['data'][$i]

							['like_count'] != null

							? (string) $data_result['data'][$i]

							['like_count'] : '0',

							'is_liked' => @$is_liked,

							'options' => @$product_options != "" ? $product_options : [],

							'available_qty' => $data_result['data'][$i]['total_quantity'],

							'is_video' => $data_result['data'][$i]['video_src'] != '' ? 'Yes' : 'No',

							'video_url' => $data_result['data'][$i]['video_src'],

							'video_thumb' => $data_result['data'][$i]['video_thumb'],

						);
					}
				}

				$default_currency = Currency::where('default_currency', 1)->first();

				if ($default_currency) {

					$code = $default_currency->code;
					$symbol = $default_currency->original_symbol;

				} else {

					$default_currency = Currency::whereCode('USD')->first();

					$code = 'USD';
					$symbol = $default_currency->original_symbol;

				}

			$filter['min_value'] = 0;
            $filter['max_value'] = $this->payment_helper->currency_convert('USD', $code ,1000);


				$result = array(

					'total_page' => $data_result['last_page'],

					'data' => $result_value,

					'currency_code' => $code,

					'currency_symbol' => $symbol,

					'filter' =>$filter,
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

	public function getCategorychild($childs) {
		foreach ($childs as $child) {
			array_push($this->array_category, $child->id);
			if (count($child->childs)) {
				$this->getCategorychild($child->childs);
			}
		}
	}

	/**
	 * View Editor Picks
	 *@param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function editor_picks(Request $request) {

		if (isset($request->page)) {

			$rules = array(

				'page' => 'required|integer|min:1',

				'type' => 'required|in:Picks,Recommendation,Categories,New,Popular,OnSale',

				'price_from' => 'integer',

				'price_to' => 'integer',

			);

		}
		if (isset($request->type)) {
			if ($request->type == 'Categories') {
				$rules = array(

					'page' => 'required|integer|min:1',

					'type' => 'required',

					'category_id' => 'required|integer',

					'price_from' => 'integer',

					'price_to' => 'integer',

				);
			}

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
		if ($request->token != '') {

			try {

				$user = $user_token = JWTAuth::toUser($request->token);
				$id = $user->id;

			} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
				return response()->json([

					'success_message' => 'invalid_token',

					'status_code' => '0',

				]);

			}
		} else {
			$id = '';
		}

		if ($request->page != '' && $request->page != '0') {
			//Editor picks based product
			if ($request->type == 'Picks') {
				$products_where['products.is_editor'] = 'Yes';
				$order_by = 'true';
			}
			//Recommendation based product
			if ($request->type == 'Recommendation') {
				$products_where['products.is_recommend'] = 'Yes';
			}

			//Latest product first view
			if ($request->type == 'New' || $request->sort_id == '0') {
				$order_by = 'true';
			}

			//On Sale product
			if ($request->type == 'OnSale') {
				$products_where['products.sold_out'] = 'No';
				$order_by = 'true';
			}

			//Price from

			$price_from = $request->price_from != '' ? $request->price_from : '';

			$price_to = $request->price_to != '' ? $request->price_to : '';

			$products = Product::select('*', 'products.id as product_id')->where('products.admin_status', 'Approved')->where('status', 'Active')->where('total_quantity', '<>', '0')->where('sold_out', 'No');

			//Popular product
			if ($request->type == 'Popular' || $request->sort_id == '1') {
				$products = $products->where('products.likes_count', '<>', '0')->orderBy('products.likes_count', 'DESC');
			}

			if (@$products_where) {
				$products = $products->where($products_where);
			}

			$products = $products->with(['products_prices_details' => function ($query) {},
				'users',
				'products_like_details'])
				->whereHas('products_prices_details', function ($query) {
				})->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->activeUser();

			$currency_rate = Currency::where('code', api_currency_code)->first()->rate;

		

			if ($price_from != '' && $price_to == '') {
				$product_price = DB::table('products')->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->leftjoin('products_options', 'products_options.product_id', '=', 'products.id')->join('currency', 'currency.code', '=', 'products_prices_details.currency_code')
					->whereRaw('round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * ' . $currency_rate . ')) >= ' . $price_from);
				$product_price1 = $product_price->pluck('products.id');
				$products = $products->wherein('products.id', array_unique($product_price1->toArray()));
			} elseif ($price_from != '' && $price_to != '') {
				$product_price = DB::table('products')->join('products_prices_details', 'products_prices_details.product_id', '=', 'products.id')->leftjoin('products_options', 'products_options.product_id', '=', 'products.id')->join('currency', 'currency.code', '=', 'products_prices_details.currency_code')
					->whereRaw('round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * ' . $currency_rate . ')) >= ' . $price_from . ' and round(((IFNULL((select products_options.price from products_options where products.id = products_options.product_id order by id asc limit 1), products_prices_details.price) / currency.rate) * ' . $currency_rate . ')) <= ' . $price_to);

				$product_price1 = $product_price->pluck('products.id');
				$products = $products->wherein('products.id', array_unique($product_price1->toArray()));
			}

			//Category based product
			if ($request->type == 'Categories' || $request->category_id) {

				$categories1 = Category::where("id", $request->category_id)->where('status', 'Active')->get();
				foreach ($categories1 as $category) {
					array_push($this->array_category, $category->id);
					if (count($category->childs)) {
						$this->getCategorychild($category->childs);
					}
				}
				$products = $products->wherein('products.category_id', array_unique($this->array_category));
			}

			if (@$order_by == 'true') {
				$products = $products->orderBy('products.id', 'desc');
			}
			//high to low price product
			if ($request->sort_id == 2) {
				$products = $products->orderBy('products_prices_details.price', 'DESC');
			}
			// low to high price product
			if ($request->sort_id == 3) {
				$products = $products->orderBy('products_prices_details.price', 'ASC');
			}

			if ($id != '') {
				$blocked_users = BlockUsers::whereUserId($id)->orWhere('blocked_user_id', $id)->get();
				$blocked_user_ids = array_column($blocked_users->toArray(), 'blocked_user_id');
				$user_ids = array_column($blocked_users->toArray(), 'user_id');

				$merged_ids = array_merge($blocked_user_ids, $user_ids);

				$products = collect($products->get())->filter(function ($value, $key) use ($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();
			}

			$products = $products->paginate(10)->toJson();
			$data = array(

				'status_message' => 'Product Details Listed Successfully',

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

				$first_key = key($data_result['data']);

				end($data_result['data']);

				$last_key = key($data_result['data']);

				$result_value = array();
				for ($i = $first_key; $i <= $last_key; $i++) {
					$product_price = $data_result['data'][$i]['price'];
					// product options details
					$options['name'] = ProductOption::where('product_id', $data_result['data'][$i]['product_id'])->get();
					$product_options[$i] = array();
					if ($options['name']->count()) {
						$j = 0;
						foreach ($options['name'] as $opt) {
							@$product_options[$j] = ['id' => $opt->id, 'name' => $opt->option_name, 'available_qty' => $opt->total_quantity,
								'price' => $opt->price];
							$j++;
						}
					}
					$is_liked = ProductLikes::where('user_id', $id)->count();

					@$result_value[] = array(

						'id' => $data_result['data'][$i]['product_id'],

						'name' => $data_result['data'][$i]['title'],

						'image_url' => $data_result['data'][$i]['image_name'],

						'price' => $data_result['data'][$i]['price'],

						'seller_name' => $data_result['data'][$i]['users']['full_name'],

						'like_count' => $data_result['data'][$i]

						['like_count'] != null

						? (string) $data_result['data'][$i]

						['like_count'] : '0',
						'is_liked' => @$is_liked,

						'options' => $product_options[$i],

						'available_qty' => $data_result['data'][$i]['total_quantity'],
					);

				}
			}

			$result = array(
				'total_page' => $data_result['last_page'],

				'data' => $result_value,
			);

			$data = json_encode($result);

			return json_encode(array_merge(json_decode($data_success, true), json_decode($data, true)), JSON_UNESCAPED_SLASHES);

		} else {
			return response()->json([

				'status_message' => 'Undefind Page No',

				'status_code' => '0',

			]);

		}

	}

	/**
	 * View Search Keyword
	 *@param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function search(Request $request) {
		if ($request->token != '') {

			try {

				$user = $user_token = JWTAuth::toUser($request->token);
				$id = $user->id;

			} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
				return response()->json([

					'success_message' => 'invalid_token',

					'status_code' => '0',

				]);

			}
		} else {
			$id = '';
		}
		$rules = array(
			'keyword' => 'required',
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

			//search keyword products
			$search_keyword = Product::select('*', 'products.id as product_id')->where('title', 'like', '%' . $request->keyword . '%')->where('products.admin_status', 'Approved')->where('status', 'Active')->where('total_quantity', '<>', '0')->activeUser()->where('sold_out', 'No');

			if ($id != '') {
				$blocked_users = BlockUsers::whereUserId($id)->orWhere('blocked_user_id', $id)->get();
				$blocked_user_ids = array_column($blocked_users->toArray(), 'blocked_user_id');
				$user_ids = array_column($blocked_users->toArray(), 'user_id');

				$merged_ids = array_merge($blocked_user_ids, $user_ids);

				$search_keyword = collect($search_keyword->get())->filter(function ($value, $key) use ($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();

				$search_keyword = $search_keyword->take(5);
			} else {
				$search_keyword = $search_keyword->limit(5)->get();
			}

			if (count($search_keyword)) {
				foreach ($search_keyword as $items) {
					$is_liked = ProductLikes::where('user_id', $id)->where('product_id', $items->product_id)->count();
					$product_detail['id'] = $items->id;
					$product_detail['title'] = $items->title;
					$product_detail['image_url'] = @$items->image_name;
					$product_detail['price'] = $items->products_prices_details->price;
					$product_detail['like_count'] = $items->likes_count;
					$product_detail['is_liked'] = @$is_liked;

					$search[] = $product_detail;
				}
			} else {
				$search = [];
			}
			//user details
			$search_user = User::select('*', 'users.id as users_id')->where('full_name', 'like', '%' . $request->keyword . '%')->where('users.status', '!=', 'Inactive');

			if ($id != '') {
				$search_user = collect($search_user->get())->filter(function ($value, $key) use ($merged_ids) {
					return !in_array($value->users_id, $merged_ids);
				})->values();

				$search_user = $search_user->take(5);
			} else {
				$search_user = $search_user->limit(5)->get();
			}

			if (count($search_user)) {
				foreach ($search_user as $user) {

					$profile_picture = ProfilePicture::where('user_id', $user->users_id)->first();
					@$profile_image = $profile_picture->src;

					$user_details['id'] = $user->users_id;
					$user_details['image_url'] = @$profile_image != '' ? @$profile_image : url('/image/profile.png');
					$user_details['full_name'] = $user->full_name;
					$user_details['username'] = $user->user_name;
					$people[] = $user_details;
				}
			} else {
				$people = [];
			}

			//store details

			$search_store = MerchantStore::select('*', 'merchant_store.id as store_id')->where('store_name', 'like', '%' . $request->keyword . '%')->activeUser();

			if ($id != '') {
				$search_store = collect($search_store->get())->filter(function ($value, $key) use ($merged_ids) {
					return !in_array($value->user_id, $merged_ids);
				})->values();
				$search_store = $search_store->take(5);
			} else {
				$search_store = $search_store->limit(5)->get();
			}

			if (count($search_store)) {
				foreach ($search_store as $stores) {
					$store_details['id'] = $stores->store_id;
					$store_details['image_url'] = $stores->logo_img;
					$store_details['store_name'] = $stores->store_name;
					$store[] = $store_details;
				}
			} else {
				$store = [];
			}

			return response()->json([

				'status_message' => 'Details Listed successfully',

				'status_code' => '1',

				'product' => @$search,

				'people' => @$people,

				'store' => @$store,

			]);
		}

	}
}
