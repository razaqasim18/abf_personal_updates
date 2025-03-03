<?php

namespace App\Jobs;

use App\Helpers\CustomHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class assignPointsToUserAndParentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    private $userid;
    private $points;
    private $orgid;
    public function __construct($orgid, $userid, $points)
    {
        $this->orgid = $orgid;
        $this->userid = $userid;
        $this->points = $points;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
          CustomHelper::assignPointsToUserAndParents($this->orgid,$this->userid, $this->points);
    }
}
