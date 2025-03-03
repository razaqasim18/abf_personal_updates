<?php

namespace App\Jobs;

use App\Helpers\CustomHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class singleUserRankJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */private $userid;
    public function __construct($userid)
    {
        $this->userid = $userid;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        CustomHelper::calculateUserRank($this->userid);
    }
}
