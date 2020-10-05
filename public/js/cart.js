app.controller('cart_product', ['$scope', '$http','$timeout', function($scope, $http,$timeout) 
{
  $scope.all_cart=[];
  $scope.value = [];
  $scope.cart_product = function()
  {

    $(".cart").hide(); 
    $("#products_loading").show();
    $("#recently_viewed_things").show();

    $http.post(APP_URL+'/cart_product').then(function(response) 
      {
        $scope.total_cart = response.data.price.tot_item;
        $scope.cart = response.data.cart;
        $scope.subtotal = response.data.price.subtotal;
        $scope.currency_symbol  = response.data.price.currency_symbol;
        
       
        angular.forEach(response.data.cart, function(value, key){
           // var qty = [];
            //   for(var i=1;i<=value.total_quantity;i++) {
            //     qty.push(i);
            //   }                    

              if(value.product_details.status == 'Inactive'  || value.product_details.users.status == 'Inactive'){
                value['is_available'] =  Lang.get('js_messages.cart.unavailable');
              }
              else if (value.product_details.sold_out =='Yes' || value.product_details.total_quantity <=0){
                value['is_available'] =  Lang.get('js_messages.cart.soldout');
              }
              else if(value.product_details.product_option.length > 0){

                angular.forEach(value.product_details.product_option, function(option_un, option_key) {
                  if(option_un.id == value.option_id  && option_un.sold_out=='Yes' ){
                    value['is_available'] =  Lang.get('js_messages.cart.soldout');
                  } 
                  else if(option_un.id == value.option_id  && option_un.total_quantity <=0 ){
                    value['is_available'] =  Lang.get('js_messages.cart.soldout');
                  }   
                  else if(option_un.id == value.option_id  && option_un.total_quantity <=value.quantity ){
                    value['is_available'] =  Lang.get('js_messages.cart.only')+' '+option_un.total_quantity+' '+ Lang.get('js_messages.cart.product_available');
                  }                   
                });
              }
              else {
                if(value.product_details.sold_out =='Yes' || value.product_details.total_quantity <=0){
                  value['is_available'] =  Lang.get('js_messages.cart.unavailable');  
                }
                else if(value.product_details.total_quantity < value.quantity){                  
                    value['is_available'] =  Lang.get('js_messages.cart.only')+' '+value.product_details.total_quantity+' '+ Lang.get('js_messages.cart.product_available');
                }
                else{
                 
                  value['is_available'] = '';
                }
              }

              if(typeof(value['is_available']) == 'undefined'){                 
                value['is_available'] = '';
              }
              
              // value['qty'] = qty;    
             $scope.all_cart.push(value);             
        });   

        $('.head-list .cart_count').html(response.data.cart.length);
          

          $timeout(function () {
            $("#products_loading").hide();

            var unavailable = $('.unavailable').length;

              if(unavailable == 0)
              {
                $('.prced-btn').removeAttr('disabled');
              }
              else
              {
                $('.prced-btn').attr('disabled','disabled');
              }

               angular.forEach(response.data.cart, function(value, key){                
                  $('.cart_qty_'+value.id).html(value.qty_select); 
                  $('.cart_qty_'+value.id).val(value.quantity);  
              });

                if(response.data.cart=="")
                {
                  $(".empty-cart").show();
                  $(".cart").hide(); 
                }
                else
                {
                  $(".empty-cart").hide();
                  $(".cart").show(); 
                }

          });
      });

  }
$(document).ready(function() {
  $scope.cart_product();  
  $('.container-mini').show();
});
  /*to change option to the product amount changed based on option*/
  $(document).on('change','.cart_option',function(){
    var option = $(this).val();
    var cart_id =$(this).attr('id');
    var qty = $('.cart_qty_'+cart_id).val();  
    var product_id = $('#product_id_'+cart_id).val();    
    var qty_option = 1;
    $http.post(APP_URL+'/cart_update', { option :option,cart_id : cart_id,quantity:qty,product_id:product_id }).then(function(response) 
    {    
      $scope.all_cart=[];
      $scope.cart_product();
    });
  });

  /*to change quantity to the product amount changed based on option*/
  $(document).on('change','.cart_qty',function(){  
    var cart_id =$(this).attr('id');
    var qty = $(this).val();  
    var option = $('.cart_option_'+cart_id).val();
    var product_id = $('#product_id_'+cart_id).val();
    var qty_option = 0;    
    $http.post(APP_URL+'/cart_update', { option :option,cart_id : cart_id,quantity:qty }).then(function(response) 
    {    
        $scope.all_cart=[];
        $scope.cart_product();
    });
  });

  //remove cart function
  $scope.remove_cart = function(cart_id)
  {
    $http.post(APP_URL+'/remove_cart', { cart_id : cart_id }).then(function(response) 
    { 
      $scope.all_cart=[];
      $scope.cart_product();
    });
  }
}]);;