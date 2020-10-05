<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="{{ url('admin_assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="{{ url('admin_assets/plugins/jQueryUI/jquery-ui.min.js') }}"></script>

<script src="{{ url('js/angular.js') }}"></script>
<script src="{{ url('js/angular-sanitize.js') }}"></script>

<script> 
var app = angular.module('App', ['ngSanitize']);
var APP_URL = {!! json_encode(url('/')) !!}; 
</script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Bootstrap 3.3.5 -->
<script src="{{ url('admin_assets/bootstrap/js/bootstrap.min.js') }}"></script>

@if (!isset($exception))



    @if (Route::current()->uri() == 'admin/dashboard')
    	<!-- Morris.js charts -->
      <script src="{{ url('admin_assets/plugins/morris/raphael-min.js') }}"></script>
      <script src="{{ url('admin_assets/plugins/morris/morris.min.js') }}"></script>
      <!-- datepicker -->
      <script src="{{ url('admin_assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
      <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		  <script src="{{ url('admin_assets/dist/js/dashboard.js') }}"></script>
    @endif
    @if (Route::current()->uri() == 'admin/admin_users' || Route::current()->uri() == 'admin/categories' || Route::current()->uri() == 'admin/products' || Route::current()->uri() == 'admin/stores' || Route::current()->uri() == 'admin/users' || Route::current()->uri() == 'admin/orders' || Route::current()->uri() == 'admin/returns_policy' || Route::current()->uri() == 'admin/country' || Route::current()->uri() == 'admin/pages' || Route::current()->uri() == 'admin/metas' || Route::current()->uri() == 'admin/currency' || Route::current()->uri() == 'admin/roles' || Route::current()->uri() == 'admin/language' || Route::current()->uri() == 'admin/owe' || Route::current()->uri() == 'admin/coupon_code' || Route::current()->uri() == 'admin/product_reports' || Route::current()->uri() == 'admin/blocked_users'|| Route::current()->uri() == 'admin/slider'|| Route::current()->uri() == 'admin/feature_slider')
      <script src="{{ url('admin_assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ url('admin_assets/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    @endif


    @if (Route::current()->uri() == 'admin/reports' || Route::current()->uri() == 'admin/add_country' || Route::current()->uri() == 'admin/edit_country/{id}')
    <script src="{{ url('admin_assets/plugins/jQuery/jquery.validate.js') }}"></script>
    <script src="{{ url('admin_assets/dist/js/reports.js') }}"></script>
    @endif

@endif

 @if (Route::current()->uri() == 'admin/add_page' || Route::current()->uri() == 'admin/edit_page/{id}' || Route::current()->uri() == 'admin/send_email' || Route::current()->uri() == 'admin/add_help' || Route::current()->uri() == 'admin/edit_help/{id}')
    <script src="{{ url('admin_assets/plugins/editor/editor.js') }}"></script>
      <script type="text/javascript"> 
        $("[name='submit']").click(function(){
          $('#content').text($('#txtEditor').Editor("getText"));
          $('#message').text($('#txtEditor').Editor("getText"));
          $('#answer').text($('#txtEditor').Editor("getText"));
        });
      </script>
    @endif
  @if(Route::current()->uri() == 'admin/products/add' || Route::current()->uri() == 'admin/products/edit/{id}')
      {!! Html::script('js/admin-bootstrap-drilldown-select.js') !!}
      {!! Html::script('js/selectize.js') !!}
      {!! Html::script('js/jquery.validate.js') !!}
      <script src="{{ url('admin_assets/dist/js/product.js') }}"></script>
      {!! Html::script('js/tinymce/js/tinymce/tinymce.min.js') !!}
      @endif

<!-- AdminLTE App -->
<script src="{{ url('admin_assets/dist/js/app.js') }}"></script>
<script src="{{ url('admin_assets/dist/js/common.js') }}"></script>


<!-- AdminLTE for demo purposes -->
<script src="{{ url('admin_assets/dist/js/demo.js') }}"></script>

<script src="{{ url('admin_assets/dist/js/treeview.js') }}"></script>

@stack('scripts')

<script type="text/javascript">
  $('#dataTableBuilder_length').addClass('dt-buttons');
  $('#dataTableBuilder_wrapper > div:not("#dataTableBuilder_length").dt-buttons').css('margin-left','20%');
</script>

</body>
</html>