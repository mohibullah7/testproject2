<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (!auth()->check()) {
        return redirect()->route('login.ui')
            ->with('error', 'Please login first');
    }

    return $next($request);
    }
}
