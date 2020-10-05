$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

app.controller('help', ['$scope', '$http', '$compile', '$timeout', function($scope, $http, $compile, $timeout) {

$scope.change_category = function(value) {
	$http.post(APP_URL+'/admin/ajax_help_subcategory/'+value).then(function(response) {
    	$scope.subcategory = response.data;
    	$timeout(function() { $('#input_subcategory_id').val($('#hidden_subcategory_id').val()); $('#hidden_subcategory_id').val('') }, 10);
    });
};

$timeout(function() { $scope.change_category($scope.category_id); }, 10);

}]);

var currenttime = $('#current_time').val();

var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
document.getElementById("show_date_time").innerHTML="<b>"+datestring+"</b>"+"&nbsp;<b>"+timestring+"</b>";
}

window.onload=function(){
setInterval("displaytime()", 1000)
}

app.controller('destination_admin', ['$scope', '$http', '$compile', function($scope, $http, $compile) {

initAutocomplete(); // Call Google Autocomplete Initialize Function

// Google Place Autocomplete Code

var autocomplete;

function initAutocomplete()
{
  autocomplete = new google.maps.places.Autocomplete(document.getElementById('input_home_location'));
    autocomplete.addListener('place_changed', fillInAddress);
   
}

function fillInAddress() 
{
    fetchMapAddress(autocomplete.getPlace());
    
}

function fetchMapAddress(data)
{

    var place = data;
   

   $('#input_home_location').val(place.formatted_address);
  var latitude  = place.geometry.location.lat();
  var longitude = place.geometry.location.lng();

  $('#home_latitude').val(latitude);
  $('#home_longitude').val(longitude);
 
}  

initAutocomplete1(); // Call Google Autocomplete Initialize Function

// Google Place Autocomplete Code

var autocomplete1;

function initAutocomplete1()
{
  autocomplete1 = new google.maps.places.Autocomplete(document.getElementById('input_work_location'));
    autocomplete1.addListener('place_changed', fillInAddress1);
   
}

function fillInAddress1() 
{
    fetchMapAddress1(autocomplete1.getPlace());
    
}

function fetchMapAddress1(data)
{
  /*var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'long_name',
      country: 'short_name',
      postal_code: 'short_name'
  };

    $('#city').val('');
    $('#state').val('');
    $('#country').val('');
    $('#destination_address').val('');
    $('#address_line_2').val('');
    $('#postal_code').val('');*/

    var place1 = data;
    /*for (var i = 0; i < place.address_components.length; i++) 
    {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) 
      {
        var val = place.address_components[i][componentForm[addressType]];
      
      if(addressType       == 'street_number')
        $scope.street_number = val;
      if(addressType       == 'route')
        $('#destination_address').val(val);
      if(addressType       == 'postal_code')
        $('#postal_code').val(val);
      if(addressType       == 'locality')
        $('#city').val(val);
      if(addressType       == 'administrative_area_level_1')
        $('#state').val(val);
      if(addressType       == 'country')
        $('#country').val(val);
      }
    }*/

   $('#input_work_location').val(place1.formatted_address);
  var latitude  = place1.geometry.location.lat();
  var longitude = place1.geometry.location.lng();
 

  $('#work_latitude	').val(latitude);
  $('#work_longitude').val(longitude);
 
  // $('#zoom').val(zoom);
  // $('#bounds').val(bounds);
}  

}]);
$(document).on('change','#input_driver',function(){

 ($('#input_driver').val()=='smtp') ? $('#show_hide').show() : $('#show_hide').hide();
 ($('#input_driver').val()=='mailgun') ? $('#hide_show').show() : $('#hide_show').hide();

 });
