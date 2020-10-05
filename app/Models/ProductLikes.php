<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLikes extends Model
{
	protected $table = 'product_likes';
    public $fillable = ['product_id','user_id'];

    public $timestamps = false; // disable all behaviour

    public function scopeActiveUser($query) {
        $query = $query->whereHas('users', function ($sub_query) {
            $sub_query->where('status', '!=', 'Inactive');
        });
        return $query;
    }

    // Join with products table
    public function products()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }

    // Join with products_images table
    public function products_images()
    {
        return $this->belongsTo('App\Models\ProductImages','product_id','id');
    }
    // Join with products_price table
    public function products_prices_details()
    {
        return $this->belongsTo('App\Models\ProductPrice','product_id','product_id');
    }

    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function wishlist()
    {
        return $this->belongsTo('App\Models\Wishlists','id','product_id');
    }

}
