@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Order Details
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Orders</a></li>
        <li class="active">Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Order Details</h3>
            </div>



            <div class="price_calculation col-xs-12">
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Order id
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">
                  #{{ $orders_details[0]->id }}
                </div>
              </div>
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Order Placed
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">
                  {{ $orders_details[0]->order_date }}
                </div>
              </div>

              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Buyer Name
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">
                  {{ $orders_details[0]->buyer_name }}
                </div>
              </div>
              <!-- <div class="form-group col-xs-12">
                  <label class="col-sm-3 control-label">
                   Customer Paypal Email ID
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ @$orders_details[0]->payout_preferences->paypal_email }}
                   </div>
                </div> -->
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Payment Method
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">
                  {{ $orders_details[0]->show_payment_mode }}
                </div>
              </div>
              @if($orders_details[0]->paymode!='cos')
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Shipping Address
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">
                  <p>{{ $orders_details[0]->shipping_details->name }},</p>
                  <p>{{ $orders_details[0]->shipping_details->address_line }}  {{ $orders_details[0]->shipping_details->address_line2 }}</p>
                  <p>{{ $orders_details[0]->shipping_details->city }} - {{ $orders_details[0]->shipping_details->state }}</p>
                  <p>{{ $orders_details[0]->shipping_details->country }} - {{ $orders_details[0]->shipping_details->postal_code }}</p>
                  <p>{{ $orders_details[0]->shipping_details->address_nick }}({{ $orders_details[0]->shipping_details->phone_number }})</p>
                </div>
              </div>
              @endif
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Billing Address
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">
                  <p>{{ $orders_details[0]->billing_details->name }},</p>
                  <p>{{ $orders_details[0]->billing_details->address_line }}  {{ $orders_details[0]->billing_details->address_line2 }}</p>
                  <p>{{ $orders_details[0]->billing_details->city }} - {{ $orders_details[0]->billing_details->state }}</p>
                  <p>{{ $orders_details[0]->billing_details->country }} - {{ $orders_details[0]->billing_details->postal_code }}</p>
                  <p>{{ $orders_details[0]->billing_details->address_nick }}({{ $orders_details[0]->billing_details->phone_number }})</p>
                </div>
              </div>
            </div>

            <div class="products col-xs-12">
              @foreach($orders_details[0]->orders_details as $products)
              <div class="pro col-md-12 nopad">

                <div class="product_image col-md-2">
                <img src="{{ $products->products->image_name }}" />
                </div>
                <div class="product_details col-md-10">
                <div class="sold_by">
                <p><label>Sold by : </label>{{ $products->products->user_name }}</p>
                <p><label>Status : </label>
                {{ $products->status }}
                @if($products->status=="Returned")
               ( {{ @$products->return_status }} )
                @elseif($products->status=="Cancelled")
                by {{ @$products->cancelled_by }}
                @endif
                </p>
                  @if(@$orders_details[0]->orders_cancel->cancel_reason )
                  <p><label>Cancel reason : </label>
                  {{ $orders_details[0]->orders_cancel->cancel_reason }}
                  </p>
                  @endif

                </div>
                <p><label>Merchant Name : </label>
                {{ $products->products->user_name }}
                </p>
                <p><label>Store Name : </label>
                {{ $products->products->store_name }}
                </p>
                @if(@$products->products->payout_method == 'Paypal')
                <p><label>Merchant Paypal Email ID : </label>
                @else
                <p><label>Merchant Stripe Account Id: </label>
                @endif
                {{ $products->products->paypal_email }}
                </p>
                <p><label>Product Name : </label>
                {{ $products->products->title }}
                @if($products->product_option && $products->product_option != "null" )
                <em class="option_name"> ( {{ $products->product_option->option_name }} )</em>
                @endif
                </p>
                <p><label>Price : </label>{{ $orders_details[0]->currency->symbol }}{{ $products->price}}</p>
                <p><label>Quantity : </label>{{ $products->quantity }}</p>
                <p><label>Total : </label>{{ $orders_details[0]->currency->symbol }}{{ ($products->price * $products->quantity) }}</p>

                <p><label>Shipping : </label>
                @if($products->shipping == "0" )
                Free Shipping
                @else
                {{ $orders_details[0]->currency->symbol }}{{ $products->shipping }}
                @endif
                </p>
                @if($products->incremental != "0" )
                <p><label>incremental : </label>
                {{ $orders_details[0]->currency->symbol }}{{ $products->incremental }}
                </p>
                @endif
                <p><label>Return Policy : </label>
                @if($products->return_policy == "0" )
                No return.
                @else
                {{ $products->return_policy }} days returns.
                @endif
                </p>
                </div>
              </div>
              @endforeach
            </div>
            <div class="price_totals col-xs-12">
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Subtotal
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">

                  {{ $orders_details[0]->currency->symbol }}{{ number_format($orders_details[0]->subtotal,2) }}

                </div>
              </div>
              @if($orders_details[0]->paymode!='cos')
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Shipping
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">


                 @if($orders_details[0]->shipping_fee < 1 )
                  Free Shipping
                  @else
                  {{ $orders_details[0]->currency->symbol }}{{ $orders_details[0]->shipping_fee }}
                  @endif


                </div>
              </div>
              @if(@$orders_details[0]->incremental_fee!="0")
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Incremental Fee
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">


                  {{ $orders_details[0]->currency->symbol }}{{ number_format($orders_details[0]->incremental_fee,2) }}

                </div>
              </div>
              @endif
              @endif
              @if(@$orders_details[0]->service_fee!="0")
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Service Fee
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">


                  {{ $orders_details[0]->currency->symbol }}{{ number_format($orders_details[0]->service_fee,2) }}

                </div>
              </div>
              @endif

              @if(@$orders_details[0]->merchant_fee>0)
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Merchant Fee
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">
                  {{ $orders_details[0]->currency->symbol }}{{ number_format($orders_details[0]->merchant_fee,2) }}
                </div>
              </div>
              @endif

              @if(@$orders_details[0]->coupon_amount>0)
              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Coupon Amount
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">
                  - {{ $orders_details[0]->currency->symbol }}{{ number_format($orders_details[0]->coupon_amount,2) }}
                </div>
              </div>
              @endif


              <div class="form-group col-xs-12">
                <label class="col-sm-3 control-label">
                  Total
                </label>
                <div class="col-sm-6 col-sm-offset-1 form-control-static">



                  {{ $orders_details[0]->currency->symbol }}{{ number_format($orders_details[0]->total,2) }}

                </div>
              </div>
            </div>

          <div class="payout_section col-xs-12">
            @if((strtolower($orders_details[0]->paymode) =='paypal' || strtolower($orders_details[0]->paymode) =='credit card') && count($payouts))
            <div class="merchant_payout col-xs-12">
            @foreach($payouts as $merchant_payout_detail)
            @if($merchant_payout_detail->total_amount >0 || $merchant_payout_detail->total_applied_amount>0)
            {{ Form::open(array('url' => 'payments/payout')) }}
             <h4><b>Payouts to Merchants</b></h4>
            <p>{{ $merchant_payout_detail->users->full_name }}</p>
            @if(count($merchant_payout_detail->payout_preferences )==0)
            <span>Say merchant to set payout preferences  <a href="{{ url('admin/need_payout_info/'.$merchant_payout_detail->order_id.'/'.$merchant_payout_detail->user_id.'/merchant') }}">Send Email to Merchant</a></span>
            @else
                @if($merchant_payout_detail->total_applied_amount>0)
                <p>Applied Owe Amount : {{ $merchant_payout_detail->currency->symbol }}{{ number_format((new \App\Http\Helper\PaymentHelper)->currency_convert($merchant_payout_detail->currency_code, '', $merchant_payout_detail->total_applied_amount), 2,'.', '') }}</p>
                @endif
              @if($merchant_payout_detail->status=="Future")
              <input type="hidden" name="order_id" value="{{ $merchant_payout_detail->order_id }}">
              <input type="hidden" name="merchant_id" value="{{ $merchant_payout_detail->user_id }}">

              <input type="hidden" name="amount" value="{{ number_format($merchant_payout_detail->total_amount, 2,'.', '') }}">

              <input type="hidden" name="payout_email_id" value="{{ $merchant_payout_detail->payout_preferences[0]->paypal_email }}">

              <p>
              @if($merchant_payout_detail->status!='Completed')
                  <button class="btn btn-primary" type="submit">Payout - {{ $merchant_payout_detail->currency->symbol }}{{ number_format((new \App\Http\Helper\PaymentHelper)->currency_convert($merchant_payout_detail->currency_code, '', $merchant_payout_detail->total_amount), 2,'.', '') }}</button>
              @endif
              @else
                  <label>Payout Amount Sent Successfully - {{ $merchant_payout_detail->currency->symbol }} {{ number_format((new \App\Http\Helper\PaymentHelper)->currency_convert($merchant_payout_detail->currency_code, '', $merchant_payout_detail->total_amount), 2,'.', '') }}</label>
              @endif
            @endif
            </p>
            <hr>
            {{ Form::close() }}
            @endif
            @endforeach
            </div>
            @endif

            @if(count($refunds))
            <div class="buyer_refund col-xs-12">

            @foreach($refunds as $buyer_refund_detail)
            @if($buyer_refund_detail->total_amount != '0')
            {{ Form::open(array('url' => 'payments/refund')) }}
            <h4><b>Refunds to Buyers</b></h4>
            <p>{{ $buyer_refund_detail->users->full_name }} :
             @if($buyer_refund_detail->status=="Future")
              <input type="hidden" name="order_id" value="{{ $buyer_refund_detail->order_id }}">
              <input type="hidden" name="merchant_id" value="{{ $buyer_refund_detail->user_id }}">

              <input type="hidden" name="amount" value="{{ number_format($buyer_refund_detail->total_amount, 2,'.', '') }}">

               <button class="btn btn-primary" type="submit">Refund - {{ $buyer_refund_detail->currency->symbol }}{{ number_format((new \App\Http\Helper\PaymentHelper)->currency_convert($buyer_refund_detail->currency_code, '', $buyer_refund_detail->total_amount), 2,'.', '') }}</button>

              @else

                <label>Refund Amount Sent Successfully - {{ $buyer_refund_detail->currency->symbol }} {{ number_format((new \App\Http\Helper\PaymentHelper)->currency_convert($buyer_refund_detail->currency_code, '', $buyer_refund_detail->total_amount), 2,'.', '') }}</label>

              @endif
            </p>
            {{ Form::close() }}
            @endif
            @endforeach
            </div>
            @endif
            </div>
              <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
<style>
.sold_by
{
  float:right;
}
.currency_span
{
  padding: 0 10px;
}
.price_totals
{
    background: #fff;
}
.nopad
{
  padding: 0 !important;
}
.products
{
    background: #fff;
    max-height: 350px;
    overflow-y: scroll;
}
.products .product_image img
{
    height: 100px;
    width: 100px;
    object-fit: contain;
}
.product_details p,.price_calculation p
{
  margin: 0;
}
.pro
{
    background: #fff;
    border-radius: 3px;
    margin: 10px 0px;
    box-shadow: 0 0 5px #888888;
}
.control-label {
    padding-top: 7px;
    margin-bottom: 0;
    text-align: right;
}
.form-group {
    margin-right: -15px;
    margin-left: -15px;
        margin-bottom: 15px;
}
.form-control-static {
    min-height: 34px;
    padding-top: 7px;
    padding-bottom: 7px;
    margin-bottom: 0;
}
.price_calculation
{
  background-color: #fff;
}
.payout_section
{
  background-color: #fff;
}
</style>
@stop
