<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SoloRaid;
use App\Models\QuestionBank; // Assuming this model exists for questions
// use App\Models\SessionSolo; // Uncomment if you want to count total answers from sessions

class DashboardController extends Controller
{
    public function __invoke()
    {
        // 1. Fetch Real Data
        $totalUsers = User::count();
        // Assuming 'is_active' column exists or logic for active events. 
        // If not, we just count all SoloRaids for now.
        $activeEvents = SoloRaid::count(); 
        
        // Count questions (if QuestionBank model exists, otherwise 0)
        $totalQuestions = class_exists(QuestionBank::class) ? QuestionBank::count() : 0;
        
        // Total Answers (Simulated or fetched)
        // $totalAnswers = SessionSolo::sum('jumlah_soal'); 
        $totalAnswers = 1500; // Placeholder until SessionSolo logic is confirmed

        // Recent Users (last 5)
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
