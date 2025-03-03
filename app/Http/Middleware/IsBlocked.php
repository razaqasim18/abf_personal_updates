<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if (Auth::guard($guard)->user()->is_blocked) {
            if (
                Auth::getDefaultDriver() == 'admin' &&
                Auth::guard('admin')->check()
            ) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                Auth::guard($guard)->logout();
                return redirect()
                    ->route('admin.adminLogin')
                    ->with('error', 'Your account is not blocked');
            } else {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()
                    ->route('login')
                    ->with('error', 'Your account is not blocked');
            }
        }

        return $next($request);
    }
}
