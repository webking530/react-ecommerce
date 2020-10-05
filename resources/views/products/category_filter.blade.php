@extends('template')
@section('main')
<div class="container-fluid">
    <div class="cls_bannerimg">
        <img src="{{ url('image/new_product/category-popular.png') }}">

        <h2>popular</h2>
    </div>
    </div>

@if($page=="onsale")
<div class="category_filter nt_cate">
<div class="menuitem cls_category_menu" ng-cloak><div class="every icon icon-down-arrow">
    <span ng-if="you_liked != 1">Everything</span>
    <span ng-if="you_liked == 1">You Liked</span>
</div>

    <ul class="menu">
        <li><a href="#"  class="onsale_tab current" data="everything">Everything</a></li>
        <li><a href="#"  class="onsale_tab" data="fancy">You Liked</a></li>
        <li><a class="onsale_tab cls_refine"></a></li>
    </ul>
</div>
</div>
<div class="onpage cls_filter cls_refine_menu my-3">

 
<div class="menuitem" ng-cloak>
    <div class="every1 cls_clicm" id="idsale">
        <span class="cls_clili cls_catename">{{ (request()->segment(3))?request()->segment(3) : "Categories" }}</span>
        
        <ul class="onsale_category onsale mobileview" >
            <li><a href="{{ url('shop/'.$page.'/') }}" class="{{ (!request()->segment(3)) ? 'current' : '' }}">Everything</a></li>
            @include('products.category_list_item', ['categories' => $categories, 'current_name' => request()->segment(3) ])
        </ul>
    </div>

   <div class="pricate cls_clicm">
        <span class="cls_clili">Price</span>
            <div class="display_price onsale_category">
            <span>{!! Session::get('symbol') !!}</span> <span class="minprice" id="mob_min_text">{{ $min_value }}</span> - 
            <span>{!! Session::get('symbol') !!}</span><span class="maxprice" id="mob_max_text">{{ $max_value }}</span>

             <div class="example">
                  <div id="mob_slider" class="price-range-slider p2-slider-new"></div>
                    <div class="row" style="margin-top: 20px;">
                      <div class="col-6 lang-chang-label">
                        <span class="price-min"> 
                        
                        <input type="hidden" id="mob_min_value" class="minvalue" value="{{ $min_value }}">
                        </span>
                        </div>
                        <div class="col-6 text-right">
                        <span class="price-max">
                        
                        <input type="hidden" id="mob_max_value" class="maxvalue" value="{{ $max_value }}">
                        <input type="hidden" id="maximum_value" value="{{ $default_max_price }}">
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        

       
    </div>
    <div class="caefilter cls_clicm">
        <span class="cls_clili cls_seafilter1">{{ trans('messages.home.filter') }}</span>
        <div class="cls_seafilter onsale_category">
            <input class="keyword from-control" type="text" id="keyword" value="{{ @$keyword }}" name="keyword" placeholder="{{ trans('messages.home.filter_keyword') }}">
            <span class="close-icon">x</span>
        </div>
    </div>
  </div> 
</div>
@endif
@if($page!="onsale" && $page!="search")
<div class="category_filter nt_cate">

<div class="cls_category_menu">
        <ul class="menu ntnew_more">

        @php
            foreach($categories as $category){
                $array[]=$category;
            }
        @endphp
        <li><a href="{{ url('shop/'.$page.'/') }}" class="{{ (!request()->segment(3)) ? 'current' : '' }}">Everything</a></li>
        @if(!empty($array))
            @for($i = 0; $i < min(9, count($array)); $i++)
            <li><a href="{{ url('shop/'.$page.'/'.$array[$i]->title) }}" class="{{ (request()->segment(3)==$array[$i]->title) ? 'current' : '' }}">{{ $array[$i]->title }}</a></li>
            @endfor
             @if(count($array) > 7)
            <li class="more_category">
                <a href="#">More</a>
                <small class="showme">
                @for($i = 8; $i < count($array); $i++)
                <a href="{{ url('shop/'.$page.'/'.$array[$i]->title) }}" class="{{ (request()->segment(3)==$array[$i]->title) ? 'current' : '' }}">{{ $array[$i]->title }}</a>
                @endfor
                </small>
            </li>
            @endif 
        @endif
    </ul>
    </div>
</div>

@endif

@if($page=='search')
<div class="category_filter">
    <ul class="menu mr_search mr_fix">
        <li><a href="{{ url('search?search_key='.$search_key) }}" class="{{ (@$search_for=='') ? 'current' : '' }}">{{ trans('messages.home.top') }}</a></li>        
        <li><a href="{{ url('search?search_key='.$search_key.'&search_for=things') }}" class="{{ (@$search_for=='things') ? 'current' : '' }}">{{ trans('messages.home.items') }}</a></li>
        <li><a href="{{ url('search?search_key='.$search_key.'&search_for=brands') }}" class="{{ (@$search_for=='brands') ? 'current' : '' }}">{{ trans('messages.home.stores') }}</a></li>
        <li><a href="{{ url('search?search_key='.$search_key.'&search_for=people') }}" class="{{ (@$search_for=='people') ? 'current' : '' }}">{{ trans('messages.home.people') }}</a></li>
    </ul>
</div>

<input type="hidden" id="search_for" name="search_for" value="{{@$search_for}}"/>
<input type="hidden" name="search_key" id="search_key" value="{{@$search_key}}"/>

@endif
@endsection