<?php

/**
 * Payment Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Payment
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\BillingAddress;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Notifications;
use App\Models\Orders;
use App\Models\OrdersBillingAddress;
use App\Models\OrdersDetails;
use App\Models\OrdersShippingAddress;
use App\Models\PaymentGateway;
use App\Models\PayoutPreferences;
use App\Models\Payouts;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductShipping;
use App\Models\ShippingAddress;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Session;

class PaymentController extends Controller {
	protected $omnipay; // Global variable for Omnipay instance

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
	 * Setup the Omnipay PayPal API credentials
	 *
	 * @param string $gateway  PayPal Payment Gateway Method as PayPal_Express/PayPal_Pro
	 * PayPal_Express for PayPal account payments, PayPal_Pro for CreditCard payments
	 */
	public function setup($gateway = 'PayPal_Express') {
		$this->omnipay = Omnipay::create($gateway);

		if ($gateway == 'Stripe') {
			// Get Stripe Credentials From payment_gateway table
			$stripe_credentials = PaymentGateway::where('site', 'Stripe')->get();
			$this->omnipay->setApiKey(@$stripe_credentials['0']->value);
			return;
		}

		// Get PayPal credentials from payment_gateway table
		$paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();

		// Create the instance of Omnipay

		$this->omnipay->setUsername($paypal_credentials[0]->value);
		$this->omnipay->setPassword($paypal_credentials[1]->value);
		$this->omnipay->setSignature($paypal_credentials[2]->value);
		$this->omnipay->setTestMode(($paypal_credentials[3]->value == 'sandbox') ? true : false);
		if ($gateway == 'PayPal_Express') {
			$this->omnipay->setLandingPage('Login');
		}

	}

	/**
	 * Load Payment view file
	 *
	 * @param $request  Input values
	 * @return payment page view
	 */
	public function index(Request $request) {

	}

	/**
	 * Payment Submit Function
	 *
	 * @param array $request    Input values
	 * @return redirect to Dashboard Page
	 */
	public function check_cart_soldout($cart_array = array()) {
		$check_cart = Cart::with([
				'product_details' => function ($query) {
					$query->with(
						'products_prices_details.currency',
						'products_shipping',
						'product_photos',
						'product_option'
					);
				},
			])
			->whereIn('id', $cart_array)
			->orderBy('id', 'desc')
			->get();
		$soldout = false;
		foreach ($check_cart as $value) {
			if ($value->product_details->sold_out == "Yes") {
				$soldout = true;
			} else if ($value->product_details->product_option->count()) {
				foreach ($value->product_details->product_option as $value_option) {
					if ($value->option_id == $value_option->id) {
						if ($value_option->total_quantity <= 0 || $value_option->sold_out == "Yes") {
							$soldout = true;
						}
					}
				}
			} else {
				if ($value->product_details->total_quantity <= 0 || $value->product_details->sold_out == "Yes") {
					$soldout = true;
				}
			}
		}
		return $soldout;

	}

	public function create_booking(Request $request) {

		//dd($request->all());

		// Get PayPal credentials from payment_gateway table
		$final_item = array();
		if ($this->check_cart_soldout($request->cart_id)) {
			$this->helper->flash_message('danger', "Product has been sold out"); // Call flash message function
			return redirect('cart');
		}

		if ($this->payment_helper->check_cart_payment($request->cart_id, $request->payment_method)) {
			if ($request->payment_method == "cod") {
				$payment_method_show = trans('messages.products.cash_on_delivery');
			} elseif ($request->payment_method == "cos") {
				$payment_method_show = trans('messages.products.cash_on_store');
			} else {
				$payment_method_show = trans('messages.cart.paypal');
			}

			$this->helper->flash_message('danger', "Some products payment option not supported for " . $payment_method_show . ". Remove those products from cart and try again.."); // Call flash message function
			return redirect('cart');
		}

		if ($request->payment_method == "cod") {
			$cart = Cart::where('user_id', Auth::id())->get();
			$payment_description = implode(',', @$request->cart_id);
			$price_list = json_decode($this->payment_helper->price_calculation($cart, "yes"), true);
			$purchaseData['custom'] = $payment_description;
			Session::put('checkout_payment', $purchaseData);
			Session::put('checkout_prices', $price_list);
			Session::save();
			$this->store("", $request->payment_method);
			$this->helper->flash_message('success', trans('messages.order.order_success'));
			return redirect('purchases');
		} elseif ($request->payment_method == "cos") {
			$cart = Cart::where('user_id', Auth::id())->get();
			$payment_description = implode(',', @$request->cart_id);
			$price_list = json_decode($this->payment_helper->price_calculation($cart), true);
			$purchaseData['custom'] = $payment_description;
			Session::put('checkout_payment', $purchaseData);
			Session::put('checkout_prices', $price_list);
			Session::save();
			$this->store("", $request->payment_method);
			$this->helper->flash_message('success', trans('messages.order.order_success'));
			return redirect('purchases');
		} else {
			if (count($request->cart_id)) {
				for ($i = 0; $i < count($request->cart_id); $i++) {
					$item_array['name'] = $request->product_name[$i];
					$item_array['price'] = $this->payment_helper->currency_convert_original(Session::get('currency'), 'USD', $request->product_price[$i]);
					$item_array['quantity'] = $request->product_quantity[$i];
					$final_item[] = $item_array;
				}
			}

			$paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();
			$cart = Cart::where('user_id', Auth::id())->get();

			$price_list = json_decode($this->payment_helper->price_calculation($cart, "yes"), true);

			$shipping_fee = $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $price_list['shipping_charge']);
			$incremental_fee = $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $price_list['incremental_fee']);
			$coupon_amount = $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $price_list['coupon_amount']);

			if ($shipping_fee != 0 && $shipping_fee != '') {
				$item_array['name'] = "Shipping Fee";
				$item_array['price'] = $shipping_fee;
				$item_array['quantity'] = 1;
				$final_item[] = $item_array;
			}

			if ($incremental_fee != 0 && $incremental_fee != '') {
				$item_array['name'] = "Incremental Fee";
				$item_array['price'] = $incremental_fee;
				$item_array['quantity'] = 1;
				$final_item[] = $item_array;
			}

			if ($coupon_amount != 0 && $coupon_amount != '') {}{
				$item_array['name'] = "Coupon";
				$item_array['price'] = -$coupon_amount;
				$item_array['quantity'] = 1;
				$final_item[] = $item_array;
			}

			$item_array['name'] = "Service fee";
			$item_array['price'] = $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $price_list['service']);
			$item_array['quantity'] = 1;
			$final_item[] = $item_array;

			$payment_description = implode(',', @$request->cart_id);

			$amount = $price_list['total'];

			// $paypal_price        = $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $price_list['total']);
			$paypal_price = 0;

			foreach ($final_item as $item) {
				$paypal_price += (@$item['quantity'] * (@$item['price'] - 0));
			}
			//dd($paypal_price);

			$purchaseData =
				[
				'testMode' => ($paypal_credentials[3]->value == 'sandbox') ? true : false,
				'amount' => $paypal_price,
				'currency' => "USD",
				'returnUrl' => url('payments/success'),
				'cancelUrl' => url('payments/cancel'),
				'custom' => $payment_description,
			];

			//Put params on the session
			Session::forget('checkout_payment');
			Session::forget('checkout_prices');
			Session::put('checkout_payment', $purchaseData);
			Session::put('checkout_prices', $price_list);
			Session::save();

			//Stripe Credut Card

			if ($request->payment_method == 'cc') {

				try
				{
					$stripe_credentials = PaymentGateway::where('site', 'Stripe')->get();

					// Credit card payment method
					$this->setup('Stripe');
					// $credit_card_details = Session::get('payment_credit_card');

					// $card = [
					// 	'number' => $credit_card_details['card_number'],
					// 	'expiryMonth' => $credit_card_details['cc_expire_month'],
					// 	'expiryYear' => $credit_card_details['cc_expire_year'],
					// 	'cvv' => $credit_card_details['cvv'],
					// ];
					if ($request->customer_id != '') {

						\Stripe\Stripe::setApiKey(@$stripe_credentials[0]->value);
						$card = \Stripe\Customer::retrieve($request->customer_id);
						$card_id = $card->sources->data[0]->id;
						$customer_id = $request->customer_id;

					} else {

						\Stripe\Stripe::setApiKey(@$stripe_credentials[0]->value);

						// // Create Token for Stripe
						// $token_response = \Stripe\Token::create(array(
						// 	"card" => array(
						// 		'number' => preg_replace("/[^0-9,.]/", "", $credit_card_details['card_number']),
						// 		'exp_month' => $credit_card_details['cc_expire_month'],
						// 		'exp_year' => $credit_card_details['cc_expire_year'],
						// 		'cvc' => $credit_card_details['cvv'],
						// 	),
						// ));

						// $token = $token_response->id;

						//dd($request->stripeToken);
						$token = $request->stripeToken;
						$email = @Auth::user()->email;
						//dd($token);
						$customer = \Stripe\Customer::create(array(
							"email" => $email,
							"source" => $token,
						));

						$card = \Stripe\Customer::retrieve($customer->id);
						$card_id = $card->sources->data[0]->id;
						$customer_id = $customer->id;
					}

					$charge = \Stripe\Charge::create([
						'amount' => intval($amount) * 100,
						'currency' => 'USD',
						'customer' => $customer_id,
					]);

					//dd($charge->id);

					$checkout_payment['card_id'] = $card_id;

					$checkout_payment['customer_id'] = $customer_id;

					$checkout_payment['transaction_id'] = $charge->id;

					$code = $this->store($checkout_payment, 'credit card');

					$this->helper->flash_message('success', trans('messages.order.order_success'));
					return redirect('purchases');

				} catch (\Exception $e) {
					dd($e);
					Session::forget('payment_credit_card');
					$this->helper->flash_message('danger', $e->getMessage());
					return redirect('cart');
				}

				$purchaseData['token'] = $token;
			} else {
				$this->setup();
			}

			if ($request->payment_method != 'cc') {

				if ($amount) {
					$response = $this->omnipay->purchase($purchaseData)->setItems($final_item)->send();

					// Process response
					if ($response->isSuccessful()) {
						// Payment was successful
						$result = $response->getData();

						$checkout_payment = Session::get('checkout_payment');

						$checkout_payment['transaction_id'] = $request->payment_method == 'cc' ? @$result['id'] : @$result['TRANSACTIONID'];
						$code = $this->store($checkout_payment, 'credit card');

						$this->helper->flash_message('success', trans('messages.order.order_success'));
						return redirect('purchases');
					} else if ($response->isRedirect()) {
						// Redirect to offsite payment gateway
						$response->redirect();
					} else {
						$this->helper->flash_message('error', $response->getMessage()); // Call flash message function
						return redirect('/');
					}
				}
			}
		}
	}

	/**
	 * Callback function for Payment Success
	 *
	 * @param array $request    Input values
	 * @return redirect to Payment Success Page
	 */
	public function success(Request $request) {

		$this->setup();
		$checkout_payment = Session::get('checkout_payment');

		// dd($checkout_payment['amount'],$checkout_payment);

		$amount = isset($checkout_payment['amount']) ? $checkout_payment['amount'] :$checkout_payment->amount;

		$transaction = $this->omnipay->completePurchase(array(
			'payer_id' => $request->PayerID,
			'transactionReference' => $request->token,
			'amount' => $amount,
			'currency' => "USD",
		));

		$response = $transaction->send();

		$result = $response->getData();

		if (@$result['ACK'] == 'Success') {
			$checkout_payment['transaction_id'] = $result['PAYMENTINFO_0_TRANSACTIONID'];
			$this->store($checkout_payment);
			$this->helper->flash_message('success', trans('messages.order.order_success'));
			return redirect('purchases');
		} else {
			// Payment failed
			$this->helper->flash_message('error', $result['L_SHORTMESSAGE0']); // Call flash message function
			return redirect('/');
		}
	}

	/**
	 * Callback function for Payment Failed
	 *
	 * @param array $request    Input values
	 * @return redirect to Payments Booking Page
	 */
	public function cancel(Request $request) {
		$checkout_prices = Session::get('checkout_prices');
		$checkout_payment = Session::get('checkout_payment');

		if ($checkout_prices && $checkout_payment) {
			$this->helper->flash_message('danger', trans('messages.order.payment_cancelled'));
		}
		// Call flash message function

		return redirect('/');
	}

	/**
	 * Create Orders After Payment Successfully Done
	 *
	 * @param array $data    Payment Data
	 * @return string $code  Reservation Code
	 */
	public function store($data, $payment_method = "paypal") {
		//dd($payment_method);
		$user_id = Auth::id();
		$checkout_prices = Session::get('checkout_prices');

		$checkout_payment = Session::get('checkout_payment');
		$orders_data['buyer_id'] = $user_id;
		if ($payment_method == "paypal" || $payment_method == 'cc') {
			$orders_data['transaction_id'] = $data['transaction_id'];
		}
		if ($payment_method == 'credit card') {
			$orders_data['card_id'] = $data['card_id'];
			$orders_data['customer_id'] = $data['customer_id'];
			$orders_data['transaction_id'] = $data['transaction_id'];
		}

		$orders_data['subtotal'] = $checkout_prices['subtotal'];
		$orders_data['shipping_fee'] = $checkout_prices['shipping_charge'];
		$orders_data['incremental_fee'] = $checkout_prices['incremental_fee'];
		$orders_data['service_fee'] = $checkout_prices['service'];
		$orders_data['merchant_fee'] = $checkout_prices['merchant_fee'];
		$orders_data['total'] = $checkout_prices['total'];
		$orders_data['currency_code'] = Session::get('currency');

		if ($checkout_prices['coupon_amount']) {
			$orders_data['coupon_code'] = $checkout_prices['coupon_code'];
			$orders_data['coupon_amount'] = $coupon_amount = $checkout_prices['coupon_amount'];
		}

		$orders_data['paymode'] = $payment_method;
		$orders_data['updated_at'] = date('Y-m-d H:i:s');
		$orders_data['created_at'] = date('Y-m-d H:i:s');

		//dd($orders_data);

		$order_id = Orders::insertGetId($orders_data);

		//store the billing and shipping address for later uses to show admin panel and orders pages
		$billing = BillingAddress::where('user_id', $user_id)->where('is_default', 'yes')->first()->tojson();
		$billing = json_decode($billing, true);
		unset($billing['id'], $billing['user_id'], $billing['is_default'], $billing['created_at'], $billing['updated_at']);
		$billing['order_id'] = $order_id;
		OrdersBillingAddress::insert($billing);

		//shipping no use in cos - cash on store
		if ($payment_method != "cos") {
			$shipping_user = ShippingAddress::where('user_id', $user_id)->where('is_default', 'yes')->first()->tojson();
			$shipping_user = json_decode($shipping_user, true);
			unset($shipping_user['id'], $shipping_user['user_id'], $shipping_user['is_default'], $shipping_user['created_at'], $shipping_user['updated_at']);
			$shipping_user['order_id'] = $order_id;
			OrdersShippingAddress::insert($shipping_user);
			$shipping = ShippingAddress::where('user_id', $user_id)->where('is_default', 'yes');
			$country = $shipping->first()->country;
			$carts = Cart::with([
				'product_details' => function ($query) {
					$query->with([
						'products_prices_details' => function ($query) {},
						'product_photos' => function ($query) {},
						'returns_policy' => function ($query) {},
						'exchanges_policy' => function ($query) {},
					]);
				},
				'product_option_details' => function ($query) {},
				'product_shipping_details' => function ($query) use ($country) {
					$query->where('ships_to', $country);
				},
			])->where('user_id', $user_id);
		} else {
			$carts = Cart::with([
				'product_details' => function ($query) {
					$query->with([
						'products_prices_details' => function ($query) {},
						'product_photos' => function ($query) {},
						'returns_policy' => function ($query) {},
						'exchanges_policy' => function ($query) {},
					]);
				},
				'product_option_details' => function ($query) {},
			])->where('user_id', $user_id);
		}

		$carts = $carts->get();
		$merchant_ids = array();

		foreach ($carts as $cart) {
			$orders_details['order_id'] = $order_id;
			$orders_details['product_id'] = $cart->product_id;
			$orders_details['option_id'] = $cart->option_id;
			$orders_details['quantity'] = $cart->quantity;
			$orders_details['status'] = "Pending";

			$qty = $cart->quantity;
			$tot_shipping_charge = 0;
			$tot_incremental_fee = 0;
			$shipping = 0;

			$orders_details['shipping'] = 0;
			$orders_details['incremental'] = 0;

			// $user_id = Auth::user()->id;
			$shipping_address = ShippingAddress::where('user_id', $user_id)->first();
			if ($shipping_address != '') {
				$country = $shipping_address->country;
				$shipping_country = ProductShipping::where('product_id', $orders_details['product_id'])->where('ships_to', $country)->first();
				if (isset($shipping_country)) {
					if ($shipping_country->shipping_type == 'Flat Rates') {
						$charge = $shipping_country->charge != NULL ? $shipping_country->charge : 0;

						$incremental_fee = $shipping_country->incremental_fee != NULL ? $shipping_country->incremental_fee : 0;

						$tot_shipping_charge += $charge;

						$tot_incremental_fee += ($incremental_fee * ($qty - 1));

						$shipping += $charge + ($incremental_fee * ($qty - 1));

						$orders_details['shipping'] = $tot_shipping_charge;

						$orders_details['incremental'] = $tot_incremental_fee;
					}
				}
			}

			if ($payment_method == "cos") {
				$orders_details['shipping'] = 0;
				$orders_details['incremental'] = 0;
				$orders_details['status'] = "Completed";
			}

			if (isset($cart->product_option_details->id)) {
				$orders_details['price'] = $cart->product_option_details->price;
				$update_option_quantity['total_quantity'] = ($cart->product_option_details->total_quantity) - $cart->quantity;
				$update_option_quantity['sold'] = ($cart->product_option_details->sold) + $cart->quantity;
				ProductOption::where('id', $cart->product_option_details->id)->where('product_id', $cart->product_option_details->product_id)->update($update_option_quantity);
			} else {
				$orders_details['price'] = $cart->product_details->products_prices_details->price;
			}

			$product_service = json_decode($this->payment_helper->products_service_fee($orders_details['price'], $orders_details['quantity'], $orders_details['shipping'], $orders_details['incremental']), true);
			$orders_details['service'] = $product_service['service'];

			$product_merchant_fee = json_decode($this->payment_helper->products_merchant_fee($orders_details['price'], $orders_details['quantity'], $orders_details['shipping'], $orders_details['incremental']), true);
			$orders_details['merchant_fee'] = $product_merchant_fee['merchant_fee'];

			$applied_owe_amount = 0;
			if ($payment_method == "cos" && $cart->product_details->returns_policy->days == "0") {
				$orders_details['owe_amount'] = $orders_details['service'] + $orders_details['merchant_fee'];
				$orders_details['remaining_owe_amount'] = $orders_details['owe_amount'];
			} else {
				$orders_details['owe_amount'] = 0;
				$orders_details['remaining_owe_amount'] = 0;
			}
			$orders_details['applied_owe_amount'] = $applied_owe_amount;

			$update_quantity['total_quantity'] = ($cart->product_details->total_quantity) - $cart->quantity;
			$update_quantity['sold'] = ($cart->product_details->sold) + $cart->quantity;

			if (@$update_quantity['total_quantity'] == '0') {
				$update_quantity['sold_out'] = 'Yes';
			}

			Product::where('id', $cart->product_details->id)->update($update_quantity);

			if (@$update_quantity['total_quantity'] == '0') {

				$email_controller = new EmailController();
				$merchant = User::where('id', $cart->product_details->user_id)->first();
				$er = $email_controller->order_custom_notification($merchant->email, $merchant->full_name, "Your product quantity Sold Out", "Your product quantity Sold Out", "Add Quantity", url('merchant/edit_product') . "/" . $cart->product_details->id);

			}

			$orders_details['merchant_id'] = $cart->product_details->user_id;
			array_push($merchant_ids, $cart->product_details->user_id);
			$orders_details['return_policy'] = $cart->product_details->returns_policy->days;
			$orders_details['exchange_policy'] = $cart->product_details->exchanges_policy->days;
			$orders_details['updated_at'] = date('Y-m-d H:i:s');
			$orders_details['created_at'] = date('Y-m-d H:i:s');
			$orders_details_id = OrdersDetails::insertGetId($orders_details);

			//store activity data in notification table
			$activity_data = new Notifications;
			$activity_data->order_id = $order_id;
			$activity_data->order_details_id = $orders_details_id;
			$activity_data->user_id = $user_id;
			$activity_data->notify_id = $cart->product_details->user_id;
			$activity_data->product_id = $cart->product_id;
			$activity_data->notification_type = "order";
			$activity_data->notification_type_status = "pending";
			$activity_data->notification_message = "Placed the orders for you products";
			$activity_data->save();

		}
		$buyer = User::where('id', $user_id)->first();

		$email_controller = new EmailController();
		//Email notification for buyer and merchant - Order placing
		$email_controller->order_notification("Buyer", $buyer->email, ucfirst($buyer->full_name), "Your Order Placed Successfully", $user_id, "Order Placed", $order_id, "Your Order Placed Successfully");

		if (count($merchant_ids)) {
			$mail_array = array();
			for ($i = 0; $i < count($merchant_ids); $i++) {
				$email_controller = new EmailController();
				$merchant = User::where('id', $merchant_ids[$i])->first();
				if (!in_array($merchant->email, $mail_array)) {
					array_push($mail_array, $merchant->email);
					$er = $email_controller->order_notification("Merchant", $merchant->email, ucfirst($merchant->full_name), "You have a new Order", $merchant->id, "Order Placed", $order_id, "You have a new Order.");
				}
			}
		}

		///remove datas from cart
		$cart_id = explode(',', $checkout_payment['custom']);
		Cart::whereIn('id', $cart_id)->delete();
		Session::forget('checkout_payment');
		Session::forget('checkout_prices');
		Session::forget('coupon_code');
		Session::forget('coupon_amount');
		Session::forget('remove_coupon');
		Session::forget('manual_coupon');
		Session::forget('payment_credit_card');
	}

	/**
	 * Merchant Payout
	 *
	 * @param array $request    Input values
	 * @return redirect     to Order details view
	 */
	public function payout(Request $request, EmailController $email_controller) {

		$payout_email_id = $request->payout_email_id;

		
	
		$amount = $request->amount;

		$redirect_url = ADMIN_URL . '/view_order/' . $request->order_id;

		$payout_check = PayoutPreferences::where('user_id', $request->merchant_id)->where('default', 'yes')->first();

		$orders_details = Orders::where('id', $request->order_id)->first();
		//Stripe Payout
		    $payout_currency = 'USD';
			
		if ($payout_check->payout_method == 'Stripe') {
			$payout_currency = $payout_check->currency_code;
			$rate = Currency::where('code', 'USD')->first()->rate;
			$session_rate = Currency::where('code', $payout_check->currency_code)->first()->rate;

			$amount1 = round(($amount / $rate) * $session_rate);

			$stripe_credentials = PaymentGateway::where('site', 'Stripe')->pluck('value', 'name');
			$this->omnipay = Omnipay::create('Stripe');
			$this->omnipay->setApiKey(@$stripe_credentials['secret']);
			try
			{
				$response = $this->omnipay->transfer([
					'amount' => $amount1,
					'currency' => $payout_currency,
					'destination' => $payout_email_id,
					'transfer_group' => $orders_details->transaction_id,
				])->send();
			} catch (\Exception $e) {
				$this->helper->flash_message('danger', $e->getMessage());
				return redirect($redirect_url);
			}
			if ($response->isSuccessful()) {
				$response_data = $response->getData();

				$correlation_id = @$response_data['id'];
			} else {
				$this->helper->flash_message('danger', $response->getMessage());
				return redirect($redirect_url);
			}

		} else {
			// Set request-specific fields.
			$vEmailSubject = 'PayPal Payment';
			$emailSubject = urlencode($vEmailSubject);
			$receiverType = urlencode($payout_email_id);
			$currency = $payout_currency; // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

			$data = [
				'sender_batch_header' => [
					'email_subject' => "$emailSubject",
				],
				'items' => [
					[
						'recipient_type' => "EMAIL",
						'amount' => [
							'value' => "$amount",
							'currency' => "$payout_currency",
						],
						'receiver' => "$payout_email_id",
						'note' => 'payment of commissions',
						'sender_item_id' => "$request->order_id",
					],
				],
			];

			$data = json_encode($data);

			$payout_response = $this->paypal_payouts($data);



			if (@$payout_response->name == 'INTERNAL_ERROR') {
				$this->helper->flash_message('error', 'Payout failed : ' . @$payout_response->name);
				return redirect($redirect_url);
			}

			if (@$payout_response != "error") {
				if (@$payout_response->batch_header->batch_status == "PENDING" || $payout_response->batch_header->batch_status == "SUCCESS") {
					$correlation_id = $payout_response->batch_header->payout_batch_id;
				} else {
					// Call flash message function
					$this->helper->flash_message('error', 'Payout failed : ' . @$payout_response->name);
					return redirect($redirect_url);
				}
			} else {
				// Call flash message function
				$this->helper->flash_message('error', 'Payout failed : Token Error or Client ID or Secret mismatch');
				return redirect($redirect_url);
			}

		}

		if ($correlation_id == '') {
			$this->helper->flash_message('error', 'Payout failed : Please try again.');
			return redirect($redirect_url);
		}

		//Order details Depend payout update

		$current_date = date('Y-m-d');

		$order_detail_id = Payouts::with([
			'payout_preferences' => function ($query) {},
			'users' => function ($query) {},
			'currency' => function ($query) {},
			'order_detail' => function ($query) {},
		])->leftJoin('orders_details', 'payouts.order_detail_id', '=', 'orders_details.id')->where(function ($query1) use ($current_date) {
			$query1->whereRaw('(return_status = "Approved") OR (return_status = "Rejected") OR (`cancelled_by` = "Merchant" OR `cancelled_by` = "Buyer") OR (DATE_FORMAT(DATE_ADD(order_return_date,INTERVAL return_policy DAY),"%Y-%m-%d") <= "' . $current_date . '")');
		})->where('payouts.order_id', $request->order_id)->where('payouts.user_type', 'merchant')->groupBy('payouts.user_id', 'payouts.currency_code', 'payouts.status', 'payouts.order_id', 'payouts.order_detail_id')->get();

		$payouts_data['status'] = 'Completed';
		foreach ($order_detail_id as $k => $v) {

			Payouts::where('user_id', $request->merchant_id)->where('order_id', $request->order_id)->where('order_detail_id', $v->id)->update($payouts_data);

		}

		$payout_details = Payouts::where('user_id', $request->merchant_id)->where('order_id', $request->order_id)->first();

		$orders_details_data = OrdersDetails::where('id', $payout_details->order_detail_id)->first();

		//store activity data in notification table
		$activity_data = new Notifications;
		$activity_data->order_id = $request->order_id;
		$activity_data->order_details_id = $payout_details->order_detail_id;
		$activity_data->user_id = $request->merchant_id;
		$activity_data->notify_id = $request->merchant_id;
		$activity_data->product_id = $orders_details_data->product_id;
		$activity_data->notification_type = "order";
		$activity_data->notification_type_status = "payout";
		$activity_data->notification_message = "Received Payout amount from admin for order";
		$activity_data->save();

		$email_controller = new EmailController();
		$merchant = User::where('id', $request->merchant_id)->first();
		$er = $email_controller->order_custom_notification($merchant->email, $merchant->full_name, "Payout from Admin", "Payout from admin. For More details click the button and see the details", "View Order", url('merchant/order') . "/" . $request->order_id);

		$this->helper->flash_message('success', trans('messages.order.transferred_successfully'));
		return redirect('admin/view_order/' . $request->order_id);

		// if(@$payout_response!="error")
		// {
		//     if(@$payout_response->batch_header->batch_status=="SUCCESS")
		//     {
		//         if($payout_response->items[0]->transaction_status == 'SUCCESS')
		//         {
		//             $payouts_data['correlation_id']       = @$payout_response->items[0]->transaction_id;
		//             $payouts_data['status']               = 'Completed';
		//             Payouts::where('user_id',$request->merchant_id)->where('order_id',$request->order_id)->update($payouts_data);

		//             $payout_details=Payouts::where('user_id',$request->merchant_id)->where('order_id',$request->order_id)->first();
		//             $orders_details_data=OrdersDetails::where('id',$payout_details->order_detail_id)->first();

		//             //store activity data in notification table
		//             $activity_data = new Notifications;
		//             $activity_data->order_id =  $request->order_id;
		//             $activity_data->order_details_id = $payout_details->order_detail_id;
		//             $activity_data->user_id = $request->merchant_id;
		//             $activity_data->notify_id = $request->merchant_id;
		//             $activity_data->product_id  = $orders_details_data->product_id;
		//             $activity_data->notification_type  = "order";
		//             $activity_data->notification_type_status  = "payout";
		//             $activity_data->notification_message  = "Received Payout amount from admin for order";
		//             $activity_data->save();

		//             $email_controller=new EmailController();
		//             $merchant=User::where('id',$request->merchant_id)->first();
		//             $er=$email_controller->order_custom_notification($merchant->email,$merchant->full_name,"Payout from Admin","Payout from admin. For More details click the button and see the details","View Order", url('merchant/order')."/". $request->order_id);

		//             $this->helper->flash_message('success', trans('messages.order.transferred_successfully'));
		//             return redirect('admin/view_order/'.$request->order_id);
		//         }
		//         else
		//         {
		//             $this->helper->flash_message('error', trans('messages.order.payout_failed').' '.$payout_response->items[0]->errors->name);
		//             return redirect('admin/view_order/'.$request->order_id);
		//         }
		//     }
		//     else
		//     {
		//         $this->helper->flash_message('error', trans('messages.order.payout_failed').' '.$payout_response->name);
		//         return redirect('admin/view_order/'.$request->order_id);
		//     }
		// }
		// else
		// {
		//     $this->helper->flash_message('error', trans('messages.order.token_mismach'));
		//     return redirect('admin/view_order/'.$request->order_id);
		// }
	}

	/**
	 * Buyer Refund
	 *
	 * @param array $request    Input values
	 * @return redirect     to Order details view
	 */
	public function refund(Request $request) {

		$order_details = Orders::where('id', $request->order_id)->get();
		$refund_currency = 'USD';
		$amount = $request->amount;
		$transaction_id = $order_details->first()->transaction_id;

		$paymode = $order_details->first()->paymode;

		if ($paymode == 'paypal' || $paymode == 'PayPal') {
			$paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();
			$this->omnipay = Omnipay::create('PayPal_Express');
			$this->omnipay->setUsername($paypal_credentials[0]->value);
			$this->omnipay->setPassword($paypal_credentials[1]->value);
			$this->omnipay->setSignature($paypal_credentials[2]->value);
			$this->omnipay->setTestMode(($paypal_credentials[3]->value == 'sandbox') ? true : false);
		} else {
			$stripe_credentials = PaymentGateway::where('site', 'Stripe')->pluck('value', 'name');

			$this->omnipay = Omnipay::create('Stripe');
			$this->omnipay->setApiKey(@$stripe_credentials['secret']);
		}

		// Partial refund
		$refund = $this->omnipay->refund(array(
			'transactionReference' => $transaction_id,
			'amount' => $amount,
			'currency' => $refund_currency,
		));

		$response = $refund->send();
		if ($response->isSuccessful()) {
			$data = $response->getData();
			if ($paymode == 'Credit Card' || $paymode == 'credit card') {
				$refunds = @$data['refunds']['data'];
				$payouts_data['correlation_id'] = @$refunds[0]['id'];
			} else {
				$payouts_data['correlation_id'] = $data['CORRELATIONID'];
			}
			$payouts_data['status'] = 'Completed';
			Payouts::where('user_id', $request->merchant_id)->where('order_id', $request->order_id)->update($payouts_data);
			$payout_details = Payouts::where('user_id', $request->merchant_id)->where('order_id', $request->order_id)->first();
			$orders_details_data = OrdersDetails::where('id', $payout_details->order_detail_id)->first();
			$order_data = Orders::where('id', $orders_details_data->order_id)->first();
			//store activity data in notification table
			$activity_data = new Notifications;
			$activity_data->order_id = $request->order_id;
			$activity_data->order_details_id = $payout_details->order_detail_id;
			$activity_data->user_id = $order_data->buyer_id;
			$activity_data->notify_id = $order_data->buyer_id;
			$activity_data->product_id = $orders_details_data->product_id;
			$activity_data->notification_type = "order";
			$activity_data->notification_type_status = "refund";
			$activity_data->notification_message = "Received refund amount from admin for order";
			$activity_data->save();

			$email_controller = new EmailController();
			$buyer = User::where('id', $order_data->buyer_id)->first();
			$er = $email_controller->order_custom_notification($buyer->email, $buyer->full_name, "Refund from Admin", "Refund from Admin. For More details click the button and see the details", "View Order", url('purchases') . "/" . $request->order_id);

			$this->helper->flash_message('success', trans('messages.order.refund_successfully'));
			return redirect('admin/view_order/' . $request->order_id);
		} else {
			$this->helper->flash_message('error', $response->getMessage());
			return redirect('admin/view_order/' . $request->order_id);
		}
	}

	// Single payout using paypal
	public function paypal_payouts($data = false) {
		global $environment;
		$paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();
		$api_user = $paypal_credentials[0]->value;
		$api_pwd = $paypal_credentials[1]->value;
		$api_key = $paypal_credentials[2]->value;
		$paymode = $paypal_credentials[3]->value;
		$client = $paypal_credentials[4]->value;
		$secret = $paypal_credentials[5]->value;

		if ($paymode == 'sandbox') {
			$environment = 'sandbox';
		} else {
			$environment = '';
		}

		$ch = curl_init();

		//$client="ASeeaUVlKXDd8DegCNSuO413fePRLrlzZKdGE_RwrWqJOVVbTNJb6-_r6xX9GdsRUVNc8butjTOIK_Xm";
		//$secret="ENCGBUb_QSpHzGIAxjtSehkRIAI9lOELOiZUUjZUTEdjACeILOUUG58ijBNsuzdV-RPyDbHNxYTPkapn";

		curl_setopt($ch, CURLOPT_URL, "https://api.$environment.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $client . ":" . $secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

		$result = curl_exec($ch);
		$json = json_decode($result);
		if (!isset($json->error)) {
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
			curl_setopt($ch, CURLOPT_URL, "https://api.$environment.paypal.com/v1/payments/payouts?sync_mode=true");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $json->access_token, ""));

			$result = curl_exec($ch);

			if (empty($result)) {
				$json = "error";
			} else {
				$json = json_decode($result);
			}
			curl_close($ch);

		} else {
			$json = "error";

		}
		return $json;
	}
}
