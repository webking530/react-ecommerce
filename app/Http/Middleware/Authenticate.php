<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Factory as Auth;
use Session;

class Authenticate
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

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
    public function handle($request, Closure $next, ...$guards)
    {
        $guard = @$guards[0] ?: 'web';

        $redirect_to = 'login';

        if($guard == 'admin') {
            $redirect_to = 'admin/login';
        }
        else if($guard == 'merchant') {
            $redirect_to = 'merchant/login';
        }
        $is_admin_path = ($request->segment(1) == "admin");

        // Redirect to payment for stripe confirmation in mobile payment
        if(isset($request->is_mobile) && session('get_token')) {
            return $next($request);
        }

        if (!$this->auth->guard($guard)->check() && ($guard != 'admin' || $is_admin_path)) {
            session(['url.intended' => url()->current()]);
            return redirect($redirect_to);
        }
        else if($guard == 'admin' && !$is_admin_path) {
            return redirect($request->segment(1));
        }
        if($this->auth->guard($guard)->user()->status == 'Inactive') {
            $this->auth->guard($guard)->logout();
            return redirect('user_disabled');
        }

        return $next($request);
    }
}
