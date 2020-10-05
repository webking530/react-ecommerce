@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Api Credentials
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Api Credential</a></li>
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
              <h3 class="box-title">Api Credentials Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => 'admin/api_credentials', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_facebook_client_id" class="col-sm-3 control-label">Facebook Client ID<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('facebook_client_id', $result[0]->value, ['class' => 'form-control', 'id' => 'input_facebook_client_id', 'placeholder' => 'Facebook Client ID']) !!}
                    <span class="text-danger">{{ $errors->first('facebook_client_id') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_facebook_client_secret" class="col-sm-3 control-label">Facebook Client Secret<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('facebook_client_secret', $result[1]->value, ['class' => 'form-control', 'id' => 'input_facebook_client_secret', 'placeholder' => 'Facebook Client Secret']) !!}
                    <span class="text-danger">{{ $errors->first('facebook_client_secret') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_google_client_id" class="col-sm-3 control-label">Google Client ID<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('google_client_id', $result[2]->value, ['class' => 'form-control', 'id' => 'input_google_client_id', 'placeholder' => 'Google Client ID']) !!}
                    <span class="text-danger">{{ $errors->first('google_client_id') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_google_client_secret" class="col-sm-3 control-label">Google Client Secret<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('google_client_secret', $result[3]->value, ['class' => 'form-control', 'id' => 'input_google_client_secret', 'placeholder' => 'Google Client Secret']) !!}
                    <span class="text-danger">{{ $errors->first('google_client_secret') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_twitter_client_id" class="col-sm-3 control-label">Twitter Client ID<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('twitter_client_id', $result[4]->value, ['class' => 'form-control', 'id' => 'input_twitter_client_id', 'placeholder' => 'Twitter Client ID']) !!}
                    <span class="text-danger">{{ $errors->first('twitter_client_id') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_twitter_client_secret" class="col-sm-3 control-label">Twitter Client Secret<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('twitter_client_secret', $result[5]->value, ['class' => 'form-control', 'id' => 'input_twitter_client_secret', 'placeholder' => 'Twitter Client Secret']) !!}
                    <span class="text-danger">{{ $errors->first('twitter_client_secret') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_cloud_name" class="col-sm-3 control-label">Cloudinary Name <em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('cloud_name', $result[6]->value, ['class' => 'form-control', 'id' => 'input_cloud_name', 'placeholder' => 'Cloud Name']) !!}
                    <span class="text-danger">{{ $errors->first('cloud_name') }}</span>
                  </div>
                </div>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="input_cloud_key" class="col-sm-3 control-label">Cloudinary Key <em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('cloud_key', $result[7]->value, ['class' => 'form-control', 'id' => 'input_cloud_key', 'placeholder' => 'Cloudinary Key']) !!}
                    <span class="text-danger">{{ $errors->first('cloud_key') }}</span>
                  </div>
                </div>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="input_cloud_secret" class="col-sm-3 control-label">Cloudinary Secret <em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('cloud_secret', @$result[8]->value, ['class' => 'form-control', 'id' => 'input_cloud_secret', 'placeholder' => 'Cloudinary Secret']) !!}
                    <span class="text-danger">{{ $errors->first('cloud_secret') }}</span>
                  </div>
                </div>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="input_cloud_secret" class="col-sm-3 control-label">Cloudinary BaseUrl <em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('cloud_base_url', @$result[9]->value, ['class' => 'form-control', 'id' => 'input_cloud_base', 'placeholder' => 'Cloudinary BaseUrl']) !!}
                    <span class="text-danger">{{ $errors->first('cloud_base_url') }}</span>
                  </div>
                </div>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="input_cloud_secret" class="col-sm-3 control-label">Cloudinary SecureUrl <em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('cloud_secure_url', @$result[10]->value, ['class' => 'form-control', 'id' => 'input_cloud_secure', 'placeholder' => 'Cloudinary SecureUrl']) !!}
                    <span class="text-danger">{{ $errors->first('cloud_secure_url') }}</span>
                  </div>
                </div>
              </div>

              <div class="box-body">
                <div class="form-group">
                  <label for="input_cloud_secret" class="col-sm-3 control-label">Cloudinary ApiUrl <em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('cloud_api_url', @$result[11]->value, ['class' => 'form-control', 'id' => 'input_cloud_api', 'placeholder' => 'Cloudinary ApiUrl']) !!}
                    <span class="text-danger">{{ $errors->first('cloud_api_url') }}</span>
                  </div>
                </div>
              </div>

              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-default" name="cancel" value="cancel">Cancel</button>
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
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