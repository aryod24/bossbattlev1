<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        $excludePretest = function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->whereNull('is_pretest')
                    ->orWhere('is_pretest', false);
            })->where(function ($subQuery) {
                $subQuery->whereNull('jumlah_soal')
                    ->orWhere('jumlah_soal', '!=', 30);
            });
        };

        // Fetch top students ordered by XP
        $users = User::where('role', 'student')
            ->orderBy('total_xp', 'desc')
            ->withCount(['sessionSolos as total_games' => $excludePretest])
            ->withSum(['sessionSolos as total_wins' => function ($query) use ($excludePretest) {
                $excludePretest($query);
                $query->where('boss_kalah', true);
            }], 'boss_kalah') // Assuming boss_kalah is boolean (1/0), sum works as count of true
            ->withAvg(['sessionSolos as avg_score' => $excludePretest], 'skor_akhir')
            ->paginate(10);

        // Process users to calculate rates and ranks
        // Since pagination is used, rank needs to be calculated based on page
        $startRank = ($users->currentPage() - 1) * $users->perPage() + 1;

        $rankings = $users->getCollection()->map(function ($user, $index) use ($startRank) {
            $user->rank = $startRank + $index;
            
            // Calculate Win Rate
            $totalGames = $user->total_games ?? 0;
            $totalWins = $user->total_wins ?? 0; // sum of booleans
            $user->win_rate = $totalGames > 0 ? round(($totalWins / $totalGames) * 100) : 0;
            
            // Format Avg Score
            $user->avg_score_formatted = number_format($user->avg_score ?? 0, 0);

            return $user;
        });

        // Replace collection with processed one
        $users->setCollection($rankings);

        // Current User for Sidebar
        $currentUser = Auth::user();
        // Calculate current user's rank efficiently
        $currentUserRank = User::where('role', 'student')
            ->where('total_xp', '>', $currentUser->total_xp)
            ->count() + 1;
            
        // Get user above (target) if not first
        $targetUser = null;
        if ($currentUserRank > 1) {
            $targetUser = User::where('role', 'student')
                ->where('total_xp', '>', $currentUser->total_xp)
                ->orderBy('total_xp', 'asc') // Lowest XP higher than current user
                ->first();
        }

        // Get Top 3 explicitly for podium
        $topUsers = User::where('role', 'student')
            ->orderBy('total_xp', 'desc')
            ->take(3)
            ->get();

        return view('leaderboard.index', compact('users', 'currentUser', 'currentUserRank', 'targetUser', 'topUsers'));
    }
}
