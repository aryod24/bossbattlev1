<?php

namespace App\Services;

use App\Models\QuestionBank;
use App\Models\SessionAnswer;
use App\Models\SessionSolo;
use App\Models\SoloRaid;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SoloBattleService
{
    public const LEVEL_CONFIG = [
        'Easy' => ['questions' => 10, 'boss_hp' => 10, 'min_correct' => 6, 'timer_minutes' => 2],
        'Medium' => ['questions' => 15, 'boss_hp' => 15, 'min_correct' => 9, 'timer_minutes' => 3],
        'Hard' => ['questions' => 17, 'boss_hp' => 17, 'min_correct' => 11, 'timer_minutes' => 4],
    ];

    protected $xpService;
    protected $badgeService;

    public function __construct(XpService $xpService, BadgeService $badgeService)
    {
        $this->xpService = $xpService;
        $this->badgeService = $badgeService;
    }

    public function initSession(User $user, SoloRaid $soloRaid, string $level)
    {
        // 1. Check for existing active session
        $existingSession = SessionSolo::where('user_id', $user->id)
            ->where('solo_raid_id', $soloRaid->id)
            ->where('level', $level)
            ->whereNull('waktu_selesai')
            ->first();

        if ($existingSession) {
            return $existingSession;
        }

        // 2. Create new session
        $config = self::LEVEL_CONFIG[$level] ?? self::LEVEL_CONFIG['Easy'];
        
        return DB::transaction(function () use ($user, $soloRaid, $level, $config) {
            // Determine attempt number
            $attemptNumber = SessionSolo::where('user_id', $user->id)
                ->where('solo_raid_id', $soloRaid->id)
                ->where('level', $level)
                ->count() + 1;

            // Check if raid is active
            if ($soloRaid->status !== 'active') {
                throw new \Exception('Maaf, event ini belum aktif atau sudah dinonaktifkan.');
            }

            // Check global dates
            $now = now();
            if ($soloRaid->tanggal_mulai && $now->lt($soloRaid->tanggal_mulai)) {
                throw new \Exception('Maaf, event ini belum dimulai.');
            }
            if ($soloRaid->tanggal_selesai && $now->gt($soloRaid->tanggal_selesai)) {
                 throw new \Exception('Maaf, event ini sudah berakhir.');
            }
            
            // Check level specific dates if set
            $levelStart = $soloRaid->{strtolower($level) . '_date_start'};
            $levelEnd = $soloRaid->{strtolower($level) . '_date_end'};

            if ($levelStart && $now->lt($levelStart)) {
                 throw new \Exception("Level {$level} belum dimulai.");
            }
            if ($levelEnd && $now->gt($levelEnd)) {
                 throw new \Exception("Level {$level} sudah berakhir.");
            }

            $session = SessionSolo::create([
                'user_id' => $user->id,
                'solo_raid_id' => $soloRaid->id,
                'level' => $level,
                'waktu_mulai' => now(),
                'jumlah_soal' => $config['questions'],
                'boss_hp_awal' => $config['boss_hp'],
                'boss_hp_akhir' => $config['boss_hp'],
                'attempt_number' => $attemptNumber,
                'is_counted_research' => ($attemptNumber === 1),
                'is_first_attempt' => ($attemptNumber === 1),
            ]);

            // 3. Randomize and assign questions
            $this->assignQuestionsToSession($session, $soloRaid->question_bank_id, $level, $config['questions']);

            return $session;
        });
    }

    private function assignQuestionsToSession(SessionSolo $session, int $bankGroup, string $level, int $count)
    {
        // Fetch random questions from the selected bank and level
        $questions = QuestionBank::where('bank_group', $bankGroup)
            ->where('level', $level)
            ->inRandomOrder()
            ->limit($count)
            ->get();

        // If not enough questions, we might need to handle it (e.g., take all available)
        // For now, we assume there are enough questions.

        $order = 1;
        foreach ($questions as $question) {
            SessionAnswer::create([
                'session_id' => $session->id,
                'session_type' => 'solo',
                'question_id' => $question->id,
                'urutan_soal' => $order++,
                'attempt_number' => $session->attempt_number,
                'is_counted_research' => $session->is_counted_research,
                // 'answered_at' => null // Default is null now
            ]);
        }
    }

    public function submitAnswer(int $sessionId, array $data)
    {
        // 1. Validate session exists & not finished
        $session = SessionSolo::findOrFail($sessionId);
        if ($session->waktu_selesai) {
            return ['error' => 'Session already finished'];
        }

        // 2. Security: Check Deadline (Prevent Infinite Time Exploit)
        $config = self::LEVEL_CONFIG[$session->level] ?? self::LEVEL_CONFIG['Easy'];
        $deadline = $session->waktu_mulai->copy()->addMinutes($config['timer_minutes']);
        
        // Add small buffer (e.g. 5 seconds) for network latency
        if (now()->greaterThan($deadline->addSeconds(5))) {
             return ['error' => 'Time expired'];
        }

        // 3. Idempotency: Check if already answered (Prevent Double Scoring)
        $existingAnswer = SessionAnswer::where('session_id', $sessionId)
            ->where('question_id', $data['question_id'])
            ->first();

        if ($existingAnswer && $existingAnswer->jawaban_user) {
            // Already answered! Return existing result without re-processing damage.
            return [
                'is_correct' => $existingAnswer->is_correct,
                'damage' => 0, // No new damage
                'boss_hp_current' => $session->boss_hp_akhir,
                'boss_hp_max' => $session->boss_hp_awal,
                'feedback_message' => $existingAnswer->is_correct
                    ? "Jawaban sudah tersimpan. Boss HP: {$session->boss_hp_akhir}/{$session->boss_hp_awal}"
                    : "Jawaban salah tersimpan. Boss HP: {$session->boss_hp_akhir}/{$session->boss_hp_awal}"
            ];
        }

        // 4. Get question & validate answer
        $question = QuestionBank::findOrFail($data['question_id']);
        $isCorrect = $this->validateAnswer($question, $data['jawaban_user']);

        // 5. Calculate damage
        $damage = $isCorrect ? 1 : 0;

        // 6. Update boss HP
        $session->boss_hp_akhir = max(0, $session->boss_hp_akhir - $damage);
        $session->jumlah_benar += $isCorrect ? 1 : 0;
        $session->jumlah_salah += $isCorrect ? 0 : 1;
        $session->save();

        // 7. Save answer to session_answer
        if ($existingAnswer) {
            $existingAnswer->update([
                'jawaban_user' => $data['jawaban_user'],
                'is_correct' => $isCorrect,
                'waktu_jawab_detik' => $data['waktu_jawab_detik'] ?? 0,
                'answered_at' => now(),
            ]);
        } else {
            // Fallback if not found (should not happen if init works correctly)
            SessionAnswer::create([
                'session_id' => $sessionId,
                'session_type' => 'solo',
                'question_id' => $question->id,
                'urutan_soal' => $data['urutan_soal'],
                'jawaban_user' => $data['jawaban_user'],
                'is_correct' => $isCorrect,
                'waktu_jawab_detik' => $data['waktu_jawab_detik'] ?? 0,
                'attempt_number' => $session->attempt_number,
                'is_counted_research' => $session->is_counted_research,
                'answered_at' => now()
            ]);
        }

        // 8. Return response
        return [
            'is_correct' => $isCorrect,
            'damage' => $damage,
            'boss_hp_current' => $session->boss_hp_akhir,
            'boss_hp_max' => $session->boss_hp_awal,
            'feedback_message' => $isCorrect
                ? "Benar! Damage {$damage} ke Boss. Boss HP: {$session->boss_hp_akhir}/{$session->boss_hp_awal}"
                : "Salah! Boss HP tetap: {$session->boss_hp_akhir}/{$session->boss_hp_awal}"
        ];
    }

    public function finishSession($sessionId, $forcedEndTime = null)
    {
        $session = SessionSolo::with('user')->findOrFail($sessionId);
        
        if ($session->waktu_selesai) {
            return $session; // Already finished
        }

        // 1. Calculate duration & Validate End Time
        $config = self::LEVEL_CONFIG[$session->level] ?? self::LEVEL_CONFIG['Easy'];
        $deadline = $session->waktu_mulai->copy()->addMinutes($config['timer_minutes']);
        
        $endTime = $forcedEndTime ?? now();
        
        // Cap at deadline if exceeded
        if ($endTime->greaterThan($deadline)) {
            $endTime = $deadline;
        }

        $session->waktu_selesai = $endTime;
        $session->durasi_detik = $session->waktu_mulai->diffInSeconds($session->waktu_selesai);

        // 2. Calculate final score (percentage)
        $session->skor_akhir = $session->jumlah_soal > 0 
            ? ($session->jumlah_benar / $session->jumlah_soal) * 100 
            : 0;

        // 3. Determine if boss defeated
        $config = self::LEVEL_CONFIG[$session->level] ?? self::LEVEL_CONFIG['Easy'];
        $minDamage = $config['min_correct'];
        $session->boss_kalah = ($session->jumlah_benar >= $minDamage) || ($session->skor_akhir >= 100);

        // 4. Calculate XP using XpService
        $finalXP = $this->xpService->calculateSessionXP($session);
        $session->xp_diperoleh = $finalXP;

        $session->save();

        // 5. Update user XP & Check Level Up
        $levelUpResult = $this->xpService->addXP($session->user, $finalXP);

        // 6. Check badges
        $newBadges = $this->badgeService->checkAll($session->user, $session);

        return [
            'pemenang' => $session->boss_kalah ? 'Player' : 'Boss',
            'boss_kalah' => $session->boss_kalah,
            'skor_akhir' => $session->skor_akhir,
            'jumlah_benar' => $session->jumlah_benar,
            'jumlah_soal' => $session->jumlah_soal,
            'xp_diperoleh' => $finalXP,
            'durasi' => gmdate("H:i:s", $session->durasi_detik),
            'level_up' => $levelUpResult,
            'new_badges' => $newBadges
        ];
    }

    public function autoFinishExpiredSessions()
    {
        // Get all active sessions
        $activeSessions = SessionSolo::whereNull('waktu_selesai')->get();

        foreach ($activeSessions as $session) {
            $config = self::LEVEL_CONFIG[$session->level] ?? self::LEVEL_CONFIG['Easy'];
            $deadline = $session->waktu_mulai->addMinutes($config['timer_minutes']);

            if (now()->greaterThan($deadline)) {
                // Session expired, finish it with deadline as end time
                $this->finishSession($session->id, $deadline);
            }
        }
    }

    public function finishExpiredSessionsForUser($userId)
    {
        $activeSessions = SessionSolo::where('user_id', $userId)
            ->whereNull('waktu_selesai')
            ->get();

        $count = 0;
        foreach ($activeSessions as $session) {
            $config = self::LEVEL_CONFIG[$session->level] ?? self::LEVEL_CONFIG['Easy'];
            $deadline = $session->waktu_mulai->addMinutes($config['timer_minutes']);

            if (now()->greaterThan($deadline)) {
                $this->finishSession($session->id, $deadline);
                $count++;
            }
        }
        return $count;
    }

    private function validateAnswer($question, $userAnswer)
    {
        if ($question->tipe === 'multiple_choice') {
            // Map 'A', 'B', 'C', 'D' to the actual answer text
            $map = [
                'A' => $question->pilihan_a,
                'B' => $question->pilihan_b,
                'C' => $question->pilihan_c,
                'D' => $question->pilihan_d,
            ];

            // If user sent 'A', 'B', etc., map it. If they sent full text, use it directly.
            $userAnswerText = $map[strtoupper($userAnswer)] ?? $userAnswer;

            return trim(strtoupper($userAnswerText)) === trim(strtoupper($question->jawaban_benar));
        } else {
            // Short answer: case-insensitive, trim spaces
            return strtolower(trim($userAnswer)) === strtolower(trim($question->jawaban_benar));
        }
    }
}
