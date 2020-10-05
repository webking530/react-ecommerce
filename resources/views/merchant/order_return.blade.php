@extends('merchant_template') @section('main')
<div class="cls_dashmain">
<input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">
<div class="container container-pad" ng-controller="returns_requests" ng-cloak>
    <input type="hidden" id="pagin_next" value="{{ trans('messages.pagination.pagi_next') }} ">
    <input type="hidden" id="pagin_prev" value="{{ trans('messages.pagination.pagi_prev') }} ">

  
    <div class="cls_allproduct pt-4">
    
        <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
            <div>
              <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">{{ trans('messages.header.orders') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('messages.header.return_requests') }}</li>
                  </ol>
            </div>
        </div>
     <div class="cls_merchentwrapper new-listing">
        <input type="hidden" id="current" value="all">
        <div class="check-active">
            <div class="data-field empty d-flex justify-content-between align-items-center flex-wrap">
                <div class="col-lg-6 p-0">
                    <div class="dropdown check return_dropdown">
                        <span class="checkbox" id="no-sel" onclick="$(this).closest('.checkbox').toggleClass('all-sel'); $(this).closest('.check-active').toggleClass('check-active-true');return false;"></span>
                        <a href="#" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;"></a>
                        <ul class="check_action">
                            <li data-status="all"><a href="javascript:;">{{ trans('messages.order.all') }}</a></li>
                            <li data-status="none"><a href="javascript:;">{{ trans('messages.order.none') }}</a></li>
                        </ul>
                    </div>
                    <div class="dropdown bulk action" style="display: none;">
                        <a href="#" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;"><span  class="action_count"></span> {{ trans('messages.order.actions') }}</a>
                        <ul class="check_action">
                            <li class="hr"></li>
                            <li class="accept_select" type="single"><a href="javascript:void(0)" data="accept" class="bulk-items accept-items">{{ trans('messages.order.accept_items') }}</a></li>
                            <li class="reject_select" type="single"><a href="javascript:void(0)" data="reject" class="bulk-items reject-items">{{ trans('messages.order.reject_items') }}</a></li>
                        </ul>
                    </div>
                    <div class="dropdown bulk select-order">
                        <a href="javascript:void(0)" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;">{{ trans('messages.order.select_orders') }}</a>

                    </div>
                </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center p-0">
                    <!-- order search input -->
                    <i class="icon-pos icon-search-1 smallsearch" aria-hidden="true"></i>
                    <fieldset class="input-set dropdown nopad " style="border:0px;">
                        <input type="hidden" name="search_by" id="search_by" value="all">
                         <div>
                        <i class="icon-del fa fa-times" ng-click="search = null ; resetfilter()" aria-hidden="true"></i>
                         <input name="search" ng-model="search" id="search" class="text head-search" placeholder="{{ trans('messages.order.search') }}" type="text">
                        </div>
                         <div class="dropdown cls_searcgright">
                         <a class="toggle-button"  aria-hidden="true" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;" style="padding-left: 18px;"></a>
                      
                        <ul name="search-field" class="search-product-filter dropdownlist check_action">
                            <li><a href="javascript:void(0);" ng-click="updateFilterby('order_id')">{{ trans('messages.order.order_id') }}</a></li>
                            <li><a href="javascript:void(0);" ng-click="updateFilterby('username')">{{ trans('messages.order.search_customer') }}</a></li>
                        </ul>
                        </div>
                    </fieldset>
                   
                </div>
            </div>
        </div>
        <div class="all-pro-table tablelist">
            <div class="table">
                <table class="cls_mtable tb-type4 table-trips">
                    <colgroup>
                        <col style="*">
                        <col style="width:110px;">
                        <col style="width:110px;">
                        <col style="width:80px;">
                        <col style="width:100px;">
                        <col style="width:100px;">
                        <col style="width:44px;">
                        </colgroup>
                    <thead>
                        <tr class="table-head">
                            <th><span>{{ trans('messages.order.request_id') }}</span></th>
                            <th><span>{{ trans('messages.order.order_no') }}</span></th>
                            <th><span>{{ trans('messages.order.customer') }}</span></th>
                            <th style="width: 20%;"><span>{{ trans('messages.order.date') }}</span></th>
                            <th><span>{{ trans('messages.order.order_status') }}</span></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-body" ng-repeat="order_item in orders.data">
                            <td class="title">
                                <input data-id="@{{ order_item.id }}" data-thing-id="@{{ order_item.id }}" status="active" type="checkbox" class="pro-check @{{ order_item.status }}"> @{{ order_item.id }}
                            </td>
                            <td class="qty-td">
                                <a href="{{ url('merchant/order')}}/@{{ order_item.orders_details[0].order_id }}" class="after">
                                    # @{{ order_item.orders_details[0].order_id  }}
                                </a>

                            </td>
                            <td>@{{ order_item.orders_details[0].orders.buyer_name }}</td>
                            <td>
                                @{{ order_item.created_at }}
                            </td>
                            <td class="price">
                                <span ng-if="order_item.status=='Approved'"> 
                                    {{ trans('messages.order.approved') }}
                                    </span>

                                <span ng-if="order_item.status=='Requested'"> 
                                    {{ trans('messages.order.requested') }} 
                                    </span>

                                <span ng-if="order_item.status=='Awaiting'"> 
                                    {{ trans('messages.order.awaiting') }}
                                    </span>

                                <span ng-if="order_item.status=='Received'"> 
                                    {{ trans('messages.order.received') }}
                                    </span>

                                <span ng-if="order_item.status=='Rejected'"> 
                                    {{ trans('messages.order.rejected') }}  
                                    </span>

                                <span ng-if="order_item.status=='Cancelled'"> 
                                    {{ trans('messages.order.cancelled') }}
                                    </span>

                                <span ng-if="order_item.status=='Completed'"> 
                                    {{ trans('messages.order.completed') }}  
                                    </span>
                            </td>
                            <td>
                                <div class="setting-menu">
                                    <!-- <a href="#" class="btn-setting" onclick="$(this).closest('.setting-menu').toggleClass('opened');return false;"><i class="icon"></i></a> -->
                                    <select class="select_order" data-id="@{{ order_item.id }}" sale-id="2271636" style="width: 100px">
                                        <option value="none">{{ trans('messages.order.action') }}</option>
                                        <option value="accept" ng-if="order_item.status=='Requested'">{{ trans('messages.order.accept') }}</option>
                                        <option value="reject" ng-if="order_item.status=='Requested'">{{ trans('messages.order.reject') }}</option>
                                        <option value="completed" ng-if="order_item.status=='Approved' || order_item.status=='Rejected'">{{ trans('messages.order.completed') }}</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="table-body no_orders" style="display: none;text-align: center;padding: 10px;background: #f1f1f1;">
                    {{ trans('messages.order.no_orders_found') }}
                </div>
            </div>

            <div class="pb-4">
                <returns-pagination ng-if="orders.total > 10"></returns-pagination>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="active-error" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default js-delete-close" data-toggle="modal" data-target="" data-dismiss="modal">Ok</button>
            </div>

        </div>

    </div>
</div>


@stop