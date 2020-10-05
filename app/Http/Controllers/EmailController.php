<?php

/**
 * Email Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Email
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PasswordResets;
use App\Models\User;
use App\Models\Payouts;
use App\Models\Orders;
use App;
use Mail;
use App\Mail\MailQueue;

class EmailController extends Controller
{
	/**
     * Send Welcome Mail to Users with Confirmation Link
     *
     * @param array $user  User Details
     * @return true
     */
    public function welcome_email_confirmation($user)
    {
    	$data['full_name'] = $user->full_name;
        $data['email'] = $user->email;
        $data['token'] = str_random(100);
        $data['url'] = url('/').'/';
        $data['locale']       = App::getLocale();

        $password_resets = new PasswordResets;

        $password_resets->email      = $user->email;
        $password_resets->token      = $data['token'];
        $password_resets->created_at = date('Y-m-d H:i:s');

        $password_resets->save();
        
        $data['subject'] = "Please confirm your e-mail address";
        $data['view_file'] = 'emails.email_confirm';

        try {
            Mail::to($data['email'], $data['full_name'])->queue(new MailQueue($data));
        }
        catch(\Exception $e) {
            
        }
        
        return 'true';
    }

    /**
     * Send Resend Mail to Users with Confirmation Link
     *
     * @param array $user  User Details
     * @return true
     */
    public function resend_email_confirmation($user)
    {
        $data['full_name'] = $user->full_name;
        $data['email'] = $user->email;
        $data['token'] = str_random(100);
        $data['url'] = url('/').'/';
        $data['locale'] = App::getLocale();

        $password_resets = new PasswordResets;
        $password_resets->email      = $user->email;
        $password_resets->token      = $data['token'];
        $password_resets->created_at = date('Y-m-d H:i:s');
        $password_resets->save();
        
        $data['subject'] ="Please Confirm your ".SITE_NAME." account, ".$data['full_name'];
        $data['view_file'] = 'emails.resend_confirm_email';

        try {
            Mail::to($data['email'], $data['full_name'])->queue(new MailQueue($data));
        }
        catch(\Exception $e) {
            
        }
        
        return 'true';
    }

    /**
     * Send Resend Mail to Users with Confirmation Link
     *
     * @param array $user  User Details
     * @return true
     */
    public function change_email_confirmation($user)
    {
        $data['full_name'] = $user->full_name;
        $data['email'] 	= $user->email;
        $data['token'] 	= str_random(100);
        $data['url'] 	= url('/').'/';
        $data['locale'] = App::getLocale();

        $password_resets = new PasswordResets;

        $password_resets->email      = $user->email;
        $password_resets->token      = $data['token'];
        $password_resets->created_at = date('Y-m-d H:i:s');

        $password_resets->save();
        
        $data['subject'] = SITE_NAME." has received a request to change your account's email address";
        $data['view_file'] = 'emails.change_confirm_email';
        
        try {
            Mail::to($data['email'], $data['full_name'])->queue(new MailQueue($data));        
        }
        catch(\Exception $e) {
            
        }
        
        return 'true';
    }

    /**
     * Function for Email notification
     *
     * @param  $user_type           Merchant /Buyer
     * @param  $email_to            Merchant ?Buyer
     * @param  $email_for           Orders - Placed , Processing , Finished, Cancel and Return
     * @param  $first_name          Name to display in email
     * @param  $subject             Mail Subject
     * @param  $order_id            Order id
     * 
     */
    public function order_notification($user_type,$email_to,$first_name,$subject,$merchant_id,$email_for,$order_id,$message_content)
    {
        $orders=Orders::with([
                'buyers' => function($query){},
                'shipping_details'  =>function($query){},
                'billing_details'  =>function($query){},
                'orders_details'  =>function($query) use($user_type,$merchant_id){
                    $query->with([
                        'products' => function($query) {},
                        'product_photos' => function($query){},
                        'product_option' => function($query){},
                        'merchant_users' => function($query){}
                    ]);
                    if($user_type=="Merchant")
                    {
                        $query->where('merchant_id',$merchant_id);    
                    }
                }
        ])->where('id',$order_id);

        $data['orders'] = $orders->first()->toArray();

        $data['email'] 		= $email_to;
        $data['first_name'] = $first_name;
        $data['url'] 		= url('/').'/';
        $data['message_content']=$message_content;

        $data['subject'] = $subject;
        $data['view_file'] = ($user_type=="Merchant") ? "emails.orders_merchant_email" : "emails.orders_buyer_email";

        try {
            Mail::to($data['email'], $data['first_name'])->queue(new MailQueue($data));
        }
        catch(\Exception $e) {
            
        }

        return 'true';
    }

    /**
     * Send Forgot Password Mail with Confirmation Link
     *
     * @param array $user  User Details
     * @return true
     */
    public function forgot_password($user)
    {
        $data['token'] 		= str_random(100);
        $data['url'] 		= url('/').'/';
        $data['locale']     = App::getLocale();
        $data['first_name'] = $user->full_name;
        $data['email']      = $user->email;
        $password_resets = new PasswordResets;
        $password_resets->email      = $user->email;
        $password_resets->token      = $data['token'];
        $password_resets->created_at = date('Y-m-d H:i:s');
        $password_resets->save();
        $data['subject'] = "Password Reset Link";        
        $data['view_file'] = 'emails.forgot_password';
        try {
            Mail::to($data['email'], $data['first_name'])->queue(new MailQueue($data));
        }
        catch(\Exception $e) {
            
        }
    
        return 'true';
    }

    public function order_custom_notification($email_to,$first_name,$subject,$content,$btn_text,$btn_link)
    {
        $data['email']		= $email_to;
        $data['first_name']	= $first_name;
        $data['content']	= $content;
        $data['btn_text']	= $btn_text;
        $data['btn_link']	= $btn_link;
        $data['url'] 		= url('/').'/';

        $data['subject'] = $subject;
        $data['view_file'] = 'emails.order_custom';
        
        try {
            Mail::to($data['email'], $data['full_name'])->queue(new MailQueue($data));
        }
        catch(\Exception $e) {
            
        }

        return 'true';
    }

    /**
     * Send Need Payout Information Mail to Host/Guest
     *
     * @param array $order_id Reservation Details
     * @return true
     */
    public function need_payout_info($order_id,$merchant_id)
    {
        $result = Orders::find($order_id);
        $payouts = Payouts::where('order_id',$order_id)->where('user_type','merchant')->where('user_id',$merchant_id)->get();

        if($payouts->count()) {
            $payout_amount=0;
            foreach ($payouts as $payout_detail) {
                $payout_amount+=$payout_detail->amount;
            }
        }

        $user 				= User::find($merchant_id);
        $data['email'] 		= $user->email;
        $data['first_name']	= $user->full_name;
        $data['content']	= 'We have '.$result->currency->symbol.@$payout_amount.' for you but we need you to tell us where to send it. Please log in to your account and add a payout method';
        $data['btn_text']	= 'click here ';
        $data['btn_link']	= url('merchant/settings_paid');

        $data['subject'] = "Information Needed: It's time to get paid!";
        $data['view_file'] = 'emails.resend_confirm_email';
        
        try {
            Mail::to($data['email'], $data['first_name'])->queue(new MailQueue($data));
        }
        catch(\Exception $e) {
            
        }

        return 'true';   
    }
}