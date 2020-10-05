@extends('template')
@section('main')
<main id="site-content" role="main" style="padding-bottom: 0px;">
    <div class="container">
        <div class="container-mini cls_cartall cart_pro" ng-controller="cart_product" style="display: none">
            <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">{{ trans('messages.cart.cart') }}</li>
                    </ol>
                </div>
            </div>
            <div class="empty-cart wrapper p-4 mb-5" style="display: none;">
                <p><b>{{ trans('messages.cart.cart_empty') }}</b></p>
                <span> {{ trans('messages.cart.cart_empty_desc') }}</span>
                <br>
                <a href="{{url('/')}}" class="btn btns-blue-embo">{{ trans('messages.cart.shop_now') }}</a>
            </div>
            <div class="loading products_loading" id="products_loading" style="display:none;margin: 50px"></div>
            <div class="row nopad cart " style="display: none;" ng-cloak>
                <div class="col-lg-8 col-md-8 col-sm-8 col-12 cart-item wrapper nopad">
                    <h3 class="stit">@{{total_cart}} {{ trans('messages.cart.item_cart') }}</h3>
                    <ul class="back-white">
                        <li class="cls_cartlist" ng-repeat="cart in all_cart">
                            <!-- view product image -->
                            <div class="loading products_loading" id="products_loading_check" style="display:none"></div>
                            <div class="row align-items-center">
                                <a class="col-lg-2 col-md-3 col-12 cart_img nopad text-center cart_mob">
                                    <img ng-src="@{{cart.product_details.image_name}}" class="item-img">
                                </a>
                                <!--end view product image -->
                                <div class="col-lg-10 col-sm-9 col-md-9 col-12 cart_pro_txt">
                                    <!-- view product title -->
                                    <div class="nopad row mt-3 mt-lg-0">
                                        <div class="col-lg-8 col-md-8 col-sm-8 nopad col-8">
                                            <a href="{{ url('/things/')}}/@{{cart.product_details.id}}" class="cartag text-truncate">
                                                @{{cart['product_details']['title']}}
                                            </a>
                                            <div class="nopad catprag">
                                                <span>{{ trans('messages.cart.sold_by') }}</span>
                                                <a href="{{ url('profile')}}/@{{cart.product_details.product_user}}">
                                                    @{{cart.product_details.user_name}}
                                                </a>
                                            </div>
                                            <span class="unavailable" ng-if="cart.is_available!= ''">@{{cart.is_available}}</span>
                                            <!-- view product option -->
                                            <input type="hidden" name="product_id" id="product_id_@{{cart.id}}" value="@{{cart['product_id']}}">
                                            <span ng-if="cart.product_details.product_option.length > 0">
                                                <select  class="cus_form_control cart_option cart_option_@{{cart.id}}" id="@{{cart.id}}">
                                                    <option value="@{{option.id}}" ng-selected="(option.id == cart.option_id )? 'selected': ''" ng-repeat = "option in cart.product_details.product_option" >@{{option.option_name}} ( @{{option.price}})
                                                    </option>
                                                </select>
                                            </span>
                                            <!-- end view product option -->
                                            <div class="prgtag">
                                                <a href="javascript:;" id="@{{cart.id}}" ng-click="remove_cart(cart.id)">{{ trans('messages.cart.remove') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
                                            <p id="product_price_@{{$cart.id}}" class="cls_price"><span ng-bind-html="cart.product_details.currency_symbol"></span> @{{ cart.price.toFixed(2)}}  <span class="sales cls_retailprice" ng-if='cart["retail_price"]'>
                                                <em id= "product_retail_price_@{{cart.id}}"><span ng-bind-html="cart.product_details.currency_symbol"></span> @{{cart.retail_price.toFixed(2)}}
                                                </em>
                                            </span></p>
                                            
                                            <div class="">
                                                <select class="cus_form_control cart_qty cart_qty_@{{cart.id}}" style="width: auto;" id="@{{cart['id']}}">
                                                </select>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <!-- end view product title -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 p-0 nopad payment_options">
                                        @if(@$cod_status=='Yes' || @$cos_status=='Yes')
                                        <span class="check"><i class="fa fa-check" aria-hidden="true"></i>PayPal</span>
                                        <span class="check"><i class="fa fa-check" aria-hidden="true"></i>CC</span> @endif @if(@$cod_status=='Yes')
                                        <span class="check" ng-if="cart.product_details.cash_on_delivery=='Yes'"><i class="fa fa-check" aria-hidden="true"></i>COD</span> @endif @if(@$cos_status=='Yes')
                                        <span class="check" ng-if="cart.product_details.cash_on_store=='Yes'"><i class="fa fa-check" aria-hidden="true"></i>CAS</span> @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-12 ">
                    <div class="cls_paypal pb-4">
                        <h3 class="stit">{{ trans('messages.cart.payment_method') }}</h3>
                        <div class="glob-back">
                            <form action="{{ url('checkout') }}" method="post" id="checkout_go">
                                {{ csrf_field() }}
                                <ul class="payment-method">
                                    <li class="select-payment selected">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="payment_method_paypal" name="payment_type" class="custom-control-input" value="paypal" checked>
                                            <label class="custom-control-label" for="payment_method_paypal">{{ trans('messages.cart.paypal') }}</label>
                                        </div>
                                    </li>
                                </ul>
                                <ul class="payment-reciept after">
                                    <li class="subtotal d-flex justify-content-between align-items-center flex-wrap">
                                        <label>{{ trans('messages.cart.subtotal') }}</label>
                                        <span class="price">
                                            <span ng-bind-html="currency_symbol"></span> @{{ subtotal }}
                                        </span>
                                    </li>
                                </ul>
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                    <button class="btns-green-embo btn prced-btn" type="submit"> {{ trans('messages.cart.proceed_checkout') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- <div class="currency-cart after">
                        <a href="#" class="change_currency" style="">Select your currency</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    @if($recently_viewed_things->count() !=0)
    <div class="col-lg-12 col-sm-12 col-md-12 col-12 mt-3 cls_cartall" id='recently_viewed_things' style="display: none">
        <div class="popular-head">
            <h3 class="flt-left">{{ trans('messages.cart.recently_viewed_items') }}</h3>
        </div>
        <div class="owl-carousel cate1 cls_owlslider">
            @foreach($recently_viewed_things as $recently_key => $recently_things)
            <div class="hover-img-effect slide-img" data-id='{{$recently_key}}' id="slide-img{{$recently_key}}">
                @foreach($recently_things->products->product_photos as $photos_key => $product_photos) @if($photos_key == 0)
                <div class="post_img">
                    <a href="{{url('things')}}/{{@$recently_things->products->id}}"><img src="{{ @$product_photos->images_name }}" /> </a>
                </div>
                <div class="fig-hover d-flex justify-content-between align-items-center fig-hover{{$recently_key}}">
                    <div class="text-truncate">
                        <span class="price">{!! $recently_things->products->currency_symbol !!} {{ @$recently_things->products->price }}</span>
                        <a href="{{url('things')}}/{{@$recently_things->products->id}}" class="title ">
                        {{ @$recently_things->products->title}}</a>
                        
                    </div>
                    
                </div>
                @if(!Auth::id())
                <button class="btn-cart nopopup add_cart" data-id='{{@$recently_things->products->id}}'>Add to Cart</button>
                @else
                <span ng-if='product.user_id!={{@Auth::id()}}'>
                    <button class="btn-cart nopopup add_cart" data-id='{{@$recently_things->products->id}}'>Add to Cart</button>
                </span>
                @endif
                @endif @endforeach
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <a href="javascript:void(0)" id="scroll-to-top" class="scroll-top">
        <span>
            {{ trans('messages.home.jump_top') }}
        </span>
    </a>
</main>
@endsection