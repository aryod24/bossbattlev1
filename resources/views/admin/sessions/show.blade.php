<x-admin-layout>
    <div class="mb-6">
        <a href="{{ route('admin.sessions.index') }}" class="flex items-center text-text-muted hover:text-text-primary transition-colors mb-4">
            <span class="material-symbols-outlined mr-2">arrow_back</span> Back to Monitor
        </a>
        <h1 class="text-3xl font-black text-text-primary">Session Details #{{ $session->id }}</h1>
    </div>

    @if($session->is_pretest || (int) ($session->jumlah_soal ?? 0) === 30)
        <div class="mb-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200 font-bold">Pre-Test session (boss HP not applicable)</span>
        </div>
    @endif

    <!-- Session Info Card -->
    <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <div class="text-sm text-text-muted mb-1">User</div>
                <div class="font-bold text-lg text-text-primary">{{ $session->user->nama ?? '-' }}</div>
                @if($session->user?->nim)
                    <div class="text-xs text-text-muted">NIM: {{ $session->user->nim }}</div>
                @endif
                <div class="text-xs text-text-muted">{{ $session->user->email ?? '' }}</div>
            </div>
            <div>
                <div class="text-sm text-text-muted mb-1">Raid / Level</div>
                <div class="font-bold text-lg text-text-primary">{{ $session->soloRaid->name ?? '-' }}</div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                    {{ $session->level }} (Attempt {{ $session->attempt_number }})
                </span>
            </div>
            <div>
                <div class="text-sm text-text-muted mb-1">Status</div>
                @if($session->waktu_selesai)
                    @if($session->boss_kalah)
                        <span class="text-green-500 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">trophy</span> Victory
                        </span>
                    @else
                        <span class="text-red-500 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">close</span> Defeat
                        </span>
                    @endif
                @else
                    <span class="text-yellow-500 font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">play_circle</span> Playing
                    </span>
                @endif
            </div>
            <div>
                <div class="text-sm text-text-muted mb-1">Score</div>
                <div class="font-bold text-lg text-text-primary">{{ number_format($session->skor_akhir, 1) }}%</div>
                <div class="text-xs text-text-muted">
                    Correct: {{ $session->jumlah_benar }} / {{ $session->jumlah_soal }}
                </div>
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-border-light dark:border-border-dark grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="text-sm text-text-muted mb-1">Boss HP</div>
                @if($session->boss_hp_awal && $session->boss_hp_awal > 0)
                    <div class="font-bold text-text-primary">{{ $session->boss_hp_akhir }} / {{ $session->boss_hp_awal }}</div>
                    <div class="w-full bg-background-light dark:bg-background-dark h-2 rounded-full mt-2 overflow-hidden">
                        <div class="h-full bg-red-500" style="width: {{ round(($session->boss_hp_akhir / $session->boss_hp_awal) * 100, 2) }}%"></div>
                    </div>
                @else
                    <div class="text-text-muted font-bold">Not applicable for Pre-Test</div>
                @endif
            </div>
            <div>
                <div class="text-sm text-text-muted mb-1">Duration</div>
                <div class="font-bold text-text-primary">
                    {{ $session->durasi_detik ? gmdate("H:i:s", $session->durasi_detik) : 'Ongoing' }}
                </div>
                <div class="text-xs text-text-muted">
                    Start: {{ $session->waktu_mulai->format('d M Y H:i:s') }}
                </div>
            </div>
            <div>
                <div class="text-sm text-text-muted mb-1">XP Gained</div>
                <div class="font-bold text-primary text-lg">
                    @if($session->soloRaid && $session->soloRaid->type === 'learning')
                        0 XP <span class="text-xs font-normal text-text-muted">(Learning)</span>
                    @else
                        +{{ $session->xp_diperoleh }} XP
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Question & Answers List -->
    <h2 class="text-xl font-bold text-text-primary mb-4">Question Log</h2>
    <div class="space-y-4">
        @forelse($session->answers as $answer)
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg border border-border-light dark:border-border-dark p-4 flex gap-4">
                <div class="flex-shrink-0">
                    @if($answer->is_correct)
                        <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center border border-green-500/20">
                            <span class="material-symbols-outlined">check</span>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center border border-red-500/20">
                            <span class="material-symbols-outlined">close</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold text-text-muted uppercase">Question #{{ $answer->urutan_soal }}</span>
                        <span class="text-xs text-text-muted">{{ $answer->waktu_jawab_detik }}s</span>
                    </div>
                    <div class="text-text-primary font-medium mb-3">{!! $answer->question->soal_text ?? 'Question Deleted' !!}</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="p-3 rounded bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark {{ $answer->is_correct ? 'border-green-500/30' : 'border-red-500/30' }}">
                            <div class="text-xs text-text-muted mb-1">User Answer</div>
                            <div class="{{ $answer->is_correct ? 'text-green-500' : 'text-red-500' }} font-bold">
                                {{ $answer->jawaban_user ?? '(No Answer)' }}
                            </div>
                        </div>
                        <div class="p-3 rounded bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark">
                            <div class="text-xs text-text-muted mb-1">Correct Answer</div>
                            <div class="text-text-primary font-bold">
                                {{ $answer->question->jawaban_benar ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-text-muted">No answers recorded for this session.</div>
        @endforelse
    </div>
</x-admin-layout>
