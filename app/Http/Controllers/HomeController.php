<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\User;
use App\Models\Banner;
use App\Models\Order;
use App\Models\VendorOrder;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Wallet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // order pending
        $pendingorder = Order::select(DB::raw('COUNT(id) as count'))->where('status', '0')->where('user_id', Auth::guard('web')->user()->id)->first();
        $pendingvendororder = VendorOrder::select(DB::raw('COUNT(id) as count'))->where('status', '0')->where('user_id', Auth::guard('web')->user()->id)->first();
        $pendingcombinedOrders  = $pendingorder->count + $pendingvendororder->count;
        
        // order inprocess
        $inprocessorder = Order::select(DB::raw('COUNT(id) as count'))->where('status', '1')->where('user_id', Auth::guard('web')->user()->id)->first();
        $inprocessvendororder = VendorOrder::select(DB::raw('COUNT(id) as count'))->where('status', '1')->where('user_id', Auth::guard('web')->user()->id)->first();
        $inprocesscombinedOrders  = $inprocessorder->count + $inprocessvendororder->count;
        
        // order approved
        $approvedorder = Order::select(DB::raw('COUNT(id) as count'))->where('status', '2')->where('user_id', Auth::guard('web')->user()->id)->first();
        $approvedvendororder = VendorOrder::select(DB::raw('COUNT(id) as count'))->where('status', '2')->where('user_id', Auth::guard('web')->user()->id)->first();
        $approvedcombinedOrders  = $approvedorder->count + $approvedvendororder->count;
        
        // order delivered
        $deliveredorder = Order::select(DB::raw('COUNT(id) as count'))->where('status', '3')->where('user_id', Auth::guard('web')->user()->id)->first();
        $deliveredvendororder = VendorOrder::select(DB::raw('COUNT(id) as count'))->where('status', '3')->where('user_id', Auth::guard('web')->user()->id)->first();
        $deliveredcombinedOrders  = $deliveredorder->count + $deliveredvendororder->count;
      
        // order cancelled
        $cancelledorder = Order::select(DB::raw('COUNT(id) as count'))->where('status', '-1')->where('user_id', Auth::guard('web')->user()->id)->first();
        $cancelledvendororder = VendorOrder::select(DB::raw('COUNT(id) as count'))->where('status', '-1')->where('user_id', Auth::guard('web')->user()->id)->first();
        $cancelledcombinedOrders  = $cancelledorder->count + $cancelledvendororder->count;
     
       // order total
        $totalorder = Order::select(DB::raw('COUNT(id) as count'))->where('user_id', Auth::guard('web')->user()->id)->first();
        $totalvendororder = VendorOrder::select(DB::raw('COUNT(id) as count'))->where('user_id', Auth::guard('web')->user()->id)->first();
        $totalcombinedOrders  = $totalorder->count + $totalvendororder->count;
        
            
        $order = [
            'pending' => $pendingcombinedOrders,
            'inprocess' => $inprocesscombinedOrders,
            'approved' => $approvedcombinedOrders,
            'delivered' => $deliveredcombinedOrders,
            'cancelled' => $cancelledcombinedOrders,
            'total' => $totalcombinedOrders,
        ];
        $date = date("Y-m-d");
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;


        $startquater = $currentMonth <= 6 ? 1 : 6;
        $user = [
            'point' => Point::where('user_id', Auth::guard('web')->user()->id)->first(),
            'wallet' => Wallet::where('user_id', Auth::guard('web')->user()->id)->first(),
            // 'walletcommission' => Wallet::select(DB::raw('SUM(wallet_transactions.amount) as count'))
            //     ->join('wallet_transactions', 'wallet_transactions.wallet_id', '=', 'wallets.id')
            //     ->where('wallets.user_id', Auth::guard('web')->user()->id)
            //     ->where('wallet_transactions.status', 2)
            //     ->where('wallet_transactions.is_gift', 0)
            //     ->first(),

            //wallet commission with 6 month condition
            'walletcommission' => Wallet::select(DB::raw('SUM(wallet_transactions.amount) as count'))
                ->join('wallet_transactions', 'wallet_transactions.wallet_id', '=', 'wallets.id')
                ->where('wallets.user_id', Auth::guard('web')->user()->id)
                ->where('wallet_transactions.status', 2)
                ->where('wallet_transactions.is_gift', 0)
                ->whereYear('wallet_transactions.created_at', $currentYear)
                ->whereMonth('wallet_transactions.created_at', '>=', $startquater) // start of quater
                ->whereMonth('wallet_transactions.created_at', '<=', $currentMonth) // Current month
                ->first(),
            //wallet commission with 6 month condition

            'mppointmonthly' => PointTransaction::select(DB::raw('SUM(point) as count'))
                ->where('user_id', Auth::guard('web')->user()->id)
                ->where('status', 1)
                ->where('is_child', 0)
                ->whereYear('point_transactions.created_at', $currentYear)
                ->whereMonth('point_transactions.created_at', $currentMonth)
                ->first(),

            // 'monthlypoint' => DB::table('point_transactions')
            // ->select(DB::raw("SUM(point) as totapoint"))
            // ->where('status', '1')
            // ->where('is_child', '1')
            // ->where('user_id', Auth::guard('web')->user()->id)
            // ->where(DB::raw("MONTH(created_at) = MONTH($date)"))
            // ->first(),


            'monthlypoint' => DB::table('point_transactions')
                ->select(DB::raw("SUM(point) as totapoint"))
                ->where('status', '1')
                ->where('is_child', '1')
                ->where('user_id', Auth::guard('web')->user()->id)
                ->whereYear('created_at', date('Y', strtotime($date)))
                ->whereMonth('created_at', date('m', strtotime($date)))
                ->first(),


            'usermonthlypointtodeduct' => DB::table('point_transactions')
                ->select(DB::raw("SUM(point) as totapoint"))
                ->where('status', '1')
                ->where('user_id', Auth::guard('web')->user()->id)
                ->where(DB::raw("MONTH(created_at) = MONTH($date)"))
                ->first(),
            'personalpoint' => PointTransaction::select(DB::raw('SUM(point) as count'))
                ->where('user_id', Auth::guard('web')->user()->id)
                ->where('status', 1)
                ->where('is_child', 0)
                ->first(),
            'personalmonthlyearning' => Wallet::select(DB::raw('SUM(wallet_transactions.amount) as count'))
                ->join('wallet_transactions', 'wallet_transactions.wallet_id', '=', 'wallets.id')
                ->where('wallets.user_id', Auth::guard('web')->user()->id)
                ->where('wallet_transactions.status', 2)
                ->where('wallet_transactions.is_gift', 0)
                ->whereYear('wallet_transactions.created_at', $currentYear)
                ->whereMonth('wallet_transactions.created_at', $currentMonth)
                ->first(),
        ];
        $dashboardbanner = Banner::where('type', 0)->get();
        $myteam = User::select(DB::raw('COUNT(id) AS count'))
            ->where('sponserid', Auth::guard('web')->user()->id)
            ->first();
        $totalteam = DB::selectOne('WITH RECURSIVE SubChildUsers AS (SELECT id, sponserid, name FROM users WHERE id = :userId UNION ALL SELECT u.id, u.sponserid, u.name FROM users u INNER JOIN SubChildUsers scu ON u.sponserid = scu.id)SELECT COUNT(*) AS userCount FROM SubChildUsers', ['userId' => Auth::guard('web')->user()->id])->userCount;
        return view('user.home', compact('order', 'user', 'dashboardbanner', 'totalteam', 'myteam'));
    }
}
