<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\AdminusersDataTable;
use App\Models\Admin;
use App\Models\Orders;
use App\Models\Product;
use App\Models\OrdersDetails;
use App\Models\User;
use App\Models\Slider;
use App\Models\JoinUs;
use Auth;
use Validator;

class AdminController extends Controller
{
    /**
     * Load Index View for Dashboard
     *
     * @return view index
     */
    public function index()
    {
        $quarter1 = ['01', '02', '03'];
        $quarter2 = ['04', '05', '06'];
        $quarter3 = ['07', '08', '09'];
        $quarter4 = ['10', '11', '12'];

        $data['users_count'] = User::get()->count();
        $data['orders_count'] = Orders::get()->count();
        $data['products_count'] = Product::get()->count();
        $data['merchant_users_count'] =User::where('type','merchant')->get()->count();
        //total sale
        $order_details = OrdersDetails::where('status','Completed')->get();
        $data['total_sale'] = 0;
        foreach($order_details as $details){
           $data['total_sale'] += ($details->price * $details->quantity) + $details->shipping + $details->service ;
        }
        $data['today_users_count'] = User::whereDate('created_at', '=', date('Y-m-d'))->count();
        $data['today_orders_count'] = Orders::whereDate('created_at', '=', date('Y-m-d'))->count();
        $data['today_products_count'] = Product::whereDate('created_at', '=', date('Y-m-d'))->count();
        $data['today_merchant_users_count'] =User::where('type','merchant')->whereDate('created_at', '=', date('Y-m-d'))->get()->count();
        //today sale
         $today_order_details =OrdersDetails::whereDate('completed_at', '=', date('Y-m-d'))->where('status','Completed')->get();
         $data['today_sale'] = 0;
         foreach($today_order_details as $order_detail){
           $data['today_sale'] += ($order_detail->price * $order_detail->quantity) + $order_detail->shipping + $order_detail->service;
        }

        $chart = Orders::whereYear('created_at', '<=', date('Y'))->whereYear('created_at', '>=', date('Y')-3)->get();

        $chart_array = [];

        foreach($chart as $row)
        {
            $month = date('m', strtotime($row->created_at));
            $year = date('Y', strtotime($row->created_at));

            if(in_array($month, $quarter1))
                $quarter = 1;
            if(in_array($month, $quarter2))
                $quarter = 2;
            if(in_array($month, $quarter3))
                $quarter = 3;
            if(in_array($month, $quarter4))
                $quarter = 4;

            $array['y'] = $year.' Q'.$quarter;
            $array['amount'] = $row->total;

            $chart_array[] = $array;
        }

        $data['line_chart_data'] = json_encode($chart_array);
        return view('admin.index', $data);
    }

    /**
     * Load Datatable for Admin Users
     *
     * @param array $dataTable  Instance of AdminuserDataTable
     * @return datatable
     */
    public function view(AdminusersDataTable $dataTable)
    { 
        return $dataTable->render('admin.admin_users.view');
    }

    /**
     * Load Login View
     *
     * @return view login
     */
    public function login()
    {
        return view('admin.login');
    }

    public function get()
    {
        $slider = Slider::whereStatus('Active')->orderBy('order', 'asc')->whereFrontEnd('Adminpage')->get(); 
        $rows['succresult'] = $slider->pluck('image_url');
        return json_encode($rows);
    }


    /**
     * Update Admin User Details
     *
     * @param array $request    Input values
     * @return redirect     to Admin Users View
     */
    public function update(Request $request)
    {
        if($request->isMethod('GET')) {
            $data['result']  = Admin::find($request->id);
            return view('admin.admin_users.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Admin User Validation Rules
            $rules = array(
                'username'   => 'required|unique:admin,username,'.$request->id,
                'email'      => 'required|email|unique:admin,email,'.$request->id,
                'status'     => 'required'
            );

            // Edit Admin User Validation Custom Fields Name
            $attributes = array(
                'username'   => 'Username',
                'email'      => 'Email',
                'status'     => 'Status'
            );

            $validator = Validator::make($request->all(), $rules,[],$attributes);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $admin = Admin::find($request->id);

            $admin->username = $request->username;
            $admin->email    = $request->email;
            
            if($request->password != '') {
                $admin->password = bcrypt($request->password);
            }

            $admin->save();

            flashMessage('success', 'Updated Successfully');

            return redirect('admin/admin_users');
        }
        return redirect('admin/admin_users');
    }

    /**
     * Login Authentication
     *
     * @param array $request Input values
     * @return redirect     to dashboard
     */
    public function authenticate(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();
        if(@$admin->status != 'Inactive') {
            if(Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
                return redirect()->intended('admin/dashboard');
            }

            flashMessage('danger', 'Log In Failed. Please Check Your Username/Password');
            return redirect('admin/login');
        }

        flashMessage('danger', 'Log In Failed. You are Blocked by Admin.');
        return redirect('admin/login');
    }

    /**
     * Admin Logout
     */    
    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect('admin/login');
    }

    public function joinUs(Request $request)
    {
        if($request->isMethod("GET")) {
            $result = resolve("join_us");
            return view('admin.join_us',compact('result'));
        }

        $result = resolve("join_us");

        foreach ($result as $join) {
            $key = $join->name;
            $join->value = $request->$key ?? '';
            $join->save();
        }

        flashMessage('success', 'Updated Successfully');

        return redirect()->route('admin.join_us');
    }
}