@extends('merchant_template') @section('main')
<div class="cls_dashmain">
<div class="container container-pad " ng-controller="view_orders" ng-cloak ng-init="vm.subtotal=0;vm.shipping_fee=0;vm.incremental_fee=0;vm.merchant_fee=0;">
    <input type="hidden" value="{{ $orders_details[0]->order_id }}" id="order_id">
     <div class="cls_allproduct pt-4">
    
            <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
                <div>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item active">{{ trans('messages.header.orders') }} <small>#{{ $orders_details[0]->order_id }}</small></li>
                  </ol>
                  </div>
                  <a href="#" onclick="printDiv('prinArea')" class="btn-add">{{ trans('messages.order.print') }}</a>
                
                </div>
            </div>
    <div id="prinArea">
    <div id="printableArea" class="d-flex flex-wrap p-0 ">
        <div class="col-lg-9 all-pro-table">
            <div class="pro border-light nopad row" ng-repeat="order_item in orders">
              <div class="cls_orderlistall d-flex flex-wrap p-3 mb-3  ">
                <div class="product_image col-lg-2 p-0 col-12">
                    <img ng-src="@{{ order_item.products.image_name }}" />
                </div>
                <div class="product_details cls_orderlist col-12 col-lg-10">
                    <div class="sold_by">
                        <span ng-if="order_item.status=='Cancelled'"><b>{{ trans('messages.order.cancelled') }}</b>
                        <span ng-if="order_item.cancelled_by=='Buyer'">
                            ({{ trans('messages.order.by_buyer') }})
                        </span>
                                        <span ng-if="order_item.cancelled_by=='Merchant'"> 
                            ({{ trans('messages.order.by_merchant') }})
                        </span>
                        </span>
                        <span ng-if="order_item.status=='Pending'" class="badge badge-info">{{ trans('messages.order.pending') }}</span>
                        <span ng-if="order_item.status=='Completed'" class="badge badge-success">{{ trans('messages.order.completed') }}</span>
                        <span ng-if="order_item.status=='Exchanged'" class="badge badge-dark">{{ trans('messages.order.exchanged') }}</span>
                        <span ng-if="order_item.status=='Delivered'" class="badge badge-success">{{ trans('messages.order.delivered') }}</span>
                        <span ng-if="order_item.status=='Processing'" class="badge badge-warning">{{ trans('messages.order.processing') }}</span>
                        <span ng-if="order_item.status=='Returned'" class="badge badge-light">{{ trans('messages.order.returned') }}
                        <span ng-if="order_item.return_status=='Approved'" class="badge badge-success"> 
                        ({{ trans('messages.order.approved') }})
                        </span>

                         <span ng-if="order_item.return_status=='Requested'"> 
                        ({{ trans('messages.order.requested') }})
                        </span>

                         <span ng-if="order_item.return_status=='Awaiting'"> 
                        ({{ trans('messages.order.awaiting') }})
                        </span>

                        <span ng-if="order_item.return_status=='Received'"> 
                        ({{ trans('messages.order.received') }})
                        </span>

                        <span ng-if="order_item.return_status=='Rejected'"> 
                        ({{ trans('messages.order.rejected') }})  
                        </span>

                        <span ng-if="order_item.return_status=='Cancelled'"> 
                        ({{ trans('messages.order.cancelled') }})
                        </span>

                        <span ng-if="order_item.return_status=='Completed'"> 
                        ({{ trans('messages.order.completed') }})  
                        </span>
                        </span>
                        <!-- <span ng-if="order_item.status!='Cancelled'"><b>@{{ order_item.status }}</b></span> -->
                        <div class="status_loader_div" id="status_loader_@{{order_item.id}}" style="display:none">
                            <div class="status_loader"></div>
                        </div>
                        <div class="">
                            <a ng-if="order_item.status=='Pending' || order_item.status=='Processing'" href="javascript:;" class="dash-settings" id="@{{ $index+1 }}" style="float: right;"><i class="fa fa-cog" aria-hidden="true"></i> </a>
                            <div class="setting-popup" id='setting-popup-@{{ $index+1 }}'>
                                <ul class="msg-setting">
                                    <span ng-if="order_item.status=='Pending'" >        
                                      <li><a href="javascript:void(0);" data-id="@{{order_item.id}}" ng-click="merchant_action('process',order_item.id)" class="badge badge-warning">{{ trans('messages.order.mark_process') }}</a></li>
                                  </span>
                                    <span ng-if="order_item.status=='Processing'">
                                      <li><a href="javascript:void(0);" data-id="@{{order_item.id}}" ng-click="merchant_action('complete',order_item.id)"  class="badge badge-success">{{ trans('messages.order.mark_completed') }}</a></li>  
                                  </span>
                                    <span ng-if="order_item.status=='Pending' || order_item.status=='Processing'">
                                      <li ><a href="javascript:void(0);" id="@{{order_item.id}}" class="merchant_cancel_popup badge badge-info">{{ trans('messages.order.cancel_item') }}</a></li>  
                                  </span>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex align-items-center flex-wrap cls_list">
                        <label>{{ trans('messages.order.product_name') }} : </label>
                        <span class="text-truncate">@{{ order_item.products.title }}</span>
                        <em class="option_name" ng-if="order_item.product_option"> ( @{{ order_item.product_option.option_name }} )</em>
                    </div>
                    <div class="d-flex align-items-center flex-wrap cls_list">
                        <label>{{ trans('messages.order.price') }} : </label> <small ng-bind-html="order_item.currency_symbol"> </small>@{{ order_item.price}}
                      </div>
                    <div class="d-flex align-items-center flex-wrap cls_list">
                        <label>{{ trans('messages.order.quantity') }} : </label>@{{ order_item.quantity }}</div>
                    <div class="d-flex align-items-center flex-wrap cls_list">
                        <label ng-init="vm.subtotal = vm.subtotal + (order_item.price * order_item.quantity)">Total : </label> <small ng-bind-html="order_item.currency_symbol"> </small>@{{ (order_item.price * order_item.quantity) }}</div>
                    <span ng-init="vm.shipping_fee = vm.shipping_fee + order_item.shipping"></span>
                    <span ng-init="vm.merchant_fee = vm.merchant_fee + order_item.merchant_fee"></span>
                    <div class="d-flex align-items-center flex-wrap cls_list">
                        <label>{{ trans('messages.order.shipping_fee') }} : </label>
                        <span ng-if="order_item.shipping==null || order_item.shipping==0">{{ trans('messages.order.free_shipping') }}  </span>
                        <small ng-if="order_item.shipping!=null && order_item.shipping!=0"> <small ng-bind-html="order_item.currency_symbol"> </small>@{{ order_item.shipping }}</small>
                    </div>
                    <span ng-init="vm.incremental_fee = vm.incremental_fee + order_item.incremental"></span>
                    <span ng-if="order_item.incremental!=null && order_item.incremental>0">
                    <div class="d-flex align-items-center flex-wrap cls_list">
                      <label>{{ trans('messages.order.incremental_fee') }} : </label>
                      <span> <small ng-bind-html="order_item.currency_symbol"> </small>@{{ order_item.incremental }}</span>
                    </div>
                    </span>

                    <div class="d-flex align-items-center flex-wrap cls_list">
                        <label>{{ trans('messages.order.return_policy') }} : </label>
                        <span ng-if="order_item.return_policy==0">{{ trans('messages.order.no_return') }} </span>
                        <span ng-if="order_item.return_policy!=0">@{{ order_item.return_policy }} {{ trans('messages.order.days_return') }} </span>
                   </div>
                    <div ng-if="order_item.status=='Cancelled'">
                        @if(@$orders_details[0]->orders_cancel->cancel_reason)
                        <label> {{ trans('messages.order.cancel') }} {{ trans('messages.order.reason') }} :</label>
                        <span>{{ @$orders_details[0]->orders_cancel->cancel_reason }} </span> @endif
                    </div>

                </div>
              </div>
            </div>
        </div>
        <div class="col-lg-3" ng-repeat="order_items in orders_details">
          <div class="orderplace p-3">
            <div class="border-bottom py-2 border-light">
            <label>{{ trans('messages.order.order_placed') }} :</label><span> @{{ order_items.order_date }}</span></div>
            <div class="border-bottom py-2 border-light">
              <label>{{ trans('messages.order.order_id') }} :</label><span> # @{{order_items.id}} </span>
            </div>
            <div ng-if="order_items.paymode!='cos'" class="border-bottom py-2 border-light">
              <label class="">{{ trans('messages.order.ship_to') }} :</label>
                <span>
                @{{ order_items.shipping_details.address_line }},
                <span ng-if="order_items.shipping_details.address_line2 != NULL && order_items.shipping_details.address_line2 !=''">
                 @{{order_items.shipping_details.address_line2 }},
                </span> @{{ order_items.shipping_details.city }},
                 @{{ order_items.shipping_details.state }},
                 @{{ order_items.shipping_details.country }}-@{{ order_items.shipping_details.postal_code }}
               
                </span>
            </div>
            <div class="border-bottom py-2 border-light">
              <label>{{ trans('messages.order.payment_method') }} :</label><span> @{{ order_items.show_payment_mode }} </span>
            </div>
            <div class="border-bottom py-2 border-light">
              <label>{{ trans('messages.order.subtotal') }} : </label>  <span class="pull-left" ng-bind-html="order_items.currency_symbol"></span><span> @{{ vm.subtotal }}</span>
            </div>
            <div class="border-bottom py-2 border-light" ng-if='vm.shipping_fee!=0'>
              <span >
                <label>{{ trans('messages.order.shipping_fee') }} : </label>  <span ng-bind-html="order_items.currency_symbol" class="pull-left"></span><span>@{{ vm.shipping_fee }}</span>
              </span>
            </div>
          <div class="border-bottom py-2 border-light" ng-if='vm.incremental_fee>0'>
            <span >
              <label>{{ trans('messages.order.incremental_fee') }} :</label>  <span ng-bind-html="order_items.currency_symbol" class="pull-left"></span><span>@{{ vm.incremental_fee }}</span>
            </span>
          </div>
          <div class="border-bottom py-2 border-light" ng-if='vm.merchant_fee>0'>
            <span >
              <label>{{ trans('messages.checkout.merchant_fee') }} :  </label>  <span class="pull-left">-</span><span ng-bind-html="order_items.currency_symbol" class="pull-left"></span><span>@{{ vm.merchant_fee }}</span>
            </span>
          </div>
          <div class="border-bottom py-2 border-light">
            <label>{{ trans('messages.merchant.payout') }}:</label>  <span class="pull-left" ng-bind-html="order_items.currency_symbol"></span> <span>@{{ (vm.subtotal+vm.shipping_fee+vm.incremental_fee)- vm.merchant_fee}}</span>
          </div>
          </div>
        </div>
    </div>
  </div>

    <div class="add-fancy-back modal merchant-cancel-popup" style="display:none">
      <div class="d-flex align-items-center flex-wrap cls_modal_height">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><span class="order_title text-capitalize">{{ trans('messages.order.cancel') }}</span> {{ trans('messages.order.this_order') }}</h5>
              <button type="button" class="close common-close merchant-cancel-close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
           
            <div class="popup col-lg-12 col-sm-12 col-md-12 col-xs-12 nopad">
                <div class="edit-setting">
                    <input type="hidden" name="order_id" id="order_detail_id" value="">
                    <input type="hidden" name="order_action" id="order_action" value="">
                </div>
                <div class="edit-setting form-group">
                    <label><span class="order_title label">{{ trans('messages.order.cancel') }}</span> {{ trans('messages.order.reason') }}</label>
                    <br/>
                    <textarea class="form-control text" name="reason" id="reason_msg" cols="50" rows="10"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btns-gray merchant-cancel-close btn-dark btn">{{ trans('messages.order.cancel') }}</button>
                <button type="button" class="btn-blue-fancy btn-add" ng-click="merchant_action('cancel',1)">{{ trans('messages.order.save') }}</button>
            </div>

        </div>
      </div>
    </div>
</div>


<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;

    }
</script>
@stop
