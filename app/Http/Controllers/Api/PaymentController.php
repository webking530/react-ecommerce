<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\BillingAddress;
use App\Models\Cart;
use App\Models\Fees;
use App\Models\Notifications;
use App\Models\Orders;
use App\Models\OrdersBillingAddress;
use App\Models\OrdersCancel;
use App\Models\OrdersDetails;
use App\Models\OrdersReturn;
use App\Models\OrdersShippingAddress;
use App\Models\Payouts;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class PaymentController extends Controller {
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
	/*
		    * Create Orders After Payment Successfully Done
		     *
		     * @param array $data    Payment Data
		     * @return string $code  Reservation Code
	*/
	public function purchase_order(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		if ($request->transaction_id != '') {

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

			if (count($data['cart'])) {

				$data = json_decode($this->payment_helper->price_calculation($data['cart'], "yes"));

				$transaction_id = $this->payment_helper->get_transaction_id_from_payment_id($request->transaction_id);

				$orders_data['buyer_id'] = $user_id;
				$orders_data['transaction_id'] = $transaction_id;
				$orders_data['subtotal'] = @$data->subtotal;
				$orders_data['shipping_fee'] = @$data->shipping_charge;
				$orders_data['incremental_fee'] = @$data->incremental_fee;
				$orders_data['merchant_fee'] = @$data->merchant_fee;
				$orders_data['service_fee'] = @$data->service;
				$orders_data['total'] = @$data->total;
				$orders_data['currency_code'] = api_currency_code;
				$orders_data['paymode'] = "PayPal";
				$orders_data['updated_at'] = date('Y-m-d H:i:s');
				$orders_data['created_at'] = date('Y-m-d H:i:s');
				$order_id = Orders::insertGetId($orders_data);
				//store the billing and shipping address for later uses to show admin panel and orders pages
				$billing = BillingAddress::where('user_id', $user_id)->where('is_default', 'yes')->first()->tojson();
				$billing = json_decode($billing, true);
				$shipping_user = ShippingAddress::where('user_id', $user_id)->where('is_default', 'yes')->first()->tojson();
				$shipping_user = json_decode($shipping_user, true);
				unset($billing['id'], $billing['user_id'], $billing['is_default'], $billing['created_at'], $billing['updated_at']);
				unset($shipping_user['id'], $shipping_user['user_id'], $shipping_user['is_default'], $shipping_user['created_at'], $shipping_user['updated_at']);
				$billing['order_id'] = $order_id;
				$shipping_user['order_id'] = $order_id;
				OrdersBillingAddress::insert($billing);
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
				$carts = $carts->get();
				$merchant_ids = array();
				foreach ($carts as $cart) {
					$orders_details['order_id'] = $order_id;
					$orders_details['product_id'] = $cart->product_id;
					$orders_details['option_id'] = $cart->option_id;
					$orders_details['quantity'] = $cart->quantity;
					$orders_details['status'] = "Pending";
					if (count($cart->product_shipping_details)) {
						$charge = $cart->product_shipping_details[0]->charge != NULL ? $cart->product_shipping_details[0]->charge : 0;

						$incremental_fee = $cart->product_shipping_details[0]->incremental_fee != NULL ? $cart->product_shipping_details[0]->incremental_fee : 0;

						$shipping_fe = $charge + ($incremental_fee * ($cart->quantity - 1));

						$incremental_fe = ($incremental_fee * ($cart->quantity - 1));

						$shipping_fee = $charge;

						$orders_details['shipping'] = $shipping_fee;

						$orders_details['incremental'] = $incremental_fe;
					} else {
						$orders_details['shipping'] = 0;
						$orders_details['incremental'] = 0;
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

					$update_quantity['total_quantity'] = ($cart->product_details->total_quantity) - $cart->quantity;
					$update_quantity['sold'] = ($cart->product_details->sold) + $cart->quantity;

					Product::where('id', $cart->product_details->id)->update($update_quantity);

					$orders_details['merchant_id'] = $cart->product_details->user_id;
					array_push($merchant_ids, $cart->product_details->user_id);
					$orders_details['return_policy'] = $cart->product_details->returns_policy->days;
					$orders_details['exchange_policy'] = $cart->product_details->exchanges_policy->days;
					$orders_details['updated_at'] = date('Y-m-d H:i:s');
					$orders_details['created_at'] = date('Y-m-d H:i:s');
					$orders_details_id = OrdersDetails::insertGetId($orders_details);

					//update quantity for products
					///remove datas from cart
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
					Cart::where('id', $cart->id)->delete();
				}
				$buyer = User::where('id', $user_id)->first();

				$email_controller = new EmailController();
				//Email notification for buyer and merchant - Order placing
				$email_controller->order_notification("Buyer", $buyer->email, ucfirst($buyer->full_name), "Your Order Placed Successfully", $user_id, "Order Placed", $order_id, "Your Order Placed Successfully");

				if (count($merchant_ids)) {
					for ($i = 0; $i < count($merchant_ids); $i++) {
						$email_controller = new EmailController();
						$merchant = User::where('id', $merchant_ids[$i])->first();
						$er = $email_controller->order_notification("Merchant", $merchant->email, ucfirst($merchant->full_name), "You have a new Order", $merchant->id, "Order Placed", $order_id, "You have a new Order.");
					}
				}
				return response()->json([
					'status_message' => 'Payment Success',
					'status_code' => '1',
				]);
			} else {
				return response()->json([
					'status_message' => 'No Cart available',
					'status_code' => '0',
				]);
			}

		} else {
			return response()->json([
				'status_message' => 'Payment Failed',
				'status_code' => '0',
			]);
		}

	}
	/*cancel order in buyer
     */
	public function order_cancel(Request $request) {
		/* $rules = array(
			                  'id'          =>   'required',
			                  'order_id'    =>   'required',
			                        );

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
			            $user_id         = $user_token->id;

			             $orders = Orders::where('id',@$request->order_id)->where('buyer_id',$user_id)->get();
			             $ordersdetails =OrdersDetails::where('id',@$request->id)->where('order_id',@$request->order_id)->get();
			            if(count($orders) && count($ordersdetails)){

			            $orderscancel = new OrdersCancel;

			            $orderscancel->order_id         = $request->id;
			            $orderscancel->cancel_reason    = @$request->reason;
			            $orderscancel->save();
			            $orders_details['status'] = 'Cancelled';
			            $orders_details['cancelled_by'] = 'buyer';
			            $orders_details = OrdersDetails::where('id',$request->id)->update($orders_details);

			            $orders_details=OrdersDetails::where('id',$request->id)->first();

			            $payouts_data['order_id'] =  $orders_details->order_id;
			            $payouts_data['order_detail_id'] =  $request->id;
			            $payouts_data['user_id'] =  $user_id;
			            $payouts_data['user_type'] =  "buyer";
			            $payouts_data['account'] =  "Paypal";
			            $payouts_data['subtotal'] =  $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', ($orders_details->original_price * $orders_details->quantity));
			            $payouts_data['service'] =  $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_service);
			            $payouts_data['merchant_fee'] =  $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_merchant);
			            $payouts_data['shipping'] =  $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_shipping) + $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_incremental);
			            $payouts_data['amount'] =  $payouts_data['subtotal'] + $payouts_data['service'] + $payouts_data['shipping'];
			            $payouts_data['currency_code'] =  "USD";
			            $payouts_data['status'] =  "Future";
			            $payouts_data['created_at'] = date('Y-m-d H:i:s');
			            $payouts_data['updated_at'] =  date('Y-m-d H:i:s');
			            if($orders_details->paymode=="paypal" || $orders_details->paymode=="PayPal")
			            {
			                Payouts::create($payouts_data);
			            }

			            //update products table quantity
			            $product=Product::where('id',$orders_details->product_id)->first();
			            $update_quantity['total_quantity']=$product->total_quantity + $orders_details->quantity;
			            $update_quantity['sold']=$product->sold - $orders_details->quantity;
			            Product::where('id',$orders_details->product_id)->update($update_quantity);

			            //update product option table quantity if option is available
			            if($orders_details->option_id!=NULL && $orders_details->option_id!=null && $orders_details->option_id!="NULL")
			            {
			                $product_option=ProductOption::where('id',$orders_details->option_id)->where('product_id',$orders_details->product_id)->first();
			                $update_option_quantity['total_quantity']=$product_option->total_quantity + $orders_details->quantity;
			                $update_option_quantity['sold']=$product_option->sold - $orders_details->quantity;
			                ProductOption::where('id',$orders_details->option_id)->where('product_id',$orders_details->product_id)->update($update_option_quantity);
			            }

			            $order_data=Orders::where('id',$orders_details->order_id)->first();
			            //store activity data in notification table

			            $email_controller=new EmailController();
			            $merchant=User::where('id',$orders_details->merchant_id)->first();
			            $er=$email_controller->order_custom_notification($merchant->email,$merchant->full_name,"Order Cancelled by Buyer","Your order has been cancelled","View Order",url('merchant/order')."/". $orders_details->order_id);

			            $activity_data = new Notifications;
			            $activity_data->order_id = $orders_details->order_id;
			            $activity_data->order_details_id = $orders_details->id;
			            $activity_data->user_id = $orders_details->merchant_id; //merchant id
			            $activity_data->notify_id = $order_data->buyer_id; //buyer id
			            $activity_data->product_id  = $orders_details->product_id;
			            $activity_data->notification_type  = "order";
			            $activity_data->notification_type_status  ="cancelled";
			            $activity_data->notification_message  = "Cancelled the order";
			            $activity_data->save();
			            return  response()->json([

			                              'status_message' => 'Order Suceesfully Canceled',

			                              'status_code'    => '1'

			                              ]);

			        }else{
			            return  response()->json([

			                              'status_message' => 'Order id invalid',

			                              'status_code'    => '0'

			                              ]);

		*/
		$rules = array(
			'id' => 'required',
			'order_id' => 'required',
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
		}
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;
		$order_detail_id = @$request->id;
		$order_id = @$request->order_id;
		$reason = @$request->reason;

		/* Declare orders related datas as variable */
		$orders_data = Orders::where('id', $order_id)->where('buyer_id', $user_id)->first();
		$orders_detail_data = OrdersDetails::where('id', $order_detail_id)->where('order_id', $order_id)->first();
		$previous_status = $orders_detail_data->status;

		/* Check the order is cancelled before or not */
		if ($orders_detail_data->status == 'Cancelled') {
			return response()->json([
				'status_message' => 'Order Already Canceled',
				'status_code' => '0',
			]);
		}

		if (count($orders_data) && count($orders_detail_data)) {

			/* Insert Order Cancel data with reason */
			$orderscancel = new OrdersCancel;
			$orderscancel->order_id = $order_detail_id;
			$orderscancel->cancel_reason = $reason;
			$orderscancel->save();

			/* Update the Order details data */
			$orders_detail_data->status = 'Cancelled';
			$orders_detail_data->cancelled_by = 'buyer';
			$orders_detail_data->save();

			/*Get subtotal*/
			$subtotal = $orders_detail_data->original_price * $orders_detail_data->quantity;

			/* Currency convert to order's session currency */
			$shipping = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_shipping);
			$incremental = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_incremental);
			$service = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_service);
			$merchant_fee = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_merchant);
			$subtotal = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $subtotal);

			if (strtolower($previous_status) == 'pending') {
				$amount = ($subtotal + $shipping + $incremental + $service);
				$merchant_amount = null;
			} else {
				$amount = $subtotal;

				/*Merchant refund amount  calculate merchant fee*/
				$merchant_fee = Fees::where('name', 'merchant_fee')->first()->value;

				if ($merchant_fee > 0) {

					$merchant_fee_amount = ($shipping + $incremental) - (($merchant_fee / 100) * ($shipping + $incremental));
					$merchant_amount = number_format(round($merchant_fee_amount), 2, '.', '');
				} else {
					$merchant_amount = $shipping + $incremental;
				}
				//$merchant_amount = ($shipping + $incremental);
			}

			/* Insert payout details */
			$payouts_data['order_id'] = $merchant_payouts_data['order_id'] = $orders_detail_data->order_id;
			$payouts_data['order_detail_id'] = $merchant_payouts_data['order_detail_id'] = $order_detail_id;

			$payouts_data['user_id'] = $user_id;
			$merchant_payouts_data['user_id'] = $orders_detail_data->merchant_id;

			$payouts_data['user_type'] = "buyer";
			$merchant_payouts_data['user_type'] = "merchant";

			$payouts_data['account'] = $merchant_payouts_data['account'] = "Paypal";
			$payouts_data['subtotal'] = $merchant_payouts_data['subtotal'] = $subtotal;
			$payouts_data['service'] = $merchant_payouts_data['service'] = $service;

			$payouts_data['merchant_fee'] = $merchant_payouts_data['merchant_fee'] = $merchant_fee;
			$payouts_data['shipping'] = $merchant_payouts_data['shipping'] = ($shipping + $incremental);

			$payouts_data['amount'] = $amount;
			$merchant_payouts_data['amount'] = $merchant_amount;

			$payouts_data['currency_code'] = $merchant_payouts_data['currency_code'] = "USD";
			$payouts_data['status'] = $merchant_payouts_data['status'] = "Future";
			$payouts_data['created_at'] = $merchant_payouts_data['created_at'] = date('Y-m-d H:i:s');
			$payouts_data['updated_at'] = $merchant_payouts_data['updated_at'] = date('Y-m-d H:i:s');

			if (strtolower($orders_detail_data->paymode) == "paypal" || strtolower($orders_detail_data->paymode) == "credit card") {
				Payouts::create($payouts_data);
				Payouts::create($merchant_payouts_data);
			}

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

			$email_controller = new EmailController();
			$merchant = User::find($orders_detail_data->merchant_id);
			$er = $email_controller->order_custom_notification($merchant->email, $merchant->full_name, "Order Cancelled by Buyer", "Your order has been cancelled", "View Order", url('merchant/order') . "/" . $orders_detail_data->order_id);

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
			return response()->json([

				'status_message' => 'Order Suceesfully Canceled',

				'status_code' => '1',

			]);

		} else {
			return response()->json([

				'status_message' => 'Order id invalid',

				'status_code' => '0',

			]);

		}

	}
	/*
		        Return Order Request
	*/
	public function order_return(Request $request) {

		$rules = array(
			'order_id' => 'required',
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
		}
		$user_token = JWTAuth::parseToken()->authenticate();
		$user_id = $user_token->id;

		$order_id = @$request->id;

		$ordersdetails = OrdersDetails::where('id', @$order_id)->get();

		$ordersreturn = new OrdersReturn;

		$ordersreturn->order_id = $order_id;
		$ordersreturn->return_reason = @$request->reason;
		$ordersreturn->status = 'Requested';
		$ordersreturn->save();

		$orders_details['status'] = 'Returned';
		$orders_details['return_status'] = 'Requested';

		$orders_details = OrdersDetails::where('id', $order_id)->update($orders_details);
		$orders_details_data = OrdersDetails::where('id', $order_id)->first();
		$orders = Orders::where('id', $orders_details_data->order_id)->first();
		//store activity data in notification table

		$email_controller = new EmailController();
		$merchant = User::where('id', $orders_details_data->merchant_id)->first();
		$er = $email_controller->order_custom_notification($merchant->email, $merchant->full_name, "Order Returned", "Your order has been Returned", "View Order", url('merchant/order') . "/" . $orders_details_data->order_id);

		$activity_data = new Notifications;
		$activity_data->order_id = $orders_details_data->order_id;
		$activity_data->order_details_id = $orders_details_data->id;
		$activity_data->user_id = $user_id;
		$activity_data->notify_id = $orders_details_data->merchant_id;
		$activity_data->product_id = $orders_details_data->product_id;
		$activity_data->notification_type = "order";
		$activity_data->notification_type_status = "returned";
		$activity_data->notification_message = "returned the order";
		$activity_data->save();
		return response()->json([

			'status_message' => 'Order Return request successfully send',

			'status_code' => '1',

		]);

	}

}
