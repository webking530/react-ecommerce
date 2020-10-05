@extends('merchant_signuptemplate')

@section('main')

<div id="container-wrapper" class="merchant_signup col-lg-7 col-12" role="main"  ng-controller="merchant_signup" ng-cloak>
    <div class="container sign step1" >
        <h2 class="py-4 text-center">{{ trans('messages.merchant.create_your') }} <b>{{ trans('messages.merchant.merchant') }} {{ trans('messages.merchant.account') }}</b></h2> 
        <div id="content">
            <ol class="step d-flex justify-content-around align-items-center">
                <li class="order1 py-4">{{ trans('messages.merchant.account_details') }}</li>
                <li class="order2 py-4" style="opacity: 0.5">{{ trans('messages.merchant.your_basic_info') }}</li>
            </ol>
            {!! Form::open(['action' => 'MerchantController@create', 'class' => 'signup-form pb-4', 'data-action' => 'Signup', 'id' => 'user_new', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}

           <!--  <form action= name="signup-form" id="merchant_user_new" method="post">  -->
                <div class="frm step_1 sign-out">
                    @if(@$userid =='')
                    <div class="form-group">
                        <input type="text" name="store_name"  ng-model='store_name' class="form-control" placeholder="{{ trans('messages.merchant.your_store_name') }}">
                        <span class="help-inline" ng-show="required_store" style="color:red">
                            {{ trans('messages.merchant.required_store') }}
                        </span>
                        @if ($errors->has('store_name'))
                            <span class="help-inline" style="color:red">
                                {{ $errors->first('store_name') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-lg-6 col-12">
                            <input type="text" name="full_name" ng-model='full_name' class="form-control" placeholder="{{ trans('messages.merchant.full_name') }} ">
                            <span class="help-inline" ng-show="required_fullname" style="color:red">
                                {{ trans('messages.merchant.required_fullname') }}
                            </span>
                            @if ($errors->has('full_name'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('full_name') }}
                                </span>
                            @endif 
                        </div>
                         

                         <div class="form-group col-lg-6 col-12">
                            <input type="text" name="user_name" ng-model='user_name' class="form-control" placeholder="{{ trans('messages.merchant.user_name') }}">
                            <span class="help-inline" ng-show="required_username" style="color:red">
                                {{ trans('messages.merchant.required_username') }}                            
                            </span>
                            <span class="help-inline" ng-show="exist_user" style="color:red" >
                                {{ trans('messages.merchant.exist_user') }} 
                            </span>
                            @if ($errors->has('user_name'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('user_name') }}
                                </span>
                            @endif
                        </div>
                    </div>
                     <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="{{ trans('messages.merchant.email_address') }}" value="" ng-model="email">
                        <span class="help-inline" ng-show="required_email" style="color:red">
                            {{ trans('messages.merchant.required_email') }}  
                        </span>
                        <span class="help-inline" ng-show="invalid_email" style="color:red" >
                            {{ trans('messages.merchant.invalid_email') }}  
                        </span>
                        <span class="help-inline" ng-show="exist_mail" style="color:red" >
                            {{ trans('messages.merchant.exist_mail') }}
                        </span>
                        @if ($errors->has('email'))
                            <span class="help-inline" style="color:red">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                    

                    <div class="form-group">
                        <input type="password" name="password" ng-model='password' class="form-control" placeholder="{{ trans('messages.merchant.password') }}">
                        <span class="help-inline" ng-show="required_password" style="color:red">
                            {{ trans('messages.merchant.required_password') }}
                        </span>
                        <span class="help-inline" ng-show="min_len_password" style="color:red">
                             {{ trans('messages.merchant.min_len_password') }}
                        </span>
                        @if ($errors->has('user_password'))
                            <span class="help-inline" style="color:red">
                                {{ $errors->first('user_password') }}
                            </span>
                        @endif
                    </div>
                   
                    @else 
                     <div class="form-group">
                        <input type="text" name="store_name" ng-model='store_name' class="form-control" placeholder="{{ trans('messages.merchant.your_store_name') }}">
                        <span class="help-inline" ng-show="required_store" style="color:red">
                            {{ trans('messages.merchant.required_store') }}
                        </span>
                        @if ($errors->has('store_name'))
                            <span class="help-inline" style="color:red">
                                {{ $errors->first('store_name') }}
                            </span>
                        @endif
                    </div>
                    
                    <input type="hidden" name="user_id"  class="text" value="{{@$userid}}">

                     <div class="form-group">
                        <input type="text" name="full_name" ng-model='full_name' class="form-control" placeholder="{{ trans('messages.merchant.full_name') }} ">
                            <span class="help-inline" ng-show="required_fullname" style="color:red">
                                {{ trans('messages.merchant.required_fullname') }}
                            </span>
                        @if ($errors->has('full_name'))
                            <span class="help-inline" style="color:red">
                                {{ $errors->first('full_name') }}
                            </span>
                        @endif 
                    </div>
                     
                    @endif

                    
                    <div class="btn-area d-flex justify-content-between align-items-center flex-wrap">
                        <div class="">
                            <div class="custom-control custom-checkbox">
                               
                                <input type="checkbox" name='terms' id="terms_chk" class="custom-control-input" ng-model='terms'>
                                 <label for="terms_chk" class="custom-control-label">
                                {{ trans('messages.merchant.agree_to') }} <a href="{{ url('terms_of_merchant_sale') }}"> {{ trans('messages.merchant.terms_conditions_merchant') }}</a>.
                                    </label>
                            </div>
                            <span class="help-inline" ng-show="required_terms" style="color:red;float:left" >{{ trans('messages.merchant.required_terms') }}</span>
                        </div>
                        <div class="cls_merchantnext">
                        @if(@$userid =='') 
                            <button class="btn-green btn btn-block btn-round btn-signup" type="button" ng-click="account_details(form)" id='account_details' ng-disabled="!terms">{{ trans('messages.merchant.next') }}</button>
                        @else
                            <button class="btn-green btn btn-block btn-round btn-signup" type="button" ng-click="account_detail(form)" id='account_details' ng-disabled="!terms" >{{ trans('messages.merchant.next') }}</button>
                        @endif

                        
                        </div>
                    </div>
                        
                    
                </div>

                <div class="frm step_2 d-none">
                    <div class="row">
                        <div class="form-group col-lg-6 col-12">
                            <input type="text" name="address_line" ng-model='address_line' class="form-control" placeholder="{{ trans('messages.merchant.address') }}">
                            @if ($errors->has('address_line'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('address_line') }}
                                </span>
                            @endif 
                        </div>

                        <div class="form-group col-lg-6 col-12">
                            <input type="text" name="address_line2" ng-model='address_line2' class="form-control" placeholder="{{ trans('messages.merchant.address_line') }}">
                        </div>
                    </div>
                    <div class="row">
                         <div class="form-group col-lg-6 col-12">
                            <input type="text" name="city" ng-model='city' class="form-control" placeholder="{{ trans('messages.merchant.city') }}">
                            @if ($errors->has('city'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('city') }}
                                </span>
                            @endif 
                        </div>

                         <div class="form-group col-lg-6 col-12">
                            <input type="text" name="postal_code" ng-model='postal_code' class="form-control" placeholder="{{ trans('messages.merchant.postal_code') }}">
                            @if ($errors->has('postal_code'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('postal_code') }}
                                </span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group">
                            <input type="text" class="form-control" id="state" ng-model='state' name='state' placeholder="{{ trans('messages.merchant.state') }}">
                             @if ($errors->has('state'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('state') }}
                                </span>
                            @endif 
                    </div>
                    <div class="form-group">
                        <select name="country" id="select-country" class="form-control">
                                <option value="">{{ trans('messages.merchant.select_country') }}</option>
                            @foreach ($country as $key => $value)
                                   <option value="{{$value['short_name']}}" {{ $value['short_name'] == $countryCode ? 'selected' : ''}} >{{$value['long_name']}}</option>
                            @endforeach 
                            
                         </select>
                            @if ($errors->has('country'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('country') }}
                                </span>
                            @endif 

                    </div>
                    <div class="form-group">
                        <input type="text" name="phone_number" ng-model='phone_number' class="form-control" placeholder="{{ trans('messages.merchant.telephone_number') }}" data-confirmed="False" data-value="" numbers-only>
                            @if ($errors->has('phone_number'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('phone_number') }}
                                </span>
                            @endif 
                    </div>
                    
                    <div class="btn-area d-flex justify-content-between align-items-center flex-wrap">
                        <button class="btn-back btn  btn-round" id="account_details_back">{{ trans('messages.merchant.back') }}</button>
                        <button class="btn-green btn btn-round " type='submit' id="create_account" disabled>{{ trans('messages.merchant.create_account') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- / content -->
            @if(@$userid =='')
                <p class="another my-2 text-center">{{ trans('messages.merchant.have_account') }} <a href="{{url('/login')}}">{{ trans('messages.header.sign_in') }}</a></p>
            @endif
    </div>
</div>
    <!-- / container -->
</main>

@stop