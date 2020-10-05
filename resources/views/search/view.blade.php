<input type="hidden" name="category" id="page_category" value="{{ request()->segment(3) }}">
<input type="hidden" id="page_name" value="{{ $page }}">
<?php
$no_store_url=url('image/cover_image.jpg');
$no_product_url=url('image/profile.png');
?> 
@if(@$search_for!='' && @$search_for != 'things')
<ul class="{{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}} check_login col-xs-12 nopad overflow_visible" ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}}" data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" infinite-scroll='search_product()' infinite-scroll-distance='1' infinite-scroll-disabled='search_busy' ng-init="count=0" ng-cloak id="search_product">
<!-- start long-padding -->
    @if(@$search_for=='people' )
    <li id="all-@{{$index}}" ng-repeat="all in all_search" ng-cloak>
        <div class="col-md-4 col-sm-12 col-xs-12 col-lg-4 short-padding st_name" >
            <div class="cover-photo-div ">
                <div class="cover-photo" > 
                    <img ng-src= "@{{all.original_cover_image_name}}" style="width:100%" />          
                </div>
                <div class="profile-photo">
                    <a href="{{url('profile')}}/@{{all.user_name}}" class="profile-photo-img">
                    <span ng-if="all.original_image_name">
                        <img class="img-round profile-img" ng-src="@{{ all.original_image_name }}">
                    </span>
                    <span ng-if="!all.original_image_name">
                        <img class="img-round profile-img" src="{{ url('image/profile.png') }}">
                    </span>
                    </a>
                    <p class=" text-center  ">@{{all.full_name}}</p>
                    <em>@{{all.user_name}}</em>
                   
                </div>
                <div class="profile-follow"> 
                @if(@Auth::user()->id !='')
                <span ng-if="all.id != {{ @Auth::user()->id}}">    
                    <a href="javascript:;" class="user_follow {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}"  id="follow_@{{all.id }}" ng-click="FollowData(all.id,{{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }})">
                        <span ng-if='all.user_follow!=0'>
                            {{ trans('messages.home.following') }}
                        </span>
                        <span ng-if='all.user_follow==0'>
                            {{ trans('messages.home.follow') }}
                        </span>
                    </a>  
                </span> 
                @else
                     <a href="javascript:;"  class="user_follow {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" id="follow_@{{all.id }}" ng-click="FollowData(all.id,{{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }})">
                        <span ng-if='all.user_follow!=0'>
                             {{ trans('messages.home.following') }}
                        </span>
                        <span ng-if='all.user_follow==0'>
                            {{ trans('messages.home.follow') }}
                        </span>
                    </a>  
                @endif 
                </div> 
            </div>
        </div>
    </li>
    @elseif(@$search_for == 'brands')
    <li id="all-@{{$index}}" ng-repeat="all in all_search" ng-cloak>
        <div class="col-md-4 col-sm-12 col-xs-12 col-lg-4 short-padding" >
            <div class="cover-photo-div ">

                <div class="cover-photo">           
                <img ng-src= "@{{all.header_img}}" style="width:100%" />
                </div>
                <div class="profile-photo">
                    <a href="{{ url('store') }}/@{{all.id}}"  class="profile-photo-img">
                    <span ng-if="all.logo_img">
                        <img class="img-round profile-img" ng-src="@{{ all.logo_img }}">
                    </span>
                    <span ng-if="!all.logo_img">
                        <img class="img-round profile-img" src="{{ url('image/profile.png') }}">
                    </span>
                    </a>
                    <p class="text-center  ">@{{all.store_name}}</p>
                    <em>@{{all.user_address[0].city}},@{{all.user_address[0].country}}</em>
                   
                </div>

                <div class="profile-follow" >      
                @if(@Auth::user()->id !='')
                <span ng-if="all.user_id != {{ @Auth::user()->id}}">      
                    <a href="javascript:;" class="follow-store store_@{{all.id }} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click="HomeFollowStore(all.id,{{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }})"> 
                        <span ng-if="all.follow_store.id!=null" class="btn-secondary"> {{ trans('messages.home.following_store') }}</span>
                        <span ng-if="all.follow_store.id==null"> {{ trans('messages.home.follow_store') }}</span> 
                    </a>
                </span>  
                @else
                     <a href="javascript:;" class="follow-store store_@{{all.id }} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click="HomeFollowStore(all.id,{{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }})"> 
                        <span ng-if="all.follow_store.id!=null" class="btn-secondary">{{ trans('messages.home.following_store') }}</span>
                        <span ng-if="all.follow_store.id==null"> {{ trans('messages.home.follow_store') }}</span> 
                    </a>
                @endif  
                </div>                 
            </div>
        </div>
    </li>
    @elseif(@$search_for == 'things')
    <li id="all-@{{$index}}" ng-repeat="all in all_search" ng-cloak>
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-4 short-padding" >
            <div class="cover-photo-div ">

                <div class="cover-photo">           
                <img ng-src= "@{{all.header_img}}" style="width:100%" />
                </div>
                <div class="profile-photo">
                    <a class="profile-photo-img">
                    <span ng-if="all.logo_img">
                        <img class="img-round profile-img" ng-src="@{{ all.logo_img }}">
                    </span>
                    <span ng-if="!all.logo_img">
                        <img class="img-round profile-img" src="{{ url('image/profile.png') }}">
                    </span>
                    </a>
                    <p class="profile-name text-center  ">@{{all.store_name}}</p>
                    <em>@{{all.user_address[0].city}},@{{all.user_address[0].country}}</em>
                   
                </div>

                <div class="profile-follow"> 
                    <p>Follow Store</p>    
                </div> 
            </div>
        </div>
    </li>
    <!-- @elseif(@$for=='lists')
    This is {{$for}} -->
     @endif
</ul>
@else
@if(@$search_for == '')
<h5 class='topresult text-muted' style="display: none">Top Results</h5>
<div class="row1">
<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 nopad top_results search_stores" style="display:none;">
        <div class="popular-head">
            <h3>Stores</h3>
        </div>
    <div class="glob-back">
        <ul class="store-list"  >
            <li class="bor-bot" ng-repeat="allbrands in all_brands" ng-cloak>
                <a class="pro-logo flt-left">
                    <span ng-if="allbrands.logo_img">
                        <img ng-src="@{{ allbrands.logo_img }}" width="40px">
                    </span>
                    <span ng-if="!allbrands.logo_img">
                        <img src="{{ url('image/profile.png') }}" width="40px">
                    </span>
                </a>
                <a href="{{ url('store') }}/@{{allbrands.id}}" class="pro-name flt-left">@{{allbrands.store_name}}
                <br/>
                <small>@{{allbrands.user_address[0].city}},@{{allbrands.user_address[0].country}}</small></a>
            </li>               
        </ul>
        <a href="{{ url('search?search_key='.$search_key.'&search_for=brands') }}" class="register-btn col-xs-12 col-lg-12 col-md-12 col-sm-12 back-white mar-0">See More</a>
    </div>
</div>

<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 nopad top_results search_people"  style="display: none" >
        <div class="popular-head">
            <h3>People</h3>
        </div>
    <div class="glob-back">
        <ul class="store-list">
            <li class="bor-bot" ng-repeat="allpeople in all_people" ng-cloak>
                <a class="pro-logo flt-left">
                    <span ng-if="allpeople.original_image_name">
                        <img class="img-round" width="35px" height="35px" ng-src="@{{ allpeople.original_image_name }}">
                    </span>
                    <span ng-if="!allpeople.original_image_name">
                        <img class="img-round" width="35px" height="35px" src="{{ url('image/profile.png') }}">
                    </span>                   
                </a>
                <a href="{{url('profile')}}/@{{allpeople.user_name}}" class="pro-name flt-left">@{{allpeople.full_name}}
                    <br/>
                    <small>@{{allpeople.user_name}}</small>
                </a>
            </li>   
        </ul>
        <a href="{{ url('search?search_key='.$search_key.'&search_for=people') }}" class="register-btn col-xs-12 col-lg-12 col-md-12 col-sm-12 back-white mar-0">See More</a>
    </div>
</div>

<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 nopad top_results" style="display: none;">
        <div class="popular-head">
            <h3>Lists</h3>
        </div>
    <div class="glob-back">
        <ul class="store-list">
            <li class="bor-bot">
                <a class="pro-logo flt-left">
                    <img src="image/product-logo.png" width="40px">
                </a>
                <a class="pro-name flt-left">Generate Design<small>502 Products</small></a>
            </li>   
            <li class="bor-bot">
                <a class="pro-logo flt-left">
                    <img src="image/product-logo.png" width="40px">
                </a>
                <a class="pro-name flt-left">Generate Design<small>502 Products</small></a>
            </li>
            <li class="bor-bot">
                <a class="pro-logo flt-left">
                    <img src="image/product-logo.png" width="40px">
                </a>
                <a class="pro-name flt-left">Generate Design<small>502 Products</small></a>
            </li>
        </ul>
        <a href="#" class="register-btn col-xs-12 col-lg-12 col-md-12 col-sm-12 back-white mar-0">See More</a>
    </div>
</div>
</div>


@endif
<h5 class="text-muted" ng-if="all_items.length">Items</h5>
<div class="clearfix search_item" style="display: none;">
@if($search_for =='')


@endif
    <ul class="{{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}} check_login col-xs-12 nopad overflow_visible" ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}}" data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" infinite-scroll='search_item()' infinite-scroll-distance='1' infinite-scroll-disabled='search_busy' ng-init="count=0" ng-cloak id="search_product">
        <li  id="all-@{{$index}}"  ng-repeat="all in all_items" ng-cloak>
        <!-- <a href="{{ url('things') }}/@{{ all.id }}"> -->
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-4 short-padding hole_vie" >
                <div class=" popshow img-height-shorted newshot" id="@{{ all.id }}" >
                    <!-- <video preload="auto" loop="true" muted="true" poster="https://thingd-media-ec5.thefancy.com/video/output/FancyMerchant/saleitem/1164847/20170104214924/thumbs/00001.png" autoplay="" ori_volume="1" style="width: 100%;">
                        <source src="https://thingd-media-ec1.thefancy.com/video/upload/FancyMerchant/saleitem/1164847/20170104214924/boat.mp4" type="video/mp4"></source>
                    </video> -->     
 <img width="100%" ng-src="@{{ all.image_name }}">       
                         
                </div>
                <div class="img-content col-xs-12 col-lg-12 col-md-12 col-sm-12 nopad bor-bot text_sec">
                    <a class="img-title flt-left" href="{{url('things')}}/@{{all.id}}">@{{ all.title}}</a>
                    <button class="btn-blue  mar-10 flt-right bold not-clickable" type="submit"><span ng-bind-html="all.currency_symbol"></span>  @{{all.price}}</button>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 img-content pad-10 heg1">
                    <span ng-if= 'all.user_like.length'>
                        <button class="btn-like product_like  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}' type="submit"><span><i></i></span>@{{ all.like_user.length }}</button>
                    </span>
                    <span ng-if= '!all.user_like.length '>
                        <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}' type="submit"><span><i></i></span>@{{all.like_user.length }}</button>
                    </span>
                <span class="like-link">

                <span class="popover-wrapper right user-pic" ng-repeat="like_user in all.like_user | limitTo:3" ng-cloak>
                    <a href="{{url('profile')}}/@{{like_user.users.user_name}}" data-id="user_@{{all.id}}_@{{like_user.users.id}}" class="user_a user_@{{all.id}}_@{{like_user.users.id}}" >  

                   <!--  <a href="{{url('profile')}}/@{{like_user.users.user_name}}" data-role="popover" data-id="user_@{{all.id}}_@{{like_user.users.id}}"  data-target="example-popover-3" class="user_a user_@{{all.id}}_@{{like_user.users.id}}" >   -->


                    <span ng-if="like_user.users.original_image_name">
                    <img ng-src="@{{ like_user.users.original_image_name }}" class="user-img">
                    </span>
                    <span ng-if="!like_user.users.original_image_name">
                        <img class="user-img" src="{{ url('image/profile.png') }}">
                    </span>
                    </a>
                      <div class="popover-modal example-popover-3">
                        <a href="{{url('profile')}}/@{{like_user.users.user_name}}">
                        <div class="popover-body">
                            <div class="user_background">
                                <img class="" ng-src="@{{ like_user.users.original_cover_image_name }}">
                            </div>
                            <div class="user_profile_image">
                                <img class="" ng-src="@{{ like_user.users.original_image_name }}">
                                <p class="userfull_text">@{{like_user.users.full_name}}</p>
                                <p class="user_text">@{{like_user.users.user_name}}</p>
                                <p>
                                    <span class="follow_text"><span class="follow_count">@{{like_user.users.user_follower}}</span> {{ trans('messages.home.followers') }} </span>.
                                    <span class="follow_text"><span class="follow_count">@{{like_user.users.user_following}}</span>  {{ trans('messages.home.following') }} </span>
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
                
                <a ng-if='all.like_user.length' class="like_user_more" ng-click="like_user_loadMore(all.id)" type="button" data-toggle="modal" data-target="#view_like_user"><i data-icon="D" class="icon"></i></a>
            </span>
                      <span class="popover-wrapper show-more-options right flt-right">
              <a href="#" data-role="popover"  class="btn-more more_List"  data-target="example-popover-2">&nbsp;</a>
              <div class="popover-modal example-popover-2">
                <div class="sub-more share">
                    <i class="fa fa-share share"></i>
                    <b>{{ trans('messages.home.share') }}</b><small>{{ trans('messages.home.share_friends') }}</small>
                    <span class="sharable" style="display:none">
                    <a href="@{{ all.share_url.facebook }}" target="_blank" class="share_facebook"><i class="fa fa-facebook-square"></i></a>
                    <a href="@{{ all.share_url.gplus }}" target="_blank" class="share_google"><i class="fa fa-google-plus-square"></i></a>
                    <a href="@{{ all.share_url.twitter }}" target="_blank" class="share_twitter"><i class="fa fa-twitter-square"></i></a>
                    </span>
                </div>
                
                <!-- <div class="sub-more">
                    <span ng-if="all.wishlist.length">
                        <i class="fa fa-heart-o wishlist"></i>
                        <b>Save to Wishlist</b><small>Save this to your Wishlist</small>
                    </span>
                    <span ng-if="!all.wishlist.length">
                        <i class="fa fa-heart wishlist"></i>
                        <b>Saved to Wishlist</b><small>Click to unsave</small>
                    </span>
                </div>
 -->

                <div class="popover-body add-wishlist">
                    <div ng-class="(all.wishlist.id !='' && all.wishlist.id!=null) ? 'fa fa-heart btn btn-blue' : 'fa fa-heart-o btn btn-blue' " id="wishlist_@{{all.id}}" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.id )"> 
                    <span ng-if="all.wishlist.id !='' && all.wishlist.id!=null"> {{ trans('messages.home.saved_wishlist') }} <small>{{ trans('messages.home.click_unsave') }}</small></span>
                    <span ng-if="all.wishlist.id==null"> {{ trans('messages.home.save_wishlist') }} <small>{{ trans('messages.home.save_your_wishlist') }}</small></span>
                    </div>
                </div>
                <div class="sub-more copy" data-copy="{{url('things')}}/@{{all.id}}">
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
</div>
@endif

<div id="search-result-empty" class="empty search-result-empty" style="display:none;">   
    <i class="fa fa-search"></i> 
        <h3><h3>{{ trans('messages.home.result_empty_search') }}</h3> "{{@$search_key}}".</h3>
     <p>{{ trans('messages.home.search_result_empty_desc') }}</p>
</div>

<div class="loading products_loading" id="products_loading" style="display:none"></div>
@include('products.popup')

<div id='popup_container' style="top: 0px; display: none; opacity: 0;">
    <div class="popup policy_detail add-fancy-back">
        <div class="d-flex align-items-center flex-wrap cls_modal_height">
            <div data-reactroot="" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Return Policy</h5>
                    <button class="ly-close close" title="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
               
                <div class="terms">
                    <p>This item is final sale, non-returnable and non-exchangeable.</p>
                </div>
            
            </div>
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
                <label ng-if="$index=='0'" class="firstname">Added by</label>
                <label ng-if="$index!='0'" class="firstname">
                <a href="{{ url('profile') }}/@{{ user_likes.users.original_user_name }}" class="link_user_name">@{{ user_likes.users.original_full_name }}</a></label>
                <p class="normal">@ @{{ user_likes.users.original_user_name }}</p>
                </div>
                
                <div class="col-md-2  col-sm-2 col-xs-2 nopad" ng-model="all_product_like_user[$index].users.user_follow" ng-if="current_userid!=user_likes.users.id">
                    <a ng-if="user_likes.users.user_follow=='0'"  ng-click="likeuser_follow($index)"><i class="icon icon-male-user-with-plus-symbol1"></i></a>
                    <a ng-if="user_likes.users.user_follow!='0'"  ng-click="likeuser_unfollow($index)"><i class="icon icon icon-user-with-tick"></i></a>
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