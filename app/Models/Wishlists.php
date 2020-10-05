<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlists extends Model
{
    protected $table="wishlists";

    public $timestamps = false;

    public function wish_product_details()
    {
    	return $this->belongsTo('App\Models\Product','product_id','id');
    }

    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','follower_id','id');
    }
}
