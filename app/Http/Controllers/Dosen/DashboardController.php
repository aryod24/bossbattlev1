<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\SoloRaid;
use App\Models\QuestionBank;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Statistik global (sesuai akses dosen di halaman list).
        $totalEvents     = SoloRaid::count();
        $totalBanks      = QuestionBank::distinct('bank_group')->count('bank_group');
        $totalQuestions  = QuestionBank::count();

        // Statistik kontribusi pribadi.
        $myEvents    = SoloRaid::where('created_by', $user->id)->count();
        $myQuestions = QuestionBank::where('created_by', $user->id)->count();
        $myBanks     = QuestionBank::where('created_by', $user->id)
            ->distinct('bank_group')
            ->count('bank_group');

        // Event terbaru di sistem (bukan hanya milik dosen ini).
        $recentEvents = SoloRaid::with('creator:id,nama')
            ->latest()
            ->take(5)
            ->get();

        // --- Talent Scout Data ---
        $sort = request('sort', 'avg_score');
        
        $excludePretest = function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->whereNull('is_pretest')
                    ->orWhere('is_pretest', false);
            })->where(function ($subQuery) {
                $subQuery->whereNull('jumlah_soal')
                    ->orWhere('jumlah_soal', '!=', 30);
            });
        };

        $studentsQuery = \App\Models\User::whereRoleName('student')
            ->whereNotIn('email', ['usertest@gmail.com'])
            ->withCount(['sessionSolos as total_games' => $excludePretest])
            ->withSum(['sessionSolos as total_wins' => function ($query) use ($excludePretest) {
                $excludePretest($query);
                $query->where('boss_kalah', true);
            }], 'boss_kalah')
            ->withAvg(['sessionSolos as avg_score' => $excludePretest], 'skor_akhir');

        if ($sort === 'total_xp') {
            $studentsQuery->orderBy('total_xp', 'desc');
        } elseif ($sort === 'pretest_score') {
            $studentsQuery->orderByRaw('COALESCE(pretest_score, 0) desc');
        }

        $allStudents = $studentsQuery->get()->map(function ($std) {
            $totalGames = $std->total_games ?? 0;
            $totalWins = $std->total_wins ?? 0;
            $std->win_rate = $totalGames > 0 ? round(($totalWins / $totalGames) * 100) : 0;
            $std->avg_score_val = round($std->avg_score ?? 0);
            return $std;
        });

        if ($sort === 'avg_score') {
            $allStudents = $allStudents->sortByDesc('avg_score_val')->values();
        } elseif ($sort === 'win_rate') {
            $allStudents = $allStudents->sortByDesc('win_rate')->values();
        }

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $topStudents = new \Illuminate\Pagination\LengthAwarePaginator(
            $allStudents->forPage($page, $perPage)->values(),
            $allStudents->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $topStudents->appends(request()->query());

        // Assign rank numbers based on the sorted collection
        $startRank = ($topStudents->currentPage() - 1) * $topStudents->perPage() + 1;
        foreach ($topStudents->items() as $index => $std) {
            $std->rank = $startRank + $index;
        }

        return view('dosen.dashboard', compact(
            'totalEvents', 'totalBanks', 'totalQuestions',
            'myEvents', 'myBanks', 'myQuestions',
            'recentEvents', 'topStudents', 'sort'
        ));
    }
}
