<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\Admin;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Auth;
use DB;
use Illuminate\Http\Request;
// use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    public function monthlyComission()
    {
        $wallet = Wallet::where('user_id', Auth::user()->id)->first();
        if($wallet){
            $report = WalletTransaction::select(
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year')
            )
            ->where('wallet_id', $wallet->id)
            ->where('status', 2)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->latest()
            ->get();
        } else {
            $report = [];
        }    
         return view('user.report.monthlylist', [
            'report' => $report,
        ]); 
    }
}