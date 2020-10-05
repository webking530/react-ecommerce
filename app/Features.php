<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    //
    protected $table = 'feature';

    public $timestamps = false;

    // adding extra column image Url with Data

    public $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
    	$photo_src = explode('.',$this->attributes['image']);
    	if(count( $photo_src ) > 1)
    	{
    		return url('/').'/image/homepage/'.$this->attributes['image'];
    	}
    	else
        {
            $options['secure']=TRUE;
            $options['crop']    = 'fill';
            return $src=\Cloudder::show($this->attributes['image'],$options);
        }

    }

}
