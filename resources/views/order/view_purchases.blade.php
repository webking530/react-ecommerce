@extends('settings_template')
@section('main')
<div ng-controller="purchases" ng-cloak class="col-lg-9 col-12 setting-rightbar cls_generator">
        <div class="cls_setting1 d-flex align-items-center justify-content-between col-lg-12" ng-cloak>
            <h2 class="csv_tit" style="margin: 0px;">{{ trans('messages.header.orders') }}</h2>

            <ul class="new_product flt-right or_flt">
                <a href="{{ url('/invoice/'.@$order_id) }}" class="btn-add btn">{{ trans('messages.order.generate_invoice') }}</a>
                <a href="#" onclick="printDiv('printableArea')" class="grey-btn btn btn-light">{{ trans('messages.order.print') }}</a>
            </ul>
        </div>
        <input type="hidden" name="orderid" value="{{@$order_id}}">

        <div class="no-data" style="display: none">
            <span class="icon"></span>
            <p>{{ trans('messages.order.no_order_msg') }}</p>
        </div>
        <div class="loading products_loading" id="products_loading" style="display:none"></div>
        <div class="table" id="printableArea" style="display:none;">
            <div class="d-flex row">
            <div class="or_over col-lg-8 col-12">
                <table class="cls_mtable table-trips tableorderlist order_tpy" style="background-color: #fff;">
                  
                    <th><span>Id</span></th>
                    <th><span>{{ trans('messages.order.items') }}</span></th>

                    <tbody class="body_tab">

                        <tr class="table-body nt_ort" ng-repeat="product_value in all_purchases" ng-cloak>
                            @{{product_value.product_id}}
                            <td>
                                @{{ $index+1 }}
                            </td>
                            <td>
                                <table class="cls_mtable table-trips tableorderlist tb-change">
                                    <tr class="table-head">
                                        <td style="text-align: center;">
                                            <a href="{{ url('/things') }}/@{{product_value.product_id}}" target="blank">
                                                <img ng-src="@{{product_value.products.image_name}}" style="width: 75px;"> </a>
                                        </td>
                                        <td>
                                            <h5 class="p-2"><a class="small" href="{{ url('/things/') }}/@{{product_value.product_id}}" target="blank">@{{product_value.products.title }}</a> 
                                                <!--product_value.status_return != 0 && (product_value.status=='Pending' || product_value.status =='Processing' || product_value.status !='Cancelled') && (product_value.payout_date == 'Yes') -->
                                                <a ng-if="(product_value.status_return != 0 && product_value.payout_date == 'Yes') || ((product_value.status=='Pending' || product_value.status=='Processing') && product_value.status !='Cancelled')" href="javascript:;" class="dash-settings" id="@{{ $index+1 }}" style="float: right;"><i class="fa fa-cog" aria-hidden="true"></i> </a> </h5>
                                            </h5>
                                            <div class="setting-popup" id='setting-popup-@{{ $index+1 }}'>
                                                <ul class="msg-setting">
                                                    <span ng-if="(product_value.status=='Pending' || product_value.status =='Processing') && product_value.status !='Cancelled' ">        
                                                        <li>
                                                            <a href="javascript:void(0);" id="@{{product_value.id}}" data-msg="cancel" data-value="{{ trans('messages.order.cancel') }}" class="order_popup">{{ trans('messages.order.cancel') }}</a>
                                                        </li>
                                                    </span>
                                                    <span ng-if="product_value.status_return != 0">
                                                        <li>
                                                            <a href="javascript:void(0);" id="@{{product_value.id}}" data-msg="return" data-value="{{ trans('messages.order.return') }}" class="order_popup">{{ trans('messages.order.return') }}</a>
                                                        </li>  
                                                    </span>
                                                </ul>
                                            </div>
                                            <table class="cls_mtable cls_tablechange table-trips tableorderlist">
                                                <thead>
                                                <tr style="font-weight: 700; ">
                                                    <td>{{ trans('messages.order.price') }}</td>
                                                    <td>{{ trans('messages.order.quantity') }}</td>
                                                    <td>{{ trans('messages.order.total') }}</td>
                                                    <td>{{ trans('messages.order.status') }}</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <span ng-bind-html="product_value.currency_symbol"></span> @{{product_value.price}}

                                                    </td>
                                                    <td>@{{product_value.quantity}}</td>
                                                    <td><span ng-bind-html="product_value.currency_symbol"></span> @{{product_value.price * product_value.quantity}}</td>
                                                    <td>
                                                        <span ng-if="product_value.status=='Cancelled'">{{ trans('messages.order.cancelled') }}
                                                        <span ng-if="product_value.cancelled_by=='Buyer'">
                                                            ({{ trans('messages.order.by_buyer') }})
                                                        </span>
                                                        <span ng-if="product_value.cancelled_by=='Merchant'"> 
                                                            ({{ trans('messages.order.by_merchant') }})
                                                        </span>
                                                        </span>
                                                        <span ng-if="product_value.status=='Pending'">{{ trans('messages.order.pending') }}</span>
                                                        <span ng-if="product_value.status=='Completed'">{{ trans('messages.order.completed') }}</span>
                                                        <span ng-if="product_value.status=='Exchanged'">{{ trans('messages.order.exchanged') }}</span>
                                                        <span ng-if="product_value.status=='Delivered'">{{ trans('messages.order.delivered') }}</span>
                                                        <span ng-if="product_value.status=='Processing'">{{ trans('messages.order.processing') }}</span>
                                                        <span ng-if="product_value.status=='Returned'">{{ trans('messages.order.returned') }} 
                                                        <span ng-if="product_value.return_status=='Approved'"> 
                                                        ({{ trans('messages.order.approved') }})
                                                        </span>

                                                        <span ng-if="product_value.return_status=='Requested'"> 
                                                        ({{ trans('messages.order.requested') }})  
                                                        </span>

                                                        <span ng-if="product_value.return_status=='Awaiting'"> 
                                                        ({{ trans('messages.order.awaiting') }})
                                                        </span>

                                                        <span ng-if="product_value.return_status=='Received'"> 
                                                        ({{ trans('messages.order.received') }})
                                                        </span>

                                                        <span ng-if="product_value.return_status=='Rejected'"> 
                                                        ({{ trans('messages.order.rejected') }})  
                                                        </span>

                                                        <span ng-if="product_value.return_status=='Cancelled'"> 
                                                        ({{ trans('messages.order.cancelled') }})
                                                        </span>

                                                        <span ng-if="product_value.return_status=='Completed'"> 
                                                        ({{ trans('messages.order.completed') }})  
                                                        </span>
                                                        </span>
                                                    </td>

                                                </tr>
                                                </tbody>
                                            </table>
                                            <!--             
                                            <span><b>Shipping Type</b> : 
                                                <span ng-if="product_value.shipping != null">
                                                Flat Rating 
                                                </span>
                                                <span ng-if="product_value.shipping == null">
                                                 Free Shipping </span>
                                             </span> -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="cls_mtable table-trips tableorderlist order_tpy1 col-lg-4 col-12" ng-cloak>
                <tr class="table-body">
                    <td>
                        <h5 style="font-weight: 900; ">{{ trans('messages.order.order_placed') }} :</h5> @{{ order.order_date }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5 style="font-weight: 900; ">{{ trans('messages.order.order_id') }} :</h5> # @{{order.id}}
                    </td>
                </tr>
                <tr ng-if="order.paymode!='cos'">
                    <td>
                        <h5 style="font-weight: 900; ">{{ trans('messages.order.ship_to') }} :</h5> @{{ shipping_address.address_line }},
                        <br/>
                        <span ng-if="shipping_address.address_line2 != NULL && shipping_address.address_line2 !=''">
                         @{{shipping_address.address_line2 }},
                        </span> @{{ shipping_address.city }},
                         @{{ shipping_address.state }},
                        @{{ shipping_address.country }}-@{{ shipping_address.postal_code }}
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5 style="font-weight: 900; ">{{ trans('messages.order.payment_method') }}:</h5> @{{ order.show_payment_mode }}
                    </td>
                </tr>

                <tr>
                    <td>{{ trans('messages.order.subtotal') }}: <span ng-bind-html="order.currency_symbol"></span> @{{ order.subtotal }}</td>
                </tr>

                <tr ng-show="order.paymode!='cos' && order.shipping_fee!=0">
                    <td>{{ trans('messages.order.shipping_fee') }} : <span ng-bind-html="order.currency_symbol"></span> @{{ order.shipping_fee }}</td>
                </tr>
                <tr ng-show="order.paymode!='cos' && order.shipping_fee==0">
                    <td>{{ trans('messages.order.shipping') }} : {{ trans('messages.order.free_shipping') }} </td>
                </tr>
                <tr ng-show="order.incremental_fee!=0">
                    <td>{{ trans('messages.order.incremental_fee') }} : <span ng-bind-html="order.currency_symbol"></span> @{{ order.incremental_fee }}</td>
                </tr>
                <tr ng-show="order.service_fee!=NULL && order.service_fee!=0">
                    <td>{{ trans('messages.order.service_fee') }} : <span ng-bind-html="order.currency_symbol"></span> @{{ order.service_fee }}</td>
                </tr>
                <tr ng-show="order.coupon_amount!=NULL && order.coupon_amount!=0">
                    <td>{{ trans('messages.order.coupon') }} : - <span ng-bind-html="order.currency_symbol"></span> @{{ order.coupon_amount }}</td>
                </tr>
                <tr>
                    <td>{{ trans('messages.order.total') }} : <span ng-bind-html="order.currency_symbol"></span> @{{ order.total }}</td>
                </tr>

            </table>

        </div>

    </div>
    <div class="add-fancy-back order-action-popup" style="display:none">
        <div class="d-flex align-items-center flex-wrap cls_modal_height">
            <div class="col-lg-5 col-md-7 col-sm-8 back-white pos-top mar-auto flt-none nopad col-xs-11 currency-content ">
                <h2 class="fancy-head-popup"><span class="order_title text-capitalize">{{ trans('messages.order.cancel') }}</span> {{ trans('messages.order.this_order') }}</h2>
                <div class="popup col-lg-12 col-sm-12 col-md-12 col-xs-12 nopad my-2 py-2">
                    <div class="edit-setting">
                        <input type="hidden" name="order_id" id="order_id" value="">
                        <input type="hidden" name="order_action" id="order_action" value="">
                    </div>
                    <div class="edit-setting">
                        <label><span class="order_title text-capitalize">{{ trans('messages.order.cancel') }}</span> {{ trans('messages.order.reason') }}</label>
                        <br/>
                        <textarea class="text w-100" name="reason" id="reason_msg" rows="10"></textarea>
                    </div>
                </div>
                <div class="btns-area text-right over-hidden">
                    <button type="button" class="btn btn-gray cancel_buyer">{{ trans('messages.order.cancel') }}</button>
                    <button type="button" class="btn btn-blue-fancy btn-add" ng-click="order_action()">{{ trans('messages.order.save') }}</button>
                </div>
                <button class="ly-close" type="button" title="Close"><i class="ic-del-black"></i></button>
            </div>
        </div>
    </div>
</div>
</main>
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
<style type="text/css">
    .tb-type4 tbody td {
        border-left: 1px !important;
    }
    
    .tb-change tbody td {
        padding: 1px 1px 1px !important;
    }
    
    @media print {
        a[href]:after {
            content: none !important;
        }
        html {
            min-height: inherit;
        }
    }
</style>