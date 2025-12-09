<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Get both Solo Raids and Multiplayer Events
        $soloRaids = \App\Models\SoloRaid::orderBy('created_at', 'desc')->get();
        $events = \App\Models\Event::orderBy('created_at', 'desc')->get();
        
        return view('admin.reports.index', compact('soloRaids', 'events'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'report_source' => 'required|string', // Format: "event:1" or "solo:1"
        ]);

        [$type, $id] = explode(':', $request->report_source);

        $data = [];
        $title = '';
        
        if ($type === 'solo') {
            $source = \App\Models\SoloRaid::findOrFail($id);
            $title = $source->nama;
            $items = \App\Models\SessionSolo::with('user')
                ->where('solo_raid_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $source = \App\Models\Event::findOrFail($id);
            $title = $source->title;
            $items = \App\Models\EventParticipant::with('user')
                ->where('event_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $filename = 'report-' . $type . '-' . $id . '-' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($items, $title, $type) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header - Using Semicolon separator for better Excel compatibility
            fputcsv($file, [
                'User ID',
                'Name',
                'Email',
                'Source Title',
                'Type',
                'Level/Rank', 
                'Join Date',
                'Start Time',
                'End Time',
                'Duration (Seconds)',
                'Score',
                'Status',
                'Total Questions',
                'Correct Answers',
                'Wrong Answers',
                'Accuracy (%)',
                'Boss HP Start',
                'Boss HP End',
                'Boss Defeated',
                'Mental Demand (MD)',
                'Physical Demand (PD)',
                'Temporal Demand (TD)',
                'Performance (OP)',
                'Effort (EF)',
                'Frustration (FR)',
                'NASA-TLX Score',
            ], ';');

            foreach ($items as $item) {
                // Common Logic
                $accuracy = $item->jumlah_soal > 0 ? round(($item->jumlah_benar / $item->jumlah_soal) * 100, 2) : 0;
                
                // Specific Logic
                if ($type === 'solo') {
                    $levelOrRank = $item->level;
                    $status = $item->boss_kalah ? 'Win' : 'Loss';
                } else {
                    $levelOrRank = $item->peringkat_leaderboard ?? '-';
                    $status = ucfirst(str_replace('_', ' ', $item->status));
                }

                fputcsv($file, [
                    $item->user->id ?? '?',
                    $item->user->nama ?? '?', // Fixed: name -> nama
                    $item->user->email ?? '?',
                    $title,
                    ucfirst($type),
                    $levelOrRank,
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->waktu_mulai ? $item->waktu_mulai->format('H:i:s') : '-',
                    $item->waktu_selesai ? $item->waktu_selesai->format('H:i:s') : '-',
                    $item->durasi_detik ?? 0,
                    $item->skor_akhir ?? 0,
                    $status,
                    $item->jumlah_soal,
                    $item->jumlah_benar,
                    $item->jumlah_salah,
                    $accuracy . '%',
                    $item->boss_hp_awal,
                    $item->boss_hp_akhir,
                    $item->boss_kalah ? 'Yes' : 'No',
                    '', '', '', '', '', '', ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
