@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Site Settings
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Site Settings</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Site Settings Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => 'admin/site_settings', 'class' => 'form-horizontal', 'files' => true]) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Site Name<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('site_name', $result[0]->value, ['class' => 'form-control', 'id' => 'input_site_name', 'placeholder' => 'Site Name']) !!}
                    <span class="text-danger">{{ $errors->first('site_name') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Add code to the < head >(for tracking codes such as google analytics)</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('head_code', $result[8]->value, ['class' => 'form-control', 'id' => 'input_head_code', 'placeholder' => 'Head Code']) !!}
                    <span class="text-danger">{{ $errors->first('head_code') }}</span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="input_site_name" class="col-sm-3 control-label">Version</label>
                  <div class="col-sm-6">
                    {!! Form::text('version', $result[1]->value, ['class' => 'form-control', 'id' => 'input_version', 'placeholder' => 'Version']) !!}
                    <span class="text-danger">{{ $errors->first('version') }}</span>
                  </div>
                </div>

                <div class="form-group">
                 <label for="input_logo" class="col-sm-3 control-label">Logo</label>
                 <em>Size: 92x18</em>
                 <div class="col-sm-6">
                 <input type="file" name="logo" class="form-control"  id = "input_logo" >
                   <span class="text-danger">{{ $errors->first('logo') }}</span>
                   <img src="{{ $logo }}" style="width: 140px;height: auto;">
                 </div>
               </div>
             
               <div class="form-group">
                 <label for="input_email_logo" class="col-sm-3 control-label">Email Logo</label>
                 <em>Size: 92x18</em>
                 <div class="col-sm-6">
                 <input type="file" name="email_logo" class="form-control"  id = "input_email_logo" >
                   <span class="text-danger">{{ $errors->first('email_logo') }}</span>
                   <img src="{{ @$email_logo }}" style="width: 140px;height: auto;">
                 </div>
               </div>
               <div class="form-group">
                 <label for="input_favicon" class="col-sm-3 control-label">Favicon</label>
                 <em>Size: 16x16</em>
                 <div class="col-sm-6">
                 <input type="file" name="favicon" class="form-control"  id = "input_favicon" >
                   <span class="text-danger">{{ $errors->first('favicon') }}</span>
                   <img class="img" style="width: 50px;height: auto;" src="{{ @$favicon }}">
                 </div>
               </div>


                <div class="form-group">

                  <label for="input_default_language" class="col-sm-3 control-label">File Upload Driver</label>
                  <div class="col-sm-6">
                    {!! Form::select('upload_driver',['php' => 'Local', 'cloudinary' => 'Cloudinary'],$default_upload_driver, ['class' => 'form-control', 'id' => 'input_default_language']) !!}
                    <span class="text-danger">{{ $errors->first('upload_driver') }}</span>
                    <p class="text-danger">For Cloudinary driver, free package maximum upload size is 10MB. To increase the upload size, please check the Cloudinary purchase plans.</p>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_default_currency" class="col-sm-3 control-label">Default Currency</label>
                  <div class="col-sm-6">
                    {!! Form::select('default_currency', $currency, $default_currency, ['class' => 'form-control', 'id' => 'input_default_currency']) !!}
                    <span class="text-danger">{{ $errors->first('default_currency') }}</span>
                  </div>
                </div>
               
                <div class="form-group">
                  <label for="input_default_language" class="col-sm-3 control-label">Default Language</label>
                  <div class="col-sm-6">
                    {!! Form::select('default_language', $language, $default_language, ['class' => 'form-control', 'id' => 'input_default_language']) !!}
                    <span class="text-danger">{{ $errors->first('default_language') }}</span>
                  </div>
                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
               <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
                <button type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel</button>
              </div>
              <!-- /.box-footer -->
            {!! Form::close() !!}
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@stop