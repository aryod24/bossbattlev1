<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Load user's unlocked badges
        // We ensure userBadges is loaded. If via relationship, it works.
        $unlockedBadges = $user->userBadges->keyBy('badge_id');
        
        // Get all badge definitions
        $allBadges = BadgeService::BADGE_DETAILS;
        
        return view('profile.badges', compact('user', 'unlockedBadges', 'allBadges'));
    }
}
