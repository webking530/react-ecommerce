@extends('template')
@section('main')
@include('common.sections')
<main id="site-content" role="main" ng-controller="productController">
    <div class="whole-products" ng-cloak>
        <div class="m_container" ng-init="current_tab='products';load_more_type='store';store_id='{{$result->user_id}}'">
            <div class="cls_profilepage d-flex flex-wrap" ng-controller="storeController">
                <div class="cls_coversmall text-center text-lg-left col-lg-2 col-12 col-md-2">
                    <a class="profile-photo-img">
                        <img class="img-round profile-img" src="{{ $result->logo_img }}" onerror="this.src='{{ $no_product_url }}';">
                        @if(Auth::check() && Auth::user()->user_name == @$user->user_name)
                            <div href="javascript:;" class="photo-change model_popup_logimg"><i class="icon"></i><em style="margin-left: -68.5px;">Upload Profile Photo</em></div>
                        @endif
                    </a>
                    <div class="title">
                        <h1> {{ $result->store_name }}</h1>
                        <small>
                            {{ $result['user_address'][0]['city'] }}, {{ $result['user_address'][0]['country'] }}
                        </small>
                        <small>
                            {{ $result->tagline }}
                        </small>
                    </div>
                    <ul class="followers">
                        <li>
                            <b class="follower_cnt">{{ $following_count ?? '' }}</b> @lang('messages.profile.followers')
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-10 col-12 col-md-10 cls_coverlarge store_detail" id="mainContent">
                    <div class="cls_coverlargein">
                        <div class="cover-photo bg_cover">
                            <div class="cover-photo">
                                <img src="{{$result->header_img }}" width="40px" onerror="this.src='{{ $no_store_url }}';">
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center cls_coverlargeedit">
                            <div class="col-lg-10 col-12 col-sm-9 nopad sort">
                                <ul class="menu tab">
                                    <li>
                                        <a href="javascript:void(0)" ng-click="current_tab='products';" class="profile_tab" ng-class="(current_tab == 'products') ? 'current' : ''"><b> @lang('messages.home.products') </b> <small>{{ $product_count ?? '' }}</small></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" ng-click="current_tab='about';" class="profile_tab" ng-class="(current_tab == 'about') ? 'current' : ''"><b> @lang('messages.home.about') </b> </a>
                                    </li>
                                </ul>
                            </div>
                            <div class=" col-lg-2 col-sm-3 mt-3 mt-lg-0 text-center col-12">
                                @if(Auth::id() != $result->user_id)
                                <div class="follow_btn_row ask_ques st-flow">
                                    <a href="javascript:;"  ng-click="FollowStore({{ $result->id }})" class="btns-gray-embo" id="follow">
                                        <span class="{{ (@$follow=='Following Store') ? 'following_btn' : 'follow_btn' }}">
                                            {{ @$follow }}
                                        </span>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="store_id" value="{{ $result->user_id }}">
                    <div class="cls_thingspro" ng-show="current_tab == 'products'">
                        <ul class="row" id="show_store_products" infinite-scroll='productLoadMore()' infinite-scroll-distance='1' infinite-scroll-disabled='no_more_products' ng-cloak>
                            <div class="mb-4 more_products loading" ng-show="ajax_loading"></div>
                            <li class="col-lg-4 col-md-6 col-12 mb-3 cls_thingsproli" ng-repeat="product in detail_products" title="@{{ product.title }}">
                                @yield('product_detail')
                            </li>
                        </ul>
                    </div>
                    <div class="about cls_about" id="store_about" ng-show="current_tab == 'about'">
                        {!! $result->description !!}
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
                                        <li ng-repeat="user_likes in all_product_like_user"     ng-cloak class="user_like_li col-xs-12 nopad">
                                            <div class="user_detail_like col-xs-12 col-md-12 col-sm-12 nopad">
                                                <a href="{{ url('profile') }}/@{{ user_likes.users.original_user_name }}" class="link_user_profile">
                                                    <div class="col-md-2 col-sm-2 col-xs-2 nopad"><img class="img" ng-src="@{{user_likes.users.original_image_name}}" /></div>
                                                </a>
                                                <div class="col-md-8 col-sm-8 col-xs-8 nopad text_left">
                                                    <p ng-if="$index=='0'" class="normal">Added by <br/>
                                                        <span ng-if="$index=='0'" class="firstname" style="color: #393d4d; font-weight: bold;">@{{ user_likes.users.original_user_name }}</span></p>
                                                        <label ng-if="$index!='0'" class="firstname">
                                                            <a href="{{ url('profile') }}/@{{ user_likes.users.original_user_name }}" class="link_user_name">
                                                            @{{ user_likes.users.original_full_name }}</a></label>
                                                            <p class="normal"  ng-if="$index!='0'" >@ @{{ user_likes.users.original_user_name }}</p>
                                                        </div>
                                                        <div class="col-md-2  col-sm-2 col-xs-2 nopad" ng-model="all_product_like_user[$index].users.user_follow" ng-if="current_userid!=user_likes.users.id">
                                                            <a ng-if="user_likes.users.user_follow=='0'"  ng-click="likeuserFollow($index)"><i class="icon icon-male-user-with-plus-symbol1"></i></a>
                                                            <a ng-if="user_likes.users.user_follow!='0'"  ng-click="likeuser_unfollow($index)"><i class="icon icon icon-user-with-tick"></i></a>
                                                        </div>
                                                    </div>
                                        </li>

                                        <li ng-repeat="user_likes in all_product_like_user"     ng-cloak class="user_like_li col-xs-12 nopad" ng-show='all_product_like_user.length == 0 && !ajax_loading'>
                                        </li>
                                        <div class="mb-4 more_products loading" ng-show="ajax_loading"></div>  
                                    </ul>
                                        <div id="no_likes" class="cls_empty search-result-empty" style="display:none">
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
                    </div>
                </div>
            </div>
        </main>
        @endsection