@extends('template')
@section('main')
<div class="filter">
    <ul class="menu">
        @php
        foreach($categories as $category){
            $array[]=$category;
        }
        @endphp
        <li>
            <a href="{{ url('shop/popular') }}" class="{{ (!request()->segment(3)) ? 'current' : '' }}">Everything</a></li>
        @for($i = 0; $i < 7; $i++)
        <li>
            <a href="{{ url('shop/popular/'.$array[$i]->title) }}" class="{{ (request()->segment(3)==$array[$i]->title) ? 'current' : '' }}">{{ $array[$i]->title }}</a></li>
        @endfor
        <li class="more">
            <a href="#">More</a>
            <small class="showme">
                @for($i = 8; $i < count($array); $i++)
                <a href="{{ url('shop/popular/'.$array[$i]->title) }}" class="{{ (request()->segment(3)==$array[$i]->title) ? 'current' : '' }}">{{ $array[$i]->title }}</a>
                @endfor
            </small>
        </li>
    </ul>
</div>

<input type="hidden" name="category" id="page_category" value="{{ request()->segment(3) }}">

<ul infinite-scroll='popular_loadMore()' infinite-scroll-distance='1' infinite-scroll-disabled='popular_products_busy'>
    <li id="all-@{{$index}}" ng-repeat="all in popular_products" ng-cloak>
        <a href="{{ url('things') }}/@{{ all.id }}">
            <div class="col-12" ng-class="$index % 3 == 0 ? 'col-lg-11' : 'col-lg-5'">
                <div class="popshow" id="@{{ all.id }}" ng-class="$index % 3 == 0 ? 'img-height' : 'img-height-short'">
                    {!! Html::image('@{{ all.products_images.image_name }}', '', ['width' =>'100%']) !!}            
                </div>
                <div class="img-content col-xs-12 col-lg-12 col-md-12 col-sm-12 nopad bor-bot">
                    <a class="img-title flt-left" href="#">@{{ all.title}}</a>
                    <button class="btn-blue" type="submit">$ @{{all.products_prices_details.price}}</button>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 img-content pad-10">
                    <button class="btn-gray" type="submit"><span><i></i></span>19.8k</button>
                    <span class="like-link">
                        <a href="#" class="user-pic"><img class="user-img" src="{{ url('image/profile.png') }}"></a>
                        <a href="#" class="user-pic"><img class="user-img" src="{{ url('image/profile.png') }}"></a>
                        <a href="#" class="user-pic"><img class="user-img" src="{{ url('image/profile.png') }}"></a>
                    </span>
                    <span class="btn-more"></span>
                </div>
            </div>
        </a>
    </li>
    <div id="popular-search-result-empty" class="empty search-result-empty" style="display:none">   
        <i class="fa fa-search"></i>         
        <h3>{{ trans('messages.home.popular_result_empty') }}</h3>
        <p>{{ trans('messages.home.search_result_empty_desc') }}</p>
    </div>
</ul>
<div class="loading products_loading" id="popular_loading" style="display:none"></div>
@endsection