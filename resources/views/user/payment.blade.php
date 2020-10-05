@extends('settings_template')
@section('main')
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 pad-min min-size-long setting-rightbar" ng-controller="payout_preferences" ng-cloak>  
<div class="content">
<h2 class="ptit flt-left" style="margin:0px;"><b>Payment Methods</b></h2>
<button class="btns-gray-embo btn-new btn-payment flt-right" >Add Payment Method</button>
</div>

<div class="no-data1">

</div>
<div class="no-data" >
    <span class="icon" style="background-position: -150px -150px !important;width:128px;"></span>
    <p>You haven’t made any purchases yet.</p>
</div>
<div class="loading" style="padding-top:100px;display:none" ></div>
</div>
</main>
@stop

<div class="add-fancy-back payment-popup">
	<div class="d-flex align-items-center flex-wrap cls_modal_height">
		<div class="col-lg-4 col-md-7 col-sm-8 back-white pos-top mar-auto flt-none nopad col-xs-11 payment-content ">
		<h2 class="fancy-head-popup">Add new payment method</h2>
		<div class='payout_error' style="display:none">
			<ul>
		    <li ng-repeat="item in error_fields">@{{ item }}</li>
		    </ul>
		</div>
		<div class="file">
			<label style="padding: 5px 0px;">Payment Info</label>
				<span class="uoload_frm">
					<p class="pay-p">PayPal Email ID <span style="color:red">*</span></p>
					<input class="text" id="paypal_email_id" style="width: 100%;height: 34px;" placeholder="E.g. John@gmail.com"  type="text">
				</span>
				<span class="uoload_frm">
					<input name="set_default" id="make_this_primary_addr" value="true" type="checkbox" >
					<label for="make_this_primary_addr" style="display: inline-block !important;cursor: pointer !important;font-weight: normal !important;">Make this my primary payment method</label>
				</span>
				<label style="padding: 5px 0px;">Billing Address</label>
				<span class="uoload_frm">
				<div class="flt-left pad-rit-10" style="width:50%">
				<p class="pay-p">Address Line 1 <span style="color:red">*</span></p>
				<input class="text" id="address_1" style="width: 100%;height: 34px;"   type="text">
				</div>
				<div class="flt-left pad-rit-10" style="width:50%">
				<p class="pay-p">Address Line 2</p>
				<input class="text" id="address_2" style="width: 100%;height: 34px;"   type="text">
				</div>
				</span>
				<span class="uoload_frm">
				<div class="flt-left pad-rit-10" style="width:50%">
				<p class="pay-p">Country <span style="color:red">*</span></p>
		        <select name="country_code" class="select-boxes select-country" style="width:100%;" id="select_country">
		            <option value="">Select Country</option>
		            @foreach ($country as $key => $value)
		            <option value="{{$value['short_name']}}" {{ $value['long_name'] == $countryName ? 'selected' : ''}}>{{$value['long_name']}}</option>
		            @endforeach 
		        </select>
				</div>
				<div class="flt-left pad-rit-10" style="width:50%">
				<p class="pay-p">City</p>
				<input class="text" id="payout_city" style="width: 100%;height: 34px;"   type="text">
				</div>
				</span>
					<span class="uoload_frm">
				<div class="flt-left pad-rit-10" style="width:25%">
				<p class="pay-p">State <span style="color:red">*</span></p>
				<input class="text" id="payout_state" style="width: 100%;height: 34px;"   type="text">
				</div>
				<div class="flt-left pad-rit-10" style="width:25%">
				<p class="pay-p">Zip <span style="color:red">*</span></p>
				<input class="text"  id="payout_zip" style="width: 100%;height: 34px;"   type="text">
				</div>
				</span>
			</div>	
			<div class="btns-area text-right">
				<button class="btns-gray btn-back cancel_">Cancel</button>
				<button class="btn-blue-fancy" ng-click="add_payout()">Save Payment</button>
			</div>
			<button class="ly-close" title="Close"><i class="ic-del-black"></i></button>
		</div>
	</div>
</div>
