<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    // cek apakah sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

    // cek
        if (!Auth::check()) {
    return redirect()->route('login');
}

    return $next($request);
    }
}
