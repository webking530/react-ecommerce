@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" ng-controller="add_product" ng-cloak >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Product
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Products</a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Product Form</h3>
            </div>
@if ($errors->any())
 <div class="error-box">
    <h3><i class="icon"></i><span>There were {{ count($errors) }} errors, please check the highlighted fields:</span></h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => 'admin/products/product_add', 'class' => 'form-horizontal', 'id' => 'add_product_form', 'files' => true]) !!}

<input type="hidden" name="product_id" value="{{ $product_id }}" id="product_id">
<input type="hidden" name="update_type" value="{{$update_type}}" id="update_type">
<input type="hidden" name="currency_code" value="{{$product_currency}}" id="currency_code">
<input type="hidden" id='minimum_amount' value="{{ @$minimum_amount }}">
<input type="hidden" name="tmp_product_id" value="{{ @$tmp_product_id }}" id="tmp_product_id">
<span style="display:none" id='currency_symbol'>{{ $product_symbol }}</span>
               <div id="sf1" class="frm">
                <div class="box-header with-border">
                  <h4 class="box-title">Step 1 of 8 - Product Information</h4>
                </div>
                <p class="text-danger">(*)Fields are Mandatory</p>
                <fieldset class="box-body">
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Title<em class="text-danger">*</em></label>
                  <div class="col-sm-6">                    
                    {!! Form::text('title', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Product Title']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Description<em class="text-danger">*</em></label>
                  <div class="col-sm-6">        
                  <textarea id="description" name="description" class="textInMce " style="width:100% !important;border-top: 0px !important;">
                  </textarea>         
                                      
                    <span class="help-inline text-danger required_desc" style="display: none;">
                        </span>
                  </div>
                </div>
                
              </fieldset>

              <div class="box-footer">
                    <button class="btn btn-primary open1 pull-right" type="button" onclick="next(1)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
              </div>

            <div id="sf2" class="frm" style="display: none">
            <div class="box-header with-border">
              <h4 class="box-title">Step 2 of 8 - Images and Video</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
              <div class="form-group" ng-init="video_src = ''">
              <label for="night" class="col-sm-3 control-label">Video</label>
              <div class="col-sm-6">
                 <ul id="js-video-grid" class="additional ui-sortable row sortable" ng-show="!video_src">
                            <li class="add new  col-lg-4 col-md-4 p-0 m-3" id="add-video" data-toggle="modal" data-target="#video_upload_popup">
                                <a href="javascript:void(0)"></a>
                            </li>

                        </ul>
                <div class="additional video_preview" ng-show="video_src">
                    <video class="img-responsive" controls="" src="@{{video_src}}">
                    </video>
                    <button type="button" data-toggle="modal" data-target="#js-error" ng-click="delete_video('{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_descrip') }}')" class="delete-video-btn overlay-btn js-delete-video-btn">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="additional snap_area" ng-show="video_src">
                    <canvas style="display:none"></canvas>
                    <img crossorigin="Anonymous" id="canvasImg" src="@{{canvas_image_src}}" name="canvas_image" width="100px" height="100px" alt="">
                    <span class="">
                    <label class="btn_canvas"  id="snap">Click</label>
                    <p>Click here to set thumbnail image</p>
                    </span>
                </div>
                <label id="video_upload_error">Select Valid file</label>
              </div>
              </div>
              <div class="form-group">
              <label for="night" class="col-sm-3 control-label">Image<em class="text-danger">*</em></label>
              <div class="col-sm-6">              
              <input type="file" id="add-product-imagevideo" name="upload-file[]" multiple="true" style="display:none" accept="image/*">
              <span class="help-inline text-danger required_img" style="display: none;">
              </span>
                <ul id="js-photo-grid" class="additional ui-sortable row  sortable">
                    <li ng-repeat="item in photos_list" class="add item col-lg-4 col-md-6 row-space-4" data-id="@{{ $index }}" data-index="0" draggable="true" style="display: list-item;" ng-mouseover="over_first_photo($index)" ng-mouseout="out_first_photo($index)">
                        <div class="panel photo-item">
                          <div class="photo-size photo-drag-target js-photo-link" id="photo-@{{ $index }}"></div>
                          <a class="media-photo media-photo-block text-center photo-size" href="#">
                          <img ng-src="@{{ item.images_name }}" class="img-responsive-height">
                          </a>
                          <button type="button" data-toggle="modal" data-target="#js-error" data-photo-id="@{{ $index }}" ng-click="delete_photo(item,item.id,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_descrip') }}')" class="delete-photo-btn overlay-btn js-delete-photo-btn">
                            <i class="fa fa-trash"></i>
                          </button>
                        </div>
                    </li>
                      <!-- <li class="form-control add col-lg-4 col-md-6 row-space-4" id="add-image">
                          <a href="javascript::"></a>
                      </li> -->
                       <li class="add new col-lg-4 col-md-4 p-0 m-3" id="add-image">
                                <a href="#"></a>
                            </li>
                  <input type="file" class="hide" name="photos[]" multiple="true" id="upload_photos2" accept="image/*">

                  </ul>
                  <span class="description" style="color: #9da1ab;">Allowed file types JPG, GIF or PNG.</span>
                  <label id="image_upload_error">Select Valid file</label>
                  <label id="cloud_upload_error"></label>
              </div>
              </div>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(2)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(2)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
            </div>

            <div id="sf3" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 3 of 8 - Pricing & Details</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Currency</label>
              <div class="col-sm-6">
                <select class="form-control" name="default_currency"  id="input_default_currency">
                @foreach($currency as $cur)
                <option {{ ($product_currency==$cur->code) ? 'selected' : '' }} value="{{$cur->code}}">{{$cur->code}}</option>
                @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Price<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                <div class="input-grou">
                  <span class="input-group-addon boot_addon" style="width: 6%">{{ $product_symbol }}</span>
                  <input class="boot_text" style="width: 94%;"  name="price" ng-change="updateDiscount()" type="text"  id="price" ng-model="price" limit-to="9" numbers-only>
                </div>
              </div>
            </div>
            <div class="form-group retail" style="display: none;">
              <label for="name" class="col-sm-3 control-label">Retail Price<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                <div class="input-grou">
                  <span class="input-group-addon boot_addon" style="width: 6%;">{{ $product_symbol }}</span>
                  <input class="boot_text" style="width: 94%;" ng-change="updateDiscount()" name="retail_price" type="text" id="retail_price" ng-model="retail_price" limit-to="9" numbers-only>
                </div>
              </div>
            </div>
            <div class="form-group discount" style="display: none;">
              <label for="name" class="col-sm-3 control-label">Discount</label>
              <div class="col-sm-6 pro_discountspan">
                 <input class="text" name="discount" style="width: 100%;height: 34px" type="text" placeholder="" readonly="readonly" id="discount" value="" ng-model="discount">
                <small style="top:3px;">% OFF</small>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-3 control-label"></div>
              <div class="selectable-option-admin col-sm-6"><input type="checkbox" name="check_sale" id="check-sale"> <label for="check-sale">This product is on sale</label></div>
            </div>
          <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Length</label>
              <div class="col-sm-6 pro_details">
                <input class="text" name="length" style="width: 100%;height: 34px;"  type="text"  id="length" ng-model="length"  valid-number>   
                <small>inch</small>                            
              </div>
            </div>

             <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Width</label>
              <div class="col-sm-6 pro_details">
                 <input class="text" name="width" style="width: 100%;height: 34px;;"  type="text" id="width" ng-model="width" valid-number>  
                 <small>inch</small>                            
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Height</label>
              <div class="col-sm-6 pro_details">
                 <input class="text" name="height" style="width: 100%;height: 34px;"  type="text" id="height" ng-model="height" valid-number>   
                 <small>inch</small>                         
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Weight</label>
              <div class="col-sm-6 pro_details">
                <input class="text" name="weight" style="width: 100%;height: 34px;"  type="text" id="weight" ng-model="weight" valid-number> 
                <small>lbs</small>                          
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label">SKU (Stock Keeping Unit)</label>
              <div class="col-sm-6">
                <input class="text" name="sku_stock" style="width: 100%;height: 34px;"  type="text" id="sku" ng-model="sku">
              </div>
            </div>
            </fieldset>
            <fieldset class="box-body">
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Total Quantity<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    
                     <input class="text" style="width: 100%;height: 34px;"  name="total_quantity" type="text" id="total_quantity" limit-to="4" maxlength="4" only-number ng-model="total_quantity">
                  </div>
                </div>
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Sold</label>
                  <div class="col-sm-6">                     
                   <input class="text" name="sold" style="width: 100%;height: 34px;    background-color: #e6e1e1 !important;" value="0" type="text" readonly="readonly" id="sold" ng-model="sold">
                  </div>
                </div>
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Add Product Option</label>
                  <div class="col-sm-6">                     
                   <input type="text" style="width: 100%;height: 34px;" id="input-tags" class="demo-default">


<div class="added space_top_1 table_adjust pro_opt_table" style="" ng-show="product_options.length">
                    <div class="detail" style=""><table>
                        <colgroup>
                            <col width="*">
                            <col width="148">
                            <col width="66">
                            <col width="66">
                            <col width="94">
                            <col width="31">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="name" style="width: 25% !important;">Option</th>
                                <th class="sku">SKU</th>
                                <th class="qty">QTY</th>
                                <th class="sold">Sold</th>
                                <th class="price" style="width: 25% !important;">Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="">
                        <tr ng-repeat="item in product_options" ng-init="item.index=$index"><td>
                        <input type="hidden" name="product_option_id[]" value="@{{ $index }}">
<div class="modal fade" id="product_option_extra_@{{ $index }}" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          Add Item Option
        </div>
        <div class="modal-body pay-p">
            <div class="data-frm fixed_summary option">
                <p class="pay-p name">
                    <label>Option name</label>
                    <input type="text" class="text" readonly="readonly" ng-model="product_options[$index].option_name" value="@{{ item.option_name }}">
                </p>
                <p class="qty tool_tip_new">
                    <label>Quantity</label><span class="help-inline text-danger" id='required_qty_@{{$index}}' style="display: none">This field is required.</span> 
                    <span class="sync">
                        <input type="text" ng-model="product_options[$index].option_qty" class="text"  name="quantity" limit-to="4" value="@{{ item.quantity }}" placeholder="∞" only-number>
                        <button class="btn-switch" id="opt_sync_qty" style="display:none"><span class="on">On</span><span class="off">Off</span></button>
                        <label for="opt_sync_qty" style="display:none">Sync</label>
                    </span>
                </p>
            </div>
            <div class="data-frm fixed_summary ">
            <div class="back-white col-lg-12 nopad bor-bot-ash">
                <div class="flt-left tool_tip_new" style="width:32.5%;padding: 0 5px 0 0px !important;">
                    <p class="pay-p">Price</p>
                    <div class="input-grou">
                      <span class="input-group-addon boot_addon">{{ $product_symbol }}</span>
                      <input class="pop_boot_text ng-pristine ng-untouched ng-valid" name="option_price[]"  style="width: 100%;height: 34px;" type="text" ng-change="updateOptionDiscount($index)" ng-model="product_options[$index].option_price" limit-to="9" numbers-only>
                    </div>
                    

                    <span class="help-inline text-danger" id='required_price_@{{$index}}' style="display: none">This field is required.</span> 
                      <span class="help-inline text-danger" id='required_price_less_@{{$index}}' style="display: none"></span> 
                    <br/>
                </div>
                <div class="flt-left tool_tip_new retail retail_@{{ $index }}" style="width: 32.5%; padding: 0 5px 0 0px !important; display: none;">
                    <p class="pay-p ">Retail Price</p> <span class="help-inline text-danger" id='required_retail_price_@{{$index}}' style="display: none">This field is required.</span>
                    <div class="input-grou">
                      <span class="input-group-addon boot_addon">{{ $product_symbol }}</span>
                    <input class="pop_boot_text ng-pristine ng-untouched ng-valid" style="width: 100%;height: 34px;" ng-change="updateOptionDiscount($index)" name="product_option_retail_price[]" type="text" id="retail_price" ng-model="product_options[$index].retail_price" limit-to="9" numbers-only>

                    </div>

                    <span class="help-inline text-danger" id='required_retail_@{{$index}}' style="display: none">Retail Price should not be less than or equal to Price.</span> 

                </div>
                <div class="flt-left  discount discount_@{{ $index }}" style="width: 32.5%; padding: 0 5px 0 0px !important; display: none;">
                    <p class="pay-p">Discount</p>
                    
                    <span class="discountspan">
                    <input class="text ng-pristine ng-untouched ng-valid" name="product_option_discount[]" style="width: 100%;height: 34px" type="text" placeholder="" readonly="readonly" id="discount" value="" ng-model="product_options[$index].option_discount">
                    <small>% OFF</small>
                    </span>                
                </div>
                <div class="selectable-option-admin col-xs-12 col-sm-12 col-md-12 no_padding"><input  type="checkbox" data_id=""  class="check_sale_option check_sale_option_@{{ $index }}" name="product_option_check_sale[@{{ $index }}]"  data="@{{ $index }}" value="@{{ $index }}" > <label for="check-sale">This product is on sale</label>
                </div>
                  
            </div>
    
            </div>
                <div class="data-frm fixed_summary">
                    <p class="length">
                        <label>Length</label>
                        <span class="unit tool_tip_new"><input type="text" valid-number ng-model="product_options[$index].length" name="product_option_length[]" class="text" value="" >
                        <small >Inch</small></span>
                    </p>
                    <p class="width">
                        <label>Width</label>
                        <span class="unit tool_tip_new"><input type="text" ng-model="product_options[$index].width" name="product_option_width[]" class="text" value="" valid-number>
                        <small>Inch</small></span>
                    </p>
                    <p class="height">
                        <label>Height</label>
                        <span class="unit tool_tip_new"><input type="text" ng-model="product_options[$index].height" name="product_option_height[]" class="text" value="" valid-number>
                        <small>Inch</small></span>
                    </p>
                    <p class="weight">
                        <label>Weight</label>
                        <span class="unit tool_tip_new"><input type="text" ng-model="product_options[$index].weight" name="product_option_weight[]" class="text" value="" valid-number>
                        <small>Ibs</small></span>
                    </p>
                </div>
                <div class="data-frm fixed_summary">
                    <p class="error-comment" style="display:none;">Please check the highlighted fields above for errors</p>
                    <p class="marked_soldout">
                        <input type="checkbox" id="marked_soldout_options_@{{ $index }}" class="marked_soldout_option_@{{ $index }}" value="@{{ $index }}" name="product_option_soldout[]">
                        <label for="marked_soldout_option">Mark as sold out</label>
                    </p>
                </div>


        </div>
        <div class="modal-footer">
    <button type="button" id="delete_option_button_@{{ item.option_name }}"  data-dismiss="modal" data-toggle="modal" data-target="#js-error" ng-click="delete_option(item,$index,item.id,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_option') }}')" class="btns-gray-embo btn-delete" style="float:left"><i class="fa fa-trash">&nbsp;</i>Delete</button>
    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary"  ng-click="saveOption($index)">Save</button>
          
        </div>
      </div>      
    </div>
</div>

    <a href="#" class="btn-reposition disabled"></a>
    <input type="text" name="product_option[]" class="text name" readonly="readonly" value="@{{ item.option_name }}" ng-model="product_options[$index].option_name" >
</td>
<td><input type="text" name="product_option_sku[]" class="text sku" value="@{{ item.sku }}" ng-model="item.sku"></td>
<td class="tool_tip_new bt1"><input type="text" name="product_option_qty[]"  id='required_Quantity@{{$index}}'  limit-to="4" class="text qty" placeholder="∞" value="@{{ item.quantity }}" ng-model="product_options[$index].total_quantity" ng-change="product_options[$index].option_qty=product_options[$index].total_quantity" ng-keyup="update_main_quantity()" only-number></td>
<td><input type="text" name="product_option_sold[]" class="text sold" readonly="readonly" value="@{{ item.sold }}" ng-model="item.sold"></td>
<td class="tool_tip_new bt1">
<div class="input-grou">
<span class="input-group-addon boot_addon">{{ $product_symbol }}</span>
<input type="text" name="product_option_price[]" class="product_option_price op_boot_text price" id="option_price_@{{ $index }}" value="@{{ item.price }}"  ng-change="updateOptionprice($index)"  ng-model="product_options[$index].price" limit-to="9" numbers-only>
</div>
</td>
<td>
    <a href="#" data-toggle="modal" data-target="#product_option_extra_@{{ $index }}" class="btn-edit editbtn">
        <i class="fa fa-pencil" aria-hidden="true"></i>
    </a>
</td>


</tr>
</tbody>
                    </table></div>
                </div>





                  </div>
                </div>
                </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(3)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(3)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
            </div>

           <div id="sf4" class="frm add-shipping" style="display: none">
                <div class="box-header with-border">
                  <h4 class="box-title">Step 4 of 8 - Shipping</h4>
                </div>
                <p class="text-danger">(*)Fields are Mandatory</p>
                <fieldset class="box-body shipping_details" ng-init="ships_from='{{ $countryName }}';manufacture_country='{{ $countryName }}'">
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Shipping calculation<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    
                    <input type="hidden" name="shipping_type" id="shipping_type" value="@{{ shipping_type }}">
                        <ul class="checked-option">
                            
                            <li class=""><label id="shipping-type_free">Free Shipping</label></li>
                            <li class="checked"><label id="shipping-type_flat">Flat Rates</label></li>
                        </ul>
                  </div>
                </div>
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Shipping location<em class="text-danger">*</em></label>
                  <div class="col-sm-6">                     
                    {!! Form::select('ships_from', $country, $countryName, ['class' => 'select-country', 'ng-model'=>'ships_from']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Ships to<em class="text-danger">*</em></label>
                  <div class="col-sm-6">                     
                    {!! Form::select('ships_to[]', $country, '', ['id' => 'ships_to', 'class' => 'select-country', 'multiple' => true, 'ng-model'=>'ships_to']) !!}                    
                  </div>
                  <span class="help-inline text-danger required_shipsto" style="display: none;">
                        </span>
                </div>
                <div class="form-group">
                <div class="col-sm-6 col-sm-offset-3">
                 <div class="multiple-shipping-frm custom-shipping product_shipping">
                            <table>
                                <colgroup><col width="165"><col width="110"><col width="110"><col width="*"></colgroup>
                                <thead>
                                    <tr>
                                        <th>Destination</th>
                                        <th class="custom-shipping-field" ng-hide="hide_shipping_column">Charge</th>
                                        <th class="custom-shipping-field" ng-hide="hide_shipping_column">Incremental fee <span class="tooltip-custom"><i class="icon"></i> <em style="margin-left: -108px;"><b>Incremental fee</b> An incremental fee is the increase in charge resulting from an increase in products or other items.</em></span></th>
                                        <th>Shipping Window <span class="tooltip-custom"><i class="icon"></i> <em style="margin-left: -108px;"><b>Shipping windows</b> The number of days in which your customers should expect their orders to arrive. For example, with a start window of 7 days and end window of 10 days, a customer should expect a shipment to arrive 7-10 days after the order is placed. You can specify different values for US and non-US shipments</em></span></th>
                                    </tr>
                                </thead>
<tbody>
    <tr ng-repeat="item in shipping" ng-init="item.index = $index">
        <td>@{{ item.ships_to }}</td>
        <td class="custom-shipping-field " ng-hide="hide_shipping_column"><div class="tool_tip_new"><input class="text custom-charge-domestic" name="custom_charge_domestic[]" valid-number onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" value="" type="text" id="charge_@{{ $index }}" ng-model="item.charge" valid-number></div></td>
        <td class="custom-shipping-field" ng-hide="hide_shipping_column"><input class="text custom-incremental-domestic" valid-number name="custom_incremental_domestic[]" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="0.00" type="text" id="incremental_fee_@{{ $index }}" ng-model="item.incremental_fee"></td>
        <td><div class="tool_tip_new"><input maxlength="3" valid-number limit-to="8" class="text expected_delivery_day_1" name="expected_delivery_day_1[]" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" value="" type="text" id="start_window_@{{ $index }}" ng-model="item.start_window"> - <input maxlength="3" valid-number limit-to="8" class="text start-day expected_delivery_day_2" name="expected_delivery_day_2[]" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" value="" type="text"  id="end_window_@{{ $index }}" ng-model="item.end_window"></div></td>
    </tr>
</tbody>
                            </table>
                            </div>
                        </div>
                        </div>
                         <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Country of Manufacture<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    
                    {!! Form::select('manufacture_country', $country, $countryName, ['class' => 'select-country', 'ng-model'=>'manufacture_country']) !!}
                  </div>
                </div>
              </fieldset>

              <div class="box-footer">
              <button class="btn btn-warning" type="button" onclick="back(4)"><span class="fa fa-arrow-left"></span> Back</button>
                    <button class="btn btn-primary open1 pull-right" type="button" onclick="next(4)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
              </div>

               <div id="sf5" class="frm" style="display: none">
                <div class="box-header with-border">
                  <h4 class="box-title">Step 5 of 8 - Return & Exchange</h4>
                </div>
                <p class="text-danger">(*)Fields are Mandatory</p>
                <fieldset class="box-body policy">
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Return Policy<em class="text-danger">*</em></label>
                  <div class="col-sm-6">                    
                     <select name="return_policy" id="return_policy" ng-model="return_policy" ng-init="return_policy={{@$return_policy_value}}">
                                @foreach($return_policy as $rpolicy)
                                <option value="{{ $rpolicy->id }}" >{{ $rpolicy->name }}</option>
                                @endforeach
                      </select>
                      <span class="help-inline text-danger required_return" style="display: none;"></span>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-3 control-label"></div>
                  <div class="selectable-option-admin col-sm-6" style="margin-left: 15px !important;"> 
                  <input id="use_exchange" name="use_exchange" type="checkbox"  checked> <label class="check-label" for="use_exchange">Use same rules for exchanges</label></div>
                </div>
                <div class="form-group use_exchange" style="display: none;width: unset !important;">
                  <label for="calendar" class="col-sm-3 control-label">Exchange Policy<em class="text-danger">*</em></label>
                  <div class="col-sm-6">                     
                     <select class="exchange-policy" id="exchange_policy" name="exchange_policy" ng-model="exchange_policy" ng-init="exchange_policy={{@$return_policy_value}}" style="width:102%">
                                @foreach($return_policy as $rpolicy)
                                <option value="{{ $rpolicy->id }}" >{{ $rpolicy->name }}</option>
                                @endforeach
                      </select>
                      <span class="help-inline text-danger required_exchange" style="display: none;"></span>
                  </div>                  
                </div>
                <div class="comment return_exchange_policy_description">
                      <div class="tool_tip_new" style="width:50%; margin-left:25%;">
                        <textarea id="return_exchange_policy_description" onfocus="$(this).closest('.comment').addClass('focus')" onblur="$(this).closest('.comment').removeClass('focus')" name="return_exchange_policy_description" class="text" default-policy-desc="This item is non-returnable and non-exchangeable." custom-policy-desc="" ng-model="policy_description">This item is non-returnable and non-exchangeable.</textarea>
                      </div>
                        <button type="button" ng-click="policy_description = null" class="btn-reset" style="display:block;left: 70%;">Reset</button>
                    </div>
              </fieldset>

              <div class="box-footer">
              <button class="btn btn-warning" type="button" onclick="back(5)"><span class="fa fa-arrow-left"></span> Back</button>
                    <button class="btn btn-primary open1 pull-right" type="button" onclick="next(5)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
              </div>

              <div id="sf6" class="frm" style="display: none">
                <div class="box-header with-border">
                  <h4 class="box-title">Step 6 of 8 - Organize</h4>
                </div>
                <p class="text-danger">(*)Fields are Mandatory</p>
                <fieldset class="box-body">
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Category<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    <input type="hidden" name="category_id" id="category_id" value="">
                        <input type="hidden" name="category_path" id="category_path" value="">
                        <button id="drilldown" class="btn btn-block drilldown" data-toggle="dropdown">
                            <span class="text" placeholder="Select Category"></span>
                        </button>  
                         <span class="help-inline text-danger required_category" style="display: none;">
                        </span>                  
                  </div>
                  
                </div>

              </fieldset>

              <div class="box-footer">
              <button class="btn btn-warning" type="button" onclick="back(6)"><span class="fa fa-arrow-left"></span> Back</button>
                    <button class="btn btn-primary pull-right" type="button" onclick="next(6)">Next<span class="fa fa-arrow-right"></span></button>
              </div>
              </div>

              <div id="sf7" class="frm" style="display: none">
                <div class="box-header with-border">
                  <h4 class="box-title">Step 7 of 8 - Status</h4>
                </div>
                <p class="text-danger">(*)Fields are Mandatory</p>
                <fieldset class="box-body">
                <div class="form-group">
                  <label for="calendar" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    
                    <ul class="status">
                    <li>
                        <input type="hidden" name="status" id="product_status" value="active">
                        <button type="button" id="product_status_btn" class="product-color product_status btn-switch" onclick="$(this).toggleClass('on')"><span class="on"></span><span class="off"></span></button>
                        <label for="product_status_btn">Active</label>                        
                    </li>
                    
                    <li>
                    <input type="hidden" name="sold_out" id="soldout_status" value="No">
                        <button type="button" id="soldout_status_btn" class="product-color soldout_status btn-switch on" onclick="$(this).toggleClass('on')"><span class="on"></span><span class="off"></span></button>
                        <label for="soldout_status_btn">Mark as sold out</label>
                    </li>

                    @if($cod_status=='Yes')
                    <li>
                        <button type="button" id="cashdelivery_status_btn" class="product-color cashdelivery_status btn-switch on" onclick="$(this).toggleClass('on')"><span class="on"></span><span class="off"></span></button>
                        <label for="cashdelivery_status_btn">Cash on Delivery</label>
                    </li>
                    @endif
                    @if($cos_status=='Yes')
                    <li>
                        <button type="button" id="cashstore_status_btn" class="product-color cashstore_status btn-switch on" onclick="$(this).toggleClass('on')"><span class="on"></span><span class="off"></span></button>
                        <label for="cashstore_status_btn">Cash at Store</label>
                    </li>
                    @endif
                    <input type="hidden" name="cash_on_delivery" id="cashdelivery_status" value="No">
                    <input type="hidden" name="cash_on_store" id="cashdstore_status" value="No">
                </ul>
                  </div>
                  
                </div>

              </fieldset>

              <div class="box-footer">
              <button class="btn btn-warning" type="button" onclick="back(7)"><span class="fa fa-arrow-left"></span> Back</button>
                    <button class="btn btn-primary open1 pull-right" type="button" onclick="next(7)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
              </div>

              <div id="sf8" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 8 of 8 - User</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
              <div class="form-group">
              <label for="user_id" class="col-sm-3 control-label">Username<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('user_id', $users_list, '', ['class' => 'form-control', 'id' => 'user_id', 'placeholder' => 'Select...']) !!}
              </div>
              </div>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(8)"><span class="fa fa-arrow-left"></span> Back</button>
                 <button class="btn btn-primary pull-right" type="button" onclick="next(8)">Add Product</button>
                
              </div>
            </div>
              <!-- /.box-body -->
              
              <!-- /.box-footer -->
            {!! Form::close() !!}
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <div class="modal fade" id="js-error" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default js-delete-close" data-toggle="modal" data-target="" data-dismiss="modal">Close</button>
          <button class="btn btn-primary js-delete-photo-confirm hide" data-option="0" data-photo="" data-option-id="" del-parent="" data-dismiss="modal" data-id="">
            {{ trans('messages.lys.delete') }}
          </button>
        </div>

      </div>
      
    </div>
  </div>
  <!-- hide for admin side room_add page settings/skins problems -->
  
  <div class="modal fade" id="video_upload_popup" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload Video</h4>
      </div>
      <div class="modal-body">
          <p><label>Format .mp4</label><br/><input type="file"  name="product_video_mp4" id="add_product_video_mp4" >
          <label id="video_upload_error_mp4" class="video_upload_error">Please Select valid video</label></p>
          <p><label>Format .webm</label><br/><input type="file" name="product_video_webm" id="add_product_video_webm" >
          <label id="video_upload_error_webm" class="video_upload_error">Please Select valid video</label></p>
          <label id="video_cloud_upload_error"></label>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-toggle="modal" data-target="" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="add-video-btn">Upload</button>
    </div>

</div>

</div>
</div>

<style type="text/css">
.dropdown-menu{
    position: initial;
}
@media screen and (min-width: 767px){
  a.logo {
      position: absolute;
       margin: 0px !important; 
       left: 0px !important; 
       top: 0% !important; 
      z-index: 99;
  }
}
</style>

@stop
@push('scripts')
<script type="text/javascript">
var data = {!! json_encode($categories) !!};
</script>
@endpush