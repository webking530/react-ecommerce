<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\UsersDataTable;
use App\Models\User;
use App\Models\Country;
use App\Models\MerchantStore;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductLikes;
use App\Models\Wishlists;
use App\Models\ProductClick;
use App\Models\ProductImagesTemp;
use App\Models\ProductPrice;
use App\Models\ProductShipping;
use App\Models\ProductOption;
use App\Models\ProductOptionImages;
use App\Models\OrdersDetails;
use App\Models\OrdersBillingAddress;
use App\Models\OrdersShippingAddress;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Payouts;
use App\Models\OrdersReturn;
use App\Models\OrdersCancel;
use App\Models\FollowStore;
use App\Models\Follow;
use App\Models\BillingAddress;
use App\Models\UserAddress;
use App\Models\UsersVerification;
use App\Models\Notifications;
use App\Models\Messages;
use App\Http\Start\Helpers;
use File;
use Validator;
use App\Http\Controllers\EmailController;

class UserController extends Controller
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
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.users.view');
    }
    /**
     * Add a New User
     *
     * @param array $request  Input values
     * @return redirect     to Users view
     */
    public function add(Request $request,EmailController $email_controller)
    {
        if(!$_POST)
        {
            $data['country']    = Country::where('status','Active')->pluck('long_name','short_name');
            return view('admin.users.add',$data);
        }
        else if($request->submit)
        {
            // Add User Validation Rules
            $rules = array(
                    'type'        => 'required',
                    'full_name'   => 'required|max:35|regex:/^[a-zA-Z0-9_\- ]*$/',
                    'user_name'   => 'required|max:35|regex:/^[a-zA-Z0-9_\-]*$/|unique:users,user_name',
                    'email'       => 'required|max:255|email|unique:users',
                    'password'    => 'required|min:6',
                    'status'      => 'required',
                    );
             
             if($request->type == 'merchant'){
                $rules['store_name']    = 'required|max:150|unique:users,store_name';
                $rules['address_line']  = 'required';
                $rules['city']          = 'required|max:50';
                $rules['postal_code']   = 'required|max:50';
                $rules['state']         = 'required|max:50';
                $rules['country']       = 'required';
                $rules['phone_number']  = 'required|numeric';
            }

             // Add User Validation Custom Names
            $niceNames = array(
                        'type'       => 'User Type',
                        'full_name'  => 'Full name',
                        'user_name'  => 'User name',
                        'store_name' => 'Store name',
                        'email'      => 'Email',
                        'password'   => 'Password',                        
                        'status'     => 'Status'                        
                        );

            if($request->type == 'merchant'){
                $niceNames['address_line']  = 'Address Line';
                $niceNames['city']          = 'City';
                $niceNames['postal_code']   = 'Postal Code';
                $niceNames['state']         = 'State';
                $niceNames['country']       = 'Country';
                $niceNames['phone_number']  = 'Phone Number';
            }

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {

                $user = new User;
                
                $user->full_name  = $request->full_name;
                $user->user_name  = $request->user_name;               
                $user->email      = $request->email;
                $user->type       = $request->type;
                $user->status     = $request->status;

                if($request->password != '')
                    $user->password = bcrypt($request->password);

                if($request->store_name != '')
                     $user->store_name = $request->store_name;

                $user->save();

                $already=UsersVerification::where('user_id',$user->id)->count();

                if($already==0){

                $user_verification = new UsersVerification;

                $user_verification->user_id      =   $user->id;

                $user_verification->save();  // Create a users verification record

                $email_controller->welcome_email_confirmation($user); }

                if($request->store_name != '' && $request->type == 'merchant') {
                    $user_address = new UserAddress;

                    $user_address->user_id         = $user->id;
                    $user_address->address_line    = $request->address_line;
                    $user_address->address_line2   = $request->address_line2;
                    $user_address->city            = $request->city;
                    $user_address->postal_code     = $request->postal_code;
                    $user_address->state           = $request->state;
                    $user_address->country         = $request->country;
                    $user_address->phone_number    = $request->phone_number;

                    $user_address->save();

                    $user_merchant = new MerchantStore;
                    $user_merchant->store_name      = $request->store_name;
                    $user_merchant->user_id         = $user->id;
                    $user_merchant->save();
                }

                flashMessage('success', 'Added Successfully');

                return redirect('admin/users');
            }
        }
        else
        {
            return redirect('admin/users');
        }
    }
    /**
     * Update User Details
     *
     * @param array $request    Input values
     * @return redirect     to Users View
     */
     public function update(Request $request)
    {        
        if(!$_POST)
        {
            $data['result'] = User::find($request->id);

            if(!empty($data['result'])){

                $data['user_details'] = $data['result']->user_address;

                $data['country']    = Country::where('status','Active')->pluck('long_name','short_name');

                return view('admin.users.edit', $data);
            }
            else{
                abort('404');
            }        
        }
        else if($request->submit)
        {  
            // Edit User Validation Rules
            $rules = array(
                    'full_name'   => 'required',
                    'user_name'   => 'required|unique:users,user_name,'.$request->id,
                    'email'       => 'required|email|unique:users,email,'.$request->id,                    
                    'status'      => 'required',
                    );
            if($request->type !='merchant')
            {
                 $rules['type']       = 'required';
            }
             
            if($request->type == 'merchant'){
                $rules['store_name']    = 'required|unique:users,store_name,'.$request->id;
                $rules['address_line']  = 'required';
                $rules['city']          = 'required';
                $rules['postal_code']   = 'required';
                $rules['state']         = 'required';
                $rules['country']       = 'required';
                $rules['phone_number']  = 'required';
            }

            // Edit User Validation Custom Fields Name
            $niceNames = array(
                        'full_name'  => 'Full name',
                        'user_name'  => 'User name',
                        'store_name' => 'Store name',
                        'email'      => 'Email',
                        'type'       => 'Type',
                        'status'     => 'Status'
                        );
            if($request->type == 'merchant') {
                $niceNames['address_line']  = 'Address Line';
                $niceNames['city']          = 'City';
                $niceNames['postal_code']   = 'Postal Code';
                $niceNames['state']         = 'State';
                $niceNames['country']       = 'Country';
                $niceNames['phone_number']  = 'Phone Number';
            }

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                // dd($validator);
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            { 
                
                $user = User::find($request->id);                
                $user->full_name  = $request->full_name;
                $user->user_name  = $request->user_name;                
                $user->email      = $request->email;
                $user->type       = $request->type;
                $user->status     = $request->status;

                if($request->password != '')
                    $user->password = bcrypt($request->password);

                if($request->store_name != '' && $request->type == 'merchant')
                    $user->store_name = $request->store_name;

                $user->save();

                if($request->store_name != '' && $request->type == 'merchant') {

                    $user_address = UserAddress::firstOrNew(['user_id' => $user->id]);

                    $user_address->user_id         = $user->id;
                    $user_address->address_line    = $request->address_line;
                    $user_address->address_line2   = $request->address_line2;
                    $user_address->city            = $request->city;
                    $user_address->postal_code     = $request->postal_code;
                    $user_address->state           = $request->state;
                    $user_address->country         = $request->country;
                    $user_address->phone_number    = $request->phone_number;

                    $user_address->save();

                    $user_merchant = Merchantstore::firstOrNew(['user_id' => $user->id]);
                    
                    $user_merchant->store_name      = $request->store_name;
                    $user_merchant->user_id         = $user->id;

                    $user_merchant->save();
                }


                flashMessage('success', 'Updated Successfully');

                if($request->password != '') {
                    User::clearUserSession($request->id);
                }
                
                return redirect('admin/users');
            }
        }
        else
        {
            return redirect('admin/users');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function user_update(Request $request)
    {
        $status=User::where('id',$request->id)->first()->status;
        $type = $request->type;
        $type_status=User::where('id',$request->id)->first()->$type;
        if($type_status=="No") {
            $data[$request->type]="Yes";
            User::where('id', $request->id)->update($data);           
        }
        else {
            $data[$request->type]="No";
            User::where('id', $request->id)->update($data);           
        }
        flashMessage('success','Updated Successfully.'); 
        return redirect('admin/users');
    }

    /**
     * Delete User
     *
     * @param array $request    Input values
     * @return redirect     to Users View
     */
    public function delete(Request $request)
    {         
        $check_product  = Product::where('user_id',$request->id)->count();
        $check_order    = Orders::where('buyer_id', $request->id)->count();

        if($check_product) {
            flashMessage('error', 'This user has some products. Please delete that products, before deleting this user.');
            return redirect('admin/users');
        }

        if($check_order) {
            flashMessage('error', "This user has some orders. We can't delete this user");
            return redirect('admin/users');
        }

        try {
            User::clearUserSession($request->id);
            User::find($request->id)->Delete_All_User_Relationship();
            flashMessage('success', 'Deleted Successfully');
        }
        catch (\Exception $e) {
            flashMessage('danger', $e->getMessage());
        }

        return redirect('admin/users');
    }
}