<?php

namespace App\Http\Controllers;

use App\Models\SoloRaid;
use App\Models\SessionSolo;
use Illuminate\Http\Request;

class SoloBattleController extends Controller
{
    protected $service;

    public function __construct(\App\Services\SoloBattleService $service)
    {
        $this->service = $service;
    }

    public function init(SoloRaid $soloRaid, $level)
    {
        try {
            $session = $this->service->initSession(auth()->user(), $soloRaid, $level);
            return redirect()->route('solo.battle', ['soloRaid' => $soloRaid->id, 'session' => $session->id]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index(SoloRaid $soloRaid, SessionSolo $session = null)
    {
        if ($session) {
            // Verify ownership and raid
            if ($session->user_id !== auth()->id() || $session->solo_raid_id !== $soloRaid->id) {
                abort(403);
            }
            // Verify active
            if ($session->waktu_selesai) {
                 return redirect()->route('solo.result', ['session' => $session->id]);
            }
        } else {
            // Fallback: Get latest active session
            $session = SessionSolo::where('user_id', auth()->id())
                ->where('solo_raid_id', $soloRaid->id)
                ->whereNull('waktu_selesai')
                ->latest()
                ->first();
        }

        if (!$session) {
            return redirect()->route('solo.index')->with('error', 'No active battle found. Please start a level.');
        }

        $questions = $session->answers()->with('question')->orderBy('urutan_soal')->get()->map(function($answer) {
            return [
                'id' => $answer->question->id,
                'soal_text' => $answer->question->soal_text,
                'tipe' => $answer->question->tipe,
                'pilihan_a' => $answer->question->pilihan_a,
                'pilihan_b' => $answer->question->pilihan_b,
                'pilihan_c' => $answer->question->pilihan_c,
                'pilihan_d' => $answer->question->pilihan_d,
                'urutan' => $answer->urutan_soal,
                'is_answered' => $answer->jawaban_user !== null,
                'jawaban_benar' => $answer->question->jawaban_benar, // For testing purposes
            ];
        });

        $config = \App\Services\SoloBattleService::LEVEL_CONFIG[$session->level] ?? \App\Services\SoloBattleService::LEVEL_CONFIG['Easy'];
        $durationSeconds = $config['timer_minutes'] * 60;
        
        // Calculate absolute deadline
        $deadline = $session->waktu_mulai->addSeconds($durationSeconds);
        
        // Check if expired
        if (now()->greaterThan($deadline)) {
            $result = $this->service->finishSession($session->id, $deadline);
            return redirect()->route('solo.result', ['session' => $session->id])->with('battle_result', $result);
        }
        
        // For fallback/initial display (optional, but good for SSR)
        $timeRemaining = max(0, now()->diffInSeconds($deadline, false));

        // Get boss name based on level
        $bossName = match($session->level) {
            'Easy' => $soloRaid->boss_easy_name,
            'Medium' => $soloRaid->boss_medium_name,
            'Hard' => $soloRaid->boss_hard_name,
            default => 'Boss'
        };

        return \Inertia\Inertia::render('Solo/Play', compact('soloRaid', 'session', 'questions', 'timeRemaining', 'deadline', 'bossName'));
    }

    public function action(Request $request, SoloRaid $soloRaid)
    {
        $data = $request->validate([
            'session_id' => 'required|integer',
            'question_id' => 'required|integer',
            'jawaban_user' => 'required',
            'waktu_jawab_detik' => 'integer',
            'urutan_soal' => 'integer'
        ]);
        
        $result = $this->service->submitAnswer($data['session_id'], $data);
        return response()->json($result);
    }

    public function finish(Request $request, $sessionId)
    {
        $result = $this->service->finishSession($sessionId);
        session()->flash('battle_result', $result);
        return response()->json($result);
    }

    public function result(SessionSolo $session)
    {
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }
        
        $battleResult = session('battle_result');
        
        // Force finish if not already finished (e.g. user manually navigated here)
        if (!$session->waktu_selesai) {
            $battleResult = $this->service->finishSession($session->id);
            $session->refresh();
        }
        
        $session->load('soloRaid');
        $bossName = $session->soloRaid->{'boss_'.strtolower($session->level).'_name'};
        $allBadges = \App\Models\Badge::all()->keyBy('id'); // Use ID as key for JS mapping
        
        return view('solo.result', compact('session', 'bossName', 'allBadges', 'battleResult'));
    }



    public function checkExpired()
    {
        $count = $this->service->finishExpiredSessionsForUser(auth()->id());
        return response()->json(['processed' => $count]);
    }
}
