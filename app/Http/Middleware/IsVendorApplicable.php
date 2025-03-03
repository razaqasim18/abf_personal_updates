<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VendorRequest;

class IsVendorApplicable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = VendorRequest::where('user_id', Auth::guard('web')->user()->id)->first();
        $user = Auth::guard('web')->user();
        if ($user->is_vendor_allowed == '0' && $user->userpoint->point < 40000) {
            return redirect()->route('dashboard');
        }
        if (isset($vendor->status)) {
            if (in_array($vendor->status, ['-1', '2', '3'])) {
                return redirect()->route('vendor.request.payment.load');
            }
            if (in_array($vendor->status, ['4'])) {
                return redirect()->route('dashboard');
            }
        }
        return $next($request);
    }
}
