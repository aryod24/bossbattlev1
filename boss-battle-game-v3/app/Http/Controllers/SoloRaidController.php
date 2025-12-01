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
            ->get();
            
        return view('solo.index', compact('raids'));
    }

    public function map(SoloRaid $soloRaid)
    {
        // Period validation
        if (now()->lt($soloRaid->tanggal_mulai) || now()->gt($soloRaid->tanggal_selesai)) {
            return redirect()->route('solo.index')->with('error', 'Periode raid ini belum dimulai atau sudah berakhir.');
        }

        return view('solo.map', compact('soloRaid'));
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
