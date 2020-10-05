<div role="main" ng-controller="login_signup">
	<header>
		<div class="">
			<div class="header-wrap d-lg-flex d-none align-items-center justify-content-between">
				<div class="logo">
					<a href="{{ url('/') }}">
						<img src="{{ $logo }}">
					</a>
				</div>
				<div class="search-field">
					<form action="{{ url('search') }}"  onSubmit="return searching()">
						<fieldset ng-init="search_key='{{isset($search_key)?$search_key:''}}'">
							<input class="head-search ui-autocomplete-input" autocomplete="off" id="search" type="text" name="search_key" ng-model='search_key' ng-change="ajax_search()" value="{{@$search_key}}" placeholder="{{ trans('messages.header.search') }} {{ $site_name }}"/>
							<i class="icon icon-search" aria-hidden="true"></i>
						</fieldset>
					</form>
					<div class="result pb-3">
						<ul class="keywords">
							<li ng-repeat="search_things in searchthings">
								<a href="{{ url('things') }}/@{{ search_things.id }}">
									<span class="pro-name">@{{ search_things.title }}</span>
								</a>
							</li>
							<hr>
						</ul>
						
						<div class="user-lists cls_search_in" id="user-lists">
							<h4>
							<a href="{{url('/search')}}?search_key=@{{key}}&amp;search_for=people">
								{{ trans('messages.header.people') }}
							</a>
							</h4>
							<ul class="user-list">
								<li ng-repeat="search_users in searchusers" class="pb-2" ng-cloak>
									<a href="{{url('profile')}}/@{{search_users.username}}" class="d-flex align-items-center">
										<span class="pro-logo">
											<span ng-if="search_users.src">
												<img ng-src="@{{ search_users.src }}">
											</span>
											<span ng-if="!search_users.src">
												<img src="{{ url('image/profile.png') }}">
											</span>
										</span>
										<span class="pro-name text-truncate">
											@{{search_users.full_name}}
											<br>
											<small>
											(@{{search_users.username}})
											</small>
										</span>
									</a>
								</li>
							</ul>
							<hr>
						</div>
						<div class="stores-lists cls_search_in" id="stores-lists">
							<h4>
							<a href="{{url('/search')}}?search_key=@{{key}}&amp;search_for=brands">
								{{ trans('messages.header.stores') }}
							</a>
							</h4>
							<ul class="store-list">
								<li ng-repeat="search_brands in searchbrands" class="pb-2" ng-cloak>
									<a class="search-lists d-flex align-items-center" href="{{ url('store') }}/@{{search_brands.id}}">
										<span class="pro-logo">
											<span ng-if="search_brands.logo_img">
												<img ng-src="@{{ search_brands.logo_img }}">
											</span>
											<span ng-if="!search_brands.logo_img">
												<img src="{{ url('image/profile.png') }}">
											</span>
										</span>
										<span class="pro-name">
											@{{search_brands.store_name}}
											<br>
											<small>
											@{{search_brands.city}},@{{search_brands.country}}
											</small>
										</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="main-menu">
					<nav class="navbar navbar-expand-lg p-0">
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-navbar" aria-controls="menu-navbar" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse" id="menu-navbar">
							<ul class="navbar-nav cls_mainright">
								<li class="nav-item {{ (Auth::guard('web')->check()) ? 'd-none' : '' }}">
									<a href="javascript:void(0)" class="nav-link signup_popup_head text-truncate cls_joinsign" data-toggle="modal" data-target="#signupmodel" style="width: 130px;" title="{{ trans('messages.header.join') }} {{$site_name }}">
										{{ trans('messages.header.join') }} {{$site_name }}
									</a>
								</li>
								<li class="nav-item {{ (Auth::guard('web')->check()) ? 'd-none' : '' }}">
									<!-- <a href="javascript:void(0)" class="nav-link login_popup_head">
											{{ trans('messages.header.login') }}
									</a> -->
									<a href="javascript:void(0)" class="nav-link cls_joinlogin login_popup_head" data-toggle="modal" data-target="#loginmodal">{{ trans('messages.header.login') }}</a>
								</li>
								<li class="nav-item cls_upcount {{ (Auth::guard('web')->check() && @Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
									<a href="{{ url('cart') }}" class="nav-link cls_cart">
										@if(@Auth::user()->cart_count !=0)
										<span class="cart_count">
											{{@Auth::user()->cart_count}}
										</span>
										@endif
									</a>
								</li>
								<li class="nav-item cls_upcount {{ (Auth::guard('web')->check() && @Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
									<a class="nav-link cls_inbox" href="{{ url('messages') }}">
										@if(Auth::check())
										<span class="inbox_count cart_count {{ (@Auth::user()->inbox_count()) ? '' : 'd-none' }}">
											{{ @Auth::user()->inbox_count() }}
										</span>
										@endif
									</a>
								</li>
								<li class="nav-item show_activity {{ (Auth::guard('web')->check() && @Auth::user()->status != 'Inactive' ) ? '' : 'd-none' }}">
									<a href="javascript:void(0);" class="nav-link cls_activity"></a>
									<div class="cls_activitydrop msg-popup2" style="display: none;">
										<h4 class="d-flex align-items-center justify-content-between cls_taptile">
										<a href="javascript:void(0);" class="current activity" tab="activity">
											Activity
										</a>
										<a href="javascript:void(0);" tab="notifications" class="activity-you">
											You
										</a>
										</h4>
										<div class="cls_activityscroll">
											<div class="activity-content">
												<div class="msg-content" infinite-scroll='get_activity_header()' data_user="{{ (!Auth::id()) ? '0' : Auth::id()}}" infinite-scroll-distance='1' ng-init="current_userid={{ (!Auth::id()) ? '0' : Auth::id()}}" infinite-scroll-disabled='header_activity_busy'>
													<div class="whiteloading activity_loading_header"></div>
													<div class="activity_empty">
														<p>
															<a href="javascript:void(0)" class="dash-icon dash-arrow" id="dash-arrow-ash"></a>
														</p>
														<h4>
														{{ trans('messages.header.no_store_activity') }}
														</h4>
														<p>
															{{ trans('messages.header.no_store_activity_desc') }}
														</p>
													</div>
													<ul class="msg-list" ng-init="user_from={{ (Auth::id())? Auth::id() : '0' }}" ng-cloak>
														<li ng-repeat="all in header_activity" class="msg_list_li">
															<span ng-repeat="(act_type,act_value) in all" class="actvi childact">
																<ul ng-if="act_type == 'store'">
																	<li ng-repeat="(store_type,store_data) in act_value">
																		<div ng-if="store_type=='add_product'">
																			<span ng-repeat="(store_key,store_value) in store_data" ng-if="current_userid != store_value[0].source_store.user_id" class="d-flex align-items-center justify-content-start flex-wrap msg_list_lifirst">
																				<a href="{{url('store')}}/@{{store_value[0].source_store.id}}" class="col-md-2">
																					<span ng-if="store_value[0].source_store.logo_img">
																						<img ng-src="@{{ store_value[0].source_store.logo_img }}" height="30px" width="30px">
																					</span>
																					<span ng-if="!store_value[0].source_store.logo_img">
																						<img src="{{ url('image/cover_image.jpg') }}" height="30px" width="30px">
																					</span>
																				</a>
																				<div class="msg-body2 cls_body2 actmesg1 col-md-10 d-flex justify-content-start align-items-center flex-wrap">
																					<a href="{{url('store')}}/@{{store_value[0].source_store.id}}">
																						@{{store_value[0].source_store.store_name}}
																					</a>
																					<p class="w-100">
																						@lang('messages.home.added')
																						<span ng-if="store_value.length > 1">
																							<a href="{{url('store')}}/@{{store_value[0].source_store.id}}">@{{store_value.length}} {{ trans('messages.home.new_products') }}
																							</a>
																						</span>
																						<span ng-if="store_value.length <= 1">
																							{{ trans('messages.home.a') }} {{ trans('messages.products.product') }}
																						</span>
																					</p>
																					<span class="noti-wrap2">
																						<span class="items">
																							<a href="{{url('things')}}/@{{store_product.target_product.id}}" class="rm-link activity_user_@{{store_product.source_id}}" ng-class="$index== 0 ? 'active ' : ''" data-group_id="@{{store_product.date}}" data-activity_user = "@{{store_product.source_id}}" data-activity_id="@{{store_product.id}}" data-target_id="@{{store_product.target_product.id}}" ng-repeat ="store_product in store_value | limitTo:4">
																								<img ng-src="@{{ store_product.target_product.image_name }}" onerror="this.src='{{ $no_product_url }}';" height="48px" width="48px">
																							</a>
																						</span>
																					</span>
																				</div>
																			</span>
																		</div>
																	</li>
																</ul>
																<span ng-if="act_type == 'user'">
																	<span ng-repeat="(user_type,user_data) in act_value">
																		<ul ng-if="user_type=='like_product'">
																			<li ng-repeat="(like_key,like_value) in user_data" class="d-flex justify-content-start align-items-center flex-wrap">
																				<a href="{{url('profile')}}/@{{like_value[0].source_user.user_name}}" class="col-md-2">
																					<span ng-if="like_value[0].source_user.original_image_name">
																						<img ng-src="@{{ like_value[0].source_user.original_image_name }}" height="30px" width="30px">
																					</span>
																					<span ng-if="!like_value[0].source_user.original_image_name">
																						<img src="{{ url('image/profile.png') }}" height="30px" width="30px">
																					</span>
																				</a>
																				<div class="msg-body2  cls_body2 dropactivity col-md-10 d-flex justify-content-start align-items-center flex-wrap">
																					<a href="{{url('profile')}}/@{{like_value[0].source_user.user_name}}">
																						@{{like_value[0].source_user.full_name}}
																					</a>
																					<div>
																						{{ trans('messages.home.liked') }}
																						<span ng-if="like_value.length > 1">
																							<a href="{{url('profile')}}/@{{like_value[0].source_user.user_name}}">
																								@{{like_value.length}} {{ trans('messages.home.items') }}
																							</a>
																						</span>
																						<span ng-if="like_value.length <= 1" class="likeproduct">
																							<a href="{{url('things')}}/@{{like_value[0].target_product.id}}">
																								@{{like_value[0].target_product.title}}
																							</a>
																						</span>
																					</div>
																					
																					
																					<span class="noti-wrap2">
																						<span class="items">
																							<a href="{{url('things')}}/@{{like_product.target_product.id}}" class="rm-link activity_user_@{{like_product.source_id}}" ng-class="$index== 0 ? 'active ' : ''" data-activity_user="@{{like_product.source_id}}" data-group_id="@{{like_product.date}}" data-activity_id="@{{like_product.id}}" data-target_id="@{{like_product.target_product.id}}" ng-repeat ="like_product in like_value | limitTo:4">
																								<img ng-src="@{{ like_product.target_product.image_name }}" onerror="this.src='{{ $no_product_url }}';" height="48px" width="48px">
																							</a>
																						</span>
																					</span>
																				</div>
																			</li>
																		</ul>
																		<div ng-if="user_type=='following_store'" class="msg_list_li">
																			<div ng-repeat="(follow_key,follow_value) in user_data" class="d-flex align-items-center justify-content-start flex-wrap">
																				<a href="{{url('profile')}}/@{{follow_value[0].source_user.user_name}}" class="col-md-2">
																					<span ng-if="follow_value[0].source_user.original_image_name">
																						<img ng-src="@{{ follow_value[0].source_user.original_image_name }}" height="30px" width="30px">
																					</span>
																					<span ng-if="!follow_value[0].source_user.original_image_name">
																						<img src="{{ url('image/profile.png') }}">
																					</span>
																				</a>
																				<div class="msg-body2 cls_body2 d-flex align-items-center justify-content-between flex-wrap col-md-10">
																					<a href="{{url('profile')}}/@{{follow_value[0].source_user.user_name}}">
																						@{{follow_value[0].source_user.full_name}}
																					</a>
																					<p style="margin: 0">
																						{{ trans('messages.home.started_following') }}
																						<span ng-if="follow_value.length > 1">
																							@{{follow_value.length}}
																						</span>
																						<span ng-if="follow_value.length <= 1">
																							{{ trans('messages.home.a') }}
																						</span>
																						{{ trans('messages.header.stores') }}
																					</p>
																				</div>
																			</div>
																		</div>
																		<span ng-if="user_type=='following_user'" class="msg_list_li">
																			<span ng-repeat="(follow_user_key,follow_user_value) in user_data" class="d-flex justify-content-start align-items-center flex-wrap">
																				<a href="{{url('profile')}}/@{{follow_user_value[0].source_user.user_name}}" class="col-md-2">
																					<span ng-if="follow_user_value[0].source_user.original_image_name">
																						<img ng-src="@{{ follow_user_value[0].source_user.original_image_name }}" height="30px" width="30px;">
																					</span>
																					<span ng-if="!follow_user_value[0].source_user.original_image_name">
																						<img src="{{ url('image/profile.png') }}" height="30px" width="30px">
																					</span>
																				</a>
																				<div class="msg-body2 cls_body2 dropactivity d-flex align-items-center justify-content-between flex-wrap col-md-10" >
																					<a href="{{url('profile')}}/@{{follow_user_value[0].source_user.user_name}}">
																						@{{follow_user_value[0].source_user.full_name}}
																					</a>
																					<p style="margin: 0">
																						{{ trans('messages.home.started_following') }}
																						<span ng-if="follow_user_value.length > 1">
																							@{{follow_user_value.length}} {{ trans('messages.header.people') }}
																						</span>
																						<span ng-if="follow_user_value.length <= 1">
																							<span ng-if="current_userid == follow_user_value[0].target_user.id">
																								{{ trans('messages.header.you') }}
																							</span>
																							<span ng-if="current_userid != follow_user_value[0].target_user.id">
																								<a href="{{url('profile')}}/@{{follow_user[0].target_user.user_name}}">
																									@{{follow_user_value[0].target_user.user_name}}
																								</a>
																							</span>
																						</span>
																					</p>
																				</div>
																			</span>
																		</span>
																	</span>
																</span>
															</span>
														</li>
													</ul>
												</div>
												<a href="{{ url('activity') }}" class="register-btn col-12">
													{{ trans('messages.header.all_activities') }}
												</a>
											</div>
											<div class="activity-content-you" style="display: none;">
												<div class="msg-content" infinite-scroll='get_notification_header()' infinite-scroll-distance='1' infinite-scroll-disabled='header_notification_busy' ng-init="count='0';user_from='{{ Auth::id() }}'" ng-cloak>
													<div class="notifation_empty activity_empty">
														<a href="#" class="dash-icon dash-arrow" id="dash-arrow-ash"></a>
														<h4>
														{{ trans('messages.header.no_notifications') }}
														</h4>
													</div>
													<ul class="msg-list" ng-init="user_from={{ (Auth::id())? Auth::id() : '0' }}" ng-cloak>
														<li ng-repeat="all in header_notification" class="msg_list_li">
															<span ng-if="all.notification_type != 'featured'" class="d-flex align-items-center justify-content-between flex-wrap">
																<a href="{{ url('profile') }}/@{{all.users.user_name}}" class="col-md-2">
																	<img ng-if="all.notification_type_status!='refund' && all.notification_type_status!='payout'" ng-src="@{{ all.users.original_image_name }}" height="30px" width="30px">
																	<img ng-if="all.notification_type_status=='refund' || all.notification_type_status=='payout'" src="{{ url('/') }}/admin_assets/dist/img/avatar04.png" height="30px" width="30px">
																</a>
<div class="msg-body2 cls_body2 d-flex align-items-center justify-content-between flex-wrap col-md-10">
<a href="{{ url('profile') }}/@{{all.users.user_name}}">
	@{{all.users.full_name}}</a><p>
	@{{all.notification_message}}</p>
																	<span ng-if="all.notification_type=='order' && all.notification_type_status!='refund' && all.notification_type_status!='payout'">
																		<a ng-if="all.products.user_id==user_from"  href="{{ url('merchant/order') }}/@{{all.order_id}}">#@{{all.order_id}}</a>
																		<a ng-if="all.products.user_id!=user_from" href="{{ url('purchases') }}/@{{all.order_id}}">#@{{all.order_id}}</a>
																	</span>
																	<span ng-if="all.notification_type=='like_product' || all.notification_type=='wishlist' ">
																		<a href="{{ url('things') }}/@{{all.product_id}}">#@{{all.products.title}}</a>
																	</span>
																	<span ng-if="all.notification_type_status=='refund'">
																		<a href="{{ url('purchases') }}/@{{all.order_id}}">#@{{all.order_id}}</a>
																	</span>
																</div>
															</span>
															<span ng-if="all.notification_type == 'featured'" class="d-flex align-items-center justify-content-between flex-wrap">
																<a href="{{ url('things') }}/@{{all.product_id}}" class="col-md-2">
																	<img src="{{url('image/new-navigation.png')}}" height="30px" width="30px">
																</a>
																<div class="msg-body2 cls_body2 d-flex align-items-center justify-content-between flex-wrap col-md-10">
																	<p>
																		@{{all.notification_message}}
																	</p>
																	<a href="{{ url('things') }}/@{{all.product_id}}">
																		#@{{all.products.title}}
																	</a>
																</div>
															</span>
														</li>
													</ul>
													<div class="whiteloading notification_loading_header"></div>
												</div>
												<a href="{{ url('notification') }}" class="register-btn">
													{{ trans('messages.header.all_notifications') }}
												</a>
											</div>
										</div>
									</div>
								</li>
								<li class="nav-item dropdown {{ (Auth::guard('web')->check() && Auth::guard('web')->user()->type == 'merchant' && @Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
									<a href="{{ url('merchant/dashboard') }}" class="nav-link cls_dash" id="store_detail" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
									<ul class="dropdown-menu cls_store_detail" aria-labelledby="store_detail">
										
										@if(Auth::check() && @Auth::user()->original_detail()->type=="merchant")
										<li class="cls_store_user">
											<a href="{{ url('store') }}/{{ @Auth::user()->store_detail()->id }}">
												<img src="{{ @Auth::user()->store_logo() }}" height="30px" width="30px">
												<div class="cls_store_detailin text-truncate">
													<p>
														{{ @Auth::user()->store_detail()->store_name }}
													</p>
													
													<p>
														{{ trans('messages.header.view_your_store') }}
													</p>
												</div>
											</a>
										</li>
										@endif
										<li>
											<a href="{{url('merchant/dashboard')}}">
												{{ trans('messages.header.dashboard') }}
											</a>
										</li>
										<li>
											<a href="{{ url('merchant/order') }}">
												{{ trans('messages.header.orders') }}
											</a>
										</li>
										<li>
											<a href="{{ url('merchant/all_products') }}">
												{{ trans('messages.header.products') }}
											</a>
										</li>
										<li>
											<a href="{{ url('merchant/settings') }}">
												{{ trans('messages.header.store_settings') }}
											</a>
										</li>
										
									</ul>
								</li>
								<li class="nav-item dropdown  {{ (Auth::guard('web')->check()) ? '' : 'd-none' }}">
									@if(Auth::check())
									<a href="javascript:void(0)" class="cls_user" id="youprofile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ Auth::user()->original_user_name }}">
										<img src="{{ Auth::user()->original_image_name }}">
										<span class="text-truncate cls_usename" >{{ Auth::user()->original_user_name }} </span>
									</a>
									@endif
									<ul class="dropdown-menu cls_store_detail" aria-labelledby="youprofile">
										@if(Auth::check())
										<li class="cls_store_user">
											<a href="{{ url('profile/').'/'.@Auth::user()->user_name }}">
												<img src="{{ Auth::user()->original_image_name }}" height="30px" width="30px;">
												<div class="cls_store_detailin text-truncate">
													<p class="net_name">
														{{ Auth::user()->original_full_name }}
													</p>
													<p class="net_name">
														{{ Auth::user()->original_user_name }}
													</p>
												</div>
											</a>
										</li>
										@endif
										
										<li class="{{ (@Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
											<a href="{{ url('purchases') }}">
												{{ trans('messages.header.orders') }}
											</a>
										</li>
										<li class="{{ (@Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
											<a href="{{ url('edit_profile') }}">
												{{ trans('messages.header.settings') }}
											</a>
										</li>
										<li>
											<a href="{{ url('logout') }}">
												{{ trans('messages.header.logout') }}
											</a>
										</li>
									</ul>
									<div class="add-fancy-div" style="display: none;">
										<a href="javascript:void(0)" class="fancy-head">Add</a>
										<ul class="inner-fancy-list">
											<li>
												<a href="#" class="fancy-site">
													Website
												</a>
											</li>
											<li>
												<a href="#" class="fancy-upload">
													Upload
												</a>
											</li>
											<li>
												<a href="#" class="fancy-email">
													Email
												</a>
											</li>
										</ul>
										<a href="#" class="register-btn">
											Install Bookmarklet
										</a>
									</div>
								</li>
								
								<li class="nav-item dropdown">
									<a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="trick full"></span>
										<span class='current_lang_name'>
											{{ Session::get('language_name')}}
										</span>
									</a>
									<div class="dropdown-menu">
										<ul id="language_header">
											@foreach($language as $lang)
											<li>
												<a href="javascript:void(0);" data-value="{{$lang->value}}" data-name='{{$lang->name}}' class="{{($lang->value== Session::get('language'))?'active': ''}}">
													{{$lang->name}}
												</a>
											</li>
											@endforeach
										</ul>
									</div>
								</li>
								<input type="hidden" class="prev_currency" value="{{Session::get('currency')}}">
								<li class="nav-item dropdown">
									<a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										{{ Session::get('currency') }}
										<span class="trick full"></span>
									</a>
									<div class="dropdown-menu">
										<ul id="currency_hearder">
											@foreach($currency as $cur)
											<li>
												<a href="javascript:void(0);" data-value="{{$cur->code}}" class="{{($cur->code== Session::get('currency'))?'active': ''}}">
													{{$cur->code}}
												</a>
											</li>
											@endforeach
										</ul>
									</div>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</div>
			<div class="mobile_menu d-lg-none">
				<div class="d-flex align-items-center">
					<div class="cls_leftmenu col-6 p-0 d-flex align-items-center flex-wrap">
						<i class="nav-bar icon-menu ml-3"></i>
						<div class="ml-2">
							
							<h1 ><a class="logo" href="{{ url('/') }}">
								<img src="{{ $mobile_logo }}"/>
							</a>
							</h1>
							
						</div>
					</div>
					<div class="cls_mrightmenu cls_mainright col-6 p-0">
						<ul class="d-flex align-items-center justify-content-end cls_marchentmenu">
							<li class="nav-item {{ (Auth::guard('web')->check()) ? 'd-none' : '' }}">
								<a href="javascript:void(0)" class="nav-link text-truncate signup_popup_head cls_joinsign_mbl" data-toggle="modal" data-target="#signupmodel" style="width: 100px;margin:0 3px !important;">
									{{ trans('messages.header.join') }} {{$site_name }}
								</a>
							</li>
							<li class="nav-item {{ (Auth::guard('web')->check()) ? 'd-none' : '' }}">
								<a href="javascript:void(0)" class="nav-link login_popup_head cls_joinlogin_mbl" data-toggle="modal" data-target="#loginmodal" style="margin:0 3px !important;">{{ trans('messages.header.login') }}</a>
							</li>
							<li class="nav-item cls_upcount {{ (Auth::guard('web')->check() && @Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
								<a href="{{ url('cart') }}" class="nav-link cls_cart">
									@if(@Auth::user()->cart_count !=0)
									<span class="cart_count">
										{{@Auth::user()->cart_count}}
									</span>
									@endif
								</a>
							</li>
							<li class="nav-item cls_upcount {{ (Auth::guard('web')->check() && @Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
								<a class="nav-link cls_inbox" href="{{ url('messages') }}">
									@if(Auth::check())
									<span class="inbox_count cart_count {{ (@Auth::user()->inbox_count()) ? '' : 'd-none' }}">
										{{ @Auth::user()->inbox_count() }}
									</span>
									@endif
								</a>
							</li>
							<li class="nav-item show_activity {{ (Auth::guard('web')->check() && @Auth::user()->status != 'Inactive' ) ? '' : 'd-none' }}">
								<a href="{{ url('activity') }}" class="nav-link cls_activity"></a>
							</li>
							
							<li class="nav-item {{ (Auth::guard('web')->check() && Auth::guard('web')->user()->type == 'merchant' && @Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
								<a href="javascript:void(0)" class="nav-link cls_dash cls_resdash" id="store_detail"></a>
								<ul class=" cls_store_detail cls_resdashul d-none">
									
									@if(@Auth::check() && @Auth::user()->original_detail()->type=="merchant")
									<li class="cls_store_user">
										<a href="{{ url('store') }}/{{ @Auth::user()->store_detail()->id }}">
											<img src="{{ @Auth::user()->store_logo() }}" height="30px" width="30px">
											<div class="cls_store_detailin text-truncate">
												<p>
													{{ @Auth::user()->store_detail()->store_name }}
												</p>
												
												<p>
													{{ trans('messages.header.view_your_store') }}
												</p>
											</div>
										</a>
									</li>
									@endif
									<li>
										<a href="{{url('merchant/dashboard')}}">
											{{ trans('messages.header.dashboard') }}
										</a>
									</li>
									<li>
										<a href="{{ url('merchant/order') }}">
											{{ trans('messages.header.orders') }}
										</a>
									</li>
									<li>
										<a href="{{ url('merchant/all_products') }}">
											{{ trans('messages.header.products') }}
										</a>
									</li>
									<li>
										<a href="{{ url('merchant/settings') }}">
											{{ trans('messages.header.store_settings') }}
										</a>
									</li>
									
								</ul>
							</li>
							
							<li class="nav-item {{ (Auth::guard('web')->check()) ? '' : 'd-none' }}">
								@if(Auth::check())
								<a href="javascript:void(0)" class="cls_user cls_resuser" id="youprofile"  title="{{ Auth::user()->original_user_name }}">
									<img src="{{ Auth::user()->original_image_name }}">
									<!-- <span class="trick full"></span> -->
								</a>
								@endif
								<ul class="d-none cls_store_detail cls_resuserul" aria-labelledby="youprofile">
									@if(Auth::check())
									<li class="cls_store_user">
										<a href="{{ url('profile/').'/'.@Auth::user()->user_name }}">
											<img src="{{ Auth::user()->original_image_name }}" height="30px" width="30px;">
											<div class="cls_store_detailin text-truncate">
												<p class="net_name">
													{{ Auth::user()->original_full_name }}
												</p>
												<p class="net_name">
													{{ Auth::user()->original_user_name }}
												</p>
											</div>
										</a>
									</li>
									@endif
									
									<li class="{{ (@Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
										<a href="{{ url('purchases') }}">
											{{ trans('messages.header.orders') }}
										</a>
									</li>
									<li class="{{ (@Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
										<a href="{{ url('edit_profile') }}">
											{{ trans('messages.header.settings') }}
										</a>
									</li>
									<li>
										<a href="{{ url('logout') }}">
											{{ trans('messages.header.logout') }}
										</a>
									</li>
								</ul>
								<div class="add-fancy-div" style="display: none;">
									<a href="javascript:void(0)" class="fancy-head">Add</a>
									<ul class="inner-fancy-list">
										<li>
											<a href="#" class="fancy-site">
												Website
											</a>
										</li>
										<li>
											<a href="#" class="fancy-upload">
												Upload
											</a>
										</li>
										<li>
											<a href="#" class="fancy-email">
												Email
											</a>
										</li>
									</ul>
									<a href="#" class="register-btn">
										Install Bookmarklet
									</a>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="show-list d-none">
					<div class="search_cnt">
						<form action="{{ url('search') }}">
							<fieldset>
								<i class="nav-bar icon-right-arrow" aria-hidden="true"></i>
								<input class="head-search ui-autocomplete-input" autocomplete="off" type="text" name="search_key" ng-model='search_key' ng-change="ajax_search()" value="{{@$search_key}}" placeholder="{{ trans('messages.header.search') }} {{ $site_name }}"/>
							</fieldset>
						</form>
						<div class="result mob_result">
							<ul class="keywords">
								<li ng-repeat="search_things in searchthings">
									<a href="{{ url('things') }}/@{{ search_things.id }}">
										<span class="pro-name">
											@{{ search_things.title }}
										</span>
									</a>
								</li>
							</ul>
							<div class="user-lists" id="user-lists" ng-if="searchusers.length">
								<h4>
								<a href="{{url('/search')}}?search_key=@{{key}}&amp;search_for=people">
									{{ trans('messages.header.people') }}
								</a>
								</h4>
								<ul class="user-list">
									<li ng-repeat="search_users in searchusers" ng-cloak>
										<a href="{{url('profile')}}/@{{search_users.username}}">
											<span class="pro-logo">
												<span ng-if="search_users.src">
													<img ng-src="@{{ search_users.src }}">
												</span>
												<span ng-if="!search_users.src">
													<img src="{{ url('image/profile.png') }}">
												</span>
											</span>
											<span class="pro-name">
												@{{search_users.full_name}}
												<small>
												(@{{search_users.username}})
												</small>
											</span>
										</a>
									</li>
								</ul>
							</div>
							<div class="stores-lists" id="stores-lists" ng-if="searchbrands.length">
								<h4>
								<a href="{{url('/search')}}?search_key=@{{key}}&amp;search_for=brands">
									{{ trans('messages.header.stores') }}
								</a>
								</h4>
								<ul class="store-list">
									<li ng-repeat="search_brands in searchbrands" ng-cloak>
										<a class="search-lists" href="{{ url('store') }}/@{{search_brands.id}}">
											<span class="pro-logo">
												<span ng-if="search_brands.logo_img">
													<img ng-src="@{{ search_brands.logo_img }}">
												</span>
												<span ng-if="!search_brands.logo_img">
													<img src="{{ url('image/profile.png') }}">
												</span>
											</span>
											<span class="pro-name">
												@{{search_brands.store_name}}
												<small>
												@{{search_brands.city}},@{{search_brands.country}}
												</small>
											</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="log_menu {{ (Auth::guard('web')->check()) ? 'd-none' : '' }}">
						<ul class="head-list">
							<li>
								<a href="#" class="signup_popup_head">
									Join {{$site_name }}
								</a>
							</li>
							<li>
								<a href="javascript:void(0)" class="login_popup_head" data-toggle="modal" data-target="#loginmodal">Log In</a>
								<!-- <a href="#" class="login_popup_head">
										Log In
								</a> -->
							</li>
						</ul>
					</div>
					<div class="{{ (Auth::guard('web')->check()) ? '' : 'd-none' }}">
						<ul>
							<li>
								<a href="{{ url('profile/').'/'.@Auth::user()->user_name }}">
									<span>
										<img src="{{ @Auth::user()->original_image_name }}">
									</span>
									<span>
										<p>
											{{ @Auth::user()->original_full_name }}
										</p>
										<p>
											View your profile
										</p>
									</span>
								</a>
							</li>
							<a href="{{url('user_settings')}}" class="setiing">
								<i class="icon icon-settings"></i>
							</a>
						</ul>
					</div>
					<div class="nav-sections log_menu {{ (Auth::guard('web')->check()) ? '' : 'd-none' }}">
						<div class="nav-wrap">
							<h5>
							YOU
							</h5>
							<ul>
								<li>
									<a href="{{ url('cart') }}" class="dash-shop cartshop">
										<span>Cart</span>
									</a>
								</li>
								<li>
									<a href="{{ url('messages') }}" class="dash-car cartshop dash-icon">
										<span>Inbox</span>
									</a>
								</li>
								<li>
									<a href="{{ url('activity') }}" class="dash-icon dash-arrow cartshop">
										<span>Activites</span>
									</a>
								</li>
								<li class="{{ (Auth::guard('web')->check() && Auth::guard('web')->user()->type == 'merchant' && @Auth::user()->status != 'Inactive') ? '' : 'd-none' }}">
									<a href="{{ url('merchant/dashboard') }}" class="dash-icon dash-merchant cartshop">
										<span>Merchant</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="nav-sections">
						<div class="nav-wrap">
							<h5>
							Home
							</h5>
							<ul>
								<li>
									<a href="{{ url('/') }}?feed=featured" class="feature {{(request()->input('feed')=='featured') ? ' current':''}}">
										<img class="static_page_lazy" data-src="{{url('image/new-navigation.png')}}">
										<span>
											Featured
										</span>
									</a>
								</li>
								<li>
									<a href="{{ url('/') }}?feed=recommended" class="f-icon recom {{(request()->input('feed')=='recommended') ? ' current':''}}">
										<i class="icon icon-o-star"></i>
										<span>
											Recommended
										</span>
									</a>
								</li>
							</ul>
						</div>
						<div class="nav-wrap">
							<h5>
							Shop
							</h5>
							<ul>
								<li>
									<a href="{{ url('shop/popular') }}" class="f-icon popular {{(request()->segment(2)=='popular') ? ' current':''}}">
										<i class="icon icon-number-one"></i>
										<span>
											Popular Products
										</span>
									</a>
								</li>
								<li>
									<a href="{{ url('shop/newest') }}"  class="f-icon new_products {{(request()->segment(2)=='newest') ? ' current':''}}">
										<i class="icon icon-add"></i>
										<span>
											New Products
										</span>
									</a>
								</li>
								<li>
									<a href="{{ url('shop/editor') }}" class="f-icon Editor {{(request()->segment(2)=='editor') ? ' current':''}}">
										<i class="icon icon-checkbox"></i>
										<span>
											Editor's Picks
										</span>
									</a>
								</li>
								<li>
									<a href="{{ url('shop/onsale') }}" class="f-icon On_sale {{(request()->segment(2)=='onsale') ? ' current':''}}">
										<i class="icon icon-shop-tag"></i>
										<span>
											On Sale
										</span>
									</a>
								</li>
							</ul>
						</div>
						<div class="nav-wrap">
							<h5>
							Browse
							</h5>
							<ul ng-init="url='{{url('/')}}';uri='{{ request()->segment(3) }}'">
								<li ng-repeat="category in categories_browse">
									<a href="@{{ url+'/shop/browse/'+category.title }}" class="@{{ category.title }} (uri==category.title) ? 'current' : '' ">
										<img class="category_lazy" ng-src='@{{category.icon_name}}'>
										@{{ category.title }}
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			@if(Route::current()->uri() != 'checkout')
			@if(isset($header_categories))
			<div class="cls_topcategory d-none d-lg-block">
				<div class="cls_inner">
					<ul class="cls_ul">
						@include('products.category_list_item', ['categories' => $header_categories, 'current_name' => request()->segment(3) ])
					</ul>
					<button class="horizon-prev d-none d-lg-block"><i class="icon-chevron-left"></i></button>
					<button class="horizon-next d-none d-lg-block"><i class="icon-chevron-left"></i></button>
				</div>
			</div>
			@endif
			@endif
		</div>
		@if(Session::has('message') && !isset($exception))
			<div class="flash-container">
				@if((Auth::check()))
				<div class="alert {{ Session::get('alert-class') }}" role="alert">
					<a href="#" class="alert-close" data-dismiss="alert"></a>
					{{ Session::get('message') }}
				</div>
				@endif
			</div>
		@endif
	</header>
	<div class="modal fade cls_loginpop" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="login-back">
					<div class="col-lg-12 back-white cls_sociallogin">
						 <h1 class="sitelogo">
			                <a href="{{ url('/') }}"><img src="{{ $logo }}"></a>
			            </h1>
						<h4 class="modal-title" id="myModalLabel">{{ trans('messages.header.login') }}</h4>
						<div class="social-buttons my-3">
							<a href="{{URL::to('facebookLogin')}}" class="facebook-btn cls_socialicon col-lg-12 col-md-12 text-center mb-2">
								{{ trans('messages.login.login_with') }} Facebook
							</a>
							<a href="javascript:;" class="google-btn cls_socialicon text-center google_signin">
								Google
							</a>
							<a href="{{URL::to('twitterLogin')}}" class="twitter-btn cls_socialicon text-center">
								Twitter
							</a>
						</div>
						<div style="clear: both"></div>
						<hr>
						<div class="d-flex flex-column text-center">
							{!! Form::open(['action' => 'UserController@authenticate', 'class' => 'login-form', 'data-action' => 'login', 'id' => '', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
							<div class="form-group">
								{!! Form::email('login_email', '', ['class' => $errors->has('login_email') ? 'form-control mb-3 invalid' : 'form-control mb-3', 'placeholder' => trans('messages.login.email_address')]) !!}
								@if ($errors->has('login_email'))<p class="help-block" style="color:red">{{ $errors->first('login_email') }}</p> @endif
								{!! Form::input('password', 'login_password', '',['class' => $errors->has('password') ? 'form-control invalid' : 'form-control', 'placeholder' => trans('messages.login.password'), 'data-hook' => 'signin_password']) !!}
								@if ($errors->has('login_password'))<span class="help-block" style="color:red">{{ $errors->first('login_password') }}</span> @endif
							</div>
							<button class="btn  mt-3 btn-signup">{{ trans('messages.header.login') }}</button>
							{!! Form::close() !!}
						</div>
						<div class="others my-3">
							<!-- <a href="{{ url('profile') }}" >More social networks</a><br> -->
							{{ trans('messages.login.new_to') }} {{$site_name }}? <a href="javascript:void(0)" class="signup" data-toggle="modal" data-target="#signupmodel"> {{ trans('messages.login.signup_now') }}</a> |  <a href="javascript:void(0)" class="forgotmodal" data-toggle="modal" data-target="#forgotmodal">{{ trans('messages.login.forget_password') }}?</a>
						</div>
						
					</div>
					<div class="selling">{{ trans('messages.login.interested_selling') }}? <a href="{{ url('merchant/signup') }}">{{ trans('messages.login.get_started') }}</a></div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade cls_signuppop" id="signupmodel" tabindex="-1" role="dialog" aria-labelledby="signupmodel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="login-content loginvalue">
					<div class="login-back cls_loginpage p-lg-0 my-4">
			        <div class="clsloginheader">
			            <h1 class="sitelogo">
			                <a href="{{ url('/') }}"><img src="{{ $logo }}"></a>
			            </h1>
			        </div>
		            <div class="" style="{{(!empty(Session::get('user_data')))?'display:none':'display:block'}};">
		                @if(Session::has('message') && !isset($exception))
		                <div class="flash-container  newflash">
		                    <div class="flash-container1">

		                        <div class="alert error_alert {{ Session::get('alert-class') }} success_msg" role="alert">
		                            <a href="#" class="alert-close error_close" data-dismiss="alert"></a>
		                            {{ Session::get('message') }}
		                        </div>

		                    </div>
		                </div>
		                @endif
		                <p>{{ trans('messages.login.join_desc') }}</p>
		                <div class="social-buttons my-3">
		                    <a href="{{URL::to('facebookLogin')}}" class="facebook-btn cls_socialicon col-lg-12 col-md-12 text-center mb-2" style="width:100%;">
		                        {{ trans('messages.login.login_with') }} Facebook
		                    </a>
		                    <a href="javascript:;" class="google-btn cls_socialicon col-md-6 col-lg-6 text-center google_signin">
								Google
							</a>
		                    <a href="{{URL::to('twitterLogin')}}" class="twitter-btn cls_socialicon col-md-6 col-lg-6 text-center">
		                        Twitter
		                    </a>
		                </div>
		                <div style="clear: both"></div>
		                <hr>
		                <div class="d-flex flex-column text-center">
		                    <form name="form" novalidate class="form-group">
		                        <div class="mb-3">
		                            <input type="email" class="text form-control" id="email_id" placeholder="{{trans('messages.login.email_address')}}" name="email" ng-model="email" required>
		                            <span class="help-inline d-block text-left" ng-show="required_email" style="color:red">{{ trans('messages.login.required_email') }}</span>
		                            <span class="help-inline d-block text-left" ng-show="invalid_email" style="color:red">{{ trans('messages.login.invalid_email') }}</span>
		                            <span class="help-inline d-block text-left" ng-show="exist_mail" style="color:red">{{ trans('messages.login.exist_mail') }}</span>
		                        </div>
		                        <button class="btn-signup btn btn-info btn-block btn-round" type="submit" ng-click="email_check(form)">{{ trans('messages.header.join') }} {{ $site_name }}</button>
		                    </form>
		                </div>
		                <div class="others mb-3">
		                    @lang('messages.login.already_an') {{ $site_name }}? 
		                    <a href="javascript:void(0)" class="loginsign">Log In</a>
		                </div>
		                <div class="row">
		                    <div class="selling">{{ trans('messages.login.interested_selling') }}? <a href="{{ url('merchant/signup') }}">{{ trans('messages.login.get_started') }}</a></div>
		                </div>
		            </div>
		        </div>
		        </div>
			</div>
		</div>
	</div>
	<div class="modal cls_forgotpop" id="forgotmodal" tabindex="-1" role="dialog" aria-labelledby="forgotmodal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="cls_loginpage cls_forgot">
					<div class="login-content">
						<div class="flash-container">
							@if(Session::has('message') && !isset($exception))
							<div class="alert {{ Session::get('alert-class') }}" role="alert">
								<a href="#" class="alert-close" data-dismiss="alert"></a>
								{{ Session::get('message') }}
							</div>
							@endif
						</div>
						<h2 class="sitelogo mb-4">
						<a href="{{ url('/') }}"><img src="{{ $logo }}"></a>
						{{ trans('messages.login.forget_password') }}</h2>
						{!! Form::open(['url' => url('forgot_password')]) !!}
						<div class="email-form form-group">
							{!! Form::email('email', '', ['class' => 'form-control ', 'placeholder' => trans('messages.login.email_address')]) !!} @if ($errors->has('email'))
							<p class="help-block" style="color:red">{{ $errors->first('email') }}</p> @endif
							
						</div>
						<button class="btn-signup btn btn-info btn-block btn-round">{{ trans('messages.login.send') }}</button>
						{!! Form::close() !!}
						<div class="others mt-3">
							{{ trans('messages.login.new_to') }} {{ $site_name }}? <a href="javascript:void(0)" class="signup">{{ trans('messages.login.signup_now') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="login-back sign_register cls_loginpop {{(!empty(Session::get('error_code')) && Session::get('error_code') == 1)? 'sign_err' : ''}}" style="{{(!empty(Session::get('error_code')) && Session::get('error_code') == 1)?'display:block':'display:none'}}">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="signup-content">
					
					<h2 class="modal-title">{{ trans('messages.login.looking_good') }}</h2>
					{!! Form::open(['action' => 'UserController@create', 'class' => 'signup-form form-group', 'data-action' => 'Signup', 'id' => 'user_new', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
					<div class="error" style="margin:-10px 0 20px;display:none;"></div>
					<div class="signup-content_in">
						<div class="hastext col-lg-12 my-3 p-0">
							<label class="label">{{ trans('messages.login.full_name') }}<span class="text-danger">*</span></label>
							{!! Form::text('full_name', old('full_name'), ['class'=>'text sffocus form-control', 'placeholder'=>trans('messages.login.full_name'),'value'=>'','autocomplete'=>'off']) !!}
							@if ($errors->has('full_name'))<p class="help-block" style="color:red">{{ $errors->first('full_name') }}</p> @endif
						</div>
						<div class="with-label col-lg-12 my-3 p-0">
							<label class="label">{{ trans('messages.login.email') }}  <span class="text-danger">*</span></label>
							{!! Form::text('email', old('email'), ['id'=>'email_signup','class'=>'text form-control', 'placeholder'=>trans('messages.login.email_address'),'autocomplete'=>'off']) !!}
							@if ($errors->has('email'))<p class="help-block" style="color:red">{{ $errors->first('email') }}</p> @endif
						</div>
						@if(!@Session::get('user_data')['source'])
						<div class="with-label col-lg-12 my-3 p-0">
							<label class="label">{{ trans('messages.login.password') }}  <span class="text-danger">*</span></label>
							<input type="password" class="text form-control" name="user_password"  placeholder="{{ trans('messages.login.create_password') }}">
							@if ($errors->has('user_password'))<p class="help-block" style="color:red">{{ $errors->first('user_password') }}</p> @endif
						</div>
						@endif
						<span class="loader" style="display: none;"><b></b> <em></em></span>
						<div class="with-label col-lg-12 my-3 p-0">
							<label class="label">{{ trans('messages.login.user_name') }}  <span class="text-danger">*</span></label>
							{!! Form::text('user_name', old('user_name'), ['id'=>'user_name','class'=>'text form-control','placeholder'=>trans('messages.login.choose_username'),'autocomplete'=>'off']) !!}
							@if ($errors->has('user_name'))<p class="help-block" style="color:red">{{ $errors->first('user_name') }}</p> @endif
						</div>
						<button class="btn btn-signup my-3">{{ trans('messages.header.join') }} {{$site_name }}</button>
						<input type="hidden" class="next_url" value="/">
					</div>
				</form>
				<?php
				foreach ($company_pages as $company_page) {
					if ($company_page->id == 2) {
						@$terms_service = $company_page->url;
					} else if ($company_page->id == 3) {
						$privacy_privacy = $company_page->url;
					}
				}
				?>
				<div class="terms pb-3">
					{{ trans('messages.login.signup_agree') }} {{$site_name }} <a href="{{url($terms_service)}}">{{ trans('messages.login.terms_service') }}</a> and <a href="{{url($privacy_privacy)}}">{{ trans('messages.login.privacy_policy') }}</a>.
				</div>
			</div>
		</div>
	</div>
</div>
<!-- welcome popup -->
</div>
<script type="text/javascript">
function searching() {
if ($('#search').val()=="")
return false;
else
return true;
}
</script>