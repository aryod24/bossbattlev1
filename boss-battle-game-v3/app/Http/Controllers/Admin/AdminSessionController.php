<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminSessionController extends Controller
{
    public function index()
    {
        $stats = [
            'session_solo' => DB::table('session_solo')->count(),
            'session_answer' => DB::table('session_answer')->count(),
            'event_participants' => Schema::hasTable('event_participant') ? DB::table('event_participant')->count() : 0,
        ];

        $soloSessions = \App\Models\SessionSolo::with(['user', 'soloRaid'])
            ->latest('waktu_mulai')
            ->paginate(10, ['*'], 'solo_page');

        $eventParticipants = [];
        if (Schema::hasTable('event_participant')) {
            $eventParticipants = \App\Models\EventParticipant::with(['user', 'event'])
                ->get()
                ->groupBy(function($item) {
                    return $item->event ? $item->event->nama_event : 'Unknown Event';
                });
        }

        return view('admin.sessions.index', compact('stats', 'soloSessions', 'eventParticipants'));
    }

    public function show($id)
    {
        $session = \App\Models\SessionSolo::with(['user', 'soloRaid', 'answers.question'])->findOrFail($id);
        return view('admin.sessions.show', compact('session'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'type' => 'required|in:solo,participant',
            'id' => 'required|integer'
        ]);

        if ($request->type === 'solo') {
            \App\Models\SessionSolo::destroy($request->id);
            return back()->with('success', 'Solo session deleted successfully.');
        } elseif ($request->type === 'participant') {
            \App\Models\EventParticipant::destroy($request->id);
            return back()->with('success', 'Event participant removed successfully.');
        }

        return back()->with('error', 'Invalid request.');
    }

    public function clear(Request $request)
    {
        $request->validate([
            'table' => 'required|string|in:session_solo,session_answer,event_participants,all'
        ]);

        $table = $request->table;

        if ($table === 'all') {
            Schema::disableForeignKeyConstraints();
            DB::table('session_answer')->truncate();
            DB::table('session_solo')->truncate();
            if (Schema::hasTable('event_participant')) {
                DB::table('event_participant')->truncate();
            }
            Schema::enableForeignKeyConstraints();
            
            return back()->with('success', 'All session data cleared successfully.');
        }

        // Handle table name mapping if needed (e.g. event_participants -> event_participant)
        $realTable = $table === 'event_participants' ? 'event_participant' : $table;

        if (Schema::hasTable($realTable)) {
            Schema::disableForeignKeyConstraints();
            DB::table($realTable)->truncate();
            Schema::enableForeignKeyConstraints();
            return back()->with('success', "Table {$realTable} cleared successfully.");
        }

        return back()->with('error', "Table {$realTable} not found.");
    }
}
