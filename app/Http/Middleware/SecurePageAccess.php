<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SecurePageAccess
{
    public function handle($request, Closure $next)
    {
        if (Session::get('loggedIn')) {
            return $next($request);
        }

        return redirect('/do-it-yourself/login');
    }
}