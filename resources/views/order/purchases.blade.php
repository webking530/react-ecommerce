@extends('settings_template')
@section('main')
<div class="col-lg-9 col-12 setting-rightbar  ed_contpro">
    <div class="cls_setting1">
        <h2 class="csv_tit" style="margin: 0px;"><b>{{ trans('messages.header.orders') }}</b></h2>
   

    @if(@$orders_count !=0)
    <div class="table">
        <table class="cls_mtable table-trips tableorderlist">
            <colgroup>

                <col style="width:100px;">
                <col style="width:80px;">
                <col style="width:100px;">
                <col style="width:100px;">

            </colgroup>
            <tbody>
                <tr class="table-head">

                    <th><span>ID</span></th>
                    <th><span>{{ trans('messages.order.date') }}</span></th>
                    <th><span>{{ trans('messages.order.price') }}</span></th>
                    <th><span>{{ trans('messages.order.paymode') }}</span></th>

                </tr>

                @foreach($orders as $order)
                <tr class="table-body">
                    <td><a href="{{ url('purchases')}}/{{ $order['id'] }}" class="after">#
                        {{ $order['id'] }}
                        </a>
                    </td>
                    <td>
                        {{ $order['order_date'] }}
                    </td>
                    <td>
                        {!! $order->currency_symbol !!}{{ $order['total'] }}
                    </td>
                    <td>
                        {{ $order['show_payment_mode'] }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="table-body no_products" style="display: none;text-align: center;padding: 10px;background: #f1f1f1;">
            No Products found
        </div>
    </div>
    @else
    <div class="no-data">
        <span class="icon"></span>
        <p>{{ trans('messages.order.no_order_msg') }}</p>
    </div>

    @endif
     </div>
</div>
</main>
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        // browser back button redirect for accomendation page 

        if (window.history && window.history.pushState) {

            $(window).on('popstate', function() {
                var hashLocation = location.hash;
                var hashSplit = hashLocation.split("#!/");
                var hashName = hashSplit[1];

                if (hashName !== '') {
                    var hash = window.location.hash;
                    if (hash === '') {
                        window.location = APP_URL + '/purchases';
                        return false;
                    }
                }
            });

            window.history.pushState('forward', null, '#');
        }

    });
</script>
@endpush @stop