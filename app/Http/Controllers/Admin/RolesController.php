<?php

/**
 * Roles Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Roles
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\RoleDataTable;
use App\Models\Role;
use App\Models\Admin;
use App\Models\Permission;
use App\Http\Start\Helpers;
use Auth;
use Validator;
use DB;

class RolesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Roles
     *
     * @param array $dataTable  Instance of RolesDataTable
     * @return datatable
     */
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('admin.roles.view');
    }

    /**
     * Add a New Role
     *
     * @param array $request  Input values
     * @return redirect     to Roles view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            $data['permissions'] = Permission::get();

            return view('admin.roles.add', $data);
        }
        else if($request->submit)
        {
            // Add Role Validation Rules
            $rules = array(
                    'name'         => 'required|max:50|unique:roles',
                    'display_name' => 'required|max:50',
                    'description'  => 'required|max:255'
                    );

            // Add Role Validation Custom Names
            $niceNames = array(
                        'name'         => 'Name',
                        'display_name' => 'Display Name',
                        'description'  => 'Description'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $permission = [];

                $permission = $request->permission;

                if(in_array(3, $request->permission) || in_array(4, $request->permission) || in_array(5, $request->permission) )
                {
                    $permission[] ='2';
                }

                if(in_array(8, $request->permission) || in_array(9, $request->permission) || in_array(10, $request->permission) )
                {
                    $permission[] ='7';
                }     

                $role = new Role;

                $role->name = $request->name;
                $role->display_name = $request->display_name;
                $role->description = $request->description;

                $role->save();

                if($request->permission)
                    $role->permissions()->sync($permission);

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/roles');
            }
        }
        else
        {
            return redirect('admin/roles');
        }
    }

    /**
     * Update Role Details
     *
     * @param array $request    Input values
     * @return redirect     to Roles View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = Role::find($request->id);

            $data['stored_permissions'] = Role::permission_role($request->id);

            $data['permissions'] = Permission::get();

            if(!empty($data['result']))
                return view('admin.roles.edit', $data);
            else
                abort('404');
            
        }
        else if($request->submit)
        {
            // Edit Role Validation Rules
            $rules = array(
                    'name'         => 'required|max:50|unique:roles,name,'.$request->id,
                    'display_name' => 'required|max:50',
                    'description'  => 'required|max:255',
                    'permission'   => 'required',
                    );

            // Edit Role Validation Custom Fields Name
            $niceNames = array(
                        'name'         => 'Name',
                        'display_name' => 'Display Name',
                        'description'  => 'Description',
                        'permission'   => 'Permission',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $permission = [];
                $permission = $request->permission;
                if(in_array(3, $request->permission) || in_array(4, $request->permission) || in_array(5, $request->permission) )
                {
                    $permission[] ='2';
                }

                if(in_array(8, $request->permission) || in_array(9, $request->permission) || in_array(10, $request->permission) )
                {
                    $permission[] ='7';
                }                

                $role = Role::find($request->id);

                $role->name = $request->name;
                $role->display_name = $request->display_name;
                $role->description = $request->description;

                $role->save();

                $role->permissions()->sync($permission);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/roles');
            }
        }
        else
        {
            return redirect('admin/roles');
        }
    }

    /**
     * Delete Role
     *
     * @param array $request    Input values
     * @return redirect     to Roles View
     */
    public function delete(Request $request)
    {

        $check_role = DB::table('role_user')->where('role_id', $request->id)->count();        

        if($check_role !=0){
            $this->helper->flash_message('danger', 'This role has already used by some admin user account. So, you cannot delete this role.'); // Call flash message function            
        }
        else {

            Role::where('id', $request->id)->delete();

            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }

        return redirect('admin/roles');
    }
}
