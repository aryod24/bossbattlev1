<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\RaidNode;
use App\Models\SoloRaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of all events (for monitoring).
     */
    public function index()
    {
        $raids = SoloRaid::with('creator:id,nama')
            ->latest()
            ->get();

        // Lookup bank_group → bank_name (since solo_raid.question_bank_id stores bank_group, not the PK)
        $banksByGroup = \App\Models\QuestionBank::select('bank_group', 'bank_name')
            ->groupBy('bank_group', 'bank_name')
            ->pluck('bank_name', 'bank_group');

        return view('dosen.events.index', compact('raids', 'banksByGroup'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $banks = \App\Models\QuestionBank::select('bank_group', 'bank_name')
            ->distinct()
            ->orderBy('bank_group')
            ->get();
        return view('dosen.events.create', compact('banks'));
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'question_bank_id' => 'required|integer',
            'type' => 'required|in:learning,boss',
            'section' => 'required|in:Easy,Medium,Hard',
            'section_order' => 'required|integer|min:1|max:6',
            'status' => 'required|in:draft,active,selesai',
            // Dynamic nodes
            'nodes' => 'nullable|array|exclude_if:type,boss',
            'nodes.*.type' => 'required_with:nodes|in:content,quiz|exclude_if:type,boss',
            'nodes.*.title' => 'required_with:nodes|string|max:150|exclude_if:type,boss',
            'nodes.*.content' => 'nullable|string|exclude_if:type,boss',
            'nodes.*.order' => 'required_with:nodes|integer|min:1|max:6|exclude_if:type,boss',
        ]);

        $clash = SoloRaid::where('section', $validated['section'])
            ->where('section_order', $validated['section_order'])
            ->first(['id', 'nama']);
        if ($clash) {
            return back()->withInput()->withErrors([
                'section_order' => "Slot Section {$validated['section']} urutan #{$validated['section_order']} sudah dipakai event \"{$clash->nama}\". Pilih urutan lain.",
            ]);
        }

        $validated['created_by'] = auth()->id();

        DB::transaction(function () use ($validated) {
            $nodes = $validated['nodes'] ?? [];
            unset($validated['nodes']);

            $raid = SoloRaid::create($validated);

            // Create associated nodes
            foreach ($nodes as $nodeData) {
                RaidNode::create([
                    'solo_raid_id' => $raid->id,
                    'type' => $nodeData['type'],
                    'title' => $nodeData['title'],
                    'content' => $nodeData['content'] ?? null,
                    'order' => $nodeData['order'],
                ]);
            }
        });

        return redirect()->route('dosen.events.index')->with('success', 'Solo Raid created successfully.');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(SoloRaid $soloRaid)
    {
        // Allow all dosen to edit (not just creator)
        // if ($soloRaid->created_by !== auth()->id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $banks = \App\Models\QuestionBank::select('bank_group', 'bank_name')
            ->distinct()
            ->orderBy('bank_group')
            ->get();
        $soloRaid->load('nodes');
        return view('dosen.events.edit', compact('soloRaid', 'banks'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, SoloRaid $soloRaid)
    {
        // Allow all dosen to edit (not just creator)
        // if ($soloRaid->created_by !== auth()->id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'question_bank_id' => 'required|integer',
            'type' => 'required|in:learning,boss',
            'section' => 'required|in:Easy,Medium,Hard',
            'section_order' => 'required|integer|min:1|max:6',
            'status' => 'required|in:draft,active,selesai',
            // Dynamic nodes
            'nodes' => 'nullable|array|exclude_if:type,boss',
            'nodes.*.id' => 'nullable|integer|exclude_if:type,boss',
            'nodes.*.type' => 'required_with:nodes|in:content,quiz|exclude_if:type,boss',
            'nodes.*.title' => 'required_with:nodes|string|max:150|exclude_if:type,boss',
            'nodes.*.content' => 'nullable|string|exclude_if:type,boss',
            'nodes.*.order' => 'required_with:nodes|integer|min:1|max:6|exclude_if:type,boss',
        ]);

        $clash = SoloRaid::where('section', $validated['section'])
            ->where('section_order', $validated['section_order'])
            ->where('id', '!=', $soloRaid->id)
            ->first(['id', 'nama']);
        if ($clash) {
            return back()->withInput()->withErrors([
                'section_order' => "Slot Section {$validated['section']} urutan #{$validated['section_order']} sudah dipakai event \"{$clash->nama}\". Pilih urutan lain, atau ubah/hapus event tersebut dulu.",
            ]);
        }

        DB::transaction(function () use ($validated, $soloRaid) {
            $nodes = $validated['nodes'] ?? [];
            unset($validated['nodes']);

            $soloRaid->update($validated);

            // Upsert nodes: update existing, create new, delete removed
            $incomingIds = [];
            foreach ($nodes as $nodeData) {
                if (!empty($nodeData['id'])) {
                    // Update existing node (preserves ID → keeps user_node_completions)
                    $existingNode = RaidNode::where('id', $nodeData['id'])
                        ->where('solo_raid_id', $soloRaid->id)
                        ->first();
                    if ($existingNode) {
                        $existingNode->update([
                            'type' => $nodeData['type'],
                            'title' => $nodeData['title'],
                            'content' => $nodeData['content'] ?? null,
                            'order' => $nodeData['order'],
                        ]);
                        $incomingIds[] = $existingNode->id;
                        continue;
                    }
                }
                // Create new node
                $newNode = RaidNode::create([
                    'solo_raid_id' => $soloRaid->id,
                    'type' => $nodeData['type'],
                    'title' => $nodeData['title'],
                    'content' => $nodeData['content'] ?? null,
                    'order' => $nodeData['order'],
                ]);
                $incomingIds[] = $newNode->id;
            }

            // Delete nodes that were removed
            $soloRaid->nodes()->whereNotIn('id', $incomingIds)->delete();
        });

        return redirect()->route('dosen.events.index')->with('success', 'Solo Raid updated successfully.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(SoloRaid $soloRaid)
    {
        // Allow all dosen to delete (not just creator)
        // if ($soloRaid->created_by !== auth()->id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $soloRaid->delete();

        return redirect()->route('dosen.events.index')->with('success', 'Event berhasil dihapus!');
    }

    /**
     * Duplicate an event.
     */
    public function duplicate(SoloRaid $soloRaid)
    {
        $newRaid = $soloRaid->replicate();
        $newRaid->nama = $newRaid->nama . ' (Copy)';
        $newRaid->status = 'draft';
        $newRaid->created_by = auth()->id();

        $used = SoloRaid::where('section', $newRaid->section)
            ->pluck('section_order')
            ->all();

        $nextFree = null;
        for ($i = 1; $i <= 6; $i++) {
            if (!in_array($i, $used, true)) {
                $nextFree = $i;
                break;
            }
        }

        if ($nextFree !== null) {
            $newRaid->section_order = $nextFree;
            $newRaid->save();

            foreach ($soloRaid->nodes as $node) {
                $newNode = $node->replicate();
                $newNode->solo_raid_id = $newRaid->id;
                $newNode->save();
            }

            return redirect()->route('dosen.events.edit', $newRaid->id)
                ->with('success', "Event diduplikat ke Section {$newRaid->section} urutan #{$newRaid->section_order}. Sesuaikan detailnya bila perlu.");
        }

        return back()->with('error',
            "Section {$newRaid->section} sudah penuh (slot 1–6 terpakai). Hapus atau pindahkan salah satu event di section tersebut sebelum melakukan duplicate."
        );
    }

    /**
     * Toggle levelEnabled.
     */
    public function toggleLevel(Request $request, SoloRaid $soloRaid)
    {
        // Kolom *_enabled sudah dihapus, semua level kini selalu aktif.
        return back()->with('success', ucfirst((string) $request->level) . ' level toggled.');
    }

    public function monitoring(SoloRaid $soloRaid)
    {
        // Allow all dosen to view monitoring (not just creator)
        // if ($soloRaid->created_by !== auth()->id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $soloRaid->load('nodes');
        $nodeIds = $soloRaid->nodes->pluck('id');
        
        // Load semua data sekaligus dengan eager loading
        $students = \App\Models\User::whereRoleName('student')
            ->with([
                'eventProgress' => function($q) use ($soloRaid) {
                    $q->where('solo_raid_id', $soloRaid->id);
                },
                'sessionSolos' => function($q) use ($soloRaid) {
                    $q->where('solo_raid_id', $soloRaid->id)
                      ->latest('waktu_mulai');
                }
            ])
            ->paginate(50);
        
        // Load completed nodes untuk semua students sekaligus
        $completedNodesByUser = \App\Models\UserNodeCompletion::whereIn('raid_node_id', $nodeIds)
            ->whereIn('user_id', $students->pluck('id'))
            ->select('user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('user_id')
            ->pluck('count', 'user_id');
        
        // Load attempts count untuk semua students sekaligus
        $attemptsByUser = \App\Models\SessionSolo::where('solo_raid_id', $soloRaid->id)
            ->whereIn('user_id', $students->pluck('id'))
            ->select('user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('user_id')
            ->pluck('count', 'user_id');
        
        $monitoringData = [];
        
        foreach ($students as $student) {
            $progress = $student->eventProgress->first();
            $lastSession = $student->sessionSolos->first();
            $completedNodesCount = $completedNodesByUser[$student->id] ?? 0;
            $attempts = $attemptsByUser[$student->id] ?? 0;
            
            $monitoringData[] = [
                'user' => $student,
                'progress' => $progress ? $progress->status : 'belum mulai',
                'completed_nodes_count' => $completedNodesCount,
                'total_nodes' => $soloRaid->nodes->where('type', 'content')->count(),
                'last_session' => $lastSession,
                'attempts' => $attempts,
                'last_active' => $lastSession ? $lastSession->waktu_mulai : ($progress ? $progress->updated_at : null),
                'boss_defeated' => $lastSession ? $lastSession->boss_kalah : false
            ];
        }

        $monitoringData = collect($monitoringData)->sortByDesc(function ($item) {
            return $item['last_active'] ?? '0000-00-00 00:00:00';
        })->values();

        return view('dosen.events.monitoring.index', compact('soloRaid', 'monitoringData', 'students'));
    }

    public function monitoringDetail(SoloRaid $soloRaid, \App\Models\User $user)
    {
        // Allow all dosen to view monitoring detail (not just creator)
        // if ($soloRaid->created_by !== auth()->id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $soloRaid->load('nodes');
        
        $nodeCompletions = \App\Models\UserNodeCompletion::with('node')
            ->where('user_id', $user->id)
            ->whereIn('raid_node_id', $soloRaid->nodes->pluck('id'))
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'materi',
                    'title' => 'Membaca materi: ' . ($item->node ? $item->node->title : 'Unknown'),
                    'time' => $item->created_at,
                    'status' => 'Selesai'
                ];
            });
            
        $quizSessions = \App\Models\SessionSolo::where('user_id', $user->id)
            ->where('solo_raid_id', $soloRaid->id)
            ->get()
            ->flatMap(function ($session) {
                $events = [
                    [
                        'type' => 'kuis_mulai',
                        'title' => 'Memulai Kuis (Level ' . ucfirst($session->level) . ' - Percobaan #' . $session->attempt_number . ')',
                        'time' => $session->waktu_mulai,
                        'status' => 'Mulai'
                    ]
                ];
                
                if ($session->waktu_selesai) {
                    $statusDetail = 'Skor: ' . round($session->skor_akhir) . '. Boss ' . ($session->boss_kalah ? 'Kalahkan' : 'Bertahan');
                    if ($session->soloRaid && $session->soloRaid->type === 'learning') {
                         $statusDetail = 'Skor: ' . round($session->skor_akhir);
                    }

                    $events[] = [
                        'type' => 'kuis_selesai',
                        'title' => 'Menyelesaikan Kuis (Level ' . ucfirst($session->level) . ' - Percobaan #' . $session->attempt_number . ')',
                        'time' => $session->waktu_selesai,
                        'status' => $statusDetail
                    ];
                }
                
                return $events;
            });

        $timeline = collect($nodeCompletions)->concat($quizSessions)->sortByDesc('time')->values();
        
        $progress = \App\Models\UserEventProgress::where('user_id', $user->id)
            ->where('solo_raid_id', $soloRaid->id)
            ->first();

        return view('dosen.events.monitoring.detail', compact('soloRaid', 'user', 'timeline', 'progress'));
    }
}
