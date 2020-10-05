$(document).ready(function(){ 	

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

app.controller('merchant_insights', ['$scope', '$http','$filter', function($scope, $http,$filter) 
{
	$scope.most_click=[];
	$scope.most_popular=[];
	$scope.most_store=[];
	$scope.chart_click=[];
	$scope.insight = function (){
		var start=$('#start_date').val();
		var end = $('#end_date').val();
		if(start !='' && end !=''){
           var days ='specific';
		}else{
			var days = $('#range').val();
		}		

		var log_type = $('#log_type').val();

		$("#products_loading").show();
		$('.insights_container').hide();
		$http.post(APP_URL+'/merchant/insight_summary', {days:days,log_type:log_type,date_from:start,date_to:end}).then(function(response) 
	    {
	    	$scope.startdate = response.data.startdate;
	    	$scope.today     = response.data.today;
	    	$scope.range     = response.data.range;
	    	$scope.log_type  = response.data.log_type;
	    	$scope.total_views     = response.data.total_views;
	    	$scope.total_likes    = response.data.total_likes;
	    	$scope.total_order     = response.data.total_order;
	    	$scope.total_amount     = response.data.total_amount;
	    	var chart = response.data.chart;	    

	    	if($scope.range == '12')
	    	{
	    		$scope.type = Lang.get('js_messages.merchant.months');
	    	}
	    	else
	    	{
	    		$scope.type = Lang.get('js_messages.merchant.days');
	    	}
		    
		    if(response.data.clicks=="" && response.data.store =="")
	    	{
	    		$('#click_empty').show();
	    		$('.most_click').hide();
	    	}
	    	else{
	    		$('#click_empty').hide();
	    		$('.most_click').show();
	    	}

	    	if(response.data.popular=="")
	    	{
	    		$('#pop_empty').show();
	    		$('#pop_product').hide();
	    	}
	    	else{
	    		$('#pop_empty').hide();
	    		$('#pop_product').show();
	    	}

		    angular.forEach(response.data.clicks, function(value, key){
		    		value['most_click_count'] = value.length;
		    		value['created_date']	 = value[0].created_at;
		    		value['click_products'] = value[0].products;
		         $scope.most_click.push(value);
		   	});

		   	
		   	angular.forEach(response.data.popular, function(value, key){
		    		value['most_popular_count'] = value.length;	
		    		value['pop_products'] = value[0].products;
		         $scope.most_popular.push(value);
		   	});

		   	angular.forEach(response.data.store, function(value, key){
		    		value['most_store_count'] = value.length;	
		    		value['click_store'] = value[0].store;
		         $scope.most_store.push(value);
		   	});
		   	
		   	
		   	if($scope.range == 'specific'){
            $('.date-range').html($scope.startdate+' - '+ $scope.today);
		   	}else{
		   	$('.date-range').html(Lang.get('js_messages.merchant.Last')+' '+$scope.range+' '+ $scope.type);	
		   	}
		   	
		   	$('.insights_container').show();
		   	$("#products_loading").hide();

		   	if($scope.log_type=='view')
	    	{
	    		var log_title = Lang.get('js_messages.merchant.total_clicks');
	    		var series_name = Lang.get('js_messages.merchant.clicks');
	    	}
	    	else if($scope.log_type=='likes')
	    	{
	    		var title = $('#likes').val();
	    		var log_title = Lang.get('js_messages.merchant.total')+' '+title;
	    		var series_name = title;
	    	}
	    	else if($scope.log_type=='orders')
	    	{
	    		var log_title = Lang.get('js_messages.merchant.total_orders');
	    		var series_name = Lang.get('js_messages.merchant.orders');
	    	}
	    	else if($scope.log_type=='sales')
	    	{
	    		var log_title = Lang.get('js_messages.merchant.total_sales');
	    		var series_name = Lang.get('js_messages.merchant.sales');
	    	}
	    	else
	    	{
	    		var log_title = Lang.get('js_messages.merchant.total') +$scope.log_type;
	    		var series_name = $scope.log_type;
	    	}
    	
	    	// chart view
		   	Highcharts.chart('container', {
			    chart: {
			        type: 'spline'
			    },
			    title: {
			        text: log_title
			    },

			    legend: {
				        enabled: false
				    },
			  
			    xAxis: {
			        type: 'datetime',
			        dateTimeLabelFormats: {
			                month: '%b %e , %Y',
			            }
			    },
			    yAxis: {   
			    	allowDecimals: false,    
			        min: 0
			    },
			    tooltip: {
			        headerFormat: '<b>{series.name}</b><br>',
			        pointFormat: '{point.x:%e %b %Y} : {point.y} '
			    },

			    plotOptions: {
			        spline: {
			            marker: {
			                enabled: true
			            }
			        }
			    },

			    series: [{
			        name: log_title,
			        data: chart,
			    }]
			});

		});

	}

	$scope.insight();

	$('.controll-date ul li a').click(function(e) {
		e.stopPropagation();
	  	var date_range=$(this).attr("range");
	  	
	  	$('.controll-date').hide();
	  	$('#range').val(date_range);
	  	$('.date_detail').hide();
	  	
	  	var originalURL = window.location.href;	  	
	    var remove_datafrom = removeParam("date_from", originalURL);
        removeParam("date_to", remove_datafrom);
        
	  	if(date_range != '')
        $start_date = $('#start_date').val();
        $end_date = $('#end_date').val();

	    setGetParameter('range', date_range);

	    $scope.most_click=[];
		$scope.most_popular=[];
		$scope.most_store=[];
		
	    $scope.insight();
	    
	});

	$('.type li a').click(function(e) {
		e.stopPropagation();
	  	var log_type=$(this).attr("log-type");
	  	$('#log_type').val(log_type);
	  	var originalURL = window.location.href;	
	  	$range = $('#range').val();
	  	
	  	if($range != 'specific'){	 
	     
	   var remove_datafrom = removeParam("date_from", originalURL);
        removeParam("date_to", remove_datafrom);
    }
	  	if(log_type != '')
	    	setGetParameter('log_type', log_type);

	    $scope.most_click=[];
		$scope.most_popular=[];
		$scope.most_store=[];

	    $scope.insight();
	});

	

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

	  function removeParam(key, sourceURL) {	  	
		    var rtn = sourceURL.split("?")[0],
		        param,
		        params_arr = [],
		        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
		       
		    if (queryString !== "") {
		        params_arr = queryString.split("&");
		        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
		            param = params_arr[i].split("=")[0];
		            if (param === key) {
		                params_arr.splice(i, 1);
		            }
		        }
		        rtn = rtn + "?" + params_arr.join("&");		         
		    }
		    var start=$('#start_date').val('');
			var end = $('#end_date').val('');
		    history.pushState(null, null, rtn);
		    return rtn;
		}

  
$(".datepicker").datepicker({

			maxDate: 0,
			numberOfMonths: 1,
			beforeShowDay: function(date) {
				var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#start_date").val());
				var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#end_date").val());
				return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
			},
			onSelect: function(dateText, inst) {
				var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#start_date").val());
				var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#end_date").val());
				if (!date1 || date2) {
					
					$("#start_date").val(dateText);
					$("#end_date").val("");
                    $(this).datepicker();
				}
				 else {
				 	$("#end_date").val(dateText);
                    $(this).datepicker();
                    $start = $("#start_date").val();
                    $end = $("#end_date").val();
                    
                    if($start > $end){
                    	var date_range ='specific';
                    	$('#range').val(date_range);
                    	$("#start_date").val(dateText);
                    	$("#end_date").val($start);
                    	 $(this).datepicker();
                    	 var date_range ='specific';
	  	            if(date_range != '')
	    	        setGetParameter('range', date_range);
	    	        setGetParameter('date_from', $("#start_date").val());
	    	        setGetParameter('date_to', $("#end_date").val());
	    	        $('.controll-date').hide();
	    	         $scope.most_click=[];
		             $scope.most_popular=[];
		             $scope.most_store=[];
                    $scope.insight();
                    }else{

                    var date_range ='specific';
                    $('#range').val(date_range);
	  	            if(date_range != '')
	    	        setGetParameter('range', date_range);
                    setGetParameter('date_from', $("#start_date").val());
	    	        setGetParameter('date_to', $("#end_date").val());
	    	        $('.controll-date').hide();
	    	         $scope.most_click=[];
		             $scope.most_popular=[];
		             $scope.most_store=[];
                    $scope.insight();
                }
                    
                   
				}
				
			}
		});

}]);
