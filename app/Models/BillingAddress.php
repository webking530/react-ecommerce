<?php

/**
 * BillingAddress Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    BillingAddress
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use DateTime;
use DateTimeZone;
use Config;
use Auth;
use JWTAuth;

class BillingAddress extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'billing_address';
	public $fillable = ['name','user_id','address_line','address_line2','country','city','state','postal_code','phone_number','address_nick','is_default'];
}
