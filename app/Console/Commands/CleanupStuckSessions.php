<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SessionSolo;

class CleanupStuckSessions extends Command
{
    protected $signature = 'cleanup:sessions {--hours=2 : Hours threshold} {--dry-run : Preview without executing}';
    protected $description = 'Cleanup stuck sessions that never completed';

    public function handle()
    {
        $hours = (int) $this->option('hours');
        $dryRun = $this->option('dry-run');

        $this->info("🧹 Cleanup Stuck Sessions (>{$hours} hours)");
        $this->newLine();

        // Find stuck sessions
        $stuckSessions = SessionSolo::whereNull('waktu_selesai')
            ->where('created_at', '<', now()->subHours($hours))
            ->get();

        if ($stuckSessions->isEmpty()) {
            $this->info('✅ No stuck sessions found');
            return 0;
        }

        $this->warn("Found {$stuckSessions->count()} stuck sessions:");
        $this->newLine();

        $tableData = $stuckSessions->map(function($session) {
            return [
                $session->id,
                $session->user->nama ?? 'N/A',
                $session->created_at->format('Y-m-d H:i:s'),
                $session->created_at->diffForHumans(),
            ];
        })->toArray();

        $this->table(
            ['ID', 'User', 'Started', 'Age'],
            $tableData
        );

        if ($dryRun) {
            $this->info("\n🔍 DRY RUN - No changes made");
            return 0;
        }

        if (!$this->confirm('Mark these sessions as completed?')) {
            $this->info('Cancelled');
            return 0;
        }

        // Mark as completed with timeout
        foreach ($stuckSessions as $session) {
            $session->update([
                'waktu_selesai' => now(),
                'durasi_detik' => now()->diffInSeconds($session->waktu_mulai ?? $session->created_at),
            ]);
        }

        $this->info("\n✅ Cleaned up {$stuckSessions->count()} sessions");
        return 0;
    }
}
