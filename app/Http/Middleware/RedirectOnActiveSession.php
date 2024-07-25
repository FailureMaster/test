<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectOnActiveSession
{
    public function handle($request, Closure $next, $guard = null)
    {

        if (Session::get('loggedIn')) {
            return redirect('/do-it-yourself/orders/open');
        }

        return $next($request);

    }
}