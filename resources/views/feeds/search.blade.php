@extends('template')
@section('main')
@include('common.sections')
<main id="site-content" class="p-0 cls_categoryview" role="main" ng-controller="productController" ng-init="search_for='{{ $search_for }}';search_key='{{ $search_key }}';load_more_type='search';">
	<div class="container-fluid">
		<div class="cls_bannerimg">
			<div  style="height:200px;width: 100%"></div>
			<h2>{{ ($search_key != '') ? $search_key : "Top" }}</h2>
			<input type="hidden" name="symbol" id="symbol" value="{{ session::get('symbol')}}">	
		</div>
	</div>

	<div class="cls_searchtap p-3" ng-show="detail_products.length >0">
		<div class="container">
		        <div id="tab-sidebar" class="nav nav-pills d-flex align-items-center justify-content-center flex-wrap" role="tablist" aria-orientation="vertical" >
		            <a id="pill-A" class="nav-link active" data-toggle="pill" href="" role="tab" aria-controls="tab-A" aria-selected="true">Top</a>
		        </div>
		   </div>
		</div>

	<div class="container" infinite-scroll='productLoadMore()' ng-cloak>
		 
		<div class="cls_profilepage" >
			<ul class="row">
				<!-- <div id="tab-content" class="col-12 tab-content">
	            <div id="tab-A" class="tab-pane fade show active" role="tabpanel" aria-labelledby="pill-A">[A]</div>
	            <div id="tab-B" class="tab-pane fade" role="tabpanel" aria-labelledby="pill-B">[B]</div>
	        </div> -->
				<li class="col-lg-4 col-md-6 col-12 mb-3 cls_thingsproli" ng-repeat="product in detail_products" title="@{{ product.title }}">
					@yield('product_detail')
				</li>
			</ul>
			<div class="text-center" ng-show="detail_products.length == 0 && !ajax_loading">
				<span class="font-weight-bold"> @lang("messages.products.no_search_products_found") {{$search_key}} </span>
			</div>
			<div class="mb-4 more_products loading" ng-show="ajax_loading"></div>
		</div>
	</div>
</main>
@endsection