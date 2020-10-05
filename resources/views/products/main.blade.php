@extends('template')
@section('main')
<main class="cls_homemain">
  <div ng-controller="home_products">
      <div class="cls_topbannermain " >
        <ul class="owl-carousel owl-theme cls_topbanner"> 
        @foreach($topbanner as $key => $value)
        <li class="item" >
          <a href="{{ url('store') }}/{{ $value['id'] }}">
            <img src="{{ $value['logo_img']}}" onerror="this.src='{{ $no_store_url }}';">
          <div class="cls_owlinnertext">
            <h2>{{$value['store_name']}}</h2>
            <span class="btn-shop">SHOP NOW</span>
          </div>
          </a> 
        </li>
        @endforeach
      </ul>
      </div>
      <div class="cls_thingslist px-3 pb-3" ng-controller="login_signup1">
        <div class="cls_head" >
          <h2>Our Popular Products</h2>
          <a href="{{ url('shop/popular') }}" ng-show="header_slider.length > 0" > All Products</a>
        </div>
      
      <ul class="owl-carousel owl-theme cls_things things">
          <li class="item" ng-repeat="hslider in header_slider" title="@{{ hslider.title}}">
            <a href="@{{ hslider.link_url }}"><img ng-src="@{{hslider.image_url }}" onerror="this.src='{{ $no_store_url }}';"  >
              <span class="figcaption">
                <span class="category" style="visibility: visible;">@{{ hslider.store_name}}</span>
                <span class="title text-truncate" style="width: 260px;" title="@{{ hslider.title}}">@{{ hslider.title}}</span>
                <b class="price sales"> <span ng-bind-html="hslider.currency_symbol"></span> @{{ hslider.price}}
                </b>
              </span>
            </a> 
          </li>
        </ul>
    </div>    
    <div class="container-fluid">
      <div class="cls_displayhome">
          <div class="cls_head">
          <h2>OUR FAVORITE DEALS</h2>
          </div>
          <div class="col-lg-12 d-flex flex-wrap p-0">
             @foreach($feature as $featured )
             <div class="col-lg-4 col-12 cls_displayinhome">
              
              <a href="{{ url('shop/'.strtolower($featured->title))}}" class="{{(request()->input('feed')=='featured') ? ' current':''}}">
                <span class="figure">
                <img src="{{$featured->image_url}}">
                 <div class="cls_btmtext">
                  <span class="title"><b>{{$featured->title}}</b></span>
                </div>
                </span>
                <div class="cls_btmtext1 d-flex align-items-center flex-wrap justify-content-between">
                </div>
              </a>
            </div> 
            @endforeach          
          </div>
        
        </div>
      </div>
      <div class="cls_thingslist px-3 pb-c5"  ng-cloak >
          <div class="cls_head">
            <h2>Shop Listing</h2>        
            <a href="{{ url('stores') }}" class="{{$bottombanner->count() > 0 ? '': 'd-none'}}">SHOP ALL</a>
          </div>
          <ul class="owl-carousel owl-theme cls_things store_prod">
          @foreach($bottombanner as $banner)
          <li class="item" title="{{ $banner->store_name }}">
              <a href="{{ url('store') }}/{{ $banner->id }}"><img src="{{ $banner->logo_img }}">
                <span class="figcaption">
                <span class="category" style="visibility: visible;">Total Products {{ $banner->total_products }}</span>
                <span class="title text-truncate" style="width: 260px;" >{{$banner->store_name}}</span>        
              </span>
              </a>
          </li>
          @endforeach
        </ul>
      </div>
 
</div>
</main>
@endsection