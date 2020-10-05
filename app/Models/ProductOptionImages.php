<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionImages extends Model
{
	protected $table = 'products_options_images';
    public $fillable = ['product_id','product_option_id','image_name'];

    /**
     * Get the index name for the model.
     *
     * @return string
    */
    
}
