<?php

namespace App\Services;

use App\Models\User;
use App\Models\SessionSolo;

class XpService
{
    public const LEVEL_THRESHOLDS = [
        1 => 0,
        2 => 100,
        3 => 250,
        4 => 450,
        5 => 700,
    ];

    /**
     * Add XP to user and check for level up.
     */
    public function addXP(User $user, int $xp)
    {
        $user->increment('total_xp', $xp);
        return $this->checkLevelUp($user);
    }

    /**
     * Check if user's new XP total warrants a level up.
     */
    public function checkLevelUp(User $user)
    {
        $currentXp = $user->total_xp;
        $currentLevel = $user->level;
        $newLevel = 1;

        // Determine appropriate level based on XP
        foreach (self::LEVEL_THRESHOLDS as $level => $threshold) {
             if ($currentXp >= $threshold) {
                 $newLevel = max($newLevel, $level);
             }
        }

        // Only process level up if new level is higher than current
        if ($newLevel > $currentLevel) {
            $user->update(['level' => $newLevel]);
            return [
                'leveled_up' => true,
                'new_level' => $newLevel,
                'message' => "Level Up! 🎉 Anda sekarang Level $newLevel!"
            ];
        }

        return ['leveled_up' => false];
    }

    /**
     * Calculate XP for a solo session including bonuses and penalties.
     */
    public function calculateSessionXP(SessionSolo $session)
    {
        // 1. Config based on level
        $levelConfig = [
            'Easy' => ['xp_per_soal' => 10, 'boss_bonus' => 50],
            'Medium' => ['xp_per_soal' => 15, 'boss_bonus' => 75],
            'Hard' => ['xp_per_soal' => 20, 'boss_bonus' => 100],
        ];

        $config = $levelConfig[$session->level] ?? $levelConfig['Easy'];

        // 2. Base XP from correct answers
        $baseXp = $session->jumlah_benar * $config['xp_per_soal'];

        // 3. Boss Bonus
        $bossBonus = $session->boss_kalah ? $config['boss_bonus'] : 0;

        $totalRawXp = $baseXp + $bossBonus;

        // 4. Retry Penalty
        // Attempt 1: 100%, Attempt 2: 50%, Attempt 3+: 0%
        $multiplier = 0;
        if ($session->attempt_number == 1) {
            $multiplier = 1.0;
        } elseif ($session->attempt_number == 2) {
            $multiplier = 0.5;
        } else {
            $multiplier = 0.0;
        }

        return (int) round($totalRawXp * $multiplier);
    }
}
