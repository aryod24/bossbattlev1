<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SoloRaid;
use App\Models\SessionSolo;
use App\Models\SessionAnswer;
use App\Services\SoloBattleService;
use App\Services\PreTestService;

class LoadTestBattle extends Command
{
    protected $signature = 'test:load 
                            {--users=10 : Number of concurrent users}
                            {--type=boss : Type: pretest, boss, learning}
                            {--level=Easy : Level: Easy, Medium, Hard}';

    protected $description = 'Load test with multiple concurrent users';

    public function handle(SoloBattleService $battleService, PreTestService $pretestService)
    {
        $userCount = (int) $this->option('users');
        $type = $this->option('type');
        $level = $this->option('level');

        $this->info("🚀 LOAD TEST SCENARIO");
        $this->info("Users: {$userCount} | Type: {$type} | Level: {$level}");
        $this->newLine();

        // Create test users if needed
        $this->info('👥 Preparing test users...');
        $users = $this->prepareUsers($userCount);
        $this->info("✅ {$users->count()} users ready");
        $this->newLine();

        // Find raid
        if ($type !== 'pretest') {
            $raid = SoloRaid::where('status', 'active')
                ->where('type', $type)
                ->first();

            if (!$raid) {
                $this->error("No active {$type} raid found!");
                return 1;
            }

            $this->info("🎯 Raid: {$raid->nama}");
        }

        // Start sessions for all users
        $this->info('🎮 Starting sessions...');
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $sessions = collect();
        $startTime = microtime(true);

        foreach ($users as $user) {
            if ($type === 'pretest') {
                $session = $pretestService->initPreTest($user);
            } else {
                $session = $battleService->initSession($user, $raid, $level);
            }
            $sessions->push($session);
            $bar->advance();
        }

        $bar->finish();
        $sessionTime = round((microtime(true) - $startTime) * 1000, 2);
        $this->newLine(2);
        $this->info("✅ All sessions started in {$sessionTime}ms");
        $this->newLine();

        // Auto-answer all questions
        $this->info('📝 Auto-answering questions...');
        $bar = $this->output->createProgressBar($sessions->count());
        $bar->start();

        $answerStartTime = microtime(true);

        foreach ($sessions as $session) {
            $sessionType = 'solo'; // All sessions use 'solo' type
            
            $answers = SessionAnswer::where('session_id', $session->id)
                ->where('session_type', $sessionType)
                ->with('question')
                ->get();

            foreach ($answers as $answer) {
                $answer->update([
                    'jawaban_user' => $answer->question->jawaban_benar,
                    'is_correct' => true,
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $answerTime = round((microtime(true) - $answerStartTime) * 1000, 2);
        $this->newLine(2);
        $this->info("✅ All questions answered in {$answerTime}ms");
        $this->newLine();

        // Finish all sessions
        $this->info('🏁 Finishing sessions...');
        $bar = $this->output->createProgressBar($sessions->count());
        $bar->start();

        $finishStartTime = microtime(true);

        foreach ($sessions as $session) {
            $battleService->finishSession($session->id);
            $bar->advance();
        }

        $bar->finish();
        $finishTime = round((microtime(true) - $finishStartTime) * 1000, 2);
        $this->newLine(2);
        $this->info("✅ All sessions finished in {$finishTime}ms");
        $this->newLine();

        // Calculate stats
        $totalTime = round((microtime(true) - $startTime) * 1000, 2);
        $avgTimePerUser = round($totalTime / $userCount, 2);

        // Show results
        $this->info('📊 LOAD TEST RESULTS:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Users', $userCount],
                ['Total Time', "{$totalTime}ms"],
                ['Avg Time/User', "{$avgTimePerUser}ms"],
                ['Session Creation', "{$sessionTime}ms"],
                ['Answer Submission', "{$answerTime}ms"],
                ['Session Finish', "{$finishTime}ms"],
                ['Throughput', round($userCount / ($totalTime / 1000), 2) . ' users/sec'],
            ]
        );

        // Query stats
        $this->newLine();
        $this->info('💾 DATABASE STATS:');
        
        $sessionCount = SessionSolo::whereIn('id', $sessions->pluck('id'))->count();
        $answerCount = SessionAnswer::whereIn('session_id', $sessions->pluck('id'))
            ->where('session_type', 'solo')
            ->count();

        $this->table(
            ['Table', 'Records'],
            [
                ['Sessions Created', $sessionCount],
                ['Answers Submitted', $answerCount],
                ['Total Records', $sessionCount + $answerCount],
            ]
        );

        return 0;
    }

    protected function prepareUsers($count)
    {
        $existing = User::whereRoleName('student')
            ->where('email', 'like', 'loadtest%@test.com')
            ->get();

        if ($existing->count() >= $count) {
            return $existing->take($count);
        }

        // Create additional users
        $needed = $count - $existing->count();
        $this->info("Creating {$needed} additional test users...");

        for ($i = $existing->count() + 1; $i <= $count; $i++) {
            User::create([
                'nama' => "Load Test User {$i}",
                'email' => "loadtest{$i}@test.com",
                'password' => bcrypt('password'),
                'role' => 'student',
                'nim' => "LOAD" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'is_pretest_done' => true,
                'current_section' => 'Easy',
            ]);
        }

        return User::whereRoleName('student')
            ->where('email', 'like', 'loadtest%@test.com')
            ->take($count)
            ->get();
    }
}
