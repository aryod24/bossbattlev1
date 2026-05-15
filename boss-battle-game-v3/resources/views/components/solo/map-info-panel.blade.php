@props(['soloRaid', 'stats', 'sessions', 'activeSession'])

<div class="w-full md:w-1/2 bg-card p-6 md:p-12 flex flex-col justify-start overflow-y-auto">
    <!-- Header -->
    <div class="mb-2">
        <!-- Header Card -->
        <div class="flex justify-between items-center bg-surface-light dark:bg-surface-dark p-4 rounded-xl border border-border mb-6 shadow-sm">
            <!-- Left: Back to List -->
            <a href="{{ route('solo.index') }}" class="group inline-flex items-center gap-2 text-text-muted hover:text-primary transition-colors text-sm font-bold">
                <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
                Kembali ke List
            </a>

            <!-- Right: Logo -->
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-text-primary hidden sm:block">CodeBossArena</h2>
                <div class="w-8 h-8">
                    <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="w-full h-full object-contain">
                </div>
            </div>
        </div>

        <!-- Active Session Warning -->
        @if($activeSession && $activeSession->solo_raid_id !== $soloRaid->id)
            <div class="bg-yellow-500/10 border border-yellow-500/50 rounded-lg p-4 mb-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-yellow-500">warning</span>
                    <div>
                        <p class="font-bold text-yellow-500 mb-1">Sesi Aktif di Map Lain</p>
                        <p class="text-sm text-text-muted">
                            Kamu sedang mengerjakan <strong class="text-text-primary">{{ $activeSession->soloRaid->nama }}</strong> ({{ $activeSession->level }}).
                            <br>Selesaikan dulu sebelum memulai map baru.
                        </p>
                        <a href="{{ route('solo.battle', ['soloRaid' => $activeSession->solo_raid_id, 'session' => $activeSession->id]) }}" 
                           class="inline-block mt-2 text-primary hover:underline text-sm font-semibold">
                            → Lanjutkan {{ $activeSession->soloRaid->nama }}
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
                <p class="text-xs text-text-muted">Periode</p>
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
            <span class="text-text-primary font-semibold">Progress Materi</span>
            <span class="text-text-muted font-medium">{{ $stats['completed_nodes'] }}/{{ $stats['total_nodes'] }} Dibaca</span>
        </div>
        <div class="w-full bg-border rounded-full h-3 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-accent-hover h-full rounded-full" style="width: {{ $stats['total_nodes'] > 0 ? ($stats['completed_nodes'] / $stats['total_nodes']) * 100 : 0 }}%"></div>
        </div>
    </div>

    <!-- Attempt History -->
    <div class="flex-1 overflow-hidden flex flex-col">
        <h3 class="text-text-muted font-semibold mb-3 flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-primary">history</span>
            Riwayat Percobaan ({{ $stats['attempts'] }}x)
        </h3>
        
        @if($sessions->count() > 0)
            <div class="space-y-2 overflow-y-auto pr-1" style="max-height: 400px;">
                @foreach($sessions as $session)
                    <div class="bg-surface-light rounded-lg p-3 border border-border flex justify-between items-center text-xs">
                        <div class="flex items-center gap-3">
                            <span class="text-text-muted">{{ \Carbon\Carbon::parse($session->waktu_mulai)->format('d M, H:i') }}</span>
                        </div>
                        
                        @if(!$session->waktu_selesai)
                            <span class="font-bold text-yellow-500 animate-pulse">BERLANGSUNG</span>
                        @else
                            <span class="font-bold {{ ($session->boss_kalah || $session->skor_akhir >= 100) ? 'text-success' : 'text-error' }}">
                                {{ ($session->boss_kalah || $session->skor_akhir >= 100) ? '✓ Lulus' : '✗ Gagal' }}
                                — {{ number_format($session->skor_akhir, 0) }}%
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-text-muted">
                <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                <p class="text-sm">Belum ada percobaan.</p>
            </div>
        @endif
    </div>
</div>
