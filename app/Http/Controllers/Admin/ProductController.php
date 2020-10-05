<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\ProductsDataTable;
use App\Models\Product;
use App\Http\Start\Helpers;
use App\Models\Category;
use App\Models\User;
use App\Models\ReturnPolicy;
use App\Models\Country;
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
use App\Models\Activity;
use App\Models\OrdersBillingAddress;
use App\Models\OrdersShippingAddress;
use App\Models\MerchantStore;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\Orders;
use App\Models\Payouts;
use App\Models\OrdersReturn;
use App\Models\OrdersCancel;
use App\Http\Helper\PaymentHelper;
use App\Models\Notifications;
use Validator;
use Session;
use File;

class ProductController extends Controller
{

    public function __construct(PaymentHelper $payment)
    {   
        $this->helper = new Helpers;
        $this->payment_helper = $payment;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductsDatatable $dataTable)
    {
        return $dataTable->render('admin.products.view');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $categories = Category::parent_categories();
        $data['return_policy']  = ReturnPolicy::orderBy('days')->get();
        $data['exchange_policy']  = ReturnPolicy::orderBy('days')->get();
        $data['return_policy_value'] = $data['return_policy']->first()->id;
        $data['country']  = Country::where('status','Active')->pluck('long_name', 'long_name');
        $data['tmp_product_id'] = $data['product_id'] = rand();

        $data['users_list']= User::whereStatus('Active')->whereType('merchant')->pluck('full_name','id');
        if(Session::get('product_currency'))
        {
            $data['minimum_amount'] = $this->payment_helper->currency_convert('USD', Session::get('product_currency'), 1); 
            $data['product_currency'] = Session::get('product_currency'); 
            $data['product_symbol'] = Currency::original_symbol(Session::get('product_currency')); 
        }
        else
        {
            $data['minimum_amount'] = $this->payment_helper->currency_convert('USD', Session::get('currency'), 1);
            $data['product_currency'] = Session::get('currency'); 
            $data['product_symbol'] = Currency::original_symbol(Session::get('currency'));      
        }
        


        $data['update_type'] = 'add_product';
        $data['result']=array();
        foreach($categories as $row) {
            $data['id'] = $row->id;
            $data['name'] = $row->title;
            $data['list'] = $this->get_child_categories($row->id);
            $final_data[] = $data;
        }

        $data['categories'] = @$final_data;

        return view('admin.products.add',$data);        
    }

    public function get_product(Request $request) 
    {
        $products = Product::with('products_prices_details', 'products_images', 'product_photos', 'products_shipping', 'product_option.product_option_images')->find($request->id);
        return $products;
    }

    public function edit_product(Request $request) {
        if(!$_POST){

            $data['result'] = Product::where('id',$request->id)->first();

            if(!empty($data['result'])){
                $categories = Category::parent_categories();
                $data['return_policy']  = ReturnPolicy::orderBy('days')->get();
                $data['exchange_policy']  = ReturnPolicy::orderBy('days')->get();
                $data['return_policy_value'] = $data['return_policy']->first()->id;
                $data['country']  = Country::where('status','Active')->pluck('long_name', 'long_name');
                $data['product_id'] = $request->id;

                $data['tmp_product_id'] = rand();
                
                $currency_code = ProductPrice::where('product_id',$request->id)->first()->currency_code;
                $data['minimum_amount'] = $this->payment_helper->currency_convert('USD', $currency_code, 1); 
                $data['product_currency'] = $currency_code; 
                $data['product_symbol'] = Currency::original_symbol($currency_code);       

                $data['update_type'] = 'edit_product';
                foreach($categories as $row) {
                    $data['id'] = $row->id;
                    $data['name'] = $row->title;
                    $data['list'] = $this->get_child_categories($row->id);
                    $final_data[] = $data;
                }

                $data['categories'] = @$final_data;   

                return view('admin.products.edit',$data); 
            }
            else{
                abort('404');
            }
               
        }
        else {

            $data['result'] = Product::where('id',$request->product_id)->first();
            if(empty($data['result'])){ 
                return redirect('admin/products');
            }

            $products['user_id'] = $request->user_id;
            $products['title'] = $request->title;
            $products['description'] = $request->description;
            $products['category_id'] = $request->category_id;
            $products['category_path'] = $request->category_path;
            $products['total_quantity'] = $request->total_quantity;
            $products['return_policy'] = $request->return_policy;
            if($request->use_exchange)
                $products['exchange_policy'] = $request->return_policy;
            else
            $products['exchange_policy'] = $request->exchange_policy;        

            $products['policy_description'] = $request->return_exchange_policy_description;
            $products['sold'] = ($request->sold!="") ? $request->sold : 0;
            $products['views_count'] = 0;
            $products['status'] = $request->status;
            $products['sold_out']=$request->sold_out;
            $products['cash_on_delivery']=$request->cash_on_delivery;
            $products['cash_on_store']=$request->cash_on_store;
                Product::where("id",$request->product_id)->update($products);

                $product_id = $request->product_id;

           if($request->submit == 'images'){

            if ($request->update_type == "edit_product") {
            if($request->delete_video_update !=''){
                //dd("ki");
                Product::where('id', $request->product_id)->update(['video_mp4' => '', 'video_webm' => '']);
                if($request->add_product_video == ''){
                ProductImagesTemp::where('product_id', $request->product_id)->where('option','video_mp4')->delete();
                ProductImagesTemp::where('product_id', $request->product_id)->where('option','video_webm')->delete();
                ProductImagesTemp::where('product_id', $request->product_id)->where('option','video_thumb')->delete();
            }

            }
            //dd("ki11");
            $check_product = Product::where('id', $request->product_id)->first();

            if (empty($check_product)) {

                return redirect('merchant/all_products');

            }

        }

        if ($request->update_type == "edit_product") {
            if($request->delete_product_id !=''){
                $product_img = explode(',', $request->delete_product_id);
                //dd($product_img);
                foreach($product_img  as $k =>$v){
                    if($v != ''){

                 $photos = ProductImages::where('id',$v)->where('product_id',$request->product_id);
                
                if ($photos != NULL) {
                    $photos->delete();
                }

                    }

                }
             
            }
        }

        

            $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id;
            $oldfilename = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->tmp_product_id;
            if (!file_exists($filename)) {
                try {
                    mkdir($filename, 0777, true);
                }
                catch(\Exception $e) {
                    flashMessage('danger',$e->getMessage());
                    return redirect('admin/products');
                }
            }
            $product_image_temps = ProductImagesTemp::where('product_id', $request->tmp_product_id)->where('option', NULL)->get();
            foreach ($product_image_temps as $product_image_temp) {
                $update_image['product_id'] = $product_id;
                $update_image['image_name'] = $product_image_temp->image_name;
                ProductImages::create($update_image);

                $old = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->tmp_product_id . '/' . $product_image_temp->image_name;
                $new = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_image_temp->image_name;
                $old_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->tmp_product_id.'/';
                $new_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/';


                if (UPLOAD_DRIVER != 'cloudinary') {

                    File::move($old, $new); // keep the same folder to just rename

                    $ext = substr( $old, strrpos( $old, "."));

                    $old_compress = basename($old, $ext). "_compress" . $ext;
                    $old_home_full = basename($old, $ext). "_home_full" . $ext;
                    $old_home_half = basename($old, $ext). "_home_half" . $ext;
                    $old_popular = basename($old, $ext). "_popular" . $ext;
                    $old_header = basename($old, $ext). "_header" . $ext;

                    $ext = substr( $new, strrpos( $new, "."));
                    $new_compress = basename($new, $ext). "_compress" . $ext;
                    $new_home_full = basename($new, $ext). "_home_full" . $ext;
                    $new_home_half = basename($new, $ext). "_home_half" . $ext;
                    $new_popular = basename($new, $ext). "_popular" . $ext;
                    $new_header = basename($new, $ext). "_header" . $ext;

                    File::move($old_dir.$old_compress,$new_dir.$new_compress); // keep the same folder to just rename 
                    File::move($old_dir.$old_home_full,$new_dir.$new_home_full); // keep the same folder to just rename 
                    File::move($old_dir.$old_home_half,$new_dir.$new_home_half); // keep the same folder to just rename 
                    File::move($old_dir.$old_popular,$new_dir.$new_popular); // keep the same folder to just rename 
                    File::move($old_dir.$old_header,$new_dir.$new_header); // keep the same folder to just rename 

                }
            }

            

            $product_video_mp4_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_mp4')->first();
            $product_video_webm_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_webm')->first();
            $product_video_thumb_temp = ProductImagesTemp::where('product_id', $request->product_id)->where('option', 'video_thumb')->first();
            if ($product_video_mp4_temp) {
                Product::where('id', $product_id)->update(['video_mp4' => $product_video_mp4_temp->image_name, 'video_webm' => $product_video_webm_temp->image_name, 'video_thumb' => $product_video_thumb_temp->image_name]);
                $old = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_mp4_temp->image_name;
                $new = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_mp4_temp->image_name;
                $old_webm = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_webm_temp->image_name;
                $new_webm = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_webm_temp->image_name;
                $old_thumb = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $request->product_id . '/' . $product_video_thumb_temp->image_name;
                $new_thumb = dirname($_SERVER['SCRIPT_FILENAME']) . '/image/products/' . $product_id . '/' . $product_video_thumb_temp->image_name;
                if (UPLOAD_DRIVER != 'cloudinary') {
                    File::move($old, $new); // keep the same folder to just rename
                    File::move($old_webm, $new_webm); // keep the same folder to just rename
                    File::move($old_thumb, $new_thumb); // keep the same folder to just rename
                }
            }

        
           }

            if($request->submit == 'price_details')
            {
                //for product price table
                if($request->check_sale)
                {
                    $product_prices['discount']=($request->discount != "" ? $request->discount : NULL);
                    $product_prices['retail_price']=($request->retail_price != "" ? $request->retail_price : NULL);
                }
                else
                {
                    $product_prices['discount']=NULL;
                    $product_prices['retail_price']=NULL;
                }
                $product_prices['product_id']=$product_id;
                $product_prices['price']=round($request->price,2);
                $product_prices['sku']=$request->sku_stock;
                $product_prices['length']=($request->length != "" ? round($request->length,2) : NULL);
                $product_prices['height']=($request->height != "" ? round($request->height,2) : NULL);
                $product_prices['width']=($request->width != "" ? round($request->width,2) : NULL);
                $product_prices['weight']=($request->weight != "" ? round($request->weight,2) : NULL);
                $product_prices['currency_code'] = $request->currency_code;
                $product_price = ProductPrice::where('product_id',$product_id)->update($product_prices);

                                //for product option table
                if($request->product_option)
                {
                    
                    $product_op=ProductOption::whereNotIn('option_name',$request->product_option)->where('product_id',$product_id);
                    if($product_op->count() >0)
                    {
                        foreach ($product_op->get() as $value) {
                            ProductOptionImages::where('product_id',$product_id)->where('product_option_id',$value->id)->delete();
                            ProductImagesTemp::where('product_id',$product_id)->where('option',$value->id)->delete();
                            ProductOption::where('id',$value->id)->delete();
                            Cart::where('option_id',$value->id)->delete();
                        }
                    }
                    $product_quantity['total_quantity']=0;
                    for($i=0;$i<count($request->product_option);$i++)
                    {
                        $product_options['product_id']=$product_id;
                        $product_options['sku']=$request->product_option_sku[$i];
                        $product_options['option_name']=$request->product_option[$i];
                        $product_options['total_quantity']=($request->product_option_qty[$i] != "" ? $request->product_option_qty[$i] : NULL);
                        $product_quantity['total_quantity']+=$request->product_option_qty[$i];
                        $product_options['price']=($request->product_option_price[$i] != "" ? $request->product_option_price[$i] : $request->price);
                        $product_options['sold']=($request->product_option_sold[$i] != "" ? $request->product_option_sold[$i] : 0);
                        $product_options['currency_code'] = $request->currency_code;


                        if(isset($request->product_option_check_sale[$i]))
                        {
                            $product_options['retail_price']=($request->product_option_retail_price[$i] != "" ? $request->product_option_retail_price[$i] : NULL);
                            $product_options['discount']=($request->product_option_discount[$i] != "" ? $request->product_option_discount[$i] : NULL);
                        }
                        else
                        {
                            $product_options['retail_price']="0";
                            $product_options['discount']="0";
                        }
                        
                        $product_options['length']=($request->product_option_length[$i] != "" ? $request->product_option_length[$i] : NULL);
                        $product_options['width']=($request->product_option_width[$i] != "" ? $request->product_option_width[$i] : NULL);
                        $product_options['height']=($request->product_option_height[$i] != "" ? $request->product_option_height[$i] : NULL);
                        $product_options['weight']=($request->product_option_weight[$i] != "" ? $request->product_option_weight[$i] : NULL);
                        if($request->product_option_soldout)
                        {
                           $option_soldout=$request->product_option_soldout;
                        }
                        else
                        {
                            $option_soldout=array();
                        }

                        if(in_array($i,$option_soldout))
                        {
                            $product_options['sold_out']="Yes";    
                        }
                        else
                        {
                            $product_options['sold_out']="No";
                        }
                        $check_option=ProductOption::where('product_id',$product_id)->where('option_name',$request->product_option[$i]);

                        if($check_option->count())
                        {
                            $product_option = ProductOption::where('product_id',$product_id)->where('option_name',$request->product_option[$i])->update($product_options);       
                        }
                        else
                        {
                            
                            $product_option = ProductOption::create($product_options);           
                        
                            $option_filename=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/options/'.$product_option->id;
                            $option_oldfilename=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/options/'.$request->product_option_id[$i];
                            if(!file_exists($option_filename))
                            {
                                try {
                                    mkdir($option_filename, 0777, true);
                                }
                                catch(\Exception $e) {
                                    flashMessage('danger',$e->getMessage());
                                    return redirect('admin/products');
                                }
                            }
                            $product_option_image_temps=ProductImagesTemp::where('product_id',$product_id)->where('option',$request->product_option_id[$i])->get();
                            foreach ($product_option_image_temps as $product_option_image_temp) 
                            {
                                $update_option_image['product_id'] = $product_id;
                                $update_option_image['product_option_id'] = $product_option->id;
                                $update_option_image['image_name'] = $product_option_image_temp->image_name;
                                ProductOptionImages::create($update_option_image);

                                $old=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/options/'.$request->product_option_id[$i].'/'.$product_option_image_temp->image_name;
                                $new=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/options/'.$product_option->id.'/'.$product_option_image_temp->image_name;
                                File::move($old,$new); // keep the same folder to just rename 
                            }
                        }
                    }
                    Product::where('id',$product_id)->update($product_quantity);

                    if($request->update_type!="edit_product")
                    {
                        File::deleteDirectory($option_oldfilename);
                    }
                }
                else
                {
                    $product_op=ProductOption::where('product_id',$product_id);
                    if($product_op->count() >0)
                    {
                        foreach ($product_op->get() as $value) {
                            ProductOptionImages::where('product_id',$product_id)->where('product_option_id',$value->id)->delete();
                            ProductImagesTemp::where('product_id',$product_id)->where('option',$value->id)->delete();
                            ProductOption::where('id',$value->id)->delete();
                            Cart::where('option_id',$value->id)->delete();
                        }
                    }
                }

            }
            else if($request->submit == 'shipping')
            {
                //for product shipping table
                $product_shippings['shipping_type']=$request->shipping_type;
                $product_shippings['ships_from']=$request->ships_from;
                $product_shippings['manufacture_country']=$request->manufacture_country;
                $product_shippings['product_id']=$product_id;    
                $product_sh = ProductShipping::whereNotIn('ships_to',$request->ships_to)->where('product_id',$product_id);
                if($product_sh->count() >0) {
                    foreach ($product_sh->get() as $value) {
                        ProductShipping::where('id',$value->id)->delete();
                    }
                }
                for($i=0;$i<count($request->ships_to);$i++) {
                    $product_shippings['ships_to'] = $request->ships_to[$i];
                    $product_shippings['start_window'] = $request->expected_delivery_day_1[$i];
                    $product_shippings['end_window'] = $request->expected_delivery_day_2[$i];
                    if($request->shipping_type != "Free Shipping") {
                        $product_shippings['charge'] = round($request->custom_charge_domestic[$i],2);
                        $product_shippings['incremental_fee'] = ($request->custom_incremental_domestic[$i] != "" ? round($request->custom_incremental_domestic[$i],2) : NULL);
                    }
                    $check_shipping = ProductShipping::where('product_id',$product_id)->where('ships_to',$request->ships_to[$i]);
                    if($check_shipping->count()) {
                        $product_shipping = $check_shipping->update($product_shippings);
                    }
                    else {
                        $product_shipping = ProductShipping::create($product_shippings);           
                    }
                }
            }
            else if($request->submit == 'inventory')
            {

            }

            else if($request->submit == 'cancel')
            {
                return redirect('admin/products');
            }

            $this->helper->flash_message('success', 'Product Updated Successfully'); // Call flash message function
            return redirect('admin/products');
        }
    }
    public function get_child_categories($parent_id) {

        $child_categories = Category::child_categories($parent_id);

        foreach($child_categories as $row) {
            $data['id'] = $row->id;
            $data['name'] = $row->title;
            $data['list'] = $this->get_child_categories($row->id);
            $final_data[] = $data;
        }
        return @$final_data;
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
            $data['categories'] = Product::where('parent_id', '=', 0)->where('status', 'Active')->get();
            $data['allCategories'] = Product::pluck('title','id')->all();
            $data['result'] = @Product::where('id', $request->id)->first();
            return view('admin.categories.edit', $data);
        }
        else
        {
            $this->validate($request, [
                'title' => 'required',
            ]);
            $input = $request->all();
            $parent_id = empty($input['parent_id']) ? 0 : $input['parent_id'];
        
            Product::where('id', $request->id)->update(['title'=>$request->title, 'parent_id'=>$parent_id, 'status'=>$request->status]);
            return back()->with('success', 'Category updated successfully.');
        }
    }

    public function delete(Request $request)
    {
        
        $active_count=Product::where('id', $request->id)->where('status',"Active")->count();
        if($active_count=="0")
        {

            $orders_details=OrdersDetails::where('product_id', $request->id)->get();
            foreach ($orders_details as $orders) {
                
                Payouts::where('order_detail_id',$orders->id)->delete();
                OrdersReturn::where('order_id',$orders->id)->delete();
                OrdersCancel::where('order_id',$orders->id)->delete();
                
            }
            OrdersDetails::where('product_id', $request->id)->delete();
            Cart::where('product_id', $request->id)->delete();
            Wishlists::where('product_id', $request->id)->delete();
            ProductClick::where('product_id', $request->id)->delete();
            ProductLikes::where('product_id', $request->id)->delete();
            ProductShipping::where('product_id', $request->id)->delete();
            ProductPrice::where('product_id', $request->id)->delete();
            ProductOptionImages::where('product_id', $request->id)->delete();
            ProductOption::where('product_id', $request->id)->delete();
            ProductImages::where('product_id', $request->id)->delete();
            Product::where('id', $request->id)->delete();
            $this->helper->flash_message('success', 'Product deleted Successfully'); // Call flash message function
            return redirect('admin/products');

        }
        else
        {
            $this->helper->flash_message('error', trans('messages.products.delete_error')); 
            return redirect('admin/products');
        }

       
    }

    public function set_approval(Request $request)
    {
        $product=Product::where('id',$request->id)->first();
        $user_status = User::where('id',$product->user_id)->first()->status;
        if($user_status != 'Active')
        {
            $this->helper->flash_message('error',"Can't Update Inactive User's Product."); 
        }
        if($product->admin_status=="Waiting" && $user_status == 'Active')
        {
            $data['admin_status']="Approved";
            Product::where('id', $request->id)->update($data);           
            $this->helper->flash_message('success','Product Approved Successfully.'); 
        }        
        return redirect('admin/products');
    }


    public function set_update(Request $request)
    {
        $admin_status=Product::where('id',$request->id)->first()->admin_status;
        $type = $request->type;
        $status=Product::where('id',$request->id)->first()->$type;
        $product = Product::find($request->id);
        if($product->status == 'Inactive')
        {
            $this->helper->flash_message('error',"Can't Update Inactive Product."); 
            return redirect('admin/products');
        }
        if($product->users->status == "Inactive")
        {
            $this->helper->flash_message('error',"Can't Update Inactive User's Product."); 
            return redirect('admin/products');
        }
        if($admin_status!="Approved")
        {
            $this->helper->flash_message('error','Waiting for Admin Approval.'); 
            return redirect('admin/products');
        }
        else
        {
            
            if($status=="No")
            {
                $data[$request->type]="Yes";
                Product::where('id', $request->id)->update($data);

                if($request->type=="is_featured")
                {
                    $products=Product::where('id', $request->id)->first();
                    //store activity data in notification table         
                    $activity_data = new Notifications;
                    $activity_data->product_id =   $request->id;
                    $activity_data->notify_id =   $products->user_id;
                    $activity_data->notification_type  = "featured";
                    $activity_data->notification_message  = SITE_NAME." featured your things";
                    $activity_data->save();
                }           
            }
            else
            {
                $data[$request->type]="No";
                Product::where('id', $request->id)->update($data);           
            }
            $this->helper->flash_message('success','Updated Successfully.'); 
            return redirect('admin/products');
        }
    }
    /**
     * Update  Product Status 
     *
     * @param int $id    product id
     * @return redirect to admin product manage page
     */ 
    public function status_update(Request $request)
    {
        $status=Product::where('id',$request->id)->first()->status;
        if($status=="Inactive")
        {
            $data['status']="Active";            
        }
        else
        {
            $data['status']="Inactive"; 
        }

        Product::where('id', $request->id)->update($data);           
        $this->helper->flash_message('success','Product Status Updated Successfully.'); 
        return redirect('admin/products');
    }

    public function add_product_option_photo(Request $request)
    {
        if(isset($_FILES["upload-option-file"]["name"]))
        {   
            $rows = array();
            $err = array();

            $files = $request->file('upload-option-file');

            if($request->hasFile('upload-option-file'))
            {
                foreach ($files as $file) {

                    $ext=$file->getClientOriginalExtension();
                    
                    if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')   
                    {   
                        if($request->option_db=='0')
                        {
                            $option_id=$request->option;
                        }
                        else
                        {
                            $option_id=$request->option_db;
                        }

                        $file->storeAs('image/products/'.$request->id.'/options/'.$option_id,$file->getClientOriginalName(),'mydisk');

                        
                        $temp_photos['product_id'] = $request->id;
                        $temp_photos['image_name'] = $file->getClientOriginalName();
                        $temp_photos['created_at'] = date('Y-m-d H:i:s');
                        $temp_photos['updated_at'] =  date('Y-m-d H:i:s');                       

                        $check_option_exists=ProductOption::where('product_id',$request->id)->where('id',$option_id);
                        if($check_option_exists->count()>0)
                        {
                            unset($temp_photos['option']);
                            $temp_photos['product_option_id'] = $option_id;
                            ProductOptionImages::create($temp_photos);                            
                        }
                        else
                        {
                            $temp_photos['option'] = $option_id;
                            ProductImagesTemp::create($temp_photos);
                        }
                    }
                    else
                    {
                        $err = array('error_title' => ' Photo Error', 'error_description' => 'This is not an image file');
                    }
                }
            }
            

            
            if($check_option_exists->count()>0)
            {
                $result = ProductOptionImages::where('product_id',$request->id)->where('product_option_id',$option_id)->get();
            }
            else
            {
                $result = ProductImagesTemp::select('*','option as product_option_id')->where('product_id',$request->id)->where('option',$option_id)->get();
            }
            $rows['succresult'] = $result;
            $rows['steps_count'] = $result->count();
            $rows['error'] = $err;
            return json_encode($rows);
            
        }
    }

    public function delete_product_photo(Request $request)
    {
        if($request->type=="edit_product")
            {
                $check_product=Product::where('id',$request->productid)->first();

                if(empty($check_product)){

                    $err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

                    $rows['error'] = $err;

                    return json_encode(['success'=>'false','error'=>$err]);
                }
                
            }
        if($request->option=="false")
        {
            if($request->type=="edit_product")
            {
               $photos_count = ProductImages::where('id',$request->photo_id)->where('product_id',$request->productid)->count();
                 if($photos_count){
                 $photos = ProductImages::find($request->photo_id);
                 $product_id = $photos->product_id;
                if ($photos != NULL) {
                 $photos->delete();
                }
                 $photos = ProductImages::where('product_id', $product_id)->count();
                 $photos_count =  $photos - 1;

                return json_encode(['success' => 'true', 'steps_count' => $photos_count,'delete_img'=> $request->photo_id]);
            }else{
                $photos = ProductImagesTemp::find($request->photo_id);
                $product_id = $photos->product_id;
                if ($photos != NULL) {
                    $photos->delete();
                }
                $photos = ProductImagesTemp::where('product_id', $product_id)->where('option', NULL);
            }

            }
            else
            {

                $photos = ProductImagesTemp::find($request->photo_id);
                $product_id=$photos->product_id;
                if($photos != NULL){
                    $photos->delete();
                }
                $photos = ProductImagesTemp::where('product_id',$product_id)->where('option',NULL);
            }
        }
        elseif($request->option == 'video')
        {
            $product_id = $request->productid;
            if($request->type=="edit_product")
            {
                //Product::where('id',$product_id)->update(['video_mp4' => '','video_webm' => '','video_thumb' => '']);
            }
            ProductImagesTemp::where('product_id',$product_id)->where('option','video_mp4')->delete();
            ProductImagesTemp::where('product_id',$product_id)->where('option','video_webm')->delete();
            ProductImagesTemp::where('product_id',$product_id)->where('option','video_thumb')->delete();

            return json_encode(['success'=>'true']);
        }
        else
        {
            $check_option_exists=ProductOption::where('product_id',$request->productid)->where('id',$request->option_id);
            if($check_option_exists->count()>0)
            {
                $photos = ProductOptionImages::where('id',$request->photo_id)->where('product_id',$request->productid)->where('product_option_id',$request->option_id);
                if($photos != NULL){
                    $photos->delete();
                }
                $photos = ProductOptionImages::where('product_id',$request->productid)->where('product_option_id',$request->option_id)->get();
            }
            else
            {
                $photos = ProductImagesTemp::where('id',$request->photo_id)->where('product_id',$request->productid)->where('option',$request->option_id);
                if($photos != NULL){
                    $photos->delete();
                }
                $photos = ProductImagesTemp::where('product_id',$request->productid)->where('option',$request->option_id);
            }
        }
        return json_encode(['success'=>'true','steps_count' => $photos->count()]);
    }
    public function add_product_video_mp4(Request $request)
    {
        if(isset($_FILES["product_video_mp4"]["name"]))
        {   
            $rows = array();
            $err = array();

            if($request->type=="edit_product")
            {
                $check_product=Product::where('id',$request->id)->first();

                if(empty($check_product)){

                    $err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

                    $rows['error'] = $err;

                    return json_encode($rows);
                }
                
            }

            $tmp_name = $_FILES["product_video_mp4"]["tmp_name"];
            $name = str_replace(' ', '_', $_FILES["product_video_mp4"]["name"]);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $name = time().'_mp4_video.'.$ext;
            $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id;
                            
            if(!file_exists($filename))
            {
                try {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id, 0777, true);
                }
                catch(\Exception $e) {
                    flashMessage('danger',$e->getMessage());
                    return redirect('admin/products');
                }
            }
                                       
            if($ext == 'mp4')   
            {            
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $c=$this->helper->cloud_upload($tmp_name,"","video");
                    if($c['status']!="error")
                    {
                        $name=$c['message']['public_id'];    
                    }
                    else
                    {
                        $err = array('error_title' => ' Video Error', 'error_description' => $c['message']);
                    }
                }
                else
                {
                    if(move_uploaded_file($tmp_name, "image/products/".$request->id."/".$name))
                    {
                        
                    }
                }
                ProductImagesTemp::where('product_id',$request->id)->where('option','video_mp4')->delete();

                $temp_photos['product_id'] = $request->id;
                $temp_photos['image_name'] = $name;
                $temp_photos['option'] = 'video_mp4';
                $temp_photos['created_at'] = date('Y-m-d H:i:s');
                $temp_photos['updated_at'] =  date('Y-m-d H:i:s');
                if(!count($err))
                {
                    if($request->type=="add_product")
                    {
                        ProductImagesTemp::create($temp_photos);
                    }
                    else
                    {
                        ProductImagesTemp::create($temp_photos);                
                    }
                }
            }
            else
            { 
                $err = array('error_title' => ' Video Error', 'error_description' => 'The format is not valid');
                
            }
            $rows['succresult'] = "success";
            $rows['error'] = $err;
            return json_encode($rows);
        }
    }
    public function add_video_thumb(Request $request)
    {
        $tmp_name = $_FILES["picture"]["tmp_name"];

        if($request->type=="edit_product")
            {
                $check_product=Product::where('id',$request->id)->first();

                if(empty($check_product)){

                    $err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

                    $rows['error'] = $err;

                    return json_encode($rows);
                }
                
            }

        $name = str_replace(' ', '_', $_FILES["picture"]["name"]);
        $ext = 'png';
        $original_name = time().'_'.$name;
        $name = $original_name.'.'.$ext;
        $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id;
        $err = array();
        if(!file_exists($filename))
        {
            try {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id, 0777, true);
            }
            catch(\Exception $e) {
                flashMessage('danger',$e->getMessage());
                return redirect('admin/products');
            }
        }
        if(UPLOAD_DRIVER=='cloudinary')
        {
            $c=$this->helper->cloud_upload($tmp_name);
            if($c['status']!="error")
            {
                $name=$c['message']['public_id'];    
            }
            else
            {
                $err = array('error_title' => ' Photo Error', 'error_description' => $c['message']);
            }
        }
        else
        {
            if(move_uploaded_file($tmp_name, "image/products/".$request->id."/".$name))
            {
                $this->helper->compress_image($filename.'/'.$name, $filename.'/'.$name, 90);
                $name=$original_name.'_225x225.'.$ext;
            }
        }
        ProductImagesTemp::where('product_id',$request->id)->where('option','video_thumb')->delete();
        $temp_photos['product_id'] = $request->id;
        $temp_photos['image_name'] = $name;
        $temp_photos['option'] = 'video_thumb';
        $temp_photos['created_at'] = date('Y-m-d H:i:s');
        $temp_photos['updated_at'] =  date('Y-m-d H:i:s');
        if(!count($err))
        {
            if($request->type=="add_product")
            {
                ProductImagesTemp::create($temp_photos);
            }
            else
            {
                // Product::where('id', $request->id)->update(['video_thumb' => $name]);
                 ProductImagesTemp::create($temp_photos);
            }
        }
        $rows['error'] = $err;
        $rows['successres'] = $name;
        return json_encode($rows);
    }
    public function add_product_video_webm(Request $request)
    {
        if(isset($_FILES["product_video_webm"]["name"]))
        {   
            $rows = array();
            $err = array();

            if($request->type=="edit_product")
            {
                $check_product=Product::where('id',$request->id)->first();

                if(empty($check_product)){

                    $err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

                    $rows['error'] = $err;

                    return json_encode($rows);
                }
                
            }

            $tmp_name = $_FILES["product_video_webm"]["tmp_name"];
            $name = str_replace(' ', '_', $_FILES["product_video_webm"]["name"]);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $name = time().'_webm_video.'.$ext;
            $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id;
                            
            if(!file_exists($filename))
            {
                try {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id, 0777, true);
                }
                catch(\Exception $e) {
                    flashMessage('danger',$e->getMessage());
                    return redirect('admin/products');
                }
            }
                                       
            if($ext == 'webm')   
            {            
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $c=$this->helper->cloud_upload($tmp_name,"","video");
                    if($c['status']!="error")
                    {
                        $name=$c['message']['public_id'];    
                    }
                    else
                    {
                        $err = array('error_title' => ' Video Error', 'error_description' => $c['message']);
                    }
                }
                else
                {
                    if(move_uploaded_file($tmp_name, "image/products/".$request->id."/".$name))
                    {
                        
                    }
                }
                ProductImagesTemp::where('product_id',$request->id)->where('option','video_webm')->delete();

                $temp_photos['product_id'] = $request->id;
                $temp_photos['image_name'] = $name;
                $temp_photos['option'] = 'video_webm';
                $temp_photos['created_at'] = date('Y-m-d H:i:s');
                $temp_photos['updated_at'] =  date('Y-m-d H:i:s');
                if(!count($err))
                {
                    if($request->type=="add_product")
                    {
                        ProductImagesTemp::create($temp_photos);
                    }
                    else
                    {
                        ProductImagesTemp::create($temp_photos);
                        // Product::where('id', $request->id)->update(['video_webm' => $name]);
                    }
                }
            }
            else
            { 
                $err = array('error_title' => ' Video Error', 'error_description' => 'The format is not valid');
            }
            if($request->type=="add_product")
            {
                $rows['video_src']=ProductImagesTemp::where('product_id',$request->id)->where('option','video_webm')->first()->images_name;
                $rows['video_src_mp4']=ProductImagesTemp::where('product_id',$request->id)->where('option','video_mp4')->first()->images_name;
                $rows['video_src_webm']=ProductImagesTemp::where('product_id',$request->id)->where('option','video_webm')->first()->images_name;
            }
            else
            {
                // $result = Product::where('id', $request->id)->first();
                // $rows['video_src']=$result->video_src;
                // $rows['video_src_mp4']=$result->video_src_mp4;
                // $rows['video_src_webm']=$result->video_src_webm;
                $rows['video_src'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_webm')->first()->images_name;
                $rows['video_src_mp4'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_mp4')->first()->images_name;
                $rows['video_src_webm'] = ProductImagesTemp::where('product_id', $request->id)->where('option', 'video_webm')->first()->images_name;
            }
            
            $rows['error'] = $err;
            return json_encode($rows);
        }
    }
    public function add_product_photo(Request $request)
    { 
        if(isset($_FILES["upload-file"]["name"]))
        {   $rows = array();
            $err = array();

            if ($request->type == "edit_product") {
                $check_product = Product::where('id', $request->product_id)->first();

                if (empty($check_product)) {

                    $err = array('error_title' => 'Invalid Product Id', 'error_description' => 'Invalid Product Id');

                    $rows['error'] = $err;

                    return json_encode($rows);
                }

            }
            foreach($_FILES["upload-file"]['error'] as $key=>$error) 
            {
 
                $tmp_name = $_FILES["upload-file"]["tmp_name"][$key];

                $name = str_replace(' ', '_', $_FILES["upload-file"]["name"][$key]);

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                $name = time().'_'.$name;

                 $ex = substr( $name, strrpos( $name, "."));
                 $newfilename = basename($name, $ex);

                $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id;
                                
                if(!file_exists($filename))
                {
                    try {
                        mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id, 0777, true);
                    }
                    catch(\Exception $e) {
                        flashMessage('danger',$e->getMessage());
                        return redirect('admin/products');
                    }
                }
                                           
                if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')   
                {            
                    
                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $c=$this->helper->cloud_upload($tmp_name);

                        if($c['status']!="error")
                        {
                            $name=$c['message']['public_id'];    
                        }
                        else
                        {
                            $err = array('error_title' => ' Photo Error', 'error_description' => $c['message']);
                        }
                    }
                    else
                    {
                        $upload_path = "image/products/".$request->id."/".$name;
                        $resize_path = "image/products/".$request->id."/".$newfilename;

                        if(move_uploaded_file($tmp_name, $upload_path))
                        {
                            
                        }

                        $this->helper->compress_image($upload_path , "image/products/".$request->id."/".$newfilename."_compress.".$ext , 90);        
                        $this->helper->resize_image($upload_path, 650,640,$resize_path.'_home_full');
                        $this->helper->resize_image($upload_path, 450,340,$resize_path.'_home_half');
                        $this->helper->resize_image($upload_path, 124,132,$resize_path.'_popular');
                        $this->helper->resize_image($upload_path, 104,104,$resize_path.'_header');


                    }
                       
                   

                    $temp_photos['product_id'] = $request->id;
                    $temp_photos['image_name'] = $name;
                    $temp_photos['created_at'] = date('Y-m-d H:i:s');
                    $temp_photos['updated_at'] =  date('Y-m-d H:i:s');
                    if(!count($err))
                    {
                        if($request->type=="add_product")
                        {
                            ProductImagesTemp::create($temp_photos);
                        }
                        else
                        {
                            ProductImagesTemp::create($temp_photos);
                        }
                    }
                }
                else
                { 
                    $err = array('error_title' => ' Photo Error', 'error_description' => 'This is not an image file');
                    
                }
            }
            if($request->type=="add_product")
            {
                $result = ProductImagesTemp::where('product_id',$request->id)->where('option',NULL)->get();
            }
            else
            {
                $pro_img = ProductImages::where('product_id', $request->product_id)->get();
                $pro_temp_img = ProductImagesTemp::where('product_id', $request->id)->where('option', NULL)->get();
                $result = $pro_img->merge($pro_temp_img );
            }
            $rows['succresult'] = $result;
            $rows['steps_count'] = $result->count();
            $rows['error'] = $err;
            return json_encode($rows);
            
        }
    }

    public function add_product_video(Request $request)
    {
        if(isset($_FILES["product_video"]["name"]))
        {   
            $rows = array();
            $err = array();

            $tmp_name = $_FILES["product_video"]["tmp_name"];
            $name = str_replace(' ', '_', $_FILES["product_video"]["name"]);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $name = time().'_'.$name;
            $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id;
                            
            if(!file_exists($filename))
            {
                try {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->id, 0777, true);
                }
                catch(\Exception $e) {
                    flashMessage('danger',$e->getMessage());
                    return redirect('admin/products');
                }
            }
                                       
            if($ext == 'mp4')   
            {            
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $c=$this->helper->cloud_upload($tmp_name);
                    if($c['status']!="error")
                    {
                        $name=$c['message']['public_id'];    
                    }
                    else
                    {
                        $err = array('error_title' => ' Video Error', 'error_description' => $c['message']);
                    }
                }
                else
                {
                    if(move_uploaded_file($tmp_name, "image/products/".$request->id."/".$name))
                    {
                        
                    }
                }
                ProductImagesTemp::where('product_id',$request->id)->where('option','video')->delete();

                $temp_photos['product_id'] = $request->id;
                $temp_photos['image_name'] = $name;
                $temp_photos['option'] = 'video';
                $temp_photos['created_at'] = date('Y-m-d H:i:s');
                $temp_photos['updated_at'] =  date('Y-m-d H:i:s');
                if(!count($err))
                {
                    if($request->type=="add_product")
                    {
                        ProductImagesTemp::create($temp_photos);
                    }
                    else
                    {
                        Product::where('id', $request->id)->update(['video' => $name]);
                    }
                }
            }
            else
            { 
                $err = array('error_title' => ' Video Error', 'error_description' => 'The format is not valid');
                
            }
            if($request->type=="add_product")
            {
                $result = ProductImagesTemp::where('product_id',$request->id)->where('option','video')->first()->images_name;
            }
            else
            {
                $result = Product::where('id', $request->id)->first()->video_src;
            }
            $rows['succresult'] = $result;
            $rows['error'] = $err;
            return json_encode($rows);
        }
    }

    public function product_add(Request $request)
    {

        $float_value = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";
        //for product table
        $messages = [
            'title.required' => 'Title',
            'ships_to.required' => 'Ships To',
            'description.required' => 'Description',
            'category_id.required' => 'Category',
            'price.required' => 'Price'
        ];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'ships_to' => 'required'
        ],$messages);

        if ($validator->fails()) {
                return redirect('admin/products/add')->withErrors($validator)->withInput();
            }
            

        $products['user_id'] = $request->user_id;
        $products['title'] = html_entity_decode($request->title);
        $products['description'] = html_entity_decode($request->description);
        $products['category_id'] = $request->category_id;
        $products['category_path'] = $request->category_path;
        $products['total_quantity'] = $request->total_quantity;
        $products['return_policy'] = $request->return_policy;
        if($request->use_exchange)
            $products['exchange_policy'] = $request->return_policy;
        else
            $products['exchange_policy'] = $request->exchange_policy;        

        $products['policy_description'] = $request->return_exchange_policy_description;
        $products['sold'] = ($request->sold!="") ? $request->sold : 0;
        $products['views_count'] = 0;
        if($request->update_type=="edit_product")
        {
            Product::where("id",$request->product_id)->update($products);
        }
        else
        {   
            $products['created_at'] = date('Y-m-d H:i:s');

            $product_id=Product::insertGetId($products);
        }

        if($request->update_type=="edit_product")
            $product_id=$request->product_id;
        else
            $product_id=$product_id;

        $update_d['status'] = $request->status;
        $update_d['sold_out']=$request->sold_out;
        $update_d['cash_on_delivery']=$request->cash_on_delivery;
        $update_d['cash_on_store']=$request->cash_on_store;
        Product::where('id',$product_id)->update($update_d);

        //for product price table
        if($request->check_sale)
        {
            $product_prices['discount']=($request->discount != "" ? $request->discount : NULL);
            $product_prices['retail_price']=($request->retail_price != "" ? $request->retail_price : NULL);
        }
        else
        {
            $product_prices['discount']=NULL;
            $product_prices['retail_price']=NULL;
        }
        $product_prices['product_id']=$product_id;
        $product_prices['price']=round($request->price,2);
        $product_prices['sku']=$request->sku_stock;
        $product_prices['length']=($request->length != "" ? round($request->length,2) : NULL);
        $product_prices['height']=($request->height != "" ? round($request->height,2) : NULL);
        $product_prices['width']=($request->width != "" ? round($request->width,2) : NULL);
        $product_prices['weight']=($request->weight != "" ? round($request->weight,2) : NULL);
        $product_prices['currency_code']=$request->currency_code;
        if($request->update_type=="edit_product")
        {
            $product_price = ProductPrice::where('product_id',$product_id)->update($product_prices);
        }
        else
        {
            $product_price = ProductPrice::create($product_prices);            
        }
        //for product shipping table
        $product_shippings['shipping_type']=$request->shipping_type;
        $product_shippings['ships_from']=$request->ships_from;
        $product_shippings['manufacture_country']=$request->manufacture_country;
        $product_shippings['product_id']=$product_id;    
        $product_sh=ProductShipping::whereNotIn('ships_to',$request->ships_to)->where('product_id',$product_id);
        if($product_sh->count() >0)
        {
            foreach ($product_sh->get() as $value) {
                ProductShipping::where('id',$value->id)->delete();
            }
        }
        for($i=0;$i<count($request->ships_to);$i++)
        {
            $product_shippings['ships_to']=$request->ships_to[$i];
            $product_shippings['start_window']=$request->expected_delivery_day_1[$i];
            $product_shippings['end_window']=$request->expected_delivery_day_2[$i];
            if($request->shipping_type!="Free Shipping")
            {
                $product_shippings['charge']=round($request->custom_charge_domestic[$i],2);
                $product_shippings['incremental_fee']=($request->custom_incremental_domestic[$i] != "" ? round($request->custom_incremental_domestic[$i],2) : NULL);
            }
            $check_shipping=ProductShipping::where('product_id',$product_id)->where('ships_to',$request->ships_to[$i]);
            if($check_shipping->count())
            {
                $product_shipping = ProductShipping::where('product_id',$product_id)->where('ships_to',$request->ships_to[$i])->update($product_shippings);       
            }
            else
            {
                $product_shipping = ProductShipping::create($product_shippings);           
            }
            
        }
        
        if($request->update_type=="add_product")
        {
            $filename=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id;
            $oldfilename=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->product_id;
            if(!file_exists($filename))
            {
                try {
                    mkdir($filename, 0777, true);
                }
                catch(\Exception $e) {
                    flashMessage('danger',$e->getMessage());
                    return redirect('admin/products');
                }
            }
            $product_image_temps=ProductImagesTemp::where('product_id',$request->product_id)->where('option',NULL)->get();
            foreach ($product_image_temps as $product_image_temp) {
                $update_image['product_id'] = $product_id;
                $update_image['image_name'] = $product_image_temp->image_name;
                ProductImages::create($update_image);

                $old=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->product_id.'/'.$product_image_temp->image_name;
                
                $new=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/'.$product_image_temp->image_name;
                $old_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->product_id.'/';
                $new_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/';
                
                
                if(UPLOAD_DRIVER !='cloudinary')
                {
                 
                    File::move($old,$new); // keep the same folder to just rename 

                    $ext = substr( $old, strrpos( $old, "."));

                    $old_compress = basename($old, $ext). "_compress" . $ext;
                    $old_home_full = basename($old, $ext). "_home_full" . $ext;
                    $old_home_half = basename($old, $ext). "_home_half" . $ext;
                    $old_popular = basename($old, $ext). "_popular" . $ext;
                    $old_header = basename($old, $ext). "_header" . $ext;

                    $ext = substr( $new, strrpos( $new, "."));
                    $new_compress = basename($new, $ext). "_compress" . $ext;
                    $new_home_full = basename($new, $ext). "_home_full" . $ext;
                    $new_home_half = basename($new, $ext). "_home_half" . $ext;
                    $new_popular = basename($new, $ext). "_popular" . $ext;
                    $new_header = basename($new, $ext). "_header" . $ext;

                    File::move($old_dir.$old_compress,$new_dir.$new_compress); // keep the same folder to just rename 
                    File::move($old_dir.$old_home_full,$new_dir.$new_home_full); // keep the same folder to just rename 
                    File::move($old_dir.$old_home_half,$new_dir.$new_home_half); // keep the same folder to just rename 
                    File::move($old_dir.$old_popular,$new_dir.$new_popular); // keep the same folder to just rename 
                    File::move($old_dir.$old_header,$new_dir.$new_header); // keep the same folder to just rename 


                }
            }

            $product_video_mp4_temp=ProductImagesTemp::where('product_id',$request->product_id)->where('option','video_mp4')->first();
            $product_video_webm_temp=ProductImagesTemp::where('product_id',$request->product_id)->where('option','video_webm')->first();
            $product_video_thumb_temp=ProductImagesTemp::where('product_id',$request->product_id)->where('option','video_thumb')->first();
            if($product_video_mp4_temp)
            {
                Product::where('id',$product_id)->update(['video_mp4' => $product_video_mp4_temp->image_name,'video_webm' => $product_video_webm_temp->image_name,'video_thumb' => $product_video_thumb_temp->image_name]);
                $old=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->product_id.'/'.$product_video_mp4_temp->image_name;
                $new=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/'.$product_video_mp4_temp->image_name;
                $old_webm=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->product_id.'/'.$product_video_webm_temp->image_name;
                $new_webm=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/'.$product_video_webm_temp->image_name;
                $old_thumb=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$request->product_id.'/'.$product_video_thumb_temp->image_name;
                $new_thumb=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/'.$product_video_thumb_temp->image_name;
                 if(UPLOAD_DRIVER !='cloudinary')
                {
                    File::move($old,$new); // keep the same folder to just rename 
                    File::move($old_webm,$new_webm); // keep the same folder to just rename 
                    File::move($old_thumb,$new_thumb); // keep the same folder to just rename 
                }                
            }
        }
        

        //for product option table
        if($request->product_option)
        {
            $product_op=ProductOption::whereNotIn('option_name',$request->product_option)->where('product_id',$product_id);
            if($product_op->count() >0)
            {
                foreach ($product_op->get() as $value) {
                    ProductOptionImages::where('product_id',$product_id)->where('product_option_id',$value->id)->delete();
                    ProductImagesTemp::where('product_id',$product_id)->where('option',$value->id)->delete();
                    ProductOption::where('id',$value->id)->delete();
                    Cart::where('option_id',$value->id)->delete();
                }
            }
            $product_quantity['total_quantity']=0;
            for($i=0;$i<count($request->product_option);$i++)
            {
                $product_options['product_id']=$product_id;
                $product_options['sku']=$request->product_option_sku[$i];
                $product_options['option_name']=$request->product_option[$i];
                $product_options['total_quantity']=($request->product_option_qty[$i] != "" ? $request->product_option_qty[$i] : NULL);
                $product_quantity['total_quantity']+=$request->product_option_qty[$i];
                $product_options['price']=($request->product_option_price[$i] != "" ? $request->product_option_price[$i] : $request->price);
                $product_options['sold']=($request->product_option_sold[$i] != "" ? $request->product_option_sold[$i] : 0);

                if(isset($request->product_option_check_sale[$i]))
                {

                    $product_options['retail_price']=($request->product_option_retail_price[$i] != "" ? $request->product_option_retail_price[$i] : NULL);
                    $product_options['discount']=($request->product_option_discount[$i] != "" ? $request->product_option_discount[$i] : NULL);
                }
                else
                {
                    $product_options['retail_price']="0";
                    $product_options['discount']="0";
                }
                
                
                $product_options['length']=($request->product_option_length[$i] != "" ? $request->product_option_length[$i] : NULL);
                $product_options['width']=($request->product_option_width[$i] != "" ? $request->product_option_width[$i] : NULL);
                $product_options['height']=($request->product_option_height[$i] != "" ? $request->product_option_height[$i] : NULL);
                $product_options['weight']=($request->product_option_weight[$i] != "" ? $request->product_option_weight[$i] : NULL);
                $product_options['currency_code']=$request->currency_code;
                if($request->product_option_soldout)
                {
                   $option_soldout=$request->product_option_soldout;
                }
                else
                {
                    $option_soldout=array();
                }

                if(in_array($i,$option_soldout))
                {
                    $product_options['sold_out']="Yes";    
                }
                else
                {
                    $product_options['sold_out']="No";
                }
                $check_option=ProductOption::where('product_id',$product_id)->where('option_name',$request->product_option[$i]);
                if($check_option->count())
                {
                    $product_option = ProductOption::where('product_id',$product_id)->where('option_name',$request->product_option[$i])->update($product_options);       
                }
                else
                {
                    $product_option = ProductOption::create($product_options);           
                
                    $option_filename=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/options/'.$product_option->id;
                    $option_oldfilename=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/options/'.$request->product_option_id[$i];
                    if(!file_exists($option_filename))
                    {
                        try {
                            mkdir($option_filename, 0777, true);
                        }
                        catch(\Exception $e) {
                            flashMessage('danger',$e->getMessage());
                            return redirect('admin/products');
                        }
                    }
                    $product_option_image_temps=ProductImagesTemp::where('product_id',$product_id)->where('option',$request->product_option_id[$i])->get();
                    foreach ($product_option_image_temps as $product_option_image_temp) 
                    {
                        $update_option_image['product_id'] = $product_id;
                        $update_option_image['product_option_id'] = $product_option->id;
                        $update_option_image['image_name'] = $product_option_image_temp->image_name;
                        ProductOptionImages::create($update_option_image);

                        $old=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/options/'.$request->product_option_id[$i].'/'.$product_option_image_temp->image_name;
                        $new=dirname($_SERVER['SCRIPT_FILENAME']).'/image/products/'.$product_id.'/options/'.$product_option->id.'/'.$product_option_image_temp->image_name;
                        if(UPLOAD_DRIVER !='cloudinary')
                        {
                            File::move($old,$new); // keep the same folder to just rename 
                        }
                    }
                }
            }
            Product::where('id',$product_id)->update($product_quantity);

            if($request->update_type!="edit_product")
            {
                File::deleteDirectory($option_oldfilename);
            }
        }
        else
        {
            $product_op=ProductOption::where('product_id',$product_id);
            if($product_op->count() >0)
            {
                foreach ($product_op->get() as $value) {
                    ProductOptionImages::where('product_id',$product_id)->where('product_option_id',$value->id)->delete();
                    ProductImagesTemp::where('product_id',$product_id)->where('option',$value->id)->delete();
                    ProductOption::where('id',$value->id)->delete();
                    Cart::where('option_id',$value->id)->delete();
                }
            }
        }

        if($request->update_type!="edit_product")
        {
            File::deleteDirectory($oldfilename);
            ProductImagesTemp::where('product_id',$request->product_id)->delete();

            $store = MerchantStore::where('user_id',$request->user_id)->first();

            if(isset($store)) {
                $activity_data                  = new Activity;
                $activity_data->source_id       = $store->id;
                $activity_data->source_type     = "store";
                $activity_data->activity_type   = "add_product";
                $activity_data->target_id       = $product_id;
                $activity_data->save();
            }
        }
        Session::forget('product_currency');
        Session::forget('product_symbol');
         $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
        return redirect('admin/products');
    }
    /**
    * Delete Product
    *
    * @param array $request    Input values
    * @return redirect     to Products View
    */
    public function delete_product(Request $request)
    {
        $check_orders_details=OrdersDetails::where('product_id', $request->id)->where('status','!=','Cancelled')->where('status','!=','Completed')->count();

        if($check_orders_details) {
            $this->helper->flash_message('error', 'This products has some orders. So, you cannot delete this product.'); // Call flash message function
        }
        else 
        {
            Product::find($request->id)->Delete_All_Product_Relationship(); 
           $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }

        return redirect('admin/products');
    }
    public function change_currency(Request $request)
    {
        $data['currency_symbol']=$symbol = Currency::original_symbol($request->currency);
        $data['currency_code']=$request->currency;
        $data['minimum_amount'] = $this->payment_helper->currency_convert('USD', $request->currency, 1); 
        return response()->json($data);
    }

    public function edit_currency(Request $request)
    {
        Session::put('product_currency', $request->currency);
        $symbol = Currency::original_symbol($request->currency);
        Session::put('product_symbol', $symbol);
    }
    public function check_price(Request $request)
    {
        $symbol = Currency::original_symbol($request->currency);
        $minimum_amount = $this->payment_helper->currency_convert('USD', $request->currency, 1); 
        if($request->price < $minimum_amount)
        {
            $data['status']="error";
            $data['error']="Price must be greater than or equal to ".$symbol." ".$minimum_amount;    
        }
        else
        {
           $data['status']="success";
           $data['error']="";
        }
        
        return response()->json($data);
    }
    public function check_option_price(Request $request)
    {
        $symbol = Currency::original_symbol($request->currency);
        $minimum_amount = $this->payment_helper->currency_convert('USD', $request->currency, 1); 
        if($request->product_option_price[0] < $minimum_amount)
        {
            $data['status']="error";
            $data['error']="Price must be greater than or equal to ".$symbol." ".$minimum_amount;    
        }
        else
        {
           $data['status']="success";
           $data['error']="";
        }
        
        return response()->json($data);
    }

}
