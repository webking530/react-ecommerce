@extends('settings_template')
@section('main')
<div class="col-lg-9 col-12 setting-rightbar  ed_contpro">
    <div class="cls_setting1">
        <h2 class="csv_tit"><b>{{ trans('messages.profile.change_password') }}</b></h2> 
        {!! Form::open(['url' =>'buyer/update_password', 'class' => 'form-horizontal','id'=>'form']) !!}
        <div class="d-flex pt-4 flex-wrap">
            <div class="col-lg-3 col-12">
                <h2 class="stit"></h2>
            </div>

            <div class="col-lg-9" id='change_pwd'>
                <div class="edit-setting form-group">
                    <label class="label">{{ trans('messages.profile.current_password') }}</label>
                    {{ Form::password('old_password', ['placeholder' => trans('messages.profile.current_password'),'class' => 'form-control']) }}
                    <span class="text-danger">{{ $errors->first('old_password') }}</span>

                </div>
                <div class="edit-setting form-group">
                    <label class="label">{{ trans('messages.profile.new_password') }}</label>
                    {{ Form::password('new_password', ['placeholder' => trans('messages.profile.new_password'),'class' => 'form-control']) }}
                    <small class="comment">{{ trans('messages.profile.new_password') }}, {{ trans('messages.profile.at_least_characters') }}</small>
                    <span class="text-danger">{{ $errors->first('new_password') }}</span>
                </div>
                <div class="edit-setting form-group">
                    <label class="label">{{ trans('messages.profile.confirm_password') }}</label>
                    {{ Form::password('password_confirmation', ['placeholder' => trans('messages.profile.confirm_password'),'class' => 'form-control']) }}
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                </div>
            </div>
        </div>
        <div class="btn-pwd profile col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button class="btns-blue-embo btn-add"><b>{{ trans('messages.profile.change_password') }}</b></button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<a href="javascript:void(0)" id="scroll-to-top" class="scroll-top">
    <span>
		{{ trans('messages.home.jump_top') }}
	</span>
</a>
</main>
@stop