@extends('template')
@include('common.sections')
@section('main')
<main id="site-content" class="p-0 cls_categoryview" role="main">
	<div class="container-fluid">
		<div class="cls_bannerimg">
			<img src="{{$feature->image_url}}">
			<h2>{{$feature->title}}</h2>
		</div>
	</div>
	<div class="container" infinite-scroll='details_loadMore()'  ng-controller="products_details" ng-cloak>
		<input type="hidden" name="searchby" id="searchby" value="{{request()->segment(2)}}">
		<input type="hidden" name="symbol" id="symbol" value="{{ session::get('symbol')}}">	
		<div class="cls_profilepage" >	
		<div class="slide-listing text-center">
		<h3>{{$feature->description}}</h3>
	   </div>
	    @yield('filter')
		<ul class="row" >	
			<li id="all-2" class="col-lg-4 col-md-6 col-12 mb-3 cls_thingsproli ng-scope"  ng-repeat="product in detail_products" title="@{{ all.title}}">
				@yield('product_detail')
		    </li>
		   </ul>
		   <div class="mb-4 more_products loading" ng-show="ajax_loading"></div>
		</div>
	</div>
</main>
@endsection