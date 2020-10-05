@extends('settings_template')
@section('main')
<div class="col-lg-9 col-12 setting-rightbar cls_generator">
    <div class="cls_setting1 d-flex align-items-center justify-content-between col-lg-12">
        <h2 class="csv_tit" style="margin: 0px;"> {{ trans('messages.order.invoices') }} </h2>
        <ul class="new_product flt-right">
            <a href="#" onclick="printDiv('printableArea')" class="grey-btn btn-add btn">{{ trans('messages.order.print') }}</a>
        </ul>
    </div>

    <div class="table" id="printableArea">
        <table class="cls_mtable tb-type4" style="background-color: #fff;">
            <tbody>
                <tr class="table-head cls_h5">
                    <td>
                        <h5 style="font-weight: 900; ">{{ trans('messages.order.invoice_no') }} : </h5>#{{ $orders['id'] }}</td>
                    <td>
                        <h5 style="font-weight: 900; ">{{ trans('messages.order.date') }} : </h5>{{ $orders['order_date'] }}</td>
                </tr>
                <tr class="table-head cls_h5">
                    <td>
                        <h5 style="font-weight: 900; ">{{ trans('messages.order.billing_address') }} : </h5> {{ $billing_address['address_line'] }},
                         @if($billing_address['address_line2'] != NULL &&$billing_address['address_line2'] !=''){{ $billing_address['address_line2'] }},
                        @endif {{ $billing_address['city'] }},
                        {{ $billing_address['state'] }},
                         {{ $billing_address['country'] }}-{{ $billing_address['postal_code'] }}
                        
                    </td>
                    @if($orders['paymode']!='cos')
                    <td>
                        <h5 style="font-weight: 900; ">{{ trans('messages.order.shipping_address') }}:</h5>
                        <!-- {{ $merchant_store['store_name'] }},<br/> -->
                        {{ $user_address['address_line'] }},
                         @if($user_address['address_line2'] != NULL &&$user_address['address_line2'] !=''){{ $user_address['address_line2'] }},
                        @endif {{ $user_address['city'] }},
                         {{ $user_address['state'] }},
                         {{ $user_address['country'] }}-{{ $user_address['postal_code'] }}
                        
                    </td>
                    @endif
                </tr>
            </tbody>
        </table>
        <br/>
        <div class="invoice-tablelist tablelist">
            <table class="cls_mtable tb-type4 addressdetails">
                    <thead>
                    <tr class="table-head">
                        <th class="idname"><span>Id</span></th>
                        <th class="productoption"><span>{{ trans('messages.order.items') }}</span></th>
                        <th><span>{{ trans('messages.order.unit_price') }}</span></th>
                        <th><span>{{ trans('messages.order.quantity') }}</span></th>
                        <th><span>{{ trans('messages.order.price') }}</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders_details as $product_details=>$product_value)

                    <tr class="table-body productrange">
                        <td>
                            {{ $product_details+1 }}
                        </td>
                        <td class="">
                            <a href="{{ url('/things/'.$product_value['product_id']) }}" target="blank" class="text-truncate" style="width: 300px;"> {{$product_value['products']['title'] }}</a>
                        </td>
                        <td>
                            @if($product_value['option_id'] != null &&$product_value['product_option']['price'] !='0') {!! $product_value->currency_symbol !!} {{$product_value['product_option']['price'] }} @elseif(@$product_value['product_option_id'][0]['price'] !='0' && count(@$product_value['product_option_id'])) {!! $product_value->currency_symbol !!}{{$product_value['product_option_id'][0]['price'] }} @else {!! $product_value->currency_symbol !!}{{$product_value['products_prices_details']['price']}} @endif
                        </td>
                        <td>{{$product_value['quantity']}}</td>
                        <td>{!! $product_value->currency_symbol !!}{{$product_value['price'] * $product_value['quantity']}}</td>

                    </tr>

                    @endforeach
                    <tr><span ng-bind-html="order.currency_symbol"></span>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: 600;">{{ trans('messages.order.subtotal') }}:
                            <br/> @if($orders['paymode']!='cos') {{ trans('messages.order.shipping_fee') }} :
                            <br/> @if($orders['incremental_fee'] !=NULL) {{ trans('messages.order.incremental_fee') }} :
                            <br/> @endif @endif @if($orders['service_fee'] !=NULL) {{ trans('messages.order.service_fee') }} :
                            <br/> @endif @if($orders['coupon_amount'] !=NULL) {{ trans('messages.order.coupon') }} :
                            <br/> @endif {{ trans('messages.order.total') }} :
                            <br/>
                        </td>
                        <td>
                            {!! @$orders->currency_symbol !!} {{ $orders['subtotal'] }}
                            <br/> @if($orders['paymode']!='cos') {{ ($orders['shipping_fee'] > 0 ) ? @$orders->currency_symbol.' ' .$orders['shipping_fee']:'Free Shipping' }}
                            <br/> @if($orders['incremental_fee'] !=NULL) {!! @$orders->currency_symbol !!} {{ $orders['incremental_fee'] }}
                            <br/> @endif @endif @if($orders['service_fee'] !=NULL) {!! @$orders->currency_symbol !!} {{ $orders['service_fee'] }}
                            <br/> @endif @if($orders['coupon_amount'] !=NULL)
                            <span>-</span>{!! @$orders->currency_symbol !!} {{ $orders['coupon_amount'] }}
                            <br/> @endif {!! @$orders->currency_symbol !!} {{ $orders['total'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
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
    
    @media print {
        a[href]:after {
            content: none !important;
        }
    }
</style>