<?php

/**
 * Return Policy Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Return Policy
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\ProductsDataTable;
use App\Models\ReturnPolicy;
use App\Models\Currency;
use App\Http\Start\Helpers;
use Validator;

class ReturnPolicyController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Fees Data
     *
     * @return redirect     to fees
     */
    public function index(ProductsDatatable $dataTable)
    {
        return $dataTable->render('admin.products.view');
    }

    public function add_return_policy(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = Fees::get();
            return view('admin.fees', $data);
        }
        else if($request->submit)
        {
            // Fees Validation Rules
            $rules = array(
                    'service_fee' => 'numeric'
                    );

            // Fees Validation Custom Names
            $niceNames = array(
                        'service_fee' => 'Service Fee'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                Fees::where(['name' => 'service_fee'])->update(['value' => $request->service_fee]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            
                return redirect('admin/fees');
            }
        }
        else
        {
            return redirect('admin/fees');
        }
    }

    public function host_penalty_fees(Request $request)
    {
        $rules = [];

        if($request->penalty_mode == 1)
        {
            // Fees Validation Rules
            $rules = array(
                'penalty_currency'  => 'required',
                'before_seven_days' => 'required|numeric',
                'after_seven_days'  => 'required|numeric',
                'cancel_limit'      => 'required|numeric'
                );
        }

        // Fees Validation Custom Names
        $niceNames = array(
                    'penalty_currency'  => 'Currency',
                    'before_seven_days' => 'Cancel Before Seven days',
                    'after_seven_days'  => 'Cancel After Seven days',
                    'cancel_limit'      => 'Cancel Limit'
                    );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($niceNames); 

        if ($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
        }
        else
        {   
            $currency_code = Currency::where('id',$request->penalty_currency)->first()->code;

            Fees::where(['name' => 'host_penalty'])->update(['value' => $request->penalty_mode]);
                
            if($request->penalty_mode == 1)
            {
                Fees::where(['name' => 'currency'])->update(['value' => $currency_code]);
                Fees::where(['name' => 'before_seven_days'])->update(['value' => $request->before_seven_days]);
                Fees::where(['name' => 'after_seven_days'])->update(['value' => $request->after_seven_days]);
                Fees::where(['name' => 'cancel_limit'])->update(['value' => $request->cancel_limit]);
            }

            $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            return redirect('admin/fees');
        }
    }
}
