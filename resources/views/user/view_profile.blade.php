@extends('settings_template')
@section('main')
<main id="site-content" role="main">
<div class="m_container" ng-controller="home_products">
    <div class="cls_profilepage d-flex flex-wrap">
            <div class="cls_coversmall text-center text-lg-left col-lg-2 col-12 col-md-2">
                <a class="profile-photo-img">
                    <img class="img-round profile-img" src="{{ @$user->profile_picture->src }}">
                    @if(@Auth::user()->user_name == @$user->user_name)
                    <div href="javascript:;" class="photo-change model_popup_logimg"><i class="icon"></i><em style="margin-left: -68.5px;">Upload Profile Photo</em></div>
                    @endif
                </a>
                <div class="title"><h1>{{$user->full_name}}</h1><small>
                {{ '@'. $user->user_name}}
                </small></div>
                <ul class="followers">
                    <li><a class=""  ><b class="follower_cnt" id="followers">{{ @$following_count }}</b> {{ trans('messages.profile.followers') }}
                    </a></li>
                    <li><a class="" ><b id="following">{{ @$follower_count }}</b> {{ trans('messages.profile.following') }}
                    </a></li>
                </ul>
            </div>
            <div class="col-lg-10 col-12 col-md-10 cls_coverlarge">
            <div class="cls_coverlargein">
                <div class="cover-photo bg_cover" style="background-image: url('{{ @$user->profile_picture->cover_image_src }}');">
                    @if(@Auth::user()->user_name == @$user->user_name)
                    <div class="menu-container-cover menu-container" style="display:block;">
                        <a href="javascript:;" class="menu-title-cover btn btn-gray"><em>{{ trans('messages.profile.upload_cover_image') }}</em></a>
                        <div class="menu-content-cover">
                            <ul class="first">
                                <li><a href="javascript:;" class="list-icon change btn-upload-cover model_popup_coverimg" >{{ trans('messages.profile.upload_image') }}</a></li>
                            @if(isset($user->profile_picture->cover_image_src) && $user->profile_picture->original_cover_image_src != '' )
                                <li><a class="list-icon delete btn-delete-cover" href="{{url('remove_cover_img')}}">{{ trans('messages.profile.remove') }} </a></li>
                                 @endif
                            </ul>
                           
                        </div>
                    </div>
                    @endif
                </div>
                <div class="d-flex flex-wrap align-items-center cls_coverlargeedit">
                    <div class="col-lg-10 col-12 col-sm-9 nopad sort">
                        <ul class="menu tab menu_3">
                            <li ng-init="show= 1">
                                <a href="javascript:;" ng-click="show= 1" class="current profile_tab" ng-class="show == 1 ? 'current ' : ''" data="{{$site_name}}">
                                    <b>
                                    {{ trans('messages.home.liked') }}
                                </b>
                                    <small>{{count($like_count) }}</small>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" ng-click="show= 3" class="profile_tab" ng-class="show == 3 ? 'current ' : ''" data="stores">
                                    <b>
                                    {{ trans('messages.home.stores') }}
                                </b>
                                    <small>@{{ stores.length }}</small>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" ng-click="show= 4" class="profile_tab" ng-class="show == 4 ? 'current ' : ''" data="stores">
                                    <b>
                                    {{ trans('messages.home.wishlists') }}
                                </b>
                                    <small>@{{ Wishlists.length }}</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="pro_follow pos-rel profile-real col-lg-2 col-sm-3 mt-3 mt-lg-0 text-center col-12">
                        @if ($errors->has('profile_image'))
                        <p class="help-inline" style="color:red">
                            {{ $errors->first('profile_image') }}
                        </p>
                        @endif @if ($errors->has('cover_image'))
                        <p class="help-inline" style="color:red">
                            {{ $errors->first('cover_image') }}
                        </p>
                        @endif @if(@Auth::user()->id!='' && @$user->id!=Auth::user()->id)
                        <div ng-controller="FollowController" class="profilefollower">
                            <input type="hidden" id="follower_id" value="{{ @Auth::user()->id}}">
                            <input type="hidden" id="user_id" value="{{ @$user->id }}">

                            <button type="button" class="btn btn-primary btn-follow user_follows {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" id="follow" ng-click="FollowData()"><i class="fa fa-plus" aria-hidden="true"></i>{{ @$follow }}</button>
                        </div>
                        @endif @if(@Auth::user()->user_name == @$user->user_name)
                        <a href="{{url('/edit_profile')}}" class="edit-profile btns-gray-embo">{{ trans('messages.profile.edit_profile') }}</a> @endif

                        <div class="menu-content" style="display: none;">
                            <ul class="list-option">
                                <li><a href="javascript:;">RSS Feed</a></li>
                                <li><a href="javascript:;">Share Profile</a></li>
                                <li><a href="javascript:;">Activity Log</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div ng-show="show == 1" class="nopad cls_thingspro">
                <ul class="{{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}} check_login row nopad overflow_visible" ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}}; count={{ $like_count }}" data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" id="show_spiffy">
                    <li id="all-@{{$index}}" class="col-lg-4 col-md-6 col-12 mb-3 cls_thingsproli" ng-repeat="all in count" ng-cloak title="@{{ all.products.title}}">
                        <div class="min-size short-padding">
                            <a class="thingsimga" href="{{url('things')}}/@{{all.products.id}}">
                                <div class="thingsimg" id="@{{ all.products.id }}" ng-init="like_limit='6'" style="background-image:url('@{{ all.products.image_name }}');">
                                </div>
                                <div class="cls_storehead">
                                    <span class="img-title text-truncate" >
                                    @{{ all.products.title}}
                                    </span>
                                    <button class="cls_price not-clickable" type="submit">
                                        $ @{{all.products.price}}
                                    </button>
                                </div>
                            </a>
                                <div class="img-content pro_likes">
                                    <span ng-if='all.products.user_like.length'>
                                    <button class="btn-like product_like  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all.products,all.products.id)' data= '@{{ all.products.id }}'>
                                        <span><i></i></span> @{{ all.products.like_user.length }}
                                    </button>
                                    </span>
                                    <span ng-if='!all.products.user_like.length '>
                                    <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}}  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all.products,all.products.id)' data='@{{ all.products.id }}'>
                                        <span><i></i></span> @{{ all.products.like_user.length }}
                                    </button>
                                    </span>
                                    
                                    <span class="cls_share dropdown dropleft">
                                          <a href="#" id="sharelist1" class="btn-more more_List" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                          <div class="dropdown-menu" aria-labelledby="sharelist" >
                                            <div class="sub-more share cls_shareimg">
                                                <b>{{ trans('messages.home.share') }}</b>
                                                <small>{{ trans('messages.home.share_friends') }}</small>
                                                <span class="sharable" style="display:none">
                                                    <a href="@{{ all.products.share_url.facebook }}" target="_blank" class="share_facebook"><i class="icon icon-facebook"></i></a>
                                                    <a href="@{{ all.products.share_url.twitter }}" target="_blank" class="share_twitter"><i class="icon icon-twitter"></i></a>
                                                </span>
                                            </div>
                                            <div class="popover-body add-wishlist">
                                                <div ng-class="(@{{all.products.id}} !='' && @{{all.products.id}}!=null) ? 'icon-heart cls_wish' : 'icon-heart-o cls_wish' " class='wishlist_@{{all.products.id}} cls_wish' id="wishlist_@{{all.products.id}}" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.products.id )">
                                                    <span ng-if="all.products.wishlist.id !='' && all.products.wishlist.id!=null"> <b>{{ trans('messages.home.saved_wishlist') }} </b> <small>{{ trans('messages.home.click_unsave') }}</small></span>
                                                    <span ng-if="all.products.wishlist.id==null">
                                                    <b> {{ trans('messages.home.save_wishlist') }} </b><small>{{ trans('messages.home.save_your_wishlist') }}</small></span>
                                                </div>
                                            </div>
                                            <div class="sub-more copy cls_shareimg" >
                                                <b class="copy-to-clipboard" data-copy="{{url('things')}}/@{{all.products.id}}">{{ trans('messages.home.copy_link') }}</b>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                        </div>
            
                    </li>

                </ul>

                <span ng-if='count.length ==0'>
                    <div id="search-result-empty" class="cls_empty search-result-empty">   
                        <i class="fa fa-search"></i>         
                        <h3>{{ trans('messages.home.no_items_found') }}</h3>
                    </div> 
                </span>
            </div>
            <input type="hidden" value="{{ @$user->id }}" id="user_id">
            <div id="show_stores" class="row nopad cls_storelist" ng-show="show == 3">
                <div class="col-lg-4 col-md-6 col-12 my-3 item short-padding" ng-repeat="item in stores" id='profile_stores'>
                    <div class="mb-4 more_products loading" ng-show="ajax_loading"></div> 
                    <span ng-if='stores.length !=0'>

                    <div class="cover-photo-div pb-3 ">
                        <div class="cover-photo">           
                            <img ng-src= "@{{item.store_details.header_img}}" style="width:100%" />
                         </div>

                        <div class="profile-photo">
                            <a href="{{ url('store') }}/@{{item.store_details.id}}" >
                                <span ng-if="item.store_details.logo_img" class="cls_span">
                                    <img class="profile-img" ng-src="@{{ item.store_details.logo_img }}">
                                </span>
                                <span ng-if="!item.store_details.logo_img">
                                 <img class="profile-img" src="{{ url('image/profile.png') }}">
                                </span>
                            </a>
                       
                            <div class="store_details">
                                <b class="text-center">@{{item.store_details.store_name}}</b>
                                <small>@{{ item.store_details.user_address[0].city }} @{{ item.store_details.user_address[0].country }}</small>
                            </div>
                        </div>
                            <div class="profile-follow">
                                <a href="javascript:;" class="follow-store {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" id="store_@{{item.store_details.id }}" ng-click="HomeFollowStore(item.store_details.id,{{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }})">
                                    <span ng-if="item.store_details.follow_store.id!=null"> {{ trans('messages.home.following_store') }}</span>
                                    <span ng-if="item.store_details.follow_store.id==null"> {{ trans('messages.home.follow_store') }}</span>
                                </a>
                            </div>
                         
                         </div>
                    </span>
                </div>
                <span ng-if='stores.length ==0'>
                    <div id="search-result-empty" class="cls_empty search-result-empty">   
                        <i class="fa fa-search"></i>         
                        <h3>{{ trans('messages.home.no_stores_found') }}</h3>
                    </div> 
                </span>
            </div>
            <div id="show_stores" class="cls_thingspro nopad" ng-show="show == 4">
                <ul class="{{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}} check_login row  nopad overflow_visible" ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}}; count={{ $like_count }}" data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" id="show_store_products" ng-cloak class="col-lg-12 col-md-12 col-sm-12 nopad">
                    <div class="mb-4 more_products loading" ng-show="ajax_loading"></div> 
                    <li id="all-@{{$index}}" class="col-lg-4 col-12 col-md-6 mb-3 cls_thingsproli" ng-repeat="all in Wishlists" ng-cloak>
                        <div class="min-size short-padding">
                        <a class="thingsimga" href="{{url('things')}}/@{{all.wish_product_details.id}}">
                            <div class="pro_21 thingsimg" id="@{{ all.wish_product_details.id }}" ng-init="like_limit='6'" style="background-image:url('@{{ all.wish_product_details.image_name }}');">
                            </div>
                            <div class="img-content cls_storehead">
                                <span class="img-title text-truncate">@{{ all.wish_product_details.title}}</span>
                                <button class="cls_price not-clickable" type="submit">$ @{{all.wish_product_details.price}}</button>
                            </div>
                        </a>
                            <div class="img-content pro_likes">
                                <span ng-if='all.wish_product_details.user_like.length'>
                                <button class="btn-like product_like  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all.wish_product_details,all.wish_product_details.id)' data= '@{{ all.wish_product_details.id }}'><span><i></i></span>@{{ all.wish_product_details.like_user.length }}</button>
                                </span>
                                <span ng-if='!all.wish_product_details.user_like.length '>
                                <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}}  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all.wish_product_details,all.wish_product_details.id)' data= '@{{ all.wish_product_details.id }}' ><span><i></i></span>@{{ all.wish_product_details.like_user.length }}</button>
                                </span>
                               
                              
                                <span class="cls_share dropdown dropleft">
                              <a href="#" id="sharelist1" class="btn-more more_List"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&nbsp;</a>
                              <div class="dropdown-menu" aria-labelledby="sharelist1">
                                            <div class="sub-more share cls_shareimg">
                                    <b>{{ trans('messages.home.share') }}</b><small>{{ trans('messages.home.share_friends') }}</small>
                                    <span class="sharable" style="display:none">
                                        <a href="@{{ all.wish_product_details.share_url.facebook }}" target="_blank" class="share_facebook"><i class="icon-facebook"></i></a>
                                        <a href="@{{ all.wish_product_details.share_url.twitter }}" target="_blank" class="share_twitter"><i class="icon-twitter"></i></a>
                                    </span>
                            </div>
                            <div class="popover-body add-wishlist">
                                <div ng-class="(all.wish_product_details.id !='' && all.wish_product_details.id!=null) ? 'icon-heart cls_wish' : 'icon-heart-o cls_wish' " class='wishlist_@{{all.wish_product_details.id}} cls_wish' id="wishlist_@{{all.wish_product_details.id}}" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.wish_product_details.id )">
                                    <span ng-if="all.wish_product_details.id !='' && all.wish_product_details.id!=null"> <b>{{ trans('messages.home.saved_wishlist') }}</b> <small>{{ trans('messages.home.click_unsave') }}</small></span>
                                    <span ng-if="all.wish_product_details.id==null"><b> {{ trans('messages.home.save_wishlist') }}</b> <small>{{ trans('messages.home.save_your_wishlist') }}</small></span>
                                </div>
                            </div>
                            <div class="sub-more copy cls_shareimg" data-copy="{{url('things')}}/@{{all.wish_product_details.id}}">
                                <i class="fa fa-chain-broken copy"></i>
                                <small class="copy-to-clipboard">{{ trans('messages.home.copy_link') }}</small>
                            </div>

                        </div>
                        </span>
                        </div>
                        </div>
                        <!-- </a> -->
                    </li>

                </ul>
                <span ng-if='Wishlists.length ==0'>
                    <div id="search-result-empty" class="cls_empty search-result-empty">   
                        <i class="fa fa-search"></i>         
                        <h3>{{ trans('messages.home.no_wishlists_found') }}</h3>
                    </div> 
                </span>
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
                                            </a>
                                            <div class="col-md-2  col-sm-2 col-xs-2 nopad" ng-model="all_product_like_user[$index].users.user_follow" ng-if="current_userid!=user_likes.users.id">
                                                <a ng-if="user_likes.users.user_follow=='0'" ng-click="likeuser_follow($index)"><i class="icon icon-male-user-with-plus-symbol1"></i></a>
                                                <a ng-if="user_likes.users.user_follow!='0'" ng-click="likeuser_unfollow($index)"><i class="icon icon icon-user-with-tick"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div id="no_likes" class="cls_empty search-result-empty" style="display:none">
                                    <i class="fa fa-search"></i>
                                    <h3>{{ trans('messages.home.popular_result_empty') }}</h3>
                                    <p>{{ trans('messages.home.search_result_empty_desc') }}</p>
                                </div>
                                <div class="whiteloading_like" id="likes_loading" style="display:none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="add-fancy-back profile-cover-img-popup" style="display: none;">
                <div class="d-flex align-items-center flex-wrap cls_modal_height">
                    <div class="back-white pos-top mar-auto flt-none nopad col-xs-11 upload-cover-img-content ">
                        <h2 class="fancy-head-popup">{{ trans('messages.profile.add_cover_image') }}</h2> {{ Form::open(array('url' => 'upload_cover_img', 'files' => true,'id' => 'upload_cover_img')) }}
                        <div class="file py-3">
                            <p style="font-weight: bold;">{{ trans('messages.profile.upload_image') }}</p>
                            <input type="file" id="cover_image" name="cover_image" accept="image/*">
                        </div>
                        <div class="btns-area text-right border-top pt-3">
                            <button type="button" class="btn btn-primary btn-back cancel_">{{ trans('messages.profile.cancel') }}</button>
                            <button class="btn-blue-fancy btn btn-secondary">{{ trans('messages.profile.upload_image') }}</button>
                        </div>
                        <button type="button" class="ly-close" title="Close"><i class="icon-close"></i></button>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>

            <div class="add-fancy-back profile-img-popup" style="display: none;">
                <div class="d-flex align-items-center flex-wrap cls_modal_height">
                    <div class="back-white pos-top mar-auto flt-none nopad col-xs-11 upload-img-content ">
                    <h2 class="fancy-head-popup">{{ trans('messages.profile.upload_profile_image') }}</h2> {{ Form::open(array('url' => 'upload_profile_img', 'files' => true)) }}
                    <div class="file py-3">
                        <p style="font-weight: bold;">{{ trans('messages.profile.upload_image') }}</p>
                        <input type="file" id="profile_image" name="profile_image" accept="image/*">
                    </div>
                    <div class="btns-area text-right border-top pt-3">
                        <button type="button" class="btn btn-primary btn-back cancel_">{{ trans('messages.profile.cancel') }}</button>
                        <button class="btn-blue-fancy btn btn-secondary">{{ trans('messages.profile.upload_image') }}</button>
                    </div>
                    <button type="button" class="ly-close" title="Close"><i class="icon-close"></i></button>
                    {{ Form::close() }}
                </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
@stop
