<?php

/**
 * Currency Conversion Trait
 *
 * @package     Spiffy
 * @subpackage  Traits
 * @category    Currency Conversion
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Traits;
use App\Models\Currency;
use DB;
use Session;

trait CurrencyConversion
{
	// Join with currency table
	public function currency()
	{
		return $this->belongsTo('App\Models\Currency', 'currency_code', 'code');
	}

	public function getCurrencyCode()
	{
		return @$this->attributes['currency_code'];
	}

	// Calculation for current currency conversion of given price field
	public function currency_calc($field)
	{
		$currency_code = $this->getCurrencyCode();
		if($currency_code == '') {
			return 0;
		}

		$rate = Currency::whereCode($currency_code)->first()->rate;
		$usd_amount = @$this->attributes[$field] / $rate;

		if (request()->segment(1) == 'api') {			
			$session_rate = Currency::whereCode(api_currency_code)->first()->rate;
			return round($usd_amount * $session_rate);
		}

		if (request()->segment(1) == 'admin' && request()->segment(2) == 'products') {
			return @$this->attributes[$field];
		}

		$default_currency = Currency::where('default_currency', 1)->first()->code;

		$session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;

		return round($usd_amount * $session_rate);
	}

	// Get default currency code if session is not set
	public function getCodeAttribute()
	{
		//Check current user login is web or mobile
		if (request()->segment(1) == 'api') {
			return DB::table('currency')->whereCode('USD')->first()->code;
		}

		if (Session::get('currency')) {
			return Session::get('currency');
		}

		return DB::table('currency')->where('default_currency', 1)->first()->code;
	}

	// Get default currency symbol if session is not set
	public function getCurrencySymbolAttribute()
	{
		if(Session::get('symbol')) {
           $symbol = Session::get('symbol');
        }
        else {
           $symbol = DB::table('currency')->where('default_currency', 1)->first()->symbol;
        }

        return html_entity_decode($symbol);
	}

	public function getOriginalCurrencySymbolAttribute()
	{
		return Currency::original_symbol(@$this->attributes['currency_code']);
	}
}