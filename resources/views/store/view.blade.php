@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="all_store" class="store_page">
    <div class="container cls_allstores">
        
        <div class="store-list cls_profilepage">
            <ul class="{{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}} check_login row cls_storelist overflow_visible" ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}}" data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" infinite-scroll='stores_loadMore()' infinite-scroll-distance='1' infinite-scroll-disabled='stores_busy'>
                <li id="all-@{{$index}}" class="col-lg-4 col-md-6 col-12 my-3" ng-repeat="all in all_stores_list" ng-cloak>

                    <div class="cover-photo-div pb-3">
                        <a class="w-100" href="{{ url('store') }}/@{{ all.id }}">
                            <div class="cover-photo">
                                <img class="img" ng-src="@{{ all.header_img }}" onerror="this.src='{{ $no_store_url }}';">
                               
                            </div>
                            <div class="profile-photo">
                               <div class="cls_prosimg">
                                    <span class="cls_span">
                                        <img class="store-img" ng-src="@{{all.logo_img}}" width="40px" onerror="this.src='{{ $no_product_url }}';">
                                    </span>
                                </div>
                            <div class="store_details">
                                <b class="profile-name text-center">@{{ all.store_name }}</b>
                                <small>@{{ all.user_address[0].city }} @{{ all.user_address[0].country }}</small>
                                <span class="desc_tagline text-truncate" title="@{{ all.tagline }}">@{{ all.tagline }}</span>
                            </div>
                             </div>
                        </a>
                        
                        <a href="{{ url('store') }}/@{{ all.id }}" class="text-center text-truncate shop_link"> {{ trans('messages.home.shop') }} @{{ all.store_name }}</a>
                    </div>

                </li>
            </ul>
            <div class="loading products_loading" id="store_loading" style="display:none"></div>
            <div id="stores-result-empty" class="empty search-result-empty" style="display:none">
                <i class="fa fa-search"></i>
                <h3>{{ trans('messages.home.search_result_empty_stores') }}</h3>
            </div>
        </div>
    </div>
    <div class="modal fade col-xs-12" id="view_like_user" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center " style="width:450px">
                <div class="modal-content modal-content-like" style="border-radius: 1px !important">
                    <div class="modal-header ">
                        <h4 class="modal-title poptitle_like" style="display: inline-block;" id="mySmallModalLabel">{{ trans('messages.home.liked_by') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body nopad">
                        <ul ng-cloak class="ul_like_more">
                            <li ng-repeat="user_likes in all_product_like_user" ng-cloak class="user_like_li col-xs-12 nopad">
                                <div class="user_detail_like col-xs-12 col-md-12 col-sm-12 nopad">
                                    <a href="{{ url('profile') }}/@{{ user_likes.users.original_user_name }}" class="link_user_profile">
                                        <div class="col-md-2 col-sm-2 col-xs-2 nopad"><img class="img" ng-src="@{{user_likes.users.original_image_name}}" /></div>
                                    </a>
                                    <div class="col-md-8 col-sm-8 col-xs-8 nopad text_left">
                                        <p ng-if="$index=='0'" class="normal">Added by
                                            <br/>
                                            <span ng-if="$index=='0'" class="firstname" style="color: #393d4d; font-weight: bold;">@{{ user_likes.users.original_user_name }}</span></p>
                                        <label ng-if="$index!='0'" class="firstname">
                                            <a href="{{ url('profile') }}/@{{ user_likes.users.original_user_name }}" class="link_user_name">@{{ user_likes.users.original_full_name }}</a></label>
                                        <p class="normal" ng-if="$index!='0'">@ @{{ user_likes.users.original_user_name }}</p>
                                    </div>

                                    <div class="col-md-2  col-sm-2 col-xs-2 nopad" ng-model="all_product_like_user[$index].users.user_follow" ng-if="current_userid!=user_likes.users.id">
                                        <a ng-if="user_likes.users.user_follow=='0'" ng-click="likeuser_follow($index)"><i class="icon icon-male-user-with-plus-symbol1"></i></a>
                                        <a ng-if="user_likes.users.user_follow!='0'" ng-click="likeuser_unfollow($index)"><i class="icon icon icon-user-with-tick"></i></a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div id="no_likes" class="empty search-result-empty" style="display:none">
                            <i class="fa fa-search"></i>
                            <h3>{{ trans('messages.home.search_result_empty') }}</h3>
                            <p>{{ trans('messages.home.search_result_empty_desc') }}</p>
                        </div>
                        <div class="whiteloading_like" id="likes_loading" style="display:none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection