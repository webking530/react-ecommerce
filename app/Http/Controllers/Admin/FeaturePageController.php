<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use App\DataTables\HomePageImageDataTable;
use App\Models\Feature;
use Validator;

class FeaturePageController extends Controller
{
    public function __construct()
    {
        $this->helper = new Helpers;
    }

    public function  index(HomePageImageDataTable $dataTable)
    {	
    	return $dataTable->render('admin.slider.homepage_image');
    }

    public function add(Request $request)
    {
    	if(!$_POST)
        {
            return view('admin.slider.homepage_add');
        }
        else
        {
        	$rules = array(                
                'image'   => 'required|mimes:jpg,png,gif,jpeg', 
                'description'=>'required',
                'order'   => 'required', 
            );
        	$attributes = array(
                'image'    => 'Image',
                'order'   => 'Position', 
                'status'  => 'Status',
                'title'  => 'Title For',
                'description' => 'Description'
            );
        	$validator = Validator::make($request->all(), $rules,[], $attributes);
            
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput(); 
            }
            else
            {
                if($request->hasFile('image')) {
                    $image = $request->file('image');

                    $target_dir = '/image/homepage';
                    $extension = $image->getClientOriginalExtension();
                    $file_name = "home_banner_".time().".".$extension;
                    $options = compact('target_dir','file_name');

                    $upload_result = $image_uploader->upload($image,$options);
                    if(!$upload_result['status']) {
                        flashMessage('danger', $upload_result['status_message']);
                        return back();
                    }
                    $file_name = $upload_result['file_name'];
                }

                $feature = new Feature;

                $feature->image = $file_name;
                $feature->order = $request->order; 
                $feature->title = $request->title;
                $feature->description = $request->description;
                $feature->save();

                $this->helper->flash_message('success', 'Added Successfully');
                return redirect(ADMIN_URL.'/feature_slider');
            }
        }

    }

    public function update(Request $request)
    {
        if($request->isMethod("GET")) {
            $data['result'] = Feature::find($request->id);
            return view('admin.slider.homepage_edit',$data);
        }

        $rules = array(                    
            'image'         => 'mimes:jpeg,png,gif,webp',
            'description'   =>'required',
            'order'         => 'required|unique:feature,order,'.$request->id,
        );
        $attributes = array(
            'image'    => 'Image',
            'description' => 'Description',
            'order'   => 'Position', 
        );
        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput(); 
        }

        $feature = Feature::findOrFail($request->id);

        if (UPLOAD_DRIVER == 'cloudinary') {
            $handler = 'App\Services\CloudinaryImageHandler';
        }
        else {
            $handler = 'App\Services\LocalImageHandler';
        }

        $image_uploader = resolve($handler);

        if($request->hasFile('image')) {
            $image = $request->file('image');

            $target_dir = '/image/homepage';
            $extension = $image->getClientOriginalExtension();
            $file_name = "home_banner_".time().".".$extension;
            $options = compact('target_dir','file_name');

            $upload_result = $image_uploader->upload($image,$options);
            if(!$upload_result['status']) {
                flashMessage('danger', $upload_result['status_message']);
                return back();
            }
            $feature->image = $upload_result['file_name'];
        }

        $feature->order = $request->order; 
        $feature->description = $request->description;
        $feature->save();
        flashMessage('success', 'Updated Successfully');
        return redirect(ADMIN_URL.'/feature_slider');
    }

    public function delete(Request $request)
    {
        $feature = Feature::find($request->id);
        if($feature != ''){
            $feature->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }

        return redirect(ADMIN_URL.'/feature_slider');
    }


}
