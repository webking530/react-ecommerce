<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;
use App\Traits\CurrencyConversion;

class ProductOption extends Model
{
	use CurrencyConversion;

	protected $table = 'products_options';
	
	public $fillable = ['product_id', 'sku', 'option_name', 'total_quantity', 'sold', 'price', 'retail_price', 'discount', 'length', 'width', 'height', 'weight', 'sold_out', 'currency_code'];

	protected $appends = ['code', 'original_price', 'original_retail_price'];

	//join with product option images
	public function product_option_images()
	{
		return $this->hasMany('App\Models\ProductOptionImages', 'product_option_id', 'option_id');
	}

	// Get result of  price for current currency
	public function getPriceAttribute()
	{
		return $this->currency_calc('price');
	}

	public function getOriginalPriceAttribute()
	{
		return $this->attributes['price'];
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