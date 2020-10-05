@extends('template')
@include('common/sections')
@section('main')
<main id="site-content" class="p-0 cls_categoryview" role="main" ng-controller="productController">
	<div class="container-fluid">
		<div class="cls_bannerimg">
			<img src="{{ $banner_img }}" style="height:200px;width: 100%">
			<h2>{{$category}}</h2>
			<input type="hidden" name="symbol" id="symbol" value="{{ session::get('symbol')}}">	
		</div>
	</div>

	<div class="cls_category_menu" ng-init="load_more_type='category';category_type='all';category_id='{{$category_id}}';searchby='all'">	
		<ul class="menu tab" >
			@if($subcategory->count() == 0)
				<li>
					<a href="javascript:void(0)" ng-click="updatePageCategory('{{ $category_id }}')" data="product" class="store_tab df profile_tab {{$category_id}} current" ng-class="category_type=='{{ $category_id }}' ? 'current' : '';">
						<b> Every Thing </b>
					</a>
				</li> 
				<li>
					<a href="javascript:void(0)" ng-click="Wishlist_View( {{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }})" data="product" class="store_tab profile_tab wish"> 
						<b>On Your Wishlist</b>
					</a>
				</li>
			@else
				@foreach($subcategory  as $key => $value)
					@if($value->childs->count() > 0) 
						<li >
							<a href="{{ url('shop/browse/'.$value['title']) }}"  data="product" class="store_tab profile_tab" ng-class="category_type=='{{ $value['id'] }}' ? 'current' : '';">
								<b> {{ $value['title'] }} </b>
							</a>
						</li> 
					@else 	
						<li>
							<a href="javascript:void(0)" ng-click="updatePageCategory('{{$value['id']}}')" data="product" class="store_tab profile_tab {{$value['id']}}" ng-class="category_type=='{{ $value['id'] }}' ? 'current' : '';">
								<b> {{ $value['title'] }} </b>
							</a>
						</li> 
					@endif 
				@endforeach
			@endif 	
		</ul>
    </div>
    <div class="container" infinite-scroll='productLoadMore()' ng-cloak>
		<div class="cls_profilepage" ng-init="initSlider();">
			@yield('filter')
			<ul class="row">
				<li class="col-lg-4 col-md-6 col-12 mb-3 cls_thingsproli" ng-repeat="product in detail_products" title="@{{ product.title }}">
					@yield('product_detail')
				</li>
			</ul>
			<div class="text-center" ng-show="detail_products.length == 0 && !ajax_loading">
				<span class="font-weight-bold"> @lang("messages.products.no_products_found") </span>
			</div>
			<div class="mb-4 more_products loading" ng-show="ajax_loading"></div>
		</div>
	</div>
</main>
@endsection