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
            // Standardize level input
            $level = ucfirst(strtolower($level));
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

        // Catatan: kunci jawaban (`jawaban_benar`) TIDAK boleh dikirim ke
        // client. Validasi jawaban dilakukan server-side di SoloBattleService.
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
            ];
        });

        $config = \App\Services\SoloBattleService::LEVEL_CONFIG[$session->level] ?? \App\Services\SoloBattleService::LEVEL_CONFIG['Easy'];
        $durationSeconds = $config['timer_minutes'] * 60;

        // Calculate absolute deadline. Auto-finalisasi sesi expired sudah
        // ditangani oleh middleware `finish.expired` + cron — di sini
        // cukup cek hasilnya: kalau session sudah ditutup, redirect.
        $deadline = $session->waktu_mulai->addSeconds($durationSeconds);

        if ($session->waktu_selesai) {
            return redirect()->route('solo.result', ['session' => $session->id]);
        }

        // For fallback/initial display (optional, but good for SSR)
        $timeRemaining = max(0, now()->diffInSeconds($deadline, false));

        // Get boss name based on level
        $bossName = $soloRaid->bossName($session->level);

        return view('solo.play', compact('soloRaid', 'session', 'questions', 'timeRemaining', 'deadline', 'bossName'));
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

        // Sesi yang masih aktif (belum di-finish oleh middleware/cron/endpoint
        // finish) berarti user belum waktunya melihat halaman result. Daripada
        // diam-diam memfinalisasi di GET, balikkan ke battle dan biarkan flow
        // normal yang menutup sesi.
        if (!$session->waktu_selesai) {
            return redirect()->route('solo.battle', [
                'soloRaid' => $session->solo_raid_id,
                'session'  => $session->id,
            ]);
        }

        $session->load('soloRaid');

        // Gunakan helper bossName() — kolom `boss_<level>_name` sudah di-drop
        // dari skema, akses langsung akan mengembalikan null.
        $bossName = $session->soloRaid?->bossName($session->level) ?? 'Boss';

        // Cache badges untuk 1 jam
        $allBadges = \Illuminate\Support\Facades\Cache::remember('badges_all_keyed', 3600, function () {
            return \App\Models\Badge::all()->keyBy('id');
        });

        return view('solo.result', compact('session', 'bossName', 'allBadges', 'battleResult'));
    }



    public function checkExpired()
    {
        $count = $this->service->finishExpiredSessionsForUser(auth()->id());
        return response()->json(['processed' => $count]);
    }
}
