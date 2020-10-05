@extends('merchant_template') @section('main') {{ Form::open(array('url' => 'merchant/product_add', 'files' => true,'id'=>'add_product_form','name'=>'form')) }}
<div class="cls_dashmain">
    <div class="container container-pad pt-4 add_pto" ng-controller="add_product" ng-cloak>
        <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
            <div>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('merchant/all_products') }}">{{ trans('messages.products.products') }}</a></li>
                <li class="breadcrumb-item active">@{{ page_title }}</li>
              </ol>
            </div>
            <div class="d-flex">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="minimum_amount" id='minimum_amount' value="{{ @$minimum_amount }}">
                <span style="display:none" id='currency_symbol'>{{ @$product_symbol }}</span>
                <input type="hidden" name="product_id" value="{{ $product_id }}" id="product_id">
                <input type="hidden" name="tmp_product_id" value="{{ @$tmp_product_id }}" id="tmp_product_id">
                <input type="hidden" name="update_type" value="{{ request()->segment(2) }}" id="update_type">
                <input type="hidden" value="" id="max_option_id">
                <input type="hidden" id="delete_product_id" name="delete_product_id">
                <a href="{{ url('merchant/all_products') }}" class="btn btn-light">{{ trans('messages.products.cancel') }}</a>
                <button class="btn-add ml-2" id="add_product" type="button">@{{ page_button }}</button>
            </div>
        </div>

        <div class="d-flex row p-0">
            <div class="col-lg-8 col-12 martop_addpro">
                <div class="cls_maddproduct">
                    <div class="main_error">
                        <div class="error-head errorh3" style="display:none">{{ trans('messages.products.highlighted_fields') }} :</div>
                        
                        <div class="error-box error-head" style="display:none">
                           
                            @if ($errors->any())
                            
                            <ul>
                                <li>{{ trans('messages.products.highlighted_fields') }} :</li>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                             @endif
                        </div>
                       
                    </div>

                    <div class="wrapper cls_product_info ">
                        <h2 class="stit">{{ trans('messages.products.product_information') }}</h2>
                        <div class="p-3">
                            <div class="form-group">

                                <label class="label font-weight-bold" for="title"> {{ trans('messages.products.title') }} </label>
                                <input class="tex form-control" name="title" type="text" maxlength="100" id="title" ng-model="title">
                            </div>
                            <div class="form-group">
                                <label for="description" class="label font-weight-bold" > {{ trans('messages.products.description') }} </label>

                                <textarea id="description" name="description" class="textInMce form-control">{{ isset($result->description) ? $result->description : '' }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="delete_video_update" name="delete_video_update">
                    <input type="hidden" id="add_product_video" name="add_product_video">
                    <div class="wrapper cls_images_video" ng-init="video_src=''">
                        <h2 class="stit">{{ trans('messages.products.video') }} 
                            <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.products.video') }}</b> {{ trans('messages.products.supported_file_formats') }} : MP4, WEBM<br>{{ trans('messages.products.max_file_size') }} : 640 x 640</em></span>
                            
                        </h2>
                        
                        <ul id="js-video-grid" class="additional ui-sortable row sortable" ng-show="!video_src">
                            <li class="add new  col-lg-4 col-md-4 p-0 m-3" id="add-video" data-toggle="modal" data-target="#video_upload_popup">
                                <a href="javascript:void(0)"></a>
                            </li>

                        </ul>
                        <div class="additional video_preview" ng-show="video_src">
                            <video class="img-responsive" controls="" src="@{{video_src}}">
                            </video>
                            <button type="button" data-toggle="modal" data-target="#js-error" ng-click="delete_video('{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_video') }}')" class="delete-video-btn overlay-btn js-delete-video-btn">
                                <i class="icon-trash" style="vertical-align: middle;display: inline-flex;"></i>
                            </button>
                        </div>
                        <div class="additional snap_area" ng-show="video_src">
                            <canvas style="display:none"></canvas>
                            <img crossorigin="Anonymous" id="canvasImg" src="@{{canvas_image_src}}" name="canvas_image" width="100px" height="100px" alt="">
                            <span class="text_img">
                            <label class="btn_canvas btn btn-light" style="    background-color: #ccc;"  id="snap">{{ trans('messages.products.click') }}</label>
                            <p>{{ trans('messages.products.click_msg') }}</p>
                            </span>
                        </div>
                    </div>
                    <div class="wrapper cls_images_video1">
                        <h2 class="stit">{{ trans('messages.products.images') }} 
                            <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.products.images') }}</b> {{ trans('messages.products.minimum_resolution') }} : 640x640<br>{{ trans('messages.products.supported_file_formats') }} : GIF, PNG, JPG<br>{{ trans('messages.products.max_file_size') }} : 5MB</em></span>
                            <label id="cloud_upload_error"></label>
                        </h2>                       
                        <input type="file" id="add-product-imagevideo" name="upload-file[]" multiple="true" style="display:none" accept="image/*">

                        <ul id="js-photo-grid" class="additional ui-sortable row  sortable">

                            <li ng-repeat="item in photos_list" class="photo col-lg-3 col-md-4 p-0 m-3 item" data-id="@{{ $index }}" data-index="0" draggable="true" style="display: list-item;" ng-mouseover="over_first_photo($index)" ng-mouseout="out_first_photo($index)">
                                    <div class="photo-size photo-drag-target js-photo-link" id="photo-@{{ $index }}"></div>
                                    <a class="preview" href="#">
                                        <!--   {!! Html::image('image/products/@{{ item.product_id }}/@{{ item.image_name }}', '', ['class' => 'img-responsive-height']) !!} -->
                                        <img class="img-responsive-height" ng-src="@{{ item.images_name }}">
                                    </a>
                                    <button type="button" data-toggle="modal" data-target="#js-error" data-photo-id="@{{ $index }}" ng-click="delete_photo(item,item.id,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_descrip') }}')" class="delete-photo-btn overlay-btn js-delete-photo-btn">
                                        <i class="icon-trash" style="vertical-align: middle;display: inline-flex;"></i>
                                    </button>
                               
                            </li>
                            <li class="add new col-lg-4 col-md-4 p-0 m-3" id="add-image">
                                <a href="#"></a>
                            </li>
                            <input type="file" class="d-none" name="photos[]" multiple="true" id="upload_photos2" accept="image/*">
                        </ul>
                         <label id="image_upload_error" style="display: none;">{{ trans('messages.products.image_upload_error') }}</label>
                  <label id="cloud_upload_error"></label>
                    </div>
                    <div class="wrapper">
                         <h2 class="stit">{{ trans('messages.products.price_details') }}</h2>
                       <div class="p-3 row">
                        <div class="form-group col-lg-12">
                                <label class="label font-weight-bold" for="input_default_currency">{{ trans('messages.products.currency') }}</label>
                                <select class="form-control" name="default_currency" id="input_default_currency" @if($errors->has('default_currency')) border-color: #a92225 !important; @endif ">
                                    @foreach($currency as $cur)
                                    <option data-to-rate="{{ $cur->rate }}" data-currency-code="{{ $cur->code }}" data-currency-symbol="{{ $cur->original_symbol }}" {{($product_currency==$cur->code)?'selected':''}} value="{{$cur->code}}">{{$cur->code}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-lg-12 d-flex p-0 flex-wrap">
                            <div class="form-group col-lg-4">
                                     <label class="label font-weight-bold" for="price">{{ trans('messages.products.price') }}</label>
                                    <input class="text form-control cc_symbol" name="price" ng-change="updateDiscount()" type="text" placeholder="{{ @$product_symbol }}" id="price" ng-model="price" limit-to="9" numbers-only>

                                    <div class="selectable-option cls_seletable_op">
                                        <input type="checkbox" name="check_sale" id="check-sale">
                                        <label for="check-sale">{{ trans('messages.products.product_on_sale') }}</label>
                                    </div>
                            </div>
                                <div class="form-group col-lg-4 retail" style="display: none;">
                                     <label class="label font-weight-bold" >{{ trans('messages.products.retail_price') }}</label>
                                    <input class="text form-control cc_symbol" style="width: 100%;height: 34px;" ng-change="updateDiscount()" name="retail_price" type="text" placeholder="{{ @$product_symbol }}" id="retail_price" ng-model="retail_price" limit-to="9" numbers-only>
                                </div>
                                <div class="form-group col-lg-4 discount" style="display: none;">
                                    <label class="label font-weight-bold">{{ trans('messages.products.discount') }}</label>

                                    <span class="pro_discountspan cls_inputsmall">
                                     <input class="text form-control" name="discount" style="width: 100%;height: 34px" type="text" placeholder="" readonly="readonly" id="discount" value="" ng-model="discount"  limit-to="9" numbers-only>
                                     <small>% {{ trans('messages.products.off') }}</small>
                                    </span>
                                </div>
                           </div>
                            <div class="form-group col-lg-3">
                                  
                                  <label class="label font-weight-bold">{{ trans('messages.products.length') }}</label>
                                <span class="pro_lengthspan cls_inputsmall">
                                <input class="text form-control" name="length" type="text" id="length" ng-model="length"  valid-number>
                                <small>{{ trans('messages.products.inch') }}</small>
                                </span>
                            </div>
                            <div class="form-group col-lg-3">
                                <label class="label font-weight-bold">{{ trans('messages.products.width') }}</label>
                                <span class="pro_widthspan cls_inputsmall">
                                <input class="text form-control" name="width" type="text"  id="width" ng-model="width" valid-number>
                                <small>{{ trans('messages.products.inch') }}</small>
                                </span>
                            </div>
                            <div class="form-group col-lg-3">
                                <label class="label font-weight-bold">{{ trans('messages.products.height') }}</label>
                                <span class="pro_heightspan cls_inputsmall">
                                <input class="text form-control" name="height" type="text"  id="height" ng-model="height" valid-number>
                                <small>{{ trans('messages.products.inch') }}</small>
                                </span>
                            </div>
                            <div class="form-group col-lg-3">
                                <label class="label font-weight-bold">{{ trans('messages.products.weight') }}</label>
                                <span class="prowidthspan cls_inputsmall">
                                <input class="text form-control" name="weight" type="text"  id="weight" ng-model="weight" valid-number>
                                <small>{{ trans('messages.products.lbs') }}</small>
                                </span>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="label font-weight-bold">SKU ({{ trans('messages.products.stock_keeping_unit') }})</label>
                                <input class="text form-control" name="sku_stock" type="text" id="sku" ng-model="sku">

                            </div>
                        </div>
                    </div>
                    <div class="wrapper">
                         <h2 class="stit">{{ trans('messages.products.inventory') }}</h2>
                       <div class="p-3 row">
                            <div class="form-group col-lg-6">
                                 <label class="label font-weight-bold">{{ trans('messages.products.total_quantity') }}</label>
                                <input class="text form-control" name="total_quantity" type="text" limit-to="4" maxlength="4" id="total_quantity" only-number ng-model="total_quantity">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="label font-weight-bold">{{ trans('messages.products.sold') }}</label>
                                <input class="text form-control" name="sold" value="0" type="text" readonly="readonly" id="sold" ng-model="sold">
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="label font-weight-bold">{{ trans('messages.products.add_product_option') }}</label>
                                <input type="text form-control" id="input-tags" class="demo-default">
                                <div class="added space_top_1 table_adjust pro_opt_table" style="" ng-show="product_options.length">
                                    <div class="detail" style="">
                                        <table class="cls_mtable">
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
                                                    <th class="name">{{ trans('messages.products.option') }}</th>
                                                    <th class="sku">SKU</th>
                                                    <th class="qty">{{ trans('messages.products.quantity') }}</th>
                                                    <th class="sold">{{ trans('messages.products.sold') }}</th>
                                                    <th class="price">{{ trans('messages.products.price') }}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="">
                                                <tr ng-repeat="item in product_options" ng-init="item.index=$index">
                                                    <td>
                                                        <input type="hidden" name="product_option_id[]" value="@{{ $index }}">
                                                        <div class="modal fade" id="product_option_extra_@{{ $index }}" role="dialog">
                                                            <div class="modal-dialog">
                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">{{ trans('messages.products.option_name') }}</h5>
                                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="data-frm fixed_summary option row">
                                                                            <div class="col-lg-6 form-group">
                                                                                <label class="label">{{ trans('messages.products.add_item_option') }}</label>
                                                                                <input type="text" class="text form-control" readonly="readonly" ng-model="product_options[$index].option_name" value="@{{ item.option_name }}">
                                                                            </div>
                                                                            <div class="col-lg-6 form-group">
                                                                                <label class="label">{{ trans('messages.products.quantity') }}</label><span class="help-inline text-danger" id='required_qty_@{{$index}}' style="display: none"> {{ trans('messages.products.field_required') }} </span>
                                                                                <span class="sync">
                                                                                <input type="text" ng-model="product_options[$index].option_qty"  class="text form-control"  name="quantity" limit-to="4" value="@{{ item.quantity }}" placeholder="∞" only-number>
                                                                                <button class="btn-switch" id="opt_sync_qty" style="display:none"><span class="on">On</span><span class="off">Off</span></button>
                                                                                <label for="opt_sync_qty" style="display:none">Sync</label>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="data-frm fixed_summary row d-flex p-0 flex-wrap">
                                                                    <div class="col-lg-4 form-group">
                                                                        <label class="label">{{ trans('messages.products.price') }}</label>
                                                                        <input class="cc_symbol text form-control ng-pristine ng-untouched ng-valid" name="option_price[]"type="text" placeholder="{{ @$product_symbol }}" ng-change="updateOptionDiscount($index)" ng-model="product_options[$index].option_price" limit-to="9" numbers-only>
                                                                        <div class="selectable-option cls_seletable_op">
                                                                        <input type="checkbox" data_id="" class="check_sale_option check_sale_option_@{{ $index }}" name="product_option_check_sale[@{{ $index }}]" data="@{{ $index }}" value="@{{ $index }}">
                                                                        <label  for="check-sale" style="font-size: 11px;">{{ trans('messages.products.product_on_sale') }}</label>
                                                                        </div>
                                                                        <span class="help-inline text-danger" id='required_price_@{{$index}}' style="display: none">{{ trans('messages.products.field_required') }}  <br/></span>

                                                                        <span class="help-inline text-danger" id='required_price_greater_@{{$index}}' style="display: none">{{ trans('messages.products.retain_greater') }} <br/></span>
                                                                        <span class="help-inline text-danger" id='required_price_less_@{{$index}}' style="display: none"></span>
                                                                    </div>
                                                                    <div class="col-lg-4 form-group retail retail_@{{ $index }}" style="display: none;">
                                                                        <label for="retail_price" class="label">{{ trans('messages.products.retail_price') }}</label>
                                                                        <input class="cc_symbol text ng-pristine ng-untouched ng-valid form-control" ng-change="updateOptionDiscount($index)" name="product_option_retail_price[]" type="text" placeholder="{{ @$product_symbol }}" id="retail_price" ng-model="product_options[$index].retail_price" limit-to="9" numbers-only>
                                                                    </div>
                                                                    <div class="col-lg-4  discount discount_@{{ $index }}" style="display: none;">
                                                                        <label for="retail_price" class="label">{{ trans('messages.products.discount') }}</label>
                                                                        <span class="discountspan cls_inputsmall">
                                                                        <input class="text form-control ng-pristine ng-untouched ng-valid" name="product_option_discount[]" style="width: 100%;height: 34px" type="text" placeholder="" readonly="readonly" id="discount" value="" ng-model="product_options[$index].option_discount">
                                                                        <small>% {{ trans('messages.products.off') }}</small>
                                                                        </span>
                                                                        </div>
                        

                                                                            </div>

                                                                        
                                                                        <!-- <div class="data-frm fixed_summary ">
                                                                        <div class="popular-head images-add">
                                                                        <h3 class="flt-left" style="text-transform:capitalize;font-size: 12px !important;">Images
                                                                        <span class="tooltip-custom"><i class="icon"></i> <em style="margin-left: -108px;"><b>Images</b> Minimum resolution: 640x640<br>Supported file formats: GIF, PNG, JPG<br>Max file size: 5MB</em></span></h3>
                                                                        <label id="image_upload_error_@{{ $index }}" class="image_upload_option_error">Please Select valid image</label>
                                                                        </div>
                                                                        <input type="file" class="add-product-option-imagevideo"  id="add-product-option-imagevideo_@{{ $index }}" data="@{{ $index }}" data-option="@{{ $index }}" data-db="@{{ item.id }}" name="upload-option-file[]" multiple="true" style="display:none" accept="image/*">

                                                                        <ul id="js-option-photo-grid_@{{ $index }}" class="additional ui-sortable row  sortable">

                                                                        <li ng-repeat="photo_item in photos_option_list[$index]" class="add col-lg-4 col-md-6 row-space-4" data-id="@{{ photo_$index }}" data-index="0" draggable="true" style="display: list-item;" ng-mouseover="over_first_photo($index)" ng-mouseout="out_first_photo($index)">
                                                                            <div class="panel photo-item">

                                                                              <div class="photo-size photo-drag-target js-photo-link" id="photo-@{{ $index }}"></div>
                                                                              <a class="media-photo media-photo-block text-center photo-size" href="#">
                                                                              {!! Html::image('image/products/@{{ photo_item.product_id }}/options/@{{ photo_item.product_option_id }}/@{{ photo_item.image_name }}', '', ['class' => 'img-responsive-height']) !!}
                                                                              </a>
                                                                              <button type="button" data-toggle="modal" data-target="#js-error" data-photo-id="@{{ $index }}" ng-click="delete_photo_option(photo_item,$parent.$index,$index,photo_item.product_option_id,photo_item.id,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_descrip') }}')" class="delete-photo-btn overlay-btn js-delete-photo-btn">
                                                                                <i class="fa fa-trash"></i>
                                                                              </button>
                                                                            </div>
                                                                        </li>
                                                                        <li class="add col-lg-4 col-md-6 row-space-4 add-image-option" data="@{{ $index }}" >
                                                                            <a href="#"></a>
                                                                        </li>

                                                                        <input type="file" class="hide" name="photos[]" multiple="true" id="upload_photos2" accept="image/*">

                                                                        </ul>

                                                                        </div> -->
                                                                        <div class="data-frm fixed_summary row p-3">
                                                                            <div class="col-lg-6 form-group">
                                                                                <label class="label">{{ trans('messages.products.length') }}</label>
                                                                                <span class="unit cls_inputsmall"><input type="text" valid-number ng-model="product_options[$index].length" name="product_option_length[]" class="text form-control" value="" >
                                                                                <small>{{ trans('messages.products.inch') }}</small></span>
                                                                            </div>
                                                                            <div class="col-lg-6 form-group">
                                                                                <label class="label">{{ trans('messages.products.width') }}</label>
                                                                                <span class="unit cls_inputsmall"><input type="text" ng-model="product_options[$index].width" name="product_option_width[]" class="text form-control" value="" valid-number>
                                                                                <small>{{ trans('messages.products.inch') }}</small></span>
                                                                            </div>
                                                                            <div class="col-lg-6 form-group">
                                                                                <label class="label">{{ trans('messages.products.height') }}</label>
                                                                                <span class="unit cls_inputsmall"><input type="text" ng-model="product_options[$index].height" name="product_option_height[]" class="text form-control" value="" valid-number>
                                                                                <small>{{ trans('messages.products.inch') }}</small></span>
                                                                            </div>
                                                                            <div class="col-lg-6 form-group">
                                                                                <label class="label">{{ trans('messages.products.weight') }}</label>
                                                                                <span class="unit cls_inputsmall"><input type="text" ng-model="product_options[$index].weight" name="product_option_weight[]" class="text form-control" value="" valid-number>
                                                                                <small>{{ trans('messages.products.lbs') }}</small></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-12">
                                                                            <p class="error-comment" style="display:none;">Please check the highlighted fields above for errors</p>
                                                                            <p class="marked_soldout">
                                                                                <input type="checkbox" id="marked_soldout_options_@{{ $index }}" class="marked_soldout_option_@{{ $index }}" value="@{{ $index }}" name="product_option_soldout[]">
                                                                                <label for="marked_soldout_option">{{ trans('messages.products.mark_sold_out') }}</label>
                                                                            </p>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" id="delete_option_button_@{{ item.option_name }}" data-dismiss="modal" data-toggle="modal" data-target="#js-error" ng-click="delete_option(item,$index,item.id,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_option') }}')" class=" btn btn-dark btn-delete" style="float:left"><i class="fa fa-trash">&nbsp;</i>{{ trans('messages.products.delete') }}</button>
                                                                        <button type="button" class="btn btn-light" data-dismiss="modal">{{ trans('messages.products.cancel') }}</button>
                                                                        <button type="button" class="btn btn-primary" ng-click="saveOption($index)">{{ trans('messages.products.save') }}</button>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <a href="#" class="btn-reposition disabled d-none"></a>
                                                        <input type="text" name="product_option[]" class="text form-control name" readonly="readonly" value="@{{ item.option_name }}" ng-model="product_options[$index].option_name">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="product_option_sku[]" class="text form-control sku" value="@{{ item.sku }}" ng-model="item.sku">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="product_option_qty[]" limit-to="4" class="text qty form-control" placeholder="∞" value="@{{ item.quantity }}" ng-model="product_options[$index].total_quantity" ng-change="product_options[$index].option_qty=product_options[$index].total_quantity" ng-keyup="update_main_quantity()" only-number>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="product_option_sold[]" class="text sold form-control" readonly="readonly" value="@{{ item.sold }}" ng-model="item.sold">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="product_option_price[]" class="text price form-control" id="option_price_@{{ $index }}" ng-init="product_options[$index].price = item.original_price" ng-change="updateOptionprice($index)" ng-model="product_options[$index].price" limit-to="9" numbers-only>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#product_option_extra_@{{ $index }}" class="btn-edit editbtn">
                                                            <i class="icon-pencil" aria-hidden="true"></i>
                                                        </a>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapper">
                         <h2 class="stit">{{ trans('messages.products.shipping') }}</h2>
                       <div class="p-3 row">
                           
                            <div class="back-white col-lg-12 nopad bor-bot-ash shipping-container res_addpro">
                                <div class="add-shipping">
                                    <div class="shipping_rate">
                                        <label class="label font-weight-bold">{{ trans('messages.products.shipping_calculation') }} <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.products.shipping_calculation') }}</b> {{ trans('messages.products.shipping_calculation_desc') }}</em></span></label>
                                        <input type="hidden" name="shipping_type" id="shipping_type" value="@{{ shipping_type }}">
                                        <ul class="checked-option d-flex flex-wrap my-3">
                                            <li class="mr-3">
                                                <a class="btn-light btn-sm btn" style="margin: 0px;" id="shipping-type_free">{{ trans('messages.products.free_shipping') }}</a>
                                            </li>
                                            <li class="checked">
                                                 <a href class="btn-dark btn-sm btn" id="shipping-type_flat">{{ trans('messages.products.flat_rates') }}</a>
                                            </li>

                                           <!--  <li class="btn-light btn mr-3">
                                                <label style="margin: 0px;" id="shipping-type_free">{{ trans('messages.products.free_shipping') }}</label>
                                            </li>
                                            <li class="checked btn btn-dark">
                                                <label style="margin: 0px;" id="shipping-type_flat">{{ trans('messages.products.flat_rates') }}</label>
                                            </li> -->
                                        </ul>
                                    </div>
                                    <div class="shipping_free" style="display: none;">
                                        <p class="">
                                            <input id="shipping_us_free" checked="" type="checkbox">
                                            <label for="shipping_us_free">Free shipping within USA</label>
                                        </p>
                                    </div>
                                    <div class="calculation flat" ng-init="ships_from='{{ $countryName }}';manufacture_country='{{ $countryName }}'">
                                        <p class="default ships_from">
                                            <label class="label font-weight-bold">{{ trans('messages.products.shipping_location') }} <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -100px;"><b>{{ trans('messages.products.shipping_location') }}</b> {{ trans('messages.products.shipping_location_desc') }}</em></span></label>

                                            {!! Form::select('ships_from', $country, $countryName, ['class' => 'select-country form-control', 'ng-model'=>'ships_from']) !!}
                                        </p>
                                        <p class="shipping_rate new">
                                            <label class="label font-weight-bold">{{ trans('messages.products.ships_to') }} <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.products.ships_to') }}</b> {{ trans('messages.products.ships_to_desc') }}</em></span></label>
                                            {!! Form::select('ships_to[]', $country, '', ['id' => 'ships_to', 'class' => 'select-country', 'multiple' => true, 'ng-model'=>'ships_to']) !!}
                                        </p>
                                        <p class="show_rate" style="display: none;">
                                            <label class="label font-weight-bold">{{ trans('messages.products.shipping_profile') }}</label>
                                            <select class="tiered-rates"></select>
                                            <a href="#" class="view_rate" target="_blank">{{ trans('messages.products.locations_rates') }}</a>
                                        </p>

                                        <div class="multiple-shipping-frm custom-shipping">
                                            <table class="cls_mtable">
                                                <colgroup>
                                                    <col width="165">
                                                    <col width="110">
                                                    <col width="110">
                                                    <col width="*">
                                                    </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('messages.products.destination') }}</th>
                                                        <th class="custom-shipping-field"  ng-hide="hide_shipping_column">{{ trans('messages.products.charge') }}</th>
                                                        <th class="custom-shipping-field" style="width: 24%" ng-hide="hide_shipping_column">{{ trans('messages.products.incremental_fee') }} <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>
                                                {{ trans('messages.products.incremental_fee') }} </b> {{ trans('messages.products.incremental_fee_desc') }}</em></span></th>
                                                        <th>{{ trans('messages.products.shipping_window') }} <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.products.shipping_window') }}</b> {{ trans('messages.products.shipping_window_desc') }}</em></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="item in shipping" ng-init="item.index = $index">
                                                        <td>@{{ item.ships_to }}</td>
                                                        <td class="custom-shipping-field" ng-hide="hide_shipping_column">
                                                            <input class="cc_symbol form-control text custom-charge-domestic" name="custom_charge_domestic[]" valid-number placeholder="{{ @$product_symbol }}" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" value="" type="text" id="charge_@{{ $index }}" ng-model="item.original_charge" valid-number>
                                                        </td>
                                                        <td class="custom-shipping-field" ng-hide="hide_shipping_column">
                                                            <input class="cc_symbol text form-control col-md-6 p-0 custom-incremental-domestic" valid-number name="custom_incremental_domestic[]" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="{{ @$product_symbol }}" type="text" id="incremental_fee_@{{ $index }}" ng-model="item.original_incremental_fee">
                                                        </td>
                                                        <td class="d-flex align-items-center">
                                                            <input maxlength="3" valid-number limit-to="8" class="text form-control expected_delivery_day_1" name="expected_delivery_day_1[]" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" value="" type="text" id="start_window_@{{ $index }}" ng-model="item.start_window"> -
                                                            <input maxlength="3" valid-number limit-to="8" class="text form-control col-md-6 p-0 start-day expected_delivery_day_2" name="expected_delivery_day_2[]" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" value="" type="text" id="end_window_@{{ $index }}" ng-model="item.end_window">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="country">
                                            <label class="label font-weight-bold">{{ trans('messages.products.country_manufacture') }}</label>
                                            {!! Form::select('manufacture_country', $country, $countryName, ['class' => 'select-country form-control', 'ng-model'=>'manufacture_country']) !!}
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapper">
                         <h2 class="stit">{{ trans('messages.products.return_exchange') }}</h2>
                       <div class="p-3 row">
                        <div class="back-white col-lg-12 nopad bor-bot-ash wrapper-exchange res_addpro">
                            <div class="policy" ng-init="return_policy={{@$return_policy_value}};exchange_policy={{@$return_policy_value}}">
                                <div class="default">
                                    <div class="policy_return form-group">
                                        <label class="label font-weight-bold">{{ trans('messages.products.return_policy') }}</label>
                                        <select id="return_policy" name="return_policy" ng-model="return_policy" class="form-control" ng-init="return_policy={{@$return_policy_value}}">
                                            @foreach($return_policy as $rpolicy)
                                            <option value="{{ $rpolicy->id }}">{{ $rpolicy->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="check_rul">
                                    <input id="use_exchange" name="use_exchange" type="checkbox" checked>
                                    <label class="check-label font-weight-bold" style="font-size: 11px;" for="use_exchange">{{ trans('messages.products.rules_exchanges') }}</label>
                                </div>
                                <div class="use_exchange form-group" style="display: none">
                                    <label class="label font-weight-bold" style="margin-left: 10px;">{{ trans('messages.products.exchange_policy') }}</label>
                                    <select class="exchange-policy form-control" name="exchange_policy" ng-model="exchange_policy" ng-init="exchange_policy={{@$return_policy_value}}" style="margin-bottom: 10px;margin-left: 10px;width: 305px;">
                                        @foreach($return_policy as $rpolicy)
                                        <option value="{{ $rpolicy->id }}">{{ $rpolicy->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="comment return_exchange_policy_description form-group">
                                    <textarea id="return_exchange_policy_description" rows="5" onfocus="$(this).closest('.comment').addClass('focus')" onblur="$(this).closest('.comment').removeClass('focus')" name="return_exchange_policy_description" class="text form-control" default-policy-desc="This item is non-returnable and non-exchangeable." custom-policy-desc="" ng-model="policy_description">{{ trans('messages.home.no_returns_desc') }}</textarea>
                                    <button type="button" ng-click="policy_description = null" class="btn-light btn">{{ trans('messages.products.reset') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="add-prod-sidebar cls_maddproduct col-lg-4">
                <div class="wrapper cls_status">
                    <h2 class="stit">{{ trans('messages.products.status') }}</h2>
                    <ul class="status p-3">
                        <li>
                            <input type="hidden" name="status" id="product_status" value="active">
                            <button type="button" id="product_status_btn" class="product-color product_status btn-switch" onclick="$(this).toggleClass('on')"><span class="on"></span><span class="off"></span></button>
                            <label for="product_status_btn">{{ trans('messages.products.active') }}</label>
                            <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.products.active') }}/{{ trans('messages.products.inactive') }}</b> {{ trans('messages.products.status_desc') }}</em></span>
                        </li>

                        <li>
                            <input type="hidden" name="sold_out" id="soldout_status" value="No">
                            <button type="button" id="soldout_status_btn" class="product-color soldout_status btn-switch on" onclick="$(this).toggleClass('on')"><span class="on"></span><span class="off"></span></button>
                            <label for="soldout_status_btn">{{ trans('messages.products.mark_sold_out') }}</label>
                        </li>
                        @if($cod_status=='Yes')
                        <li>
                            <button type="button" id="cashdelivery_status_btn" class="product-color cashdelivery_status btn-switch on" onclick="$(this).toggleClass('on')"><span class="on"></span><span class="off"></span></button>
                            <label for="cashdelivery_status_btn">{{ trans('messages.products.cash_on_delivery') }}</label>
                        </li>
                        @endif @if($cos_status=='Yes')
                        <li>
                            <button type="button" id="cashstore_status_btn" class="product-color cashstore_status btn-switch on" onclick="$(this).toggleClass('on')"><span class="on"></span><span class="off"></span></button>
                            <label for="cashstore_status_btn">{{ trans('messages.products.cash_on_store') }}</label>
                        </li>
                        @endif
                        <input type="hidden" name="cash_on_delivery" id="cashdelivery_status" value="No">
                        <input type="hidden" name="cash_on_store" id="cashdstore_status" value="No">
                    </ul>
                    <ul class="discount-override" style="display: none;">
                        <li>
                            <label>Discount Override</label>
                            This item is currently on <span id="discount_override"></span>% discount as set by {{ $site_name }}. The discount will be applied against your retail price automatically.
                        </li>
                    </ul>
                </div>
                <div class="wrapper  cls_organize">
                    <h2 class="stit">{{ trans('messages.products.organize') }}</h2>
                    <div class="category add-category p-3">
                        <label class="label font-weight-bold">{{ trans('messages.products.category') }} <span class="tooltip1"><i class="icon"></i> <em style="margin-left: -100px;"><b>{{ trans('messages.products.category') }} </b> {{ trans('messages.products.category_desc') }} </em></span></label>

                        <!-- <div class="select-category category_ids multi-text">
                            <div class="select-lists">
                                <a href="#" class="selector">Select Category</a>
                                <div class="lists">
                                    <span class="trick"></span>
                                    <dl>
                                        <dt><b>Select Category</b> <a href="#" class="add" style="display:none;"><em>Add <small></small></em></a></dt>
                                        <dd style="display:none;" class="recent">
                                            <b>Recent</b>
                                        </dd>
                                        <dd><div class="category-lists" style="width: 400%; left: 0px; height: 0px;">
                                            <ul class="step1">
                                                <li><a href="#" data-idx="1">Men</a></li>
                                                <li><a href="#" data-idx="2">Women</a></li>
                                                <li><a href="#" data-idx="3">Kids</a></li>
                                                <li><a href="#" data-idx="4">Pets</a></li>
                                                <li><a href="#" data-idx="5">Home</a></li>
                                                <li><a href="#" data-idx="6">Gadgets</a></li>
                                                <li><a href="#" data-idx="7">Art</a></li>
                                                <li><a href="#" data-idx="8">Food</a></li>
                                                <li><a href="#" data-idx="9">Media</a></li>
                                                <li><a href="#" data-idx="11">Architecture</a></li>
                                                <li><a href="#" data-idx="12">Travel &amp; Destinations</a></li>
                                                <li><a href="#" data-idx="13">Sports &amp; Outdoors</a></li>
                                                <li><a href="#" data-idx="14">DIY &amp; Crafts</a></li>
                                                <li><a href="#" data-idx="15">Workspace</a></li>
                                                <li><a href="#" data-idx="16">Cars &amp; Vehicles</a></li>                                            <li><a href="#" data-idx="10">Other</a></li>
                                            </ul>
                                            <ul class="step2"></ul>
                                            <ul class="step3"></ul>
                                            <ul class="step4"></ul>
                                        </div></dd>
                                    </dl>
                                </div>
                            </div>
                            <ul class="step-category" style="display:none;">

                            </ul>
                        </div> -->

                        <div class="select-category  category_ids multi-text nt_selet">
                            <input type="hidden" name="category_id" id="category_id" value="">
                            <input type="hidden" name="category_path" id="category_path" value="">
                            <button id="drilldown" class="btn btn-block btn-light drilldown Categorydrop" data-toggle="dropdown">
                                <span class="text selecttext" placeholder="{{ trans('messages.merchant.select_category') }}" style="font-size: 14px;"></span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{ Form::close() }}
        <div class="modal fade" id="js-error" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Modal Header</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel js-delete-close" data-toggle="modal" data-target="" data-dismiss="modal">{{ trans('messages.lys.close') }}</button>
                        <button class="btn btn-primary js-delete-photo-confirm hide" data-option="0" data-photo="" data-option-id="" del-parent="" data-dismiss="modal" data-id="">
                            {{ trans('messages.lys.delete') }}
                        </button>
                    </div>

                </div>

            </div>
        </div>
        <div class="modal fade" id="video_upload_popup" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('messages.products.upload_video') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <label class="add_video_mp4 d-block">{{ trans('messages.products.upload') }} .mp4</label>
                        <input class="hide" type="file" name="product_video_mp4" id="add_product_video_mp4">
                        <!-- <span id="mp4_filename"></span> -->
                        <br />
                        <label id="video_upload_error_mp4" class="video_upload_error">{{ trans('messages.products.video_upload_error') }} - .mp4</label>
                        </p>

                        <label class="add_video_webm d-block">{{ trans('messages.products.upload') }} .webm</label>
                        <input class="hide" type="file" name="product_video_webm" id="add_product_video_webm">
                        <!-- <span id="webm_filename"></span> -->
                        <br />
                        <label id="video_upload_error_webm" class="video_upload_error">{{ trans('messages.products.video_upload_error') }} - .webm</label>
                        </p>
                        <label id="video_cloud_upload_error"></label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-toggle="modal" data-target="" data-dismiss="modal">{{ trans('messages.lys.close') }}</button>
                        <button type="button" class="btn btn-primary" id="add-video-btn">
                            {{ trans('messages.products.upload') }}
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    </div>
@stop 
@push('scripts')
<script type="text/javascript">
    var data = {!!json_encode($categories) !!};
</script>
@endpush