<?php

/**
 * Merchant User Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    Merchant User
 * @version     1.5
 * @author      Trioangle Product Team
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantUser extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'MerchantUser';
    public $fillable = ['store_name', 'full_name','user_name', 'email', 'password'];

}
