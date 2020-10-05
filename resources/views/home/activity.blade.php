@extends('template')
@include('common.sections')
@section("product_detail_activity")
<div class="min-size short-padding">
    <a class="thingsimga" href="{{ url('things') }}/@{{ product.id }}">
        <div class="thingsimg">
            <img lazy-src="@{{product.image_name}}">
        </div>
        <div class="cls_storehead">
            <span class="img-title text-truncate">
                @{{ product.title}}
            </span>
            <button class="cls_price not-clickable" type="submit">
            <span ng-bind-html="product.currency_symbol"></span> @{{product.price}}
            </button>
        </div>
    </a>
    <div class="img-content pro_likes">
        <span>
            <button class="btn-gray product_like" ng-click='product_like(product,$index)'><span ><i> <span class="product_like_@{{ $index }}" ng-test="@{{ $index }}">@{{ product.like_user.length}} </span ></i></span>
            </button>
        </span>
    <span class="cls_share dropdown dropup">
        <a href="#" id="sharelist1" class="btn-more more_List" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
        <div class="dropdown-menu" aria-labelledby="sharelist">
            <div class="sub-more share cls_shareimg">
                @yield("sharable")
            </div>
            <div class="popover-body add-wishlist">
                <div class="wishlist_@{{ product.id }} cls_wish" id="wishlist_@{{ product.id }}" aria-hidden="true" ng-click="wishlist({{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, product.id)" ng-class="(product.wishlist != null && product.wishlist != '') ? 'icon-heart' : 'icon-heart-o'">
                    <span ng-show="product.wishlist != null && product.wishlist">
                        @lang('messages.home.saved_wishlist')
                        <small>
                            @lang('messages.home.click_unsave')
                        </small>
                    </span>
                    <span ng-hide="product.wishlist != null && product.wishlist">
                        @lang('messages.home.save_wishlist')
                        <small>
                            @lang('messages.home.save_your_wishlist')
                        </small>
                    </span>
                </div>
            </div>
            <div class="sub-more copy cls_shareimg" >
                <b class="copy-to-clipboard" data-copy="{{ url('things') }}/@{{ product.id }}"> @lang('messages.home.copy_link') </b>
            </div>
        </div>
    </span>
</div>
</div>
@endsection
@section("add_product")
<div class="add_product">
    <div class="card">
        <div class="card-header">
            <a class="d-flex align-items-center flex-wrap" href="{{url('store')}}/@{{user_activity[0].source_store.id}}">
                <div class="img img-thumbnail mr-2">
                    <img class="cls_smimg" ng-src="@{{ user_activity[0].source_store.logo_img }}">
                </div>
                <div class="d-flex ml-2 store_name">
                    <span class="font-weight-bolder"> @{{ user_activity[0].source_store.store_name }} </span>
                    <span class="mx-2"> @lang('messages.home.added') </span>
                    <span ng-show="user_activity.length > 1"> @{{ user_activity.length }} @lang('messages.home.items') </span>
                </div>
            </a>
        </div>
        <div class="cls_cartall">
           <ul class="owl-carousel owl-theme activity_product_slider">
                <li class="col-12 cls_thingsproli" ng-repeat="act_store in user_activity" ng-init="product = act_store.target_product">
                    @yield('product_detail_activity')
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section("like_product")
<div class="like_product">
    <div class="card">
        <div class="card-header">
            <a class="d-flex align-items-center flex-wrap" href="{{url('profile')}}/@{{user_activity[0].source_user.user_name}}">
                <div class="img img-thumbnail mr-2">
                    <img class="cls_smimg" ng-src="@{{ user_activity[0].source_user.original_image_name }}">
                </div>
                <div class="d-flex ml-2 store_name">
                    <span class="font-weight-bolder"> @{{ user_activity[0].source_store.store_name }} </span>
                    <span class="text-muted mx-2"> @lang('messages.products.spiffyd_an_item',['site_name' => $site_name]) </span>
                </div>
            </a>
        </div>
        <div class="cls_cartall">
           <ul class="owl-carousel owl-theme activity_product_slider">
                <li class="col-12 cls_thingsproli" ng-repeat="act_user in user_activity" ng-init="product = act_user.target_product">
                    @yield('product_detail')
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section("following_store")
<div class="following_store">
    <div class="card">
        <div class="card-header d-flex align-items-center flex-wrap">
            <div class="img img-thumbnail mr-2">
                <img class="cls_smimg" ng-src="@{{ user_activity[0].source_user.original_image_name }}">
            </div>
            <div class="d-flex align-items-center flex-wrap ml-2 store_name">
                <a href="{{url('profile')}}/@{{user_activity[0].source_user.user_name}}">
                    @{{user_activity[0].source_user.full_name}}
                </a>
                <span class="ml-1"> @lang('messages.home.started_following') </span>
                <span class="ml-1" ng-if="user_activity.length > 1"> @{{user_activity.length}} </span>
                <span class="ml-1" ng-if="user_activity.length <= 1"> @lang('messages.home.a') </span>
                <span class="ml-1"> @lang('messages.header.stores') </span>
            </div>
            <div class="store_list">
                <ul class="store_list_ul">
                    <li class="store_list_item" ng-repeat="act_user in user_activity">
                        <div class="">
                            <img class="cls_contimg" ng-src="@{{ act_user.target_store.original_logo_img }}">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section("following_user")
<div class="following_user">
    <div class="card">
        <div class="card-header d-flex align-items-center flex-wrap">
            <div class="img img-thumbnail mr-2">
                <img class="cls_smimg" ng-src="@{{ user_activity[0].source_user.original_image_name }}">
            </div>
            <div class="d-flex align-items-center flex-wrap ml-2 store_name">
                <a href="{{url('profile')}}/@{{user_activity[0].source_user.user_name}}">
                    @{{user_activity[0].source_user.full_name}}
                </a>
                <span class="ml-1"> @lang('messages.home.started_following') </span>
                <span class="ml-1" ng-if="user_activity.length > 1"> @{{user_activity.length}} </span>
                <span class="ml-1" ng-if="user_activity.length <= 1"> @lang('messages.home.a') </span>
                <span class="ml-1"> @lang('messages.header.people') </span>
            </div>
            <div class="store_list">
                <ul class="store_list_ul">
                    <li class="store_list_item" ng-repeat="act_user in user_activity">
                        <div class="">
                            <img class="cls_contimg" ng-src="@{{ act_user.source_user.original_image_name }}">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('main')
<main id="site-content" role="main" ng-controller="userActivities">
<div class="container cls_activityhome cls_profilepage">
    <div class="d-flex flex-wrap">
        <div class="activity-sidebar cls_activity_left col-lg-4 col-md-4 col-sm-12">
            <ul>
                <li class="ic-activity">
                    <a href="#" class="link current">Activity</a>
                </li>
                <li class="ic-msg">
                    <a href="{{ url('messages') }}" class="link">Inbox</a>
                </li>
            </ul>
        </div>
        <div class="activity-content col-md-8 col-lg-6 col-sm-12" infinite-scroll='activityLoadMore()' infinite-scroll-distance='1' infinite-scroll-disabled='noMoreActivities' ng-cloak>
            <div class="cls_activity_right">
                <ul class="row">
                    <li class="col-12 mt-0 cls_thingsproli" ng-repeat="activity in all_activity">
                        <div ng-repeat="activities in activity">
                            <div class="mb-3" ng-repeat="(activity_type,user_activities) in activities">
                                <div class="store_detail" ng-if="activity_type == 'add_product'" ng-repeat="user_activity in user_activities">
                                    @yield('add_product')
                                </div>
                                <div class="like_product" ng-if="activity_type == 'like_product'" ng-repeat="user_activity in user_activities">
                                    @yield('like_product')
                                </div>
                                <div class="following_store" ng-if="activity_type == 'following_store'" ng-repeat="user_activity in user_activities">
                                    @yield('following_store')
                                </div>
                                <div class="user_detail" ng-if="activity_type == 'following_user'" ng-repeat="user_activity in user_activities">
                                    @yield('following_user')
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="mb-4 more_products loading" ng-show="ajax_loading"></div>
                <div class="text-center" ng-show="all_activity.length == 0 && !ajax_loading">
                    <span class="font-weight-bold"> @lang("messages.header.no_activities_desc") </span>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection