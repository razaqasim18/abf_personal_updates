<?php

namespace App\Console\Commands;

use App\Helpers\CustomHelper;
use Illuminate\Console\Command;
use App\Models\Commission;
use App\Models\EpinRequest;
use App\Models\Order;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UserCommissionCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usercommission:cron';

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
        info("Users Commission Job running at which is on monthly base " . now());
        CustomHelper::directChildCommission();
        // $userCommsionJob = new userCommsionJob();
        // Queue::push($userCommsionJob);
    }
}
