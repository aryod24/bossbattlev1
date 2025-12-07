<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserBadge;
use App\Models\Badge;
use App\Models\SessionSolo;
use App\Models\EventParticipant;
use Carbon\Carbon;

class BadgeService
{
    // Badge Slugs
    public const SLUG_BOSS_NOVICE = 'boss-novice';
    public const SLUG_BOSS_VETERAN = 'boss-veteran';
    public const SLUG_TOP_3_CHALLENGER = 'top-3-challenger';
    public const SLUG_PERFECT_STRIKE = 'perfect-strike';
    public const SLUG_EVENT_WARRIOR = 'event-warrior';

    /**
     * Check all badges for a user.
     * 
     * @param User $user
     * @param mixed $currentSession - Instance of SessionSolo or EventParticipant (optional)
     * @return array - List of newly unlocked badge models
     */
    public function checkAll(User $user, $currentSession = null)
    {
        $unlockedBadges = [];

        // Fetch all system badges to check against
        // Optimized: In a real app, you might want to cache this or only fetch needed ones
        // But since we only have 5 system badges, fetching all is fine.
        $systemBadges = Badge::where('is_system', true)->get()->keyBy('slug');

        if ($this->checkBossNovice($user) && isset($systemBadges[self::SLUG_BOSS_NOVICE])) {
           if ($this->unlockBadge($user, $systemBadges[self::SLUG_BOSS_NOVICE])) {
               $unlockedBadges[] = $systemBadges[self::SLUG_BOSS_NOVICE];
           }
        }

        if ($this->checkBossVeteran($user) && isset($systemBadges[self::SLUG_BOSS_VETERAN])) {
           if ($this->unlockBadge($user, $systemBadges[self::SLUG_BOSS_VETERAN])) {
               $unlockedBadges[] = $systemBadges[self::SLUG_BOSS_VETERAN];
           }
        }
        
        if ($this->checkTop3Challenger($user) && isset($systemBadges[self::SLUG_TOP_3_CHALLENGER])) {
             if ($this->unlockBadge($user, $systemBadges[self::SLUG_TOP_3_CHALLENGER])) {
                 $unlockedBadges[] = $systemBadges[self::SLUG_TOP_3_CHALLENGER];
             }
        }

        if ($currentSession && $this->checkPerfectStrike($user, $currentSession) && isset($systemBadges[self::SLUG_PERFECT_STRIKE])) {
             if ($this->unlockBadge($user, $systemBadges[self::SLUG_PERFECT_STRIKE])) {
                 $unlockedBadges[] = $systemBadges[self::SLUG_PERFECT_STRIKE];
             }
        }

        if ($this->checkEventWarrior($user) && isset($systemBadges[self::SLUG_EVENT_WARRIOR])) {
             if ($this->unlockBadge($user, $systemBadges[self::SLUG_EVENT_WARRIOR])) {
                 $unlockedBadges[] = $systemBadges[self::SLUG_EVENT_WARRIOR];
             }
        }
        
        return $unlockedBadges;
    }

    // 1. Boss Novice: Kalahkan 1 boss any level
    public function checkBossNovice(User $user)
    {
        // Check Solo Sessions
        $soloWin = SessionSolo::where('user_id', $user->id)
            ->where('boss_kalah', true)
            ->exists();
            
        if ($soloWin) return true;

        // Check Event Participants
        $eventWin = EventParticipant::where('user_id', $user->id)
             ->where('boss_kalah', true)
             ->exists();
             
        return $eventWin;
    }

    // 2. Boss Veteran: Win Easy, Medium, AND Hard in Solo
    public function checkBossVeteran(User $user)
    {
        $levels = ['Easy', 'Medium', 'Hard'];
        foreach ($levels as $level) {
            $hasWin = SessionSolo::where('user_id', $user->id)
                ->where('level', $level)
                ->where('boss_kalah', true)
                ->exists();
            if (!$hasWin) return false;
        }
        return true;
    }

    // 3. Top 3 Challenger: Rank 1-3 in Event
    public function checkTop3Challenger(User $user)
    {
        return EventParticipant::where('user_id', $user->id)
            ->whereIn('peringkat_leaderboard', [1, 2, 3])
            ->exists();
    }

    // 4. Perfect Strike: 100% correct in a session
    public function checkPerfectStrike(User $user, $session)
    {
        if ($session->user_id !== $user->id) return false;
        
        if ($session->jumlah_soal == 0) return false;

        return $session->jumlah_benar === $session->jumlah_soal;
    }

    // 5. Event Warrior: Join 2+ events
    public function checkEventWarrior(User $user)
    {
        return EventParticipant::where('user_id', $user->id)->count() >= 2;
    }

    /**
     * Unlock a badge for a user safely.
     * Returns true if newly unlocked, false if already owned.
     * 
     * @param User $user
     * @param Badge|int $badge - Badge model instance or ID
     */
    public function unlockBadge(User $user, $badge)
    {
        $badgeId = ($badge instanceof Badge) ? $badge->id : $badge;

        $exists = UserBadge::where('user_id', $user->id)
            ->where('badge_id', $badgeId)
            ->exists();

        if ($exists) {
            return false;
        }

        UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badgeId,
            'unlock_date' => Carbon::now()
        ]);

        return true;
    }
}
