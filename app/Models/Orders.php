<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use App\Traits\CurrencyConversion;

class Orders extends Model
{
	use CurrencyConversion;

	/**
	 * Get the index name for the model.
	 *
	 * @return string
	 */
	protected $table = "orders";

	public $fillable = ['buyer_id', 'subtotal', 'shipping_fee', 'incremental_fee', 'service_fee', 'merchant_fee', 'total', 'currency_code', 'transaction_id', 'paymode', 'billing_id', 'coupon_amount', 'coupon_code'];

	protected $appends = ['order_date', 'buyer_name', 'buyer_user_name', 'currency_symbol', 'show_payment_mode', 'original_subtotal', 'original_coupon_amt'];

	// Get order Date from created_at field
	public function getOrderDateAttribute()
	{
		return date('F d, Y', strtotime($this->attributes['created_at']));
	}
	
	public function getBuyerNameAttribute()
	{
		$result = User::where('id', $this->attributes['buyer_id']);
		return @$result->first()->full_name;
	}

	public function getBuyerUserNameAttribute()
	{
		$result = User::where('id', $this->attributes['buyer_id']);
		return @$result->first()->user_name;
	}

	public function orders_details()
	{
		return $this->hasMany('App\Models\OrdersDetails', 'order_id', 'id');
	}

	public function orders_cancel()
	{
		return $this->belongsTo('App\Models\OrdersCancel', 'id', 'order_id');
	}

	public function buyers()
	{
		return $this->belongsTo('App\Models\User', 'buyer_id', 'id');
	}

	public function shipping_details()
	{
		return $this->belongsTo('App\Models\OrdersShippingAddress', 'id', 'order_id');
	}

	public function billing_details()
	{
		return $this->belongsTo('App\Models\OrdersBillingAddress', 'id', 'order_id');
	}

	//join with Payout
	public function payouts()
	{
		return $this->hasMany('App\Models\Payouts', 'order_id', 'id')->where('user_type', 'merchant');
	}

	public function getShowPaymentModeAttribute()
	{
		if ($this->attributes['paymode'] == "cod") {
			return "Cash on Delivery";
		}
		elseif ($this->attributes['paymode'] == "cos") {
			return "Cash on Store";
		}
		elseif ($this->attributes['paymode'] == "credit card") {
			return "Credit Card";
		}
		else {
			return "PayPal";
		}
	}

	//join with Payout
	public function refunds()
	{
		return $this->hasMany('App\Models\Payouts', 'order_id', 'id')->where('user_type', 'buyer');
	}

	//join with Payout
	public function payout_preferences()
	{
		return $this->belongsTo('App\Models\PayoutPreferences', 'buyer_id', 'user_id')->where('default', 'yes');
	}

	public function getSubtotalAttribute()
	{
		return $this->currency_calc('subtotal');
	}

	public function getOriginalSubtotalAttribute()
	{
		return @$this->attributes['subtotal'];
	}

	public function getOriginalCouponAmtAttribute()
	{
		return @$this->attributes['coupon_amount'];
	}

	public function getShippingFeeAttribute()
	{
		return $this->currency_calc('shipping_fee');
	}

	public function getServiceFeeAttribute()
	{
		return $this->currency_calc('service_fee');
	}

	public function getMerchantFeeAttribute()
	{
		return $this->currency_calc('merchant_fee');
	}

	public function getIncrementalFeeAttribute()
	{
		return $this->currency_calc('incremental_fee');
	}

	public function getCouponAmountAttribute()
	{
		return $this->currency_calc('coupon_amount');
	}

	public function getTotalAttribute()
	{
		return $this->currency_calc('total');
	}

	public function getOriginalTotalAttribute()
	{
		return @$this->attributes['total'];
	}

	public function getCurrencySymbolAttribute()
	{
		if (Session::get('symbol')) {
			return Session::get('symbol');
		}
		else {
			$data = Orders::find(@$this->attributes['order_id'])->currency_code;
		}
		return Currency::where('code', $data)->first()->symbol;
	}
}
