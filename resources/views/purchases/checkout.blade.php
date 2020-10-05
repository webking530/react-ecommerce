@extends('template')
@section('main')
<main class="cls_checkout" id="site-content" ng-controller="checkout" ng-cloak>
    <div class="container">
        <form action="{{ url('checkout_payment') }}" method="post" id="checkout_payment">
            {{ csrf_field() }}
            <input type="hidden" name="payment_method" id="payment_method" value="{{ $payment_method }}">
            <input type="hidden" name="customer_id" id="customer_id" value="">
            <input type="hidden" name="stripeToken" id="stripeToken" value="@{{payment.credit_card_details.token}}" />
            
            <h2 class="ptit">
                <a class="logo" href="{{ url('/') }}"><img src="{{ $logo }}"></a>
                <span class="secure">
                    <i class="icon icon-lock"></i>
                </span>
            </h2>
            <div class="cls_checkout_shipping row">
                <div class="col-lg-8 col-md-8 col-12">
                    <div class="ship-add">
                        <div class="cls_wrap mb-3 ">
                            <h3 class="stit"><b>{{ trans('messages.checkout.enter_shipping_address') }}</b> </h3>
                            <div class="cls_address p-3 shipping_add">
                                <ul class=" ship-ul">
                                    <li class="ship1-li selected" ng-repeat="shipping in shipping_details">
                                        <div class="custom-control custom-radio custom-control-inline">
                                          <input type="radio" id="customRadioInline1" name="shipping_address" value="" checked="checked" class="custom-control-input">
                                          <label class="custom-control-label" for="customRadioInline1" id="ship1">
                                              <b class="title">@{{ shipping.name }}</b>
                                            <small>@{{ shipping.address_line }}, @{{ shipping.city }}, @{{ shipping.state }}, @{{ shipping.country }} - @{{ shipping.postal_code }} .</small>
                                          </label>
                                        </div>
                                        <!-- <label id="ship1">
                                            <input type="radio" name="shipping_address" value="" checked="checked">
                                            <b class="title">@{{ shipping.name }}</b>
                                            <small>@{{ shipping.address_line }}, @{{ shipping.city }}, @{{ shipping.state }}, @{{ shipping.country }} - @{{ shipping.postal_code }} .</small></label> -->
                                        <a href="#" title="Edit Address" class="edit menu-title-cover btn-payment ship_edit line1" ng-click="ship_edit()"><i class="icon"></i><em style="margin-left: -68.5px; display: none;">{{ trans('messages.checkout.edit_address') }}</em></a>
                                    </li>
                                    <li>
                                        <button type="button" class="btn-blue-fancy btn border btn-ship-payment" disabled="disabled" ng-click="ship_next()">{{ trans('messages.checkout.bill_new_address') }}</button>
                                    </li>
                                    <li id="shipping_loading" class="loading_normal" style="padding:50px;display:none"></li>
                                </ul>

                                <div style="display:none" id="empty_shipping" class="cls_wrap mb-3  checkout_shipnn">
                                    <div class="row">
                                        <div class="col-lg-12 col-12">
                                        <div class="form-group">
                                            <label>{{ trans('messages.checkout.full_name') }} <em class="text-danger">*</em></label>
                                            
                                            <input value="" class="form-control" type="text" name="shipping_name" id="shipping_name_add">
                                        </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.address') }} <em class="text-danger">*</em></label>                                            
                                                <input id="shipping_address_1_add" value="" name="shipping_address_1_add" type="text" class="form-control" placeholder="{{ trans('messages.checkout.Street_address') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.address_line') }}</label> 
                                                
                                                <input id="shipping_address_2_add" class="form-control" value="" style="width: 100%;height: 34px;" type="text">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                 <label>{{ trans('messages.checkout.city') }}<em class="text-danger">*</em></label> 
                                                <input class="form-control" name="shipping_city_add" id="shipping_city_add" style="width: 100%;height: 34px;" value=""  type="text">
                                            </div>
                                        </div>
                                   
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                 <label>{{ trans('messages.checkout.state') }}<em class="text-danger">*</em></label> 

                                                <input class="text form-control" id="shipping_state_add" style="width: 100%;height: 34px;" value="" type="text" name="shipping_state_add">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.country') }}<em class="text-danger">*</em></label> 
                                                <select name="country_code" class="select-boxes select-country form-control" id="shipping_country_add">
                                                    @foreach($country as $countries)
                                                    <option value="{{ $countries->long_name }}" {{ $countries->long_name == $countryName ? 'selected' : ''}}>{{ $countries->long_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                         <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                 <label>{{ trans('messages.checkout.zip') }}<em class="text-danger">*</em></label> 
                                                <input class="text form-control" name="shipping_zip_add" id="shipping_zip_add" style="width: 100%;height: 34px;" value="" type="text">
                                            </div>
                                        </div>
                                         <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                 <label>{{ trans('messages.checkout.phone') }}<em class="text-danger">*</em></label> 
                                                <input class="text form-control" name="shipping_phone_add" id="shipping_phone_add" style="width: 100%;height: 34px;" value="" type="text">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                                <div class="form-group">
                                                     <label>{{ trans('messages.checkout.address_nick_name') }}</label> 
                                                <input class="text form-control" name="shipping_address_nick" id="shipping_address_nick_add" style="width: 100%;height: 34px;" value="" type="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-right col-12">
                                        <input type="hidden" id="edit" value="no">
                                        <button type="button" class="btn btn-light btn-back cancel_ d-none">{{ trans('messages.checkout.cancel') }}</button>
                                        <button type="button" value="add" class="btn btn-primary add_shipping_address">{{ trans('messages.checkout.save_address') }}</button>
                                    </div>

                                </div>
                                <div class="btns-area text-right over-hidden new_ship_address" style="margin-top:10px !important;">
                                    <!-- <button type="button" class="btn-blue-fancy flt-left btn-ship-payment" disabled="disabled" ng-click="ship_next()">{{ trans('messages.checkout.bill_new_address') }}</button> -->
                                    <a class="flt-right btn-ship new_ship" style="font-size: 13px;margin: 5px 0px;" href="#">{{ trans('messages.checkout.ship_new_address') }}</a>
                                </div>
                            </div>
                            <h3 class="disabled ash-head stit my-2" style="width:100%">{{ trans('messages.checkout.billing_address') }}</h3> @if(@$payment_method =='cc')
                            <h3 class="disabled ash-head stit" style="width:100%">{{ trans('messages.cart.payment_method') }}</h3> @endif
                            <h3 class="disabled ash-head stit my-2" style="width:100%">{{ trans('messages.checkout.review_order') }}</h3>
                        </div>
                    </div>
                    <div class="payment-add">
                        <div class="cls_wrap mb-3">                    
                            <h3 class="stit"><b>{{ trans('messages.checkout.shipping_address') }}</b>  <a href="#" class="back change-link change_function">{{ trans('messages.checkout.change') }}</a></h3>
                           
                            <div ng-repeat="shipping in shipping_details" class="cls_address p-3">
                                <b class="title">@{{ shipping.name }}</b>
                                <p>
                                    @{{ shipping.address_line }}, @{{ shipping.city }}, @{{ shipping.state }}, @{{ shipping.country }} - @{{ shipping.postal_code }}
                                </p>
                            </div>
                        </div>
                       
                       <div class="cls_wrap mb-3">
                        <h3 class="stit"><b>{{ trans('messages.checkout.billing_address') }}</b></h3>
                            <div class="cls_address p-3 shipping_add">
                                 <ul class=" ship-ul">
                                    <li class="ship1-li selected" ng-repeat="billing in billing_details">
                                        <div class="custom-control custom-radio custom-control-inline">
                                          <input type="radio" id="customRadioInline1" name="billing_address"  value="1523646" type="radio" checked="checked" class="custom-control-input">
                                          <label class="custom-control-label" for="customRadioInline1" id="ship1">
                                              <b class="title">@{{ billing.name }}</b>
                                            <small>@{{ billing.address_line }}, @{{ billing.city }}, @{{ billing.state }}, @{{ billing.country }} - @{{ billing.postal_code }} .</small>
                                          </label>
                                        </div>
                                        <a href="#" title="Edit Address" class="edit menu-title-cover btn-payment ship_edit line1" ng-click="bill_edit()"><i class="icon"></i><em style="margin-left: -68.5px; display: none;">{{ trans('messages.checkout.edit_address') }}</em></a>
                                    </li>
                                    <li id="billing_loading" class="loading_normal" style="padding:50px;display:none"></li>
                                </ul>
                                <div style="display:none" id="empty_billing" class="cls_wrap mb-3 ">
                                    <div class="row">
                                        <div class="col-lg-12 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.full_name') }} <em class="text-danger">*</em></label>
                                                <input value="" class="form-control" type="text" name="billing_name_add" id="billing_name_add">
                                            </div>
                                        </div>
                                   
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.address') }} <em class="text-danger">*</em></label>
                                                <input class="form-control" id="billing_address_1_add" value="" name="billing_address_1_add" type="text" placeholder="{{ trans('messages.checkout.Street_address') }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.address_line') }} <em class="text-danger">*</em></label>
                                                <input class="form-control" id="billing_address_2_add" value="" type="text" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.city') }} <em class="text-danger">*</em></label>
                                                <input class="form-control"  name="billing_city_add" id="billing_city_add"value="" type="text" >
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.state') }} <em class="text-danger">*</em></label>
                                                <input class="form-control"  name="billing_state_add" id="billing_state_add"value="" type="text" >
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.country') }} <em class="text-danger">*</em></label>
                                                <select name="country_code" class="select-boxes select-country form-control" id="billing_country_add">
                                                    @foreach($country as $countries)
                                                    <option value="{{ $countries->short_name }}" {{ $countries->long_name == $countryName ? 'selected' : ''}}>{{ $countries->long_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.zip') }} <em class="text-danger">*</em></label>
                                                <input class="form-control" name="billing_zip_add" id="billing_zip_add"  value="" type="text">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.phone') }} <em class="text-danger">*</em></label>
                                                <input class="form-control" name="billing_phone_add" id="billing_phone_add" value="" type="text">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>{{ trans('messages.checkout.address_nick_name') }} <em class="text-danger">*</em></label>
                                                <input class="form-control" name="shipping_address_nick" id="billing_address_nick_add" value="" type="text">
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-lg-12 text-right col-12">
                                            <input type="hidden" id="edit" value="no">
                                            <button type="button" class="btn btn-light btn-back cancel_  hide">{{ trans('messages.checkout.cancel') }}</button>
                                            <button type="button" value="add" class="btn btn-primary add_billing_address">{{ trans('messages.checkout.save_address') }}</button>
                                        </div>
                                </div>
                                <div class="btns-area text-right over-hidden new_bill_address " style="margin-top:10px !important;">
                                    <button type="button" class="btn-blue-fancy btn flt-left btn-use-payment" ng-click="bill_next()">{{ trans('messages.checkout.next') }}</button>
                                    <a class="flt-right new_bill" ng-click="view_bill()" style="font-size: 13px;margin: 5px 0px;" href="javascript:void(0)">{{ trans('messages.checkout.bill_new_address') }}</a>
                                </div>

                            </div>
                        </div>
                        @if(@$payment_method =='cc')
                        <h3 class="disabled ash-head stit my-2" style="width:100%">{{ trans('messages.cart.payment_method') }}</h3> @endif
                        <h3 class="disabled ash-head stit my-2" style="width:100%">{{ trans('messages.checkout.review_order') }}</h3>
                    </div>

                    <div class="payment-add1" style="display:none">
                        <div class="cos_shipping_div back-white col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad second-head">
                            <h3 class="ash-head cos_shipping_div">{{ trans('messages.checkout.shipping_address') }}<a style="font-weight:normal;" href="#" class="change-link flt-right">{{ trans('messages.checkout.change') }}</a></h3>
                            <div ng-repeat="shipping in shipping_details" class=s tyle="border-top-left-radius:0px;border-top-right-radius:0px;">
                                <b class="title" style="padding:15px;">@{{ shipping.name }}</b>
                                <p style="padding: 15px;margin: 0px;font-size: 13px;">
                                    @{{ shipping.address_line }}, @{{ shipping.city }}, @{{ shipping.state }}, @{{ shipping.country }} - @{{ shipping.postal_code }}
                                </p>
                            </div>
                        </div>
                        <div class="back-white col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad second-head">
                            <h3 class="ash-head">{{ trans('messages.checkout.billing_address') }}<a style="font-weight:normal;" href="#" class="change-bill change_function flt-right">{{ trans('messages.checkout.change') }}</a></h3>
                            <div ng-repeat="billing in billing_details" style="border-top-left-radius:0px;border-top-right-radius:0px;">
                                <!-- <p class="title" style="padding:15px;">Billing</p> -->
                                <b class="title" style="padding:15px;">@{{ billing.name }}</b>
                                <p style="padding: 15px;margin: 0px;font-size: 13px;">
                                    @{{ billing.address_line }}, @{{ billing.city }}, @{{ billing.state }}, @{{ billing.country }} - @{{ billing.postal_code }}
                                </p>
                            </div>
                        </div>
                        <div class="back-white col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad second-head">

                            <h3 class="ash-head">{{ trans('messages.cart.payment_method') }}</h3>
                            <div id="payment_loading" class="loading_normal" style="padding:50px;display:none"></div>
                            <div class="back-white col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad edit_payment_details" style="display:none;border-top-left-radius:0px;border-top-right-radius:0px;">

                                <ul class="ship-ul" style="width:100% !important;overflow: unset !important;">
                                    <!-- ngRepeat: billing in billing_details -->
                                    <li class="ship1-li selected ng-scope" ng-show="payment.credit_card_details.length !=0">

                                        <span><label id="ship1" ><input name="payment_address" value="1523646" type="radio" checked="checked" class="payment_address1">

        				        <small class="ng-binding" id="edit_credit_card_details" > Card Name : @{{ payment.credit_card_details.card_name }} <br/>
        					Card Number : @{{ payment.credit_card_details.card_number }} <br/>
        					Expiration date : @{{ payment.credit_card_details.cc_expire_month }} /  @{{ payment.credit_card_details.cc_expire_year }}  <br/>
        					CVV : @{{ payment.credit_card_details.cvv }}   </small></label>
        					<a href="#" class="edit menu-title-cover btn-payment ship_edit line1" ng-click="payment_edit()"><i class="icon"></i><em style="margin-left: -68.5px; display: none;">Edit Payment Method</em></a></span>
                                    </li>
                                    <span ng-repeat="a in payment.card_detail">
        					<li class="ship1-li selected ng-scope">
        					<label id="ship1" ><input name="payment_address" value="@{{a.customer_id}}" type="radio" class="payment_address" >

        				        <small class="ng-binding" id="edit_credit_card_details" > Card Number : @{{ a.last4 }}  </small></label>

        				       <!--  <a href="#" class="edit menu-title-cover btn-payment ship_edit line1" ng-click="payment_edit()"><i class="icon"></i><em style="margin-left: -68.5px; display: none;">Edit Payment Method</em></a> -->
        				       </li>
        				        </span>
                                    <!-- end ngRepeat: billing in billing_details -->

                                </ul>

                            </div>

                            <div class="btns-area text-right over-hidden" style="margin-top:10px !important;">
                                <button type="button" class="btn-blue-fancy flt-left btn-use-payment" disabled="disabled" ng-click="payment_next()">Use this payment method</button>
                                <a class="flt-right new_payment" ng-click="view_payment()" style="font-size: 13px;margin: 5px 0px;" href="javascript:void(0)">Add new payment method</a>
                            </div>

                        </div>
                        <h3 class="disabled ash-head flt-left margin-top-15" style="width:100%">{{ trans('messages.checkout.review_order') }}</h3>
                    </div>

                    <div class="review-add" style="display:none">
                        <div class="cls_wrap mb-3">                    
                            <h3 class="stit"><b>{{ trans('messages.checkout.shipping_address') }}</b>  <a href="#" class="back change-link">{{ trans('messages.checkout.change') }}</a></h3>
                           
                            <div ng-repeat="shipping in shipping_details" class="cls_address p-3">
                                <b class="title">@{{ shipping.name }}</b>
                                <p>
                                    @{{ shipping.address_line }}, @{{ shipping.city }}, @{{ shipping.state }}, @{{ shipping.country }} - @{{ shipping.postal_code }}
                                </p>
                            </div>
                        </div>
                        <div class="cls_wrap mb-3">
                             <h3 class="stit"><b>{{ trans('messages.checkout.billing_address') }}</b>  <a href="#" class="back change-bill change_function">{{ trans('messages.checkout.change') }}</a></h3>

                            
                            <div ng-repeat="billing in billing_details" class="cls_address p-3">
                                <!-- <p class="title" style="padding:15px;">Billing</p> -->
                                <b class="title">@{{ billing.name }}</b>
                                <p>
                                    @{{ billing.address_line }}, @{{ billing.city }}, @{{ billing.state }}, @{{ billing.country }} - @{{ billing.postal_code }}
                                </p>
                            </div>
                        </div>
                        <div class="cls_wrap mb-3 payment_method_details" style="display:none">
                            <h3 class="stit"><b>{{ trans('messages.cart.payment_method') }}</b>  <a href="#" class="back change-payment change_function">{{ trans('messages.checkout.change') }}</a></h3>

                            <div class="cls_address p-3">
                                <!-- <p class="title" style="padding:15px;">Billing</p> -->

                                <p id='credit_card_details' ng-show="payment.credit_card_details.length !=0 ">

                                    <!-- <div >
        							@{{a.card_name}}@{{a.card_id}}
        						</div> -->
                                    Card Name : @{{ payment.credit_card_details.card_name }}
                                    <br/> Card Number : @{{ payment.credit_card_details.card_number }}
                                    <br/> Expiration date : @{{ payment.credit_card_details.cc_expire_month }} / @{{ payment.credit_card_details.cc_expire_year }}
                                    <br/> CVV : @{{ payment.credit_card_details.cvv }}
                                </p>
                                <p style="padding: 15px;margin: 0px;font-size: 13px;" ng-repeat="a in payment.card_detail">

                                    Card Number : @{{ a.last4 }}

                                    <!--  <a href="#" class="edit menu-title-cover btn-payment ship_edit line1" ng-click="payment_edit()"><i class="icon"></i><em style="margin-left: -68.5px; display: none;">Edit Payment Method</em></a> -->

                                </p>

                            </div>
                        </div>
                        <div class="cls_wrap mb-3">
                            <h3 class="stit"><b>{{ trans('messages.checkout.review_order') }}</b></h3>
                            
                            <div id="review_loading" class="loading_normal" style="padding:50px;display:none"></div>
                            <div ng-repeat="orders in review_orders" class="border-top">
                                <input type="hidden" name="cart_id[]" value="@{{ orders.id }}">
                                <h4> {{ trans('messages.checkout.sold_by') }} @{{ orders.product_details.user_name }}</h4>
                                <div class="review_detail">
                                    <div class="col-lg-12 col-12 d-flex flex-wrap align-items-center py-2">
                                        <img ng-src="@{{ orders.product_details.image_name }}">
                                        <div class="ml-3 cls_width">
                                            <b class="title">@{{ orders.product_details.title }}</b>

                                            <span ng-if="orders.product_option_details.id">
        								<input type="hidden" name="product_name[]" value="@{{ orders.product_details.title }}(@{{ orders.product_option_details.option_name }})">
        								<input type="hidden" name="product_price[]" value="@{{ orders.product_option_details.price }}">
        								<p>@{{ orders.product_option_details.option_name }}</p>
        								<p>{{ trans('messages.checkout.price') }} : <span ng-bind-html="orders.product_details.currency_symbol" ng-test="@{{orders}}" ></span >@{{ orders.product_option_details.price }}</p>
                                        <span class="sales" ng-if='orders.product_option_details.retail_price!=0'>
        				                  <em id= "product_retail_price_@{{orders.product_option_details.id}}"><span ng-bind-html="orders.product_details.currency_symbol"></span> @{{orders.product_option_details.retail_price.toFixed(2)}}
                                            </em>
                                            </span>
                                            </span>
                                            <span ng-if="!orders.product_option_details.id">
        								<input type="hidden" name="product_name[@{{ $index }}]" value="@{{ orders.product_details.title }}">
        								<input type="hidden" name="product_price[@{{ $index }}]" value="@{{ orders.product_details.products_prices_details.price }}">
        								<p>{{ trans('messages.checkout.price') }} : <span ng-bind-html="orders.product_details.products_prices_details.currency.symbol"></span>@{{ orders.product_details.products_prices_details.price }}
                                        <span class="sales cls_retailprice" ng-if='orders.retail_price'>
                                                <em id= "product_retail_price_@{{orders.id}}"><span ng-bind-html="orders.product_details.currency_symbol"></span> @{{orders.retail_price.toFixed(2)}}
                                                </em>
                                            </span></p>    
                                        </p>
                                            </span>
                                            <p>{{ trans('messages.checkout.quantity') }} : @{{ orders.quantity }}</p>
                                            <input type="hidden" name="product_quantity[@{{ $index }}]" value="@{{ orders.quantity }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-12" ng-if="orders.product_shipping_details.length" ng-repeat='shipping in orders.product_shipping_details'>
                                        <p ng-if='shipping.shipping_type!="Free Shipping"'>{{ trans('messages.checkout.shipping_fee') }} -
                                            <span ng-bind-html="orders.product_details.products_prices_details.currency.symbol"></span>@{{ shipping.charge }}</p>
                                        <span ng-init='incrementalfee= shipping.incremental_fee * (orders.quantity - 1)'></span>
                                        <p ng-if='shipping.shipping_type!="Free Shipping" &&  incrementalfee!=0'>{{ trans('messages.checkout.incremental_fee') }} -
                                            <span ng-bind-html="orders.product_details.products_prices_details.currency.symbol"></span>@{{ (shipping.incremental_fee * (orders.quantity - 1)) }}</p>
                                        <p ng-if='shipping.shipping_type=="Free Shipping"'>{{ trans('messages.checkout.free_shipping') }}</p>
                                        <p>{{ trans('messages.checkout.ships_from') }} @{{ shipping.ships_from }}</p>
                                        <p>{{ trans('messages.checkout.estimated_delivery') }} : @{{ shipping.start_window }} - @{{ shipping.end_window }} {{ trans('messages.checkout.days') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cls_wrap mb-3 ">
                            <div class="pay_order d-flex align-items-center flex-wrap p-3">
                            <button type="submit" class="btns-green-embo btn-success btn" >
                                {{ trans('messages.checkout.place_your_order') }}
                            </button>
                            <p style="display: none" id="currencysymbol"></p>
                            <p><b style="display: block;color: #000;">{{ trans('messages.checkout.total') }} <span class="order_price_total"></span></b> {{ trans('messages.checkout.order_agree') }} {{ $site_name }} <a href="{{ url('privacy_policy') }}" target="_blank">{{ trans('messages.checkout.privacy_policy') }}</a> {{ trans('messages.checkout.and') }} <a href="{{ url('terms_of_service') }}" target="_blank">{{ trans('messages.checkout.terms_sale') }}</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-12 cls_checkout_right">
                    <div class="cls_wrap pb-2">
                        <h3 class="stit">{{ trans('messages.checkout.order_summary') }}</h3>
                        <ul class="cls_payment_reciept" style="font-size:13px;margin:15px 15px 0px !important;">
                            <li class="p-1 d-flex justify-content-between align-items-center flex-wrap" style="width:100%">
                                <p class="m-0">{{ trans('messages.cart.payment_method') }}</p><span class="flt-right">{{ trans($payment_method_show) }}</span></li>
                            <li class="p-1 d-flex justify-content-between align-items-center flex-wrap" style="width:100%">
                                <p class="m-0">{{ trans('messages.checkout.subtotal') }}</p><span class="flt-right" id="order_price_sub"></span></li>
                            <li style="width:100%" class="p-1 d-flex justify-content-between align-items-center flex-wrap" id='order_shipping'>
                                <p class="m-0">{{ trans('messages.checkout.shipping_fee') }}</p><span class="flt-right" id="order_price_shipping"></span></li>
                            <li style="width:100%" class="p-1 d-flex justify-content-between align-items-center flex-wrap" id='order_incremental'>
                                <p class="m-0">{{ trans('messages.checkout.incremental_fee') }}</p><span class="flt-right" id="order_price_incremental"></span></li>
                            <li style="width:100%" class="p-1 d-flex justify-content-between align-items-center flex-wrap" id='order_service'>
                                <p class="m-0">{{ trans('messages.checkout.service_fee') }}</p><span class="flt-right" id="order_price_tax"></span></li>
                            <li class="editable-fields mt-3" id="after_apply" style="display: none">
                                <div id="coupon_loading" class="loading_normal" style="padding:30px;display:none"></div>
                                <span>
                                  <div class="row">
                                    <div class="col-lg-8 col-9">
                                      <input autocomplete="off" class="coupon-code-field form-control" name="coupon_code" type="text" value="">
                                    </div>
                                    <div class="col-lg-4 col-3">
                                      <a href="javascript:void(0);" id="apply-coupon" class="btn btn-light btn-block apply-coupon">{{ trans('messages.checkout.apply') }}</a>
                                    </div>
                                  </div>

                                  <p id="coupon_disabled_message" class="icon-rausch " style="display:none"></p>
                                  <a href="javascript:;" class="cancel-coupon btn btn-info btn-sm mt-2">{{ trans('messages.checkout.cancel') }}</a>
                                </span>
                            </li>
                            <li class="with-applied-coupon" id="after_apply_coupon" style="display:none;">
                                <div id="remove_coupon_loading" class="loading_normal" style="padding: 30px;display:none"></div>
                                <span class="coupon-section-link">
                              	<p class="flt-left">{{ trans('messages.checkout.coupon') }}</p>
                                <span class="flt-right with-applied-coupon price" id="after_apply_amount" >
                                 <span id="applied_coupen_amount"></span>
                                            </span>
                                            </span>
                            </li>
                            <li class="without-applied-coupon mt-2" id="restric_apply">
                                <p class="flt-left">
                                    <a href="javascript:;" class="open-coupon-section-link btn btn-primary">
                                      {{ trans('messages.checkout.coupon_code') }}
                                    </a>
                                </p>
                            </li>

                            <li id="after_apply_remove" style="width:100%; display: none" class="flt-left">
                                <a data-prevent-default="true" href="javascript:void(0);" id="remove_coupon">
                                    <span>
                                        {{ trans('messages.checkout.remove_coupon') }}
                                    </span>
                                </a>

                            </li>
                        </ul>
                        <ul class="payment-reciept after" style="font-size:15px;margin:15px 15px 0px !important;">
                            <li class="subtotal_ d-flex justify-content-between align-items-center flex-wrap p-1">
                                <b>{{ trans('messages.checkout.total') }}</b>
                                <b class="price order_price_total"></b>
                            </li>
                        </ul>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12 text-center my-3">
                            <button type="submit" class="btns-green-embo btn-success btn btn-checkout" disabled="disabled" style="width: 100%"> {{ trans('messages.checkout.place_your_order') }}</button>
                            <!-- <div class="amex-express-btn"><span>{{ trans('messages.checkout.express_checkout') }}</span></div> -->
                        </div>
                    </div>
                    <div class="currency-cart after">
                        <!-- <a href="#" class="change_currency" style="">Select your currency</a> -->
                        <span href="javascript:void(0)" class="change_currency1" style="">{{ trans('messages.checkout.your_currency') }}</span>
                        <span class="float-right"> {!! Session::get('symbol') !!} {{@Session::get('currency')}}</span>
                    </div>
                    <small class="terms">{{ trans('messages.checkout.order_agree') }} {{ $site_name }}  <a href="{{ url('privacy_policy') }}" target="_blank">{{ trans('messages.checkout.privacy_policy') }}</a> {{ trans('messages.checkout.and') }} <a href="{{ url('terms_of_service') }}" target="_blank">{{ trans('messages.checkout.terms_sale') }}</a>.</small>
                </div>
            </div>

            <div class="add-fancy-back currency-popup">
                <div class="d-flex align-items-center flex-wrap cls_modal_height">
                    <div class="col-lg-5 col-md-7 col-sm-8 back-white pos-top mar-auto flt-none nopad col-xs-11 currency-content ">
                        <h2 class="fancy-head-popup">Choose your currency</h2>
                        <div class="file popup show_currency col-lg-12 col-sm-12 col-md-12 col-xs-12 nopad">
                            <div class="left col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding:20px;">
                                <ul class="continents">
                                    <li class="continent" code="all"><a href="#" class="current">All Currencies</a></li>
                                    <li class="continent" code="ap"><a href="#">Asia-Pacific</a></li>
                                    <li class="continent" code="af"><a href="#">Africa and Middle East</a></li>
                                    <li class="continent" code="am"><a href="#">Americas</a></li>
                                    <li class="continent" code="eu"><a href="#">Europe</a></li>
                                </ul>
                            </div>
                            <div class="right col-lg-9 col-md-9 col-sm-9 col-xs-12 bor-left-ash" code="all">
                                <ul class="major after bor-bot-ash" style="padding:20px;">
                                    <li class="currency" code="GBP"><a href="$"><b>British pounds</b> <small>GBP</small><span>United Kingdom (£)<b></b></span></a></li>
                                    <li class="currency" code="CAD"><a href="$"><b>Canadian dollars</b> <small>CAD</small><span>Canada ($)<b></b></span></a></li>
                                    <li class="currency" code="CNY"><a href="$"><b>Chinese yuan</b> <small>CNY</small><span>China (¥)<b></b></span></a></li>
                                    <li class="currency" code="EUR"><a href="$"><b>Euros</b> <small>EUR</small><span>European Union (€)<b></b></span></a></li>
                                    <li class="currency" code="JPY"><a href="$"><b>Japanese yen</b> <small>JPY</small><span>Japan (¥)<b></b></span></a></li>
                                    <li class="currency" code="KRW"><a href="$"><b>South Korean won</b> <small>KRW</small><span>Republic of Korea (₩)<b></b></span></a></li>
                                    <li class="currency" code="USD"><a href="$"><b>U.S. dollars</b> <small>USD</small><span>United States ($)<b></b></span></a></li>
                                </ul>
                                <ul class="after" style="padding:20px;">
                                    <li class="currency" code="AUD"><a href="$"><b>Australian dollars</b> <small>AUD</small><span>Australia ($)<b></b></span></a></li>
                                    <li class="currency" code="BHD"><a href="$"><b>Bahrain dinars</b> <small>BHD</small><span>Bahrain (BD)<b></b></span></a></li>
                                    <li class="currency" code="BRL"><a href="$"><b>Brazil reals</b> <small>BRL</small><span>Brazil (R$)<b></b></span></a></li>
                                    <li class="currency" code="GBP"><a href="$"><b>British pounds</b> <small>GBP</small><span>United Kingdom (£)<b></b></span></a></li>
                                    <li class="currency" code="BND"><a href="$"><b>Brunei dollars</b> <small>BND</small><span>Brunei ($)<b></b></span></a></li>
                                    <li class="currency" code="CAD"><a href="$"><b>Canadian dollars</b> <small>CAD</small><span>Canada ($)<b></b></span></a></li>
                                    <li class="currency" code="CNY"><a href="$"><b>Chinese yuan</b> <small>CNY</small><span>China (¥)<b></b></span></a></li>
                                    <li class="currency" code="CZK"><a href="$"><b>Czech koruny</b> <small>CZK</small><span>Czech (Kč)<b></b></span></a></li>
                                    <li class="currency" code="DKK"><a href="$"><b>Danish kroner</b> <small>DKK</small><span>Denmark (kr)<b></b></span></a></li>
                                    <li class="currency" code="EGP"><a href="$"><b>Egyptian pounds</b> <small>EGP</small><span>Egypt (£)<b></b></span></a></li>
                                    <li class="currency" code="EUR"><a href="$"><b>Euros</b> <small>EUR</small><span>European Union (€)<b></b></span></a></li>
                                    <li class="currency" code="HKD"><a href="$"><b>Hong Kong dollars</b> <small>HKD</small><span>Hong Kong ($)<b></b></span></a></li>
                                    <li class="currency" code="HUF"><a href="$"><b>Hungarian forints</b> <small>HUF</small><span>Hungary (Ft)<b></b></span></a></li>
                                    <li class="currency" code="INR"><a href="$"><b>Indian rupees</b> <small>INR</small><span>India (₹)<b></b></span></a></li>
                                    <li class="currency" code="IDR"><a href="$"><b>Indonesian rupiahs</b> <small>IDR</small><span>Indonesia (Rp)<b></b></span></a></li>
                                    <li class="currency" code="ILS"><a href="$"><b>Israeli shekels</b> <small>ILS</small><span>Israel (₪)<b></b></span></a></li>
                                    <li class="currency" code="JPY"><a href="$"><b>Japanese yen</b> <small>JPY</small><span>Japan (¥)<b></b></span></a></li>
                                    <li class="currency" code="JOD"><a href="$"><b>Jordanian dinars</b> <small>JOD</small><span>Jordan (JD)<b></b></span></a></li>
                                    <li class="currency" code="KZT"><a href="$"><b>Kazakh tenge</b> <small>KZT</small><span>Kazakhstan (лв)<b></b></span></a></li>
                                    <li class="currency" code="KWD"><a href="$"><b>Kuwaiti dinars</b> <small>KWD</small><span>Kuwait (K.D.)<b></b></span></a></li>
                                    <li class="currency" code="LTL"><a href="$"><b>Lithuanian litai</b> <small>LTL</small><span>Lithuania (Lt)<b></b></span></a></li>
                                    <li class="currency" code="MYR"><a href="$"><b>Malaysian ringgits</b> <small>MYR</small><span>Malaysia (RM)<b></b></span></a></li>
                                    <li class="currency" code="MXN"><a href="$"><b>Mexican pesos</b> <small>MXN</small><span>Mexico ($)<b></b></span></a></li>
                                    <li class="currency" code="NZD"><a href="$"><b>New Zealand dollars</b> <small>NZD</small><span>New Zealand ($)<b></b></span></a></li>
                                    <li class="currency" code="NOK"><a href="$"><b>Norwegian kroner</b> <small>NOK</small><span>Norway (kr)<b></b></span></a></li>
                                    <li class="currency" code="PKR"><a href="$"><b>Pakistan rupees</b> <small>PKR</small><span>Pakistan (₨)<b></b></span></a></li>
                                    <li class="currency" code="PHP"><a href="$"><b>Philippine pesos</b> <small>PHP</small><span>Philippines (₱)<b></b></span></a></li>
                                    <li class="currency" code="PLN"><a href="$"><b>Polish zloty</b> <small>PLN</small><span>Poland (zł)<b></b></span></a></li>
                                    <li class="currency" code="QAR"><a href="$"><b>Qatar riyals</b> <small>QAR</small><span>Qatar (﷼)<b></b></span></a></li>
                                    <li class="currency" code="RON"><a href="$"><b>Romanian leu</b> <small>RON</small><span>Romania (L)<b></b></span></a></li>
                                    <li class="currency" code="RUB"><a href="$"><b>Russian rubles</b> <small>RUB</small><span>Russia (руб)<b></b></span></a></li>
                                    <li class="currency" code="SAR"><a href="$"><b>Saudi riyals</b> <small>SAR</small><span>Saudi Arabia (﷼)<b></b></span></a></li>
                                    <li class="currency" code="RSD"><a href="$"><b>Serbian dinars</b> <small>RSD</small><span>Serbia (Дин.)<b></b></span></a></li>
                                    <li class="currency" code="SGD"><a href="$"><b>Singapore dollars</b> <small>SGD</small><span>Singapore ($)<b></b></span></a></li>
                                    <li class="currency" code="ZAR"><a href="$"><b>South African rands</b> <small>ZAR</small><span>South Africa (R)<b></b></span></a></li>
                                    <li class="currency" code="KRW"><a href="$"><b>South Korean won</b> <small>KRW</small><span>Republic of Korea (₩)<b></b></span></a></li>
                                    <li class="currency" code="SEK"><a href="$"><b>Swedish kronor</b> <small>SEK</small><span>Sweden (kr)<b></b></span></a></li>
                                    <li class="currency" code="CHF"><a href="$"><b>Swiss francs</b> <small>CHF</small><span>Switzerland (CHF)<b></b></span></a></li>
                                    <li class="currency" code="TWD"><a href="$"><b>Taiwan dollars</b> <small>TWD</small><span>Taiwan (NT$)<b></b></span></a></li>
                                    <li class="currency" code="THB"><a href="$"><b>Thai baht</b> <small>THB</small><span>Thailand (฿)<b></b></span></a></li>
                                    <li class="currency" code="TRY"><a href="$"><b>Turkish liras</b> <small>TRY</small><span>Turkey (₤)<b></b></span></a></li>
                                    <li class="currency" code="USD"><a href="$"><b>U.S. dollars</b> <small>USD</small><span>United States ($)<b></b></span></a></li>
                                    <li class="currency" code="AED"><a href="$"><b>United Arab Emirates dirhams</b> <small>AED</small><span>United Arab Emirates (AED)<b></b></span></a></li>
                                    <li class="currency" code="VND"><a href="$"><b>Vietnamese dong</b> <small>VND</small><span>Viet Nam (₫)<b></b></span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="btns-area text-right over-hidden">
                            <button type="button" class="btns-gray btn-back cancel_">Cancel</button>
                            <button type="button" class="btn-blue-fancy">Save</button>
                        </div>
                        <button type="button" class="ly-close" title="Close"><i class="ic-del-black"></i></button>
                    </div>
                </div>
            </div>

            <div class="add-fancy-back payment-methodpop" style="display: none">
                <div class="d-flex align-items-center flex-wrap cls_modal_height">
                    <div class="col-lg-5 col-12 col-md-7 col-sm-8 pos-top payment-content1">
                        <h2 class="fancy-head-popup d-flex align-items-center justify-content-between ">Add new payment method<i class="icon icon-close close_payment"></i></h2>
                        <div class="file">
                            <span class="uoload_frm">
        					<div class="flt-left pad-rit-10" style="width:50%">
        					<p class="pay-p">Name on card<em class="text-danger">*</em></p>
        					<input class="text" id="card_name" name="card_name" style="width: 100%;height: 34px;" value=""  type="text">
        					</div>
        					<div class="flt-left pad-rit-10" style="width:50%">
        					<p class="pay-p">Card Number<em class="text-danger">*</em></p>
        					<input id="cc_number" name="cc_number" class="text" value="" style="width: 100%;height: 34px;" placeholder="card number"   type="text">
        					</div>
        				</span>

                            <span class="uoload_frm">
        					<div class="flt-left pad-rit-10" style="width:30%" >
        					<p class="pay-p">Expiration date<em class="text-danger">*</em></p>
        					{{ Form::selectMonth('cc_expire_month','',['class' => 'select-boxes cc_expire_month','id' => 'cc_expire_month','style'=>'width:100%'],'%m') }}
        					<input type="hidden" id="current_month" value="{{@$current_month}}">
        						<!-- <select name="cc_expire_month" class="select-boxes cc_expire_month" id="cc_expire_month" style="width:100%;">
        						@foreach($cc_month as $ccmonth)
        							<option value="{{ $ccmonth }}">{{ $ccmonth }}</option>
        						@endforeach
        						</select> -->
        					</div>

        					<div class="flt-left pad-rit-10" style="width:20%">
        					<p class="pay-p" style="padding-bottom: 18px;"></p>
        						<select name="cc_expire_year" class="select-boxes cc_expire_year" id="cc_expire_year" style="width:100%;">
        						@foreach($cc_year as $ccyear)
        							<option value="{{ $ccyear }}">{{ $ccyear }}</option>
        						@endforeach
        						</select>
        					</div>

        					<div class="flt-left pad-rit-10" style="width:30%">
        					<p class="pay-p" >CVV<em class="text-danger">*</em></p>
        					<input class="text" id="cvv" name="cvv" style="width: 100%;height: 34px;" value=""   type="text">
        					</div>
        				</span>
                            <span class="text-danger stripe_error hide"></span>
                        </div>
                        <div class="btns-area text-right">
                            <input type="hidden" id="edit_payment_type" value="no">
                            <button type="button" class="btns-gray btn-back cancel_">{{ trans('messages.checkout.cancel') }}</button>
                            <button type="button" class="btn-blue-fancy add_payment_method">{{ trans('messages.home.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="add-fancy-back payment-popup1">
                <div class="d-flex align-items-center flex-wrap cls_modal_height">
                    <div class="col-lg-5 col-md-7 col-sm-8 back-white pos-top mar-auto flt-none payment-content1 ">
                        <h2 class="fancy-head-popup d-flex align-items-center justify-content-between">{{ trans('messages.checkout.edit_shipping_address') }} <i class="icon icon-close close_shipping"></i></h2>
                        <div class="file row">
                            <div class="uoload_frm col-lg-12 col-12">
                                <div class="form-group">
            					   <label class="pay-p">{{ trans('messages.checkout.full_name') }}<em class="text-danger">*</em></label>
            					   <input class="text form-control" id="shipping_name" name="shipping_name_add" style="width: 100%;height: 34px;" value="" type="text">
                                </div>
            				</div>

                            <div class="uoload_frm col-lg-6 col-12">
            					<div class="form-group">
            					<label class="pay-p">{{ trans('messages.checkout.address') }}<em class="text-danger">*</em></label>
            					<input id="shipping_address_1" name="shipping_address_1" class="text form-control" value="" style="width: 100%;height: 34px;" placeholder="{{ trans('messages.checkout.Street_address') }}" type="text">
            					</div>
                            </div>
        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
                					<label class="pay-p">{{ trans('messages.checkout.address_line') }}</label>
                					<input id="shipping_address_2" class="text form-control" value="" style="width: 100%;height: 34px;" type="text">
            					</div>
        				    </div>

        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
                					<label class="pay-p">{{ trans('messages.checkout.city') }}<em class="text-danger">*</em></label>
                					<input class="text form-control" name="shipping_city" id="shipping_city" style="width: 100%;height: 34px;" value="" type="text">
            					</div>
            				</div>

                            <div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
            					<label class="pay-p">{{ trans('messages.checkout.state') }}<em class="text-danger">*</em></label>
            					<input class="text form-control" name="shipping_state" id="shipping_state" style="width: 100%;height: 34px;" value=""   type="text">
            					</div>
                            </div>
                            <div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
                                    <label class="pay-p">{{ trans('messages.checkout.country') }}<em class="text-danger">*</em></label>
                                    <select name="country_code" class="select-boxes select-country form-control" id="shipping_country" style="width:100%;">
                                    @foreach($country as $countries)
                                        <option value="{{ $countries->long_name }}">{{ $countries->long_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
            					<label class="pay-p">{{ trans('messages.checkout.zip') }}<em class="text-danger">*</em></label>
            					<input class="text form-control" name="shipping_zip" id="shipping_zip" style="width: 100%;height: 34px;"  value=""  type="text">
            					</div>
                            </div>
        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
            					<label class="pay-p">{{ trans('messages.checkout.phone') }}<em class="text-danger">*</em></label>
            					<input class="text form-control" id="shipping_phone" name="shipping_phone" style="width: 100%;height: 34px;" value=""   type="text">
            					</div>
                            </div>
        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
            					<label class="pay-p">{{ trans('messages.checkout.address_nick_name') }}</label>
            					<input class="text form-control" id="shipping_address_nick"  style="width: 100%;height: 34px;" value="" type="text">
            					</div>

        					<!-- <span class="uoload_frm">
        					<input checked="" disabled id="shipping_default" name="set_default"  value="true" type="checkbox">
        					<label  for="shipping_default" class="pay-p">Make this my primary shipping address</label>
        					</span> -->
                            </div>
                        </div>
                        <div class="btns-area text-right">
                            <input type="hidden" id="edit" value="no">
                            <button type="button" class="btn-light btn btn-back cancel_">{{ trans('messages.checkout.cancel') }}</button>
                            <button type="button" value="edit" class="btn-primary btn edit_shipping_address">{{ trans('messages.checkout.save_address') }}</button>
                        </div>
                        <!-- <button type="button" class="ly-close" title="Close"><i class="ic-del-black"></i></button> -->
                    </div>
                </div>
            </div>

            <div class="add-fancy-back payment-popup-bill" style="display: none">
                <div class="d-flex align-items-center flex-wrap cls_modal_height">
                    <div class="col-lg-5 col-md-7 col-sm-8 back-white pos-top mar-auto flt-none nopad col-xs-11 payment-content1 ">
                        <h2 class="fancy-head-popup d-flex align-items-center justify-content-between">{{ trans('messages.checkout.bill_new_address') }}<i class="icon-close icon close_bill"></i></h2>
                        <div class="file row">
                            <div class="uoload_frm col-lg-12 col-12">
                                <div class="form-group">
                					<label class="pay-p">{{ trans('messages.checkout.full_name') }}<em class="text-danger">*</em></label>
                					<input class="text form-control" id="billing_name" name="billing_name" style="width: 100%;height: 34px;" value=""  type="text">
                				</div>
                            </div>

                              <div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
                					<label class="pay-p">{{ trans('messages.checkout.address') }}<em class="text-danger">*</em></label>
                					<input id="billing_address_1" name="billing_address_1" class="text form-control" value="" style="width: 100%;height: 34px;" placeholder="{{ trans('messages.checkout.Street_address') }}"   type="text">
                                </div>
        					</div>
        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
                					<label class="pay-p">{{ trans('messages.checkout.address_line') }}</label>
                					<input id="billing_address_2" class="text form-control" value="" style="width: 100%;height: 34px;" type="text">
            					</div>
            				</div>

        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
                					<p class="pay-p">{{ trans('messages.checkout.city') }}<em class="text-danger">*</em></p>
                					<input class="text form-control" id="billing_city" name="billing_city" style="width: 100%;height: 34px;" value="" type="text">
                					</div>
        				    </div>

                            <div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
            					<label class="pay-p">{{ trans('messages.checkout.state') }}<em class="text-danger">*</em></label>
            					<input class="text form-control"  name="billing_state" id="billing_state" style="width: 100%;height: 34px;" value="" type="text">
            					</div>
                            </div>

                            <div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
                                    <label class="pay-p">{{ trans('messages.checkout.country') }}<em class="text-danger">*</em></label>
                                    <select name="country_code" class="select-boxes select-country form-control" id="billing_country" style="width:100%;">
                                    @foreach($country as $countries)
                                        <option value="{{ $countries->short_name }}">{{ $countries->long_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
            					<label class="pay-p">{{ trans('messages.checkout.zip') }}<em class="text-danger">*</em></label>
            					<input class="text form-control" id="billing_zip" name="billing_zip" style="width: 100%;height: 34px;"  value="" type="text">
        					   </div>
                            </div>

        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
                					<label class="pay-p">{{ trans('messages.checkout.phone') }}<em class="text-danger">*</em></label>
                					<input class="text form-control" id="billing_phone" name="billing_phone" style="width: 100%;height: 34px;" value="" type="text">
            					</div>
                            </div>

        					<div class="uoload_frm col-lg-6 col-12">
                                <div class="form-group">
            					<label class="pay-p">{{ trans('messages.checkout.address_nick_name') }}</label>
            					<input class="text form-control" id="billing_address_nick" name="shipping_address_nick"  style="width: 100%;height: 34px;" value=""   type="text">
            					</div>
                            </div>

        					<!-- <span class="uoload_frm">
        					<input checked="" disabled id="billing_default" name="set_default"  value="true" type="checkbox">
        					<label  for="shipping_default" class="pay-p">Make this my primary billing address</label>
        					</span> -->
                            
                        </div>
                        <div class="btns-area text-right">
                            <input type="hidden" id="edit_bill" value="no">
                            <button type="button" class="btn-light btn btn-back cancel_ close_bill">{{ trans('messages.checkout.cancel') }}</button>
                            <button type="button" class="btn-primary btn add_billing edit_billing_address">{{ trans('messages.checkout.save_address') }}</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="add-fancy-back payment-popup2">
                <div class="d-flex align-items-center flex-wrap cls_modal_height">
                    <div class="col-lg-3 col-md-5 col-sm-6 back-white pos-top mar-auto flt-none nopad col-xs-11 payment-content2 ">
                        <h2 class="fancy-head-popup">{{$site_name}}.com</h2>
                        <p style="padding:20px;margin:0px;font-size:13px;" id="checkout_error">Please enter valid address</p>
                        <div class="btns-area text-right">
                            <button type="button" class="btn-blue-fancy btn-back-error">Okay</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@stop
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var publish_key = '{!! $publish_key !!}';
</script>