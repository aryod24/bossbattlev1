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

        return view('dosen.dashboard', compact(
            'totalEvents', 'totalBanks', 'totalQuestions',
            'myEvents', 'myBanks', 'myQuestions',
            'recentEvents'
        ));
    }
}
