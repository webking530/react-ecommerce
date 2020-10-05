app.controller('purchases', ['$scope', '$http','$timeout', function($scope, $http,$timeout) 
{
  $scope.all_purchases=[];
  $scope.purchases = function()
  {

    $("#products_loading").show();
    var order_id = $('input[name="orderid"]').val();
      $http.post(APP_URL+'/purchases_details',{ id :order_id}).then(function(response) 
      {
        $scope.order = response.data.orders;
        $scope.orders_details = response.data.orders_details;
        $scope.shipping_address = response.data.shipping_address;

        if(response.data.orders_details=="")
        {
          $(".no-data").show();
          $("#printableArea").hide(); 
        }
        else
        {
          $(".no-data").hide();
          $("#printableArea").show(); 
        }

        angular.forEach(response.data.orders_details, function(value, key){  
            var status_return = 0;
            if(value.status == "Delivered" || value.status =='Completed' && value.status !='Returned')
            {
               if(value.return_policy!=0) 
                { 
                  // convert to complete date add return policy day
                  var completed_at = value.completed_at;
                  var com_date = new Date(completed_at);
                  com_date.setDate(com_date.getDate() + value.return_policy);
                  
                  var dd = com_date.getDate();
                  var mm = com_date.getMonth() + 1;
                  var y = com_date.getFullYear();

                  var return_date = mm + '/' + dd + '/' + y;
                  var last_return = new Date(return_date);
                  var today = new Date();
                  // var tday = today.getFullYear() + "/" + (today.getMonth()) + "/" + today.getDate();
                  // var lday = last_return.getFullYear() + "/" + (last_return.getMonth()) + "/" + last_return.getDate();
                  
                  // check today daye compare with return day
                  if(last_return > today){
                    status_return = 1; 
                  }
                  
                }
            }

              value['status_return'] = status_return;  
             $scope.all_purchases.push(value);             
           
        });   

          $("#products_loading").hide();
      });

  }
  $scope.order_action = function(){
    var order_id = $('input[name=order_id]').val();
    var order_action = $('input[name=order_action]').val();
    var reason_msg = $('#reason_msg').val();
    $('.ly-close').trigger('click');
      $('#reason_msg').val('');
     $http.post(APP_URL+'/purchases_action',{ id:order_id,action:order_action,reason:reason_msg,cancelled_by:'Buyer'}).then(function(response) 
      {
          $scope.all_purchases=[];
          $scope.purchases();  
      });
  }
$(document).ready(function() {
  $scope.purchases();  
});

}]);