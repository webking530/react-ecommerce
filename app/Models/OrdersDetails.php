<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CurrencyConversion;
use App\Models\Currency;
use App\Models\Orders;
use App\Models\User;
use Session;
use DB;

class OrdersDetails extends Model
{
    use CurrencyConversion;

    /**
     * Get the index name for the model.
     *
     * @return string
    */    
    protected $table="orders_details";

    public $fillable = ['order_id','merchant_id','product_id','option_id','quantity','price','shipping','merchant_fee','status','incremental','return_policy','currency_code'];

    protected $appends = ['currency_symbol','currency_code','paymode','merchant_name','original_merchant','original_service','original_price','original_shipping','original_incremental','calc_owe_amount','calc_a_owe_amount','calc_r_owe_amount','payout_date'];
     // Join with products_price table
    public function products_prices_details()
    {
        return $this->belongsTo('App\Models\ProductPrice','product_id','product_id');
    }
     // Join with products_shipping table
    public function products_shipping()
    {
        return $this->hasMany('App\Models\ProductShipping','product_id','product_id');
    }
    //Get product photo all 
    public function product_photos()
    {
       return $this->hasMany('App\Models\ProductImages', 'product_id', 'product_id')->orderBy('id');
                
    }
    //Get product Product_option based on option_id
    public function product_option()
    {
        return $this->belongsTo('App\Models\ProductOption','option_id','id');
    }
    //Get product Product_option based on product_id
    public function product_option_id()
    {
        return $this->hasMany('App\Models\ProductOption','product_id','product_id');
    }
    //join with product option images
    public function product_option_images()
    {
        return $this->hasMany('App\Models\ProductOptionImages','product_id','product_id');
    }
    //join with products 
    public function products()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }

    //join with products 
    public function orders()
    {
        return $this->belongsTo('App\Models\Orders','order_id','id');
    }

    public function orders_cancel() {
        
        return $this->belongsTo('App\Models\OrdersCancel', 'order_id', 'order_id');
    }

    //join with Payout
    public function payouts()
    {
        return $this->hasMany('App\Models\Payouts','order_id','id');
    }

    //join with merchants 
    public function merchant_users()
    {
        return $this->belongsTo('App\Models\User','merchant_id','id');
    }

    public function getCurrencySymbolAttribute()
    {
        if(Session::get('symbol'))
           return Session::get('symbol');
        else
            $data = Orders::find(@$this->attributes['order_id'])->currency_code;
            return Currency::where('code',$data)->first()->symbol;        
    }
    public function getMerchantNameAttribute()
    {
        return @User::find(@$this->attributes['merchant_id'])->full_name;
    }

    public function getPaymodeAttribute()
    {
        $data = Orders::find(@$this->attributes['order_id']);
        if($data)
            return $data->paymode;        
        else
            return "paypal";
    }
    public function getOriginalMerchantAttribute()
    {
        return @$this->attributes['merchant_fee'];
    }
    public function getOriginalPriceAttribute()
    {
        return @$this->attributes['price'];
    }
    public function getOriginalShippingAttribute()
    {
        return @$this->attributes['shipping'];
    }
    public function getOriginalIncrementalAttribute()
    {
        return @$this->attributes['incremental'];
    }
    public function getOriginalServiceAttribute()
    {
        return @$this->attributes['service'];
    }
    public function getCurrencyCodeAttribute()
    {
        return @Orders::find(@$this->attributes['order_id'])->currency_code;
    }
     // Get result of  price for current currency
    public function getPriceAttribute()
    {
        return $this->currency_calc('price');
    }

    public function getTotalAttribute()
    {
        return $this->currency_calc('total');
    }

    public function getShippingAttribute()
    {
        return $this->currency_calc('shipping');
    }
    public function getIncrementalAttribute()
    {
        return $this->currency_calc('incremental');
    }
    public function getMerchantFeeAttribute()
    {
        return $this->currency_calc('merchant_fee');
    }
    public function getServiceAttribute()
    {
        return $this->currency_calc('service');
    }
    public function getCalcAoweAmountAttribute()
    {
        return @$this->currency_calc('applied_owe_amount');
    }
    public function getCalcRoweAmountAttribute()
    {
        return @$this->currency_calc('remaining_owe_amount');
    }
    public function getCalcOweAmountAttribute()
    {
        return @$this->currency_calc('owe_amount');
    }

    public function getCurrencyCode()
    {
        $order = Orders::find(@$this->attributes['order_id']);
        return $order->currency_code;
    }

    /*
     * get Payout Date
     */
    public function getPayoutDateAttribute(){
        $payout_date = date('Y-m-d', strtotime(@$this->attributes['order_return_date'].' + '.@$this->attributes['return_policy'].' days'));
        $current_date = date('Y-m-d');

        if($payout_date > $current_date){
            return "Yes";
        }else{
           return "No"; 
        }
        

    }



}
