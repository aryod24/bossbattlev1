<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\SessionSolo;
use App\Models\User;

class ProductionMonitor extends Command
{
    protected $signature = 'monitor:production {--log : Save to log file}';
    protected $description = 'Production monitoring with alerts';

    public function handle()
    {
        $this->info('🔍 PRODUCTION MONITORING');
        $this->info('Time: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();

        $alerts = [];
        
        // 1. Active Sessions Check
        $activeSessions = SessionSolo::whereNull('waktu_selesai')
            ->where('created_at', '<', now()->subHours(2))
            ->count();
        
        if ($activeSessions > 0) {
            $alerts[] = "⚠️  {$activeSessions} sessions stuck (>2 hours)";
        }

        // 2. Database Connection
        try {
            DB::connection()->getPdo();
            $dbStatus = '✅ Connected';
        } catch (\Exception $e) {
            $dbStatus = '❌ Failed';
            $alerts[] = "🚨 Database connection failed!";
        }

        // 3. Recent Activity (last 5 minutes)
        $recentSessions = SessionSolo::where('created_at', '>=', now()->subMinutes(5))->count();
        $recentCompletions = SessionSolo::whereNotNull('waktu_selesai')
            ->where('waktu_selesai', '>=', now()->subMinutes(5))
            ->count();

        // 4. Error Rate
        $totalLast5min = SessionSolo::where('created_at', '>=', now()->subMinutes(5))->count();
        $completedLast5min = SessionSolo::whereNotNull('waktu_selesai')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();
        
        $errorRate = $totalLast5min > 0 
            ? round((($totalLast5min - $completedLast5min) / $totalLast5min) * 100, 2)
            : 0;

        if ($errorRate > 20) {
            $alerts[] = "⚠️  High error rate: {$errorRate}%";
        }

        // 5. Active Users
        $activeUsers = User::whereRoleName('student')
            ->whereHas('sessionSolos', function($q) {
                $q->where('created_at', '>=', now()->subMinutes(30));
            })
            ->count();

        // 6. Average Response Time (last 10 completed sessions)
        $avgDuration = SessionSolo::whereNotNull('waktu_selesai')
            ->whereNotNull('durasi_detik')
            ->where('waktu_selesai', '>=', now()->subMinutes(10))
            ->avg('durasi_detik');

        if ($avgDuration > 120) {
            $alerts[] = "⚠️  Slow response time: " . round($avgDuration, 2) . "s";
        }

        // 7. Memory Usage
        $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2);
        $memoryLimit = ini_get('memory_limit');

        // Display Status
        $this->table(
            ['Metric', 'Value', 'Status'],
            [
                ['Database', $dbStatus, $dbStatus === '✅ Connected' ? 'OK' : 'ERROR'],
                ['Active Sessions (now)', SessionSolo::whereNull('waktu_selesai')->count(), 'INFO'],
                ['Stuck Sessions (>2h)', $activeSessions, $activeSessions > 0 ? 'WARNING' : 'OK'],
                ['New Sessions (5min)', $recentSessions, 'INFO'],
                ['Completed (5min)', $recentCompletions, 'INFO'],
                ['Error Rate (5min)', "{$errorRate}%", $errorRate > 20 ? 'WARNING' : 'OK'],
                ['Active Users (30min)', $activeUsers, 'INFO'],
                ['Avg Duration (10min)', round($avgDuration ?? 0, 2) . 's', $avgDuration > 120 ? 'WARNING' : 'OK'],
                ['Memory Usage', "{$memoryUsage}MB / {$memoryLimit}", 'INFO'],
            ]
        );

        // Show Alerts
        if (count($alerts) > 0) {
            $this->newLine();
            $this->error('🚨 ALERTS:');
            foreach ($alerts as $alert) {
                $this->line($alert);
            }
        } else {
            $this->newLine();
            $this->info('✅ All systems operational');
        }

        // Log to file if requested
        if ($this->option('log')) {
            $logData = [
                'timestamp' => now()->toDateTimeString(),
                'active_sessions' => SessionSolo::whereNull('waktu_selesai')->count(),
                'stuck_sessions' => $activeSessions,
                'recent_sessions' => $recentSessions,
                'error_rate' => $errorRate,
                'active_users' => $activeUsers,
                'avg_duration' => round($avgDuration ?? 0, 2),
                'memory_mb' => $memoryUsage,
                'alerts' => $alerts,
            ];

            $logFile = storage_path('logs/production-monitor.log');
            file_put_contents($logFile, json_encode($logData) . "\n", FILE_APPEND);
            $this->info("\n📝 Logged to: {$logFile}");
        }

        return count($alerts) > 0 ? 1 : 0;
    }
}
