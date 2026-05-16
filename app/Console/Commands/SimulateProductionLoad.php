<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class SimulateProductionLoad extends Command
{
    protected $signature = 'simulate:production {--users=30 : Number of users}';
    protected $description = 'Simulate production with real-time monitoring';

    public function handle()
    {
        $users = (int) $this->option('users');
        
        $this->info("🎬 PRODUCTION SIMULATION");
        $this->info("Simulating {$users} concurrent users with real-time monitoring");
        $this->newLine();

        // Instructions
        $this->warn("📋 SETUP:");
        $this->line("1. Open 3 terminals");
        $this->line("2. Terminal 1: Run monitoring");
        $this->line("3. Terminal 2: Run this load test");
        $this->line("4. Terminal 3: Watch logs");
        $this->newLine();

        $this->info("Terminal 1 - Run this:");
        $this->line("   watch -n 2 'php artisan monitor:production'");
        $this->newLine();

        $this->info("Terminal 3 - Run this:");
        $this->line("   tail -f storage/logs/laravel.log");
        $this->newLine();

        if (!$this->confirm('Ready to start load test?', true)) {
            return 0;
        }

        $this->newLine();
        $this->info("🚀 Starting load test in 3 seconds...");
        sleep(1);
        $this->info("3...");
        sleep(1);
        $this->info("2...");
        sleep(1);
        $this->info("1...");
        sleep(1);
        $this->newLine();

        // Run load tests
        $this->info("📊 Running Boss Battle test ({$users} users)...");
        $this->call('test:load', [
            '--users' => $users,
            '--type' => 'boss',
            '--level' => 'Easy'
        ]);

        $this->newLine();
        $this->info("⏳ Waiting 5 seconds before next test...");
        sleep(5);
        $this->newLine();

        $this->info("📊 Running Pretest ({$users} users)...");
        $this->call('test:load', [
            '--users' => $users,
            '--type' => 'pretest'
        ]);

        $this->newLine();
        $this->info("✅ Load test completed!");
        $this->newLine();

        // Show monitoring results
        $this->info("📈 Current System Status:");
        $this->call('monitor:production');

        $this->newLine();
        $this->info("📊 Performance Report:");
        $this->call('report:performance', ['--days' => 1]);

        return 0;
    }
}
