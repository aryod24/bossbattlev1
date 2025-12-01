<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SoloRaid;
use Illuminate\Http\Request;

class SoloRaidAdminController extends Controller
{
    public function index()
    {
        $raids = SoloRaid::latest()->get();
        return view('admin.solo-raids.index', compact('raids'));
    }

    public function create()
    {
        return view('admin.solo-raids.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'info_node_1' => 'nullable|string',
            'info_node_2' => 'nullable|string',
            'info_node_3' => 'nullable|string',
            'boss_easy_name' => 'nullable|string',
            'boss_medium_name' => 'nullable|string',
            'boss_hard_name' => 'nullable|string',
            'easy_enabled' => 'boolean',
            'medium_enabled' => 'boolean',
            'hard_enabled' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['easy_enabled'] = $request->has('easy_enabled');
        $validated['medium_enabled'] = $request->has('medium_enabled');
        $validated['hard_enabled'] = $request->has('hard_enabled');

        SoloRaid::create($validated);

        return redirect()->route('admin.solo-raids.index')->with('success', 'Solo Raid created successfully.');
    }

    public function edit(SoloRaid $soloRaid)
    {
        return view('admin.solo-raids.edit', compact('soloRaid'));
    }

    public function update(Request $request, SoloRaid $soloRaid)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'info_node_1' => 'nullable|string',
            'info_node_2' => 'nullable|string',
            'info_node_3' => 'nullable|string',
            'boss_easy_name' => 'nullable|string',
            'boss_medium_name' => 'nullable|string',
            'boss_hard_name' => 'nullable|string',
            'easy_enabled' => 'boolean',
            'medium_enabled' => 'boolean',
            'hard_enabled' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['easy_enabled'] = $request->has('easy_enabled');
        $validated['medium_enabled'] = $request->has('medium_enabled');
        $validated['hard_enabled'] = $request->has('hard_enabled');

        $soloRaid->update($validated);

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
        $newRaid->status = 'inactive';
        $newRaid->created_by = auth()->id();
        $newRaid->save();

        return redirect()->route('admin.solo-raids.edit', $newRaid->id)->with('success', 'Solo Raid duplicated. Please update details.');
    }

    public function toggleLevel(Request $request, SoloRaid $soloRaid)
    {
        $level = $request->level; // easy, medium, hard
        $field = $level . '_enabled';
        
        if (in_array($field, ['easy_enabled', 'medium_enabled', 'hard_enabled'])) {
            $soloRaid->$field = !$soloRaid->$field;
            $soloRaid->save();
        }

        return back()->with('success', ucfirst($level) . ' level toggled.');
    }
}
