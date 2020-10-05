<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreClick extends Model
{
	protected $table = 'store_click';
    public $fillable = ['store_id','user_id'];

    public $timestamps = false; // disable all behaviour    

    // Join with products table
    public function store()
    {
        return $this->belongsTo('App\Models\MerchantStore','store_id','id');
    }
}
