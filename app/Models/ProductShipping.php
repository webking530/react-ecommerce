<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Route;
use Session;
use App\Traits\CurrencyConversion;

class ProductShipping extends Model
{
	use CurrencyConversion;

	protected $table = 'products_shipping';

	public $fillable = ['product_id', 'shipping_type', 'ships_from', 'manufacture_country', 'ships_to', 'start_window', 'end_window', 'charge', 'incremental_fee'];

	protected $appends = ['original_charge', 'original_incremental_fee'];
	/**
	 * Get the index name for the model.
	 *
	 * @return string
	 */

	public function getOriginalChargeAttribute()
	{
		return $this->attributes['charge'];
	}

	public function getOriginalIncrementalFeeAttribute()
	{
		return $this->attributes['incremental_fee'];
	}

	public function getChargeAttribute()
	{
		return $this->currency_calc('charge');
	}

	public function getIncrementalFeeAttribute()
	{
		return $this->currency_calc('incremental_fee');
	}

	public function getCurrencyCode()
	{
		$product_price = ProductPrice::where('product_id', $this->attributes['product_id'])->first();
        return $product_price->currency_code;
	}
}