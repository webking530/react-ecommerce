@extends('admin.template')
@section('main')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Join Us
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Join Us</a></li>
			<li class="active">Edit</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 col-sm-offset-2">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Join Us Form</h3>
					</div>
					{!! Form::open(['url' => 'admin/join_us', 'class' => 'form-horizontal', 'files' => true]) !!}
					<div class="box-body">
						@foreach($result as $join)
						<div class="form-group">
							<label for="input_facebook" class="col-sm-3 control-label"> {{ ucfirst($join->name) }} </label>
							<div class="col-sm-6">
								{!! Form::text($join->name,join_us($join->name), ['class' => 'form-control', 'id' => 'input_'.$join->name, 'placeholder' => ucfirst($join->name) ]) !!}
								<span class="text-danger">{{ $errors->first($join->name) }}</span>
							</div>
						</div>
						@endforeach
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
						<button type="submit" class="btn btn-default pull-left"> Cancel </button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</section>
</div>
@endsection