<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminSessionController extends Controller
{
    protected $soloBattleService;

    public function __construct(\App\Services\SoloBattleService $soloBattleService)
    {
        $this->soloBattleService = $soloBattleService;
    }

    public function index()
    {
        // Auto-finish expired sessions before displaying
        $this->soloBattleService->autoFinishExpiredSessions();

        $stats = [
            'session_solo' => DB::table('session_solo')->count(),
            'session_answer' => DB::table('session_answer')->count(),
        ];

        $soloSessions = \App\Models\SessionSolo::with(['user', 'soloRaid'])
            ->latest('waktu_mulai')
            ->paginate(10, ['*'], 'solo_page');

        // Mark sessions that look like pre-tests (30 questions) so frontend can treat them accordingly
        $soloSessions->getCollection()->transform(function ($session) {
            if ((int) ($session->jumlah_soal ?? 0) === 30) {
                $session->is_pretest = true;
                $session->boss_hp_awal = null;
                $session->boss_hp_akhir = null;
            }
            return $session;
        });

        return view('admin.sessions.index', compact('stats', 'soloSessions'));
    }

    public function show($id)
    {
        $session = \App\Models\SessionSolo::with(['user', 'soloRaid', 'answers.question'])->findOrFail($id);
        // If this session is a pre-test (30 questions), mark and hide boss HP for frontend
        if ((int) ($session->jumlah_soal ?? 0) === 30) {
            $session->is_pretest = true;
            $session->boss_hp_awal = null;
            $session->boss_hp_akhir = null;
        }
        return view('admin.sessions.show', compact('session'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'type' => 'required|in:solo',
            'id' => 'required|integer'
        ]);

        \App\Models\SessionSolo::destroy($request->id);
        return back()->with('success', 'Solo session deleted successfully.');
    }

    public function clear(Request $request)
    {
        $request->validate([
            'table' => 'required|string|in:session_solo,session_answer,all'
        ]);

        $table = $request->table;

        if ($table === 'all') {
            Schema::disableForeignKeyConstraints();
            DB::table('session_answer')->truncate();
            DB::table('session_solo')->truncate();
            Schema::enableForeignKeyConstraints();

            return back()->with('success', 'All session data cleared successfully.');
        }

        if (Schema::hasTable($table)) {
            Schema::disableForeignKeyConstraints();
            DB::table($table)->truncate();
            Schema::enableForeignKeyConstraints();
            return back()->with('success', "Table {$table} cleared successfully.");
        }

        return back()->with('error', "Table {$table} not found.");
    }

    public function checkExpired()
    {
        $this->soloBattleService->autoFinishExpiredSessions();
        return back()->with('success', 'Expired sessions check completed.');
    }
}
