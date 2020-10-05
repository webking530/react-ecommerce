@section('right_category_filter')
<div>
  <p class="refine_title">
    {{ trans('messages.home.refine') }}
  </p>
  <label>
    {{ trans('messages.home.categories') }}
  </label>
  <ul class="onsale_category">
    <li>
      <a href="{{ url('shop/'.$page.'/') }}" class="{{ (!request()->segment(3)) ? 'current' : '' }}">
        Everything
      </a>
    </li>
   <!--  @include('products.category_list_item', ['categories' => $categories, 'current_name' => request()->segment(3) ]) -->
  </ul>
  <div class="pri">
    <span>
      <label>
        Price
      </label>
      <div class="display_price">
        <span>
          {!! Session::get('symbol') !!}
        </span> 
        <span class="minprice" id="min_text">
          {{ $min_value }}
        </span> - 
        <span>
          {!! Session::get('symbol') !!}
        </span>
        <span class="maxprice" id="max_text">
          {{ $max_value }}
        </span>
      </div>
    </span>

    <div class="example">
      <div id="slider" class="price-range-slider p2-slider-new"></div>
      <div class="row">
        <div class="col-6">
          <span class="price-min"> 
            <input type="hidden" id="min_value" class="minvalue" value="{{ $min_value }}">
          </span>
        </div>
        <div class="col-6 text-right">
          <span class="price-max">
            <input type="hidden" id="max_value" class="maxvalue" value="{{ $max_value }}">
            <input type="hidden" id="maximum_value" value="{{ $default_max_price }}">
          </span>
        </div>
      </div>
    </div>
  </div>
  <label>
    {{ trans('messages.home.filter') }}
  </label>
  <input class="keyword" type="text" id="keyword" value="{{ @$keyword }}" name="keyword" placeholder="{{ trans('messages.home.filter_keyword') }}">
  <span class="close-icon">x</span>
</div> 
@endsection
@if($page=="onsale")
<!-- start onsale rightbar  -->
<div class="col-12 col-md-4 col-lg-3 right-sidebar-onsale">
  <div class="sale_activity">
    @yield('right_category_filter')
  </div>
</div>
@endif

@if($page!="onsale")
<div class="col-12 col-md-4 col-lg-3 right-sidebar">
  <div class="right-activity cls_right_sidebar">
    @if($page == 'browse')
    @yield('right_category_filter')
    @endif
    @if( Auth::id() =='')
    <div class="follow-content text-center">
      <h4>
        {{ $site_name }} {{ trans('messages.home.fun_with_friends') }}
        <span>
          {{ trans('messages.home.login_friends') }}
        </span>
      </h4>
      <ul>
        <li>
          <a href="{{URL::to('facebookLogin')}}" class="facebook-btn">
            <i class="icon icon-facebook"></i>
            <span>
              {{ trans('messages.login.login_with') }} Facebook
            </span>
          </a>
        </li>
        <li>
          <a href="{{URL::to('twitterLogin')}}" class="twitter-btn">
            <i class="icon icon-twitter"></i>
            <span>
              {{ trans('messages.login.login_with') }} Twitter
            </span>
          </a>
        </li>
        <li>
          <a href="{{ url('signup') }}" class="register-btn">
            {{ trans('messages.header.join') }} {{ $site_name }}
          </a>
        </li>
      </ul>
    </div>
    @endif

    <div class="load-popup-product">
      <div class="popular-head d-flex align-items-center justify-content-between">
        <h3 class="col-md-5 p-0">
          {{ trans('messages.home.popular') }}
        </h3>
        <select class="every-select col-md-7 p-0" name='category'>
          <option value='all'>
            Everything
          </option>
          @foreach($categories as $categorie)
          <option value ='{{ @$categorie->title }}'>
            {{$categorie->title}}
          </option>
          @endforeach
        </select>
      </div>

      <div id="main">
        <div id="demos">
          <table cellspacing="20">
            <tr>
              <td ng-repeat="pop_product in pop_items" ng-cloak>
                <div id="s@{{$index}}" class="pics">
                  <a href="{{url('things')}}/@{{pop_product.id}}">
                    <img class="popular_lazy" data-src="@{{pop_img.home_half_image}}" ng-repeat="pop_img in pop_product.product_photos" ng-cloak />
                  </a>
                </div>
              </td>
            </tr>
          </table>        
        </div>
        <p class="text-center search-result-pop" style="display:none">
          {{ trans('messages.home.no_products') }}
        </p>
      </div>
      <a href="{{url('shop/popular')}}" class="register-btn">
        {{ trans('messages.home.see_more') }}
      </a>
    </div>

    <div class="load-store">
      <div class="popular-head">
        <h3>
          {{ trans('messages.home.stores') }}
        </h3>
      </div>
      <?php
      $no_store_url=url('image/cover_image.jpg');
      $no_product_url=url('image/profile.png');
      ?>
      <div class="store-back">
        <ul class="store-list">
          <li ng-repeat="store_product in store_products" class="pt-3 pb-3 clslistore_list" ng-cloak>
            <div class="col-md-12 p-0 d-flex align-items-center">
            <a href="{{ url('store') }}/@{{ store_product.id }}" class="col-md-6 p-0">
              <div class="clsstore_list_in d-flex align-items-center">
                <span class="pro-logo">
                  <img class="store_lazy" data-src="@{{ store_product.home_logo_img }}" onerror="this.src='{{ $no_store_url }}';" ng-cloak />
                </span>
                <span class="pro-name text-truncate">
                  @{{ store_product.store_name }}
                  <br>
                  <small>
                    @{{ store_product.products.length }} {{ trans('messages.home.products') }}
                  </small>
                </span> 
              </div>
            </a>
            <div class="col-md-6 text-right p-0">
            @if(@Auth::user()->id !='')
            <span ng-if="store_product.user_id != {{ @Auth::user()->id}}" class="">
              <button type="button" class="follow-store p-0 {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" id="store_@{{store_product.id }}" ng-click="HomeFollowStore(store_product.id,{{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }})">
               <span ng-if="store_product.follow_store.id!=null" class="btn-secondary"> 
                 {{ trans('messages.home.following_store') }}
               </span>
               <span ng-if="store_product.follow_store.id==null" class="btn-primary"> 
                 {{ trans('messages.home.follow_store') }}
               </span> 
             </button>
           </span>
           @else
           <button type="button" class="follow-store p-0 {{ (@Auth::user()->status != 'Inactive') ? 'active-user' : 'inactive-user'}}" id="store_@{{store_product.id }}" ng-click="HomeFollowStore(store_product.id,{{ (@Auth::user()->id!='') ? @Auth::user()->id : 0 }})" class="col-md-6">
             <span ng-if="store_product.follow_store.id!=null" class="btn-secondary">
               {{ trans('messages.home.following_store') }}
             </span>
             <span ng-if="store_product.follow_store.id==null" class="btn-primary">
               {{ trans('messages.home.follow_store') }}
             </span> 
           </button>
           @endif
          </div>
        </div>
           <ul class="merchant-list d-flex justify-content-between">
            <li ng-repeat="product_image in store_product.get_products | limitTo:4" ng-cloak>
              <a href="{{ url('things') }}/@{{ product_image.id }}">
                <img class="store_product_lazy" data-src="@{{ product_image.products_images.popular_image }}" onerror="this.src='{{ $no_product_url }}';">
              </a>
            </li>
          </ul>
        </li>
      </ul>
      <div class="text-center search-result-store" style="display:none">
        {{ trans('messages.home.no_stores') }}
      </div>
      <a href="{{ url('stores') }}" class="register-btn">
        {{ trans('messages.home.see_more') }}
      </a>
    </div>  
  </div>

  <div class="footer-links">
    <ul class="d-flex justify-content-between align-items-center flex-wrap">
      @foreach($company_pages as $company_page)
      <li>
        <a href="{{ url($company_page->url) }}">
          {{ $company_page->name }}
        </a>
      </li>
      @endforeach
    </ul>
  </div>
</div>
</div>
@endif