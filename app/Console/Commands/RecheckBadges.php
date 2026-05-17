<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\BadgeService;

class RecheckBadges extends Command
{
    protected $signature = 'badges:recheck {--user= : Recheck specific user ID only}';
    protected $description = 'Re-check and award badges for all users based on current data';

    public function handle(BadgeService $badgeService)
    {
        $query = User::query();

        if ($userId = $this->option('user')) {
            $query->where('id', $userId);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->error('No users found.');
            return 1;
        }

        $this->info("Rechecking badges for {$users->count()} user(s)...");
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $totalAwarded = 0;

        foreach ($users as $user) {
            $newBadges = $badgeService->checkAll($user);
            $totalAwarded += count($newBadges);

            if (count($newBadges) > 0) {
                $names = collect($newBadges)->pluck('name')->join(', ');
                $this->newLine();
                $this->line("  <info>[{$user->nama}]</info> unlocked: <comment>{$names}</comment>");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! {$totalAwarded} new badge(s) awarded across all users.");

        return 0;
    }
}
