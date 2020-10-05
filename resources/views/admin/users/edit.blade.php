@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit User
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Users</a></li>
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
              <h3 class="box-title">Edit User Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => 'admin/edit_user/'.$result->id, 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
              @if($result->type == 'buyer')
                <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">User type<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('type', array('buyer' => 'Buyer', 'merchant' => 'Merchant'), $result->type, ['class' => 'form-control user_type', 'id' => 'input_type', 'placeholder' => 'Select User Type']) !!}
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                  </div>
                </div>
                @else 
                  <input type='hidden' name='type' class='input_type' value='{{@$result->type}}' />
              @endif
                <div class="form-group">
                  <label for="input_fullname" class="col-sm-3 control-label">Full Name<em class="text-danger">*</em></label>

                  <div class="col-sm-6">                                      
                    {!! Form::text('full_name', $result->full_name, ['class' => 'form-control', 'id' => 'input_fullname', 'placeholder' => 'Full Name']) !!}
                    <span class="text-danger">{{ $errors->first('full_name') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_username" class="col-sm-3 control-label">User Name<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('user_name', @$result->user_name, ['class' => 'form-control', 'id' => 'input_username', 'placeholder' => 'User Name']) !!}
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                  </div>
                </div>

                <div class="form-group" id='merchant_store' style="display: none;">
                  <label for="input_storename" class="col-sm-3 control-label">Store Name<em class="text-danger">*</em></label>

                  <div class="col-sm-6">                       
                    {!! Form::text('store_name', @$result->store_name, ['class' => 'form-control', 'id' => 'input_storename', 'placeholder' => 'Store Name']) !!}
                    <span class="text-danger">{{ $errors->first('store_name') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_email" class="col-sm-3 control-label">Email<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('email', @$result->email, ['class' => 'form-control', 'id' => 'input_email', 'placeholder' => 'Email']) !!}
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                  </div>
                </div>

                 <div class="form-group" id='merchant_phone_number' style="display: none;">
                  <label for="input_phone_number" class="col-sm-3 control-label">Phone Number<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('phone_number',@$user_details->phone_number, ['class' => 'form-control', 'id' => 'input_phone_number', 'placeholder' => 'Phone Number']) !!}
                    <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_password" class="col-sm-3 control-label">Password</label>

                  <div class="col-sm-6">
                    {!! Form::text('password', '', ['class' => 'form-control', 'id' => 'input_password', 'placeholder' => 'Password']) !!}                  
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                  </div>
                </div>
                 <div class="form-group" id='merchant_address' style="display: none;">
                  <label for="input_address_line" class="col-sm-3 control-label">Address Line 1<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('address_line', @$user_details->address_line, ['class' => 'form-control', 'id' => 'input_address_line', 'placeholder' => 'Address Line 1']) !!}
                    <span class="text-danger">{{ $errors->first('address_line') }}</span>
                  </div>
                </div>

                <div class="form-group" id='merchant_address2' style="display: none;">
                  <label for="input_address_line2" class="col-sm-3 control-label">Address Line 2</label>

                  <div class="col-sm-6">
                    {!! Form::text('address_line2', @$user_details->address_line2, ['class' => 'form-control', 'id' => 'input_address_line2', 'placeholder' => 'Address Line 2']) !!}
                    <span class="text-danger">{{ $errors->first('address_line2') }}</span>
                  </div>
                </div>

                <div class="form-group" id='merchant_city' style="display: none;">
                  <label for="input_city" class="col-sm-3 control-label">City<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('city', @$user_details->city, ['class' => 'form-control', 'id' => 'input_city', 'placeholder' => 'City']) !!}
                    <span class="text-danger">{{ $errors->first('city') }}</span>
                  </div>
                </div>
                
                <div class="form-group" id='merchant_postal_code' style="display: none;">
                  <label for="input_postal_code" class="col-sm-3 control-label">Postal Code<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('postal_code', @$user_details->postal_code, ['class' => 'form-control', 'id' => 'input_postal_code', 'placeholder' => 'Postal Code']) !!}
                    <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                  </div>
                </div>

                <div class="form-group" id='merchant_state' style="display: none;">
                  <label for="input_state" class="col-sm-3 control-label">State<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('state', @$user_details->state, ['class' => 'form-control', 'id' => 'input_state', 'placeholder' => 'State']) !!}
                    <span class="text-danger">{{ $errors->first('state') }}</span>
                  </div>
                </div>

                <div class="form-group" id='merchant_country' style="display: none;">
                  <label for="input_country" class="col-sm-3 control-label">Country<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('country', @$country, @$user_details->country, ['class' => 'form-control', 'id' => 'input_country', 'placeholder' => 'Country']) !!}
                    <span class="text-danger">{{ $errors->first('country') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), @$result->status, ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-default" name="cancel" value="cancel">Cancel</button>
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
              </div>
              <!-- /.box-footer -->
            </form>
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

@push('scripts')
<script type="text/javascript">

$(document).ready(function(){
    var user_type = $('.input_type').val();
    
    if(user_type =='undefined'){
      change_type(user_type);
    }
    else if(user_type =='merchant')
    {
      change_type(user_type);
    }
    else{
      $('#input_type').trigger('change');
    }
    
});

$(document).on('change','#input_type',function(){  
  var user_type = $(this).val();
  change_type(user_type);
});

function change_type(user_type)
{
  if(user_type=='merchant'){
    $('#merchant_store').show();
    $('#merchant_address').show();
    $('#merchant_address2').show();
    $('#merchant_city').show();
    $('#merchant_postal_code').show();
    $('#merchant_state').show();
    $('#merchant_country').show();
    $('#merchant_phone_number').show();
  }
  else
  {
    $('#merchant_store').hide();
    $('#merchant_address').hide();
    $('#merchant_address2').hide();
    $('#merchant_city').hide();
    $('#merchant_postal_code').hide();
    $('#merchant_state').hide();
    $('#merchant_country').hide();
    $('#merchant_phone_number').hide();
  }
}
</script>
@endpush