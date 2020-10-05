@extends('template')
@section('main')
<main id="site-content" role="main">
    <div class="container">
        <div class="cls_detail_page" ng-controller="products_details" ng-cloak>
            <div class="row" ng-init="product_id = {{ $result->id }}">
                <div class="nopad main-content cls_prodetails_left col-lg-7 col-12 p-0 pb-3" id="mainContent">
                    <div class="figure-section">
                        <div class="figure-cont detail-cont img_mag">
                            <ul class="thumb_bxslider">
                                @if($result->video_src != '')
                                <li>
                                    <div class="video_player">
                                        <video loop="true" muted="true" playsinline="true"  class="img-responsive" preload="none"  autoplay>
                                            <source  src="{{$result->video_src}}" type="video/{{$result->video_type}}" class="ng-scope" />
                                        </video>
                                    </div>
                                </li>
                                @endif
                                @foreach($result->product_photos as $photos)
                                <li>
                                    <img style="width: 100%; height: 600px;" src="{{ $photos->images_name }}" class="zoom_slider" style="object-fit: cover; object-position: center;">
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-12 nopad figure-detail">
                            <div id="bx-pager" class="thumbnail-list">
                                <ul>
                                    @if($result->video_src != '')
                                    <li>
                                        <a data-slide-index="0" href="" class="video-thumb">
                                            <img src="{{$result->video_thumb}}" />
                                        </a>
                                    </li>
                                    @endif
                                    @foreach($result->product_photos as $photos)
                                    <li>
                                        <a data-slide-index="{{ ($result->video_src != '') ? $loop->index+1 : $loop->index }}" href="">
                                            <img src="{{ $photos->images_name }}" />
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="thumbnail-dis cls_desc">
                                <div class="detail">
                                    <h4 class="tit"> @lang('messages.products.description') </h4>
                                    <div> {!! $result->description !!}</div>
                                    <a class="more"> @lang('messages.products.show_more') </a>
                                    <a class="less"> @lang('messages.products.show_less') </a>
                                </div>
                                <ul class="after">
                                    <li class="international shipping">
                                        <label> @lang('messages.home.estimated_delivery') </label>
                                            <span class="able">
                                                {{ $result['products_shipping'][0]->start_window }} -
                                                {{ $result['products_shipping'][0]->end_window }} {{ trans('messages.home.days_to') }}
                                                <a class='shipping_country_list'>{{$result['products_shipping'][0]->ships_to }}</a>
                                            </span>
                                            <span class="unable" style="display: none;"> Unable to ship to
                                            <a>IN</a>
                                        </span>
                                    </li>
                                    <li class="shipping">
                                        <label> @lang('messages.home.ships_from') </label>
                                        <span> {{ @$result->products_shipping->first()->ships_from }} </span>
                                    </li>
                                    <li>
                                        <label> @lang('messages.home.return_policy') </label>
                                        @if(@$result->return_policy == 1)
                                            <span> @lang('messages.home.no_returns') </span>
                                            <p class="return_policy_details" style="display: none"> @lang('messages.home.no_returns_desc') </p>
                                        @elseif(@$result->return_policy == 2)
                                            <span> 15 @lang('messages.home.day_return') </span>
                                            <p class="return_policy_details" style="display: none"> @lang( 'messages.home.return_desc') </p>
                                        @elseif(@$result->return_policy == 3)
                                            <span> 30  @lang('messages.home.day_return') </span>
                                            <p class="return_policy_details" style="display: none"> @lang('messages.home.return_desc') </p>
                                        @endif
                                        <a class='policy_detailed'> @lang('messages.home.view_details') </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="category" id="page_category" value="{{ $result->categories->id }}">
                    <input type="hidden" name="id" id="product_id" value="{{ $result->id }}">
                </div>
            
            <div class="col-lg-5 col-12 cls_detail_info mt-3 mt-lg-0">
                <div class="figure-info nopad mar-0">
                    <h2 class="title text-truncate">{{ $result->title }}</h2>
                    <p class="price">
                        <big class="sale">{!! $code !!} <span id="product_price_{{ $result->id }}">{{$price}} </span>
                        <small class="usd">
                        <a class="code currently-usd">{{ $product_currency}}</a>
                        </small>
                        </big>
                        <span id='currency_sym_{{ $result->id }}' style="display: none"> {!! $code !!}  </span>
                        <span class="sales">
                            <span id="product_retail_price_{{ $result->id }}">
                                
                                @if($result->products_prices_details->retail_price !='')
                                <em>{!! $code !!} {{$result->products_prices_details->retail_price}}
                                </em>
                                @endif
                            </span> <span id="product_discount_{{ $result->id }}">
                            @if ($result->products_prices_details->retail_price !='')
                            ( {{ trans('messages.home.save') }} {{ $discount }}%)
                            @endif
                        </span>
                    </span>
                </p>
                <div class="frm">
                    <fieldset class="sale-item-input clearfix">
                        <p>
                            <label>{{ trans('messages.home.option') }}</label>
                            <span class="trick-select option">
                                @if($result['product_option']->count() > 0)
                                <select id="option" class="select-boxes2 change_option product_option select-option form-control" >
                                    @foreach ($result['product_option'] as $key => $value)
                                    <option value="{{ $value['id'] }}">{{ $value['option_name'] }}</option>
                                    @endforeach
                                </select>
                                @else
                                <select id="option" class="select-boxes2 select-option form-control" disabled>
                                    <option value=''>{{ $result->title }}</option>
                                </select>
                                @endif
                            </span>
                        </p>
                        <p>
                            <label>{{ trans('messages.home.quantity') }}</label>
                            <span class="trick-select quantity">
                                @if($quantity != "" && $quantity > 0)
                                <select id="quantity"  class="select-boxes2 form-control" >
                                    @for($i=1;$i<=$quantity;$i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                @else
                                <select id="quantity"  class="select-boxes2 form-control" >
                                    <option value = '1'>1</option>
                                </select>
                                @endif
                            </span>
                        </p>
                        <span style="display: none" id="productid">{{@$result->id}}</span> {{ csrf_field() }}
                        <span style="display: none" id="userid">{{ @$result->user_id }}</span> 
                        @if($result->user_id != Auth::id() && $result->sold_out !='Yes')
                        <button class="add_to_cart green-btn prced-btn btn {{ (!Auth::id()) ? 'without_login' : ''}}">{{ trans('messages.home.add_to_cart') }}</button>
                        @elseif($result->sold_out == 'Yes')
                             <span class="soldout green-btn prced-btn btn {{ (!Auth::id()) ? 'without_login' : ''}}">{{ trans('messages.products.soldout') }}</span>
                        @endif
                    </fieldset>
                </div>
                <hr>
                <!-- <div class="figure-button"> -->
                <div class="pro_likes pad-10" ng-init="detailed_product = {{ @$result }}">
                    <span ng-if='detailed_product.user_like.length'>
                        <button class="btn-like product_like {{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click= 'product_like(detailed_product,detailed_product.id)' id= '@{{ detailed_product.id }}'  ng-cloak ><span ><i ng-bind="@{{ detailed_product.like_user.length}}"></i></span></button>
                    </span>
                    <span ng-if='!detailed_product.user_like.length '>
                    <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click= 'pdu_like(detailed_product,detailed_product.id)' id= '@{{ detailed_product.id }}' ><span><i ng-bind="@{{ detailed_product.like_user.length}}"></i></span ></button>
                </span>
                <span class="cls_share dropdown dropleft">
                    <a href="#" id="sharelist1"  class="btn-more more_List" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                    <div class="dropdown-menu" aria-labelledby="sharelist">
                        <div class="cls_shareimg cls_likeby" >
                            <b data-toggle="modal" data-target="#view_like_user" ng-click="showLikedUsers(product_id);"> {{ $site_name }}'d </b>
                            <small> @lang('messages.products.peope_who_spiffyd',["site_name"=>$site_name]) </small>
                        </div>
                        <div class="sub-more share cls_shareimg">
                            <b> @lang('messages.home.share') </b> <small> @lang('messages.home.share_friends') </small>
                            <span class="sharable" style="display:none">
                                <a href="{{ @$share_url['facebook'] }}" target="_blank" class="share_facebook"><i class="icon icon-facebook"></i></a>
                                <a href="{{ @$share_url['twitter'] }}" target="_blank" class="share_twitter"><i class="icon icon-twitter"></i></a>
                            </span>
                        </div>
                        <div class="popover-body add-wishlist" style="cursor: pointer;">
                            <div class="wishlist_{{ $result->id }} {{ (@$result->wishlist->id !='' && @$result->wishlist->id!=null && Auth::id() != null) ? 'icon-heart cls_wish' : 'icon-heart-o cls_wish' }}"
                                id="wishlist_@{{ all.id }}"  aria-hidden="true" ng-click="wishlist({{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, {{ $result->id }})">
                                @if( $result->wishlist != '' && @$result->wishlist->id !='' && @$wishlist->id!=null)
                                <span>
                                    @lang('messages.home.saved_wishlist') <small> @lang('messages.home.click_unsave') </small>
                                </span>
                                @else
                                <span>
                                    @lang('messages.home.save_wishlist') <small> @lang('messages.home.save_your_wishlist') </small>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="sub-more copy cls_shareimg" >
                            <b class="copy-to-clipboard" data-copy="{{url('things')}}/{{ @$result->id }}"> @lang('messages.home.copy_link') </b>
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </div>
</div>
<div id='popup_container' style="top: 0px; display: none; opacity: 0;">
    <div class="popup policy_detail add-fancy-back">
        <div class="d-flex align-items-center flex-wrap cls_modal_height">
            <div data-reactroot="" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('messages.home.return_policy') }}</h5>
                    <button class="ly-close ly-close1" title="Close">
                    <span class="ic-del-black" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="terms p-4">
                    <p>{{ trans('messages.home.no_returns') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="popup shipping add-fancy-back" style="display: none;">
        <div class="d-flex align-items-center flex-wrap cls_modal_height">
            <div data-reactroot="" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose your country</h5>
                    <button class="ly-close ly-close1" title="Close">
                    <span class="ic-del-black" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="country-list after modal-body">
                    <span class="line"></span>
                    <div class="right-outer scroll">
                        <div>
                            @if($shipping_country->count())
                            <ul class="after">
                                @foreach($shipping_country as $products_shipping)
                                <li><a><b data-start="{{$products_shipping->start_window}}" data-end="{{$products_shipping->end_window}}">{{$products_shipping->ships_to}}</b></a></li>
                                @endforeach
                            </ul>
                            @else
                            <div class="terms">
                                <p>No Country found</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary shipping_country_save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-12 nopad my-3 text-center" id='timeline'>
    <ul class="cls_tabs tabs after li_list">
        <li><a class="similar current" data-type='similar'>{{ trans('messages.home.you_may_also') }}</a></li>
        @if(@Auth::user()->id)
        <li><a class="recently" data-type='recently'>{{ trans('messages.home.recently_viewed') }}</a></li>
        @endif
    </ul>
    <div class="inner similars cls_profilepage p-0" style="display: block;">
        <ul class="{{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}} check_login row nopad overflow_visible" ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}};count=0;product_id={{ $result->id}}" data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" infinite-scroll='details_loadMore()' infinite-scroll-distance='1' infinite-scroll-disabled='detail_products_busy' ng-cloak class="over_non">
            <li id="all-@{{$index}}" class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3 cls_thingsproli" ng-repeat="all in detail_products" ng-cloak ng-if="product_id != all.id"  ng-hide='detail_products.length <= 1' ng-tet='@{{detail_products.length}}' title="@{{ all.title}}">
                <div class="min-size short-padding" >
                    <a class="thingsimga" href="{{ url('things') }}/@{{ all.id }}">
                        <div class="popshow img-height-short" id="@{{ all.id }}">
                            <img class="thingsimg" width="100%" ng-src="@{{ all.image_name }}" onerror="this.src='{{ $no_product_url }}';">
                        </div>
                        <div class="cls_storehead">
                            <span class="img-title text-truncate text-left" >@{{ all.title}}</span>
                            <button class="btn-blue cls_price">
                            <span ng-bind-html="all.currency_symbol"></span> @{{all.products_prices_details.price}}
                            </button>
                        </div>
                    </a>
                    <div class="img-content pro_likes">
                        <span ng-if='all.user_like.length'>
                            <button class="btn-like product_like  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}' ><span><i></i></span>@{{ all.like_user.length }}</button>
                        </span>
                        <span ng-if='!all.user_like.length '>
                            <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}}  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}' ><span><i></i></span>@{{ all.like_user.length }}</button>
                        </span>
                        <span class="cls_share dropdown dropleft">
                            <a href="#" id="sharelist1" class="btn-more more_List" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                            <div class="dropdown-menu" aria-labelledby="sharelist">
                                <div class="sub-more share cls_shareimg">
                                    <b>{{ trans('messages.home.share') }}</b>
                                    <small>{{ trans('messages.home.share_friends') }}</small>
                                    <ul class="sharable" style="display:none">
                                        <li>
                                            <a href="@{{ all.share_url.facebook }}" target="_blank" class="share_facebook">
                                                <i class="icon icon-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="@{{ all.share_url.twitter }}" target="_blank" class="share_twitter">
                                                <i class="icon icon-twitter"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="popover-body add-wishlist">
                                    <div ng-class="(all.wishlist.id !='' && all.wishlist.id!=null) ? 'icon-heart cls_wish' : 'icon-heart-o cls_wish'" class="wishlist_@{{all.id}} cls_wish" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.id )">
                                        <span ng-if="all.wishlist.id !='' && all.wishlist.id!=null">
                                            <b> {{ trans('messages.home.saved_wishlist') }} </b>
                                            <small>{{ trans('messages.home.click_unsave') }}</small>
                                        </span>
                                        <span ng-if="all.wishlist.id==null">
                                            <b> {{ trans('messages.home.save_wishlist') }} </b>
                                            <small>{{ trans('messages.home.save_your_wishlist') }}</small>
                                        </span>
                                    </div>
                                </div>
                                <div class="sub-more copy cls_shareimg" >
                                    <b class="copy-to-clipboard" data-copy="{{url('things')}}/@{{all.id}}"> @lang('messages.home.copy_link') </b>
                                   
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
            </li>
            <li ng-repeat="all in detail_products" ng-cloak ng-show='detail_products.length <= 1' ng-tet='@{{detail_products.length}}'>
                <p> {{ trans('messages.home.no_products') }} </p>
                <li>
                </ul>
            </div>
            <div class="inner recentlys cls_profilepage p-0" style="display: none;">
                <ul class="{{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}} check_login row nopad overflow_visible" ng-init="current_userid={{ (!Auth::id()) ? '0'  : Auth::id()}};count=0;product_id={{ $result->id}}" data_user="{{ (!Auth::id()) ? '0'  : Auth::id()}}" infinite-scroll='recently_viewed_things()' infinite-scroll-distance='1' infinite-scroll-disabled='recently_products_busy' class="over_non">
                    <li id="all-@{{$index}}" class="col-lg-3 col-md-6 col-12 mb-3 col-sm-6 cls_thingsproli " title="@{{ all.title}}" ng-repeat="all in recently_thing" ng-cloak ng-if="product_id != all.id" ng-hide='recently_thing.length <= 1' ng-tet='@{{recently_thing.length}}' >
                        <div class="min-size short-padding">
                            <a class="thingsimga" href="{{ url('things') }}/@{{ all.id }}">
                                <div class=" popshow img-height-short " id="@{{ all.id }}">
                                    <img class="thingsimg" width="100%" ng-src="@{{ all.image_name }}" onerror="this.src='{{ $no_product_url }}';">
                                </div>
                                <div class="cls_storehead">
                                    <span class="img-title text-truncate text-left">@{{ all.title}}</span>
                                    <button class="btn-blue cls_price">
                                    <span ng-bind-html="all.currency_symbol"></span> @{{all.products_prices_details.price}}
                                    </button>
                                </div>
                            </a>
                            <div class="img-content pro_likes">
                                <span ng-if='all.user_like.length'>
                                    <button class="btn-like product_like {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data='@{{ all.id }}'>
                                    <span><i></i></span> @{{ all.user_like.length }}
                                    </button>
                                </span>
                                <span ng-if='!all.user_like.length '>
                                    <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}}  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}'>
                                    <span>
                                        <i></i>
                                    </span> @{{ all.user_like.length }}
                                    </button>
                                </span>
                                <span class="cls_share dropdown dropleft">
                                    <a href="#" id="sharelist1" class="btn-more more_List" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"></a>
                                    <div class="dropdown-menu" aria-labelledby="sharelist">
                                        <div class="sub-more share cls_shareimg">
                                            <b>{{ trans('messages.home.share') }}</b><small>{{ trans('messages.home.share_friends') }}</small>
                                            <span class="sharable" style="display:none">
                                                <a href="@{{ all.share_url.facebook }}" target="_blank" class="share_facebook">
                                                    <i class="icon icon-facebook"></i>
                                                </a>
                                                <a href="@{{ all.share_url.twitter }}" target="_blank" class="share_twitter">
                                                    <i class="icon icon-twitter"></i>
                                                </a>
                                            </span>
                                        </div>
                                        <div class="popover-body add-wishlist">
                                            <div ng-class="(all.wishlist.id !='' && all.wishlist.id!=null) ? 'icon-heart cls_wish' : 'icon-heart-o cls_wish' " id="wishlist_@{{all.id}}" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.id )">
                                                <span ng-if="all.wishlist.id !='' && all.wishlist.id!=null">
                                                    <b> {{ trans('messages.home.saved_wishlist') }} </b>
                                                <small> {{ trans('messages.home.click_unsave') }}</small></span>
                                                <span ng-if="all.wishlist.id==null">
                                                    <b>{{ trans('messages.home.save_wishlist') }} </b>
                                                <small>{{ trans('messages.home.save_your_wishlist') }}</small></span>
                                            </div>
                                        </div>
                                        
                                        <div class="sub-more copy cls_shareimg" >
                                            <b class="copy-to-clipboard" data-copy="{{url('things')}}/@{{all.id}}">{{ trans('messages.home.copy_link') }}</b>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </li>
                    <li ng-repeat="all in recently_thing" ng-cloak ng-show='recently_thing.length <= 1' ng-tet='@{{recently_thing.length}}'>
                        <p> {{ trans('messages.cart.no_recently_viewed_items') }}</p>
                        <li>
                        </ul>
                    </div>
                    <div class="modal fade col-xs-12" id="view_like_user" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="vertical-alignment-helper">
                            <div class="modal-dialog vertical-align-center" style="width:450px">
                                <div class="modal-content modal-content-like" style="border-radius: 1px !important">
                                    <div class="modal-header ">
                                        <h4 class="modal-title poptitle_like" style="display: inline-block;" id="mySmallModalLabel"> 
                                            {{ $site_name }}'d
                                        @lang('messages.order.by') </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body nopad">
                                        <ul class="ul_like_more">
                                            <li class="user_like_li col-xs-12 nopad">
                                                <div class="d-flex" ng-init="owner_follow = '{{$result->users->user_follow}}'">
                                                        <a href="{{ url('profile') }}/{{ $result->users->original_user_name }}" class="link_user_profile d-flex">
                                                        <div >
                                                            <img class="img img-rounded img-icon" src="{{$result->users->original_image_name }}">
                                                        </div>
                                                        <div class="ml-2">
                                                            <p class="m-0 text-muted"> Added by </p>
                                                            <p class="link_user_name font-weight-bolder text-muted">@ {{ $result->users->original_user_name }} </p>
                                                        </div>
                                                    </a>
                                                    <div class="ml-auto" ng-if="current_userid != user_likes.users.id">
                                                        <a class="btn follow-btn font-weight-bolder" ng-if="owner_follow == '0'" ng-click="followUser({{ $result->users->id }},'-1')"><i class="fa fa-plus" aria-hidden="true"></i> Follow </a>
                                                        <a class="btn follow-btn font-weight-bolder" ng-if="owner_follow != '0'" ng-click="unfollowUser({{ $result->users->id }},'-1')"><i class="fa fa-check" aria-hidden="true"></i> Unfollow  </a>
                                                    </div>
                                                 </div>                                                        
                                            </li>
                                            <li ng-repeat="user_likes in product_liked_users" class="user_like_li col-xs-12 nopad">
                                                <div class="d-flex">
                                                        <a href="{{ url('profile') }}/@{{ user_likes.users.original_user_name }}" class="link_user_profile d-flex">
                                                        <div >
                                                            <img class="img img-rounded img-icon" ng-src="@{{user_likes.users.original_image_name}}">
                                                        </div>
                                                        <div class="ml-2">
                                                            <p  class="m-0 text-black">@{{ user_likes.users.original_full_name }} </p>
                                                            <p class="link_user_name font-weight-bolder text-black">@ @{{ user_likes.users.original_user_name }} </p>
                                                        </div>
                                                    </a>
                                                    <div class="ml-auto" ng-if="current_userid != user_likes.users.id">
                                                        <a class="btn follow-btn font-weight-bolder" ng-if="user_likes.users.user_follow=='0'" ng-click="followUser(user_likes.users.id,$index)"><i class="fa fa-plus" aria-hidden="true"></i> Follow </a>
                                                        <a class="btn follow-btn font-weight-bolder" ng-if="user_likes.users.user_follow!='0'" ng-click="unfollowUser(user_likes.users.id,$index)"><i class="fa fa-check" aria-hidden="true"></i> Unfollow  </a>
                                                    </div>
                                                 </div>                                                        
                                            </li>
                                            <li class="loading" ng-show="ajax_loading"></li>
                                            <hr class="m-1" ng-hide="no_more_liked_users">
                                            <li class="user_like_li col-xs-12 nopad pb-0" ng-hide="no_more_liked_users">
                                                <div class="text-center">
                                                    <a class="font-weight-bolder" ng-click="showLikedUsers(product_id);"> @lang('messages.products.view_more') </a>
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
                        </div>
                    </div>
                </div>
            </main>
            @endsection