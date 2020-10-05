@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Category
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Categories</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
       
    <div class="panel panel-primary">
      <div class="panel-heading">Manage Category Tree View</div>
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
              <h3>Edit Category</h3>

                {!! Form::open(['url'=>'admin/edit_category/'.$result->id,'enctype'=>'multipart/form-data']) !!}

                  @if ($message = Session::get('success'))
                  <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                          <strong>{{ $message }}</strong>
                  </div>
                @endif

                  <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                  {!! Form::label('Title:') !!}<span class="text-danger">*</span>
                  {!! Form::text('title', $result->title, ['class'=>'form-control', 'placeholder'=>'Enter Title']) !!}
                  <span class="text-danger">{{ $errors->first('title') }}</span>
                </div>

                <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                  {!! Form::label('Category:') !!}
                  {!! Form::select('parent_id',$allCategories, ($result->parent_id == '0') ? '' : $result->parent_id, ['class'=>'form-control', 'placeholder'=>'Select Category']) !!}
                  <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                </div>

                <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                  {!! Form::label('Status:') !!}
                  {!! Form::select('status', ['Active'=>'Active', 'Inactive'=>'Inactive'], $result->status, ['class'=>'form-control']) !!}
                  <span class="text-danger">{{ $errors->first('status') }}</span>
                </div>
                <div class="form-group {{ $errors->has('featured') ? 'has-error' : '' }}">
                  {!! Form::label('Featured:') !!}
                  {!! Form::select('featured', ['Yes'=>'Yes', 'No'=>'No'],  $result->featured,['class'=>'form-control']) !!}
                  <span class="text-danger">{{ $errors->first('featured') }}</span>
                </div>

                <div class="form-group {{ $errors->has('category_icon') ? 'has-error' : '' }}">
                {!! Form::label('Icon:') !!}
                <em style="float: right;">Size: 30x30</em>
                <input type="file" name="category_icon" accept="image/*">
                <img width="30PX" height="30px" src="{{ $result->icon_name }}" />
                <span class="text-danger">{{ $errors->first('category_icon') }}</span>   
                </div>

                <div class="form-group {{ $errors->has('category_image') ? 'has-error' : '' }}">
                {!! Form::label('Image:') !!}
                 <em style="float: right;">Size: 2000x350</em>
                <input type="file" name="category_image" accept="image/*">
                <img width="100%" height="300px" src="{{ $result->image_name }}" />
                <span class="text-danger">{{ $errors->first('category_image') }}</span>
                </div>

                <div class="form-group">
                  <button class="btn btn-success">Update</button>
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