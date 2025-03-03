<?php

namespace App\Http\Middleware;

use App\Models\VendorRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsVendorPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $vendor = VendorRequest::where('user_id', Auth::guard('web')->user()->id)->first();
        if ($vendor->status == '-1' || $vendor->status == '2' || $vendor->status == '3') {
            return $next($request);
        } else {
            return redirect()->route('dashboard');
        }
    }
}
