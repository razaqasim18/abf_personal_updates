<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use App\Http\Controllers\Admin\VendorOrder;
use App\Models\User;
use App\Models\Banner;
use App\Models\Order;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Vendor;
use App\Models\VendorOrder as ModelsVendorOrder;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function dashboard()
    {
        $order = [];


        $date = date("Y-m-d");
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        $dashboardbanner = Banner::where('type', 0)->get();
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        $vendor = Vendor::where("user_id", Auth::guard('web')->user()->id)->first();

        $monthearning = ModelsVendorOrder::where('seller_id', Auth::guard('web')->user()->id)
            ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
            ->where(function ($query) {
                $query->where('status', '3')
                    ->orWhere('status', '6');
            })
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get()->sum('total_bill');



        $total = ModelsVendorOrder::where('seller_id', Auth::guard('web')->user()->id)
            ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
            ->where(function ($query) {
                $query->where('status', '3')
                    ->orWhere('status', '6');
            })
            ->get()->sum('total_bill');


        $plateForm = ModelsVendorOrder::where('seller_id', Auth::guard('web')->user()->id)
            ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
            ->where(function ($query) {
                $query->where('status', '3')
                    ->orWhere('status', '6');
            })
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get()->sum('commission_amount');


        $earning = [
            "month" => $monthearning,
            "total" => $total,
            "plateForm" => $plateForm,
            "outstanding" =>   $vendor->outstanding_amount,
        ];

        $order = [
            'pending' => ModelsVendorOrder::select(DB::raw('COUNT(id) as count'))
                ->where('status', '0')
                ->where('seller_id', Auth::guard('web')->user()->id)
                ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
                ->first(),
            'inprocess' => ModelsVendorOrder::select(DB::raw('COUNT(id) as count'))
                ->where('status', '1')
                ->where('seller_id', Auth::guard('web')->user()->id)
                ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
                ->first(),
            'approved' => ModelsVendorOrder::select(DB::raw('COUNT(id) as count'))
                ->where('status', '2')
                ->where('seller_id', Auth::guard('web')->user()->id)
                ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
                ->first(),
            'delivered' => ModelsVendorOrder::select(DB::raw('COUNT(id) as count'))
                ->where('status', '3')
                ->where('seller_id', Auth::guard('web')->user()->id)
                ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
                ->first(),
            'cancelled' => ModelsVendorOrder::select(DB::raw('COUNT(id) as count'))
                ->where('status', '-1')
                ->where('seller_id', Auth::guard('web')->user()->id)
                ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
                ->first(),
            'total' => ModelsVendorOrder::select(DB::raw('COUNT(id) as count'))
                ->where('seller_id', Auth::guard('web')->user()->id)
                ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
                ->first(),
        ];




        return view('user.vendor.home', compact('dashboardbanner', 'earning', 'order'));
    }

    public function revenue()
    {
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;
        $result = ModelsVendorOrder::select(DB::raw('DATE(created_at) as date'), 'total_bill AS value')
            ->where('seller_id', Auth::guard('web')->user()->id)
            ->where('vendor_id', Auth::guard('web')->user()->vendor->id)
            ->where(function ($query) {
                $query->where('status', '3')
                    ->orWhere('status', '6');
            })
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('created_at', 'asc')
            ->get();


        return ($result);
    }

    public function allNotifications()
    {
        $admin = User::findorFail(Auth::guard('web')->user()->id);
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $admin->id)
            ->where('notifiable_type', get_class($admin))
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        return view('user.vendor.notification.list', compact('notifications'));
    }

    public function unreadNotifications()
    {
        // $notifications = Auth::guard('admin')->user()->unreadNotifications;
        $admin = User::findorFail(Auth::guard('web')->user()->id);
        $notifications = $admin->notifications;
        $count = count($admin->unreadNotifications);
        $response = [
            'notifications' => $notifications,
            'count' => $count,
        ];
        return response()->json($response);
    }

    public function readNotifications(Request $request)
    {
        $admin = User::findorFail(Auth::guard('web')->user()->id);
        $admin
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        $notifications = $admin->unreadNotifications;
        $count = count($notifications);
        $response = [
            'notifications' => $notifications,
            'count' => $count,
        ];
        return response()->json($response);
    }
}
