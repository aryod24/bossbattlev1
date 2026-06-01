<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    private array $excludedEmails = [
        'usertest@gmail.com',
    ];

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

        $allowedClasses = ['TI-2D', 'TI-2E'];
        $requestedClass = (string) request()->query('kelas', 'global');
        $leaderboardClass = in_array($requestedClass, $allowedClasses, true) ? $requestedClass : 'global';

        $baseQuery = User::whereRoleName('student')
            ->whereNotIn('email', $this->excludedEmails);

        if ($leaderboardClass !== 'global') {
            $baseQuery->where('kelas', $leaderboardClass);
        }

        // Fetch top students ordered by XP (excluding test accounts)
        $users = (clone $baseQuery)
            ->orderBy('total_xp', 'desc')
            ->orderBy('nama', 'asc')
            ->withCount(['sessionSolos as total_games' => $excludePretest])
            ->withSum(['sessionSolos as total_wins' => function ($query) use ($excludePretest) {
                $excludePretest($query);
                $query->where('boss_kalah', true);
            }], 'boss_kalah')
            ->withAvg(['sessionSolos as avg_score' => $excludePretest], 'skor_akhir')
            ->paginate(10)
            ->appends(['kelas' => $leaderboardClass]);

        // Process users to calculate rates and ranks
        $startRank = ($users->currentPage() - 1) * $users->perPage() + 1;

        $rankings = $users->getCollection()->map(function ($user, $index) use ($startRank) {
            $user->rank = $startRank + $index;
            
            // Calculate Win Rate
            $totalGames = $user->total_games ?? 0;
            $totalWins = $user->total_wins ?? 0;
            $user->win_rate = $totalGames > 0 ? round(($totalWins / $totalGames) * 100) : 0;
            
            // Format Avg Score
            $user->avg_score_formatted = number_format($user->avg_score ?? 0, 0);

            return $user;
        });

        // Replace collection with processed one
        $users->setCollection($rankings);

        // Current User for Sidebar
        $currentUser = Auth::user();

        // Calculate current user's rank (excluding test accounts)
        $currentUserRank = (clone $baseQuery)
            ->where('total_xp', '>', $currentUser->total_xp)
            ->count() + 1;
            
        // Get user above (target) if not first (excluding test accounts)
        $targetUser = null;
        if ($currentUserRank > 1) {
            $targetUser = (clone $baseQuery)
                ->where('total_xp', '>', $currentUser->total_xp)
                ->orderBy('total_xp', 'asc')
                ->first();
        }

        // Get Top 3 explicitly for podium (excluding test accounts)
        $topUsers = (clone $baseQuery)
            ->orderBy('total_xp', 'desc')
            ->orderBy('nama', 'asc')
            ->take(3)
            ->get();

        return view('leaderboard.index', compact(
            'users',
            'currentUser',
            'currentUserRank',
            'targetUser',
            'topUsers',
            'leaderboardClass'
        ));
    }
}
