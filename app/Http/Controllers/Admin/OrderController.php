<?php
/**
 * Admin Orders Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Orders
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;
use App\DataTables\OrdersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Currency;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\Payouts;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller {

	protected $helper; // Global variable for instance of Helpers

	public function __construct() {
		$this->helper = new Helpers;
		$this->payment_helper = new PaymentHelper;
	}
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(OrdersDataTable $dataTable) {
		return $dataTable->render('admin.orders.view');
	}

	public function view_orders(Request $request) {
		$this->update_owe_amount();
		$orders = Orders::where('id', $request->id);
		if ($orders->count()) {

			$data['orders_details'] = Orders::with([
				'orders_details' => function ($query) {
					$query->with([
						'products' => function ($query) {},
						'product_photos' => function ($query) {},
						'product_option' => function ($query) {},
					]);
				},
				'shipping_details' => function ($query) {},
				'billing_details' => function ($query) {},
				'currency' => function ($query) {},
				'payout_preferences' => function ($query) {},
				'orders_cancel' => function ($query) {},

				'payouts' => function ($query) {
					$query->with([
						'payout_preferences' => function ($query) {},
						'users' => function ($query) {},
					]);
				},
				'refunds' => function ($query) {
					$query->with([
						'payout_preferences' => function ($query) {},
						'users' => function ($query) {},
					]);
				},
			])->where('id', $request->id)->get();
			$data['orders_details'] = json_decode($data['orders_details']->tojson());

		
			$current_date = date('Y-m-d');

			$payouts =  Payouts::with([
				'payout_preferences' => function ($query) {},
				'users' => function ($query) {},
				'currency' => function ($query) {},
				'order_detail' => function ($query) {},
			])->leftJoin('orders_details', 'payouts.order_detail_id','=', 'orders_details.id')->where(function ($query1) use($current_date) {
				$query1->whereRaw( '(return_status = "Approved") OR (return_status = "Completed") OR (return_status = "Rejected") OR (`cancelled_by` = "Merchant" OR `cancelled_by` = "Buyer") OR (DATE_FORMAT(DATE_ADD(order_return_date,INTERVAL return_policy DAY),"%Y-%m-%d") <= "'.$current_date.'")');
			})->where('payouts.order_id', $request->id)->where('payouts.user_type', 'merchant')->select(DB::raw('SUM(payouts.amount) as total_amount'), DB::raw('SUM(payouts.applied_owe_amount) as total_applied_amount'), 'payouts.user_id', 'payouts.currency_code', 'payouts.status', 'payouts.order_id')->groupBy('payouts.user_id', 'payouts.currency_code', 'payouts.status', 'payouts.order_id')->get();

			if (@$payouts[0]) {
				$payouts[0]->session_total_amount = $this->payment_helper->currency_convert($payouts[0]->currency_code, '', $payouts[0]->total_amount);
			}

			$data['payouts'] = json_decode($payouts->tojson());

			$refunds = Payouts::with([
				'payout_preferences' => function ($query) {},
				'users' => function ($query) {},
				'currency' => function ($query) {},
			])->select(DB::raw('SUM(payouts.amount) as total_amount'), 'payouts.user_id', 'payouts.currency_code', 'payouts.status', 'payouts.order_id')->groupBy('payouts.user_id', 'payouts.currency_code', 'payouts.status', 'payouts.order_id')
				->where('payouts.order_id', $request->id)->where('payouts.user_type', 'buyer')->get();
			if (@$refunds[0]) {
				$refunds[0]->session_total_amount = $this->payment_helper->currency_convert($refunds[0]->currency_code, '', $refunds[0]->total_amount);
			}

			$data['default_currency'] = Currency::where('default_currency', 1)->first()->code;

			$data['refunds'] = json_decode($refunds->tojson());

			return view('admin.orders.details', $data);
		} else {
			$this->helper->flash_message('error', 'Invalid Order id'); // Call flash message function
			return redirect('admin/orders');
		}

	}

	public function need_payout_info(Request $request, EmailController $email_controller) {
		$email_controller = new EmailController();
		$er = $email_controller->need_payout_info($request->id, $request->merchant_id);

		$this->helper->flash_message('success', 'Email sent Successfully'); // Call flash message function
		return redirect('admin/view_order/' . $request->id);
	}

	public function update_owe_amount() {
		$order_details_all = OrdersDetails::where("status", "Completed")->where('owe_amount', '<=', '0')->get();
		foreach ($order_details_all as $orders_details) {
			$date_diff = count($this->get_days(strtotime($orders_details->completed_at), time()));
			$return_date = $orders_details->return_policy;
			if ($orders_details->paymode == "cod" || $orders_details->paymode == "cos") {
				if ($return_date != 0 && $date_diff > $return_date) {
					$orders_detailup['owe_amount'] = $orders_details->original_merchant + $orders_details->original_service;
					$orders_detailup['remaining_owe_amount'] = $orders_detailup['owe_amount'];
					OrdersDetails::where('id', $orders_details->id)->update($orders_detailup);
				}
			} elseif ($orders_details->paymode == "paypal") {
				if ($return_date != 0 && $date_diff > $return_date) {
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
					OrdersDetails::where('id', $orders_details->id)->update($orders_detail);

					$orders_details_cron = OrdersDetails::where('id', $orders_details->id)->first();

					$payouts_data['order_id'] = $orders_details_cron->order_id;
					$payouts_data['order_detail_id'] = $orders_details_cron->id;
					$payouts_data['user_id'] = $orders_details_cron->merchant_id;
					$payouts_data['user_type'] = "merchant";
					$payouts_data['account'] = "Paypal";
					$payouts_data['subtotal'] = $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', ($orders_details_cron->original_price * $orders_details_cron->quantity));
					$payouts_data['service'] = $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->original_service);
					$payouts_data['merchant_fee'] = $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->original_merchant);
					$payouts_data['applied_owe_amount'] = $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->applied_owe_amount);
					$payouts_data['shipping'] = $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->original_shipping) + $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->original_incremental);
					$payouts_data['amount'] = ($payouts_data['subtotal'] + $payouts_data['shipping']) - ($payouts_data['applied_owe_amount'] + $payouts_data['merchant_fee']);
					$payouts_data['currency_code'] = 'USD';
					$payouts_data['status'] = "Future";
					$payouts_data['created_at'] = date('Y-m-d H:i:s');
					$payouts_data['updated_at'] = date('Y-m-d H:i:s');

					if ($orders_details_cron->paymode == "paypal" || $orders_details_cron->paymode == "PayPal") {
						Payouts::insert($payouts_data);
					}
				}
			}
		}
	}

	/**
	 * Get dates between two dates
	 *
	 * @param date $sStartDate  Start Date
	 * @param date $sEndDate    End Date
	 * @return array $days      Between two dates
	 */
	public function get_days($sStartDate, $sEndDate, $format = 'dmy') {
		if ($format == 'dmy') {
			$sStartDate = gmdate("Y-m-d", $sStartDate);
			$sEndDate = gmdate("Y-m-d", $sEndDate);
		}
		$aDays[] = $sStartDate;
		$sCurrentDate = $sStartDate;
		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
			$aDays[] = $sCurrentDate;
		}
		return $aDays;
	}
}
