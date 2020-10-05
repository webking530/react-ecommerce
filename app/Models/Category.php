<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public $fillable = ['title', 'parent_id'];

    protected $appends = ['icon_name','original_image_name','original_icon_name'];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function childs()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->whereStatus('Active');
    }

    // Get all Active status records
    public static function active_all()
    {
        return Category::whereStatus('Active')->get();
    }

    // Get all Active status records
    public static function parent_categories()
    {
        return Category::whereParentId(0)->whereStatus('Active')->get();
    }

    // Get all Active status records
    public static function child_categories($parent_id)
    {
        return Category::whereParentId($parent_id)->whereStatus('Active')->get();
    }

    // Get picture source URL based on photo_source
    public function getImageNameAttribute()
    {
        $original_src = @$this->attributes['image_name'];

        if($original_src == '' || $original_src == 'null') {
            return url('image/new-navigation.png');
        }

        $photo_src= explode('.',$original_src);
        if(count($photo_src)>1) {
            $image_uploader = resolve('App\Services\LocalImageHandler');
            $name_suffix = '';
            $default_src = url('image/new-navigation.png');
            $file_path  = 'image/category/'.$this->attributes['id'].'/';
            $options = compact('name_suffix','default_src','file_path');
            
            return $image_uploader->getImage($original_src,$options);
        }

        $options['secure'] = TRUE;
        $src=\Cloudder::show($this->attributes['image_name'],$options);
        return $src;
    }

    public function getIconNameAttribute()
    {
        $original_src = @$this->attributes['icon_name'];

        if($original_src == '' || $original_src == 'null') {
            return url('image/new-navigation.png');
        }

        $photo_src= explode('.',$original_src);
        if(count($photo_src)>1) {
            $image_uploader = resolve('App\Services\LocalImageHandler');
            $name_suffix = '_30x30';
            $default_src = url('image/new-navigation.png');
            $file_path  = 'image/category/'.$this->attributes['id'].'/';
            $options = compact('name_suffix','default_src','file_path');
            
            return $image_uploader->getImage($original_src,$options);
        }

        $options['secure'] = TRUE;
        $src=\Cloudder::show($this->attributes['icon_name'],$options);
        return $src;
    }

    public function getHeaderImageAttribute()
    {
        $original_src = @$this->attributes['image_name'];

        if($original_src == '' || $original_src == 'null') {
            return url('image/new-navigation.png');
        }

        $photo_src= explode('.',$original_src);
        if(count($photo_src)>1) {
            $image_uploader = resolve('App\Services\LocalImageHandler');
            $name_suffix = '_104x104';
            $default_src = url('image/new-navigation.png');
            $file_path  = 'image/category/'.$this->attributes['id'].'/';
            $options = compact('name_suffix','default_src','file_path');
            
            return $image_uploader->getImage($original_src,$options);
        }

        $options['secure'] = TRUE;
        $src=\Cloudder::show($this->attributes['image_name'],$options);
        return $src;
    }

    // Get picture source URL based on photo_source
    public function getOriginalImageNameAttribute()
    {
        return @$this->attributes['image_name'];        
    }

    // Get picture source URL based on photo_source
    public function getOriginalIconNameAttribute()
    {
        return @$this->attributes['icon_name'];     
    }

    public static function get_parent_categories($category)
    {
        $parent_categories = collect();
        $parent_category = Category::where('id', $category->parent_id)->first();
        if ($parent_category) {
            $parent_categories[] = $parent_category;
            if ($parent_category->parent_id != 0) {
                $recurse_check = Category::get_parent_categories($parent_category);
                $recurse_check[] = @$parent_categories[0];
                $parent_categories = $recurse_check;
            }
        }       
        return $parent_categories;
    }

    public function is_parent($category_id)
    {
        $this_id = $this->attributes['id'];
        $parent_categories = $this->get_parent_categories($this)->pluck('id')->toArray();
        return in_array($category_id, $parent_categories);
    }

    public function is_parent_category_name($category_name, $check_id)
    {
        $this_category = Category::where('title', $category_name)->first();
        if ($this_category) {
            return $this_category->is_parent($check_id);
        }
        return false;
    }
}