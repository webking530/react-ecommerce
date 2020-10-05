@extends('template')
@section('main')
<input type="hidden" name="category" id="page_category" value="{{ request()->segment(3) }}">
<input type="hidden" id="page_name" value="{{ $page }}">
<div class="products-wrap">
    <ul class="d-flex flex-wrap justify-content-between {{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}} check_login" ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}}" data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" infinite-scroll='loadMore()' infinite-scroll-distance='1' infinite-scroll-disabled='products_busy' ng-init="count=0" ng-cloak>
        <li id="all-@{{$index}}" ng-repeat="all in all_product" ng-class="all.video_src=='' ? 'pro-img-wrap' : 'pro-video-wrap'" class="p-0" ng-cloak>
            <div class="popshow" id="@{{ all.id }}" ng-class="$index % 3 == 0 ? 'img-height' : 'img-height-short'" 
            ng-init="like_limit=($index % 3 == 0 ? '15' : '6')">                
            <a href="{{url('things')}}/@{{all.id}}">  
                <img ng-if="all.video_src=='' || all.video_src==null" class="lazy" ng-src="@{{ $index % 3 == 0 ? all.products_images.compress_image : all.products_images.home_full_image  }}" onerror="this.src='{{ $no_product_url }}';" > 
                <div class="video_player" ng-if="all.video_src!='' && all.video_src!=null" style="height: 300px;width: 100%;">
                    <player  videos='[{"type":all.video_type,"src":all.video_src,"poster":all.video_thumb,"captions":""}]'  />
                </div>
            </a>
        </div> 

        <div class="pro-content">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <a href="{{url('things')}}/@{{all.id}}" class="text-truncate col-md-8 p-0">
                    @{{ all.title}}
                </a>
                <div class="col-md-4 p-0 text-right">
                    <button class="btn-primary " type="submit">
                        <span ng-bind-html="all.currency_symbol"></span> 
                        @{{all.session_price}}
                    </button>
                </div>  
            </div>
            <div class="share-wrap d-flex align-items-center">
                <span ng-if='all.user_like.length'>
                    <button class="btn-like product_like {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data='@{{ all.id }}'>
                        <span class="icon-like"></span>
                        @{{ all.like_user.length }}
                    </button>
                </span>
                <span ng-if='!all.user_like.length'>
                    <button class="product_like {{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data='@{{ all.id }}'>
                        <span class="icon-like"></span>
                        @{{ all.like_user.length }}
                    </button>
                </span>
                <span class="like-link">
                    <span class="popover-wrapper user-pic" ng-repeat="like_user in all.like_user | limitTo:like_limit" ng-cloak>
                        <a href="{{url('profile')}}/@{{like_user.users.user_name}}" data-id="user_@{{all.id}}_@{{like_user.users.id}}" class="user_a user_@{{all.id}}_@{{like_user.users.id}}">  
                            <span ng-if="like_user.users.original_image_name">
                                <img ng-src="@{{ like_user.users.original_image_name }}" class="user-img">
                            </span>
                            <span ng-if="!like_user.users.original_image_name">
                                <img class="user-img" src="{{ url('image/profile.png') }}">
                            </span>
                        </a>
                        <div class="popover-modal example-popover-3" style="display: none;">
                            <a href="{{url('profile')}}/@{{like_user.users.user_name}}">
                                <div class="popover-body">
                                    <div class="user_background">
                                        <img ng-src="@{{ like_user.users.original_cover_image_name }}">
                                    </div>
                                    <div class="user_profile_image">
                                        <div style="background-image: url('@{{ like_user.users.original_image_name }}');"></div>
                                        <p class="userfull_text">
                                            @{{like_user.users.full_name}}
                                        </p>
                                        <p class="user_text">
                                            @{{like_user.users.user_name}}
                                        </p>
                                        <p>
                                            <span class="follow_text">
                                                <span class="follow_count">
                                                    @{{like_user.users.user_follower}}
                                                </span> 
                                                {{ trans('messages.home.followers') }} 
                                            </span>.
                                            <span class="follow_text">
                                                <span class="follow_count">
                                                    @{{like_user.users.user_following}}
                                                </span>  
                                                {{ trans('messages.home.following') }} 
                                            </span>
                                        </p>
                                        <ul>
                                            <li ng-repeat="like_user_products in like_user.users.product_likes | limitTo:3">
                                                <img ng-src="">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </span>                
                    <a ng-if='all.like_user.length' class="like_user_more" ng-click="like_user_loadMore(all.id)" type="button" data-toggle="modal" data-target="#view_like_user">
                        <i data-icon="D" class="icon"></i>
                    </a>
                </span>

                <div class="show-more-options ml-auto dropdown">
                    <button class="btn " type="button" id="sharemenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        <i class="icon icon-share"></i>
                    </button>
                    <ul class=" dropdown-menu cls_sharemenu" aria-labelledby="sharemenu">
                        <li class="d-flex">
                            <div class="share-icon-wrap">
                                <i class="icon icon-share-1"></i>
                            </div>
                            <div class="share-info">
                                <h4>
                                    {{ trans('messages.home.share') }}
                                </h4>
                                <span>
                                    {{ trans('messages.home.share_friends') }}
                                </span>
                                <ul class="sharable d-flex align-items-center justify-content-between">
                                    <li>
                                        <a href="@{{ all.share_url.facebook }}" target="_blank" class="share_facebook">
                                            <i class="icon icon-facebook-square"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="@{{ all.share_url.gplus }}" target="_blank" class="share_instagram">
                                            <i class="icon icon-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="@{{ all.share_url.twitter }}" target="_blank" class="share_twitter">
                                            <i class="icon icon-twitter-square"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="add-wishlist wishlist_div">
                            <div id="wishlist_@{{all.id}}" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.id )" class="d-flex"> 
                                <div class="share-icon-wrap" ng-class="(all.wishlist.id !='' && all.wishlist.id!=null) ? 'saved' : 'unsaved'">
                                    <i class="icon icon-heart"></i>
                                    <i class="icon icon-heart-o"></i>
                                </div>
                                <div class="share-info">
                                    <span ng-if="all.wishlist.id !='' && all.wishlist.id!=null"> 
                                        <h4>
                                            {{ trans('messages.home.saved_wishlist') }} 
                                        </h4>
                                        <small>
                                            {{ trans('messages.home.click_unsave') }}
                                        </small>
                                    </span>
                                    <span ng-if="all.wishlist.id==null"> 
                                        <h4>
                                            {{ trans('messages.home.save_wishlist') }} 
                                        </h4>
                                        <small>
                                            {{ trans('messages.home.save_your_wishlist') }}
                                        </small>
                                    </span>
                                </div>
                            </div>
                        </li>

                        <li class="d-flex align-items-center" data-copy="{{url('things')}}/@{{all.id}}">
                            <div class="share-icon-wrap">
                                <i class="icon icon-chain-broken"></i>
                            </div>
                            <div class="share-info">
                                <span class="copy-to-clipboard">
                                    {{ trans('messages.home.copy_link') }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </li>
</ul>
</div>

<div id="search-result-empty" class="empty search-result-empty" style="display:none">   
    <i class="fa fa-search"></i>         
    <h3>{{ trans('messages.home.search_result_empty') }}</h3>
    <p>{{ trans('messages.home.search_result_empty_desc') }}</p>
</div>
<div class="loading products_loading" id="products_loading" style="display:none"></div>

<div style="width:100%;height:100%" class="lds-ripple">
    <div></div>
</div>

<!-- popup -->
@include('products.popup')
<!-- popup -->

<div id='popup_container'>
    <div class="popup policy_detail add-fancy-back">
        <div class="d-flex align-items-center flex-wrap cls_modal_height">
            <div data-reactroot="">
                <p>
                    {{ trans('messages.home.return_policy') }}
                </p>
                <div class="terms">
                    <p>
                        {{ trans('messages.home.no_returns') }}
                    </p>
                </div>
                <button class="ly-close1" title="Close">
                    <i class="ic-del-black"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="popup shipping">
        <div data-reactroot="" class="viewpoped">
            <p>
                Choose your country
            </p>
            <button class="ly-close1" title="Close">
                <i class="ic-del-black"></i>
            </button>
            <div class="country-list after">
                <span class="line"></span>             
                <div class="right-outer scroll">
                    <ul class="after"></ul>
                </div>
            </div>
            <div class="btn-area">
                <button class="btns-blue-embo shipping_country_save">
                    Save
                </button>
            </div>
        </div>
    </div>
</div> 
<script type="text/javascript">
    var min_slider_price = {!! @$default_min_price !!};
    var max_slider_price = {!! @$default_max_price !!};
    var min_slider_price_value = {!! $min_value !!};
    var max_slider_price_value = {!! $max_value !!};
</script>
<div class="modal fade col-xs-12" id="view_like_user" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog">    
            <div class="modal-content modal-content-like">
                <div class="modal-header ">
                    <h4 class="modal-title poptitle_like" style="display: inline-block;" id="mySmallModalLabel">
                        {{ trans('messages.home.liked_by') }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul ng-cloak class="ul_like_more">
                        <li ng-repeat="user_likes in all_product_like_user" ng-cloak class="user_like_li ">
                            <div class="user_detail_like d-flex justify-content-between align-items-center flex-wrap">
                                <a href="{{ url('profile') }}/@{{ user_likes.users.original_user_name }}" class="link_user_profile col-lg-2">
                                    <div class="">
                                        <img class="img" ng-src="@{{user_likes.users.original_image_name}}" />
                                    </div>
                                </a>
                                <div class="col-lg-8 text_left">
                                    <p ng-if="$index=='0'" class="normal">Added by <br/>
                                        <span ng-if="$index=='0'" class="firstname">
                                            @{{ user_likes.users.original_user_name }}
                                        </span>
                                    </p>
                                    <label ng-if="$index!='0'" class="firstname">
                                        <a href="{{ url('profile') }}/@{{ user_likes.users.original_user_name }}" class="link_user_name">
                                            @{{ user_likes.users.original_full_name }}
                                        </a>
                                    </label>
                                    <p class="normal" ng-if="$index!='0'">
                                        @@{{ user_likes.users.original_user_name }}
                                    </p>
                                </div>

                                <div class="col-lg-2 nopad" ng-model="all_product_like_user[$index].users.user_follow" ng-if="current_userid!=user_likes.users.id">
                                    <a ng-if="user_likes.users.user_follow=='0'" ng-click="likeuser_follow($index)">
                                        <i class="icon-user-plus-1"></i>
                                    </a>
                                    <a ng-if="user_likes.users.user_follow!='0'" ng-click="likeuser_unfollow($index)">
                                        <i class="icon-user-check"></i>
                                    </a>
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

@stop