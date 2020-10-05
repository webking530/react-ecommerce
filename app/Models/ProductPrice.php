<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Traits\CurrencyConversion;

class ProductPrice extends Model
{
	use CurrencyConversion;

	protected $table = 'products_prices_details';

	protected $primaryKey = 'product_id';

	public $fillable = ['product_id', 'sku', 'price', 'retail_price', 'discount', 'length', 'width', 'height', 'weight', 'currency_code'];

	protected $appends = ['code', 'original_price', 'original_retail_price', 'original_currency_code', 'original_currency_symbol'];

	// Get result of  price for current currency
	public function getPriceAttribute()
	{
		return $this->currency_calc('price');
	}

	public function getOriginalPriceAttribute()
	{
		return $this->attributes['price'];
	}
	public function getOriginalCurrencyCodeAttribute()
	{
		return $this->attributes['currency_code'];
	}

	// Get result of retail price for current currency
	public function getRetailPriceAttribute()
	{
		return $this->currency_calc('retail_price');
	}
	// Get result of retail price for current currency
	public function getOriginalRetailPriceAttribute()
	{
		return $this->attributes['retail_price'];
	}
}
