<?php

namespace App\Jobs;

use App\Models\ApiLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteOldApiLogs implements ShouldQueue
{
    use Queueable;

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
        ApiLog::where('created_at', '<', now()->subDays(30))->delete();
    }
}
