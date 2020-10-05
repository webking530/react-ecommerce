<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\BlockUsersDataTable;
use App\Models\BlockUsers;
use Validator;
use Session;
use File;

class BlockUsersController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BlockUsersDataTable $dataTable)
    {
        return $dataTable->render('admin.block_users');
    }
}
