<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionBank;
use App\Models\SessionAnswer;
use App\Models\SoloRaid;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalUsers     = User::count();
        $activeEvents   = SoloRaid::where('status', 'active')->count();
        $totalQuestions = QuestionBank::count();

        // Total jawaban yang benar-benar disubmit oleh user
        // (bukan hanya placeholder soal yang di-assign saat init session).
        $totalAnswers = SessionAnswer::whereNotNull('jawaban_user')->count();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeEvents',
            'totalQuestions',
            'totalAnswers',
            'recentUsers'
        ));
    }
}
