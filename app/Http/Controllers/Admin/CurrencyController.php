<?php

/**
 * Currency Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CurrencyDataTable;
use App\Models\Currency;
use App\Http\Start\Helpers;
use Validator;
use App\Models\ProductPrice;
use App\Models\Orders;

class CurrencyController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Currency
     *
     * @param array $dataTable  Instance of CurrencyDataTable
     * @return datatable
     */
    public function index(CurrencyDataTable $dataTable)
    {        
        return $dataTable->render('admin.currency.view');
    }

    /**
     * Add a New Currency
     *
     * @param array $request  Input values
     * @return redirect     to Currency view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.currency.add');
        }
        else if($request->submit)
        {
            $rules = array(
                    'name'   => 'required|unique:currency|max:50',
                    'code'   => 'required|unique:currency|max:10',
                    'symbol' => 'required|max:10',
                    'rate'   => 'required|numeric|between:0,999999.99',
                    'status' => 'required'
                    );

            $niceNames = array(
                        'name'   => 'Name',
                        'code'   => 'Code',
                        'symbol' => 'Symbol',
                        'rate'   => 'Rate',
                        'status' => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $currency = new Currency;

                $currency->name   = $request->name;
                $currency->code   = $request->code;
                $currency->symbol = $request->symbol;
                $currency->rate   = $request->rate;
                $currency->default_currency = '0';
                $currency->status = $request->status;

                $currency->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/currency');
            }
        }
        else
        {
            return redirect('admin/currency');
        }
    }

    /**
     * Update Currency Details
     *
     * @param array $request    Input values
     * @return redirect     to Currency View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = Currency::find($request->id);
            

            if($data['result'] != null)
            {
                return view('admin.currency.edit', $data);
            }
            else
            {
                abort('404');
            }
        }
        else if($request->submit)
        {
            $rules = array(
                    'name'   => 'required|max:50|unique:currency,name,'.$request->id,
                    'code'   => 'required|max:10|unique:currency,code,'.$request->id,
                    'symbol' => 'required|max:10',
                    'rate'   => 'required|numeric|between:0,999999.99',
                    'status' => 'required'
                    );

            $niceNames = array(
                        'name'   => 'Name',
                        'code'   => 'Code',
                        'symbol' => 'Symbol',
                        'rate'   => 'Rate',
                        'status' => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                
                $default_currency = Currency::find($request->id)->default_currency;
                $product_used=ProductPrice::where('currency_code',$request->code)->get()->count();
                $currency = Currency::find($request->id);

			    $currency->name   = $request->name;
                $currency->code   = $request->code;
                $currency->symbol = $request->symbol;
                $currency->rate   = $request->rate;
                $currency->status = $request->status;
                if($request->status =='Inactive')
                {
                    if($default_currency == 1)
                    {
                        $this->helper->flash_message('error', 'This currency is Default Currency. So, can\'t change the Status.');
                    }
                    elseif($product_used!=0)
                    {
                        $this->helper->flash_message('error', 'This currency is used in products. So, can\'t change the Status.');   
                    }
                    else
                    {
                        $currency->save();
                        $this->helper->flash_message('success', 'Updated Successfully');                        
                    }                    
                }
                else
                {
                    $currency->save();
                    $this->helper->flash_message('success', 'Updated Successfully');
                }
                return redirect('admin/currency');
            }
        }
        else
        {
            return redirect('admin/currency');
        }
    }

    /**
     * Delete Currency
     *
     * @param array $request    Input values
     * @return redirect     to Currency View
     */
    public function delete(Request $request)
    {
        $currency_code = Currency::find($request->id)->code;

        $default_currency = Currency::find($request->id)->default_currency;
        $check_product=@ProductPrice::where('currency_code',$currency_code)->count();
        $check_order = @Orders::where('currency_code', $currency_code)->count();

        if($check_product)
        {
            $this->helper->flash_message('error', 'This currency has some products. Please delete that products, before deleting this Currency.'); return redirect('admin/currency');
        }

        if($check_order)
        {
            $this->helper->flash_message('error', "This currency has some orders. We can't delete this currency"); 
            return redirect('admin/currency');
        }

        if($default_currency == 1)
        {
            $this->helper->flash_message('error', 'This currency is Default Currency. So, change the Default Currency.'); // Call flash message function
        }
        else
        {
            Currency::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/currency');
    }
}
