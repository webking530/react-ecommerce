<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrdersReturn extends Model
{
    /**
     * Get the index name for the model.
     *
     * @return string
    */    

    protected $table="order_return";

    public $fillable = ['order_id','return_reason','status'];

    protected $appends = ['order_date'];

    public function orders_details()
    {
        return $this->hasMany('App\Models\OrdersDetails','id','order_id');
    }
    public function getOrderDateAttribute()
    {
      return date('F d, Y', strtotime($this->attributes['created_at']));
    }

}
