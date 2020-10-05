<div class="col-12 col-md-3 col-lg-2 left-sidebar">
   <div class="cls_left_sidebar">
        <div class="nav-wrap">  
            <h5>
                {{ trans('messages.home.home') }}
            </h5>
            <ul>
                <li>
                    <a href="{{ url('/') }}?feed=featured" class="{{(request()->input('feed')=='featured') ? ' current':''}}">
                        <img class="static_page_lazy" data-src='{{ url("image/new-navigation.png")}}'>
                        <span>
                            {{ trans('messages.home.featured') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/') }}?feed=recommended" class="{{(request()->input('feed')=='recommended') ? ' current':''}}">
                        <i class="icon icon-o-star"></i>
                        <span>
                            {{ trans('messages.home.recommended') }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-wrap">  
            <h5>
                {{ trans('messages.home.shop') }}
            </h5>
            <ul>
                <li>
                    <a href="{{ url('shop/popular') }}" class="{{(request()->segment(2)=='popular') ? ' current':''}}">
                        <i class="icon icon-number-one"></i>
                        <span>
                            {{ trans('messages.home.popular_products') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('shop/newest') }}" class="{{(request()->segment(2)=='newest') ? ' current':''}}">
                        <i class="icon icon-add"></i>
                        <span>
                            {{ trans('messages.home.new_products') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('shop/editor') }}" class="{{(request()->segment(2)=='editor') ? ' current':''}}">
                        <i class="icon icon-checkbox"></i>
                        <span>
                            {{ trans('messages.home.editor_picks') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('shop/onsale') }}" class="{{(request()->segment(2)=='onsale') ? ' current':''}}">
                        <i class="icon icon-shop-tag"></i>
                        <span>
                            {{ trans('messages.home.on_sale') }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-wrap">  
            <h5>
                Browse
            </h5>   
            <ul class="side-list" ng-init="url='{{url('/')}}';uri='{{ request()->segment(3) }}'">
                <li ng-repeat="category in categories_browse">
                    <a href="@{{ url+'/shop/browse/'+category.title }}" class="@{{ category.title }} (uri==category.title) ? 'current' : '' ">
                        <img class="category_lazy" data-src='@{{ category.icon_name }}'>
                        @{{ category.title }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
