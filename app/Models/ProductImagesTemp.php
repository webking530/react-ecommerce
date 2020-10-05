<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImagesTemp extends Model
{
	protected $table = 'tmp_product_images';
    public $fillable = ['product_id','image_name','option'];
    protected $appends = ['images_name'];

    /**
     * Get the index name for the model.
     *
     * @return string
    */

    public function getImagesNameAttribute()
    {
        $src = $this->attributes['image_name'];
        $product_id = $this->attributes['product_id'];
        if($src == '')
        {
            $imagesname = url('image/profile.png');
        }
        else
        {
            $photo_src=explode('.',$this->attributes['image_name']);
            if(count($photo_src)>1)
            {
                $imagesname = url('image/products/'.$product_id.'/'.$src);
            }
            else
            {
                $options['secure']=TRUE;
                if($this->attributes['option']=="video_mp4" || $this->attributes['option']=="video_webm")
                    $options['resource_type']="video";
                
                $imagesname=\Cloudder::show($this->attributes['image_name'],$options);
            }      
        }
        return $imagesname;
    }
    
}
