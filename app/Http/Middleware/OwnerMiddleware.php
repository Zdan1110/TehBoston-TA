<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OwnerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->type_akun === 'owner') {
            return $next($request);
        }

        abort(403, 'Akses hanya untuk Owner.');
    }
}
