<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login.view');
        }
        return view('admin.home');
    }

    public function allNotifications()
    {
        $admin = Admin::findorFail(Auth::guard('admin')->user()->id);
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $admin->id)
            ->where('notifiable_type', get_class($admin))
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        return view('admin.notification.list', compact('notifications'));
    }

    public function unreadNotifications()
    {
        // $notifications = Auth::guard('admin')->user()->unreadNotifications;
        $admin = Admin::findorFail(Auth::guard('admin')->user()->id);
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
        $admin = Admin::findorFail(Auth::guard('admin')->user()->id);
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

    // public function markAllNotification()
    // {
    //     $admin = Admin::findorFail(Auth::guard('admin')->user()->id);
    //     $admin
    //         ->unreadNotifications
    //         ->markAsRead();

    //     $notifications = $admin->unreadNotifications;
    //     $count = count($notifications);
    //     $response = [
    //         'notifications' => $notifications,
    //         'count' => $count,
    //     ];
    //     return response()->json($response);
    // }

}
