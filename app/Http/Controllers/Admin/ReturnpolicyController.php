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
use App\DataTables\ReturnPolicyDataTable;
use App\Models\ReturnPolicy;
use App\Models\Currency;
use App\Models\Product;
use App\Http\Start\Helpers;
use Validator;

class ReturnpolicyController extends Controller
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
    public function index(ReturnPolicyDataTable $dataTable)
    {
        return $dataTable->render('admin.return_policy.view');
    }
    public function view(ReturnPolicyDataTable $dataTable)
    {
        return $dataTable->render('admin.return_policy.view');
    }

    public function add_return_policy(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.return_policy.add');
        }
        else
        {
           $customMessages = [
                'days.required' => 'The days field is required',
                'days.digits_between' => 'Please enter number only.',
                'days.numeric' => 'Please enter number only.',
                'name.required' => 'The display text fields is required'
            ];
            $this->validate($request, [
                'days' => 'required|digits_between:1,100|numeric',
                'name' => 'required'
            ],$customMessages);
            $returns=ReturnPolicy::where(['days' => $request->days]);
            if($returns->count())
            {
                $this->helper->flash_message('error', 'Days already exists'); // Call flash message function
                return redirect('admin/add_return_policy');
            }
            else
            {
                $return_data['days']=$request->days;
                $return_data['name']=$request->name;
                ReturnPolicy::create($return_data);    
                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect('admin/returns_policy');
            }
            
        }
    }

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$_POST) 
        {
            $data['result'] = @ReturnPolicy::where('id', $request->id)->first();
            
            if(!empty($data['result']))
                return view('admin.return_policy.edit', $data);
            else
                abort('404');
            
        }
        else
        {
            $customMessages = [
                'days.required' => 'The days field is required',
                'days.digits_between' => 'Please enter number only.',
                'days.numeric' => 'Please enter number only.',
                'name.required' => 'The display text fields is required'
            ];
            $this->validate($request, [
                'days' => 'required|digits_between:1,100|numeric',
                'name' => 'required'
            ],$customMessages);

            
            $returns=ReturnPolicy::where(['days' => $request->days])->where('id','<>',$request->id);
            if($returns->count())
            {
                $this->helper->flash_message('error', 'Days already exists'); // Call flash message function
                return redirect('admin/edit_return_policy/'.$request->id);
            }
            else
            {
                $update['days']=$request->days;
                $update['name']=$request->name;
                ReturnPolicy::where('id',$request->id)->update($update);
                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect('admin/returns_policy');
            }
           
        }
    }

    public function delete(Request $request)
    {
        $return_products=Product::where('return_policy',$request->id)->count();
        if($return_products)
        {
        $this->helper->flash_message('error', 'This Return Policy is used in some products. So can\'t delete this return policy. '); // Call flash message function
        }
        else
        {

            $return_policy_count =  ReturnPolicy::all()->count();
            
            if($return_policy_count > 1)
            {
                ReturnPolicy::where('id', $request->id)->delete();
                $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function  
            }
            else{
               $this->helper->flash_message('error', 'Return Policy cannot be deleted. Because at least you have only one return policy.'); // Call flash message function  
            }
             

        }
        
        return redirect('admin/returns_policy');
    }


}
