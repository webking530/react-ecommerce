<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReports extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_reports';

    // Join with Users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    // Join with Products table
    public function products()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
}
