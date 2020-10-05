<?php

namespace App\Http\Middleware;

use Auth;

class MerchantAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        if (!Auth::check()) {
            return redirect('merchant/login');
        }
        if(Auth::user()->type != 'merchant') {
            return redirect('merchant/signup');
        }
        if(Auth::user()->status == 'Inactive') {
            return redirect('user_disabled');
        }

        return $next($request);
    }
}