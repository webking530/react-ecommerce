app.controller('add_product', ['$scope', '$http', '$timeout', '$sce',function($scope, $http, $timeout,$sce) 
{ 
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
      url: APP_URL+"/admin/product/add_video_thumb/"+$("#product_id").val()+'/'+$("#update_type").val(),
      type: "POST", 
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      success: function(response){
         $('#add_product_video').val("1");
        if(response.error['error_title'] == 'Invalid Product Id'){
              window.location = APP_URL+'/admin/products';
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
  $scope.product_options = [];
  $scope.shipping = [];
  $scope.images = [];
  $scope.shipping_type="Flat Rates";

  $(document).on('change','#input_default_currency',function(){
    $http.post(APP_URL+'/admin/products/change_currency', {currency:$("#input_default_currency").val() }).then(function(response) 
    {
      $("#currency_code").val(response.data.currency_code);
      $('#minimum_amount').val(response.data.minimum_amount);
      $('.boot_addon').html(response.data.currency_symbol);
      $('#currency_symbol').html(response.data.currency_symbol);
      $("#price").removeData("previousValue");
      $(".product_option_price").removeData("previousValue");
      $("#add_product_form").data('validator').element('.product_option_price'); 
    });
  });
  $scope.updateOptionDiscount = function(index) {
    
    
    var discount_cal=100-($scope.product_options[index].option_price/$scope.product_options[index].retail_price)*100;

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
     $('#required_retail_'+index).hide();
     $('#required_price_less_'+index).hide();
    
    
    $scope.product_options[index].total_quantity=$scope.product_options[index].option_qty;
    $scope.product_options[index].option_name=$scope.product_options[index].option_name;
    var minimum_amount = $('#minimum_amount').val();
    if($scope.product_options[index].option_qty =='' || $scope.product_options[index].option_qty == null)
    {
      $('#required_qty_'+index).show();
      valid_error = 1;
    }

    if($scope.product_options[index].option_price == '' || $scope.product_options[index].option_price == null )
    { 
      $('#required_price_'+index).show();
      valid_error =1;
    }
    if(parseInt($scope.product_options[index].option_price) < parseInt(minimum_amount))
    {
      $('#required_price_less_'+index).show();
      $('#required_price_less_'+index).html('The Price must be at least'+' '+$('.boot_addon').html()+' '+$('#minimum_amount').val());
      valid_error =1;
    }

    if($(".check_sale_option_"+index).prop('checked') == true && ($scope.product_options[index].retail_price==null || parseInt($scope.product_options[index].option_price) >= parseInt($scope.product_options[index].retail_price)))
    { 
      $('#required_retail_'+index).show();
      valid_error =1;
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
            $('.boot_addon').html($("#currency_symbol").html());
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
      $("#product_status").val("Inactive");
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

});

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
          url: APP_URL+"/admin/products/add_option_photos/"+$("#product_id").val()+"/"+optionid+"/"+option_db_id+"/"+$("#update_type").val(),
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
            $('.js-delete-photo-confirm').addClass('hide');
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
  if(ext == 'png' || ext == 'jpg' || ext == 'jpeg' || ext == 'gif')
  {
    $("#image_upload_error").hide();
    $("#cloud_upload_error").hide();
    $('#js-photo-grid').append('<li class="col-lg-4 col-md-6 row-space-4"><div class=" photo-item"><div class=="photo-size photo-drag-target js-photo-link"></div></div></li>');
    var loading = '<div class="" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;z-index:9;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading whiteloading_img"></div></div></div></div></div>';
    $("#js-photo-grid li").last().append(loading);
    $('#add_product1').prop("disabled", true);
     if($("#update_type").val() == 'add_product'){
      var productid = $("#product_id").val();
      var product_id = $("#product_id").val();
    }else{
       var productid = $("#tmp_product_id").val();
        var product_id = $("#product_id").val();
    }
    jQuery.ajaxFileUpload({
          url: APP_URL+"/admin/products/add_photos/"+productid+'/'+product_id+'/'+$("#update_type").val(),
          secureuri: false,
          fileElementId: "add-product-imagevideo",
          dataType: "json",
          async: false,
          success: function(response){
            $('#add_product1').prop("disabled", false);            
            $('#add-image').removeClass('error');
                 // $scope.photos_list = response.data;
            $( "#js-photo-grid #js-manage-listing-content-container" ).remove();
            $('#js-photo-grid li:last-child').remove();
          $scope.steps_count = response.steps_count;

          if(response.error['error_title'])
          {
            if(response.error['error_title'] == 'Invalid Product Id'){
              window.location = APP_URL+'/admin/products';
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
              $scope.$apply();
            
              $('#add-product-imagevideo').reset();
          }
          
          }
    });
  }
  else
  {
$("#image_upload_error").show();
  }
});
}

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
      $('#add_product1').prop("disabled", true); 
      $('#add_product').prop("disabled", true); 
      $('#add-video-btn').prop("disabled", true); 
      
      jQuery.ajaxFileUpload({
        url: APP_URL+"/admin/product/add_video_mp4/"+$("#product_id").val()+'/'+$("#update_type").val(),
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
              window.location = APP_URL+'/admin/products';
            }else{
            $("#video_upload_popup .modal-body").removeClass("loading");
            $("#video_cloud_upload_error").html(response.error['error_description']);
            $("#video_cloud_upload_error").show();
            $('#js-error .panel-header').text(response.error['error_title']);
            $('#js-error .panel-body').text(response.error['error_description']);
            $('.js-delete-photo-confirm').addClass('hide');
            $('#js-error').attr('aria-hidden',false);
            $('#add_product1').prop("disabled", false); 
            $('#add_product').prop("disabled", false);
            $('#add-video-btn').prop("disabled", false); 
            }
          }
          $('#add-video').css('border','1px solid #fff','!important');
          if(response.succresult)
          {
            jQuery.ajaxFileUpload({
              url: APP_URL+"/admin/product/add_video_webm/"+$("#product_id").val()+'/'+$("#update_type").val(),
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
                    window.location = APP_URL+'/admin/products';
                  }else{
                  $("#video_upload_popup .modal-body").removeClass("loading");
                  $("#video_cloud_upload_error").html(response.error['error_description']);
                  $("#video_cloud_upload_error").show();
                  $('#js-error .panel-header').text(response.error['error_title']);
                  $('#js-error .panel-body').text(response.error['error_description']);
                  $('.js-delete-photo-confirm').addClass('hide');
                  $('#js-error').attr('aria-hidden',false);
                  $('#add_product1').prop("disabled", false); 
                  $('#add_product').prop("disabled", false);
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
                  $('#add_product1').prop("disabled", false); 
                  $('#add_product').prop("disabled", false);
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
                  },3000);
                  document.getElementById("add_product_video_mp4").value = '';
                  document.getElementById("add_product_video_webm").value = '';
                }
              }
            });
          }
        }
      });
    }
    else
    {
      $("#video_cloud_upload_error").html("The format is not valid");
      $("#video_cloud_upload_error").show();
    }
  });
  $(document).on("change", '#add_product_video_webm', function() {
    var ext =this.value.replace(/C:\\fakepath\\/i, '');
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
  });
  $(document).on("change", '#add_product_video_mp4', function() {
    var ext =this.value.replace(/C:\\fakepath\\/i, '');
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
      before_upload_status=1;
      ok_mp4=1;
      $("#video_upload_error_mp4").hide();
    }
  });
}
upload_video();
upload();
upload_option();

/* ajaxfileupload */
jQuery.extend({ handleError: function( s, xhr, status, e ) {if ( s.error ) s.error( xhr, status, e ); else if(xhr.responseText) console.log(xhr.responseText); } });
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
    $http.post(APP_URL+'/admin/products/delete_photo', { photo_id : $(this).attr('data-photo'),type:$("#update_type").val(),option:option_del,option_id:option_id,productid:$("#product_id").val() }).then(function(response) 
    {
      if(response.data.success == 'true')
      {


        if(option_del!="false" && option_del != 'video')
        {
          var del_img = $('#delete_product_id').val()+','+response.data.delete_img; 
        $('#delete_product_id').val(del_img);
           $scope.photos_option_list[option_index].splice(index,1);
           $('#js-error').attr('aria-hidden',true);
        }
        else if(option_del == 'video')
        {
          $('#delete_video_update').val(1);
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
            window.location = APP_URL+'/admin/products';
          }
        }
      }, function(response)
      {
      if(response.status=='300')
       window.location = APP_URL+'/admin/login';
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

//edit product
var product_id = $('#product_id').val();
if(document.location.href.indexOf('edit') > -1 ) {
  $scope.page_button="Update Product";
$scope.page_title="Edit product";
$http.get(APP_URL+'/admin/products/get_product/'+product_id).then(function(response) {
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
  


  //Pricing & details
  $scope.price = response.data.products_prices_details.price;
  $scope.retail_price = response.data.products_prices_details.retail_price;
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
      $scope.product_options[key].option_price=value.price;
      $scope.product_options[key].option_discount=value.discount;
      if ($scope.product_options[key].retail_price!="0" && $scope.product_options[key].retail_price!="" && $scope.product_options[key].retail_price!=null) 
      {
        $timeout(function () {
          $(".check_sale_option_"+key).prop("checked",true);
          $('.retail_'+key).show();
          $('.discount_'+key).show();
          if(value.sold_out=="Yes")
          {
            $("#marked_soldout_options_"+key).prop("checked",true);
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
    $scope.shipping = response.data.products_shipping;
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
    $scope.exchange_policy = response.data.exchange_policy;
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
  if(response.data.status=="Inactive"){ $scope.clickFunction("product_status_btn"); }
  if(response.data.sold_out=="Yes"){ $scope.clickFunction("soldout_status_btn");}
  if(response.data.cash_on_delivery=="Yes"){ $scope.clickFunction("cashdelivery_status_btn");}
  if(response.data.cash_on_store=="Yes"){ $scope.clickFunction("cashstore_status_btn");}
  // $scope.$apply();
  $timeout(function () {
    $('#product_status').val(response.data.status);
    $('#soldout_status').val(response.data.sold_out);
    $('#cashdelivery_status').val(response.data.cash_on_delivery);
    $('#cashdstore_status').val(response.data.cash_on_store);
    $scope.video_src = $sce.trustAsResourceUrl(response.data.video_src);
    $scope.canvas_image_src = response.data.video_thumb;
  });


});

}
else
{
  $scope.page_title="Add new product";
  $scope.page_button="Add Product";
}



}]);
$.validator.addMethod("greaterThan",function (value, element, param) {
  var $otherElement = $(param);
  return parseInt(value, 10) > parseInt($otherElement.val(), 10);
});
var v = $("#add_product_form").validate({
      onfocusout: false,
      onkeyup: false,
      rules: {
         "title": { 
            required: true,
             maxlength: 100,
           },
         "user_id": "required",
          "price" :{
          required: true,
          remote: {
            url: APP_URL+'/admin/products/check_price',
            type: "post",
            async: false,
            data: {
              currency: function() {
                return $("#input_default_currency").val();
              }
            },
            dataFilter: function(data) {
                var json = JSON.parse(data);
                if(json.status != "success") {
                    return "\"" + json.error + "\"";
                }
                else
                {
                  var jsonStr = JSON.stringify(true);
                  return jsonStr; 
                }
            }
          }
        },
        'retail_price':{
          required: true,
          greaterThan: "#price"
        },
        "total_quantity" : {required: true},
        "ships_from":{required: true},
        "ships_to[]" : {required: true},
        "product_option_qty[]":{required: true},
        "product_option_price[]":
        { 
          required: true,
          remote: {
            url: APP_URL+'/admin/products/check_option_price',
            type: "post",
            async: false,
            data: {
              currency: function() {
                return $("#input_default_currency").val();
              }
            },
            dataFilter: function(data) {
                var json = JSON.parse(data);
                if(json.status != "success") {
                    return "\"" + json.error + "\"";
                }
                else
                {
                  var jsonStr = JSON.stringify(true);
                  return jsonStr; 
                }
            }
          }
        },
        "custom_charge_domestic[]":{required: true},
        "option_price[]":{required: true},
        "quantity":{required: true},
        "return_exchange_policy_description" : {
          maxlength: 1500,
        },
        "expected_delivery_day_1[]":"required",        
        "expected_delivery_day_2[]":"required",

        "user_id": { required: true },
      },
      messages: {
              return_exchange_policy_description: "please enter less than 1500 characters",
              'retail_price':{
                greaterThan: "Retail Price should not be less than or equal to Price"
              },              
              "price": 
                { 
                  min : 'The Price must be at least '+$('#currency_symbol').html()+' '+$('#minimum_amount').val(),
                },
                "product_option_price[]": 
                {
                  min : 'The Price must be at least '+$('#currency_symbol').html()+' '+$('#minimum_amount').val(),
                },

          },

      errorElement: "span",
      errorClass: "text-danger",
    });

$(document).ready(function(){
    $('.frm').hide();
    $('.frm#sf1').show();
});
   

   function step(step)
   {
      $(".frm").hide();
      $("#sf"+step).show();
   }

   function next(step)
   {
    v.form()
      var err=0;
      $('.required_desc').hide();
      $('.required_img').hide();
      $('.required_category').hide();
      $('.required_shipsto').hide();
      $('.required_return').hide();
      $('.required_exchange').hide();

     if(step == 1){     
     
        var desc_text = $(tinyMCE.get('description').getBody()).text();
        var charCount = desc_text.length;
      if(charCount==0){           
        err=1;  
        $(".required_desc").html('This field is required.'); 
         $('.required_desc').show();
        }
        else if(charCount >=1500)
          { 
        err=1;  
        $(".required_desc").html('Only 1500 characters are allowed in description field'); }
         $('.required_desc').show();
      }

      if(step == 2) {
        if($("ul[id='js-photo-grid']").children().length <= 2)
        {
        err = 1;   
          $(".required_img").html('This field is required.'); 
          $('.required_img').show();
        }       
      }
      
      if(step == 4){
        if($('.shipping_details .has-options').children().length < 2)
        {          
          err = 1; 
          $(".shipping_details .has-options").css('border-color','#a92225','!important'); 
          $(".required_shipsto").html('This field is required.'); 
          $('.required_shipsto').show();
        }
        else{
           $(".shipping_details .has-options").css('border-color','#ccc','!important');
        }
      }
      if(step == 5){
        if( $('#return_policy').val() == '? number:1 ?')
        {
          err = 1; 
          $(".required_return").html('This field is required.'); 
          $('.required_return').show();
        }

        if( $("#use_exchange").prop('checked') == false){
          if($('#exchange_policy').val() =='? number:1 ?')
          {
            err = 1; 
            $(".required_exchange").html('This field is required.'); 
            $('.required_exchange').show();
          }
        }
      }

      if($("#update_type").val() =='add_product'){
        if(step == 6)
        {
           if($('#category_id').val()=='' || $('#drilldown').find('span').text()=='Select Category') {
            err = 1;  
            $('#drilldown').addClass('error');
              $(".required_category").html('This field is required.'); 
              $('.required_category').show();
            }
            else{
            $('#drilldown').removeClass('error');
            }
        }
      }
      else{
        if(step == 7)
        {
           if($('#category_id').val()=='' || $('#drilldown').find('span').text()=='Select Category') {
            err = 1;  
            $('#drilldown').addClass('error');
              $(".required_category").html('This field is required.'); 
              $('.required_category').show();
            }
            else{
            $('#drilldown').removeClass('error');
            }
        }
      }

      if(err != 1){
        if(v.form()){
          if(step==3)
          {
            setTimeout(function(){ 
              if(v.form()){
              $(".frm").hide();
              $("#sf"+(step+1)).show();
              }
            }, 1000);
          }
          else if(step != 8)
          {
            $(".frm").hide();
            $("#sf"+(step+1)).show();
          }
          else
          {
            if(v.form()){
              document.getElementById("add_product_form").submit();
            }
          }
        }

      }
      
   }

   function back(step)
   {
    $(".frm").hide();
    $("#sf"+(step-1)).show();
   }

   $("[name='submit']").click(function(){

       var err=0;
      $('.required_desc').hide();
      $('.required_img').hide();
      $('.required_category').hide();
      $('.required_shipsto').hide();
      $('.required_return').hide();
      $('.required_exchange').hide();
      v.form();
      var step = $(this).data('id');
      if(step == 1){      

        var desc_text = $(tinyMCE.get('description').getBody()).text();
        var charCount = desc_text.length;      
      if(charCount==0){           
        err=1;  
        $(".required_desc").html('This field is required.'); 
         $('.required_desc').show();
        }
        else if(charCount >=1500)
          { 
        err=1;  
        $(".required_desc").html('Only 1500 characters are allowed in description field'); }
         $('.required_desc').show();
      }

      if(step == 2) {
        if($("ul[id='js-photo-grid']").children().length <= 2)
        {
        err = 1;   
          $(".required_img").html('This field is required.'); 
          $('.required_img').show();
        }       
      }
      if(step == 5){
        if($('.shipping_details .has-options').children().length < 2)
        {          
          err = 1; 
          $(".shipping_details .has-options").css('border-color','#a92225','!important'); 
          $(".required_shipsto").html('This field is required.'); 
          $('.required_shipsto').show();
        }
        else{
           $(".shipping_details .has-options").css('border-color','#ccc','!important');
        }
      }
       if(step == 6){
        if( $('#return_policy').val() == '? number:1 ?')
        {
          err = 1; 
          $(".required_return").html('This field is required.'); 
          $('.required_return').show();
        }

        if($("#use_exchange").prop('checked') == false){
          if($('#exchange_policy').val() =='? number:1 ?')
          {
            err = 1; 
            $(".required_exchange").html('This field is required.'); 
            $('.required_exchange').show();
          }
        }
      }


      if(step == 7)
      {
         if($('#category_id').val()=='' || $('#drilldown').find('span').text()=='Select Category') {
          err = 1;  
          $('#drilldown').addClass('error');
            $(".required_category").html('This field is required.'); 
            $('.required_category').show();
          }
          else{
          $('#drilldown').removeClass('error');
          }
      }

      if(err != 1){
        if(v.form()){         
           $("#add_product_form").submit();
        }   
      }
      else{
        return false;
      }   
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