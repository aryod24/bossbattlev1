<?php

namespace App\Http\Controllers;

use App\Models\SoloRaid;
use Illuminate\Http\Request;

class SoloRaidController extends Controller
{
    public function index()
    {
        $raids = SoloRaid::where('status', 'active')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->paginate(6);
            
        return view('solo.index', compact('raids'));
    }

    public function map(SoloRaid $soloRaid)
    {
        // Period validation
        if (now()->lt($soloRaid->tanggal_mulai) || now()->gt($soloRaid->tanggal_selesai)) {
            return redirect()->route('solo.index')->with('error', 'Periode raid ini belum dimulai atau sudah berakhir.');
        }

        // Check for active session (any raid)
        $activeSession = \App\Models\SessionSolo::where('user_id', auth()->id())
            ->whereNull('waktu_selesai')
            ->with('soloRaid')
            ->first();

        // Fetch User Stats
        $userStats = [
            'attempts' => \App\Models\SessionSolo::where('user_id', auth()->id())
                ->where('solo_raid_id', $soloRaid->id)
                ->count(),
            'best_score' => \App\Models\SessionSolo::where('user_id', auth()->id())
                ->where('solo_raid_id', $soloRaid->id)
                ->max('skor_akhir') ?? 0,
            'total_xp' => \App\Models\SessionSolo::where('user_id', auth()->id())
                ->where('solo_raid_id', $soloRaid->id)
                ->sum('xp_diperoleh') ?? 0,
            'completed_levels' => \App\Models\SessionSolo::where('user_id', auth()->id())
                ->where('solo_raid_id', $soloRaid->id)
                ->where('boss_kalah', true)
                ->distinct('level')
                ->count('level'),
            'max_xp' => \App\Models\SessionSolo::where('user_id', auth()->id())
                ->where('solo_raid_id', $soloRaid->id)
                ->max('xp_diperoleh') ?? 0,
        ];

        // Fetch Session History
        $sessionHistory = \App\Models\SessionSolo::where('user_id', auth()->id())
            ->where('solo_raid_id', $soloRaid->id)
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        // Per-level stats for modal
        $levelStats = [
            'easy' => [
                'attempts' => \App\Models\SessionSolo::where('user_id', auth()->id())
                    ->where('solo_raid_id', $soloRaid->id)
                    ->where('level', 'Easy')
                    ->whereNotNull('waktu_selesai')
                    ->count(),
                'max_xp' => \App\Models\SessionSolo::where('user_id', auth()->id())
                    ->where('solo_raid_id', $soloRaid->id)
                    ->where('level', 'Easy')
                    ->whereNotNull('waktu_selesai')
                    ->max('xp_diperoleh') ?? 0,
            ],
            'medium' => [
                'attempts' => \App\Models\SessionSolo::where('user_id', auth()->id())
                    ->where('solo_raid_id', $soloRaid->id)
                    ->where('level', 'Medium')
                    ->whereNotNull('waktu_selesai')
                    ->count(),
                'max_xp' => \App\Models\SessionSolo::where('user_id', auth()->id())
                    ->where('solo_raid_id', $soloRaid->id)
                    ->where('level', 'Medium')
                    ->whereNotNull('waktu_selesai')
                    ->max('xp_diperoleh') ?? 0,
            ],
            'hard' => [
                'attempts' => \App\Models\SessionSolo::where('user_id', auth()->id())
                    ->where('solo_raid_id', $soloRaid->id)
                    ->where('level', 'Hard')
                    ->whereNotNull('waktu_selesai')
                    ->count(),
                'max_xp' => \App\Models\SessionSolo::where('user_id', auth()->id())
                    ->where('solo_raid_id', $soloRaid->id)
                    ->where('level', 'Hard')
                    ->whereNotNull('waktu_selesai')
                    ->max('xp_diperoleh') ?? 0,
            ],
        ];

        return view('solo.map', compact('soloRaid', 'userStats', 'sessionHistory', 'levelStats', 'activeSession'));
    }

    public function info(SoloRaid $soloRaid, $nodeId)
    {
        $content = match($nodeId) {
            '1' => $soloRaid->info_node_1,
            '2' => $soloRaid->info_node_2,
            '3' => $soloRaid->info_node_3,
            default => 'Info not found'
        };

        return response()->json(['title' => "Info Node $nodeId", 'content' => $content]);
    }

    public function levelSelect(SoloRaid $soloRaid)
    {
        $levels = [
            'easy' => [
                'enabled' => $soloRaid->easy_enabled,
                'available' => $soloRaid->easy_enabled && now()->between($soloRaid->easy_start_date ?? $soloRaid->tanggal_mulai, $soloRaid->easy_end_date ?? $soloRaid->tanggal_selesai),
                'start' => $soloRaid->easy_start_date ?? $soloRaid->tanggal_mulai,
                'end' => $soloRaid->easy_end_date ?? $soloRaid->tanggal_selesai,
            ],
            'medium' => [
                'enabled' => $soloRaid->medium_enabled,
                'available' => $soloRaid->medium_enabled && now()->between($soloRaid->medium_start_date ?? $soloRaid->tanggal_mulai, $soloRaid->medium_end_date ?? $soloRaid->tanggal_selesai),
            ],
            'hard' => [
                'enabled' => $soloRaid->hard_enabled,
                'available' => $soloRaid->hard_enabled && now()->between($soloRaid->hard_start_date ?? $soloRaid->tanggal_mulai, $soloRaid->hard_end_date ?? $soloRaid->tanggal_selesai),
            ],
        ];

        return response()->json($levels);
    }
}
