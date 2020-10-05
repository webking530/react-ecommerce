@extends('admin.template')
@section('main')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Edit Favourites
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Favourites</a></li>
			<li class="active">Edit</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 col-sm-offset-2">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Favourites Form</h3>
					</div>
					{!! Form::open(['url' => ADMIN_URL.'/edit_homepage_image/'.$result->id, 'class' => 'form-horizontal', 'files' => true]) !!}
					<div class="box-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						<div class="form-group">
							<label for="input_description" class="col-sm-3 control-label">Image<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								{!! Form::file('image', ['class' => 'form-control-static', 'id' => 'input_image', 'accept' => 'image/*']) !!}
								<p class="note_text">(Note: Upload size minimum 1284px * 177px)</p>
								<span class="text-danger">{{ $errors->first('image') }}</span>
								<img src="{{ $result->image_url }}" height="100" width="200">
							</div>
						</div>
						<div class="form-group">
							<label for="input_content" class="col-sm-3 control-label">Title<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="{{$result->title}}" name="title" readonly disabled>
								<p class="text-danger"> {{ $errors->first('title') }}</p>
							</div>
						</div>
						<div class="form-group">
							<label for="input_content" class="col-sm-3 control-label">Description<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								<textarea id="txtEditor" name="description" class="form-control"> {{$result->description}} </textarea>
								<p class="text-danger"> {{ $errors->first('description') }}</p>
							</div>
						</div>
						<div class="form-group">
							<label for="input_position" class="col-sm-3 control-label">Order<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								{!! Form::text('order', $result->order, ['class' => 'form-control', 'id' => 'input_position']) !!}
								<span class="text-danger">{{ $errors->first('order') }}</span>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
						<a href ="{{ url('admin/feature_slider')}}"  type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel </a>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</section>
</div>
@endsection