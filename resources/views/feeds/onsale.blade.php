@extends('template')
@include('common/sections')
@section('main')
<main id="site-content" class="p-0 cls_categoryview" role="main" ng-controller="products_details">
	<div class="container-fluid">
    	<div class="cls_bannerimg">
    		<img src="{{ url('image/homepage/'.$onsale->image) }}">
        <h2>{{$onsale->title}}</h2>
    	</div>
	</div>
    <div class="cls_category_menu" >
        <ul class="menu">
            <li><a href="javascript:void(0)" ng-click="show_everything_tab()" id="everything" class='profile_tab everything current' > Everything </a></li>
            <li><a href="javascript:void(0)" ng-click="show_liked_tab()"  id="liked" class='profile_tab liked'> You Liked </a></li>
        </ul>
    </div>
    <div class="container">
         <div class="cls_profilepage" > 
            <div class="slide-listing text-center">
                <h3>{{$onsale->title}}</h3>
            </div>
            <input type="hidden" name="searchby" id="searchby" value="onsale">
            <input type="hidden" name="symbol" id="symbol" value="{{ session::get('symbol')}}"> @yield('filter')
        <ul class="row">
            <li id="all-0" class="col-lg-4 col-md-6 col-12 mb-3 cls_thingsproli ng-scope" infinite-scroll='onsale_loadMore()' ng-repeat="all in detail_products" ng-hide='detail_products == null || cls_onsale == "liked"' title="@{{ all.title}}">
                <div class="min-size short-padding" >
                        <a class="thingsimga" href="{{ url('things') }}/@{{ all.id }}">
                            <div class="thingsimg" style="background-image:url('@{{ all.image_name }}');">
                            </div>
                            <div class="cls_storehead">
                                <span class="img-title text-truncate ng-binding">
                                @{{ all.title}}
                                </span>
                                <button class="cls_price not-clickable ng-binding" type="submit">
                                    <span ng-bind-html="all.currency_symbol"></span> @{{all.products_prices_details.price}}
                                </button>
                            </div>
                        </a>
                        <div class="img-content pro_likes">
                           <span ng-if='all.user_like.length'>
                                 <button class="btn-like product_like {{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click= 'product_like(all,all.id)' id= '@{{ all.id }}'  ng-cloak ><span ><i ng-bind="@{{ all.like_user.length}}"></i></span></button>
                            </span>
                            <span ng-if='!all.user_like.length '>
                                <button class="btn-gray product_like {{ (!Auth::id()) ? 'without_login' : ''}} {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" ng-click= 'pdu_like(all,all.id)' id= '@{{ all.id }}' ng-as=@{{all}}><span><i ng-bind="@{{ all.like_user.length}}"></i></span ></button>
                            </span>
                            <span class="cls_share dropdown dropleft">
                                  <a href="#" id="sharelist1" class="btn-more more_List" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                   <div class="dropdown-menu" aria-labelledby="sharelist">
                                    <div class="sub-more share cls_shareimg">
                                        <b>{{ trans('messages.home.share') }}</b><small>{{ trans('messages.home.share_friends') }}</small>
                                        <span class="sharable" style="display:none">
                                            <a href="@{{all.share_url['facebook']}}" target="_blank" class="share_facebook"><i class="icon icon-facebook"></i></a>
                                            <a href="@{{all.share_url['gplus']}}" target="_blank" class="share_google"><i class="icon-google-plus"></i></a>
                                            <a href="@{{all.share_url['twitter']}}" target="_blank" class="share_twitter"><i class="icon icon-twitter"></i></a>
                                        </span>
                                    </div>
                                    <div class="popover-body add-wishlist">
                                        <div class="wishlist_@{{ all.id }} {{ (@$wishlist->id !='' && @$wishlist->id!=null && Auth::user()->id!='') ? 'icon-heart cls_wish' : 'icon-heart-o cls_wish' }}" 
                                        id="wishlist_@{{ all.id }}"  aria-hidden="true" ng-click="wishlist({{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }}, all.id)">
                                            @if(@$wishlist->id !='' && @$wishlist->id!=null)<span>{{ trans('messages.home.saved_wishlist') }} <small>{{ trans('messages.home.click_unsave') }}</small></span> @elseif(@$wishlist->id==null) <span>{{ trans('messages.home.save_wishlist') }}<small>{{ trans('messages.home.save_your_wishlist') }}</small></span> @endif
                                        </div>
                                    </div>
                                    <div class="sub-more copy cls_shareimg" >
                                        <b class="copy-to-clipboard" data-copy="{{ url('things') }}/@{{ all.id }}">{{ trans('messages.home.copy_link') }}</b>
                                    </div>

                                </div>
                            </span>
                        </div>
                </div>
            </li>
             <li id="all-0" class="col-lg-4 col-md-6 col-12 mb-3 cls_thingsproli ng-scope" ng-repeat="all in onsale_product" ng-show='cls_onsale == "liked"' title="@{{ all.title}}">
                <div class="min-size short-padding" title="@{{ all.title}}">
                        <a class="thingsimga" href="{{ url('things') }}/@{{ all.id }}">
                            <div class="thingsimg" style="background-image:url('@{{ all.image_name }}');">
                            </div>
                            <div class="cls_storehead">
                                <span class="img-title text-truncate ng-binding">
                                @{{ all.title}}
                                </span>
                                <button class="cls_price not-clickable ng-binding" type="submit">
                                    <span ng-bind-html="all.currency_symbol"></span> @{{all.products_prices_details.price}}
                                </button>
                            </div>
                        </a>
                        <div class="img-content pro_likes">
                           <span ng-if="all.products.user_like.length" class="ng-scope">
                            <button class="btn-like product_like  active-user ng-binding" ng-click="product_like(all.products,all.products.id)" data="23">
                                <span><i></i></span> 2
                            </button>
                            </span>
                            <span class="cls_share dropdown dropleft">
                                  <a href="#" id="sharelist1" class="btn-more more_List" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                  <div class="dropdown-menu" aria-labelledby="sharelist">
                                    <div class="sub-more share cls_shareimg">
                                        <b>Share</b>
                                        <small>Share this with friends</small>
                                        <span class="sharable" style="display:none">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fspiffy.trioangledemo.com%2Fthings%2F23" target="_blank" class="share_facebook"><i class="icon icon-facebook"></i></a>
                                            <a href="https://plus.google.com/share?url=http%3A%2F%2Fspiffy.trioangledemo.com%2Fthings%2F23" target="_blank" class="share_google"><i class="icon-google-plus"></i></a>
                                            <a href="https://twitter.com/intent/tweet?url=http%3A%2F%2Fspiffy.trioangledemo.com%2Fthings%2F23" target="_blank" class="share_twitter"><i class="icon icon-twitter"></i></a>
                                        </span>
                                    </div>
                                    <div class="popover-body add-wishlist">
                                        <div ng-class="(all.products.wishlist.id !='' &amp;&amp; all.products.wishlist.id!=null) ? 'icon-heart cls_wish' : 'icon-heart-o cls_wish' " class="wishlist_23 cls_wish icon-heart" id="wishlist_23" aria-hidden="true" ng-click="wishlist( 10002, all.products.id )">
                                            <span ng-if="all.products.wishlist.id !='' &amp;&amp; all.products.wishlist.id!=null" class="ng-scope"> <b>Saved to Wishlist </b> <small>Click to unsave</small></span>
                                        </div>
                                    </div>
                                    <div class="sub-more copy cls_shareimg" data-copy="http://localhost/product/spiffy_sass/things/23">
                                        
                                        <b class="copy-to-clipboard">Copy link</b>
                                    </div>
                                </div>
                            </span>
                        </div>
                </div>
            </li>
            <li id="all-2" class="col-lg-12 col-12 mb-3 cls_thingsproli border text-center ng-scope cate2gory"ng-show='detail_products.length == 0 || onsale_product == 0' ng-tet='@{{detail_products.length}}'>
                <p style="font-size: 30px;font-weight: 600;margin: 10px;"> {{ trans('messages.home.search_result_empty') }} </p>
            </li>
        </ul>
        <div class="mb-4 more_products loading" ng-show="ajax_loading"></div>
    </div>
    </div>
</main>
@endsection