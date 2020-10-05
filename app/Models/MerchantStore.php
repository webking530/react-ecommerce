<?php

/**
 * Merchant Store Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    Merchant Store
 * @version     1.5
 * @author      Trioangle Product Team
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantStore extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_store';
    public $fillable = ['user_id','store_name', 'tag_line','description','logo_img','header_img'];
    protected $appends = ['user_address','tagline_sub','original_logo_img','original_header_img','home_logo_img','total_products']; 

    public function scopeActiveUser($query)
    {
        $query = $query->whereHas('merchant_user_details', function($sub_query) {
            $sub_query->where('status','!=','Inactive');
        });
        return $query;
    }

    public function scopeisLike($query,$search_key)
    {
        return $query->where('store_name', 'LIKE', '%'.$search_key.'%');
    }

    // Get logo image source URL based on photo_source
    public function getOriginalLogoImgAttribute()
    {
        return $logo_img = $this->attributes['logo_img'];
    }
    // Get logo image source URL based on photo_source
    public function getOriginalHeaderImgAttribute()
    {
        return $header_img = $this->attributes['header_img'];
    }

    // Get logo image source URL based on photo_source
    public function getLogoImgAttribute()
    {
        $logo_img = $this->attributes['logo_img'];

        if($logo_img == '')
        {
            $logo_img = url('image/store_img.png');
        }
        else
        {      
            $photo_src=explode('.',$this->attributes['logo_img']);
            if(count($photo_src)>1)
            {
                $logo_img = url('image/merchant/'.$this->attributes['user_id'].'/'.$logo_img);        
            }
            else
            {
                $options['secure']=TRUE;
                $logo_img=\Cloudder::show($this->attributes['logo_img'],$options);
            }      
            
        }
        return $logo_img;
    }

    // Get home_logo_img source URL based on photo_source
    public function getHomeLogoImgAttribute()
    {
        $logo_img = $this->attributes['logo_img'];

        if($logo_img == '')
        {
            $logo_img = url('image/store_img.png');
        }
        else
        {      
            $photo_src=explode('.',$this->attributes['logo_img']);
            if(count($photo_src)>1)
            {
                $logo_img = url('image/merchant/'.$this->attributes['user_id'].'/'.$logo_img);        
            }
            else
            {
                $options['secure']=TRUE;
                
                $logo_img=\Cloudder::show($this->attributes['logo_img'],$options);
            }      
            
        }
        return $logo_img;
    }

    public static function logo_image($user_id,$name){

         $logo_img = $name;

        if($logo_img == '')
        {
            $logo_img = url('image/store_img.png');
        }
        else
        {      
            $photo_src=explode('.',$name);
            if(count($photo_src)>1)
            {
                $logo_img = url('image/merchant/'.$user_id.'/'.$logo_img);        
            }
            else
            {
                $options['secure']=TRUE;
                $logo_img=\Cloudder::show($name,$options);
            }      
            
        }
        return $logo_img;

    }

    public static function header_image($user_id,$name){

         $logo_img = $name;

        if($logo_img == '')
        {
            $logo_img = url('image/cover_image.jpg');
        }
        else
        {      
            $photo_src=explode('.',$name);
            if(count($photo_src)>1)
            {
                $logo_img = url('image/merchant/'.$user_id.'/'.$logo_img);        
            }
            else
            {
                $options['secure']=TRUE;
                $logo_img=\Cloudder::show($name,$options);
            }      
            
        }
        return $logo_img;

    }

    // Get logo image source URL based on photo_source
    public function getTaglineSubAttribute()
    {
        $taglength=strlen(@$this->attributes['tagline']);
        if($taglength>100)
        {
            return substr(@$this->attributes['tagline'],0,93)."...";
        }
        else
        {
            return @$this->attributes['tagline'];    
        }
        
    }
    // Get header image source URL based on photo_source
    public function getHeaderImgAttribute()
    {
        $header_img = $this->attributes['header_img'];

        if($header_img == '')
        {
            $header_img = url('image/cover_image.jpg');
        }
        else            
        {
            $photo_src=explode('.',$this->attributes['header_img']);
            if(count($photo_src)>1)
            {
                $header_img = url('image/merchant/'.$this->attributes['user_id'].'/'.$header_img);
            }
            else
            {
                $options['secure']=TRUE;
                $header_img=\Cloudder::show($this->attributes['header_img'],$options);
            }
                    
        }
        return $header_img;
    }
    // Get merchant user details
    public function merchant_user_details()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    // Get merchant user address details
    public function getUserAddressAttribute()
    {
    	$result = UserAddress::where('user_id', $this->attributes['user_id'])->get();
        return $result;
    }

    // Get total_products
    public function getTotalProductsAttribute()
    {
        return $this->get_products()->count();
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product','user_id','user_id');
    }
    public function get_products()
    {
        return $this->hasMany('App\Models\Product','user_id','user_id')->where('products.admin_status','Approved')->where('products.status','Active')->where('products.total_quantity','<>','0')->where('products.sold_out','No');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function follow_store()
    {
        return $this->belongsTo('App\Models\FollowStore','id','store_id');
    }
    public function products_like_details()
    {
        return $this->hasMany('App\Models\ProductLikes','user_id','user_id');
    }

    public function getStoreNameWithLimit($charLimit)
    {
        $str = @$this->attributes['store_name'];
        $strLength = strlen($str);
        if($strLength > $charLimit) {
            return substr($str,0,$charLimit)."...";
        }

        return @$this->attributes['store_name'];
    }
}