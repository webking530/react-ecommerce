@section('sharable')
<b> @lang('messages.home.share') </b><small> @lang('messages.home.share_friends') </small>
<span class="sharable" style="display:none">
	<a href="@{{product.share_url['facebook']}}" target="_blank" class="share_facebook"><i class="icon icon-facebook"></i></a>
	<a href="@{{product.share_url['twitter']}}" target="_blank" class="share_twitter"><i class="icon icon-twitter"></i></a>
</span>
@endsection
@section("product_detail")
<div class="min-size short-padding">
	<a class="thingsimga" href="{{ url('things') }}/@{{ product.id }}">
		<div class="thingsimg">
			<img lazy-src="@{{product.image_name}}">
		</div>
		<div class="cls_storehead">
			<span class="img-title text-truncate" >
				@{{ product.title}}
			</span>
			<button class="cls_price not-clickable" type="submit">
			<span ng-bind-html="product.currency_symbol"></span> @{{product.price}}
			</button>
		</div>
	</a>
	<div class="img-content pro_likes">
		<span ng-if='product.user_like.length'>
			<button class="btn-like product_like" ng-click='product_like(product,$index)'><span ><i ng-bind="@{{ product.like_user.length}}"></i></span>
			</button>
		</span>
		<span ng-if='!product.user_like.length'>
		<button class="btn-gray product_like" ng-click= 'pdu_like(product,$index)'><span><i ng-bind="@{{ product.like_user.length}}"></i></span >
		</button>
	</span>
	<span class="cls_share dropdown dropleft">
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
@if (Route::current()->uri() == '/' || Route::current()->uri() == 'shop/{page?}/{category?}' |Route::current()->uri() =='shop/{page}')@section("filter")
<ul class="d-flex align-items-center justify-content-end cls_filter_cate">
				<li class="cls_pricefilterli">
					<button class="btn btn-light cls_pricefilter" id="cls_pricefilter">Price</button>
					<div class="cls_pricefilteroption" style="display: none;">
					 	<p>
						  <label for="amount">Price:</label>
						<input type="hidden" id="mob_min_value" class="minvalue" value="{{ $min_value }}">
						<input type="hidden" id="mob_max_value" class="maxvalue" value="{{ $max_value }}">
                       	<input type="hidden" id="maximum_value" value="{{ $default_max_price }}">
						<input type="text" ng-model="price_range" id="amount" readonly>
						</p>
						<div id="slider-range"></div>
					</div>
				</li>
		</ul> 	
@endsection
@endif