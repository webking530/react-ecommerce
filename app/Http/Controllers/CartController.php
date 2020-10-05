<?php

/**
 * Card Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Product
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Country;
use App\Models\CouponCode;
use App\Models\Orders;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\ProductClick;
use Auth;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Session;

class CartController extends BaseController {

	protected $payment_helper; // Global variable for Helpers instance

	/**
	 * Constructor to Set PaymentHelper instance in Global variable
	 *
	 * @param array $payment   Instance of PaymentHelper
	 */
	public function __construct(PaymentHelper $payment) {
		$this->payment_helper = $payment;
		$this->helper = new Helpers;
	}
	/**
	 * Cart Product Detail
	 *
	 * @param array $request    Input values
	 * @return cart details
	 */
	public function cart(Request $request)
	{
		$data['page']='browse';
		if (Auth::id()) {
			$user_id = Auth::id();
			$data['categories'] = Category::where("parent_id",0)->where('status','Active')->get();
			$users_where['users.status'] = 'Active';
			
			$data['recently_viewed_things'] = ProductClick::with([
				'products' => function ($query) use ($users_where) {
					$query->with([
						'products_prices_details' => function ($query) {
							$query->with('currency');
						},
						'products_shipping' => function ($query) {},
						'product_photos' => function ($query) {},
						'product_option' => function ($query) {},
						'users' => function ($query) use ($users_where) {$query->where($users_where);},
					])->where('products.status', 'Active');
				},
			])->whereHas('products', function ($query) use ($users_where) {$query->where('products.status', 'Active')->where('products.admin_status', 'Approved')->where('products.total_quantity', '<>', '0')->where('products.sold_out', 'No')->whereHas('users', function ($query1) use ($users_where) {$query1->where($users_where);});})->select(DB::raw('max(created_at) as created'), 'product_id')->where('product_click.user_id', $user_id)->orderBy('product_click.created_at', 'desc')->groupBy('product_click.product_id')->get();

			Session::forget('ajax_redirect_url');

			return view('purchases.cart', $data);
		} else {
			return redirect('login'); // Redirect to login page
		}
	}

	public function cart_product() {
		$users_where['users.status'] = 'Active';

		$data['cart'] = Cart::with([
			'product_details' => function ($query) {
				$query->with([
					'products_prices_details' => function ($query) {
						$query->with('currency');
					},
					'products_shipping' => function ($query) {},
					'product_photos' => function ($query) {},
					'product_option' => function ($query) {},
					'users' => function ($query) {},
				]);
			},
		])->where('user_id', Auth::id())->orderBy('add_cart.id', 'desc')->get();

		$data['price'] = json_decode($this->payment_helper->price_calculation($data['cart'], ''));

		echo json_encode($data);

	}
	/**
	 * Ajax Add to cart before chanked login or not
	 *
	 * @param array $request    Input values
	 * @return chacked login or not
	 */
	public function add_to_cart(Request $request)
	{
		$product_id = $request->productid;
		$qty = $request->quantity;
		$option = ($request->option != '') ? $request->option : NULL;

		//if checked user login or not
		if (Auth::check()) {
			$user_id = Auth::id();

			// Checking for Already Cart Or Not..
			$checked_cart = Cart::where(['product_id' => $product_id, 'user_id' => $user_id, 'option_id' => $option])->first();

			if ($checked_cart != "") {
				$cart_id = $checked_cart->id;
				$cart = Cart::find($cart_id);
				$cart->quantity = $checked_cart->quantity + $qty;
			}
			else {
				$cart = new Cart;
				$cart->quantity = $qty;
				$cart->product_id = $product_id;
				$cart->user_id = $user_id;
			}

			if (@$option != '' && @$option != 'NULL') {
				$cart->option_id = $option;
			}

			$cart->save();

			return 1;
		}
		else {
			$redirect_url = 'things/' . $product_id;
			Session::put('ajax_redirect_url', $redirect_url);
			return 0;
		}
	}

	/**
	 * Ajax cart product update for option & quantity
	 *
	 * @param array $request    Input values
	 * @return no
	 */
	public function cart_update(Request $request)
	{
		$cart_id = $request->cart_id;
		$quantity = $request->quantity;
		$option = ($request->option != '') ? $request->option : NULL;
		$product_id = $request->product_id;
		// Checking for Already Cart Or Not..
		$checked_cart = Cart::where(['product_id' => $product_id, 'user_id' => Auth::id(), 'option_id' => $option])->where('id', '!=', $cart_id)->first();

		if ($checked_cart != "") {
			if (@$option != '' && @$option != 'NULL') {
				$cart['option_id'] = $option;
			}

			$cart['quantity'] = $checked_cart->quantity + $quantity;
			$cart = Cart::find($cart_id)->update($cart);
			$delete = Cart::destroy($checked_cart->id);

		}
		else {
			$cart['quantity'] = $quantity;

			if (@$option != '' && @$option != 'NULL') {
				$cart['option_id'] = $option;
			}

			$cart = Cart::find($cart_id)->update($cart);
		}
	}

	/**
	 * Ajax remove cart product
	 *
	 * @param array $request    Input values
	 * @return cart product
	 */
	public function remove_cart(Request $request)
	{
		Cart::where('id', $request->cart_id)->delete();
	}

	public function checkout(Request $request)
	{
		if (!$request->has('payment_type')) {
			return redirect('cart');
		}

		$cart = Cart::with(['product_details' => function ($query) {
			$query->with(
				'products_prices_details.currency',
				'products_shipping',
				'product_photos',
				'product_option'
			);
		}])
		->where('user_id', Auth::id())
		->orderBy('id', 'desc')
		->get();

		if($cart->count() == 0) {
			return redirect("/");
		}

		//check product quantity
		$unavailable = 0;
		$cart_id = array();
		foreach ($cart as $key => $cart_product) {
			$cart_id[] = $cart_product['id'];
			if ($cart_product->product_details->status == 'Inactive' || $cart_product->product_details->users->status == 'Inactive') {
				$unavailable++;
			}
			else if ($cart_product['total_quantity'] < $cart_product['quantity']) {
				$unavailable++;
			}

			if(strtolower($request->payment_type)=='cod'){
				if($cart_product->product_details->cash_on_delivery == 'No'){
					$this->helper->flash_message('danger', "Some products payment option not supported for Cash on delivery. Remove those products from cart and try again."); 
					return redirect('cart');
				}
			}

			if(strtolower($request->payment_type)=='cos'){
				if($cart_product->product_details->cash_on_store == 'No'){
					$this->helper->flash_message('danger', "Some products payment option not supported for cash on store. Remove those products from cart and try again."); 
					return redirect('cart');
				}
			}
		}

		if ($this->payment_helper->check_cart_payment($cart_id, $request->payment_type)) {
			if ($request->payment_type == "cod") {
				$payment_method_show = trans('messages.products.cash_on_delivery');
			}
			elseif ($request->payment_type == "cos") {
				$payment_method_show = trans('messages.products.cash_on_store');
			}
			else {
				$payment_method_show = trans('messages.cart.paypal');
			}

			$this->helper->flash_message('danger', "Some products payment option not supported for " . $payment_method_show . ". Remove those products from cart and try again.."); // Call flash message function
			return redirect('cart');
		}

		if ($unavailable != 0) {
			return redirect('cart');
		}

		$data['country'] = Country::where('status', 'Active')->get();
		$payment_method = $request->payment_type;
		if ($payment_method == "paypal") {
			$data['payment_method_show'] = "messages.cart.paypal";
		}
		elseif ($payment_method == "cc") {
			$data['payment_method_show'] = "messages.cart.credit_card";
		}
		elseif ($payment_method == "cod") {
			$data['payment_method_show'] = "messages.products.cash_on_delivery";
		}
		else {
			$data['payment_method_show'] = "messages.products.cash_on_store";
		}

		$data['payment_method'] = $payment_method;

		$data['cc_month'] = [];

		for ($i = 1; $i <= 12; $i++) {
			array_push($data['cc_month'], $i);
		}

		$year = date('Y');
		$data['current_month'] = date("n");

		$data['cc_year'] = [];
		for ($y = $year; $y <= $year + 20; $y++) {
			array_push($data['cc_year'], $y);
		}

		$stripe_secret = PaymentGateway::where('site', 'Stripe')->get();

		$data['publish_key'] = PaymentGateway::where('name', 'publish')->first()->value;

		$order_detail = Orders::where('buyer_id', @Auth::user()->id)->where('customer_id', '!=', '')->get();
		$card_detail = array();
		foreach ($order_detail as $key => $orderdetail) {
			try {
				$stripe = \Stripe\Stripe::setApiKey($stripe_secret[0]->value);

				$card = \Stripe\Customer::retrieve($orderdetail->customer_id);
				$card_detail[$key]['last4'] = '************' . $card->sources->data[0]->last4;
				$card_detail[$key]['customer_id'] = $orderdetail->customer_id;
				$card_detail[$key]['card_id'] = @$orderdetail->card_id;
			}
			catch (\Exception $e) {
				$card_detail[$key]['last4'] = '';
				$card_detail[$key]['customer_id'] = '';
				$card_detail[$key]['card_id'] = '';
			}
		}

		$card_detail = array_map("unserialize", array_unique(array_map("serialize", $card_detail)));

		$data['card_detail'] = $card_detail;

		return view('purchases.checkout', $data);
	}

	/**
	 * Appy Coupen Code Function
	 *
	 * @param array $request    Input values
	 * @return no
	 */
	public function apply_coupon(Request $request)
	{
		$coupon_code = $request->coupon_code;

		if ($coupon_code == '') {

			$interval = 'Enter_coupon';

		} else {

			$result = CouponCode::where('coupon_code', $coupon_code)->where('status', 'Active')->get();

			$interval = "Check_Expired_coupon";

			if ($result->count()) {

				$check_already_used = Orders::where('coupon_code', $coupon_code)->where('buyer_id', Auth::id())->get();

				if ($check_already_used->count()) {
					$interval = 'Already_Used_Coupon';
				} else {

					if ($result->count()) {
						$datetime1 = new DateTime(date('Y-m-d'));
						$datetime2 = new DateTime(date('Y-m-d', strtotime($result[0]->expired_at . ' +1 day')));

						if ($datetime1 < $datetime2) {
							$interval_diff = $datetime1->diff($datetime2);
							if ($interval_diff->days) {
								$interval = $interval_diff->days;
							} else {
								$interval = $interval_diff->h;
							}
						} else {
							$interval = "Expired_coupon";
						}

					} else {
						$interval = "Check_Expired_coupon";
					}

				}
			}
		}

		if ($interval != "Expired_coupon" && $interval != "Check_Expired_coupon" && $interval != 'Already_Used_Coupon' && $interval != "Enter_coupon") {

			$code = Session::get('currency');

			$users_where['users.status'] = 'Active';

			$cart = Cart::with([
				'product_details' => function ($query) use ($users_where) {
					$query->with([
						'products_prices_details' => function ($query) {
							$query->with('currency');
						},
						'products_shipping' => function ($query) {},
						'product_photos' => function ($query) {},
						'product_option' => function ($query) {},
						'users' => function ($query) use ($users_where) {$query->where($users_where);},
					]);
				},
			])->whereHas('product_details', function ($query) use ($users_where) {$query->where('products.status', 'Active')->where('products.admin_status', 'Approved')->whereHas('users', function ($query1) use ($users_where) {$query1->where($users_where);});})->where('user_id', Auth::id())->orderBy('add_cart.id', 'desc')->get();

			if ($request->payment_method == "cos") {
				$price = json_decode($this->payment_helper->price_calculation($cart, 'no'));
			} else {
				$price = json_decode($this->payment_helper->price_calculation($cart, 'yes'));
			}

			$data['couponamount'] = $this->payment_helper->currency_convert($result[0]->currency_code, $code, $result[0]->amount);

			if ($price->subtotal > $data['couponamount']) {
				$data['coupen_applied_total'] = number_format($price->total - $data['couponamount'], 2, '.', '');

				$data['coupon_amount'] = number_format($data['couponamount'], 2, '.', '');

				Session::forget('coupon_code');
				Session::forget('coupon_amount');
				Session::forget('remove_coupon');
				Session::forget('manual_coupon');
				Session::put('coupon_code', $coupon_code);
				Session::put('coupon_amount', $data['coupon_amount']);
				Session::put('manual_coupon', 'yes');
			} else {
				$data['message'] = trans('messages.checkout.big_coupon');
			}
		} else {
			if ($interval == "Expired_coupon") {
				$data['message'] = trans('messages.checkout.expired_coupon');
			} else if ($interval == "Already_Used_Coupon") {
				$data['message'] = trans('messages.checkout.already_used_coupon');
			} else if ($interval == "Enter_coupon") {
				$data['message'] = trans('messages.checkout.enter_coupon');
			} else {
				$data['message'] = trans('messages.checkout.invalid_coupon');
			}
		}

		return json_encode($data);
	}
	public function remove_coupon(Request $request)
	{
		Session::forget('coupon_code');
		Session::forget('coupon_amount');
		Session::forget('manual_coupon');
		Session::put('remove_coupon', 'yes');

		$code = Session::get('currency');

		$users_where['users.status'] = 'Active';

		$cart = Cart::with([
			'product_details' => function ($query) use ($users_where) {
				$query->with([
					'products_prices_details' => function ($query) {
						$query->with('currency');
					},
					'products_shipping' => function ($query) {},
					'product_photos' => function ($query) {},
					'product_option' => function ($query) {},
					'users' => function ($query) use ($users_where) {$query->where($users_where);},
				]);
			},
		])->whereHas('product_details', function ($query) use ($users_where) {$query->where('products.status', 'Active')->where('products.admin_status', 'Approved')->whereHas('users', function ($query1) use ($users_where) {$query1->where($users_where);});})->where('user_id', Auth::id())->orderBy('add_cart.id', 'desc')->get();

		if ($request->payment_method == "cos") {
			echo $this->payment_helper->price_calculation($cart, "no");
		} else {
			echo $this->payment_helper->price_calculation($cart, "yes");
		}

	}

}
