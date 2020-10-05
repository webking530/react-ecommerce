<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\ProductReportsDataTable;
use App\Models\ProductReports;
use Validator;
use Session;
use File;

class ProductReportsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductReportsDataTable $dataTable)
    {
        return $dataTable->render('admin.product_reports');
    }
}
