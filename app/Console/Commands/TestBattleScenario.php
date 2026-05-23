<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SoloRaid;
use App\Models\SessionSolo;
use App\Models\SessionAnswer;
use App\Models\QuestionBank;
use App\Services\SoloBattleService;
use App\Services\PreTestService;

class TestBattleScenario extends Command
{
    protected $signature = 'test:battle 
                            {--user= : User ID or email}
                            {--type=pretest : Type: pretest, boss, learning}
                            {--level=Easy : Level: Easy, Medium, Hard}
                            {--auto-answer : Auto answer all questions correctly}';

    protected $description = 'Simulate battle scenario for testing';

    public function handle(SoloBattleService $battleService, PreTestService $pretestService)
    {
        $this->info('🎮 Starting Battle Test Scenario...');
        $this->newLine();

        // Get or create test user
        $user = $this->getUser();
        if (!$user) {
            $this->error('User not found!');
            return 1;
        }

        $this->info("👤 User: {$user->nama} ({$user->email})");
        $this->newLine();

        $type = $this->option('type');
        
        if ($type === 'pretest') {
            return $this->runPretest($user, $pretestService, $battleService);
        } else {
            return $this->runBattle($user, $type, $battleService);
        }
    }

    protected function getUser()
    {
        $userInput = $this->option('user');
        
        if ($userInput) {
            // Try to find by ID or email
            $user = User::where('id', $userInput)
                ->orWhere('email', $userInput)
                ->first();
            
            if ($user) {
                return $user;
            }
        }

        // Get first student
        $user = User::whereRoleName('student')->first();
        
        if (!$user) {
            $this->warn('No student found. Creating test student...');
            $user = User::create([
                'nama' => 'Test Student',
                'email' => 'test@student.com',
                'password' => bcrypt('password'),
                'role' => 'student',
                'nim' => 'TEST001',
            ]);
        }

        return $user;
    }

    protected function runPretest($user, $pretestService, $battleService)
    {
        $this->info('📝 Running PRETEST scenario...');
        
        // Check if user needs pretest
        if (!$user->needsPretest()) {
            $this->warn('User already completed pretest!');
            $this->info("Current section: {$user->current_section}");
            
            if (!$this->confirm('Reset pretest status?')) {
                return 0;
            }
            
            $user->update([
                'is_pretest_done' => false,
                'current_section' => null,
            ]);
            $this->info('✅ Pretest status reset!');
        }

        // Start pretest session
        $this->info('🚀 Starting pretest session...');
        $session = $pretestService->initPreTest($user);
        $this->info("✅ Session created: #{$session->id}");

        // Load questions
        $questions = SessionAnswer::where('session_id', $session->id)
            ->where('session_type', 'solo')
            ->with('question')
            ->orderBy('urutan_soal')
            ->get();

        $this->info("📚 Total questions: {$questions->count()}");
        $this->newLine();

        $autoAnswer = $this->option('auto-answer');

        // Answer questions
        $bar = $this->output->createProgressBar($questions->count());
        $bar->start();

        foreach ($questions as $answer) {
            $question = $answer->question;
            
            if ($autoAnswer) {
                // Auto answer correctly
                $userAnswer = $question->jawaban_benar;
            } else {
                // Show question and ask for answer
                $this->newLine(2);
                $this->line("Q{$answer->urutan_soal}: " . strip_tags($question->soal_text));
                
                if ($question->tipe === 'multiple_choice') {
                    $this->line("A. {$question->pilihan_a}");
                    $this->line("B. {$question->pilihan_b}");
                    $this->line("C. {$question->pilihan_c}");
                    $this->line("D. {$question->pilihan_d}");
                    $this->line("✅ Correct: {$question->jawaban_benar}");
                    $userAnswer = $this->ask('Your answer (or press Enter for correct answer)', $question->jawaban_benar);
                } else {
                    $this->line("✅ Correct: {$question->jawaban_benar}");
                    $userAnswer = $this->ask('Your answer (or press Enter for correct answer)', $question->jawaban_benar);
                }
            }

            // Submit answer
            $answer->update([
                'jawaban_user' => $userAnswer,
                'is_correct' => $userAnswer === $question->jawaban_benar,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Calculate correct answers
        $correctCount = $questions->where('is_correct', true)->count();
        $session->jumlah_benar = $correctCount;
        $session->save();

        // Finish session using PreTestService
        $this->info('🏁 Finishing session...');
        $result = $pretestService->finishPreTest($session);

        // Show results
        $this->newLine();
        $this->info('📊 RESULTS:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Questions', $result['jumlah_soal']],
                ['Correct Answers', $result['jumlah_benar']],
                ['Score', $result['score'] . '%'],
                ['Placement', $result['section']],
                ['Duration', $result['durasi']],
            ]
        );

        return 0;
    }

    protected function runBattle($user, $type, $battleService)
    {
        $level = $this->option('level');
        
        $this->info("⚔️ Running BATTLE scenario...");
        $this->info("Type: {$type} | Level: {$level}");
        
        // Find active raid
        $raid = SoloRaid::where('status', 'active')
            ->where('type', $type)
            ->first();

        if (!$raid) {
            $this->error("No active {$type} raid found!");
            return 1;
        }

        $this->info("🎯 Raid: {$raid->nama}");
        $this->newLine();

        // Start battle session
        $this->info('🚀 Starting battle session...');
        $session = $battleService->initSession($user, $raid, $level);
        $this->info("✅ Session created: #{$session->id}");

        // Load questions
        $questions = SessionAnswer::where('session_id', $session->id)
            ->where('session_type', 'solo')
            ->with('question')
            ->orderBy('urutan_soal')
            ->get();

        $this->info("📚 Total questions: {$questions->count()}");
        $this->newLine();

        $autoAnswer = $this->option('auto-answer');

        // Answer questions
        $bar = $this->output->createProgressBar($questions->count());
        $bar->start();

        foreach ($questions as $answer) {
            $question = $answer->question;
            
            if ($autoAnswer) {
                // Auto answer correctly
                $userAnswer = $question->jawaban_benar;
            } else {
                // Show question and ask for answer
                $this->newLine(2);
                $this->line("Q{$answer->urutan_soal}: " . strip_tags($question->soal_text));
                
                if ($question->tipe === 'multiple_choice') {
                    $this->line("A. {$question->pilihan_a}");
                    $this->line("B. {$question->pilihan_b}");
                    $this->line("C. {$question->pilihan_c}");
                    $this->line("D. {$question->pilihan_d}");
                    $this->line("✅ Correct: {$question->jawaban_benar}");
                    $userAnswer = $this->ask('Your answer (or press Enter for correct answer)', $question->jawaban_benar);
                } else {
                    $this->line("✅ Correct: {$question->jawaban_benar}");
                    $userAnswer = $this->ask('Your answer (or press Enter for correct answer)', $question->jawaban_benar);
                }
            }

            // Submit answer
            $answer->update([
                'jawaban_user' => $userAnswer,
                'is_correct' => $userAnswer === $question->jawaban_benar,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Finish session (will auto-calculate jumlah_benar)
        $this->info('🏁 Finishing session...');
        $battleService->finishSession($session->id);
        $session->refresh();

        // Show results
        $this->newLine();
        $this->info('📊 RESULTS:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Questions', $questions->count()],
                ['Correct Answers', $session->jumlah_benar],
                ['Score', round($session->skor_akhir, 2)],
                ['Boss HP', $session->boss_hp_akhir],
                ['Boss Defeated', $session->boss_kalah ? '✅ YES' : '❌ NO'],
            ]
        );

        return 0;
    }
}
