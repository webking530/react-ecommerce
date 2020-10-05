<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductClick extends Model
{
	protected $table = 'product_click';

    public $fillable = ['product_id','user_id'];

    public $timestamps = false; // disable all behaviour

    // Join with products table
    public function products()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }

    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
