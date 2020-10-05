@extends('merchant_template') @section('main')
<div class="cls_dashmain">
<input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">

<div class="container container-pad" ng-controller="orders" ng-cloak>
    <input type="hidden" id="pagin_next" value="{{ trans('messages.pagination.pagi_next') }} ">
    <input type="hidden" id="pagin_prev" value="{{ trans('messages.pagination.pagi_prev') }} ">
     <div class="cls_allproduct pt-4">
    
      <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">{{ trans('messages.header.orders') }}</li>
          </ol>
        </div>
        <!-- <a href="{{ url('merchant/add_product') }}" class="btn-add">{{ trans('messages.products.add_product') }}</a> -->
    </div>
    
    <div class="cls_merchentwrapper new-listing">
        <input type="hidden" id="current" value="all">
        <ul class="cls_mtab tab3">
            <li>
              <a href="javascript:void(0);" data="all" name="all" class="active current">
            {{ trans('messages.order.all') }}         
            <small ng-if="count_all > 0">@{{ count_all }}</small>            
              </a>
            </li>
            <li>
                <a href="javascript:void(0);" data="open" name="open" class="open ">
            {{ trans('messages.order.open') }}       
                <small ng-if=" count_open > 0">@{{ count_open }}</small>            
              </a>
            </li>
            <li>
                <a href="javascript:void(0);" data="completed" name="completed" class="completed">
            {{ trans('messages.order.completed') }} 
            <small ng-if="count_completed > 0">@{{ count_completed }}</small>
              </a>
            </li>
            <li>
                <a href="javascript:void(0);" data="cancelled" name="cancelled" class="cencelled">
            {{ trans('messages.order.cancelled') }} 
            <small ng-if="count_cancelled > 0">@{{ count_cancelled }}</small>            
            </a>
            </li>
        </ul>
         <div class="check-active">
            <div class="data-field empty d-flex justify-content-between align-items-center flex-wrap">
              <div class="col-lg-6 p-0">
                <div class="dropdown check order_dropdown">
                    <span class="checkbox" id="no-sel" onclick="$(this).closest('.checkbox').toggleClass('all-sel'); $(this).closest('.check-active').toggleClass('check-active-true');return false;"></span>
                    <a href="#" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;"></a>
                    <ul class="check_action">
                        <li data-status="all"><a href="javascript:;">{{ trans('messages.order.all') }}</a></li>
                        <li data-status="none"><a href="javascript:;">{{ trans('messages.order.none') }}</a></li>
                        <li data-status="open"><a href="javascript:;">{{ trans('messages.order.open') }}</a></li>
                        <li data-status="completed"><a href="javascript:;">{{ trans('messages.order.completed') }}</a></li>
                        <li data-status="cancelled"><a href="javascript:;">{{ trans('messages.order.cancelled') }}</a></li>
                    </ul>
                </div>
                <div class="dropdown bulk action" style="display: none;">
                    <a href="#" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;"><span  class="action_count"></span> {{ trans('messages.order.action') }}</a>
                    <ul class="check_action">
                        <li class="hr"></li>
                        <li class="process_select" type="single"><a href="javascript:void(0);" data="process" class="bulk-items process-items">{{ trans('messages.order.process_items') }}</a></li>
                        <li class="completed_select" type="single"><a href="javascript:void(0);" data="completed" class="bulk-items complete-items">{{ trans('messages.order.complete_items') }}</a></li>
                    </ul>
                </div>
                <div class="dropdown bulk select-order">
                    <a href="javascript:;" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;">{{ trans('messages.order.select_orders') }}</a>
                </div>
              </div>
              <div class="col-lg-6 d-flex justify-content-end align-items-center p-0">
                      <!-- order search input -->
                      <i class="icon-pos icon-search-1 smallsearch" aria-hidden="true"></i>
                      <fieldset class="input-set dropdown nopad " style="border:0px;">
                          <input type="hidden" name="search_by" id="search_by" value="order_id">
                          <div>
                          <i class="icon-del fa fa-times" ng-click="search = null ; resetfilter()" aria-hidden="true"></i>
                           <input name="search" ng-model="search" id="search" class="text head-search" placeholder="{{ trans('messages.order.search_order_id') }}" type="text">
                         </div>
                            <div class="dropdown cls_searcgright">
                          <a class="toggle-button"  aria-hidden="true" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;" style="padding-left: 18px;"></a>
                         
                          <ul name="search-field" class="search-product-filter droporder check_action">
                              <li><a href="javascript:void(0);" ng-click="updateFilterby('order_id')">{{ trans('messages.order.order_id') }}</a></li>
                              <li><a href="javascript:void(0);" ng-click="updateFilterby('username')">{{ trans('messages.order.user_name') }}</a></li>
                              <li><a href="javascript:void(0);" ng-click="updateFilterby('fullname')">{{ trans('messages.order.full_name') }}</a></li>
                          </ul>
                        </div>
                      </fieldset>

              </div>
            </div>
        </div>
        <div class="all-pro-table tablelist">
            <div class="table ordertable">
                <table class="cls_mtable table-trips tableorderlist">
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
                        <tr class="table-head listhead">
                            <th><span>{{ trans('messages.order.orders') }}</span></th>
                            <th><span>{{ trans('messages.order.date') }}</span></th>
                            <th><span>{{ trans('messages.order.customer') }}</span></th>
                            <th style="padding: 0 25px; width: 130px;"><span>{{ trans('messages.order.total_spent') }}</span></th>
                            <th></th>
                        </tr>
                      </thead>
                        <tbody>
                        <tr class="table-body tabledata" ng-repeat="order_item in orders.data">
                            <td class="title">
                                <input data-id="@{{ order_item.order_id }}" data-thing-id="@{{ order_item.order_id }}" status="active" type="checkbox" class="pro-check @{{ order_item.status }}">
                                <a href="{{ url('merchant/order')}}/@{{ order_item.order_id }}" class="after text-truncate">
                                  # @{{ order_item.order_id }}
                              </a>
                            </td>
                            <td class="qty-td">
                                <div class="qty">
                                    @{{ order_item.orders.order_date }}
                                </div>
                            </td>
                            <td>@{{ order_item.orders.buyer_name }}</td>
                            <td class="price"><span ng-bind-html="order_item.currency_symbol"></span>@{{ (order_item.total_order_amount + order_item.total_order_shipping + order_item.total_order_incremental) - order_item.total_order_merchant }}</td>
                            <td>
                                <div class="setting-menu btn-setting" onclick="$(this).closest('.setting-menu').toggleClass('opened');return false;">
                                    <!-- <i class="icon"></i> </a> -->
                                    <select class="select_order" data-id="@{{ order_item.order_id }}" sale-id="2271636" style="width: 100px;">
                                        <option value="none">{{ trans('messages.order.action') }}</option>
                                        <option value="process" ng-if="order_item.status_open !='0'">{{ trans('messages.order.mark_process') }}</option>
                                        <option value="completed" ng-if="order_item.status_open == '0' && order_item.status_processing !='0'">{{ trans('messages.order.finish_order') }}</option>
                                        <option value="view_order">{{ trans('messages.order.view_order') }}</option>
                                        <option value="print_receipt">{{ trans('messages.order.print_receipt') }}</option>
                                    </select>

                                </div>
                            </td>

                            <td style="display: none">
                                <div id="printableArea_@{{ order_item.order_id }}" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none">
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 all-pro-table" style="display:block;float:left;width:60%;">
                                        <div class="pro col-lg-4  nopad" ng-repeat="order_item_print in order_item.orders.orders_details" style="display:block;float:left;width:100%;">
                                            <div class="product_image col-md-2" style="float:left;width:25%;">
                                                <img height="50px" width="50px" ng-src="@{{ order_item_print.products.image_name }}" />
                                            </div>
                                            <div class="product_details col-md-10" style="float:left;width:75%;">
                                                <div class="sold_by">
                                                    <span ng-if="order_item_print.status=='Cancelled'"><b>{{ trans('messages.order.cancelled') }}</b>
                                                    <span ng-if="order_item_print.cancelled_by=='Buyer'">
                                                        ({{ trans('messages.order.by_buyer') }})
                                                    </span>
                                                    <span ng-if="order_item_print.cancelled_by=='Merchant'"> 
                                                        ({{ trans('messages.order.by_merchant') }})
                                                    </span>
                                                    </span>
                                                    <span ng-if="order_item_print.status=='Pending'">{{ trans('messages.order.pending') }}</span>
                                                    <span ng-if="order_item_print.status=='Completed'">{{ trans('messages.order.completed') }}</span>
                                                    <span ng-if="order_item_print.status=='Exchanged'">{{ trans('messages.order.exchanged') }}</span>
                                                    <span ng-if="order_item_print.status=='Delivered'">{{ trans('messages.order.delivered') }}</span>
                                                    <span ng-if="order_item_print.status=='Processing'">{{ trans('messages.order.processing') }}</span>
                                                    <span ng-if="order_item_print.status=='Returned'">{{ trans('messages.order.returned') }}
                                                    <span ng-if="order_item_print.return_status=='Approved'"> 
                                                    ({{ trans('messages.order.approved') }})
                                                    </span>

                                                    <span ng-if="order_item_print.return_status=='Requested'"> 
                                                    ({{ trans('messages.order.requested') }})
                                                    </span>

                                                    <span ng-if="order_item_print.return_status=='Awaiting'"> 
                                                    ({{ trans('messages.order.awaiting') }})
                                                    </span>

                                                    <span ng-if="order_item_print.return_status=='Received'"> 
                                                    ({{ trans('messages.order.received') }})
                                                    </span>

                                                    <span ng-if="order_item_print.return_status=='Rejected'"> 
                                                    ({{ trans('messages.order.rejected') }})  
                                                    </span>

                                                    <span ng-if="order_item_print.return_status=='Cancelled'"> 
                                                      ({{ trans('messages.order.cancelled') }})
                                                      </span>

                                                    <span ng-if="order_item_print.return_status=='Completed'"> 
                                                    ({{ trans('messages.order.completed') }})  
                                                    </span>
                                                    </span>

                                                </div>
                                                <p>
                                                    <label>{{ trans('messages.order.product_name') }} : </label>
                                                    @{{ order_item_print.products.title }}
                                                    <em class="option_name" ng-if="order_item_print.product_option"> ( @{{ order_item_print.product_option.option_name }} )</em>
                                                </p>
                                                <p>
                                                    <label>{{ trans('messages.order.price') }} : </label><span ng-bind-html="order_item_print.currency_symbol"> </span>@{{ order_item_print.price}} </p>
                                                <p>
                                                    <label>{{ trans('messages.order.quantity') }} : </label>@{{ order_item_print.quantity }}</p>
                                                <p>
                                                    <label>{{ trans('messages.order.total') }} : </label><span ng-bind-html="order_item_print.currency_symbol"> </span>@{{ (order_item_print.price * order_item_print.quantity) }}</p>

                                                <p ng-if='order_item_print.shipping !=0'>
                                                    <label>{{ trans('messages.order.shipping_fee') }} : </label>
                                                    <span ng-if="order_item_print.shipping==null">{{ trans('messages.order.free_shipping') }}  </span>
                                                    <span ng-if="order_item_print.shipping!=null"> <span></span>@{{ order_item_print.shipping }}</span>
                                                </p>

                                                <p ng-if='order_item_print.incremental !=null && order_item_print.incremental>0'>
                                                    <label>{{ trans('messages.order.incremental_fee') }} : </label>
                                                    <span ng-if="order_item_print.incremental!=null"> <span></span>@{{ order_item_print.incremental }}</span>
                                                </p>

                                                <p>
                                                    <label>{{ trans('messages.order.return_policy') }} : </label>
                                                    <span ng-if="order_item_print.return_policy==0">{{ trans('messages.order.no_return') }} </span>
                                                    <span ng-if="order_item_print.return_policy!=0">@{{ order_item_print.return_policy }} {{ trans('messages.order.days_return') }} </span>
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 orderplace" style="display:block;float:left;width:30%;background-color: #f2f2f2;">
                                        <h5 style="font-weight: 900; ">{{ trans('messages.order.order_placed') }} :</h5> @{{ order_item.orders.order_date }}
                                        <h5 style="font-weight: 900; ">{{ trans('messages.order.order_id') }} :</h5> # @{{order_item.orders.id}}
                                        <span ng-if="order_item.orders.paymode!='cos'">
                                        <h5 style="font-weight: 900; ">{{ trans('messages.order.ship_to') }} :</h5>
                                        @{{ order_item.orders.shipping_details.address_line }},<br/>
                                        <span ng-if="order_item.orders.shipping_details.address_line2 != NULL && order_item.orders.shipping_details.address_line2 !=''">
                                        @{{order_item.orders.shipping_details.address_line2 }},<br/>
                                        </span> @{{ order_item.orders.shipping_details.city }},
                                        <br/> @{{ order_item.orders.shipping_details.state }},
                                        <br/> @{{ order_item.orders.shipping_details.country }}-@{{ order_item.orders.shipping_details.postal_code }}
                                        <br/>
                                        </span>
                                        <h5 style="font-weight: 900; ">{{ trans('messages.order.payment_method') }} :</h5> @{{ order_item.orders.show_payment_mode }}

                                        <h5 style="font-weight: 900; ">{{ trans('messages.order.subtotal') }} : <span><span ng-bind-html="order_item.currency_symbol"> </span>@{{ order_item.total_order_amount }} </span></h5>
                                        <h5 style="font-weight: 900; " ng-if='order_item.total_order_shipping!=0 && order_item.total_order_shipping>0'>{{ trans('messages.order.shipping_fee') }} : <span>@<span ng-bind-html="order_item.currency_symbol"> </span> @{{ order_item.total_order_shipping }} </span></h5>
                                        <h5 style="font-weight: 900; " ng-if='order_item.total_order_incremental!=null && order_item.total_order_incremental>0'>{{ trans('messages.order.incremental_fee') }} : <span>@<span ng-bind-html="order_item.currency_symbol"> </span> @{{ order_item.total_order_incremental }} </span></h5>
                                        <h5 style="font-weight: 900; ">{{ trans('messages.order.total') }} : <span><span ng-bind-html="order_item.currency_symbol"> </span>@{{ order_item.total_order_amount + order_item.total_order_shipping + order_item.total_order_incremental}} </span></h5>

                                    </div>
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
                <orders-pagination ng-if="orders.total > 10"></orders-pagination>
            </div>

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