<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaidNode;
use App\Models\SoloRaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoloRaidAdminController extends Controller
{
    public function index()
    {
        $raids = SoloRaid::latest()->get();
        return view('admin.solo-raids.index', compact('raids'));
    }

    public function create()
    {
        $banks = \App\Models\QuestionBank::select('bank_group', 'bank_name')
            ->distinct()
            ->orderBy('bank_group')
            ->get();
        return view('admin.solo-raids.create', compact('banks'));
    }

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
            'boss_easy_name' => 'nullable|string',
            'boss_medium_name' => 'nullable|string',
            'boss_hard_name' => 'nullable|string',
            'easy_enabled' => 'boolean',
            'medium_enabled' => 'boolean',
            'hard_enabled' => 'boolean',
            'status' => 'required|in:draft,active,selesai',
            // Dynamic nodes
            'nodes' => 'nullable|array',
            'nodes.*.type' => 'required_with:nodes|in:content,quiz',
            'nodes.*.title' => 'required_with:nodes|string|max:150',
            'nodes.*.content' => 'nullable|string',
            'nodes.*.order' => 'required_with:nodes|integer|min:1|max:6',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['easy_enabled'] = $request->has('easy_enabled');
        $validated['medium_enabled'] = $request->has('medium_enabled');
        $validated['hard_enabled'] = $request->has('hard_enabled');

        DB::transaction(function () use ($validated, $request) {
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

        return redirect()->route('admin.solo-raids.index')->with('success', 'Solo Raid created successfully.');
    }

    public function edit(SoloRaid $soloRaid)
    {
        $banks = \App\Models\QuestionBank::select('bank_group', 'bank_name')
            ->distinct()
            ->orderBy('bank_group')
            ->get();
        $soloRaid->load('nodes');
        return view('admin.solo-raids.edit', compact('soloRaid', 'banks'));
    }

    public function update(Request $request, SoloRaid $soloRaid)
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
            'boss_easy_name' => 'nullable|string',
            'boss_medium_name' => 'nullable|string',
            'boss_hard_name' => 'nullable|string',
            'easy_enabled' => 'boolean',
            'medium_enabled' => 'boolean',
            'hard_enabled' => 'boolean',
            'status' => 'required|in:draft,active,selesai',
            // Dynamic nodes
            'nodes' => 'nullable|array',
            'nodes.*.id' => 'nullable|integer',
            'nodes.*.type' => 'required_with:nodes|in:content,quiz',
            'nodes.*.title' => 'required_with:nodes|string|max:150',
            'nodes.*.content' => 'nullable|string',
            'nodes.*.order' => 'required_with:nodes|integer|min:1|max:6',
        ]);

        $validated['easy_enabled'] = $request->has('easy_enabled');
        $validated['medium_enabled'] = $request->has('medium_enabled');
        $validated['hard_enabled'] = $request->has('hard_enabled');

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

            // Delete nodes that were removed by admin
            $soloRaid->nodes()->whereNotIn('id', $incomingIds)->delete();
        });

        return redirect()->route('admin.solo-raids.index')->with('success', 'Solo Raid updated successfully.');
    }

    public function destroy(SoloRaid $soloRaid)
    {
        $soloRaid->delete();
        return redirect()->route('admin.solo-raids.index')->with('success', 'Solo Raid deleted successfully.');
    }

    public function duplicate(SoloRaid $soloRaid)
    {
        $newRaid = $soloRaid->replicate();
        $newRaid->nama = $newRaid->nama . ' (Copy)';
        $newRaid->status = 'draft';
        $newRaid->created_by = auth()->id();
        $newRaid->save();

        // Duplicate nodes
        foreach ($soloRaid->nodes as $node) {
            $newNode = $node->replicate();
            $newNode->solo_raid_id = $newRaid->id;
            $newNode->save();
        }

        return redirect()->route('admin.solo-raids.edit', $newRaid->id)->with('success', 'Solo Raid duplicated. Please update details.');
    }

    public function toggleLevel(Request $request, SoloRaid $soloRaid)
    {
        $level = $request->level;
        $field = $level . '_enabled';
        
        if (in_array($field, ['easy_enabled', 'medium_enabled', 'hard_enabled'])) {
            $soloRaid->$field = !$soloRaid->$field;
            $soloRaid->save();
        }

        return back()->with('success', ucfirst($level) . ' level toggled.');
    }

    public function monitoring(SoloRaid $soloRaid)
    {
        $soloRaid->load('nodes');
        
        // Get all students
        $students = \App\Models\User::where('role', 'student')->get();
        
        $monitoringData = [];
        
        foreach ($students as $student) {
            // Get user event progress
            $progress = \App\Models\UserEventProgress::where('user_id', $student->id)
                ->where('solo_raid_id', $soloRaid->id)
                ->first();
                
            // Get completed nodes count
            $completedNodesCount = \App\Models\UserNodeCompletion::where('user_id', $student->id)
                ->whereIn('raid_node_id', $soloRaid->nodes->pluck('id'))
                ->count();
            
            // Get last session
            $lastSession = \App\Models\SessionSolo::where('user_id', $student->id)
                ->where('solo_raid_id', $soloRaid->id)
                ->latest('waktu_mulai')
                ->first();

            // Total attempts
            $attempts = \App\Models\SessionSolo::where('user_id', $student->id)
                ->where('solo_raid_id', $soloRaid->id)
                ->count();
                
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

        // Sort by last active descending
        $monitoringData = collect($monitoringData)->sortByDesc(function ($item) {
            return $item['last_active'] ?? '0000-00-00 00:00:00';
        })->values();

        return view('admin.solo-raids.monitoring.index', compact('soloRaid', 'monitoringData'));
    }

    public function monitoringDetail(SoloRaid $soloRaid, \App\Models\User $user)
    {
        $soloRaid->load('nodes');
        
        // Fetch node completions
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
            
        // Fetch quiz sessions
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

        // Combine and sort
        $timeline = collect($nodeCompletions)->concat($quizSessions)->sortByDesc('time')->values();
        
        // Progress status
        $progress = \App\Models\UserEventProgress::where('user_id', $user->id)
            ->where('solo_raid_id', $soloRaid->id)
            ->first();

        return view('admin.solo-raids.monitoring.detail', compact('soloRaid', 'user', 'timeline', 'progress'));
    }
}
