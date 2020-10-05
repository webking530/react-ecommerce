@extends('emails.template')

@section('emails.main')
<tr>
    <td valign="top">
<table width="640" border="0" cellpadding="0" cellspacing="0" style="font-size:14px;background:#fff;padding: 0 10px;font-family:'Verdana',Helvetica Neue,Helvetica,Arial,sans-serif;">
<tbody>
    <tr>
        <td style="padding-bottom:12px">Hi {{ $first_name }},</td>
    </tr>
    <tr>
        <td style="padding-bottom:12px">{{ $message_content }}</td>
    </tr>
    <tr><td height="20" colspan="3" style="height:20px"></td></tr>
    <tr>
        <td style="padding-bottom:12px">
            <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:14px;margin:0 auto;width:640px;font-family:'Verdana',Helvetica Neue,Helvetica,Arial,sans-serif;">
            <tbody>
            <tr>
                <td><b>Order ID</b> : #{{ $orders['id'] }}</td>
                <td><b>Order Placed</b> : {{ $orders['order_date'] }}</td>
            </tr>
            </tbody>
            </table>
        </td>
    </tr>
    <tr><td height="20" colspan="3" style="height:20px"></td></tr>
    <tr>
        <td style="padding-bottom:12px">
            <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:14px;margin:0 auto;width:640px;font-family:'Verdana',Helvetica Neue,Helvetica,Arial,sans-serif;">
            <tbody>
            <tr>
                @if($orders['paymode']!="cos")
                <td><b>Shipping Details</b>
                    <br>{{ $orders['shipping_details']['name'] }},
                    <br>{{ $orders['shipping_details']['address_line'] }}, {{ $orders['shipping_details']['address_line2'] }},
                    <br>{{ $orders['shipping_details']['city'] }}, {{ $orders['shipping_details']['state'] }},
                    <br>{{ $orders['shipping_details']['country'] }}, {{ $orders['shipping_details']['postal_code'] }},
                    <br>{{ $orders['shipping_details']['address_nick'] }} - ({{ $orders['shipping_details']['phone_number'] }}).
                </td>
                @endif
                <td><b>Billing Details</b>
                    <br>{{ $orders['billing_details']['name'] }},
                    <br>{{ $orders['billing_details']['address_line'] }}, {{ $orders['billing_details']['address_line2'] }},
                    <br>{{ $orders['billing_details']['city'] }}, {{ $orders['billing_details']['state'] }},
                    <br>{{ $orders['billing_details']['country'] }}, {{ $orders['billing_details']['postal_code'] }},
                    <br>{{ $orders['billing_details']['address_nick'] }} - ({{ $orders['billing_details']['phone_number'] }}).
                </td>
            </tr>
            </tbody>
            </table>
        </td>
    </tr>
    
    <tr><td height="20" colspan="3" style="height:20px"></td></tr>
    <tr><td colspan="3" ><b>Items</b></td></tr>
    <tr><td height="20" colspan="3" style="height:20px"></td></tr>
    <tr>
        <td style="padding-bottom:12px">
            <table frame="1" width="640"  align="center" cellpadding="2" cellspacing="2" style="border-collapse: collapse; border:1px solid #ccc;font-size:14px;margin:0 auto;width:640px;font-family:'Verdana',Helvetica Neue,Helvetica,Arial,sans-serif;">
            <thead style="background-color: #ccc">
                <tr>
                
                <th>Image</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orders['orders_details'] as $order_details)
            <tr >
               
               <td align="center" style="border: 1px solid #ccc"><img width="50px" height="50px" src="{{ $order_details['products']['image_name'] }}"></td>
                <td  style="border: 1px solid #ccc">{{ $order_details['products']['title'] }}{{ ($order_details['product_option']) ? "(".$order_details['product_option']['option_name'].")" : '' }}</td>
                
                <td align="center" style="border: 1px solid #ccc">{{ $order_details['quantity'] }}</td>
                <td align="right" style="border: 1px solid #ccc">{{ number_format($order_details['price'],2) }} {{ $orders['currency_code'] }} </td>
                <td align="right" style="border: 1px solid #ccc">{{ number_format(($order_details['price']*$order_details['quantity']),2) }} {{ $orders['currency_code'] }} </td>
                <td align="right" style="border: 1px solid #ccc">{{ $order_details['status'] }}</td>
            </tr>
            @endforeach
            </tbody>
            </table>
        </td>
    </tr>
    <tr><td height="20" colspan="3" style="height:20px"></td></tr>
    <tr><td colspan="3" ><b>Totals</b></td></tr>
    <tr><td height="20" colspan="3" style="height:20px"></td></tr>
    <tr>
        <td style="padding-bottom:12px">
            <table width="640"  align="center" cellpadding="2" cellspacing="2" style="border-collapse: collapse; border:1px solid #ccc;font-size:14px;margin:0 auto;width:640px;font-family:'Verdana',Helvetica Neue,Helvetica,Arial,sans-serif;">
            <tbody>
            <tr style="border-bottom: 1px dashed #ccc;height:30px">
                <td>Payment Mode</td>
                <td style="padding-right: 20px" align="right">{{ @$orders['show_payment_mode'] }} </td>
            </tr>
            <tr style="border-bottom: 1px dashed #ccc;height:30px">
                <td>Subtotal</td>
                <td style="padding-right: 20px" align="right">{{ number_format($orders['subtotal'],2) }} {{ $orders['currency_code'] }} </td>
            </tr>
            @if($orders['paymode']!="cos")
            <tr style="border-bottom: 1px dashed #ccc;height:30px">
                <td>Shipping fee</td>
                <td style="padding-right: 20px"  align="right">
                @if($orders['shipping_fee']!="0" && $orders['shipping_fee']!=null)
                {{ number_format($orders['shipping_fee'],2) }} {{ $orders['currency_code'] }} 
                @else
                Free Shipping
                @endif
                </td>
            </tr>
            @endif
            @if($orders['incremental_fee']!="0" && $orders['incremental_fee']!=null)
            <tr style="border-bottom: 1px dashed #ccc;height:30px">
                <td>Incremental fee</td>
                <td style="padding-right: 20px" align="right">{{ number_format($orders['incremental_fee'],2) }} {{ $orders['currency_code'] }} </td>
            </tr>
            @endif
            @if($orders['service_fee']!="0" && $orders['service_fee']!=null)
            <tr style="border-bottom: 1px dashed #ccc;height:30px">
                <td>Service fee</td>
                <td style="padding-right: 20px" align="right">{{ number_format($orders['service_fee'],2) }} {{ $orders['currency_code'] }} </td>
            </tr>
            @endif
            @if($orders['coupon_amount']!="0" && $orders['coupon_amount']!=null)
            <tr style="border-bottom: 1px dashed #ccc;height:30px">
                <td>Coupon</td>
                <td style="padding-right: 20px" align="right">-{{ number_format($orders['coupon_amount'],2) }} {{ $orders['currency_code'] }} </td>
            </tr>
            @endif
            <tr style="height:30px">
                <td><b>Total</b></td>
                <td style="padding-right: 20px" align="right"><b> {{ number_format($orders['total'],2) }} {{ $orders['currency_code'] }}</b></td>
            </tr>
            </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width:100%;line-height:45px;text-align:center;font-size:16px;background:#2184dc;border-radius:3px;font-weight:500">
        <a target="_blank" href="{{ $url.('purchases') }}/{{ $orders['id'] }}" style="display:block;text-decoration:none;color:#fff">View Order Details</a></td>
    </tr>
</tbody>
</table>
    </td>
</tr>
@stop