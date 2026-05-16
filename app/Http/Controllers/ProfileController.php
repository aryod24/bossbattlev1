<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $excludePretest = function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->whereNull('is_pretest')
                    ->orWhere('is_pretest', false);
            })->where(function ($subQuery) {
                $subQuery->whereNull('jumlah_soal')
                    ->orWhere('jumlah_soal', '!=', 30);
            });
        };

        $totalGames = $user->sessionSolos()->where($excludePretest)->count();
        $totalWins = $user->sessionSolos()->where($excludePretest)->where('boss_kalah', true)->count();
        $avgScore = $user->sessionSolos()->where($excludePretest)->avg('skor_akhir');
        $winRate = $totalGames > 0 ? round(($totalWins / $totalGames) * 100) : 0;
        $avgScoreFormatted = number_format($avgScore ?? 0, 0);
        
        // Load user's unlocked badges
        $unlockedBadges = $user->userBadges->keyBy('badge_id');
        
        // Get all badge definitions from DB (cached)
        $allBadges = \Illuminate\Support\Facades\Cache::remember('badges_all', 3600, function () {
            return \App\Models\Badge::all();
        });

        return view('profile.mhs', [
            'user' => $user,
            'unlockedBadges' => $unlockedBadges,
            'allBadges' => $allBadges,
            'winRate' => $winRate,
            'avgScoreFormatted' => $avgScoreFormatted,
            'totalGames' => $totalGames,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
