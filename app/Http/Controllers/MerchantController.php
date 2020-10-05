<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Activity;
use App\Models\BillingAddress;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Fees;
use App\Models\MerchantStore;
use App\Models\Notifications;
use App\Models\Orders;
use App\Models\OrdersCancel;
use App\Models\OrdersDetails;
use App\Models\OrdersReturn;
use App\Models\PaymentGateway;
use App\Models\PayoutPreferences;
use App\Models\Payouts;
use App\Models\Product;
use App\Models\ProductClick;
use App\Models\ProductImages;
use App\Models\ProductImagesTemp;
use App\Models\ProductLikes;
use App\Models\ProductOption;
use App\Models\ProductOptionImages;
use App\Models\ProductPrice;
use App\Models\ProductShipping;
use App\Models\ProfilePicture;
use App\Models\ReturnPolicy;
use App\Models\ShippingAddress;
use App\Models\StoreClick;
use App\Models\Timezone;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UsersVerification;
use Auth;
use Carbon;
use Config;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Session;
use Validator;

class MerchantController extends BaseController
{
	protected $helper;

	public function __construct(PaymentHelper $payment)
	{
		$this->helper = new Helpers;
		$this->payment_helper = $payment;
	}

	public function index(Request $request)
	{
		$next_page = 'merchant';
		$prev_url = url()->previous();
		Session::put('ajax_redirect_url', $prev_url);
		$request->session()->put('url.intended', url()->previous());
		return redirect('login?next=merchant');
	}

	public function signup(Request $request)
	{
		$data['country'] = Country::where('status', 'Active')->get();
		$data['category'] = Category::active_all();
		$data['userid'] = Auth::id();
		if(Auth::check() && Auth::user()->type == 'merchant')
			return redirect('/');

		return view('merchant.signup', $data);
	}

	public function dashboard()
	{
		$data['site_name'] = Config::get('site_name');
		$data['store_name'] = Auth::user()->store_name;
		$data['user_status'] = Auth::user()->status;
		$data['user_from'] = Auth::id();
		$data['product_count'] = Product::where('user_id', Auth::id())->count();
		$data['payout_preferences'] = PayoutPreferences::where('user_id', Auth::id())->count();
		$data['order'] = OrdersDetails::where('merchant_id', Auth::id())->where('status', 'Pending')->count();

		$data['notification_feed'] = Notifications::with([
			'orders' => function ($query) {},
			'users' => function ($query) {},
			'notify_id' => function ($query) {
				$query->where('users.status', 'Active');
			},
			'products' => function ($query) {
				$query->activeProduct();
			},
		])
		->whereHas('products', function ($query) {
			$query->activeProduct();
		})
		->whereHas('notify_id', function ($query) {
			$query->where('users.status', 'Active');
		})
		->where('notification_type', '!=', 'add_product')
		->where('notification_type', '!=', 'follow_like')
		->where('notification_type', '!=', 'user_follow')
		->where('notifications.notify_id', Auth::id())
		->orderBy('id', 'desc')
		->get();

		return view('merchant.dashboard', $data);
	}

	public function all_products()
	{
		return view('merchant.all_products');
	}

	public function insights(Request $request)
	{
		$data['range'] = $range = (@$request->range != '') ? @$request->range : '7';
		$data['log_type'] = (@$request->log_type != '') ? @$request->log_type : 'view';
		$data['date_from'] = (@$request->date_from != '') ? @$request->date_from : '';
		$data['date_to'] = (@$request->date_to != '') ? @$request->date_to : '';
		$data['site_name'] = Config::get('site_name');
		return view('merchant.insights', $data);
	}

	//insight_summary
	public function insight_summary(Request $request)
	{
		$data['range'] = $range = (@$request->days != '') ? @$request->days : '7';
		$data['log_type'] = (@$request->log_type != '') ? @$request->log_type : 'view';

		// most click product details
		$clicks = ProductClick::with('products');

		// most click popular details
		$popular = ProductLikes::with('products');

		// most click store details
		$store = StoreClick::with('store');

		// most order details
		$order = OrdersDetails::with('products');

		$sales = OrdersDetails::with('products')->where('status', 'Completed');

		// get login user product details

		$clicks = $clicks->WhereHas('products', function ($query1) {
			$query1->where(['products.user_id' => Auth::id()]);
		});

		$popular = $popular->WhereHas('products', function ($query1) {
			$query1->where(['products.user_id' => Auth::id()]);
		});

		$store = $store->WhereHas('store', function ($query1) {
			$query1->where('user_id', Auth::id());
		});

		$order = $order->WhereHas('products', function ($query1) {
			$query1->where(['products.user_id' => Auth::id()]);
		});
		$sales = $sales->WhereHas('products', function ($query1) {
			$query1->where(['products.user_id' => Auth::id()]);
		});

		// end login user product details
		$data['today'] = date('M d Y '); //today date

		// filter the days or month
		if ($range == '12') {
			// chart clicks data
			$data['views'] = $clicks->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>', Carbon::now()->subMonths($range))->get()->groupBy('date');
			// chart popular data
			$data['likes'] = $popular->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>', Carbon::now()->subMonths($range))->get()->groupBy('date');
			// chart orders data
			$data['order'] = $order->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>', Carbon::now()->subMonths($range))->get()->groupBy('date');
			// chart sales data
			$data['sales'] = $sales->select(DB::raw('DATE(updated_at) as date'), 'price', 'quantity', 'order_id')->where('updated_at', '>', Carbon::now()->subMonths($range))->get()->groupBy('date');

			//most click product details
			$data['clicks'] = $clicks->select(DB::raw('product_id'), 'created_at')->where('created_at', '>', Carbon::now()->subMonths($range))->get()->groupBy('product_id')->toJson();
			//most like product details
			$data['popular'] = $popular->select(DB::raw('product_id'), 'created_at')->where('created_at', '>', Carbon::now()->subMonths($range))->get()->groupBy('product_id')->toJson();
			//most click store details
			$data['store'] = $store->select(DB::raw('store_id'), 'created_at')->where('created_at', '>', Carbon::now()->subMonths($range))->get()->groupBy('store_id')->toJson();

			$data['startdate'] = date('M d Y ', strtotime('-' . $range . ' months'));

		} else if ($range == 'specific') {
			$start_date = date('Y-m-d', strtotime($request->date_from));
			$end_date = date('Y-m-d ', strtotime($request->date_to));

			// chart clicks data
			$data['views'] = $clicks->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get()->groupBy('date');
			// chart popular data
			$data['likes'] = $popular->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get()->groupBy('date');
			// chart orders data

			$data['order'] = $order->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get()->groupBy('date');
			// chart sales data
			$data['sales'] = $sales->select(DB::raw('DATE(updated_at) as date'), 'price', 'quantity', 'order_id')->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $end_date)->get()->groupBy('date');
			//most click product details
			$data['clicks'] = $clicks->select(DB::raw('product_id'), 'created_at')->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get()->groupBy('product_id')->toJson();
			//most like product details
			$data['popular'] = $popular->select(DB::raw('product_id'), 'created_at')->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get()->groupBy('product_id')->toJson();
			//most click store details
			$data['store'] = $store->select(DB::raw('store_id'), 'created_at')->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get()->groupBy('store_id')->toJson();

			$data['startdate'] = date('M d Y ', strtotime($start_date));
			$data['today'] = date('M d Y', strtotime($end_date));
		} else if ($range == '1') {
			// chart clicks data
			$data['views'] = $clicks->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>=', Carbon::today())->get()->groupBy('date');
			// chart popular data
			$data['likes'] = $popular->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>=', Carbon::today())->get()->groupBy('date');
			// chart orders data
			$data['order'] = $order->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>=', Carbon::today())->get()->groupBy('date');
			// chart sales data
			$data['sales'] = $sales->select(DB::raw('DATE(updated_at) as date'), 'price', 'quantity', 'order_id')->where('updated_at', '>=', Carbon::today())->get()->groupBy('date');

			$data['startdate'] = date('M d Y');
		} else {
			// chart clicks data
			$data['views'] = $clicks->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>', Carbon::now()->subDays($range))->get()->groupBy('date');
			// chart popular data
			$data['likes'] = $popular->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>', Carbon::now()->subDays($range))->get()->groupBy('date');
			// chart orders data
			$data['order'] = $order->select(DB::raw('DATE(created_at) as date'))->where('created_at', '>', Carbon::now()->subDays($range))->get()->groupBy('date');
			// chart sales data DB::raw('Sum(orders_details.price*orders_details.quantity)
			$data['sales'] = $sales->select(DB::raw('DATE(updated_at) as date'), 'price', 'quantity', 'order_id')->where('updated_at', '>', Carbon::now()->subDays($range))->get()->groupBy('date');
			//most click product details
			$data['clicks'] = $clicks->select(DB::raw('product_id'), 'created_at')->where('created_at', '>', Carbon::now()->subDays($range))
				->get()->groupBy('product_id')->toJson();
			//most like product details
			$data['popular'] = $popular->select(DB::raw('product_id'), 'created_at')->where('created_at', '>', Carbon::now()->subDays($range))->get()->groupBy('product_id')->toJson();
			//most click store details
			$data['store'] = $store->select(DB::raw('store_id'), 'created_at')->where('created_at', '>', Carbon::now()->subDays($range))->get()->groupBy('store_id')->toJson();

			$data['startdate'] = date('M d Y', strtotime('-' . $range . ' days'));
		}
		// chart data details

		$data['chart'] = [];
		$data['total_views'] = 0;
		$data['total_likes'] = 0;
		$data['total_order'] = 0;
		$data['total_amount'] = 0;

		$result['clicks'] = [];
		$result['popular'] = [];
		$result['store'] = [];

		foreach ($data['views'] as $key => $views) {
			$data['chart_views'][] = array(strtotime($views[0]['date']) * 1000, $views->count());
			$data['total_views'] = $data['total_views'] + $views->count();
		}

		foreach ($data['likes'] as $key => $likes) {
			$data['chart_likes'][] = array(strtotime($likes[0]['date']) * 1000, $likes->count());
			$data['total_likes'] = $data['total_likes'] + $likes->count();
		}

		foreach ($data['order'] as $key => $order) {
			$data['chart_order'][] = array(strtotime($order[0]['date']) * 1000, $order->count());
			$data['total_order'] = $data['total_order'] + $order->count();
		}

		foreach ($data['sales'] as $key => $sales) {
			$sales_price = 0;

			foreach ($sales as $sales_key => $sales_value) {
				$sales_price = $sales_price + $sales_value['price'] * $sales_value['quantity'];
			}

			$data['chart_sales'][] = array(strtotime($sales[0]['date']) * 1000, $sales_price);
			$data['total_amount'] = number_format($data['total_amount'] + $sales_price, 2);
		}

		if ($data['log_type'] == 'view') {
			$data['chart'] = @$data['chart_views'];
		} else if ($data['log_type'] == 'likes') {
			$data['chart'] = @$data['chart_likes'];
		} else if ($data['log_type'] == 'orders') {
			$data['chart'] = @$data['chart_order'];
		} else if ($data['log_type'] == 'sales') {
			$data['chart'] = @$data['chart_sales'];
		}

		$result['log_type'] = @$data['log_type'];
		$result['range'] = @$data['range'];

		$result['today'] = @$data['today'];
		$result['startdate'] = @$data['startdate'];

		$data['clicks'] = json_decode(@$data['clicks'], true);
		$data['popular'] = json_decode(@$data['popular'], true);
		$data['store'] = json_decode(@$data['store'], true);

		$result['clicks'] = @$data['clicks'];
		$result['popular'] = @$data['popular'];
		$result['store'] = @$data['store'];

		$result['chart'] = @$data['chart'];

		$result['total_views'] = @$data['total_views'];
		$result['total_likes'] = @$data['total_likes'];
		$result['total_order'] = @$data['total_order'];
		$result['total_amount'] = @$data['total_amount'];

		echo json_encode($result);

	}

	/**
	 * Create a new Email signup merchant user
	 *
	 * @param array $request    Post method inputs
	 * @return redirect     to dashboard page
	 */
	public function create(Request $request, EmailController $email_controller) {

		// Email signup validation rules
		if (@$request->user_id) {
			$rules = array(
				'store_name' => 'required|max:255',
				'full_name' => 'required',
				'address_line' => 'required',
				'city' => 'required',
				'postal_code' => 'required',
				'state' => 'required',
				'country' => 'required',
				'phone_number' => 'required',
			);

			// Add Admin User Validation Custom Names
			$niceNames = array(
				'store_name' => 'Store Name',
				'full_name' => 'Full Name',
				'address_line' => 'Address Line',
				'city' => 'City',
				'postal_code' => 'Postal Code',
				'state' => 'State',
				'country' => 'Country',
				'phone_number' => 'Pnone Number',
			);
		} else {
			$rules = array(
				'store_name' => 'required|max:255',
				'user_name' => 'required|max:255|unique:users,user_name',
				'email' => 'required|max:255|email|unique:users',
				'password' => 'required|min:6',
				'address_line' => 'required',
				'city' => 'required',
				'postal_code' => 'required',
				'state' => 'required',
				'country' => 'required',
				'phone_number' => 'required',
			);

			// Add Admin User Validation Custom Names
			$niceNames = array(
				'store_name' => trans('messages.merchant.store_name'),
				'username' => trans('messages.merchant.user_name'),
				'email' => trans('messages.merchant.email_address'),
				'password' => trans('messages.merchant.password'),
				'address_line' => trans('messages.merchant.address'),
				'city' => trans('messages.merchant.city'),
				'postal_code' => trans('messages.merchant.postal_code'),
				'state' => trans('messages.merchant.state'),
				'country' => trans('messages.merchant.country'),
				'phone_number' => trans('messages.merchant.phone_no'),
			);
		}

		$validator = Validator::make($request->all(), $rules);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput()->with('error_code', 1); // Form calling with Errors and Input values
		} else {

			if (@$request->user_id) {
				$user = User::find(@$request->user_id);
			} else {
				$user = new User;
				$user->user_name = $request->user_name;
				$user->email = $request->email;
				$user->password = bcrypt($request->password);
			}

			$ip = getenv("REMOTE_ADDR");
			$get_timezone = @file_get_contents('https://timezoneapi.io/api/ip/?' . $ip);
			$timezone = json_decode($get_timezone, true);
			$timezone = $timezone['data']['timezone']['id'];
			if (!$timezone) {
				$timezone = 'UTC';
			}

			$user->timezone = $timezone;
			$user->store_name = $request->store_name;
			$user->full_name = $request->full_name;
			$user->type = 'merchant';

			if (@$request->already_selling != '') {
				$user->already_selling = $request->already_selling;
			}
			if (@$request->product_categories != '') {
				$product_categories = implode(",", $request->product_categories);
				$user->product_categories = $product_categories;
			}

			$user->save();

			$already = UsersVerification::where('user_id', $user->id)->count();
			$check_profile = ProfilePicture::where('user_id', $user->id)->count();
			$user_pic['user_id'] = $user->id;
			$user_pic['photo_source'] = 'Local';
			if ($check_profile == 0) {
				ProfilePicture::insert($user_pic);
			}
			if ($already == 0) {

				$user_verification = new UsersVerification;

				$user_verification->user_id = $user->id;

				$user_verification->save(); // Create a users verification record

				$email_controller->welcome_email_confirmation($user);}

			$user_address = new UserAddress;

			$user_address->user_id = $user->id;
			$user_address->address_line = $request->address_line;
			$user_address->address_line2 = $request->address_line2;
			$user_address->city = $request->city;
			$user_address->postal_code = $request->postal_code;
			$user_address->state = $request->state;
			$user_address->country = $request->country;
			$user_address->phone_number = $request->phone_number;

			$user_address->save();

			$user_merchant = new MerchantStore;
			$user_merchant->store_name = $request->store_name;
			$user_merchant->user_id = $user->id;
			$user_merchant->save();

			if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
				Session::forget('error_code');
				$this->helper->flash_message('success', trans('messages.login.reg_successfully'));
				return redirect('merchant/dashboard'); // Redirect to dashboard page
			} else if (@$request->user_id) {
				Session::forget('error_code');
				$this->helper->flash_message('success', trans('messages.login.reg_successfully'));
				return redirect('merchant/dashboard'); // Redirect to dashboard page
			} else {
				$this->helper->flash_message('danger', trans('messages.login.reg_failed')); // Call flash message function
				return redirect('merchant/signup'); // Redirect to sign page
			}
		}
	}
	public function add_product() {
		$categories = Category::parent_categories();
		$data['return_policy'] = ReturnPolicy::orderBy('days')->get();
		$data['exchange_policy'] = ReturnPolicy::orderBy('days')->get();
		$data['return_policy_value'] = $data['return_policy']->first()->id;
		$data['country'] = Country::where('status', 'Active')->pluck('long_name', 'long_name');
		$data['tmp_product_id'] = $data['product_id'] = rand();
		$data['result'] = array();
		$data['product_currency'] = Session::get('currency');
		$data['product_symbol'] = Currency::original_symbol($data['product_currency']);
		$data['minimum_amount'] = $this->payment_helper->currency_convert('USD', $data['product_currency'], 1);
		foreach ($categories as $row) {
			$data['id'] = $row->id;
			$data['name'] = $row->title;
			$data['list'] = $this->get_child_categories($row->id);
			$final_data[] = $data;
		}
		$data['categories'] = @$final_data;
		return view('merchant.add_product', $data);
	}

	public function edit_product(Request $request) {
		$categories = Category::parent_categories();
		$data['return_policy'] = ReturnPolicy::orderBy('days')->get();
		$data['exchange_policy'] = ReturnPolicy::orderBy('days')->get();
		$data['return_policy_value'] = $data['return_policy']->first()->id;
		$data['country'] = Country::where('status', 'Active')->pluck('long_name', 'long_name');
		$data['product_id'] = $request->id;
		$data['tmp_product_id'] = rand();
		$data['result'] = Product::where('id', $request->id)->first();
		if (!empty($data['result'])) {
			$data['product_currency'] = @ProductPrice::where('product_id', $request->id)->first()->original_currency_code;
			$data['product_symbol'] = Currency::original_symbol($data['product_currency']);
			$data['minimum_amount'] = $this->payment_helper->currency_convert('USD', $data['product_currency'], 1);
			foreach ($categories as $row) {
				$data['id'] = $row->id;
				$data['name'] = $row->title;
				$data['list'] = $this->get_child_categories($row->id);
				$final_data[] = $data;
			}

			$data['categories'] = @$final_data;
			return view('merchant.add_product', $data);
		} else {
			abort('404');
		}
	}

	public function get_product(Request $request) {
		$products = Product::with([
			'products_prices_details' => function ($query) {
				$query->with('currency');
			},
			'products_images' => function ($query) {},
			'product_photos' => function ($query) {},
			'products_shipping' => function ($query) {},
			'product_option' => function ($query) {
				$query->with(['product_option_images']);
			},
		]);
		$products = $products->where('products.id', $request->id)->first();
		return $products;
	}

	public function product_add(Request $request) {
		$float_value = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";
		//for product table
		$messages = [
			'title.required' => 'Title',
			'ships_to.required' => 'Ships To',
			'description.required' => 'Description',
			'category_id.required' => 'Category',
			'price.required' => 'Price',
			'default_currency' => 'Default Currency',
		];
		$validator = Validator::make($request->all(), [
			'title' => 'required',
			'description' => 'required',
			'category_id' => 'required',
			'price' => 'required',
			'ships_to' => 'required',
			'default_currency' => 'exists:currency,code,status,Active',
		], $messages);

		if ($validator->fails()) {
			if ($request->update_type == "edit_product") {
				return redirect('merchant/edit_product/' . $request->product_id)->withErrors($validator)->withInput();
			} else {
				return redirect('merchant/add_product')->withErrors($validator)->withInput();
			}

		}

//dd($request->all());
		if ($request->update_type == "edit_product") {

			if ($request->delete_video_update != '') {
				//dd("ki");
				Product::where('id', $request->product_id)->update(['video_mp4' => '', 'video_webm' => '']);
				if ($request->add_product_video == '') {
					ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_mp4')->delete();
					ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_webm')->delete();
					ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_thumb')->delete();
				}

			}
			//dd("ki11");
			$check_product = Product::where('id', $request->product_id)->first();

			if (empty($check_product)) {

				return redirect('merchant/all_products');

			}

		}

		if ($request->update_type == "edit_product") {
			if ($request->delete_product_id != '') {
				$product_img = explode(',', $request->delete_product_id);
				//dd($product_img);
				foreach ($product_img as $k => $v) {
					if ($v != '') {

						$photos = ProductImages::where('id', $v)->where('product_id', $request->product_id);

						if ($photos != NULL) {
							$photos->delete();
						}

					}

				}

			}
		}

		$products['user_id'] = $request->user_id;
		$products['title'] = html_entity_decode($request->title);
		$products['description'] = html_entity_decode($request->description);
		$products['category_id'] = $request->category_id;
		$products['category_path'] = $request->category_path;
		$products['total_quantity'] = $request->total_quantity;
		$products['return_policy'] = $request->return_policy;
		if ($request->use_exchange) {
			$products['exchange_policy'] = $request->return_policy;
		} else {
			$products['exchange_policy'] = $request->exchange_policy;
		}

		$products['policy_description'] = $request->return_exchange_policy_description;
		$products['sold'] = ($request->sold != "") ? $request->sold : 0;
		$products['views_count'] = 0;
		if ($request->update_type == "edit_product") {
			Product::where("id", $request->product_id)->update($products);
		} else {
			$products['created_at'] = date('Y-m-d H:i:s');

			$product_id = Product::insertGetId($products);
		}

		if ($request->update_type == "edit_product") {
			$product_id = $request->product_id;
		} else {
			$product_id = $product_id;
		}

		$update_d['status'] = $request->status;
		$update_d['sold_out'] = $request->sold_out;
		$update_d['cash_on_delivery'] = $request->cash_on_delivery;
		$update_d['cash_on_store'] = $request->cash_on_store;

		Product::where('id', $product_id)->update($update_d);

		//for product price table
		if ($request->check_sale) {
			$product_prices['discount'] = ($request->discount != "" ? $request->discount : NULL);
			$product_prices['retail_price'] = ($request->retail_price != "" ? $request->retail_price : NULL);
		} else {
			$product_prices['discount'] = NULL;
			$product_prices['retail_price'] = NULL;
		}
		$product_prices['product_id'] = $product_id;
		$product_prices['price'] = round($request->price, 2);
		$product_prices['sku'] = $request->sku_stock;
		$product_prices['length'] = ($request->length != "" ? round($request->length, 2) : NULL);
		$product_prices['height'] = ($request->height != "" ? round($request->height, 2) : NULL);
		$product_prices['width'] = ($request->width != "" ? round($request->width, 2) : NULL);
		$product_prices['weight'] = ($request->weight != "" ? round($request->weight, 2) : NULL);
		$product_prices['currency_code'] = $request->default_currency;

		if ($request->update_type == "edit_product") {
			$product_price = ProductPrice::where('product_id', $product_id)->update($product_prices);
		} else {
			$product_price = ProductPrice::create($product_prices);
		}

		//for product shipping table
		$product_shippings['shipping_type'] = $request->shipping_type;
		$product_shippings['ships_from'] = $request->ships_from;
		$product_shippings['manufacture_country'] = $request->manufacture_country;
		$product_shippings['product_id'] = $product_id;
		$product_sh = ProductShipping::whereNotIn('ships_to', $request->ships_to)->where('product_id', $product_id);
		if ($product_sh->count() > 0) {
			foreach ($product_sh->get() as $value) {
				ProductShipping::where('id', $value->id)->delete();
			}
		}
		for ($i = 0; $i < count($request->ships_to); $i++) {
			$product_shippings['ships_to'] = $request->ships_to[$i];
			$product_shippings['start_window'] = $request->expected_delivery_day_1[$i];
			$product_shippings['end_window'] = $request->expected_delivery_day_2[$i];
			if ($request->shipping_type != "Free Shipping") {
				$product_shippings['charge'] = round($request->custom_charge_domestic[$i], 2);
				$product_shippings['incremental_fee'] = ($request->custom_incremental_domestic[$i] != "" ? round($request->custom_incremental_domestic[$i], 2) : NULL);
			}
			$check_shipping = ProductShipping::where('product_id', $product_id)->where('ships_to', $request->ships_to[$i]);
			if ($check_shipping->count()) {
				$product_shipping = ProductShipping::where('product_id', $product_id)->where('ships_to', $request->ships_to[$i])->update($product_shippings);
			} else {
				$product_shipping = ProductShipping::create($product_shippings);
			}

		}

		if ($request->update_type == "add_product") {

			$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id;
			$oldfilename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id;
			if (!file_exists($filename)) {
				mkdir($filename, 0777, true);
			}
			$product_image_temps = ProductImagesTemp::where('product_id', $request->product_id)->where('option', NULL)->get();
			foreach ($product_image_temps as $product_image_temp) {
				$update_image['product_id'] = $product_id;
				$update_image['image_name'] = $product_image_temp->image_name;
				ProductImages::create($update_image);

			 $old=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->product_id.'/'.$product_image_temp->image_name;
                
                $new=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/'.$product_image_temp->image_name;
                $old_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->product_id.'/';
                $new_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/';
                
                
                if(UPLOAD_DRIVER !='cloudinary')
                {
                 
                    File::move($old,$new); // keep the same folder to just rename 

                    $ext = substr( $old, strrpos( $old, "."));

                    $old_compress = basename($old, $ext). "_compress" . $ext;
                    $old_home_full = basename($old, $ext). "_home_full" . $ext;
                    $old_home_half = basename($old, $ext). "_home_half" . $ext;
                    $old_popular = basename($old, $ext). "_popular" . $ext;
                    $old_header = basename($old, $ext). "_header" . $ext;

                    $ext = substr( $new, strrpos( $new, "."));
                    $new_compress = basename($new, $ext). "_compress" . $ext;
                    $new_home_full = basename($new, $ext). "_home_full" . $ext;
                    $new_home_half = basename($new, $ext). "_home_half" . $ext;
                    $new_popular = basename($new, $ext). "_popular" . $ext;
                    $new_header = basename($new, $ext). "_header" . $ext;

                    File::move($old_dir.$old_compress,$new_dir.$new_compress); // keep the same folder to just rename 
                    File::move($old_dir.$old_home_full,$new_dir.$new_home_full); // keep the same folder to just rename 
                    File::move($old_dir.$old_home_half,$new_dir.$new_home_half); // keep the same folder to just rename 
                    File::move($old_dir.$old_popular,$new_dir.$new_popular); // keep the same folder to just rename 
                    File::move($old_dir.$old_header,$new_dir.$new_header); // keep the same folder to just rename 


                }
            }

			$product_video_mp4_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_mp4')->first();
			$product_video_webm_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_webm')->first();
			$product_video_thumb_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_thumb')->first();
			if ($product_video_mp4_temp) {
				Product::where('id', $product_id)->update(['video_mp4' => $product_video_mp4_temp->image_name, 'video_webm' => $product_video_webm_temp->image_name, 'video_thumb' => $product_video_thumb_temp->image_name]);
				$old = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_mp4_temp->image_name;
				$new = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_mp4_temp->image_name;
				$old_webm = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_webm_temp->image_name;
				$new_webm = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_webm_temp->image_name;
				$old_thumb = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_thumb_temp->image_name;
				$new_thumb = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_thumb_temp->image_name;
				if (UPLOAD_DRIVER != 'cloudinary') {
					File::move($old, $new); // keep the same folder to just rename
					File::move($old_webm, $new_webm); // keep the same folder to just rename
					File::move($old_thumb, $new_thumb); // keep the same folder to just rename
				}
			}

		}

		//Edit Product upload video

		if ($request->update_type == "edit_product") {

			$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id;
			$oldfilename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->tmp_product_id;
			if (!file_exists($filename)) {
				mkdir($filename, 0777, true);
			}
			$product_image_temps = ProductImagesTemp::where('product_id', $request->tmp_product_id)->where('option', NULL)->get();
			foreach ($product_image_temps as $product_image_temp) {
				$update_image['product_id'] = $product_id;
				$update_image['image_name'] = $product_image_temp->image_name;
				ProductImages::create($update_image);

			
			 $old=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->tmp_product_id.'/'.$product_image_temp->image_name;
                
                $new=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/'.$product_image_temp->image_name;
                $old_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->tmp_product_id.'/';
                $new_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/';
                
                
                if(UPLOAD_DRIVER !='cloudinary')
                {
                 
                    File::move($old,$new); // keep the same folder to just rename 

                    $ext = substr( $old, strrpos( $old, "."));

                    $old_compress = basename($old, $ext). "_compress" . $ext;
                    $old_home_full = basename($old, $ext). "_home_full" . $ext;
                    $old_home_half = basename($old, $ext). "_home_half" . $ext;
                    $old_popular = basename($old, $ext). "_popular" . $ext;
                    $old_header = basename($old, $ext). "_header" . $ext;

                    $ext = substr( $new, strrpos( $new, "."));
                    $new_compress = basename($new, $ext). "_compress" . $ext;
                    $new_home_full = basename($new, $ext). "_home_full" . $ext;
                    $new_home_half = basename($new, $ext). "_home_half" . $ext;
                    $new_popular = basename($new, $ext). "_popular" . $ext;
                    $new_header = basename($new, $ext). "_header" . $ext;

                    File::move($old_dir.$old_compress,$new_dir.$new_compress); // keep the same folder to just rename 
                    File::move($old_dir.$old_home_full,$new_dir.$new_home_full); // keep the same folder to just rename 
                    File::move($old_dir.$old_home_half,$new_dir.$new_home_half); // keep the same folder to just rename 
                    File::move($old_dir.$old_popular,$new_dir.$new_popular); // keep the same folder to just rename 
                    File::move($old_dir.$old_header,$new_dir.$new_header); // keep the same folder to just rename 


                }
            }


			$product_video_mp4_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_mp4')->first();
			$product_video_webm_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_webm')->first();
			$product_video_thumb_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_thumb')->first();
			if ($product_video_mp4_temp) {
				Product::where('id', $product_id)->update(['video_mp4' => $product_video_mp4_temp->image_name, 'video_webm' => $product_video_webm_temp->image_name, 'video_thumb' => $product_video_thumb_temp->image_name]);
				$old = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_mp4_temp->image_name;
				$new = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_mp4_temp->image_name;
				$old_webm = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_webm_temp->image_name;
				$new_webm = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_webm_temp->image_name;
				$old_thumb = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_thumb_temp->image_name;
				$new_thumb = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_thumb_temp->image_name;
				if (UPLOAD_DRIVER != 'cloudinary') {
					File::move($old, $new); // keep the same folder to just rename
					File::move($old_webm, $new_webm); // keep the same folder to just rename
					File::move($old_thumb, $new_thumb); // keep the same folder to just rename
				}
			}

		}

		//for product option table

		if ($request->product_option) {
			$product_op = ProductOption::whereNotIn('option_name', $request->product_option)->where('product_id', $product_id);
			if ($product_op->count() > 0) {
				foreach ($product_op->get() as $value) {
					ProductOptionImages::where('product_id', $product_id)->where('product_option_id', $value->id)->delete();
					ProductImagesTemp::where('product_id', $product_id)->where('option', $value->id)->delete();
					ProductOption::where('id', $value->id)->delete();
					Cart::where('option_id', $value->id)->delete();
				}
			}
			$product_quantity['total_quantity'] = 0;
			for ($i = 0; $i < count($request->product_option); $i++) {
				$product_options['product_id'] = $product_id;
				$product_options['sku'] = $request->product_option_sku[$i];
				$product_options['option_name'] = $request->product_option[$i];
				$product_options['total_quantity'] = ($request->product_option_qty[$i] != "" ? $request->product_option_qty[$i] : NULL);
				$product_quantity['total_quantity'] += $request->product_option_qty[$i];
				$product_options['price'] = ($request->product_option_price[$i] != "" ? $request->product_option_price[$i] : $request->price);
				$product_options['sold'] = ($request->product_option_sold[$i] != "" ? $request->product_option_sold[$i] : 0);
				$product_options['currency_code'] = $request->default_currency;

				if (isset($request->product_option_check_sale[$i])) {

					$product_options['retail_price'] = ($request->product_option_retail_price[$i] != "" ? $request->product_option_retail_price[$i] : NULL);
					$product_options['discount'] = ($request->product_option_discount[$i] != "" ? $request->product_option_discount[$i] : NULL);
				} else {
					$product_options['retail_price'] = "0";
					$product_options['discount'] = "0";
				}

				$product_options['length'] = ($request->product_option_length[$i] != "" ? $request->product_option_length[$i] : NULL);
				$product_options['width'] = ($request->product_option_width[$i] != "" ? $request->product_option_width[$i] : NULL);
				$product_options['height'] = ($request->product_option_height[$i] != "" ? $request->product_option_height[$i] : NULL);
				$product_options['weight'] = ($request->product_option_weight[$i] != "" ? $request->product_option_weight[$i] : NULL);
				if ($request->product_option_soldout) {
					$option_soldout = $request->product_option_soldout;
				} else {
					$option_soldout = array();
				}

				if (in_array($i, $option_soldout)) {
					$product_options['sold_out'] = "Yes";
				} else {
					$product_options['sold_out'] = "No";
				}
				$check_option = ProductOption::where('product_id', $product_id)->where('option_name', $request->product_option[$i]);
				if ($check_option->count()) {
					$product_option = ProductOption::where('product_id', $product_id)->where('option_name', $request->product_option[$i])->update($product_options);
				} else {
					$product_option = ProductOption::create($product_options);

					$option_filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/options/' . $product_option->id;
					$option_oldfilename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/options/' . $request->product_option_id[$i];
					if (!file_exists($option_filename)) {
						mkdir($option_filename, 0777, true);
					}
					$product_option_image_temps = ProductImagesTemp::where('product_id', $product_id)->where('option', $request->product_option_id[$i])->get();
					foreach ($product_option_image_temps as $product_option_image_temp) {
						$update_option_image['product_id'] = $product_id;
						$update_option_image['product_option_id'] = $product_option->id;
						$update_option_image['image_name'] = $product_option_image_temp->image_name;
						ProductOptionImages::create($update_option_image);

						$old = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/options/' . $request->product_option_id[$i] . '/' . $product_option_image_temp->image_name;
						$new = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/options/' . $product_option->id . '/' . $product_option_image_temp->image_name;
						File::move($old, $new); // keep the same folder to just rename
					}
				}
			}
			Product::where('id', $product_id)->update($product_quantity);

			if ($request->update_type != "edit_product") {
				File::deleteDirectory($option_oldfilename);
			}
		} else {
			$product_op = ProductOption::where('product_id', $product_id);
			if ($product_op->count() > 0) {
				foreach ($product_op->get() as $value) {
					ProductOptionImages::where('product_id', $product_id)->where('product_option_id', $value->id)->delete();
					ProductImagesTemp::where('product_id', $product_id)->where('option', $value->id)->delete();
					ProductOption::where('id', $value->id)->delete();
					Cart::where('option_id', $value->id)->delete();
				}
			}
		}

		if ($request->update_type != "edit_product") {
			File::deleteDirectory($oldfilename);

			ProductImagesTemp::where('product_id', $request->product_id)->delete();

			$store = MerchantStore::where('user_id', Auth::id())->first();

			if (isset($store)) {

				//store activity data in notification table
				$activity_data = new Activity;
				$activity_data->source_id = $store->id;
				$activity_data->source_type = "store";
				$activity_data->activity_type = "add_product";
				$activity_data->target_id = $product_id;
				$activity_data->save();

			}
		}

		return redirect('merchant/all_products');
	}

	public function get_child_categories($parent_id) {

		$child_categories = Category::child_categories($parent_id);

		foreach ($child_categories as $row) {
			$data['id'] = $row->id;
			$data['name'] = $row->title;
			$data['list'] = $this->get_child_categories($row->id);
			$final_data[] = $data;
		}
		return @$final_data;
	}

	public function product_list(Request $request) {
		$userid = Auth::id();
		$type = $request->type;

		if ($type == "all") {
			$products = Product::where('user_id', $userid)->get();
		} else {
			switch ($type) {
			case "active":$option = "status";
				$type = "Active";
				break;
			case "inactive":$option = "status";
				$type = "Inactive";
				break;
			case "soldout":$option = "sold_out";
				$type = "Yes";
				break;
			case "expired":$option = "status";
				$type = "Expired";
				break;
			case "awaiting":$option = "admin_status";
				$type = "Waiting";
				break;
			case "onsale":$option = "admin_status";
				$type = "Approved";
				break;
			default:$option = "status";
				$type = "Active";
				break;
			}
			$products = Product::where('user_id', $userid)->where($option, $type)->get();
		}
		$final = [];
		foreach ($products as $product) {
			$products_price = ProductPrice::where('product_id', $product_id)->first();
			$products_image = ProductImages::where('product_id', $product_id)->first();
			$products_option = ProductOption::where('product_id', $product_id)->count();
			if ($products_option == 0) {
				$data['options'] = "";
			} else {
				$data['options'] = $products_option;
			}
			$data['title'] = $product->title;
			$data['id'] = $product_id;
			$data['quantity'] = $product->total_quantity;
			$data['sold'] = $product->sold;
			$data['status'] = $product->status;
			$data['sold_out'] = $product->sold_out;
			$data['admin_status'] = $product->admin_status;
			$data['sku'] = $products_price->sku;
			$data['price'] = $products_price->price;
			$data['retail'] = $products_price->retail_price;
			$data['image'] = $products_image->image_name;
			$final[] = $data;
		}
		return json_encode($final);
	}

	public function order() {
		Session::forget('ajax_redirect_url');
		return view('merchant.order');
	}
	/**
	 * Load order return request
	 *
	 * @return order_return page view
	 */
	public function order_return(Request $request) {
		$data['status'] = @$request->status;
		return view('merchant.order_return', $data);
	}
	public function return_request(Request $request) {
		$status = (@$request->status != '') ? @$request->status : '';
		$search_by = @$request->search_by;
		$search = @$request->search;
		$return_request = OrdersReturn::with([
			'orders_details' => function ($query) {
				$query->with([
					'products' => function ($query) {},
					'orders' => function ($query) {
						$query->with([
							'buyers' => function ($query) {},
						]);
					},
				]);
			},
		]);

		$return_request = $return_request->WhereHas('orders_details', function ($query1) use ($search, $search_by) {
			$query1->where('merchant_id', Auth::id());
		});

		if (@$request->status != '') {
			$return_request = $return_request->where('order_return.status', $request->status);
		}

		if (@$search != "" && $search_by == "order_id") {
			$return_request = $return_request->WhereHas('orders_details', function ($query) use ($search, $search_by) {
				$query->where('order_id', '=', $search);
			});
		} else if (@$search != "" && $search_by == "customer") {
			$return_request = $return_request->WhereHas('orders_details', function ($query1) use ($search, $search_by) {
				$query1->WhereHas('orders', function ($query2) use ($search, $search_by) {
					$query2->WhereHas('buyers', function ($query3) use ($search, $search_by) {
						$query3->where('full_name', 'like', '%' . $search . '%');
					});
				});
			});
		}

		$return_request = $return_request->orderBy('order_return.id', 'desc');

		$return_request = $return_request->paginate(15)->toJson();

		$return_request = json_decode($return_request, true);

		echo json_encode($return_request);
	}
	/**
	 * Load Edit merchant profile view file with user, timezone and country
	 *
	 * @return edit merchant profile view file
	 */
	public function settings(Request $request) {
		$data['userid'] = Auth::id();
		$data['countrys'] = Country::where('status', 'Active')->get();
		$data['timezone'] = Timezone::get()->pluck('name', 'value');
		$data['user'] = User::find(Auth::id());
		$data['user_address'] = UserAddress::where('user_id', Auth::id())->first();
		return view('merchant.settings', $data);
	}
	/**
	 * Update edit merchant profile page data
	 *
	 * @return redirect     to Edit profile
	 */
	public function update_seller_profile(Request $request) {
		// Email signup validation rules
		$rules = array(
			'store_name' => 'required|max:255',
			'full_name' => 'required',
			'address_line' => 'required',
			'city' => 'required',
			'postal_code' => 'required',
			'state' => 'required',
			'country' => 'required',
			'phone_number' => 'required',
		);

		// Email signup validation custom Fields name
		$niceNames = array(
			'store_name' => trans('messages.merchant.store_name'),
			'full_name' => trans('messages.merchant.full_name'),
			'address_line' => trans('messages.merchant.address'),
			'city' => trans('messages.merchant.city'),
			'postal_code' => trans('messages.merchant.postal_code'),
			'state' => trans('messages.merchant.state'),
			'country' => trans('messages.merchant.country'),
			'phone_number' => trans('messages.merchant.phone_no'),
		);

		$validator = Validator::make($request->all(), $rules);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
		} else {

			$user = User::find(@$request->userid);

			$user->store_name = $request->store_name;
			$user->full_name = $request->full_name;

			if (@$request->timezone != '') {
				$user->timezone = $request->timezone;
			}

			$user->save();

			$useraddress = UserAddress::where('user_id', $request->userid)->first();

			if ($useraddress != '') {
				$user_address = UserAddress::find($useraddress->id);
			}
			else {
				$user_address = new UserAddress;
				$user_address->user_id = $request->userid;
			}

			$user_address->address_line = $request->address_line;
			$user_address->address_line2 = $request->address_line2;
			$user_address->city = $request->city;
			$user_address->postal_code = $request->postal_code;
			$user_address->state = $request->state;
			$user_address->country = $request->country;
			$user_address->phone_number = $request->phone_number;

			$user_address->save();

			$user_merchant = MerchantStore::where('user_id', '=', $request->userid)->first();

			$user_merchant->store_name = $request->store_name;
			$user_merchant->save();
			$this->helper->flash_message('success', trans('messages.merchant.settings_basic_update')); // Call flash message function
			return redirect('merchant/settings');
		}
	}

	public function settings_paid()
	{
		$data['country'] = Country::all()->where('status', 'Active')->pluck('long_name', 'short_name');
		$data['stripe_data'] = PaymentGateway::where('site', 'Stripe')->get();
		$data['country_list'] = Country::getPayoutCoutries();
		$data['iban_supported_countries'] = Country::getIbanRequiredCountries();
		$data['country_currency'] = Country::getCurrency();
		$data['mandatory'] = PayoutPreferences::getAllMandatory();
		$data['branch_code_required'] = Country::getBranchCodeRequiredCountries();
		$data['payouts'] = PayoutPreferences::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
		return view('merchant.merchant_preference', $data);
	}

	public function settings_general(Request $request) {
		$data['merchant_store'] = MerchantStore::where('user_id', Auth::id())->first();
		return view('merchant.settings_general', $data);
	}
	public function add_video_thumb(Request $request) {
		$tmp_name = $_FILES["picture"]["tmp_name"];

		if ($request->type == "edit_product") {
			$check_product = Product::where('id', $request->id)->first();

			if (empty($check_product)) {

				$err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

				$rows['error'] = $err;

				return json_encode($rows);
			}

		}
		$name = str_replace(' ', '_', $_FILES["picture"]["name"]);
		$ext = 'png';
		$original_name = time() . '_' . $name;
		$name = $original_name . '.' . $ext;
		$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->id;
		$err = array();
		if (!file_exists($filename)) {
			mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->id, 0777, true);
		}
		if (UPLOAD_DRIVER == 'cloudinary') {
			$c = $this->helper->cloud_upload($tmp_name);
			if ($c['status'] != "error") {
				$name = $c['message']['public_id'];
			} else {
				$err = array('error_title' => ' Photo Error', 'error_description' => $c['message']);
			}
		} else {
			if (move_uploaded_file($tmp_name, "image/products/" . $request->id . "/" . $name)) {
				$this->helper->compress_image($filename . '/' . $name, $filename . '/' . $name, 90);
				$name = $original_name . '_225x225.' . $ext;
			}
		}
		ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_thumb')->delete();
		$temp_photos['product_id'] = $request->id;
		$temp_photos['image_name'] = $name;
		$temp_photos['option'] = 'video_thumb';
		$temp_photos['created_at'] = date('Y-m-d H:i:s');
		$temp_photos['updated_at'] = date('Y-m-d H:i:s');
		if (!count($err)) {
			if ($request->type == "add_product") {
				ProductImagesTemp::create($temp_photos);
			} else {
				// Product::where('id', $request->id)->update(['video_thumb' => $name]);
				ProductImagesTemp::create($temp_photos);
			}
		}
		$rows['error'] = $err;
		$rows['successres'] = $name;
		return json_encode($rows);
	}
	public function add_product_photo(Request $request) {
		if (isset($_FILES["upload-file"]["name"])) {
			$rows = array();
			$err = array();
			if ($request->type == "edit_product") {
				$check_product = Product::where('id', $request->product_id)->first();

				if (empty($check_product)) {

					$err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

					$rows['error'] = $err;

					return json_encode($rows);
				}

			}
			foreach ($_FILES["upload-file"]['error'] as $key => $error) {

				$tmp_name = $_FILES["upload-file"]["tmp_name"][$key];

				$name = str_replace(' ', '_', $_FILES["upload-file"]["name"][$key]);

				$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

				$name = time() . '_' . $name;

				$ex = substr( $name, strrpos( $name, "."));
                 $newfilename = basename($name, $ex);

				$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->id;

				if (!file_exists($filename)) {
					mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->id, 0777, true);
				}

				if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') {
					// if($this->helper->compress_image($tmp_name, "images/rooms/".$request->id."/".$name, 80))
					if (UPLOAD_DRIVER == 'cloudinary') 
					{
						$c = $this->helper->cloud_upload($tmp_name);
						if ($c['status'] != "error") {
							$name = $c['message']['public_id'];
						} else {
							$err = array('error_title' => ' Photo Error', 'error_description' => $c['message']);
						}
					}
					else
                    {
                        $upload_path = "image/products/".$request->id."/".$name;
                        $resize_path = "image/products/".$request->id."/".$newfilename;

                        if(move_uploaded_file($tmp_name, $upload_path))
                        {
                            
                        }

                        $this->helper->compress_image($upload_path , "image/products/".$request->id."/".$newfilename."_compress.".$ext , 90);        
                        $this->helper->resize_image($upload_path, 650,640,$resize_path.'_home_full');
                        $this->helper->resize_image($upload_path, 450,340,$resize_path.'_home_half');
                        $this->helper->resize_image($upload_path, 124,132,$resize_path.'_popular');
                        $this->helper->resize_image($upload_path, 104,104,$resize_path.'_header');


                    }
					$temp_photos['product_id'] = $request->id;
					$temp_photos['image_name'] = $name;
					$temp_photos['created_at'] = date('Y-m-d H:i:s');
					$temp_photos['updated_at'] = date('Y-m-d H:i:s');
					if (!count($err)) {
						if ($request->type == "add_product") {
							ProductImagesTemp::create($temp_photos);
						} else {
							//ProductImages::create($temp_photos);
							ProductImagesTemp::create($temp_photos);
						}
					}
				} else {
					$err = array('error_title' => ' Photo Error', 'error_description' => 'This is not an image file');

				}
			}
			if ($request->type == "add_product") {
				$result = ProductImagesTemp::where('product_id', $request->id)->where('option', NULL)->get();
			} else {
				$pro_img = ProductImages::where('product_id', $request->product_id)->get();
				$pro_temp_img = ProductImagesTemp::where('product_id', $request->id)->where('option', NULL)->get();
				$result = $pro_img->merge($pro_temp_img);
			}
			$rows['succresult'] = $result;
			$rows['steps_count'] = $result->count();
			$rows['error'] = $err;
			return json_encode($rows);

		}
	}

	public function add_product_video_mp4(Request $request) {
		if (isset($_FILES["product_video_mp4"]["name"])) {
			$rows = array();
			$err = array();

			if ($request->type == "edit_product") {
				$check_product = Product::where('id', $request->id)->first();

				if (empty($check_product)) {

					$err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

					$rows['error'] = $err;

					return json_encode($rows);
				}

			}

			$tmp_name = $_FILES["product_video_mp4"]["tmp_name"];
			$name = str_replace(' ', '_', $_FILES["product_video_mp4"]["name"]);
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			$name = time() . '_mp4_video.' . $ext;
			$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->id;

			if (!file_exists($filename)) {
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->id, 0777, true);
			}

			if ($ext == 'mp4') {
				if (UPLOAD_DRIVER == 'cloudinary') {
					$c = $this->helper->cloud_upload($tmp_name, "", "video");
					if ($c['status'] != "error") {
						$name = $c['message']['public_id'];
					} else {
						$err = array('error_title' => ' Video Error', 'error_description' => $c['message']);
					}
				} else {
					if (move_uploaded_file($tmp_name, "image/products/" . $request->id . "/" . $name)) {

					}
				}
				ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_mp4')->delete();

				$temp_photos['product_id'] = $request->id;
				$temp_photos['image_name'] = $name;
				$temp_photos['option'] = 'video_mp4';
				$temp_photos['created_at'] = date('Y-m-d H:i:s');
				$temp_photos['updated_at'] = date('Y-m-d H:i:s');
				if (!count($err)) {
					if ($request->type == "add_product") {
						ProductImagesTemp::create($temp_photos);
					} else {
						// Product::where('id', $request->id)->update(['video_mp4' => $name]);
						ProductImagesTemp::create($temp_photos);
					}
				}
			} else {
				$err = array('error_title' => ' Video Error', 'error_description' => 'The format is not valid');

			}
			$rows['succresult'] = "success";
			$rows['error'] = $err;
			return json_encode($rows);
		}
	}
	public function add_product_video_webm(Request $request) {
		if (isset($_FILES["product_video_webm"]["name"])) {
			$rows = array();
			$err = array();

			if ($request->type == "edit_product") {
				$check_product = Product::where('id', $request->id)->first();

				if (empty($check_product)) {

					$err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

					$rows['error'] = $err;

					return json_encode($rows);
				}

			}

			$tmp_name = $_FILES["product_video_webm"]["tmp_name"];
			$name = str_replace(' ', '_', $_FILES["product_video_webm"]["name"]);
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			$name = time() . '_webm_video.' . $ext;
			$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->id;

			if (!file_exists($filename)) {
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->id, 0777, true);
			}

			if ($ext == 'webm') {
				if (UPLOAD_DRIVER == 'cloudinary') {
					$c = $this->helper->cloud_upload($tmp_name, "", "video");
					if ($c['status'] != "error") {
						$name = $c['message']['public_id'];
					} else {
						$err = array('error_title' => ' Video Error', 'error_description' => $c['message']);
					}
				} else {
					if (move_uploaded_file($tmp_name, "image/products/" . $request->id . "/" . $name)) {

					}
				}
				ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_webm')->delete();

				$temp_photos['product_id'] = $request->id;
				$temp_photos['image_name'] = $name;
				$temp_photos['option'] = 'video_webm';
				$temp_photos['created_at'] = date('Y-m-d H:i:s');
				$temp_photos['updated_at'] = date('Y-m-d H:i:s');
				if (!count($err)) {
					if ($request->type == "add_product") {
						ProductImagesTemp::create($temp_photos);
					} else {

						// Product::where('id', $request->id)->update(['video_webm' => $name]);
						ProductImagesTemp::create($temp_photos);
					}
				}
			} else {
				$err = array('error_title' => ' Video Error', 'error_description' => 'The format is not valid');
			}
			if ($request->type == "add_product") {
				$rows['video_src'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_webm')->first()->images_name;
				$rows['video_src_mp4'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_mp4')->first()->images_name;
				$rows['video_src_webm'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_webm')->first()->images_name;
			} else {
				// $result = Product::where('id', $request->id)->first();
				// $rows['video_src'] = $result->video_src;
				// $rows['video_src_mp4'] = $result->video_src_mp4;
				// $rows['video_src_webm'] = $result->video_src_webm;
				$rows['video_src'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_webm')->first()->images_name;
				$rows['video_src_mp4'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_mp4')->first()->images_name;
				$rows['video_src_webm'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_webm')->first()->images_name;
			}

			$rows['error'] = $err;
			return json_encode($rows);
		}
	}
	public function add_product_option_photo(Request $request) {

		if (isset($_FILES["upload-option-file"]["name"])) {
			$rows = array();
			$err = array();

			$files = $request->file('upload-option-file');

			if ($request->hasFile('upload-option-file')) {
				foreach ($files as $file) {
					$ext = $file->getClientOriginalExtension();

					if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') {
						if ($request->option_db == '0') {
							$option_id = $request->option;
						} else {
							$option_id = $request->option_db;
						}

						$file->storeAs('image/products/' . $request->id . '/options/' . $option_id, $file->getClientOriginalName(), 'mydisk');
						$temp_photos['product_id'] = $request->id;
						$temp_photos['image_name'] = $file->getClientOriginalName();
						$temp_photos['created_at'] = date('Y-m-d H:i:s');
						$temp_photos['updated_at'] = date('Y-m-d H:i:s');

						$check_option_exists = ProductOption::where('product_id', $request->id)->where('id', $option_id);
						if ($check_option_exists->count() > 0) {
							unset($temp_photos['option']);
							$temp_photos['product_option_id'] = $option_id;
							ProductOptionImages::create($temp_photos);
						} else {
							$temp_photos['option'] = $option_id;
							ProductImagesTemp::create($temp_photos);
						}
					} else {
						$err = array('error_title' => ' Photo Error', 'error_description' => 'This is not an image file');
					}
				}
			}

			if ($check_option_exists->count() > 0) {
				$result = ProductOptionImages::where('product_id', $request->id)->where('product_option_id', $option_id)->get();
			} else {
				$result = ProductImagesTemp::select('*', 'option as product_option_id')->where('product_id', $request->id)->where('option', $option_id)->get();
			}
			$rows['succresult'] = $result;
			$rows['steps_count'] = $result->count();
			$rows['error'] = $err;
			return json_encode($rows);

		}
	}

	public function delete_product_photo(Request $request) {

		if ($request->type == "edit_product") {
			$check_product = Product::where('id', $request->productid)->first();

			if (empty($check_product)) {

				$err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

				$rows['error'] = $err;

				return json_encode(['success' => 'false', 'error' => $err]);
			}

		}
		if ($request->option == "false") {
			if ($request->type == "edit_product") {
				$photos_count = ProductImages::where('id', $request->photo_id)->where('product_id', $request->productid)->count();
				if ($photos_count) {
					$photos = ProductImages::find($request->photo_id);
					$product_id = $photos->product_id;
					if ($photos != NULL) {
						$photos->delete();
					}
					$photos = ProductImages::where('product_id', $product_id)->count();
					$photos_count = $photos - 1;

					return json_encode(['success' => 'true', 'steps_count' => $photos_count, 'delete_img' => $request->photo_id]);
				} else {
					$photos = ProductImagesTemp::find($request->photo_id);
					$product_id = $photos->product_id;
					if ($photos != NULL) {
						$photos->delete();
					}
					$photos = ProductImagesTemp::where('product_id', $product_id)->where('option', NULL);
				}

			} else {

				$photos = ProductImagesTemp::find($request->photo_id);
				$product_id = $photos->product_id;
				if ($photos != NULL) {
					$photos->delete();
				}
				$photos = ProductImagesTemp::where('product_id', $product_id)->where('option', NULL);
			}
		} elseif ($request->option == 'video') {
			$product_id = $request->productid;
			if ($request->type == "edit_product") {
				//Product::where('id', $product_id)->update(['video_mp4' => '', 'video_webm' => '']);
			}
			ProductImagesTemp::where('product_id', $product_id)->where('option', 'video')->delete();

			return json_encode(['success' => 'true']);
		} else {
			$check_option_exists = ProductOption::where('product_id', $request->productid)->where('id', $request->option_id);
			if ($check_option_exists->count() > 0) {
				$photos = ProductOptionImages::where('id', $request->photo_id)->where('product_id', $request->productid)->where('product_option_id', $request->option_id);
				if ($photos != NULL) {
					$photos->delete();
				}
				$photos = ProductOptionImages::where('product_id', $request->productid)->where('product_option_id', $request->option_id)->get();
			} else {
				$photos = ProductImagesTemp::where('id', $request->photo_id)->where('product_id', $request->productid)->where('option', $request->option_id);
				if ($photos != NULL) {
					$photos->delete();
				}
				$photos = ProductImagesTemp::where('product_id', $request->productid)->where('option', $request->option_id);
			}
		}
		return json_encode(['success' => 'true', 'steps_count' => $photos->count()]);
	}
	public function delete_option(Request $request) {
		ProductOptionImages::where('product_option_id', $request->option_id)->delete();
		ProductImagesTemp::where('option', $request->option_id)->delete();
		ProductOption::where('id', $request->option_id)->delete();
		return json_encode(['success' => 'true']);
	}

	public function product_search(Request $request) {
		$current = $request->input('current');
		$search = $request->input('search');
		$search_by = $request->input('search_by');
		if ($current != "all") {
			switch ($current) {
			case "active":$option = "status";
				$type = "Active";
				break;
			case "inactive":$option = "status";
				$type = "Inactive";
				break;
			case "soldout":$option = "sold_out";
				$type = "Yes";
				break;
			case "expired":$option = "status";
				$type = "Expired";
				break;
			case "awaiting":$option = "admin_status";
				$type = "Waiting";
				break;
			case "onsale":$option = "admin_status";
				$type = "Approved";
				$products_where["sold_out"] = 'No';
				$products_where['total_quantity'] = '0';
				$products_where['status'] = 'Active';
				break;
			default:$option2 = "status";
				$type2 = "Active";
				break;
			}
			$products_where[$option] = $type;
		}
		$products_where["user_id"] = Auth::id();
		if ($search != "" && $search_by == "id") {
			$products_where["products.id"] = $search;
		} elseif ($search != "" && $search_by == "title") {
			$products_where["products.title"] = '%' . $search . '%';
		}

		$products = Product::with([
			'products_prices_details' => function ($query) use ($search, $search_by) {
				$query->with('currency');
			},
			'products_images' => function ($query) {},
			'product_photos' => function ($query) {},
			'product_option' => function ($query) use ($search, $search_by) {

			},
		])
			->where(function ($query) use ($search, $search_by) {
				if ($search != "" && $search_by == "all") {
					$query->where(function ($query1) use ($search, $search_by) {
						$query1->orwhere('title', 'like', '%' . $search . '%')->orwhere('id', $search);
					})->orWhereHas('products_prices_details', function ($query) use ($search, $search_by) {
						if ($search != "" && $search_by == "all") {
							$query->where(function ($query1) use ($search, $search_by) {
								$query1->orwhere('products_prices_details.sku', $search);
							});
						}
					});
				}
			});
		if ($search != "" && $search_by == "sku") {
			$products = $products->WhereHas('products_prices_details', function ($query) use ($search, $search_by) {
				if ($search != "" && $search_by == "sku") {
					$query->where('sku', $search);
				}

			});
		}

		if (isset($products_where)) {
			foreach ($products_where as $row => $value) {
				if ($row == 'total_quantity') {
					$operator = '>';
				} else if ($row == 'products.title') {
					$operator = 'LIKE';
				} else {
					$operator = '=';
				}

				if ($value == '') {
					$value = 0;
				}

				$products = $products->where($row, $operator, $value);
			}
		}

		$userid = Auth::id();
		$count['count_all'] = Product::where('user_id', $userid)->count();
		$count['count_active'] = Product::where('user_id', $userid)->where('status', "Active")->count();
		$count['count_inactive'] = Product::where('user_id', $userid)->where('status', "Inactive")->count();
		$count['count_soldout'] = Product::where('user_id', $userid)->where('sold_out', "Yes")->count();
		$count['count_expired'] = Product::where('user_id', $userid)->where('status', "Expired")->count();
		$count['count_awaiting'] = Product::where('user_id', $userid)->where('admin_status', "Waiting")->count();
		$count['count_onsale'] = Product::where('user_id', $userid)->where('admin_status', "Approved")->where('status', 'Active')->where('sold_out', "No")->where('total_quantity', '>', 0)->WhereNotNull('total_quantity')->count();
		$products = $products->orderBy('id', 'desc')->paginate(10)->toJson();
		$products = json_decode($products, true);
		$final = array_merge($products, $count);
		echo json_encode($final);
	}

	public function product_status_update(Request $request) {
		if ($request->update == "activate") {
			Product::whereIn('id', $request->data)->update(['status' => 'Active']);
		} elseif ($request->update == "deactivate") {
			Product::whereIn('id', $request->data)->update(['status' => 'Inactive']);
		} elseif ($request->update == "delete") {

			$active_count = Product::whereIn('id', $request->data)->where('status', "Active")->count();
			if ($active_count == 0) {

				OrdersDetails::whereIn('product_id', $request->data)->delete();
				Cart::whereIn('product_id', $request->data)->delete();
				ProductShipping::whereIn('product_id', $request->data)->delete();
				ProductPrice::whereIn('product_id', $request->data)->delete();
				ProductOptionImages::whereIn('product_id', $request->data)->delete();
				ProductOption::whereIn('product_id', $request->data)->delete();
				ProductImages::whereIn('product_id', $request->data)->delete();
				Product::whereIn('id', $request->data)->delete();
				$data['status'] = "success";
				echo json_encode($data);
			} else {
				$data['title'] = Config::get('site_name');
				$data['status'] = "error";
				$data['body'] = trans('messages.products.delete_error');
				echo json_encode($data);
			}

		}

	}

	public function add_store_logo(Request $request) {
		if (isset($_FILES["upload-logo"]["name"])) {
			$rows = array();
			$err = array();
			foreach ($_FILES["upload-logo"]['error'] as $key => $error) {

				$tmp_name = $_FILES["upload-logo"]["tmp_name"][$key];
				$name = str_replace(' ', '_', $_FILES["upload-logo"]["name"][$key]);
				$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
				$name = time() . '_' . $name;
				$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/merchant/' . $request->id;
				if (UPLOAD_DRIVER == 'cloudinary') {
					$c = $this->helper->cloud_upload($tmp_name);
					if ($c['status'] != "error") {
						$file_name = $c['message']['public_id'];
						$temp_photos['logo_img'] = $file_name;
						//	MerchantStore::where('user_id', $request->id)->update($temp_photos);
					} else {
						$err = array('error_title' => ' Photo Error', 'error_description' => $c['message']);
					}
				} else {
					if (!file_exists($filename)) {
						mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/merchant/' . $request->id, 0777, true);
					}

					if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') {
						// if($this->helper->compress_image($tmp_name, "images/rooms/".$request->id."/".$name, 80))
						if (move_uploaded_file($tmp_name, "image/merchant/" . $request->id . "/" . $name)) {
							$temp_photos['logo_img'] = $name;
							//MerchantStore::where('user_id', $request->id)->update($temp_photos);
						}
					} else {
						$err = array('error_title' => ' Photo Error', 'error_description' => 'This is not an image file');

					}
				}
			}

			$result = MerchantStore::where('user_id', $request->id)->get();
			$data_param['user_id'] = @$result->first()->user_id;
			if (UPLOAD_DRIVER == 'cloudinary') {
				$data_param['logo_img'] = MerchantStore::logo_image($request->id, $file_name);
				$rows['logoimg'] = $file_name;
			} else {
				$data_param['logo_img'] = MerchantStore::logo_image($request->id, $name);
				$rows['logoimg'] = $name;
			}
			$rows['succresult'] = array($data_param);
			$rows['error'] = $err;
			return json_encode($rows);
		}
	}

	public function add_store_header(Request $request) {
		if (isset($_FILES["upload-header"]["name"])) {
			$rows = array();
			$err = array();
			foreach ($_FILES["upload-header"]['error'] as $key => $error) {

				$tmp_name = $_FILES["upload-header"]["tmp_name"][$key];
				$name = str_replace(' ', '_', $_FILES["upload-header"]["name"][$key]);
				$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
				$name = time() . '_' . $name;
				$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/merchant/' . $request->id;
				if (UPLOAD_DRIVER == 'cloudinary') {
					$c = $this->helper->cloud_upload($tmp_name);
					if ($c['status'] != "error") {
						$file_name = $c['message']['public_id'];
						$temp_photos['header_img'] = $file_name;
						//MerchantStore::where('user_id', $request->id)->update($temp_photos);
					} else {
						$err = array('error_title' => ' Photo Error', 'error_description' => $c['message']);
					}
				} else {
					if (!file_exists($filename)) {
						mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/image/merchant/' . $request->id, 0777, true);
					}

					if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') {
						// if($this->helper->compress_image($tmp_name, "images/rooms/".$request->id."/".$name, 80))
						if (move_uploaded_file($tmp_name, "image/merchant/" . $request->id . "/" . $name)) {
							$temp_photos['header_img'] = $name;
							//MerchantStore::where('user_id', $request->id)->update($temp_photos);
						}
					} else {
						$err = array('error_title' => ' Photo Error', 'error_description' => 'This is not an image file');

					}
				}
			}

			$result = MerchantStore::where('user_id', $request->id)->get();
			$data_param['user_id'] = @$result->first()->user_id;
			// $data_param['header_img'] = @$result->first()->header_img;
			if (UPLOAD_DRIVER == 'cloudinary') {
				$data_param['header_img'] = MerchantStore::header_image($request->id, $file_name);
				$rows['headerimg'] = $file_name;
			} else {
				$data_param['header_img'] = MerchantStore::header_image($request->id, $name);
				$rows['headerimg'] = $name;
			}
			$rows['succresult'] = array($data_param);
			$rows['error'] = $err;
			return json_encode($rows);
		}
	}

	public function delete_store_header(Request $request) {
		$image_name = MerchantStore::where('user_id', $request->id)->first()->header_img;
		// $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/merchant/' . $request->id . '/' . $image_name;
		// if (file_exists($filename)) {
		// 	File::delete($filename);
		// }
		$temp_image_name = $image_name;
		$temp_photos['header_img'] = "";
		$rows['img_name'] = $temp_image_name;
		return json_encode($rows);
		//$temp_photos['header_img'] = "";
		//MerchantStore::where('user_id', $request->id)->update($temp_photos);
	}
	public function delete_store_logo(Request $request) {
		$image_name = MerchantStore::where('user_id', $request->id)->first()->logo_img;
		// $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/merchant/' . $request->id . '/' . $image_name;
		// if (file_exists($filename)) {
		// 	File::delete($filename);
		// }
		$temp_image_name = $image_name;
		$temp_photos['logo_img'] = "";
		$rows['img_name'] = $temp_image_name;
		return json_encode($rows);
		// MerchantStore::where('user_id', $request->id)->update($temp_photos);
	}
	public function get_store_data(Request $request) {
		$result=[];
		$result = MerchantStore::where('user_id', $request->id)->get();
		// dd($result);
		$rows['succresult'] = $result;
		return json_encode($rows);
	}

	public function update_brand(Request $request) {
		// Email signup validation rules
		$rules = array(
			'store_name' => 'required|max:255',
			'tagline' => 'max:255',
		);

		// Email signup validation custom Fields name
		$niceNames = array(
			'store_name' => trans('messages.merchant.store_name'),
			'tagline' => trans('messages.merchant.tagline'),
		);

		$validator = Validator::make($request->all(), $rules);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput()->with('error_code', 1); // Form calling with Errors and Input values
		} else {
			//dd($request->all());

			$brand['store_name'] = $request->store_name;
			$brand['tagline'] = $request->tagline;
			$brand['description'] = $request->store_description;
			if ($request->logoimg != '') {
				$brand['logo_img'] = $request->logoimg;
			}
			if ($request->headerimg != '') {
				$brand['header_img'] = $request->headerimg;
			}

			if ($request->delete_log_img != '') {

				$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/merchant/' . $request->merchant_id . '/' . $request->delete_log_img;
				if (file_exists($filename)) {
					File::delete($filename);
				}
				$temp_photos['logo_img'] = "";
				MerchantStore::where('user_id', $request->merchant_id)->update($temp_photos);
			}

			if ($request->delete_header_img != '') {

				$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/merchant/' . $request->merchant_id . '/' . $request->delete_header_img;
				if (file_exists($filename)) {
					File::delete($filename);
				}
				$temp_photos1['header_img'] = "";
				MerchantStore::where('user_id', $request->merchant_id)->update($temp_photos1);
			}

			MerchantStore::where('user_id', $request->merchant_id)->update($brand);

			$user = User::find(@$request->merchant_id);
			$user->store_name = $request->store_name;
			$user->save();

			$this->helper->flash_message('success', trans('messages.merchant.settings_store_update')); // Call flash message function
			return redirect('merchant/settings_general');
		}
	}

	//Add payout preferences
	public function add_payout_preferences(Request $request)
	{
		$user_id = $payout['user_id'] = Auth::id();
		if ($request->set_default == 'true') {
			$undefault['default'] = 'no';
			PayoutPreferences::where('user_id', $user_id)->update($undefault);
		}

		$payout['address1'] = $request->address_1;
		$payout['address2'] = $request->address_2;
		$payout['city'] = $request->payout_city;
		$payout['state'] = $request->payout_state;
		$payout['postal_code'] = $request->payout_zip;
		$payout['country'] = $request->country_code;
		$payout['payout_method'] = $request->payout_method;
		$payout['paypal_email'] = $request->paypal_email;
		$payout['currency_code'] = (Session::get('currency')) ? Session::get('currency') : default_currency_code;

		$payout['default'] = ($request->set_default == 'true') ? 'yes' : 'no';

		$payout['created_at'] = date('Y-m-d H:i:s');
		$payout['updated_at'] = date('Y-m-d H:i:s');

		if ($request->payout_method == 'Stripe') {
			$stripe_credentials = PaymentGateway::where('site', 'Stripe')->pluck('value', 'name');
			\Stripe\Stripe::setApiKey($stripe_credentials['secret']);
			\Stripe\Stripe::setClientId($stripe_credentials['client_id']);
			$oauth_url = \Stripe\OAuth::authorizeUrl([
				'response_type' => 'code',
				'scope' => 'read_write',
				'redirect_uri' => url('merchant/stripe_payout_preferences'),
			]);

			Session::put('payout_preferences_data', $payout);

			return redirect($oauth_url);
		}

		PayoutPreferences::where('user_id', $user_id)->insert($payout);

		$default_count = PayoutPreferences::where('user_id', $user_id)->where('default','yes')->count();

		if($default_count == 0) {
			$payout = PayoutPreferences::where('user_id', $user_id)->first();
		    $payout->default = 'yes';
		    $payout->save();
		}
		flashMessage('success', trans('messages.merchant.payout_updated'));
		return redirect('merchant/settings_paid');
	}

	public function stripe_payout_preferences(Request $request)
	{
		$stripe_credentials = PaymentGateway::where('site', 'Stripe')->pluck('value', 'name');
		\Stripe\Stripe::setApiKey($stripe_credentials['secret']);
		\Stripe\Stripe::setClientId($stripe_credentials['client_id']);
		try {
			$response = \Stripe\OAuth::token([
				'client_secret' => $stripe_credentials['secret'],
				'code' => $request->code,
				'grant_type' => 'authorization_code',
			]);
		} catch (\Exception $e) {
			$oauth_url = \Stripe\OAuth::authorizeUrl([
				'response_type' => 'code',
				'scope' => 'read_write',
				'redirect_uri' => url('merchant/stripe_payout_preferences'),
			]);
			return redirect($oauth_url);
		}
		$session_payout_data = Session::get('payout_preferences_data');
		if (!$session_payout_data || !@$response['stripe_user_id']) {
			return redirect('merchant/settings_paid');
		}
		$session_payout_data->paypal_email = @$response['stripe_user_id'];
		$session_payout_data->payout_method = "Stripe";
		$session_payout_data->save();

		// $payout_check = PayoutPreferences::where('user_id', Auth::user()->user()->id)->where('default','yes')->get();

		// if($payout_check->count() == 0)
		// {
		//     $session_payout_data->default = 'yes';
		//     $session_payout_data->save();
		// }

		Session::forget('payout_preferences_data');
		$this->helper->flash_message('success', trans('messages.merchant.payout_updated')); // Call flash message function
		return redirect('merchant/settings_paid');
	}

	public function get_payout_preferences() {
		$user_id = Auth::id();
		$payouts = PayoutPreferences::where('user_id', $user_id);
		$payouts = $payouts->orderBy('id', 'desc')->get()->toJson();
		echo $payouts;
	}

	public function get_shipping_details() {
		$user_id = Auth::id();
		$shipping = ShippingAddress::where('user_id', $user_id);
		$shipping = $shipping->get()->toJson();
		echo $shipping;
	}
	public function get_billing_details() {
		$user_id = Auth::id();
		$billing = BillingAddress::where('user_id', $user_id);
		$billing = $billing->get()->toJson();
		echo $billing;
	}
	public function get_review_orders(Request $request) {
		$user_id = Auth::id();
		$shipping = ShippingAddress::where('user_id', $user_id);
		if ($request->payment_method == "cos") {
			$country = null;
		} else {
			$country = $shipping->first()->country;
		}

		$users_where['users.status'] = 'Active';

		$carts = Cart::with([
			'product_details' => function ($query) use ($users_where) {
				$query->with([
					'products_prices_details' => function ($query) {
						$query->with('currency');
					},
					'product_photos' => function ($query) {},
					'users' => function ($query) use ($users_where) {$query->where($users_where);},
				]);
			},
			'product_option_details' => function ($query) {},
			'product_shipping_details' => function ($query) use ($country) {
				$query->where('ships_to', $country);
			},
		])->whereHas('product_details', function ($query) use ($users_where) {$query->where('products.status', 'Active')->where('products.admin_status', 'Approved')->whereHas('users', function ($query1) use ($users_where) {$query1->where($users_where);});})->where('user_id', Auth::id());

		$carts = $carts->get()->toJson();
		echo $carts;
	}

	public function get_price_details(Request $request)
	{
		$cart = Cart::with([
			'product_details' => function ($query) {
				$query->with([
					'products_prices_details.currency',
					'products_shipping',
					'product_photos',
					'product_option',
					'users' => function ($query) {
						$query->activeOnly();
					},
				]);
			},
		])
		->whereHas('product_details', function ($query) {
			$query->approved()->isActive()->activeUser();
		})
		->where('user_id', Auth::id())
		->orderBy('id', 'desc')
		->get();

		if ($request->payment_method == "cos") {
			$price_data = $this->payment_helper->price_calculation($cart, "no");
		}
		else {
			$price_data = $this->payment_helper->price_calculation($cart, "yes");
		}
		return response($price_data);

	}

	public function add_shipping_details(Request $request) {
		$edit = $request->edit;
		unset($request['edit']);
		$request['user_id'] = Auth::id();
		if ($edit == "yes") {
			ShippingAddress::where('user_id', Auth::id())->update($request->all());
		} else {
			ShippingAddress::create($request->all());
		}

	}
	public function add_billing_details(Request $request) {
		$edit = $request->edit;
		unset($request['edit']);
		$request['user_id'] = Auth::id();
		if ($edit == "yes") {
			BillingAddress::where('user_id', Auth::id())->update($request->all());
		} else {
			BillingAddress::create($request->all());
		}

	}
	public function get_payment_method(Request $request) {
		if (Session::has('payment_credit_card')) {
			$data['credit_card_details'] = Session::get('payment_credit_card');
		} else {
			$data['credit_card_details'] = [];
		}

		$order_detail = Orders::where('buyer_id', @Auth::user()->id)->where('customer_id', '!=', '')->get();
		$card_detail = array();
		$stripe_secret = PaymentGateway::where('site', 'Stripe')->get();
		//dd($order_detail);
		foreach ($order_detail as $key => $orderdetail) {
			try
			{
				$stripe = \Stripe\Stripe::setApiKey($stripe_secret[0]->value);

				$card = \Stripe\Customer::retrieve($orderdetail->customer_id);
				$card_detail[$key]['last4'] = '************' . $card->sources->data[0]->last4;
				$card_detail[$key]['customer_id'] = $orderdetail->customer_id;
				$card_detail[$key]['card_id'] = @$orderdetail->card_id;

			} catch (\Exception $e) {

				$card_detail[$key]['last4'] = '';
				$card_detail[$key]['customer_id'] = '';
				$card_detail[$key]['card_id'] = '';

			}
		}

		$card_detail = array_map("unserialize", array_unique(array_map("serialize", $card_detail)));

		$data['card_detail'] = $card_detail;
		$data['card_detail_count'] = count($card_detail);

		echo json_encode($data);
	}
	public function add_payment_method(Request $request) {
		Session::forget('payment_credit_card');

		$credit_card_details = [
			'card_name' => $request->card_name,
			'card_number' => $request->card_number,
			'cc_expire_month' => $request->cc_expire_month,
			'cc_expire_year' => $request->cc_expire_year,
			'cvv' => $request->cvv,
			'token' => $request->token,
		];

		Session::put('payment_credit_card', $credit_card_details);

		Session::save();

		echo json_encode($credit_card_details);
	}

	public function checkout_payment() {
		$cart = Cart::where('user_id', Auth::id())->get();
		$prices = json_decode($this->payment_helper->price_calculation($cart, "yes"));
	}

	public function default_pay(Request $request) {
		$undefault['default'] = 'no';
		PayoutPreferences::where('user_id', Auth::id())->update($undefault);
		$default['default'] = 'yes';
		PayoutPreferences::where('user_id', Auth::id())->where('id', $request->id)->update($default);
	}
	public function remove_pay(Request $request) {
		PayoutPreferences::where('id', $request->id)->where('default', 'no')->delete();

	}

	public function order_search(Request $request) {

		$current = $request->input('current');
		$search = $request->input('search');
		$search_by = $request->input('search_by');
		$userid = Auth::id();
		if ($current != "all") {
			$option = "status";
			switch ($current) {
			case "open":$type = "Pending";
				break;
			case "completed":$type = "Completed";
				break;
			case "cancelled":$type = "Cancelled";
				break;
			default:$option2 = "status";
				$type2 = "Pending";
				break;
			}
			$orders_where[$option] = $type;
		}
		$orders_where["merchant_id"] = Auth::id();
		if ($search != "" && $search_by == "order_id") {
			$orders_where["orders_details.order_id"] = $search;
		}

		$orders = $orders_data = OrdersDetails::with([

			'orders' => function ($query) {
				$query->with([
					'shipping_details' => function ($query) {},
					'billing_details' => function ($query) {},
					'buyers' => function ($query) {},
					'orders_details' => function ($query) {
						$query->with(['products' => function ($query1) {
							$query1->with(['product_option' => function ($query) {}]);
						}]);
						$userid = Auth::id();
						$query->where('merchant_id', $userid);
					},

				]);
			},
		])->select(DB::raw('count(*) as orders_count'), DB::raw('Sum(orders_details.price*orders_details.quantity) as total_amount'), DB::raw('Sum(orders_details.shipping) as total_shipping'), DB::raw('Sum(orders_details.incremental) as total_incremental'), DB::raw('Sum(orders_details.merchant_fee) as total_merchant'), 'orders_details.order_id')->groupBy('orders_details.order_id')->orderBy('orders_details.order_id', 'DESC');
		if ($search != "" && $search_by == "product_title") {
			$orders = $orders->WhereHas('products', function ($query) use ($search, $search_by) {
				$query->where('title', 'like', '%' . $search . '%');
			});
		} else if ($search != "" && $search_by == "product_id") {
			$orders = $orders->WhereHas('products', function ($query) use ($search, $search_by) {
				$query->where('id', '=', $search);
			});
		} else if ($search != "" && $search_by == "username") {
			$orders = $orders->WhereHas('orders', function ($query1) use ($search, $search_by) {
				$query1->WhereHas('buyers', function ($query2) use ($search, $search_by) {
					$query2->where('user_name', 'like', '%' . $search . '%');
				});
			});
		} else if ($search != "" && $search_by == "fullname") {
			$orders = $orders->WhereHas('orders', function ($query1) use ($search, $search_by) {
				$query1->WhereHas('buyers', function ($query2) use ($search, $search_by) {
					$query2->where('full_name', 'like', '%' . $search . '%');
				});
			});
		}

		if (isset($orders_where)) {
			foreach ($orders_where as $row => $value) {
				if ($row == 'products.title') {
					$operator = 'LIKE';
				} else {
					$operator = '=';
				}

				if ($value == '') {
					$value = 0;
				}

				$orders = $orders->where($row, $operator, $value);
			}
		}

		$userid = Auth::id();

		$count['count_all'] = OrdersDetails::select(DB::raw('count(*) as orders_count'), 'orders_details.order_id')->groupBy('orders_details.order_id')->where('merchant_id', $userid)->get()->count();
		$count['count_open'] = OrdersDetails::select(DB::raw('count(*) as orders_count'), 'orders_details.order_id')->groupBy('orders_details.order_id')->where('merchant_id', $userid)->where('status', "Pending")->get()->count();
		$count['count_completed'] = OrdersDetails::select(DB::raw('count(*) as orders_count'), 'orders_details.order_id')->groupBy('orders_details.order_id')->where('merchant_id', $userid)->where('status', "Completed")->get()->count();
		$count['count_cancelled'] = OrdersDetails::select(DB::raw('count(*) as orders_count'), 'orders_details.order_id')->groupBy('orders_details.order_id')->where('merchant_id', $userid)->where('status', "Cancelled")->get()->count();

		$orders = $orders->paginate(10)->toJson();

		$orders = json_decode($orders, true);

		foreach ($orders['data'] as $key => $value) {

			$orders['data'][$key]['total_order_amount'] = 0;

			$orders['data'][$key]['total_order_amount'] = 0;

			$orders['data'][$key]['total_order_shipping'] = 0;

			$orders['data'][$key]['total_order_incremental'] = 0;

			$orders['data'][$key]['total_order_merchant'] = 0;

			foreach (@$value['orders']['orders_details'] as $order_key => $order_value) {

				$orders['data'][$key]['total_order_amount'] += @$value['orders']['orders_details'][$order_key]['price'] * @$value['orders']['orders_details'][$order_key]['quantity'];

				$orders['data'][$key]['total_order_shipping'] += @$value['orders']['orders_details'][$order_key]['shipping'];

				$orders['data'][$key]['total_order_incremental'] += @$value['orders']['orders_details'][$order_key]['incremental'];

				$orders['data'][$key]['total_order_merchant'] += @$value['orders']['orders_details'][$order_key]['merchant_fee'];

			}

			$orders['data'][$key]['status_open'] = OrdersDetails::select(DB::raw('count(*) as orders_count'), 'orders_details.order_id')->groupBy('orders_details.order_id')->where('merchant_id', $userid)->where('status', "Pending")->where('order_id', @$value['orders']['id'])->get()->count();

			$orders['data'][$key]['status_processing'] = OrdersDetails::select(DB::raw('count(*) as orders_count'), 'orders_details.order_id')->groupBy('orders_details.order_id')->where('merchant_id', $userid)->where('status', "Processing")->where('order_id', @$value['orders']['id'])->get()->count();

			$orders['data'][$key]['status_completed'] = OrdersDetails::select(DB::raw('count(*) as orders_count'), 'orders_details.order_id')->groupBy('orders_details.order_id')->where('merchant_id', $userid)->where('status', "Completed")->where('order_id', @$value['orders']['id'])->get()->count();

			$orders['data'][$key]['status_cancelled'] = OrdersDetails::select(DB::raw('count(*) as orders_count'), 'orders_details.order_id')->groupBy('orders_details.order_id')->where('merchant_id', $userid)->where('status', "Cancelled")->where('order_id', @$value['orders']['id'])->get()->count();

			$status = [];

			if ($orders['data'][$key]['status_open'] != 0) {
				array_push($status, 'Pending');
			}

			if ($orders['data'][$key]['status_processing'] != 0) {
				array_push($status, 'Processing');
			}

			if ($orders['data'][$key]['status_completed'] != 0) {
				array_push($status, 'Completed');
			}

			if ($orders['data'][$key]['status_cancelled'] != 0) {
				array_push($status, 'Cancelled');
			}

			if (count($status) == 0) {
				array_push($status, 'Returned');
			}

			$orders['data'][$key]['status'] = implode(' ', $status);
		}

		$final = array_merge($orders, $count);

		echo json_encode($final);
	}

	public function order_notification($order_id, $merchant_id, $status, $type, $message) {

		$order_detail = OrdersDetails::whereIn('order_id', $order_id)->where('merchant_id', $merchant_id)->where('status', 'Pending');
		if ($order_detail->count()) {
			foreach ($order_detail->get() as $request_order) {
				OrdersDetails::where('id', $request_order->id)->update(['status' => 'Processing']);
				$orders = Orders::where('id', $request_order->order_id)->first();
				//store activity data in notification table
				$activity_data = new Notifications;
				$activity_data->order_id = $request_order->order_id;
				$activity_data->order_details_id = $request_order->id;
				$activity_data->user_id = $merchant_id; //merchant id
				$activity_data->notify_id = $orders->buyer_id; //buyer id when order process intimate to buyer
				$activity_data->product_id = $request_order->product_id;
				$activity_data->notification_type = "order";
				$activity_data->notification_type_status = $type;
				$activity_data->notification_message = $message;
				$activity_data->save();
				$email_controller = new EmailController();
				$buyer = User::where('id', $orders->buyer_id)->first();
				$er = $email_controller->order_custom_notification($buyer->email, $buyer->full_name, "Order Processing", "Your Order is Processing. For More details click the button and see the details", "View Order", url('purchases') . "/" . $request_order->order_id);
			}
		}

	}

	public function order_status_update(Request $request) {

		$userid = Auth::id();

		if ($request->update == "process") {
			$this->order_notification($request->data, $userid, 'Pending', 'process', 'processing your order');
		} elseif ($request->update == "delivered") {
			OrdersDetails::whereIn('order_id', $request->data)->update(['status' => 'Delivered']);
		} elseif ($request->update == "open") {
			OrdersDetails::whereIn('order_id', $request->data)->update(['status' => 'Pending']);
		} elseif ($request->update == "completed") {
			$order_detail = OrdersDetails::whereIn('order_id', $request->data)->where('merchant_id', $userid)->where('status', 'Processing');

			if ($order_detail->count()) {
				foreach ($order_detail->get() as $request_order) {
					$orders_detail = array();
					$payouts_data = array();
					$order_detail_id = $request_order->id;
					$orders_details = OrdersDetails::where('id', $order_detail_id)->first();
					$orders_detail['status'] = 'Completed';
					$orders_detail['completed_at'] = date('Y-m-d H:i:s');
					$orders_detail['order_return_date'] = date('Y-m-d');

					if ($orders_details->paymode == "cod") {
						$orders_detail['owe_amount'] = $orders_details->original_merchant + $orders_details->original_service;
						$orders_detail['remaining_owe_amount'] = $orders_detail['owe_amount'];
					} elseif (strtolower($orders_details->paymode) == "paypal" || strtolower($orders_details->paymode) == "credit card") {
						$applied_owe_amount = 0;
						$amount_for_owe = ($orders_details->original_price * $orders_details->quantity) + ($orders_details->original_shipping + $orders_details->original_incremental) - $orders_details->original_merchant;
						$merchant_owe = OrdersDetails::where('merchant_id', $orders_details->merchant_id)->where('remaining_owe_amount', '!=', 0)->get();
						if ($merchant_owe->count()) {
							foreach ($merchant_owe as $row) {
								$calc_remaining_owe_amount = $this->payment_helper->currency_convert($row->currency_code, $orders_details->currency_code, $row->remaining_owe_amount);
								if ($amount_for_owe > 0) {
									if ($amount_for_owe >= $calc_remaining_owe_amount) {
										$amount_for_owe -= $calc_remaining_owe_amount;
										$applied_owe_amount += $calc_remaining_owe_amount;
										$remaining_owe_amount_for = 0;
									} else {
										$remaining_owe_amount_for = $calc_remaining_owe_amount - $amount_for_owe;
										$remaining_owe_amount_for = $this->payment_helper->currency_convert($orders_details->currency_code, $row->currency_code, $remaining_owe_amount_for);
										$applied_owe_amount += $amount_for_owe;
										$amount_for_owe = 0;
									}

									OrdersDetails::where('id', $row->id)->update(['remaining_owe_amount' => $remaining_owe_amount_for]);
								}
							}
						}
						$orders_detail['applied_owe_amount'] = $applied_owe_amount;
					}
					OrdersDetails::where('id', $order_detail_id)->update($orders_detail);
					$orders_details_new = OrdersDetails::where('id', $order_detail_id)->first();
					$payouts_data['order_id'] = $orders_details_new->order_id;
					$payouts_data['order_detail_id'] = $request_order->id;
					$payouts_data['user_id'] = Auth::id();
					$payouts_data['user_type'] = "merchant";
					$payouts_data['account'] = "Paypal";
					$payouts_data['subtotal'] = $this->payment_helper->currency_convert($orders_details_new->currency_code, 'USD', ($orders_details_new->original_price * $orders_details_new->quantity));
					$payouts_data['service'] = $this->payment_helper->currency_convert($orders_details_new->currency_code, 'USD', $orders_details_new->original_service);
					$payouts_data['merchant_fee'] = $this->payment_helper->currency_convert($orders_details_new->currency_code, 'USD', $orders_details_new->original_merchant);
					$payouts_data['applied_owe_amount'] = $this->payment_helper->currency_convert($orders_details_new->currency_code, 'USD', $orders_details_new->applied_owe_amount);
					$payouts_data['shipping'] = $this->payment_helper->currency_convert($orders_details_new->currency_code, 'USD', $orders_details_new->original_shipping) + $this->payment_helper->currency_convert($orders_details_new->currency_code, 'USD', $orders_details_new->original_incremental);
					$payouts_data['amount'] = ($payouts_data['subtotal'] + $payouts_data['shipping']) - ($payouts_data['applied_owe_amount'] + $payouts_data['merchant_fee']);
					$payouts_data['currency_code'] = 'USD';
					$payouts_data['status'] = "Future";
					$payouts_data['created_at'] = date('Y-m-d H:i:s');
					$payouts_data['updated_at'] = date('Y-m-d H:i:s');

					if ($orders_details_new->paymode == "paypal" || $orders_details_new->paymode == "PayPal" || strtolower($orders_details_new->paymode) == "credit card") {
						Payouts::insert($payouts_data);
					}

					$orders = Orders::where('id', $request_order->order_id)->first();
					//store activity data in notification table
					$activity_data = new Notifications;
					$activity_data->order_id = $request_order->order_id;
					$activity_data->order_details_id = $request_order->id;
					$activity_data->user_id = $userid; //merchant id
					$activity_data->notify_id = $orders->buyer_id; //buyer id when order process intimate to buyer
					$activity_data->product_id = $request_order->product_id;
					$activity_data->notification_type = "order";
					$activity_data->notification_type_status = 'finished';
					$activity_data->notification_message = "finished the order, Ready for Shipping";
					$activity_data->save();
					$email_controller = new EmailController();
					$buyer = User::where('id', $orders->buyer_id)->first();
					$er = $email_controller->order_custom_notification($buyer->email, $buyer->full_name, "Order Completed", "Your Order has beed Completed. For More details click the button and see the details", "View Order", url('purchases') . "/" . $request_order->order_id);
				}
			}

		} elseif ($request->update == "delete") {

			$active_count = Product::whereIn('id', $request->data)->where('status', "Active")->count();
			if ($active_count == 0) {

				OrdersDetails::whereIn('product_id', $request->data)->delete();
				Cart::whereIn('product_id', $request->data)->delete();
				ProductShipping::whereIn('product_id', $request->data)->delete();
				ProductPrice::whereIn('product_id', $request->data)->delete();
				ProductOptionImages::whereIn('product_id', $request->data)->delete();
				ProductOption::whereIn('product_id', $request->data)->delete();
				ProductImages::whereIn('product_id', $request->data)->delete();
				Product::whereIn('id', $request->data)->delete();
				$data['status'] = "success";
				echo json_encode($data);
			} else {
				$data['title'] = Config::get('site_name');
				$data['status'] = "error";
				$data['body'] = trans('messages.products.delete_error');
				echo json_encode($data);
			}
		}
	}

	//view indivijual order details for merchant
	public function view_order(Request $request) {
		Session::forget('ajax_redirect_url');
		$orders = OrdersDetails::with([
			'products' => function ($query) {},
			'product_photos' => function ($query) {},
			'product_option' => function ($query) {},
			'orders_cancel' => function ($query) {},
			'orders' => function ($query) {
				$query->with([
					'buyers' => function ($query) {},
				]);
			},
		])->where('order_id', $request->id)->where('merchant_id', Auth::id());
		if ($orders->count()) {
			$data['orders_details'] = $orders->get();
			return view('merchant.view_order', $data);
		} else {
			$this->helper->flash_message('danger', trans('messages.order.invalid_order_id'));
			return redirect('merchant/order'); // Redirect to dashboard page
		}

	}

	public function orders_view(Request $request) {
		$orders = OrdersDetails::with([
			'products' => function ($query) {},
			'product_photos' => function ($query) {},
			'product_option' => function ($query) {},
			'orders' => function ($query) {
				$query->with([
					'buyers' => function ($query) {},
					'shipping_details' => function ($query) {},
					'billing_details' => function ($query) {},
				]);
			},
		])->where('order_id', $request->id)->where('merchant_id', Auth::id());
		echo $orders->get()->tojson();

	}
	public function orders_details_view(Request $request) {
		$orders = Orders::with([
			'shipping_details' => function ($query) {},
			'billing_details' => function ($query) {},
			'currency' => function ($query) {},
		])->where('id', $request->id)->get();
		echo $orders->tojson();
	}

	public function merchant_action(Request $request) {
		
		$order_detail_id = $request->id;
		$action = $request->action;
		$reason = $request->reason;

		if (strtolower($action) == 'cancel') {

			$orders_detail_data = OrdersDetails::find($order_detail_id);
			$previous_status = $orders_detail_data->status;
			$orders_data = Orders::find($orders_detail_data->order_id);
			// $default_currency = Currency::where('default_currency', 1)->first()->code;

			/* Check the order is cancelled before or not */
			if ($orders_detail_data->status == 'Cancelled') {
				return "fail";
				exit;
			}

			$orders_detail_data->status = 'Cancelled';
			$orders_detail_data->cancelled_by = 'Merchant';
			$orders_detail_data->save();

			$orderscancel = new OrdersCancel;
			$orderscancel->order_id = $order_detail_id;
			$orderscancel->cancel_reason = $reason;
			$orderscancel->save();

			$shipping = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_shipping);
			$incremental = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_incremental);
			$service = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_service);
			$merchant_fee = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_merchant);

			if ($orders_data->coupon_code != '') {
				$remaining_amount = round(OrdersDetails::where('order_id', $orders_data->id)->whereNotIn('id', [$order_detail_id])->whereNotIn('status', ['Cancelled', 'Returned'])->get()->sum('original_price'));

				$refunded_sum = OrdersDetails::where('order_id', $orders_data->id)->whereIn('status', ['Cancelled', 'Returned'])->whereNotIn('id', [$order_detail_id])->get()->sum('original_price');

				$subtotal = $orders_data->original_subtotal;
				$coupon_amt = $orders_data->original_coupon_amt;

				$user_payout_amount = 0;

				if ($refunded_sum >= $coupon_amt) {

					$user_payout_amount = $refunded_sum - $coupon_amt;
				}

				$user_paid_amount = round(($subtotal - $coupon_amt) - $user_payout_amount);

				$subtotal = 0;

				if ($user_paid_amount > $remaining_amount) {
					$subtotal = $user_paid_amount - $remaining_amount;
				}
			} else {
				$subtotal = $orders_detail_data->original_price * $orders_detail_data->quantity;
			}
			$subtotal = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $subtotal);

			//Merchant Cancel the product buyer send amount(product price & shipping & incremental & service)
			$amount = $subtotal + $shipping + $incremental + $service;
			$merchant_amount = null;

			$payouts_data['order_id'] = $merchant_payouts_data['order_id'] = $orders_detail_data->order_id;
			$payouts_data['order_detail_id'] = $merchant_payouts_data['order_detail_id'] = $order_detail_id;

			$payouts_data['user_id'] = $orders_data->buyer_id;
			$merchant_payouts_data['user_id'] = Auth::id();

			$payouts_data['user_type'] = "buyer";
			$merchant_payouts_data['user_type'] = "merchant";

			$payouts_data['account'] = $merchant_payouts_data['account'] = "Paypal";
			$payouts_data['subtotal'] = $merchant_payouts_data['subtotal'] = $subtotal;
			$payouts_data['service'] = $merchant_payouts_data['service'] = $service;
			$payouts_data['merchant_fee'] = $merchant_payouts_data['merchant_fee'] = $merchant_fee;
			$payouts_data['shipping'] = $merchant_payouts_data['shipping'] = ($shipping + $incremental);
			$payouts_data['amount'] = $amount;
			$merchant_payouts_data['amount'] = $merchant_amount;

			$payouts_data['currency_code'] = $merchant_payouts_data['currency_code'] = 'USD';
			$payouts_data['status'] = $merchant_payouts_data['status'] = "Future";
			$payouts_data['created_at'] = $merchant_payouts_data['created_at'] = date('Y-m-d H:i:s');
			$payouts_data['updated_at'] = $merchant_payouts_data['updated_at'] = date('Y-m-d H:i:s');
			// if ($payouts_data['amount'] > 0) {
			if (strtolower($orders_detail_data->paymode) == "paypal" || strtolower($orders_detail_data->paymode) == "credit card") {
				Payouts::create($payouts_data);
				Payouts::create($merchant_payouts_data);
			}
			// }

			//update products table quantity
			$product = Product::find($orders_detail_data->product_id);
			$product->total_quantity = $product->total_quantity + $orders_detail_data->quantity;
			$product->sold = $product->sold - $orders_detail_data->quantity;
			if ($product->total_quantity > 0) {
				$product->sold_out = "No";
			}
			$product->save();

			//update product option table quantity if option is available
			if (strtolower($orders_detail_data->option_id) != null) {
				$product_option = ProductOption::where('id', $orders_detail_data->option_id)->where('product_id', $orders_detail_data->product_id)->first();
				$product_option->total_quantity = $product_option->total_quantity + $orders_detail_data->quantity;
				$product_option->sold = $product_option->sold - $orders_detail_data->quantity;
				$product_option->save();
			}

			//store activity data in notification table
			$activity_data = new Notifications;
			$activity_data->order_id = $orders_detail_data->order_id;
			$activity_data->order_details_id = $orders_detail_data->id;
			$activity_data->user_id = $orders_detail_data->merchant_id; //merchant id
			$activity_data->notify_id = $orders_data->buyer_id; //buyer id
			$activity_data->product_id = $orders_detail_data->product_id;
			$activity_data->notification_type = "order";
			$activity_data->notification_type_status = "cancelled";
			$activity_data->notification_message = "Cancelled the order";
			$activity_data->save();

			$email_controller = new EmailController();
			$buyer = User::find($orders_data->buyer_id);
			$er = $email_controller->order_custom_notification($buyer->email, $buyer->full_name, "Order cancelled by Merchant", $orderscancel->cancel_reason, "View Order", url('purchases') . "/" . $orders_detail_data->order_id);

		} elseif ($action == 'process') {
			$orders_detail_data = OrdersDetails::find($order_detail_id);
			$orders_data = Orders::find($orders_detail_data->order_id);

			if ($orders_detail_data->status != 'Pending') {
				return "fail";exit;
			}
			$orders_detail_data->status = 'Processing';
			$orders_detail_data->save();

			//store activity data in notification table
			$activity_data = new Notifications;
			$activity_data->order_id = $orders_detail_data->order_id;
			$activity_data->order_details_id = $orders_detail_data->id;
			$activity_data->user_id = $orders_detail_data->merchant_id; //merchant id
			$activity_data->notify_id = $orders_data->buyer_id; //buyer id
			$activity_data->product_id = $orders_detail_data->product_id;
			$activity_data->notification_type = "order";
			$activity_data->notification_type_status = "process";
			$activity_data->notification_message = "processing your order";
			$activity_data->save();

			$email_controller = new EmailController();
			$buyer = User::find($orders_data->buyer_id);
			$er = $email_controller->order_custom_notification($buyer->email, $buyer->full_name, "Order Processing", "Your Order is Processing. For More details click the button and see the details", "View Order", url('purchases') . "/" . $orders_data->id);

		} elseif ($action == 'complete') {

			$orders_details = OrdersDetails::where('id', $request->id)->first();
			if ($orders_details->status != 'Processing') {
				return "fail";exit;
			}

			$orders_details_up['status'] = 'Completed';
			$orders_details_up['completed_at'] = date('Y-m-d H:i:s');
			$orders_details_up['order_return_date'] = date('Y-m-d');

			if ($orders_details->paymode == "cod") {
				$orders_details_up['owe_amount'] = $orders_details->original_merchant + $orders_details->original_service;
				$orders_details_up['remaining_owe_amount'] = $orders_details_up['owe_amount'];
				//dd($orders_details_up['owe_amount']);
			} elseif (($orders_details->paymode == "paypal" || $orders_details->paymode == "PayPal" || $orders_details->paymode == "credit card" || $orders_details->paymode == "Credit Card")) {
				$applied_owe_amount = 0;
				$amount_for_owe = ($orders_details->original_price * $orders_details->quantity) + ($orders_details->original_shipping + $orders_details->original_incremental) - $orders_details->original_merchant;
				$merchant_owe = OrdersDetails::where('merchant_id', $orders_details->merchant_id)->where('remaining_owe_amount', '!=', 0)->get();
				if ($merchant_owe->count()) {
					foreach ($merchant_owe as $row) {
						$calc_remaining_owe_amount = $this->payment_helper->currency_convert($row->currency_code, $orders_details->currency_code, $row->remaining_owe_amount);
						if ($amount_for_owe > 0) {
							if ($amount_for_owe >= $calc_remaining_owe_amount) {
								$amount_for_owe -= $calc_remaining_owe_amount;
								$applied_owe_amount += $calc_remaining_owe_amount;
								$remaining_owe_amount_for = 0;
							} else {
								$remaining_owe_amount_for = $calc_remaining_owe_amount - $amount_for_owe;
								$remaining_owe_amount_for = $this->payment_helper->currency_convert($orders_details->currency_code, $row->currency_code, $remaining_owe_amount_for);
								$applied_owe_amount += $amount_for_owe;
								$amount_for_owe = 0;
							}
							OrdersDetails::where('id', $row->id)->update(['remaining_owe_amount' => $remaining_owe_amount_for]);
						}
					}
				}
				$orders_details_up['applied_owe_amount'] = $applied_owe_amount;
			}

			$orders_details = OrdersDetails::where('id', $request->id)->update($orders_details_up);

			$orders_details = OrdersDetails::where('id', $request->id)->first();

			$payouts_data['order_id'] = $orders_details->order_id;
			$payouts_data['order_detail_id'] = $request->id;
			$payouts_data['user_id'] = Auth::id();
			$payouts_data['user_type'] = "merchant";
			$payouts_data['account'] = "Paypal";
			$payouts_data['subtotal'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', ($orders_details->original_price * $orders_details->quantity));
			$payouts_data['service'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_service);
			$payouts_data['merchant_fee'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_merchant);
			$payouts_data['applied_owe_amount'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->applied_owe_amount);
			$payouts_data['shipping'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_shipping) + $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_incremental);
			$payouts_data['amount'] = ($payouts_data['subtotal'] + $payouts_data['shipping']) - ($payouts_data['applied_owe_amount'] + $payouts_data['merchant_fee']);
			$payouts_data['currency_code'] = 'USD';
			$payouts_data['status'] = "Future";
			$payouts_data['created_at'] = date('Y-m-d H:i:s');
			$payouts_data['updated_at'] = date('Y-m-d H:i:s');

			//dd($payouts_data);

			if ($orders_details->paymode == "paypal" || $orders_details->paymode == "PayPal" || $orders_details->paymode == "credit card" || $orders_details->paymode == "Credit Card") {
				Payouts::insert($payouts_data);
			}

			

			$order_data = Orders::where('id', $orders_details->order_id)->first();

			//store activity data in notification table
			$activity_data = new Notifications;
			$activity_data->order_id = $orders_details->order_id;
			$activity_data->order_details_id = $orders_details->id;
			$activity_data->user_id = $orders_details->merchant_id; //merchant id
			$activity_data->notify_id = $order_data->buyer_id; //buyer id
			$activity_data->product_id = $orders_details->product_id;
			$activity_data->notification_type = "order";
			$activity_data->notification_type_status = "finished";
			$activity_data->notification_message = "finished the order, Ready for Shipping";
			$activity_data->save();
			$email_controller = new EmailController();
			$buyer = User::where('id', $order_data->buyer_id)->first();
			$er = $email_controller->order_custom_notification($buyer->email, $buyer->full_name, "Order Completed", "Your Order has beed Completed. For More details click the button and see the details", "View Order", url('purchases') . "/" . $order_data->id);
		}

	}

	public function return_search(Request $request) {

		$current = $request->input('current');
		$search = $request->input('search');
		$search_by = $request->input('search_by');
		$userid = Auth::id();

		$orders = OrdersReturn::with([
			'orders_details' => function ($query) {
				$query->with([
					'products' => function ($query) {},
					'orders' => function ($query) {},
				]);
			},
		])->orderBy('order_return.id', 'desc');
		$orders = $orders->WhereHas('orders_details', function ($query1) use ($search, $search_by) {
			$query1->where('merchant_id', Auth::id());
		});

		if ($search != "" && $search_by == "username") {
			$orders = $orders->WhereHas('orders_details', function ($query1) use ($search, $search_by) {

				$query1->WhereHas('orders', function ($query2) use ($search, $search_by) {
					$query2->WhereHas('buyers', function ($query3) use ($search, $search_by) {
						$query3->where('user_name', 'like', '%' . $search . '%')
						->orWhere('full_name', 'like', '%' . $search . '%');
					});
				});
			});
		}
		if ($search != "" && $search_by == "order_id") {
			$orders = $orders->WhereHas('orders_details', function ($query1) use ($search, $search_by) {

				$query1->where('order_id', $search);
			});
		}

		if ($search != "" && $search_by == "all") {
			$orders = $orders->WhereHas('orders_details', function ($query1) use ($search, $search_by) {
				$query1->WhereHas('orders', function ($query2) use ($search, $search_by) {
					$query2->WhereHas('buyers', function ($query3) use ($search, $search_by) {
						$query3->where('user_name', 'like', '%' . $search . '%')
						->orWhere('full_name', 'like', '%' . $search . '%');
					});
				})
				->orWhere('order_id', $search);
			});
		}

		if (isset($orders_where)) {
			foreach ($orders_where as $row => $value) {
				if ($row == 'products.title') {
					$operator = 'LIKE';
				} else {
					$operator = '=';
				}

				if ($value == '') {
					$value = 0;
				}

				$orders = $orders->where($row, $operator, $value);
			}
		}
		$orders = $orders->paginate(10)->toJson();
		echo $orders;
	}

	public function order_return_status_update(Request $request) {
		if ($request->update == "accept") {

			

			$orders_return = OrdersReturn::whereIn('id', $request->data)->where('status', 'Requested');
			if ($orders_return->count()) {
				foreach ($orders_return->get() as $data) {
					OrdersReturn::where('id', $data->id)->update(['status' => 'Approved']);

					/* Declare orders related datas as variable */
					$order_detail_id = OrdersReturn::find($data->id)->order_id;
					$orders_detail_data = OrdersDetails::find($order_detail_id);
					$orders_data = Orders::find($orders_detail_data->order_id);
					$order_id = $orders_detail_data->order_id;
					$coupon_amount = 0;

					/* Delete previous payout detail of specific merchant*/
					Payouts::where('order_id', $order_id)->where('order_detail_id', $order_detail_id)->where('user_type', 'merchant')->delete();

					/* Currency convert to order's session currency */
					$shipping = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_shipping);
					$incremental = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_incremental);
					$service = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_service);
					$merchant_fee = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_merchant);

					/*Reduce owe amount*/
					$owe_amount = $orders_detail_data->owe_amount;
					if ($owe_amount != '' && $owe_amount > 0) {

						$apply_owe_amount = $owe_amount - $orders_detail_data->original_merchant;
						$remaining_owe_amount = $orders_detail_data->remaining_owe_amount - $orders_detail_data->original_merchant;

						$update_order_details = OrdersDetails::find($order_detail_id);
						$update_order_details->owe_amount = $apply_owe_amount;
						$update_order_details->remaining_owe_amount = $apply_owe_amount;
						$update_order_details->save();

					}

					//apply Owe Amount reduce
					$apply_owe_amount = $orders_detail_data->applied_owe_amount;
					if ($apply_owe_amount > 0) {
						$update_order_details = OrdersDetails::find($order_detail_id);
						$update_order_details->applied_owe_amount = '0';
						$update_order_details->remaining_owe_amount = $apply_owe_amount;
						$update_order_details->save();

						$payouts_data['applied_owe_amount'] = '0';

					}

					/* Checking the order Used coupon code status  */
					if ($orders_data->coupon_code != '') {
						$remaining_amount = round(OrdersDetails::where('order_id', $orders_data->id)->whereNotIn('id', [$order_detail_id])->whereNotIn('status', ['Cancelled', 'Returned'])->get()->sum('original_price'));

						$refunded_sum = OrdersDetails::where('order_id', $orders_data->id)->whereIn('status', ['Cancelled', 'Returned'])->whereNotIn('id', [$order_detail_id])->get()->sum('original_price');

						$subtotal = $orders_data->original_subtotal;
						$coupon_amt = $orders_data->original_coupon_amt;

						$user_payout_amount = 0;

						if ($refunded_sum >= $coupon_amt) {
							$user_payout_amount = $refunded_sum - $coupon_amt;
						}

						$user_paid_amount = round(($subtotal - $coupon_amt) - $user_payout_amount);

						$subtotal = 0;
						if ($user_paid_amount > $remaining_amount) {
							$subtotal = $user_paid_amount - $remaining_amount;
						}

					} else {
						$subtotal = $orders_detail_data->original_price * $orders_detail_data->quantity;
					}

					$subtotal = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $subtotal);

					$amount = $subtotal;

					//Merchant refund amount  calculate merchant fee
					$merchantfee = Fees::where('name', 'merchant_fee')->first()->value;

					if ($merchantfee > 0) {

						$merchant_fee_amount = ($shipping + $incremental) - (($merchantfee / 100) * ($shipping + $incremental));
						$merchant_amount = number_format(round($merchant_fee_amount), 2, '.', '');
					} else {
						$merchant_amount = $shipping + $incremental;
					}

					//$merchant_amount = $merchant_amount;

					/* Insert payout details */
					$payouts_data['order_id'] = $merchant_payouts_data['order_id'] = $orders_detail_data->order_id;
					$payouts_data['order_detail_id'] = $merchant_payouts_data['order_detail_id'] = $order_detail_id;
					$merchant_payouts_data['user_id'] = $orders_detail_data->merchant_id;
					$payouts_data['user_id'] = $orders_data->buyer_id;

					$merchant_payouts_data['user_type'] = "merchant";
					$payouts_data['user_type'] = "buyer";

					$payouts_data['account'] = $merchant_payouts_data['account'] = "Paypal";
					$payouts_data['subtotal'] = $merchant_payouts_data['subtotal'] = $subtotal;
					$payouts_data['service'] = $merchant_payouts_data['service'] = $service;
					$payouts_data['merchant_fee'] = $merchant_payouts_data['merchant_fee'] = $merchant_fee;
					$payouts_data['shipping'] = $merchant_payouts_data['shipping'] = $shipping + $incremental;
					$payouts_data['amount'] = $amount;
					$merchant_payouts_data['amount'] = $merchant_amount;

					$payouts_data['currency_code'] = $merchant_payouts_data['currency_code'] = 'USD';
					$payouts_data['status'] = $merchant_payouts_data['status'] = "Future";
					$payouts_data['created_at'] = $merchant_payouts_data['created_at'] = date('Y-m-d H:i:s');
					$payouts_data['updated_at'] = $merchant_payouts_data['updated_at'] = date('Y-m-d H:i:s');

					if (strtolower($orders_detail_data->paymode) == "paypal" || strtolower($orders_detail_data->paymode) == "credit card") {
						Payouts::insert($payouts_data);
						Payouts::insert($merchant_payouts_data);
					}

					/* Update the Order details data */
					$orders_detail_data->return_status = "Approved";
					$orders_detail_data->save();

					//update products table quantity
					$product = Product::find($orders_detail_data->product_id);
					$product->total_quantity = $product->total_quantity + $orders_detail_data->quantity;
					$product->sold = $product->sold - $orders_detail_data->quantity;
					if ($product->total_quantity > 0) {
						$product->sold_out = "No";
					}
					$product->save();

					//update product option table quantity if option is available
					if (strtolower($orders_detail_data->option_id) != null) {
						$product_option = ProductOption::where('id', $orders_detail_data->option_id)->where('product_id', $orders_detail_data->product_id)->first();
						$product_option->total_quantity = $product_option->total_quantity + $orders_detail_data->quantity;
						$product_option->sold = $product_option->sold - $orders_detail_data->quantity;
						$product_option->save();
					}

					//store activity data in notification table
					$activity_data = new Notifications;
					$activity_data->order_id = $orders_detail_data->order_id;
					$activity_data->order_details_id = $orders_detail_data->id;
					$activity_data->user_id = $orders_detail_data->merchant_id; //merchant id
					$activity_data->notify_id = $orders_data->buyer_id; //buyer id
					$activity_data->product_id = $orders_detail_data->product_id;
					$activity_data->notification_type = "order";
					$activity_data->notification_type_status = "return_accept";
					$activity_data->notification_message = "accepeted your return order";
					$activity_data->save();

					$email_controller = new EmailController();
					$buyer = User::find($orders_data->buyer_id);
					$er = $email_controller->order_custom_notification($buyer->email, $buyer->full_name, "Order Return Accepted", "Your order return has accepted", "View Order", url('purchases') . "/" . $orders_detail_data->order_id);

				}
			}
		} elseif ($request->update == "reject") {
			$order_detail = OrdersReturn::whereIn('id', $request->data)->where('status', 'Requested');
			if ($order_detail->count()) {
				foreach ($order_detail->get() as $data) {
					OrdersReturn::where('id', $data->id)->update(['status' => 'Rejected']);
					$order_detail_id = OrdersReturn::where('id', $data->id)->first()->order_id;
					$orders_details['return_status'] = "Rejected";
					OrdersDetails::where('id', $order_detail_id)->update($orders_details);
					$orders_details_data = OrdersDetails::where('id', $order_detail_id)->first();
					$order_data = Orders::where('id', $orders_details_data->order_id)->first();
					//store activity data in notification table
					$activity_data = new Notifications;
					$activity_data->order_id = $orders_details_data->order_id;
					$activity_data->order_details_id = $orders_details_data->id;
					$activity_data->user_id = $orders_details_data->merchant_id; //merchant id
					$activity_data->notify_id = $order_data->buyer_id; //buyer id
					$activity_data->product_id = $orders_details_data->product_id;
					$activity_data->notification_type = "order";
					$activity_data->notification_type_status = "return_reject";
					$activity_data->notification_message = "Order Return is rejected";
					$activity_data->save();

					$email_controller = new EmailController();
					$buyer = User::where('id', $order_data->buyer_id)->first();
					$er = $email_controller->order_custom_notification($buyer->email, $buyer->full_name, "Order Return Rejected", "Your order return has rejected", "View Order", url('purchases') . "/" . $orders_details_data->order_id);
				}
			}
		} elseif ($request->update == "completed") {
			OrdersReturn::whereIn('id', $request->data)->update(['status' => 'Completed']);

			$orderid = OrdersReturn::whereIn('id', $request->data)->first()->order_id;

			OrdersDetails::where('id', $orderid)->update(['return_status' => 'Completed']);
		}
	}

	public function payout_history() {
		$data['userid'] = Auth::id();
		$data['country'] = Country::where('status', 'Active')->get();
		$data['timezone'] = Timezone::all();
		$data['user'] = User::find(Auth::id());
		$data['user_address'] = UserAddress::where('user_id', Auth::id())->get();
		return view('merchant.view_payout_history', $data);
	}

	public function transfers(Request $request) {
		$current = $request->input('current');

		$transfers = Payouts::with(['currency'])->where('user_id', Auth::id())->where('status', $current);

		$userid = Auth::id();
		$transfers = $transfers->paginate(10)->toJson();
		echo $transfers;

	}
	public function change_currency(Request $request) {
		$data['currency_symbol'] = $symbol = Currency::original_symbol($request->currency);
		$data['currency_code'] = $request->currency;
		$data['minimum_amount'] = $this->payment_helper->currency_convert('USD', $request->currency, 1);
		return response()->json($data);
	}

	// stripe account creation
	public function update_payout_preferences(Request $request, EmailController $email_controller) {
		// dd($request);

		$country_data = Country::where('short_name', $request->country)->first();
		//dd($country_data);

		if (!$country_data) {
			$message = trans('messages.lys.service_not_available_country');
			$this->helper->flash_message('error', $message); // Call flash message function
			return back();
		}

		$user_id = Auth::user()->id;

		$user = User::find($user_id);
		$dob_array = explode('-', @$user->dob);

		if (@$user->dob == '' || @$user->dob == NULL) {
			$message = trans('messages.lys.user_dob_not_available');
			$this->helper->flash_message('error', $message); // Call flash message function
			return back();

		}

		/*** required field validation --start-- ***/
		$country = $request->country;

		$rules = array(
			'country' => 'required',
			'currency' => 'required',
			'account_number' => 'required',
			'holder_name' => 'required',
			'stripe_token' => 'required',
			'address1' => 'required',
			'city' => 'required',
			'postal_code' => 'required',
			'document' => 'required|mimes:png,jpeg,jpg',

		);

		// dd($dob_array[0]);

		// custom required validation for Japan country
		if ($country == 'JP') {

			$rules['phone_number'] = 'required';
			$rules['bank_name'] = 'required';
			$rules['branch_name'] = 'required';
			$rules['address1'] = 'required';
			$rules['kanji_address1'] = 'required';
			$rules['kanji_address2'] = 'required';
			$rules['kanji_city'] = 'required';
			$rules['kanji_state'] = 'required';
			$rules['kanji_postal_code'] = 'required';

			if (!$user->gender) {
				$rules['gender'] = 'required|in:male,female';
			}

		}
		// custom required validation for US country
		else if ($country == 'US') {
			$rules['ssn_last_4'] = 'required|digits:4';
		}

		$nice_names = array(
			'payout_country' => trans('messages.account.country'),
			'currency' => trans('messages.account.currency'),
			'routing_number' => trans('messages.account.routing_number'),
			'account_number' => trans('messages.account.account_number'),
			'holder_name' => trans('messages.account.holder_name'),
			'additional_owners' => trans('messages.account.additional_owners'),
			'business_name' => trans('messages.account.business_name'),
			'business_tax_id' => trans('messages.account.business_tax_id'),
			'holder_type' => trans('messages.account.holder_type'),
			'stripe_token' => 'Stripe Token',
			'address1' => trans('messages.account.address'),
			'city' => trans('messages.account.city'),
			'state' => trans('messages.account.state'),
			'postal_code' => trans('messages.account.postal_code'),
			'document' => trans('messages.account.legal_document'),
			'ssn_last_4' => trans('messages.account.ssn_last_4'),
		);
		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);
		$validator->setAttributeNames($nice_names);

		//dd($request->all());

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();

		}

		/*** required field validation --end-- ***/

		$stripe_data = PaymentGateway::where('site', 'Stripe')->pluck('value', 'name');

		\Stripe\Stripe::setApiKey($stripe_data['secret']);

		$account_holder_type = 'individual';

		/*** create stripe account ***/
		try
		{
			$recipient = \Stripe\Account::create(array(
				"country" => strtolower($request->country),
				"payout_schedule" => array(
					"interval" => "manual",
				),
				"tos_acceptance" => array(
					"date" => time(),
					"ip" => $_SERVER['REMOTE_ADDR'],
				),
				"type" => "custom",
			));
		} catch (\Exception $e) {

			$this->helper->flash_message('error', $e->getMessage());
			return back();
		}

		$recipient->email = Auth::user()->email;

		// create external account using stripe token --start-- //

		try {
			$recipient->external_accounts->create(array(
				"external_account" => $request->stripe_token,
			));
		} catch (\Exception $e) {

			$this->helper->flash_message('error', $e->getMessage());
			return back();
		}

		// create external account using stripe token --end-- //
		try
		{
			// insert stripe external account datas --start-- //
			if ($request->country != 'JP') {
				// for other countries //
				$recipient->legal_entity->type = $account_holder_type;
				$recipient->legal_entity->first_name = $user->first_name;
				$recipient->legal_entity->last_name = $user->last_name;
				$recipient->legal_entity->dob->day = @$dob_array[2];
				$recipient->legal_entity->dob->month = @$dob_array[1];
				$recipient->legal_entity->dob->year = @$dob_array[0];
				$recipient->legal_entity->address->line1 = @$request->address1;
				$recipient->legal_entity->address->line2 = @$request->address2 ? @$request->address2 : null;
				$recipient->legal_entity->address->city = @$request->city;
				$recipient->legal_entity->address->country = @$request->country;
				$recipient->legal_entity->address->state = @$request->state ? @$request->state : null;
				$recipient->legal_entity->address->postal_code = @$request->postal_code;
				if ($request->country == 'US') {
					$recipient->legal_entity->ssn_last_4 = $request->ssn_last_4;
				}
			} else {
				// for Japan country //
				$address = array(
					'line1' => $request->address1,
					'line2' => $request->address2,
					'city' => $request->city,
					'state' => $request->state,
					'postal_code' => $request->postal_code,
				);
				$address_kana = array(
					'line1' => $request->address1,
					'town' => $request->address2,
					'city' => $request->city,
					'state' => $request->state,
					'postal_code' => $request->postal_code,
					'country' => $request->country,
				);
				$address_kanji = array(
					'line1' => $request->kanji_address1,
					'town' => $request->kanji_address2,
					'city' => $request->kanji_city,
					'state' => $request->kanji_state,
					'postal_code' => $request->kanji_postal_code,
					'country' => $request->country,
				);

				$recipient->legal_entity->type = $account_holder_type;
				$recipient->legal_entity->first_name_kana = $user->first_name;
				$recipient->legal_entity->last_name_kana = $user->last_name;
				$recipient->legal_entity->first_name_kanji = $user->first_name;
				$recipient->legal_entity->last_name_kanji = $user->last_name;
				$recipient->legal_entity->dob->day = @$dob_array[2];
				$recipient->legal_entity->dob->month = @$dob_array[1];
				$recipient->legal_entity->dob->year = @$dob_array[0];
				$recipient->legal_entity->address_kana = $address_kana;
				$recipient->legal_entity->address_kanji = $address_kanji;
				$recipient->legal_entity->gender = $request->gender ? $request->gender : strtolower(Auth::user()->gender);

				$recipient->legal_entity->phone_number = @$request->phone_number ? $request->phone_number : null;

			}

			$recipient->save();
			// insert stripe external account datas --end-- //
		} catch (\Exception $e) {
			//dd(@$user->dob_array[1]);
			try
			{
				$recipient->delete();
			} catch (\Exception $e) {

			}

			$this->helper->flash_message('error', $e->getMessage());
			return back();
		}

		// verification document upload for stripe account --start-- //
		$document = $request->file('document');

		if ($request->document) {
			$extension = $document->getClientOriginalExtension();
			$filename = $user_id . '_user_document_' . time() . '.' . $extension;
			$filenamepath = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user_id . '/uploads';

			if (!file_exists($filenamepath)) {
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user_id . '/uploads', 0777, true);
			}
			$success = $document->move('images/users/' . $user_id . '/uploads/', $filename);
			if ($success) {
				$document_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user_id . '/uploads/' . $filename;

				try
				{
					$stripe_file_details = \Stripe\FileUpload::create(
						array(
							"purpose" => "identity_document",
							"file" => fopen($document_path, 'r'),
						),
						array("stripe_account" => @$recipient->id)
					);

					$recipient->legal_entity->verification->document = $stripe_file_details->id;
					$recipient->save();

					$stripe_document = $stripe_file_details->id;
				} catch (\Exception $e) {
					$this->helper->flash_message('error', $e->getMessage());
					return back();
				}

			}

		}

		// verification document upload for stripe account --end-- //

		// store payout preference data to payout_preference table --start-- //
		$payout_preference = new PayoutPreferences;
		$payout_preference->user_id = $user_id;
		$payout_preference->country = $request->country;
		$payout_preference->currency_code = $request->currency;
		$payout_preference->routing_number = $request->routing_number;
		$payout_preference->account_number = $request->account_number;
		$payout_preference->holder_name = $request->holder_name;
		$payout_preference->holder_type = $account_holder_type;
		$payout_preference->paypal_email = @$recipient->id;

		$payout_preference->address1 = @$request->address1;
		$payout_preference->address2 = @$request->address2;
		$payout_preference->city = @$request->city;

		$payout_preference->state = @$request->state;
		$payout_preference->postal_code = @$request->postal_code;
		$payout_preference->document_id = $stripe_document;
		$payout_preference->document_image = @$filename;
		$payout_preference->phone_number = @$request->phone_number ? $request->phone_number : '';
		$payout_preference->branch_code = @$request->branch_code ? $request->branch_code : '';
		$payout_preference->bank_name = @$request->bank_name ? $request->bank_name : '';
		$payout_preference->branch_name = @$request->branch_name ? $request->branch_name : '';

		$payout_preference->ssn_last_4 = @$request->country == 'US' ? $request->ssn_last_4 : '';
		$payout_preference->payout_method = 'Stripe';

		$payout_preference->address_kanji = @$address_kanji ? json_encode(@$address_kanji) : json_encode([]);

		$payout_preference->save();

		if ($request->gender) {
			$user->gender = $request->gender;
			$user->save();
		}

		$payout_check = PayoutPreferences::where('user_id', Auth::user()->id)->where('default', 'yes')->get();

		if ($payout_check->count() == 0) {
			$payout_preference->default = 'yes'; // set default payout preference when no default
			$payout_preference->save();
		}
		// store payout preference data to payout_preference table --end-- //

		// $email_controller->payout_preferences($payout_preference->id); // send payout preference updated email to host user.
		$this->helper->flash_message('success', trans('messages.account.payout_updated'));
		return back();

	}

}
