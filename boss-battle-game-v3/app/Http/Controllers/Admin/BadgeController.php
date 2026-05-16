<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $badges = Badge::all();
        return view('admin.badges.index', compact('badges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.badges.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:badges,slug',
            'name' => 'required|string|max:255',
            'emoji' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'threshold' => 'nullable|integer',
            'requirements' => 'nullable|json',
        ]);

        if (!empty($validated['requirements'])) {
            $validated['requirements'] = json_decode($validated['requirements'], true);
        }

        Badge::create($validated);
        
        // Clear badge cache
        \Illuminate\Support\Facades\Cache::forget('badges_all');
        \Illuminate\Support\Facades\Cache::forget('badges_all_keyed');

        return redirect()->route('admin.badges.index')->with('success', 'Badge created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Badge $badge)
    {
        return view('admin.badges.edit', compact('badge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:badges,slug,' . $badge->id,
            'name' => 'required|string|max:255',
            'emoji' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'threshold' => 'nullable|integer',
            'requirements' => 'nullable|json',
        ]);

        if (!empty($validated['requirements'])) {
            $validated['requirements'] = json_decode($validated['requirements'], true);
        } else {
            $validated['requirements'] = null; // Ensure we can clear it
        }

        $badge->update($validated);
        
        // Clear badge cache
        \Illuminate\Support\Facades\Cache::forget('badges_all');
        \Illuminate\Support\Facades\Cache::forget('badges_all_keyed');

        return redirect()->route('admin.badges.index')->with('success', 'Badge updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badge $badge)
    {
        if ($badge->is_system) {
            return redirect()->route('admin.badges.index')->with('error', 'Cannot delete system badges.');
        }

        $badge->delete();
        
        // Clear badge cache
        \Illuminate\Support\Facades\Cache::forget('badges_all');
        \Illuminate\Support\Facades\Cache::forget('badges_all_keyed');

        return redirect()->route('admin.badges.index')->with('success', 'Badge deleted successfully.');
    }
}
