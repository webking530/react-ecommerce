<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowStore extends Model
{
    protected $table="follow_store";

      public function store_details()
    {
    	return $this->belongsTo('App\Models\MerchantStore','store_id','id');
    }

}


