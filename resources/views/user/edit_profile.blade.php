@extends('settings_template')
@section('main')
    <div class="col-lg-9 col-12 setting-rightbar  ed_contpro">
        <div class="cls_setting1 pb-4">
                {!! Form::open(['action' => 'UserController@update', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true' , 'enctype'=> 'multipart/form-data']) !!}
                <div class="back-white csv_right nopad">
                    <h2 class="csv_tit">{{ trans('messages.profile.edit_profile') }}</h2>
                    <div class="d-flex pt-4 flex-wrap">
                        <div class="col-lg-3 col-12">
                            <h2 class="stit">{{ trans('messages.profile.profile') }}</h2>
                        </div>
                        <div class="col-lg-9">
                            <div class="edit-setting nt_pro">
                                <label class="label">{{ trans('messages.profile.photo') }}</label>
                                <div class="user_img user_imgnn d-flex flex-wrap ju">
                                    <div class="cls_editimg"> 
                                        @if(@$profile[0]->src)
                                        <img src="{{ @$profile[0]->src }}" width="100px" height="100px" class="img-round flt-left">
                                        @else
                                        <img src="{{ url('image/profile.png') }}" width="100px" height="100px" class="img-round flt-left">
                                        @endif
                                    </div>
                                    <div class="cls_editimgright">
                                        <div class="photo-func">
                                            <button class="btns-gray btn btn-change" onclick="$('.photo-func').hide();$('.upload-file').show();return false;">{{ trans('messages.profile.upload_photo') }}</button>
                                            <button class="btn-delete" id="delete_profile_image" style="display:none">{{ trans('messages.profile.delete_photo') }}</button>
                                        </div>
                                        @if ($errors->has('profile_image'))
                                        <span class="help-inline" style="color:red">
                                            {{ $errors->first('profile_image') }}
                                        </span>
                                        @endif
                                        <div class="upload-file" style="display: none;">
                                            <input id="uploadavatar" type="file" name="profile_image">
                                            <span class="uploading" style="display:none">Uploading...</span>
                                            <span class="description">{{ trans('messages.profile.uploading_type') }} JPG, GIF or PNG. {{ trans('messages.profile.uploading_size') }} 700K</span>
                                            <button class="btn-cancel btn cacelbtton" type="button" onclick="$('.photo-func').show();$('.upload-file').hide();">{{ trans('messages.profile.cancel') }}</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <input type="hidden" id='user_id' name="id" value= "{{Auth::user()->id}}" />
                            <div class="edit-setting form-group mt-3">
                                <label class="label">{{ trans('messages.profile.full_name') }}</label>
                                {!! Form::text('full_name', Auth::user()->full_name, ['class' => 'text form-control']) !!}
                                <small class="comment">{{ trans('messages.profile.your_real_name') }}</small>
                                @if ($errors->has('full_name'))
                                <span class="help-inline">
                                    {{ $errors->first('full_name') }}
                                </span>
                                @endif
                            </div>
                            <div class="edit-setting form-group ">
                                <label class="label">{{ trans('messages.profile.user_name') }}</label>
                                {!! Form::text('user_name', Auth::user()->user_name, ['class' => 'text form-control']) !!}
                                @if ($errors->has('user_name'))
                                <span class="help-inline">
                                    {{ $errors->first('user_name') }}
                                </span>
                                @endif
                            </div>
                            <div class="edit-setting form-group ">
                                <label class="label">{{ trans('messages.profile.website') }}</label>
                                {!! Form::text('website', Auth::user()->website, ['class' => 'text form-control']) !!}
                            </div>
                            <div class="edit-setting form-group">
                                <label class="label">{{ trans('messages.profile.location') }}</label>
                                {!! Form::text('location', Auth::user()->location, ['class' => 'text form-control']) !!}
                            </div>
                            <div class="edit-setting edit_setting form-group">
                                <label class="label">{{ trans('messages.profile.bio') }}</label>
                                {!! Form::textarea('bio', Auth::user()->bio, ['class' => 'text form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex pt-4 flex-wrap">
                        <div class="col-lg-3 col-12">
                            <h2 class="stit">{{ trans('messages.profile.account') }}</h2>
                        </div>
                        <div class="col-lg-9" style="">
                            <div class="edit-setting form-group">
                                <label class="label">{{ trans('messages.profile.email_address') }}</label>
                                {!! Form::text('email', Auth::user()->email, ['class' => 'text form-control','id'=>'email_id']) !!}
                                <small class="comment">{{ trans('messages.profile.email_display') }}</small>
                                @if(Auth::user()->status !='Active')
                                <small class="comment">{{ trans('messages.profile.your_email_address') }} {{Auth::user()->email}} {{ trans('messages.profile.pending_confirmation') }}  </small>
                                <a id="resend_confirmation" href="javascript:;">{{ trans('messages.profile.resend_confirmation') }} </a>
                                <br/>
                                <span class="resend_confirmation" style="display: none"></span>
                                @endif
                                @if ($errors->has('email'))
                                <span class="help-inline">
                                    {{ $errors->first('email') }}
                                </span>
                                @endif
                            </div>
                            <div class="edit-setting birthlist form-row">
                                <label class="label col-lg-12">{{ trans('messages.profile.birthday') }}</label>
                                <div class="form-group col-lg-4 col-12">
                                <select id="birthday_year" name="birthday_year" class="select-boxes2 form-control">
                                    <option value="">{{ trans('messages.profile.year') }}</option>
                                    @for($i=date('Y');$i>=1900;$i--)        
                                    <option value="{{$i}}"  {{ ( @$dob[0] == $i ) ? 'selected' : ''}}>{{$i}}</option>             
                                    @endfor
                                </select>   </div>
                                <div class="form-group col-lg-4 col-12">
                                <select id="birthday_month" name="birthday_month" class="select-boxes2 form-control">
                                    <?php $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");  
                                    $i = 1;
                                    ?>
                                    <option value="">{{ trans('messages.profile.month') }}</option>  
                                    @foreach ($months as $key =>$value) 
                                    <option value="{{$i}}" {{ ( @$dob[1] == $i ) ? 'selected' : ''}}>{{$value}}</option>  
                                    <?php $i++ ?>
                                    @endforeach                                           
                                </select> </div>
                                <div class="form-group col-lg-4 col-12">
                                <select id="birthday_day" name="birthday_day" class="select-boxes2 form-control">
                                    <option value="">{{ trans('messages.profile.day') }}</option>                        
                                    @for($i = 1; $i<=31; $i++)
                                    <option value="{{$i}}" {{ ( @$dob[2] == $i ) ? 'selected' : ''}}>{{$i}}</option> 
                                    @endfor

                                </select> </div>
                                <div class="error">
                                    @if ($errors->has('birthday_day') || $errors->has('birthday_month') || $errors->has('birthday_year'))
                                    <span class="help-inline" style="color:red">
                                        {{ $errors->first('birthday_day') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="edit-setting label_edit genderlist form-row">
                                <label class="label col-lg-12">{{ trans('messages.profile.gender') }}</label>
                                <div class="form-check form-check-inline custom-control custom-radio">
                                    <input name="gender" value="male" id="gender1" type="radio" {{ ( @$gender == 'Male' ) ? 'checked' : ''}} class="form-check-input custom-control-input">
                                    <label for="gender1" class="form-check-label custom-control-label">{{ trans('messages.profile.male') }}</label>
                                </div>
                                <div class="form-check form-check-inline custom-control custom-radio">
                                    <input name="gender" value="female" id="gender2" type="radio" {{ ( @$gender == 'Female' ) ? 'checked' : ''}} class="form-check-input custom-control-input">
                                    <label for="gender2" class="form-check-label custom-control-label">{{ trans('messages.profile.female') }}</label>
                                </div>
                                <div class="form-check form-check-inline custom-control custom-radio">
                                <input name="gender" value="other" id="gender3"  type="radio" {{ ( @$gender == 'Other' ) ? 'checked' : ''}} class="form-check-input custom-control-input">
                                    <label for="gender3" class="form-check-label custom-control-label">{{ trans('messages.profile.unspecified') }}</label>
                                </div>
                            </div>

                            <div>
                                @if ($errors->has('gender'))
                                <span class="help-inline" style="color:red">
                                    {{ $errors->first('gender') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex pt-4 flex-wrap">
                        <div class="col-lg-3 col-12">
                            <h2 class="stit">{{ trans('messages.profile.time') }}</h2>
                        </div>
                        <div class="col-lg-9" style="">
                            <div class="form-group">
                                <label class="label">{{ trans('messages.profile.time_zone') }}</label>
                                <select class="selct-zone form-control" name="timezone">
                                    <option value="">{{ trans('messages.profile.choose_timezone') }}</option>
                                    @foreach ($timezone as $key => $value)
                                    <option value="{{$value['value']}}"  {{ ( @$user['timezone'] == $value['value'] ) ? 'selected' : ''}} >{{$value['name']}}</option>
                                    @endforeach               

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="btn-area cls_profileedit btn-fix for_savebot">
                        <div class="savebot">
                            <button class="blue-btn btn-add" id="save_account" >
                                <b>{{ trans('messages.profile.save_profile') }}</b>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</main>

    <!-- <a href="javascript:void(0)" id="scroll-to-top" class="scroll-top">
        <span>
          {{ trans('messages.home.jump_top') }}
      </span>
    </a> -->

@stop
