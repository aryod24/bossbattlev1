<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\QuestionBank;
use App\Models\SoloRaid;

class HealthCheck extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Quick health check for production';

    public function handle()
    {
        $this->info('🏥 HEALTH CHECK');
        $this->newLine();

        $checks = [];
        $failed = 0;

        // 1. Database Connection
        try {
            DB::connection()->getPdo();
            $checks[] = ['Database Connection', '✅ OK', ''];
        } catch (\Exception $e) {
            $checks[] = ['Database Connection', '❌ FAIL', $e->getMessage()];
            $failed++;
        }

        // 2. Users Table
        try {
            $userCount = User::count();
            $checks[] = ['Users Table', '✅ OK', "{$userCount} users"];
        } catch (\Exception $e) {
            $checks[] = ['Users Table', '❌ FAIL', $e->getMessage()];
            $failed++;
        }

        // 3. Questions Available
        try {
            $questionCount = QuestionBank::count();
            if ($questionCount > 0) {
                $checks[] = ['Question Bank', '✅ OK', "{$questionCount} questions"];
            } else {
                $checks[] = ['Question Bank', '⚠️  WARN', 'No questions found'];
                $failed++;
            }
        } catch (\Exception $e) {
            $checks[] = ['Question Bank', '❌ FAIL', $e->getMessage()];
            $failed++;
        }

        // 4. Active Raids
        try {
            $activeRaids = SoloRaid::where('status', 'active')->count();
            if ($activeRaids > 0) {
                $checks[] = ['Active Raids', '✅ OK', "{$activeRaids} raids"];
            } else {
                $checks[] = ['Active Raids', '⚠️  WARN', 'No active raids'];
            }
        } catch (\Exception $e) {
            $checks[] = ['Active Raids', '❌ FAIL', $e->getMessage()];
            $failed++;
        }

        // 5. Storage Writable
        $storagePath = storage_path('logs');
        if (is_writable($storagePath)) {
            $checks[] = ['Storage Writable', '✅ OK', $storagePath];
        } else {
            $checks[] = ['Storage Writable', '❌ FAIL', 'Cannot write to storage'];
            $failed++;
        }

        // 6. PHP Version
        $phpVersion = PHP_VERSION;
        $checks[] = ['PHP Version', '✅ OK', $phpVersion];

        // 7. Memory Limit
        $memoryLimit = ini_get('memory_limit');
        $checks[] = ['Memory Limit', '✅ OK', $memoryLimit];

        // Display Results
        $this->table(
            ['Check', 'Status', 'Details'],
            $checks
        );

        $this->newLine();
        if ($failed === 0) {
            $this->info('✅ All checks passed');
            return 0;
        } else {
            $this->error("❌ {$failed} check(s) failed");
            return 1;
        }
    }
}
