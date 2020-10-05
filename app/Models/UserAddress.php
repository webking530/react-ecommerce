<?php

/**
 * User Address Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    User Address
 * @version     1.5
 * @author      Trioangle Product Team
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_address';
    public $fillable = ['address_line', 'address_line2','city', 'postal_code', 'state','country','phone_number'];

}
