<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Panel</title>
  <link rel="shortcut icon" href="{{ $favicon }}" type="image/png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ url('admin_assets/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- <link href="https://file.myfontastic.com/FnPb8VZY3vfKnosQUYCeYY/icons.css" rel="stylesheet"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('admin_assets/dist/css/AdminLTE.css') }}">

  <link rel="stylesheet" href="{{ url('admin_assets/plugins/datatables/dataTables.bootstrap.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ url('admin_assets/dist/css/skins/_all-skins.css') }}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ url('admin_assets/plugins/morris/morris.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ url('admin_assets/plugins/datepicker/datepicker3.css') }}">
  <!-- text editor -->
  <link rel="stylesheet" href="{{ url('admin_assets/plugins/editor/editor.css') }}">

  <link rel="stylesheet" href="{{ url('admin_assets/plugins/jQueryUI/jquery-ui.css') }}">

  <link rel="stylesheet" href="{{ url('admin_assets/dist/css/treeview.css') }}">

  @if(!isset($exception) && Route::current()->uri() == 'admin/products/add'|| Route::current()->uri() == 'admin/products/edit/{id}')
      <!-- <link rel="stylesheet" href="{{ url('css/merchant_home.css') }}"> -->
      <link rel="stylesheet" href="{{ url('css/merchant.css') }}">
      <link rel="stylesheet" href="{{ url('css/merchant1.css') }}">
      <link rel="stylesheet" href="{{ url('css/home.css') }}">
     
    {!! Html::style('css/selectize.default.css') !!}
  @endif
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-red sidebar-mini" ng-app="App">
<div class="wrapper">