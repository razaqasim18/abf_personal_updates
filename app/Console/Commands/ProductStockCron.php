<?php

namespace App\Console\Commands;

use App\Jobs\productStoreJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
class ProductStockCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Productstock:cron';

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
        info("Product Store Job running at " . now());
        $productStoreJob = new productStoreJob();
        Queue::push($productStoreJob);
    }
}
