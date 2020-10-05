<title>{{ $site_name }}</title>    
<?php
$no_store_url=url('image/cover_image.jpg');
$no_product_url=url('image/profile.png');
?>
<input type="hidden" name="category" id="page_category" value="{{ request()->segment(3) }}">
<input type="hidden" id="page_name" value="{{ $page }}">
<ul infinite-scroll='notification_loadMore()' infinite-scroll-distance='1' infinite-scroll-disabled='notification_busy' ng-init="count=0;user_from={{ Auth::id() }}" ng-cloak>
    <li  id="all-@{{$index}}" ng-repeat="all in all_notification" class="col-12 activity_li" ng-cloak>
        <!-- for orders notifications -->
        <div class="col-12" ng-if="all.notification_type=='order' && all.notification_type_status!='refund' && all.notification_type_status!='payout'">
            <div class="col-12 col-md-2">
                <img class="userby_image" ng-src="@{{ all.users.original_image_name }}">
            </div>
            <div class="col-12 col-md-9 activity_message">
                <label class="message_title">
                    <i class="fa fa-shopping-cart fa_order"></i>
                    <a href="{{ url('profile') }}/@{{all.users.user_name}}">@{{all.users.full_name}}</a>
                    @{{all.trans_message}}
                    <a ng-if="all.products.user_id==user_from"  href="{{ url('merchant/order') }}/@{{all.order_id}}">#@{{all.order_id}}</a>
                    <a ng-if="all.products.user_id!=user_from" href="{{ url('purchases') }}/@{{all.order_id}}">#@{{all.order_id}}</a>
                </label>
                <div class="image_section">
                    <img ng-src="@{{all.products.image_name}}"/>
                </div> 
            </div>
        </div>

        <!-- for follow user notifications -->
        <div class="col-12" ng-if="all.notification_type=='user_follow'">
            <div class="col-12 col-md-2">
                <img class="userby_image" ng-src="@{{ all.users.original_image_name }}">
            </div>
            <div class="col-12 col-md-9 activity_message"> 
                <label class="message_title">
                    <i class="fa fa-check fa_follow"></i>
                    <a href="{{ url('profile') }}/@{{all.users.user_name}}">
                        @{{all.users.full_name}}
                    </a>
                    @{{all.trans_message}}
                </label> 
            </div>
        </div>

        <!-- for follow store notifications -->
        <div class="col-xs-12" ng-if="all.notification_type=='store_follow'">
            <div class="col-xs-12 col-md-2">
                <img class="userby_image" ng-src="@{{ all.users.original_image_name }}">
            </div>
            <div class="col-12 col-md-9 activity_message"> 
                <label class="message_title">
                    <i class="icon icon-store fa_store"></i>
                    <a href="{{ url('profile') }}/@{{all.users.user_name}}">
                        @{{all.users.full_name}}
                    </a>
                    @{{all.trans_message}}
                </label> 
            </div>
        </div>

        <!-- for liked products notifications -->
        <div class="col-12" ng-if="all.notification_type=='like_product' ">
            <div class="col-12 col-md-2">
                <img class="userby_image" ng-src="@{{ all.users.original_image_name }}">
            </div>
            <div class="col-12 col-md-9 activity_message"> 
                <label class="message_title">
                    <i class="fa fa-thumbs-up fa_order"></i>
                    <a href="{{ url('profile') }}/@{{all.users.user_name}}">
                        @{{all.users.full_name}}
                    </a>
                    @{{all.trans_message}}
                    <a href="{{ url('things') }}/@{{all.product_id}}">
                        #@{{all.products.title}}
                    </a>
                </label> 
            </div>
        </div>

        <!-- for wishlist products notifications -->
        <div class="col-12" ng-if="all.notification_type=='wishlist' ">
            <div class="col-12 col-md-2">
                <img class="userby_image" ng-src="@{{ all.users.original_image_name }}">
            </div>
            <div class="col-12 col-md-9 activity_message"> 
                <label class="message_title">
                    <i class="fa fa-plus fa_plus"></i>
                    <a href="{{ url('profile') }}/@{{all.users.user_name}}">
                        @{{all.users.full_name}}
                    </a>
                    @{{all.trans_message}}
                    <a href="{{ url('things') }}/@{{all.product_id}}">
                        #@{{all.products.title}}
                    </a>
                </label> 
            </div>
        </div>

        <!-- for refund amount notifications -->
        <div class="col-12" ng-if="all.notification_type_status=='refund'">
            <div class="col-12 col-md-2">
                <img class="userby_image" src="{{ url('/') }}/admin_assets/dist/img/avatar04.png">
            </div>
            <div class="col-xs-12 col-md-9 activity_message"> 
                <label class="message_title">
                    <i class="fa fa-paypal fa_follow"></i>
                    <a href="{{ url('profile') }}/@{{all.users.user_name}}">
                        @{{all.users.full_name}}
                    </a>
                    @{{all.trans_message}}
                    <a href="{{ url('purchases') }}/@{{all.order_id}}">
                        #@{{all.order_id}}
                    </a>
                </label> 
            </div>
        </div>

        <!-- for payout amount notifications -->
        <div class="col-12" ng-if="all.notification_type_status=='payout'">
            <div class="col-12 col-md-2">
                <img class="userby_image" src="{{ url('/') }}/admin_assets/dist/img/avatar04.png">
            </div>
            <div class="col-12 col-md-9 activity_message"> 
                <label class="message_title">
                    <i class="fa fa-paypal fa_follow"></i>
                    <a href="{{ url('profile') }}/@{{all.users.user_name}}">
                        @{{all.users.full_name}}
                    </a>
                    @{{all.trans_message}}
                    <a href="{{ url('purchases') }}/@{{all.order_id}}">
                        #@{{all.order_id}}
                    </a>
                </label> 
            </div>
        </div>

        <!-- for featured your product notifications  -->

        <div class="col-12" ng-if="all.notification_type=='featured'">
            <div class="col-12 col-md-2">
                <img class="userby_image" src="{{url('image/new-navigation.png')}}">
            </div>
            <div class="col-12 col-md-9 activity_message"> 
                <label class="message_title">
                    <i class="fa fa-plus fa_plus"></i>
                    @{{all.trans_message}} 
                    <a href="{{ url('things') }}/@{{all.product_id}}">
                        #@{{all.products.title}}
                    </a>
                </label> 
            </div>
        </div>
    </li>
</ul>
<div id="search-result-empty" class="empty search-result-empty" style="display:none">   
    <i class="fa fa-search"></i>         
    <p>
        <b>
            {{ trans('messages.header.no_notifications') }}
        </b>
    </p>    
</div>
<div class="loading products_loading" id="activity_loading" style="display:none"></div>

