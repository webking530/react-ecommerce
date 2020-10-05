<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
	protected $table = 'products_images';
    public $fillable = ['product_id','image_name'];
    protected $appends = ['images_name','compress_image','home_full_image','home_half_image','header_image','popular_image'];

    /**
     * Get the index name for the model.
     *
     * @return string
    */
    // Get picture source URL based on photo_source
    public function getImagesNameAttribute()
    {
        $src = $this->attributes['image_name'];
        $product_id = $this->attributes['product_id'];
        if($src == '')
        {
            $imagesname = url('image/new-navigation.png');
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
                 // $options['width']=450;
                 // $options['height']=250;
                 // $options['quality']=20;
                 // $options['crop']='fill';
                 // $options['fetch_format']='auto';
                $imagesname=\Cloudder::show($this->attributes['image_name'],$options);
            }      
        }
        return $imagesname;
    }

     public function getCompressImageAttribute()
    {
        $src = $this->attributes['image_name'];
        $product_id = $this->attributes['product_id'];
        if($src == '')
        {
            $imagesname = url('image/new-navigation.png');
        }
        else
        {
            $photo_src=explode('.',$this->attributes['image_name']);

            if(count($photo_src)>1)
            {     
                        $ext = substr($src, strrpos( $src, "."));
                        $compress = basename($src, $ext). "_compress" . $ext;
                        $imagesname = url('image/products/'.$product_id.'/'.$compress);
            }
            else
            {
                
                $options['secure']=TRUE;
                // $options['fetch_format']='auto';
                // $options['quality']=20;
                // $options['width']=650;
                // $options['height']=835;
                // $options['crop']='fill';
                $imagesname=\Cloudder::show($this->attributes['image_name'],$options);
            }      
        }

        return $imagesname;

    }

     public function getHomeFullImageAttribute()
    {
        $src = $this->attributes['image_name'];
        $product_id = $this->attributes['product_id'];
        if($src == '')
        {
            $imagesname = url('image/new-navigation.png');
        }
        else
        {
            $photo_src=explode('.',$this->attributes['image_name']);

            if(count($photo_src)>1)
            {     
                        $ext = substr($src, strrpos( $src, "."));
                        $home_full = basename($src, $ext). "_home_full" . $ext;
                        $imagesname = url('image/products/'.$product_id.'/'.$home_full);
            }
            else
            {
                
                $options['secure']=TRUE;
                // $options['width']=315;
                // $options['height']=315;
                // $options['crop']='fill';
                // $options['quality']=20;
                // $options['fetch_format']='auto';
                $imagesname=\Cloudder::show($this->attributes['image_name'],$options);
            }      
        }

        return $imagesname;

    }

    public function getHomeHalfImageAttribute()
    {
        $src = $this->attributes['image_name'];
        $product_id = $this->attributes['product_id'];
        if($src == '')
        {
            $imagesname = url('image/new-navigation.png');
        }
        else
        {
            $photo_src=explode('.',$this->attributes['image_name']);

            if(count($photo_src)>1)
            {     
                        $ext = substr($src, strrpos( $src, "."));
                        $home_full = basename($src, $ext). "_home_half" . $ext;
                        $imagesname = url('image/products/'.$product_id.'/'.$home_full);

            }
            else
            {
                $options['secure']=TRUE;
                // $options['width']=128;
                // $options['height']=112;                
                // $options['crop']='fill';
                // $options['quality']=20;
                // $options['fetch_format']='auto';
                $imagesname=\Cloudder::show($this->attributes['image_name'],$options);
            }      
        }

        return $imagesname;


    }


    public function getPopularImageAttribute()
    {
        $src = $this->attributes['image_name'];
        $product_id = $this->attributes['product_id'];
        if($src == '')
        {
            $imagesname = url('image/new-navigation.png');
        }
        else
        {
            $photo_src=explode('.',$this->attributes['image_name']);

            if(count($photo_src)>1)
            {     
                        $ext = substr($src, strrpos( $src, "."));
                        $home_full = basename($src, $ext). "_popular" . $ext;
                        $imagesname = url('image/products/'.$product_id.'/'.$home_full);

             }
            else
            {
                $options['secure']=TRUE;
                // $options['width']=65;
                // $options['height']=63;  
                // $options['quality']=20;                            
                // $options['crop']='fill';
                // $options['fetch_format']='auto';
                $imagesname=\Cloudder::show($this->attributes['image_name'],$options);
            }      
        }

        return $imagesname;


    }


    public function getHeaderImageAttribute()
    {
        $src = $this->attributes['image_name'];
        $product_id = $this->attributes['product_id'];
        if($src == '')
        {
            $imagesname = url('image/new-navigation.png');
        }
        else
        {
            $photo_src=explode('.',$this->attributes['image_name']);

            if(count($photo_src)>1)
            {     
                        $ext = substr($src, strrpos( $src, "."));
                        $home_full = basename($src, $ext). "_header" . $ext;
                        $imagesname = url('image/products/'.$product_id.'/'.$home_full);

             }
            else
            {
                $options['secure']=TRUE;
                // $options['width']=52;
                // $options['height']=52;
                // $options['quality']=20;                                           
                // $options['crop']='fill';
                // $options['fetch_format']='auto';
                $imagesname=\Cloudder::show($this->attributes['image_name'],$options);
            }      
        }

        return $imagesname;


    }

}
