@extends('merchant_template') @section('main')
<div class="cls_dashmain">
	<div class="container container-pad cls_msettings">
		<div class="cls_allproduct pt-4">
			<div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
				<div>
					<ol class="breadcrumb">
						<li class="breadcrumb-item active">{{ trans('messages.header.settings') }}</li>
					</ol>
				</div>
			</div>
			<div class="csv d-flex flex-wrap p-0" ng-controller="payout_preferences" ng-cloak>
				@include('common.settings_subheader_logged')
				<div class="col-lg-9 col-12 nopad right-sett mt-3 mt-lg-0">
					<div class="cls_setting1" >
						<div class="content d-flex justify-content-between align-items-center flex-wrap border-bottom">
							<h2 class="csv_tit" style="border-bottom: none;">
								@lang('messages.merchant.payout_preferences')
							</h2>
							<div class="pr-3">
								<a href="{{ url('merchant/settings_paid/transfers') }}" class="btn btn-light">
									@lang('messages.merchant.view_payout_history')
								</a>
								<button class="btns-gray-embo btn-new btn-payment btn-dark btn flt-right">
									@lang('messages.merchant.add_payout_preferences')
								</button>
							</div>
						</div>
						<div class="no-data1 payout-option d-flex flex-wrap my-4">
							<div class="col-lg-6 mb-4  short-padding new_perfer payout_@{{item.id}}" ng-repeat="item in payout_details ">
								<div class="payout_main border  clearfix">
									<h2 class="csv_tit" ng-if="item.payout_method == 'Stripe'">
									<i class="icon-paypal" aria-hidden="true"></i>
									<span style="font-size: 13px;">
										@lang('messages.merchant.stripe')
									</span>
									</h2>
									<h2 class="csv_tit" ng-if="item.payout_method == 'PayPal'">
									<i class="icon-paypal" aria-hidden="true"></i>
									<span class="" style="font-size: 13px;">
										@lang('messages.merchant.payPal')
									</span>
									</h2>
									<div class="payout_body  nopad pay_address">
										<div class="d-flex justify-content-around align-items-center flex-wrap payment_met" ng-ts=@{{item.payout_method}}>
											<label class="my-2 col-lg-6 payout_sub" ng-if="item.payout_method == 'Stripe'">{{ trans('messages.merchant.account_id') }}</label>
											<label class="payout_sub my-2 col-lg-6" ng-if="item.payout_method == 'PayPal'">{{ trans('messages.merchant.paypal_email') }}</label>
											<div class=" col-lg-6" style="word-break: break-word">@{{ item.paypal_email }}</div>
										</div>
										<div class="d-flex justify-content-around align-items-center flex-wrap address-details">
											<label class="payout_sub col-lg-6">{{ trans('messages.merchant.billing_address') }}</label>
											<div class="col-lg-6">
												<p class="m-0"> @{{ item.address1 }} </p>
												<p class="m-0"> @{{ item.city }}, @{{ item.state }} </p>
												<p class="m-0"> @{{ item.country }} </p>
											</div>
										</div>
									</div>
									<span class="text-danger error error_@{{item.id}} ml-2" style="display: none;"> @lang('messages.merchant.default_payout_delete') </span>
									<div class="payout_action nopad but_mer py-4 text-right">
										<button class="btn" ng-click="default_pay(item.id)" ng-class="(item.default=='yes') ? 'btn-light btns-gray-embo default_pay' : 'btn-add btns-green-embo'" ng-disabled="item.default == 'yes'"> @lang('messages.merchant.default') </button>
										<button class="btn btn-light remove-btn" ng-click="remove_pay(item.id)" ng-hide="item.default == 'yes'"> @lang('messages.merchant.remove') </button>
									</div>
								</div>
							</div>
						</div>
						<div class="no-data text-center" style="display:none">
							<span class="icon" style="background-position: -150px -150px !important;width:128px; "></span>
							<p>{{ trans('messages.merchant.no_payout_preferences') }}</p>
						</div>
						<div class="loading" id="payout_preferences_loading" style="padding-top:100px;display:none"></div>
						<input type="hidden" id="choose_method" value="{{trans('messages.merchant.choose_method')}}">
					</div>
				</div>
				<div class="add-fancy-back payment-popup modal" id="payout_popup1" style="display: none">
					<div class="d-flex align-items-center flex-wrap cls_modal_height">
						<div class="modal-content">
							<div class="payment-content ">
								<div class="modal-header">
									<h5 class="modal-title"> @lang('messages.merchant.add_new_payout_preferences') </h5>
									<button class="ly-close close" title="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='payout_error' style="display:none">
									<ul>
										<li ng-repeat="item in error_fields">@{{ item }}</li>
									</ul>
								</div>
								<div class="file p-4">
									{!! Form::open(['url' => '', 'id' => 'payout_preferences_form']) !!}
										<div class="row">
											<div class="form-group col-lg-6">
												<label class="label"> @lang('messages.merchant.address') <span style="color:red">*</span></label>
												{!! Form::text('address1', '', ['id' => 'address_1','autocomplete'=>"billing address-line1" ,'class'=>'text for_clear form-control' ]) !!}
											</div>
											<div class="form-group col-lg-6">
												<label class="label"> @lang('messages.merchant.address_line') </label>
												{!! Form::text('address2', '', ['id' => 'address_2','class'=>'text for_clear form-control' ]) !!}
											</div>
										</div>
										<div class="row">
											<div class="form-group col-lg-6">
												<label class="label"> @lang('messages.merchant.country') <span style="color:red">*</span></label>
												{!! Form::select('country', $country, old('country') ? old('country') : $default_country, [ 'id' => 'select_country' ,'class' => 'for_clear select-boxes select-country form-control' ]) !!}
											</div>
											<div class="form-group col-lg-6">
												<label class="label"> @lang('messages.merchant.city') <span style="color:red">*</span></label>
												{!! Form::text('city','', ['id' => 'payout_city','class'=>'text for_clear form-control']) !!}
											</div>
										</div>
										<div class="row">
											<div class="form-group col-lg-6">
												<label class="label"> @lang('messages.merchant.state') <span style="color:red">*</span></label>
												{!! Form::text('state', '', ['id' => 'payout_state','class'=>'text for_clear form-control' ]) !!}
											</div>
											<div class="form-group col-lg-6">
												<label class="label"> @lang('messages.merchant.zip') <span style="color:red">*</span></label>
												{!! Form::text('postal_code', '', ['id' => 'payout_zip','class'=>'text for_clear form-control']) !!}
											</div>
										</div>
									{!! Form::close() !!}
								</div>
								<div class="modal-footer">
									<button class="btn-blue-fancy btn-add add_payout_form"> @lang('messages.merchant.next') </button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="add-fancy-back payment-popup2 modal" id="payout_popup2" style="display: none">
					<div class="d-flex align-items-center flex-wrap cls_modal_height">
						<div class="modal-content">
							<div class="payment-content ">
								<div class="modal-header">
									<h5 class="modal-title">{{ trans('messages.merchant.add_new_payout_preferences') }}</h5>
									<button class="ly-close close" title="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								
								<div id="popup2_flash-container" style="text-align: center"> </div>
								<div class='payout_error' style="display:none">
									<ul>
										<li ng-repeat="item in error_fields">@{{ item }}</li>
									</ul>
								</div>
								<div class="file p-4">
									<!-- <p>{{ trans('messages.merchant.payout_released_desc1') }}</p> -->
									<p><b>{{ trans('messages.merchant.payout_released_desc2') }}</b> {{ trans('messages.merchant.payout_released_desc3') }}</p>
									<form id="payout_preferences_form">
										{!! Form::token() !!}
										<input type="hidden" id="payout_info_payout2_address1" value="" name="address1">
										<input type="hidden" id="payout_info_payout2_address2" value="" name="address2">
										<input type="hidden" id="payout_info_payout2_city" value="" name="city">
										<input type="hidden" id="payout_info_payout2_country" value="" name="country">
										<input type="hidden" id="payout_info_payout2_state" value="" name="state">
										<input type="hidden" id="payout_info_payout2_zip" value="" name="postal_code">
										<table id="payout_method_descriptions" class="cls_mtable table table-striped">
											<thead>
												<tr>
													<th></th>
													<th>{{ trans('messages.merchant.payout_method') }}</th>
													<th>{{ trans('messages.merchant.processing_time') }}</th>
													<th>{{ trans('messages.merchant.additional_fees') }}</th>
													<th>{{ trans('messages.merchant.details') }}</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<input type="radio" value="PayPal" name="payout_method" id="payout2_method">
													</td>
													<td class="type">
														<label for="payout_method">PayPal</label>
													</td>
													<td>3-5 {{ trans('messages.merchant.business_days') }}</td>
													<td>{{ trans('messages.merchant.none') }}</td>
													<td>{{ trans('messages.merchant.business_day_processing') }}</td>
												</tr>
												<!--  <tr>
															<td>
																<input type="radio" value="Stripe" name="payout_method" id="payout2_method">
															</td>
															<td class="type"><label for="payout_method">Stripe</label></td>
															<td>5-7 {{ trans('messages.merchant.business_days') }}</td>
															<td>{{ trans('messages.merchant.none') }}</td>
															<td>{{ trans('messages.merchant.business_day_processing') }}</td>
												</tr> -->
											</tbody>
										</table>
									</form>
								</div>
								<div class="modal-footer">
									<!-- <button class="btns-gray btn-back cancel_">{{ trans('messages.merchant.cancel') }}</button> -->
									<button class="btn-blue-fancy btn-add add_payout_form2">{{ trans('messages.merchant.next') }}</button>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<div class="add-fancy-back payment-popup4 modal" id="payout_popup4" style="display: none">
					<div class="d-flex align-items-center flex-wrap cls_modal_height">
						<div class="modal-content">
							<div class="payment-content ">
								<div class="modal-header">
									<h5 class="modal-title">{{ trans('messages.merchant.add_new_payout_preferences') }}</h5>
									<button class="ly-close close" title="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								
								<div class='payout_error' style="display:none">
									<ul>
										<li ng-repeat="item in error_fields">@{{ item }}</li>
									</ul>
								</div>
								<form method="post" id="payout_stripe" action="{{ url('users/update_payout_preferences/'.Auth::user()->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
									{!! Form::token() !!}
									<div class="file p-4">
										<input type="hidden" id="payout_info_payout4_address1" value="" name="address1">
										<input type="hidden" id="payout_info_payout4_address2" value="" name="address2">
										<input type="hidden" id="payout_info_payout4_city" value="" name="city">
										<input type="hidden" id="payout_info_payout4_country" value="" name="country">
										<input type="hidden" id="payout_info_payout4_state" value="" name="state">
										<input type="hidden" id="payout_info_payout4_zip" value="" name="postal_code">
										<input type="hidden" id="payout4_method" value="" name="payout_method" ng-model="payout_method">
											<div ng-init="payout_country={{json_encode(old('country') ?: '')}};payout_currency={{json_encode(old('currency') ?: '')}};iban_supported_countries = {{json_encode($iban_supported_countries)}};branch_code_required={{json_encode($branch_code_required)}};country_currency={{json_encode($country_currency)}};change_currency();mandatory={{ json_encode($mandatory)}};old_currency='{{ old('currency') ? json_encode(old('currency')) : '' }}'">
												<div class="row">
													<div class="form-group col-lg-6">
														<label class="label">{{ trans('messages.account.country') }} <span style="color:red">*</span></label>
														{!! Form::select('country', $country_list, $default_country, ['autocomplete' => 'billing country', 'id' => 'payout_info_payout_country1','placeholder'=>'Select','ng-model'=>'payout_country','class' => 'form-control']) !!}
													</div>
													<div class="form-group col-lg-6">
														<label class="label">{{ trans('messages.account.currency') }} <span style="color:red">*</span></label>
														{!! Form::select('currency', $payout_currency, $default_currency, ['autocomplete' => 'billing currency', 'id' => 'payout_info_payout_currency','placeholder'=>'Select','class' => 'form-control']) !!}
													</div>
												</div>
												<span class="uoload_frm" ng-show="mandatory[payout_country][3]">
													<div class="flt-left pad-rit-10" style="width:100%">
														<p class="pay-p">@{{mandatory[payout_country][3]}}<span style="color:red">*</span></p>
														{!! Form::text('bank_name', '', ['id' => 'bank_name', 'class' => 'form-control']) !!}
														<p class="text-danger">{{$errors->first('bank_name')}}</p>
													</div>
												</span>
												<span class="uoload_frm" ng-show="mandatory[payout_country][4]">
													<div class="flt-left pad-rit-10" style="width:100%">
														<p class="pay-p">@{{mandatory[payout_country][4]}}<span style="color:red">*</span></p>
														{!! Form::text('branch_name', '', ['id' => 'branch_name', 'class' => 'form-control']) !!}
														<p class="text-danger">{{$errors->first('bank_name')}}</p>
													</div>
												</span>
												<span class="uoload_frm" ng-if="payout_country" ng-hide="iban_supported_countries.includes(payout_country)">
													<div class="flt-left pad-rit-10" style="width:100%">
														<p class="pay-p">@{{mandatory[payout_country][0] }}<span style="color:red">*</span></p>
														{!! Form::text('routing_number', @$payout_preference->routing_number, ['id' => 'routing_number', 'class' => 'form-control']) !!}
														<p class="text-danger">{{$errors->first('bank_name')}}</p>
													</div>
												</span>
												<span class="uoload_frm" ng-show="mandatory[payout_country][2]">
													<div class="flt-left pad-rit-10" style="width:100%">
														<p class="pay-p">@{{mandatory[payout_country][2]}}<span style="color:red">*</span></p>
														{!! Form::text('branch_code', '', ['id' => 'branch_code', 'class' => 'form-control','maxlength'=>'3']) !!}
														<p class="text-danger">{{$errors->first('branch_code')}}</p>
													</div>
												</span>
												<!-- Branch code -->
												<!-- Account Number -->
												<span class="uoload_frm" ng-if="payout_country">
													<div class="flt-left pad-rit-10" style="width:100%">
														<p class="pay-p" for="account_number" ng-hide="iban_supported_countries.includes(payout_country)"><span class="account_number_cls">@{{mandatory[payout_country][1]}}</span><span style="color:red">*</span></p>
														<p class="pay-p" for="account_number" ng-show="iban_supported_countries.includes(payout_country)">{{ trans('messages.account.iban_number') }}<span style="color:red">*</span></p>
														{!! Form::text('account_number', '', ['id' => 'account_number', 'class' => 'form-control']) !!}
														<p class="text-danger">{{$errors->first('account_number')}}</p>
													</div>
												</span>
												<!-- Account Number -->
												<!-- Account Holder name -->
												<span class="uoload_frm">
													<p class="pay-p" ng-if="payout_country == 'JP'" for="holder_name">@{{mandatory[payout_country][5]}}<span style="color:red">*</span></p>
													<p class="pay-p" ng-if="payout_country != 'JP'" for="holder_name">{{ trans('messages.account.holder_name') }}<span style="color:red">*</span></p>
													{!! Form::text('holder_name', '', ['id' => 'holder_name', 'class' => 'form-control']) !!}
													<p class="text-danger">{{$errors->first('holder_name')}}</p>
												</span>
												<!-- Account Holder name -->
												<!-- SSN Last 4 only for US -->
												<span class="uoload_frm" ng-show="payout_country == 'US'">
													<p class="pay-p" ng-if="payout_country == 'US'" for="ssn_last_4">{{ trans('messages.account.ssn_last_4') }}<span style="color:red">*</span></p>
													{!! Form::text('ssn_last_4', '', ['id' => 'ssn_last_4', 'class' => 'form-control','maxlength'=>'4']) !!}
													<p class="text-danger">{{$errors->first('ssn_last_4')}}</p>
												</span>
												<!-- SSN Last 4 only for US -->
												<!-- Phone number only for Japan -->
												<span class="uoload_frm" ng-show="payout_country == 'JP'">
													<p class="pay-p" for="phone_number" >{{ trans('messages.merchant.phone_no') }}<span style="color:red">*</span></p>
													{!! Form::text('phone_number', '', ['id' => 'phone_number', 'class' => 'form-control']) !!}
													<p class="text-danger">{{$errors->first('phone_number')}}</p>
												</span>
												<!-- Phone number only for Japan -->
												<input type="hidden" id="is_iban" name="is_iban" ng-value="iban_supported_countries.includes(payout_country) ? 'Yes' : 'No'">
												<input type="hidden" id="is_branch_code" name="is_branch_code" ng-value="branch_code_required.includes(payout_country) ? 'Yes' : 'No'">
												<!-- Gender only for Japan -->
												@if(!Auth::user()->gender)
												<span class="uoload_frm" ng-if="payout_country == 'JP'">
													<p class="pay-p" for="user_gender">
														{{ trans('messages.profile.gender') }}
													</p>
													{!! Form::select('gender', ['male' => trans('messages.profile.male'), 'female' => trans('messages.profile.female')], Auth::user()->gender, ['id' => 'user_gender', 'placeholder' => trans('messages.profile.gender'), 'class' => 'form-control','style'=>'min-width:140px;']) !!}
													<span class="text-danger">{{ $errors->first('gender') }}</span>
												</span>
												@endif
												<!-- Gender only for Japan -->
												<!-- Address Kanji Only for Japan -->
												<span class="uoload_frm" ng-class="(payout_country == 'JP'? 'jp_form row':'')">
													<div ng-if="payout_country == 'JP'" class="col-md-12 col-sm-12">
														<p class="pay-p"><b>Address Kanji:</b></p>
														<div>
															<p class="pay-p"  for="payout_info_payout_address2">{{ trans('messages.account.address') }} 1<span style="color:red">*</span></p>
															{!! Form::text('kanji_address1', '', ['id' => 'kanji_address1', 'class' => 'form-control']) !!}
															<p class="text-danger">{{$errors->first('kanji_address1')}}</p>
														</div>
														<div>
															<p class="pay-p" for="payout_info_payout_address2">Town<span style="color:red">*</span></p>
															{!! Form::text('kanji_address2', '', ['id' => 'kanji_address2', 'class' => 'form-control']) !!}
															<p class="text-danger">{{$errors->first('kanji_address2')}}</p>
														</div>
														<div>
															<p class="pay-p" for="payout_info_payout_city">{{ trans('messages.account.city') }} <span style="color:red">*</span></p>
															{!! Form::text('kanji_city', '', ['id' => 'kanji_city', 'class' => 'form-control']) !!}
															<p class="text-danger">{{$errors->first('kanji_city')}}</p>
														</div>
														<div>
															<p class="pay-p" for="payout_info_payout_state">{{ trans('messages.account.state') }} / {{ trans('messages.account.province') }}<span style="color:red">*</span></p>
															{!! Form::text('kanji_state', '', ['id' => 'kanji_state', 'class' => 'form-control']) !!}
															<p class="text-danger">{{$errors->first('kanji_state')}}</p>
														</div>
														<div>
															<p class="pay-p" for="payout_info_payout_zip">{{ trans('messages.account.postal_code') }} <span style="color:red">*</span></p>
															{!! Form::text('kanji_postal_code', '', ['id' => 'kanji_postal_code', 'class' => 'form-control']) !!}
															<p class="text-danger">{{$errors->first('kanji_postal_code')}}</p>
														</div>
													</div>
												</span>
												<!-- Address Kanji Only for Japan -->
												<!-- Legal document -->
												<span class="uoload_frm" id="legal_document">
													<p class="pay-p"  for="document">@lang('messages.account.legal_document') @lang('messages.account.legal_document_format')<span style="color:red">*</span></p>
													{!! Form::file('document', ['id' => 'document', 'class' => 'form-control',"accept"=>".jpg,.jpeg,.png"]) !!}
													<p class="text-danger">{{$errors->first('document')}}</p>
												</span>
												<!-- Legal document -->
												<input type="hidden" name="holder_type" value="individual" id="holder_type">
												<input type="hidden" name="stripe_token" id="stripe_token">
												<p class="text-danger col-sm-12" id="stripe_errors"></p>
											</div>
										</div>
										<div class="btns-area text-right">
											<!-- <button class="btns-gray btn-back cancel_">{{ trans('messages.merchant.cancel') }}</button> -->
											<!-- <button type="submit" class="btn-blue-fancy add_payout_form4">{{ trans('messages.merchant.save_payment') }}</button> -->
											<input type="submit" value="{{ trans('messages.account.submit') }}" id="modal-stripe-submit" class="btn-blue-fancy stripe_payout add_payout_form3">
										</div>
									</form>
									<button class="ly-close" title="Close"><i class="ic-del-black"></i></button>
								</div>
							</div>
						</div>
					</div>
					<div class="add-fancy-back payment-popup3 modal" id="payout_popup3" style="display: none">
						<div class="d-flex align-items-center flex-wrap cls_modal_height">
							<div class="modal-content">
								<div class="payment-content ">
									<div class="modal-header">
										<h5 class="modal-title">{{ trans('messages.merchant.add_new_payout_preferences') }}</h5>
										<button class="ly-close close" title="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div id="popup3_flash-container" style="text-align: center"> </div>
									<div class='payout_error' style="display:none">
										<ul>
											<li ng-repeat="item in error_fields">@{{ item }}</li>
										</ul>
									</div>
									<div class="file p-4">
											{!! Form::open(['url' => url('merchant/add_payout_preferences'), 'id' => 'payout_paypal', 'method' => 'POST']) !!}
											<input type="hidden" id="payout_info_payout3_address1" value="" name="address_1">
											<input type="hidden" id="payout_info_payout3_address2" value="" name="address_2">
											<input type="hidden" id="payout_info_payout3_city" value="" name="payout_city">
											<input type="hidden" id="payout_info_payout3_country" value="" name="country_code">
											<input type="hidden" id="payout_info_payout3_state" value="" name="payout_state">
											<input type="hidden" id="payout_info_payout3_zip" value="" name="payout_zip">
											<input type="hidden" id="payout3_method" value="" name="payout_method" ng-model="payout_method">
											<div class="form-group">
												<label class="label"> @lang('messages.merchant.paypal_email') <span style="color:red">*</span> </label>
												<input class="text form-control for_clear" name="paypal_email" value="" id="paypal_email_id"  placeholder="E.g. John@gmail.com" type="text">
											</div>
											<div class="form-group form-check">
												<input name="set_default" id="make_this_primary_addr" value="true"  type="checkbox" class="form-check-input">
												<label for="make_this_primary_addr" class="form-check-label"> @lang('messages.merchant.make_primary_payment') </label>
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<!-- <button class="btns-gray btn-back cancel_">{{ trans('messages.merchant.cancel') }}</button> -->
										<button type="submit" class="btn-blue-fancy add_payout_form3 btn-add">{{ trans('messages.merchant.save_payment') }}</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="blank_address" value="{{trans('messages.account.blank_address')}}">
	<input type="hidden" id="blank_city" value="{{trans('messages.account.blank_city')}}">
	<input type="hidden" id="blank_post" value="{{trans('messages.account.blank_post')}}">
	<input type="hidden" id="blank_country" value="{{trans('messages.account.blank_country')}}">
	<input type="hidden" id="choose_method" value="{{trans('messages.account.choose_method')}}">
	<input type="hidden" id="blank_holder_name" value="{{trans('messages.account.blank_holder_name')}}">
	<input type="hidden" name="stripe_publish_key" id="stripe_publish_key" value="{{@$stripe_data[1]->value}}"> @foreach($payouts as $row)
	<ul data-sticky="true" data-trigger="#payout-options-{{ $row->id }}" class="tooltip tooltip-top-left list-unstyled dropdown-menu" aria-hidden="true" role="tooltip">
		@if($row->default != 'yes')
		<li>
			<a rel="nofollow" data-method="post" class="link-reset menu-item" href="{{ url('/') }}/users/payout_delete/{{ $row->id }}">{{ trans('messages.account.remove') }}</a>
		</li>
		@endif
		<li>
			<a rel="nofollow" data-method="post" class="link-reset menu-item" href="{{ url('/') }}/users/payout_default/{{ $row->id }}">{{ trans('messages.account.set_default') }}</a>
		</li>
	</ul>
	@endforeach @stop @push('scripts')
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript">
	@if(count($errors) > 0)
	$('#payout_popup4').css("display", "block");
	@endif
	</script>
	@endpush