<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SoloRaid;
use App\Models\QuestionBank;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        
        // Stats untuk Dosen (filtered by created_by)
        $myEvents = SoloRaid::where('created_by', $user->id)->count();
        $myQuestions = QuestionBank::where('created_by', $user->id)->count();
        
        // Recent Events created by Dosen
        $recentEvents = SoloRaid::where('created_by', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dosen.dashboard', compact(
            'myEvents',
            'myQuestions',
            'recentEvents'
        ));
    }
}
