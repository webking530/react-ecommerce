app.controller('reports', ['$scope', '$http', function($scope, $http) {
  
  $scope.report = function(from, to, category)
  {
    $('.loading').show();
    $('#empty_data').hide();
    $('.print_area').hide();
    $('#print_footer').hide();
    $http.post(APP_URL+"/admin/reports", { from: from, to: to, category: category }).then(function( response ) {
      $('.loading').hide();
      $('#empty_data').show();
      $('.print_area').show();
    $('#print_footer').show();
      $scope.formatted_from=response.data.from;
      $scope.formatted_to=response.data.to;

       if(!$scope.category) {

      $scope.users_report = response.data.result;
      $scope.merchant_users_report = false;
      $scope.items_report = false;
      $scope.orders_report = false;
       }
       if($scope.category == 'merchant'){
        $scope.users_report = false;
        $scope.merchant_users_report = response.data.result;
        $scope.items_report = false;
        $scope.orders_report = false;
       }
       if($scope.category == 'items') {
       $scope.users_report = false;
        $scope.merchant_users_report = false;
       $scope.items_report = response.data.result;
       $scope.orders_report = false;
       }
       if($scope.category == 'orders') {
      $scope.users_report = false;
      $scope.merchant_users_report = false;
       $scope.items_report = false;
       $scope.orders_report = response.data.result;
       }
    });
  };

  $scope.print = function(category)
  {
    category = (!category) ? 'users' : category;
    var prtContent = document.getElementById(category);
    var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
  };

  // $('.date').datepicker({ 'dateFormat': 'dd-mm-yy'});

  $('.date').datepicker({ 'dateFormat': 'dd-mm-yy', maxDate: new Date()});


   $( document ).ready(function() {
     $( "#from_to_disable").change(function()
       {
         var value = $("#from_to_disable option:selected").val();
          
         if(value =='orders')
          {       
            // $('.date').datepicker('destroy');
            $('.date').datepicker('option', 'maxDate', '')
            $('.date').datepicker('refresh');
            } 
            else
            {
              $('.date').datepicker('option', 'maxDate', new Date())
              $('.date').datepicker('refresh');
            }         
        });
    });
  
}]);

app.controller('payments', ['$scope', '$http', function($scope, $http) {
  
  $scope.payment = function(from,to)
  {
    $http.post(APP_URL+"/admin/payments", { from: from, to: to }).then(function( response ) {
      
      
        $scope.payments_report = response.data;
      
    });
  };

  $scope.print = function(category)
  {
    var prtContent = document.getElementById('payments');
    var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
  };

  $('.date').datepicker({ 'dateFormat': 'dd-mm-yy'});
  
}]);