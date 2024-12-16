<?php

namespace App\Console\Commands;

use App\Models\PageVisit;
use Illuminate\Console\Command;

class PruneMonitoringData extends Command
{
    protected $signature = 'monitoring:prune';
    protected $description = 'Prune old monitoring data';

    public function handle()
    {
        $days = config('monitoring.retention_days', 30);
        
        $deleted = PageVisit::where('visit_time', '<', now()->subDays($days))->delete();
        
        $this->info("Deleted {$deleted} old monitoring records.");
    }
} 