<?php

namespace App\Http\Controllers;

use App\Models\RaidNode;
use App\Models\SoloRaid;
use App\Models\UserEventProgress;
use App\Models\UserNodeCompletion;
use Illuminate\Http\Request;
use App\Services\SoloBattleService;

class SoloRaidController extends Controller
{
    // ─── INDEX ────────────────────────────────────────────────────────────────

    public function index()
    {
        $user = auth()->user();

        if ($user->needsPretest()) {
            return redirect()->route('pretest.index');
        }

        $currentSection = $user->current_section ?? 'Easy';
        $sections = ['Easy', 'Medium', 'Hard'];
        $sectionRanks = ['Easy' => 1, 'Medium' => 2, 'Hard' => 3];
        $userRank = $sectionRanks[$currentSection] ?? 1;

        $eventsBySection = [];

        // Load UserEventProgress for all events to avoid N+1
        $progressMap = UserEventProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('solo_raid_id');

        foreach ($sections as $sectionName) {
            $events = SoloRaid::where('status', 'active')
                ->where('section', $sectionName)
                ->ordered()
                ->get();

            $sectionRank = $sectionRanks[$sectionName];
            $isSectionUnlocked = $sectionRank <= $userRank;
            
            $unlockedIds = [];
            
            if ($isSectionUnlocked) {
                // Determine linear progression unlocks within the section
                foreach ($events as $i => $event) {
                    if ($i === 0) {
                        $unlockedIds[] = $event->id; // First event always unlocked if section is unlocked
                    } else {
                        $prevEvent = $events[$i - 1];
                        $prevProgress = $progressMap[$prevEvent->id] ?? null;
                        if ($prevProgress && $prevProgress->status === 'completed') {
                            $unlockedIds[] = $event->id;
                        }
                    }
                }
            }

            // Attach progress & unlock status to each event
            $events->each(function ($event) use ($progressMap, $unlockedIds) {
                $event->progress = $progressMap[$event->id] ?? null;
                $event->is_unlocked = in_array($event->id, $unlockedIds);
            });

            $eventsBySection[$sectionName] = [
                'is_unlocked' => $isSectionUnlocked,
                'events' => $events
            ];
        }

        return view('solo.index', compact('eventsBySection', 'currentSection'));
    }

    // ─── MAP ──────────────────────────────────────────────────────────────────

    public function map(SoloRaid $soloRaid, SoloBattleService $battleService)
    {
        // Boss-type raids don't need the dungeon map — go straight to boss intro
        if ($soloRaid->type === 'boss') {
            return redirect()->route('solo.boss', $soloRaid);
        }

        // Period validation
        if (now()->lt($soloRaid->tanggal_mulai) || now()->gt($soloRaid->tanggal_selesai)) {
            return redirect()->route('solo.index')->with('error', 'Periode event ini belum dimulai atau sudah berakhir.');
        }

        $user = auth()->user();

        // Check for active session (handle expired)
        $activeSession = \App\Models\SessionSolo::where('user_id', $user->id)
            ->whereNull('waktu_selesai')
            ->with('soloRaid')
            ->first();

        if ($activeSession) {
            $config   = SoloBattleService::LEVEL_CONFIG[$activeSession->level] ?? SoloBattleService::LEVEL_CONFIG['Easy'];
            $deadline = $activeSession->waktu_mulai->addMinutes($config['timer_minutes']);
            if (now()->greaterThan($deadline)) {
                $battleService->finishSession($activeSession->id, $deadline);
                return redirect()->route('solo.result', $activeSession->id);
            }
        }

        // Load nodes ordered
        $nodes = $soloRaid->nodes()->get();

        // Load completed node IDs for this event's nodes
        $nodeIds = $nodes->pluck('id');
        $completedNodeIds = UserNodeCompletion::where('user_id', $user->id)
            ->whereIn('raid_node_id', $nodeIds)
            ->pluck('raid_node_id')
            ->toArray();

        // Session history (for info panel)
        $sessionHistory = \App\Models\SessionSolo::where('user_id', $user->id)
            ->where('solo_raid_id', $soloRaid->id)
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        $userStats = [
            'attempts'  => $sessionHistory->count(),
            'completed_nodes' => count($completedNodeIds),
            'total_nodes'     => $nodes->where('type', 'content')->count(),
        ];

        return view('solo.map', compact(
            'soloRaid', 'nodes', 'completedNodeIds', 'activeSession', 'sessionHistory', 'userStats'
        ));
    }

    // ─── COMPLETE NODE ────────────────────────────────────────────────────────

    public function completeNode(RaidNode $node)
    {
        $user = auth()->user();

        // Mark content node as completed (idempotent)
        UserNodeCompletion::firstOrCreate([
            'user_id'      => $user->id,
            'raid_node_id' => $node->id,
        ]);

        // Check if ALL content nodes for this event are now done
        $soloRaid    = $node->soloRaid;
        $contentNodes = RaidNode::where('solo_raid_id', $soloRaid->id)
            ->where('type', 'content')
            ->pluck('id');

        $completedCount = UserNodeCompletion::where('user_id', $user->id)
            ->whereIn('raid_node_id', $contentNodes)
            ->count();

        $allDone = $completedCount >= $contentNodes->count();

        // Ensure UserEventProgress exists
        UserEventProgress::firstOrCreate(
            ['user_id' => $user->id, 'solo_raid_id' => $soloRaid->id],
            ['status' => 'in_progress']
        );

        // Find order of next node
        $nextNode = RaidNode::where('solo_raid_id', $soloRaid->id)
            ->where('order', '>', $node->order)
            ->orderBy('order')
            ->first();

        return response()->json([
            'success'      => true,
            'all_done'     => $allDone,
            'next_order'   => $nextNode?->order,
        ]);
    }

    // ─── MATERI (JSON for modal) ───────────────────────────────────────────────

    public function materi(SoloRaid $soloRaid, $nodeId)
    {
        $node = RaidNode::where('solo_raid_id', $soloRaid->id)
            ->where('order', $nodeId)
            ->where('type', 'content')
            ->first();

        if (!$node) {
            return response()->json(['error' => 'Materi tidak ditemukan'], 404);
        }

        // Track progress
        $user = auth()->user();
        UserEventProgress::firstOrCreate(
            ['user_id' => $user->id, 'solo_raid_id' => $soloRaid->id],
            ['status' => 'in_progress']
        );

        return response()->json([
            'id'      => $node->id,
            'title'   => $node->title,
            'content' => $node->content,
            'order'   => $node->order,
        ]);
    }

    // ─── BOSS INFO PAGE ───────────────────────────────────────────────────────

    public function boss(SoloRaid $soloRaid)
    {
        $user = auth()->user();

        // Period validation
        if (now()->lt($soloRaid->tanggal_mulai) || now()->gt($soloRaid->tanggal_selesai)) {
            return redirect()->route('solo.index')->with('error', 'Periode boss battle ini sudah berakhir.');
        }

        $section = $soloRaid->section ?? 'Easy';

        // Boss name based on section
        $bossName = match ($section) {
            'Easy'   => $soloRaid->boss_easy_name,
            'Medium' => $soloRaid->boss_medium_name,
            'Hard'   => $soloRaid->boss_hard_name,
            default  => $soloRaid->boss_easy_name,
        };

        $levelConfig = SoloBattleService::LEVEL_CONFIG[$section] ?? SoloBattleService::LEVEL_CONFIG['Easy'];

        // Session history for this boss
        $sessionHistory = \App\Models\SessionSolo::where('user_id', $user->id)
            ->where('solo_raid_id', $soloRaid->id)
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        $bestSession = $sessionHistory->where('boss_kalah', true)->first();

        return view('solo.boss', compact(
            'soloRaid', 'bossName', 'section', 'levelConfig', 'sessionHistory', 'bestSession'
        ));
    }

    // ─── LEVEL SELECT (kept for compatibility) ────────────────────────────────

    public function levelSelect(SoloRaid $soloRaid)
    {
        $levels = [
            'easy'   => ['enabled' => $soloRaid->easy_enabled,   'available' => $soloRaid->easy_enabled],
            'medium' => ['enabled' => $soloRaid->medium_enabled, 'available' => $soloRaid->medium_enabled],
            'hard'   => ['enabled' => $soloRaid->hard_enabled,   'available' => $soloRaid->hard_enabled],
        ];
        return response()->json($levels);
    }
}
