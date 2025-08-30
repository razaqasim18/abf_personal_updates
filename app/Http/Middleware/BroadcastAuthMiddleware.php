<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BroadcastAuthMiddleware
{
    /**
     * Handle an incoming request for broadcasting auth.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log the incoming broadcast auth attempt with details
        Log::info('Broadcast auth attempt', [
            'web' => Auth::guard('web')->check(),
            'admin' => Auth::guard('admin')->check(),
            'user_id' => Auth::guard('admin')->check() ? Auth::guard('admin')->id() : Auth::guard('web')->id(),
            'request_user_agent' => $request->header('User-Agent'),
        ]);

        // Check if the user is authenticated via the 'web' guard
        if (Auth::guard('web')->check()) {
            Auth::setDefaultDriver('web');
            return $next($request);
        }

        // Check if the user is authenticated via the 'admin' guard
        if (Auth::guard('admin')->check()) {
            Auth::setDefaultDriver('admin');
            return $next($request);
        }

        // Log and return unauthorized response if neither guard is authenticated
        Log::warning("Broadcast auth failed: no valid guard authenticated");
        return response('Unauthorized.', 403);
    }
}
