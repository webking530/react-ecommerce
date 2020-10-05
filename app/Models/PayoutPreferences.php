<?php

/**
 * PayoutPreferences Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    PayoutPreferences
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

class PayoutPreferences extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payout_preferences';

    public function getUpdatedTimeAttribute()
    {
		if(request()->segment(1) == 'api') { 
			$new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));
			$new_str->setTimeZone(new DateTimeZone(JWTAuth::parseToken()->authenticate()->timezone));
			return $new_str->format('d M').' at '.$new_str->format('H:i');
		}

		$new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));
		$new_str->setTimeZone(new DateTimeZone(Auth::user()->user()->timezone));
		return $new_str->format('d M').' at '.$new_str->format('H:i');
    }

    // Get Updated date for Payout Information
    public function getUpdatedDateAttribute()
    {
        if(request()->segment(1) == 'api') { 
           $new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));

           $new_str->setTimeZone(new DateTimeZone(JWTAuth::parseToken()->authenticate()->timezone));

           return $new_str->format('d F, Y');
        }
        
		$new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));
		$new_str->setTimeZone(new DateTimeZone(Auth::user()->user()->timezone));
		return $new_str->format('d F, Y');
    }

    // Join with users table
    public function users()
    {
      return $this->belongsTo('App\Models\User','user_id','id');
    }

    // get mandatory field for create stripe token
    public static function getAllMandatory()
    {
		$mandatory = [];
		$mandatory['AT'] = array('IBAN');
		$mandatory['AU'] = array('BSB','Account Number');
		$mandatory['BE'] = array('IBAN');
		$mandatory['CA'] = array('Transit Number','Account Number','Institution Number');
		$mandatory['GB'] = array('Sort Code','Account Number');
		$mandatory['HK'] = array('Clearing Code','Account Number','Branch Code');
		$mandatory['JP'] = array('Bank Code','Account Number','Branch Code','Bank Name','Branch Name','Account Owner Name ');
		$mandatory['NZ'] = array('Routing Number','Account Number');
		$mandatory['SG'] = array('Bank Code','Account Number','Branch Code');
		$mandatory['US'] = array('Routing Number','Account Number');
		$mandatory['CH'] = array('IBAN');
		$mandatory['DE'] = array('IBAN');
		$mandatory['DK'] = array('IBAN');
		$mandatory['ES'] = array('IBAN');
		$mandatory['FI'] = array('IBAN');
		$mandatory['FR'] = array('IBAN');
		$mandatory['IE'] = array('IBAN');
		$mandatory['IT'] = array('IBAN');
		$mandatory['LU'] = array('IBAN');
		$mandatory['NL'] = array('IBAN');
		$mandatory['NO'] = array('IBAN');
		$mandatory['PT'] = array('IBAN');
		$mandatory['SE'] = array('IBAN');
		return $mandatory;
    }
}