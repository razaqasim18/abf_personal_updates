<?php

namespace App\Console\Commands;

use App\Models\Commission;
use App\Models\Point;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UsersRankCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userrank:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Users Rank Job running at " . now());

         $user = User::select('commission_id', 'users.id AS userid', 'point')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->get();
        $commission = Commission::all();
        foreach ($user as $userrow) {
            foreach ($commission as $commissionrow) {
                if ($userrow->point >= $commissionrow->points) {
                    $pointtransactions = DB::table('point_transactions')
                        ->select(DB::raw("SUM(point) as point"))
                        ->where('status', '1')
                        ->where('user_id',  $userrow->userid)
                        ->where('is_child',0)
                        // ->whereMonth('created_at', '=', $currentMonth)
                        // ->whereYear('created_at', '=', $currentYear)
                        ->first();
                    if ($userrow->commission_id == null || $userrow->commission_id < $commissionrow->id) {
                        if($pointtransactions != null && $pointtransactions->point >= 100){
                                Point::updateOrCreate(
                                    ['user_id' => $userrow->userid],
                                    ['commission_id' => $commissionrow->id]
                                );
                                DB::transaction(function () use ($userrow, $commissionrow) {
                                $wallet = Wallet::updateOrCreate(
                                    ['user_id' => $userrow->userid],
                                    ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                                );
                                WalletTransaction::insert([
                                    'wallet_id' => $wallet->id,
                                    'amount' => $commissionrow->gift,
                                    'is_gift' => 1,
                                    'status' => 1,
                                    'detail' => 'user rank reward by daily cron job',
                                ]);
                            });
                        }
                            
                    }
                }
            }
        }


        // Queue::push(new usersRankJob());

    }
}
