app.controller('order_return', ['$scope', '$http','$timeout', function($scope, $http,$timeout) 
{
	 $scope.all_order_return=[];
	 $scope.currentPage = '0';
	 // get order return details
	$scope.return_request = function()
  		{
  			$("#products_loading").show();

	    	var status = $("#status_check").val(); 
	    	var search = $("#search").val(); 
  			var search_by = $("#search_by").val(); 
	    	if(status != '')
	    		setGetParameter('status', status);
	    	if(search !='')
	    		setGetParameter('search', search);
	    	if(search_by !='')
	    		setGetParameter('search_by', search_by);

  			pageNumber=parseInt($scope.currentPage)+1;
  			$http.post(APP_URL+'/merchant/return_request?page='+pageNumber,{status:status,search: search, search_by: search_by}).then(function(response) 
		      {
		      	$scope.currentPage=response.data.current_page;

	      		if(response.data.data=="")
		    	{
		    		$(".empty").show();
		    	}
		    	else
		    	{
			    	$(".empty").hide();	
		    	}
		        angular.forEach(response.data.data, function(value, key){
		        	$scope.all_order_return.push(value);
		   		});
			        
		           $("#products_loading").hide();
		      });
  		}
  	$( document ).ready(function() {
	  $scope.return_request();  
	});
	$(document).on("keypress",'#search',function(e) {
	    if(e.which == 13) {
	    	$scope.all_order_return=[];
  			$scope.currentPage = '0';
	        $scope.return_request();  
	    }
	});

  	$('.check_action li').click(function(e) {
  		e.stopPropagation();
  		var check_status=$(this).attr("data-status");
  		$('#status_check').val(check_status);
  		$scope.all_order_return=[];
  		$scope.currentPage = '0';
  		$scope.return_request();  
  	});

  	$scope.updateFilterby = function (filter)
	{
	  $("#search_by").val(filter);
	  var filter_placeholder;
	  switch(filter) {
	    case 'order_id':
	        filter_placeholder="Search Order Id";
	        break;
	    case 'customer':
	        filter_placeholder="Search Customer";
	        break;
	    default:
	        filter_placeholder="Search";
	  }
	  $("#search").attr("placeholder",filter_placeholder);
	}
	$scope.resetfilter = function (filter){
    if($("#search").val()!="")
    {
      $("#search").val("");
      $scope.search_result();      
    }
    $(".icon-pos").hide();
}

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