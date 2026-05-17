@props(['soloRaid', 'stats', 'sessions', 'activeSession', 'nodes', 'completedNodeIds'])

@php
    $totalNodes = $stats['total_nodes'] ?? 0;
    $completedNodes = $stats['completed_nodes'] ?? 0;
    $progressPct = $totalNodes > 0 ? ($completedNodes / $totalNodes) * 100 : 0;
    $isLearning = $soloRaid->type === 'learning';
@endphp

<div class="w-full md:w-1/2 md:h-screen p-6 md:p-10 flex flex-col gap-6 overflow-y-auto custom-scrollbar"
     style="background-color: rgba(14, 14, 15, 0.4); border-right: 1px solid rgba(0, 242, 255, 0.15);">

    {{-- Header: Back link + Brand --}}
    <div class="glass-card rounded-xl p-4 flex justify-between items-center">
        <a href="{{ route('solo.index') }}"
           class="group inline-flex items-center gap-2 font-mono-label text-xs uppercase tracking-widest text-soft hover:text-cyan-glow transition-colors">
            <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
            Keluar Misi
        </a>
        <div class="flex items-center gap-3">
            <h2 class="font-headline text-base font-bold hidden sm:block" style="color: #e5e2e3;">CodeBossArena</h2>
            <div class="w-8 h-8">
                <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="w-full h-full object-contain">
            </div>
        </div>
    </div>

    {{-- Active Session Conflict Warning --}}
    @if($activeSession && $activeSession->solo_raid_id !== $soloRaid->id)
        <div class="rounded-xl p-4"
             style="background-color: rgba(255, 99, 99, 0.1); border: 1px solid rgba(255, 99, 99, 0.3);">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined" style="color: #ffb4ab;">priority_high</span>
                <div class="flex-1">
                    <p class="font-mono-label text-[10px] font-medium uppercase tracking-wider mb-1" style="color: #ffb4ab;">
                        Konflik Sesi Terdeteksi
                    </p>
                    <p class="font-body text-xs text-soft leading-relaxed">
                        Kamu sedang mengerjakan
                        <span class="font-headline font-bold" style="color: #e5e2e3;">{{ $activeSession->soloRaid->nama }}</span>.
                    </p>
                    <a href="{{ route('solo.battle', ['soloRaid' => $activeSession->solo_raid_id, 'session' => $activeSession->id]) }}"
                       class="font-mono-label inline-block mt-2 text-[10px] font-medium uppercase tracking-wider text-cyan-glow hover:text-magenta-glow transition-colors">
                        Lanjutkan Sesi →
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Mission Briefing --}}
    <div class="glass-card rounded-xl p-8">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                 style="background: linear-gradient(135deg, rgba(0,242,255,0.15), rgba(206,93,255,0.15)); border: 1px solid rgba(0,242,255,0.3);">
                <span class="material-symbols-outlined text-cyan-glow">{{ $isLearning ? 'menu_book' : 'skull' }}</span>
            </div>
            <span class="font-mono-label text-[10px] font-medium uppercase tracking-widest text-faint">
                ID Misi · #SR-{{ str_pad($soloRaid->id, 3, '0', STR_PAD_LEFT) }}
            </span>
        </div>

        <h1 class="font-headline text-3xl md:text-[32px] font-extrabold text-cyan-glow leading-tight mb-4">
            {{ $soloRaid->nama }}
        </h1>

        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="rounded-lg p-3"
                 style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                <div class="font-mono-label text-[10px] font-medium uppercase tracking-wider text-soft mb-1">Klasifikasi</div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm"
                          style="color: {{ $isLearning ? '#00f2ff' : '#ffb4ab' }};">
                        {{ $isLearning ? 'school' : 'skull' }}
                    </span>
                    <span class="font-headline text-xs font-bold uppercase tracking-wider"
                          style="color: {{ $isLearning ? '#00f2ff' : '#ffb4ab' }};">
                        {{ $isLearning ? 'Mode Latihan' : 'Mode Pertempuran' }}
                    </span>
                </div>
            </div>
            <div class="rounded-lg p-3"
                 style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                <div class="font-mono-label text-[10px] font-medium uppercase tracking-wider text-soft mb-1">Periode Event</div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm" style="color: #fde68a;">calendar_today</span>
                    <span class="font-headline text-xs font-bold" style="color: #e5e2e3;">
                        {{ \Carbon\Carbon::parse($soloRaid->tanggal_mulai)->format('d M') }} – {{ \Carbon\Carbon::parse($soloRaid->tanggal_selesai)->format('d M') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="relative pl-4">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-full"
                 style="background: linear-gradient(180deg, rgba(0,242,255,0.4), rgba(206,93,255,0.4));"></div>
            <p class="font-body text-sm text-soft leading-relaxed italic">
                "{{ $soloRaid->deskripsi }}"
            </p>
        </div>
    </div>

    {{-- Tactical Progress + Modul List --}}
    <div class="glass-card rounded-xl p-8">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-cyan-glow">route</span>
                <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Progres Materi</h3>
            </div>
            <span class="font-mono-label text-xs font-medium uppercase tracking-wider px-3 py-1 rounded-full bg-cyan-soft text-cyan-glow border border-cyan-soft">
                {{ $completedNodes }} / {{ $totalNodes }}
            </span>
        </div>

        {{-- Progress Bar --}}
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <span class="font-mono-label text-xs uppercase tracking-wider font-medium text-soft">Penyelesaian</span>
                <span class="font-mono-label text-xs uppercase tracking-wider font-medium text-soft">
                    {{ number_format($progressPct, 0) }}%
                </span>
            </div>
            <div class="w-full h-3 rounded-full overflow-hidden relative" style="background-color: #353436;">
                <div class="h-full progress-bar-fill rounded-full relative transition-all duration-500"
                     style="width: {{ $progressPct }}%;">
                    @if($progressPct > 0 && $progressPct < 100)
                        <div class="absolute right-0 top-0 bottom-0 w-2 bg-white rounded-full progress-glow-tip"></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modul List --}}
        @php
            $contentNodes = $nodes->where('type', 'content')->sortBy('order');
        @endphp
        <div class="space-y-2">
            @foreach($contentNodes as $node)
                @php
                    $isDone = in_array($node->id, $completedNodeIds);
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-lg transition-colors"
                     style="background-color: {{ $isDone ? 'rgba(34, 197, 94, 0.08)' : 'rgba(32, 31, 32, 0.4)' }};
                            border: 1px solid {{ $isDone ? 'rgba(34, 197, 94, 0.25)' : 'rgba(58, 73, 75, 0.3)' }};">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
                         style="background-color: {{ $isDone ? 'rgba(34, 197, 94, 0.2)' : 'rgba(0, 242, 255, 0.1)' }};
                                border: 1px solid {{ $isDone ? 'rgba(34, 197, 94, 0.4)' : 'rgba(0, 242, 255, 0.3)' }};">
                        @if($isDone)
                            <span class="material-symbols-outlined" style="font-size: 14px; color: #86efac;">check</span>
                        @else
                            <span class="font-mono-label text-[11px] font-bold text-cyan-glow">{{ $loop->iteration }}</span>
                        @endif
                    </div>
                    <span class="font-body text-sm font-medium flex-1 truncate"
                          style="color: {{ $isDone ? '#e5e2e3' : '#b9cacb' }};">
                        {{ $node->title }}
                    </span>
                    <span class="font-mono-label text-[10px] font-medium uppercase tracking-wider"
                          style="color: {{ $isDone ? '#86efac' : '#849495' }};">
                        {{ $isDone ? 'Selesai' : 'Belum' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Riwayat Percobaan --}}
    <div class="glass-card rounded-xl p-8">
        <div class="flex justify-between items-center mb-6 pb-4" style="border-bottom: 1px solid rgba(58, 73, 75, 0.5);">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined" style="color: #ebb2ff;">history</span>
                <h3 class="font-headline text-xl font-semibold" style="color: #e5e2e3;">Riwayat Percobaan</h3>
            </div>
            <span class="font-mono-label text-xs uppercase tracking-wider text-faint">
                {{ $stats['attempts'] ?? 0 }} Entri
            </span>
        </div>

        @if($sessions->count() > 0)
            <div class="space-y-3">
                @foreach($sessions->take(3) as $session)
                    @php
                        $levelColor = match($session->level ?? '') {
                            'Hard' => '#ffb4ab',
                            'Medium' => '#fde68a',
                            'Easy' => '#86efac',
                            default => '#00f2ff',
                        };
                        $isPassed = $session->boss_kalah || $session->skor_akhir >= 100;
                    @endphp
                    <div class="flex items-center justify-between p-4 rounded-lg transition-colors"
                         style="background-color: rgba(32, 31, 32, 0.4); border: 1px solid rgba(58, 73, 75, 0.3);">
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center justify-center p-2 rounded min-w-[60px]"
                                 style="background-color: #131314; border: 1px solid rgba(58, 73, 75, 0.5);">
                                <span class="font-mono-label text-[10px] uppercase tracking-wider mb-1 text-faint">Level</span>
                                <span class="font-headline font-bold text-sm" style="color: {{ $levelColor }};">{{ $session->level ?? '—' }}</span>
                            </div>
                            <div>
                                <h4 class="font-headline text-sm font-semibold mb-1" style="color: #e5e2e3;">
                                    Percobaan #{{ $session->attempt_number }}
                                </h4>
                                <div class="flex flex-wrap gap-3">
                                    <span class="font-mono-label text-[10px] uppercase tracking-wider flex items-center gap-1 text-soft">
                                        <span class="material-symbols-outlined" style="font-size: 12px;">calendar_today</span>
                                        {{ \Carbon\Carbon::parse($session->waktu_mulai)->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            @if(!$session->waktu_selesai)
                                <div class="font-mono-label inline-flex items-center gap-1.5 text-[10px] font-medium uppercase tracking-wider px-2 py-1 rounded-full"
                                     style="background-color: rgba(250,204,21,0.15); color: #fde68a; border: 1px solid rgba(250,204,21,0.3);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                                    Aktif
                                </div>
                            @else
                                <div class="font-mono-label inline-block text-[10px] font-medium uppercase tracking-wider px-2 py-1 rounded-full mb-1"
                                     style="{{ $isPassed
                                        ? 'background-color: rgba(34,197,94,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.3);'
                                        : 'background-color: rgba(255,99,99,0.15); color: #ffb4ab; border: 1px solid rgba(255,99,99,0.3);' }}">
                                    {{ $isPassed ? '✓ Lulus' : '✗ Gagal' }}
                                </div>
                                <div class="font-mono-label text-[10px] uppercase tracking-wider text-soft">
                                    Akurasi {{ number_format($session->skor_akhir, 0) }}%
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($sessions->count() > 3)
                    <div class="text-center pt-1">
                        <span class="font-mono-label text-[10px] uppercase tracking-wider text-faint">
                            ... {{ $sessions->count() - 3 }} entri lainnya disembunyikan
                        </span>
                    </div>
                @endif
            </div>
        @else
            <div class="flex flex-col items-center justify-center w-full py-8 text-center rounded-lg"
                 style="border: 2px dashed rgba(58, 73, 75, 0.5);">
                <span class="material-symbols-outlined text-4xl mb-2 text-faint">folder_open</span>
                <p class="font-body font-medium text-soft">Belum ada riwayat percobaan.</p>
                <p class="font-body text-sm text-faint">Mulai materi pertama untuk memulai!</p>
            </div>
        @endif
    </div>
</div>
