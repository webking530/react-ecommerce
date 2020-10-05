<?php

namespace App\Models;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Session;
use Share;
use App\Traits\CurrencyConversion;

class Product extends Model
{
	use CurrencyConversion;
	
	public $fillable = ['user_id', 'category_id', 'title', 'description', 'total_quantity', 'sold', 'sold_out', 'return_policy', 'exchange_policy', 'policy_description'];

	protected $appends = ['option_count', 'like_count', 'like_user', 'image_name', 'user_like', 'user_name', 'original_retail_price', 'original_discount', 'original_total_quantity', 'return_days', 'click_count', 'category_name', 'product_user', 'shipping_type', 'paypal_email', 'store_name', 'share_url', 'code', 'currency_symbol', 'price', 'video_src', 'video_thumb', 'video_type', 'qty_option', 'payout_method','session_price'];

	public function scopeActiveUser($query)
	{
		$query = $query->whereHas('users', function ($sub_query) {
			$sub_query->activeOnly();
		});
		return $query;
	}

	public function scopeApproved($query)
	{
		$query = $query->where('admin_status', 'Approved');
		return $query;
	}

	public function scopeisHeader($query)
	{
		return $query->where('is_header', 'Yes');
	}

	public function scopeisActive($query)
	{
		return $query->where('status', 'Active');
	}

	public function scopeActiveProduct($query)
	{
		return $query->approved()->isActive()->where('sold_out',"No")->where('total_quantity','>','0');
	}

	public function scopeHeaderOnly($query)
	{
		return $query->isHeader()->activeUser()->activeProduct();
	}

	public function scopeisLike($query,$search_key)
	{
		return $query->where('title', 'LIKE', '%'.$search_key.'%');
	}

	public function product_option()
	{
		return $this->hasMany('App\Models\ProductOption', 'product_id', 'id');
	}

	public function wishlist()
	{
		return $this->belongsTo('App\Models\Wishlists', 'id', 'product_id');
	}

	//join with product option images
	public function product_option_images()
	{
		return $this->hasMany('App\Models\ProductOptionImages', 'product_id', 'id');
	}

	// Join with products_price table
	public function products_prices_details()
	{
		return $this->belongsTo('App\Models\ProductPrice', 'id', 'product_id');
	}

	public function products_like_details()
	{
		return $this->belongsTo('App\Models\ProductLikes', 'id', 'product_id');
	}

	public function categories()
	{
		return $this->belongsTo('App\Models\Category', 'category_id', 'id');
	}

	// Join with rooms_price table
	public function products_images()
	{
		return $this->belongsTo('App\Models\ProductImages', 'id', 'product_id');
	}

	// Join with users table
	public function users()
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function users_address()
	{
		return $this->belongsTo('App\Models\UserAddress', 'user_id', 'user_id');
	}

	public function merchant_store()
	{
		return $this->belongsTo('App\Models\MerchantStore', 'user_id', 'user_id');
	}

	//Get product photo all
	public function product_photos()
	{
		return $this->hasMany('App\Models\ProductImages', 'product_id', 'id')->orderBy('id');

	}

	// Get product shipping_type URL
	public function getShippingTypeAttribute()
	{
		$result = ProductShipping::where('product_id', $this->attributes['id']);
		return @$result->first()->shipping_type;
	}

	// Get product image_name URL
	public function getImageNameAttribute()
	{
		$result = ProductImages::where('product_id', $this->attributes['id'])->first();
		if ($result) {
			return $result->images_name;
		}
		return url('image/profile.png');
	}

	// Get product price table
	public function getPriceAttribute()
	{
		if ($this->product_option->count() > 0) {
			$result = ProductOption::where('product_id', @$this->attributes['id']);
		}
		else {			
			$result = ProductPrice::where('product_id', @$this->attributes['id']);
		}

		$product_price = $result->first();

		return optional($product_price)->price ?? 0;
	}

	// Get user full name
	public function getUserNameAttribute()
	{
		$result = User::where('id', $this->attributes['user_id'])->first();
		if ($result) {
			return $result->full_name;
		}
		return '';
	}

	// Get user store name
	public function getStoreNameAttribute()
	{
		$result = MerchantStore::where('user_id', $this->attributes['user_id']);
		return @$result->first()->store_name;
	}

	public function getPaypalEmailAttribute()
	{
		$result = PayoutPreferences::where('user_id', $this->attributes['user_id'])->where('default', 'yes');
		return @$result->first()->paypal_email;
	}

	public function getPayoutMethodAttribute()
	{
		$result = PayoutPreferences::where('user_id', $this->attributes['user_id'])->where('default', 'yes');
		return @$result->first()->payout_method;
	}

	// get product user name
	public function getProductUserAttribute()
	{
		$result = User::where('id', $this->attributes['user_id'])->first();
		if ($result) {
			return $result->user_name;
		}
		return '';
	}

	// Get Categories table
	public function getCategoryNameAttribute()
	{
		$result = Category::where('id', $this->attributes['category_id'])->first();
		if ($result) {
			return $result->title;
		}
		return '';
	}

	// Join with rooms_price table
	public function products_shipping()
	{
		return $this->hasMany('App\Models\ProductShipping', 'product_id', 'id');
	}

	//get like user details
	public function getLikeUserAttribute()
	{

		$product_id = $this->attributes['id'];
		$result = ProductLikes::with(['users' => function ($query) {$query->where('users.status', '!=', 'Inactive');}])->where('product_id', '=', $product_id)->whereHas('users', function ($query) {$query->where('users.status', '!=', 'Inactive');})->orderBy('id', 'desc')->get();

		return $result;
	}

	public function getOptionCountAttribute()
	{
		$option_count = ProductOption::where('product_id', $this->attributes['id'])->count();
		return $option_count;
	}

	//login user product like or not check
	public function getUserLikeAttribute()
	{
		$result = ProductLikes::with([
			'users' => function ($query) {
				$query->where('users.status', '!=', 'Inactive');
			},
		])->whereHas('users', function ($query) {$query->where('users.status', '!=', 'Inactive');})->where('product_id', $this->attributes['id'])->where('user_id', Auth::id())->get();
		return $result;
	}

	// get like count
	public function getLikeCountAttribute()
	{
		$like_counts = ProductLikes::where('product_id', $this->attributes['id'])->count();
		return $like_counts;
	}

	// Get product Retail Price table
	public function getOriginalRetailPriceAttribute()
	{
		$retail_price = ProductOption::where('product_id', @$this->attributes['id'])->get();

		if ($retail_price != '') {
			$result = ProductOption::where('product_id', @$this->attributes['id']);
		}
		else {
			$result = ProductPrice::where('product_id', @$this->attributes['id']);
		}

		return @$result->first()->retail_price;
	}

	// Get product Discount table
	public function getOriginalDiscountAttribute()
	{
		$discount = ProductOption::where('product_id', @$this->attributes['id'])->first();

		if ($discount != '') {
			$result = ProductOption::where('product_id', @$this->attributes['id']);
		}
		else {
			$result = ProductPrice::where('product_id', @$this->attributes['id']);
		}

		return @$result->first()->discount;
	}

	// Get product quantity table
	public function getOriginalTotalQuantityAttribute()
	{
		$total_quantity = ProductOption::where('product_id', @$this->attributes['id'])->first();

		if ($total_quantity != '') {
			$result = ProductOption::where('product_id', @$this->attributes['id']);
		}
		else {
			$result = Product::where('id', @$this->attributes['id']);
		}

		return @$result->first()->total_quantity;
	}

	// Get product quantity option
	public function getQtyOptionAttribute()
	{
		$total_quantity = ProductOption::where('product_id', @$this->attributes['id'])->first();

		if ($total_quantity != "") {
			$result = ProductOption::where('product_id', @$this->attributes['id']);
		}
		else {
			$result = Product::where('id', @$this->attributes['id']);
		}

		$quantity = @$result->first()->total_quantity;

		$qty_select = '<select id="quantity" class="select-boxes2">';

		if ($quantity != 0) {
			for ($i = 1; $i <= $quantity; $i++) {
				$qty_select = $qty_select . '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		else {
			$qty_select = $qty_select . '<option value="1">1</option>';
		}

		$qty_select = $qty_select . '</select>';

		return $qty_select;

	}
	// get return policy days
	public function getReturnDaysAttribute()
	{
		return @ReturnPolicy::where('id', @$this->attributes['return_policy'])->first()->days;
	}

	public function returns_policy()
	{
		return $this->belongsTo('App\Models\ReturnPolicy', 'return_policy', 'id');
	}

	public function exchanges_policy()
	{
		return $this->belongsTo('App\Models\ReturnPolicy', 'exchange_policy', 'id');
	}

	public function getClickCountAttribute()
	{
		return ProductClick::where('product_id', $this->attributes['id'])->count();
	}

	public function getShareUrlAttribute()
	{
		$site_url = \App::runningInConsole() ? SITE_URL : url('/');

		return Share::load($site_url . "/things/" . $this->attributes['id'])->services('facebook', 'twitter');
	}

	// delete for products relationship data (for all table) $this->attributes['id']
	public function Delete_All_Product_Relationship()
	{
		if ($this->attributes['id'] == '') {
			return false;
		}
		try {
			$wishlist = Wishlists::where('product_id', $this->attributes['id'])->delete();
			$productclick = ProductClick::where('product_id', $this->attributes['id'])->delete();
			$productlikes = ProductLikes::where('product_id', $this->attributes['id'])->delete();
			$productshipping = ProductShipping::where('product_id', $this->attributes['id'])->delete();
			$productprice = ProductPrice::where('product_id', $this->attributes['id'])->delete();
			$productoptionimages = ProductOptionImages::where('product_id', $this->attributes['id'])->delete();
			$productoption = ProductOption::where('product_id', $this->attributes['id'])->delete();
			$productimages = ProductImages::where('product_id', $this->attributes['id'])->delete();
		}
		catch (\Exception $e) {
			
		}
		
		$orders_details = OrdersDetails::where('product_id', $this->attributes['id'])->whereIn('status', ['Cancelled', 'Completed'])->get();

		if ($orders_details->count()) {
			foreach ($orders_details as $orders) {
				try {
					Payouts::where('order_detail_id', $orders->id)->delete();
					OrdersReturn::where('order_id', $orders->id)->delete();
					OrdersCancel::where('order_id', $orders->id)->delete();
					OrdersBillingAddress::where('order_id', $orders->id)->delete();
					OrdersShippingAddress::where('order_id', $orders->id)->delete();
					OrdersDetails::find($orders->id)->delete();
					Orders::find($orders->order_id)->delete();
				}
				catch(\Exception $exception) {

				}
			}
		}

		$cart = Cart::where('product_id', $this->attributes['id']);
		if ($cart) {
			$cart->delete();
		}

		Product::find($this->attributes['id'])->delete();

		return true;
	}

	public function getVideoThumbAttribute()
	{
		$src = @$this->attributes['video_thumb'];
		$product_id = $this->attributes['id'];
		if ($src == '') {
			$imagesname = '';
		}
		else {
			$photo_src = explode('.', $src);
			if (count($photo_src) > 1) {
				$imagesname = url('image/products/' . $product_id . '/' . $src);
			}
			else {
				$options['secure'] = TRUE;
				$imagesname = \Cloudder::show($src, $options);
			}
		}
		return $imagesname;
	}

	public function getVideoSrcAttribute()
	{
		if (USER_AGENT == "Firefox") {
			$src = @$this->attributes['video_webm'];
		}
		else {
			$src = @$this->attributes['video_mp4'];
		}

		$product_id = $this->attributes['id'];
		if ($src == '') {
			$imagesname = '';
		}
		else {
			$photo_src = explode('.', $src);
			if (count($photo_src) > 1) {
				$imagesname = url('image/products/' . $product_id . '/' . $src);
			}
			else {
				$options['secure'] = TRUE;
				$options['resource_type'] = "video";
				$imagesname = \Cloudder::show($src, $options);
			}
		}	
		return $imagesname;
	}

	public function getVideoTypeAttribute()
	{
		if (USER_AGENT == "Firefox") {
			return "webm";
		}
		return "mp4";
	}

	// Get product session_price table
	public function getSessionPriceAttribute()
	{
		return $this->currency_calc($this->price,@$this->products_prices_details->currency_code);
	}

	public function productLikes()
    {
        return $this->belongsTo('App\Models\ProductLikes','product_id','id')->where('user_id',Auth::user()->id);
    }	
}