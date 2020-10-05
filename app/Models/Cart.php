<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Cart extends Model
{

    protected $table = 'add_cart';
    public $fillable = ['product_id', 'user_id', 'option_id','quantity'];

    protected $appends = ['price','retail_price','total_quantity','qty_select'];
    
    // Join with products table    
    public function product_details()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    
    // Get product price table
    public function getPriceAttribute()
    {
        if(@$this->attributes['option_id'])
         $result    = ProductOption::where('id',@$this->attributes['option_id'])->where('product_id',@$this->attributes['product_id'])->first()->price;
        else
          $result    = ProductPrice::where('product_id',@$this->attributes['product_id'])->first()->price;

         return $result;
    }
    // Get product Retail Price table
    public function getRetailPriceAttribute()
    {
        if(@$this->attributes['option_id'])
         $result    = ProductOption::where('id',@$this->attributes['option_id'])->where('product_id',@$this->attributes['product_id'])->first()->retail_price;
        else
          $result    = ProductPrice::where('product_id',@$this->attributes['product_id'])->first()->retail_price;

         return $result;
    }

    // Get product quantity table
    public function getTotalQuantityAttribute()
    {

        if(@$this->attributes['option_id'])
         $result    = ProductOption::where('id',@$this->attributes['option_id'])->where('product_id',@$this->attributes['product_id'])->first()->total_quantity;     
        else
          $result    = Product::where('id',@$this->attributes['product_id'])->first()->total_quantity;

         return $result;
    }
    // Get product quantity option
    public function getQtySelectAttribute(){

        if(@$this->attributes['option_id'])
         $quantity    = ProductOption::where('id',@$this->attributes['option_id'])->where('product_id',@$this->attributes['product_id'])->first()->total_quantity;     
        else
          $quantity    = Product::where('id',@$this->attributes['product_id'])->first()->total_quantity;

        
        $qty_select= '';
        
        if($quantity != 0){
            for($i=1;$i<=$quantity;$i++){
                $qty_select = $qty_select.'<option value="'.$i.'">'.$i.'</option>';                    
            }
        }
        else{
           $qty_select = $qty_select.'<option value="1">1</option>';                     
        }
        

        return $qty_select;

    }


    public function product_option_details()
    {
    	return $this->belongsTo('App\Models\ProductOption','option_id','id','product_id','product_id');
    }
    public function product_shipping_details()
    {
	    return $this->hasMany('App\Models\ProductShipping','product_id','product_id');
    }


}
