@extends('merchant_template') @section('main')
<div class="cls_dashmain">
<input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">
<div class="loading products_loading insight_loading" id="products_loading" style="display:none"></div>
<div class="container insights_container container-pad" ng-controller="merchant_insights" ng-cloak style="display: none">
    <div class="cls_allproduct pt-4">
    
        <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
            <div>
              <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('merchant/dashboard') }}">{{ trans('messages.header.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('messages.insight.insights_for') }} @{{startdate}} – @{{today}}</li>
                  </ol>
            </div>
            <input type="hidden" id="range" value="{{@$range}}">
            <input type="hidden" id="log_type" value="{{@$log_type}}">
            <input type="hidden" id="site_name" value="{{@$site_name}}">
            <input type="hidden" id="likes" value="{{trans('messages.home.likes')}}">
            <div class="cls_select_bread datelist">
                <button class="date-range btndate" onclick="$('.controll-date').toggle();">
                    {{ trans('messages.insight.last') }} @{{ range }} {{ trans('messages.insight.days') }}
                </button>
                <div class="controll-date controll-list" style="display: none;">

<!--                      <span class="trick" onclick="$('.controll-date').toggle();"></span>
 -->
                    <ul>
                        <li><a href="javascript:;" class="date_ " ng-class="range == 7 ? 'current ' : ''" range="7" ng-tet="@{{ range }}">{{ trans('messages.insight.last') }} 7 {{ trans('messages.insight.days') }}</a></li>
                        <li><a href="javascript:;" class="date_ " ng-class="range == 30 ? 'current ' : ''" range="30">{{ trans('messages.insight.last') }} 30 {{ trans('messages.insight.days') }}</a></li>
                        <li><a href="javascript:;" class="date_ " ng-class="range == 12 ? 'current ' : ''" range="12">{{ trans('messages.insight.last') }} 12 {{ trans('messages.insight.months') }}</a></li>
                    </ul>

                    <div class="after">
                        <a href="javascript:;" class="date_  specific" onclick="$('.date_detail').toggle();">{{ trans('messages.insight.specific_dates') }}</a>
                        <div class="date_detail " style="display: none;" ng-class="range == 'specific' ? 'current ' :'' ">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <input id="start_date" type="text" class="text-detail specific_date form-control col-lg-5" value="{{@$date_from}}" disabled="disabled"> -
                            <input id="end_date" type="text" class="text-detail specific_date form-control col-lg-5" value="{{@$date_to}}" disabled="disabled">
                            </div>
                            <div class="datepicker" style="width:221px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
    <div class="wrapper clsview_chart">
        <input type="hidden" id="current" value="all">

        <ul class="viewer">
            <li><a href="#" class="current" chart-type="area"><i class="icon graph"></i> {{ trans('messages.insight.overview') }}</a></li>
            <!-- <li style="float: right;padding-right: 10px;"><a href="javascript:;" target="_blank" class="export" style="float: right;padding-right: 10px;"><i class="icon export"></i> Export</a></li> -->
        </ul>
        <div class="col-lg-12 row">
            <ul class="type viewlist col-lg-2">
                <li>
                    <a href="javascript:;" class="" ng-class="log_type == 'view' ? 'current ' : ''" log-type="view">
                        <b>@{{total_views}}</b>
                        <label>{{ trans('messages.insight.clicks') }}</label>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="" ng-class="log_type == 'likes' ? 'current ' : ''" log-type="likes">
                        <b>@{{total_likes}}</b>
                        <label>{{trans('messages.home.likes')}}</label>
                    </a>
                </li>
                <li><a href="javascript:;" class="" ng-class="log_type == 'orders' ? 'current ' : ''" log-type="orders"><b>@{{total_order}}</b> <label>{{ trans('messages.insight.orders') }}</label></a></li>
                <li><a href="javascript:;" class="" ng-class="log_type == 'sales' ? 'current ' : ''" log-type="sales"><b>{{Session::get('symbol')}}  @{{total_amount}}</b> <label>{{ trans('messages.insight.sales') }}</label></a></li>
            </ul>
            <div class="chart col-lg-10">
                @include('merchant.chart')
            </div>
        </div>
    </div>
    <div class="row all-pro-table">
    
    <div class="col-lg-6">
        <div class="wrapper popular p-2 cls_maxheight">
            <table class="cls_mtable ">
                <thead>
                    <tr>
                        <th class="stit">{{ trans('messages.insight.most_popular_items') }}</th>
                        <th>{{ trans('messages.insight.total') }} {{trans('messages.home.likes')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="empty" id="pop_empty" style="display:none">
                        <td colspan="2">{{ trans('messages.insight.popular_items_found') }}</td>
                    </tr>

                    <tr id="pop_product" ng-repeat="popular in most_popular">
                        <th>
                            <a href="{{url('things')}}/@{{popular.pop_products.id}}" class="label">
                                <img ng-src="@{{popular.pop_products.image_name}}"> @{{popular.pop_products.title}}
                            </a>
                        </th>
                        <td class="total">@{{popular.most_popular_count}}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <div class=" col-lg-6">
        <div class="wrapper active p-2 cls_maxheight">
            <table class="cls_mtable">
                <thead>
                    <tr>
                        <th class="stit">{{ trans('messages.insight.most_clicked') }}</th>
                        <th>{{ trans('messages.insight.total') }} {{ trans('messages.insight.clicks') }}</th>
                    </tr>
                </thead>
                <tbody>

                    <tr class="empty" id="click_empty" style="display: none">
                        <td colspan="2">{{ trans('messages.insight.most_clicked_found') }}</td>
                    </tr>

                    <tr class="most_click" ng-repeat="stores in most_store">
                        <th>
                            <a href="{{url('store')}}/@{{stores.click_store.id}}" class="label"><img ng-src="@{{stores.click_store.logo_img}}" class="store"> Your Shop
                            </a>
                        </th>
                        <td class="total">@{{stores.most_store_count}}</td>
                    </tr>
                    <tr class="most_click" ng-repeat="click in most_click">
                        <th>
                            <a href="{{url('things')}}/@{{click.click_products.id}}" class="label">
                                <img ng-src="@{{click.click_products.image_name}}"> @{{click.click_products.title}}
                            </a>
                        </th>
                        <td class="total">@{{click.most_click_count}}</td>
                    </tr>
                </tbody>
            </table>
            <div class="paging"></div>
        </div>
    </div>
    </div>
</div>
</div>

@stop