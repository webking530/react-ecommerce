$(document).ready(function(){ 	

tinymce.init({
  selector:'#store_description',
  height: 250,
  menubar: false,
  statusbar: false,
  branding: false,
  plugins: [
    'link'
  ],
  toolbar: 'bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent | link ',
  
});
$('li.products').mouseover(function(){
$('li.products small').show();
     }); 
       $('li.products').mouseout(function(){
$('li.products small').hide();
     }); 
            $('li.products small').mouseover(function(){
$('li.products small').show();
     }); 
       $('li.products small').mouseout(function(){
$('li.products small').hide();
     }); 
            $('li.orders').mouseover(function(){
$('li.orders small').show();
     }); 
       $('li.orders').mouseout(function(){
$('li.orders small').hide();
     }); 
            $('li.orders small').mouseover(function(){
$('li.orders small').show();
     }); 
       $('li.orders small').mouseout(function(){
$('li.orders small').hide();
     }); 
           $('li.insights').mouseover(function(){
$('li.insights small').show();
     }); 
       $('li.insights').mouseout(function(){
$('li.insights small').hide();
     }); 
            $('li.insights small').mouseover(function(){
$('li.insights small').show();
     }); 
       $('li.insights small').mouseout(function(){
$('li.insights small').hide();
     }); 
                  $('li.promote').mouseover(function(){
$('li.promote small').show();
     }); 
       $('li.promote').mouseout(function(){
$('li.promote small').hide();
     }); 
            $('li.promote small').mouseover(function(){
$('li.promote small').show();
     }); 
       $('li.promote small').mouseout(function(){
$('li.promote small').hide();
     }); 
                         $('li.campaigns').mouseover(function(){
$('li.campaigns small').show();
     }); 
       $('li.campaigns').mouseout(function(){
$('li.campaigns small').hide();
     }); 
            $('li.campaigns small').mouseover(function(){
$('li.campaigns small').show();
     }); 
       $('li.campaigns small').mouseout(function(){
$('li.campaigns small').hide();
     }); 
                 $('li.settings').mouseover(function(){
$('li.settings small').show();
     }); 
       $('li.settings').mouseout(function(){
$('li.settings small').hide();
     }); 
            $('li.settings small').mouseover(function(){
$('li.settings small').show();
     }); 
       $('li.settings small').mouseout(function(){
$('li.settings small').hide();
     }); 
                        $('li.faq').mouseover(function(){
$('li.faq small').show();
     }); 
       $('li.faq').mouseout(function(){
$('li.faq small').hide();
     }); 
            $('li.faq small').mouseover(function(){
$('li.faq small').show();
     }); 
       $('li.faq small').mouseout(function(){
$('li.faq small').hide();
     }); 
        $('.pro-check').click(function(){
          $('.check-active').toggleClass('check-active-true');
           $('.checkbox').toggleClass('all-sel');           
        });
        $('body').click(function(){
          $('.dropdown ul').hide();
        });
        $('.dropdown').click(function(e){
          e.stopPropagation();
          $(this).toggleClass('opened');
          $(this).find('ul').toggle();

          completed_count = $('.pro-check.Completed').length;
          cancel_count = $('.pro-check.Cancelled').length;
          action_count = $(".pro-check:checked").length;
          if(cancel_count == action_count||completed_count == action_count){
            $(".dropdown.bulk.action").hide();      
            $(".dropdown.select-order").attr("style","display:inline-block"); 
          }

        });
         $('li.list-img1').mouseover(function(){
$('.hover-slider').css('opacity' , '1');
     }); 
       $('li.list-img1').mouseout(function(){
$('.hover-slider').css('opacity' , '0');
     }); 
        $('li.list-img2').mouseover(function(){
$('.hover-slider2').css('opacity' , '1');
     }); 
       $('li.list-img2').mouseout(function(){
$('.hover-slider2').css('opacity' , '0');
     }); 
        $('li.list-img3').mouseover(function(){
$('.hover-slider3').css('opacity' , '1');
     }); 
       $('li.list-img3').mouseout(function(){
$('.hover-slider3').css('opacity' , '0');
     }); 
        $('.add_qty').mouseover(function(){
$('.availability_qty').show();
     }); 
       $('.add_qty').mouseout(function(){
$('.availability_qty').hide();
     }); 
 });


$(window).on('load', function() {
(function ($) {
  $(document).ready(function(){
    
  // hide .navbar first
  // $(".navbar").hide();
  
  // fade in .navbar
  $(function () {
    $(window).scroll(function () {
          
      // if ($(this).scrollTop() > 100) {
      //   alert("test")
      //   $('.navbar').fadeIn();
      // } else {
      //   $('.navbar').fadeOut();
      // }
    });

  
  });

});
  }(jQuery));
});
$(document).ready(function(){
  var completed_count =$('.get-started .completed').length;
  if(completed_count == 3)
  {
    $('.progress').css('width','100%');
  }
  else if(completed_count == 2)
  {
    $('.progress').css('width','75%');
  }
  else if(completed_count == 1)
  {
    $('.progress').css('width','50%');
  }
  else
  {
    $('.progress').css('width','25%');
  }
});
// Get the modal
var modal = document.getElementById('popup_container');
var submodal = document.getElementById('content-pop');
var collection = document.getElementById('myBtn-import-edit');


// Get the button that opens the modal
var btn1 = document.getElementById("myBtn-import");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("ly-close")[0];

// When the user clicks on the button, open the modal
// btn1.onclick = function() {
//     modal.style.display = "block";
//     submodal.style.display = "block";
//     modal.style.opacity = "1";
// }
// collection.onclick = function() {
//     modal.style.display = "block";
// }

// // When the user clicks on <span> (x), close the modal
// span.onclick = function() {
//     modal.style.display = "none";
// }

// // When the user clicks anywhere outside of the modal, close it
// window.onclick = function(event) {
//     if (event.target == modal) {
//         modal.style.display = "none";
//     }
// }
app.directive("limitTo", [function() {
    return {
        restrict: "A",
        link: function(scope, elem, attrs) {
            var limit = parseInt(attrs.limitTo);
            angular.element(elem).on("keypress", function(e) {
                if (this.value.length == limit) e.preventDefault();
            });
        }
    }
}]);

app.directive('onlyNumber', function() {
      return {
        require: '?ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
          if(!ngModelCtrl) {
            return; 
          }

          ngModelCtrl.$parsers.push(function(val) {
            if (angular.isUndefined(val)) {
                var val = '';
            }
            
            var clean = val.replace(/[^-0-9\.]/g, '');
            var negativeCheck = clean.split('-');
            var decimalCheck = clean.split('.');

            if (val !== clean) {
              ngModelCtrl.$setViewValue(clean);
              ngModelCtrl.$render();
            }
            return clean;
          });

          element.bind('keypress', function(event) {
            if(event.keyCode === 32) {
              event.preventDefault();
            }
          });
        }
      };
    });
app.directive('numbersOnly', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9]/g, '');

                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }            
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});
app.directive("limitTo", [function() {
    return {
        restrict: "A",
        link: function(scope, elem, attrs) {
            var limit = parseInt(attrs.limitTo);
            angular.element(elem).on("keypress", function(e) {
                if (this.value.length == limit) e.preventDefault();
            });
        }
    }
}]);
app.directive('validNumber', function() {
      return {
        require: '?ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
          if(!ngModelCtrl) {
            return; 
          }

          ngModelCtrl.$parsers.push(function(val) {
            if (angular.isUndefined(val)) {
                var val = '';
            }
            
            var clean = val.replace(/[^-0-9\.]/g, '');
            var negativeCheck = clean.split('-');
            var decimalCheck = clean.split('.');
            if(!angular.isUndefined(negativeCheck[1])) {
                negativeCheck[1] = negativeCheck[1].slice(0, negativeCheck[1].length);
                clean =negativeCheck[0] + '-' + negativeCheck[1];
                if(negativeCheck[0].length > 0) { 
                  clean =negativeCheck[0];
                }
                
            }
              
            if(!angular.isUndefined(decimalCheck[1])) {
                decimalCheck[1] = decimalCheck[1].slice(0,2);
                decimalCheck[0] = decimalCheck[0].slice(0,4);
                clean =decimalCheck[0] + '.' + decimalCheck[1];
            }
            else
            {
              if(clean.length>4)
              {
                clean =decimalCheck.slice(0,4) + '.' + decimalCheck.slice(5,6);   
              }
              
            }

            if (val !== clean) {
              ngModelCtrl.$setViewValue(clean);
              ngModelCtrl.$render();
            }
            return clean;
          });

          element.bind('keypress', function(event) {
            if(event.keyCode === 32) {
              event.preventDefault();
            }
          });
        }
      };
    });

app.directive('postsPagination', function(){  
   return{
      restrict: 'E',
      template: '<ul class="pagination">'+
        '<li class="page-item" ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(1)" class="page-link">&laquo;</a></li>'+
        '<li class="page-item" ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(currentPage-1)" class="page-link">&lsaquo; '+ $('#pagin_prev').val() +'</a></li>'+
        '<li class="page-item" ng-repeat="i in range" ng-class="{active : currentPage == i}">'+
            '<a href="javascript:void(0)" ng-click="search_result(i)" class="page-link">{{i}}</a>'+
        '</li>'+
        '<li class="page-item" ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(currentPage+1)" class="page-link">'+ $('#pagin_next').val() +' &rsaquo;</a></li>'+
        '<li class="page-item" ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(totalPages)" class="page-link">&raquo;</a></li>'+
      '</ul>'
   };
}).controller('products', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
  $scope.first_search = 'Yes';
  $scope.current_date = new Date();
  $scope.range = [];
  $scope.totalPages = 0;
  $scope.currentPage = 1;
  $scope.products= [];
  $scope.count_all = 0;
  $scope.count_active = 0;
  $scope.count_inactive = 0;
  $scope.count_soldout = 0;
  $scope.count_expired = 0;
  $scope.count_awaiting = 0;
  $scope.count_onsale = 0;


$scope.search_result = function (pageNumber) {
   
  if(pageNumber===undefined){
      pageNumber = '1';
  }
  $(".no_products").hide();
  var search = $("#search").val(); 
  var search_by = $("#search_by").val(); 
  var current=$("#current").val();
  setGetParameter('search', search);
  setGetParameter('search_by', search_by);
  setGetParameter('current', current);
  $('.all-pro-table').addClass('whiteloading');
  $http.post('product_search?page='+pageNumber, {current:current,search: search, search_by: search_by})
      .then(function(response) {

      $scope.products = response.data; 
      $scope.totalPages   = response.data.last_page;
      $scope.currentPage  = response.data.current_page;
      $(".icon-del").show();
      $scope.count_all = response.data.count_all;
      $scope.count_active = response.data.count_active;
      $scope.count_inactive = response.data.count_inactive;
      $scope.count_soldout = response.data.count_soldout;
      $scope.count_expired = response.data.count_expired;
      $scope.count_awaiting = response.data.count_awaiting;
      $scope.count_onsale = response.data.count_onsale;
        // Pagination Range
      var pages = []; 
      for(var i=1;i<=response.data.last_page;i++) {          
        pages.push(i);
      }
      $scope.range = pages; 
      
      if(response.data.total==0)
      {
        $(".no_products").show();
      }
      else
      {
       $(".no_products").hide();
      }
      $('.all-pro-table').removeClass('whiteloading');
  }); 
};
$scope.search_result();

$scope.updateFilterby = function (filter)
{
  $("#search_by").val(filter);
  var filter_placeholder;
  switch(filter) {
    case 'all':
        filter_placeholder="Search";
        break;
    case 'id':
        filter_placeholder="Search ID";
        break;
    case 'title':
        filter_placeholder="Search Title";
        break;
    case 'sku':
        filter_placeholder="Search SKU";
        break;
    default:
        filter_placeholder="Search";
  }
  $("#search").attr("placeholder",filter_placeholder);
}
$(document).on("click",'#no-sel',function(e) {
  if($(this).hasClass("all-sel")) {
        $(".pro-check").attr("checked" , true);
    } else {
        $(".pro-check").attr("checked" , false);
    }
});
$(document).on("keypress",'#search',function(e) {
    if(e.which == 13) {
        $scope.search_result();
    }
});


$scope.resetfilter = function (filter){
    if($("#search").val()!="")
    {
      $("#search").val("");
      $scope.search_result();      
    }
    $(".icon-del").hide();
}
$(document).on('change', '.pro-check', function (e) {
  var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-product").attr("style","display:inline-block");
    }    
    else
    {      
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-product").hide();  
      if(action_count ==1)
      {
        if($(".pro-check:checked").hasClass('Inactive'))
        {
          $('.active_select').css('display', 'list-item');
          $('.deactive_select').css('display', 'none');
        }
        else if($(".pro-check:checked").hasClass('Active'))
        {
          $('.active_select').css('display', 'none');
          $('.deactive_select').css('display', 'list-item');
        }
      }
      else
      {
        $('.active_select').css('display', 'list-item');
        $('.deactive_select').css('display', 'list-item');
      }
    }
});

$(document).on('change', '.select_product', function (e) {
     var up=$(this).val();
    $('.all-pro-table').addClass('whiteloading');
    var data=[];
   data.push($(this).attr("data-id"));
    
    $http.post('product/status_update', { update:up,data : data,_token:$("#token").val() }).then(function(response) 
    {
      $scope.search_result();
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-product").attr("style","display:inline-block");
    }, function(response)
    {
        if(response.status=='300')
         window.location = APP_URL+'/login';
    });

});

$(document).ready(function(){
  $('.delete-items').click(function(e) {
    
    var delete_data=[];
    $(".pro-check:checked").each(function() {
        var id = $(this).attr("data-id");
        delete_data.push(id);
    });
    
    $('.all-pro-table').addClass('whiteloading');
    $http.post('product/status_update', { update:"delete",data : delete_data,_token:$("#token").val() }).then(function(response) 
    {

      if(response.data.status=="success")
      {
        $scope.search_result();
        $("#no-sel").removeClass("all-sel");
        $(".action_count").html("");
        $(".dropdown.bulk.action").hide();
        $(".dropdown.select-product").attr("style","display:inline-block");
      }
      else
      {
        $('.all-pro-table').removeClass('whiteloading');
        $('#active-error .modal-title').text(response.data.title);
        $('#active-error .modal-body').text(response.data.body);
        $('#active-error').modal('show');
      }

    }, function(response)
    {
        if(response.status=='300')
         window.location = APP_URL+'/login';
    });
  })

  $('.deactivate-items').click(function(e) {
    $('.all-pro-table').addClass('whiteloading');
    var deactivate_data=[];
    $(".pro-check:checked").each(function() {
        var id = $(this).attr("data-id");
        deactivate_data.push(id);
    });
    
    $http.post('product/status_update', { update:"deactivate",data : deactivate_data,_token:$("#token").val() }).then(function(response) 
    {
      $scope.search_result();
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-product").attr("style","display:inline-block");
    }, function(response)
    {
        if(response.status=='300')
         window.location = APP_URL+'/login';
    });
  })

  $('.activate-items').click(function(e) {
    
    var activate_data=[];
    
    var check_status = [];
    $(".pro-check:checked").each(function() {
        if($(this).hasClass('Active')){
          var id = $(this).attr("data-id");
          check_status.push(id);       
        }
        else{
          var id = $(this).attr("data-id");
          activate_data.push(id);
        }
    });
    
    if(check_status.length == 0){
      $('.all-pro-table').addClass('whiteloading');
      $http.post('product/status_update', { update:"activate",data : activate_data,_token:$("#token").val() }).then(function(response) 
      {
        $scope.search_result();
        $("#no-sel").removeClass("all-sel");
        $(".action_count").html("");
        $(".dropdown.bulk.action").hide();
        $(".dropdown.select-product").attr("style","display:inline-block");
      }, function(response)
      {
          if(response.status=='300')
           window.location = APP_URL+'/login';
      });
    }else{
      alert('There is already an active sale item related to this item : '+ check_status);
    }
  });


  $('.check_action li').click(function(e) {
    var check_status=$(this).attr("data-status");
    switch(check_status) {
        case "all":$(".pro-check").prop("checked" , true);break;
        case "none":$(".pro-check").prop("checked" , false);break;
        case "active":$(".pro-check").prop("checked" , false);$(".pro-check.Active").prop("checked" , true);break;
        case "inactive":$(".pro-check").prop("checked" , false);$(".pro-check.Inactive").prop("checked" , true);break;
        case "soldout":$(".pro-check").prop("checked" , false);$(".pro-check.Yes").prop("checked" , true);break;
        case "expired":$(".pro-check").prop("checked" , false);$(".pro-check.expired").prop("checked" , true);break;
        case "awaiting":$(".pro-check").prop("checked" , false);$(".pro-check.Waiting").prop("checked" , true);break;
        case "onsale":$(".pro-check").prop("checked" , false);$(".pro-check.Approved").prop("checked" , true);break;
        default:$(".pro-check").prop("checked" , false);
    }
    var action_count = $(".pro-check:checked").length;

    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-product").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-product").hide();  
    }
  })
  $('#no-sel').click(function(e) {
    if($(this).hasClass("all-sel"))
    {
      $(".pro-check").prop("checked" , true);
    }
    else
    {
      $(".pro-check").prop("checked" , false);
    }
     var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-product").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-product").hide();  
    }
  })
  
  $('.pro-check').change(function(e) {
    if($("#no-sel").hasClass("all-sel"))
    {
      $(".pro-check").prop("checked" , true);
    }
    else
    {
      $(".pro-check").prop("checked" , false);
    }
     var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-product").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-product").hide();  
    }
  })

})

$(document).on("click", '.tab3 li a', function(e) {
    if($(this).hasClass("current"))
    {
      e.preventDefault();
    }
    else
    {
      var check_data=$(this).attr("data");
      var check_second=0;
      if(check_data!="all")
      {
        $('.check_action li').each(function(){
          check_second++;
          if(check_second>2)
          {
            $(this).attr("style","display:none");
          }
        })        
      }      
      else
      {
        $('.check_action li').attr("style","display:block");
      }
        if(check_data == 'inactive')
          $('.deactive_select').attr('style','display:none');
        else
          $('.deactive_select').attr('style','display:block');
        
        if(check_data == 'active')
          $('.active_select').attr('style','display:none');
        else
          $('.active_select').attr('style','display:block');

      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-product").attr("style","display:inline-block");
      
      $(".tab3 li a").removeClass("current"); 
      $(this).addClass("current");
      $("#search").val("");
      $("#search_by").attr("value","all");
      $("#search").attr("placeholder","Search");
      $("#current").val($(this).attr("data"));
      $scope.products= [];
      $scope.search_result();
    } 
})



  function setGetParameter(paramName, paramValue)
  {
      var url = window.location.href;

      if (url.indexOf(paramName + "=") >= 0)
      {
          var prefix = url.substring(0, url.indexOf(paramName));
          var suffix = url.substring(url.indexOf(paramName));
          suffix = suffix.substring(suffix.indexOf("=") + 1);
          suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
          url = prefix + paramName + "=" + paramValue + suffix;
      }
      else
      {
      if (url.indexOf("?") < 0)
          url += "?" + paramName + "=" + paramValue;
      else
          url += "&" + paramName + "=" + paramValue;
      }
      history.pushState(null, null, url);
  }

}]);
app.controller('merchant_dashboard', ['$scope', '$http', function($scope, $http) 
{
    $('#insights_details').click(function(e) {  

    var days = $(this).val();  
    
      if(days=='Today')
      {
        days = '1';
      }
    
       $http.post(APP_URL+'/merchant/insight_summary', { days:days}).then(function(response) 
        {
          $scope.total_views     = response.data.total_views;
          $scope.total_likes    = response.data.total_likes;
          $scope.total_order     = response.data.total_order;
          $scope.total_amount     = response.data.total_amount;
        });
    });
      
    $('#insights_details').trigger('click');

}]);

app.controller('brand_image', ['$scope', '$http', function($scope, $http) 
{
  $scope.store_header =[];
  $scope.store_logo =[];
  
  $http.post('store/get_store_data', { id : $("#merchant_id").val() }).then(function(response) 
  {
    if(response.data.succresult && response.data.succresult[0] && response.data.succresult[0].original_logo_img!="" && response.data.succresult[0].original_logo_img!=null)
    { 
      $scope.store_logo = response.data.succresult;
      $("#add-logo").hide();
      $("#next_store_logo").show();

    }
    if(response.data.succresult && response.data.succresult[0] && response.data.succresult[0].original_header_img!="" && response.data.succresult[0].original_header_img!=null)
    { 
      $scope.store_header = response.data.succresult;
      $("#add-header").hide();
      
    }
    $("#next_store_header").show();

  }, function(response)
  {
  if(response.status=='300')
   window.location = APP_URL+'/login';
  });


  function upload_store_logo()
  {
    $(document).on("change", '#add-store-logo', function() 
    {
      var ext = this.value.match(/\.(.+)$/)[1];
          ext = ext.toLowerCase();
      var file_size=this.files[0].size;
      if(file_size<=5000000)
      {
          if(ext == 'png' || ext == 'jpg' || ext == 'jpeg' || ext == 'gif')
          {
            $("#store_logo_upload_error").hide();
            var loading = '<div class="" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;z-index:9;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading whiteloading_im"></div></div></div></div></div>';
            var li_length=$("#js-photo-logo li").length;
            if(li_length==1)
            {
            $("#js-photo-logo li").last().append(loading);
            }
            else
            {
            $("#js-photo-logo li .panel").first().append(loading);  
            }
            
            
            
            
            jQuery.ajaxFileUpload({
                  url: APP_URL+"/merchant/store/update_logo/"+$("#merchant_id").val(),
                  secureuri: false,
                  fileElementId: "add-store-logo",
                  dataType: "json",
                  async: false,
                  success: function(response){
                    $( "#js-photo-logo #js-manage-listing-content-container" ).remove();
                    $('#js-photo-logo li:last-child').remove();
                    if(response.succresult)
                    {
                      if(response.error['error_title'])
                      {
                        $("#logo_upload_error").html(response.error['error_description']);
                        $("#logo_upload_error").show();
                      }
                      else
                      {
                        $("#logo_upload_error").hide();
                        $("#add-logo").hide();
                        $("#next_store_logo").show();
                        $scope.store_logo = response.succresult;
                        $('#logoimg').val(response.logoimg);
                        $scope.$apply();
                      }
                      $('#add-store-logo').reset();
                    }
                  }
            });
          }
          else
          {
            $("#store_logo_upload_error .invalid_image").show();
            $("#store_logo_upload_error .max_file").hide();
            $("#store_logo_upload_error").show();
          }
         }
      else
      {
        $("#store_logo_upload_error .max_file").show();
        $("#store_logo_upload_error .invalid_image").hide();
        $("#store_logo_upload_error").show();
      }    
    });
  }

  function upload_store_header()
  {
    $(document).on("change", '#add-store-header', function() 
    {
      var ext = this.value.match(/\.(.+)$/)[1];
          ext = ext.toLowerCase();
      var file_size=this.files[0].size;
      if(file_size<=5000000)
      {
        if(ext == 'png' || ext == 'jpg' || ext == 'jpeg' || ext == 'gif')
        {
          $("#store_header_upload_error").hide();
          
          var loading = '<div class="" id="js-manage-listing-content-container" class="header_img_loader"><div class="manage-listing-content-wrapper" style="height:100%;z-index:9;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading whiteloading"></div></div></div></div></div>';
          var li_length=$("#js-photo-header li").length;
          if(li_length==1)
          {
          $("#js-photo-header li").first().append(loading);
          }
          else
          {
          $("#js-photo-header li").first().append(loading);  
          }
          
          
          jQuery.ajaxFileUpload({
                url: APP_URL+"/merchant/store/update_header/"+$("#merchant_id").val(),
                secureuri: false,
                fileElementId: "add-store-header",
                dataType: "json",
                async: false,
                success: function(response){
                  $( "#js-photo-header #js-manage-listing-content-container" ).remove();
                  $('#js-photo-header li:last-child').remove();
                  if(response.succresult)
                  {
                    if(response.error['error_title'])
                    {
                      $("#header_upload_error").html(response.error['error_description']);
                      $("#header_upload_error").show();
                    }
                    else
                    { //console.log(response)
                      $("#header_upload_error").hide();
                      $("#add-header").hide();
                      $("#next_store_header").show();
                      $scope.store_header = response.succresult;
                       $('#headerimg').val(response.headerimg);
                      $scope.$apply();
                    }
                    $('#add-store-header').reset();
                  }
                }
          });
        }
        else
        {
          $("#store_header_upload_error .invalid_image").show();
          $("#store_header_upload_error .max_file").hide();
          $("#store_header_upload_error").show();
        }
      }
      else
      {
        $("#store_header_upload_error .max_file").show();
        $("#store_header_upload_error .invalid_image").hide();
        $("#store_header_upload_error").show();
      }
    });
  }

upload_store_logo();
upload_store_header();

/* ajaxfileupload */
jQuery.extend({ handleError: function( s, xhr, status, e ) {if ( s.error ) s.error( xhr, status, e ); else if(xhr.responseText) console.log(1); } });
jQuery.extend({createUploadIframe:function(e,t){var r="jUploadFrame"+e;if(window.ActiveXObject){var n=document.createElement("iframe");n.id=n.name=r,"boolean"==typeof t?n.src="javascript:false":"string"==typeof t&&(n.src=t)}else{var n=document.createElement("iframe");n.id=r,n.name=r}return n.style.position="absolute",n.style.top="-1000px",n.style.left="-1000px",document.body.appendChild(n),n},createUploadForm:function(e,t){var r="jUploadForm"+e,n="jUploadFile"+e,o=jQuery('<form  action="" method="POST" name="'+r+'" id="'+r+'" enctype="multipart/form-data"></form>'),a=jQuery("#"+t),u=jQuery(a).clone();return jQuery(a).attr("id",n),jQuery(a).before(u),jQuery(a).appendTo(o),jQuery(o).css("position","absolute"),jQuery(o).css("top","-1200px"),jQuery(o).css("left","-1200px"),jQuery(o).appendTo("body"),o},ajaxFileUpload:function(e){e=jQuery.extend({},jQuery.ajaxSettings,e);var t=(new Date).getTime(),r=jQuery.createUploadForm(t,e.fileElementId),n=(jQuery.createUploadIframe(t,e.secureuri),"jUploadFrame"+t),o="jUploadForm"+t;e.global&&!jQuery.active++&&jQuery.event.trigger("ajaxStart");var a=!1,u={};e.global&&jQuery.event.trigger("ajaxSend",[u,e]);var c=function(t){var o=document.getElementById(n);try{o.contentWindow?(u.responseText=o.contentWindow.document.body?o.contentWindow.document.body.innerHTML:null,u.responseXML=o.contentWindow.document.XMLDocument?o.contentWindow.document.XMLDocument:o.contentWindow.document):o.contentDocument&&(u.responseText=o.contentDocument.document.body?o.contentDocument.document.body.innerHTML:null,u.responseXML=o.contentDocument.document.XMLDocument?o.contentDocument.document.XMLDocument:o.contentDocument.document)}catch(c){jQuery.handleError(e,u,null,c)}if(u||"timeout"==t){a=!0;var d;try{if(d="timeout"!=t?"success":"error","error"!=d){var l=jQuery.uploadHttpData(u,e.dataType);e.success&&e.success(l,d),e.global&&jQuery.event.trigger("ajaxSuccess",[u,e])}else jQuery.handleError(e,u,d)}catch(c){d="error",jQuery.handleError(e,u,d,c)}e.global&&jQuery.event.trigger("ajaxComplete",[u,e]),e.global&&!--jQuery.active&&jQuery.event.trigger("ajaxStop"),e.complete&&e.complete(u,d),jQuery(o).unbind(),setTimeout(function(){try{jQuery(o).remove(),jQuery(r).remove()}catch(t){jQuery.handleError(e,u,null,t)}},100),u=null}};e.timeout>0&&setTimeout(function(){a||c("timeout")},e.timeout);try{var r=jQuery("#"+o);jQuery(r).attr("action",e.url),jQuery(r).attr("method","POST"),jQuery(r).attr("target",n),r.encoding?r.encoding="multipart/form-data":r.enctype="multipart/form-data",jQuery(r).submit()}catch(d){jQuery.handleError(e,u,null,d)}return window.attachEvent?document.getElementById(n).attachEvent("onload",c):document.getElementById(n).addEventListener("load",c,!1),{abort:function(){}}},uploadHttpData:function(r,type){var data=!type;return data="xml"==type||data?r.responseXML:r.responseText,"script"==type&&jQuery.globalEval(data),"json"==type&&eval("data = "+data),"html"==type&&jQuery("<div>").html(data).evalScripts(),data}});

  $("#add-logo").on('click', function(e) {
        e.preventDefault();
        $("#add-store-logo:hidden").trigger('click');
    });

  $("#add-header").on('click', function(e) {
        e.preventDefault();
        $("#add-store-header:hidden").trigger('click');
  });
  $(document).on('click', '#next_store_logo label', function(e)
  {
        e.preventDefault();
        $("#add-store-logo:hidden").trigger('click');
  });
  $(document).on('click', '#next_store_header label', function(e)
  {
        e.preventDefault();
        $("#add-store-header:hidden").trigger('click');
  });

  $scope.delete_store_logo = function(item, id,delete_photo,delete_message)
  {
    $('#js-error-store .modal-title').text(delete_photo);
    $('#js-error-store .modal-body').text(delete_message);
    $('.js-delete-photo-confirm').removeClass('d-none');
    $('#js-error-store').attr('aria-hidden',false);
    $('#js-error-store .js-delete-photo-confirm').attr('data-id',id);
    $('#js-error-store .js-delete-photo-confirm').attr('data-type',"logo");
    var index=$scope.store_logo.indexOf(item);
    $('#add-store-logo').val('');
    $('#js-error-store .js-delete-photo-confirm').attr('data-index',index);
  };

  $scope.delete_store_header = function(item, id,delete_photo,delete_message)
  {
    $('#js-error-store .modal-title').text(delete_photo);
    $('#js-error-store .modal-body').text(delete_message);
    $('.js-delete-photo-confirm').removeClass('d-none');
    $('#js-error-store').attr('aria-hidden',false);
    $('#js-error-store .js-delete-photo-confirm').attr('data-id',id);
    $('#js-error-store .js-delete-photo-confirm').attr('data-type',"header");
    var index=$scope.store_header.indexOf(item);
    $('#js-error-store .js-delete-photo-confirm').attr('data-index',index);
  };

$(document).on('click', '.js-delete-photo-confirm', function(index)
{
  var index = $(this).attr('data-index');
  var type = $(this).attr('data-type');
  if(type=="logo")
  {
    $http.post('store/delete_store_logo', { id : $(this).attr('data-id') }).then(function(response) 
    {  //console.log(response);
      $('#delete_log_img').val(response.data.img_name);
      $scope.store_logo=[];
      $('#js-error-store').attr('aria-hidden',true);
      $("#add-logo").css('display','table');
      $("#next_store_logo").css('display','hide');

    }, function(response)
    {
    if(response.status=='300')
     window.location = APP_URL+'/login';
    });
  }
  else if(type=="header")
  {
    $http.post('store/delete_store_header', { id : $(this).attr('data-id') }).then(function(response) 
    {
      $('#delete_header_img').val(response.data.img_name);
      $scope.store_header=[];
      $('#js-error-store').attr('aria-hidden',true);
      $("#add-header").show();
      $("#next_store_header").hide();
    }, function(response)
    {
    if(response.status=='300')
     window.location = APP_URL+'/login';
    });
  }
});

  $scope.save_brand = function(index) {
    var store_name=$("#store_name").val();
    var store_tag=$("#store_tagline").val();
    var store_desc=tinyMCE.get('store_description').getContent();
    if(store_name=="")
    {
      $("#brand_error").html("Please enter brand name.");
      $("#brand_error").removeClass("success").addClass("error");
      $("#brand_error").show();
    }
    else if(store_tag=="")
    {
      $("#brand_error").html("Please enter brand tagline.");
      $("#brand_error").removeClass("success").addClass("error");
      $("#brand_error").show();
    }
    else
    {
      $http.post('store/save_brand', { merchant_id : $("#merchant_id").val(),name:store_name,tag:store_tag,desc:store_desc}).then(function(response) 
      {
        $("#brand_error").html("Success! Your settings have been updated.");
        $("#brand_error").addClass("success").removeClass("error");   
        $("#brand_error").show().delay(3000).fadeOut();
      }, function(response)
      {
      if(response.status=='300')
       window.location = APP_URL+'/login';
      });
    }
    $("html, body").animate({ scrollTop: 0 }, "slow"); 
  }


}]);


app.controller('add_product', ['$scope', '$http', '$timeout','$sce', function($scope, $http, $timeout,$sce) 
{ 

  $scope.product_options = [];
  $scope.shipping = [];
  $scope.images = [];
  $scope.shipping_type="Flat Rates";

  var image_processing=0;
  var video_processing=0;
  var currency_processing=0;
  
  // Takes a snapshot of the video
  $("#snap").click(function(){
    context.fillRect(0, 0, w, h);
    // Grab the image from the video
    context.drawImage(video, 0, 0, w, h);
    // Define the size of the rectangle that will be filled (basically the entire element)
    context.fillRect(0, 0, w, h);
    // Grab the image from the video
    context.drawImage(video, 0, 0, w, h);  
    var dataURL = canvas.toDataURL();
    document.getElementById('canvasImg').src = dataURL;
    upload_thumb();
  });

  function upload_thumb()
  {    
    var image_canvas = $('#canvasImg').attr('src');
    var base64ImageContent = image_canvas.replace(/^data:image\/(png|jpg);base64,/, "");
    var blob = base64ToBlob(base64ImageContent, 'image/png');                
    var formData = new FormData();
    formData.append('picture', blob);
    $.ajax({
      url: APP_URL+"/merchant/product/add_video_thumb/"+$("#product_id").val()+'/'+$("#update_type").val(),
      type: "POST", 
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      success: function(response){
        $('#add_product_video').val("1");
        if(response.error['error_title'] == 'Invalid Product Id'){
              window.location = APP_URL+'/merchant/all_products';
        }else{
          video_processing=0;
          activate_submit();
        }
      }
    });
  }

  function base64ToBlob(base64, mime) 
  {
      mime = mime || '';
      var sliceSize = 1024;
      var byteChars = window.atob(base64);
      var byteArrays = [];

      for (var offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
          var slice = byteChars.slice(offset, offset + sliceSize);

          var byteNumbers = new Array(slice.length);
          for (var i = 0; i < slice.length; i++) {
              byteNumbers[i] = slice.charCodeAt(i);
          }

          var byteArray = new Uint8Array(byteNumbers);

          byteArrays.push(byteArray);
      }

      return new Blob(byteArrays, {type: mime});
  }
  var video = document.querySelector('video');
  var canvas = document.querySelector('canvas');
  // Get a handle on the 2d context of the canvas element
  var context = canvas.getContext('2d');
  // Define some vars required later
  var w, h, ratio;    
  video.crossOrigin = "Anonymous";
  // Add a listener to wait for the 'loadedmetadata' state so the video's dimensions can be read
  video.addEventListener('loadedmetadata', function() {
    // Calculate the ratio of the video's width to height
    ratio = video.videoWidth / video.videoHeight;
    // Define the required width as 100 pixels smaller than the actual video's width
    w = video.videoWidth - 100;
    // Calculate the height based on the video's width and the ratio
    h = parseInt(w / ratio, 10);
    // Set the canvas width and height to the values just calculated
    canvas.width = w;
    canvas.height = h;      
  }, false);


  $scope.updateOptionDiscount = function(index) {
    
    
    var discount_cal=100-($scope.product_options[index].price/$scope.product_options[index].retail_price)*100;

    if(isNaN(discount_cal) || discount_cal=="-Infinity" || discount_cal < 0)
    {
    $scope.product_options[index].option_discount = "0"; 
    
    }
    else
    {
    $scope.product_options[index].option_discount = discount_cal.toFixed(2);
    }

  }

  $scope.saveOption = function(index) {
     var valid_error =0;
    $('#required_qty_'+index).hide();
     $('#required_price_'+index).hide();
    
    $scope.product_options[index].total_quantity=$scope.product_options[index].option_qty;
    $scope.product_options[index].option_name=$scope.product_options[index].option_name;
    var minimum_amount = $('#minimum_amount').val();

    if($scope.product_options[index].option_qty =='' || $scope.product_options[index].option_qty == null)
    {
      $('#required_qty_'+index).show();
      valid_error = 1;
    }
    else
    {
      $('#required_qty_'+index).hide();
    }

    if($scope.product_options[index].option_price == '' || $scope.product_options[index].option_price == null )
    { 
      $('#required_price_'+index).show();
      valid_error =1;
    }    
    else
    {
      $('#required_price_'+index).hide();
    }
    
    if(parseInt($scope.product_options[index].option_price) < parseInt(minimum_amount))
    {
      $('#required_price_less_'+index).show();
      $('#required_price_less_'+index).html(Lang.get('js_messages.merchant.price_min')+' '+$('#currency_symbol').html()+' '+$('#minimum_amount').val());
      valid_error =1;
    }
    else{
      $('#required_price_less_'+index).hide();
    }

    if($(".check_sale_option_"+index).prop('checked') == true && parseInt($scope.product_options[index].option_price) >= parseInt($scope.product_options[index].retail_price))
    { 
      $('#required_price_greater_'+index).show();
      valid_error =1;
    }
    else
    {
      $('#required_price_greater_'+index).hide();
    }

    if(valid_error==0){
      $scope.product_options[index].price=$scope.product_options[index].option_price;
      $scope.update_main_quantity();
      $('.close').trigger('click');
    }
  }

  $scope.updateOptionprice = function(index) {
    $scope.product_options[index].option_price=$scope.product_options[index].price;
  }

  $scope.UpdateOptionname = function(index) {
     var sel = $("#input-tags");
        var selectize1 = sel[0].selectize;
        selectize1.updateOption($scope.product_options[index].optionname, $scope.product_options[index].option_name)
  }

   $scope.updateDiscount = function() {
    var discount_cal=100-($scope.price/$scope.retail_price)*100;
    if(isNaN(discount_cal)  || discount_cal=="-Infinity" || discount_cal < 0)
    {
    $scope.discount="0"; 
    
    }
    else
    {
    $scope.discount=discount_cal.toFixed(2);
    }

  }

$(document).ready(function() {

  $('#drilldown').drilldownSelect({ 
    appendValue: false, 
    data: data,
    onSelected: function(event) {
        $scope.category = $(event.target).data('id');
        $("#category_id").val($(event.target).data('id'));
        $("#category_path").val($(event.target).data('path'));

      }
  });


  $("#add-image").on('click', function(e) {
        e.preventDefault();
        $("#add-product-imagevideo:hidden").trigger('click');
  });

  $(".add_video_mp4").on('click', function(e) {
        e.preventDefault();
        $("#add_product_video_mp4:hidden").trigger('click');
  });

  $(".add_video_webm").on('click', function(e) {
        e.preventDefault();
        $("#add_product_video_webm:hidden").trigger('click');
  });



  $(document).on("click", '.add-image-option', function(e) {
      var iid=$(this).attr("data");
        e.preventDefault();
        $("#add-product-option-imagevideo_"+iid+":hidden").trigger('click');
  });





  $('#check-sale').click(function() {
    if($('#check-sale').is(':checked')) {
      $('.retail').show();
      $('.discount').show();
    }
    else {
      $('.retail').hide();
      $('.discount').hide();
    }
  });
  $(document).on("click", '.check_sale_option', function() {
 
    var id=$(this).attr('data');
    if($(this).is(':checked')) {
      $('.retail_'+id).show();
      $('.discount_'+id).show();
    }
    else {
      $('.retail_'+id).hide();
      $('.discount_'+id).hide();
    }
  });

  $scope.hide_shipping_column = false;

  $('#input-tags').selectize({
          plugins: ['remove_button'],
          persist: false,
          create: true,
          openOnFocus: false,
          create: function(input) {
            return {
              value: input,
              text: input
            }
          },
          onItemAdd: function(input) {
            $('#total_quantity').prop('readonly', true);
            $scope.product_options.push({id:'0',option_name: input, sku: '', quantity: '', sold: '', price: ''});
            $scope.$apply();
            $(".cc_symbol").attr("placeholder",$("#currency_symbol").html());
          },
          onItemRemove: function(input) {
            for(var i = $scope.product_options.length - 1; i >= 0; i--){
              if($scope.product_options[i].option_name == input){
                $scope.product_options.splice(i,1);
              }
            }
            
            $scope.update_main_quantity();
            $scope.$apply();
            if($scope.product_options.length==0)
            {
              $('#total_quantity').prop('readonly', false);
            }
          },
          onDropdownOpen: function(dropdown) {
            dropdown.remove();
          }
  });

  $('#ships_to').selectize({
    plugins: ['remove_button'],
    onItemAdd: function(input) {
      $scope.shipping.push({ships_to: input, charge: '', incremental_fee: '', start_window: '', end_window: ''});
      $scope.$apply();
      $(".cc_symbol").attr("placeholder",$("#currency_symbol").html());
    },
    onItemRemove: function(input) {
      for(var i = $scope.shipping.length - 1; i >= 0; i--){
        if($scope.shipping[i].ships_to == input){
          $scope.shipping.splice(i,1);
        }
      }
      $scope.$apply();
    }
  });

  $('#shipping-type_free').click(function() {
    if($(this).parent('li').hasClass('checked') == false) {
      $(this).parent('li').addClass('checked');
      $('#shipping-type_flat').parent('li').removeClass('checked');
      $scope.shipping_type = "Free Shipping";
      $("#shipping_type").val("Free Shipping");
      $scope.hide_shipping_column = true;
      $scope.$apply();
    }
  });

  $('#shipping-type_flat').click(function() {
    if($(this).parent('li').hasClass('checked') == false) {
      $(this).parent('li').addClass('checked');
      $('#shipping-type_free').parent('li').removeClass('checked');
      $scope.shipping_type = "Flat Rates";
      $("#shipping_type").val("Flat Rates");
      $scope.hide_shipping_column = false;
      $scope.$apply();
    }
  });

  $('#use_exchange').click(function() {
    if($(this).prop('checked')) {
      $('.use_exchange').hide();
    }
    else {
      $('.use_exchange').show();
    }
  });

  $(".product_status.btn-switch").click(function(){
    if($(".product_status.btn-switch").hasClass("on"))
    {
      $("#product_status").val("inactive");
    }
    else
    {
      $("#product_status").val("Active");
    }

  })

  $(".soldout_status.btn-switch").click(function(){
    if($(".soldout_status.btn-switch").hasClass("on"))
    {
      $("#soldout_status").val("No");
    }
    else
    {
      $("#soldout_status").val("Yes");
    }

  })

  $(".cashdelivery_status.btn-switch").click(function(){
    if($(".cashdelivery_status.btn-switch").hasClass("on"))
    {
      $("#cashdelivery_status").val("No");
    }
    else
    {
      $("#cashdelivery_status").val("Yes");
    }

  })

  $(".cashstore_status.btn-switch").click(function(){
    if($(".cashstore_status.btn-switch").hasClass("on"))
    {
      $("#cashdstore_status").val("No");
    }
    else
    {
      $("#cashdstore_status").val("Yes");
    }

  })


$scope.update_main_quantity = function(){
  $scope.total_quantity = 0;

  angular.forEach($scope.product_options, function(value, key){
    if (typeof value.total_quantity != 'undefined')
    {
      $scope.total_quantity = $scope.total_quantity + parseInt(value.total_quantity);
    }
   })
}
$.validator.addMethod("greaterThan",function (value, element, param) {
  var $otherElement = $(param);
  return parseInt(value, 10) > parseInt($otherElement.val(), 10);
});
$.validator.addMethod('minStrict', function (value, el, param) {
    return value > param;
})
 $("#add_product_form").validate({

   groups: {
        charge: 'custom_charge_domestic[]',
        //inc: 'custom_incremental_domestic[]',
        from : 'expected_delivery_day_1[]',
        to : 'expected_delivery_day_2[]',
        productprice: 'product_option_price[]',

    },

 errorElement: "div",
 
errorPlacement: function(error, element) { 
    
     error.appendTo('.error-box');

   },   
      rules: {
      "title": { 
            required: true,
             maxlength: 100,
           },         
      "price" :{
        required : true,
        min :  function () { return $('#minimum_amount').val(); }
      },
      "total_quantity" : "required",
      'retail_price':{
          required: true,
          greaterThan: "#price"
        },
      "custom_charge_domestic[]":"required",
      "ships_to[]" : "required",
        "product_option_qty[]":"required",
        "product_option_price[]":{
        required : true,
        min :  function () { return $('#minimum_amount').val(); }
      },
      'option_price':{
        required : true,
        min :  function () { return $('#minimum_amount').val(); }
      },
      "return_exchange_policy_description" : {
          maxlength: 1500,
        },
      "expected_delivery_day_1[]":"required",
      "expected_delivery_day_2[]":"required",
      
      },
      messages: {
      "title": Lang.get('js_messages.merchant.title'),
      "price": 
        { 
          required:  Lang.get('js_messages.merchant.price'),
          min : function () { return Lang.get('js_messages.merchant.price_min')+' '+$('#currency_symbol').html()+' '+$('#minimum_amount').val(); },
        },
      "total_quantity": Lang.get('js_messages.merchant.total_quantity'),
      'retail_price':
      {
        required :  Lang.get('js_messages.merchant.retail_price'),
        greaterThan:  Lang.get('js_messages.merchant.retail_price_greater')
      }, 
      "product_option_price[]": 
        { 
          required:  Lang.get('js_messages.merchant.price'),
          min : function () { return Lang.get('js_messages.merchant.price_min')+' '+$('#currency_symbol').html()+' '+$('#minimum_amount').val(); },
        },
      "option_price[]": 
        { 
          required:  Lang.get('js_messages.merchant.price'),
          min : function () { return Lang.get('js_messages.merchant.price_min')+' '+$('#currency_symbol').html()+' '+$('#minimum_amount').val(); },
        },
      "custom_charge_domestic[]": Lang.get('js_messages.merchant.charge'),
      "return_exchange_policy_description": Lang.get('js_messages.merchant.return_ex_policy_desc'),
      "expected_delivery_day_1[]":  Lang.get('js_messages.merchant.expected_delivery_from'),
      "expected_delivery_day_2[]" : Lang.get('js_messages.merchant.expected_delivery_to'),    
      }


    });
   

$('#add_product,#add_product1').click(function() {

      var err='';
      var desc_text = $(tinyMCE.get('description').getBody()).text();
      var desc_err = 0;
      
      if(desc_text.length <=1){         
      err="<div id='desc-error'>"+Lang.get('js_messages.merchant.description')+"</div>";  
      desc_err = 1;
      $("#mceu_9").css('border-color','#a92225','!important'); 
      }
      else{
        desc_err = 0;
      $("#mceu_9").css('border-color','#ccc','!important');
      }

      if(desc_err == 0){
        if(desc_text.length >=1500)
        { 
        err="<div id='desc-error'>"+Lang.get('js_messages.merchant.description_char')+"</div>";  
        $("#mceu_9").css('border-color','#a92225','!important'); 
        }
        else{
        $("#mceu_9").css('border-color','#ccc','!important');
        }
      }


      if($("ul[id='js-photo-grid']").children().length <= 2)
      {
      err += "<div id='image-error'>"+Lang.get('js_messages.merchant.image')+"</div>";   
      $('#add-image').css('border','1px solid rgb(169, 34, 37)','!important');
      }
      else{
      $('#add-image').css('border','1px solid #fff','!important');
      }

      if($('#category_id').val()=='' || $('#drilldown').find('span').text()==Lang.get('js_messages.merchant.select_category')) {
      err += "<div id='category-error'>"+Lang.get('js_messages.merchant.category')+"</div>";  
      $('#drilldown').addClass('error');

      }
      else{
      $('#drilldown').removeClass('error');
      }

      if($('.has-options').children().length < 2)
      {
        
      err += "<div id='shipto-error'>"+Lang.get('js_messages.merchant.ships_to')+"</div>"; 
      $(".has-options").css('border-color','#a92225','!important'); 

      }
      else{
      $(".has-options").css('border-color','#ccc','!important');
      }

      if( $('#return_policy').val() == '? number:1 ?')
        {
          err += "<div id='shipto-error'>"+Lang.get('js_messages.merchant.return_policy')+"</div>"; 
          $(".has-options").css('border-color','#a92225','!important');
        }

      if( $("#use_exchange").prop('checked') == false){
        if($('#exchange_policy').val() =='? number:1 ?')
        {
          err += "<div id='shipto-error'>"+Lang.get('js_messages.merchant.exchange_policy')+"</div>"; 
          $(".has-options").css('border-color','#a92225','!important');
        }
      }
     
         $('.error-box').html(err);

      $("#add_product_form").valid();
      if($('.error-box').children().length ==0)
      {
        $('#add_product1').prop("disabled", true); 
        $('#add_product').prop("disabled", true);
        $(".error-box").hide(); 
        $("#add_product_form").submit(); 
        $('#add_product1').prop("disabled", true); 
        $('#add_product').prop("disabled", true);
        return false;
      }
      else
      { 
        $('#add_product1').prop("disabled", false); 
        $('#add_product').prop("disabled", false);
        $('.error-head, .main_error').show(); 
      }
      $("html, body").animate({ scrollTop: 0 }, "slow");

})

});
function activate_submit()
{

  if(image_processing==0 && video_processing==0 && currency_processing==0)
  {
    $('#add_product1').prop("disabled", false); 
    $('#add_product').prop("disabled", false);
  }  
}
function deactivate_submit()
{
  $('#add_product1').prop("disabled", true); 
  $('#add_product').prop("disabled", true);
}


tinymce.init({
  selector:'#description',
  height: 250,
  menubar: false,
  statusbar: false,
  branding: false,
  elements : "description",
  plugins: [
    'link'
  ],
  toolbar: 'bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent | link ',

});




$scope.photos_list = [];
$scope.product_list = [];
$scope.photos_option_list = [];


function upload_option()
{
$(document).on("change", '.add-product-option-imagevideo', function() {
  var ext = this.value.match(/\.(.+)$/)[1];
      ext = ext.toLowerCase();
  if(ext == 'png' || ext == 'jpg' || ext == 'jpeg' || ext == 'gif')
  {
    
    var data=$(this).attr("data");
    var option_db_id=$(this).attr("data-db");
    var optionid=$(this).attr("data-option");
    var jsul_id="#js-option-photo-grid_"+optionid;
    var error_id="#image_upload_error_"+optionid;
    $(error_id).hide();
    $(jsul_id).append('<li class="col-lg-4 col-md-6 row-space-4"><div class=" photo-item"><div class=="photo-size photo-drag-target js-photo-link"></div></div></li>');
    var loading = '<div class="" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;z-index:9;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading whiteloading"></div></div></div></div></div>';
    $(jsul_id+" li").last().append(loading);
    jQuery.ajaxFileUpload({
          url: APP_URL+"/merchant/product/add_option_photos/"+$("#product_id").val()+"/"+optionid+"/"+option_db_id+"/"+$("#update_type").val(),
          secureuri: false,
          fileElementId: "add-product-option-imagevideo_"+optionid,
          dataType: "json",
          async: false,
          success: function(response){

                 // $scope.photos_list = response.data;
            $(jsul_id+" #js-manage-listing-content-container" ).remove();
            $(jsul_id+' li:last-child').remove();
          $scope.steps_count = response.steps_count;

          if(response.error['error_title'])
          {
            $('#js-error .panel-header').text(response.error['error_title']);
            $('#js-error .panel-body').text(response.error['error_description']);
            $('.js-delete-photo-confirm').addClass('d-none');
            $('#js-error').attr('aria-hidden',false);
          }

          if(response.succresult)
          {
              $scope.photos_option_list[data] = response.succresult;
              $scope.$apply();
              $('.add-product-option-imagevideo').reset();
          }
         
          }
    });
  }
  else
  {

    var optionid=$(this).attr("data-option");
    var error_id="#image_upload_error_"+optionid;
    $(error_id).show();
  }  
});
}

function upload()
{
$(document).on("change", '#add-product-imagevideo', function() {
     var ext =this.value.replace(/C:\\fakepath\\/i, '');
     ext=ext.substr( (ext.lastIndexOf('.') +1) ); 
     ext = ext.toLowerCase();
      $('.add_product1').attr('disabled','true');
      
      if(ext == 'png' || ext == 'jpg' || ext == 'jpeg' || ext == 'gif')
      {
        $("#image_upload_error").hide();
        $("#cloud_upload_error").hide();
        $('#js-photo-grid').append('<li class="col-lg-4 col-md-6 row-space-4"><div class=" photo-item"><div class=="photo-size photo-drag-target js-photo-link"></div></div></li>');
        var loading = '<div class="" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;z-index:9;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading whiteloading_img"></div></div></div></div></div>';
        $("#js-photo-grid li").last().append(loading);
        $('#add_product1').prop("disabled", true); 
        $('#add_product').prop("disabled", true);
        deactivate_submit();
        image_processing=1;
        if($("#update_type").val() == 'add_product'){
          var productid = $("#product_id").val();
          var product_id = $("#product_id").val();
        }else{
           var productid = $("#tmp_product_id").val();
            var product_id = $("#product_id").val();
        } 
        $('#add-image').addClass("loading");  
        jQuery.ajaxFileUpload({
              url: APP_URL+"/merchant/product/add_photos/"+productid+'/'+product_id+'/'+$("#update_type").val(),
              secureuri: false,
              fileElementId: "add-product-imagevideo",
              dataType: "json",
              async: false,
              success: function(response){
                $('#add-image').removeClass("loading");   
                $('#add_product1').prop("disabled", false); 
                $('#add_product').prop("disabled", false);         
                image_processing=0;
                activate_submit();
                $('#add-image').removeClass('error');
                     // $scope.photos_list = response.data;
                $( "#js-photo-grid #js-manage-listing-content-container" ).remove();
                $('#js-photo-grid li:last-child').remove();
              $scope.steps_count = response.steps_count;

              if(response.error['error_title'])
              {
                if(response.error['error_title'] == 'Invalid Product Id'){
                  window.location = APP_URL+'/merchant/all_products';
                }else{
                  $("#cloud_upload_error").html(response.error['error_description']);
                  $("#cloud_upload_error").show();
                  $('#js-error .panel-header').text(response.error['error_title']);
                  $('#js-error .panel-body').text(response.error['error_description']);
                  $('.js-delete-photo-confirm').addClass('hide');
                  $('#js-error').attr('aria-hidden',false);
                }
              }
              $('#add-image').css('border','1px solid #fff','!important');
              if(response.succresult)
              {
                  $scope.photos_list = response.succresult;
                  // alert($scope.photos_list[0].id);
                  $scope.$apply();
                  $('#add-product-imagevideo').reset();
              }
              // upload();
              }
        }); 
        
      }
      else
      {
        $("#image_upload_error").show();
      }
});
}

$(document).on('click','#add-video',function(){
  $("#video_upload_error").hide();
  $("#video_cloud_upload_error").hide();
  $('#video_upload_error_webm').hide();
  $('#video_upload_error_mp4').hide();
  $("#mp4_filename").html("");
  $("#webm_filename").html("");
});

function upload_video() {
  var before_upload_status=0;
  var after_upload_status=0;
  var ok_webm=0;
  var ok_mp4=0;
  $(document).on('click', '#add-video-btn', function(){
    if(before_upload_status && ok_mp4 && ok_webm)
    {
      $("#video_upload_error").hide();
      $("#video_cloud_upload_error").hide();
      $("#video_upload_popup .modal-body").addClass("loading");
      // $('#add_product1').prop("disabled", true); 
      // $('#add_product').prop("disabled", true); 
      deactivate_submit();
      $('#add-video-btn').prop("disabled", true); 
      video_processing=1;  
      jQuery.ajaxFileUpload({
        url: APP_URL+"/merchant/product/add_video_mp4/"+$("#product_id").val()+'/'+$("#update_type").val(),
        secureuri: false,
        fileElementId: "add_product_video_mp4",
        dataType: "json",
        async: false,
        success: function(response){           
          $('#add-video').removeClass('error');
          $( "#js-video-grid #js-manage-listing-content-container" ).remove();
          if(response.error['error_title'])
          {
            if(response.error['error_title'] == 'Invalid Product Id'){
              window.location = APP_URL+'/merchant/all_products';
            }else{

              $("#video_upload_popup .modal-body").removeClass("loading");
              $("#video_cloud_upload_error").html(response.error['error_description']);
              $("#video_cloud_upload_error").show();
              $('#js-error .panel-header').text(response.error['error_title']);
              $('#js-error .panel-body').text(response.error['error_description']);
              $('.js-delete-photo-confirm').addClass('hide');
              $('#js-error').attr('aria-hidden',false);
              // $('#add_product1').prop("disabled", false); 
              // $('#add_product').prop("disabled", false);
              video_processing=0;
              activate_submit();
              $('#add-video-btn').prop("disabled", false); 
            }
          }
          $('#add-video').css('border','1px solid #fff','!important');
          if(response.succresult)
          {
            jQuery.ajaxFileUpload({
              url: APP_URL+"/merchant/product/add_video_webm/"+$("#product_id").val()+'/'+$("#update_type").val(),
              secureuri: false,
              fileElementId: "add_product_video_webm",
              dataType: "json",
              async: false,
              success: function(response){           
                $('#add-video').removeClass('error');
                $( "#js-video-grid #js-manage-listing-content-container" ).remove();
                if(response.error['error_title'])
                {
                  if(response.error['error_title'] == 'Invalid Product Id'){
                    window.location = APP_URL+'/merchant/all_products';
                  }else{
                    $("#video_upload_popup .modal-body").removeClass("loading");
                    $("#video_cloud_upload_error").html(response.error['error_description']);
                    $("#video_cloud_upload_error").show();
                    $('#js-error .panel-header').text(response.error['error_title']);
                    $('#js-error .panel-body').text(response.error['error_description']);
                    $('.js-delete-photo-confirm').addClass('hide');
                    $('#js-error').attr('aria-hidden',false);
                    // $('#add_product1').prop("disabled", false); 
                    // $('#add_product').prop("disabled", false);
                    video_processing=0;
                    activate_submit();
                    $('#add-video-btn').prop("disabled", false); 
                  }
                }
                $('#add-video').css('border','1px solid #fff','!important');
                if(response.video_src)
                {
                  $scope.video_src = $sce.trustAsResourceUrl(response.video_src);
                  
                  $scope.$apply();
                  $("#video_upload_popup .modal-body").removeClass("loading");
                  $('#video_upload_popup').modal('hide');                                    
                  $('#add-video-btn').prop("disabled", false); 
                  var video = document.querySelector('video');
                  var canvas = document.querySelector('canvas');
                  // Get a handle on the 2d context of the canvas element
                  var context = canvas.getContext('2d');
                  // Define some vars required later
                  var w, h, ratio;    
                  // Add a listener to wait for the 'loadedmetadata' state so the video's dimensions can be read
                  video.addEventListener('loadedmetadata', function() {
                    // Calculate the ratio of the video's width to height
                    ratio = video.videoWidth / video.videoHeight;
                    // Define the required width as 100 pixels smaller than the actual video's width
                    w = video.videoWidth - 100;
                    // Calculate the height based on the video's width and the ratio
                    h = parseInt(w / ratio, 10);
                    // Set the canvas width and height to the values just calculated
                    canvas.width = w;
                    canvas.height = h;      
                  }, false);
                  setTimeout(function(){
                  context.fillRect(0, 0, w, h);
                  // Grab the image from the video
                  context.drawImage(video, 0, 0, w, h);
                  // Define the size of the rectangle that will be filled (basically the entire element)
                  context.fillRect(0, 0, w, h);
                  // Grab the image from the video
                  context.drawImage(video, 0, 0, w, h);  
                  var dataURL = canvas.toDataURL();
                  document.getElementById('canvasImg').src = dataURL;
                    upload_thumb();
                    $('#add_product1').prop("disabled", false); 
                    $('#add_product').prop("disabled", false);
                  },3000);
                  document.getElementById("add_product_video_mp4").value = '';
                  document.getElementById("add_product_video_webm").value = '';
                  ok_webm=0;
                  ok_mp4=0;
                  $("#mp4_filename").html("");
                  $("#webm_filename").html("");

                }
              }
            });
          }
        }
      });
    }
    else
    {
      $("#video_cloud_upload_error").html("Choose both .mp4 and .webm video");
      $("#video_cloud_upload_error").show();
    }
  });
  $(document).on("change", '#add_product_video_webm', function() {
    var ext =this.value.replace(/C:\\fakepath\\/i, '');
    var filename=ext;
    $("#webm_filename").text(filename);
    ext=ext.substr( (ext.lastIndexOf('.') +1) ); 
    ext = ext.toLowerCase();
    if(ext != 'webm')
    {
      before_upload_status=0;
      ok_webm=0;
      $("#video_upload_error_webm").show();
    }
    else
    {
      before_upload_status=1;
      ok_webm=1;
      $("#video_upload_error_webm").hide(); 
    }
    $("#video_cloud_upload_error").hide();
  });
  $(document).on("change", '#add_product_video_mp4', function() {
    var ext =this.value.replace(/C:\\fakepath\\/i, '');
    var filename=ext;
    $("#mp4_filename").text(filename);
    ext=ext.substr( (ext.lastIndexOf('.') +1) ); 
    ext = ext.toLowerCase();
    if(ext != 'mp4')
    {
      before_upload_status=0;
      ok_mp4=0;
      $("#video_upload_error_mp4").show();
    }
    else
    {
      ok_mp4=1;
      before_upload_status=1;
      $("#video_upload_error_mp4").hide();
    }
    $("#video_cloud_upload_error").hide();
  });
}
upload_video();
upload();
upload_option();

/* ajaxfileupload */
jQuery.extend({ handleError: function( s, xhr, status, e ) {if ( s.error ) s.error( xhr, status, e ); } });
jQuery.extend({createUploadIframe:function(e,t){var r="jUploadFrame"+e;if(window.ActiveXObject){var n=document.createElement("iframe");n.id=n.name=r,"boolean"==typeof t?n.src="javascript:false":"string"==typeof t&&(n.src=t)}else{var n=document.createElement("iframe");n.id=r,n.name=r}return n.style.position="absolute",n.style.top="-1000px",n.style.left="-1000px",document.body.appendChild(n),n},createUploadForm:function(e,t){var r="jUploadForm"+e,n="jUploadFile"+e,o=jQuery('<form  action="" method="POST" name="'+r+'" id="'+r+'" enctype="multipart/form-data"></form>'),a=jQuery("#"+t),u=jQuery(a).clone();return jQuery(a).attr("id",n),jQuery(a).before(u),jQuery(a).appendTo(o),jQuery(o).css("position","absolute"),jQuery(o).css("top","-1200px"),jQuery(o).css("left","-1200px"),jQuery(o).appendTo("body"),o},ajaxFileUpload:function(e){e=jQuery.extend({},jQuery.ajaxSettings,e);var t=(new Date).getTime(),r=jQuery.createUploadForm(t,e.fileElementId),n=(jQuery.createUploadIframe(t,e.secureuri),"jUploadFrame"+t),o="jUploadForm"+t;e.global&&!jQuery.active++&&jQuery.event.trigger("ajaxStart");var a=!1,u={};e.global&&jQuery.event.trigger("ajaxSend",[u,e]);var c=function(t){var o=document.getElementById(n);try{o.contentWindow?(u.responseText=o.contentWindow.document.body?o.contentWindow.document.body.innerHTML:null,u.responseXML=o.contentWindow.document.XMLDocument?o.contentWindow.document.XMLDocument:o.contentWindow.document):o.contentDocument&&(u.responseText=o.contentDocument.document.body?o.contentDocument.document.body.innerHTML:null,u.responseXML=o.contentDocument.document.XMLDocument?o.contentDocument.document.XMLDocument:o.contentDocument.document)}catch(c){jQuery.handleError(e,u,null,c)}if(u||"timeout"==t){a=!0;var d;try{if(d="timeout"!=t?"success":"error","error"!=d){var l=jQuery.uploadHttpData(u,e.dataType);e.success&&e.success(l,d),e.global&&jQuery.event.trigger("ajaxSuccess",[u,e])}else jQuery.handleError(e,u,d)}catch(c){d="error",jQuery.handleError(e,u,d,c)}e.global&&jQuery.event.trigger("ajaxComplete",[u,e]),e.global&&!--jQuery.active&&jQuery.event.trigger("ajaxStop"),e.complete&&e.complete(u,d),jQuery(o).unbind(),setTimeout(function(){try{jQuery(o).remove(),jQuery(r).remove()}catch(t){jQuery.handleError(e,u,null,t)}},100),u=null}};e.timeout>0&&setTimeout(function(){a||c("timeout")},e.timeout);try{var r=jQuery("#"+o);jQuery(r).attr("action",e.url),jQuery(r).attr("method","POST"),jQuery(r).attr("target",n),r.encoding?r.encoding="multipart/form-data":r.enctype="multipart/form-data",jQuery(r).submit()}catch(d){jQuery.handleError(e,u,null,d)}return window.attachEvent?document.getElementById(n).attachEvent("onload",c):document.getElementById(n).addEventListener("load",c,!1),{abort:function(){}}},uploadHttpData:function(r,type){var data=!type;return data="xml"==type||data?r.responseXML:r.responseText,"script"==type&&jQuery.globalEval(data),"json"==type&&eval("data = "+data),"html"==type&&jQuery("<div>").html(data).evalScripts(),data}});

$scope.delete_photo = function(item, id,delete_photo,delete_message)
{

  //$('#js-error .panel-header').text(delete_descrip);
  $('.js-delete-close').attr('data-target',"");
  $('#js-error .modal-title').text(delete_photo);
    $('#js-error .modal-body').text(delete_message);
    $('.js-delete-photo-confirm').removeClass('hide');
    $('#js-error').attr('aria-hidden',false);
    $('.js-delete-photo-confirm').attr('data-id',id);
    $('.js-delete-photo-confirm').attr('data-photo',id);
    $('.js-delete-photo-confirm').attr('data-option',"false");
    var index=$scope.photos_list.indexOf(item);
    $('#add-product-imagevideo').val('');
    $('.js-delete-photo-confirm').attr('data-index',index);
};
$scope.delete_video = function(delete_photo,delete_message)
{
  $('.js-delete-close').attr('data-target',"");
  $('#js-error .modal-title').text(delete_photo);
  $('#js-error .modal-body').text(delete_message);
  $('.js-delete-photo-confirm').removeClass('hide');
  $('#js-error').attr('aria-hidden',false);
  $('.js-delete-photo-confirm').attr('data-option',"video");
  $('.js-delete-photo-confirm').attr('data-index','');
};
$scope.delete_photo_option = function(item, parent, id,option_id,photo_id,delete_photo,delete_message)
{
  //$('#js-error .panel-header').text(delete_descrip);
  $('.js-delete-close').attr('data-target',"");
  $('#js-error .modal-title').text(delete_photo);
  $('#js-error .modal-body').text(delete_message);
  $('.js-delete-photo-confirm').removeClass('hide');
  $('#js-error').attr('aria-hidden',false);
  $('.js-delete-photo-confirm').attr('data-id',id);
  $('.js-delete-photo-confirm').attr('data-option-id',option_id);
  $('.js-delete-photo-confirm').attr('data-option',"option_id");
  $('.js-delete-photo-confirm').attr('data-photo',photo_id);
  $('.js-delete-photo-confirm').attr('del-parent',parent);
  $('.js-delete-photo-confirm').attr('data-index',id);
};


$scope.delete_option = function(item,index,option_id,delete_option,delete_message)
{
//$('#js-error .panel-header').text(delete_descrip);
  $('#js-error .modal-title').text(delete_option);
  $('#js-error .modal-body').text(delete_message);
  $('.js-delete-photo-confirm').removeClass('hide');
  $('#js-error').attr('aria-hidden',false);
    $('.js-delete-photo-confirm').attr('data-photo',option_id);
  $('.js-delete-photo-confirm').attr('data-option',"option");
    var target="#product_option_extra_"+index;

   $('.js-delete-close').attr('data-target',target);
    $('.js-delete-photo-confirm').attr('del-parent',index);
    var index=$scope.product_options.indexOf(item);
    $('.js-delete-photo-confirm').attr('data-index',index);
};

$(document).on('click', '.js-delete-photo-confirm', function(index)
{
  var index = $(this).attr('data-index');
  var option_del = $(this).attr('data-option');
  var option_index = $(this).attr('del-parent');
  var option_id = $(this).attr('data-option-id');
  if(option_del=="option")
  {
    var input_value=$scope.product_options[index].option_name;
    $('#input-tags')[0].selectize.removeOption(input_value);  
  }
  else
  {
    deactivate_submit();
    $('#add_product1').prop("disabled", true); 
    $('#add_product').prop("disabled", true);
    $http.post(APP_URL+'/product/delete_photo', { photo_id : $(this).attr('data-photo'),type:$("#update_type").val(),option:option_del,option_id:option_id,productid:$("#product_id").val() }).then(function(response) 
    {      
      activate_submit();
      $('#add_product1').prop("disabled", false); 
      $('#add_product').prop("disabled", false);
      if(response.data.success == 'true')
      {
        var del_img = $('#delete_product_id').val()+','+response.data.delete_img; 
        $('#delete_product_id').val(del_img);

        if(option_del!="false" && option_del != 'video')
        {
           $scope.photos_option_list[option_index].splice(index,1);
           $('#js-error').attr('aria-hidden',true);
        }
        else if(option_del == 'video')
        { $('#delete_video_update').val(1);
          $scope.video_src = '';
          $('#js-error').attr('aria-hidden',true);
        }
        else
        {
          $scope.photos_list.splice(index,1);
          $('#js-error').attr('aria-hidden',true);
          // photos_list();
          $scope.steps_count = response.data.steps_count;
          $('#add-product-imagevideo').reset();          

        }
       
    
        }else{
          if(response.data.redirect != '' && response.data.redirect != undefined){
            window.location = response.data.redirect;
          }
          else{            
             window.location = APP_URL+'/merchant/all_products';
          }
        }
      }, function(response)
      {
      if(response.status=='300')
       window.location = APP_URL+'/login';
      });
  }
});

$scope.$watch('photos_list', function (value) {

  if($scope.photos_list != undefined)
  {
     if($scope.photos_list.length != 0)
  {
    $('[data-track="photos"] a div div .transition').removeClass('visible');
      $('[data-track="photos"] a div div .transition').addClass('hide');
      $('[data-track="photos"] a div div div .icon-ok-alt').removeClass('hide');
  }
  else
  {
    $('[data-track="photos"] a div div .transition').removeClass('hide');
      $('[data-track="photos"] a div div div .icon-ok-alt').addClass('hide');
  }
  }
});
$scope.clickFunction = function(id) {
    var currentButton = angular.element(document.getElementById(id));
    $timeout(function () {
      currentButton.triggerHandler("click");
    });
}
$scope.checkedFunction = function(id) {
    var currentButton = angular.element(document.getElementById(id));
    $timeout(function () {
      currentButton.prop("checked",true);
    });
}
$scope.uncheckedFunction = function(id) {
    var currentButton = angular.element(document.getElementById(id));
    $timeout(function () {
      currentButton.prop("checked",false);
    });
}
$(document).on('change','#input_default_currency',function(){
  $('#add_product1').prop("disabled", true);
  $('#add_product').prop("disabled", true);
  deactivate_submit();
  currency_processing=1;
  var cc_code=$(this).find(':selected').attr('data-currency-code');
  var cc_symbol=$(this).find(':selected').attr('data-currency-symbol');
  var cc_torate=$(this).find(':selected').attr('data-to-rate');

  var rate=1.00;
  var usd_amount = 1 / parseFloat(rate);
  var minimum=Math.round(parseFloat(usd_amount) * parseFloat(cc_torate));
  $("#currency_code").val(cc_code);
  $('#minimum_amount').val(minimum);
  $('#currency_symbol').html(cc_symbol);
  $(".cc_symbol").attr("placeholder",$("#currency_symbol").html());
  $("#add_product_form").valid();
  $('#add_product1').prop("disabled", false);
  $('#add_product').prop("disabled", false);
  currency_processing=0;
  activate_submit();
});
//edit product
var product_id = $('#product_id').val();
if(document.location.href.indexOf('edit_product') > -1 ) {
  $scope.page_button=Lang.get('js_messages.merchant.update_product');
$scope.page_title= Lang.get('js_messages.merchant.edit_product');
$http.get(APP_URL+'/merchant/get_product/'+product_id).then(function(response) {
  var partt=response.data.category_path.split(',');
  for(var i=0;i<partt.length;i++)
  {
    $("#cat_"+partt[i]).trigger("click");
    if(partt.length==1)
    {
      $(".select-category.category_ids").removeClass("open");
    }
  }
  //Product Information
  $scope.title = response.data.title;

  //images section
  $scope.photos_list=response.data.product_photos;


  //Inventory details
  $scope.total_quantity = response.data.total_quantity;
  $scope.sold = response.data.sold;
  
$("#input_default_currency").val(response.data.products_prices_details.original_currency_code);
$("#currency_symbol").html(response.data.products_prices_details.original_currency_symbol);

  //Pricing & details
  $scope.price = response.data.products_prices_details.original_price;
  $scope.retail_price = response.data.products_prices_details.original_retail_price;
  $scope.discount = response.data.products_prices_details.discount;
  if ($scope.retail_price!="0" && $scope.retail_price!="" && $scope.retail_price!=null) {
    $scope.checkedFunction("check-sale");
    $('.retail').show();
    $('.discount').show();
  }
  $scope.length = response.data.products_prices_details.length;
  $scope.width = response.data.products_prices_details.width;
  $scope.weight = response.data.products_prices_details.weight;
  $scope.height = response.data.products_prices_details.height;
  $scope.sku = response.data.products_prices_details.sku;


  //shipping details

  if(response.data.products_shipping[0].shipping_type=="Free Shipping")
  {

    $scope.clickFunction("shipping-type_free");
  } 
  else{
    $scope.clickFunction("shipping-type_flat");
  }
  $timeout(function () {
    angular.forEach(response.data.product_option, function(value, key){
    var selectize_tags = $("#input-tags")[0].selectize
    selectize_tags.addOption({
        text:value.option_name,
        value: value.option_name
    });
    selectize_tags.addItem(value.option_name);
    });
    $scope.product_options=response.data.product_option;
    
    //extra popup option details
    angular.forEach(response.data.product_option, function(value, key){
      $scope.product_options[key].option_qty=value.total_quantity;
      $scope.product_options[key].option_price=value.original_price;
      $scope.product_options[key].option_discount=value.discount;
      $scope.product_options[key].retail_price=value.original_retail_price;
      if ($scope.product_options[key].retail_price!="0" && $scope.product_options[key].retail_price!="" && $scope.product_options[key].retail_price!=null) 
      {
        $timeout(function () {
          $(".check_sale_option_"+key).prop("checked",true);
          $(".check_sale_option_"+key).addClass("btn-add");
          $('.retail_'+key).show();
          $('.discount_'+key).show();
          if(value.sold_out=="Yes")
          {
            $("#marked_soldout_options_"+key).prop("checked",true);
            $("#marked_soldout_options_"+key).addClass("btn-add");
          }
        });

        
      }

      $timeout(function () {

          if(value.sold_out=="Yes")
          {
            $("#marked_soldout_options_"+key).prop("checked",true);
          }
        });
      $scope.photos_option_list[key]=value.product_option_images;
      
      
      
    });

  

  });
  


      
   

  $timeout(function () {

    angular.forEach(response.data.products_shipping, function(value, key){
      var selectize_tags = $("#ships_to")[0].selectize
      selectize_tags.addOption({
          text:value.ships_to,
          value: value.ships_to
      });
      selectize_tags.addItem(value.ships_to);
    });
    $scope.shipping= response.data.products_shipping;
  });

  
  $scope.manufacture_country = response.data.products_shipping[0].manufacture_country;
  $scope.ships_from = response.data.products_shipping[0].ships_from;

  //Return policy
  if($('#return_policy option[value='+response.data.return_policy+']').text() !='')
  {
    $scope.return_policy = response.data.return_policy;
  }

  $scope.policy_description = response.data.policy_description;
  if(response.data.return_policy!=response.data.exchange_policy)
  {

    $scope.uncheckedFunction("use_exchange");
    $('.use_exchange').show();
    if($('.exchange-policy option[value='+response.data.exchange_policy+']').text())
    {
      $scope.exchange_policy = response.data.exchange_policy;
    }
  }
  else
  {
    $('.use_exchange').hide();
    if($('.exchange-policy option[value='+response.data.return_policy+']').text() !='')
    {
      $scope.exchange_policy = response.data.return_policy;
    }    
  }

  //status
  if(response.data.status=="Inactive"){ $scope.clickFunction("product_status_btn");}
  if(response.data.sold_out=="Yes"){ $scope.clickFunction("soldout_status_btn");}
  if(response.data.cash_on_delivery=="Yes"){ $scope.clickFunction("cashdelivery_status_btn");}
  if(response.data.cash_on_store=="Yes"){ $scope.clickFunction("cashstore_status_btn");}

  // $scope.$apply();
  $timeout(function () {
    $('#product_status').val(response.data.status);
    $('#soldout_status').val(response.data.sold_out);
    $('#cashdelivery_status').val(response.data.cash_on_delivery);
    $('#cashdstore_status').val(response.data.cash_on_store);
    $(".cc_symbol").attr("placeholder",$("#currency_symbol").html());
    $scope.video_src = $sce.trustAsResourceUrl(response.data.video_src);
    $scope.canvas_image_src = response.data.video_thumb;
  });


});

}
else
{
  $scope.page_title= Lang.get('js_messages.merchant.add_new_product');
  $scope.page_button= Lang.get('js_messages.merchant.add_product');
}



}]);



//signup merchant signup

app.controller('merchant_signup', ['$scope', '$http', function($scope, $http) { 

  $scope.account_details = function(form) 
  {
        $scope.required_store     = '';
        $scope.required_fullname  = '';
        $scope.required_username  = '';
        $scope.exist_user         = '';
        $scope.required_email     = '';
        $scope.exist_mail         = '';
        $scope.invalid_email      = '';
        $scope.required_password  = '';
        $scope.required_terms     = '';
        $scope.min_len_password   = '';
        var check ='';
        if($scope.email!='')
        { 
            var email_id = $scope.email;

            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

            check = emailReg.test(email_id);
         }
         
      if(!$scope.store_name)
        $scope.required_store     = 1;
      if(!$scope.full_name)
        $scope.required_fullname  = 1;
      if(!$scope.user_name)
        $scope.required_username  = 1;   
      if(!$scope.email)
        $scope.required_email     = 1;
      if(check == false)
        $scope.invalid_email  = 1;
      if(!$scope.password)
        $scope.required_password  = 1;
      if((($scope.password).length) < 6)
        $scope.min_len_password   = 1;
      if(!$scope.terms)
        $scope.required_terms     = 1;
      else
      {
           var user_name = $scope.user_name;

            $scope.min_len_password   = '';
            $scope.required_store     = '';
            $scope.required_fullname  = '';
            $scope.required_username  = '';
            $scope.exist_user         = '';
            $scope.required_email     = '';
            $scope.exist_mail         = '';
            $scope.invalid_email      = '';
            $scope.required_password  = '';
            $scope.required_terms     = '';
            
            $http.post(APP_URL+'/user/check_users', { email_id:email_id, user_name:user_name}).then(function(response) 
                {
                  if(response.data == 1)
                    $scope.exist_mail = response.data;
                  else if (response.data == 2)
                    $scope.exist_user= response.data;
                  else
                  {
                    $('.step_2').removeClass('d-none');  
                    $('.step_1').addClass('d-none');
                    $('#content .order1').css('opacity' ,'0.5');
                    $('#content .order2').css('opacity' ,'1'); 

                  }

                });
           
      }
  };

   $scope.account_detail = function(form) 
  {
        $scope.required_store     = '';
        $scope.required_fullname  = '';
        $scope.required_terms     = '';
      if(!$scope.store_name)
        $scope.required_store     = 1;
      else if(!$scope.full_name)
        $scope.required_fullname = 1;
      else if(!$scope.terms)    
        $scope.required_terms = 1;
      else
      {
          $('.sign').removeClass('step1');  
          $('.sign').addClass('step2'); 
          $('.step_2').removeClass('d-none'); 
          $('.step_1').addClass('d-none'); 
      }
  };

  $('#account_details').click(function(event){
    event.preventDefault();
    $('.sign').removeClass('step2');  
    $('.sign').addClass('step1');  
  });
 
  $('#account_details_back').click(function(event){
    event.preventDefault();
    $('.step_2').addClass('d-none'); 
    $('.step_1').removeClass('d-none');  
    $('.sign').removeClass('step_2');  
    $('.sign').addClass('step_1'); 
    $('#content .order1').css('opacity' ,'1');
    $('#content .order2').css('opacity' ,'0.5'); 
  });

  $('.step_2 div').change(function(){   
    var address_line = $('input[name="address_line"]').val();
    var city         = $('input[name="city"]').val();
    var postal_code  = $('input[name="postal_code"]').val();
    var state        = $('input[name="state"]').val();    
    var country      = $('select[name="country"]').val();
    var phone_number = $('input[name="phone_number"]').val();

    if(address_line.trim() !='' && city.trim() !='' && postal_code.trim() !='' && state.trim() !='' && country.trim() !='' && phone_number.trim() !=''){
      $('#create_account').prop('disabled', false);
    }
    else
    {      
      $('#create_account').prop('disabled', true);
    }
  });

}]);

app.controller('payout_preferences', ['$scope', '$http', function($scope, $http) { 
  $(document).ready(function() {
    $("#payout_preferences_form").validate({
        rules: {
            "paypal_email": {
                required: true,
                email_valid: true
            },
            "address1": {
                required: true,
            },
            "country_code": {
              required: true,
            },
            "city": {
                required: true,
               
               
            },
            "state": {
                required: true,
                
            },
            "postal_code": {
              required: true,
            },            
        },
        messages: {
            "paypal_email": {
                required: Lang.get('js_messages.payout_preferences.paypal_email'),
                email_valid: Lang.get('js_messages.payout_preferences.paypal_email_valid')
            },
            "address1": {
                required: Lang.get('js_messages.payout_preferences.paypal_address'),
            },
            "country_code": {
              required: Lang.get('js_messages.payout_preferences.country_code'),
            },
            "city": {
                required: Lang.get('js_messages.payout_preferences.paypal_city'),
                alphanumeric: Lang.get('js_messages.payout_preferences.paypal_city_alphanumeric'),
                notNumber: Lang.get('js_messages.payout_preferences.paypal_city_alphanumeric'),
            },
            "state": {
                required: Lang.get('js_messages.payout_preferences.payout_state'),
                alphanumeric: Lang.get('js_messages.payout_preferences.paypal_city_alphanumeric'),
                notNumber: Lang.get('js_messages.payout_preferences.paypal_city_alphanumeric'),
            },
            "postal_code": {
              required: Lang.get('js_messages.payout_preferences.payout_zip'),
            }
        },
        
      errorElement: "span",
      errorClass: "text-danger",
      errorPlacement: function( label, element ) {
        if(element.attr( "name" ) === "first_name" || element.attr( "name" ) === "last_name" || element.attr( "name" ) === "company_name" || element.attr( "name" ) === "email" || element.attr( "name" ) === "mobile_phone_number" || element.attr( "name" ) === "address" || element.attr( "name" ) === "post_code" || element.attr( "name" ) === "country" ) {
          element.parent().append( label ); 
        } else {
        label.insertAfter( element ); 
      }
    }
    });
    jQuery.validator.addMethod("email_valid", function( value, element ) {   
    return this.optional(element) || /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(value); 
  }, "");
    jQuery.validator.addMethod("notNumber", function(value, element, param) {
      var reg = /^[a-zA-Z\s]+$/;
      if(reg.test(value)){
        return true;
      }else{
        return false;
      }
    }, "Only alphabatic characters allowed.");
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
     return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    }, "Only alphabatic characters allowed.");

    $('.btn-payment').click(function(e){
              e.preventDefault();
                 $("body").addClass("pos-fix");
                $(".payment-popup").show();
                });
    $('.add_payout_form').click(function() {
      if($('#payout_preferences_form').valid()){
        $("body").addClass("pos-fix");  

        $('#payout_info_payout2_address1').val($("#address_1").val());
        $('#payout_info_payout2_address2').val($("#address_2").val());
        $('#payout_info_payout2_city').val($('#payout_city').val());
        $('#payout_info_payout2_state').val($('#payout_state').val());
        $('#payout_info_payout2_zip').val($('#payout_zip').val());
        $('#payout_info_payout2_country').val($('#select_country').val());

        $(".payment-popup2").show();
        $(".payment-popup").hide();
      }
      else{
        return false;        
      }
    });
   
    $('.add_payout_form2').click(function() {
      var validation_container = '<div class="alert alert-danger alert-header"><a class="close alert-close" href="javascript:void(0);"></a><i class="icon alert-icon icon-alert-alt"></i>';
        if ($('[id="payout2_method"]:checked').val() == undefined) {
            $('#popup2_flash-container').html(validation_container+$('#choose_method').val()+'</div>');
            $('#popup2_flash-container').show();
            return false;
        }
      
              $('#payout_info_payout3_address1').val($('#payout_info_payout2_address1').val());
              $('#payout_info_payout3_address2').val($('#payout_info_payout2_address2').val());
              $('#payout_info_payout3_city').val($('#payout_info_payout2_city').val());
              $('#payout_info_payout3_state').val($('#payout_info_payout2_state').val());
              $('#payout_info_payout3_zip').val($('#payout_info_payout2_zip').val());
              $('#payout_info_payout3_country').val($('#payout_info_payout2_country').val());
              $('#payout3_method').val($('[id="payout2_method"]:checked').val());
            payout_method = $('[id="payout2_method"]:checked').val();
            if(payout_method == 'Stripe')
            {
              $("body").addClass("pos-fix"); 
               $(".payment-popup4").show();
               $(".payment-popup2").hide();
               //$("#payout_paypal").submit();
                $('#popup2_flash-container').hide();
                return true;
            }
            else{
              $("body").addClass("pos-fix");        
              $(".payment-popup3").show();
              $(".payment-popup2").hide();
              $('#popup2_flash-container').hide();
              return true;
            }
        
            
      
    });

    //payout country depend currency 

     $('#select_country').change(function() {
      //console.log($(this).val());
        $scope.country = $(this).val();
        $('#payout_info_payout_country1').val($(this).val());

        if($('#payout_info_payout_country1').val() == '' || $('#payout_info_payout_country1').val() == undefined)
        {            
            $("#payout_info_payout_country1").val('');
            $scope.payout_country = '';
            $scope.payout_currency = '';
        }
        else
        {
            $scope.payout_country = $(this).val();
            $('#payout_info_payout_country1').trigger("change");
            $scope.change_currency();
        }

        
    });

      // change currency based on country selected
    $scope.change_currency = function()
    {        

        var selected_country = [];
        angular.forEach($scope.country_currency, function(value, key) {          
                  if($('#payout_info_payout_country1').val() == key)
                     selected_country = value;
                });
        
                if(selected_country)
                {
                    var $el = $("#payout_info_payout_currency");
                    $el.empty(); // remove old options
                    $.each(selected_country, function(key,value) {
                      $el.append($("<option></option>")
                         .attr("value", value).text(value));
                        if($scope.old_currency != '')
                        {
                            
                            $('#payout_info_payout_currency').val($scope.payout_currency);
                        }
                        else
                        {
                            
                            $('#payout_info_payout_currency').val(selected_country[0]);
                        }


                    });
                    
                    if($('#payout_info_payout_country1').val() == 'GB' && $('#payout_info_payout_currency').val() == 'EUR')
                    {
                       $('.routing_number_cls').addClass('hide');
                       $('.account_number_cls').html('IBAN');
                    
                    }
                    else
                    {
                        $('.routing_number_cls').removeClass('hide');
                        $('.account_number_cls').html('Account Number');
                    }
                }
                else
                {
                    var $el = $("#payout_info_payout_currency");
                    $el.empty(); // remove old options                   
                      $el.append($("<option></option>")
                         .attr("value", '').text('Select'));
                    
                }
                
                        if($('#payout_info_payout_currency').val() == '' || $('#payout_info_payout_currency').val() == null)
                        {
                            
                            $("#payout_info_payout_currency").val($("#payout_info_payout_currency option:first").val());
                        }
               
    }


    $(document).on('change', '#payout_info_payout_country1', function() {

        $scope.change_currency();
        
        if($('#payout_info_payout_country1').val() == 'GB' && $('#payout_info_payout_currency').val() == 'EUR')
        {
           $('.routing_number_cls').addClass('hide');
           $('.account_number_cls').html('IBAN');
        
        }
        else
        {
            $('.routing_number_cls').removeClass('hide');
            $('.account_number_cls').html('Account Number');
        }
        $scope.payout_currency = $('#payout_info_payout_currency').val();
        $("#payout_info_payout_currency").val($("#payout_info_payout_currency option:first").val());
        $('#payout_info_payout_country').val($('#payout_info_payout_country1').val());
        
    });

     // set publishable key for stripe validation on js //
    var stripe_publish_key = document.getElementById("stripe_publish_key").value;
    var stripe = Stripe.setPublishableKey(stripe_publish_key);

    $('#payout_stripe').submit(function() {
      

        $('#payout_info_payout4_address1').val($('#address_1').val());
        $('#payout_info_payout4_address2').val($('#address_2').val());
        $('#payout_info_payout4_city').val($('#payout_city').val());
        $('#payout_info_payout4_state').val($('#payout_state').val());
        $('#payout_info_payout4_zip').val($('#payout_zip').val());        

        // check stripe token already exist
        stripe_token = $("#stripe_token").val();
        if(stripe_token != ''){
            return true;
        }

        // required field validation --start-- //
        if($('#payout_info_payout_country1').val() == '')
        {
            $("#stripe_errors").html('Please fill all required fields');               
            return false;
        }
        if($('#payout_info_payout_currency').val() == '')
        {
            $("#stripe_errors").html('Please fill all required fields');               
            return false;
        }
        if($('#holder_name').val() == '')
        {
            $("#stripe_errors").html('Please fill all required fields');               
            return false;
        }

        if($('#account_number').val() == '')
        {
            $("#stripe_errors").html('Please fill all required fields');               
            return false;
        }
        
        is_iban = $('#is_iban').val();
        is_branch_code = $('#is_branch_code').val();

        // bind bank account params to get stripe token
        var bankAccountParams = {
              country: $('#payout_info_payout_country1').val(),
              currency: $('#payout_info_payout_currency').val(),              
              account_number: $('#account_number').val(),
              account_holder_name: $('#holder_name').val(),
              account_holder_type: $('#holder_type').val()
          };

          // check whether iban supported country or not for bind routing number
          if(is_iban == 'No')
          {            
            if(is_branch_code == 'Yes')
            {
              // here routing number is combination of routing number and branch code
              if($('#payout_info_payout_country1').val() != 'GB' && $('#payout_info_payout_currency').val() != 'EUR')
              {
                if($('#routing_number').val() == '')
                {
                  $("#stripe_errors").html('Please fill all required fields');               
                  return false;
                }
                if($('#branch_code').val() == '')
                {
                  $("#stripe_errors").html('Please fill all required fields');                
                  return false;
                }

                bankAccountParams.routing_number = $('#routing_number').val()+'-'+$('#branch_code').val();
              }
            }
            else
            {
              
              if($('#payout_info_payout_country1').val() != 'GB' && $('#payout_info_payout_currency').val() != 'EUR')
              {
                if($('#routing_number').val() == '')
                {
                  $("#stripe_errors").html('Please fill all required fields');                
                  return false;
                }
                bankAccountParams.routing_number = $('#routing_number').val();
              }
            }
          }

          // required field validation --end-- //
          $('#payout_stripe').addClass('loading');
          country = $scope.payout_country;
          Stripe.bankAccount.createToken(bankAccountParams, stripeResponseHandler);


        return false;
    });

    // response handler function from for create stripe token
    function stripeResponseHandler(status, response) {

      //console.log(response);
        
     $('#payout_stripe').removeClass('loading');
     
      if (response.error) {       
          $("#stripe_errors").html("");
          if(response.error.message == "Must have at least one letter"){
            $("#stripe_errors").html('Please fill all required fields');
          }else{
            $("#stripe_errors").html(response.error.message); 
          }
          return false;
      } else {
          $("#stripe_errors").html("");
          var token = response['id'];
          $("#stripe_token").val(token); 
          $('#payout_stripe').removeClass('loading');
          $("#payout_stripe").submit();
          return true;
      }
    }

    $('.add_payout_form3').click(function(){
        payout_method = $("#payout3_method").val();
        if(payout_method != 'PayPal')
        {
            return true;
        }
        var validation_container = '<div class="alert alert-danger alert-header"><a class="close alert-close" href="javascript:void(0);"></a><i class="icon alert-icon icon-alert-alt"></i>';
         
        if ($('#paypal_email_id').val() =='') {          
            $('#popup3_flash-container').html(validation_container+Lang.get('js_messages.payout_preferences.paypal_email')+'</div>');
            $('#popup3_flash-container').show();
            return false;
        }

        // var validation_container = '<div class="alert alert-error alert-error alert-header"><a class="close alert-close" href="javascript:void(0);"></a><i class="icon alert-icon icon-alert-alt"></i>';
        var emailChar = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (emailChar.test($('#paypal_email_id').val())) {
          $(".payment-popup3").hide();
          $("body").removeClass("pos-fix");           
          $('#popup3_flash-container').hide();
          $("#payout_paypal").submit();
            return true;
        } else {
            $('#popup3_flash-container').html(validation_container+Lang.get('js_messages.payout_preferences.paypal_email_valid')+'</div>');
            $('#popup3_flash-container').show();
            return false;
        }
      });
    
    $('.panel-close').click(function() {
        $(this).parent().parent().parent().parent().parent().addClass('hide');
    });

    $('[id$="_flash-container"]').on('click', '.alert-close', function() {
        $(this).parent().parent().html('');
    });

     
});
  $scope.error_fields = [];
  $scope.payout_details = [];
  function validateEmail(input) {
    var x = input;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        return false;
    }
    else
    {
      return true;
    }
  }

  $scope.default_pay=function(id){

    $http.post('default_pay', {id:id}).then(function(response) 
    {
      $scope.get_payout();
    }, function(response)
    {
      if(response.status=='300')
        window.location = APP_URL+'/login';
    });
  }
  $scope.remove_pay=function(id){
     check_default = $('.payout_'+id+' .default_pay').length;
    $('.error_'+id).hide();
    $('.error').hide();
    if(check_default == 0){
      $('.error_'+id).hide();
      $http.post('remove_pay', {id:id}).then(function(response) 
      {      
         $scope.get_payout();
      }, function(response)
      {

        if(response.status=='300')
          window.location = APP_URL+'/login';
      });
    }
    else{
      $('.error_'+id).show();      
    }
  }
  $scope.get_payout=function(){
    $scope.payout_details=[];
    $(".no-data").hide();
    $("#payout_preferences_loading").show();
    $http.post('get_payout_preferences', {}).then(function(response) 
    {
      $("#payout_preferences_loading").hide();
      if(response.data.length)
      {
        $(".no-data").hide();
        $scope.payout_details=response.data;
      }
      else
      {
        $(".no-data").show();
      }
      
    }, function(response)
    {
      if(response.status=='300')
        window.location = APP_URL+'/login';
    });
  }
  
  $scope.add_payout = function() {
    var paypal_email=$("#paypal_email_id").val();
    var address_1=$("#address_1").val();
    var address_2=$("#address_2").val();
    var country_code=$("#select_country").val();
    var payout_city=$("#payout_city").val();
    var payout_state=$("#payout_state").val();
    var payout_zip=$("#payout_zip").val();
    var payout_method = $('[id="payout2_method"]:checked').val();
     if(document.getElementById('make_this_primary_addr').checked) {
        var default_account="yes";
    } else {
        var default_account="no";
    }
      $(".payment-popup").hide();
      $(".payout_error").hide();
      $http.post('add_payout_preferences', { email:paypal_email, address_1:address_1, address_2:address_2, country_code:country_code,
      payout_city:payout_city, payout_state:payout_state, payout_zip:payout_zip,default_account:default_account,payout_method:payout_method}).then(function(response) 
      {
        $scope.get_payout();
        $(".for_clear").val("");
      }, function(response)
      {

      });
  }
  $scope.get_payout();

}]);


app.controller('checkout', ['$scope', '$http','$timeout', function($scope, $http, $timeout) 
{

$(document).ready(function() {
    $("#checkout_payment").validate({
        ignore: ":hidden",
        rules: {
            "shipping_name": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "shipping_name_add": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "billing_name": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "billing_name_add": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "shipping_address_1": {
                required: true,
            },
            "shipping_address_1_add": {
                required: true,
            },
            "billing_address_1": {
                required: true,
            },
            "billing_address_1_add": {
                required: true,
            },
            "shipping_city": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "shipping_city_add": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "billing_city": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "billing_city_add": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "shipping_state": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "shipping_state_add": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "billing_state": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "billing_state_add": {
                required: true,
                alphanumeric:true,
                notNumber: true
            },
            "shipping_phone": {
              required: true,
              number: true,
              minlength: 6
            },
            "shipping_phone_add": {
              required: true,
              number: true,
              minlength: 6
            },
            "billing_phone": {
              required: true,
              number: true,
              minlength: 6
            },
            "billing_phone_add": {
              required: true,
              number: true,
              minlength: 6
            },
            "shipping_zip": {
              required: true,
              maxlength: 10
            },
            "shipping_zip_add": {
              required: true,
              maxlength: 10
            },
            "billing_zip": {
              required: true,
              maxlength: 10
            },
            "billing_zip_add": {
              required: true,
              maxlength: 10
            },
            "card_name" : {
              required :true,
            },
            "cc_number" :{
              required: true,
            },
            "cc_expire_month" :{
              required: true,
              // min: function() {
              //   return parseInt($('#current_month').val());
              // }
            },
            "cc_expire_year" :{
              required: true,
            },
            "cvv" :{
              required: true,
              number: true,
              maxlength:5
            }
        },
        messages: {
            "shipping_name": {
                required: Lang.get('js_messages.checkout_payment.name'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "shipping_name_add": {
                required: Lang.get('js_messages.checkout_payment.name'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "billing_name": {
                required: Lang.get('js_messages.checkout_payment.name'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "billing_name_add": {
                required: Lang.get('js_messages.checkout_payment.name'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "shipping_address_1": {
                required: Lang.get('js_messages.checkout_payment.address'),
            },
            "shipping_address_1_add": {
                required: Lang.get('js_messages.checkout_payment.address'),
            },
            "billing_address_1": {
                required: Lang.get('js_messages.checkout_payment.address'),
            },
            "billing_address_1_add": {
                required: Lang.get('js_messages.checkout_payment.address'),
            },
            "shipping_city": {
                required: Lang.get('js_messages.checkout_payment.city'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "shipping_city_add": {
                required: Lang.get('js_messages.checkout_payment.city'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "billing_city": {
                required: Lang.get('js_messages.checkout_payment.city'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "billing_city_add": {
                required: Lang.get('js_messages.checkout_payment.city'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "shipping_state": {
                required: Lang.get('js_messages.checkout_payment.state'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber:Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "shipping_state_add": {
                required: Lang.get('js_messages.checkout_payment.state'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber:Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "billing_state": {
                required: Lang.get('js_messages.checkout_payment.state'),
                alphanumeric: Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber:Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "billing_state_add": {
                required: Lang.get('js_messages.checkout_payment.state'),
                alphanumeric:Lang.get('js_messages.checkout_payment.alphanumeric'),
                notNumber: Lang.get('js_messages.checkout_payment.alphanumeric'),
            },
            "shipping_phone": {
              required: Lang.get('js_messages.checkout_payment.phone'),
            },
            "shipping_phone_add": {
              required: Lang.get('js_messages.checkout_payment.phone'),
            },
            "billing_phone": {
              required: Lang.get('js_messages.checkout_payment.phone'),
            },
            "billing_phone_add": {
              required: Lang.get('js_messages.checkout_payment.phone'),
            },
            "shipping_zip": {
              required: Lang.get('js_messages.checkout_payment.zip'),
            },
            "shipping_zip_add": {
              required: Lang.get('js_messages.checkout_payment.zip'),
            },
            "billing_zip": {
              required: Lang.get('js_messages.checkout_payment.zip'),
            },
            "billing_zip_add": {
              required: Lang.get('js_messages.checkout_payment.zip'),
            },
            "card_name": {
              required: Lang.get('js_messages.checkout_payment.card_name'),
            },
            "cc_number": {
              required: Lang.get('js_messages.checkout_payment.card_number'),
            },
            "cc_expire_month" :{              
              required: Lang.get('js_messages.checkout_payment.cc_expire_month'),
              min: Lang.get('js_messages.checkout_payment.cc_expire_month'),
            },
            "cc_expire_year" :{
              required: Lang.get('js_messages.checkout_payment.cc_expire_year'),
            },
            "cvv" :{
              required: Lang.get('js_messages.checkout_payment.cvv'),
              number: Lang.get('js_messages.checkout_payment.cvv'),
              maxlength: Lang.get('js_messages.checkout_payment.cvv_limit'),
            }
        },
        
      errorElement: "span",
      errorClass: "text-danger",
      errorPlacement: function( label, element ) {
        if(element.attr( "name" ) === "first_name" || element.attr( "name" ) === "last_name" || element.attr( "name" ) === "company_name" || element.attr( "name" ) === "email" || element.attr( "name" ) === "mobile_phone_number" || element.attr( "name" ) === "address" || element.attr( "name" ) === "post_code" || element.attr( "name" ) === "country" ) {
          element.parent().append( label ); 
        } else {
        label.insertAfter( element ); 
      }
    }
    });
    jQuery.validator.addMethod("notNumber", function(value, element, param) {
      var reg = /^[a-zA-Z\s]+$/;
      if(reg.test(value)){
        return true;
      }else{
        return false;
      }
    }, Lang.get('js_messages.checkout_payment.alphanumeric'));
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
     return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    }, "Only alphabatic characters allowed.");

    $('.open-coupon-section-link').click(function() {        
      $('#after_apply').show();
      $('.coupon-code-field').val('');
      $(".btn-checkout").attr("disabled",true);
      $('#restric_apply').hide();
  });

  $('.cancel-coupon').click(function() {      
      $('#restric_apply').show();
      $('#after_apply').hide();
      $('#coupon_loading').hide();
      $('#coupon_disabled_message').hide();
      $(".btn-checkout").attr("disabled",false);
  });

  $('#apply-coupon').click(function() {
        var coupon_code = $('.coupon-code-field').val();

        var payment_method = $('#payment_method').val();

        $('#coupon_loading').show();

        $(".btn-checkout").attr("disabled",true);

        $("#coupon_disabled_message").hide();

        $http.post(APP_URL + '/apply_coupon', {
            coupon_code: coupon_code,payment_method :payment_method
        }).then(function(response) {
          $('#coupon_loading').hide();
            if (response.data.message) {
                $("#coupon_disabled_message").show();
                $('#coupon_disabled_message').text(response.data.message);
                $("#after_apply_remove").hide();
                $("#remove_coupon").hide();
                $(".btn-checkout").attr("disabled",true);
            } else if(response.data.coupon_amount) {
                $("#coupon_disabled_message").hide();
                $("#restric_apply").hide();
                $("#after_apply").hide();
                $("#after_apply_remove").show();
                $("#after_apply_coupon").show();
                $("#after_apply_amount").show();
                $("#remove_coupon").show();
                currency_symbol = $('#currencysymbol').text();
                $('#applied_coupen_amount').text('-'+currency_symbol+''+response.data.coupon_amount);
                $('.order_price_total').html(currency_symbol+''+response.data.coupen_applied_total);
                $(".btn-checkout").attr("disabled",false);                
            }
            else{
              window.location.reload();
            }
        });
    });

    $(document).on('click','#remove_coupon',function() {    

        $('#remove_coupon_loading').show();

        $(".btn-checkout").attr("disabled",true);

        var payment_method = $('#payment_method').val();

        $http.post(APP_URL + '/remove_coupon', {payment_method :payment_method}).then(function(response) {

            $('#remove_coupon_loading').hide();
            $("#restric_apply").show();
            $("#after_apply_remove").hide();
            $("#after_apply_coupon").hide();
            $("#after_apply_amount").hide();
            $("#remove_coupon").hide();
            $(".btn-checkout").attr("disabled",false);  
            $(".order_price_total").html(response.data.currency_symbol+' '+response.data.total);  

        });
    });

    $('.add_shipping_address').click(function() {      
      var validator = $("#checkout_payment").validate()
      if($('#checkout_payment').valid()){
        $(this).attr('disabled','disabled');
        $scope.add_shipping_address();
      }
      else{

        return false;        
      }
    });
    $('.edit_shipping_address').click(function() {
      if($('#checkout_payment').valid())
        $scope.edit_shipping_address();
      else
        return false;        
    });
    $('.add_billing_address').click(function()
    {
      if($('#checkout_payment').valid()){
        $(this).attr('disabled','disabled');        
        $scope.add_billing_address();
      }
      else
        return false;  
    });
    $('.edit_billing_address').click(function()
    {
      if($('#checkout_payment').valid())
        $scope.edit_billing_address();
      else
        return false;  
    }) 
    $('.add_payment_method').click(function()
    {
      if($('#checkout_payment').valid())
        $scope.add_payment_method();
      else
        return false;  
    })

    $('#cc_number').keyup(function()
      {
          $(this).val(function(i, v)
          {
              var v = v.replace(/[^\d]/g, '').match(/.{1,4}/g);
              return v ? v.join(' ') : '';
          });
      });
});

  $(document).ready(function(){

    $(".change_function").click(function(){
      $(".btn-checkout").attr("disabled",true);
    })
    
  })

  $scope.trigger_login=function(){
    $timeout(function () {
      $(".login_popup_head").triggerHandler("click");
    });
  }

  $scope.shipping_details=[];
  $scope.billing_details=[];
  $scope.review_orders=[];  

  $scope.paypal_payment=function(){  
    $http.post('checkout_payment', { }).then(function(response) 
    {
    })
  }

  $scope.get_price_details=function() {
    $http.post('get_price_details', { time:$.now() ,payment_method:$("#payment_method").val()}).then(function(response) 
    {
      document.getElementById("order_shipping").style.cssText = "display:none !important";
      if(response.data.shipping_charge >= '0.00') {
        $("#order_price_shipping").html(response.data.currency_symbol+' '+response.data.shipping_charge);
        $('#order_shipping').show();
      }

      document.getElementById("order_incremental").style.cssText = "display:none !important";
      if(response.data.incremental_fee != '0.00') {
        $("#order_price_incremental").html(response.data.currency_symbol+' '+response.data.incremental_fee);
        $('#order_incremental').show();
      }

      document.getElementById("order_service").style.cssText = "display:none !important";
      if(response.data.service != '0.00') {
        $("#order_price_tax").html(response.data.currency_symbol+' '+response.data.service);
        $("#order_service").show();
      }

      if(response.data.coupon_amount !='0') {
          $("#coupon_disabled_message").hide();
          $("#restric_apply").hide();
          $("#after_apply").hide();
          $("#after_apply_remove").show();
          $("#after_apply_coupon").show();
          $("#after_apply_amount").show();
          $("#remove_coupon").show();          
          $('#applied_coupen_amount').html(' - '+response.data.currency_symbol+' '+response.data.coupon_amount);          
      }
      else {
          $("#restric_apply").show();
      }

      $('#currencysymbol').html(response.data.currency_symbol);
      $("#order_price_sub").html(response.data.currency_symbol+' '+response.data.subtotal);
      $(".order_price_total").html(response.data.currency_symbol+' '+response.data.total);      
    });    
  }

  $scope.bill_edit=function(){
    $(".add_billing").attr("disabled",false);
    $(".payment-popup-bill").show();
    $http.post('get_billing_details', { }).then(function(response) 
    {
      $("#edit_bill").val("yes");
      $("#billing_name").val(response.data[0].name);
      $("#billing_address_1").val(response.data[0].address_line);
      $("#billing_address_2").val(response.data[0].address_line2);
      $("#billing_country").val(response.data[0].country);
      $("#billing_city").val(response.data[0].city);
      $("#billing_state").val(response.data[0].state);
      $("#billing_zip").val(response.data[0].postal_code);
      $("#billing_phone").val(response.data[0].phone_number);
      $("#billing_address_nick").val(response.data[0].address_nick);
    });    
  }

  $scope.payment_edit=function(){
    $("body").addClass("pos-fix");
    $('.payment-methodpop').show();
    $http.post('get_payment_method', { }).then(function(response) 
    {      

       $('#card_name').val(response.data.credit_card_details.card_name);
       $('#cc_number').val(response.data.credit_card_details.card_number);
       $('#cc_expire_month').val(response.data.credit_card_details.cc_expire_month);
       $('#cc_expire_year').val(response.data.credit_card_details.cc_expire_year);
       $('#cvv').val(response.data.credit_card_details.cvv);      
    });  
  }
  $scope.view_payment=function(){

      $(".add_shipping").attr("disabled",false);
      $("body").addClass("pos-fix");
      $('.payment-methodpop').show();
     $('#card_name').val();
     $('#cc_number').val();     
     $('.cc_expire_month').removeClass('text-danger');
     $('span.text-danger').html('');
  }

  $scope.ship_edit=function(){
    $(".add_shipping").attr("disabled",false);
    $(".payment-popup1").show();
    $http.post('get_shipping_details', { }).then(function(response) 
    {
      $("#edit").val("yes");
      $("#shipping_name").val(response.data[0].name);
      $("#shipping_address_1").val(response.data[0].address_line);
      $("#shipping_address_2").val(response.data[0].address_line2);
      $("#shipping_country").val(response.data[0].country);
      $("#shipping_city").val(response.data[0].city);
      $("#shipping_state").val(response.data[0].state);
      $("#shipping_zip").val(response.data[0].postal_code);
      $("#shipping_phone").val(response.data[0].phone_number);
      $("#shipping_address_nick").val(response.data[0].address_nick);
    });    
  }

  $scope.view_bill=function(){
    $(".payment-popup-bill").show();
  }

  $scope.get_billing_details=function(){

    $scope.billing_details=[];
    payment_type = $('#payment_method').val();
    $("#billing_loading").show();
    $http.post('get_billing_details', {  time:$.now()}).then(function(response) 
    {      
      $("#billing_loading").hide();
      if(response.data.length)
      {        
        $scope.billing_details=response.data;
        
        if(payment_type=='cc'){
          $scope.get_payment_details();
        }
        else{

          $timeout(function () {
            $('.btn-use-payment').trigger('click');
          });

          $scope.bill_next();
        }

        $("#empty_billing").hide();
        $(".new_bill").hide();
        $('.new_bill_address').show();

      }
      else
      {
        $("#empty_billing").show();
        $(".new_bill").show();
        $('.new_bill_address').hide();

      }
    });
  }

   $scope.get_payment_details = function(){
    $('.ship-add').hide();
    $('.payment-add').hide();
    $('.payment-add1').show();  
    $('#payment_loading').show();
    $http.post('get_payment_method',{}).then(function(response){
        $('#payment_loading').hide();
        if(response.data.credit_card_details.length !=0 && response.data.card_detail_count ==0)
        { 
          $scope.get_review_orders();
          $scope.payment= response.data;
          $timeout(function () {
            $('.ship-add').hide();
            $('.payment-add').hide();
            $('.payment-add1').css('display','none !important');
            $('.payment_method_details').show();
            $('.review-add').show();             
          });                  
        }

        if(response.data.credit_card_details.length !=0 && response.data.card_detail_count !=0)
        { 
           $scope.payment= response.data;

            $('.payment-add1').show();

           $('.edit_payment_details').show();
        }

        if(response.data.card_detail_count !=0 )
        { 
           $scope.payment= response.data;

            $('.payment_method_details').show();

           $('.edit_payment_details').show();

        }   

    });
  }

  $scope.get_review_orders=function(){
    $scope.review_orders=[];
    $("#review_loading").show();
    $(".btn-checkout").attr("disabled",true);
    $http.post('get_review_orders', { time:$.now() ,payment_method:$("#payment_method").val()}).then(function(response) 
    {
      if(response.data.length)
      {
        $("#review_loading").hide();
        $scope.review_orders=response.data;
        $timeout(function () {
          $(".btn-checkout").attr("disabled",false);
        });        
      }
      else
      {
        $(".btn-checkout").attr("disabled",true);
      }
    });
  }


  $scope.ship_next=function(){
    $scope.get_billing_details();
  }
  $scope.payment_next = function(){
    $('.ship-add').hide();
    $('.payment-add').hide();
    $('.payment-add1').hide();
    $('.review-add').show();
    $scope.get_review_orders(); 
   //$('.edit_payment_details').show();
       $('#customer_id').val($('.payment_address').val());
       $scope.customerid = $('.payment_address').val();
    
   
  }
  $scope.bill_next=function(){ 
  payment_method = $('#close_payment').val();
    if(payment_method == 'cc'){
       $scope.get_payment_details();
     }
     else{
      $scope.get_review_orders(); 
     }
  } 

  $scope.get_shipping_details=function(){
    $scope.shipping_details=[];
    $("#shipping_loading").show();
    $http.post('get_shipping_details', {  time:$.now()}).then(function(response) 
    {
      $("#shipping_loading").hide();
      if(response.data.length)
      {

        $scope.ship_next();
        $timeout(function () {
          $('.btn-ship-payment').trigger('click');
        });
         
        $scope.shipping_details=response.data;
        $("input:radio[name=shipping_address]").prop('checked',true);

        $(".btn-ship-payment").attr("disabled",false);
        $('.new_ship_address').show();

        $("#empty_shipping").hide();
        $(".new_ship").hide();

      }
      else
      {
        $('.new_ship_address').hide();
        $(".btn-ship-payment").attr("disabled",true);
        $("#empty_shipping").show();
        $(".new_ship").show();
      }
    });
  }
  var payment_method=$("#payment_method").val();
  if(payment_method=="paypal" || payment_method=="cod" || payment_method=="cc")
  {
    $scope.get_shipping_details();  
  }
  else
  {
    $(".ship-add").hide();
    $(".payment-add").show();
    $(".cos_shipping_div").hide();
    $(".for_bill_shipping").removeClass("margin-top-15");
    $scope.ship_next();
  }
  
  $scope.get_price_details();
  
  
  

  $scope.add_shipping_address=function(){

    var shipping_name=$("#shipping_name_add").val();
    var shipping_address_1=$("#shipping_address_1_add").val();
    var shipping_address_2=$("#shipping_address_2_add").val();
    var shipping_country=$("#shipping_country_add").val();
    var shipping_city=$("#shipping_city_add").val();
    var shipping_state=$("#shipping_state_add").val();
    var shipping_zip=$("#shipping_zip_add").val();
    var shipping_phone=$("#shipping_phone_add").val();
    var shipping_address_nick=$("#shipping_address_nick_add").val();
    var is_default="yes";

      $(".add_shipping").attr("disabled",true);
      var shipping_name
      var shipping_address_1
      var shipping_address_2
      var shipping_country
      var shipping_city
      var shipping_state
      var shipping_zip
      var shipping_phone
      var shipping_address_nick
      var is_default="yes";
      var edit=$("#edit").val();

      $http.post('add_shipping_details', { edit:edit,name:shipping_name, address_line:shipping_address_1, 
        address_line2:shipping_address_2, country:shipping_country, city:shipping_city, 
        state:shipping_state, postal_code:shipping_zip,phone_number:shipping_phone,
        address_nick:shipping_address_nick,is_default:is_default
      }).then(function(response) 
      {
        $scope.get_price_details();
        $scope.get_shipping_details();


        $(".payment-popup1").hide();
        $("body").removeClass("pos-fix");
        $(".new_ship").hide();
      });

    // }


  }
    $scope.edit_shipping_address=function(){

    var shipping_name=$("#shipping_name").val();
    var shipping_address_1=$("#shipping_address_1").val();
    var shipping_address_2=$("#shipping_address_2").val();
    var shipping_country=$("#shipping_country").val();
    var shipping_city=$("#shipping_city").val();
    var shipping_state=$("#shipping_state").val();
    var shipping_zip=$("#shipping_zip").val();
    var shipping_phone=$("#shipping_phone").val();
    var shipping_address_nick=$("#shipping_address_nick").val();
    var is_default="yes";

      $(".add_shipping").attr("disabled",true);
      var shipping_name
      var shipping_address_1
      var shipping_address_2
      var shipping_country
      var shipping_city
      var shipping_state
      var shipping_zip
      var shipping_phone
      var shipping_address_nick
      var is_default="yes";
      var edit='yes';
      $http.post('add_shipping_details', { edit:edit,name:shipping_name, address_line:shipping_address_1, 
        address_line2:shipping_address_2, country:shipping_country, city:shipping_city, 
        state:shipping_state, postal_code:shipping_zip,phone_number:shipping_phone,
        address_nick:shipping_address_nick,is_default:is_default
      }).then(function(response) 
      {
        $scope.get_price_details();
        $scope.get_shipping_details();
        $http.post('get_shipping_details', {  time:$.now()}).then(function(response) 
    {
      $("#shipping_loading").hide();
      if(response.data.length)
      {
        
        $scope.shipping_details=response.data;
        $("input:radio[name=shipping_address]").prop('checked',true);

        $(".btn-ship-payment").attr("disabled",false);
        $('.new_ship_address').show();

        $("#empty_shipping").hide();
        $(".new_ship").hide();
      }
      else
      {
        $('.new_ship_address').hide();
        $(".btn-ship-payment").attr("disabled",true);
        $("#empty_shipping").show();
        $(".new_ship").show();
      }
    });

        $(".payment-popup1").hide();
        $("body").removeClass("pos-fix");
        $(".new_ship").hide();
      });

    // }


  }

  function stripeResponseHandler1(status, response) {

  // Grab the form:
  var $form = $('#checkout_payment');
 
  if (response.error) { // Problem!

    if(response.error)
    {
      
      $('.stripe_error').removeClass('hide');
      $('.stripe_error').text(response.error.message);
      $('.stripe_error').show();
    }
    else
    {
      $('.stripe_error').addClass('hide');
      $('.stripe_error').hide();
      // Show the errors on the form   
    $form.find('.payment-card-error').text(response.error.message);
    $('.payment-card-error').removeClass('hide');
    
    }
    
    return false;

  } else { // Token was created!
    $('.payment-card-error').addClass('hide');
    // Get the token ID:
    var token = response.id;

    // Insert the token into the form so it gets submitted to the server:
    //$form.append($('<input type="hidden" name="stripeToken" />').val(token));
    $('#stripeToken').val(token);
    // Submit the form:
    var card_name = $('#card_name').val();
    var card_number = $('#cc_number').val();
    var cc_expire_month = $('#cc_expire_month').val();
    var cc_expire_year = $('#cc_expire_year').val();
    var cvv = $('#cvv').val();
    $('.cancel_').trigger('click');
    $('#add_payment_method').attr('disabled',true);    
    $http.post('add_payment_method',{card_number: card_number,card_name : card_name,cc_expire_month:cc_expire_month,cc_expire_year:cc_expire_year,cvv:cvv,token:token }).then(function(response){
        $scope.get_payment_details();
        $('.edit_payment_details').hide();
    });
    return true;

  }
}

  $scope.add_payment_method = function(){

    Stripe.setPublishableKey(publish_key);
var stripe = Stripe.card.createToken({
  number: $('#cc_number').val(),
  cvc: $('#cvv').val(),
  exp_month: $('#cc_expire_month').val(),
  exp_year: $('#cc_expire_year').val()
}, stripeResponseHandler1);


return false;

    // var card_name = $('#card_name').val();
    // var card_number = $('#cc_number').val();
    // var cc_expire_month = $('#cc_expire_month').val();
    // var cc_expire_year = $('#cc_expire_year').val();
    // var cvv = $('#cvv').val();
    // $('.cancel_').trigger('click');
    // $('#add_payment_method').attr('disabled',true);    
    // $http.post('add_payment_method',{card_number: card_number,card_name : card_name,cc_expire_month:cc_expire_month,cc_expire_year:cc_expire_year,cvv:cvv }).then(function(response){
    //     $scope.get_payment_details();
    //     $('.edit_payment_details').hide();
    // });
  }
  

  $scope.add_billing_address=function(){
    var billing_name=$("#billing_name_add").val();
    var billing_address_1=$("#billing_address_1_add").val();
    var billing_address_2=$("#billing_address_2_add").val();
    var billing_country=$("#billing_country_add").val();
    var billing_city=$("#billing_city_add").val();
    var billing_state=$("#billing_state_add").val();
    var billing_zip=$("#billing_zip_add").val();
    var billing_phone=$("#billing_phone_add").val();
    var billing_address_nick=$("#billing_address_nick_add").val();
    var is_default="yes";
    
      $(".add_billing").attr("disabled",true);
      var billing_name
      var billing_address_1
      var billing_address_2
      var billing_country
      var billing_city
      var billing_state
      var billing_zip
      var billing_phone
      var billing_address_nick
      var is_default="yes";
      var edit=$("#edit_bill").val();
      $http.post('add_billing_details', { edit:edit,name:billing_name, address_line:billing_address_1, 
        address_line2:billing_address_2, country:billing_country, city:billing_city, 
        state:billing_state, postal_code:billing_zip,phone_number:billing_phone,
        address_nick:billing_address_nick,is_default:is_default
      }).then(function(response) 
      {
        $scope.get_billing_details();
        $(".payment-popup-bill").hide();
        $("body").removeClass("pos-fix");
        $(".new_bill").hide();
      });
    }
     $scope.edit_billing_address=function(){
    var billing_name=$("#billing_name").val();
    var billing_address_1=$("#billing_address_1").val();
    var billing_address_2=$("#billing_address_2").val();
    var billing_country=$("#billing_country").val();
    var billing_city=$("#billing_city").val();
    var billing_state=$("#billing_state").val();
    var billing_zip=$("#billing_zip").val();
    var billing_phone=$("#billing_phone").val();
    var billing_address_nick=$("#billing_address_nick").val();
    var is_default="yes";
    
      $(".add_billing").attr("disabled",true);
      var billing_name
      var billing_address_1
      var billing_address_2
      var billing_country
      var billing_city
      var billing_state
      var billing_zip
      var billing_phone
      var billing_address_nick
      var is_default="yes";
      var edit='yes';
      $http.post('add_billing_details', { edit:edit,name:billing_name, address_line:billing_address_1, 
        address_line2:billing_address_2, country:billing_country, city:billing_city, 
        state:billing_state, postal_code:billing_zip,phone_number:billing_phone,
        address_nick:billing_address_nick,is_default:is_default
      }).then(function(response) 
      {
        // $scope.get_billing_details();
        $http.post('get_billing_details', {  time:$.now()}).then(function(response) 
        {
          $("#billing_loading").hide();
          if(response.data.length)
          {
            $scope.billing_details=response.data;
            $("#empty_billing").hide();
            $(".new_bill").hide();
            $('.new_bill_address').show();
          }
          else
          {
            $("#empty_billing").show();
            $(".new_bill").show();
            $('.new_bill_address').hide();

          }
        });
        $(".payment-popup-bill").hide();
        $("body").removeClass("pos-fix");
        $(".new_bill").hide();
      });
    }

  
}]);


app.directive('ordersPagination', function(){  
   return{
      restrict: 'E',
      template: '<ul class="pagination">'+
        '<li class="page-item" ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(1)" class="page-link">&laquo;</a></li>'+
        '<li class="page-item" ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(currentPage-1)" class="page-link">&lsaquo; '+ $('#pagin_prev').val() +'</a></li>'+
        '<li class="page-item" ng-repeat="i in range" ng-class="{active : currentPage == i}">'+
            '<a href="javascript:void(0)" ng-click="search_result(i)" class="page-link">{{i}}</a>'+
        '</li>'+
        '<li class="page-item" ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(currentPage+1)" class="page-link">'+ $('#pagin_next').val() +' &rsaquo;</a></li>'+
        '<li class="page-item" ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(totalPages)" class="page-link">&raquo;</a></li>'+
      '</ul>'
   };
}).controller('orders', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
  $scope.first_search = 'Yes';
  $scope.current_date = new Date();
  $scope.range = [];
  $scope.totalPages = 0;
  $scope.currentPage = 1;
  $scope.orders= [];
  $scope.count_all = 0;
  $scope.count_open = 0;
  $scope.count_completed = 0;
  $scope.count_cancelled = 0;



$scope.search_result = function (pageNumber) {
   
  if(pageNumber===undefined){
      pageNumber = '1';
  }
  $(".no_orders").hide(); 
  var search = $("#search").val(); 
  var search_by = $("#search_by").val(); 
  var current=$("#current").val();
  setGetParameter('search', search);
  setGetParameter('search_by', search_by);
  setGetParameter('current', current);
  $('.all-pro-table').addClass('whiteloading');
  $http.post('order_search?page='+pageNumber, {current:current,search: search, search_by: search_by})
      .then(function(response) {

      $scope.orders = response.data; 
      $scope.totalPages   = response.data.last_page;
      $scope.currentPage  = response.data.current_page;
      $(".icon-del").show();
      $scope.count_all = response.data.count_all;
      $scope.count_open = response.data.count_open;
      $scope.count_completed = response.data.count_completed;
      $scope.count_cancelled = response.data.count_cancelled;

        // Pagination Range
      var pages = []; 
      for(var i=1;i<=response.data.last_page;i++) {          
        pages.push(i);
      }
      $scope.range = pages; 
      
      if(response.data.total==0)
      {
        $(".no_orders").show();
      }
      else
      {
       $(".no_orders").hide();
      }

      $('.all-pro-table').removeClass('whiteloading');
  }); 
};
$scope.search_result();

$scope.updateFilterby = function (filter)
{
  $("#search_by").val(filter);
  var filter_placeholder;
  switch(filter) {
    case 'all':
        filter_placeholder="Search";
        break;
    case 'order_id':
        filter_placeholder="Search Order ID";
        break;
    case 'username':
        filter_placeholder="Search Username";
        break;
    case 'fullname':
        filter_placeholder="Search Fullname";
        break;
    case 'product_id':
        filter_placeholder="Search Product ID";
        break; 
    case 'product_title':
        filter_placeholder="Search Product Title";
        break;       
    default:
        filter_placeholder="Search";
  }
  $("#search").attr("placeholder",filter_placeholder);
}
$(document).on("click",'#no-sel',function(e) {
  if($(this).hasClass("all-sel")) {
        $(".pro-check").attr("checked" , true);
    } else {
        $(".pro-check").attr("checked" , false);
    }
});
$(document).on("keypress",'#search',function(e) {
    if(e.which == 13) {
        $scope.search_result();
    }
});


$scope.resetfilter = function (filter){
    if($("#search").val()!="")
    {
      $("#search").val("");
      $scope.search_result();      
    }
    $(".icon-del").hide();
}
$(document).on('change', '.pro-check', function (e) {
  var action_count = $(".pro-check:checked").length;  

  completed_count = $('.pro-check.Completed').length;
  open_count      = $('.pro-check.Pending').length;

  
           
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-order").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-order").hide();  
      if(action_count ==1)
      {
        if($(".pro-check:checked").hasClass('Pending'))
        {
          $('.process_select').css('display', 'list-item');
          $('.completed_select').css('display', 'none');
        }
        else if($(".pro-check:checked").hasClass('Processing'))
        {
          $('.process_select').css('display', 'none');
          $('.completed_select').css('display', 'list-item');
        }
        else{
          $(".dropdown.bulk.action").show();
          $(".dropdown.select-order").attr("style","display:none");
        }
      }
      else
      {
        current_tab = $('.tab3 li a.current').attr('data');

        if(current_tab == 'completed') {          
          $(".dropdown.bulk.action").hide();      
          $(".dropdown.select-order").attr("style","display:inline-block"); 
        }
        else if(current_tab == 'open'){
            $('.process_select').css('display', 'list-item');
            $('.completed_select').css('display', 'none');
        }        
        else{
          if(current_tab == 'cencelled'){
            $(".dropdown.bulk.action").hide();
            $(".dropdown.select-order").attr("style","display:inline-block");
          }else{
            $('.process_select').css('display', 'list-item');
            $('.completed_select').css('display', 'list-item');
          }
        }
      }
    }
});
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
$(document).on('change', '.select_order', function (e) {
    var up=$(this).val();
    if(up=="view_order")
    {
      var location = APP_URL+"/merchant/order/"+$(this).attr("data-id");
      window.open(location);
    }
    else if(up=="print_receipt")
    {
      var orders_id=$(this).attr("data-id");
      printDiv('printableArea_'+orders_id);
    }
    else
    {
      $('.all-pro-table').addClass('whiteloading');
      var data=[];
      data.push($(this).attr("data-id"));
      
      $http.post('order/status_update', { update:up,data : data,_token:$("#token").val() }).then(function(response) 
      {
        $scope.search_result();
        $("#no-sel").removeClass("all-sel");
        $(".action_count").html("");
        $(".dropdown.bulk.action").hide();
        $(".dropdown.select-order").attr("style","display:inline-block");
      }, function(response)
      {
          if(response.status=='300')
           window.location = APP_URL+'/login';
      });
    }
})

$(document).ready(function(){

  $('.bulk-items').click(function(e) {
    var update=$(this).attr("data");
    
    var activate_data=[];

      if(update == 'process')
        check_action = 'Processing';
      else if(update == 'completed')
        check_action = 'Completed';

      var check_status = [];

    $(".pro-check:checked").each(function() {
        if($(this).hasClass(check_action)){          
          var id = $(this).attr("data-id");
          check_status.push(id);      
        }
        else{
          if(check_action == 'Processing'){            
            if($(this).hasClass('Completed')){
              var id = $(this).attr("data-id");
              check_status.push(id);      
            }
          }
          else{
           var id = $(this).attr("data-id");
            activate_data.push(id);
          }
        }     
    });

    if(check_status.length == 0){
      $('.all-pro-table').addClass('whiteloading');
      $http.post('order/status_update', { update:update,data : activate_data,_token:$("#token").val() }).then(function(response) 
      {
        $scope.search_result();
        $("#no-sel").removeClass("all-sel");
        $(".dropdown.bulk.action").hide();
        $(".dropdown.select-order").attr("style","display:inline-block");
      }, function(response)
      {
          if(response.status=='300')
           window.location = APP_URL+'/login';
      });
    }else{      
      alert('There is already an '+check_action+' order item related to this item : '+ check_status);
    }
  });

  $('.check_action li').click(function(e) {
    var check_status=$(this).attr("data-status");
    switch(check_status) {
        case "all":$(".pro-check").prop("checked" , true);break;
        case "none":$(".pro-check").prop("checked" , false);break;
        case "open":$(".pro-check").prop("checked" , false);$(".pro-check.Pending").prop("checked" , true);break;
        case "completed":$(".pro-check").prop("checked" , false);$(".pro-check.Completed").prop("checked" , true);break;
        case "cancelled":$(".pro-check").prop("checked" , false);$(".pro-check.Cancelled").prop("checked" , true);break;
        default:$(".pro-check").prop("checked" , false);
    }

    var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();      
      $(".dropdown.select-order").attr("style","display:inline-block");       
    }
    else
    {      
      if(check_status == 'open' || check_status == 'all'){
        $("#no-sel").addClass("all-sel");
        $(".action_count").html(action_count);
        $(".dropdown.bulk.action").attr("style","display:inline-block");
        $(".dropdown.select-order").hide(); 
          if(check_status == 'all'){
            completed_count = $('.pro-check.Completed').length;
            cancel_count = $('.pro-check.Cancelled').length;
            if(completed_count == action_count){
              $(".dropdown.bulk.action").hide();      
              $(".dropdown.select-order").attr("style","display:inline-block"); 
            }else if(cancel_count == action_count){
              $(".dropdown.bulk.action").hide();      
              $(".dropdown.select-order").attr("style","display:inline-block"); 
            }
          }
      }
      else{
        $("#no-sel").addClass("all-sel");
        $(".action_count").html(action_count);
        $(".dropdown.bulk.action").hide();      
        $(".dropdown.select-order").attr("style","display:inline-block");   
      }     
    }
  });

  $('#no-sel').click(function(e) {
    if($(this).hasClass("all-sel"))
    {
      $(".pro-check").prop("checked" , true);
    }
    else
    {
      $(".pro-check").prop("checked" , false);
    }
     var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-order").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-order").hide();  
    }
  });

 
  
  $('.pro-check').change(function(e) {

    if($("#no-sel").hasClass("all-sel"))
    {
      $(".pro-check").prop("checked" , true);
    }
    else
    {
      $(".pro-check").prop("checked" , false);
    }
     var action_count = $(".pro-check:checked").length;

    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-order").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-order").hide();  
    }
  })

})

$(document).on("click", '.tab3 li a', function(e) {
    if($(this).hasClass("current"))
    {
      e.preventDefault();
    }
    else
    {
      var check_data=$(this).attr("data");
      var check_second=0;
      if(check_data!="all")
      {
        $('.check_action li').each(function(){
          check_second++;
          if(check_second>2)
          {
            $(this).attr("style","display:none");
          }
        })
      }
      else
      {
        $('.check_action li').attr("style","display:block");
      }

        if(check_data == 'open')
          $('.process_select').attr('style','display:none');
        else
          $('.process_select').attr('style','display:block');
        
        if(check_data == 'completed' || check_data == 'cancelled')
          $('.completed_select').attr('style','display:none');
        else
          $('.completed_select').attr('style','display:block');

        $("#no-sel").removeClass("all-sel");
        $(".action_count").html("");
        $(".dropdown.bulk.action").hide();
        $(".dropdown.select-order").attr("style","display:inline-block");
        
        $(".tab3 li a").removeClass("current"); 
        $(this).addClass("current");
        $("#search").val("");
        $("#search_by").attr("value","all");
        $("#search").attr("placeholder","Search");
        $("#current").val($(this).attr("data"));
        $scope.orders= [];
        $scope.search_result();
    } 
})



  function setGetParameter(paramName, paramValue)
  {
      var url = window.location.href;

      if (url.indexOf(paramName + "=") >= 0)
      {
          var prefix = url.substring(0, url.indexOf(paramName));
          var suffix = url.substring(url.indexOf(paramName));
          suffix = suffix.substring(suffix.indexOf("=") + 1);
          suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
          url = prefix + paramName + "=" + paramValue + suffix;
      }
      else
      {
      if (url.indexOf("?") < 0)
          url += "?" + paramName + "=" + paramValue;
      else
          url += "&" + paramName + "=" + paramValue;
      }
      history.pushState(null, null, url);
  }

}]);

app.controller('view_orders', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
$('.all-pro-table').addClass('whiteloading');
$scope.orders=[];
$scope.orders_details=[];
$scope.view_orders = function (pageNumber) {
  var id=$("#order_id").val();
  $http.post(APP_URL+'/merchant/orders_view', {id:id})
      .then(function(response) {
        $scope.vm.subtotal=0;$scope.vm.shipping_fee=0;$scope.vm.incremental_fee=0;$scope.vm.merchant_fee=0;
      $scope.orders = response.data; 
      $('.all-pro-table').removeClass('whiteloading');
  }); 
};
$scope.view_orders_details = function (pageNumber) {
  var id=$("#order_id").val();
  $http.post(APP_URL+'/merchant/orders_details_view', {id:id})
      .then(function(response) {
      $scope.orders_details = response.data; 
  }); 
};
$scope.view_orders_details();
$scope.view_orders();


$scope.merchant_action = function (action,id) {
  
  if(action=="cancel")
  {
    var id=$('#order_detail_id').val();
    $("body").removeClass("pos-fix");
    $(".merchant-cancel-popup").hide();
  }
  else
  {
    var id=id;

  }
  $("#status_loader_"+id).show();
  var reason_msg = $('#reason_msg').val();
  $('#reason_msg').val('');
  $('.setting-popup').hide();

  $http.post(APP_URL+'/merchant/merchant_action', {id:id,action:action,reason:reason_msg})
      .then(function(response) {

      $scope.view_orders();
      $scope.view_orders_details();
  }); 
};


}]);


app.directive('ReturnsPagination', function(){  
   return{
      restrict: 'E',
      template: '<ul class="pagination">'+
        '<li class="page-item" ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(1)" class="page-link">&laquo;</a></li>'+
        '<li class="page-item" ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(currentPage-1)" class="page-link">&lsaquo; '+ $('#pagin_prev').val() +'</a></li>'+
        '<li class="page-item" ng-repeat="i in range" ng-class="{active : currentPage == i}">'+
            '<a href="javascript:void(0)" ng-click="search_result(i)" class="page-link">{{i}}</a>'+
        '</li>'+
        '<li class="page-item" ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(currentPage+1)" class="page-link">'+ $('#pagin_next').val() +' &rsaquo;</a></li>'+
        '<li class="page-item" ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(totalPages)" class="page-link">&raquo;</a></li>'+
      '</ul>'
   };
}).controller('returns_requests', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
  $scope.first_search = 'Yes';
  $scope.current_date = new Date();
  $scope.range = [];
  $scope.totalPages = 0;
  $scope.currentPage = 1;
  $scope.orders= [];




$scope.search_result = function (pageNumber) {
   
  if(pageNumber===undefined){
      pageNumber = '1';
  }
  $(".no_orders").hide(); 
  var search = $("#search").val(); 
  var search_by = $("#search_by").val(); 
  var current=$("#current").val();
  setGetParameter('search', search);
  setGetParameter('search_by', search_by);
  setGetParameter('current', current);
  $('.all-pro-table').addClass('whiteloading');
  $http.post('return_search?page='+pageNumber, {current:current,search: search, search_by: search_by})
      .then(function(response) {

      $scope.orders = response.data; 
      $scope.totalPages   = response.data.last_page;
      $scope.currentPage  = response.data.current_page;
      $(".icon-del").show();


        // Pagination Range
      var pages = []; 
      for(var i=1;i<=response.data.last_page;i++) {          
        pages.push(i);
      }
      $scope.range = pages; 
      
      if(response.data.total==0)
      {
        $(".no_orders").show();
      }
      else
      {
       $(".no_orders").hide();
      }

      $('.all-pro-table').removeClass('whiteloading');
  }); 
};
$scope.search_result();

$scope.updateFilterby = function (filter)
{
  $("#search_by").val(filter);
  var filter_placeholder;
  switch(filter) {
    case 'all':
        filter_placeholder="Search";
        break;
    case 'order_id':
        filter_placeholder="Search Order ID";
        break;
    case 'username':
        filter_placeholder="Search Username";
        break;
    default:
        filter_placeholder="Search";
  }
  $("#search").attr("placeholder",filter_placeholder);
}
$(document).on("click",'#no-sel',function(e) {
  if($(this).hasClass("all-sel")) {
        $(".pro-check").attr("checked" , true);
    } else {
        $(".pro-check").attr("checked" , false);
    }
});
$(document).on("keypress",'#search',function(e) {
    if(e.which == 13) {
        $scope.search_result();
    }
});


$scope.resetfilter = function (filter){
    if($("#search").val()!="")
    {
      $("#search").val("");
      $scope.search_result();      
    }
    $(".icon-del").hide();
}
$(document).on('change', '.pro-check', function (e) {
  var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-order").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-order").hide();  
      if(action_count ==1)
      {
        if($(".pro-check:checked").hasClass('Requested'))
        {
          $('.accept_select').css('display', 'list-item');
          $('.reject_select').css('display', 'list-item');
        }
        else{
          $(".dropdown.bulk.action").hide();
          $(".dropdown.select-order").attr("style","display:inline-block");
        }
      }
      else
      {
        $('.accept_select').css('display', 'list-item');
        $('.reject_select').css('display', 'list-item');
      }
    }
});

$(document).on('change', '.select_order', function (e) {
    var up=$(this).val();

    $('.all-pro-table').addClass('whiteloading');
    var data=[];
    data.push($(this).attr("data-id"));
    
    $http.post('order_return/status_update', { update:up,data : data,_token:$("#token").val() }).then(function(response) 
    {

      $scope.search_result();
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-order").attr("style","display:inline-block");
    }, function(response)
    {
        if(response.status=='300')
         window.location = APP_URL+'/login';
    });
})

$(document).ready(function(){

  $('.bulk-items').click(function(e) {
    var update=$(this).attr("data");

    var check_action ='';

    var activate_data=[];
    var check_status =[];

    if(update == 'accept')
      check_action ='Approved';
    else if(update == 'reject')
      check_action ='Rejected';

    
    $(".pro-check:checked").each(function() {

        if($(this).hasClass(check_action)){
          var id = $(this).attr("data-id");
          check_status.push(id);       
        }
        else{
          var id = $(this).attr("data-id");
          activate_data.push(id);
        }
    });

    if(check_status.length == 0){
      $http.post('order_return/status_update', { update:update,data : activate_data,_token:$("#token").val() }).then(function(response) 
      {
        $('.all-pro-table').addClass('whiteloading');
        $scope.search_result();
        $("#no-sel").removeClass("all-sel");
        $(".dropdown.bulk.action").hide();
        $(".dropdown.select-order").attr("style","display:inline-block");
      }, function(response)
      {
          if(response.status=='300')
           window.location = APP_URL+'/login';
      });
    }
    else{
      alert('There is already an '+check_action+' Order Return item related to this item : '+ check_status);
    }
  })

  $('.check_action li').click(function(e) {
    var check_status=$(this).attr("data-status");
    switch(check_status) {
        case "all":$(".pro-check").prop("checked" , true);break;
        case "none":$(".pro-check").prop("checked" , false);break;
        case "open":$(".pro-check").prop("checked" , false);$(".pro-check.Pending").prop("checked" , true);break;
        default:$(".pro-check").prop("checked" , false);
    }
    var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-order").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-order").hide();  
    }
  });

  $('#no-sel').click(function(e) {
    if($(this).hasClass("all-sel"))
    {
      $(".pro-check").prop("checked" , true);
    }
    else
    {
      $(".pro-check").prop("checked" , false);
    }
     var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-order").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-order").hide();  
    }
  });
  
  $('.pro-check').change(function(e) {

    if($("#no-sel").hasClass("all-sel"))
    {
      $(".pro-check").prop("checked" , true);
    }
    else
    {
      $(".pro-check").prop("checked" , false);
    }
     var action_count = $(".pro-check:checked").length;
    if(action_count==0)
    {
      $("#no-sel").removeClass("all-sel");
      $(".action_count").html("");
      $(".dropdown.bulk.action").hide();
      $(".dropdown.select-order").attr("style","display:inline-block");
    }
    else
    {
      $("#no-sel").addClass("all-sel");
      $(".action_count").html(action_count);
      $(".dropdown.bulk.action").attr("style","display:inline-block");
      $(".dropdown.select-order").hide();  
    }
  })

})





  function setGetParameter(paramName, paramValue)
  {
      var url = window.location.href;

      if (url.indexOf(paramName + "=") >= 0)
      {
          var prefix = url.substring(0, url.indexOf(paramName));
          var suffix = url.substring(url.indexOf(paramName));
          suffix = suffix.substring(suffix.indexOf("=") + 1);
          suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
          url = prefix + paramName + "=" + paramValue + suffix;
      }
      else
      {
      if (url.indexOf("?") < 0)
          url += "?" + paramName + "=" + paramValue;
      else
          url += "&" + paramName + "=" + paramValue;
      }
      history.pushState(null, null, url);
  }

}]);

app.directive('transfersPagination', function(){  
   return{
      restrict: 'E',
      template: '<ul class="pagination">'+
        '<li class="page-item" ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(1)" class="page-link">&laquo;</a></li>'+
        '<li class="page-item" ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(currentPage-1)" class="page-link">&lsaquo; '+ $('#pagin_prev').val() +'</a></li>'+
        '<li class="page-item" ng-repeat="i in range" ng-class="{active : currentPage == i}">'+
            '<a href="javascript:void(0)" ng-click="search_result(i)" class="page-link">{{i}}</a>'+
        '</li>'+
        '<li class="page-item" ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(currentPage+1)" class="page-link">'+ $('#pagin_next').val() +' &rsaquo;</a></li>'+
        '<li class="page-item" ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(totalPages)" class="page-link">&raquo;</a></li>'+
      '</ul>'
   };
}).controller('payout_history', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
  $scope.first_search = 'Yes';
  $scope.current_date = new Date();
  $scope.range = [];
  $scope.totalPages = 0;
  $scope.currentPage = 1;
  $scope.transfers= [];

  $scope.search_result = function (pageNumber) {
    if(pageNumber===undefined){
        pageNumber = '1';
    }
    $(".no_results").hide();
    $('.all-pro-table').addClass('whiteloading');
    var type=$("#current").val();
    $http.post('transfers?page='+pageNumber, {current:type})
        .then(function(response) {

        $scope.transfers = response.data; 
        $scope.totalPages   = response.data.last_page;
        $scope.currentPage  = response.data.current_page;

          // Pagination Range
        var pages = []; 
        for(var i=1;i<=response.data.last_page;i++) {          
          pages.push(i);
        }
        $scope.range = pages; 
        
        if(response.data.total==0)
        {
          if(type=="Completed")
          {
          $(".no_results").html(Lang.get('js_messages.merchant.no_balance_history_found'));  
          }
          else
          {
            $(".no_results").html(Lang.get('js_messages.merchant.no_transfers_found'));  
          }
          
          $(".no_results").show();
        }
        else
        {
         $(".no_results").hide();
        }

        $('.all-pro-table').removeClass('whiteloading');
    }); 
  };
  $scope.search_result();

  $(document).on("click", '.tab3 li a', function(e) {
      if($(this).hasClass("current"))
      {
        e.preventDefault();
      }
      else
      {       
        $(".tab3 li a").removeClass("current"); 
        $(this).addClass("current");
        $("#current").val($(this).attr("data"));
        $scope.transfers= [];
        $scope.search_result();
      } 
  })


}]);



$(document).ready(function(){
    $(".nt_order1").click(function(){
        $(".nt_merchant1").slideToggle();
    });
      $(".nt_order2").click(function(){
        $(".nt_merchant2").slideToggle();
    });
        $(".nt_order3").click(function(){
        $(".nt_merchant3").slideToggle();
    });
});