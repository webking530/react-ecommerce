<?php

/**
 * Owe Amount Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Owe Amount
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\OweDataTable;
use App\Http\Start\Helpers;
use Validator;

class OweController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Pages
     *
     * @param array $dataTable  Instance of PagesDataTable
     * @return datatable
     */
    public function index(OweDataTable $dataTable)
    {
        return $dataTable->render('admin.owe.view');
    }
}
