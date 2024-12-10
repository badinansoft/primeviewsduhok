<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Cache;

class EnforceFiveSecondDelay
{
    public function handle($job, $next): void
    {
        $lastRun = Cache::get('slow_job_last_run', 0);
        if (time() - $lastRun < 20) {
            $job->release(20 - (time() - $lastRun));
            return;
        }

        $next($job);

        Cache::put('slow_job_last_run', time(), 60);
    }
}
