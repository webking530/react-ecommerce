@extends('merchant_template')
@section('main')
<div class="cls_dashmain">
  @if(Session::has('message'))
  <div class="flash-container newflash" style="margin-top:58px; ">
    <div class="flash-container1">
      <div class="alert {{ Session::get('alert-class') }} success_msg" role="alert">
        <a href="#" class="alert-close" data-dismiss="alert"></a>
        {{ Session::get('message') }}
      </div>
    </div>
  </div>
  @endif
  <div class="intro">
    <p><b>{{ trans('messages.merchant.welcome') }}, {{@$store_name}}</b></p>
  </div>
  <div class="container dashboard_content d-lg-flex d-md-flex" ng-controller="merchant_dashboard" ng-cloak>
    <div class="content col-md-8 col-lg-8 col-12">
      <div class="wrapper get-started">
        <h3><b>{{ trans('messages.merchant.get_started') }}</b></h3>
        <span class="progress" style="width:14%"></span>
        <ul>
          <li class="{{ @$user_status=='Active' ? 'completed' : ''}}"><span class="num">1</span>
          <label>{{ trans('messages.merchant.confirm_email_address') }}</label>
          @if(@$user_status !='Active')
          <a href="{{url('edit_profile')}}" class="btns-white">{{ trans('messages.merchant.open_settings') }}</a>@endif</li>
          
          <li class="{{ @$product_count!='0' ? 'completed' : ''}}"><span class="num">2</span>
          <label>{{ trans('messages.merchant.add_first_product_store') }}</label>
          @if(@$product_count =='0')
          <a href="{{url('merchant/add_product')}}" class="btns-white verify_phone">{{ trans('messages.merchant.add_product') }}</a>@endif</li>
          
          <li class="{{ @$payout_preferences!='0' ? 'completed' : ''}}"><span class="num">3</span>
          <label>{{ trans('messages.merchant.add_payout_info') }}</label>
          @if(@$payout_preferences =='0')
          <a href="{{ route('merchant.settings_paid') }}" class="btns-white">{{ trans('messages.merchant.open_settings') }}</a>@endif</li>
        </ul>
      </div>
      <div class="wrapper activity" id="merchant_activity">
        <h3><b>{{ trans('messages.merchant.store_activity') }}</b></h3>
        @if(count(@$notification_feed) == 0)
        <div class="empty">
          <p><b>{{ trans('messages.header.no_store_activity') }}</b> {{ trans('messages.header.no_store_activity_desc') }}</p>
        </div>
        @else
        <ul>
          @foreach(@$notification_feed as $feed) @if($feed->notification_type !='featured')
          <li style="width:100%;">
            
            <div class="msg-body2 d-flex align-items-center flex-wrap">
              <a href="{{ url('profile') }}/{{@$feed->users->user_name}}" class="username">
                <img src="{{@$feed->users->original_image_name}}" width="30px" height="30px" class="avatar">
              </a>
              <a href="{{ url('profile') }}/{{@$feed->users->user_name}}" class="text-truncate label"> {{@$feed->users->full_name}}</a>
              <label class="text-truncate label">{{ @$feed->trans_message}}</label>
              @if(@$feed->notification_type=='order' && @$feed->notification_type_status!='refund' && @$feed->notification_type_status!='payout') @if(@$feed->products->user_id== $user_from)
              <a href="{{ url('merchant/order') }}/{{@$feed->order_id}}" class="text-truncate label">#{{ @$feed->order_id}}</a> @else
              <a href="{{ url('purchases') }}/{{@$feed->order_id}}" class="text-truncate label">#{{ @$feed->order_id}}</a> @endif @elseif(@$feed->notification_type=='like_product' || $feed->notification_type=='wishlist')
              <a href="{{ url('things') }}/{{@$feed->product_id}}" class="text-truncate label">#{{@$feed->products->title}}</a> @elseif(@$feed->notification_type_status=='refund')
              <a href="{{ url('purchases') }}/{{@$feed->order_id}}" class="text-truncate label">#{{@$feed->order_id}}</a> @endif
            </div>
          </li>
          @else
          <li style="width:100%;">
            
            <div class="msg-body2 d-flex align-items-center flex-wrap">
              <a href="{{ url('things') }}/{{@$feed->product_id }}" class="">
                <img src="{{ $favicon }}" width="30px" height="30px" class="avatar">
              </a>
              <label class="text-truncate label1">{{ @$feed->trans_message}}</label>
              <a href="{{ url('things') }}/{{@$feed->product_id}}" class="text-truncate label1">#{{@$feed->products->title}}</a>
            </div>
          </li>
          @endif @endforeach
        </ul>
        @endif
      </div>
    </div>
    <div class="sidebar col-md-4 col-lg-4 col-12">
      <div class="wrapper insights">
        <h3><b>{{ trans('messages.insight.insights') }}</b></h3>
        <div class="date">
          <span class="selector">
            <em>Last 12 Months</em>
            
            <select class="every-select" id='insights_details'>
              <option vlaue="1">{{ trans('messages.insight.today') }}</option>
              <option value='7'>{{ trans('messages.insight.last') }} 7 {{ trans('messages.insight.days') }}</option>
              <option value='30'>{{ trans('messages.insight.last') }} 30 {{ trans('messages.insight.days') }}</option>
              <option value='12'>{{ trans('messages.insight.last') }} 12 {{ trans('messages.insight.days') }}</option>
            </select>
          </span>
        </div>
        <div class="sales">
          <small>{{ trans('messages.merchant.your_sales') }}</small>
          <b>{{ session::get('symbol')}} @{{total_amount}}</b>
        </div>
        <ul>
          <li class=""><b>@{{total_order}}</b> <small>{{ trans('messages.merchant.orders') }}</small></li>
          <li class=""><b>@{{total_views}}</b> <small>{{ trans('messages.merchant.views') }}</small></li>
          
          <li class=""><b>@{{total_likes}}</b> <small>{{trans('messages.home.likes')}}</small></li>
          
        </ul>
      </div>
    </div>
  </div>
</div>
@stop