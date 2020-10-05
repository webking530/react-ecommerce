<?php

/**
 * Language Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Language
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\LanguageDataTable;
use App\Models\Language;
use App\Http\Start\Helpers;
use Validator;

class LanguageController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Language
     *
     * @param array $dataTable  Instance of LanguageDataTable
     * @return datatable
     */
    public function index(LanguageDataTable $dataTable)
    {        
        return $dataTable->render('admin.language.view');
    }

    /**
     * Add a New Language
     *
     * @param array $request  Input values
     * @return redirect     to Language view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.language.add');
        }
        else if($request->submit)
        {
            // Add Language Validation Rules
            $rules = array(
                    'name'   => 'required|max:50|unique:language',
                    'value'  => 'required|max:5|unique:language',
                    'status' => 'required'
                    );

            // Add Language Validation Custom Names
            $niceNames = array(
                        'name'    => 'Name',
                        'value'   => 'Value',
                        'status'  => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $language = new Language;

                $language->name   = $request->name;
                $language->value  = $request->value;
                $language->status = $request->status;
                $language->default_language = '0';

                $language->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/language');
            }
        }
        else
        {
            return redirect('admin/language');
        }
    }

    /**
     * Update Language Details
     *
     * @param array $request    Input values
     * @return redirect     to Language View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = Language::find($request->id);

            if($data['result'] != null)
            {
                return view('admin.language.edit', $data);                
            }
            else
            {
                abort('404');
            }
            
        }
        else if($request->submit)
        {
            // Edit Language Validation Rules
            $rules = array(
                    'name'   => 'required|max:50|unique:language,name,'.$request->id,
                    'value'  => 'required|max:5|unique:language,value,'.$request->id,
                    'status' => 'required'
                    );

            // Edit Language Validation Custom Fields Name
            $niceNames = array(
                        'name'    => 'Name',
                        'value'   => 'Value',
                        'status'  => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {

                $default_language = Language::find($request->id)->default_language;

                $language = Language::find($request->id);

			    $language->name   = $request->name;
                $language->value  = $request->value;
                $language->status = $request->status;

                if($default_language != 1)
                {                                    
                    $language->save();

                    $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                }
                else{
                    
                    if($request->status =='Inactive')
                    {
                        $this->helper->flash_message('error', 'This language is Default Language. So, can\'t change the Status.'); // Call flash message function
                    }
                    else{

                        $language->save();

                        $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                    }
                    
                }            

                return redirect('admin/language');
            }
        }
        else
        {
            return redirect('admin/language');
        }
    }

    /**
     * Delete Language
     *
     * @param array $request    Input values
     * @return redirect     to Language View
     */
    public function delete(Request $request)
    {
        $default_language = Language::find($request->id)->default_language;

        if($default_language == 1)
            {
                $this->helper->flash_message('error', 'This language is Default Language. So, change the Default Language.'); // Call flash message function
            }
        else
            {
                Language::find($request->id)->delete();

                $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
            }

        return redirect('admin/language');
    }
}
