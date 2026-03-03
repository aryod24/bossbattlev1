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
     * Display a listing of events created by this dosen.
     */
    public function index()
    {
        $raids = SoloRaid::where('created_by', auth()->id())
            ->latest()
            ->get();

        return view('dosen.events.index', compact('raids'));
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
            'boss_easy_name' => 'nullable|string',
            'boss_medium_name' => 'nullable|string',
            'boss_hard_name' => 'nullable|string',
            'easy_enabled' => 'boolean',
            'medium_enabled' => 'boolean',
            'hard_enabled' => 'boolean',
            'status' => 'required|in:draft,active,selesai',
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

        DB::transaction(function () use ($validated) {
            $nodes = $validated['nodes'] ?? [];
            unset($validated['nodes']);

            $raid = SoloRaid::create($validated);

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
        if ($soloRaid->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

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
        if ($soloRaid->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

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

            $soloRaid->nodes()->delete();
            foreach ($nodes as $nodeData) {
                RaidNode::create([
                    'solo_raid_id' => $soloRaid->id,
                    'type' => $nodeData['type'],
                    'title' => $nodeData['title'],
                    'content' => $nodeData['content'] ?? null,
                    'order' => $nodeData['order'],
                ]);
            }
        });

        return redirect()->route('dosen.events.index')->with('success', 'Solo Raid updated successfully.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(SoloRaid $soloRaid)
    {
        if ($soloRaid->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

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
        $newRaid->save();

        foreach ($soloRaid->nodes as $node) {
            $newNode = $node->replicate();
            $newNode->solo_raid_id = $newRaid->id;
            $newNode->save();
        }

        return redirect()->route('dosen.events.edit', $newRaid->id)->with('success', 'Solo Raid duplicated. Please update details.');
    }

    /**
     * Toggle levelEnabled.
     */
    public function toggleLevel(Request $request, SoloRaid $soloRaid)
    {
        if ($soloRaid->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $level = $request->level;
        $field = $level . '_enabled';
        
        if (in_array($field, ['easy_enabled', 'medium_enabled', 'hard_enabled'])) {
            $soloRaid->$field = !$soloRaid->$field;
            $soloRaid->save();
        }

        return back()->with('success', ucfirst($level) . ' level toggled.');
    }
}
