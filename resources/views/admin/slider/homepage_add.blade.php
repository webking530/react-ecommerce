@extends('admin.template')
@section('main')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Add Favourites
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Favourites</a></li>
			<li class="active">Add</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 col-sm-offset-2">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Add Favourites Form</h3>
					</div>
					{!! Form::open(['url' => ADMIN_URL.'/add_homepage_image', 'class' => 'form-horizontal', 'files' => true]) !!}
					<div class="box-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						<div class="form-group">
							<label for="input_description" class="col-sm-3 control-label">Image<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								{!! Form::file('image', ['class' => 'form-control', 'id' => 'input_image', 'accept' => 'image/*']) !!}
								<p class="note_text">(Note: Upload size minimum 1360px * 600px)</p>
								<span class="text-danger">{{ $errors->first('image') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="input_content" class="col-sm-3 control-label">Description<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								<textarea id="txtEditor" name="description" class="form-control"></textarea>
								<span class="text-danger">{{ $errors->first('description') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="input_position" class="col-sm-3 control-label">Order<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								{!! Form::text('order', 0, ['class' => 'form-control', 'id' => 'input_position']) !!}
								<span class="text-danger">{{ $errors->first('order') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								{!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], '', ['class' => 'form-control', 'id' => 'input_status']) !!}
								<span class="text-danger">{{ $errors->first('status') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="input_title" class="col-sm-3 control-label">Ttile For<em class="text-danger">*</em></label>
							<div class="col-sm-6">
								{!! Form::select('title', ['Feature' => 'Feature', 'Recommended' => 'Recommended','Popular' => 'Popular ','Onsale' => 'Onsale','Newest' => 'Newest ','Editor' => 'Editor'], '', ['class' => 'form-control', 'id' => 'input_title']) !!}
								<span class="text-danger">{{ $errors->first('title') }}</span>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
						<button type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</section>
</div>
@endsection