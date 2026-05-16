<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SessionSolo;
use App\Models\User;

class PerformanceReport extends Command
{
    protected $signature = 'report:performance {--days=7 : Number of days to analyze}';
    protected $description = 'Generate performance report';

    public function handle()
    {
        $days = (int) $this->option('days');
        $startDate = now()->subDays($days);

        $this->info("📈 PERFORMANCE REPORT (Last {$days} days)");
        $this->newLine();

        // Session stats
        $totalSessions = SessionSolo::where('created_at', '>=', $startDate)->count();
        $completedSessions = SessionSolo::where('created_at', '>=', $startDate)
            ->whereNotNull('waktu_selesai')
            ->count();
        $activeSessions = SessionSolo::whereNull('waktu_selesai')->count();

        // Average duration
        $avgDuration = SessionSolo::where('created_at', '>=', $startDate)
            ->whereNotNull('waktu_selesai')
            ->whereNotNull('durasi_detik')
            ->avg('durasi_detik');

        // User stats
        $activeUsers = User::where('role', 'student')
            ->whereHas('sessionSolos', function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            })
            ->count();

        // Success rate
        $successRate = $completedSessions > 0 
            ? round(($completedSessions / $totalSessions) * 100, 2) 
            : 0;

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Sessions', $totalSessions],
                ['Completed', $completedSessions],
                ['Active Now', $activeSessions],
                ['Success Rate', "{$successRate}%"],
                ['Avg Duration', round($avgDuration ?? 0, 2) . 's'],
                ['Active Users', $activeUsers],
                ['Sessions/User', $activeUsers > 0 ? round($totalSessions / $activeUsers, 2) : 0],
            ]
        );

        // Daily breakdown
        $this->newLine();
        $this->info('📅 DAILY BREAKDOWN:');
        
        $dailyStats = SessionSolo::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        $tableData = $dailyStats->map(function($stat) {
            return [$stat->date, $stat->count];
        })->toArray();

        $this->table(['Date', 'Sessions'], $tableData);

        return 0;
    }
}
