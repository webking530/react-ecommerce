<?php

/**
 * Cron Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Cron
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use Auth;
use App\Models\Currency;
use App\Models\Payouts;
use App\Models\OrdersDetails;
use Swap;
use DB;
use Session;

class CronController extends Controller
{
    /**
     * Update currency rate based on Swap Config file
     *
     * @param array $swap   Instance of SwapInterface
     * @return redirect     to Home page
     */
     protected $helper; // Global variable for Helpers instance
    
    protected $payment_helper; // Global variable for PaymentHelper instance

    /**
     * Constructor to Set PaymentHelper instance in Global variable
     *
     * @param array $payment   Instance of PaymentHelper
     */
    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    public function currency()
    {
        // Get all currencies from Currency table
        $result = Currency::all();

        // Update Currency rate by using Code as where condition
        foreach($result as $row)
        {
            if($row->code != 'USD')
                $rate = Swap::latest('USD/'.$row->code)->getValue();
            else
                $rate = 1;

            Currency::where('code',$row->code)->update(['rate' => $rate]);
        }
    }

    public function update_owe_amount()
    {
        $order_details_all=OrdersDetails::where("status","Completed")->where('owe_amount','<=','0')->get();
        foreach($order_details_all as $orders_details)
        {
            $date_diff=count($this->get_days(strtotime($orders_details->completed_at),time())) ;
            $return_date=$orders_details->return_policy;
            if($orders_details->paymode=="cod" || $orders_details->paymode=="cos")
            {
                if($return_date!=0 && $date_diff>$return_date)
                {
                    $orders_detailup['owe_amount'] = $orders_details->original_merchant + $orders_details->original_service;
                    $orders_detailup['remaining_owe_amount'] = $orders_detailup['owe_amount'];
                    OrdersDetails::where('id',$orders_details->id)->update($orders_detailup);
                }
            }
            elseif($orders_details->paymode=="paypal")
            {
                if($return_date!=0 && $date_diff>$return_date)
                {
                    $applied_owe_amount=0;
                    $amount_for_owe=($orders_details->original_price*$orders_details->quantity)+($orders_details->original_shipping+$orders_details->original_incremental)-$orders_details->original_merchant;
                    $merchant_owe = OrdersDetails::where('merchant_id',$orders_details->merchant_id)->where('remaining_owe_amount','!=',0)->get();
                    if($merchant_owe->count())
                    {
                        foreach($merchant_owe as $row)
                        {
                            $calc_remaining_owe_amount=$this->payment_helper->currency_convert($row->currency_code,$orders_details->currency_code,$row->remaining_owe_amount);
                            if($amount_for_owe > 0)
                            {
                                if($amount_for_owe >= $calc_remaining_owe_amount)
                                {
                                    $amount_for_owe -= $calc_remaining_owe_amount;
                                    $applied_owe_amount+=$calc_remaining_owe_amount;
                                    $remaining_owe_amount_for = 0;
                                }
                                else
                                {
                                    $remaining_owe_amount_for = $calc_remaining_owe_amount - $amount_for_owe;
                                    $remaining_owe_amount_for=$this->payment_helper->currency_convert($orders_details->currency_code,$row->currency_code,$remaining_owe_amount_for);
                                    $applied_owe_amount+=$amount_for_owe;
                                    $amount_for_owe = 0;
                                }
                                OrdersDetails::where('id',$row->id)->update(['remaining_owe_amount' => $remaining_owe_amount_for]);
                            }
                        }
                    }
                    $orders_detail['applied_owe_amount'] = $applied_owe_amount;
                    OrdersDetails::where('id',$orders_details->id)->update($orders_detail);

                    $orders_details_cron=OrdersDetails::where('id',$orders_details->id)->first();

                    $payouts_data['order_id'] =  $orders_details_cron->order_id;
                    $payouts_data['order_detail_id'] =  $orders_details_cron->id;
                    $payouts_data['user_id'] = $orders_details_cron->merchant_id;
                    $payouts_data['user_type'] =  "merchant";
                    $payouts_data['account'] =  "Paypal";
                    $payouts_data['subtotal'] =  $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', ($orders_details_cron->original_price * $orders_details_cron->quantity));
                    $payouts_data['service'] =  $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->original_service);
                    $payouts_data['merchant_fee'] =  $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->original_merchant);
                    $payouts_data['applied_owe_amount'] =  $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->applied_owe_amount);            
                    $payouts_data['shipping'] =  $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->original_shipping) + $this->payment_helper->currency_convert($orders_details_cron->currency_code, 'USD', $orders_details_cron->original_incremental);
                    $payouts_data['amount'] =  ($payouts_data['subtotal']  + $payouts_data['shipping'])-($payouts_data['applied_owe_amount']+$payouts_data['merchant_fee']);
                    $payouts_data['currency_code'] =  'USD';
                    $payouts_data['status'] =  "Future";
                    $payouts_data['created_at'] = date('Y-m-d H:i:s');
                    $payouts_data['updated_at'] =  date('Y-m-d H:i:s');
                    
                    if($orders_details_cron->paymode=="paypal" || $orders_details_cron->paymode=="PayPal")
                    {
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
    public function get_days($sStartDate, $sEndDate, $format='dmy')
    {
        if($format == 'dmy')
        {
            $sStartDate   = gmdate("Y-m-d", $sStartDate);  
            $sEndDate     = gmdate("Y-m-d", $sEndDate);  
        }
        $aDays[]      = $sStartDate;  
        $sCurrentDate = $sStartDate;
        while($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));     
            $aDays[]      = $sCurrentDate;  
        }
        return $aDays;  
    }
}
