@extends('template')
@section('main')

<div class="login-back cls_loginpage mt-5" style="margin-bottom: 14px;">
    <div class="clsloginheader">
        <h1 class="sitelogo">
            <a href="{{ url('/') }}"><img src="{{ $logo }}"></a>
          {{ trans('messages.header.login') }}
        </h1>
    </div>
    <div class="login-content loginvalue">
        @if(Session::has('message') && !isset($exception))
        <div class="flash-container  newflash">
            <div class="flash-container1">
                <div class="alert error_alert {{ Session::get('alert-class') }}" role="alert">
                    <a href="#" class="alert-close error_close" data-dismiss="alert"></a>
                    {{ Session::get('message') }}
                </div>

            </div>
        </div>
        @endif
        <div class="social-buttons my-3">
            <a href="{{URL::to('facebookLogin')}}" class="facebook-btn cls_socialicon col-lg-12 col-md-12 text-center mb-2" style="width:100%;">
              {{ trans('messages.login.login_with') }} Facebook
            </a>
            <a href="javascript:;" class="google-btn cls_socialicon text-center google_signin">
              Google
            </a>
            <a href="{{URL::to('twitterLogin')}}" class="twitter-btn cls_socialicon text-center">
              Twitter
            </a>
        </div>
        <div style="clear: both"></div>
        <hr>
        <div class="d-flex flex-column text-center">
            {!! Form::open(['action' => 'UserController@authenticate', 'class' => 'login-form', 'data-action' => 'login', 'id' => '', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}

            <div class="email-form form-group">
               <div class="mb-3">
                {!! Form::email('login_email', '', ['class' => $errors->has('login_email') ? 'text decorative-input inspectletIgnore invalid form-control' : 'text decorative-input inspectletIgnore form-control', 'placeholder' => trans('messages.login.email_address') ]) !!} @if ($errors->has('login_email'))
                <span class="help-block d-block text-left" style="color:red">{{ $errors->first('login_email') }} @endif
                </span>
              </div>
                  <div class="mb-3">
                <input type='hidden' name='next' value="{{@$next}}" /> {!! Form::input('password', 'login_password','',['class' => $errors->has('password') ? 'text decorative-input inspectletIgnore invalid form-control' : 'text decorative-input inspectletIgnore form-control', 'placeholder' => trans('messages.login.password'), 'data-hook' => 'signin_password']) !!} @if ($errors->has('login_password'))
                <span class="help-block d-block text-left" style="color:red">{{ $errors->first('login_password') }} @endif
                </span>
              </div>
            </div>

            <button class="btn mt-3 btn-signup">{{ trans('messages.header.login') }}</button>

            {!! Form::close() !!}
        </div>
        <div class="others my-3">
            {{ trans('messages.login.new_to') }} {{ $site_name }}? <a href="{{ url('signup') }}">{{ trans('messages.login.signup_now') }}</a> |
            <a href="{{ url('forgot') }}" class="nav-link cls_joinlogin login_popup_head">{{ trans('messages.login.forget_password') }}?</a>

        </div>
        <div class="row">
            <div class="selling">{{ trans('messages.login.interested_selling') }}? <a href="{{ url('merchant/signup') }}">{{ trans('messages.login.get_started') }}</a></div>
        </div>

    </div>

</div>
@endsection