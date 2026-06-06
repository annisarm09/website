<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string... $roles): Response
    {
        if (!Auth::check()) {
            if (!$request->is('/login')) {
            return redirect()->route('login');
        }
    }

        $user = Auth::user();
    if ($user && !in_array($user->role, $roles)) {
        return redirect('/')->with('error', 'Akses ditolak');
    }
        return $next($request);
    }
}
