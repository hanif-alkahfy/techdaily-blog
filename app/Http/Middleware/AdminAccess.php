<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()?->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            if (!Auth::check()) {
                return redirect()->route('login');
            }

            return redirect()->route('blog.index')
                ->with('error', 'Unauthorized. This area is restricted to administrators only.');
        }

        return $next($request);
    }
}
