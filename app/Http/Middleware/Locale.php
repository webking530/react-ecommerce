<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use App;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::get('language'))
        {
            $get_language=DB::table('language')->where('status','Active')->where('value',Session::get('language'));
            if($get_language->count())
            {
                App::setLocale(Session::get('language'));
            }
            else
            {
                $this->get_default_language();
            }
        }
        else
        {
            $this->get_default_language();
        }

        if(Session::get('currency'))
        {
            $get_currency=DB::table('currency')->where('status','Active')->where('code',Session::get('currency'));
            if($get_currency->count())
            {
                Session::put('currency', $get_currency->first()->code);
                Session::put('symbol', $get_currency->first()->symbol);
            }
            else
            {
                $this->get_default_currency();
            }
        }
        else
        {
            $this->get_default_currency();
        }
         
        $response = $next($request);
        return $response;
    }

    public function get_default_language()
    {
        $default_language=DB::table('language')->where('default_language',1)->first();
        $default_language_value=$default_language->value;
        $default_language_name=$default_language->name;
        Session::forget('language');
        Session::forget('language_name');
        Session::put('language', $default_language_value);
        Session::put('language_name', $default_language_name);
        App::setLocale($default_language_value);
    }

    public function get_default_currency()
    {
        $default_currency=DB::table('currency')->where('default_currency',1)->first();
        $default_currency_value=$default_currency->code;
        $symbol = $default_currency->symbol;
        Session::forget('currency');
        Session::forget('symbol');
        Session::put('currency', $default_currency_value);
        Session::put('symbol', $symbol);
    }
}
