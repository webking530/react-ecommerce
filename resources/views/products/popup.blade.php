<script type="text/javascript">
    var mySlider;
</script>
<div id="overlay-thing" class="home_popup">

    <div data-reactroot="" class="popup overlay-thing thing-detail" ng-cloak>
        <a id="closed" class="ly-close">X</a>
        <div class="wrapper-content thing-detail-container thing-detail-overlay" style="">
            <div class="col-md-8 figure-section">
                <div class="figure-cont col-md-12">
                    <ul class="thumb_bxslider">
                        <li ng-if="video_src">
                            <div class="video_player">
                                <player videos='[{"type":video_type,"src":video_src,"poster":video_thumb,"captions":""}]' />
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="figure-detail col-md-12 nopad">            
                    <div id="bx-pager" class=" thumbnail-list col-md-4">
                        <ul>

                            <li ng-repeat="products_img in slider_products_pager" >
                                <a data-slide-index=@{{$index}} href="javascript:;">
                                    <img ng-src="@{{products_img}}"  /> 
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class=" thumbnail-dis col-md-8">
                        <div class="detail">
                            <h4 class="tit">{{ trans('messages.products.description') }}</h4>
                            <div ng-bind-html="description"></div>
                            <a class="more">{{ trans('messages.products.show_more') }}</a>
                            <a class="less">{{ trans('messages.products.show_less') }}</a>              
                        </div>
                        <ul class="after">
                            <li class="international shipping">
                                <span ng-if="product.products_shipping[0].start_window">
                                    <label>{{ trans('messages.home.estimated_delivery') }}</label>
                                    <span class="able">
                                        @{{ product.products_shipping[0].start_window }} - 
                                        @{{ product.products_shipping[0].end_window }} 
                                        <span ng-if='product.products_shipping[0].ships_to'>
                                            {{ trans('messages.home.days_to') }}
                                            <a class="shipping_country_list">
                                                @{{product.products_shipping[0].ships_to}}
                                            </a>
                                        </span>
                                    </span>
                                    <span class="unable" style="display: none;"><!-- react-text: 2253 -->Unable to ship to <!-- /react-text -->
                                        <a>IN</a>
                                    </span>
                                </span>
                                <span ng-if="!product.products_shipping[0].start_window">
                                    <span ng-if='product.products_shipping[0].ships_to'>
                                        <label>{{ trans('messages.home.delivery') }}</label>
                                        {{ trans('messages.home.days_to') }}
                                        <a>
                                            @{{product.products_shipping[0].ships_to}}
                                        </a>
                                    </span>

                                </span>
                            </li>
                            <span ng-if='product.products_shipping[0].ships_from'>
                                <li class="shipping">
                                    <label> {{ trans('messages.home.ships_from') }}</label>
                                    <span>@{{ product.products_shipping[0].ships_from }}</span>
                                </li>   
                            </span>
                            <span ng-if='product.return_policy'>
                                <li>
                                    <label>{{ trans('messages.home.return_policy') }}</label>
                                    <span>
                                        <!-- react-text: 2261 -->
                                        <span ng-if = "product.return_policy == '1'">
                                            {{ trans('messages.home.no_returns') }}
                                            <p class="return_policy_details" style="display: none">{{ trans('messages.home.no_returns_desc') }}</p>
                                        </span>                                        
                                        <span ng-if = "product.return_policy == '2'">
                                            15 {{ trans('messages.home.day_return') }}
                                            <p class="return_policy_details" style="display: none">{{ trans('messages.home.return_desc') }}</p>
                                        </span>
                                        <span ng-if = "product.return_policy == '3'">
                                            30 {{ trans('messages.home.day_return') }}
                                            <p class="return_policy_details" style="display: none">{{ trans('messages.home.return_desc') }}</p>
                                        </span>
                                        <!-- /react-text -->

                                    </span>

                                    <a class='policy_detailed'>{{ trans('messages.home.view_details') }}</a>
                                </li>
                            </span>
                        </ul>
                    </div>
                </div>              
            </div>
            <div class="col-md-4 fig_cart">
                <div class="figure-info col-lg-12 col-sm-12 col-md-12 col-xs-12 nopad mar-0" style="border-bottom-left-radius:0px;border-bottom-right-radius:0px;">
                    <h3 class="title" style="overflow: hidden;text-overflow: ellipsis;">@{{ product_title }}</h3>
                    <p class="price">
                        <big class="sale" id="product_price_@{{product.id}}"><span ng-bind-html="product.currency_symbol"></span> @{{ price }}
                            <small class="usd">
                                <a class="code currently-usd">@{{ product.products_prices_details.code}}
                                </small>
                            </big>
                            <span ng-if="retail_price">
                                <span class="sales">
                                    <em id="product_retail_price_@{{product.id}}"><span ng-bind-html="product.currency_symbol"></span>@{{ retail_price }}</em> <span id="product_discount_@{{product.id}}">({{ trans('messages.home.save') }} @{{ discount }}%)</span>
                                </span>
                            </span>
                            <span class="currency_price" style="display: none;">APPROXIMATELY nullnull 
                                <a class="code" style="display: none;">USD</a>
                            </span>
                        </p>
                        <div class="frm">
                            <fieldset class="sale-item-input">
                                <p>
                                    <label>{{ trans('messages.home.option') }}</label>
                                    <span class="trick-select option">
                                        <span ng-if = "product.product_option.length > 0">

                                            <select id="product_option_@{{product.id}}"  class="select-boxes2 product_option select-option" ng-model="product_option_id[0]"  ng-change="change_option()" >
                                                <option value="@{{ product_option.id }}" ng-repeat="product_option in product.product_option" ng-cloak>@{{ product_option.option_name }}</option>
                                            </select>
                                        </span>
                                        <span ng-if = "product.product_option.length == 0">
                                            <select id="option" class="select-boxes2 select-option" disabled>
                                                <option value="">@{{ product_title }}</option>
                                            </select>
                                        </span>
                                    </span>
                                </p>
                                <p>
                                    <label>{{ trans('messages.home.quantity') }}</label>
                                    <span class="trick-select quantity">
                                        <span id="qty_select"></span>
<!-- <span ng-if="quantity.length !=0">                                    

<select id="quantity" class="select-boxes2" >                                
<option value="@{{ qty }}" ng-repeat="qty in quantity" ng-cloak>
@{{ qty }}
</option>
</select>
</span> 
<span ng-if="quantity.length == 0">
<select id="quantity" class="select-boxes2" >
<option value="1">1</option>
</select>
</span>   --> 
</span>   
</p>                 
<br/>
<input type="hidden" name="id" id="product_id" value="@{{product.id}}">

@if(!Auth::id())
<button class="add_to_cart green-btn prced-btn without_login">{{ trans('messages.home.add_to_cart') }}</button>
@else
<span ng-if='product.user_id!={{@Auth::id()}}'>
    <button class="add_to_cart green-btn prced-btn">{{ trans('messages.home.add_to_cart') }}</button>
</span>
@endif
</fieldset>
</div>
<!-- <div class="figure-button"> -->
    <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 img-content pro_likes pad-10">
        <span ng-if= 'product.user_like.length'>
            <button class="btn-like product_like  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click= 'pdu_like(product,product.id)' id= '@{{ product.id }}' ><span><i></i></span>@{{ product.like_user.length}}</button>
        </span>
        <span ng-if= '!product.user_like.length '>
            <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}}  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click= 'pdu_like(product,product.id)' id= '@{{ product.id }}' ><span><i></i></span>@{{ product.like_user.length}}</button>
        </span>

        <span class="like-link">
            <a href="{{url('profile')}}/@{{lke_user.users.user_name}}" class="user-pic" ng-repeat="lke_user in product.like_user" ng-cloak>  
                <span ng-if="lke_user.users.original_image_name">
                    <img ng-src="@{{ lke_user.users.original_image_name }}" class="user-img">
                </span>
                <span ng-if="!lke_user.users.original_image_name">
                    <img class="user-img" src="{{ url('image/profile.png') }}">
                </span>
            </a>                            
        </span>

        <span class="popover-wrapper show-more-options right flt-right">
            <a href="#" data-role="popover"  class="btn-more more_List" data-target="example-popover-2">&nbsp;</a>
            <div class="popover-modal example-popover-2">
                <div class="sub-more share">
                    <i class="fa fa-share share"></i>
                    <b>{{ trans('messages.home.share') }}</b><small>{{ trans('messages.home.share_friends') }}</small>
                    <ul class="sharable" style="display:none">
                        <li>
                            <a href="@{{ product.share_url.facebook }}" target="_blank" class="share_facebook">
                                <i class="fa fa-facebook-square"></i>
                            </a>
                        </li>
                        <li>
                            <a href="@{{ product.share_url.gplus }}" target="_blank" class="share_google">
                                <i class="fa fa-google-plus-square"></i>
                            </a>
                        </li>
                        <li>
                            <a href="@{{ product.share_url.twitter }}" target="_blank" class="share_twitter">
                                <i class="fa fa-twitter-square"></i>
                            </a>
                        </li>
                    </ul>
                </div>                                

                <div class="popover-body add-wishlist">
                    <div ng-class="(product.wishlist.id !='' && product.wishlist.id!=null) ? 'fa fa-heart btn btn-blue' : 'fa fa-heart-o btn btn-blue' " class="wishlist_@{{product.id}}" id="wishlist_@{{product.id}}" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, product.id )"> 
                        <span ng-if="product.wishlist.id !='' && product.wishlist.id!=null"> 
                            {{ trans('messages.home.saved_wishlist') }} <small>{{ trans('messages.home.click_unsave') }}</small>
                        </span>
                        <span ng-if="all.wishlist.id==null"> {{ trans('messages.home.save_wishlist') }} 
                            <small>{{ trans('messages.home.save_your_wishlist') }}</small>
                        </span>
                    </div>
                </div>
                <div class="sub-more copy" data-copy="{{url('things')}}/@{{product.id}}">
                    <i class="fa fa-chain-broken copy"></i>
                    <small class="copy-to-clipboard">
                        {{ trans('messages.home.copy_link') }}
                    </small>
                </div>
            </div>
        </span>
    </div>
</div>
</div>
<input type="hidden" name="category" id="page_category" value="@{{ product.categories.title }}">
<input type="hidden" name="id" id="product_id" value="@{{ product.id }}">    

<div class="col-lg-12 col-md-9 margin-top-10 nopad main-content col-xs-12 rm_margin-left-move" id='timeline'>
    <ul class="tabs after li_list">
        <li>
            <a class="similar current" data-type='similar'>
                {{ trans('messages.home.you_may_also') }}
            </a>
        </li>
        @if(@Auth::user()->id)
        <li>
            <a class="recently" data-type='recently'>
                {{ trans('messages.home.recently_viewed') }}
            </a>
        </li>
        @endif
    </ul>
    <div class="inner similars" >
        <ul  ng-init="count=0;" ng-cloak class="over_non">
            <li id="all-@{{$index}}"  ng-repeat="all in like_products" ng-if="product_id != all.id" ng-cloak >
                <a href="{{ url('things') }}/@{{ all.id }}">
                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-4 short-padding">
                        <div class=" popshow img-height-short" id="@{{ all.id }}">
                            <img ng-src="@{{ all.products_images.home_half_image }}" onerror="this.src='{{ $no_product_url }}';">          
                        </div>
                        <div class="img-content col-12">
                            <a class="img-title flt-left" href="{{url('things')}}/@{{all.id}}">@{{ all.title}}</a>
                            <button class="btn-blue">
                                <span ng-bind-html="product.currency_symbol"></span> 
                                @{{all.products_prices_details.price}}
                            </button>
                        </div>

                        <div class="col-12 img-content">
                            <span ng-if= 'all.user_like.length'>
                                <button class="btn-like product_like {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}'><span><i></i></span>@{{ all.like_user.length }}</button>
                            </span>
                            <span ng-if= '!all.user_like.length'>
                                <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}}  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}' ><span><i></i></span>@{{ all.like_user.length }}</button>
                            </span>                           
                            <span class="like-link">
                                <span class="popover-wrapper right user-pic" ng-repeat="like_user in all.like_user | limitTo:3" ng-cloak>
                                    <a href="{{url('profile')}}/@{{like_user.users.user_name}}" data-id="user_@{{all.id}}_@{{like_user.users.id}}" class="user_a user_@{{all.id}}_@{{like_user.users.id}}">
                                        <span ng-if="like_user.users.original_image_name">
                                            <img ng-src="@{{ like_user.users.original_image_name }}" class="user-img">
                                        </span>
                                        <span ng-if="!like_user.users.original_image_name">
                                            <img class="user-img" src="{{ url('image/productofile.png') }}">
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

                            <span class="popover-wrapper show-more-options">
                                <a href="#" data-role="popover" class="btn-more more_List" data-target="example-popover-2">&nbsp;</a>
                                <div class="popover-modal example-popover-2">
                                    <div class="sub-more share">
                                        <i class="fa fa-share share"></i>
                                        <b>{{ trans('messages.home.share') }}</b><small>{{ trans('messages.home.share_friends') }}</small>
                                        <ul class="sharable" style="display:none">
                                            <li>
                                                <a href="@{{ all.share_url.facebook }}" target="_blank" class="share_facebook">
                                                    <i class="fa fa-facebook-square"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="@{{ all.share_url.gplus }}" target="_blank" class="share_google">
                                                    <i class="fa fa-google-plus-square"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="@{{ all.share_url.twitter }}" target="_blank" class="share_twitter">
                                                    <i class="fa fa-twitter-square"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="popover-body add-wishlist">
                                        <div ng-class="(all.wishlist.id !='' && all.wishlist.id!=null) ? 'fa fa-heart btn btn-blue' : 'fa fa-heart-o btn btn-blue' " class="wishlist_@{{all.id}}" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.id )"> 
                                            <span ng-if="all.wishlist.id !='' && all.wishlist.id!=null"> { trans('messages.home.saved_wishlist') }} <small>{{ trans('messages.home.click_unsave') }}</small></span>
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
                </a>
            </li>
        </ul>
    </div>
    <div class="inner recentlys" style="display: none;">
        <ul ng-init="count=0;" class="over_non">
            <li id="all-@{{$index}}" ng-repeat="all in recently_thing" ng-if="product_id != all.id" ng-cloak>
                <a href="{{ url('things') }}/@{{ all.id }}">
                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-4 short-padding">
                        <div class="popshow img-height-short" id="@{{ all.id }}">
                            <img ng-src="@{{ all.products_images.home_full_image }}" onerror="this.src='{{ $no_product_url }}';">      
                        </div>
                        <div class="img-content col-xs-12 col-lg-12 col-md-12 col-sm-12 nopad bor-bot">
                            <a class="img-title flt-left" href="{{url('things')}}/@{{all.id}}">@{{ all.title}}</a>
                            <button class="btn-blue  mar-10 flt-right bold not-clickable" style="margin-left: 0px !important;"><span ng-bind-html="product.currency_symbol"></span> @{{all.products_prices_details.price}}</button>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 img-content pad-10 heg1">
                            <span ng-if= 'all.user_like.length'>
                                <button class="btn-like product_like  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}' ><span><i></i></span>@{{ all.like_user.length }}</button>
                            </span>
                            <span ng-if= '!all.user_like.length'>
                                <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}}  {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click='product_like(all,all.id)' data= '@{{ all.id }}'><span><i></i></span>@{{ all.like_user.length }}</button>
                            </span>
                            <span class="like-link">
                                <span class="popover-wrapper right user-pic" ng-repeat="like_user in all.like_user | limitTo:3" ng-cloak>
                                    <a href="{{url('profile')}}/@{{like_user.users.user_name}}" data-id="user_@{{all.id}}_@{{like_user.users.id}}" class="user_a user_@{{all.id}}_@{{like_user.users.id}}">

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
                                <a href="#" data-role="popover"  class="btn-more more_List" data-target="example-popover-2">&nbsp;</a>
                                <div class="popover-modal example-popover-2">
                                    <div class="sub-more share">
                                        <i class="fa fa-share share"></i>
                                        <b>{{ trans('messages.home.share') }}</b><small>{{ trans('messages.home.share_friends') }}</small>
                                        <ul class="sharable" style="display:none">
                                            <li>
                                                <a href="@{{ all.share_url.facebook }}" target="_blank" class="share_facebook">
                                                    <i class="fa fa-facebook-square"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="@{{ all.share_url.gplus }}" target="_blank" class="share_google">
                                                    <i class="fa fa-google-plus-square"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="@{{ all.share_url.twitter }}" target="_blank" class="share_twitter">
                                                    <i class="fa fa-twitter-square"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="popover-body add-wishlist">
                                        <div ng-class="(all.wishlist.id !='' && all.wishlist.id!=null) ? 'fa fa-heart btn btn-blue' : 'fa fa-heart-o btn btn-blue' " class="wishlist_@{{all.id}}" aria-hidden="true" ng-click="wishlist( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.id )"> 
                                            <span ng-if="all.wishlist.id !='' && all.wishlist.id!=null"> 
                                                {{ trans('messages.home.saved_wishlist') }} 
                                                <small>
                                                    {{ trans('messages.home.click_unsave') }}
                                                </small>
                                            </span>
                                            <span ng-if="all.wishlist.id==null"> 
                                                {{ trans('messages.home.save_wishlist') }} 
                                                <small>
                                                    {{ trans('messages.home.save_your_wishlist') }}
                                                </small>
                                            </span>
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
                </a>
            </li>
        </ul>
    </div>
</div>
</div>
</div>
</div>
