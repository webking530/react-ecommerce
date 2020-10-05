<?php

/**
 * Admin Model
 *
 * @package     Gofer
 * @subpackage  Model
 * @category    Admin
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait;
use DB;

class Admin extends Authenticatable
{
    use Notifiable;

    use LaravelEntrustUserTrait;

    protected $guard = 'admin';

    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Update Admin Role
    public static function update_role($user_id, $role_id)
    {
        return DB::table('role_user')->where('user_id', $user_id)->update(['role_id' => $role_id]);
    }
}
