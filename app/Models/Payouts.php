<?php

/**
 * Payouts Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    Payouts
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Route;
use Session;
use App\Traits\CurrencyConversion;

class Payouts extends Model
{
	use CurrencyConversion;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payouts';
	
	protected $fillable = ['order_id', 'order_detail_id', 'user_id', 'user_type', 'account', 'subtotal', 'service', 'shipping', 'amount', 'currency_code', 'status'];

	//join with Payout
	public function payout_preferences()
	{
		return $this->hasMany('App\Models\PayoutPreferences', 'user_id', 'user_id')->where('default', 'yes');
	}

	public function users()
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function order_detail()
	{
		return $this->belongsTo('App\Models\OrdersDetails', 'order_detail_id', 'id');
	}

	public function getUpdatedAtAttribute()
	{
		return date('F d, Y', strtotime($this->attributes['updated_at']));

	}
	// Get Payout Amount
	public function getAmountAttribute()
	{
		return $this->currency_calc('amount');
	}

	public function getSessionAmountAttribute()
	{
		return $this->currency_calc('amount');
	}

	public function getSessionSubtotalAttribute()
	{
		return $this->currency_calc('subtotal');
	}

	public function getSessionServiceAttribute()
	{
		return $this->currency_calc('service');
	}

	public function getSessionShippingAttribute()
	{
		return $this->currency_calc('shipping');
	}

	// Get Payout  Original Amount
	public function getOriginalAmountAttribute()
	{
		return @$this->attributes['amount'];
	}
}