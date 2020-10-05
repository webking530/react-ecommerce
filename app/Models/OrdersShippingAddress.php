<?php

/**
 * Orders Shipping Address Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    Orders Shipping Address
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

class OrdersShippingAddress extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders_shipping_address';
    public $fillable = ['name','order_id','address_line','address_line2','country','city','state','postal_code','phone_number','address_nick'];
}
