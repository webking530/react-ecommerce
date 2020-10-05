<?php

/**
 * Language Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    Language
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'language';

    public $timestamps = false;
}
