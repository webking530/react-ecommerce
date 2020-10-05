@extends('merchant_template') @section('main')
<div class="cls_dashmain">
    <div class="container container-pad cls_msettings">
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
                    {!! Form::open(['action' => 'MerchantController@update_seller_profile', 'class' => 'basic-form', 'data-action' => 'basic', 'id' => 'basic_form', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
                    <div class="back-white csv_right nopad">
                        <h2 class="csv_tit">{{ trans('messages.merchant.basic') }}</h2>
                        <div class="d-flex pt-4 nopad bor-bot-ash flex-wrap">
                            <div class="col-lg-3 col-12">
                                <h2 class="stit">{{ trans('messages.merchant.your_details') }}</h2>
                            </div>
                            <div class="col-lg-9">
                                <div class="account-detail">
                                    <div class="form-group">
                                        <label class="label">{{ trans('messages.merchant.store_name') }}</label>
                                        {!! Form::text('store_name', @$user->store_name, ['id' => 'store_name', 'class' => 'text form-control']) !!}
                                        <input class="hidden d-none" name="userid" value="{{@$userid}}" type="text"> @if ($errors->has('store_name'))
                                        <span class="help-inline" style="color:red">
                                            {{ $errors->first('store_name') }}
                                          </span> @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="label">{{ trans('messages.merchant.full_name') }}</label> {!! Form::text('full_name', @$user->full_name, ['id' => 'full_name', 'class' => 'text form-control']) !!} @if ($errors->has('full_name'))
                                        <span class="help-inline" style="color:red">
                                            {{ $errors->first('full_name') }}
                                        </span> @endif
                                    </div>
                                    <div class="row ">
                                        <div class="form-group col-lg-6">
                                            <label class="label">{{ trans('messages.merchant.address') }}</label> {!! Form::text('address_line', @$user_address->address_line, ['id' => 'address_line', 'class' => 'text form-control']) !!} @if ($errors->has('address_line'))
                                            <span class="help-inline" style="color:red">
                                                {{ $errors->first('address_line') }}
                                            </span> @endif
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label class="label">{{ trans('messages.merchant.address_line') }}</label> {!! Form::text('address_line2', @$user_address->address_line2, ['id' => 'address_line2', 'class' => 'text form-control']) !!}
                                        </div>
                                    </div>
                                     <div class="row ">
                                         <div class="form-group col-lg-6">
                                            <label class="label">{{ trans('messages.merchant.city') }}</label> {!! Form::text('city', @$user_address->city, ['id' => 'city', 'class' => 'text form-control']) !!} @if ($errors->has('city'))
                                            <span class="help-inline" style="color:red">
                                                {{ $errors->first('city') }}
                                            </span> @endif
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label class="label">{{ trans('messages.merchant.state') }}</label> {!! Form::text('state', @$user_address->state, ['id' => 'state', 'class' => 'text form-control']) !!} @if ($errors->has('state'))
                                            <span class="help-inline" style="color:red">
                                                {{ $errors->first('state') }}
                                            </span> @endif
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="form-group col-lg-6">
                                            <label class="label">{{ trans('messages.merchant.postal_code') }}</label> {!! Form::text('postal_code', @$user_address->postal_code, ['id' => 'postal_code', 'class' => 'text form-control']) !!} @if ($errors->has('postal_code'))
                                            <span class="help-inline" style="color:red">
                                                {{ $errors->first('postal_code') }}
                                            </span> @endif
                                        </div>
                                         <div class="form-group col-lg-6">
                                            <label class="label">{{ trans('messages.merchant.country') }}</label>
                                            <select name="country" class="select-boxes select-country form-control">
                                                @foreach ($countrys as $key => $country)
                                                <option value="{{$country->short_name}}" {{ ( @$user_address->country == $country->short_name ) ? 'selected' : ''}} >{{$country->long_name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('country'))
                                            <span class="help-inline" style="color:red">
                                                {{ $errors->first('country') }}
                                            </span> @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">{{ trans('messages.merchant.phone') }}</label>
                                        
                                            {!! Form::text('phone_number', @$user_address->phone_number, ['id' => 'phone_number', 'class' => 'text form-control']) !!}
                                        
                                        @if ($errors->has('phone_number'))
                                        <span class="help-inline" style="color:red">
                                            {{ $errors->first('phone_number') }}
                                        </span> @endif
                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="d-flex flex-wrap pt-4 nopad bor-bot-ash border-top border-light">
                            <div class="col-lg-3 col-12">
                                <h2 class="stit">{{ trans('messages.merchant.time') }}</h2>
                            </div>
                            <div class="col-lg-9">

                                <div class="form-group">
                                    <label class="label">{{ trans('messages.merchant.time_zone') }}</label>
                                    {!! Form::select('timezone', $timezone, @$user->timezone, ['id' => 'user_time_zone', 'class' => 'focus form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="btn-area">
                            <button class="blue-btn btn-add" type="submit">{{ trans('messages.merchant.save_changes') }}</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
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
                        <p>Never miss a sale with the {{$site_name}} Seller app.
                            <br> Remotely manage inventory, customers and orders.
                            <br>
                            <a href="/about/merchants/mobile" target="_blank">Learn more about the {{$site_name}} Seller app</a></p>
                        <p class="download">
                            <a href="https://itunes.apple.com/us/app/Spiffy-seller/id961870454?ls=1&amp;mt=8" class="btn-ios" target="_blank">Download on the App Store</a>
                            <br> Or, <a href="#" onclick="$.dialog('send_app').open();return false;">send a link</a> to your iPhone to download the app.</p>
                    </div>
                </div>
                <div class="popup send_app">
                    <p class="tit">Send App Link</p>
                    <fieldset class="frm">
                        <p>Get the {{$site_name}} Seller app sent to your phone.</p>
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
    </div>
</div>
        @stop