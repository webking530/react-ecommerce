<?php

namespace App\Http\Controllers\Api;

use App;
use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\BillingAddress;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\OrdersBillingAddress;
use App\Models\OrdersDetails;
use App\Models\OrdersReturn;
use App\Models\OrdersShippingAddress;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Models\Currency;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class CartController extends Controller {
	protected $payment_helper; // Global variable for Helpers instance

	/**
	 * Constructor to Set PaymentHelper instance in Global variable
	 *
	 * @param array $payment   Instance of PaymentHelper
	 */
	public function __construct(PaymentHelper $payment) {
		$this->payment_helper = $payment;
		$this->helper = new Helpers;
		App::setLocale('en');
	}
	/**
	 * add ,update, remove cart
	 * @param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function add_cart(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$user_details = User::where('id', $user_id)->first();
		$cart_count = $user_details->cart_count != '' ? $user_details->cart_count : '';

		$pro_opt = ProductOption::where('product_id', $request->product_id)->first();
		$pro_option = ProductOption::where('product_id', $request->product_id)->where('id', @$request->product_option)->first();
		if (isset($pro_opt) && !(isset($pro_option))) {
			$request->product_option = $pro_opt->id;
		}
		elseif (isset($pro_opt) && isset($pro_option)) {
			$request->product_option = $request->product_option;
		}
		else {
			$request->product_option = "";
		}

		$rules = array(
			'product_id' => 'required|integer',
			'cart_id' => 'integer',
			'product_option' => 'integer',
			'product_qty' => 'integer',
		);

		$validator = Validator::make($request->all(), $rules);

		$check_product = Product::with([
			'products_prices_details' => function ($query) {},
			'product_option' => function ($query) {},
		]);
		$check_product = $check_product->where('products.id', $request->product_id)->get();

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'status_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		} else if ($check_product->count() == '0') {
			return response()->json([

				'status_message' => 'products not available',

				'status_code' => '0',

			]);
		} else {
			if (@$request->remove != '1') {

				if (@$request->cart_id != '') {

					$cart = Cart::where('id', $request->cart_id)->first();
					if (isset($cart)) {
						if ($request->product_option != '') {
							if ($cart->option_id == $request->product_option && $cart->product_id == $request->product_id) {

								$update_cart = ['quantity' => @$request->product_qty != '' ? @$request->product_qty : '1'];
								Cart::where('id', $request->cart_id)->update($update_cart);
							} else if ($cart->option_id != $request->product_option && $cart->product_id != $request->product_id) {

								//check already exist or not
								$check_cart = Cart::where('user_id', $user_id)->where('product_id', $request->product_id)->where('option_id', $request->product_option)->first();
								if (isset($check_cart)) {
									$qty = @$request->product_qty + $check_cart->quantity;
									$update_cart = ['quantity' => @$qty];
									Cart::where('id', $check_cart->id)->update($update_cart);

									$cart->delete();
								} else {

									$update_cart = ['quantity' => @$request->product_qty != '' ? @$request->product_qty : '1',
										'option_id' => @$request->product_option,
										'product_id' => @$request->product_id,
									];
									Cart::where('id', $request->cart_id)->update($update_cart);

								}

							} else {
								$update_cart = ['quantity' => @$request->product_qty != '' ? @$request->product_qty : '1',
									'option_id' => @$request->product_option,
									'product_id' => @$request->product_id,
								];
								Cart::where('id', $request->cart_id)->update($update_cart);
							}

						} else {
							if ($cart->product_id == $request->product_id) {
								$update_cart = ['quantity' => @$request->product_qty != '' ? @$request->product_qty : '1'];
								Cart::where('id', $request->cart_id)->update($update_cart);
							}
						}

						$cart_products = Cart::where('user_id', $user_id)->get();
						if ($cart_products->count()) {
							foreach ($cart_products as $cart_product_details) {
								$products = Product::where('id', $cart_product_details->product_id)->first();
								$product_detail['cart_id'] = $cart_product_details->id;
								$product_detail['store_name'] = @$products->merchant_store->store_name != '' ? @$products->merchant_store->store_name : '';
								$product_detail['product_id'] = @$products->id;
								$product_detail['user_id'] = @$products->user_id;
								$product_detail['status'] = @$products->status;
								$product_detail['name'] = @$products->title;
								$product_detail['image_url'] = @$products->image_name;
								$product_detail['price'] = @$cart_product_details->option_id != "" && @$cart_product_details->option_id != 'NULL' && @$cart_product_details->option_id != '0' ? @$cart_product_details->product_option_details->price : @$products->price;
								$product_detail['shipping_type'] = @$products->shipping_type;
								$product_detail['sold_out'] = @$products->sold_out;
								$product_detail['total_quantity'] = @$products->total_quantity;
								$product_detail['qty'] = @$cart_product_details->quantity;
								$product_detail['option'] = @$cart_product_details->option_id != "" && @$cart_product_details->option_id != 'NULL' & @$cart_product_details->option_id != '0' ? @$cart_product_details->option_id : '';
								$product_detail['option_name'] = @$cart_product_details->option_id != "" && @$cart_product_details->option_id != 'NULL' & @$cart_product_details->option_id != '0' ? @$cart_product_details->product_option_details->option_name : '';
								//product options details
								$options = ProductOption::where('product_id', $cart_product_details->product_id)->get();
								if ($options->count()) {
									$product_option = [];
									foreach ($options as $opt) {									
										$product_option_id['id'] = $opt->id;
										$product_option_id['name'] = $opt->option_name;
										$product_option_id['available_qty'] = $opt->total_quantity;
										$product_option[] = $product_option_id;
										$product_detail['options'] = $product_option;
									}
								}
								else {
									$product_detail['options'] = [];
								}
								$product_option = '';
								$product_detail['available_qty'] = @$products->total_quantity;

								$product_user = User::where('id', $product_detail['user_id'])->first();

								if ($product_detail['status'] == 'Inactive' || $product_user['status'] == 'Inactive') {

									$product_detail['is_available'] = "Unavailable";
								}
								elseif ($product_detail['sold_out'] == 'Yes' || $product_detail['total_quantity'] <= 0) {
									$product_detail['is_available'] = "Sold Out";
								}
								else if ($options->count()) {
									$availableqty = ProductOption::where('id', $product_detail['option'])->first();
									if ($availableqty['sold_out'] == 'Yes' || $availableqty['total_quantity'] <= 0) {
										$product_detail['is_available'] = "Sold Out";
									}
									else if ($availableqty['total_quantity'] < $product_detail['qty']) {
										$product_detail['is_available'] = "Only " . $availableqty['total_quantity'] . " Products are available";
									}
									else {
										$product_detail['is_available'] = "";
									}
								}
								else {
									if ($product_detail['sold_out'] == 'Yes' || $product_detail['total_quantity'] <= 0) {
										$product_detail['is_available'] = "Sold Out";
									}
									else if ($product_detail['available_qty'] < $product_detail['qty']) {
										$product_detail['is_available'] = "Only " . $product_detail['available_qty'] . " Products are available";
									}
									else {
										$product_detail['is_available'] = "";
									}
								}
								$product_details[] = @$product_detail;
							}
						}
						$shippingaddress = ShippingAddress::where('user_id', $user_id)->first();
						if (isset($shippingaddress)) {
							$shipping_address['id'] = @$shippingaddress->id;
							$shipping_address['shipping_address'] = @$shippingaddress->address_line . ',' . @$shippingaddress->city . ',' . @$shippingaddress->state . ',' . @$shippingaddress->country . ',' . @$shippingaddress->postal_code;
							$address[] = @$shipping_address;
						} else {
							$address = [];
						}
						$billingaddress = BillingAddress::where('user_id', $user_id)->first();
						if (isset($billingaddress)) {
							$billing_address['id'] = @$billingaddress->id;
							$billing_address['billing_address'] = @$billingaddress->address_line . ',' . @$billingaddress->city . ',' . @$billingaddress->state . ',' . @$billingaddress->country . ',' . @$billingaddress->postal_code;
							$billaddress[] = @$billing_address;
						}
						else {
							$billaddress = [];
						}

						$data['cart'] = Cart::with([
							'product_details' => function ($query) {
								$query->with('products_prices_details','products_shipping','product_photos','product_option');
							},
						])->where('user_id', $user_id)->orderBy('add_cart.id', 'desc')->get();

						$cart_count = $data['cart']->count();

						$data = json_decode($this->payment_helper->price_calculation($data['cart'], 'yes'));

						$price['item_total'] = @$data->subtotal;
						$price['shipping'] = @$data->shipping_charge;
						$price['incremental_fee'] = @$data->incremental_fee;
						$price['service_fee'] = @$data->service;
						$price['order_total'] = @$data->total;

						return response()->json([
							'status_message' => 'Cart Updated successfully',

							'status_code' => '1',

							'product_details' => @$product_details,

							'price' => @$price,

							'shipping_address' => @$address,

							'billing_address' => @$billaddress,

							'cart_count' => @$cart_count,
						]);
					} else {
						return response()->json([
							'status_message' => 'No Cart available',

							'status_code' => '0',
						]);
					}

				} else {
					//create new cart
					$product_id = $request->product_id;
					$qty = $request->product_qty != '' ? $request->product_qty : '1';

					$option = ($request->product_option != '') ? $request->product_option : NULL;

					// Checking for Already Cart Or Not..
					$checked_cart = Cart::where(['product_id' => $product_id, 'user_id' => $user_id, 'option_id' => $option])->first();

					if (count($checked_cart) > 0) {
						$cart_id = $checked_cart->id;

						$cart = Cart::find($cart_id);

						$cart->quantity = $checked_cart->quantity + $qty;

					} else {
						$cart = new Cart;

						$cart->quantity = $qty;

						$cart->product_id = $product_id;

						$cart->user_id = $user_id;
					}
					if (@$option != '' && @$option != 'NULL') {
						$cart->option_id = $option;
					}

					$cart->save();
					$cart_products = Cart::where('user_id', $user_id)->get();
					$cart_count = count($cart_products);
					if (count($checked_cart) > 0) {
						$cart_products = Cart::where('user_id', $user_id)->get();

						if (count($cart_products)) {
							foreach ($cart_products as $cart_product_details) {
								$products = Product::where('id', $cart_product_details->product_id)->first();
								$product_detail['cart_id'] = $cart_product_details->id;
								$product_detail['store_name'] = @$products->merchant_store->store_name != '' ? @$products->merchant_store->store_name : '';
								$product_detail['product_id'] = @$products->id;
								$product_detail['name'] = @$products->title;
								$product_detail['status'] = @$products->status;
								$product_detail['user_id'] = @$products->user_id;
								$product_detail['image_url'] = @$products->image_name;
								$product_detail['price'] = @$cart_product_details->option_id != "" && @$cart_product_details->option_id != 'NULL' & @$cart_product_details->option_id != '0' ? @$cart_product_details->product_option_details->price : @$products->price;
								$product_detail['shipping_type'] = @$products->shipping_type;
								$product_detail['qty'] = @$cart_product_details->quantity;
								$product_detail['sold_out'] = @$products->sold_out;
								$product_detail['total_quantity'] = @$products->total_quantity;
								$product_detail['option'] = @$cart_product_details->option_id != "" && @$cart_product_details->option_id != 'NULL' & @$cart_product_details->option_id != '0' ? @$cart_product_details->option_id : '';
								$product_detail['option_name'] = @$cart_product_details->option_id != "" && @$cart_product_details->option_id != 'NULL' & @$cart_product_details->option_id != '0' ? @$cart_product_details->product_option_details->option_name : '';
								//product options details
								$options = ProductOption::where('product_id', $cart_product_details->product_id)->get();
								if (count($options)) {
									@$product_option = [];
									foreach ($options as $opt) {									
										@$product_option_id['id'] = $opt->id;
										@$product_option_id['name'] = $opt->option_name;
										@$product_option_id['available_qty'] = $opt->total_quantity;
										@$product_option[] = @$product_option_id;
										@$product_detail['options'] = @$product_option;
									}
								} else {
									@$product_detail['options'] = [];
								}
								@$product_option = '';
								$product_detail['available_qty'] = @$products->total_quantity;

								$product_user = User::where('id', $product_detail['user_id'])->first();

								if ($product_detail['status'] == 'Inactive' || $product_user['status'] == 'Inactive') {
									$product_detail['is_available'] = "Unavailable";
								} elseif ($product_detail['sold_out'] == 'Yes' || $product_detail['total_quantity'] <= 0) {
									$product_detail['is_available'] = "Sold Out";
								} else if (count($options)) {

									$availableqty = ProductOption::where('id', $product_detail['option'])->first();
									if ($availableqty['sold_out'] == 'Yes' || $availableqty['total_quantity'] <= 0) {
										$product_detail['is_available'] = "Sold Out";

									} else if ($availableqty['total_quantity'] < $product_detail['qty']) {
										$product_detail['is_available'] = "Only " . $availableqty['total_quantity'] . " Products are available";
									} else {
										$product_detail['is_available'] = "";
									}

								} else {
									if ($product_detail['sold_out'] == 'Yes' || $product_detail['total_quantity'] <= 0) {
										$product_detail['is_available'] = "Sold Out";
									} else if ($product_detail['available_qty'] < $product_detail['qty']) {
										$product_detail['is_available'] = "Only " . $product_detail['available_qty'] . " Products are available";
									} else {
										$product_detail['is_available'] = "";
									}

								}

								$product_details[] = @$product_detail;

							}
						}
						$shippingaddress = ShippingAddress::where('user_id', $user_id)->first();
						if (count($shippingaddress)) {
							$shipping_address['id'] = @$shippingaddress->id;
							$shipping_address['shipping_address'] = @$shippingaddress->address_line . ',' . @$shippingaddress->city . ',' . @$shippingaddress->state . ',' . @$shippingaddress->country . ',' . @$shippingaddress->postal_code;
							$address[] = @$shipping_address;
						} else {
							$address = [];
						}
						$billingaddress = BillingAddress::where('user_id', $user_id)->first();
						if (count($billingaddress)) {
							$billing_address['id'] = @$billingaddress->id;
							$billing_address['billing_address'] = @$billingaddress->address_line . ',' . @$billingaddress->city . ',' . @$billingaddress->state . ',' . @$billingaddress->country . ',' . @$billingaddress->postal_code;
							$billaddress[] = @$billing_address;
						} else {
							$billaddress = [];
						}
						$data['cart'] = Cart::with([
							'product_details' => function ($query) {
								$query->with([
									'products_prices_details' => function ($query) {},
									'products_shipping' => function ($query) {},
									'product_photos' => function ($query) {},
									'product_option' => function ($query) {},
								]);
							},
						])->where('user_id', $user_id)->orderBy('add_cart.id', 'desc')->get();
						$cart_count = count($data['cart']);
						$data = json_decode($this->payment_helper->price_calculation($data['cart'], 'yes'));

						$price['item_total'] = @$data->subtotal;
						$price['shipping'] = @$data->shipping_charge;
						$price['incremental_fee'] = @$data->incremental_fee;
						$price['service_fee'] = @$data->service;
						$price['order_total'] = @$data->total;

						return response()->json([
							'status_message' => 'Cart Updated successfully',

							'status_code' => '1',

							'product_details' => @$product_details,

							'price' => @$price,

							'shipping_address' => @$address,

							'billing_address' => @$billaddress,

							'cart_count' => @$cart_count,
						]);

					} else {
						return response()->json([
							'status_message' => 'Cart Added successfully',

							'status_code' => '1',

							'cart_count' => @$cart_count,
						]);
					}

				}
			} else {
				//Remove cart

				$rules = array(
					'cart_id' => 'required|integer',
					'remove' => 'integer|in:1',

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

						'cart_count' => @$cart_count,

					]);
				}
				$validator = Validator::make($request->all(), $rules);
				$cart = Cart::where('id', $request->cart_id)->where('user_id', $user_id)->first();
				if (count($cart)) {

					$remove_cart = Cart::where('user_id', $user_id)->where('id', $request->cart_id);

					$remove_cart->delete();
					$remain_cart_count = Cart::where('user_id', $user_id)->count();
					$data['cart'] = Cart::with([
						'product_details' => function ($query) {
							$query->with([
								'products_prices_details' => function ($query) {},
								'products_shipping' => function ($query) {},
								'product_photos' => function ($query) {},
								'product_option' => function ($query) {},
							]);
						},
					])->where('user_id', $user_id)->orderBy('add_cart.id', 'desc')->get();

					$cart_products = Cart::where('user_id', $user_id)->get();

					if (count($cart_products)) {
						foreach ($cart_products as $cart_product_details) {
							$products = Product::where('id', $cart_product_details->product_id)->first();
							$product_detail['cart_id'] = $cart_product_details->id;
							$product_detail['store_name'] = @$products->merchant_store->store_name != '' ? @$products->merchant_store->store_name : '';
							$product_detail['product_id'] = @$products->id;
							$product_detail['name'] = @$products->title;
							$product_detail['image_url'] = @$products->image_name;
							$product_detail['status'] = @$products->status;
							$product_detail['user_id'] = @$products->user_id;
							$product_detail['price'] = @$cart_product_details->option_id != "" && @$cart_product_details->option_id != 'NULL' & @$cart_product_details->option_id != '0' ? @$cart_product_details->product_option_details->price : @$products->price;
							$product_detail['shipping_type'] = @$products->shipping_type;
							$product_detail['qty'] = @$cart_product_details->quantity;
							$product_detail['sold_out'] = @$products->sold_out;
							$product_detail['total_quantity'] = @$products->total_quantity;
							$product_detail['option'] = @$cart_product_details->option_id != "" && @$cart_product_details->option_id != 'NULL' & @$cart_product_details->option_id != '0' ? @$cart_product_details->option_id : '';
							$product_detail['option_name'] = @$cart_product_details->option_id != 'NULL' & @$cart_product_details->option_id != '0' ? @$cart_product_details->product_option_details->option_name : '';
							//product options details
							$options = ProductOption::where('product_id', $cart_product_details->product_id)->get();
							if (count($options)) {
								@$product_option = [];
								foreach ($options as $opt) {									
									@$product_option_id['id'] = $opt->id;
									@$product_option_id['name'] = $opt->option_name;
									@$product_option_id['available_qty'] = $opt->total_quantity;
									@$product_option[] = @$product_option_id;
									@$product_detail['options'] = @$product_option;
								}
							} else {
								@$product_detail['options'] = [];
							}
							@$product_option = '';
							$product_detail['available_qty'] = @$products->total_quantity;

							$product_user = User::where('id', $product_detail['user_id'])->first();

							if ($product_detail['status'] == 'Inactive' || $product_user['status'] == 'Inactive') {
								$product_detail['is_available'] = "Unavailable";
							} elseif ($product_detail['sold_out'] == 'Yes' || $product_detail['total_quantity'] <= 0) {
								$product_detail['is_available'] = "Sold Out";
							} else if (count($options)) {

								$availableqty = ProductOption::where('id', $product_detail['option'])->first();
								if ($availableqty['sold_out'] == 'Yes' || $availableqty['total_quantity'] <= 0) {
									$product_detail['is_available'] = "Sold Out";

								} else if ($availableqty['total_quantity'] < $product_detail['qty']) {
									$product_detail['is_available'] = "Only " . $availableqty['total_quantity'] . " Products are available";
								} else {
									$product_detail['is_available'] = "";
								}

							} else {
								if ($product_detail['sold_out'] == 'Yes' || $product_detail['total_quantity'] <= 0) {
									$product_detail['is_available'] = "Sold Out";
								} else if ($product_detail['available_qty'] < $product_detail['qty']) {
									$product_detail['is_available'] = "Only " . $product_detail['available_qty'] . " Products are available";
								} else {
									$product_detail['is_available'] = "";
								}

							}

							$product_details[] = @$product_detail;

						}
					}
					$shippingaddress = ShippingAddress::where('user_id', $user_id)->first();
					if (count($shippingaddress)) {
						$shipping_address['id'] = @$shippingaddress->id;
						$shipping_address['shipping_address'] = @$shippingaddress->address_line . ',' . @$shippingaddress->city . ',' . @$shippingaddress->state . ',' . @$shippingaddress->country . ',' . @$shippingaddress->postal_code;
						$address[] = @$shipping_address;
					} else {
						$address = [];
					}
					$billingaddress = BillingAddress::where('user_id', $user_id)->first();
					if (count($billingaddress)) {
						$billing_address['id'] = @$billingaddress->id;
						$billing_address['billing_address'] = @$billingaddress->address_line . ',' . @$billingaddress->city . ',' . @$billingaddress->state . ',' . @$billingaddress->country . ',' . @$billingaddress->postal_code;
						$billaddress[] = @$billing_address;
					} else {
						$billaddress = [];
					}

					$data = json_decode($this->payment_helper->price_calculation($data['cart'], 'yes'));

					$price['item_total'] = @$data->subtotal;
					$price['shipping'] = @$data->shipping_charge;
					$price['incremental_fee'] = @$data->incremental_fee;
					$price['service_fee'] = @$data->service;
					$price['order_total'] = @$data->total;

					return response()->json([
						'status_message' => 'Cart removed successfully',

						'status_code' => '1',

						'remain_cart_count' => $remain_cart_count,

						'product_details' => @$product_details,

						'price' => @$price,

						'shipping_address' => @$address,

						'billing_address' => @$billaddress,

						'cart_count' => $remain_cart_count,

					]);
				} else {
					return response()->json([
						'status_message' => 'Cart Not available',

						'status_code' => '0',
					]);
				}
			}

		}

	}
	/**
	 * add ,update shipping address
	 * @param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function shipping_address(Request $request) {

		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$user_details = User::where('id', $user_id)->first();
		$rules = array(
			'type' => 'required|in:shipping,billing',
			'shipping_name' => 'required',
			'address1' => 'required',
			'city' => 'required',
			'country' => 'required',
			'zip_code' => 'required',
			'phone_number' => 'required|regex:/^[0-9]+$/|min:6',
		);
		$niceNames = array(
			'shipping_name' => 'Shipping Name',
			'address1' => 'Address1',
			'city' => 'City',
			'state' => 'State',
			'country' => 'Country',
			'zip_code' => 'Zip code',
			'phone_number' => 'Phone Number',
		);
		$validator = Validator::make($request->all(), $rules);
		$validator->setAttributeNames($niceNames);
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
			if ($request->type == 'shipping') {
				//To check shipping address already exist or not
				$shipping_address = ShippingAddress::where('user_id', $user_id)->first();

				if (count($shipping_address)) {
					//update shipping address
					$shipping = ShippingAddress::find($shipping_address->id);
					$shipping->user_id = $user_id;
					$shipping->name = $request->shipping_name;
					$shipping->address_line = $request->address1;
					$shipping->address_line2 = $request->address2;
					$shipping->address_nick = $request->shipping_nick_name;
					$shipping->city = $request->city;
					$shipping->country = $request->country;
					$shipping->state = $request->state;
					$shipping->postal_code = $request->zip_code;
					$shipping->phone_number = $request->phone_number;
					$shipping->save();
					return response()->json([
						'status_message' => 'Shipping address updated successfully',
						'status_code' => '1',
					]);

				} else {
					//insert shipping address
					$shipping = new ShippingAddress;
					$shipping->user_id = $user_id;
					$shipping->name = $request->shipping_name;
					$shipping->address_line = $request->address1;
					$shipping->address_line2 = $request->address2;
					$shipping->address_nick = $request->shipping_nick_name;
					$shipping->city = $request->city;
					$shipping->country = $request->country;
					$shipping->state = $request->state;
					$shipping->postal_code = $request->zip_code;
					$shipping->phone_number = $request->phone_number;
					$shipping->save();
					return response()->json([
						'status_message' => 'Shipping address added successfully',
						'status_code' => '1',
					]);
				}
			} elseif ($request->type == 'billing') {
				//To check billing address already exist or not
				$billing_address = BillingAddress::where('user_id', $user_id)->first();

				if (count($billing_address)) {
					//update billing address
					$billing = BillingAddress::find($billing_address->id);
					$billing->user_id = $user_id;
					$billing->name = $request->shipping_name;
					$billing->address_line = $request->address1;
					$billing->address_line2 = $request->address2;
					$billing->address_nick = $request->shipping_nick_name;
					$billing->city = $request->city;
					$billing->country = $request->country;
					$billing->state = $request->state;
					$billing->postal_code = $request->zip_code;
					$billing->phone_number = $request->phone_number;
					$billing->save();
					return response()->json([
						'status_message' => 'Billing address updated successfully',
						'status_code' => '1',
					]);

				} else {
					//insert billing address
					$billing = new BillingAddress;
					$billing->user_id = $user_id;
					$billing->name = $request->shipping_name;
					$billing->address_line = $request->address1;
					$billing->address_line2 = $request->address2;
					$billing->address_nick = $request->shipping_nick_name;
					$billing->city = $request->city;
					$billing->country = $request->country;
					$billing->state = $request->state;
					$billing->postal_code = $request->zip_code;
					$billing->phone_number = $request->phone_number;
					$billing->save();
					return response()->json([
						'status_message' => 'Billing address added successfully',
						'status_code' => '1',
					]);
				}
			}

		}

	}

	/**
	 * View shipping address
	 * @param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function view_shipping_address(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$rules = array(
			'type' => 'required|in:shipping,billing',
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
			if ($request->type == 'shipping') {
				$shipping_address = ShippingAddress::where('user_id', $user_id)->first();

				if (count($shipping_address)) {
					$shippingaddress['shipping_address_id'] = @$shipping_address->id;
					$shippingaddress['shipping_name'] = @$shipping_address->name;
					$shippingaddress['shipping_nick_name'] = @$shipping_address->address_nick != '' ? @$shipping_address->address_nick : '';
					$shippingaddress['address1'] = @$shipping_address->address_line;
					$shippingaddress['address2'] = @$shipping_address->address_line2 != '' ? @$shipping_address->address_line2 : '';
					$shippingaddress['city'] = @$shipping_address->city;
					$shippingaddress['state'] = @$shipping_address->state;
					$shippingaddress['country'] = @$shipping_address->country;
					$shippingaddress['zip_code'] = @$shipping_address->postal_code;
					$shippingaddress['phone_number'] = @$shipping_address->phone_number;
					$shipping[] = $shippingaddress;

					return response()->json([
						'status_message' => 'Shipping address listed successfully',
						'status_code' => '1',
						'shipping_details' => $shipping,
					]);
				} else {
					return response()->json([
						'status_message' => 'No Shipping Address',
						'status_code' => '0',
					]);
				}
			} elseif ($request->type == 'billing') {

				$billing_address = BillingAddress::where('user_id', $user_id)->first();
				if (count($billing_address)) {
					$billingaddress['shipping_address_id'] = @$billing_address->id;
					$billingaddress['shipping_name'] = @$billing_address->name;
					$billingaddress['shipping_nick_name'] = @$billing_address->address_nick != '' ? @$billing_address->address_nick : '';
					$billingaddress['address1'] = @$billing_address->address_line;
					$billingaddress['address2'] = @$billing_address->address_line2 != '' ? @$billing_address->address_line2 : '';
					$billingaddress['city'] = @$billing_address->city;
					$billingaddress['state'] = @$billing_address->state;
					$billingaddress['country'] = @$billing_address->country;
					$billingaddress['zip_code'] = @$billing_address->postal_code;
					$billingaddress['phone_number'] = @$billing_address->phone_number;
					$billing[] = $billingaddress;

					return response()->json([
						'status_message' => 'Billing address listed successfully',
						'status_code' => '1',
						'shipping_details' => $billing,
					]);
				} else {
					return response()->json([
						'status_message' => 'No Billing Address',
						'status_code' => '0',
					]);
				}

			}
		}
	}
	/**
	 * Shopping cart details
	 * @param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function shopping_cart(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$cart_products = Cart::where('user_id', $user_id)->get();

		if (count($cart_products)) {
			foreach ($cart_products as $cart_product_details) {
				$products = Product::where('id', $cart_product_details->product_id)->first();

				$product_detail['cart_id'] = $cart_product_details->id;
				$product_detail['store_name'] = @$products->merchant_store->store_name != '' ? @$products->merchant_store->store_name : '';
				$product_detail['product_id'] = @$products->id;
				$product_detail['sold_out'] = @$products->sold_out;
				$product_detail['total_quantity'] = @$products->total_quantity;
				$product_detail['name'] = @$products->title;
				$product_detail['image_url'] = @$products->image_name;
				$product_detail['status'] = @$products->status;
				$product_detail['user_id'] = @$products->user_id;
				$product_detail['price'] = @$cart_product_details->option_id != 'NULL' && @$cart_product_details->option_id != '' && @$cart_product_details->option_id != '0' ? @$cart_product_details->product_option_details->price : @$products->price;
				$product_detail['qty'] = @$cart_product_details->quantity;
				$product_detail['shipping_type'] = @$products->shipping_type;

				$product_detail['option'] = @$cart_product_details->option_id != 'NULL' && @$cart_product_details->option_id != '' && @$cart_product_details->option_id != '0' ? @$cart_product_details->option_id : '';
				$product_detail['option_name'] = @$cart_product_details->option_id != 'NULL' && @$cart_product_details->option_id != '' && @$cart_product_details->option_id != '0' ? @$cart_product_details->product_option_details->option_name : '';
				//product options details
				$options = ProductOption::where('product_id', $cart_product_details->product_id)->get();
				
				if (count($options)) {
					@$product_option = [];
					foreach ($options as $opt) {						
						@$product_option_id['id'] = $opt->id;
						@$product_option_id['name'] = $opt->option_name;
						@$product_option_id['available_qty'] = $opt->total_quantity;
						@$product_option[] = @$product_option_id;
						@$product_detail['options'] = @$product_option;
					}
				} else {
					@$product_detail['options'] = [];
				}
				@$product_option = '';
				$product_detail['available_qty'] = @$products->total_quantity;

				$product_user = User::where('id', $product_detail['user_id'])->first();

				if ($product_detail['status'] == 'Inactive' || $product_user['status'] == 'Inactive') {
					$product_detail['is_available'] = "Unavailable";
				} else if ($product_detail['sold_out'] == 'Yes' || $product_detail['total_quantity'] <= 0) {
					$product_detail['is_available'] = "Sold Out";
				} else if (count($options)) {

					$availableqty = ProductOption::where('id', $product_detail['option'])->first();
					if ($availableqty['sold_out'] == 'Yes' || $availableqty['total_quantity'] <= 0) {
						$product_detail['is_available'] = "Sold Out";

					} else if ($availableqty['total_quantity'] < $product_detail['qty']) {
						$product_detail['is_available'] = "Only " . $availableqty['total_quantity'] . " Products are available";
					} else {
						$product_detail['is_available'] = "";
					}

				} else {
					if ($product_detail['sold_out'] == 'Yes' || $product_detail['total_quantity'] <= 0) {
						$product_detail['is_available'] = "Sold Out";
					} else if ($product_detail['available_qty'] < $product_detail['qty']) {
						$product_detail['is_available'] = "Only " . $product_detail['available_qty'] . " Products are available";
					} else {
						$product_detail['is_available'] = "";
					}

				}
				$product_details[] = @$product_detail;
			}

			$data['cart'] = Cart::with([
				'product_details' => function ($query) {
					$query->with([
						'products_prices_details' => function ($query) {},
						'products_shipping' => function ($query) {},
						'product_photos' => function ($query) {},
						'product_option' => function ($query) {},
					]);
				},
			])->where('user_id', @$user_id)->orderBy('add_cart.id', 'desc')->get();

			$data = json_decode($this->payment_helper->price_calculation($data['cart'], 'yes'));

			

			$price['item_total'] = @$data->subtotal;
			$price['shipping'] = @$data->shipping_charge;
			$price['service_fee'] = @$data->service;
			$price['incremental_fee'] = @$data->incremental_fee;
			$price['order_total'] = @$data->total;
			$price['order_total_usd'] = (string)$this->payment_helper->currency_convert(api_currency_code,'USD',@$data->total);

			$shippingaddress = ShippingAddress::where('user_id', $user_id)->first();
			if (count($shippingaddress)) {
				$shipping_address['id'] = @$shippingaddress->id;
				$shipping_address['shipping_address'] = @$shippingaddress->name . ',' . @$shippingaddress->address_line . ',' . @$shippingaddress->city . ',' . @$shippingaddress->state . ',' . @$shippingaddress->country . '-' . @$shippingaddress->postal_code . ',' . @$shippingaddress->phone_number;
				$address[] = @$shipping_address;
			} else {
				$address = [];
			}
			$billingaddress = BillingAddress::where('user_id', $user_id)->first();
			if (count($billingaddress)) {
				$billing_address['id'] = @$billingaddress->id;
				$billing_address['billing_address'] = @$billingaddress->name . ',' . @$billingaddress->address_line . ',' . @$billingaddress->city . ',' . @$billingaddress->state . ',' . @$billingaddress->country . '-' . @$billingaddress->postal_code . ',' . @$billingaddress->phone_number;
				$billaddress[] = @$billing_address;
			} else {
				$billaddress = [];
			}

			return response()->json([

				'status_message' => 'Shopping Cart listed successfully',

				'status_code' => '1',

				'product_details' => $product_details,

				'price' => $price,

				'shipping_address' => @$address,

				'billing_address' => @$billaddress,

				'cart_count' => count($cart_products),


			]);
		} else {
			return response()->json([

				'status_message' => 'No Cart added',

				'status_code' => '0',
			]);

		}
	}

	/**
	 * order_detail
	 * @param  Get method request inputs
	 *
	 * @return Response Json
	 */
	public function order_detail(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$orders_detail = Orders::where('buyer_id', $user_id)->orderBy('id', 'desc')->get();
		$cart_products = Cart::where('user_id', $user_id)->get();
		$cart_count = count($cart_products);
		$order_active = array();
		$order_history = array();

		if (count($orders_detail)) {
			foreach ($orders_detail as $orders_details) {
				// display order details
				$order['id'] = $orders_details->id;
				$order['subtotal'] = $orders_details->subtotal;
				$order['shipping'] = $orders_details->shipping_fee;
				$order['incremental_fee'] = $orders_details->incremental_fee;
				$order['service'] = $orders_details->service_fee;
				$order['total'] = $orders_details->total;
				$order['payment_method'] = $orders_details->paymode;

				//display product details
				$order_detail = OrdersDetails::where('order_id', $orders_details->id)->orderBy('order_id', 'desc')->get();
				$detail_product = [];
				$check_active = 0;
				$check_history = 0;
				foreach ($order_detail as $details) {
					$products = Product::where('id', $details->product_id)->first();
					$product_detail['id'] = @$details->id;
					$product_detail['product_id'] = @$products->id;
					$product_detail['name'] = @$products->title;
					$product_detail['image_url'] = @$products->image_name;
					$product_detail['price'] = @$details->price;
					$product_detail['qty'] = @$details->quantity;
					$product_detail['shipping_type'] = @$products->shipping_type;
					$product_detail['total'] = @$details->price * @$details->quantity;
					if ($details->status == "Cancelled") {
						$product_detail['status'] = @$details->status . ' by ' . @$details->cancelled_by;
					} else if ($details->status == "Returned") {
						$order_return = OrdersReturn::where('order_id', @$details->id)->first();
						$product_detail['status'] = @$details->status . ' (' . @$order_return->status . ')';
					} else {
						$product_detail['status'] = @$details->status;
					}
					$product_detail['date'] = date('d M Y', strtotime(@$details->completed_at));
					$product_detail['return_policy'] = @$products->returns_policy->name;
					$product_detail['returns_policy'] = @$products->returns_policy->days;
					$return_available = "0";

					if ($product_detail['returns_policy'] != '0' && ($details->status == "Delivered" || $details->status == "Completed") && $details->status != "Returned") {

						$datesum = date('d-m-Y', strtotime(@$details->completed_at . ' + ' . $product_detail['returns_policy'] . ' days'));
						$today = date('d-m-Y');

						if (strtotime($datesum) > strtotime($today)) {
							$return_available = "1";
						} else {
							$return_available = '2';
						}
					} else {
						if ($details->status == "Pending" || $details->status == "Processing" && $details->status != "Cancelled") {

							$return_available = '0';
						} else {
							$return_available = '2';
						}

					}
					$product_detail['return_available'] = $return_available;

					if ($details->status == "Pending" || $details->status == "Processing" || ($details->status == "Returned" && ($details->return_status == "Requested" || $details->return_status == "Approved"))) {
						$check_active = 1;
					}
					$detail_product[] = $product_detail;
				}

				$order['product_details'] = $detail_product;

				$detail_product = '';
				$shippingaddress = OrdersShippingAddress::where('order_id', $orders_details->id)->first();
				if (count($shippingaddress)) {
					$shipping_address['id'] = @$shippingaddress->id;

					if (@$shippingaddress->address_line != '' && @$shippingaddress->address_line2 != '') {
						$ship_address = @$shippingaddress->address_line . ',' . @$shippingaddress->address_line2;
					} else if (@$shippingaddress->address_line != '' && @$shippingaddress->address_line2 == '') {
						$ship_address = @$shippingaddress->address_line;
					}

					$shipping_address['shipping_address'] = @$ship_address . ',' . @$shippingaddress->city . ',' . @$shippingaddress->state . ',' . @$shippingaddress->country . '-' . @$shippingaddress->postal_code;
					$order['shipping_address'] = @$shipping_address;
				} else {
					$order['shipping_address'] = [];
				}
				$billingaddress = OrdersBillingAddress::where('order_id', $orders_details->id)->first();
				if (count($billingaddress)) {
					$billing_address['id'] = @$billingaddress->id;

					if (@$billingaddress->address_line != '' && @$billingaddress->address_line2 != '') {
						$bill_address = @$billingaddress->address_line . ',' . @$billingaddress->address_line2;
					} else if (@$billingaddress->address_line != '' && @$billingaddress->address_line2 == '') {
						$bill_address = @$billingaddress->address_line;
					}

					$billing_address['billing_address'] = @$bill_address . ',' . @$billingaddress->city . ',' . @$billingaddress->state . ',' . @$billingaddress->country . '-' . @$billingaddress->postal_code;
					$order['billing_address'] = @$billing_address;
				} else {
					$order['billing_address'] = [];
				}
				$orders[] = $order;

				$pending_count = OrdersDetails::where('order_id', $orders_details->id)->where('status', 'Pending')->orWhere('status', 'Processing')->get()->count();

				// if($details->status=="Pending" || $details->status=="Processing" || ($details->status=="Returned" && ($details->return_status=="Requested" || $details->return_status=="Approved")))
				//   {
				//     $order_active[] = $order;
				//   }
				// else
				// {
				//   $order_history[] = $order;
				// }

				if ($check_active == 1) {
					$order_active[] = $order;
				} else {
					$order_history[] = $order;
				}
			}
			return response()->json([

				'status_message' => 'Orders details Listed Successfully',

				'status_code' => '1',

				'order_details' => $orders,

				'order_details_active' => $order_active,

				'order_details_history' => $order_history,

				'active_count' => count($order_active),

				'history_count' => count($order_history),

				'cart_count' => $cart_count,

			]);
		} else {
			return response()->json([

				'status_message' => 'Orders details Listed Successfully',

				'status_code' => '1',

				'order_details_active' => [],

				'order_details_history' => [],

				'active_count' => 0,

				'history_count' => 0,

				'cart_count' => $cart_count,
			]);

		}
	}
}
