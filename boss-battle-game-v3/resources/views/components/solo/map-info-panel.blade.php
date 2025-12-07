@props(['soloRaid', 'stats', 'sessions', 'activeSession'])

<div class="w-full md:w-1/2 bg-card p-6 md:p-12 flex flex-col justify-center overflow-y-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <!-- Logo + Text -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10">
                    <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="w-full h-full object-contain">
                </div>
                <h2 class="text-xl font-bold text-text-primary">CodeBossArena</h2>
            </div>
            
            <!-- Back Button -->
            <a href="{{ route('solo.index') }}" class="bg-primary hover:bg-accent-hover px-6 py-2 rounded-lg font-semibold transition-colors text-white">
                ← Back to List
            </a>
        </div>

        <!-- Active Session Warning -->
        @if($activeSession && $activeSession->solo_raid_id !== $soloRaid->id)
            <div class="bg-yellow-500/10 border border-yellow-500/50 rounded-lg p-4 mb-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-yellow-500">warning</span>
                    <div>
                        <p class="font-bold text-yellow-500 mb-1">Active Session in Another Map</p>
                        <p class="text-sm text-text-muted">
                            You're currently working on <strong class="text-text-primary">{{ $activeSession->soloRaid->nama }}</strong> ({{ $activeSession->level }}).
                            <br>Complete it first before starting a new map.
                        </p>
                        <a href="{{ route('solo.battle', ['soloRaid' => $activeSession->solo_raid_id, 'session' => $activeSession->id]) }}" 
                           class="inline-block mt-2 text-primary hover:underline text-sm font-semibold">
                            → Continue {{ $activeSession->soloRaid->nama }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Raid Title with Period -->
    <div class="mb-8">
        <div class="flex justify-between items-start mb-2">
            <h1 class="text-3xl md:text-4xl font-bold text-text-primary">
                {{ $soloRaid->nama }}
            </h1>
            <div class="text-right">
                <p class="text-xs text-text-muted">Period</p>
                <p class="text-sm font-semibold text-text-primary">{{ \Carbon\Carbon::parse($soloRaid->tanggal_mulai)->format('M d') }} - {{ \Carbon\Carbon::parse($soloRaid->tanggal_selesai)->format('M d') }}</p>
            </div>
        </div>
        <p class="text-text-muted text-base md:text-lg leading-relaxed">
            {{ $soloRaid->deskripsi }}
        </p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-2">
            <span class="text-text-primary font-semibold">Raid Progress</span>
            <span class="text-text-muted font-medium">{{ $stats['completed_levels'] }}/3 Completed</span>
        </div>
        <div class="w-full bg-border rounded-full h-3 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-accent-hover h-full rounded-full" style="width: {{ ($stats['completed_levels'] / 3) * 100 }}%"></div>
        </div>
    </div>

    <!-- Attempt History -->
    <div class="flex-1 overflow-hidden flex flex-col">
        <h3 class="text-text-muted font-semibold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">history</span>
            Attempt History ({{ $stats['attempts'] }} total)
        </h3>
        
        @if($sessions->count() > 0)
            <div class="space-y-3 overflow-y-auto pr-2" style="max-height: 400px;">
                @foreach($sessions as $session)
                    <div class="bg-surface-light rounded-xl p-4 border border-border">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded text-xs font-bold bg-primary/20 text-primary">
                                    Attempt #{{ $session->attempt_number }}
                                </span>
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    @if($session->level === 'Easy') bg-green-500/20 text-green-500
                                    @elseif($session->level === 'Medium') bg-yellow-500/20 text-yellow-500
                                    @else bg-red-500/20 text-red-500
                                    @endif">
                                    {{ $session->level }}
                                </span>
                            </div>
                            <span class="text-xs text-text-muted">
                                {{ \Carbon\Carbon::parse($session->waktu_mulai)->format('M d, H:i') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 mt-3">
                            <div>
                                <p class="text-xs text-text-muted">Score</p>
                                <p class="text-sm font-bold text-text-primary">{{ number_format($session->skor_akhir, 1) }}%</p>
                            </div>
                            <div>
                                <p class="text-xs text-text-muted">XP</p>
                                <p class="text-sm font-bold text-primary">+{{ $session->xp_diperoleh }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-text-muted">Status</p>
                                @if($session->boss_kalah || $session->skor_akhir >= 100)
                                    <p class="text-xs font-bold text-green-500">✓ WIN</p>
                                @else
                                    <p class="text-xs font-bold text-red-500">✗ LOSS</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-text-muted">
                <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                <p class="text-sm">No attempts yet. Start your first battle!</p>
            </div>
        @endif
    </div>
</div>
