@extends('template')
@section('main')
<main class="cls_homemain">
  <div class="container-fluid">
    <div class="whole-products row" ng-controller="home_products" ng-cloak>
        @if($page == "view_profile")
          @include('user.view_profile')
        @else
         <div class="cls_topbannermain " ng-controller="home_products" ng-cloak>
          <ul class="owl-carousel owl-theme cls_topbanner">
            <li class="item" ng-repeat="store_product in store_products">
              <a href="{{ url('store') }}/@{{ store_product.id }}"><img src="@{{ store_product.home_logo_img }}" onerror="this.src='{{ $no_store_url }}';" ng-cloak>
              <div class="cls_owlinnertext">
                <p>@{{ store_product.user_address[0].city }} @{{ store_product.user_address[0].country }}</p>
                <h2>@{{ store_product.store_name }} </h2>
                <span class="btn-shop">SHOP NOW</span>
              </div>
              </a> 
            </li>
          </ul>
        </div>
         <div class="cls_thingslist " ng-controller="login_signup">

      <div class="cls_head">
        <h2>Our Populer Products</h2>
        <a href="{{ url('shop/popular') }}"> All Products</a>
      </div>
      <ul class="owl-carousel owl-theme cls_things">
        <li class="item" ng-repeat="hslider in product_slider">
        </li>
      </ul>
    </div>  
       
@endif 
  </div>
</div>
</main>
@endsection
