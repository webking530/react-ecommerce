<?php

/**
 * Role Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    Role
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Models;

use Shanmuga\LaravelEntrust\Models\EntrustRole;
use DB;

class Role extends EntrustRole
{
	// Get permission_id in lists type
    public static function permission_role($id)
    {
        return DB::table('permission_role')->where('role_id', $id)->pluck('permission_id')->toArray();
    }

    // Get role_user data by using given id
    public static function role_user($id)
    {
        return DB::table('role_user')->where('user_id', $id)->first();
    }
}
