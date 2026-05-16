<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\SessionSolo;
use App\Models\SessionAnswer;

class MonitorPerformance extends Command
{
    protected $signature = 'monitor:performance {--interval=5 : Refresh interval in seconds}';
    protected $description = 'Monitor system performance in real-time';

    public function handle()
    {
        $interval = (int) $this->option('interval');
        
        $this->info('📊 Performance Monitor Started');
        $this->info('Press Ctrl+C to stop');
        $this->newLine();

        while (true) {
            $this->displayStats();
            sleep($interval);
            
            // Clear screen for next iteration
            if (PHP_OS_FAMILY !== 'Windows') {
                system('clear');
            } else {
                system('cls');
            }
        }
    }

    protected function displayStats()
    {
        $startTime = microtime(true);

        // Database stats
        $activeSessions = SessionSolo::whereNull('waktu_selesai')->count();
        $completedSessions = SessionSolo::whereNotNull('waktu_selesai')
            ->where('updated_at', '>=', now()->subMinutes(5))
            ->count();
        
        $totalAnswers = SessionAnswer::where('session_type', 'solo')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        // Query performance
        $queryStart = microtime(true);
        DB::table('session_solo')->count();
        $queryTime = round((microtime(true) - $queryStart) * 1000, 2);

        // Memory usage
        $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2);
        $memoryPeak = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

        $totalTime = round((microtime(true) - $startTime) * 1000, 2);

        // Display
        $this->info('📊 SYSTEM PERFORMANCE - ' . now()->format('H:i:s'));
        $this->newLine();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Active Sessions', $activeSessions],
                ['Completed (5min)', $completedSessions],
                ['Answers (5min)', $totalAnswers],
                ['Query Time', "{$queryTime}ms"],
                ['Memory Usage', "{$memoryUsage}MB"],
                ['Memory Peak', "{$memoryPeak}MB"],
                ['Stats Time', "{$totalTime}ms"],
            ]
        );
    }
}
