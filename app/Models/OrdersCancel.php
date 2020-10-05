<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrdersCancel extends Model
{
    /**
     * Get the index name for the model.
     *
     * @return string
    */    

    protected $table="order_cancel";

    public $fillable = ['order_id','cancel_reason'];

}
