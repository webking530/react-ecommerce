@extends('template')
@include('common/sections')
@section('main')
<main id="site-content" class="p-0 cls_categoryview" role="main">
	<div class="container-fluid">
		<div class="cls_bannerimg">
			<img src="{{ url('image/homepage/'.$feature->image) }}">
			<h2>{{$feature->title}}</h2>
		</div>
	</div>
	<div class="container" infinite-scroll='details_loadMore()'  ng-controller="products_details" ng-cloak>
		<div class="cls_profilepage" >	
			<div class="slide-listing text-center">
			<h3>{{$feature->title}}</h3>
		   </div>
		    <input type="hidden" name="searchby" id="searchby" value="editor">
			<input type="hidden" name="symbol" id="symbol" value="{{ session::get('symbol')}}">	
			 @yield('filter')	  
			<ul class="row">	
				<li id="all-2" class="col-lg-4 col-md-6 col-12 mb-3 cls_thingsproli ng-scope"  ng-repeat="all in detail_products" title="@{{ all.title}}">
		            <div class="min-size short-padding">
		              <a class="thingsimga" href="{{ url('things') }}/@{{ all.id }}">
		                    <div class="thingsimg" style="background-image:url('@{{ all.image_name }}');">
		                    </div>
		                    <div class="cls_storehead">
		                        <span class="img-title text-truncate ng-binding">
		                        @{{ all.title}}
		                        </span>
		                        <button class="cls_price not-clickable ng-binding" type="submit">
		                            <span ng-bind-html="all.currency_symbol"></span> @{{all.price}}
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
			    
			</ul>
			<div class="mb-4 more_products loading" ng-show="ajax_loading"></div>
		</div>
	</div>
</main>
@endsection