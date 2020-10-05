@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Fees
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Fees</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>

    <!-- Main content -->

      <div class="row">
        <!-- right column -->
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Fees Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => 'admin/fees', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
                <div class="form-group">
                  <label for="input_service_fee" class="col-sm-3 control-label">Service Fee</label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::text('service_fee', $result[0]->value, ['class' => 'form-control', 'id' => 'input_service_fee', 'placeholder' => 'Service Fee']) !!}
                    <div class="input-group-addon" style="background-color:#eee;">%</div>
                    <span class="text-danger">{{ $errors->first('service_fee') }}</span>
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_service_fee" class="col-sm-3 control-label">Merchant Fee<em class="text-danger">*</em></label>
                  <div class="col-sm-7">
                  <div class="input-group"> 
                    {!! Form::text('merchant_fee', @$result[1]->value, ['class' => 'form-control', 'id' => 'input_merchant_fee', 'placeholder' => 'Merchant Fee']) !!}
                    <div class="input-group-addon" style="background-color:#eee;">%</div>
                    <span class="text-danger">{{ $errors->first('merchant_fee') }}</span>
                  </div>
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

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@stop

