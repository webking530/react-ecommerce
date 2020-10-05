@extends('template')
@section('main')
<div class="login-back cls_loginpage cls_forgot p-4 mt-5">
    <div class="login-content">
        @if(Session::has('message') && !isset($exception))
        <div class="flash-container">
            <div class="alert {{ Session::get('alert-class') }}" role="alert">
                <a href="#" class="alert-close" data-dismiss="alert"></a>
                {{ Session::get('message') }}
            </div>
        </div>
        @endif
        <h2 class="sitelogo mb-4">
        <a href="{{ url('/') }}">
            <img src="{{ $logo }}">
        </a>
        @lang('messages.login.forget_password') </h2>
        {!! Form::open(['url' => url('forgot_password')]) !!}
        <div class="email-form form-group">
            {!! Form::email('email', '', ['class' => 'form-control ', 'placeholder' => trans('messages.login.email_address')]) !!}
            @if ($errors->has('email'))
            <p class="help-block" style="color:red">{{ $errors->first('email') }}</p>
            @endif
        </div>
        <button class="btn-signup btn btn-info btn-block btn-round"> @lang('messages.login.send') </button>
        {!! Form::close() !!}
        <div class="others mt-3">
            {{ trans('messages.login.new_to') }} {{ $site_name }}? <a href="{{ url('signup') }}"> @lang('messages.login.signup_now') </a>
        </div>
    </div>
</div>
@endsection