<?php

namespace App\Http\Middleware\Customer;

use Closure;
use Illuminate\Support\Facades\Auth;

class AllowIfAuthenticated {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(Auth::check()) {
            return $next($request);
        }
        return redirect('/');
    }
}
