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
        $badges = Badge::where('is_system', true)->get();

        foreach ($badges as $badge) {
            $isUnlocked = false;

            // 1. Dynamic Check via Requirements JSON
            if (!empty($badge->requirements)) {
                $isUnlocked = $this->checkDynamicRequirements($user, $badge->requirements, $currentSession);
            } 
            // 2. Legacy Fallback via Slug
            else {
                switch ($badge->slug) {
                    case self::SLUG_BOSS_NOVICE:
                        $isUnlocked = $this->checkBossNovice($user);
                        break;
                    case self::SLUG_BOSS_VETERAN:
                        $isUnlocked = $this->checkBossVeteran($user);
                        break;
                    case self::SLUG_TOP_3_CHALLENGER:
                        $isUnlocked = $this->checkTop3Challenger($user);
                        break;
                    case self::SLUG_PERFECT_STRIKE:
                        if ($currentSession) {
                            $isUnlocked = $this->checkPerfectStrike($user, $currentSession);
                        }
                        break;
                    case self::SLUG_EVENT_WARRIOR:
                        $isUnlocked = $this->checkEventWarrior($user);
                        break;
                }
            }

            if ($isUnlocked) {
                 if ($this->unlockBadge($user, $badge)) {
                     $unlockedBadges[] = $badge;
                 }
            }
        }
        
        return $unlockedBadges;
    }

    /**
     * Check dynamic requirements from JSON
     */
    public function checkDynamicRequirements(User $user, array $requirements, $currentSession = null)
    {
        // Support single rule or array of rules (implicit AND)
        $rules = isset($requirements['type']) ? [$requirements] : ($requirements['rules'] ?? $requirements);

        foreach ($rules as $rule) {
            $type = $rule['type'] ?? '';
            $pass = false;

            switch ($type) {
                // Check for N victories (optionally unique raids)
                case 'solo_victory_count':
                    $query = SessionSolo::query()
                        ->where('user_id', $user->id)
                        ->where('boss_kalah', true);
                    
                    if (isset($rule['difficulty'])) {
                         $query->whereIn('level', (array)$rule['difficulty']);
                    }
                    
                    if (!empty($rule['unique_raid'])) {
                        $count = $query->distinct('solo_raid_id')->count('solo_raid_id');
                    } else {
                        $count = $query->count();
                    }
                    
                    $target = $rule['count'] ?? 1;
                    $pass = $count >= $target;
                    break;
                
                // Check for victories in specific set of levels (e.g. Easy AND Medium AND Hard)
                case 'complete_difficulties':
                    $targetLevels = $rule['levels'] ?? ['Easy', 'Medium', 'Hard'];
                    $pass = true;
                    foreach ($targetLevels as $lvl) {
                        $hasWin = SessionSolo::where('user_id', $user->id)
                            ->where('level', $lvl)
                            ->where('boss_kalah', true)
                            ->exists();
                        if (!$hasWin) {
                            $pass = false;
                            break;
                        }
                    }
                    break;

                case 'event_participation_count':
                    $count = EventParticipant::where('user_id', $user->id)->count();
                    $target = $rule['count'] ?? 1;
                    $pass = $count >= $target;
                    break;
                    
                case 'perfect_score':
                    // Requires current session to be perfect
                    if (!$currentSession) {
                        $pass = false; 
                        break; 
                    }
                    // Reuse existing logic
                    $pass = $this->checkPerfectStrike($user, $currentSession);
                    break;

                default: 
                    // If unknown rule, fail safe
                    $pass = false;
            }

            if (!$pass) return false;
        }

        return true;
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
