<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockUsers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'block_users';

    // Join with Users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    // Join with Blocked Users
    public function blocked_users()
    {
        return $this->belongsTo('App\Models\User','blocked_user_id','id');
    }
}
