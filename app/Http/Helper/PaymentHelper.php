<?php

/**
 * Payment Helper
 *
 * @package     Spiffy
 * @subpackage  Helper
 * @category    Helper
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Helper;

use App\Models\Cart;
use App\Models\CouponCode;
use App\Models\Currency;
use App\Models\Fees;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductPrice;
use App\Models\ProductShipping;
use App\Models\ShippingAddress;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Route;
use Session;

class PaymentHelper {

	/**
	 * Common Function for Price Calculation
	 *
	 * @param array products(product_id,option_id,qty)
	 * @param int $checkout   checkout page(shipping details,service fee)
	 * @return json   Calculation Result
	 */

	public function price_calculation($products_array = array(), $checkout = '') {
		$price = 0;
		$shipping = 0;
		$service = 0;
		$merchant_fee = 0;
		$tot_item = 0;
		$tot_shipping_charge = 0;
		$tot_incremental_fee = 0;

		$result['coupon_code'] = 0;
		$result['coupon_amount'] = 0;
		foreach ($products_array as $products) {

			$product_id = $products['product_id'];
			$option_id = $products['option_id'];
			$qty = $products['quantity'];

			$currency_symbol = @$products->product_details->currency_symbol;

			if ($option_id != "") {
				//price calculation based on option price
				$product_option = ProductOption::where('id', $option_id)->first();
				$original_price = $product_option->price;
				$price += $original_price * $qty;
				$currency_code = $product_option->currency_code;
			} else {
				//price calculation based on Price
				$product_price = ProductPrice::where('product_id', $product_id)->first();
				$original_price = $product_price->price;
				$price += $original_price * $qty;
				$currency_code = $product_price->currency_code;
			}

			if ($checkout == 'yes') {
				//check login user
				if (Auth::check()) {
					$user_id = Auth::user()->id;
					$shipping_address = ShippingAddress::where('user_id', $user_id)->first();

					if ($shipping_address != '') {
						$country = $shipping_address->country;
						$shipping_country = ProductShipping::where('product_id', $product_id)->where('ships_to', $country)->first();
						if ($shipping_country != '') {
							if ($shipping_country->shipping_type == 'Flat Rates') {
								$charge = $shipping_country->charge != NULL ? $shipping_country->charge : 0;

								$incremental_fee = $shipping_country->incremental_fee != NULL ? $shipping_country->incremental_fee : 0;

								$tot_shipping_charge += $charge;

								$tot_incremental_fee += ($incremental_fee * ($qty - 1));

								$shipping += $charge + ($incremental_fee * ($qty - 1));
							}
						}
					}
				}
			}
			$tot_item++;
		}
		$result['subtotal'] = number_format($price, 2, '.', '');
		$result['shipping'] = number_format($shipping, 2, '.', '');
		$result['shipping_charge'] = number_format($tot_shipping_charge, 2, '.', '');
		$result['incremental_fee'] = number_format($tot_incremental_fee, 2, '.', '');
		$result['currency_symbol'] = @$currency_symbol;

		$service_fee = Fees::where('name', 'service_fee')->first()->value;
		$merchant_fee = Fees::where('name', 'merchant_fee')->first()->value;
		if ($service_fee == 0 || $service_fee == "0") {
			$result['service'] = 0;
		} else {
			$service_fee_amount = ($service_fee / 100) * ($result['subtotal'] + $result['shipping']);
			$result['service'] = number_format(round($service_fee_amount), 2, '.', '');
		}

		if ($merchant_fee == 0 || $merchant_fee == "0") {
			$result['merchant_fee'] = 0;
		} else {
			$merchant_fee_amount = ($merchant_fee / 100) * ($result['subtotal'] + $result['shipping']);
			$result['merchant_fee'] = number_format(round($merchant_fee_amount), 2, '.', '');
		}
		if (Session::get('coupon_code')) {

			$coupon_code = Session::get('coupon_code');

			$coupon_details = CouponCode::where('coupon_code', $coupon_code)->first();

			if ($coupon_details) {

				$code = Session::get('currency');

				$result['coupon_amount'] = number_format($this->currency_convert($coupon_details->currency_code, $code, $coupon_details->amount), 2, '.', '');

			}

			$result['coupon_code'] = $coupon_code;
		}

		$result['tot_item'] = $tot_item;

		$result['total'] = number_format(($result['subtotal'] + $result['shipping'] + $result['service'] - $result['coupon_amount']), 2, '.', '');

		return json_encode($result);
	}
	/*
		   * Common Function for Price
		   * @param int option
		   * @param int $product_id
		   * @return json   price  Result
	*/
	public function price_details($option, $product_id) {
		if ($option != '') {
			$product_price = ProductOption::where('id', $option)->where('product_id', $product_id)->first();
			$quantity = @$product_price->total_quantity;
		} else {
			$product_price = ProductPrice::where('product_id', $product_id)->first();
			$quantity = Product::where('id', $product_id)->first()->total_quantity;
		}

		$qty_select = '<select id="quantity" class="select-boxes2">';

		if ($quantity != 0) {
			for ($i = 1; $i <= $quantity; $i++) {
				$qty_select = $qty_select . '<option value="' . $i . '">' . $i . '</option>';
			}
		} else {
			$qty_select = $qty_select . '<option value="1">1</option>';
		}

		$qty_select = $qty_select . '</select>';

		$result['price'] = @$product_price->price;
		$result['quantity'] = @$qty_select;
		$result['retail_price'] = @$product_price->retail_price != '0' ? @$product_price->retail_price : '';
		$result['discount'] = @$product_price->discount != '0.00' ? @$product_price->discount : '';
		return json_encode($result);
	}

	public function products_service_fee($price, $quantity, $shipping, $incremental) {
		$result['service'] = 0;
		$service_fee = Fees::where('name', 'service_fee')->first()->value;
		if ($service_fee == 0 || $service_fee == "0") {
			$result['service'] = 0;
		} else {
			$service_fee_amount = ($service_fee / 100) * (($price * $quantity) + $shipping + $incremental);
			$result['service'] = number_format($service_fee_amount, 2, '.', '');
		}
		return json_encode($result);
	}

	public function products_merchant_fee($price, $quantity, $shipping, $incremental) {
		$result['merchant_fee'] = 0;
		$merchant_fee = Fees::where('name', 'merchant_fee')->first()->value;
		if ($merchant_fee == 0 || $merchant_fee == "0") {
			$result['merchant_fee'] = 0;
		} else {
			$merchant_fee_amount = ($merchant_fee / 100) * (($price * $quantity) + $shipping + $incremental);
			$result['merchant_fee'] = number_format($merchant_fee_amount, 2, '.', '');
		}
		return json_encode($result);
	}

	/**
	 * Currency Convert
	 *
	 * @param int $from   Currency Code From
	 * @param int $to     Currency Code To
	 * @param int $price  Price Amount
	 * @return int Converted amount
	 */
	public function currency_convert($from = '', $to = '', $price) {
		if ($from == '') {
			if (Session::get('currency')) {
				$from = Session::get('currency');
			} else {
				$from = Currency::where('default_currency', 1)->first()->code;
			}

		}

		if ($to == '') {
			if (Session::get('currency')) {
				$to = Session::get('currency');
			} else {
				$to = Currency::where('default_currency', 1)->first()->code;
			}

		}

		$rate = Currency::whereCode($from)->first()->rate;

		$usd_amount = $price / $rate;

		$session_rate = Currency::whereCode($to)->first()->rate;

		return round($usd_amount * $session_rate);
	}

	// Calculation for current currency conversion of given price field
	public function currency_calc($value, $currency_code)
	{
		$default_currency = Currency::where('default_currency', 1)->first()->code;
		$rate = Currency::whereCode($currency_code)->first()->rate;

		if (request()->segment(1) == 'api') {
			$code = 'USD';
			if ($default_currency) {
				$code = $default_currency->code;
			}
		}
		else {
			$code = (Session::get('currency')) ? Session::get('currency') : $default_currency;
		}

		$usd_amount = $value / $rate;

		$session_rate = Currency::whereCode($code)->first()->rate;

		return round($usd_amount * $session_rate);
	}

	/**
	 * Currency Convert Original
	 *
	 * @param int $from   Currency Code From
	 * @param int $to     Currency Code To
	 * @param int $price  Price Amount
	 * @return int Converted amount
	 */
	public function currency_convert_original($from = '', $to = '', $price)
	{
		if ($from == '') {
			if (Session::get('currency')) {
				$from = Session::get('currency');
			} else {
				$from = Currency::where('default_currency', 1)->first()->code;
			}

		}

		if ($to == '') {
			if (Session::get('currency')) {
				$to = Session::get('currency');
			} else {
				$to = Currency::where('default_currency', 1)->first()->code;
			}

		}
		if ($from != $to) {
			$rate = Currency::whereCode($from)->first()->rate;
			$usd_amount = $price / $rate;
			$session_rate = Currency::whereCode($to)->first()->rate;
			return round(($usd_amount * $session_rate), 2);
		} else {
			return $price;
		}
	}

	public function check_cart_payment($cart_array = array(), $payment_method = "paypal") {
		$check_cart = Cart::with([
			'product_details' => function ($query) {
				$query->with([
					'products_prices_details' => function ($query) {
						$query->with('currency');
					},
					'products_shipping' => function ($query) {},
					'product_photos' => function ($query) {},
					'product_option' => function ($query) {},
				]);
			},
		])->whereIn('id', $cart_array)->orderBy('add_cart.id', 'desc')->get();
		$soldout = false;
		foreach ($check_cart as $value) {
			if ($payment_method == "cod") {
				if ($value->product_details->cash_on_delivery != "Yes") {
					$soldout = true;
				}
			}
			if ($payment_method == "cos") {
				if ($value->product_details->cash_on_store != "Yes") {
					$soldout = true;
				}
			}
		}
		return $soldout;
	}

	public function get_transaction_id_from_payment_id($payment_id = '') {
		$paypal_credentials = PaymentGateway::where('site', 'PayPal')->pluck('value', 'name');

		$gateway = \Omnipay\Omnipay::create('PayPal_Rest');

		// Initialise the gateway
		$gateway->initialize(array(
			'clientId' => $paypal_credentials['client'],
			'secret' => $paypal_credentials['secret'],
			'testMode' => ($paypal_credentials['mode'] == 'sandbox'), // Or false when you are ready for live transactions
		));

		$transaction_id = $payment_id ?: '';

		try
		{
			$purchase_response = $gateway->fetchPurchase(['transactionReference' => $payment_id])->send();
			$transaction_id = $purchase_response->getTransactionReference() ?: '';
		} catch (\Exception $e) {
			\Log::info($e->getMessage());
			$transaction_id = $payment_id ?: '';
		}

		return $transaction_id;
	}

	public function priceRange($minprice,$maxprice)
	{
		$data['default_min_price'] = 0;
        $data['default_max_price'] = $this->currency_convert('USD', Session::get('currency') ,1000);
        if(isset($minprice) && isset($maxprice) )
        {
            $data['min_value'] =$minprice;
            $data['max_value'] = $maxprice ? $maxprice : $this->currency_convert('USD', Session::get('currency') ,1000);
        } 
        else
        {
            $data['min_value'] = 0;
            $data['max_value'] = $this->currency_convert('USD', Session::get('currency') ,1000);
        }
        if($minprice == '' && $maxprice == '')
        {
            $data['min_value'] = $data['default_min_price'];
            $data['max_value'] = $data['default_max_price'];
        }elseif(Session::get('previous_currency')){
            $data['min_value'] = $this->currency_convert(Session::get('previous_currency'), Session::get('currency'), $data['min_value']); 
            $data['max_value'] = $this->currency_convert(Session::get('previous_currency'), Session::get('currency'), $data['max_value']); 
        } else {            
            $data['min_value'] = $this->currency_convert('', Session::get('currency'), $data['min_value']);
            $data['max_value'] = $this->currency_convert('', Session::get('currency'), $data['max_value']);
        } 
       return $data;
	}
}
