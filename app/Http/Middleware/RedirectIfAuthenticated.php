<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        $redirect_to = ($guard == 'admin') ? "admin/dashboard" : "/";

        $is_admin_path = $request->segment(1) == "admin";

        if (Auth::guard($guard)->check() && ($guard != 'admin' || $is_admin_path)) {
            return redirect($redirect_to);
        }
        else if($guard == 'admin' && !$is_admin_path) {
            return redirect($request->segment(1));
        }

        return $next($request);
    }
}
