@props(['soloRaid', 'stats', 'sessions', 'activeSession', 'nodes', 'completedNodeIds'])

<div class="w-full md:w-1/2 bg-background p-6 md:p-10 flex flex-col justify-start overflow-y-auto custom-scrollbar border-r border-border">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center bg-surface p-4 rounded-xl border border-border shadow-lg">
            <a href="{{ route('solo.index') }}" class="group inline-flex items-center gap-2 text-text-muted hover:text-primary transition-colors text-xs font-bold uppercase tracking-widest">
                <span class="material-symbols-outlined text-[18px] transition-transform group-hover:-translate-x-1">arrow_back</span>
                Keluar Misi
            </a>

            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-text-primary hidden sm:block">CodeBossArena</h2>
                <div class="w-8 h-8">
                    <img src="{{ asset('assets/logo.png') }}" alt="CodeBossArena Logo" class="w-full h-full object-contain">
                </div>
            </div>
        </div>

        @if($activeSession && $activeSession->solo_raid_id !== $soloRaid->id)
            <div class="mt-4 bg-error/10 border border-error/30 rounded-lg p-4 animate-pulse">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-error">priority_high</span>
                    <div>
                        <p class="font-bold text-error text-xs uppercase tracking-widest mb-1">Konflik Sesi Terdeteksi</p>
                        <p class="text-xs text-text-muted leading-relaxed">
                            Kamu sedang mengerjakan <span class="text-text-primary font-bold">{{ $activeSession->soloRaid->nama }}</span>.
                        </p>
                        <a href="{{ route('solo.battle', ['soloRaid' => $activeSession->solo_raid_id, 'session' => $activeSession->id]) }}" 
                           class="inline-block mt-2 text-primary hover:text-white text-[10px] font-black uppercase tracking-widest border-b border-primary/50">
                            Lanjutkan Sesi →
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Mission Briefing -->
    <div class="mb-10">
        <div class="flex items-baseline gap-2 mb-1">
            <span class="text-[10px] font-black text-primary uppercase tracking-[0.3em]">ID MISI: #SR-{{ str_pad($soloRaid->id, 3, '0', STR_PAD_LEFT) }}</span>
        </div>
        <h1 class="text-4xl font-black text-white mb-4 tracking-tight">
            {{ $soloRaid->nama }}
        </h1>
        
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-surface/50 border border-border rounded-lg p-3">
                <div class="text-[10px] text-text-muted font-bold uppercase tracking-widest mb-1">Klasifikasi</div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm {{ $soloRaid->type === 'boss' ? 'text-error' : 'text-info' }}">
                        {{ $soloRaid->type === 'boss' ? 'skull' : 'school' }}
                    </span>
                    <span class="text-xs font-bold text-text-primary uppercase">{{ $soloRaid->type === 'learning' ? 'Mode Latihan' : 'Mode Pertempuran' }}</span>
                </div>
            </div>
            <div class="bg-surface/50 border border-border rounded-lg p-3">
                <div class="text-[10px] text-text-muted font-bold uppercase tracking-widest mb-1">Periode Event</div>
                <div class="flex items-center gap-2 text-xs font-bold text-text-primary">
                    <span class="material-symbols-outlined text-sm text-warning">calendar_today</span>
                    {{ \Carbon\Carbon::parse($soloRaid->tanggal_mulai)->format('M d') }} - {{ \Carbon\Carbon::parse($soloRaid->tanggal_selesai)->format('M d') }}
                </div>
            </div>
        </div>

        <div class="relative group">
            <div class="absolute -left-4 top-0 bottom-0 w-1 bg-primary/30 rounded-full group-hover:bg-primary transition-colors"></div>
            <p class="text-text-muted text-sm leading-relaxed pl-2 italic">
                "{{ $soloRaid->deskripsi }}"
            </p>
        </div>
    </div>

    <!-- Tactical Progress -->
    <div class="mb-10 bg-surface border border-border rounded-xl p-5 shadow-inner">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-primary animate-ping"></div>
                <span class="text-xs font-black text-white uppercase tracking-widest">Progress Materi</span>
            </div>
            <span class="text-[10px] font-bold text-text-muted px-2 py-0.5 bg-background rounded-full border border-border">
                {{ $stats['completed_nodes'] }}/{{ $stats['total_nodes'] }} Materi Selesai
            </span>
        </div>
        
        <div class="w-full bg-background rounded-full h-2 mb-6 overflow-hidden border border-border">
            <div class="bg-gradient-to-r from-primary via-info to-success h-full transition-all duration-1000 shadow-[0_0_10px_rgba(0,122,204,0.5)]" 
                 style="width: {{ $stats['total_nodes'] > 0 ? ($stats['completed_nodes'] / $stats['total_nodes']) * 100 : 0 }}%"></div>
        </div>

        <!-- Modul List (Filling space) -->
        <div class="space-y-2">
            @php
                $contentNodes = $nodes->where('type', 'content')->sortBy('order');
            @endphp
            @foreach($contentNodes as $node)
                @php
                    $isDone = in_array($node->id, $completedNodeIds);
                @endphp
                <div class="flex items-center gap-3 p-2 rounded hover:bg-white/5 transition-colors group">
                    <div class="w-5 h-5 rounded-full border border-border flex items-center justify-center shrink-0 
                         {{ $isDone ? 'bg-success/20 border-success text-success' : 'group-hover:border-primary/50' }}">
                        @if($isDone)
                            <span class="material-symbols-outlined text-[12px] font-black">check</span>
                        @else
                            <span class="text-[8px] font-black text-text-muted">{{ $loop->iteration }}</span>
                        @endif
                    </div>
                    <span class="text-xs font-bold {{ $isDone ? 'text-text-primary' : 'text-text-muted' }} truncate flex-1">
                        {{ $node->title }}
                    </span>
                    <span class="text-[9px] font-black uppercase tracking-tighter {{ $isDone ? 'text-success' : 'text-text-muted' }}">
                        {{ $isDone ? 'Selesai' : 'Terkunci' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Operational Log (Attempt History) -->
    <div class="mb-6">
        <h3 class="text-text-muted font-black mb-4 flex items-center gap-2 text-[10px] uppercase tracking-[0.2em]">
            <span class="material-symbols-outlined text-primary text-sm">history</span>
            Riwayat Percobaan ({{ $stats['attempts'] }} Entri)
        </h3>
        
        @if($sessions->count() > 0)
            <div class="space-y-3">
                @foreach($sessions->take(3) as $session)
                    <div class="group bg-surface/30 rounded-lg p-3 border border-border hover:border-primary/50 transition-all flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-[9px] text-text-muted font-bold uppercase tracking-tight">{{ \Carbon\Carbon::parse($session->waktu_mulai)->format('d M Y, H:i') }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-text-primary uppercase tracking-widest">Percobaan #{{ $session->attempt_number }}</span>
                                <span class="w-1 h-1 rounded-full bg-border"></span>
                                <span class="text-[10px] font-bold text-info uppercase">{{ $session->level }}</span>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            @if(!$session->waktu_selesai)
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                                    <span class="text-[10px] font-black text-yellow-500 uppercase tracking-widest">Aktif</span>
                                </div>
                            @else
                                <div class="text-xs font-black {{ ($session->boss_kalah || $session->skor_akhir >= 100) ? 'text-success' : 'text-error' }} uppercase">
                                    {{ ($session->boss_kalah || $session->skor_akhir >= 100) ? 'Lulus' : 'Gagal' }}
                                </div>
                                <div class="text-[10px] font-bold text-text-muted">Akurasi {{ number_format($session->skor_akhir, 0) }}%</div>
                            @endif
                        </div>
                    </div>
                @endforeach
                @if($sessions->count() > 3)
                    <div class="text-center">
                        <span class="text-[9px] font-bold text-text-muted uppercase tracking-widest">... {{ $sessions->count() - 3 }} entri lainnya disembunyikan ...</span>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-10 border-2 border-dashed border-border rounded-xl">
                <span class="material-symbols-outlined text-3xl text-border mb-2 block">folder_open</span>
                <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em]">Belum Ada Rekaman Aktivitas</p>
            </div>
        @endif
    </div>
</div>

