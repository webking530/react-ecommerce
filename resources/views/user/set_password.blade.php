@extends('settings_template')
@section('main')

<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 pad-min min-size-long setting-rightbar change_pwd">
<div class="content">
<h2 class="ptit"><b>Change Password</b></h2>
 {!! Form::open(['url' =>'users/set_password', 'class' => 'form-horizontal','id'=>'password-form']) !!}
 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad bor-bot-ash" style="padding-bottom:25px !important;">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
<h2 class="stit"></h2>
</div>
 <input id="id" name="id" type="hidden" value="{{ $result->id }}">
<input id="token" name="token" type="hidden" value="{{ $token }}">
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
<div class="edit-setting">
<label>New Password</label>
   {!! Form::password('password', ['id' => 'new_password',  'class' => $errors->has('password') ? 'invalid' : '']) !!}
              
 <span class="text-danger">{{ $errors->first('password') }}</span>

</div>
<div class="edit-setting">
<label>Confirm Password</label>
 {!! Form::password('password_confirmation', ['id' => 'user_password_confirmation', 'class' => '']) !!}
 <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
</div>

</div>
</div>
<div class="btn-area profile col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button class="btns-blue-embo " style="float:right;"><b>Save Password</b></button>
      </div>
  {!! Form::close() !!}   
</div>
</div>
</div>

</div>
</main>

<style>
.setting-sidebar{ visibility:hidden; }
</style>
@stop