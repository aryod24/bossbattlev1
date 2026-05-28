<?php

namespace App\Http\Controllers;

use App\Models\SessionSolo;
use App\Models\QuestionBank;
use App\Models\User;
use App\Services\PreTestService;
use App\Services\SoloBattleService;
use Illuminate\Http\Request;

class PreTestController extends Controller
{
    protected PreTestService $preTestService;
    protected SoloBattleService $battleService;

    public function __construct(PreTestService $preTestService, SoloBattleService $battleService)
    {
        $this->preTestService = $preTestService;
        $this->battleService = $battleService;
    }

    /**
     * Show pre-test intro page or redirect if already completed.
     */
    public function index()
    {
        $user = auth()->user();

        // If user already completed pre-test, redirect to solo index
        if ($user->hasCompletedPretest()) {
            return redirect()->route('solo.index')->with('info', 'Anda sudah menyelesaikan pre-test.');
        }

        // Check for active pre-test session
        $activeSession = SessionSolo::where('user_id', $user->id)
            ->where('is_pretest', true)
            ->whereNull('waktu_selesai')
            ->first();

        return view('pretest.index', compact('activeSession'));
    }

    /**
     * Initialize/resume pre-test session.
     */
    public function start()
    {
        $user = auth()->user();

        if ($user->hasCompletedPretest()) {
            return redirect()->route('solo.index');
        }

        try {
            $session = $this->preTestService->initPreTest($user);
            return redirect()->route('pretest.play', ['session' => $session->id]);
        } catch (\Throwable $e) {
            return redirect()->route('pretest.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Play the pre-test (quiz interface without boss visual).
     */
    public function play(SessionSolo $session)
    {
        $user = auth()->user();

        // Verify ownership
        if ($session->user_id !== $user->id || !$session->is_pretest) {
            abort(403);
        }

        // If already finished, redirect to result
        if ($session->waktu_selesai) {
            return redirect()->route('pretest.result', ['session' => $session->id]);
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
                'level' => $answer->question->level,
            ];
        });

        // Pre-test timer: 30 minutes for 30 questions. Auto-finalisasi
        // sesi expired sudah ditangani middleware `finish.expired` + cron;
        // di sini cukup cek apakah sesi sudah ditutup, lalu redirect.
        $durationSeconds = 30 * 60;
        $deadline = $session->waktu_mulai->addSeconds($durationSeconds);

        if ($session->waktu_selesai) {
            return redirect()->route('pretest.result', ['session' => $session->id]);
        }

        $timeRemaining = max(0, now()->diffInSeconds($deadline, false));

        return view('pretest.play', compact('session', 'questions', 'timeRemaining', 'deadline'));
    }

    /**
     * Submit an answer for pre-test (reuses SoloBattleService logic).
     */
    public function action(Request $request)
    {
        $data = $request->validate([
            'session_id' => 'required|integer',
            'question_id' => 'required|integer',
            'jawaban_user' => 'required',
            'waktu_jawab_detik' => 'integer',
            'urutan_soal' => 'integer'
        ]);

        // Reuse the existing answer submission logic, but guard for unexpected exceptions
        try {
            $result = $this->battleService->submitAnswer($data['session_id'], $data);
            return response()->json($result);
        } catch (\Throwable $e) {
            logger()->error('PreTest action error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Finish pre-test and show placement result.
     */
    public function finish(Request $request, SessionSolo $session)
    {
        $user = auth()->user();

        if ($session->user_id !== $user->id || !$session->is_pretest) {
            abort(403);
        }

        $result = $this->preTestService->finishPreTest($session);
        session()->flash('pretest_result', $result);
        return response()->json($result);
    }

    /**
     * Show pre-test result with section placement.
     */
    public function result(SessionSolo $session)
    {
        $user = auth()->user();

        if ($session->user_id !== $user->id || !$session->is_pretest) {
            abort(403);
        }

        // Auto-finalisasi sudah ditangani middleware `finish.expired` + cron.
        // Kalau session masih aktif berarti user terlalu cepat sampai sini —
        // kembalikan ke halaman play, jangan finalisasi diam-diam di GET.
        if (!$session->waktu_selesai) {
            return redirect()->route('pretest.play', ['session' => $session->id]);
        }

        $pretestResult = session('pretest_result') ?? [
            'score' => $session->skor_akhir,
            'jumlah_benar' => $session->jumlah_benar,
            'jumlah_soal' => $session->jumlah_soal,
            'section' => $user->current_section,
        ];

        return view('pretest.result', compact('session', 'pretestResult'));
    }
}
