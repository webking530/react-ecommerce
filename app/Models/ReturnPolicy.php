<?php
 
/**
 * Return Policy Model
 *
 * @package     Spiffy
 * @subpackage  Model
 * @category    Return Policy
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnPolicy extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'return_policy';
    protected $fillable=['days','name'];
}
