@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" ng-controller="reports">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reports
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reports</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-cloak>
      <div class="row">
        <!-- right column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Reports Form</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-1 control-label">From</label>
                  <div class="col-sm-2">
                  <input type="text" id="from_date" ng-model="from" ng-change="report(from, to, category)" class="form-control date" placeholder="From Date">
                  </div>
                  <label class="col-sm-1 control-label">To</label>
                  <div class="col-sm-2">
                  <input type="text" id="to_date" ng-model="to" ng-change="report(from, to, category)" class="form-control date" placeholder="To Date">
                  </div>
                  <label class="col-sm-1 control-label">Category</label>
                  <div class="col-sm-2">
                  <select class="form-control" id="from_to_disable" ng-model="category" ng-change="report(from, to, category)">
                    <option value="">Users</option>
                    <option value="merchant">Merchant</option>
                    <option value="items">Products</option>
                    <option value="orders">Orders</option>
                  </select>
                  </div>
                </div>
            </div>
            <div class="loading" style="display: none;"></div>
            <div class="box-body print_area" id="users" ng-show="users_report.length">
            <div class="text-center"><h4>Users Report (@{{ from }} - @{{ to }})</h4></div>
              <table class="table">
                <thead>
                  <th>Id</th>
                  <th>Full Name</th>
                  <th>User Name</th>
                  <th>Email</th>
                  <th>User Type</th>
                  <th>Registered At</th>
                </thead>
                <tbody>
                  <tr ng-repeat="item in users_report">
                    <td>@{{ item.id }}</td>
                    <td>@{{ item.full_name }}</td>
                    <td>@{{ item.user_name }}</td>
                    <td>@{{ item.email }}</td>
                    <td style="text-transform: capitalize;">@{{ item.type }}</td>
                    <td>@{{ item.created_at }}</td>
                  </tr>
                </tbody>
              </table>
              <br>
            </div>
            <div class="box-body print_area" id="merchant" ng-show="merchant_users_report.length">
            <div class="text-center"><h4>Merchant users Report (@{{ from }} - @{{ to }})</h4></div>
              <table class="table">
                <thead>
                  <th>Id</th>
                  <th>Full Name</th>
                  <th>User Name</th>
                  <th>Email</th>
                  <th>Store Name</th>
                  <th>Registered At</th>
                </thead>
                <tbody>
                  <tr ng-repeat="item in merchant_users_report">
                    <td>@{{ item.id }}</td>
                    <td>@{{ item.full_name }}</td>
                    <td>@{{ item.user_name }}</td>
                    <td>@{{ item.email }}</td>
                    <td>@{{ item.store_name }}</td>
                    <td>@{{ item.created_at }}</td>
                  </tr>
                </tbody>
              </table>
              <br>
            </div>
         <div class="box-body print_area" id="items" ng-show="items_report.length">
            <div class="text-center"><h4>Products Report (@{{ from }} - @{{ to }})</h4></div>
              <table class="table">
                <thead>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Merchant Name</th>
                  <th>Category</th>
                  <th>Status</th>
                  <th>Created At</th>
                </thead>
                <tbody>
                  <tr ng-repeat="item in items_report">
                    <td>@{{ item.id }}</td>
                    <td>@{{ item.title }}</td>
                    <td>@{{ item.user_name }}</td>
                    <td>@{{ item.category_name }}</td>
                    <td>@{{ item.status }}</td>
                    <td>@{{ item.created_at }}</td>
                  </tr>
                </tbody>
              </table>
              <br>
            </div> 
             <div class="box-body print_area" id="orders" ng-show="orders_report.length">
            <div class="text-center"><h4>Orders Report (@{{ from }} - @{{ to }})</h4></div>
              <table class="table">
                <thead>
                  <th>Id</th>
                  <th>Buyer Name</th>
                  <th>Total Amount</th>
                  <th>Created At</th>
                </thead>
                <tbody>
                  <tr ng-repeat="item in orders_report">
                    <td>@{{ item.id }}</td>
                    <td>@{{ item.buyer_name }}</td> 
                    <td><span ng-bind-html="item.total_amount"></span></td>
                    <td>@{{ item.created_at }}</td>
                  </tr>
                </tbody>
              </table>
              <br>
            </div> 
            <div class="text-center" id="print_footer" ng-show="users_report.length || merchant_users_report.length || items_report.length || orders_report.length ">
             <a class="btn btn-success" id="export" href="{{ url('admin/reports/export') }}/@{{ formatted_from }}/@{{ formatted_to }}/@{{ (category) ? category : 'users' }}"><i class="fa fa-file-excel-o"></i> Export</a>
              <button class="btn btn-info" ng-click="print(category)"><i class="fa fa-print"></i> Print</button>
            </div>
            <div class="text-center" id="empty_data" ng-show="!users_report.length && !merchant_users_report.length && !items_report.length && !orders_report.length">
              No results
            </div>
            <br>
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
  <style type="text/css">
  @media print {
    body * {
      visibility: hidden;
    }
    .print_area * {
      visibility: visible;
    }
    .print_area {
      position: absolute;
      left: 0;
      top: 0;
    }
  }
  .loading {
      position: relative;
      padding: 30px;
    }

  .loading:before {
      position: absolute;
      display: block;
      top: 50%;
      left: 40%;
      z-index: 10;
      content: " ";
      background-image: url("../image/white_index.gif");
      background-repeat: no-repeat;
      height: 100px;
      width: 100px;
      margin-top: -15px;    
  }
  .loading:after {
    position: absolute;
    display: block;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #fff;
    content: " ";
    z-index: 9;
    opacity: 0.9;
    filter: alpha(opacity=90);
  }
</style>
@stop