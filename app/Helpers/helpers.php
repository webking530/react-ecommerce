<?php

/**
 * Set Flash Message function
 *
 * @param  string $class     Type of the class ['danger','success','warning']
 * @param  string $message   message to be displayed
 */
if (!function_exists('flashMessage')) {

	function flashMessage($class, $message)
	{
		Session::flash('alert-class', 'alert-'.$class);
        Session::flash('message', $message);
	}
}

/**
 * Currency Convert
 *
 * @param int $from   Currency Code From
 * @param int $to     Currency Code To
 * @param int $price  Price Amount
 * @return int Converted amount
 */
if (!function_exists('currency_convert')) {

	function currency_convert($from = '', $to = '', $price)
	{
		if(session('currency')) {
			$currency_code = session('currency');
		}
		else {
			$currency_code = Currency::where('default_currency', 1)->first()->code;
		}
		if($from == '') {
			$from = $currency_code;
		}
		if($to == '') {
			$to = $currency_code;
		}

		if($from == $to) {
			return ceil($price);
		}

		$rate = Currency::whereCode($from)->first()->rate;
		$usd_amount = $price / $rate;
		$session_rate = Currency::whereCode($to)->first()->rate;

		return ceil($usd_amount * $session_rate);
	}
}

/**
 * Checks if a value exists in an array in a case-insensitive manner
 *
 * @param string $key The searched value
 * 
 * @return if key found, return particular value of key.
 */
if (!function_exists('site_settings')) {
	
	function site_settings($key) {
		$site_settings = resolve('site_settings');
		$site_setting = $site_settings->where('name',$key)->first();

		return optional($site_setting)->value ?? '';
	}
}

/**
 * Checks if a value exists in an array in a case-insensitive manner
 *
 * @param string $key The searched value
 * 
 * @return if key found, return particular value of key.
 */
if (!function_exists('api_credentials')) {
	
	function api_credentials($key, $site) {
		$api_credentials = resolve('api_credentials');
		$credentials = $api_credentials->where('name',$key)->where('site',$site)->first();

		return optional($credentials)->value ?? '';
	}
}

/**
 * Checks if a value exists in an array in a case-insensitive manner
 *
 * @param string $key The searched value
 * 
 * @return if key found, return particular value of key.
 */
if (!function_exists('join_us')) {
	
	function join_us($key) {
		$join_us = resolve('join_us');
		$join = $join_us->where('name',$key)->first();

		return optional($join)->value ?? '';
	}
}

/**
 * File Get Content by using CURL
 *
 * @param  string $url  Url
 * @return string $data Response of URL
 */
if (!function_exists('file_get_contents_curl')) {

	function file_get_contents_curl($url)
	{
	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

	    $data = curl_exec($ch);
	    curl_close($ch);

	    return $data;
	}
}

/**
 * Process CURL With POST
 *
 * @param  String $url  Url
 * @param  Array $params  Url Parameters
 * @return string $data Response of URL
 */
if (!function_exists('curlPost')) {

	function curlPost($url,$params)
	{
		$curlObj = curl_init();

		curl_setopt($curlObj,CURLOPT_URL,$url);
		curl_setopt($curlObj,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curlObj,CURLOPT_HEADER, false); 
		curl_setopt($curlObj,CURLOPT_POST, count($params));
		curl_setopt($curlObj,CURLOPT_POSTFIELDS, http_build_query($params));    
		curl_setopt($curlObj, CURLOPT_HTTPHEADER, [
	        'Accept: application/json',
	        'User-Agent: curl',
	    ]);
		$output = curl_exec($curlObj);

		curl_close($curlObj);
		return json_decode($output,true);
	}
}

/**
 * Check if a string is a valid timezone
 *
 * @param string $timezone
 * @return bool
 */
if (!function_exists('isValidTimezone')) {
    function isValidTimezone($timezone)
    {
        return in_array($timezone, timezone_identifiers_list());
    }
}

/**
 * Convert Given Float To Nearest Half Integer
 * @return Int
 */
if (!function_exists('roundHalfInteger')) {
	function roundHalfInteger($value)
	{
		return floor($value * 2) / 2;
	}
}