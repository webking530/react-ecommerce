<?php

/**
 * Country Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Country
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\CountryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use App\Models\Country;
use App\Models\PayoutPreferences;
use App\Models\ShippingAddress;
use App\Models\BillingAddress;
use App\Models\UserAddress;
use App\Models\OrdersShippingAddress;
use App\Models\OrdersBillingAddress;
use App\Models\ProductShipping;
use Illuminate\Http\Request;
use Validator;

class CountryController extends Controller {
	protected $helper; // Global variable for instance of Helpers

	public function __construct() {
		$this->helper = new Helpers;
	}

	/**
	 * Load Datatable for Country
	 *
	 * @param array $dataTable  Instance of CountryDataTable
	 * @return datatable
	 */
	public function index(CountryDataTable $dataTable) {
		return $dataTable->render('admin.country.view');
	}

	/**
	 * Add a New Country
	 *
	 * @param array $request  Input values
	 * @return redirect     to Country view
	 */
	public function add(Request $request) {
		if (!$_POST) {
			return view('admin.country.add');
		} else if ($request->submit) {
			// Add Country Validation Rules
			$rules = array(
				'short_name' => 'required|max:2|unique:country',
				'long_name' => 'required|max:50|unique:country',
				'phone_code' => 'required|max:5',
				'status' => 'required',
			);

			// Add Country Validation Custom Names
			$niceNames = array(
				'short_name' => 'Short Name',
				'long_name' => 'Long Name',
				'phone_code' => 'Phone Code',
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$country = new Country;

				$country->short_name = $request->short_name;
				$country->long_name = $request->long_name;
				$country->iso3 = $request->iso3;
				$country->num_code = $request->num_code;
				$country->phone_code = $request->phone_code;
				$country->status = $request->status;

				$country->save();

				$this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

				return redirect('admin/country');
			}
		} else {
			return redirect('admin/country');
		}
	}

	/**
	 * Update Country Details
	 *
	 * @param array $request    Input values
	 * @return redirect     to Country View
	 */
	public function update(Request $request) {
		if (!$_POST) {
			$data['result'] = Country::find($request->id);

			if (!empty($data['result'])) {
				return view('admin.country.edit', $data);
			} else {
				abort('404');
			}

		} else if ($request->submit) {
			// Edit Country Validation Rules
			$rules = array(
				'short_name' => 'required|max:2|unique:country,short_name,' . $request->id,
				'long_name' => 'required|max:50|unique:country,long_name,' . $request->id,
				'phone_code' => 'required|max:5',
				'status' => 'required',
			);

			// Edit Country Validation Custom Fields Name
			$niceNames = array(
				'short_name' => 'Short Name',
				'long_name' => 'Long Name',
				'phone_code' => 'Phone Code',
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$country = Country::find($request->id);

				$country->short_name = $request->short_name;
				$country->long_name = $request->long_name;
				$country->iso3 = $request->iso3;
				$country->num_code = $request->num_code;
				$country->phone_code = $request->phone_code;
				$country->status = $request->status;

				$country->save();

				$this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

				return redirect('admin/country');
			}
		} else {
			return redirect('admin/country');
		}
	}

	/**
	 * Delete Country
	 *
	 * @param array $request    Input values
	 * @return redirect     to Country View
	 */
	public function delete(Request $request) {
		$country_count = Country::all()->count();

		if ($country_count > 1) {

			$country = Country::find($request->id);
			$short_name = $country->short_name;
			$long_name = $country->long_name;

		    $payout_preference = PayoutPreferences::where('country',$short_name)->count();
		    $shipping_address = ShippingAddress::where('country',$long_name)->count();
		    $billing_address = BillingAddress::where('country',$long_name)->count();
		    $ordershipping_address = OrdersShippingAddress::where('country',$long_name)->count();
		    $orderbilling_address = OrdersBillingAddress::where('country',$long_name)->count();
		    $user_address = UserAddress::where('country',$short_name)->count();
		    $product_ship_from = ProductShipping::where('ships_from',$long_name)->count(); 
		    $product_ship_to = ProductShipping::where('ships_to',$long_name)->count(); 
		    $product_manufacture = ProductShipping::where('manufacture_country',$long_name)->count(); 

		    if($payout_preference){
		    	$this->helper->flash_message('error', 'Some PayoutPreferences have this Country. So, We cannot delete the country.'); // Call flash message function

		    } 
		    else if($shipping_address)
		    {
		    	$this->helper->flash_message('error', 'Some Shipping address have this Country. So, We cannot delete the country.'); // Call flash message function
		    }
		    else if($billing_address)
		    {
		    	$this->helper->flash_message('error', 'Some Billing address have this Country. So, We cannot delete the country.'); // Call flash message function
		    }
		    else if($ordershipping_address)
		    {
		    	$this->helper->flash_message('error', 'Some Orders having Shipping address as this Country. So, We cannot delete the country.'); // Call flash message function
		    }
		    else if($orderbilling_address)
		    {
		    	$this->helper->flash_message('error', 'Some Orders having Billing address as this Country. So, We cannot delete the country.'); // Call flash message function
		    }
		    else if($user_address)
		    {
		    	$this->helper->flash_message('error', 'Some User\'s have been located in this Country. So, We cannot delete the country.'); // Call flash message function
		    }
		    else if($product_ship_from)
		    {
		    	$this->helper->flash_message('error', 'Some Product\'s having this Country as Shipping location. So, We cannot delete the country.'); // Call flash message function
		    }
		    else if($product_ship_to)
		    {
		    	$this->helper->flash_message('error', 'Some Product\'s having this Country as Ships to location. So, We cannot delete the country.'); // Call flash message function
		    }
		    else if($product_manufacture)
		    {
		    	$this->helper->flash_message('error', 'Some Product\'s having this Country as manufacture country. So, We cannot delete the country.'); // Call flash message function
		    }
		    else{
		    Country::find($request->id)->delete();
			$this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function	
		    }

			

		} else {
			$this->helper->flash_message('error', 'Country cannot be deleted. Because at least you have only one country.'); // Call flash message function
		}

		return redirect('admin/country');
	}
	/**
	 * Update Country status
	 *
	 * @param array $request    Input values
	 * @return redirect     to Country View
	 */
	public function update_status(Request $request) {
		$country = Country::find($request->id);
		$country->status = $request->status;
		$country->save();

		$this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

		return redirect('admin/country');
	}

}
