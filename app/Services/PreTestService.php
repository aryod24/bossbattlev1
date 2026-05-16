<?php

namespace App\Services;

use App\Models\QuestionBank;
use App\Models\SessionAnswer;
use App\Models\SessionSolo;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PreTestService
{
    /**
     * Pre-test composition: 10 Easy + 10 Medium + 10 Hard = 30 questions
     */
    public const PRETEST_COMPOSITION = [
        'Easy' => 10,
        'Medium' => 10,
        'Hard' => 10,
    ];

    public const TOTAL_QUESTIONS = 30;

    /**
     * Section placement thresholds based on percentage score
     */
    public const PLACEMENT_THRESHOLDS = [
        'Hard' => 71,    // 71-100% → Section Hard
        'Medium' => 41,  // 41-70% → Section Medium
        'Easy' => 0,     // 0-40% → Section Easy
    ];

    /**
     * Initialize a pre-test session for a user.
     * Returns an existing active pre-test session if one exists.
     */
    public function initPreTest(User $user): SessionSolo
    {
        // Check for existing active pre-test session
        $existingSession = SessionSolo::where('user_id', $user->id)
            ->where('is_pretest', true)
            ->whereNull('waktu_selesai')
            ->first();

        if ($existingSession) {
            return $existingSession;
        }

        return DB::transaction(function () use ($user) {
            $config = \App\Http\Controllers\Admin\PreTestConfigController::getConfig();
            $totalQuestions = $config['total_questions'] ?? self::TOTAL_QUESTIONS;

            // Create pre-test session (no raid association, no boss HP)
            $session = SessionSolo::create([
                'user_id' => $user->id,
                'solo_raid_id' => null, // No raid associated (pre-test is standalone)
                'level' => 'Easy', // Default, not really used for pre-test
                'waktu_mulai' => now(),
                'jumlah_soal' => $totalQuestions,
                'boss_hp_awal' => null, // Pre-test has no boss
                'boss_hp_akhir' => null, // Pre-test has no boss
                'attempt_number' => 1,
                'is_counted_research' => true,
                'is_first_attempt' => true,
                'is_pretest' => true,
            ]);

            // Assign questions from each level
            $this->assignPretestQuestions($session);

            return $session;
        });
    }

    /**
     * Assign pre-test questions: 10 Easy + 10 Medium + 10 Hard, shuffled together.
     */
    private function assignPretestQuestions(SessionSolo $session): void
    {
        $allQuestions = collect();
        $config = \App\Http\Controllers\Admin\PreTestConfigController::getConfig();
        $composition = $config['composition'] ?? self::PRETEST_COMPOSITION;
        $bankGroup = $config['bank_group'] ?? null;

        foreach ($composition as $level => $count) {
            if ($count <= 0) continue;

            $query = QuestionBank::where('level', $level);
            
            // If bank_group is specified, use it
            // If bank_group is null or empty, prioritize bank_group 0 (Pre-Test bank)
            // If bank_group 0 doesn't have enough questions, get from all banks
            if (!empty($bankGroup)) {
                $query->where('bank_group', $bankGroup);
            } else {
                // Try to get from bank_group 0 first (Pre-Test specific bank)
                $pretestQuestions = QuestionBank::where('level', $level)
                    ->where('bank_group', 0)
                    ->inRandomOrder()
                    ->limit($count)
                    ->get();
                
                // If not enough questions in bank_group 0, get from all banks
                if ($pretestQuestions->count() < $count) {
                    $remaining = $count - $pretestQuestions->count();
                    $additionalQuestions = QuestionBank::where('level', $level)
                        ->where('bank_group', '!=', 0)
                        ->inRandomOrder()
                        ->limit($remaining)
                        ->get();
                    
                    $questions = $pretestQuestions->merge($additionalQuestions);
                } else {
                    $questions = $pretestQuestions;
                }
                
                $allQuestions = $allQuestions->merge($questions);
                continue;
            }

            $questions = $query->inRandomOrder()
                ->limit($count)
                ->get();
            $allQuestions = $allQuestions->merge($questions);
        }

        // Shuffle all questions together for mixed difficulty
        $allQuestions = $allQuestions->shuffle();

        $order = 1;
        $answersToInsert = [];
        $now = now();

        foreach ($allQuestions as $question) {
            $answersToInsert[] = [
                'session_id' => $session->id,
                'session_type' => 'solo',
                'question_id' => $question->id,
                'urutan_soal' => $order++,
                'attempt_number' => 1,
                'is_counted_research' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($answersToInsert)) {
            SessionAnswer::insert($answersToInsert);
        }
    }

    /**
     * Finish a pre-test session and determine section placement.
     */
    public function finishPreTest(SessionSolo $session): array
    {
        if ($session->waktu_selesai) {
            return [
                'already_finished' => true,
                'score' => $session->skor_akhir,
                'section' => $session->user->current_section,
            ];
        }

        $session->waktu_selesai = now();
        $session->durasi_detik = $session->waktu_mulai->diffInSeconds($session->waktu_selesai);

        // Calculate score as percentage
        $score = $session->jumlah_soal > 0 
            ? round(($session->jumlah_benar / $session->jumlah_soal) * 100, 2) 
            : 0;
        $session->skor_akhir = $score;

        // Pre-test: no boss defeat, no XP
        $session->boss_kalah = false;
        $session->xp_diperoleh = 0;

        $session->save();

        // Determine section placement
        $section = $this->determineSection($score);

        // Update user profile
        $user = $session->user;
        $user->update([
            'pretest_score' => (int) $score,
            'current_section' => $section,
        ]);

        return [
            'score' => $score,
            'jumlah_benar' => $session->jumlah_benar,
            'jumlah_soal' => $session->jumlah_soal,
            'section' => $section,
            'durasi' => gmdate("H:i:s", $session->durasi_detik),
        ];
    }

    /**
     * Determine which section a user should be placed in based on score.
     * 0-40% → Easy, 41-70% → Medium, 71-100% → Hard
     */
    public function determineSection(float $score): string
    {
        foreach (self::PLACEMENT_THRESHOLDS as $section => $minScore) {
            if ($score >= $minScore) {
                return $section;
            }
        }

        return 'Easy';
    }
}
