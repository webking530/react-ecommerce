<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\StoresDataTable;
use App\Models\User;
use App\Http\Start\Helpers;
use File;
use Validator;

class StoreController extends Controller
{

    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StoresDataTable $dataTable)
    {
        
        return $dataTable->render('admin.stores.view');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_update(Request $request)
    {
        $status=User::where('id',$request->id)->first()->status;
        $type = $request->type;
        $type_status=User::where('id',$request->id)->first()->$type;
        if($type_status=="No")
        {
            $data[$request->type]="Yes";
            User::where('id', $request->id)->update($data);           
        }
        else
        {
            $data[$request->type]="No";
            User::where('id', $request->id)->update($data);           
        }
        $this->helper->flash_message('success','Updated Successfully.'); 
        return redirect('admin/stores');
    }

}
