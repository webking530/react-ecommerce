@extends('template')
@section('main')
<div class="login-back cls_loginpage mt-5" ng-controller="login_signup">
    <div class="clsloginheader">
        <h1 class="sitelogo">
            <a href="{{ url('/') }}">
                <img src="{{ $logo }}">
            </a>
        </h1>
    </div>
    <div class="login-content loginvalue">
        <div class="" style="{{(!empty(Session::get('user_data')))?'display:none':'display:block'}};">
            @if(Session::has('message') && !isset($exception))
            <div class="flash-container  newflash">
                <div class="flash-container1">
                    <div class="alert error_alert {{ Session::get('alert-class') }} success_msg" role="alert">
                        <a href="#" class="alert-close error_close" data-dismiss="alert">
                        </a>
                        {{ Session::get('message') }}
                    </div>
                </div>
            </div>
            @endif
            <p>{{ trans('messages.login.join_desc') }}</p>
            <div class="social-buttons my-3">
                <a href="{{URL::to('facebookLogin')}}" class="facebook-btn cls_socialicon col-lg-12 col-md-12 text-center mb-2" style="width:100%;">
                    {{ trans('messages.login.login_with') }} Facebook
                </a>
                <a href="javascript:;" class="google-btn cls_socialicon col-md-6 col-lg-6 text-center google_signin">
                    Google
                </a>
                <a href="{{URL::to('twitterLogin')}}" class="twitter-btn cls_socialicon col-md-6 col-lg-6 text-center">
                    Twitter
                </a>
            </div>
            <div style="clear: both">
            </div>
            <hr>
            <div class="d-flex flex-column text-center">
                <form name="form" novalidate class="form-group">
                    <div class="mb-3">
                        <input type="email" class="text form-control" id="email_id" placeholder="{{trans('messages.login.email_address')}}" name="email" ng-model="email" required>
                        <span class="help-inline d-block text-left" ng-show="required_email" style="color:red">{{ trans('messages.login.required_email') }}</span>
                        <span class="help-inline d-block text-left" ng-show="invalid_email" style="color:red">{{ trans('messages.login.invalid_email') }}</span>
                        <span class="help-inline d-block text-left" ng-show="exist_mail" style="color:red">{{ trans('messages.login.exist_mail') }}</span>
                    </div>
                    <button class="btn-signup btn btn-info btn-block btn-round" type="submit" ng-click="email_check(form)">{{ trans('messages.header.join') }} {{ $site_name }}</button>
                </form>
            </div>
            <div class="others mb-3">
                {{ trans('messages.login.already_an') }} {{ $site_name }}? <a href="{{ url('login') }}">{{ trans('messages.header.login') }}</a>
            </div>
            <div class="row">
                <div class="selling">{{ trans('messages.login.interested_selling') }}? <a href="{{ url('merchant/signup') }}">{{ trans('messages.login.get_started') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="login-back sign_register cls_loginpop" style="{{(!empty(Session::get('error_code')) && Session::get('error_code') == 1)?'display:block':'display:none'}};background-color:rgba(0,0,0,0.1) !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="signup-content m-0">
                    <h1 class="sitelogo">
                        <a href="{{ url('/') }}">
                            <img src="{{ $logo }}">
                        </a>
                    </h1>
                    <h2 class="modal-title">{{ trans('messages.login.looking_good') }}</h2>
                    {!! Form::open(['action' => 'UserController@create', 'class' => 'signup-form form-group', 'data-action' => 'Signup', 'id' => 'user_new', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
                    <div class="error" style="margin:-10px 0 20px;display:none;">
                    </div>
                    <div class="signup-content_in">
                        <div class="hastext col-lg-12 my-3 p-0">
                            <label class="label">{{ trans('messages.login.full_name') }}<span class="text-danger">*</span>
                            </label>
                            {!! Form::text('full_name', old('full_name',session('user_data.full_name')), ['class'=>'text sffocus form-control', 'placeholder'=>trans('messages.login.full_name'),'value'=>'','autocomplete'=>'off']) !!}
                            @if ($errors->has('full_name'))<p class="help-block" style="color:red">{{ $errors->first('full_name') }}</p> @endif
                        </div>
                        <div class="with-label col-lg-12 my-3 p-0">
                            <label class="label">{{ trans('messages.login.email') }}  <span class="text-danger">*</span>
                            </label>
                            {!! Form::text('email', old('email',session('user_data.email')), ['id'=>'email_signup','class'=>'text form-control', 'placeholder'=>trans('messages.login.email_address'),'autocomplete'=>'off']) !!}
                            @if ($errors->has('email'))<p class="help-block" style="color:red">{{ $errors->first('email') }}</p> @endif
                        </div>
                        @if(!@Session::get('user_data')['source'])
                        <div class="with-label col-lg-12 my-3 p-0">
                            <label class="label">{{ trans('messages.login.password') }}  <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="text form-control" name="user_password"  placeholder="{{ trans('messages.login.create_password') }}">
                            @if ($errors->has('user_password'))<p class="help-block" style="color:red">{{ $errors->first('user_password') }}</p> @endif
                        </div>
                        @endif
                        <span class="loader" style="display: none;">
                            <b>
                            </b> <em>
                            </em>
                        </span>
                        <div class="with-label col-lg-12 my-3 p-0">
                            <label class="label">{{ trans('messages.login.user_name') }}  <span class="text-danger">*</span>
                            </label>
                            {!! Form::text('user_name', old('user_name',session('user_data.user_name')), ['id'=>'user_name','class'=>'text form-control','placeholder'=>trans('messages.login.choose_username'),'autocomplete'=>'off']) !!}
                            @if ($errors->has('user_name'))<p class="help-block" style="color:red">{{ $errors->first('user_name') }}</p> @endif
                        </div>
                        <button class="btn btn-signup my-3">{{ trans('messages.header.join') }} {{$site_name }}</button>
                        <input type="hidden" class="next_url" value="/">
                    </div>
                    {!! Form::close() !!}
                    <div class="terms pb-3">
                        @lang('messages.login.signup_agree') {{ $site_name }} <a href="{{ url($company_pages->where('id','2')->first()->url) }}">{{ trans('messages.login.terms_service') }}</a> and <a href="{{url($company_pages->where('id','3')->first()->url)}}">{{ trans('messages.login.privacy_policy') }}</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{ Session::put('error_code', '') }}
@endsection