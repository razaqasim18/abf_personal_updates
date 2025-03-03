<?php

namespace App\Jobs;

use App\Models\Commission;
use App\Models\Point;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class usersRankJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // CustomHelper::calculateUsersRank();
        $user = User::select('commission_id', 'users.id AS userid', 'point')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->get();
        $commission = Commission::all();
        foreach ($user as $userrow) {
            foreach ($commission as $commissionrow) {
                if ($userrow->point >= $commissionrow->points) {
                    Point::updateOrCreate(
                        ['user_id' => $userrow->userid],
                        ['commission_id' => $commissionrow->id]
                    );
                    if ($userrow->commission_id == null || $userrow->commission_id < $commissionrow->id) {
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
                                'detail' => "User rank cronjob"
                            ]);
                        });
                    }
                }
            }
        }
    }
}
