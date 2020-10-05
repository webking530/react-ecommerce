<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoriesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use File;
use Image;

class CategoryController extends Controller
{
	public function __construct()
	{
		$this->helper = resolve('App\Http\Start\Helpers');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(CategoriesDataTable $dataTable)
	{
		return $dataTable->render('admin.categories.view');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function add(Request $request)
	{
		if($request->isMethod("GET")) {
			$data['categories'] = Category::where('parent_id', '=', 0)->where('status', 'Active')->get();
			$data['allCategories'] = Category::where('status', 'Active')->pluck('title', 'id')->all();
			return view('admin.categories.add', $data);
		}

		$this->validate($request, [
			'title' => 'required',
			'category_icon' => 'required | mimes:jpeg,jpg,png',
			'category_image' => 'required | mimes:jpeg,jpg,png',
		]);

		$input = $request->all();

		if (UPLOAD_DRIVER == 'cloudinary') {
			$handler = 'App\Services\CloudinaryImageHandler';
		}
		else {
			$handler = 'App\Services\LocalImageHandler';
		}

		$image_uploader = resolve($handler);

		$input['parent_id'] = empty($input['parent_id']) ? 0 : $input['parent_id'];

		$category = Category::create($input);

		$update['status'] = $input['status'];
		$update['featured'] = $input['featured'];

		if($request->hasFile('category_icon')) {
			$category_icon = $request->file('category_icon');
			
			$target_dir = '/image/category/'.$category->id;
            $compress_size = array(
                ['width' => 30, 'height' => 30],
            );
            $file_name = "icon-".camel_case($category_icon->getClientOriginalName());

            $options = compact('target_dir','compress_size','file_name');

			$icon_name = $category->getOriginal('icon_name');
			if($icon_name != '') {
				$image_uploader->delete($category->icon_name);
			}

			$upload_result = $image_uploader->upload($category_icon,$options);
			if(!$upload_result['status']) {
				flashMessage('danger', $upload_result['status_message']);
				return back();
			}

			$update['icon_name'] = $upload_result["file_name"];
		}

		if($request->hasFile('category_image')) {
			$category_image = $request->file('category_image');
			
			$target_dir = '/image/category/'.$category->id;
            $compress_size = array(
                ['width' => 104, 'height' => 104],
            );
            $file_name = "category_".time().".".$category_image->getClientOriginalExtension();

            $options = compact('target_dir','compress_size','file_name');

			$image_name = $category->getOriginal('image_name');
			if($image_name != '') {
				$image_uploader->delete($category->image_name);
			}
			$upload_result = $image_uploader->upload($category_image,$options);
			if(!$upload_result['status']) {
				flashMessage('danger', $upload_result['status_message']);
				return back();
			}
			
			$update['image_name'] = $upload_result["file_name"];
		}

		Category::where('id', $category->id)->update($update);

		flashMessage('success', 'New Category added successfully.');
		return redirect('admin/categories');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		if ($request->isMethod("GET")) {
			$data['categories'] = Category::where('parent_id', '=', 0)->where('status', 'Active')->get();
			$data['allCategories'] = Category::where('status', 'Active')->where('parent_id', '!=', $request->id)->where('id', '!=', $request->id)->pluck('title', 'id')->all();
			$data['result'] = Category::findOrFail($request->id);
			return view('admin.categories.edit', $data);
		}

		$this->validate($request, [
			'title' 		=> 'required',
			'category_icon' => 'mimes:jpeg,jpg,png',
			'category_image'=> 'mimes:jpeg,jpg,png',
		]);

		$input = $request->all();
		$parent_id = empty($input['parent_id']) ? 0 : $input['parent_id'];

		$update['title'] = $request->title;
		$update['parent_id'] = $parent_id;
		$update['status'] = $input['status'];
		$update['featured'] = $input['featured'];
		$active_count = Category::where('status', 'Active')->whereNotIn('id', [$request->id])->count();
		if ($active_count < 1) {			
			flashMessage('error', 'Category cannot be updated.Atleast one category must be active.');
			return redirect('admin/categories');
		}
		$category = Category::findOrFail($request->id);

		if (UPLOAD_DRIVER == 'cloudinary') {
			$handler = 'App\Services\CloudinaryImageHandler';
		}
		else {
			$handler = 'App\Services\LocalImageHandler';
		}

		$image_uploader = resolve($handler);

		if($request->hasFile('category_icon')) {
			$category_icon = $request->file('category_icon');
			
			$target_dir = '/image/category/'.$category->id;
            $compress_size = array(
                ['width' => 30, 'height' => 30],
            );
            $file_name = "icon-".camel_case($category_icon->getClientOriginalName());

            $options = compact('target_dir','compress_size','file_name');

			$icon_name = $category->getOriginal('icon_name');
			if($icon_name != '') {
				$image_uploader->delete($category->icon_name);
			}

			$upload_result = $image_uploader->upload($category_icon,$options);
			if(!$upload_result['status']) {
				flashMessage('danger', $upload_result['status_message']);
				return back();
			}

			$update['icon_name'] = $upload_result["file_name"];
		}

		if($request->hasFile('category_image')) {
			$category_image = $request->file('category_image');
			
			$target_dir = '/image/category/'.$category->id;
            $compress_size = array(
                ['width' => 104, 'height' => 104],
            );
            $file_name = "category_".time().".".$category_image->getClientOriginalExtension();

            $options = compact('target_dir','compress_size','file_name');

			$image_name = $category->getOriginal('image_name');
			if($image_name != '') {
				$image_uploader->delete($category->image_name);
			}
			$upload_result = $image_uploader->upload($category_image,$options);
			if(!$upload_result['status']) {
				flashMessage('danger', $upload_result['status_message']);
				return back();
			}
			
			$update['image_name'] = $upload_result["file_name"];
		}

		Category::where('id', $request->id)->update($update);
		flashMessage('success', 'Category updated successfully.');

		return redirect('admin/categories');
	}

	public function delete(Request $request)
	{
		$check_product = Product::where('category_id', $request->id)->count();
		if ($check_product != 0 ) {
			flashMessage('error', 'This category has some products. Please delete that products, before deleting this category.');
			return redirect('admin/categories');
		}

		$child_category_id = Category::where('parent_id', $request->id)->select('id')->get()->toArray();
		$check_child_product = 0;
		foreach ($child_category_id as $value) {
			$check_child_product += Product::where('category_id', $value['id'])->count();
		}

		if($check_child_product != 0) {
			flashMessage('error', 'This category has some products. Please delete that products, before deleting this category.');
			return redirect('admin/categories');
		}

		$category_count = Category::all()->count();

		if ($category_count <= 1) {
			flashMessage('error', 'Category cannot be deleted. Because at least you have only one category.');
			return redirect('admin/categories');
		}

		$check_parent_category = Category::where('parent_id', $request->id)->get()->count();
		if($check_parent_category > 0) {
			flashMessage('error', 'This category have sub category. Please delete that sub category, before deleting this category.');
			return redirect('admin/categories');
		}

		$image_name = Category::where('id', $request->id)->first()->image_name;
		$icon_name = Category::where('id', $request->id)->first()->icon_name;
		$filename_old = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/category/' . $request->id . '/' . $image_name;
		
		$this->helper->delete_cloud_upload($image_name);
		$this->helper->delete_cloud_upload($icon_name);
		if (file_exists($filename_old)) {
			File::delete($filename_old);
		}

		Category::where('parent_id', $request->id)->delete();
		Category::where('id', $request->id)->delete();
		flashMessage('success', 'Category deleted Successfully');
		return redirect('admin/categories');
	}

	public function category_update(Request $request)
	{
		$active_count = Category::where('status', 'Active')->whereNotIn('id', [$request->id])->count();

		$status = Category::where('id', $request->id)->first()->status;
		$type = $request->type;
		$type_status = Category::where('id', $request->id)->first()->$type;
		if ($active_count < 1) {
			flashMessage('error', 'Category cannot be updated.Atleast one category must be active.');
			return redirect('admin/categories');
		}

		if ($request['type'] == "status") {
			$data[$type] = ($type_status == "Inactive") ? "Active" : "Inactive";
		}
		else {
			if ($status != "Active") {
				flashMessage('error', 'Inactive Status category');
				return redirect('admin/categories');
			}
			$data[$type] = ($type_status == "No") ? "Yes" : "No";
		}

		Category::where('id', $request->id)->update($data);
		flashMessage('success', 'Updated Successfully.');

		return redirect('admin/categories');
	}
}