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
            'event_participants' => Schema::hasTable('event_participants') ? DB::table('event_participants')->count() : 0,
        ];

        return view('admin.sessions.index', compact('stats'));
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
            if (Schema::hasTable('event_participants')) {
                DB::table('event_participants')->truncate();
            }
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
}
