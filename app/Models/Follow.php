<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
      protected $table="follow";

    // Join with users table
    public function follower_user()
    {
        return $this->belongsTo('App\Models\User','follower_id','id');
    }

    // Join with users table
    public function following_users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
