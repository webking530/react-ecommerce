<?php

/**
 * ApiCredential Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    ApiCredential
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiCredentials extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'api_credentials';

    public $timestamps = false;
}
