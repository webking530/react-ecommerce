@extends('merchant_template') @section('main')
<div class="cls_dashmain">
<div class="container container-pad cls_msettings" ng-controller="brand_image" ng-cloak>
    <div class="cls_allproduct pt-4">
    
            <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
                <div>
                  <ol class="breadcrumb">
                        <li class="breadcrumb-item active">{{ trans('messages.header.settings') }}</li>
                      </ol>
                </div>
            </div>
    
    <div class="csv d-flex row p-0">
        @include('common.settings_subheader_logged')
        <div class="col-lg-9 col-12 nopad right-sett mt-3 mt-lg-0">
                <div class="cls_setting1" >
         {!! Form::open(['action' => 'MerchantController@update_brand', 'class' => 'basic-form', 'data-action' => 'basic', 'id' => 'basic_form', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
            <div class="back-white csv_right nopad">
                <h2 class="csv_tit">{{ trans('messages.merchant.brand_image') }}
                <label id="brand_error"></label>
                </h2>

                <div class="d-flex flex-wrap pt-4 nopad bor-bot-ash">
                    <div class="col-lg-3 col-12">
                        <h2 class="stit">{{ trans('messages.merchant.store_details') }}</h2>
                    </div>
                    <div class="col-lg-9">
                        <div class="account-detail" ng-cloak>
                            <div class="form-group">
                                <label class="label">{{ trans('messages.merchant.store_name') }}</label>
                                {!! Form::text('store_name', $merchant_store->store_name, ['id' => 'store_name', 'class' => 'text form-control']) !!}
                                <input type="hidden" name="merchant_id" value="{{ Auth::id() }}" id="merchant_id"> @if ($errors->has('store_name'))
                                <span class="help-inline" style="color:red">
                                {{ $errors->first('store_name') }}
                            </span> @endif
                            </div>

                            <div class="form-group cls_maddproduct">
                                <label class="label">{{ trans('messages.merchant.tagline') }} &nbsp;<span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.merchant.tagline') }}</b> {{ trans('messages.merchant.tagline_desc') }}</em></span></label>
                                {!! Form::text('tagline', @$merchant_store->tagline, ['id' => 'store_tagline', 'class' => 'text form-control']) !!} @if ($errors->has('tagline'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('tagline') }}
                                </span> @endif
                            </div>

                            <div class="form-group"><label class="label">{{ trans('messages.merchant.description') }}</label>
                                <textarea id="store_description" name="store_description">
                                    {{ $merchant_store->description }}
                                </textarea>
                            </div>
                        </div>

                    </div>

                </div>

                <!--store logo-->
                <div class="d-flex flex-wrap py-4 nopad bor-bot-ash border-top border-light">
                    <div class="col-lg-3 col-12">
                        <h2 class="stit">{{ trans('messages.merchant.store_logo') }}</h2>
                    </div>
                    <div class="col-lg-9">
                        <label id="store_logo_upload_error">
                            <span class="invalid_image" style="display: none">{{ trans('messages.merchant.invalid_image_file') }} </span>
                            <span class="max_file" style="display:none">{{ trans('messages.merchant.maximum_file_size') }} </span>
                        </label>
                        <label id="logo_upload_error"></label>
                        <div class="form-group cls_maddproduct">
                            <label class="label">{{ trans('messages.merchant.logo_image') }} &nbsp;<span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.merchant.logo_image') }}</b> {{ trans('messages.merchant.logo_image_desc') }}
                        <br/><br/>
                        {{ trans('messages.merchant.logo_type') }} JPG, GIF or PNG
                        {{ trans('messages.merchant.logo_file_size') }} 5MB.</em></span></label>
                        </div>
                        <input type="hidden" id="logoimg" name="logoimg">
                        <input type="hidden" id="delete_log_img" name="delete_log_img">
                        <input type="file" id="add-store-logo" name="upload-logo[]" style="display:none" accept="image/*">

                        <!-- @{{ store_logo | json }} -->

                        <ul id="js-photo-logo" class="additional additional-store-logo ui-sortable row  sortable">

                            <li ng-repeat="slogo in store_logo" class="main add d-flex flex-wrap align-items-center col-lg-12 col-md-12 my-3 p-0" data-id="@{{ $index }}" data-index="0" draggable="true" ng-mouseover="over_first_photo($index)" ng-mouseout="out_first_photo($index)">
                                <div class="panel photo-item mr-3">
                                    <div class="photo-size photo-drag-target js-photo-link" id="photo-@{{ $index }}"></div>
                                    <a class="media-photo media-photo-block text-center photo-size" href="#">
                                        <img ng-src="@{{ slogo.logo_img }}" class="img-responsive-height" height="112" width="180">
                                    </a>
                                </div>
                                <div id="next_store_logo" style="text-align: center;">
                                    <label class="next_logo">{{ trans('messages.merchant.change_logo') }}</label>
                                    <br>

                                    <button type="button" data-toggle="modal" data-target="#js-error-store" data-photo-id="@{{ $index }}" ng-click="delete_store_logo(slogo,slogo.user_id,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_descrip') }}')" class="btn btn-light">{{ trans('messages.merchant.delete_logo') }}</i>
                                    </button>

                                </div>
                            </li>

                            <li class="add new  col-lg-4 col-md-6 row-space-4 nopad" id="add-logo">
                                <a href="#"></a>
                            </li>

                            <input type="file" class="d-none" name="photos_logo[]" id="upload_photos2" accept="image/*">

                        </ul>

                    </div>
                </div>

                <!--Store header -->
                <div class="d-flex flex-wrap py-4 nopad bor-bot-ash border-top border-light">
                    <div class="col-lg-3 col-12">

                        <h2 class="stit">{{ trans('messages.merchant.store_header') }}</h2>
                        <input type="hidden" id="headerimg" name="headerimg">
                        <input type="hidden" id="delete_header_img" name="delete_header_img">
                    </div>
                    <div class="col-lg-9">
                        <label id="store_header_upload_error">
                            <span class="invalid_image" style="display: none">{{ trans('messages.merchant.invalid_image_file') }} </span>
                            <span class="max_file" style="display:none">{{ trans('messages.merchant.maximum_file_size') }} </span>
                        </label>
                        <label id="header_upload_error"></label>
                        <div class="form-group cls_maddproduct"><label class="label">{{ trans('messages.merchant.store_header') }} &nbsp;<span class="tooltip1"><i class="icon"></i> <em style="margin-left: -108px;"><b>{{ trans('messages.merchant.store_header') }}</b> {{ trans('messages.merchant.store_header_desc') }}</em></span></label>
                        </div>

                        <input type="file" id="add-store-header" name="upload-header[]" style="display:none" accept="image/*">

                        <!-- @{{ photos_list | json }} -->

                        <ul id="js-photo-header" class="additional additional-store-header ui-sortable row  sortable">

                            <li ng-repeat="sheader in store_header" class="main add d-flex flex-wrap align-items-center col-lg-12 col-md-12 my-3 p-0" data-id="@{{ $index }}"  draggable="true" ng-mouseover="over_first_photo($index)" ng-mouseout="out_first_photo($index)" ng-daa=@{{store_header.length}}>

                                <div class="panel photo-item mr-3">
                                    <div class="photo-size photo-drag-target js-photo-link" id="photo-@{{ $index }}"></div>
                                    <a class="media-photo media-photo-block text-center photo-size brand_img" href="#">
                                        <img ng-src="@{{ sheader.header_img }}" class="img-responsive-height" height="112" width="180" > 
                                    </a>
                                </div>
                                <div id="next_store_header" style="text-align: center;">
                                    <label>{{ trans('messages.merchant.change_header_image') }}</label>
                                    <br>
                                    <button type="button" data-toggle="modal" data-target="#js-error-store" data-photo-id="@{{ $index }}" ng-click="delete_store_header(sheader,sheader.user_id,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_descrip') }}')" class="btn btn-light">{{ trans('messages.merchant.delete_header_image') }}</button>
                                </div>
                            </li>

                            <li class="add new col-lg-4 col-md-6 row-space-4 nopad" id="add-header">
                                <a href="#"></a>
                            </li>

                            <input type="file" class="d-none" name="photos_header[]" id="upload_photos2" accept="image/*">

                        </ul>

                    </div>
                </div>

                <div class="btns-fix btn-area">
                    <button class="blue-btn btn-add" id="save_brand" type="submit">{{ trans('messages.merchant.save_changes') }}</button>
                </div>

            </div>
        </div>
        </form>
        <div id="popup_container" class="" style="display: none; opacity: 0; top: 0px;">

            <div id="content-pop" class="popup content-pop" style="margin-top: 5%; margin-left: 20%; display: none;">
                <p class="ltit">Import Products</p>
                <dl class="docs">
                    <dt>You can upload a CSV file to import products. Download and view a sample product CSV file. You can use this as a template for creating your CSV file. Just remember to remove the example products.</dt>
                    <dd>
                        <div class="table">
                            <table class="tb-type2 head">
                                <colgroup>
                                    <col width="370">
                                        <col width="136">
                                            <col width="*">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Requirement</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="scroll">
                                <table class="tb-type2">
                                    <colgroup>
                                        <col width="370">
                                            <col width="136">
                                                <col width="*">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <td class="para"><code>title</code></td>
                                            <td class="require">Required</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>description</code></td>
                                            <td class="require">Required</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>quantity</code></td>
                                            <td class="require"></td>
                                            <td>Default: Unlimited</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>price</code></td>
                                            <td class="require">Required</td>
                                            <td>in USD</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>retail_price</code></td>
                                            <td class="require">Optional</td>
                                            <td>in USD</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>image1_url</code>,<code>image2_url</code>,<code>image3_url</code></td>
                                            <td class="require">Required</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>categories</code></td>
                                            <td class="require">Required</td>
                                            <td>Separated by vertical bar "|"
                                                <br>
                                                <span class="tooltip">View categories</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>option_name1</code>,<code>option_quantity1</code>,<code>option_price1</code>
                                                <br>
                                                <code>option_name2</code>,<code>option_quantity2</code>,<code>option_price2</code>
                                                <br>
                                                <code>option_name3</code>,<code>option_quantity3</code>,<code>option_price3</code>
                                                <br>
                                                <code>option_name4</code>,<code>option_quantity4</code>,<code>option_price4</code>
                                                <br>
                                                <code>option_name5</code>,<code>option_quantity5</code>,<code>option_price5</code></td>
                                            <td class="require"></td>
                                            <td>Options are optional. Name and quantity must have value, or it will be ignored. Price can be omitted and it will use the item price.</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>charge_shipping</code></td>
                                            <td class="require">True / False</td>
                                            <td>Charge domestic shipping fee (default: true)</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>charge_international_shipping</code></td>
                                            <td class="require">True / False</td>
                                            <td>Charge international shipping fee (default: true)</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>international_shipping</code></td>
                                            <td class="require">True / False</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>us_window_start</code></td>
                                            <td class="require">Required</td>
                                            <td>Domestic shipping minimum window</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>us_window_end</code></td>
                                            <td class="require">Required</td>
                                            <td>Domestic shipping maximum window</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>intl_start</code></td>
                                            <td class="require">Required</td>
                                            <td>International shipping minimum window</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>intl_end</code></td>
                                            <td class="require">Required</td>
                                            <td>International shipping maximum window</td>
                                        </tr>

                                        <tr>
                                            <td class="para"><code>sale_start_date</code></td>
                                            <td class="require">Optional</td>
                                            <td>YYYY-MM-DD HH:MM:SS
                                                <br>Default is upload time.</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>prod_colors</code></td>
                                            <td class="require">Optional</td>
                                            <td>Colors separated by comma</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>prod_country_of_origin</code></td>
                                            <td class="require">Optional</td>
                                            <td>Country of origin (2-letter country code)</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>prod_length</code></td>
                                            <td class="require">Optional</td>
                                            <td>Length of the product in inch</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>prod_height</code></td>
                                            <td class="require">Optional</td>
                                            <td>Height of the product in inch</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>prod_width</code></td>
                                            <td class="require">Optional</td>
                                            <td>Width of the product in inch</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>prod_weight</code></td>
                                            <td class="require">Optional</td>
                                            <td>Weight of the product in pound</td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>seller_sku</code></td>
                                            <td class="require">Optional</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="para"><code>is_active</code></td>
                                            <td class="require">True / False</td>
                                            <td>Default: Activated</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <span class="tooltip trick" style="top: 281.133px; left: 516px;"><small>View category IDs</small><em>
                    1 - Men<br>
                    2 - Women<br>
                    3 - Kids<br>
                    4 - Pets<br>
                    5 - Home<br>
                    6 - Gadgets<br>
                    7 - Art<br>
                    8 - Food<br>
                    9 - Media<br>
                    10 - Other<br>
                    11 - Architecture<br>
                    12 - Travel &amp; Destinations<br>
                    13 - Sports &amp; Outdoors<br>
                    14 - DIY &amp; Crafts<br>
                    15 - Workspace<br>
                    16 - Cars &amp; Vehicles
                </em></span>
                        </div>
                    </dd>
                </dl>
                <button class="ly-close"><i class="ic-del-black"></i></button>
            </div>
            <div class="popup download_sellapp">
                <div class="inner">
                    <h3>Sell on the go <small>New</small></h3>
                    <p>Never miss a sale with the {{ $site_name }} Seller app.
                        <br> Remotely manage inventory, customers and orders.
                        <br>
                        <a href="/about/merchants/mobile" target="_blank">Learn more about the {{ $site_name }} Seller app</a></p>
                    <p class="download">
                        <a href="https://itunes.apple.com/us/app/Spiffy-seller/id961870454?ls=1&amp;mt=8" class="btn-ios" target="_blank">Download on the App Store</a>
                        <br> Or, <a href="#" onclick="$.dialog('send_app').open();return false;">send a link</a> to your iPhone to download the app.</p>
                </div>
            </div>
            <div class="popup send_app">
                <p class="tit">Send App Link</p>
                <fieldset class="frm">
                    <p>Get the {{ $site_name }} Seller app sent to your phone.</p>
                    <p class="country">
                        <select name="country_code" class="select-country">
                            <option value="AX">Aaland Islands</option>
                            <option value="AF">Afghanistan</option>
                            <option value="AL">Albania</option>
                            <option value="DZ">Algeria</option>

                        </select>
                    </p>
                    <p class="phone">
                        <input class="text input-phone" placeholder="Enter your Phone number" type="text">
                    </p>
                    <button class="btn-green btn-send">Send</button>
                </fieldset>
                <p class="notify">Data rates may apply. We will <b>not</b> store your number.</p>
            </div>
        </div>
    </div>
    <div class="modal fade" id="js-error-store" role="dialog">
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
                    <button type="button" class="btn btn-default js-delete-close" data-toggle="modal" data-target="" data-dismiss="modal">{{ trans('messages.lys.close') }}</button>
                    <button class="btn btn-primary js-delete-photo-confirm d-none" data-option="0" data-photo="" del-parent="" data-dismiss="modal" data-id="">
                        {{ trans('messages.lys.delete') }}
                    </button>
                </div>

            </div>

        </div>
    </div>

    @stop