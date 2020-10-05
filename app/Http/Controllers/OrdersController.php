<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\BillingAddress;
use App\Models\MerchantStore;
use App\Models\Messages;
use App\Models\Notifications;
use App\Models\Orders;
use App\Models\OrdersCancel;
use App\Models\OrdersDetails;
use App\Models\OrdersReturn;
use App\Models\Payouts;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Models\Fees;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class OrdersController extends BaseController {

	protected $helper; // Global variable for Helpers instance
	protected $payment_helper; // Global variable for PaymentHelper instance

	public function __construct() {
		$this->payment_helper = new PaymentHelper;
		$this->helper = new Helpers;
	}

	/*Buyer displayed orders*/
	public function purchases() {
		$data['user_id'] = Auth::user()->id;
		$data['orders'] = Orders::where('buyer_id', Auth::id())->orderBy('id', 'DESC')->get();
		$data['orders_count'] = Orders::where('buyer_id', Auth::id())->count();
		return view('order.purchases', $data);

	}
	/*Buyer displayed view orders*/
	public function view_order(Request $request) {
		$data['order_id'] = $request->id;
		$user_order = Orders::where('buyer_id', Auth::id())->where('id', $request->id)->count();
		if ($user_order) {
			return view('order.view_purchases', $data);
		} else {
			$this->helper->flash_message('danger', trans('messages.order.invalid_order_id'));
			return redirect('/'); // Redirect to dashboard page
		}

	}
	public function purchases_details(Request $request) {
		$data['user_id'] = Auth::user()->id;
		$data['orders'] = Orders::where('buyer_id', Auth::id())->where('id', $request->id)->first();
		$data['orders_details'] = OrdersDetails::with([
			'products' => function ($query) {},
			'products_prices_details' => function ($query) {},
			'products_shipping' => function ($query) {},
			'product_photos' => function ($query) {},
			'product_option' => function ($query) {},
			'product_option_id' => function ($query) {},
			'product_option_images' => function ($query) {},
		])->where('order_id', $request->id)->get();

		$data['shipping_address'] = ShippingAddress::where('user_id', Auth::id())->first();

		echo json_encode($data);
	}
	/*Buyer purchases  cancel or return */
	public function purchases_action(Request $request) {
		/*$order_id = $request->id;
			$action = $request->action;
			$reason = $request->reason;

			//insert record to order cancel details
			if ($action == 'cancel') {
				$orders_details_data = OrdersDetails::where('id', $request->id)->first();
				if ($orders_details_data->status == 'Cancelled') {
					return "fail";exit;
				}

				$orderscancel = new OrdersCancel;

				$orderscancel->order_id = $order_id;
				$orderscancel->cancel_reason = $reason;
				$orderscancel->save();
				$orders_details['status'] = 'Cancelled';
				$orders_details['cancelled_by'] = $request->cancelled_by;
				$orders_details = OrdersDetails::where('id', $order_id)->update($orders_details);

				$orders_details = OrdersDetails::where('id', $order_id)->first();

				$coupon_amount = 0;

				$exists_coupon = Orders::where('id', $orders_details_data->order_id)->first();

				if ($exists_coupon->coupon_code != '') {

					$default_currency = Currency::where('default_currency', 1)->first()->code;

					$coupon_amount = $this->payment_helper->currency_convert($exists_coupon->currency_code, 'USD', $exists_coupon->coupon_amount);

					$payout_detail = Payouts::where('order_id', $orders_details->order_id)->count();

					if ($payout_detail) {

						$merchant_payout_count = Payouts::where('order_id', $orders_details->order_id)->where('user_type', 'merchant')->count();
						if ($merchant_payout_count) {

							$merchant_payout = Payouts::where('order_id', $orders_details->order_id)->where('user_type', 'merchant')->get();
							$buyer_amount = 0;
							foreach ($merchant_payout as $key => $value) {
								$buyer_amount += $value->amount;
							}

							if ($buyer_amount >= $exists_coupon->total) {

								$shipping = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_shipping) + $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_incremental);

								$service = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_service);
								if ($orders_details_data->status == 'Pending') {
									$merchant_amount = 0;
									$amount = $shipping + $service;

								} else {
									$merchant_amount = $shipping;
									$amount = 0;
								}

							} else {

								$shipping = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_shipping) + $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_incremental);
								$service = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_service);
								if ($orders_details_data->status == 'Pending') {
									$merchant_amount = 0;
									$amount = $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $exists_coupon->total) - $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $buyer_amount) + $shipping + $service;

								} else {
									$merchant_amount = $shipping;
									$amount = $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $exists_coupon->total) - $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $buyer_amount);
								}

							}

						}

						$buyer_payout_count = Payouts::where('order_id', $orders_details->order_id)->where('user_type', 'buyer')->count();
						if ($buyer_payout_count) {
							$shipping = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_shipping) + $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_incremental);

							$service = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_service);

							if ($orders_details_data->status == 'Pending') {
								$amount = $shipping + $service;
								$merchant_amount = null;
							} else {
								$amount = 0;
								$merchant_amount = $shipping;
							}

						}

					} else {

						$total = $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $exists_coupon->subtotal) - $this->payment_helper->currency_convert(Session::get('currency'), 'USD', $exists_coupon->coupon_amount);
						$shipping = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_shipping) + $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_incremental);

						$service = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_service);

						if ($orders_details_data->status == 'Pending') {
							$amount = $total + $shipping + $service;
							$merchant_amount = null;
						} else {
							$amount = $total;
							$merchant_amount = $shipping;
						}

					}

				} else {
					$subtotal = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', ($orders_details->original_price * $orders_details->quantity));
					$shipping = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_shipping) + $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_incremental);

					$service = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_service);

					if ($orders_details_data->status == 'Pending') {

						$amount = $subtotal + $shipping + $service;
						$merchant_amount = null;
					} else {
						$amount = $subtotal;
						$merchant_amount = $shipping;
					}
				}

				// if ($exists_coupon->coupon_code != '') {

				// 	$coupon_amount = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $exists_coupon->coupon_amount);

				// }

				$payouts_data['order_id'] = $merchant_payouts_data['order_id'] = $orders_details->order_id;
				$payouts_data['order_detail_id'] = $merchant_payouts_data['order_detail_id'] = $order_id;
				$payouts_data['user_id'] = Auth::id();
				$merchant_payouts_data['user_id'] = $orders_details_data->merchant_id;
				$payouts_data['user_type'] = "buyer";
				$merchant_payouts_data['user_type'] = "merchant";
				$payouts_data['account'] = $merchant_payouts_data['account'] = "Paypal";
				$payouts_data['subtotal'] = $merchant_payouts_data['subtotal'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', ($orders_details->original_price * $orders_details->quantity));
				$payouts_data['service'] = $merchant_payouts_data['service'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_service);
				$payouts_data['merchant_fee'] = $merchant_payouts_data['merchant_fee'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_merchant);
				$payouts_data['shipping'] = $merchant_payouts_data['shipping'] = $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_shipping) + $this->payment_helper->currency_convert($orders_details->currency_code, 'USD', $orders_details->original_incremental);

				if ($orders_details_data->status == 'Pending') {
					$payouts_data['amount'] = $amount;
					$merchant_payouts_data['amount'] = $merchant_amount;
				} else {
					$payouts_data['amount'] = $amount;
					$merchant_payouts_data['amount'] = $merchant_amount;
				}

				$payouts_data['currency_code'] = $merchant_payouts_data['currency_code'] = "USD";
				$payouts_data['status'] = $merchant_payouts_data['status'] = "Future";
				$payouts_data['created_at'] = $merchant_payouts_data['created_at'] = date('Y-m-d H:i:s');
				$payouts_data['updated_at'] = $merchant_payouts_data['updated_at'] = date('Y-m-d H:i:s');

				if ($payouts_data['amount'] > 0) {
					if ($orders_details->paymode == "paypal" || $orders_details->paymode == "PayPal" || $orders_details->paymode == "Credit Card" || $orders_details->paymode == "credit card") {
						Payouts::create($payouts_data);
						Payouts::create($merchant_payouts_data);
					}
				}

				//update products table quantity
				$product = Product::where('id', $orders_details->product_id)->first();
				$update_quantity['total_quantity'] = $product->total_quantity + $orders_details->quantity;
				$update_quantity['sold'] = $product->sold - $orders_details->quantity;

				if ($update_quantity['total_quantity'] > 0) {
					$update_quantity['sold_out'] = "No";
				}

				Product::where('id', $orders_details->product_id)->update($update_quantity);

				//update product option table quantity if option is available
				if ($orders_details->option_id != NULL && $orders_details->option_id != null && $orders_details->option_id != "NULL") {
					$product_option = ProductOption::where('id', $orders_details->option_id)->where('product_id', $orders_details->product_id)->first();
					$update_option_quantity['total_quantity'] = $product_option->total_quantity + $orders_details->quantity;
					$update_option_quantity['sold'] = $product_option->sold - $orders_details->quantity;
					ProductOption::where('id', $orders_details->option_id)->where('product_id', $orders_details->product_id)->update($update_option_quantity);
				}
				$orders = Orders::where('id', $orders_details->order_id)->first();

				//store activity data in notification table
				$activity_data = new Notifications;
				$activity_data->order_id = $orders_details->order_id;
				$activity_data->order_details_id = $orders_details->id;
				$activity_data->user_id = $orders->buyer_id;
				$activity_data->notify_id = $orders_details->merchant_id;
				$activity_data->product_id = $orders_details->product_id;
				$activity_data->notification_type = "order";
				$activity_data->notification_type_status = "cancelled_buyer";
				$activity_data->notification_message = "cancelled the order";
				$activity_data->save();

				$this->send_messages(Auth::id(), $orders_details->merchant_id, $reason);

				$email_controller = new EmailController();
				$merchant = User::where('id', $orders_details->merchant_id)->first();
				$er = $email_controller->order_custom_notification($merchant->email, $merchant->full_name, "Order Cancelled by Buyer", "Your order has been cancelled", "View Order", url('merchant/order') . "/" . $orders_details->order_id);

			} else if ($action == "return") {
				$ordersreturn = new OrdersReturn;

				$ordersreturn->order_id = $order_id;
				$ordersreturn->return_reason = $reason;
				$ordersreturn->status = 'Requested';
				$ordersreturn->save();

				$orders_details['status'] = 'Returned';
				$orders_details['return_status'] = 'Requested';

				$orders_details = OrdersDetails::where('id', $order_id)->update($orders_details);
				$orders_details_data = OrdersDetails::where('id', $order_id)->first();
				$orders = Orders::where('id', $orders_details_data->order_id)->first();
				//store activity data in notification table
				$activity_data = new Notifications;
				$activity_data->order_id = $orders_details_data->order_id;
				$activity_data->order_details_id = $orders_details_data->id;
				$activity_data->user_id = $orders->buyer_id;
				$activity_data->notify_id = $orders_details_data->merchant_id;
				$activity_data->product_id = $orders_details_data->product_id;
				$activity_data->notification_type = "order";
				$activity_data->notification_type_status = "returned";
				$activity_data->notification_message = "returned the order";
				$activity_data->save();

				$this->send_messages(Auth::id(), $orders_details_data->merchant_id, $reason);
				$email_controller = new EmailController();
				$merchant = User::where('id', $orders_details_data->merchant_id)->first();
				$er = $email_controller->order_custom_notification($merchant->email, $merchant->full_name, "Order Returned", "Your order has been Returned", "View Order", url('merchant/order') . "/" . $orders_details_data->order_id);
		*/

		$order_detail_id = $request->id;
		$action = $request->action;
		$reason = $request->reason;

		//insert record to order cancel details
		if ($action == 'cancel') {

			/* Declare orders related datas as variable */
			$orders_detail_data = OrdersDetails::find($order_detail_id);
			$previous_status = $orders_detail_data->status;
			$orders_data = Orders::find($orders_detail_data->order_id);

			/* Check the order is cancelled before or not */
			if ($orders_detail_data->status == 'Cancelled') {
				return "fail";
				exit;
			}
			/* Update the Order details data */
			$orders_detail_data->status = 'Cancelled';
			$orders_detail_data->cancelled_by = $request->cancelled_by;
			$orders_detail_data->save();

			/* Insert Order Cancel data with reason */
			$orderscancel = new OrdersCancel;
			$orderscancel->order_id = $order_detail_id;
			$orderscancel->cancel_reason = $reason;
			$orderscancel->save();

			/* Currency convert to order's session currency */
			$shipping = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_shipping);
			$incremental = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_incremental);
			$service = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_service);
			$merchant_fee = $this->payment_helper->currency_convert($orders_detail_data->currency_code, 'USD', $orders_detail_data->original_merchant);

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

			if (strtolower($previous_status) == 'pending') {
				$amount = ($subtotal + $shipping + $incremental+$service);
				$merchant_amount = null;
			} else {
				$amount = $subtotal;
				//Merchant refund amount  calculate merchant fee
					$merchant_fee=Fees::where('name','merchant_fee')->first()->value;

					if($merchant_fee > 0){

					$merchant_fee_amount = ($shipping + $incremental)-(($merchant_fee / 100) * ($shipping + $incremental));
                    $merchant_amount  = number_format(round($merchant_fee_amount), 2,'.', '');
                    }else{
                    $merchant_amount =	$shipping + $incremental;
                    }
				//$merchant_amount = ($shipping + $incremental);
			}

			/* Insert payout details */
			$payouts_data['order_id'] = $merchant_payouts_data['order_id'] = $orders_detail_data->order_id;
			$payouts_data['order_detail_id'] = $merchant_payouts_data['order_detail_id'] = $order_detail_id;

			$payouts_data['user_id'] = Auth::id();
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

			//store activity data in notification table
			$activity_data = new Notifications;
			$activity_data->order_id = $orders_detail_data->order_id;
			$activity_data->order_details_id = $orders_detail_data->id;
			$activity_data->user_id = $orders_data->buyer_id;
			$activity_data->notify_id = $orders_detail_data->merchant_id;
			$activity_data->product_id = $orders_detail_data->product_id;
			$activity_data->notification_type = "order";
			$activity_data->notification_type_status = "cancelled_buyer";
			$activity_data->notification_message = "cancelled the order";
			$activity_data->save();

			$this->send_messages(Auth::id(), $orders_detail_data->merchant_id, $reason);

			$email_controller = new EmailController();
			$merchant = User::find($orders_detail_data->merchant_id);
			$er = $email_controller->order_custom_notification($merchant->email, $merchant->full_name, "Order Cancelled by Buyer", "Your order has been cancelled", "View Order", url('merchant/order') . "/" . $orders_detail_data->order_id);

		} else if ($action == "return") {

			$ordersreturn = new OrdersReturn;
			$ordersreturn->order_id = $order_detail_id;
			$ordersreturn->return_reason = $reason;
			$ordersreturn->status = 'Requested';
			$ordersreturn->save();

			$orders_detail_data = OrdersDetails::find($order_detail_id);
			$orders_detail_data->status = 'Returned';
			$orders_detail_data->return_status = 'Requested';
			$orders_detail_data->save();

			$orders = Orders::find($orders_detail_data->order_id);
			//store activity data in notification table
			$activity_data = new Notifications;
			$activity_data->order_id = $orders_detail_data->order_id;
			$activity_data->order_details_id = $orders_detail_data->id;
			$activity_data->user_id = $orders->buyer_id;
			$activity_data->notify_id = $orders_detail_data->merchant_id;
			$activity_data->product_id = $orders_detail_data->product_id;
			$activity_data->notification_type = "order";
			$activity_data->notification_type_status = "returned";
			$activity_data->notification_message = "returned the order";
			$activity_data->save();

			$this->send_messages(Auth::id(), $orders_detail_data->merchant_id, $reason);
			$email_controller = new EmailController();
			$merchant = User::where('id', $orders_detail_data->merchant_id)->first();
			$er = $email_controller->order_custom_notification($merchant->email, $merchant->full_name, "Order Returned", "Your order has been Returned", "View Order", url('merchant/order') . "/" . $orders_detail_data->order_id);
		}
	}
	/*Buyer view Invoice Details*/
	public function invoice_details(Request $request) {
		$data['user_id'] = Auth::user()->id;
		$data['orders'] = Orders::where('buyer_id', Auth::id())->where('id', $request->id)->first();
		$data['orders_details'] = OrdersDetails::with([
			'products' => function ($query) {},
			'products_prices_details' => function ($query) {},
			'products_shipping' => function ($query) {},
			'product_photos' => function ($query) {},
			'product_option' => function ($query) {},
			'product_option_id' => function ($query) {},
			'product_option_images' => function ($query) {},
		])->where('order_id', $request->id)->get();
		$data['billing_address'] = BillingAddress::where('user_id', Auth::id())->first();
		$data['user_address'] = ShippingAddress::where('user_id', Auth::id())->first();
		$data['merchant_store'] = MerchantStore::where('user_id', Auth::id())->first();
		return view('order.view_invoice', $data);
	}
	public function send_messages($user_from, $user_to, $message = "Hai") {
		$get_group_message_user_from = Messages::where('user_to', $user_to)->where('user_from', $user_from);
		$get_group_message_user_to = Messages::where('user_to', $user_from)->where('user_from', $user_to);

		if ($get_group_message_user_from->count()) {
			$group_id = $get_group_message_user_from->first()->group_id;
		} elseif ($get_group_message_user_to->count()) {
			$group_id = $get_group_message_user_to->first()->group_id;
		} else {
			if (Messages::count()) {
				$max_group_id = Messages::orderBy('group_id', 'desc')->first()->group_id;
				$group_id = $max_group_id + 1;
			} else {
				$group_id = "1000";
			}
		}

		$message_data['user_from'] = $user_from;
		$message_data['user_to'] = $user_to;
		$message_data['message'] = $message;
		$message_data['group_id'] = $group_id;
		$message_data['read'] = '0';
		$message_data['created_at'] = date('Y-m-d H:i:s');
		$message_data['updated_at'] = date('Y-m-d H:i:s');
		Messages::insert($message_data);
	}
}
