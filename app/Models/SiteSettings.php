<?php

/**
 * Site Settings Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    Site Settings
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'site_settings';

    public $timestamps = false;
}
