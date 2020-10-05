<div id="merchant-header" class="merchant_header" ng-controller="login_signup">
	<div class="container">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 d-none d-lg-flex align-items-center">
			<div class="col-lg-7 col-sm-12 col-xs-8 col-md-8 d-flex justify-content-between align-items-center flex-wrap">
				
				<ul class="gnb cls_marchentmenu d-flex align-items-center">
					<h1>
						<a href="{{ url('merchant/dashboard') }}">
						<span class="merchantlogo d-flex align-items-center" >
						<img src='{{ $favicon }}' style="width:16px; height: 16px;">
					<span class="chepter">{{ trans('messages.merchant.merchant') }}</span>
				</span>
				</a>
				</h1>
					<li class="dashboard">
						<a href="{{ url('merchant/dashboard') }}" aria-selected="{{(Route::current()->uri() == 'merchant/dashboard') ? 'true' : 'false'}}" class="current cls_amenu">{{ trans('messages.header.dashboard') }}</a>
				</li>
					<li class="products">
						<a href="{{ url('merchant/all_products') }}" aria-selected="{{(Route::current()->uri() == 'merchant/all_products') ? 'true' : 'false'}}" class="current cls_amenu">{{ trans('messages.header.products') }}</a>
					<small>
					
					<a href="{{ url('merchant/all_products') }}"  aria-selected="{{(Route::current()->uri() == 'merchant/all_products') ? 'true' : 'false'}}" class="current-inn">{{ trans('messages.header.all_products') }}</a>
					
					<a  href="{{ url('merchant/add_product') }}"  aria-selected="{{(Route::current()->uri() == 'merchant/add_product') ? 'true' : 'false'}}" class="current-inn">{{ trans('messages.header.add_product') }}</a>
					
					</small>
				</li>
				<li class="orders">
					<a aria-selected="{{(Route::current()->uri() == 'merchant/order') ? 'true' : 'false'}}" class="current cls_amenu" href="{{ url('merchant/order') }}">{{ trans('messages.header.orders') }}
					</a>
					<small>
					<a href="{{ url('merchant/order') }}"  aria-selected="{{(Route::current()->uri() == 'merchant/order') ? 'true' : 'false'}}" >{{ trans('messages.header.order_management') }}</a>
					<!-- <a href="{{ url('merchant/order_customer') }}"  aria-selected="{{(Route::current()->uri() == 'merchant/order_customer') ? 'true' : 'false'}}" >Customers</a> -->
					<a href="{{ url('merchant/order_return') }}"  aria-selected="{{(Route::current()->uri() == 'merchant/order_return') ? 'true' : 'false'}}" >{{ trans('messages.header.return_requests') }}</a>
					</small>
				</li>
				<li class="insights">
					<a href="{{ url('merchant/insights') }}" class="cls_amenu">{{ trans('messages.header.insights') }}</a>
			</li>
				<!--<li class="promote">
				<a href="#">Promote</a>
										<small>
											<a href="#">Coupons</a>
				<a href="#" target="_blank">Fancy Anywhere</a>
										</small>
								</li>
								<li class="campaigns">
								<a href="#">Campaigns</a>
									<small>
										<a href="#">Your Campaigns</a>
										<a href="#">Create Campaign</a>
									</small>
		</li>-->
		<li class="settings">
			<a href="{{ url('merchant/settings') }}" class="cls_amenu">{{ trans('messages.header.settings') }}</a>
		<small>
		<a href="{{ url('merchant/settings') }}"  aria-selected="{{(Route::current()->uri() == 'merchant/settings') ? 'true' : 'false'}}">{{ trans('messages.header.basics') }}</a>
		<a href="{{ url('merchant/settings_general') }}"  aria-selected="{{(Route::current()->uri() == 'merchant/settings_general') ? 'true' : 'false'}}">{{ trans('messages.header.brand_image') }}</a>
		<a href="{{ url('merchant/settings_paid') }}"  aria-selected="{{(Route::current()->uri() == 'merchant/settings_paid') ? 'true' : 'false'}}">{{ trans('messages.header.getting_paid') }}</a>
		</small>
	</li>
	<!--<li class="faq">
	<a href="#" target="_blank">FAQ</a>
</li>-->
</ul>
</div>
<div class="col-lg-5 col-md-4 col-sm-3 col-xs-12 cls_mrightmenu">
<ul class="d-flex align-items-center justify-content-end cls_marchentmenu">
	<li>
		<a href="{{ url('messages') }}" class="cls_amenu cls_mminbox" style="position:relative">
			@if(Auth::id())
			<span class="inbox_count {{ (@Auth::user()->inbox_count()) ? '' : 'd-none' }}">{{ @Auth::user()->inbox_count() }}</span>
			@endif
		</a>
	</li>
	<li>
		<a href="javascript:;" class="show_activity cls_amenu cls_mmactivity">
		</a>
		<div class="msg-popup2">
			<div class="popular-head">
				<h3> @lang('messages.header.store_activity')</h3>
			</div>
			<div class="activity-content">
				<div class="msg-content bor-top" infinite-scroll='get_merchant_header()' data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" infinite-scroll-distance='1' ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}}" infinite-scroll-disabled='merchant_notification_busy'>
					<div class="activity_empty" style="display: block">
						<p>
							<a href="#" class="dash-icon dash-arrow" id="dash-arrow-ash" style="border-radius:100%;width:100%">
							</a>
						</p>
						<p>
							<b> @lang('messages.header.no_store_activity') </b>
					</p>
						<p> @lang('messages.header.no_store_activity_desc') </p>
					</div>
					<ul class="msg-list" ng-init="user_from={{ Auth::id() }}" ng-cloak>
						<li style="width:100%; " ng-repeat="all in merchant_activity">
							<span ng-if="all.notification_type != 'featured'" class="d-flex justify-content-start align-items-center flex-wrap">
								<a href="{{ url('profile') }}/@{{all.users.user_name}}" class="flt-left">
									<img ng-if="all.notification_type_status!='refund' && all.notification_type_status!='payout'" ng-src="@{{ all.users.original_image_name }}" width="40px" height="40px" class="img-fluid">
									<img ng-if="all.notification_type_status=='refund' || all.notification_type_status=='payout'" src="{{ url('/') }}/admin_assets/dist/img/avatar04.png" width="30px" height="30px" class="flt-left img-round">
								</a>
								<div class="flt-left d-flex align-items-center">
									<a href="{{ url('profile') }}/@{{all.users.user_name}}" class="flt-left text-truncate">@{{all.users.full_name}} </a>
									<p class="flt-left text-truncate" style="">@{{all.trans_message}}</p>
									<span ng-if="all.notification_type=='order' && all.notification_type_status!='refund' && all.notification_type_status!='payout'">
										<a ng-if="all.products.user_id==user_from"  href="{{ url('merchant/order') }}/@{{all.order_id}}" class="flt-left text-truncate">#@{{all.order_id}}</a>
										<a ng-if="all.products.user_id!=user_from" href="{{ url('purchases') }}/@{{all.order_id}}" class="flt-left text-truncate">#@{{all.order_id}}</a>
									</span>
									<span ng-if="all.notification_type=='like_product' || all.notification_type=='wishlist' ">
										<a href="{{ url('things') }}/@{{all.product_id}}" class="flt-left text-truncate">#@{{all.products.title}}</a>
									</span>
									<span ng-if="all.notification_type_status=='refund'">
										<a href="{{ url('purchases') }}/@{{all.order_id}}" class="text-truncate">#@{{all.order_id}}</a>
									</span>
								</div>
							</span>
							<span ng-if="all.notification_type == 'featured'" class="d-flex justify-content-start align-items-center flex-wrap">
								<a href="{{ url('things') }}/@{{all.product_id}}" class="flt-left">
									<img src="{{url('image/new-navigation.png')}}" width="40px" height="40px" class="flt-left img-round">
								</a>
								<div class="flt-left msg-body2 d-flex align-items-center flex-wrap">
									<p class="flt-left text-truncate">@{{all.trans_message}}</p>
									<a href="{{ url('things') }}/@{{all.product_id}}" class="flt-left text-truncate">#@{{all.products.title}}</a>
								</div>
							</span>
						</li>
					</ul>
					<div class="whiteloading activity_loading_header" style="display: none">
					</div>
				</div>
				<a href="{{ url('activity') }}" class="register-btn col-lg-12 col-md-12 col-sm-12 back-white mar-0" style="text-transform:capitalize;font-size:14px !important">{{ trans('messages.header.all_activities') }}</a>
			</div>
		</div>
	</li>
	@if(Auth::id() && Auth::user()->original_detail()->type=="merchant")
	<li class="marchuser">
		<a style="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="{{ url('merchant/dashboard') }}" class="dash-icon dash-merchant cls_amenu cls_mmstore">
			<span class="bold text-truncate" title="{{ @Auth::user()->store_detail()->store_name }}"> {{ @Auth::user()->store_detail()->getStoreNameWithLimit(20) }} </span>
	</a>
	<div class="cls_marchentdrop dropdown-menu" aria-labelledby="dropdownMenuButton">
		<ul class="msg-profile">
			<li>
				<a href="{{ url('store') }}/{{ @Auth::user()->store_detail()->id }}" class="d-flex justify-content-between align-items-center flex-wrap">
					<img src="{{ @Auth::user()->store_logo() }}" width="40px" height="40px" style="border-radius:2px;" class="flt-left">
					<div class="" style="width: 100%;padding-left: 30px;">
						<p class="flt-left mar-0 bold text-truncate" style="padding:0px 10px" title="{{ @Auth::user()->store_detail()->store_name }}">{{ @Auth::user()->store_detail()->store_name }}</p>
						<p class="flt-left mar-0"  style="padding:0px 10px" title="{{ trans('messages.header.view_your_store') }}"> {{ trans('messages.header.view_your_store') }}</p>
					</div>
				</a>
			</li>
			<ul class="msg-profile-inner flt-left bor-bot-ash">
				<li>
					<a href="{{ url('merchant/dashboard') }}">{{ trans('messages.header.dashboard') }}</a>
			</li>
				<li>
					<a href="{{ url('merchant/order') }}">{{ trans('messages.header.orders') }}</a>
			</li>
				<li>
					<a href="{{ url('merchant/all_products') }}">{{ trans('messages.header.products') }}</a>
			</li>
				<!--<li>
				<a href="#">Campaigns</a>
			</li>-->
			</ul>
			<ul class="msg-profile-inner flt-left">
				<!--<li>
				<a href="#">Download Seller App</a>
				</li>
				<li>
				<a href="#">Help Center</a>
			</li>-->
				<li>
					<a href="{{ url('merchant/settings') }}">{{ trans('messages.header.store_settings') }}</a>
			</li>
				<li>
					<a href="{{ url('/')}}">{{ trans('messages.header.back_to') }} {{ $site_name }}</a>
			</li>
				<li>
					<a href="{{ url('logout') }}">{{ trans('messages.header.logout') }}</a>
			</li>
			</ul>
		</ul>
	</div>
</li>
@endif
</ul>
</div>
</div>
<div class="show-sm d-lg-none d-block">
<div class="d-flex align-items-center">
<div class="cls_leftmenu col-6 p-0">
<i class="nav-bar icon-menu">
</i>
<div class="d-flex align-items-center flex-wrap">
	
	
	<h1 >
		<a href="{{ url('merchant/dashboard') }}">
		<img src='{{ url("image/logo_blu_tra.png")}}'> Merchant
	</a>
	</h1>
	
</div>
</div>
<div class="cls_mrightmenu col-6 p-0">
<ul class="d-flex align-items-center justify-content-end cls_marchentmenu">
	<li>
		<a href="{{ url('messages') }}" class="cls_amenu cls_mminbox" style="position:relative">
			@if(Auth::id())
			<span class="inbox_count {{ (@Auth::user()->inbox_count()) ? '' : 'd-none' }}">{{ @Auth::user()->inbox_count() }}</span>
			@endif
		</a>
	</li>
	<li>
		<a href="{{ url('activity') }}" class="cls_amenu cls_mmactivity">
		</a>
	</li>
	@if(Auth::id() && Auth::user()->original_detail()->type=="merchant")
	<li class="marchuser">
		<a style="" href="{{ url('merchant/dashboard') }}" class="cls_amenu cls_mmstore">
	</a>
</li>
@endif
</ul>
</div>
</div>
<div class="show-list merchant_show">
<div class="cls_reslmenu pad-tab nt_mobtab">
<ul class="gnb">
<li>
	<a href="{{ url('store') }}/{{ @Auth::user()->store_detail()->id }}" class="d-flex justify-content-start align-items-center flex-wrap">
		<img src="{{ @Auth::user()->store_logo() }}" width="40px" height="40px" style="border-radius:2px;" class="flt-left">
		<div class="">
			<p class="m-0 bold" style="padding:0px 10px">{{ @Auth::user()->store_detail()->store_name }}</p>
			<p class="m-0"  style="padding:0px 10px"> {{ trans('messages.header.view_your_store') }}</p>
		</div>
	</a>
</li>
<li class="dashboard">
	<a href="{{ url('merchant/dashboard') }}" class="current">{{ trans('messages.header.dashboard') }}</a>
</li>
<li class="products nt_order nt_order1">
	<a href="javascript:;">{{ trans('messages.header.products') }}</a>
<div class="nt_merchant nt_merchant1">
	<a href="{{ url('merchant/all_products') }}">{{ trans('messages.header.all_products') }}</a>
	<a href="{{ url('merchant/add_product') }}">{{ trans('messages.header.add_product') }}</a>
</li>
<li class="orders nt_order nt_order2">
	<a href="javascript:;">	{{ trans('messages.header.orders') }}</a>
<div class="nt_merchant nt_merchant2">
	<a href="{{ url('merchant/order') }}">{{ trans('messages.header.order_management') }}</a>
	<a href="{{ url('merchant/order_return') }}">{{ trans('messages.header.return_requests') }}</a>
</div>
</li>
<li class="insights">
	<a href="{{ url('merchant/insights') }}">{{ trans('messages.header.insights') }}</a>
</li>
<li class="settings nt_order nt_order3">
	<a href="javascript:;">{{ trans('messages.header.settings') }}</a>
<div class="nt_merchant nt_merchant3">
<a href="{{ url('merchant/settings') }}">{{ trans('messages.header.basics') }}</a>
<a href="{{ url('merchant/settings_general') }}">{{ trans('messages.header.brand_image') }}</a>
<a href="{{ url('merchant/settings_paid') }}">{{ trans('messages.header.getting_paid') }}</a>
</div>
</li>
<li>
	<a href="{{ url('/')}}">{{ trans('messages.header.back_to') }} {{ $site_name }}</a>
</li>
<li>
	<a href="{{ url('logout') }}">{{ trans('messages.header.logout') }}</a>
</li>
</ul>
</div>

</div>
</div>
</div>
</div>
@if(Session::has('message') &&  Route::current()->uri() != 'merchant/dashboard')
<div class="flash-container newflash" style="margin-top:0px;">
<div class="flash-container1">
<div class="alert {{ Session::get('alert-class') }} success_msg" role="alert">
<a href="#" class="alert-close" data-dismiss="alert">
</a>
{{ Session::get('message') }}
</div>
</div>
</div>
@endif