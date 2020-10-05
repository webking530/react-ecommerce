@extends('admin.template')

@section('main')


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Category
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Categories</a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        
    <div class="panel panel-primary">
      <div class="panel-heading navbar">Manage Category Tree View</div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-6">
              <h3>Category List</h3>
                <ul id="tree1">
                    @foreach($categories as $category)
                        <li>
                            {{ $category->title }}
                            @if($category->childs->count())
                                @include('admin.categories.child',['childs' => $category->childs])
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
              <h3>Add New Category</h3>

                {!! Form::open(['url'=>'admin/add_category','enctype'=>'multipart/form-data']) !!}

                  @if ($message = Session::get('success'))
                  <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                          <strong>{{ $message }}</strong>
                  </div>
                @endif

                  <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                  {!! Form::label('Title:') !!}<span class="text-danger">*</span>
                  {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=>'Enter Title']) !!}
                  <span class="text-danger">{{ $errors->first('title') }}</span>
                </div>

                <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                  {!! Form::label('Category:') !!}
                  {!! Form::select('parent_id',$allCategories, old('parent_id'), ['class'=>'form-control', 'placeholder'=>'Select Category']) !!}
                  <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                </div>

                <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                  {!! Form::label('Status:') !!}
                  {!! Form::select('status', ['Active'=>'Active', 'Inactive'=>'Inactive'], old('status'), ['class'=>'form-control']) !!}
                  <span class="text-danger">{{ $errors->first('status') }}</span>
                </div>

                <div class="form-group {{ $errors->has('featured') ? 'has-error' : '' }}">
                  {!! Form::label('Featured:') !!}
                  {!! Form::select('featured', ['Yes'=>'Yes', 'No'=>'No'], old('featured'), ['class'=>'form-control']) !!}
                  <span class="text-danger">{{ $errors->first('featured') }}</span>
                </div>

                <div class="form-group {{ $errors->has('category_icon') ? 'has-error' : '' }}">
                {!! Form::label('Icon:') !!}<span class="text-danger">*</span>
                <em style="float: right;">Size: 30x30</em>
                <input type="file" name="category_icon" accept="image/*">
                <span class="text-danger">{{ $errors->first('category_icon') }}</span>
                </div>

                <div class="form-group {{ $errors->has('category_image') ? 'has-error' : '' }}">
                {!! Form::label('Image:') !!}<span class="text-danger">*</span>
                <em style="float: right;">Size: 2000x350</em>
                <input type="file" name="category_image" accept="image/*">
                <span class="text-danger">{{ $errors->first('category_image') }}</span>
                </div>

               <!--  <div class="form-group {{ $errors->has('category_banner_image') ? 'has-error' : '' }}">
                {!! Form::label('Banner Image:') !!}
                <input type="file" name="category_banner_image" accept="image/*">
                <span class="text-danger">{{ $errors->first('banner_img') }}</span>
                
                </div> -->

                <div class="form-group">
                  <button class="btn btn-success">Add New</button>
                </div>

                {!! Form::close() !!}

            </div>
          </div>

          
        </div>
        </div>
 
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@stop